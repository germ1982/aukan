<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_atp_monto;
use app\models\Mds_atp_montoSearch;
use app\models\Mds_atp_solicitud;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use Exception;
use PHPUnit\Framework\Constraint\IsNull;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * Mds_atp_montoController implements the CRUD actions for Mds_atp_monto model.
 */
class Mds_atp_montoController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'create', 'view', 'change_estado'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'change_estado'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ATP_ALTA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_atp_monto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_atp_montoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_atp_monto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->fechahora = date('d-m-Y H:i', strtotime($model->fechahora));
        $usuario = Mds_seg_usuario::findOne($model->idusuario);
        $model->idusuario = $usuario->nombre . " " . $usuario->apellido;
        $model->estado = $this->getEstado($model->estado);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ATPCen Montos N°" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Mds_atp_monto model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_atp_monto();

        $model->fechahora = date('Y-m-d H:i');

        $usuario = Yii::$app->user->identity;
        $model->idusuario = $usuario->idusuario;

        $model->path = 'undefined';

        $model->estado = 0;

        // Buscar la cantidad de solicitudes aprobadas
        $count_montos = Mds_atp_solicitud::find()->where(['estado' => Mds_atp_solicitud::APROBADO])->count();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
            return [
                'title' => "Generar archivo de Montos",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'count_montos' => $count_montos,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else if ($model->load($request->post())) {

            if (!preg_match('/\d+\,\d{2}/', $model->monto) || strlen(substr(strrchr($model->monto, ","), 1)) != 2) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Error al Generar archivo de Montos",
                    'content' => '<span class="text-danger">Por favor, ingresar un número válido. Ej: 1245,00 - 4000,50</span>',
                    'footer' => Html::a(
                        'Cerrar',
                        ['create'],
                        ['role' => 'modal-remote', 'class' => 'btn btn-default pull-left',]
                    )
                ];
            }

            $model->monto = str_replace(',', '', $model->monto);

            $solicitud_actual = null;

            try {
                // Recuperar de la tabla mds_atp_solicitud los registro con estado 4 = APROBADA
                $solicitudes = Mds_atp_solicitud::find()->where(['estado' => Mds_atp_solicitud::APROBADO])->all();

                // Crear directorio formado por idusuario y fecha actual en milisegundos
                $path = "uploads/atpcen/montos/" . $model->idusuario . '_' . $this->getTimestamp();
                mkdir($path, 0777, true);
                $fecha_movimiento = date("dmY");
                $model->path = $path . "/ArchivoMontos_$fecha_movimiento.txt";
                $fileName = Yii::getAlias("@app/web/$model->path");
                $file = fopen($fileName, "w");

                
                /** ----------- LINEA HEADER ------------ */

                // TIPO DE REGISTRO(1) = 0
                // ENT PRESENTANTE(5) = 00042
                // RESERVADO FD(5) = cadena vacía de 5 digitos
                // COD MOVIMIENTO(3) = 000
                // ORIGEN COBRANZA(2) = EO
                $header_inicio = "000042     000EO";

                // FECHA DE GENERAC(8) = Fecha en que se genero el archivo. Formato AAAAMMDD.
                $fecha_proceso_header = date("Ymd");

                // HORA DE GENERAC(6) = Hora en que se genero el archivo. Formato HHMMSS.
                $hora_proceso = date("His"); // 090000

                // NOMBRE DE ARCHIVO(8) = SC100D + Dejando dos espacios al final.
                $nombre_archivo = "SC100D  ";

                // NUM DE TRANSMICION(18) = vacio
                $num_transmision = $this->completarTexto(18, "");

                // RESERVADO FD(154) = cadena vacía
                $header_ultima = $this->completarTexto(154, "");

                $header = $header_inicio . $fecha_proceso_header . $hora_proceso . $nombre_archivo . $num_transmision . $header_ultima;

                fwrite($file, $this->formateo($header) . "\n");

                /** --------- FIN LINEA HEADER ---------- */

                $registro = 0;

                foreach ($solicitudes as $key => $solicitud) {
                    $solicitud_actual = $solicitud;

                    // TIPO DE REGISTRO(1) = 1
                    $tipo_registro = "1";

                    // ENT PRESENTANTE(5) = 00042
                    $entidad = "00042";

                    if (!isset($solicitud->sucursal)) {
                        // Eliminar archivo
                        fclose($file);
                        unlink($fileName);
                        rmdir($path);

                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error al Generar archivo de Montos",
                            'content' => "<span class='text-danger'>La solicitud con Documento N° $solicitud->documento no tiene una sucursal asociada. Por favor, completar dicho registro y volver a intentar generar el documento.</span>",
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }

                    // SUCURSAL(5) -> toma valor de la solicitud, rellenar con 0
                    $sucursal = $solicitud->sucursal;
                    $sucursal = $this->completarNumero(5, $sucursal);

                    // COD MOVIMIENTO(3) = 551
                    $codigo_mov = "551";

                    // MONEDA(3) = 032
                    $moneda = "032";

                    if (!isset($solicitud->numero_cuenta) || $solicitud->numero_cuenta == '') {
                        // Eliminar archivo
                        fclose($file);
                        unlink($fileName);
                        rmdir($path);

                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error al Generar archivo de Montos",
                            'content' => "<span class='text-danger'>La solicitud con Documento N° $solicitud->documento no tiene un numero de cuenta asociado. Por favor, completar dicho registro y volver a intentar generar el documento.</span>",
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }

                    // NUMERO DE CUENTA(9) -> toma valor de la solicitud, rellenar con 0
                    $nro_cuenta_socio = $solicitud->numero_cuenta;
                    $nro_cuenta_socio = $this->completarNumero(9, $nro_cuenta_socio);

                    // AUTORIZADO(1) -> 0
                    $codigo_autorizado = "0";

                    if (!isset($solicitud->digito_verificador)) {
                        // Eliminar archivo
                        fclose($file);
                        unlink($fileName);
                        rmdir($path);

                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error al Generar archivo de Montos",
                            'content' => "<span class='text-danger'>La solicitud con Documento N° $solicitud->documento no tiene un digito verificador asociado. Por favor, completar dicho registro y volver a intentar generar el documento.</span>",
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }

                    // DV(1) -> toma valor de la solicitud, rellenar con 0
                    $digito_verificador = $solicitud->digito_verificador;

                    // FECHA DE OPERAC(8) -> fecha actual, formato AAAAMMDD
                    $fecha_proceso = date("Ymd");

                    // IMPORTE_DE_LA_OPER(15) -> monto ingresado formateado y completando con 0
                    $monto_local = $this->completarNumero(15, $model->monto);

                    // MARCA DB/CR(1) = espacio vacio
                    // CONCEPTO(5) = 00000
                    // IDENTIF DEL CUPON(15) = 000000000000000
                    // LEYENDA(22) = cadena vacía
                    // RESERVADO FD(11) = cadena vacía
                    // FSAC(4) = 0000
                    // ENTIDAD RECAUDAD(5) = 00042
                    // SUCURSAL RECAUDAD(5) = 00376
                    // USUARIO ALTA(8) = espacio vacio
                    $campos_intermedios = " 00000000000000000000                                 00000004200376        ";

                    // CANAL ALTERNATIVO(8) = 00011584
                    $afinidad = $this->completarTexto(8, "00011584");

                    // CODIGO DE AUT(8) + MCA PAGO OFFLINE(1) + RESERVADO FD(66) = cadena vacía
                    $texto_final = $this->completarTexto(75, " ");

                    $linea = $tipo_registro . $entidad . $sucursal . $codigo_mov . $moneda . $nro_cuenta_socio . $codigo_autorizado . $digito_verificador . $fecha_proceso . $monto_local . $campos_intermedios . $afinidad . $texto_final;

                    fwrite($file, $this->formateo($linea) . "\n");

                    $registro = $registro + 1;
                }
                
                /** ----------- LINEA RESUMEN ------------- */

                // TIPO DE REGISTRO(1) = 2 
                // ENT PRESENTANTE(5) = 00042
                // RESERVADO FD(5) = cadena vacía de 5 digitos
                // COD MOVIMIENTO(3) = 551
                // MONEDA(3) = 032
                $resumen_inicio = "200042     551032";
                
                // CANT DE MOVIMIENTOS(9) -> rellenar 9 digitos con 0
                $cant_movimientos = $this->completarNumero(9, "0");
                
                // IMPORTE TOTAL(15) -> rellenar 15 digitos con 0
                $importe_total_dolar = $this->completarNumero(15, "0");

                // RESERVADO FD(169) -> vacio
                $reservado = $this->completarTexto(169, " ");

                $resumen = $resumen_inicio . $cant_movimientos . $importe_total_dolar . $reservado;

                fwrite($file, $this->formateo($resumen) . "\n");

                /** --------- FIN LINEA RESUMEN ----------- */


                /** ----------- LINEA TRAILER ------------- */

                // TIPO DE REGISTRO(1) = 9 
                // ENT PRESENTANTE(5) = 00042 
                // RESERVADO FD(5) = cadena vacía de 5 digitos
                // COD MOVIMIENTO(3) 999
                $trailer_inicio = "900042     999";

                // CANT DE MOV LOCAL(9) -> cantidad de solicitudes, rellenar 9 digitos
                $cant_mov = $this->completarNumero(9, $registro);

                //IMPORTE TOTAL LOC(15) -> cantidad de solicitudes por monto a acreditar, rellenar 15 digitos
                $monto_total = $registro * $model->monto;
                $cadenamonto = strval($monto_total);
                $cadenamonto = $this->completarNumero(15, $cadenamonto);

                // CANT MOV. DOLAR(9) -> rellenar 9 digitos con 0
                $cant_mov_dolar = $this->completarNumero(9, "0");

                // IMPORTE TOT DOLAR(15) -> rellenar 15 digitos con 0
                $monto_dolar = $this->completarNumero(15, "0");

                // RESERVADO FD(148) = cadena vacía
                $trailer_resumen = $this->completarTexto(148, " ");

                $trailer = $trailer_inicio . $cant_mov . $cadenamonto . $cant_mov_dolar . $monto_dolar . $trailer_resumen;

                fwrite($file, $this->formateo($trailer) . "\n");

                /** --------- FIN LINEA TRAILER ----------- */

                fclose($file);

                // Guardar registro
                $model->save();
            } catch (\Throwable $th) {
                if ($path && $file && $fileName) {
                    // Eliminar archivo
                    fclose($file);
                    unlink($fileName);
                    rmdir($path);
                }
                if ($solicitud_actual) {
                    throw new Exception('Error: ' . $solicitud_actual->documento . ' ' . $th->toString);
                } else {
                    throw $th;
                }
            }

            return [
                'forceReload' => '#crud-datatable-pjax',
                'title' => "Generar archivo de Montos",
                'content' => '<span class="text-success">Archivo de Monto Generado con éxito.</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return [
                'title' => "Generar archivo de Montos",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'count_montos' => $count_montos,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }
    }

    /**
     * 
     */
    public function actionChange_estado($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
            return [
                'title' => "Cambio de Estado registro " . $id,
                'content' => $this->renderAjax('change_estado', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else if ($model->load($request->post()) && $model->save()) {
            $model->fechahora = date('d-m-Y H:i', strtotime($model->fechahora));
            $usuario = Mds_seg_usuario::findOne($model->idusuario);
            $model->idusuario = $usuario->nombre . " " . $usuario->apellido;
            $model->estado = $this->getEstado($model->estado);
            return [
                'forceReload' => '#crud-datatable-pjax',
                'title' => "Registro Alta " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return [
                'title' => "Cambio de Estado registro " . $id,
                'content' => $this->renderAjax('change_estado', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    protected function getEstado($estado)
    {
        switch ($estado) {
            case Mds_atp_monto::GENERADO:
                return 'Generado';
                break;
            case Mds_atp_monto::ACEPTADO:
                return 'Aceptado';
                break;
            case Mds_atp_monto::RECHAZADO:
                return 'Rechazado';
                break;
        }
    }

    protected function completarTexto($total, $valor)
    {

        $numero = strval($valor);

        while (strlen($numero) < $total) {
            $numero = $numero . " ";
        }

        return $numero;
    }

    protected function cortaryrellenar($total, $texto)
    {

        if (strlen($texto) > $total) {
            $texto = substr($texto, 0, $total - 1);
        }
        return $this->completarTexto($total, $texto);
    }

    protected function completarNumero($total, $valor)
    {
        $numero = strval($valor);

        while (strlen($numero) < $total) {
            $numero = "0" . $numero;
        }

        return $numero;
    }

    protected function formateo($linea)
    {
        $fila = strtoupper($linea);
        $fila = str_replace("Ñ", "&", $fila);
        $fila = str_replace("Á", "A", $fila);
        $fila = str_replace("É", "E", $fila);
        $fila = str_replace("Í", "I", $fila);
        $fila = str_replace("Ó", "O", $fila);
        $fila = str_replace("Ú", "U", $fila);
        $fila = str_replace("Ü", "U", $fila);
        return $fila;
    }


    /**
     * Finds the Mds_atp_monto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_atp_monto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_atp_monto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** Obtener timestamp de la hora actual */
    protected function getTimestamp()
    {
        // Obtenemos la hora actual en segundos y microsegundos
        $microtime = microtime();

        // Separamos los segundos y los microsegundos en un array
        list($seconds, $microSeconds) = explode(' ', $microtime);

        // Obtenemos los milisegundos
        $milliseconds = round($microSeconds * 1000);

        return $milliseconds;
    }
}
