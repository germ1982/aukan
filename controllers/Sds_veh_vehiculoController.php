<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_veh_modelo;
use Yii;
use app\models\Sds_veh_vehiculo;
use app\models\Sds_veh_vehiculoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_veh_vehiculoController implements the CRUD actions for Sds_veh_vehiculo model.
 */
class Sds_veh_vehiculoController extends Controller
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
     * Lists all Sds_veh_vehiculo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_veh_vehiculoSearch();
        $searchModel->idorganismo=Yii::$app->user->identity->organismo_stock;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $filter = $this->filter();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ]);
    }


    /**
     * Displays a single Sds_veh_vehiculo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=Sds_veh_vehiculo::findBySql(
            "SELECT v.*, cmarca.descripcion marca, cestado.descripcion estado_descripcion, ctipo.descripcion tipo_descripcion,
            m.descripcion modelo_descripcion FROM sds_veh_vehiculo v
            JOIN sds_veh_modelo m ON v.modelo=m.idmodelo
            JOIN sds_com_configuracion cmarca ON m.idmarca=cmarca.idconfiguracion
            JOIN sds_com_configuracion cestado ON v.estado=cestado.idconfiguracion
            JOIN sds_com_configuracion ctipo ON v.tipo=ctipo.idconfiguracion
            WHERE v.idvehiculo=$id"
        )->one();
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "$model->tipo_descripcion: <b class='text-primary'>$model->marca - $model->modelo_descripcion</b>
                            <div class='pull-right' style='margin-right:35px;'>Dominio: <b class='text-primary'>$model->dominio</b></div>",
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_veh_vehiculo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    protected function filter($all=false)
    {
        if($all){
            $dataMarca=Sds_com_configuracion::find()->where(['idconfiguraciontipo'=>Sds_com_configuracion_tipo::VEH_MARCA])->all();
        }else{
            $dataMarca=Sds_com_configuracion::findBySql(
                "SELECT c.* FROM sds_veh_vehiculo v
                JOIN sds_veh_modelo vm ON v.modelo=vm.idmodelo 
                JOIN sds_com_configuracion c ON vm.idmarca=c.idconfiguracion"
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
            'marca' => ArrayHelper::map(
                $dataMarca,
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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_veh_vehiculo();
        $filter = $this->filter(true);
        $model->idorganismo = Yii::$app->user->identity->organismo_stock;
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $request->post()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'El vehículo se guardó de manera correcta.');
                    $model = new Sds_veh_vehiculo();
                    $model->idorganismo = Yii::$app->user->identity->organismo_stock;
                } else {
                    Yii::$app->session->setFlash('fail', 'Error al guardar vehículo.');
                }
                return [
                    'forceReload' => "#crud-datatable-pjax",
                    'title' => "Nuevo Vehículo",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'filter' => $filter
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                return [
                    'title' => "Nuevo Vehículo",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'filter' => $filter
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    /**
     * Updates an existing Sds_veh_vehiculo model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $marca=Sds_veh_modelo::findOne($model->modelo);
        $model->marca=$marca->idmarca;
        $filter = $this->filter(true);

        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->save()) {
                $model=Sds_veh_vehiculo::findBySql(
                    "SELECT v.*, cmarca.descripcion marca, cestado.descripcion estado_descripcion, ctipo.descripcion tipo_descripcion,
                    m.descripcion modelo_descripcion FROM sds_veh_vehiculo v
                    JOIN sds_veh_modelo m ON v.modelo=m.idmodelo
                    JOIN sds_com_configuracion cmarca ON m.idmarca=cmarca.idconfiguracion
                    JOIN sds_com_configuracion cestado ON v.estado=cestado.idconfiguracion
                    JOIN sds_com_configuracion ctipo ON v.tipo=ctipo.idconfiguracion
                    WHERE v.idvehiculo=$model->idvehiculo"
                )->one();
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "$model->tipo_descripcion: <b class='text-primary'>$model->marca - $model->modelo_descripcion</b>
                                <div class='pull-right' style='margin-right:35px;'>Dominio: <b class='text-primary'>$model->dominio</b></div>",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }else{
                return [
                    'title' => "Editar Vehículo",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'filter' => $filter
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idvehiculo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_veh_vehiculo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Sds_veh_vehiculo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Sds_veh_vehiculo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_veh_vehiculo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_veh_vehiculo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
