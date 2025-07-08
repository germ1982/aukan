<?php

namespace app\controllers;

use app\models\LogPlataforma;
use Yii;
use app\models\RegistroRecepcion;
use app\models\RegistroRecepcionlSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Persona;
use app\models\PersonasNoHomologadas;
use yii\db\Expression;
use app\models\OrganismoDispositivo;
use DateTime;
use Mpdf\Mpdf;

/**
 * Registro_recepcionController implements the CRUD actions for RegistroRecepcion model.
 */
class Registro_recepcionController extends Controller
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
     * Lists all RegistroRecepcion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistroRecepcionlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RegistroRecepcion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Registro Recepcion " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /* public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new RegistroRecepcion();

        // Si la petición es AJAX
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Si la petición es GET, muestra el formulario
            if ($request->isGet) {
                return [
                    'title' => "Crear Nuevo Registro",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'role' => 'modal-remote-submit'])


                ];
            }

            // Si los datos son cargados y guardados correctamente
            else if ($model->load($request->post()) && $model->save()) {
                // Verificamos si la persona no está ni en Persona ni en PersonasNoHomologadas
                $existePersona = \app\models\Persona::findOne(['documento' => $model->dni]);
                $existeNoHomo = \app\models\PersonasNoHomologadas::findOne(['documento' => $model->dni]);

                // Si no existe la persona en ninguna de las tablas
                if (!$existePersona && !$existeNoHomo) {
                    $noHomo = new \app\models\PersonasNoHomologadas();
                    $noHomo->documento = $model->dni;
                    $noHomo->nombre = $model->nombre;
                    $noHomo->apellido = $model->apellido;
                    $noHomo->documento_tipo = $model->documento_tipo;
                    $noHomo->nacionalidad = $model->nacionalidad;
                    $noHomo->genero = $model->genero;
                    $noHomo->fecha_nacimiento = $model->fecha_nacimiento;

                    // Si no se puede guardar la persona no homologada, logueamos el error
                    if (!$noHomo->save()) {
                        Yii::error($noHomo->getErrors(), 'app'); // logueamos errores si ocurren
                    } else {
                        LogPlataforma::registrar(32, 1, $noHomo->idpersona_no_homologada);
                    }
                }
                
                // Registrar la acción
                LogPlataforma::registrar(31, 1, $model->id_registro_recepcion);

                // Responder en formato JSON para que el modal se cierre
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear Nuevo Registro",
                    'content' => '<span class="text-success">Registro Creado Correctamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }
        }

        // Si la petición no es AJAX
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_registro_recepcion]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    } */

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new RegistroRecepcion();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Si es GET, mostrar formulario limpio
            if ($request->isGet) {
                $model = new RegistroRecepcion(); // Refuerza que venga vacío
                return [
                    'title' => "Crear Nuevo Registro",
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'role' => 'modal-remote-submit']),
                ];
            }

            // Si se cargaron datos y se guarda correctamente
            elseif ($model->load($request->post()) && $model->save()) {
                // Verificamos si existe en personas o no homologadas
                $existePersona = \app\models\Persona::findOne(['documento' => $model->dni]);
                $existeNoHomo = \app\models\PersonasNoHomologadas::findOne(['documento' => $model->dni]);

                if (!$existePersona && !$existeNoHomo) {
                    $noHomo = new \app\models\PersonasNoHomologadas();
                    $noHomo->documento = $model->dni;
                    $noHomo->nombre = $model->nombre;
                    $noHomo->apellido = $model->apellido;
                    $noHomo->documento_tipo = $model->documento_tipo;
                    $noHomo->nacionalidad = $model->nacionalidad;
                    $noHomo->genero = $model->genero;
                    $noHomo->fecha_nacimiento = $model->fecha_nacimiento;

                    if (!$noHomo->save()) {
                        Yii::error($noHomo->getErrors(), 'app');
                    } else {
                        LogPlataforma::registrar(32, 1, $noHomo->idpersona_no_homologada);
                    }
                }

                LogPlataforma::registrar(31, 1, $model->id_registro_recepcion);

                // Si se presionó el botón "Crear Otro"
                if ($request->post('create-another')) {
                    $model = new RegistroRecepcion(); // reinicia el modelo limpio
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear Nuevo Registro",
                        'content' => $this->renderAjax('create', ['model' => $model]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'role' => 'modal-remote-submit']),
                    ];
                }

                // Caso normal, mostrar mensaje de éxito y opción "Crear Otro"
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear Nuevo Registro",
                    'content' => '<span class="text-success">Registro Creado Correctamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                ];
            }

            // Si no pasa la validación
            return [
                'title' => "Crear Nuevo Registro",
                'content' => $this->renderAjax('create', ['model' => $model]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'role' => 'modal-remote-submit']),
            ];
        }

        // Si no es AJAX
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_registro_recepcion]);
        }

        return $this->render('create', ['model' => $model]);
    }



    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        // Si la petición es AJAX
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Si la petición es GET, muestra el formulario
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Registro " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }

            // Si los datos son cargados y guardados correctamente
            else if ($model->load($request->post()) && $model->save()) {
                // Verificamos si la persona no está ni en Persona ni en PersonasNoHomologadas
                $existePersona = \app\models\Persona::findOne(['documento' => $model->dni]);
                $existeNoHomo = \app\models\PersonasNoHomologadas::findOne(['documento' => $model->dni]);

                // Si no existe la persona en ninguna de las tablas
                if (!$existePersona && !$existeNoHomo) {
                    $noHomo = new \app\models\PersonasNoHomologadas();
                    $noHomo->documento = $model->dni;
                    $noHomo->nombre = $model->nombre;
                    $noHomo->apellido = $model->apellido;
                    $noHomo->documento_tipo = $model->documento_tipo;
                    $noHomo->nacionalidad = $model->nacionalidad;
                    $noHomo->genero = $model->genero;
                    $noHomo->fecha_nacimiento = $model->fecha_nacimiento;

                    // Si no se puede guardar la persona no homologada, logueamos el error
                    if (!$noHomo->save()) {
                        Yii::error($noHomo->getErrors(), 'app');
                    } else {
                        LogPlataforma::registrar(32, 1, $noHomo->idpersona_no_homologada);
                    }
                }

                // Registrar la acción
                LogPlataforma::registrar(31, 2, $model->id_registro_recepcion);

                // Responder en formato JSON para que el modal se cierre
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Actualizar Registro " . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }
        }

        // Si la petición no es AJAX
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_registro_recepcion]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Delete an existing RegistroRecepcion model.
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
     * Delete multiple existing RegistroRecepcion model.
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

    public function actionBuscarPersonaPorDni()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $dni = Yii::$app->request->get('dni');

        $persona = \app\models\Persona::findOne(['documento' => $dni]);

        if (!$persona) {
            $persona = \app\models\PersonasNoHomologadas::findOne(['documento' => $dni]);
        }

        if (!$persona) {
            $persona = new \app\models\PersonasNoHomologadas();
            $persona->documento = $dni;
            $persona->nombre = '';
            $persona->apellido = '';
            $persona->documento_tipo = '';
            $persona->nacionalidad = '';
            $persona->genero = '';
            $persona->fecha_nacimiento = '';
            $persona->save(false);
            return [
                'existe' => false,
                'mensaje' => 'Persona no encontrada. Se cargó como no homologada.'
            ];
        }

        return [
            'existe' => true,
            'nombre' => $persona->nombre,
            'apellido' => $persona->apellido,
            'documento_tipo' => $persona->documento_tipo,
            'nacionalidad' => $persona->nacionalidad,
            'genero' => $persona->genero,
            'fecha_nacimiento' => $persona->fecha_nacimiento,


        ];
    }


    public function actionEstadisticas($fecha_inicio = null, $fecha_final = null)
    {

        try {
            if ($fecha_inicio !== null) {
                $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio)->format('Y-m-d');
            } else {
                $fecha_inicio = date('Y-m-d');
            }

            if ($fecha_final !== null) {
                $fecha_final = DateTime::createFromFormat('d/m/Y', $fecha_final)->format('Y-m-d');
            } else {
                $fecha_final = date('Y-m-d');
            }
        } catch (\Exception $e) {
            Yii::error('Error en fechas: ' . $e->getMessage(), 'app');
            $fecha_inicio = $fecha_final = date('Y-m-d'); // fallback
        }


        $mysql = "  SELECT concat(o.abreviatura,' - ',  od.descripcion) as descripcion , count(*) as visitas
                    from registro_recepcion r
                    join organismo_dispositivo od on od.iddispositivo=r.id_dispositivo_derivacion
                    join organismo o on o.idorganismo=od.idorganismo
                    WHERE r.fecha BETWEEN '$fecha_inicio' AND '$fecha_final'
                    group by r.id_dispositivo_derivacion";

        //return $mysql;

        $registros = Yii::$app->db->createCommand($mysql)->queryAll();



        return $this->renderAjax('estadisticas', [
            'registros' => $registros,
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
        ]);

        /* return $this->render('estadisticas', [
            'registros' => $registros,
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
        ]); */
    }


    private function crearGraficoTorta($labels, $values, $rutaImagen)
    {
        $width = 500;
        $height = 450; // más alto para dejar espacio a la leyenda arriba
        $image = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);

        $colors = [
            imagecolorallocate($image, 255, 99, 132),
            imagecolorallocate($image, 54, 162, 235),
            imagecolorallocate($image, 255, 206, 86),
            imagecolorallocate($image, 75, 192, 192),
            imagecolorallocate($image, 153, 102, 255),
            imagecolorallocate($image, 255, 159, 64),
        ];

        // Dibuja la leyenda arriba
        $startX = 20;
        $startY = 10;
        $boxSize = 15;
        $spacing = 10;
        $textOffsetY = 12;
        $grisOscuro = imagecolorallocate($image, 80, 80, 80);

        foreach ($labels as $i => $label) {
            $color = $colors[$i % count($colors)];

            // Dibujar caja de color
            imagefilledrectangle($image, $startX, $startY, $startX + $boxSize, $startY + $boxSize, $color);



            // Cambiamos imagestring(3, ...) por imagestring(2, ...) para letra más pequeña
            //imagestring($image, 2, $label_x - 40, $label_y - 7, $etiqueta, );
            imagestring($image, 2, $startX + $boxSize + 5, $startY, $label, $grisOscuro);

            $startY += $boxSize + $spacing;
        }

        // Ahora dibujar la torta debajo de la leyenda
        $total = array_sum($values);
        $start_angle = 0;
        $cx = $width / 2;
        $cy = $height - 200; // bajar el centro para dejar espacio a la leyenda arriba
        $radius = 150;

        foreach ($values as $i => $val) {
            $percentage = $val / $total;
            $angle = $percentage * 360;
            $end_angle = $start_angle + $angle;
            $color = $colors[$i % count($colors)];

            imagefilledarc($image, $cx, $cy, $radius * 2, $radius * 2, $start_angle, $end_angle, $color, IMG_ARC_PIE);

            $start_angle = $end_angle;
        }

        imagepng($image, $rutaImagen);
        imagedestroy($image);
    }






    public function actionExportar($fecha_inicio, $fecha_final)
    {
        $this->layout = false;

        $mysql = "SELECT concat(o.abreviatura,' - ', od.descripcion) as descripcion, count(*) as visitas
              FROM registro_recepcion r
              JOIN organismo_dispositivo od ON od.iddispositivo = r.id_dispositivo_derivacion
              JOIN organismo o ON o.idorganismo = od.idorganismo
              WHERE r.fecha BETWEEN :fecha_inicio AND :fecha_final
              GROUP BY r.id_dispositivo_derivacion";

        $registros = Yii::$app->db->createCommand($mysql)
            ->bindValue(':fecha_inicio', $fecha_inicio)
            ->bindValue(':fecha_final', $fecha_final)
            ->queryAll();

        $labels = [];
        $data = [];
        foreach ($registros as $registro) {
            $labels[] = $registro['descripcion'];
            $data[] = $registro['visitas'];
        }

        $rutaImagen = Yii::getAlias('@runtime') . '/grafico_torta.png';
        $this->crearGraficoTorta($labels, $data, $rutaImagen);

        $html = $this->renderPartial('pdf_estadisticas', [
            'registros' => $registros,
            'graficoImagen' => $rutaImagen,
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
        ]);

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'orientation' => 'P']);
        $mpdf->WriteHTML($html);
        $mpdf->Output("estadisticas_{$fecha_inicio}_{$fecha_final}.pdf", \Mpdf\Output\Destination::INLINE);
    }













    /**
     * Finds the RegistroRecepcion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistroRecepcion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistroRecepcion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
