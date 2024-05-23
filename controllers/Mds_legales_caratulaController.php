<?php

namespace app\controllers;

use Yii;
use app\models\Mds_legales_caratula;
use app\models\Mds_legales_caratulaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Mds_legales_oficio;

use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\helpers\Html;

/**
 * Mds_legales_caratulaController implements the CRUD actions for Mds_legales_caratula model.
 */
class Mds_legales_caratulaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'view', 'create', 'store', 'update', 'delete', 'reactivate', 'search_caratula', 'listado_requerimientos'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'store', 'update', 'delete', 'reactivate', 'listado_requerimientos'],
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_CARATULA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['search_caratula'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_SEARCH_CARATULA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Mds_legales_caratula models.
     * @return mixed
     */
    public function actionIndex($fechaInicio = null, $fechaFin = null)
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $searchModel = new Mds_legales_caratulaSearch();
            $hasRolAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $hasRolAdminGeneral, $fechaInicio, $fechaFin);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_caratula', null, array());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_legales_caratula model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_caratula', $id, array());
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_legales_caratula model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mds_legales_caratula();
        $action = 'create';

        return $this->render('create', [
            'action' => $action,
            'model' => $model,
            'puedeEliminar' => true,
        ]);
    }

    public function actionStore()
    {
        $model = new Mds_legales_caratula();
        if (Yii::$app->request->post()) {
            $payload = Yii::$app->request->post();
            $model->created_at = date('Y-m-d H:i:s');
            $model->idusuario_alta = Yii::$app->user->id;

            $model->load(Yii::$app->request->post());

            if ((isset($model['deleted_at']) && ($model['deleted_at'] == '0')) || (isset($payload['deleted_at']) && ($payload['deleted_at'] == '0'))) {
                $model->deleted_at = date('Y-m-d H:i:s');
                $model->idusuario_borra = Yii::$app->user->id;
            } else {
                $model->deleted_at = null;
                $model->idusuario_borra = null;
            }

            $transaction = Yii::$app->db->beginTransaction();

            if ($model->validate()) {
                if ($model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_caratula', $model->idlegalescaratula, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se generó correctamente la carátula.");
                    return $this->redirect(['mds_legales_caratula/index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al guardar la carátula.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la carátula.");
            }
        }
    }

    /**
     * Updates an existing Mds_legales_caratula model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $hasRolAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $deletedTemporal = $model->deleted_at;

            if ($model->load($request->post())) {
                if (!isset($request->post()['Mds_legales_caratula']['deleted_at'])) {
                    $model->deleted_at = 1;
                }
                // Verificar si anteriormente ya estaba eliminado. Si no estaba eliminado, setear la nueva fecha de deleted at
                if ($deletedTemporal == null) {
                    // Estaba activo y no eliminado
                    if ($model->deleted_at == 0) {
                        // Ahora el registro editado debe eliminarse 
                        $model->deleted_at = date('Y-m-d H:i:s');
                        $model->idusuario_borra = Yii::$app->user->id;
                    } else {
                        $model->deleted_at = null;
                    }
                } else {
                    // Estaba eliminado (no activo)
                    if ($model->deleted_at == 1) {
                        $model->deleted_at = null;
                        $model->idusuario_borra = null;
                    } else {
                        $model->deleted_at = $deletedTemporal;
                    }
                }

                $model->idusuario_modifica = Yii::$app->user->id;
                $model->updated_at = date('Y-m-d H:i:s');

                if ($model->validate()) {
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_caratula', $model->idlegalescaratula, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se actualizó correctamente la carátula.");
                        return $this->redirect(['mds_legales_caratula/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al actualizar la carátula.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos de la carátula.");
                }
            } else {
                $action = 'update';

                if ($model->deleted_at !== null) {
                    $model->deleted_at = 0;
                } else {
                    $model->deleted_at = 1;
                }

                $puedeEliminar = (($model['idusuario_alta'] === $usuarioAuth->idusuario) || $hasRolAdminGeneral);

                return $this->render('update', [
                    'action' => $action,
                    'model' => $model,
                    'puedeEliminar' => $puedeEliminar,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_legales_caratula model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $hasRolAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        $model = $this->findModel($id);

        if ($hasRolAdminGeneral || (($model['idusuario_alta'] === $usuarioAuth->idusuario))) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;

            if ($model->validate()) {
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_caratula', $model->idlegalescaratula, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente la carátula.");
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', "Error al borrar la solicitud.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id)
    {
        $hasRolAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($hasRolAdminGeneral) {
            $supervisorArea = Mds_legales_caratula::findOne($id);
            if ($supervisorArea) {
                $supervisorArea->deleted_at = null;
                $supervisorArea->idusuario_borra = null;
                if ($supervisorArea->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente la carátula.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar la carátula.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_caratula', $supervisorArea->idlegalescaratula, $supervisorArea->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "La carátula no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function actionSearch_caratula($inputSearch)
    {
        /*
        Esta funcion es para ser llamada por JS.
        */
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = Mds_legales_caratulaController::searchCaratula($inputSearch);
        return json_encode($result);
    }

    public function actionListado_requerimientos($idlegalescaratula)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $listadoRequerimientos = Mds_legales_oficio::getRequerimientosByCaratula($idlegalescaratula);
            return [
                'title' => "Listado de requerimientos asociados a la carátula #$idlegalescaratula",
                'content' => $this->renderAjax('modal_listado_requerimientos', [
                    'idlegalescaratula' => $idlegalescaratula,
                    'listadoRequerimientos' => $listadoRequerimientos,
                ]),
                'footer' => [
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                ]
            ];
        } else {
            return $this->redirect(['/mds_legales_caratula/index']);
        }
    }

    public static function searchCaratula($inputSearch)
    {
        $success = true;
        $message = "";

        $caratulas = Mds_legales_caratula::searchCaratula($inputSearch);

        foreach ($caratulas as &$caratula) {
            $requerimientos = Mds_legales_oficio::getRequerimientosByCaratula($caratula['idlegalescaratula']);
            $caratula['requerimientos'] = $requerimientos;
        }

        $response = [
            'success' => $success,
            'data' => $caratulas,
            'message' => $message,
        ];
        return $response;
    }

    /**
     * Finds the Mds_legales_caratula model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_legales_caratula the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_legales_caratula::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
