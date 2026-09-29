<?php

namespace app\controllers;

use app\models\ConstantesGlobales;
use app\models\LogPlataforma;
use app\models\RegistroTecnico;
use Yii;
use app\models\RegistroTecnicoIncidencia;
use app\models\RegistroTecnicoIncidenciaMovimiento;
use app\models\RegistroTecnicoIncidenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Registro_tecnico_incidenciaController implements the CRUD actions for RegistroTecnicoIncidencia model.
 */
class Registro_tecnico_incidenciaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all RegistroTecnicoIncidencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistroTecnicoIncidenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RegistroTecnicoIncidencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "RegistroTecnicoIncidencia #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Crea una incidencia con sus movimientos y la deja ligada a un Registro Técnico.
     *
     * - Si viene $idregistro (derivada desde el módulo de registros), hereda persona y sector del registro.
     * - Si no viene (alta desde su propio módulo), al guardar se genera un Registro Técnico para ella.
     *
     * @param int|null $idregistro
     * @return mixed
     */
    public function actionCreate($idregistro = null)
    {
        $request = Yii::$app->request;

        if (!$request->isAjax) {
            return $this->redirect(['index']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new RegistroTecnicoIncidencia();
        $registroTecnico = $idregistro !== null ? RegistroTecnico::findOne($idregistro) : null;

        // Datos heredados del registro de origen (se pueden pisar con lo que venga en el POST, salvo idregistro)
        if ($registroTecnico !== null) {
            $model->idregistro    = $registroTecnico->idregistro;
            $model->iddispositivo = $registroTecnico->iddispositivo;
            $model->idingresante  = $registroTecnico->idsolicitante;
        }

        // GET, o POST sin datos de incidencia (ej: derivado desde el create de Registro Técnico) => mostrar formulario
        if ($request->isGet || !$model->load($request->post())) {
            return $this->respuestaFormulario($model, $registroTecnico);
        }

        // idregistro nunca se toma del POST
        $model->idregistro = $registroTecnico !== null ? $registroTecnico->idregistro : null;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Movimientos serializados por JS en el input hidden
            $movimientos = json_decode((string) $request->post('movimientosArrayInput', ''), true);
            if (!is_array($movimientos) || empty($movimientos)) {
                throw new \Exception('No se recibió ningún movimiento en el input hidden.');
            }
            $movimientos = array_values($movimientos);

            // 2. Guardar la incidencia con el estado que surge de sus movimientos
            $model->idestado = $this->calcularEstadoIncidencia($movimientos);
            if (!$model->save()) {
                throw new \Exception('Error en Incidencia: ' . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }

            // 3. Guardar movimientos y sus asistentes
            foreach ($movimientos as $movData) {
                $mov = new RegistroTecnicoIncidenciaMovimiento();
                $mov->idincidencia        = $model->idincidencia;
                $mov->fecha               = $this->fechaParaMySql($movData['fecha'] ?? null);
                $mov->hora                = $movData['hora'] ?? date('H:i:s');
                $mov->idmovimiento_nombre = $movData['idmovimiento_nombre'] ?? null;
                $mov->descripcion         = $movData['descripcion'] ?? '';

                if (!$mov->save()) {
                    throw new \Exception('Error en Movimiento: ' . json_encode($mov->getErrors(), JSON_UNESCAPED_UNICODE));
                }

                $this->guardarAsistentes(
                    'registro_tecnico_incidencia_movimiento_asistencia',
                    'idmovimiento',
                    $mov->idmovimiento,
                    $movData['asistentes'] ?? []
                );
            }

            // 4. Crear/actualizar el Registro Técnico y dejar la incidencia ligada a él
            $this->sincronizarRegistro($model, $movimientos);

            $transaction->commit();

            return [
                //'forceReload' => '#crud-datatable-pjax',
                'title' => "Crear Incidencia",
                'content' => '<span class="text-success">Incidencia registrada correctamente</span>',
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
            ];
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return [
                'title' => "Crear Incidencia",
                'content' => '<span class="text-danger">' . Html::encode($e->getMessage()) . '</span>',
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
            ];
        }
    }

    /**
     * Respuesta JSON con el formulario de alta. Los botones del footer arrancan ocultos:
     * el JS del form los muestra cuando corresponde (no mientras se edita el movimiento inicial).
     */
    protected function respuestaFormulario($model, $registroTecnico)
    {
        return [
            'title' => "Crear Incidencia",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'registroTecnico' => $registroTecnico,
            ]),
            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal', 'style' => 'display: none;']) .
                Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => 'submit', 'style' => 'display: none;'])
        ];
    }

    /**
     * Estado global de la incidencia: "Entregado" si algún movimiento lo es; si no, el del último movimiento.
     */
    protected function calcularEstadoIncidencia(array $movimientos)
    {
        foreach ($movimientos as $mov) {
            if ((int) ($mov['idmovimiento_nombre'] ?? 0) === ConstantesGlobales::ESTADO_INCIDENCIA_ENTREGADO) {
                return ConstantesGlobales::ESTADO_INCIDENCIA_ENTREGADO;
            }
        }

        $ultimo = end($movimientos);
        return (int) ($ultimo['idmovimiento_nombre'] ?? ConstantesGlobales::ESTADO_INCIDENCIA_RECEPCIONADO);
    }

    /**
     * Acepta d/m/Y o Y-m-d y devuelve Y-m-d (hoy si viene vacío).
     */
    protected function fechaParaMySql($fecha)
    {
        if (empty($fecha)) {
            return date('Y-m-d');
        }
        return date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
    }

    /**
     * Inserta en una tabla puente (idXXX, idtecnico) los técnicos indicados.
     */
    protected function guardarAsistentes($tabla, $campoClave, $id, $tecnicos)
    {
        if (empty($tecnicos) || !is_array($tecnicos)) {
            return;
        }

        $filas = [];
        foreach ($tecnicos as $idTecnico) {
            $filas[] = [$id, (int) $idTecnico];
        }
        Yii::$app->db->createCommand()->batchInsert($tabla, [$campoClave, 'idtecnico'], $filas)->execute();
    }

    /**
     * Deja la incidencia ligada a un Registro Técnico:
     * - Si ya tenía (derivada desde registros) lo reutiliza.
     * - Si no, genera uno nuevo con persona/sector de la incidencia y los asistentes del movimiento inicial.
     * Si hay un movimiento "Entregado", finaliza el registro.
     *
     * @throws \Exception si falla el guardado del registro
     */
    protected function sincronizarRegistro($model, array $movimientos)
    {
        $movimientoEntrega = null;
        foreach ($movimientos as $mov) {
            if ((int) ($mov['idmovimiento_nombre'] ?? 0) === ConstantesGlobales::ESTADO_INCIDENCIA_ENTREGADO) {
                $movimientoEntrega = $mov;
                break;
            }
        }

        $asistentesIniciales = $movimientos[0]['asistentes'] ?? [];

        if ($model->idregistro !== null) {
            $registro = RegistroTecnico::findOne($model->idregistro);
        } else {
            $registro = new RegistroTecnico();
            $registro->fecha_solicitud = date('Y-m-d');
            $registro->hora_solicitud  = date('H:i:s');
            $registro->idsolicitante   = $model->idingresante;
            $registro->iddispositivo   = $model->iddispositivo;
            $registro->problema        = 'Registro generado automáticamente desde Incidencia N° ' . $model->idincidencia;
            $registro->usuario_carga   = Yii::$app->user->id ?? null;
            $registro->estado          = !empty($asistentesIniciales)
                ? RegistroTecnico::ESTADO_ASISTENCIA
                : RegistroTecnico::ESTADO_PENDIENTE;
        }

        $esNuevoRegistro = $registro->isNewRecord;

        if ($movimientoEntrega !== null) {
            $descEntrega = !empty($movimientoEntrega['descripcion']) ? ' - ' . $movimientoEntrega['descripcion'] : '';

            $registro->estado         = RegistroTecnico::ESTADO_FINALIZADO;
            $registro->fecha_solucion = $this->fechaParaMySql($movimientoEntrega['fecha'] ?? null);
            $registro->hora_solucion  = date('H:i:s', strtotime($movimientoEntrega['hora'] ?? 'now'));
            $registro->solucion       = 'Equipo entregado desde Incidencia N° ' . $model->idincidencia . $descEntrega;
        }

        if (!$registro->save()) {
            throw new \Exception('Error al sincronizar el Registro Técnico: ' . json_encode($registro->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        if ($esNuevoRegistro) {
            $this->guardarAsistentes('registro_tecnico_asistencia', 'idregistro', $registro->idregistro, $asistentesIniciales);
            LogPlataforma::registrar(ConstantesGlobales::REGISTRO_TECNICO_INFORMATICA, ConstantesGlobales::CREACION, $registro->idregistro);

            $model->idregistro = $registro->idregistro;
            // AHORA (Forza un UPDATE directo sobre la columna idregistro sin tocar la clave primaria):
            if ($model->updateAttributes(['idregistro' => $registro->idregistro]) === false) {
                throw new \Exception('Error al vincular la Incidencia con el Registro Técnico.');
            }
        }
    }


    /**
     * Actualiza una incidencia existente re-sincronizando sus movimientos, asistentes y registro técnico asociado.
     * Cierra el modal automáticamente tras guardar exitosamente.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException si no se encuentra el modelo
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;

        if (!$request->isAjax) {
            return $this->redirect(['index']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);

        // GET o render inicial ante fallo en la carga de datos POST del modelo principal
        if ($request->isGet || !$model->load($request->post())) {
            return [
                'title' => "Actualizar Incidencia #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => 'submit'])
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Decodificar los movimientos enviados desde el input hidden
            $movimientos = json_decode((string) $request->post('movimientosArrayInput', ''), true);
            if (!is_array($movimientos) || empty($movimientos)) {
                throw new \Exception('La incidencia debe contener al menos un movimiento.');
            }
            $movimientos = array_values($movimientos);

            // 2. Recalcular estado global y guardar cambios en la incidencia existente
            $model->idestado = $this->calcularEstadoIncidencia($movimientos);
            if (!$model->save()) {
                throw new \Exception('Error al actualizar la Incidencia: ' . json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }

            // 3. Sincronización diferencial de movimientos (Baja, Modificación y Alta)
            $idsMovimientosEnviados = array_filter(array_column($movimientos, 'idmovimiento'));

            // a) Baja: Eliminar movimientos que el usuario removió de la grilla en la UI
            $queryEliminar = RegistroTecnicoIncidenciaMovimiento::find()
                ->where(['idincidencia' => $model->idincidencia]);
            if (!empty($idsMovimientosEnviados)) {
                $queryEliminar->andWhere(['not in', 'idmovimiento', $idsMovimientosEnviados]);
            }
            $movimientosAEliminar = $queryEliminar->all();

            foreach ($movimientosAEliminar as $movViejo) {
                // Limpiar tabla puente de asistentes del movimiento borrado
                Yii::$app->db->createCommand()
                    ->delete('registro_tecnico_incidencia_movimiento_asistencia', ['idmovimiento' => $movViejo->idmovimiento])
                    ->execute();
                $movViejo->delete();
            }

            // b) Alta y Modificación de movimientos
            foreach ($movimientos as $movData) {
                if (!empty($movData['idmovimiento'])) {
                    $mov = RegistroTecnicoIncidenciaMovimiento::findOne($movData['idmovimiento']);
                    if (!$mov) {
                        $mov = new RegistroTecnicoIncidenciaMovimiento();
                        $mov->idincidencia = $model->idincidencia;
                    }
                } else {
                    $mov = new RegistroTecnicoIncidenciaMovimiento();
                    $mov->idincidencia = $model->idincidencia;
                }

                $mov->fecha               = $this->fechaParaMySql($movData['fecha'] ?? null);
                $mov->hora                = $movData['hora'] ?? date('H:i:s');
                $mov->idmovimiento_nombre = $movData['idmovimiento_nombre'] ?? null;
                $mov->descripcion         = $movData['descripcion'] ?? '';

                if (!$mov->save()) {
                    throw new \Exception('Error en Movimiento: ' . json_encode($mov->getErrors(), JSON_UNESCAPED_UNICODE));
                }

                // Resetear y re-insertar asistentes en la tabla puente
                Yii::$app->db->createCommand()
                    ->delete('registro_tecnico_incidencia_movimiento_asistencia', ['idmovimiento' => $mov->idmovimiento])
                    ->execute();

                $this->guardarAsistentes(
                    'registro_tecnico_incidencia_movimiento_asistencia',
                    'idmovimiento',
                    $mov->idmovimiento,
                    $movData['asistentes'] ?? []
                );
            }

            // 4. Sincronizar estado y datos del Registro Técnico vinculado
            $this->sincronizarRegistro($model, $movimientos);

            $transaction->commit();

            // RESPUESTA DE ÉXITO: Cierre automático de modal para evitar ventanas colgadas
            /* return [
                'forceClose' => true,
                //'forceReload' => '#crud-datatable-pjax',
            ]; */

                        return [
                //'forceReload' => '#crud-datatable-pjax',
                'title' => "Crear Incidencia",
                'content' => '<span class="text-success">Incidencia registrada correctamente</span>',
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
            ];
        } catch (\Throwable $e) {
            $transaction->rollBack();

            // RESPUESTA DE ERROR: Mantiene la ventana abierta para dar feedback visual
            return [
                'title' => "Actualizar Incidencia #" . $id,
                'content' => '<span class="text-danger">' . Html::encode($e->getMessage()) . '</span>',
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
            ];
        }
    }

    /**
     * Delete an existing RegistroTecnicoIncidencia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing RegistroTecnicoIncidencia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the RegistroTecnicoIncidencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistroTecnicoIncidencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistroTecnicoIncidencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Renders the partial view for the movements grid via AJAX.
     *
     * @return string
     */
    public function actionGrilla_movimientos()
    {
        // Recuperar JSON enviado por POST desde JS
        $movimientosJson = Yii::$app->request->post('movimientos', '[]');
        $movimientos = json_decode($movimientosJson, true) ?? [];

        return $this->renderAjax('_grilla_movimientos', [
            'movimientos' => $movimientos,
        ]);
    }

    /**
     * Devuelve los ítems de inventario asignados a un dispositivo/sector con datos extendidos para incidencias.
     * @param integer $id ID del dispositivo/sector
     * @return array
     */
    public function actionGet_inventario_por_dispositivo_sector($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sql = "SELECT 
                    i.idinventario,
                    CONCAT(
                        
                        COALESCE(ct.descripcion, ''), ' ',
                        COALESCE(cm.descripcion, ''), ' ',
                        COALESCE(a.modelo, ''), ' ',
                        COALESCE(a.descripcion, ''),
                        ' -- Matricula: ', COALESCE(i.matricula, 'S/N'),
                        IF(e.idempleado IS NOT NULL, CONCAT(' -- Referente: ', p.apellido, ' ', p.nombre, ' -- '), ''),
                        IF(ip.ip IS NOT NULL AND ip.ip != '', CONCAT(' [IP: ', ip.ip, ']'), ''),
                        IF(i.observacion IS NOT NULL AND i.observacion != '', CONCAT(' - ', i.observacion), '')
                    ) AS descripcion
                FROM inventario i
                INNER JOIN articulo a ON a.idarticulo = i.idarticulo
                LEFT JOIN configuracion ct ON ct.id_configuracion = a.idtipo
                LEFT JOIN configuracion cm ON cm.id_configuracion = a.idmarca
                LEFT JOIN empleado e ON e.idempleado = i.idempleado
                LEFT JOIN personas p ON p.idpersona = e.idpersona
                LEFT JOIN inventario_dispositivo_red ir ON ir.idinventario = i.idinventario
                LEFT JOIN informatica_ip ip ON ip.iddispositivo_red = ir.iddispositivo_red
                WHERE i.iddispositivo = :iddispositivo 
                AND i.activo = 1
                ORDER BY ct.descripcion, cm.descripcion, a.modelo;";

        return Yii::$app->db->createCommand($sql, [':iddispositivo' => $id])->queryAll();
    }

    
}
