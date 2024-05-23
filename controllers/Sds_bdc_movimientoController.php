<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_bdc_equipo;
use Yii;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_bdc_movimiento_equipo;
use app\models\Sds_bdc_movimientoSearch;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_reg_ip;
use DateTime;
use Exception;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Sds_bdc_movimientoController implements the CRUD actions for Sds_bdc_movimiento model.
 */
class Sds_bdc_movimientoController extends Controller
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
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'delete', 
                           'reporte','get_equipos_responsable',
                           'get_responsable','filter_equipos','get_ip_equipo'
                        ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete',
                                      'reporte', 'get_equipos_responsable', 
                                      'get_responsable','filter_equipos','get_ip_equipo'
                                    ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::BDC_EQUIPO
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new Sds_bdc_movimientoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate($equipo='', $tipo=''){
        $request = Yii::$app->request;
        if($request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $model = new Sds_bdc_movimiento();
        $model->fecha_hora=date('d-m-Y H:i:s');
        $model->idusuario=Yii::$app->user->identity->idusuario;
        $model->usuario_carga=Yii::$app->user->identity->nombre.' '.Yii::$app->user->identity->apellido;
        if($tipo==Sds_bdc_movimiento::MOV_CAM_IP){
            $model->tipo=$tipo;
            $model->equipos=$equipo;
        }
        if(!$request->isAjax){ 
            /* Process for non-ajax request */
            if ($model->load($request->post())){
                if($request->post('Sds_bdc_movimiento')['equipos']==''){
                    $model->addError('equipos', 'Equipos no puede estar vacio');
                    return $this->render('create', [
                        'model' => $model
                    ]);
                }
                $date=new DateTime($model->fecha_hora);
                $model->fecha_hora=date_format($date, 'Y-m-d H:i:s');
                $transaction = Yii::$app->db->beginTransaction();
                
                $commit_ok=false;
                try{
                    if($model->tipo==Sds_bdc_movimiento::MOV_CAM_IP){
                        if($model->ip_nueva=='' && $model->ip_anterior==''){
                            $model->addError('ip_nueva', 'El equipo no tiene IP asignada. Este campo no puede estar vacio.');
                            return $this->render('create', [
                                'model' => $model
                            ]);
                        }else{
                            $commit_ok=$this->changeIp($model);
                        }
                    }else{
                        $count_save_ok=$this->saveMovimientoandEquipo($model);
                        if($count_save_ok == count($request->post('Sds_bdc_movimiento')['equipos'])){
                            $commit_ok=true;
                        }else{
                            if(count($request->post('Sds_bdc_movimiento')['equipos'])>1){
                                throw new Exception('Hubo fallas al guardar el movimiento');
                            }else{
                                throw new Exception('Hubo fallas al guardar al menos un movimiento');
                            }
                        }
                    }
                    if($commit_ok){
                        $transaction->commit();
                        $idmovimiento=$model->idmovimiento;
                        $model = new Sds_bdc_movimiento();
                        $model->fecha_hora=date('d-m-Y H:i:s');
                        $model->idusuario=Yii::$app->user->identity->idusuario;
                        $model->usuario_carga=Yii::$app->user->identity->nombre.' '.Yii::$app->user->identity->apellido;
                        Yii::$app->session->setFlash('save_movimiento_equipo', 'El <a href="index.php?r=sds_bdc_movimiento/view&id='.$idmovimiento.'">movimiento</a> se guardó de manera correcta.');
                    }
                }catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('fail_save_movimiento_equipo', "La operación no se realizó de manera 
                    correcta.<br><br><i>*Error: ".$e->getMessage()."*</i>");
                    $model->fecha_hora=date_format($date, 'd-m-Y H:i:s');
                }

                return $this->render('create', [
                    'model' => $model
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model
                ]);
            }
        }
       
    }
    
    public function actionDelete($id)
    {
        $movimientosequipos=Sds_bdc_movimiento_equipo::find()->where(['idmovimiento'=>$id])->all();
        foreach($movimientosequipos as $movimientoequipo){
            $movimientoequipo->delete();
        }
        $this->findModel($id)->delete();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title'=> "¡Excelente!",
            'content'=>'<div class="alert alert-info"><b>Movimiento eliminado de forma correcta.</b></div>',
            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
        ]; 
    }
    
    protected function findModel($id)
    {
        if (($model = Sds_bdc_movimiento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    function actionGet_equipos_responsable($equipo, $tipo){
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $equipo=Sds_bdc_equipo::findOne($equipo);

        if($tipo==Sds_bdc_movimiento::MOV_CAM_RESPONSABLE){
            $where='WHERE m2.tipo<>'.Sds_bdc_movimiento::MOV_BAJA.')';
        }
        if($tipo==Sds_bdc_movimiento::MOV_REPARACION){
            $where='WHERE m2.tipo='.Sds_bdc_movimiento::MOV_REPARACION.')';
        }
        if($equipo!=null){
            $where.=' AND e.responsable='.$equipo->responsable;
        }
        $equipos_filtrados=Sds_bdc_equipo::findBySql(
            'SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                    (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                        (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                        JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                    GROUP BY mov.idequipo
                    ) um ON m2.idmovimiento=um.ultimo_movimiento '.$where)->all();

        $equipos=ArrayHelper::map(
            $equipos_filtrados,
            'idequipo',
            function($model){
                $marca=Sds_com_configuracion::findOne($model->marca);
                $tipo=Sds_com_configuracion::findOne($model->tipo);
                return '#'.str_pad($model->idequipo,6,"0", STR_PAD_LEFT).' - '.$tipo->descripcion.' '.$marca->descripcion.($model->matricula!=''?' | Mat.: ':'').$model->matricula;
            }
        );
        return json_encode($equipos);
    }

    function actionGet_responsable($equipo){
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $responsable= array();
        if($equipo!=0){
            $equipo=Sds_bdc_equipo::findOne($equipo);
            $contacto=Mds_org_contacto::findOne($equipo->responsable);
            $persona=Sds_com_persona::findOne($contacto->idpersona);

            $responsable['contacto']=$contacto->attributes;
            $responsable['persona']=$persona->attributes;
        }
        return json_encode($responsable);
    }

    function actionFilter_equipos($tipo){
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $baja=Sds_bdc_movimiento::MOV_BAJA;
        $alta=Sds_bdc_movimiento::MOV_ALTA;
        $cambio_responsable=Sds_bdc_movimiento::MOV_CAM_RESPONSABLE;
        $reparacion=Sds_bdc_movimiento::MOV_REPARACION;
        $entrega_reparacion=Sds_bdc_movimiento::MOV_ENT_REPARACION;
        $cambio_ip=Sds_bdc_movimiento::MOV_CAM_IP;
        $home_office=Sds_bdc_movimiento::MOV_HOME_OFFICE;
        
        $equipos_filtrados=null;
        switch($tipo){
            case $baja:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                            GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                        WHERE m2.tipo='.$reparacion.')')->all();
                break;

            case $alta:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                            GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                        WHERE m2.tipo='.$baja.')')->all();
                break;

            case $cambio_responsable:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo NOT IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                            GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                        WHERE m2.tipo='.$baja.')')->all();
                break;

            case $reparacion:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo NOT IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                            GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                        WHERE m2.tipo='.Sds_bdc_movimiento::MOV_BAJA.' OR m2.tipo='.Sds_bdc_movimiento::MOV_REPARACION.')')->all();
                break;

            case $entrega_reparacion:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                            GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                        WHERE m2.tipo='.$reparacion.')')->all();
                break;
            
            case $cambio_ip:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo NOT IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                                GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                    WHERE m2.tipo='.Sds_bdc_movimiento::MOV_BAJA.') AND e.tipo<>'.Sds_bdc_equipo::MONITOR
                )->all();
                break;
            
            case $home_office:
                $equipos_filtrados=Sds_bdc_equipo::findBySql(
                    'SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                        (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                            (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                            GROUP BY mov.idequipo
                            ) um ON m2.idmovimiento=um.ultimo_movimiento
                        WHERE m2.tipo='.$reparacion.')')->all();
                break;

        }
        if($equipos_filtrados!=null){
            $equipos=ArrayHelper::map(
                $equipos_filtrados,
                'idequipo',
                function($model){
                    $marca=Sds_com_configuracion::findOne($model->marca);
                    $tipo=Sds_com_configuracion::findOne($model->tipo);
                    return '#'.str_pad($model->idequipo,6,"0", STR_PAD_LEFT).' - '.$tipo->descripcion.' '.$marca->descripcion.($model->matricula!=''?' | Mat.: ':'').$model->matricula;
                }
            );
            return json_encode($equipos);
        }
        
        return json_encode($equipos_filtrados);
    }

    function actionGet_ip_equipo($pk){
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $equipo=Sds_bdc_equipo::findOne($pk);
        if($equipo!=null){
            return $equipo->ip;
        }
        return null;
    }

    function actionReporte($movimiento){
        if($movimiento==null){
            $content='No se ha selecionado movimiento';
        }
        $content=$this->renderPartial('reporte', ['idmovimiento' => $movimiento]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 5,
            'marginBottom' => 2,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Reporte de Movimiento',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);
        return $pdf->render();
    }

    protected function changeIp($model){
        if($model->ip_anterior!=''){
            //Separo la IP en octetos, para realizar la busqueda de la misma en Sds_reg_ip
            $octetos=explode(".",$model->ip_anterior);
            $model_ip_anterior = Sds_reg_ip::findBySql("SELECT * FROM sds_reg_ip
                WHERE subred='".$octetos[0].".".$octetos[1].".".$octetos[2]."' AND ip=".$octetos[3])->one();
        }
        if($model->ip_nueva!=''){
            $octetos=explode(".",$model->ip_nueva);
            $model_ip_nueva = Sds_reg_ip::findBySql("SELECT * FROM sds_reg_ip
            WHERE subred='".$octetos[0].".".$octetos[1].".".$octetos[2]."' AND ip=".$octetos[3])->one();
        }
        if(isset($model_ip_anterior)){
            $equipo=Sds_bdc_equipo::findOne($model->equipos);
            //Limpio los datos de la IP que se libera:
            $model_ip_anterior->idequipo=null;
            $model_ip_anterior->conectividad=null;
            $model_ip_anterior->disco=null;
            $model_ip_anterior->memoria=null;
            $model_ip_anterior->procesador=null;
            $model_ip_anterior->sistema_operativo=null;
            $model_ip_anterior->asignacion=1;
            $model_ip_anterior->observaciones=null;
            $model_ip_anterior->idcontacto=null;

            $equipo->ip=null;
            if($model_ip_anterior->save()){
                if($model->ip_nueva!=''){
                    $equipo->ip=$model->ip_nueva;
                    if(isset($model_ip_nueva)){
                        $model_ip_nueva->idequipo=$equipo->idequipo;
                    }else{
                        $model_ip_nueva=new Sds_reg_ip();
                        $model_ip_nueva->ip=$octetos[3];
                        $model_ip_nueva->subred=$octetos[0].".".$octetos[1].".".$octetos[2];
                        $model_ip_nueva->idcontacto=$model->idusuario;
                        $model_ip_nueva->observaciones='Alta desde Movimientos de Equipos';
                        if($equipo->tipo==Sds_bdc_equipo::GABINETE){
                            $model_ip_nueva->asignacion=Sds_reg_ip::ASIGNACION_PC_USUARIO;
                        }
                        if($equipo->tipo==Sds_bdc_equipo::IMPRESORA){
                            $model_ip_nueva->asignacion=Sds_reg_ip::ASIGNACION_IMPRESORA;
                        }
                        $model_ip_nueva->idequipo=$equipo->idequipo;
                    }
                    if(!$model_ip_nueva->save()){
                       throw new Exception('Hubo fallas al actualizar/crear la IP Nueva en Sds_reg_ip.');                                
                    }
                }
            }else{
                throw new Exception('No se pudo actualizar el registro de IP existente.');
            }
            if(!$equipo->save()){
                throw new Exception('No se pudo asignar la nueva IP al equipo.');
            }
        }else{
            if($model->ip_nueva!=''){
                $equipo=Sds_bdc_equipo::findOne($model->equipos);
                $equipo->ip=$model->ip_nueva;
                if($equipo->save()){
                    if(isset($model_ip_nueva)){
                        $model_ip_nueva->idequipo=$equipo->idequipo;
                    }else{
                        $model_ip_nueva=new Sds_reg_ip();
                        $model_ip_nueva->ip=$octetos[3];
                        $model_ip_nueva->subred=$octetos[0].".".$octetos[1].".".$octetos[2];
                        $model_ip_nueva->idcontacto=$model->idusuario;
                        $model_ip_nueva->observaciones='Alta desde Movimientos de Equipos';
                        if($equipo->tipo==Sds_bdc_equipo::GABINETE){
                            $model_ip_nueva->asignacion=Sds_reg_ip::ASIGNACION_PC_USUARIO;
                        }
                        if($equipo->tipo==Sds_bdc_equipo::IMPRESORA){
                            $model_ip_nueva->asignacion=Sds_reg_ip::ASIGNACION_IMPRESORA;
                        }
                        $model_ip_nueva->idequipo=$equipo->idequipo;
                    }
                    if(!$model_ip_nueva->save()){
                       throw new Exception('Hubo fallas al actualizar/crear la IP Nueva en Sds_reg_ip.');                                
                    }
                }else{
                    throw new Exception('No se pudo asignar la nueva IP al equipo.');
                }
            }
        }
        if($this->saveMovimientoandEquipo($model)!=0){
            return true;
        }
        return false;
    }

    protected function saveMovimientoandEquipo($model){
        $count_save_ok=0;
        foreach($model->equipos as $id_equipo){
            $equipo_update=Sds_bdc_equipo::findOne($id_equipo);
            $dispositivo=Mds_org_dispositivo::find()->select('d.idorganismo')
                ->from('mds_org_dispositivo d')
                ->innerJoin('mds_org_contacto c', 'c.iddispositivo=d.iddispositivo')
                ->where('c.idcontacto='.$equipo_update->responsable)->one();
            $model->organismo_anterior=$dispositivo->idorganismo;
            
            if($model->tipo==Sds_bdc_movimiento::MOV_ALTA || 
               $model->tipo==Sds_bdc_movimiento::MOV_CAM_RESPONSABLE){
                if($model->responsable_nuevo!=''){
                    $model->responsable_anterior=$equipo_update->responsable;
                    $equipo_update->responsable=$model->responsable_nuevo;

                    $dispositivo=Mds_org_dispositivo::find()->select('d.idorganismo')
                    ->from('mds_org_dispositivo d')
                    ->innerJoin('mds_org_contacto c', 'c.iddispositivo=d.iddispositivo')
                    ->where('c.idcontacto='.$model->responsable_nuevo)->one();
                    $model->organismo_nuevo=$dispositivo->idorganismo;
                    $equipo_update->idorganismo=$dispositivo->idorganismo;
                }
                if($model->usuario_nuevo!=''){
                    $model->usuario_anterior=$equipo_update->usuario;
                    $equipo_update->usuario=$model->usuario_nuevo;
                }
                if(!$equipo_update->save()){
                    throw new Exception('No fue posible actualizar el equipo.');
                }
            }
            if($model->tipo==Sds_bdc_movimiento::MOV_BAJA){
                $model_ip_anterior=Sds_reg_ip::find()->where(['idequipo'=>$equipo_update->idequipo])->one();
                if($model_ip_anterior!=null){
                    $model_ip_anterior->idequipo=null;
                    $model_ip_anterior->conectividad=null;
                    $model_ip_anterior->disco=null;
                    $model_ip_anterior->memoria=null;
                    $model_ip_anterior->procesador=null;
                    $model_ip_anterior->sistema_operativo=null;
                    $model_ip_anterior->asignacion=1;
                    $model_ip_anterior->observaciones=null;
                    $model_ip_anterior->idcontacto=null;
                    if(!$model_ip_anterior->save()){
                        throw new Exception('No fue posible actualizar el registro de la IP.');
                    }
                    $equipo_update->ip=null;
                    if(!$equipo_update->save()){
                        throw new Exception('No fue posible quitar la IP del equipo.');
                    }
                }
            }
            if($model->save()){
                $movimientoequipo = new Sds_bdc_movimiento_equipo();
                $movimientoequipo->idmovimiento=$model->idmovimiento;
                $movimientoequipo->idequipo=$id_equipo;
                if($movimientoequipo->save()){
                    $count_save_ok++;
                }else{
                    throw new Exception('No fue posible guardar el movimiento del equipo #'.str_pad($id_equipo,6,"0", STR_PAD_LEFT));
                }
            }else{
                throw new Exception('No fue posible guardar el movimiento.');
            }
        }
        return $count_save_ok;
    }

    /* 
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }
    */
}