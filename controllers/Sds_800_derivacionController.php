<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_800_derivacion;
use app\models\Sds_800_derivacionSearch;
use app\models\Sds_800_llamada;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use yii\web\ForbiddenHttpException;

/**
 * Sds_800_derivacionController implements the CRUD actions for Sds_800_derivacion model.
 */
class Sds_800_derivacionController extends Controller
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
                            Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_800_derivacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $searchModel = new Sds_800_derivacionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_derivacion', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    /**
     * Displays a single Sds_800_derivacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_derivacion', $id, array());
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Sds_800_derivacion #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return $this->render('view', [
                    'model' => $this->findModel($id),
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_800_derivacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_800_derivacion();
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Nueva Derivación",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else if ($model->load($request->post()) && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_derivacion', $model->idderivacion, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nueva Derivación",
                        'content' => '<span class="text-success">Derivación creada correctamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Nueva Derivación",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_derivacion', $model->idderivacion, $model->getAttributes());
                    return $this->redirect(['view', 'id' => $model->idderivacion]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_800_derivacion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Actualizar Derivación #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else if ($model->load($request->post()) && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_derivacion', $model->idderivacion, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Actualizar Derivación #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    return [
                        'title' => "Actualizar Derivación #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_derivacion', $model->idderivacion, $model->getAttributes());
                    return $this->redirect(['view', 'id' => $model->idderivacion]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_800_derivacion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            if ($model->delete() > 0) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_800_derivacion', $id, $model->getAttributes());
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
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Sds_800_derivacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_800_derivacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_800_derivacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
