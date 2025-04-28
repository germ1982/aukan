<?php

namespace app\controllers;

use Yii;
use app\models\ConfiguracionTipo;
use app\models\ConfiguracionTipoSearch;
use app\models\LogPlataforma;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Configuracion_tipoController  ConfiguracionTipo model.
 */
class Configuracion_tipoController extends Controller
{
    
 

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
     * Lists all ConfiguracionTipo models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ConfiguracionTipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=50;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single ConfiguracionTipo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Tipo Dato id ".$id,
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
     * Creates a new ConfiguracionTipo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

     public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new ConfiguracionTipo();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Tipo De Dato',
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
                      LogPlataforma::registrar(13,1,$model->id_configuracion_tipo);  

                      return [
                          'title' => 'Nuevo Tipo De Dato',
                          'content' => '<span class="text-success">Tipo de Dato Creado Correctamente</span>',
                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                      ];
                  }
              }
              return [
                  'title' => "Nuevo Tipo De Dato, Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                      'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                      Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
  
              ];
          }
    }
    

    /**
     * Updates an existing ConfiguracionTipo model.
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
                    'title' => 'Editar Tipo De Dato Id: '.$id,
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
                      LogPlataforma::registrar(13,2,$model->id_configuracion_tipo);  
                      return [
                          'title' => 'Editar Tipo De Dato Id: '.$id,
                          'content' => '<span class="text-success">Tipo de Dato Editado Correctamente</span>',
                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                      ];
                  }
              }
              return [
                  'title' => "Editar Tipo De Dato Id $id, Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                      'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                      Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
  
              ];
          }
    }

    /**
     * Delete an existing ConfiguracionTipo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /* $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{

            return $this->redirect(['index']);
        } */


    }

     /**
     * Delete multiple existing ConfiguracionTipo model.
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
     * Finds the ConfiguracionTipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConfiguracionTipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfiguracionTipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
