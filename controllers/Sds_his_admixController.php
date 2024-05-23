<?php

namespace app\controllers;

use Yii;
use app\models\Sds_his_admix;
use app\models\Sds_his_admixSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_his_admixController implements the CRUD actions for Sds_his_admix model.
 */
class Sds_his_admixController extends Controller
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
     * Lists all Sds_his_admix models.
     * @return mixed
     */
    public function actionIndex($dni)
    {    
        $searchModel = new Sds_his_admixSearch();
        $searchModel->documento_numero=$dni;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_his_admix model.
     * @param integer $documento_numero
     * @param string $nombre
     * @param string $servicio
     * @param string $importe
     * @param string $fecha
     * @param string $periodo
     * @return mixed
     */
    public function actionView($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sds_his_admix #".$documento_numero, $nombre, $servicio, $importe, $fecha, $periodo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','documento_numero, $nombre, $servicio, $importe, $fecha, $periodo'=>$documento_numero, $nombre, $servicio, $importe, $fecha, $periodo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo),
            ]);
        }
    }

    /**
     * Creates a new Sds_his_admix model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_his_admix();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Sds_his_admix",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Sds_his_admix",
                    'content'=>'<span class="text-success">Create Sds_his_admix success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Sds_his_admix",
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
                return $this->redirect(['view', 'documento_numero' => $model->documento_numero, 'nombre' => $model->nombre, 'servicio' => $model->servicio, 'importe' => $model->importe, 'fecha' => $model->fecha, 'periodo' => $model->periodo]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Sds_his_admix model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $documento_numero
     * @param string $nombre
     * @param string $servicio
     * @param string $importe
     * @param string $fecha
     * @param string $periodo
     * @return mixed
     */
    public function actionUpdate($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Sds_his_admix #".$documento_numero, $nombre, $servicio, $importe, $fecha, $periodo,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Sds_his_admix #".$documento_numero, $nombre, $servicio, $importe, $fecha, $periodo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','documento_numero, $nombre, $servicio, $importe, $fecha, $periodo'=>$documento_numero, $nombre, $servicio, $importe, $fecha, $periodo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Sds_his_admix #".$documento_numero, $nombre, $servicio, $importe, $fecha, $periodo,
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
                return $this->redirect(['view', 'documento_numero' => $model->documento_numero, 'nombre' => $model->nombre, 'servicio' => $model->servicio, 'importe' => $model->importe, 'fecha' => $model->fecha, 'periodo' => $model->periodo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_his_admix model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $documento_numero
     * @param string $nombre
     * @param string $servicio
     * @param string $importe
     * @param string $fecha
     * @param string $periodo
     * @return mixed
     */
    public function actionDelete($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo)
    {
        $request = Yii::$app->request;
        $this->findModel($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo)->delete();

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
     * Delete multiple existing Sds_his_admix model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $documento_numero
     * @param string $nombre
     * @param string $servicio
     * @param string $importe
     * @param string $fecha
     * @param string $periodo
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
     * Finds the Sds_his_admix model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $documento_numero
     * @param string $nombre
     * @param string $servicio
     * @param string $importe
     * @param string $fecha
     * @param string $periodo
     * @return Sds_his_admix the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($documento_numero, $nombre, $servicio, $importe, $fecha, $periodo)
    {
        if (($model = Sds_his_admix::findOne(['documento_numero' => $documento_numero, 'nombre' => $nombre, 'servicio' => $servicio, 'importe' => $importe, 'fecha' => $fecha, 'periodo' => $periodo])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
