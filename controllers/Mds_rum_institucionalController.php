<?php

namespace app\controllers;

use Yii;
use app\models\Mds_rum_institucional;
use app\models\Mds_rum_institucionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_rum_institucionalController implements the CRUD actions for Mds_rum_institucional model.
 */
class Mds_rum_institucionalController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(), 
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'view','create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view','create', 'update', 'delete'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RUM_INSTITUCIONAL,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_rum_institucional models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_rum_institucionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_institucional', null, array());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_rum_institucional model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_institucional', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "RUMBO:: Ver Publicación Institucional",
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
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
     * Creates a new Mds_rum_institucional model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_institucional();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "RUMBO:: Nueva Publicación Institucional",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/institucional', $extension);
                    $model->imagen = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/institucional/' . $nuevo_nombre);
                } else {
                };
                $fechaalta = strftime("%Y-%m-%d", time());
                $horaalta = strftime("%H:%M:%S", time());
                $fechamodificacion = $fechaalta;
                $horamodificacion = $horaalta;
                $fecha_publicacion = $fechaalta;
                $hora_publicacion = $horaalta;
                $model->fechaalta = $fechaalta;
                $model->horaalta = $horaalta;
                $model->fechamodificacion = $fechamodificacion;
                $model->horamodificacion = $horamodificacion;
                $model->fecha_publicacion = $fecha_publicacion;
                $model->hora_publicacion = $hora_publicacion;

                $usuario = Yii::$app->user->identity;
                $idusuario = $usuario != null ? $usuario->idusuario : null;
                if (!isset($idusuario) || $idusuario == null) {
                    $model = new \app\models\LoginForm();
                    return Yii::$app->getResponse()->redirect([
                        'site/login',
                        'model' => $model,
                    ]);
                }
                $id_usuario = $usuario->idusuario;
                $model->autor = $id_usuario;
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_institucional', $model->id, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "RUMBO:: Nueva Publicación Institucional",
                        'content' => '<span class="text-success">Se creo exitosamente una nueva Publicación Institucional</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "RUMBO:: Nueva Publicación Institucional",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                return [
                    'title' => "Create new Mds_rum_institucional",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_institucional', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_rum_institucional model.
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
                    'title' => "RUMBO:: Editar Publicacion Institucional",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_institucional', $model->id, $model->getAttributes());
                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/institucional', $extension);
                    $model->imagen = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/institucional/' . $nuevo_nombre);
                } else {
                };
                $fechamodificacion = strftime("%Y-%m-%d", time());
                $horamodificacion = strftime("%H:%M:%S", time());
                $model->fechamodificacion = $fechamodificacion;
                $model->horamodificacion = $horamodificacion;

                $guardado = $model->save();
                if ($guardado) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_institucional', $model->id, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "RUMBO:: Ver Publicación Institucional",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    return [
                        'title' => "RUMBO:: Editar Publicacion Institucional",
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_rum_institucional #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_rum_institucional #" . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_institucional', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_rum_institucional model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_rum_institucional', $id, $model->getAttributes());
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
     * Finds the Mds_rum_institucional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rum_institucional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rum_institucional::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
