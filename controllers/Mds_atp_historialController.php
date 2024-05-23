<?php

namespace app\controllers;

use Yii;
use app\models\Mds_atp_historial;
use app\models\Mds_atp_historialSearch;
use app\models\Mds_atp_solicitud;
use app\models\Mds_seg_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\AccessRule;

/**
 * Mds_atp_historialController implements the CRUD actions for Mds_atp_historial model.
 */
class Mds_atp_historialController extends Controller
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
                'only' => ['index2', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index2', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ATP_SOLICITUD,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_atp_historial models.
     * @return mixed
     */
    public function actionIndex2($id)//$id es id_atp_solicitud
    {    
        $searchModel = new Mds_atp_historialSearch();        
        $searchModel->id_atp_solicitud = $id;        
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query=Mds_atp_historial::find()->where(['id_atp_solicitud' => $id]);        
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $this->render(            
            'index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id_search' => $id,
        ]);
       
    }

    /**
     * Displays a single Mds_atp_historial model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver Registro de Observaciones de ATPCen #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_atp_historial model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_atp_solicitud)
    {
        $request = Yii::$app->request;
        $model = new Mds_atp_historial();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Registro de Observación ATPCen",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){               

                $model->id_atp_solicitud=$id_atp_solicitud;
                $model->fecha_hora=date('Y-m-d h:i:s a', time());                 
                $model_atp_solicitud = Mds_atp_solicitud::findOne($id_atp_solicitud);
                $model->estado_nuevo=$model_atp_solicitud->estado;
                $model->estado_anterior=$model_atp_solicitud->estado;
                $model->save();
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registro exitoso",
                    'content'=>'<span class="text-success">Se guardo el nuevo Registro de Observacion ATPCen</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                           
        
                ];         
            }else{           
                return [
                    'title'=> "2",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_atp_historial]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_atp_historial model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        //$model->estado_anterior=$model->estado_nuevo;

        /*$una_solicitud2 = Mds_atp_solicitud::findOne($model->id_atp_solicitud);
        $model->estado_anterior=$una_solicitud2->estado;*/
        

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Registro de Observacion #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else 
                if($model->load($request->post()))
                {
                    //$model_historial = new Mds_atp_historial();
                    /*$una_solicitud = Mds_atp_solicitud::findOne($model->id_atp_solicitud);
                    $una_solicitud->estado=$model->estado_nuevo;
                    $una_solicitud->save();*/
                    $model->save();

                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "11 #".$model->estado_nuevo.' - '.$model->estado_anterior,
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
            } else{
                 return [
                    'title'=> "22 #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_atp_historial]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_atp_historial model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    /**
     * Finds the Mds_atp_historial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_atp_historial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_atp_historial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
