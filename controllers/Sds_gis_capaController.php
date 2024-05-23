<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_gis_capa;
use app\models\Sds_gis_capaSearch;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;
use app\models\Mds_sys_log;
use yii\web\UploadedFile;

/**
 * Sds_gis_capaController implements the CRUD actions for Sds_gis_capa model.
 */
class Sds_gis_capaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_GIS_CAPAS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_gis_capa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_gis_capaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_gis_capa', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_gis_capa model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_gis_capa', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Ver gis capa #" . $id,
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
     * Creates a new Sds_gis_capa model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_gis_capa();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Capa de GIS",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {            

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {
                  
                    $extension= $tmpfile->extension;
                    $nuevo_nombre=$model->random_filename(30, '/uploads/gis_capa',$extension);                    
                    $ruta = 'uploads/gis_capa/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->capa_icono = $ruta . $nuevo_nombre ;                                 
                    $tmpfile->saveAs('uploads/gis_capa/' . $nuevo_nombre );                    
                   
                } else 
                {  
                };               

                if($model->save())
                {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_gis_capa', $model->idcapa, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nueva Capa de GIS",
                        'content' => '<span class="text-success">La capa gis se creó exitosamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
    
                    ];     

                }
                else{           
                    return [
                        'title' => "Nueva Capa de GIS",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];         
                }
                
            } else {
                return [
                    'title' => "Nueva Capa de GIS",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_gis_capa', $model->idcapa, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idcapa]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_gis_capa model.
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
                    'title' => "Actualizar Capa #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {            

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {
                  
                    $extension= $tmpfile->extension;
                    $nuevo_nombre=$model->random_filename(30, '/uploads/gis_capa',$extension);
                    $ruta = 'uploads/gis_capa/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->capa_icono = $ruta . $nuevo_nombre ;                                 
                    $tmpfile->saveAs('uploads/gis_capa/' . $nuevo_nombre );                    
                   
                } else 
                {
                };     
                if($model->save())
                {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_gis_capa', $model->idcapa, $model->getAttributes());
                    return [
                        'title' => "Actualizar Capa #" . $id,
                        'forceReload' => '#crud-datatable-pjax',
                        'content' => '<span class="text-success">Se ha editado de forma exitosa</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                    ];                    

                }
                else{           
                    return [
                        'title' => "Nueva Capa de GIS",
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];         
                }

               
                
            } else {
                return [
                    'title' => "Actualizar Capa #" . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_gis_capa', $model->idcapa, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idcapa]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_gis_capa model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_gis_capa', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_gis_capa model.
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
     * Finds the Sds_gis_capa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_gis_capa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_gis_capa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_capa($idcapa)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_gis_capa/reporte_capa', $idcapa, array());
        $content = $this->renderPartial('reporte_capa', ['idcapa' => $idcapa]); // setup kartik\mpdf\Pdf component         
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Informe de Capa #'.$idcapa,
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }
}
