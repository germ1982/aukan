<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;

use app\models\Mds_acomp_asistencia;
use app\models\Mds_acomp_asistenciaSearch;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion_tipo;

use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * Mds_acomp_asistenciaController implements the CRUD actions for Mds_acomp_asistencia model.
 */
class Mds_acomp_asistenciaController extends Controller
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
                'only' => ['index', 'view', 'create', 'store', 'update', 'delete', 'dashboard'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'store', 'update', 'delete'],
                        'roles' => [Mds_seg_item::MODULO_ACOMP],
                    ],
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_ACOMP_ASISTENCIA_SEGUIMIENTO
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_acomp_asistencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_acomp_asistenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ACOMP, $usuarioAuth->idusuario);
            $hasOnePermission = $this->hasOnePermission($permissions, "ver");

            if ($hasOnePermission) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_acomp_asistencia', null, array());
                $hasRolGlobal = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
                $hasRolAdminGeneral = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolGlobal' => $hasRolGlobal,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'permissions' => $permissions,
                    'localidadesFiltro' => $this->getFilterLocalidades(),
                    'localidadesIngresoFiltro' => $this->getFilterLocalidadesIngreso(),
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_acomp_asistencia model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ACOMP, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_acomp_asistencia', $id, array());
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_acomp_asistencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

        $nacionalidades = $this->getListNacionalidades();
        $generos = $this->getListGeneros();
        $tiposDocumentos = $this->getListTiposDocumentos();

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ACOMP, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {
            $action = 'create';
            $model = new Mds_acomp_asistencia();

            $hasRolGlobal = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
            $hasRolAdminGeneral = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
            return $this->render('create', [
                'action' => $action,
                'model' => $model,
                'ID_LOCALIDAD_NEUQUEN_CAPITAL' => Mds_acomp_asistencia::ID_LOCALIDAD_NEUQUEN_CAPITAL,
                'localidades' => $this->getListLocalidades(),
                'riesgos' => $this->getListRiesgos(),
                'nacionalidades' => $nacionalidades,
                'tiposDocumentos' => $tiposDocumentos,
                'username' => Yii::$app->user->identity->user,
                'generos' => $generos,
                'hasRolGlobal' => $hasRolGlobal,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'token' => $token
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStore()
    {

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ACOMP, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {

            $model = new Mds_acomp_asistencia();
            $model->created_at = date('Y-m-d H:i:s');
            $model->idusuario_carga = Yii::$app->user->id;

            if (Yii::$app->request->post()) {
                $model->load(Yii::$app->request->post());
                $transaction = Yii::$app->db->beginTransaction();

                $validarFechas = compararFechas($model);

                if ($model['deleted_at'] == '0') {
                    $model->deleted_at = date('Y-m-d H:i:s');
                    $model->idusuario_borra = Yii::$app->user->id;
                } else {
                    $model->deleted_at = null;
                    $model->idusuario_borra = null;
                }

                if ($validarFechas && $model->validate()) {
                    if ($model->periodo_desde) {
                        $periodo_desde = armarDateParaMySql($model->periodo_desde);
                        $periodo_desde = date_create($periodo_desde);
                        $periodo_desde = date_format($periodo_desde, 'Y-m-d');
                        $model->periodo_desde = $periodo_desde;
                    }
                    if ($model->periodo_hasta) {
                        $periodo_hasta = armarDateParaMySql($model->periodo_hasta);
                        $periodo_hasta = date_create($periodo_hasta);
                        $periodo_hasta = date_format($periodo_hasta, 'Y-m-d');
                        $model->periodo_hasta = $periodo_hasta;
                    }
                    if ($model->save()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_acomp_asistencia', $model->idasistencia, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se generó correctamente la solicitud.");
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al guardar la solicitud.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                }
                return $this->redirect(['mds_acomp_asistencia/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_acomp_asistencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ACOMP, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "modifica");
        $hasRolGlobal = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
        $hasRolAdminGeneral = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
        $model = $this->findModel($id);
        if ($hasRolGlobal || $hasRolAdminGeneral || ($hasOnePermission && $model->idusuario_carga == $usuarioAuth->idusuario)) {
            $request = Yii::$app->request;
            $deletedTemporal = $model->deleted_at;

            if ($model->load($request->post())) {
                if (!isset($request->post()['Mds_acomp_asistencia']['deleted_at'])) {
                    $model->deleted_at = 1;
                }

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
                    if ($model->periodo_desde) {
                        $periodo_desde = armarDateParaMySql($model->periodo_desde);
                        $periodo_desde = date_create($periodo_desde);
                        $periodo_desde = date_format($periodo_desde, 'Y-m-d');
                        $model->periodo_desde = $periodo_desde;
                    }
                    if ($model->periodo_hasta) {
                        $periodo_hasta = armarDateParaMySql($model->periodo_hasta);
                        $periodo_hasta = date_create($periodo_hasta);
                        $periodo_hasta = date_format($periodo_hasta, 'Y-m-d');
                        $model->periodo_hasta = $periodo_hasta;
                    }

                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_acomp_asistencia', $model->idasistencia, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se generó correctamente la solicitud.");
                        return $this->redirect(['mds_acomp_asistencia/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al generar la solicitud.");
                    }
                }
            } else {

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

                $nacionalidades = $this->getListNacionalidades();
                $generos = $this->getListGeneros();
                $tiposDocumentos = $this->getListTiposDocumentos();

                $action = 'update';
                if ($model->deleted_at !== null) {
                    $model->deleted_at = 0;
                } else {
                    $model->deleted_at = 1;
                }

                return $this->render('update', [
                    'action' => $action,
                    'model' => $model,
                    'localidades' => $this->getListLocalidades(),
                    'riesgos' => $this->getListRiesgos(),
                    'tiposDocumentos' => $tiposDocumentos,
                    'username' => Yii::$app->user->identity->user,
                    'nacionalidades' => $nacionalidades,
                    'generos' => $generos,
                    'hasRolGlobal' => $hasRolGlobal,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'token' => $token
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_acomp_asistencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = Yii::$app->user->id;

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ACOMP, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "baja");
        if ($hasOnePermission || (($model['idusuario_carga'] === $usuarioAuth->idusuario))) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente la asistencia.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_acomp_asistencia', $model->idasistencia, $model->getAttributes());
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', "Error al borrar la asistencia.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la asistencia.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $hasRolGlobal = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
        $hasRolAdminGeneral = $this->hasRolGlobal(Mds_acomp_asistencia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
        if ($hasRolGlobal || $hasRolAdminGeneral) {
            $asistencia = Mds_acomp_asistencia::findOne($id);
            if ($asistencia) {
                $asistencia->deleted_at = null;
                $asistencia->idusuario_borra = null;
                if ($asistencia->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente la asistencia.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar la asistencia.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_acomp_asistencia', $asistencia->idasistencia, $asistencia->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "La asistencia no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function actionDetalle_asistencia($id)
    {
        //Genera un PDF con el detalle de la asistencia para imprimirla

        $usuarioAuth = Yii::$app->user->identity;

        $model = $this->findModel($id);
        $content = $this->renderPartial('reporte_detalle_asistencia', [
            'model' => $model,
        ]);
        $dateToday = date('d/m/Y H:i:s');

        $usuarioImprimeApellido = mb_strtoupper($usuarioAuth->apellido);
        $usuarioImprimeNombre = mb_strtoupper($usuarioAuth->nombre);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
            'methods' => [
                'SetTitle' => 'DETALLE DE ASISTENCIA ' . $id,
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime $usuarioImprimeApellido, $usuarioImprimeNombre - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_acomp_asistencia', $id, array());

        return $pdf->render();
    }

    public function actionReporte_asistencia()
    {

        $usuarioAuth = Yii::$app->user->identity;
        $dateToday = date('d/m/Y H:i:s');

        $request = Yii::$app->request;
        $get = $request->get();

        $asistencia = $get['idasistencia'];
        $localidad = $get['localidad'];
        $localidadIngreso = $get['localidadIngreso'];
        $riesgo = $get['riesgo'];
        $deleted_at = $get['activo'];

        $desde = $get['periodo_desde'];
        $hasta = $get['periodo_hasta'];

        $where = "";
        if ($deleted_at == 0) {
            $where = "deleted_at IS NOT NULL";
        } else if ($deleted_at == 1) {
            $where = "deleted_at IS NULL";
        }

        $arrayAsistencias = Mds_acomp_asistencia::find()->where($where)->innerJoinWith('beneficiario', 'mds_acomp_asistencia.idbeneficiario = beneficiario.idpersona')->orderBy(['sds_com_persona.apellido' => SORT_ASC]);
        $arrayAsistencias->joinWith('riesgo');

        if (!empty($asistencia)) {
            $arrayAsistencias->andWhere(['idasistencia' => $asistencia]);
        }
        if (!empty($localidad)) {
            $arrayAsistencias->andWhere(['idlocalidad' => $localidad]);
        }
        if (!empty($localidadIngreso)) {
            $arrayAsistencias->andWhere(['idlocalidad_ingreso' => $localidadIngreso]);
        }
        if (!empty($riesgo)) {
            $arrayAsistencias->andWhere(
                ['like', 'sds_com_configuracion.descripcion', $riesgo]
            );
        }

        if ($desde != "0000-00-00") {
            $arrayAsistencias->andFilterWhere(['>=', 'periodo_desde', $desde]);
        }
        if ($hasta != "0000-00-00") {
            $arrayAsistencias->andFilterWhere(['<=', 'periodo_hasta', $hasta]);
        }


        $content = $this->renderPartial('reporte_asistencia', [
            'arrayAsistencias' => $arrayAsistencias->all(),
        ]);

        $usuarioImprimeApellido = mb_strtoupper($usuarioAuth->apellido);
        $usuarioImprimeNombre = mb_strtoupper($usuarioAuth->nombre);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
            'methods' => [
                'SetTitle' => 'REPORTE ASISTENCIAS',
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime $usuarioImprimeApellido, $usuarioImprimeNombre - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_acomp_asistencia', null, array());

        return $pdf->render();
    }

    public function actionDashboard()
    {

        /*
        Cantidad de registros creados (activos)
        */

        $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
        $fechaFin = null;
        $fechaFinOriginal = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;
        if ($fechaFinOriginal) {
            $fechaFin = date_create($fechaFinOriginal);
            $fechaFin = $fechaFin->modify('+1 day');
            $fechaFin = date_format($fechaFin, 'Y-m-d');
        }

        $model = new Mds_acomp_asistencia();
        $where = "deleted_at IS NULL";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND created_at >= '$fechaInicio' AND created_at <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND created_at >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND created_at <= '$fechaFin'";
        }
        $totalRegistros = $model->find()->where($where)->all();

        $modelAsistencia = new Mds_acomp_asistencia();
        $idConfiguracionRiesgo = Sds_com_configuracion_tipo::ACOMP_RIESGO;
        $arrayLocalidades = $modelAsistencia->find()->select(['localidad.idlocalidad', 'localidad.descripcion'])->join("inner join", "sds_com_localidad as localidad", "localidad.idlocalidad = mds_acomp_asistencia.idlocalidad")->where($where)->groupBy(['localidad.idlocalidad'])->asArray()->all();
        $arrayLocalidadesIngreso = $modelAsistencia->find()->select(['localidad.idlocalidad', 'localidad.descripcion'])->join("inner join", "sds_com_localidad as localidad", "localidad.idlocalidad = mds_acomp_asistencia.idlocalidad_ingreso")->where($where)->groupBy(['localidad.idlocalidad'])->asArray()->all();
        $arrayRiesgo = $model->find()->select(['idconfiguracion', 'descripcion'])->join("inner join", "sds_com_configuracion as configuracion", "configuracion.idconfiguracion = mds_acomp_asistencia.idriesgo")->where("$where AND idconfiguraciontipo = $idConfiguracionRiesgo")->groupBy(['configuracion.idconfiguracion'])->asArray()->all();


        foreach ($totalRegistros as $registro) {
            $indexLocalidades = 0;
            $flagLocalidades = true;
            while ($flagLocalidades && $indexLocalidades < count($arrayLocalidades)) {
                $arrayLocalidades[$indexLocalidades]['titulo'] = 'Localidad';
                $arrayLocalidades[$indexLocalidades]['cantidadRegistros'] = isset($arrayLocalidades[$indexLocalidades]['cantidadRegistros']) ? $arrayLocalidades[$indexLocalidades]['cantidadRegistros'] :  0;
                if ($registro['idlocalidad'] == $arrayLocalidades[$indexLocalidades]['idlocalidad']) {
                    $arrayLocalidades[$indexLocalidades]['cantidadRegistros']++;
                    $flagLocalidades = false;
                }
                $indexLocalidades++;
            }

            $indexLocalidadesIngreso = 0;
            $flagLocalidadesIngreso = true;
            while ($flagLocalidadesIngreso && $indexLocalidadesIngreso < count($arrayLocalidadesIngreso)) {
                $arrayLocalidadesIngreso[$indexLocalidadesIngreso]['titulo'] = 'Localidad Ingreso';
                $arrayLocalidadesIngreso[$indexLocalidadesIngreso]['cantidadRegistros'] = isset($arrayLocalidadesIngreso[$indexLocalidadesIngreso]['cantidadRegistros']) ? $arrayLocalidadesIngreso[$indexLocalidadesIngreso]['cantidadRegistros'] :  0;
                if ($registro['idlocalidad_ingreso'] == $arrayLocalidadesIngreso[$indexLocalidadesIngreso]['idlocalidad']) {
                    $arrayLocalidadesIngreso[$indexLocalidadesIngreso]['cantidadRegistros']++;
                    $flagLocalidadesIngreso = false;
                }
                $indexLocalidadesIngreso++;
            }

            $indexRiesgo = 0;
            $flagRiesgo = true;
            while ($flagRiesgo && $indexRiesgo < count($arrayRiesgo)) {
                $arrayRiesgo[$indexRiesgo]['titulo'] = 'Riesgo';
                $arrayRiesgo[$indexRiesgo]['cantidadRegistros'] = isset($arrayRiesgo[$indexRiesgo]['cantidadRegistros']) ? $arrayRiesgo[$indexRiesgo]['cantidadRegistros'] :  0;
                if ($registro['idriesgo'] == $arrayRiesgo[$indexRiesgo]['idconfiguracion']) {
                    $arrayRiesgo[$indexRiesgo]['cantidadRegistros']++;
                    $flagRiesgo = false;
                }
                $indexRiesgo++;
            }
        }

        $arrayIndicadores = array_merge($arrayLocalidades, $arrayLocalidadesIngreso, $arrayRiesgo);

        return $this->render('dashboard/index', [
            'totalRegistros' => $totalRegistros,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFinOriginal,
            'arrayIndicadores' => $arrayIndicadores,
        ]);
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_acomp_asistencia_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    /**
     * Finds the Mds_acomp_asistencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_acomp_asistencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_acomp_asistencia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListLocalidades()
    {
        //Busqueda localidades
        $localidades = Sds_com_localidad::find()->where(['idprovincia' => Mds_acomp_asistencia::ID_PROVINCIA_NEUQUEN, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');

        return $localidades;
    }

    protected function getListRiesgos()
    {
        //Busqueda localidades
        $listaRiesgos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Mds_acomp_asistencia::CONFIGURACION_TIPO_RIESGO, 'activo' => 1])->asArray()->all();
        $listaRiesgos = ArrayHelper::map($listaRiesgos, 'idconfiguracion', 'descripcion');
        return $listaRiesgos;
    }


    protected function getFilterLocalidades()
    {
        //Busqueda localidades
        $localidadesFiltro = Sds_com_localidad::findBySql(
            "SELECT idasistencia, 
                localidad.idlocalidad as loc_idlocalidad, 
                localidad.descripcion as loc_descripcion, 
                localidad.idprovincia as loc_idprovincia 
                FROM mds_acomp_asistencia asistencia 
                INNER JOIN sds_com_localidad localidad 
                ON asistencia.idlocalidad = localidad.idlocalidad 
                WHERE asistencia.deleted_at IS NULL AND asistencia.idlocalidad
                IN (SELECT idlocalidad FROM sds_com_localidad WHERE activo = 1)
                ORDER BY loc_descripcion ASC
                "
        )->asArray()->all();

        $localidadesFiltro = ArrayHelper::map($localidadesFiltro, 'loc_idlocalidad', 'loc_descripcion');
        return $localidadesFiltro;
    }

    protected function getFilterLocalidadesIngreso()
    {
        //Busqueda localidades
        $localidadesFiltro = Sds_com_localidad::findBySql(
            "SELECT idasistencia, 
            localidad.idlocalidad as loc_idlocalidad, 
            localidad.descripcion as loc_descripcion, 
            localidad.idprovincia as loc_idprovincia 
            FROM mds_acomp_asistencia asistencia 
            INNER JOIN sds_com_localidad localidad 
            ON asistencia.idlocalidad_ingreso = localidad.idlocalidad 
            WHERE asistencia.deleted_at IS NULL AND asistencia.idlocalidad_ingreso 
            IN (SELECT idlocalidad FROM sds_com_localidad WHERE activo = 1)
            ORDER BY loc_descripcion ASC
            "
        )->asArray()->all();

        $localidadesFiltro = ArrayHelper::map($localidadesFiltro, 'loc_idlocalidad', 'loc_descripcion');
        return $localidadesFiltro;
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

    protected function getListNacionalidades()
    {
        //Busqueda nacionalidades
        $nacionalidades = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $nacionalidades = ArrayHelper::map($nacionalidades, 'idconfiguracion', 'descripcion');
        return $nacionalidades;
    }

    protected function getListGeneros()
    {
        //Busqueda generos
        $generos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_GENERO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $generos = ArrayHelper::map($generos, 'idconfiguracion', 'descripcion');
        return $generos;
    }
    protected function getListTiposDocumentos()
    {
        //Busqueda tipos de documentos
        $tipos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_TIPO_DOC, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $tipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $tipos;
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

    $fechaDesde = armarDateParaComparacion($model->periodo_desde);

    $fechaHasta = armarDateParaComparacion($model->periodo_hasta);

    if ($fechaHasta > $fechaDesde) {
        $comparacion = true;
    }

    return $comparacion;
}
