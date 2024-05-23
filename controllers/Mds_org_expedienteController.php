<?php

namespace app\controllers;

use Yii;
use app\models\Mds_org_expediente;
use app\models\Mds_seg_usuario;
use app\models\Mds_org_expedienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Mds_org_expedienteController implements the CRUD actions for Mds_org_expediente model.
 */
class Mds_org_expedienteController extends Controller
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

    public function actionIndex()
    {    
        $searchModel = new Mds_org_expedienteSearch();

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);

        if($usuario->organismo_stock)
        {
            $id_organismo = $usuario->organismo_stock;
            $searchModel->idorganismo = $id_organismo;
        }  
 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Expediente Id $id",
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
        $model = new Mds_org_expediente();  

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Nuevo Expediente",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                        $guardado = true;

                        $aux = 'Error al guardar: <br>';
                        
                        $fecha = ArmarDateParaMySql($model->fecha_ingreso,'00:00');
                        $model->fecha_ingreso = date('Y-m-d H:i', strtotime($fecha));

                        $fecha = ArmarDateParaMySql($model->fecha_salida,'00:00');
                        $model->fecha_salida = date('Y-m-d H:i', strtotime($fecha));

                        $usuario = Yii::$app->user->identity;
                        $idusuario = $usuario != null ? $usuario->idusuario : null;
                        $usuario = Mds_seg_usuario::findOne($idusuario);
                
                        if($usuario->organismo_stock)
                            {
                                $id_organismo = $usuario->organismo_stock;
                                $model->idorganismo = $id_organismo;
                            } 
                        else
                            {
                                $aux = "Error: Falta, el organismo del usuario logueado";
                                $guardado = false;
                                $transaction->rollBack();
                            } 
                        

                        if ($guardado && $model->save(false)) 
                        {
                            $transaction->commit();
                            //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_entrega', $model->identrega, $model->getAttributes());
                            return [
                                'title'=> "Nuevo Expediente",
                                'content'=>'<span class="text-success">Expediente Creado Correctamente</span>',
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::a('Crear otro',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    
                            ];
                        }  
                        else
                        {
                            $aux_guardado = $guardado?'true':'false';
                            $aux_guardado = "Datos de guardado: <br>guardado: $aux_guardado <br>organismo: $model->idorganismo<br>fecha_ingreso: $model->fecha_ingreso<br>fecha_salida: $model->fecha_salida";                                
                            return[
                                'title'=> "<p style='color:red'>No se ha guardado, revise los datos</p>",
                                'content'=>"<div class='row'><div class='col-md-6'>$aux_guardado</div></div><br><br><div class='row'><div class='col-md-6'><p style='color:red'>$aux</p></div></div>",
                                'footer'=> Html::button('Cerrar Recepcion',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])];         
                        }
                         
            }else{           
                return [
                    'title'=> "Nuevo Expediente",
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
                return $this->redirect(['view', 'id' => $model->idexpediente]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_org_expediente model.
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
                    'title'=> "Editar Expediente Id ".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                        $transaction = Yii::$app->db->beginTransaction();
                        $guardado = true;

                        $aux = 'Error al guardar: <br>';
                        
                        $fecha = ArmarDateParaMySql($model->fecha_ingreso,'00:00');
                        $model->fecha_ingreso = date('Y-m-d H:i', strtotime($fecha));

                        $fecha = ArmarDateParaMySql($model->fecha_salida,'00:00');
                        $model->fecha_salida = date('Y-m-d H:i', strtotime($fecha));

                        $usuario = Yii::$app->user->identity;
                        $idusuario = $usuario != null ? $usuario->idusuario : null;
                        $usuario = Mds_seg_usuario::findOne($idusuario);
                
                        if($usuario->organismo_stock)
                            {
                                $id_organismo = $usuario->organismo_stock;
                                $model->idorganismo = $id_organismo;
                            } 
                        else
                            {
                                $aux = "Error: Falta, el organismo del usuario logueado";
                                $guardado = false;
                                $transaction->rollBack();
                            } 
                        

                        if ($guardado && $model->save(false)) 
                        {
                            $transaction->commit();
                            //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_entrega', $model->identrega, $model->getAttributes());
                            return [
                                'title'=> "Edicion Expediente Id ".$id,
                                'content'=>'<span class="text-success">Expediente Editado Correctamente</span>',
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    
                            ];
                        }  
                        else
                        {
                            $aux_guardado = $guardado?'true':'false';
                            $aux_guardado = "Datos de guardado: <br>guardado: $aux_guardado <br>organismo: $model->idorganismo<br>fecha_ingreso: $model->fecha_ingreso<br>fecha_salida: $model->fecha_salida";                                
                            return[
                                'title'=> "<p style='color:red'>No se ha guardado, revise los datos</p>",
                                'content'=>"<div class='row'><div class='col-md-6'>$aux_guardado</div></div><br><br><div class='row'><div class='col-md-6'><p style='color:red'>$aux</p></div></div>",
                                'footer'=> Html::button('Cerrar Recepcion',['id'=>'btnCerrar','class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])];         
                        }
                         
            }else{
                 return [
                    'title'=> "Editar Expediente Id ".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{

            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idexpediente]);
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
        if (($model = Mds_org_expediente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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