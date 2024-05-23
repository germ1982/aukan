<?php

namespace app\controllers;

use Yii;
use app\models\Sds_cel_movimiento;
use app\models\Sds_cel_movimientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;

class Sds_cel_movimientoController extends Controller
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

    public function actionIndex($lineanro)
        {    
            $searchModel = new Sds_cel_movimientoSearch();
            $searchModel->linea = $lineanro;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_cel_movimiento', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }



    public function actionView($id)
        {   
            $request = Yii::$app->request;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_cel_movimiento', $id, array());
            if($request->isAjax){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                        'title'=> "Sds_cel_movimiento #".$id,
                        'content'=>$this->renderAjax('view', [
                            'model' => $this->findModel($id),
                        ]),
                        'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
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
            $model = new Sds_cel_movimiento();  

            if($request->isAjax)
                {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if($request->isGet)
                        {
                            return [
                                'title'=> "Nuevo Movimiento",
                                'content'=>$this->renderAjax('create', [
                                    'model' => $model,
                                ]),
                                'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                            Html::button('Guardar',['id'=>'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
                    
                            ];         
                        }
                        else if($model->load($request->post()))
                        {
                            $transaction = Yii::$app->db->beginTransaction();
                            $guardado = true;
    
                            $fecha = ArmarDateParaMySql($model->fecha,'00:00');
                            $fecha = date_create($fecha);
                            $fecha = date_format($fecha, 'Y-m-d');
                            $model->fecha = $fecha;
                            if ($guardado && $model->save()) 
                            {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_cel_movimiento', $model->idmovimiento, $model->getAttributes());
                                return [
                                            'title'=> "Nuev movimiento",
                                            'content'=>'<span class="text-success">movimiento Creado Correctamente</span>',
                                            'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                                        ]; 
                            }       
                        }
                    else
                        {           
                            return [
                                'title'=> "Nuevo Movimiento",
                                'content'=>$this->renderAjax('create', [
                                    'model' => $model,
                                ]),
                                'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                            Html::button('Guardar',['id'=>'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
                    
                            ];         
                        }
                }   
            /* else
                {

                    if ($model->load($request->post()) && $model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_cel_movimiento', $model->idmovimiento, $model->getAttributes());
                        return $this->redirect(['view', 'id' => $model->idmovimiento]);
                    } else {
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                } */
        
        }


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
                        'title'=> "Update Sds_cel_movimiento #".$id,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['id'=>'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
                    ];         
                }else if($model->load($request->post()) && $model->save()){
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_cel_movimiento', $model->idmovimiento, $model->getAttributes());
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Sds_cel_movimiento #".$id,
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }else{
                    return [
                        'title'=> "Update Sds_cel_movimiento #".$id,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['id'=>'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
                    ];        
                }
            }else{
                /*
                *   Process for non-ajax request
                */
                if ($model->load($request->post()) && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_cel_movimiento', $model->idmovimiento, $model->getAttributes());
                    return $this->redirect(['view', 'id' => $model->idmovimiento]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
        }


    public function actionDelete($id)
        {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            if ($model->delete() > 0) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_cel_movimiento', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_cel_movimiento model.
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


    protected function findModel($id)
        {
            if (($model = Sds_cel_movimiento::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }




    static public function actionGet_dato_actual($linea_inicial,$dato_return)
        {
            $aux = Sds_cel_movimiento::find()->where(['linea' => $linea_inicial])->orderBy("idmovimiento DESC")->one();
            if($aux)
                {$aux = $aux->$dato_return;}
            else
                {$aux="";}
            return $aux;

        }
}

function ArmarDateParaMySql($Fecha,$Hora)
    {
        $anio = substr($Fecha, 6,4);
        $mes  = substr($Fecha, 3,2);
        $dia = substr($Fecha, 0,2);
        $H = substr($Hora, 0,2);
        $m = substr($Hora, 3,2);
        $DT = "$anio-$mes-$dia $H:$m:00";
        return $DT;
    }