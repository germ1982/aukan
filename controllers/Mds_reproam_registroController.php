<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;
use app\models\Mds_reproam_registro;
use app\models\Mds_reproam_registroSearch;
use app\models\Mds_reproam_mandato;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Sds_com_barrio;
use app\models\Mds_sys_log;
use app\models\Mds_legales_archivo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_com_configuracion_tipo;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use DateTime;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

/**
 * Mds_reproam_registroController implements the CRUD actions for Mds_reproam_registro model.
 */
class Mds_reproam_registroController extends Controller
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
                        'roles' => [Mds_seg_item::MODULO_REPROAM],
                    ],
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_REPROAM_SEGUIMIENTO
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_reproam_registro models.
     * @return mixed
     */
    public function actionIndex($fechaInicio = null, $fechaFin = null, $tipo = null, $idzona = null, $idlocalidad = null)
    {
        $searchModel = new Mds_reproam_registroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $fechaInicio, $fechaFin, $tipo, $idzona, $idlocalidad);
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
            $hasOnePermission = $this->hasOnePermission($permissions, "ver");

            if ($hasOnePermission) {

                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_registro', null, array());

                $hasRolGlobal = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_GLOBAL, $usuarioAuth->idusuario);
                $hasRolAdminGeneral = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolGlobal' => $hasRolGlobal,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'permissions' => $permissions,
                    'barriosFiltro' => $this->getFilterBarrios(),
                    'localidadesFiltro' => $this->getFilterLocalidades(),
                    'zonasFiltro' => $this->getFilterZonas(),
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_reproam_registro model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_registro', $id, array());
        if ($hasOnePermission) {
            return $this->render('view', [
                'model' => $this->findModel($id),
                'mandatos' => $this->getListMandatos($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_reproam_registro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {
            $action = 'create';
            $model = new Mds_reproam_registro();

            return $this->render('create', [
                'action' => $action,
                'model' => $model,
                'ID_LOCALIDAD_NEUQUEN_CAPITAL' => Mds_reproam_registro::ID_LOCALIDAD_NEUQUEN_CAPITAL,
                'listaZonas' => $this->getListZonas(),
                'localidades' => $this->getListLocalidades(),
                'puedeEliminar' => true,
                'listaSituacionHabitacional' => $this->getListSituacionHabitacional(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCreate_barrio($idlocalidad)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasPermissionCreate = $this->hasOnePermission($permissions, "alta");
        $hasPermissionModif = $this->hasOnePermission($permissions, "modifica");

        if ($hasPermissionCreate || $hasPermissionModif) {
            $model_barrio = new Sds_com_barrio();
            $model_barrio->idlocalidad = $idlocalidad;
            $request = Yii::$app->request;
            if ($model_barrio->load($request->post())) {
                if ($model_barrio->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_barrio', $model_barrio->idbarrio, $model_barrio->getAttributes());
                    echo 1;
                } else {
                    return $this->renderAjax('./create_barrio', [
                        'model' => $model_barrio,
                        'botones' => true,
                    ]);
                }
            } else {
                return $this->renderAjax('./create_barrio', [
                    'model' => $model_barrio,
                    'botones' => true,
                ]);
            }
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
            $model = new Mds_reproam_registro();
            $model->created_at = date('Y-m-d H:i:s');
            $model->idusuario_carga = Yii::$app->user->id;

            if (Yii::$app->request->post()) {
                $model->load(Yii::$app->request->post());
                $transaction = Yii::$app->db->beginTransaction();

                if ($model['personeria_juridica'] == '0') {
                    $model->personeria_juridica_numero = null;
                    $model->personeria_juridica_resolucion = null;
                    $model->personeria_juridica_fecha_vencimiento = null;
                }

                if ($model['inscripto'] == '0') {
                    $model->numero_legajo_reproam = null;
                }

                if ($model['entrega_constancia_inscripcion'] == '0') {
                    $model->entrega_constancia_inscripcion_nombre = null;
                }

                if ($model['deleted_at'] == '0') {
                    $model->deleted_at = date('Y-m-d H:i:s');
                    $model->idusuario_borra = Yii::$app->user->id;
                } else {
                    $model->deleted_at = null;
                    $model->idusuario_borra = null;
                }

                if ($model->validate()) {
                    if ($model->personeria_juridica_fecha_vencimiento) {
                        $personeria_juridica_fecha_vencimiento = armarDateParaMySql($model->personeria_juridica_fecha_vencimiento);
                        $personeria_juridica_fecha_vencimiento = date_create($personeria_juridica_fecha_vencimiento);
                        $personeria_juridica_fecha_vencimiento = date_format($personeria_juridica_fecha_vencimiento, 'Y-m-d');
                        $model->personeria_juridica_fecha_vencimiento = $personeria_juridica_fecha_vencimiento;
                    }
                    if ($model->save()) {
                        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                            $this->storeAdjuntoOtros($adjuntos, $model);
                        }

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_reproam_registro', $model->idregistro, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se generó correctamente la solicitud.");
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al guardar la solicitud.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                }
                return $this->redirect(['mds_reproam_registro/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_reproam_registro model.
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
        $hasRolAdminGeneral = $this->hasRolGlobal(Mds_reproam_registro::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "modifica");
        $model = $this->findModel($id);
        if ($hasRolGlobal || $hasRolAdminGeneral || ($hasOnePermission && ($model['idusuario_carga'] === $usuarioAuth->idusuario))) {
            $request = Yii::$app->request;
            $barrios = $this->getListBarrios($model->idlocalidad);
            $deletedTemporal = $model->deleted_at;

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

                if ($model['personeria_juridica'] == '0') {
                    $model->personeria_juridica_numero = null;
                    $model->personeria_juridica_resolucion = null;
                    $model->personeria_juridica_fecha_vencimiento = null;
                }

                if ($model['inscripto'] == '0') {
                    $model->numero_legajo_reproam = null;
                }

                if ($model['entrega_constancia_inscripcion'] == '0') {
                    $model->entrega_constancia_inscripcion_nombre = null;
                }

                if ($model->validate()) {
                    if ($model->personeria_juridica_fecha_vencimiento) {
                        $personeria_juridica_fecha_vencimiento = armarDateParaMySql($model->personeria_juridica_fecha_vencimiento);
                        $personeria_juridica_fecha_vencimiento = date_create($personeria_juridica_fecha_vencimiento);
                        $personeria_juridica_fecha_vencimiento = date_format($personeria_juridica_fecha_vencimiento, 'Y-m-d');
                        $model->personeria_juridica_fecha_vencimiento = $personeria_juridica_fecha_vencimiento;
                    }

                    if ($model->save()) {

                        if (isset(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) && Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) {
                            $adjuntosEliminados = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados'], true);
                            foreach ($adjuntosEliminados as $idAdjunto) {
                                $modelArchivo = Mds_legales_archivo::findOne($idAdjunto);
                                $modelArchivo->activo = 0;
                                $modelArchivo->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_archivo', $idAdjunto, $modelArchivo->getAttributes());
                            }
                        }

                        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                            $this->storeAdjuntoOtros($adjuntos, $model);
                        }

                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_reproam_registro', $model->idregistro, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se generó correctamente la solicitud.");
                        return $this->redirect(['mds_reproam_registro/index']);
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
                $puedeEliminar = (($model['idusuario_carga'] === $usuarioAuth->idusuario) || $hasRolGlobal || $hasRolAdminGeneral);
                return $this->render('update', [
                    'action' => $action,
                    'model' => $model,
                    'barrios' => $barrios,
                    'listaZonas' => $this->getListZonas(),
                    'localidades' => $this->getListLocalidades(),
                    'puedeEliminar' => $puedeEliminar,
                    'listaSituacionHabitacional' => $this->getListSituacionHabitacional(),
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_reproam_registro model.
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

        if ($hasOnePermission || ($model['idusuario_carga'] === $usuarioAuth->idusuario)) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente el registro.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_reproam_registro', $model->idregistro, $model->getAttributes());
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
        $registro = Mds_reproam_registro::findOne($id);
        if ($registro) {
            if ($hasRolGlobal || $hasRolAdminGeneral) {
                $registro->deleted_at = null;
                $registro->idusuario_borra = null;
                if ($registro->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente el registro.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el registro.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_reproam_registro', $registro->idregistro, $registro->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "El registro no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function actionDetalle_registro($idregistro)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");

        if ($hasOnePermission) {
            $model = $this->findModel($idregistro);
            $mandatos = $this->getListMandatos($idregistro);
            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');


            $content = $this->renderPartial('reporte_detalle_registro', [
                'model' => $model,
                'mandatos' => $mandatos,
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'marginBottom' => 20,
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
                'methods' => [
                    'SetTitle' => 'DETALLE DE REGISTRO #' . $idregistro,
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left;'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_registro', $idregistro, array());

            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReporte_vencimiento()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_REPROAM, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');

            $fechaActual = new DateTime("now");
            $fv = date_format($fechaActual, 'Y-m-d');
            $fechaActual->modify(Mds_reproam_registro::DIAS_VENCIMIENTO_PERSONERIA);
            $fvp = date_format($fechaActual, 'Y-m-d');

            $arrayRegistros = Mds_reproam_registro::find()->where(['deleted_at' => null]);
            $arrayRegistros2 = Mds_reproam_registro::find()->where(['deleted_at' => null]);
            $arrayRegistros3 = Mds_reproam_registro::find()->where(['deleted_at' => null]);

            $arrayRegistros->andFilterWhere(['>=', 'personeria_juridica_fecha_vencimiento', $fv])->asArray()->all();
            $venproximo = $arrayRegistros->andFilterWhere(['<=', 'personeria_juridica_fecha_vencimiento', $fvp])->orderBy(['personeria_juridica_fecha_vencimiento' => SORT_ASC])->asArray()->all();

            $novencida = $arrayRegistros2->andFilterWhere(['>', 'personeria_juridica_fecha_vencimiento', $fvp])->orderBy(['personeria_juridica_fecha_vencimiento' => SORT_ASC])->asArray()->all();
            $vencida = $arrayRegistros3->andFilterWhere(['<', 'personeria_juridica_fecha_vencimiento', $fv])->orderBy(['personeria_juridica_fecha_vencimiento' => SORT_ASC])->asArray()->all();

            $content = $this->renderPartial('reporte_vencimientos_registro', [
                'venproximo' => $venproximo,
                'novencida' => $novencida,
                'vencida' => $vencida,

            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 5px .5rem 5px .5rem}',
                'methods' => [
                    'SetTitle' => 'REPORTE VENCIMIENTOS',
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb} </p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_registro', null, array());

            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDashboard($inscripto = null)
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

        $model = new Mds_reproam_registro();
        $where = "deleted_at IS NULL";
        if (!is_null($inscripto)) {
            $where .= " AND inscripto = '$inscripto'";
        }
        if ($fechaInicio && $fechaFin) {
            $where .= " AND created_at >= '$fechaInicio' AND created_at <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND created_at >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND created_at <= '$fechaFin'";
        }
        $totalRegistros = $model->find()->where($where)->all();

        $modelLocalidad = new Mds_reproam_registro();
        $idConfiguracionZona = Sds_com_configuracion_tipo::REPROAM_ZONA;
        $arrayLocalidades = $modelLocalidad->find()->select(['localidad.idlocalidad', 'localidad.descripcion'])->join("inner join", "sds_com_localidad as localidad", "localidad.idlocalidad = mds_reproam_registro.idlocalidad")->where($where)->groupBy(['localidad.idlocalidad'])->asArray()->all();
        $arrayZona = $model->find()->select(['idconfiguracion', 'descripcion'])->join("inner join", "sds_com_configuracion as configuracion", "configuracion.idconfiguracion = mds_reproam_registro.idzona")->where("$where AND idconfiguraciontipo = $idConfiguracionZona")->groupBy(['configuracion.idconfiguracion'])->asArray()->all();

        $totalConPersoneriaJuridica = 0;
        $totalSinPersoneriaJuridica = 0;
        $totalConConstancia = 0;
        $totalSinConstancia = 0;
        foreach ($totalRegistros as $registro) {
            $indexLocalidades = 0;
            $flagLocalidades = true;
            while ($flagLocalidades && $indexLocalidades < count($arrayLocalidades)) {
                $arrayLocalidades[$indexLocalidades]['titulo'] = 'Localidad';
                $arrayLocalidades[$indexLocalidades]['cantidadRegistros'] = isset($arrayLocalidades[$indexLocalidades]['cantidadRegistros']) ? $arrayLocalidades[$indexLocalidades]['cantidadRegistros'] :  0;
                if ($registro['idlocalidad'] == $arrayLocalidades[$indexLocalidades]['idlocalidad']) {
                    $arrayLocalidades[$indexLocalidades]['cantidadRegistros']++;
                    $arrayLocalidades[$indexLocalidades]['url'] = "&idlocalidad={$arrayLocalidades[$indexLocalidades]['idlocalidad']}";
                    $flagLocalidades = false;
                }
                $indexLocalidades++;
            }

            $indexZona = 0;
            $flagZona = true;
            while ($flagZona && $indexZona < count($arrayZona)) {
                $arrayZona[$indexZona]['titulo'] = 'Zona';
                $arrayZona[$indexZona]['cantidadRegistros'] = isset($arrayZona[$indexZona]['cantidadRegistros']) ? $arrayZona[$indexZona]['cantidadRegistros'] :  0;
                if ($registro['idzona'] == $arrayZona[$indexZona]['idconfiguracion']) {
                    $arrayZona[$indexZona]['cantidadRegistros']++;
                    $arrayZona[$indexZona]['url'] = "&idzona={$arrayZona[$indexZona]['idconfiguracion']}";
                    $flagZona = false;
                }
                $indexZona++;
            }

            if ($registro['personeria_juridica'] == 1) {
                $totalConPersoneriaJuridica++;
            } else {
                $totalSinPersoneriaJuridica++;
            }

            if ($registro['entrega_constancia_inscripcion'] == 1) {
                $totalConConstancia++;
            } else {
                $totalSinConstancia++;
            }
        }

        $arrayIndicadores = array_merge($arrayLocalidades, $arrayZona);

        $arrayTotales =
            [
                [
                    'titulo' => "CON PERSONERÍA JURÍDICA",
                    'total' => $totalConPersoneriaJuridica,
                    'tipo' => "con_personeria",
                ],
                [
                    'titulo' => "SIN PERSONERÍA JURÍDICA",
                    'total' => $totalSinPersoneriaJuridica,
                    'tipo' => "sin_personeria",
                ],
                [
                    'titulo' => "CON CONSTANCIA ENTREGADA",
                    'total' => $totalConConstancia,
                    'tipo' => "con_constancia",
                ],
                [
                    'titulo' => "SIN CONSTANCIA ENTREGADA",
                    'total' => $totalSinConstancia,
                    'tipo' => "sin_constancia",
                ],
            ];

        return $this->render('dashboard/index', [
            'totalRegistros' => $totalRegistros,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFinOriginal,
            'arrayIndicadores' => $arrayIndicadores,
            'arrayTotales' => $arrayTotales,
            'inscripto' => $inscripto,
        ]);
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reproam_registro_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    /**
     * Finds the Mds_reproam_registro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_reproam_registro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_reproam_registro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListBarrios($idLocalidad)
    {
        $barrios = Sds_com_barrio::find()->where(['idlocalidad' => $idLocalidad, "activo" => 1])->orderBy(['nombre' => SORT_ASC])->asArray()->all();
        $barrios = ArrayHelper::map($barrios, 'idbarrio', 'nombre');
        return $barrios;
    }

    protected function getListMandatos($idregistro)
    {
        $mandatos = Mds_reproam_mandato::find()->where(['idregistro' => $idregistro, 'deleted_at' => null])->orderBy(['fecha_desde' => SORT_DESC])->asArray()->all();
        foreach ($mandatos as &$mandato) {
            $mandato['fecha_desde'] = armarDateParaMandato($mandato['fecha_desde']);
            $mandato['fecha_hasta'] = armarDateParaMandato($mandato['fecha_hasta']);
        }
        return $mandatos;
    }

    protected function getListZonas()
    {
        //Busqueda localidades
        $listaZonasDB = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Mds_reproam_registro::CONFIGURACION_TIPO_ZONAS, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        //$listasZonasVacio= Array ([ "idconfiguracion" => "null", "descripcion" => "Seleccione opción ..."]);
        //$listaZonasMerge = array_merge($listasZonasVacio, $listaZonasDB);
        $listaZonas = ArrayHelper::map($listaZonasDB, 'idconfiguracion', 'descripcion');
        return $listaZonas;
    }

    protected function getListLocalidades()
    {
        //Busqueda localidades
        $localidades = Sds_com_localidad::find()->where(['idprovincia' => Mds_reproam_registro::ID_PROVINCIA_NEUQUEN, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');
        return $localidades;
    }

    protected function getListSituacionHabitacional()
    {
        $listaSituacionHab = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::REPROAM_SITUACION_HABITACIONAL, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $listaSituacion = ArrayHelper::map($listaSituacionHab, 'idconfiguracion', 'descripcion');
        return $listaSituacion;
    }

    protected function getFilterLocalidades()
    {
        //Busqueda localidades
        $localidadesFiltro = Sds_com_localidad::findBySql(
            "SELECT idregistro, 
                localidad.idlocalidad as loc_idlocalidad, 
                localidad.descripcion as loc_descripcion 
                FROM mds_reproam_registro registro 
                INNER JOIN sds_com_localidad localidad 
                ON registro.idlocalidad = localidad.idlocalidad 
                WHERE registro.deleted_at IS NULL AND registro.idlocalidad 
                IN (SELECT idlocalidad FROM sds_com_localidad WHERE activo = 1)
                ORDER BY loc_descripcion ASC
                "
        )->asArray()->all();

        $localidadesFiltro = ArrayHelper::map($localidadesFiltro, 'loc_idlocalidad', 'loc_descripcion');
        return $localidadesFiltro;
    }

    protected function getFilterZonas()
    {
        //Busqueda localidades
        $zonasFiltro = Sds_com_barrio::findBySql(
            "SELECT idregistro, 
                zona.idconfiguracion as zona_idconfiguracion, 
                zona.descripcion as zona_descripcion 
                FROM mds_reproam_registro registro 
                INNER JOIN sds_com_configuracion zona 
                ON registro.idzona = zona.idconfiguracion 
                WHERE registro.deleted_at IS NULL AND registro.idzona 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY zona_descripcion ASC
                "
        )->asArray()->all();

        $zonasFiltro = ArrayHelper::map($zonasFiltro, 'zona_idconfiguracion', 'zona_descripcion');
        return $zonasFiltro;
    }

    protected function getFilterBarrios()
    {
        //Busqueda localidades
        $barriosFiltro = Sds_com_barrio::findBySql(
            "SELECT idregistro, 
                barrio.idbarrio as barrio_idbarrio, 
                barrio.nombre as barrio_nombre 
                FROM mds_reproam_registro registro 
                INNER JOIN sds_com_barrio barrio 
                ON registro.idbarrio = barrio.idbarrio 
                WHERE registro.deleted_at IS NULL AND registro.idbarrio 
                IN (SELECT idbarrio FROM sds_com_barrio WHERE activo = 1)
                ORDER BY barrio_nombre ASC
                "
        )->asArray()->all();

        $barriosFiltro = ArrayHelper::map($barriosFiltro, 'barrio_idbarrio', 'barrio_nombre');
        return $barriosFiltro;
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

    public function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathReproam = __DIR__ . '/../web/uploads/reproam/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "registro_{$model->idregistro}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathReproam  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_reproam_registro', 'registro', $model->idregistro, $nameFile);
            }
        }
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
