<?php

namespace app\controllers;

use Yii;
use app\models\Configuracion;
use app\models\ConfiguracionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ConfiguracionController implements the CRUD actions for Configuracion model.
 */
class ConfiguracionController extends Controller
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
     * Lists all Configuracion models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ConfiguracionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=50;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Configuracion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Dato id ".$id,
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
     * Creates a new Configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Configuracion();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Dato',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
            else if ($model->load($request->post())) {
                  $transaction = Yii::$app->db->beginTransaction();
                  $guardado = true;
                  
                  if ($guardado && $model->save()) {
                      $transaction->commit();

                      return [
                          'title' => 'Nuevo Dato',
                          'content' => '<span class="text-success">Dato Creado Correctamente</span>',
                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]).
                          Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                      ];
                  }
              }
              return [
                  'title' => "Nuevo Dato, Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                      'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                      Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
  
              ];
          }
    }

    /**
     * Updates an existing Configuracion model.
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
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Editar Dato Id: '.$id,
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
            else if ($model->load($request->post())) {
                  $transaction = Yii::$app->db->beginTransaction();
                  $guardado = true;
                  
                  if ($guardado && $model->save()) {
                      $transaction->commit();

                      return [
                          'title' => 'Editar Dato Id: '.$id,
                          'content' => '<span class="text-success">Dato Editado Correctamente</span>',
                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]).
                          Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                      ];
                  }
              }
              return [
                  'title' => "Editar Dato Id $id, Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                      'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                      Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
  
              ];
          }
    }

    /**
     * Delete an existing Configuracion model.
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
     * Delete multiple existing Configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
     * Finds the Configuracion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Configuracion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Configuracion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
