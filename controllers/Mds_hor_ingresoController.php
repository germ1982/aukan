<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_ingreso;
use app\models\Mds_hor_ingresoSearch;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use johnitvn\ajaxcrud\CrudAsset;

/**
 * Mds_hor_ingresoController implements the CRUD actions for Mds_hor_ingreso model.
 */
class Mds_hor_ingresoController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout', 'guardar_ingreso'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'bulk-delete', 'update', 'view', 'logout', 'guardar_ingreso'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_INGRESO,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_ingreso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_hor_ingresoSearch();
        $searchModel->fdesde = date('d-m-Y');
        $searchModel->fhasta = date('d-m-Y');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_ingreso', null, array());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_hor_ingreso model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_ingreso', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_hor_ingreso #" . $id,
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
    }

    /**
     * Creates a new Mds_hor_ingreso model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_ingreso();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_hor_ingreso",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_ingreso', $model->idingreso, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_hor_ingreso",
                    'content' => '<span class="text-success">Create Mds_hor_ingreso success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_hor_ingreso",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_ingreso', $model->idingreso, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idingreso]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_hor_ingreso model.
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
                    'title' => "Completar Datos de Ingreso",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_ingreso', $model->idingreso, $model->getAttributes());
                return [
                    'title' => "Completar Datos de Ingreso",
                    'content' => '<span class="text-success">Datos Actualizados!</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Completar Datos de Ingreso",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_ingreso', $model->idingreso, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idingreso]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionGuardar_ingreso($idcontacto, $idlugar)
    {
        $model = new Mds_hor_ingreso();
        if (isset($idcontacto) && $idcontacto != null && isset($idlugar) && $idcontacto != null) {
            /* idingreso' => 'Idingreso',
            'idcontacto' => 'Empleado',
            'fecha_hora' => 'Fecha Hora',
            'temperatura' => 'Temperatura',
            'observaciones' => 'Observaciones', */
            $model->idcontacto = $idcontacto;
            $model->fecha_hora = date('Y-m-d H:i');
            $model->temperatura = 0;
            $contacto = Mds_org_contacto::findOne($idcontacto);
            $persona = Sds_com_persona::findOne($contacto->idpersona);
            $lugar = Sds_com_configuracion::findOne($idlugar);
            //<nombre_apellido> - Lugar <descripcion_lugar>. Ejemplo: Pedro Lopez - Lugar 1
            $model->observaciones = ($persona != null ? $persona->nombre . " " . $persona->apellido : "Guardia Sin_nombre_" . $idcontacto) . " - Lugar " . $lugar->descripcion;
            /*
            *   Process for ajax request
            */
        }
        if ($model->save()) {
            //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_ingreso', $model->idingreso, $model->getAttributes());
            Yii::$app->session->setFlash('success', "Ingreso guardado correctamente!");
            return $this->renderPartial('mensaje_ingreso');
        } else {
            Yii::$app->session->setFlash('error', "Error al guardar el ingreso.");
            return $this->renderPartial('mensaje_ingreso');
        }
    }

    /**
     * Delete an existing Mds_hor_ingreso model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_hor_ingreso', $id, $model->getAttributes());
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
     * Delete multiple existing Mds_hor_ingreso model.
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
     * Finds the Mds_hor_ingreso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_hor_ingreso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_hor_ingreso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
