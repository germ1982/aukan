<?php

namespace app\controllers;

use Yii;
use app\models\Mds_legales_supervisor_area;
use app\models\Mds_legales_supervisor_areaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\Mds_legales_oficio;

/**
 * Mds_legales_supervisor_areaController implements the CRUD actions for Mds_legales_supervisor_area model.
 */
class Mds_legales_supervisor_areaController extends Controller
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
                'only' => ['index', 'view', 'create', 'store', 'update', 'delete', 'reactivate'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'store', 'update', 'delete', 'reactivate'],
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_SUPERVISOR_AREA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Mds_legales_supervisor_area models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $searchModel = new Mds_legales_supervisor_areaSearch();
            $hasRolAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $hasRolAdminGeneral);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_supervisor_area', null, array());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'filterAreas' => $this->getFilterAreaOficio(),
                'filterUsuarios' => $this->getFilterUsuarios(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_legales_supervisor_area model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_supervisor_area', $id, array());
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_legales_supervisor_area model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mds_legales_supervisor_area();
        $action = 'create';
        $areas = $this->getListAreas();
        $usuariosSupervisores = $this->getListUsuariosSupervisores();

        return $this->render('create', [
            'action' => $action,
            'model' => $model,
            'areas' => $areas,
            'usuariosSupervisores' => $usuariosSupervisores,
            'puedeEliminar' => true,
        ]);
    }

    public function actionStore()
    {
        $model = new Mds_legales_supervisor_area();
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_supervisor_area', $model->idlegalessupervisorarea, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se generó correctamente el/la supervisor/a de área.");
                    return $this->redirect(['mds_legales_supervisor_area/index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al guardar el supervisor/a de área.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos del supervisor/a de área.");
            }
        }
    }

    /**
     * Updates an existing Mds_legales_supervisor_area model.
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

            $areas = $this->getListAreas();
            $usuariosSupervisores = $this->getListUsuariosSupervisores();
            if ($model->load($request->post())) {
                if (!isset($request->post()['Mds_legales_supervisor_area']['deleted_at'])) {
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
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_supervisor_area', $model->idlegalessupervisorarea, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se actualizó correctamente el supervisor/a de área.");
                        return $this->redirect(['mds_legales_supervisor_area/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al actualizar el supervisor/a de área.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos del supervisor/a de área.");
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
                    'areas' => $areas,
                    'usuariosSupervisores' => $usuariosSupervisores,
                    'puedeEliminar' => $puedeEliminar,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_legales_supervisor_area model.
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_supervisor_area', $model->idlegalessupervisorarea, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente el supervisor/a de área.");
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
            $supervisorArea = Mds_legales_supervisor_area::findOne($id);
            if ($supervisorArea) {
                $supervisorArea->deleted_at = null;
                $supervisorArea->idusuario_borra = null;
                if ($supervisorArea->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente el supervisor/a de área.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el supervisor/a de área.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_supervisor_area', $supervisorArea->idlegalessupervisorarea, $supervisorArea->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "El supervisor/a de área no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mds_legales_supervisor_area model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_legales_supervisor_area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_legales_supervisor_area::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getFilterAreaOficio()
    {
        //Busqueda areas existentes
        $filtro = Mds_legales_supervisor_area::findBySql(
            "SELECT
            configuracion.idconfiguracion as idarea,
            configuracion.descripcion as descripciontipo
            FROM mds_legales_supervisor_area supervisor_area
            INNER JOIN sds_com_configuracion configuracion
            ON supervisor_area.idarea = configuracion.idconfiguracion
            WHERE supervisor_area.deleted_at IS NULL
            AND (supervisor_area.idarea IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1) )
            ORDER BY descripciontipo ASC
        "
        )->asArray()->all();

        $areasFiltro = ArrayHelper::map($filtro, 'idarea', 'descripciontipo');
        return $areasFiltro;
    }

    protected function getFilterUsuarios()
    {
        //Busqueda areas existentes
        $filtro = Mds_legales_supervisor_area::findBySql(
            "SELECT
            usuario.idusuario as idusuario,
            UPPER(CONCAT(usuario.apellido,', ',usuario.nombre)) as nombre_usuario
            FROM mds_legales_supervisor_area supervisor_area
            INNER JOIN mds_seg_usuario usuario
            ON supervisor_area.idusuario = usuario.idusuario
            WHERE supervisor_area.deleted_at IS NULL
            AND (supervisor_area.idusuario IN (SELECT idusuario FROM mds_seg_usuario WHERE activo = 1) )
            ORDER BY nombre_usuario ASC
        "
        )->asArray()->all();

        $usuariosFiltro = ArrayHelper::map($filtro, 'idusuario', 'nombre_usuario');
        return $usuariosFiltro;
    }

    protected function getListAreas()
    {
        //Busqueda localidades
        $areas = Sds_com_configuracion::find('idconfiguracion', 'descripcion')->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::LEGALES_AREA_TIPO])->andWhere(['=', 'activo', 1])->orderBy(['descripcion' => SORT_ASC])->all();
        $areas = ArrayHelper::map($areas, 'idconfiguracion', 'descripcion');

        return $areas;
    }

    protected function getListUsuariosSupervisores()
    {
        //Busqueda localidades
        $usuariosSupervisores = Mds_legales_oficio::getUsuariosSegunRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $usuariosSupervisores = ArrayHelper::map($usuariosSupervisores, 'idusuario', 'nombre_apellido');

        return $usuariosSupervisores;
    }
}
