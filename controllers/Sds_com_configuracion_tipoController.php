<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion_tipoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_com_configuracion_tipoController implements the CRUD actions for Sds_com_configuracion_tipo model.
 */
class Sds_com_configuracion_tipoController extends Controller
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
                'only' => [
                    'index', 'create', 'update', 'delete', 'bulkDelete', 'view'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'delete', 'bulkDelete', 'view'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Sds_com_configuracion_tipo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_com_configuracion_tipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_configuracion_tipo', null, []);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_com_configuracion_tipo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_configuracion_tipo', $id, $model->getAttributes());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Configuracion tipo #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Actualizar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Sds_com_configuracion_tipo model. 
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_com_configuracion_tipo();
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_configuracion_tipo', $model->idconfiguraciontipo, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear nuevo configuracion tipo",
                    'content' => '<span class="text-success">Operación exitosa</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Ingresar otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            }
            return [
                'title' => "Crear nueva configuracion tipo ",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /* Process for non-ajax request */
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing Sds_com_configuracion_tipo model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null, $estado = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($estado != null) {
            $model->activo = $estado;
            if ($model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_configuracion_tipo', $model->idconfiguraciontipo, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => ($estado == 1 ? 'Activar' : 'Desactivar') . ' tipo configuración',
                    'content' => '<div class="alert alert-' . ($estado == 1 ? 'success' : 'danger') . ' text-center"><b>¡La configuración ha sido ' . ($estado == 1 ? 'activada!' : 'desactivada!') . '</b></div>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
                ];
            }
        }

        if ($request->isAjax) {
            /*Process for ajax request*/
            $forcereload = '';
            if ($model->load($request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Los datos se editaron de manera correcta.');
                    $forcereload = '#crud-datatable-pjax';
                } else {
                    Yii::$app->session->setFlash('faild', 'Hubo fallas al guardar. Por favor intente nuevamente.');
                }
            }
            return [
                'forceReload' => $forcereload,
                'title' => "Editar Tipo Configuración",
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /* Process for non-ajax request */
            $this->redirect(['index']);
        }
    }

    /**
     * Delete an existing Sds_com_configuracion_tipo model.
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
     * Delete multiple existing Sds_com_configuracion_tipo model.
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
     * Finds the Sds_com_configuracion_tipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_com_configuracion_tipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_com_configuracion_tipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
