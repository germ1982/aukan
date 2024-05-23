<?php

namespace app\controllers;

use Yii;
use app\models\Sds_reg_movimiento;
use app\models\Sds_reg_movimientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_reg_registro;

/**
 * Sds_reg_movimientoController implements the CRUD actions for Sds_reg_movimiento model.
 */
class Sds_reg_movimientoController extends Controller
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
     * Lists all Sds_reg_movimiento models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Sds_reg_movimientoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_movimiento', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_reg_movimiento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_movimiento', $id, array());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sds_reg_movimiento #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_reg_movimiento model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_reg_movimiento();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Sds_reg_movimiento",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Sds_reg_movimiento",
                    'content'=>'<span class="text-success">Create Sds_reg_movimiento success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Sds_reg_movimiento",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idmovimiento]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Sds_reg_movimiento model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    

    public function actionSet_movimiento($datos)
    {
        $datos = json_decode($datos);
        $aux = 'No se guardo';
        
        $idmovimiento = $datos->idmovimiento;
        $idregistro = $datos->idregistro;
        $idusuario = $datos->idusuario;
        $fecha = $datos->fecha;
        $hora = $datos->hora;
        $fecha = ArmarDateParaMySql($fecha,$hora);
        $descripcion = $datos->descripcion;
        $tipo = $datos->tipo; 
        $idtecnico = $datos->idtecnico;

        //$aux = " idmovimiento: $idmovimiento\n idregistro: $idregistro\n idusuario: $idusuario\n fecha: $fecha\n descripcion: $descripcion\n tipo: $tipo\n idtecnico: $idtecnico";

        $model = $this->findModel($idmovimiento);  
        $model->idregistro = $idregistro;
        $model->idusuario = $idusuario;
        $model->fecha = $fecha;
        $model->descripcion = $descripcion;
        $model->tipo = $tipo;
        $model->idtecnico = $idtecnico;
        $model->save();
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());
        $aux = 'Guardado';
             
        echo $aux;
    }
    
    public function actionAsignar_tecnico($idregistro)
        {

            $model_registro = Sds_reg_registro::findOne($idregistro);
            $usuario = Yii::$app->user->identity;
            
            $model = new Sds_reg_movimiento();  
            $model->idregistro = $idregistro;
            $model->idusuario = $model_registro->usuario_derivacion;
            $model->fecha = GetDateTimeActualParaMySql();
            $model->descripcion = 'Atencion de la solicitud';
            $model->tipo = 0;
            $model->idtecnico = $usuario->idusuario;
            $model->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());

            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title'=> "Tecnico Asignado",
                'content'=>'<span class="text-success">'."Se asigno al tecnico $usuario->user".'</span>',
                'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])

            ]; 
        }
    
    public function actionInsert_movimiento($datos)
    {
        $datos = json_decode($datos);
        $aux = 'No se guardo';

        $idregistro = $datos->idregistro;
        $idusuario = $datos->idusuario;
        $fecha = $datos->fecha;
        $hora = $datos->hora;
        $fecha = ArmarDateParaMySql($fecha,$hora);
        $descripcion = $datos->descripcion;
        $tipo = $datos->tipo; 
        $tecnicos = $datos->tecnicos;

        foreach($tecnicos as $t)
            {
                
                $model = new Sds_reg_movimiento();  
                $model->idregistro = $idregistro;
                $model->idusuario = $idusuario;
                $model->fecha = $fecha;
                $model->descripcion = $descripcion;
                $model->tipo = $tipo;
                $model->idtecnico = $t;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());
                $aux = 'Guardado';
            }


        echo $aux;
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
                    'title'=> "Update Sds_reg_movimiento #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Sds_reg_movimiento #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Sds_reg_movimiento #".$id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_movimiento', $model->idmovimiento, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idmovimiento]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_reg_movimiento model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_reg_movimiento', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_reg_movimiento model.
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
     * Finds the Sds_reg_movimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_reg_movimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_reg_movimiento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}



function GetDateTimeActualParaMySql()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date("U"));

    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }

    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }

    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }

    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }

    $Fecha = "$mydate[year]-$mes-$dia $hora:$minuto:00";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}


function GetFechaActual()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date("U"));

    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }

    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }

    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }

    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }

    $Fecha = "$dia/$mes/$mydate[year] $hora:$minuto";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}