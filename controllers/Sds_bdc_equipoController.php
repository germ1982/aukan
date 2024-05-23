<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_equipoSearch;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_bdc_movimiento_equipo;
use app\models\Sds_bdc_tipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_reg_ip;
use Exception;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_bdc_equipoController implements the CRUD actions for Sds_bdc_equipo model.
 */
class Sds_bdc_equipoController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'reporte_qr', 'get_organismo_contacto', 'get_data_tipo'],
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'reporte_qr', 'get_organismo_contacto', 'get_data_tipo'],
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
        $searchModel = new Sds_bdc_equipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        /*
        print_r(Yii::$app->request->queryParams);
        exit;
        */
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {   
        $model=Sds_bdc_equipo::findBySql("
            SELECT e.*, IFNULL((SELECT tipo FROM (
            SELECT mov.idequipo, (SELECT tipo FROM sds_bdc_movimiento WHERE idmovimiento=max(mov.idmovimiento)) tipo FROM
                (SELECT me.idmovimiento, me.idequipo, m.fecha_hora FROM sds_bdc_movimiento m
                JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
            GROUP BY mov.idequipo ) eq WHERE idequipo=e.idequipo), 2435) as estado from sds_bdc_equipo e
            WHERE e.idequipo=".$id)->one();
        
        $estado=Sds_com_configuracion::findOne($model->estado);
        
        $model->estado=$estado->descripcion;
        
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> 'Detalle de Equipo',
                    'content'=>$this->renderAjax('view', [
                        'model' => $model
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $model 
            ]);
        }
    }

    public function actionCreate(){
        $request = Yii::$app->request;
        $model = new Sds_bdc_equipo();
        if ($model->load($request->post())) {
            //Verifico si se ingreso IP, en tal caso verifico si esa IP ya está asignada a otro equipo
            $checkIp=Sds_bdc_equipo::find()->where('ip="'.($model->ip==""?"-1":$model->ip).'"')->one();
            //$confirm modifica su valor a 1 en caso de que el usuario acepte realizar el cambio de equipo de la IP
            if($checkIp!=null){//Si hay IP 'en conflicto' se lo informo al usuario (conflictIp)
                return $this->render('create', [
                    'model' => $model,
                    'conflictIp' => $checkIp->idequipo
                ]);
            }
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($model->save()){
                    if($model->usuario==null){
                        $model->usuario=$model->responsable;
                    }
                    if($model->ip!=''){
                        $octetos=explode(".",$model->ip);
                        $ip=Sds_reg_ip::findBySql("SELECT * FROM sds_reg_ip
                            WHERE subred='".$octetos[0].".".$octetos[1].".".$octetos[2]."' AND ip=".$octetos[3]
                            )->one();
                        if($ip!=null){
                            $ip->idequipo=$model->idequipo;
                            if(!$ip->update()){
                                throw new Exception('No se pudo actualizar el registro de la IP.');
                            }
                        }else{
                            $ip=new Sds_reg_ip();
                            $ip->ip=$octetos[3];
                            $ip->subred=$octetos[0].'.'.$octetos[1].'.'.$octetos[2];
                            $ip->idcontacto=$model->usuario;
                            $ip->observaciones='';
                            switch($model->tipo){
                                case Sds_bdc_equipo::IMPRESORA:
                                    $ip->asignacion=Sds_reg_ip::ASIGNACION_IMPRESORA;
                                    break;
                                case Sds_bdc_equipo::GABINETE:
                                    $ip->asignacion=Sds_reg_ip::ASIGNACION_PC_USUARIO;
                                    break;
                                default:
                                    $ip->asignacion=$model->tipo;
                            }
                            $ip->idequipo=$model->idequipo;
                            if(!$ip->save()){
                                throw new Exception('Se debía crear un registro de IP y esta acción ha fallado.');
                            }
                        }
                    }

                    $movimiento=new Sds_bdc_movimiento();
                    $movimiento->fecha_hora=date("Y-m-d H:i:s");
                    $movimiento->idusuario=Yii::$app->user->identity->idusuario;
                    $movimiento->solicitante=$model->responsable;
                    $movimiento->tipo=Sds_bdc_movimiento::MOV_ALTA;
                    $movimiento->responsable_nuevo=$model->responsable;
                    $movimiento->usuario_nuevo=$model->usuario;
                    $movimiento->ip_nueva=$model->ip;
                    $dispositivo=Mds_org_dispositivo::find()->select('d.idorganismo')
                        ->from('mds_org_dispositivo d')
                        ->innerJoin('mds_org_contacto c', 'c.iddispositivo=d.iddispositivo')
                        ->where('c.idcontacto='.$model->responsable)->one();
                    $movimiento->organismo_anterior=$dispositivo->idorganismo;
                    
                    $movimiento->observaciones="Movimiento generado automáticamente al cargar el equipo en el sistema SUR.";
                    if($movimiento->save()){
                        $mov_eq=new Sds_bdc_movimiento_equipo();
                        $mov_eq->idmovimiento=$movimiento->idmovimiento;
                        $mov_eq->idequipo=$model->idequipo;
                        if($mov_eq->save()){
                            $transaction->commit();
                            $idequipo=$model->idequipo;
                            $model = new Sds_bdc_equipo();
                            Yii::$app->session->setFlash('save_equipo', 'El equipo se guardó de manera correcta. Podés identificarlo con el ID 
                                <a href="index.php?r=sds_bdc_equipo/view&id='.$idequipo.'">#'.str_pad($idequipo,6,"0", STR_PAD_LEFT).'</a>');
                        }
                    }else{
                        throw new Exception('El movimiento de Alta no pudo ser guardado.');
                    }
                }else{
                    throw new Exception('No se pudo guardar el equipo.');
                }
            }catch(Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('fail_save_equipo', "El equipo no se guardó de manera correcta.<br><br><i>*Error: ".$e->getMessage()."*</i>");
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);       
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Equipo #".str_pad($id,6,"0", STR_PAD_LEFT),
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    if($model->save()){
                        Yii::$app->session->setFlash('save_equipo', "El equipo se actualizó de manera correcta.");
                        $transaction->commit();
                    }else{
                        throw new Exception('No se pudo actualizar los datos del equipo.');
                    }
                }catch(Exception $e){
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('fail_save_equipo', "El equipo no se guardó de manera correcta.<br><br><i>*Error: ".$e->getMessage()."*</i>");
                    return [
                        'title'=> "Actualizar El Equipo #".str_pad($id,6,"0", STR_PAD_LEFT),
                        'content'=>$this->renderAjax('update', [
                            'model' => $model
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                    ];
                }
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Se actualizó el equipo #".str_pad($id,6,"0", STR_PAD_LEFT),
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Actualizar Equipo #".str_pad($id,6,"0", STR_PAD_LEFT),
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /* Process for non-ajax request */
            return $this->redirect('index.php?r=sds_bdc_equipo');
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /* Process for non-ajax request */
            return $this->redirect(['index']);
        }


    }

    protected function findModel($id)
    {
        if (($model = Sds_bdc_equipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_qr($format='a4', $id_mov=null){
        //Edito la propiedad pcre.backtrack_limit del php.ini para que permita mayor cantidad de HTML en la pagina
        ini_set("pcre.backtrack_limit", "10000000");
        $pks = [];
        if($format=='movimiento'){
            $movimiento=Sds_bdc_movimiento::findOne($id_mov);
            $movs_equipos=Sds_bdc_movimiento_equipo::findBySql(
                "SELECT * 
                FROM sds_bdc_movimiento_equipo
                WHERE idmovimiento=$id_mov")->all();
            foreach($movs_equipos as $mov_equipo){
                array_push($pks, $mov_equipo->idequipo);
            }
            $date_mp=date('d/m/Y', strtotime($movimiento->fecha_hora));
            $format='a4';
        }else{
            $request = Yii::$app->request;
            $pks = (array) $request->post('selection');
        }
        if (empty($pks)) {
            return $this->redirect('index.php?r=sds_bdc_equipo');
        }
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_bdc_equipo/reporte_qr', null, array('idequipos' => $pks));
        if($format=='a6'){
            $content = $this->renderPartial('reporte_qr_a6', ['ids' => $pks]); // setup kartik\mpdf\Pdf component
        }else{
            $content = $this->renderPartial('reporte_qr_a4', ['ids' => $pks, 'date_mp' => isset($date_mp)?$date_mp:false]); // setup kartik\mpdf\Pdf component
        }
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_LETTER,
            'marginTop' => 4,
            'marginBottom' => 0,
            'marginLeft' => 3,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'QR Equipos',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);
        return $pdf->render();
    }

    public function actionGet_organismo_contacto($pk){
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $contacto=Mds_org_contacto::findOne($pk);
        $dispositivo=Mds_org_dispositivo::findOne($contacto->iddispositivo);
        $organismo=Mds_org_organismo::findOne($dispositivo->idorganismo);
        
        return json_encode($organismo->attributes);
    }

    public function actionGet_data_tipo($pk){
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tipo=Sds_bdc_tipo::findOne($pk);
        return json_encode($tipo->attributes);
    }

    /* public function actionBulkDelete()
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
