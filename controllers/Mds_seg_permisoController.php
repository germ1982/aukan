<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_permisoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;

/**
 * Mds_seg_permisoController implements the CRUD actions for Mds_seg_permiso model.
 */
class Mds_seg_permisoController extends Controller
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
                'only' => ['index', 'create', 'update', 'eliminar', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'eliminar', 'update', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_SEG_SEGURIDAD,
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Mds_seg_permiso models.
     * @return mixed
     */
    public function actionIndex($idrol)
    {
        $searchModel = new Mds_seg_permisoSearch();
        $searchModel->idrol = $idrol;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_permiso', null, array());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Listado de Permisos",
            'content' => $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'idrol' => $idrol
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Editar Rol', ['mds_seg_rol/update', 'id' => $idrol], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
        ];
    }


    /**
     * Displays a single Mds_seg_permiso model.
     * @param integer $id
     * @return mixed
     * POR AHORA DEJO DE USAR EL VIEW PORQUE ESTA AL PEDO
     */
    /*     public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_permiso', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_seg_permiso #" . $id,
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
    } */

    /**
     * Creates a new Mds_seg_permiso model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idrol)
    {
        $request = Yii::$app->request;
        $model = new Mds_seg_permiso();
        $model->idrol = $idrol;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Permiso",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index', 'idrol' => $idrol],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_permiso', $model->idpermiso, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Nuevo Permiso",
                    'content' => '<span class="text-success">Permiso Creado exitosamente!</span>',
                    'footer' => Html::a(
                        ' Volver a la Grilla',
                        ['index', 'idrol' => $idrol],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::a('Agregar Otro', ['create', 'idrol' => $idrol], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Nuevo Permiso",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index', 'idrol' => $idrol],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_permiso', $model->idpermiso, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idpermiso]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_seg_permiso model.
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
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Permiso #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index', 'idrol' => $model->idrol],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_permiso', $model->idpermiso, $model->getAttributes());
                $searchModel = new Mds_seg_permisoSearch();
                $searchModel->idrol = $model->idrol;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Listado de Permisos",
                    'content' => $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'idrol' => $model->idrol
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar Rol', ['mds_seg_rol/update', 'id' => $model->idrol], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Actualizar Permiso #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index', 'idrol' => $model->idrol],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_permiso', $model->idpermiso, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idpermiso]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_seg_permiso model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEliminar($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $idrol = $model->idrol;
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_permiso', $id, $model->getAttributes());
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            $searchModel = new Mds_seg_permisoSearch();
            $searchModel->idrol = $model->idrol;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Listado de Permisos",
                'content' => $this->renderAjax('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'idrol' => $model->idrol
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar Rol', ['mds_seg_rol/update', 'id' => $model->idrol], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Mds_seg_permiso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_seg_permiso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_seg_permiso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
