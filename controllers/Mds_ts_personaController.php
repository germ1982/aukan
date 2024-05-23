<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_ts_checklist;
use Yii;
use app\models\Mds_ts_persona;
use app\models\Mds_ts_personaSearch;
use app\models\Sds_com_configuracion;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_ts_personaController implements the CRUD actions for Mds_ts_persona model.
 */
class Mds_ts_personaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MDS_TS_PERSONA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_ts_persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_ts_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_ts_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $tspersona = $this->findModel($id);
        $tspersona->campania = Sds_com_configuracion::findOne($tspersona->campania)->descripcion;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Ver registro de tarifa social",
                'content' => $this->renderAjax('view', [
                    'model' => $tspersona,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

            ];
        } else {
            return $this->render('view', [
                'model' => $tspersona,
            ]);
        }
    }

    /**
     * Creates a new Mds_ts_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($tipo)
    {
        $request = Yii::$app->request;
        $model = new Mds_ts_persona();
        $model->tipo_beneficiario = $tipo;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nuevo registro de Tarifa Social",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                //verificar si el dni ya fue cargado:

                $dni_buscado = Mds_ts_persona::find()
                    ->where(['dni' => $model->dni])
                    ->one();

                if ($dni_buscado != null) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear nuevo registro de Tarifa Social",
                        'content' => '<span class="text-success">No se pudo guardar el registro. El dni ingresado ' . $model->dni . ' ya existe</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                    ];
                } else {
                    $fecha_nac = ArmarDateParaMySql($model->fecha_nacimiento);
                    $fecha_nac = date_create($fecha_nac);
                    $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                    $model->fecha_nacimiento = $fecha_nac;


                    $fecha = date('Y-m-d h:i:s', time());
                    $model->fecha_hora = $fecha;


                    $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/dni', $extension);
                        $model->foto_dni_frente = 'uploads/tarifasocial/dni/' . $nuevo_nombre;
                        $tmpfile->saveAs('uploads/tarifasocial/dni/' . $nuevo_nombre);
                    };
                    $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen2');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/dni', $extension);
                        $model->foto_dni_dorso = 'uploads/tarifasocial/dni/' . $nuevo_nombre;
                        $tmpfile->saveAs('uploads/tarifasocial/dni/' . $nuevo_nombre);
                    };

                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/facturaluz', $extension);
                        $model->factura_luz = 'uploads/tarifasocial/facturaluz/' . $nuevo_nombre;
                        $tmpfile->saveAs('uploads/tarifasocial/facturaluz/' . $nuevo_nombre);
                    };

                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto2');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/recibo', $extension);
                        $model->recibo_sueldo = 'uploads/tarifasocial/recibo/' . $nuevo_nombre;
                        $tmpfile->saveAs('uploads/tarifasocial/recibo/' . $nuevo_nombre);
                    };

                    $tmpfile = UploadedFile::getInstance($model, 'temp_personeria_juridica');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/personeria_juridica', $extension);
                        $model->personeria_juridica = 'uploads/tarifasocial/personeria_juridica/' . $nuevo_nombre;
                        $tmpfile->saveAs('uploads/tarifasocial/personeria_juridica/' . $nuevo_nombre);
                    };

                    if ($model->save()) {
                        $opciones_check = explode("-", $model->cad_check);
                        foreach ($opciones_check as $una_opcion) {
                            if (($una_opcion != '') && ($una_opcion != null)) {
                                $model_ts_checklist = new Mds_ts_checklist();
                                $un_pers = Mds_ts_persona::find()
                                    ->where(['dni' => $model->dni])
                                    ->andWhere(["nombre" => $model->nombre])
                                    ->andWhere(["apellido" => $model->apellido])
                                    ->andWhere(["mail" => $model->mail])
                                    ->one();
                                $model_ts_checklist->idtspersona = $un_pers->idtspersona;
                                $model_ts_checklist->idconfiguracion = $una_opcion;
                                $model_ts_checklist->save();
                            }
                        }
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Crear nuevo registro de Tarifa Social",
                            'content' => '<span class="text-success">Se han guardado exitosamente el registro de trarifa social</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])


                        ];
                    } else {
                        return [
                            'title' => "Crear nuevo registro de Tarifa Social3",
                            'content' => $this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                        ];
                    }
                }
            } else {
                return [
                    'title' => "Crear nuevo registro de Tarifa Social3",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idtspersona]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_ts_persona model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Registro de Tarifa Social",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $fecha_nac = ArmarDateParaMySql($model->fecha_nacimiento);
                $fecha_nac = date_create($fecha_nac);
                $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                $model->fecha_nacimiento = $fecha_nac;


                $fecha = date('Y-m-d h:i:s', time());
                $model->fecha_hora = $fecha;


                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/dni', $extension);
                    $model->foto_dni_frente = 'uploads/tarifasocial/dni/' . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/tarifasocial/dni/' . $nuevo_nombre);
                };
                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen2');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/dni', $extension);
                    $model->foto_dni_dorso = 'uploads/tarifasocial/dni/' . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/tarifasocial/dni/' . $nuevo_nombre);
                };

                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/facturaluz', $extension);
                    $model->factura_luz = 'uploads/tarifasocial/facturaluz/' . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/tarifasocial/facturaluz/' . $nuevo_nombre);
                };

                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto2');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/recibo', $extension);
                    $model->recibo_sueldo = 'uploads/tarifasocial/recibo/' . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/tarifasocial/recibo/' . $nuevo_nombre);
                };

                $tmpfile = UploadedFile::getInstance($model, 'temp_personeria_juridica');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/tarifasocial/personeria_juridica', $extension);
                    $model->personeria_juridica = 'uploads/tarifasocial/personeria_juridica/' . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/tarifasocial/personeria_juridica/' . $nuevo_nombre);
                };

                if ($model->save()) {
                    //eliminar todos los checklist cargados:
                    $all_check_list = Mds_ts_checklist::find()->where(['idtspersona' => $model->idtspersona])->all();
                    foreach ($all_check_list as $un_checkold) {
                        $un_checkold->delete();
                    }

                    $opciones_check = explode("-", $model->cad_check);
                    foreach ($opciones_check as $una_opcion) {
                        if (($una_opcion != '') && ($una_opcion != null)) {
                            $model_ts_checklist = new Mds_ts_checklist();
                            $un_pers = Mds_ts_persona::find()
                                ->where(['dni' => $model->dni])
                                ->andWhere(["nombre" => $model->nombre])
                                ->andWhere(["apellido" => $model->apellido])
                                ->andWhere(["mail" => $model->mail])
                                ->one();
                            $model_ts_checklist->idtspersona = $un_pers->idtspersona;
                            $model_ts_checklist->idconfiguracion = $una_opcion;

                            $model_ts_checklist->save();
                        }
                    }
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Se guardo la edicion del registro de tarifa social correctamente" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            } else {
                return [
                    'title' => "Update Mds_ts_persona #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idtspersona]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_ts_persona model.
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
     * Finds the Mds_ts_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_ts_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_ts_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
function ArmarDateParaMySql($Fecha)
{
    if ($Fecha == null) {
        return null;
    }
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
