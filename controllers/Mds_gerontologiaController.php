<?php

namespace app\controllers;

use Yii;
use app\models\Mds_gerontologia;
use app\models\Mds_gerontologia_respuesta;
use app\models\Mds_gerontologiaSearch;

use app\models\Sds_com_configuracion;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_gerontologia_escala;
use app\models\Mds_legales_archivo;
use app\models\Mds_seg_item;

use kartik\mpdf\Pdf;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\AccessRule;

/**
 * Mds_gerontologiaController implements the CRUD actions for Mds_gerontologia model.
 */
class Mds_gerontologiaController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['dashboard'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'store', 'update', 'delete'],
                        'roles' => [Mds_seg_item::MODULO_GERONTOLOGIA],
                    ],
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_GERONTOLOGIA_SEGUIMIENTO
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_gerontologia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_gerontologiaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $usuarioAuth = Yii::$app->user->identity;

        if ($usuarioAuth) {
            $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_GERONTOLOGIA, $usuarioAuth->idusuario);
            $hasOnePermission = $this->hasOnePermission($permissions, "ver");

            if ($hasOnePermission) {

                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_gerontologia', null, array());

                $hasRolAdmin = $this->hasRol(Mds_gerontologia::ID_ROL_ADMIN, $usuarioAuth->idusuario);
                $hasRolAdminGeneral = $this->hasRol(Mds_gerontologia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolAdmin' => $hasRolAdmin,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'permissions' => $permissions,
                    'viviendasFiltro' => $this->getFilterVivienda(),
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_gerontologia model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $data_evaluacion = Mds_gerontologia_respuesta::find()->where(["idgerontologia" => $id])->one();
        $model_evaluacion = (object)$data_evaluacion;

        return $this->render('view', [
            'model' => $this->findModel($id),
            'model_evaluacion' => $model_evaluacion
        ]);
    }

    /**
     * Creates a new Mds_gerontologia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $nacionalidades = $this->getListNacionalidades();
        $generos = $this->getListGeneros();
        $tiposDocumentos = $this->getListTiposDocumentos();
        $arrayLavados = $this->getListAbvd_lavado();
        $arrayVestidos = $this->getListAbvd_vestido();
        $arrayBanios = $this->getListAbvd_banio();
        $arrayMovilizaciones = $this->getListAbvd_movilizacion();
        $arrayContinencias = $this->getListAbvd_continencia();
        $arrayAlimentaciones = $this->getListAbvd_alimentacion();

        $arrayCapacidadTelefono = $this->getListAivd_capacidadtelefono();
        $arrayCompras = $this->getListAivd_compras();
        $arrayPreparacionComida = $this->getListAivd_preparacioncomida();
        $arrayCuidadoCasa = $this->getListAivd_cuidadocasa();
        $arrayLavadoRopa = $this->getListAivd_lavadoropa();
        $arrayUsoTransporte = $this->getListAivd_usotransporte();
        $arrayResponsabilidadMedicacion = $this->getListAivd_responsabilidadmedicacion();
        $arrayManejoAsuntosEconomicos = $this->getListAivd_asuntoseconomicos();
        $arraySituacionFamiliar = $this->getListSocial_situacionfamiliar();
        $arrayRelacionesSociales = $this->getListSocial_relacionsocial();
        $arrayRedSocial = $this->getListSocial_redsocial();

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_GERONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {
            $action = 'create';
            $model = new Mds_gerontologia();
            $model_evaluacion = new Mds_gerontologia_respuesta();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

            return $this->render('create', [
                'action' => $action,
                'model' => $model,
                'model_evaluacion' => $model_evaluacion,
                'nacionalidades' => $nacionalidades,
                'tiposDocumentos' => $tiposDocumentos,
                'username' => Yii::$app->user->identity->user,
                'generos' => $generos,
                'escolaridad' => $this->getListEscolaridad(),
                'obrasocial' => $this->getListObrasocial(),
                'estadocivil' => $this->getListEstadocivil(),
                'vivienda' => $this->getListVivienda(),
                'vacunacovid19' => $this->getListVacunacovid19(),
                'abvdLavadoSelect' => $arrayLavados,
                'abvdLavadoSelectOptions' => $this->getListExtraOptions($arrayLavados),
                'abvdVestidoSelect' => $arrayVestidos,
                'abvdVestidoSelectOptions' => $this->getListExtraOptions($arrayVestidos),
                'abvdBanioSelect' => $arrayBanios,
                'abvdBanioSelectOptions' => $this->getListExtraOptions($arrayBanios),
                'abvdMovilizacionSelect' => $arrayMovilizaciones,
                'abvdMovilizacionSelectOptions' => $this->getListExtraOptions($arrayMovilizaciones),
                'abvdContinenciaSelect' => $arrayContinencias,
                'abvdContinenciaSelectOptions' => $this->getListExtraOptions($arrayContinencias),
                'abvdAlimentacionSelect' => $arrayAlimentaciones,
                'abvdAlimentacionSelectOptions' => $this->getListExtraOptions($arrayAlimentaciones),
                'aivdCapacidadTelefonoSelect' => $arrayCapacidadTelefono,
                'aivdCapacidadTelefonoSelectOptions' => $this->getListExtraOptions($arrayCapacidadTelefono),
                'aivdComprasSelect' => $arrayCompras,
                'aivdComprasSelectOptions' => $this->getListExtraOptions($arrayCompras),
                'aivdPreparacionComidaSelect' => $arrayPreparacionComida,
                'aivdPreparacionComidaSelectOptions' => $this->getListExtraOptions($arrayPreparacionComida),
                'aivdCuidadoCasaSelect' => $arrayCuidadoCasa,
                'aivdCuidadoCasaSelectOptions' => $this->getListExtraOptions($arrayCuidadoCasa),
                'aivdLavadoRopaSelect' => $arrayLavadoRopa,
                'aivdLavadoRopaSelectOptions' => $this->getListExtraOptions($arrayLavadoRopa),
                'aivdUsoTransporteSelect' => $arrayUsoTransporte,
                'aivdUsoTransporteSelectOptions' => $this->getListExtraOptions($arrayUsoTransporte),
                'aivdResponsabilidadMedicacionSelect' => $arrayResponsabilidadMedicacion,
                'aivdResponsabilidadMedicacionSelectOptions' => $this->getListExtraOptions($arrayResponsabilidadMedicacion),
                'aivdManejoAsuntosEconomicosSelect' => $arrayManejoAsuntosEconomicos,
                'aivdManejoAsuntosEconomicosSelectOptions' => $this->getListExtraOptions($arrayManejoAsuntosEconomicos),
                'situacionFamiliarSelect' => $arraySituacionFamiliar,
                'situacionFamiliarSelectOptions' => $this->getListExtraOptions($arraySituacionFamiliar),
                'relacionesSocialesSelect' => $arrayRelacionesSociales,
                'relacionesSocialesSelectOptions' => $this->getListExtraOptions($arrayRelacionesSociales),
                'redSocialSelect' => $arrayRedSocial,
                'redSocialSelectOptions' => $this->getListExtraOptions($arrayRedSocial),
                'token' => $token
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_gerontologia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_GERONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "modifica");
        $model = $this->findModel($id);
        $data_evaluacion = Mds_gerontologia_respuesta::find()->where(["idgerontologia" => $id])->one();
        $model_evaluacion = (object)$data_evaluacion;
        $hasRolAdmin = $this->hasRol(Mds_gerontologia::ID_ROL_ADMIN, $usuarioAuth->idusuario);
        $hasRolAdminGeneral = $this->hasRol(Mds_gerontologia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
        if ($hasOnePermission && ($model->idusuario_carga == $usuarioAuth->idusuario) || $hasRolAdmin || $hasRolAdminGeneral) {
            $request = Yii::$app->request;
            $deletedTemporal = $model->deleted_at;

            if ($model->load($request->post())) {
                if ($deletedTemporal == null) {
                    // Estaba activo y no eliminado
                    if ($model->deleted_at == 0) {
                        // Ahora el registro editado debe eliminarse 
                        //$model->deleted_at = date('Y-m-d H:i:s');
                        $model->idusuario_modifica = Yii::$app->user->id;
                    } else {
                        $model->deleted_at = null;
                    }
                } else {
                    // Estaba eliminado (no activo)
                    if ($model->deleted_at == 1) {
                        $model->deleted_at = null;
                        $model->idusuario_modifica = null;
                    } else {
                        $model->deleted_at = $deletedTemporal;
                    }
                }

                $model->updated_at = date('Y-m-d H:i:s');
                if ($model->validate()) {
                    if ($model->fecha_atencion) {
                        $fecha_atencion = $this->armarDateParaMySql($model->fecha_atencion);
                        $fecha_atencion = date_create($fecha_atencion);
                        $fecha_atencion = date_format($fecha_atencion, 'Y-m-d');
                        $model->fecha_atencion = $fecha_atencion;
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
                        $this->storeEvaluacion($model->idgerontologia, Yii::$app->request->post()['Mds_gerontologia_respuesta'], $model_evaluacion->idgerontologiarespuesta);

                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_gerontologia', $model->idgerontologia, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se actualizó correctamente el registro.");
                        return $this->redirect(['mds_gerontologia/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al generar el registro.");
                    }
                }
            } else {

                $nacionalidades = $this->getListNacionalidades();
                $generos = $this->getListGeneros();
                $tiposDocumentos = $this->getListTiposDocumentos();
                $arrayLavados = $this->getListAbvd_lavado();
                $arrayVestidos = $this->getListAbvd_vestido();
                $arrayBanios = $this->getListAbvd_banio();
                $arrayMovilizaciones = $this->getListAbvd_movilizacion();
                $arrayContinencias = $this->getListAbvd_continencia();
                $arrayAlimentaciones = $this->getListAbvd_alimentacion();

                $arrayCapacidadTelefono = $this->getListAivd_capacidadtelefono();
                $arrayCompras = $this->getListAivd_compras();
                $arrayPreparacionComida = $this->getListAivd_preparacioncomida();
                $arrayCuidadoCasa = $this->getListAivd_cuidadocasa();
                $arrayLavadoRopa = $this->getListAivd_lavadoropa();
                $arrayUsoTransporte = $this->getListAivd_usotransporte();
                $arrayResponsabilidadMedicacion = $this->getListAivd_responsabilidadmedicacion();
                $arrayManejoAsuntosEconomicos = $this->getListAivd_asuntoseconomicos();
                $arraySituacionFamiliar = $this->getListSocial_situacionfamiliar();
                $arrayRelacionesSociales = $this->getListSocial_relacionsocial();
                $arrayRedSocial = $this->getListSocial_redsocial();
                $action = 'update';
                if ($model->deleted_at !== null) {
                    $model->deleted_at = 0;
                } else {
                    $model->deleted_at = 1;
                }

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

                return $this->render('update', [
                    'action' => $action,
                    'model' => $model,
                    'model_evaluacion' => $model_evaluacion,
                    'tiposDocumentos' => $tiposDocumentos,
                    'username' => Yii::$app->user->identity->user,
                    'nacionalidades' => $nacionalidades,
                    'generos' => $generos,
                    'escolaridad' => $this->getListEscolaridad(),
                    'obrasocial' => $this->getListObrasocial(),
                    'estadocivil' => $this->getListEstadocivil(),
                    'vivienda' => $this->getListVivienda(),
                    'vacunacovid19' => $this->getListVacunacovid19(),
                    'abvdLavadoSelect' => $arrayLavados,
                    'abvdLavadoSelectOptions' => $this->getListExtraOptions($arrayLavados),
                    'abvdVestidoSelect' => $arrayVestidos,
                    'abvdVestidoSelectOptions' => $this->getListExtraOptions($arrayVestidos),
                    'abvdBanioSelect' => $arrayBanios,
                    'abvdBanioSelectOptions' => $this->getListExtraOptions($arrayBanios),
                    'abvdMovilizacionSelect' => $arrayMovilizaciones,
                    'abvdMovilizacionSelectOptions' => $this->getListExtraOptions($arrayMovilizaciones),
                    'abvdContinenciaSelect' => $arrayContinencias,
                    'abvdContinenciaSelectOptions' => $this->getListExtraOptions($arrayContinencias),
                    'abvdAlimentacionSelect' => $arrayAlimentaciones,
                    'abvdAlimentacionSelectOptions' => $this->getListExtraOptions($arrayAlimentaciones),
                    'aivdCapacidadTelefonoSelect' => $arrayCapacidadTelefono,
                    'aivdCapacidadTelefonoSelectOptions' => $this->getListExtraOptions($arrayCapacidadTelefono),
                    'aivdComprasSelect' => $arrayCompras,
                    'aivdComprasSelectOptions' => $this->getListExtraOptions($arrayCompras),
                    'aivdPreparacionComidaSelect' => $arrayPreparacionComida,
                    'aivdPreparacionComidaSelectOptions' => $this->getListExtraOptions($arrayPreparacionComida),
                    'aivdCuidadoCasaSelect' => $arrayCuidadoCasa,
                    'aivdCuidadoCasaSelectOptions' => $this->getListExtraOptions($arrayCuidadoCasa),
                    'aivdLavadoRopaSelect' => $arrayLavadoRopa,
                    'aivdLavadoRopaSelectOptions' => $this->getListExtraOptions($arrayLavadoRopa),
                    'aivdUsoTransporteSelect' => $arrayUsoTransporte,
                    'aivdUsoTransporteSelectOptions' => $this->getListExtraOptions($arrayUsoTransporte),
                    'aivdResponsabilidadMedicacionSelect' => $arrayResponsabilidadMedicacion,
                    'aivdResponsabilidadMedicacionSelectOptions' => $this->getListExtraOptions($arrayResponsabilidadMedicacion),
                    'aivdManejoAsuntosEconomicosSelect' => $arrayManejoAsuntosEconomicos,
                    'aivdManejoAsuntosEconomicosSelectOptions' => $this->getListExtraOptions($arrayManejoAsuntosEconomicos),
                    'situacionFamiliarSelect' => $arraySituacionFamiliar,
                    'situacionFamiliarSelectOptions' => $this->getListExtraOptions($arraySituacionFamiliar),
                    'relacionesSocialesSelect' => $arrayRelacionesSociales,
                    'relacionesSocialesSelectOptions' => $this->getListExtraOptions($arrayRelacionesSociales),
                    'redSocialSelect' => $arrayRedSocial,
                    'redSocialSelectOptions' => $this->getListExtraOptions($arrayRedSocial),
                    'token' => $token
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStore()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_GERONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {

            $model = new Mds_gerontologia();
            $model_evaluacion = new Mds_gerontologia_respuesta();

            $model->created_at = date('Y-m-d H:i:s');
            $model->idusuario_carga = Yii::$app->user->id;

            if (Yii::$app->request->post()) {
                $model->load(Yii::$app->request->post());
                $model_evaluacion->load(Yii::$app->request->post());

                $transaction = Yii::$app->db->beginTransaction();

                if ($model['deleted_at'] == '0') {
                    $model->deleted_at = date('Y-m-d H:i:s');
                    $model->idusuario_modifica = Yii::$app->user->id;
                } else {
                    $model->deleted_at = null;
                    $model->idusuario_modifica = null;
                }

                if ($model->validate()) {
                    if ($model->fecha_atencion) {
                        $fecha_atencion = $this->armarDateParaMySql($model->fecha_atencion);
                        $fecha_atencion = date_create($fecha_atencion);
                        $fecha_atencion = date_format($fecha_atencion, 'Y-m-d');
                        $model->fecha_atencion = $fecha_atencion;
                    }
                    if ($model->save()) {
                        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                            $this->storeAdjuntoOtros($adjuntos, $model);
                        }
                        $this->storeEvaluacion($model->idgerontologia, Yii::$app->request->post()['Mds_gerontologia_respuesta'], null);

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_gerontologia', $model->idgerontologia, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se creó correctamente el registro.");
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al guardar el registro.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
                }
                return $this->redirect(['mds_gerontologia/index']);
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
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_GERONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "baja");
        if ($hasOnePermission || (($model['idusuario_carga'] === $usuarioAuth->idusuario))) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente el registro.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_gerontologia', $model->idgerontologia, $model->getAttributes());
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

    /**
     * Finds the Mds_gerontologia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_gerontologia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_gerontologia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    protected function findModelEvaluacion($id)
    {
        if (($model = Mds_gerontologia_respuesta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function storeEvaluacion($idgerontologia, $model, $idrespuesta)
    {
        $evaluacionguardada = false;

        if ($idrespuesta) {
            $model_evaluacion = $this->findModelEvaluacion($idrespuesta);
        } else {
            $model_evaluacion = new Mds_gerontologia_respuesta();
        }

        $model_evaluacion->idgerontologia = $idgerontologia;
        $model_evaluacion->abvd_lavado = $model['abvd_lavado'];
        $model_evaluacion->abvd_vestido = $model['abvd_vestido'];
        $model_evaluacion->abvd_banio = $model['abvd_banio'];
        $model_evaluacion->abvd_continencia = $model['abvd_continencia'];
        $model_evaluacion->abvd_movilizacion = $model['abvd_movilizacion'];
        $model_evaluacion->abvd_alimentacion = $model['abvd_alimentacion'];
        $model_evaluacion->abvd = $model['abvd_alimentacion'];
        $model_evaluacion->abvd = $model['abvd'];

        $model_evaluacion->aivd_capacidad_telefono = $model['aivd_capacidad_telefono'];
        $model_evaluacion->aivd_compras = $model['aivd_compras'];
        $model_evaluacion->aivd_preparacion_comida = $model['aivd_preparacion_comida'];
        $model_evaluacion->aivd_cuidado_casa = $model['aivd_cuidado_casa'];
        $model_evaluacion->aivd_lavado_ropa = $model['aivd_lavado_ropa'];
        $model_evaluacion->aivd_uso_transporte = $model['aivd_uso_transporte'];
        $model_evaluacion->aivd_responsabilidad_medicacion = $model['aivd_responsabilidad_medicacion'];
        $model_evaluacion->aivd_manejo_asuntos_economicos = $model['aivd_manejo_asuntos_economicos'];
        $model_evaluacion->aivd = $model['aivd'];

        $model_evaluacion->idsituacionfamiliar = $model['idsituacionfamiliar'];
        $model_evaluacion->idrelacionessociales = $model['idrelacionessociales'];
        $model_evaluacion->idredsocial = $model['idredsocial'];

        $model_evaluacion->icope_detcog_responde_incorrectamente = $model['icope_detcog_responde_incorrectamente'];
        $model_evaluacion->icope_detcog_no_responde = $model['icope_detcog_no_responde'];
        $model_evaluacion->icope_perdida_movilidad = $model['icope_perdida_movilidad'];
        $model_evaluacion->icope_nut_def_perdida_peso = $model['icope_nut_def_perdida_peso'];
        $model_evaluacion->icope_nut_def_perdida_apetito = $model['icope_nut_def_perdida_apetito'];
        $model_evaluacion->icope_discapacidad_visual = $model['icope_discapacidad_visual'];
        $model_evaluacion->icope_perdida_auditiva = $model['icope_perdida_auditiva'];
        $model_evaluacion->icope_sin_dep_sentimientos = $model['icope_sin_dep_sentimientos'];
        $model_evaluacion->icope_sin_dep_interes = $model['icope_sin_dep_interes'];
        $model_evaluacion->created_at = date('Y-m-d H:i:s');
        $model_evaluacion->ev_social_total = $model['ev_social_total'];

        if ($model_evaluacion->validate()) {
            if ($model_evaluacion->save()) {
                $evaluacionguardada = true;
            }
        }
        return $evaluacionguardada;
    }



    public function actionDetalle_gerontologia($id)
    {
        //Genera un PDF con el detalle para imprimirla

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_GERONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            $model = $this->findModel($id);
            $data_evaluacion = Mds_gerontologia_respuesta::find()->where(["idgerontologia" => $id])->one();
            $model_evaluacion = (object)$data_evaluacion;

            $content = $this->renderPartial('reporte_detalle_gerontologia', [
                'model' => $model,
                'model_evaluacion' => $model_evaluacion,
            ]);
            $dateToday = date('d/m/Y H:i:s');

            $icope_detcog_responde_incorrectamente = $model_evaluacion->icope_detcog_responde_incorrectamente == 1 ? 'Responde incorrectamente a las dos preguntas o no sabe.' : 'Responde correctamente a las dos preguntas.';
            $icope_detcog_no_responde = $model_evaluacion->icope_detcog_no_responde == 1 ? 'No' : 'Si';
            $icope_perdida_movilidad = $model_evaluacion->icope_perdida_movilidad == 1 ? 'No' : 'Si';
            $icope_nut_def_perdida_peso = $model_evaluacion->icope_nut_def_perdida_peso == 1 ? 'Si' : 'No';
            $icope_nut_def_perdida_apetito = $model_evaluacion->icope_nut_def_perdida_apetito == 1 ? 'Si' : 'No';
            $icope_discapacidad_visual = $model_evaluacion->icope_discapacidad_visual == 1 ? 'Si' : 'No';
            $icope_perdida_auditiva = $model_evaluacion->icope_perdida_auditiva == 1 ? 'Si' : 'No';
            $icope_sin_dep_sentimientos = $model_evaluacion->icope_sin_dep_sentimientos == 1 ? 'Si' : 'No';
            $icope_sin_dep_interes = $model_evaluacion->icope_sin_dep_interes == 1 ? 'Si' : 'No';

            // agregamos la tabla icope
            $content .= "
                    <table cellspacing='0' cellpadding='1' border='1'>
                    <thead>
                        <tr>
                            <th>Condiciones prioritarias asociadas con la disminucion de la capacidad cognitiva</th>
                            <th>Pruebas</th>
                            <th>Evaluar a fondo todos los dominios que se seleccionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>DETERIORO COGNITIVO</td>
                            <td>    1. Recordar tres palabras: flor, puerta, arroz (por ejemplo).
                                <br>2. Orientación en tiempo y espacio: ¿Cuál es la fecha completa de hoy?
                                ¿Dónde está usted ahora mismo (casa, consulta, etc.)?
                                <br>3. ¿Recuerda las tres palabras?
                            </td>
                            <td>
                                <div>
                                    2)" . $icope_detcog_responde_incorrectamente . " <br>
                                    3)" . $icope_detcog_no_responde . " recuerda las tres palabras.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>PÉRDIDA DE MOVILIDAD</td>
                            <td>Prueba de la silla: Debe levantarse de la silla cinco veces sin ayudarse con los brazos.
                                <br>¿Se levantó cinco veces de la silla en 14 segundos?
                            </td>
                            <td>
                                <div>
                                    " . $icope_perdida_movilidad . ".
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>NUTRICIÓN DEFICIENTE</td>
                            <td>1. Pérdida de peso: ¿Ha perdido más de 3 kg involuntariamente en los últimos tres meses?
                                <br>2. Pérdida del apetito: ¿Ha perdido el apetito?
                            </td>
                            <td>
                                <div>
                                    1)" . $icope_nut_def_perdida_peso . ".<br><br>
                                    2)" . $icope_nut_def_perdida_apetito . ".
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>DISCAPACIDAD VISUAL</td>
                            <td>¿Tiene algún problema de la vista?<br>
                                ¿Le cuesta ver de lejos o leer? ¿Tiene alguna enfermedad ocular o toma
                                medicación (p. ej., diabetes, hipertensión)?
                            </td>
                            <td>
                                <div>
                                    " . $icope_discapacidad_visual . ".<br> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>PÉRDIDA AUDITIVA</td>
                            <td>Oye los susurros (prueba de susurros) <b>o bien</b>
                                <br>Audiometría ≤ 35 dB <b>o bien</b>
                                <br>Supera la prueba electrónica de dígitos sobre fondo de ruido.
                            </td>
                            <td>
                                <div>
                                    " . $icope_perdida_auditiva . ".<br> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>SÍNTOMAS DEPRESIVOS</td>
                            <td>En las últimas dos semanas, ¿ha tenido alguno de los siguientes problemas?:<br>
                                1. ¿Sentimientos de tristeza, melancolía o desesperanza?<br>
                                2. ¿Falta de interés o de placer al hacer las cosas?
                            </td>
                            <td>
                                <div><br> <br>
                                    1)" . $icope_sin_dep_sentimientos . ".<br> <br>
                                    2)" . $icope_sin_dep_interes . ".
                                </div>
                            </td>
                        </tr>
    
                    </tbody>
                </table>
            ";

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
                    'SetTitle' => 'DETALLE DE GERONTOLOGÍA ' . $id,
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_gerontologia', $id, array());
            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id)
    {
        $request = Yii::$app->request;
        $rolSuperAdmin = Mds_gerontologia::tieneRol(Mds_gerontologia::ID_ROL_ADMIN_GENERAL);
        if ($rolSuperAdmin) {
            $model = Mds_gerontologia::findOne($id);
            if ($model) {
                $model->deleted_at = null;
                $model->idusuario_borra = null;
                $model->update();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_gerontologia', $model->idgerontologia, $model->getAttributes());
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
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathReproam = __DIR__ . '/../web/uploads/gerontologia/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "gerontologia_{$model->idgerontologia}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathReproam  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_gerontologia', 'registro_gerontologia', $model->idgerontologia, $nameFile);
            }
        }
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

        $model = new Mds_gerontologia();
        $where = "deleted_at IS NULL";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND created_at >= '$fechaInicio' AND created_at <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND created_at >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND created_at <= '$fechaFin'";
        }
        $totalRegistros = $model->find()->where($where)->all();
        $arrayIndicadores = array();

        return $this->render('dashboard/index', [
            'totalRegistros' => $totalRegistros,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFinOriginal,
            'arrayIndicadores' => $arrayIndicadores,
        ]);
    }

    protected function hasRol($idrol, $idusuario)
    {
        $hasRol = false;
        $roles = Mds_seg_usuario_rol::find()
            ->where(['idusuario' => $idusuario])
            ->andWhere(["idrol" => $idrol])
            ->all();

        if (count($roles) > 0) {
            $hasRol = true;
        }

        return $hasRol;
    }

    protected function getListEscolaridad()
    {
        //Busqueda escolaridad
        $escolaridad = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::NIVEL_ESCOLARIDAD, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $escolaridad = ArrayHelper::map($escolaridad, 'idconfiguracion', 'descripcion');
        return $escolaridad;
    }
    protected function getListObrasocial()
    {
        //Busqueda obra social
        $obrasocial = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::OBRA_SOCIAL, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $obrasocial = ArrayHelper::map($obrasocial, 'idconfiguracion', 'descripcion');
        return $obrasocial;
    }
    protected function getListEstadocivil()
    {
        //Busqueda estado civil
        $estadocivil = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $estadocivil = ArrayHelper::map($estadocivil, 'idconfiguracion', 'descripcion');
        return $estadocivil;
    }
    protected function getListVivienda()
    {
        //Busqueda tipo de vivienda de gerontologia
        $vivienda = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_VIVIENDA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $vivienda = ArrayHelper::map($vivienda, 'idconfiguracion', 'descripcion');
        return $vivienda;
    }
    protected function getListVacunacovid19()
    {
        //Busqueda dosis de vacuna covid19
        $vacunacovid19 = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VACUNA_COVID19, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $vacunacovid19 = ArrayHelper::map($vacunacovid19, 'idconfiguracion', 'descripcion');
        return $vacunacovid19;
    }
    protected function getListExtraOptions($arrayLavados)
    {
        $arrayLavadosValores = [];
        foreach ($arrayLavados as $idconfiguracion => $value) {
            $registroEscala = Mds_gerontologia_escala::find()->select('valor')->where(['idconfiguracion' => $idconfiguracion])->asArray()->one();
            $valor = $registroEscala ? $registroEscala['valor'] : 0;
            $arrayLavadosValores[$idconfiguracion] = ['data-valor' => $valor];
        }
        return $arrayLavadosValores;
    }
    protected function getListAbvd_lavado()
    {
        //Busqueda abvd lavado
        $abvd_lavado = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_ABVD_LAVADO, "activo" => 1])->orderBy(['idconfiguracion' => SORT_ASC])->asArray()->all();
        $abvd_lavado = ArrayHelper::map($abvd_lavado, 'idconfiguracion', 'descripcion');
        return $abvd_lavado;
    }

    protected function getListAbvd_vestido()
    {
        //Busqueda abvd vestido
        $abvd_vestido = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_ABVD_VESTIDO, "activo" => 1])->orderBy(['idconfiguracion' => SORT_ASC])->asArray()->all();
        $abvd_vestido = ArrayHelper::map($abvd_vestido, 'idconfiguracion', 'descripcion');
        return $abvd_vestido;
    }
    protected function getListAbvd_banio()
    {
        //Busqueda abvd banio
        $abvd_banio = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_ABVD_BANIO, "activo" => 1])->orderBy(['idconfiguracion' => SORT_ASC])->asArray()->all();
        $abvd_banio = ArrayHelper::map($abvd_banio, 'idconfiguracion', 'descripcion');
        return $abvd_banio;
    }
    protected function getListAbvd_movilizacion()
    {
        //Busqueda abvd movilizacion
        $abvd_movilizacion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_ABVD_MOVILIZACION, "activo" => 1])->orderBy(['idconfiguracion' => SORT_ASC])->asArray()->all();
        $abvd_movilizacion = ArrayHelper::map($abvd_movilizacion, 'idconfiguracion', 'descripcion');
        return $abvd_movilizacion;
    }
    protected function getListAbvd_continencia()
    {
        //Busqueda abvd movilizacion
        $abvd_movilizacion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_ABVD_CONTINENCIA, "activo" => 1])->orderBy(['idconfiguracion' => SORT_ASC])->asArray()->all();
        $abvd_movilizacion = ArrayHelper::map($abvd_movilizacion, 'idconfiguracion', 'descripcion');
        return $abvd_movilizacion;
    }
    protected function getListAbvd_alimentacion()
    {
        //Busqueda abvd alimentacion
        $abvd_alimentacion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_ABVD_ALIMENTACION, "activo" => 1])->orderBy(['idconfiguracion' => SORT_ASC])->asArray()->all();
        $abvd_alimentacion = ArrayHelper::map($abvd_alimentacion, 'idconfiguracion', 'descripcion');
        return $abvd_alimentacion;
    }
    protected function getListAivd_capacidadtelefono()
    {
        //Busqueda aivd
        $abvd_alimentacion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_USA_TELEFONO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $abvd_alimentacion = ArrayHelper::map($abvd_alimentacion, 'idconfiguracion', 'descripcion');
        return $abvd_alimentacion;
    }
    protected function getListAivd_compras()
    {
        //Busqueda aivd compras
        $abvd_compras = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_COMPRAS, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $abvd_compras = ArrayHelper::map($abvd_compras, 'idconfiguracion', 'descripcion');
        return $abvd_compras;
    }

    protected function getListAivd_preparacioncomida()
    {
        //Busqueda aivd preparacion comida
        $abvd_preparacion_comida = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_PREPARACION_COMIDA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $abvd_preparacion_comida = ArrayHelper::map($abvd_preparacion_comida, 'idconfiguracion', 'descripcion');
        return $abvd_preparacion_comida;
    }
    protected function getListAivd_cuidadocasa()
    {
        //Busqueda aivd cuidado de la casa
        $aivd_cuidado_casa = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_CUIDADO_CASA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $aivd_cuidado_casa = ArrayHelper::map($aivd_cuidado_casa, 'idconfiguracion', 'descripcion');
        return $aivd_cuidado_casa;
    }
    protected function getListAivd_lavadoropa()
    {
        //Busqueda aivd lavado de la ropa
        $aivd_lavado_ropa = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_LAVADO_ROPA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $aivd_lavado_ropa = ArrayHelper::map($aivd_lavado_ropa, 'idconfiguracion', 'descripcion');
        return $aivd_lavado_ropa;
    }
    protected function getListAivd_usotransporte()
    {
        //Busqueda aivd uso del transporte
        $aivd_uso_transporte = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_USO_TRANSPORTE, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $aivd_uso_transporte = ArrayHelper::map($aivd_uso_transporte, 'idconfiguracion', 'descripcion');
        return $aivd_uso_transporte;
    }
    protected function getListAivd_responsabilidadmedicacion()
    {
        //Busqueda aivd responsabilidad en su medicacion
        $aivd_responsabilidad_medicacion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_RESPONSABILIDAD_MEDICACION, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $aivd_responsabilidad_medicacion = ArrayHelper::map($aivd_responsabilidad_medicacion, 'idconfiguracion', 'descripcion');
        return $aivd_responsabilidad_medicacion;
    }
    protected function getListAivd_asuntoseconomicos()
    {
        //Busqueda aivd asuntos economicos
        $aivd_manejo_asuntos_economicos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_AIVD_ASUNTOS_ECONOMICOS, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $aivd_manejo_asuntos_economicos = ArrayHelper::map($aivd_manejo_asuntos_economicos, 'idconfiguracion', 'descripcion');
        return $aivd_manejo_asuntos_economicos;
    }
    protected function getListSocial_situacionfamiliar()
    {
        //Busqueda Social situacion familiar
        $idsituacionfamiliar = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_SOCIAL_SIT_FAMILIAR, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $idsituacionfamiliar = ArrayHelper::map($idsituacionfamiliar, 'idconfiguracion', 'descripcion');
        return $idsituacionfamiliar;
    }
    protected function getListSocial_relacionsocial()
    {
        //Busqueda Social relacion social
        $idrelacionessociales = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_SOCIAL_SIT_REL_SOCIALES, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $idrelacionessociales = ArrayHelper::map($idrelacionessociales, 'idconfiguracion', 'descripcion');
        return $idrelacionessociales;
    }
    protected function getListSocial_redsocial()
    {
        //Busqueda Social relacion social
        $idredsocial = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::GERONTOLOGIA_SOCIAL_APOYO_RED_SOCIAL, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $idredsocial = ArrayHelper::map($idredsocial, 'idconfiguracion', 'descripcion');
        return $idredsocial;
    }

    protected function getFilterVivienda()
    {
        //Busqueda localidades
        $idConfiguracionTipoVivienda = Sds_com_configuracion_tipo::GERONTOLOGIA_VIVIENDA;

        $viviendasFiltro = Mds_gerontologia::findBySql(
            "SELECT idgerontologia, 
                configuracion.idconfiguracion as idconfiguracion, 
                configuracion.descripcion as descripcion 
                FROM mds_gerontologia gerontologia 
                INNER JOIN sds_com_configuracion configuracion 
                ON gerontologia.idvivienda = configuracion.idconfiguracion 
                WHERE gerontologia.deleted_at IS NULL AND configuracion.activo = 1 
                AND configuracion.idconfiguraciontipo = $idConfiguracionTipoVivienda
                ORDER BY descripcion ASC
                "
        )->asArray()->all();
        $viviendasFiltro = ArrayHelper::map($viviendasFiltro, 'idconfiguracion', 'descripcion');
        return $viviendasFiltro;
    }
    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_gerontologia_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }
}
