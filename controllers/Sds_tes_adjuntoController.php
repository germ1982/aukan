<?php

namespace app\controllers;

use Yii;
use app\models\Sds_tes_adjunto;
use app\models\Sds_tes_adjuntoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;
/**
 * Sds_tes_adjuntoController implements the CRUD actions for Sds_tes_adjunto model.
 */
class Sds_tes_adjuntoController extends Controller
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
     * Lists all Sds_tes_adjunto models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Sds_tes_adjuntoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_tes_adjunto', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_tes_adjunto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_tes_adjunto', $id, array());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Archivo Id: ".$id,
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
     * Creates a new Sds_tes_adjunto model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_tes_adjunto();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Subir nuevo documento",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                //cuando hago algo raro como formatear una fecha, tengo que modificar aca:
                //Crear la variable guardado en true, e iniciar una transaccion,
                //Despues agrego el if mas abajo y el save me lo llevo para alla
                // en el if le mando commmit a la transaccion
                //hagoi un return agregando title content y footer para que cierre bien el modal, el resto se comenta no sirve pa nada
                $guardado = true;
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->carga);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->carga = $fecha;
 
                //----------------------------------------------------------------------------------------------------------
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                $ruta = 'uploads/tesoreria';
                $path = Yii::$app->basePath . '/web/'.$ruta.'/'.$model->path;
                

                if (isset($tmpfile)) 
                    {
                        $extension = $tmpfile->extension;
                        $nombre = get_numero_mes($model->periodo_mes).$model->periodo_anio.'_'.get_inicio_tipo($model->tipo).'_'.get_inicio_pago($model->pago).'.'.$extension;

                        // creo el nuevo, pero como es unique si ya existe va a pinchar
                        $model->path = $ruta .'/'. $nombre;
                        $tmpfile->saveAs($model->path);
 
                    } 
                else 
                    {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if($model->borrar_adjunto && $model->path) 
                            {
                                unlink($path);
                                $model->path = null;
                            }
                    } 
                //----------------------------------------------------------------------------------------------------------
                if ($guardado && $model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_tes_adjunto', $model->idadjunto, $model->getAttributes());

                return [
                    //'forceReload' => '#crud-datatable-pjax',
                    'title' => "Nuevo Documento",
                    'content' => '<span class="text-success">Instancia creada exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                ];}  
       
            }/* else{           
                return [
                    'title'=> "Subir nuevo documento",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            } */
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_tes_adjunto', $model->idadjunto, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idadjunto]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Sds_tes_adjunto model.
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

            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post()))
                {

                //cuando hago algo raro como formatear una fecha, tengo que modificar aca:
                //Crear la variable guardado en true, e iniciar una transaccion,
                //Despues agrego el if mas abajo y el save me lo llevo para alla
                // en el if le mando commmit a la transaccion
                //hagoi un return agregando title content y footer para que cierre bien el modal, el resto se comenta no sirve pa nada
                $guardado = true;
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->carga);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->carga = $fecha;
 
                //----------------------------------------------------------------------------------------------------------
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                $ruta = 'uploads/tesoreria';
                $path = Yii::$app->basePath . '/web/'.$ruta.'/'.$model->path;
                

                if (isset($tmpfile)) 
                    {
                        $extension = $tmpfile->extension;
                        $nombre = get_numero_mes($model->periodo_mes).$model->periodo_anio.'_'.get_inicio_tipo($model->tipo).'_'.get_inicio_pago($model->pago).'.'.$extension;

                        // creo el nuevo, pero como es unique si ya existe va a pinchar
                        $model->path = $ruta .'/'. $nombre;
                        $tmpfile->saveAs($model->path);
 
                    } 
                else 
                    {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if($model->borrar_adjunto && $model->path) 
                            {
                                unlink($path);
                                $model->path = null;
                            }
                    } 
                //----------------------------------------------------------------------------------------------------------

                    if ($guardado && $model->save(false)) 
                        {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_tes_adjunto', $model->idadjunto, $model->getAttributes());
                            return 
                            [
                                //'forceReload' => '#crud-datatable-pjax',
                                'title' => "Documento Numero " . $id,
                                'content' => $this->renderAjax('view', [
                                    'model' => $model,
                                ]),
                                
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                /* 'title' => "Editar Documento",
                                'content' => '<span class="text-success">Guardado exitosamente</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) */
                            ]; 
                        }   
                }
            else
                {
                    return [
                        'title'=> "Editar Documento Numero ".$id,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal", 'id' => 'btnCerrar']).
                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit", 'id' => 'btnGuardar'])
                    ];        
                }
        }
    }

    /**
     * Delete an existing Sds_tes_adjunto model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_tes_adjunto', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_tes_adjunto model.
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
     * Finds the Sds_tes_adjunto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_tes_adjunto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_tes_adjunto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

function ArmarDateParaMySql($Fecha)
{
    if ($Fecha == null) {
        return null;
    }
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}

function get_numero_mes($mes)
        {
            switch ($mes)
            {
                case "1":
                    $mes = "01";
                    break;

                case "2":
                    $mes =  "02";
                    break;

                case "3":
                    $mes =  "03";
                    break;

                case "4":
                    $mes =  "04";
                    break;
                case "5":
                    $mes = "05";
                    break;

                case "6":
                    $mes =  "06";
                    break;

                case "7":
                    $mes =  "07";
                    break;

                case "8":
                    $mes =  "08";
                    break;
                case "9":
                    $mes = "09";
                    break;

                case "10":
                    $mes =  "10";
                    break;

                case "11":
                    $mes =  "11";
                    break;

                case "12":
                    $mes =  "12";
                    break;
            } 
            return $mes;
    }   
    function get_inicio_tipo($tipo)
    {
        switch ($tipo)
            {
                case "1":
                    $tipo = "Desempleo";
                    break;
                case "2":
                    $tipo = "Familia";
                    break;
                case "3":
                    $tipo = "SST";
                break;      
            }
        $tipo = strtoupper(substr($tipo, 0,3));
        return $tipo;
    }
    function get_inicio_pago($pago)
        {
            switch ($pago)
                {
                    case "1":
                        $pago = "Acreditación";
                        break;
                    case "2":
                        $pago = "Cheque";
                        break;    
                }
            $pago = strtoupper(substr($pago, 0,3));
            return $pago;
        }