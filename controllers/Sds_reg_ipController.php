<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_reg_ip;
use app\models\Sds_reg_ipSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;

/**
 * Sds_reg_ipController implements the CRUD actions for Sds_reg_ip model.
 */
class Sds_reg_ipController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_REG_REGISTROS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_reg_ip models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Sds_reg_ipSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_ip', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLiberar_ip($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $ip = "$model->subred.$model->ip";
        $model->idcontacto = null;
        $model->observaciones = null;
        $model->asignacion = 1;
        $model->sistema_operativo = null;
        $model->procesador = null;
        $model->memoria = null;
        $model->disco = null;
        $model->conectividad = null;
        $model->save();

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Liberar Ip: ".$ip,
                    'content'=>"Se liberó Ip: $ip",
                    //'content'=>$this->renderAjax('view', ['model' => $model,]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];           
            }else if($model->load($request->post()) && $model->save()){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_ip', $model->idip, $model->getAttributes());
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Liberar Ip: ".$ip,
                    'content'=>"Se liberó Ip: $ip",
                    //'content'=>$this->renderAjax('view', ['model' => $model,]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Liberar Ip: ".$ip,
                    'content'=>"Se liberó Ip: $ip",
                    //'content'=>$this->renderAjax('view', ['model' => $model,]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];          
            }
        }else{
            return [
                //'forceReload'=>'#crud-datatable-pjax',
                'title'=> "Liberar Ip: ".$ip,
                'content'=>"Se liberó Ip: $ip",
                //'content'=>$this->renderAjax('view', ['model' => $model,]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];   
        }
    }
    
    public function actionGet_listado_ips()
    {
        $data =  Sds_reg_ip::findBySql('select idip, concat(subred,".",ip) ip_completa from sds_reg_ip')->all();
        $ips = "";
        if (sizeof($data) > 0) 
            {
                foreach ($data as $ip) 
                {
                    $Dato = $ip->ip_completa;
                    $ips =  $ips.$Dato.',';
                }
                $ips = trim($ips, ',');
            } 
        else 
            {
                $ips = "";
            } 
        return $ips;
    }
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $ip = "$model->subred.$model->ip";
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_ip', $id, array());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;


            return [
                    'title'=> "Ip: ".$ip,
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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_reg_ip();  
        if($request->isAjax) //Process for ajax request
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if($request->isGet)
                    {
                        return [
                                    'title'=> "Generar Rangos de Ip",
                                    'content'=>$this->renderAjax('create', ['model' => $model,]),
                                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Generar', [ 'class' => 'btn btn-primary', 'onclick' => 'validar_numeros()' ])
                                ];         
                    }
                else if($model->load($request->post()) && $model->save())
                        {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_ip', $model->idip, $model->getAttributes());
                            return [
                                        'forceReload'=>'#crud-datatable-pjax',
                                        'title'=> "Create new Sds_reg_ip",
                                        'content'=>'<span class="text-success">Create Sds_reg_ip success</span>',
                                        'footer'=> Html::button('Cerrar 2',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                                Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                                    ];         
                        }
                else
                        {           
                            return [
                                        'title'=> "Create new Sds_reg_ip",
                                        'content'=>$this->renderAjax('create', ['model' => $model,]),
                                        'footer'=> Html::button('Cerrar 3',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                                    ];         
                        }
            }
        else //Process for non-ajax request
            {
                if ($model->load($request->post()) && $model->save()) 
                    {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_ip', $model->idip, $model->getAttributes());
                        return $this->redirect(['view', 'id' => $model->idip]);
                    } 
                else 
                    {
                        return $this->render('create', ['model' => $model,]);
                    }
            }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $ip = "$model->subred.$model->ip";

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Editar Ip ".$ip,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal", 'id' => 'btnCerrar']).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit", 'id' => 'btnGuardar'])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_ip', $model->idip, $model->getAttributes());
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Editar Ip ".$ip,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Editar Ip ".$ip,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal", 'id' => 'btnCerrar']).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit", 'id' => 'btnGuardar'])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_ip', $model->idip, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idip]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_reg_ip model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_reg_ip', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_reg_ip model.
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
     * Finds the Sds_reg_ip model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_reg_ip the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_reg_ip::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function actionVerificar_gravacion_de_camara($ip)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $ruta = Sds_reg_ipController::get_ruta_camara($ip);
        //echo $ruta;
        $ban = 2;
        $contador=0;
        $minutitos = 61;
        $aux = '';
        //return "ip: $ip \n Ruta: $ruta";
        if($ruta!='')
            {
                $ban = 1;
                $elementos = opendir($ruta);
                while(($elemento = readdir($elementos))!==false)
                    {
                        if(!($elemento=='.' || $elemento=='..'))
                            {
                                if(!(is_dir("$ruta\\$elemento")))
                                    {
                                        $contador++;
                                        $fecha_creacion = date("Y/m/d H:i:s", filectime("$ruta\\$elemento"));
                                        $fecha_actual = date("Y/m/d H:i:s");
                                        $minutos = minutosTranscurridos($fecha_creacion,$fecha_actual);
                                        if($minutos < $minutitos)
                                            {
                                                $minutitos = $minutos;
                                            }
                                        if($minutitos>60)
                                            {
                                                $ban = 0;
                                            }
                                        $aux = "$aux $contador: Actual:($fecha_actual), Creacion($fecha_creacion) - minutos transcurridos: $minutos, lapso mas chico: $minutitos<br>";
                                    }

                            }
                    }
            }
        
            //echo "ruta: $ruta <br> $aux <br> ban: $ban";
        return "$ban";
    }
    public static function get_ruta_camara($ip)
    {
        $aux = '';
        $ruta = '\\\10.1.73.243\P\ip cam\RecordFile\\'.date("Y").date("m").date("d");
        $elementos = opendir($ruta);
        $ban = 0;
        while(($elemento = readdir($elementos))!==false)
            {
                if(!($elemento=='.' || $elemento=='..'))
                    {
                        if(is_dir("$ruta\\$elemento"))
                            {
                                //echo "<br>$elemento<br>";
                                if($ban==0)
                                    {
                                        if(strpos($elemento,$ip)===false)
                                            {
                                                $aux = '';  
                                            }
                                        else
                                            {
                                                $aux = "$ruta\\$elemento";
                                                $ban = 1;
                                                //echo "<br>$aux<br>";
                                            }
                                    }
                            }
                    }
            }
        return $aux;
    }


    
}
function minutosTranscurridos($fecha_i,$fecha_f)
{
    $minutos = (strtotime($fecha_i)-strtotime($fecha_f))/60;
    $minutos = abs($minutos); $minutos = floor($minutos);
    return $minutos;
}