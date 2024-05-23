<?php

namespace app\controllers;

use Yii;
use app\models\Mds_rum_observacion;
use app\models\Mds_rum_observacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_usuario_rol;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_rum_observacionController implements the CRUD actions for Mds_rum_observacion model.
 */
class Mds_rum_observacionController extends Controller
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
     * Lists all Mds_rum_observacion models.
     * @return mixed
     */
    public function actionIndex($id)
    {    
        $searchModel = new Mds_rum_observacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);      
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
                           ->where(['idusuario' => $usuario->idusuario])
                           ->andWhere(["idrol"=> 38] )
                           ->one();  
        if ($un_rol_usuario == null)
        {
            $dataProvider->query->where(['id_cv' => $id]);
        }
        else
        {
            if ($un_rol_usuario->idrol==38) //id de la tabla mds_seg_rol para el rol Rum Empleador
            { 
                $dataProvider->query->where(['id_cv' => $id,'id_persona'=>$idusuario]);
            }
        }                      
        return 
             $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider, 
            'id_usuario'=>$id,
        ])
             ;
        /*return [
            'title' => "Probando",
            'content' => $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];*/
    }


    /**
     * Displays a single Mds_rum_observacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver Observación",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Actualizar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_rum_observacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_cv)
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_observacion();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nueva observación",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else 
                if($model->load($request->post()))
                {
                    $usuario = Yii::$app->user->identity;
                    $idusuario = $usuario != null ? $usuario->idusuario : null;

                    $model->fecha=strftime( "%Y-%m-%d", time() );  
                    $model->hora=strftime( "%H:%M:%S", time() );
                    $model->id_persona=$idusuario;//$id_usuario de seg_usuario
                    $model->id_cv=$id_cv;
                    
                    $model->save();
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nueva observación",
                    'content'=>'<span class="text-success">Observacion creada exitosamente</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            
        
                ];         
            }else{           
                return [
                    'title'=> "Crear nueva observación",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idobservacion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_rum_observacion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Observación",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){

                    $usuario = Yii::$app->user->identity;
                    $idusuario = $usuario != null ? $usuario->idusuario : null;

                    $model->fecha=strftime( "%Y-%m-%d", time() );  
                    $model->hora=strftime( "%H:%M:%S", time() );
                    $model->id_persona=$idusuario;//$id_usuario de seg_usuario
                    $model->save();
                    
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Mds_rum_observacion #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Actualizar Observación".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idobservacion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_rum_observacion model.
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
     * Finds the Mds_rum_observacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rum_observacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rum_observacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
