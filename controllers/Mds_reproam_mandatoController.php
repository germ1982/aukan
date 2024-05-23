<?php

namespace app\controllers;

use Yii;
use app\models\Mds_reproam_mandato;
use app\models\Mds_reproam_mandatoSearch;
use app\models\Mds_reproam_registro;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_com_localidad;
use app\models\Sds_com_barrio;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;




/**
 * Mds_reproam_mandatoController implements the CRUD actions for Mds_reproam_mandato model.
 */
class Mds_reproam_mandatoController extends Controller
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
                'only' => ['index', 'view', 'create', 'store', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'store', 'update', 'delete'],
                        'roles' => [Mds_seg_item::MODULO_REPROAM],
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Mds_reproam_mandato models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_reproam_mandatoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
            $hasOnePermission = $this->hasOnePermission($permissions, "ver");

            if ($hasOnePermission) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_mandato', null, array());

                $hasRolGlobal = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
                $hasRolAdminGeneral = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolGlobal' => $hasRolGlobal,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'permissions' => $permissions,
                    'registroFiltro' => $this->getRegistrosFiltros(),
                    'zonasFiltro' => $this->getFilterZonas(),
                    'localidadesFiltro' => $this->getFilterLocalidades(),
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_reproam_mandato model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_mandato', $id, array());

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_reproam_mandato model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {
            $model = new Mds_reproam_mandato();
            $action = 'create';
            $registros  = [];
            $registros = $this->getListRegistros();

            return $this->render('create', [
                'action' => $action,
                'model' => $model,
                'registros' => $registros,
                'puedeEliminar' => true,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStore()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {
            $model = new Mds_reproam_mandato();
            if (Yii::$app->request->post()) {
                $payload = Yii::$app->request->post();
                $model->created_at = date('Y-m-d H:i:s');
                $model->idusuario_carga = Yii::$app->user->id;

                if (Yii::$app->request->isAjax) {
                    $model->idregistro = $payload['idregistro'];
                    $model->fecha_desde = $payload['fecha_desde'];
                    $model->fecha_hasta = $payload['fecha_hasta'];
                    $model->titular = $payload['titular'];
                    $model->observaciones = $payload['observaciones'];
                } else {
                    $model->load(Yii::$app->request->post());
                    $payload['redirect'] = 'mandato';
                }

                if ((isset($model['deleted_at']) && ($model['deleted_at'] == '0')) || (isset($payload['deleted_at']) && ($payload['deleted_at'] == '0'))) {
                    $model->deleted_at = date('Y-m-d H:i:s');
                    $model->idusuario_borra = Yii::$app->user->id;
                } else {
                    $model->deleted_at = null;
                    $model->idusuario_borra = null;
                }

                $transaction = Yii::$app->db->beginTransaction();

                if ($model->fecha_hasta) {
                    $validarFechas = compararFechas($model);
                } else {
                    $validarFechas = true;
                }

                if ($validarFechas && $model->validate()) {
                    if ($model->fecha_desde) {
                        $fecha_desde = armarDateParaMySql($model->fecha_desde);
                        $fecha_desde = date_create($fecha_desde);
                        $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                        $model->fecha_desde = $fecha_desde;
                    }
                    if ($model->fecha_hasta) {
                        $fecha_hasta = armarDateParaMySql($model->fecha_hasta);
                        $fecha_hasta = date_create($fecha_hasta);
                        $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
                        $model->fecha_hasta = $fecha_hasta;
                    }
                    if ($model->save()) {
                        $modelCreado = Mds_reproam_mandato::findOne($model->idmandato)->getAttributes();
                        $modelCreado['fecha_desde'] = armarDateParaMandato($modelCreado['fecha_desde']);
                        $modelCreado['fecha_hasta'] = armarDateParaMandato($modelCreado['fecha_hasta']);
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_reproam_mandato', $model->idmandato, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se generó correctamente la solicitud.");
                        if (isset($payload['redirect']) && $payload['redirect']  == 'mandato') {
                            return $this->redirect(['mds_reproam_mandato/index']);
                        } else {
                            return json_encode(['message' => 'Mandato creado correctamente!', 'id' => $model->idmandato, 'model' =>  (array) $modelCreado]);
                        }
                    } else {
                        $transaction->rollBack();
                        if (Yii::$app->request->isAjax) {
                            return json_encode(['message' => 'ERROR!: Fecha Hasta es anterior a Fecha Desde.']);
                        } else {
                            Yii::$app->session->setFlash('error', "Error al guardar la solicitud.");
                        }
                    }
                } else {
                    if (Yii::$app->request->isAjax) {
                        return json_encode(['message' => 'ERROR!: Fecha Hasta es anterior a Fecha Desde.']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                    }
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_reproam_mandato model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasRolGlobal = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "modifica");
        if ($hasOnePermission) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $registros  = [];
            $deletedTemporal = $model->deleted_at;

            $registros = $this->getListRegistros();

            if ($model->load($request->post())) {
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

                $model->updated_at = date('Y-m-d H:i:s');

                if ($model->validate()) {
                    if ($model->fecha_desde) {
                        $fecha_desde = armarDateParaMySql($model->fecha_desde);
                        $fecha_desde = date_create($fecha_desde);
                        $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                        $model->fecha_desde = $fecha_desde;
                    }
                    if ($model->fecha_hasta) {
                        $fecha_hasta = armarDateParaMySql($model->fecha_hasta);
                        $fecha_hasta = date_create($fecha_hasta);
                        $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
                        $model->fecha_hasta = $fecha_hasta;
                    }

                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_reproam_mandato', $model->idmandato, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se generó correctamente la solicitud.");
                        return $this->redirect(['mds_reproam_mandato/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al generar la solicitud.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                }
            } else {
                $action = 'update';

                if ($model->deleted_at !== null) {
                    $model->deleted_at = 0;
                } else {
                    $model->deleted_at = 1;
                }

                $puedeEliminar = (($model['idusuario_carga'] === $usuarioAuth->idusuario) || $hasRolGlobal);

                return $this->render('update', [
                    'action' => $action,
                    'registros' => $registros,
                    'model' => $model,
                    'puedeEliminar' => $puedeEliminar,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_reproam_mandato model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "baja");
        $model = $this->findModel($id);

        if ($hasOnePermission || (($model['idusuario_carga'] === $usuarioAuth->idusuario))) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;

            if ($model->validate()) {
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_reproam_mandato', $model->idmandato, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente el registro.");
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
        $usuarioAuth = Yii::$app->user->identity;
        $hasRolGlobal = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
        $hasRolAdminGeneral = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
        $mandato = Mds_reproam_mandato::findOne($id);
        if ($mandato) {
            if ($hasRolGlobal || $hasRolAdminGeneral) {
                $mandato->deleted_at = null;
                $mandato->idusuario_borra = null;
                if ($mandato->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente el mandato.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el mandato.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_reproam_mandato', $mandato->idmandato, $mandato->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "El mandato no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mds_reproam_mandato model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_reproam_mandato the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_reproam_mandato::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListRegistros()
    {
        //Busqueda registros
        $registros = Mds_reproam_registro::find()->orderBy(['nombre' => SORT_ASC])->asArray()->all();
        $registros = ArrayHelper::map($registros, 'idregistro', 'nombre');
        return $registros;
    }

    protected function hasOnePermission($permissions, $action)
    {
        $hasOnePermission = false;
        $i = 0;
        while (!$hasOnePermission && $i < count($permissions)) {
            $permission = $permissions[$i];
            $hasOnePermission = $permission[$action];
            $i++;
        }

        return $hasOnePermission;
    }

    protected function hasRolGlobal($idrol, $idusuario)
    {
        $hasRolGlobal = false;
        $roles = Mds_seg_usuario_rol::find()
            ->where(['idusuario' => $idusuario])
            ->andWhere(["idrol" => $idrol])
            ->all();

        if (count($roles) > 0) {
            $hasRolGlobal = true;
        }

        return $hasRolGlobal;
    }

    protected function getRegistrosFiltros()
    {
        //Busqueda registros que se hayan cargado en mandatos
        $registrosFiltro = Mds_reproam_registro::findBySql(
            "SELECT registro.idregistro, 
                registro.nombre as reg_nombre 
                FROM mds_reproam_registro registro 
                INNER JOIN mds_reproam_mandato mandato 
                ON registro.idregistro = mandato.idregistro 
                GROUP BY mandato.idregistro
                ORDER BY registro.nombre ASC
                "
        )->asArray()->all();

        $registrosFiltro = ArrayHelper::map($registrosFiltro, 'idregistro', 'reg_nombre');
        return $registrosFiltro;
    }

    protected function getFilterLocalidades()
    {
        //Busqueda localidades
        $localidadesFiltro = Sds_com_localidad::findBySql(
            "SELECT registro.idregistro, 
                localidad.idlocalidad as loc_idlocalidad, 
                localidad.descripcion as loc_descripcion 
                FROM mds_reproam_registro registro 
                INNER JOIN sds_com_localidad localidad 
                ON registro.idlocalidad = localidad.idlocalidad 
                INNER JOIN mds_reproam_mandato mandato 
                ON registro.idregistro = mandato.idregistro 
                WHERE registro.idlocalidad 
                IN (SELECT idlocalidad FROM sds_com_localidad WHERE activo = 1)
                ORDER BY loc_descripcion ASC
                "
        )->asArray()->all();

        $localidadesFiltro = ArrayHelper::map($localidadesFiltro, 'loc_idlocalidad', 'loc_descripcion');
        return $localidadesFiltro;
    }

    protected function getFilterZonas()
    {
        //Busqueda zonas
        $zonasFiltro = Sds_com_barrio::findBySql(
            "SELECT registro.idregistro, 
                zona.idconfiguracion as zona_idconfiguracion, 
                zona.descripcion as zona_descripcion 
                FROM mds_reproam_registro registro 
                INNER JOIN sds_com_configuracion zona 
                ON registro.idzona = zona.idconfiguracion 
                INNER JOIN mds_reproam_mandato mandato 
                ON registro.idregistro = mandato.idregistro 
                WHERE registro.idzona 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY zona_descripcion ASC
                "
        )->asArray()->all();

        $zonasFiltro = ArrayHelper::map($zonasFiltro, 'zona_idconfiguracion', 'zona_descripcion');
        return $zonasFiltro;
    }
}

function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}

function armarDateParaMandato($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $mes = substr($fecha, 5, 2);
    $dia  = substr($fecha, 8, 2);
    $anio = substr($fecha, 0, 4);
    $DT = "$dia-$mes-$anio";
    return $DT;
}

function armarDateParaComparacion($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $dia  = substr($fecha, 0, 2);
    $mes = substr($fecha, 3, 2);
    $anio = substr($fecha, 6, 4);
    $DT = strtotime("$mes/$dia/$anio");
    return $DT;
}

function compararFechas($model)
{
    $comparacion = false;

    $fechaDesde = armarDateParaComparacion($model->fecha_desde);

    $fechaHasta = armarDateParaComparacion($model->fecha_hasta);

    if ($fechaHasta > $fechaDesde) {
        $comparacion = true;
    }

    return $comparacion;
}
