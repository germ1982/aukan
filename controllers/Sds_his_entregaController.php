<?php

namespace app\controllers;

use Yii;
use app\models\Sds_his_entrega;
use app\models\Sds_his_entregaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_his_entregaController implements the CRUD actions for Sds_his_entrega model.
 */
class Sds_his_entregaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_his_entrega models.
     * @return mixed
     */
    public function actionIndex($dni)
    {    
        $searchModel = new Sds_his_entregaSearch();
        $searchModel->numero_documento=$dni;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_his_entrega model.
     * @param integer $numero_documento
     * @param string $fecha
     * @param string $servicio
     * @param string $destino
     * @return mixed
     */
    public function actionView($numero_documento, $fecha, $servicio, $destino)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sds_his_entrega #".$numero_documento, $fecha, $servicio, $destino,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($numero_documento, $fecha, $servicio, $destino),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','numero_documento, $fecha, $servicio, $destino'=>$numero_documento, $fecha, $servicio, $destino],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($numero_documento, $fecha, $servicio, $destino),
            ]);
        }
    }

    /**
     * Creates a new Sds_his_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_his_entrega();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Sds_his_entrega",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Sds_his_entrega",
                    'content'=>'<span class="text-success">Create Sds_his_entrega success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Sds_his_entrega",
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
                return $this->redirect(['view', 'numero_documento' => $model->numero_documento, 'fecha' => $model->fecha, 'servicio' => $model->servicio, 'destino' => $model->destino]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Sds_his_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $numero_documento
     * @param string $fecha
     * @param string $servicio
     * @param string $destino
     * @return mixed
     */
    public function actionUpdate($numero_documento, $fecha, $servicio, $destino)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($numero_documento, $fecha, $servicio, $destino);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Sds_his_entrega #".$numero_documento, $fecha, $servicio, $destino,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Sds_his_entrega #".$numero_documento, $fecha, $servicio, $destino,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','numero_documento, $fecha, $servicio, $destino'=>$numero_documento, $fecha, $servicio, $destino],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Sds_his_entrega #".$numero_documento, $fecha, $servicio, $destino,
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
                return $this->redirect(['view', 'numero_documento' => $model->numero_documento, 'fecha' => $model->fecha, 'servicio' => $model->servicio, 'destino' => $model->destino]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_his_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $numero_documento
     * @param string $fecha
     * @param string $servicio
     * @param string $destino
     * @return mixed
     */
    public function actionDelete($numero_documento, $fecha, $servicio, $destino)
    {
        $request = Yii::$app->request;
        $this->findModel($numero_documento, $fecha, $servicio, $destino)->delete();

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
     * Delete multiple existing Sds_his_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $numero_documento
     * @param string $fecha
     * @param string $servicio
     * @param string $destino
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

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
     * Finds the Sds_his_entrega model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $numero_documento
     * @param string $fecha
     * @param string $servicio
     * @param string $destino
     * @return Sds_his_entrega the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($numero_documento, $fecha, $servicio, $destino)
    {
        if (($model = Sds_his_entrega::findOne(['numero_documento' => $numero_documento, 'fecha' => $fecha, 'servicio' => $servicio, 'destino' => $destino])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
