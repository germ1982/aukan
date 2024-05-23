<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_veh_habilitacion;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_veh_habilitacionSearch;
use app\models\Sds_veh_modelo;
use app\models\Sds_veh_vehiculo;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Sds_veh_habilitacionController implements the CRUD actions for Sds_veh_habilitacion model.
 */
class Sds_veh_habilitacionController extends Controller
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
     * Lists all Sds_veh_habilitacion models.
     * @return mixed
     */
    public function actionIndex($vehiculo=null)
    {
        if($vehiculo==null){
            return $this->redirect(['/sds_veh_vehiculo']);
        }
        $searchModel = new Sds_veh_habilitacionSearch();
        $searchModel->idvehiculo=$vehiculo;
        $filter=$this->filter();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data_vehiculo=Sds_veh_vehiculo::findBySql(
            "SELECT v.*, cm.descripcion marca, ct.descripcion tipo_descripcion, m.descripcion modelo_descripcion
            FROM sds_veh_vehiculo v
            JOIN sds_veh_modelo m ON v.modelo=m.idmodelo
            JOIN sds_com_configuracion cm ON m.idmarca=cm.idconfiguracion
            JOIN sds_com_configuracion ct ON v.tipo=ct.idconfiguracion
            WHERE v.idvehiculo=$vehiculo"
        )->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter,
            'vehiculo' => $data_vehiculo
        ]);
    }

    protected function filter($all=false)
    {
        if($all){
            $habilitacion=Sds_com_configuracion::find()->where(['idconfiguraciontipo'=>Sds_com_configuracion_tipo::VEH_HABILITACION_TIPO])->all();
        }else{
            $habilitacion=Sds_com_configuracion::findBySql(
                "SELECT c.* FROM sds_veh_habilitacion h
                JOIN sds_com_configuracion c ON h.tipo=c.idconfiguracion"
            )->all();
        }
        if($all){
            $dataEstado=Sds_com_configuracion::find()->where(['idconfiguraciontipo'=>Sds_com_configuracion_tipo::VEH_ESTADO])->all();
        }else{
            $dataEstado=Sds_com_configuracion::findBySql(
                "SELECT c.* FROM sds_veh_vehiculo v
                JOIN sds_com_configuracion c ON v.estado=c.idconfiguracion"
            )->all();
        }
        if($all){
            $dataModelo=Sds_veh_modelo::find()->all();
        }else{
            $dataModelo=Sds_veh_modelo::findBySql(
                "SELECT vm.* FROM sds_veh_vehiculo v
                JOIN sds_veh_modelo vm ON v.modelo=vm.idmodelo"
            )->all();
        }

        if($all){
            $dataTipo=Sds_com_configuracion::find()->where(['idconfiguraciontipo'=>Sds_com_configuracion_tipo::VEH_TIPO])->all();
        }else{
            $dataTipo=Sds_com_configuracion::findBySql(
                "SELECT c.* FROM sds_veh_vehiculo v
                JOIN sds_com_configuracion c ON v.tipo=c.idconfiguracion"
            )->all();
        }

        $filter = [
            'htipo' => ArrayHelper::map(
                $habilitacion,
                'idconfiguracion',
                'descripcion'
            ),
            'estado' => ArrayHelper::map(
                $dataEstado,
                'idconfiguracion',
                'descripcion'
            ),
            'modelo' => ArrayHelper::map(
                $dataModelo,
                'idmodelo',
                'descripcion'
            ),
            'tipo' => ArrayHelper::map(
                $dataTipo,
                'idconfiguracion',
                'descripcion'
            ),
        ];
        return $filter;
    }


    /**
     * Displays a single Sds_veh_habilitacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sds_veh_habilitacion #".$id,
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
     * Creates a new Sds_veh_habilitacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($vehiculo=null)
    {
        $request = Yii::$app->request;
        $model = new Sds_veh_habilitacion();
        $model->idvehiculo=$vehiculo;
        $filter=$this->filter(true);
        $forceReload='';
        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post())){
                $model->vencimiento=date('Y-m-d', strtotime(str_replace('/','-',$model->vencimiento)));
                $save_file=true;
                $transaction = Yii::$app->db->beginTransaction();
                $tmpFile = UploadedFile::getInstance($model, 'temp_file');
                $date = date('Y-m-d_His', time());
                if (isset($tmpFile)) {
                    $extension = $tmpFile->extension;
                    $path_info = pathinfo($tmpFile);
                    $extension = $path_info['extension'];
                    $nameFile = "h{$model->tipo}_{$date}.{$extension}";
                    $model->adjunto = $nameFile;
                    if (!file_exists('uploads/veh_habilitacion/veh_' . $model->idvehiculo . '/')) {
                        mkdir('uploads/veh_habilitacion/veh_' . $model->idvehiculo. '/', 0777, true);
                    }
                    if(!$tmpFile->saveAs('uploads/veh_habilitacion/veh_'.$model->idvehiculo.'/'. $nameFile)){
                        $save_file=false;
                    }
                }
                if($model->save() && $save_file){
                    Yii::$app->session->setFlash('success', 'Los datos se guardaron de manera correcta.');
                    $forceReload='#crud-datatable-habilitacion-pjax';
                    $transaction->commit();
                    $model = new Sds_veh_habilitacion();
                    $model->idvehiculo=$vehiculo;
                }else{
                    Yii::$app->session->setFlash('faild', 'Los datos no se cargaron de manera correcta.');
                    $transaction->rollBack();
                }
            }
            return [
                'forceReload' => $forceReload,
                'title'=> "Nueva Habilitación",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                    'filter' => $filter
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }else{
            /*Process for non-ajax request*/
            return $this->redirect(['index', 'vehiculo' => $vehiculo]);
        }
       
    }

    /**
     * Updates an existing Sds_veh_habilitacion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $filter=$this->filter(true);
        if($request->isAjax){
            /*Process for ajax request*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            $forceReload='';
            if($model->load($request->post())){
                $model->vencimiento=date('Y-m-d', strtotime(str_replace('/','-',$model->vencimiento)));
                $transaction = Yii::$app->db->beginTransaction();
                $path = Yii::$app->basePath.'/web/'.'uploads/veh_habilitacion/veh_'.$model->idvehiculo.'/'. $model->adjunto;
                $commit=false;
                //Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                if($model->delete_file && $model->adjunto){
                    unlink($path);
                    $model->adjunto=null;
                    $model->delete_file=false;
                    if($model->save()){
                        $commit=true;
                    }
                }else{
                    $tmpFile = UploadedFile::getInstance($model, 'temp_file');
                    if(isset($tmpFile)){
                        $date = date('Y-m-d_His', time());
                        $extension = $tmpFile->extension;
                        $path_info = pathinfo($tmpFile);
                        $extension = $path_info['extension'];
                        $nameFile = "h{$model->tipo}_{$date}.{$extension}";
                        if($model->adjunto!=null){
                            unlink($path);//Si el archivo fue modificado, borro el existente
                        }
                        $model->adjunto = $nameFile;
                        if (!file_exists('uploads/veh_habilitacion/veh_' . $model->idvehiculo . '/')){
                            mkdir('uploads/veh_habilitacion/veh_' . $model->idvehiculo. '/', 0777, true);
                        }
                        if($model->save() && $tmpFile->saveAs('uploads/veh_habilitacion/veh_'.$model->idvehiculo.'/'. $nameFile)){
                            $commit=true;
                        }
                    }else{
                        if($model->save()){
                            $commit=true;
                        }
                    }
                }
                if($commit){
                    $transaction->commit();
                    $forceReload='#crud-datatable-habilitacion-pjax';
                    Yii::$app->session->setFlash('success', 'Los datos se actualizaron de manera correcta');
                }else{
                    Yii::$app->session->setFlash('faild', 'Hubo un error al intentar actualizar los datos. Por favor intente nuevamente.');
                    $transaction->rollBack();
                }
            }
            return [
                'forceReload'=>$forceReload,
                'title'=> "Actualizar datos de habilitación",
                'content'=>$this->renderAjax('update', [
                    'model' => $model,
                    'filter' => $filter
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }else{
            /*Process for non-ajax request*/
            return $this->redirect(['index', 'vehiculo' => $model->idvehiculo]);
        }
    }

    /**
     * Delete an existing Sds_veh_habilitacion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-habilitacion-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Sds_veh_habilitacion model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-habilitacion-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Sds_veh_habilitacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_veh_habilitacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_veh_habilitacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
