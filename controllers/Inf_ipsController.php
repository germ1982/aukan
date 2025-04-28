<?php

namespace app\controllers;

use Yii;
use app\models\InfIps;
use app\models\InfIpsSearch;
use app\models\LogPlataforma;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Inf_ipsController  InfIps model.
 */
class Inf_ipsController extends Controller
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
     * Lists all InfIps models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new InfIpsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=255;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single InfIps model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ip $model->ip",
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

    public function actionCreate()
      {
            $request = Yii::$app->request;
            $model = new InfIps();

            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nueva Ip',
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
                  } else if ($model->load($request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $guardado = true;

                        if ($guardado && $model->save()) {
                              $transaction->commit();
                              LogPlataforma::registrar(2,1,$model->idip); 
                              return [
                                    'title' => "Nueva Ip",
                                    'content' => '<span class="text-success">Nueva Ip Creada Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                              ];
                        }
                  }
                  return [
                        'title' => "Nueva Ip, Faltan datos!!! Complete Los datos Faltantes!!!",
                        'content' => $this->renderAjax('create', [
                              'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                              Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                  ];
            }
      }




      public function actionUpdate($id)
      {
            $request = Yii::$app->request;
            $model = $this->findModel($id);

            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Editar Ip',
                              'content' => $this->renderAjax('update', [
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
                  } else if ($model->load($request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $guardado = true;

                        if ($guardado && $model->save()) {
                              $transaction->commit();
                              LogPlataforma::registrar(2,2,$model->idip); 
                              return [
                                    'title' => "Editar Ip",
                                    'content' => '<span class="text-success">Ip Editada Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                              ];
                        }
                  }
                  return [
                        'title' => "Editar Ip, Faltan datos!!! Complete Los datos Faltantes!!!",
                        'content' => $this->renderAjax('create', [
                              'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                              Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                  ];
            }
      }


    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(2,3,$id); 
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
     * Delete multiple existing InfIps model.
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
     * Finds the InfIps model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InfIps the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InfIps::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLiberar_ip($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $model->idempleado = null;
        $model->idoficina = null;
        $model->observacion = null;
        $model->save();
        LogPlataforma::registrar(2,2,$model->idip); 
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            //'forceReload'=>'#crud-datatable-pjax',
            'title'=> "Liberar Ip: ".$model->ip,
            'content'=>"Se liberó Ip: $model->ip",
            //'content'=>$this->renderAjax('view', ['model' => $model,]),
            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
        ];  

    }
}
