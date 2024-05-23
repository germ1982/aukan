<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_stk_entrega_solicitud_item;
use app\models\Sds_stk_entrega_solicitud_itemSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Sds_stk_entrega_solicitud_itemController implements the CRUD actions for Sds_stk_entrega_solicitud_item model.
 */
class Sds_stk_entrega_solicitud_itemController extends Controller
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
                    'index', 'create', 'update', 'view', 'delete'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'view', 'delete'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_ENTREGA],
                    ],
                ],

            ],
        ];
    }

    /**
     * Lists all Sds_stk_entrega_solicitud_item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_stk_entrega_solicitud_itemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_stk_entrega_solicitud_item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_entrega_solicitud_item', $model->identregasolicitud, $model->getAttributes());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_stk_entrega_solicitud_item #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_stk_entrega_solicitud_item model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($entregasolicitud)
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_entrega_solicitud_item();
        $model->identregasolicitud = $entregasolicitud;
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_entrega_solicitud_item', $model->identregasolicitud, $model->getAttributes());
                return [
                    'title' => "Administrar Items Solicitud Entrega",
                    'content' => $this->renderAjax('/sds_stk_entrega_solicitud/create_item', [
                        'model' => $model,
                        //'mensaje_success' => $mensaje_success,
                        //'mensaje_error' => $mensaje_error
                    ]),
                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a(
                            '<i class="glyphicon glyphicon-plus-sign"></i> Añadir Item',
                            ['sds_stk_entrega_solicitud_item/create', 'entregasolicitud' => $model->identregasolicitud],
                            [
                                'class' => 'btn btn-success pull-right col-md-3',
                                'style' => 'margin: -5px 10px 10px',
                                'role' => 'modal-remote',
                                'title' => 'Añadir Item',
                                //'data-request-method'=>'post',
                                'data-toggle' => 'tooltip',
                            ]
                        )
                ];
            } else {
                return [
                    'title' => "Añadir Items",
                    'content' => $this->renderAjax('/sds_stk_entrega_solicitud/agregar_item', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->identregasolicituditem]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_stk_entrega_solicitud_item model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_entrega_solicitud_item', $model->identregasolicitud, $model->getAttributes());
                return [
                    'title' => "Entrega Solicitud",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Actualizar Entrega Solicitud",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    /**
     * Delete an existing Sds_stk_entrega_solicitud_item model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        if ($model->delete()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_stk_entrega_solicitud_item', $model->identregasolicitud, $model->getAttributes());
            return [
                'title' => "Administrar Items Solicitud Entrega",
                'content' => $this->renderAjax('/sds_stk_entrega_solicitud/create_item', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a(
                        '<i class="glyphicon glyphicon-plus-sign"></i> Añadir Item',
                        ['sds_stk_entrega_solicitud_item/create', 'entregasolicitud' => $model->identregasolicitud],
                        [
                            'class' => 'btn btn-success pull-right col-md-3',
                            'style' => 'margin: -5px 10px 10px',
                            'role' => 'modal-remote',
                            'title' => 'Añadir Item',
                            //'data-request-method'=>'post',
                            'data-toggle' => 'tooltip'
                        ]
                    )
            ];
        }

        /*
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
        */
    }

    /**
     * Delete multiple existing Sds_stk_entrega_solicitud_item model.
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
     * Finds the Sds_stk_entrega_solicitud_item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_stk_entrega_solicitud_item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_stk_entrega_solicitud_item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
