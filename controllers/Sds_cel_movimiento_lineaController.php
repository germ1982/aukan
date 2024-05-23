<?php

namespace app\controllers;

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_bdc_movimiento_equipo;
use app\models\Sds_cel_linea;
use Yii;
use app\models\Sds_cel_movimiento_linea;
use app\models\Sds_cel_movimiento_lineaSearch;
use app\models\Sds_cel_plan;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_cel_movimiento_lineaController implements the CRUD actions for Sds_cel_movimiento_linea model.
 */
class Sds_cel_movimiento_lineaController extends Controller
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
     * Lists all Sds_cel_movimiento_linea models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Sds_cel_movimiento_lineaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_cel_movimiento_linea model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Movimiento de linea",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_cel_movimiento_linea model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $mensaje='';
        $request = Yii::$app->request;
        $model = new Sds_cel_movimiento_linea();
        $model->usuario_carga=Yii::$app->user->identity->apellido.' '.Yii::$app->user->identity->nombre;
        $model->idusuario=Yii::$app->user->identity->idusuario;
        $model->fecha_hora=date('Y-m-d H:i:s');
        $contactos=Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.nombre, ', ', p.apellido) nombre FROM mds_org_contacto c 
            JOIN sds_com_persona p ON p.idpersona=c.idpersona"
            )->all();

        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            try{
                if($model->load($request->post())){
                    if($request->post('responsable_nuevo_id')!=''){
                        $model->responsable_nuevo=intval($request->post('responsable_nuevo_id'));
                    }
                    $transaction = Yii::$app->db->beginTransaction();
                    $commit=false;
                    if($model->save()){
                        switch ($model->tipo){
                            case Sds_cel_movimiento_linea::MOV_BAJA:
                                if($this->movimiento_baja($model)){
                                    $commit=true;
                                    $transaction->commit();
                                }
                                break;
                            case Sds_cel_movimiento_linea::MOV_CAMBIO_CHIP:
                                $commit=true;
                                $transaction->commit();
                                break;
                            case Sds_cel_movimiento_linea::MOV_CAMBIO_EQUIPO:
                                if($this->movimiento_cambio_equipo($model)){
                                    $commit=true;
                                    $transaction->commit();
                                }
                                break;
                            case Sds_cel_movimiento_linea::MOV_CAMBIO_PLAN:
                                if($model->plan_nuevo!=null){
                                    $linea=Sds_cel_linea::findOne($model->idlinea);
                                    $linea->idplan=$model->plan_nuevo;
                                    if($linea->save()){
                                        $commit=true;
                                        $transaction->commit();
                                    }
                                }else{
                                    $model->addError('plan_nuevo', 'Error al cargar el plan');
                                }
                                break;
                            case Sds_cel_movimiento_linea::MOV_CAMBIO_RESP:
                                if($this->movimiento_cambio_responsable($model)){
                                    $commit=true;
                                    $transaction->commit();
                                }
                                break;
                            case Sds_cel_movimiento_linea::MOV_SUSP_DESCONOCIDO:
                                $linea=Sds_cel_linea::findOne($model->idlinea);
                                $linea->imei=null;
                                $linea->equipo_tipo=Sds_cel_linea::TIPO_SIN_EQUIPO;
                                $linea->equipo_detalle=null;
                                $linea->fecha_entrega=null;
                                $linea->idcontacto=null;
                                $linea->idorganismo=null;
                                $linea->idusuario=null;
                                $linea->observaciones=null;
                                $linea->activo=Sds_cel_linea::ACTIVO_BAJA;
                                $linea->equipo_marca=null;
                                $linea->equipo_modelo=null;
                                $linea->organismo_padre=Sds_cel_linea::ID_MINISTERIO;
                                $linea->idequipo=null;
                                if($linea->save() && $model->save()){
                                    $commit=true;
                                    $transaction->commit();
                                }
                                break;
                            case Sds_cel_movimiento_linea::MOV_SUSP_ROBO:
                                if($this->movimiento_suspension_robo($model)){
                                    $commit=true;
                                    $transaction->commit();
                                }
                                break;
                            case Sds_cel_movimiento_linea::MOV_DESASIGNAR_EQUIPO:
                                $linea=Sds_cel_linea::findOne($model->idlinea);
                                $linea->idequipo=null;
                                $linea->idcontacto=null;
                                $linea->idorganismo=null;
                                $linea->idusuario=null;
                                if($linea->save()){
                                    $commit=true;
                                    $transaction->commit();
                                }else{
                                    throw new Exception('No fue posible actualizar datos de la linea. Por favor intente nuevamente.');
                                }
                                break;
                        }
                    }else{
                        throw new Exception('No fue posible registrar el movimiento de la linea. Por favor intente nuevamente.');
                    }
                    if($commit){
                        $mensaje='<h3 style="margin-top:0;">¡Excelente!</h3><b>El movimiento se guardó de manera correcta</b>';
                        $model= new Sds_cel_movimiento_linea();
                    }
                }
            }catch (Exception $e) {
                $transaction->rollBack();
                return [
                    'title'=> "Registrar Movimiento de línea",
                    'content'=> $this->renderAjax('create', [
                        'model' => $model,
                        'contactos' => $contactos,
                        'mensaje' => ['error'=>$e->getMessage()]
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];
            }

            return [
                'title'=> "Registrar Movimiento de línea",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                    'contactos' => $contactos,
                    'mensaje' => $mensaje
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
    
            ];
            /*
            if($request->isGet){         
            }else else{           
                return [
                    'title'=> "Create new Sds_cel_movimiento_linea",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
            */
        }else{
            /* Process for non-ajax request */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idmovimientolinea]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Sds_cel_movimiento_linea model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $mensaje='';
        $contactos=Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.nombre, ', ', p.apellido) nombre FROM mds_org_contacto c 
            JOIN sds_com_persona p ON p.idpersona=c.idpersona"
        )->all();

        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar movimiento".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'mensaje' => $mensaje,
                        'contactos' => $contactos
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Sds_cel_movimiento_linea #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Sds_cel_movimiento_linea #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'mensaje' => $mensaje,
                        'contactos' => $contactos
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /* Process for non-ajax request */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idmovimientolinea]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'mensaje' => $mensaje
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_cel_movimiento_linea model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model=$this->findModel($id);

        if($request->isAjax){
            /* Process for ajax request */
            if($model->delete()){
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /* Process for non-ajax request */
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing Sds_cel_movimiento_linea model.
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
     * Finds the Sds_cel_movimiento_linea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_cel_movimiento_linea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_cel_movimiento_linea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_equipos($tipo)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($tipo==Sds_cel_movimiento_linea::MOV_BAJA){
            $lineas=Sds_cel_linea::findBySql(
                "SELECT l.* FROM sds_cel_linea l
                WHERE idlinea NOT IN(
	                SELECT ml.idlinea
	                FROM sds_cel_movimiento_linea ml 
	                WHERE ml.idmovimientolinea IN(
	                	SELECT max(um.idmovimientolinea) ultimo_movimiento FROM (
	                		SELECT uml.idmovimientolinea, uml.idlinea, uml.fecha_hora
	                		FROM sds_cel_movimiento_linea uml
	                	) um
	                	GROUP BY um.idlinea
	                ) 
                    AND ml.tipo=".Sds_cel_movimiento_linea::MOV_BAJA."
	                )"
            )
            ->all();
        }else if($tipo==Sds_cel_movimiento_linea::MOV_SUSP_DESCONOCIDO){
            $lineas=Sds_cel_linea::find()->where(['idequipo'=>null])->all();
        }else if($tipo==Sds_cel_movimiento_linea::MOV_DESASIGNAR_EQUIPO){
            $lineas=Sds_cel_linea::find()->where('idequipo is not null')->all();
        }
        else{
            $lineas=Sds_cel_linea::findBySql(
                "SELECT l.* FROM sds_cel_linea l
                WHERE idlinea NOT IN(
	                SELECT ml.idlinea
	                FROM sds_cel_movimiento_linea ml 
	                WHERE ml.idmovimientolinea IN(
	                	SELECT max(um.idmovimientolinea) ultimo_movimiento FROM (
	                		SELECT uml.idmovimientolinea, uml.idlinea, uml.fecha_hora
	                		FROM sds_cel_movimiento_linea uml
	                	) um
	                	GROUP BY um.idlinea
	                ) 
                    AND ml.tipo=".Sds_cel_movimiento_linea::MOV_BAJA." 
                    OR ml.tipo=".Sds_cel_movimiento_linea::MOV_SUSP_ROBO." 
                    OR ml.tipo=".Sds_cel_movimiento_linea::MOV_SUSP_DESCONOCIDO."
	                )"
            )->all();
        }        
        return $lineas;
    }
    public function actionGet_data_linea($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $linea=Sds_cel_linea::findOne($id);
        $equipo=Sds_bdc_equipo::findOne($linea->idequipo);
        $contacto=Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.apellido, ' ', p.nombre) nombre 
            FROM mds_org_contacto c 
            JOIN sds_com_persona p ON c.idpersona=p.idpersona 
            WHERE c.idcontacto=".(isset($equipo->responsable) ? $equipo->responsable : $linea->idcontacto)
        )->one();
        $organismo=Mds_org_organismo::findOne(isset($equipo->idorganismo)?$equipo->idorganismo:$linea->idorganismo);
        if($organismo!=null){
            $organismo_return=['id'=>$organismo->idorganismo, 'nombre'=>$organismo->descripcion];
        }
        if($equipo!=null){
            $equipo->modelo='#'.str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT).' | '.$equipo->getMarca_modelo(); 
            $equipo_return=['id'=>$equipo->idequipo, 'nombre'=>$equipo->modelo];
        }
        $organismo_padre=Mds_org_organismo::findOne($linea->organismo_padre);
        if($organismo_padre!=null){
            $organismo_padre_return=['id'=>$organismo_padre->idorganismo, 'nombre'=>$organismo_padre->descripcion];
        }

        $plan=Sds_cel_plan::findOne($linea->idplan);
        if($plan!=null){
            $plan_return=['id'=>$plan->idplan, 'descripcion'=>$plan->descripcion];
        }

        if(isset($contacto)){
            $responsable=['id'=>$contacto->idcontacto, 'nombre'=>$contacto->nombre];
        }

        return [
            'responsable'=> (isset($responsable)?$responsable:null),
            'equipo'=> (isset($equipo_return)?$equipo_return:null),
            'plan' => (isset($plan_return)?$plan_return:null),
            'organismo' => (isset($organismo_return)?$organismo_return:null),
            'organismo_padre' => (isset($organismo_padre_return)?$organismo_padre_return:null)
        ];
    }

    public function actionGet_data_equipo($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $equipo=Sds_bdc_equipo::findOne($id);
        $organismo=Mds_org_organismo::findOne($equipo->idorganismo);
        $contacto=Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.apellido, ' ', p.nombre) nombre 
            FROM mds_org_contacto c 
            JOIN sds_com_persona p ON c.idpersona=p.idpersona 
            WHERE c.idcontacto=".$equipo->responsable
        )->one();
        return [
            'organismo'=> ['id'=>$organismo->idorganismo, 'descripcion'=>$organismo->descripcion],
            'responsable' => ['id'=> $contacto->idcontacto, 'nombre'=>$contacto->nombre]
        ];
    }

    protected function movimiento_baja($model){
        $linea=Sds_cel_linea::findOne($model->idlinea);
        $linea->idequipo=null;
        $linea->activo=Sds_cel_linea::ACTIVO_BAJA;
        if($linea->save()){
            if($model->equipo_anterior!=null){
                $equipo=Sds_bdc_equipo::findOne($model->equipo_anterior);
                $equipo->responsable= Sds_bdc_equipo::DIRECTOR_INFORMATICA;
                $organismo=Mds_org_organismo::findBySql(
                    "SELECT o.idorganismo FROM mds_org_contacto c
                    JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                    JOIN mds_org_organismo o ON d.idorganismo=o.idorganismo
                    WHERE c.idcontacto=".Sds_bdc_equipo::DIRECTOR_INFORMATICA
                )->one();
                $equipo->idorganismo=$organismo->idorganismo;
                if($equipo->save(false)){
                    $bdc_movimiento=new Sds_bdc_movimiento();
                    $bdc_movimiento->fecha_hora=$model->fecha_hora;
                    $bdc_movimiento->idusuario=$model->idusuario;
                    $bdc_movimiento->solicitante=$model->solicitante;
                    $bdc_movimiento->tipo=Sds_bdc_movimiento::MOV_REPARACION;
                    $bdc_movimiento->responsable_anterior=$model->responsable_anterior;
                    $bdc_movimiento->observaciones='Movimiento generado por el sistema al dar de baja la linea '.$linea->numero;
                    $bdc_movimiento->organismo_anterior=$model->organismo_anterior;
                    if($bdc_movimiento->save()){
                        $bdc_movimiento_equipo=new Sds_bdc_movimiento_equipo();
                        $bdc_movimiento_equipo->idequipo=$model->equipo_anterior;
                        $bdc_movimiento_equipo->idmovimiento=$bdc_movimiento->idmovimiento;
                        if($bdc_movimiento_equipo->save()){
                            return true;
                        }else{
                            throw new Exception('No fue posible asociar el movimiento a el equipo anterior. 
                            Por favor intente nuevamente.');
                        }
                    }else{
                        throw new Exception('No fue posible registrar el movimiento asociado al equipo. 
                        Por favor intente nuevamente.');
                    }
                }else{
                    throw new Exception('No fue posible actualizar los datos del equipo. 
                    Por favor intente nuevamente.');
                }
            }else{
                return true;
            }
        }else{
            throw new Exception('No fue posible actualizar los datos de la linea. 
            Por favor intente nuevamente.');
        }
        return false;
    }

    protected function movimiento_cambio_equipo($model){
        $linea=Sds_cel_linea::findOne($model->idlinea);
        $equipo=Sds_bdc_equipo::findOne($model->equipo_nuevo);
        if($equipo==null){
            $model->addError('equipo_nuevo','Error al recuperar los datos del equipo');
            return false;
        }
        //Se sobreescriben los datos del usuario que entrega el equipo, para mostrarlo de manera correcta en el Reporte de Entregas
        $linea->idusuario=$model->idusuario;
        $linea->idequipo=$model->equipo_nuevo;
        $linea->idcontacto=$equipo->responsable;
        $linea->idorganismo=$equipo->idorganismo;
        $linea->organismo_padre=$model->organismo_cuenta_nuevo;
        if($linea->save()){
            if($model->equipo_anterior!=null){
                $bdc_movimiento=new Sds_bdc_movimiento();
                $bdc_movimiento->fecha_hora=$model->fecha_hora;
                $bdc_movimiento->idusuario=$model->idusuario;
                $bdc_movimiento->solicitante=$model->solicitante;
                $bdc_movimiento->tipo=Sds_bdc_movimiento::MOV_REPARACION;
                $bdc_movimiento->responsable_anterior=$model->responsable_anterior;
                $bdc_movimiento->observaciones='Movimiento generado por el sistema al cambiar de equipo la linea '.$linea->numero;
                $bdc_movimiento->organismo_anterior=$model->organismo_anterior;
                if($bdc_movimiento->save()){
                    $bdc_movimiento_equipo=new Sds_bdc_movimiento_equipo();
                    $bdc_movimiento_equipo->idequipo=$model->equipo_anterior;
                    $bdc_movimiento_equipo->idmovimiento=$bdc_movimiento->idmovimiento;
                    if($bdc_movimiento_equipo->save()){
                        return true;
                    }else{
                        throw new Exception('No fue posible asociar el movimiento a el equipo anterior. 
                        Por favor intente nuevamente.');
                    }
                }else{
                    throw new Exception('No fue posible registrar el movimiento asociado al equipo. 
                    Por favor intente nuevamente.');
                }
            }else{
                return true;
            }
        }else{
            throw new Exception('No fue posible actualizar los datos de la linea. 
            Por favor intente nuevamente.');
        }
        return false;
    }

    protected function movimiento_cambio_responsable($model){
        if($model->responsable_nuevo==null){
            $model->addError('responsable_nuevo', 'No se ha seleccionado Responsable');
            return false;
        }
        $linea=Sds_cel_linea::findOne($model->idlinea);
        $equipo=Sds_bdc_equipo::findOne($model->equipo_anterior);
        //Se sobreescriben los datos del la fecha y el usuario que entrega de la linea:
        $linea->idusuario=$model->idusuario;
        $linea->fecha_entrega=date('Y-m-d');
        $linea->organismo_padre=intval($model->organismo_cuenta_nuevo);
        $linea->fecha_entrega=date('Y-m-d');
        $dispositivo_responsable_nuevo=Mds_org_dispositivo::find()
            ->innerJoin('mds_org_contacto c', 'mds_org_dispositivo.iddispositivo=c.iddispositivo')
            ->where(['c.idcontacto'=>$model->responsable_nuevo])
            ->one();
        $linea->idcontacto=$model->responsable_nuevo;
        $linea->idorganismo=$dispositivo_responsable_nuevo->idorganismo;
        if($linea->save(false)){
            $movimiento=new Sds_bdc_movimiento();
            $movimiento->fecha_hora=$model->fecha_hora;
            $movimiento->idusuario=Yii::$app->user->identity->idusuario;
            $movimiento->solicitante=$model->solicitante;
            $movimiento->tipo=Sds_bdc_movimiento::MOV_CAM_RESPONSABLE;
            $movimiento->responsable_anterior=$model->responsable_anterior;
            $movimiento->responsable_nuevo=$model->responsable_nuevo;
            $movimiento->observaciones='Movimiento generado automaticamente al realizar cambio de responsable de la linea '.$linea->numero;
            $movimiento->organismo_anterior=isset($equipo->idorganismo)?$equipo->idorganismo:$linea->idorganismo;
            $movimiento->organismo_nuevo=$dispositivo_responsable_nuevo->idorganismo;
            if($movimiento->save()){
                if($model->equipo_anterior!=null){
                    $equipo->responsable=$model->responsable_nuevo;
                    $equipo->idorganismo=$dispositivo_responsable_nuevo->idorganismo;
                    $equipo->usuario=null;
                    if($equipo->save()){
                        $movimiento_equipo= new Sds_bdc_movimiento_equipo();
                        $movimiento_equipo->idequipo=$equipo->idequipo;
                        $movimiento_equipo->idmovimiento=$movimiento->idmovimiento;
                        if($movimiento_equipo->save()){
                            return true;
                        }
                    }
                }
                return true;
            }else{
                throw new Exception('No fue posible guardar el movimiento de la linea.
                Por favor intente nuevamente.');
            }
        }else{
            throw new Exception('No fue posible actualizar los datos de la linea. 
            Por favor intente nuevamente.');
        }
        return false;
    }

    protected function movimiento_suspension_robo($model){
        $linea=Sds_cel_linea::findOne($model->idlinea);
        $equipo=Sds_bdc_equipo::findOne($model->equipo_anterior);

        $linea->imei=null;
        $linea->equipo_tipo=Sds_cel_linea::TIPO_SIN_EQUIPO;
        $linea->equipo_detalle=null;
        $linea->fecha_entrega=null;
        $linea->idcontacto=null;
        $linea->idorganismo=null;
        $linea->idusuario=null;
        $linea->observaciones=null;
        $linea->activo=Sds_cel_linea::ACTIVO_BAJA;
        $linea->equipo_marca=null;
        $linea->equipo_modelo=null;
        $linea->organismo_padre=Sds_cel_linea::ID_MINISTERIO;
        $linea->idequipo=null;
        if($linea->save()){
            if($model->save()){
                if($equipo!=null){
                    $equipo->responsable=Sds_bdc_equipo::DIRECTOR_INFORMATICA;
                    $equipo->usuario=null;
                    $organismo=Mds_org_organismo::findBySql(
                        "SELECT o.idorganismo FROM mds_org_contacto c
                        JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                        JOIN mds_org_organismo o ON d.idorganismo=o.idorganismo
                        WHERE c.idcontacto=".Sds_bdc_equipo::DIRECTOR_INFORMATICA
                    )->one();
                    $equipo->idorganismo=$organismo->idorganismo;
                    if($equipo->save(false)){
                        $bdc_movimiento=new Sds_bdc_movimiento();
                        $bdc_movimiento->fecha_hora=$model->fecha_hora;
                        $bdc_movimiento->idusuario=Yii::$app->user->identity->idusuario;
                        $bdc_movimiento->solicitante=$model->solicitante;
                        $bdc_movimiento->tipo=Sds_bdc_movimiento::MOV_BAJA;
                        $bdc_movimiento->responsable_anterior=$model->responsable_anterior;
                        $bdc_movimiento->observaciones='Movimiento generado automáticamente al realizar movimiento de robo de linea '.$linea->numero;
                        $bdc_movimiento->organismo_anterior=$model->organismo_cuenta_anterior;
                        if($bdc_movimiento->save()){
                            $movimiento_equipo=new Sds_bdc_movimiento_equipo();
                            $movimiento_equipo->idequipo=$equipo->idequipo;
                            $movimiento_equipo->idmovimiento=$bdc_movimiento->idmovimiento;
                            if($movimiento_equipo->save()){
                                return true;
                            }
                        }
                    }
                }else{
                    return true;
                }
            }
        }
        return false;
    }
}
