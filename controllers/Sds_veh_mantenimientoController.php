<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_veh_mantenimiento;
use app\models\Sds_veh_mantenimientoSearch;
use app\models\Sds_veh_vehiculo;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_veh_mantenimientoController implements the CRUD actions for Sds_veh_mantenimiento model.
 */
class Sds_veh_mantenimientoController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_VEH_VEHICULO,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_veh_mantenimiento models.
     * @return mixed
     */
    public function actionIndex($vehiculo=null)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_veh_mantenimiento', null, array());
        if ($vehiculo==null){
            $this->redirect(['/sds_veh_vehiculo']);
        }
        $datos_vehiculo=Sds_veh_vehiculo::findBySql(
            "SELECT v.*, m.descripcion modelo_descripcion, cm.descripcion marca, ct.descripcion tipo_descripcion 
            FROM sds_veh_vehiculo v
            JOIN sds_veh_modelo m ON v.modelo=m.idmodelo
            JOIN sds_com_configuracion cm ON m.idmarca=cm.idconfiguracion
            JOIN sds_com_configuracion ct ON v.tipo=ct.idconfiguracion
            WHERE v.idvehiculo=$vehiculo
            "
        )->one();
        $searchModel = new Sds_veh_mantenimientoSearch();
        $searchModel->idvehiculo =$vehiculo;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'vehiculo' => $datos_vehiculo
        ]);
    }


    /**
     * Displays a single Sds_veh_mantenimiento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Datos de mantenimiento",
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
     * Creates a new Sds_veh_mantenimiento model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($vehiculo=null)
    {

        $request = Yii::$app->request;
        $model = new Sds_veh_mantenimiento();
        $model->idvehiculo=$vehiculo; 

        if($request->isAjax){
            /*Process for ajax request*/
            $forceReload='';
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post())){
                $model->fecha=date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha)));
                if($model->save()){
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_veh_mantenimiento', null, array());
                    $forceReload='#crud-datatable-pjax';
                    Yii::$app->session->setFlash('success','Se ha creado un nuevo mantenimiento...'); 
                    $model = new Sds_veh_mantenimiento();
                    $model->idvehiculo=$vehiculo; 
                }else{
                    Yii::$app->session->setFlash('faild','Algo ha fallado...') ;
                }
            }
            return [
                'forceReload'=>$forceReload,
                'title'=> "Crear mantenimiento",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_veh_mantenimiento', $model->idmantenimiento, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idmantenimiento]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Sds_veh_mantenimiento model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $forceReload='';
        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post())){
                if($model->save()){
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_veh_mantenimiento', $model->idmantenimiento, $model->getAttributes());
                    $forceReload='#crud-datatable-pjax';
                    Yii::$app->session->setFlash('success','Se ha creado un nuevo mantenimiento...');
                }else{
                    Yii::$app->session->setFlash('faild','Algo ha fallado...') ;
                }
            }
            return [
                'forceReload' => $forceReload,
                'title'=> "Actualizar datos de mantenimiento",
                'content'=>$this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Editar',['class'=>'btn btn-primary','type'=>"submit"])
            ];        
            
        }else{
            /* Process for non-ajax request */
            return $this->redirect(['index', 'vehiculo'=>$model->idvehiculo]);
        }
    }

    /**
     * Delete an existing Sds_veh_mantenimiento model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model= ($this->findModel($id)->delete());
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_veh_mantenimiento', $id, array());
        
        if($request->isAjax){
            /*Process for ajax request*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /* Process for non-ajax request*/
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Sds_veh_mantenimiento model.
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
            /*Process for ajax request*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*Process for non-ajax request*/
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Sds_veh_mantenimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_veh_mantenimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_veh_mantenimiento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
