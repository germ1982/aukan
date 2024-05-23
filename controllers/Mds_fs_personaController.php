<?php

namespace app\controllers;

use Yii;
use app\models\Mds_fs_persona;
use app\models\Mds_fs_personaSearch;
use app\models\Mds_seg_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\Mds_sys_log;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_fs_personaController implements the CRUD actions for Mds_fs_persona model.
 */
class Mds_fs_personaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view','reporte_familia'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view','reporte_familia'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MDS_FS_PERSONA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_fs_persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_fs_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_fs_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Detalle Inscripción",
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

    /**
     * Creates a new Mds_fs_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_fs_persona();
        $model->idprovincia = 58;
        $model->estado = 0;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nuevo registro de Familias Solidarias",
                    'content' => $this->renderAjax('create', [
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
                if ($model->save()) {

                    // Upload archivo adjunto
                    $tmpfile = UploadedFile::getInstance($model, 'temp_informe_adjunto_path_path');
                    if (isset($tmpfile)) {

                        $extension = $tmpfile->extension;
                        $nombre = $model->random_filename(30, '/uploads/familiassolidarias', $extension);
                        $model->informe_adjunto_path = $nombre;
                        if (!file_exists('uploads/familiassolidarias/' . $model->idfspersona . '/informe/')) {
                            mkdir('uploads/familiassolidarias/' . $model->idfspersona . '/informe/', 0777, true);
                        }
                        $tmpfile->saveAs('uploads/familiassolidarias/' . $model->idfspersona . '/informe/' . $nombre);
                        $model->save();
                    }

                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_fs_persona', $model->idfspersona, $model->getAttributes());

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear nuevo registro de Familias Solidarias",
                        'content' => '<span class="text-success">Registro de Familias Solidarias Creado con Éxito</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                    ];
                } else {
                    return [
                        'title' => "Crear nuevo registro de Familias Solidarias",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idfspersona]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_fs_persona model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->idprovincia = 58;
        $estados = Mds_fs_persona::getEstados();
        $model->borrar_adjunto = false;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar registro de Familias Solidarias",
                    'content' => $this->renderAjax('update', [
                        'estados' => $estados,
                        'model' => $model
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $fecha_nac = ArmarDateParaMySql($model->fecha_nacimiento);
                $fecha_nac = date_create($fecha_nac);
                $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                $model->fecha_nacimiento = $fecha_nac;

                if ($model->save()) {

                    // Upload archivo adjunto
                    $tmpfile = UploadedFile::getInstance($model, 'temp_informe_adjunto_path');
                    if (isset($tmpfile)) {
                        // elimino el actual si existe
                        if ($model->informe_adjunto_path) {
                            $path = Yii::$app->basePath . '/web/uploads/familiassolidarias/' . $model->idfspersona . '/informe/' . $model->informe_adjunto_path;
                            unlink($path);
                        }
                        // creo el nuevo
                        $extension = $tmpfile->extension;
                        $nombre = $model->random_filename(30, '/uploads/familiassolidarias', $extension);
                        $model->informe_adjunto_path = $nombre;
                        if (!file_exists('uploads/familiassolidarias/' . $model->idfspersona . '/informe/')) {
                            mkdir('uploads/familiassolidarias/' . $model->idfspersona . '/informe/', 0777, true);
                        }
                        $tmpfile->saveAs('uploads/familiassolidarias/' . $model->idfspersona . '/informe/' . $nombre);
                        $model->save();
                    } else {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if ($model->borrar_adjunto && $model->informe_adjunto_path) {
                            $path = Yii::$app->basePath . '/web/uploads/familiassolidarias/' . $model->idfspersona . '/informe/' . $model->informe_adjunto_path;
                            unlink($path);
                            $model->informe_adjunto_path = null;
                            $model->save();
                        }
                    }

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "La edicion del registro se guardó correctamente",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])/* .
                                Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote']) */
                    ];
                } else {
                    return [
                        'title' => "Editar registro de Familias Solidarias #" . $id,
                        'content' => $this->renderAjax('update', [
                            'estados' => $estados,
                            'model' => $model
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idfspersona]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_fs_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->informe_adjunto_path) {
            $path = Yii::$app->basePath . '/web/uploads/familiassolidarias/' . $model->idfspersona;
            unlink($path . '/informe/' . $model->informe_adjunto_path);
        }
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_fs_persona', $id, $model->getAttributes());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mds_fs_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_fs_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_fs_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_familia($idfspersona)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_fs_persona/reporte_familia', $idfspersona, array());
        $content = $this->renderPartial('reporte_familia', ['idfspersona' => $idfspersona]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
            'methods' => [
                'SetTitle' => 'INFORME FAMILIA ' . $idfspersona,
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
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
