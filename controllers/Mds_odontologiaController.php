<?php

namespace app\controllers;

use Yii;
use app\models\Mds_odontologia;
use app\models\Mds_odontologiaSearch;
use app\models\Sds_com_configuracion;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_legales_archivo;
use app\models\Mds_seg_item;

use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;

/**
 * Mds_odontologiaController implements the CRUD actions for Mds_odontologia model.
 */
class Mds_odontologiaController extends Controller
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
                        'roles' => [Mds_seg_item::MODULO_ODONTOLOGIA],
                    ],
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_ODONTOLOGIA_SEGUIMIENTO
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_odontologia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_odontologiaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $usuarioAuth = Yii::$app->user->identity;

        if ($usuarioAuth) {
            $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ODONTOLOGIA, $usuarioAuth->idusuario);
            $hasOnePermissionRead = $this->hasOnePermission($permissions, "ver");

            if ($hasOnePermissionRead) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_odontologia', null, array());
                $hasRolAdmin = $this->hasRol(Mds_odontologia::ID_ROL_ADMIN, $usuarioAuth->idusuario);
                $hasRolAdminGeneral = $this->hasRol(Mds_odontologia::ID_ROL_ADMIN_GENERAL, $usuarioAuth->idusuario);
                $hasPermissionCreate = $this->hasOnePermission($permissions, "alta");
                $hasPermissionUpdate = $this->hasOnePermission($permissions, "modifica");

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolAdmin' => $hasRolAdmin,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'hasPermissionCreate' => $hasPermissionCreate,
                    'hasPermissionUpdate' => $hasPermissionUpdate,
                    'tipoIntervencionFiltro' => $this->getFilterTipos("INTERVENCION"),
                    'tipoDispositivoFiltro' => $this->getFilterTipos("DISPOSITIVO"),
                    'tipoEscolaridadFiltro' => $this->getFilterTipos("ESCOLARIDAD"),
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_odontologia model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ODONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_odontologia', $id, array());
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_odontologia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ODONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {
            $action = 'create';
            $model = new Mds_odontologia();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

            return $this->render('create', [
                'action' => $action,
                'model' => $model,
                'username' => Yii::$app->user->identity->user,
                'tiposNacionalidades' => $this->getListByTipo("NACIONALIDAD"),
                'tiposGeneros' => $this->getListByTipo("GENERO"),
                'tiposDocumentos' => $this->getListByTipo("DOCUMENTO"),
                'tiposVisitas' => $this->getListByTipo("VISITA_ODONTOLOGIA"),
                'tiposEscolaridad' => $this->getListByTipo("ULTIMO_ANIO_APROBADO"),
                'tiposVacunasCovid' => $this->getListByTipo("VACUNA_COVID19"),
                'tiposIntervenciones' => $this->getListByTipo("INTERVENCION_ODONTOLOGIA"),
                'token' => $token
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_odontologia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ODONTOLOGIA, $usuarioAuth->idusuario);
        $hasPermissionEdit = $this->hasOnePermission($permissions, "modifica");
        $hasRolAdminGeneral = Mds_odontologia::tieneRol(Mds_odontologia::ID_ROL_ADMIN_GENERAL);
        $hasRolAdmin = Mds_odontologia::tieneRol(Mds_odontologia::ID_ROL_ADMIN);

        $model = $this->findModel($id);
        if ($hasRolAdminGeneral || $hasRolAdmin || ($hasPermissionEdit && ($model->idusuario_carga == $usuarioAuth->idusuario))) {
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

                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_odontologia', $model->idodontologia, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se actualizó correctamente el registro.");
                        return $this->redirect(['mds_odontologia/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al generar el registro.");
                    }
                }
            } else {

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
                    'username' => Yii::$app->user->identity->user,
                    'hasPermissionEdit' => $hasPermissionEdit,
                    'tiposNacionalidades' => $this->getListByTipo("NACIONALIDAD"),
                    'tiposGeneros' => $this->getListByTipo("GENERO"),
                    'tiposDocumentos' => $this->getListByTipo("DOCUMENTO"),
                    'tiposVisitas' => $this->getListByTipo("VISITA_ODONTOLOGIA"),
                    'tiposEscolaridad' => $this->getListByTipo("ULTIMO_ANIO_APROBADO"),
                    'tiposVacunasCovid' => $this->getListByTipo("VACUNA_COVID19"),
                    'tiposIntervenciones' => $this->getListByTipo("INTERVENCION_ODONTOLOGIA"),
                    'token' => $token
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_odontologia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_modifica = Yii::$app->user->id;

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ODONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "baja");
        if ($hasOnePermission || (($model['idusuario_carga'] === $usuarioAuth->idusuario))) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente el registro.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_odontologia', $model->idodontologia, $model->getAttributes());
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', "Error al borrar el registro.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al borrar el registro.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_odontologia/index']);
    }

    /**
     * Finds the Mds_odontologia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_odontologia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_odontologia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionStore()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_ODONTOLOGIA, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");
        if ($hasOnePermission) {

            $model = new Mds_odontologia();
            $model->created_at = date('Y-m-d H:i:s');
            $model->idusuario_carga = Yii::$app->user->id;
            $model->idusuario_modifica = null;

            if (Yii::$app->request->post()) {
                $model->load(Yii::$app->request->post());
                $transaction = Yii::$app->db->beginTransaction();

                if ($model['deleted_at'] == '0') {
                    $model->deleted_at = date('Y-m-d H:i:s');
                } else {
                    $model->deleted_at = null;
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
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_odontologia', $model->idodontologia, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se cargó correctamente el registro odontológico.");
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al guardar el registro.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos.");
                }
                return $this->redirect(['mds_odontologia/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function getListByTipo($type)
    {
        //type can be "INTERVENCION_ODONTOLOGIA", "ULTIMO_ANIO_APROBADO", "VACUNA_COVID19", "VISITA_ODONTOLOGIA", "NACIONALIDAD", "DOCUMENTO", "GENERO"

        $data = array();
        switch ($type) {
            case "INTERVENCION_ODONTOLOGIA":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_INTERVENCION_ODONTOLOGIA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
            case "ULTIMO_ANIO_APROBADO":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
            case "VACUNA_COVID19":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::VACUNA_COVID19, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
            case "VISITA_ODONTOLOGIA":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_VISITA_ODONTOLOGIA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
            case "NACIONALIDAD":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
            case "DOCUMENTO":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_TIPO_DOC, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
            case "GENERO":
                $data = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_GENERO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
                break;
        }

        if ($data && count($data) > 0) {
            $data = ArrayHelper::map($data, 'idconfiguracion', 'descripcion');
        }

        return $data;
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

    public function actionDetalle_registro($id)
    {
        //Genera un PDF con el detalle del registro para imprimirla

        $usuarioAuth = Yii::$app->user->identity;

        $model = $this->findModel($id);

        $content = $this->renderPartial('reporte_detalle_registro', [
            'model' => $model,
        ]);
        $dateToday = date('d/m/Y H:i:s');
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
                'SetTitle' => 'DETALLE DE ODONTOLOGÍA ' . $id,
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_odontologia', $id, array());

        return $pdf->render();
    }

    public function actionReporte_odontologia()
    {

        $usuarioAuth = Yii::$app->user->identity;
        $dateToday = date('d/m/Y H:i:s');

        $request = Yii::$app->request;
        $get = $request->get();

        $asistencia = $get['idasistencia'];
        $deleted_at = $get['activo'];

        if ($deleted_at == 0) {
            $arrayAsistencias = Mds_odontologia::find()->where(['not', ['deleted_at' => null]])->innerJoinWith('persona', 'mds_persona.idpersona = persona.idpersona')->orderBy(['sds_com_persona.apellido' => SORT_ASC]);
        } else {
            $arrayAsistencias = Mds_odontologia::find()->where(['deleted_at' => null])->innerJoinWith('persona', 'mds_persona.idpersona = persona.idpersona')->orderBy(['sds_com_persona.apellido' => SORT_ASC]);
        }

        if (!empty($asistencia)) {
            $arrayAsistencias->andWhere(['idodontologia' => $asistencia]);
        }

        $content = $this->renderPartial('reporte_odontologia', [
            'arrayAsistencias' => $arrayAsistencias->all(),
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
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
            'methods' => [
                'SetTitle' => 'REPORTE ODONTOLOGIA',
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_odontologia', null, array());

        return $pdf->render();
    }


    public function actionHistorial_odontologico()
    {

        $usuarioAuth = Yii::$app->user->identity;
        $dateToday = date('d/m/Y H:i:s');

        $request = Yii::$app->request;
        $get = $request->get();
        $persona = $get['id'];

        $arrayRegistros = Mds_odontologia::find()
            ->where(['mds_odontologia.idpersona' => $persona])
            ->andWhere(['deleted_at' => null])
            ->innerJoinWith('persona', 'mds_persona.idpersona = persona.idpersona')
            ->orderBy(['mds_odontologia.fecha_atencion' => SORT_DESC]);

        $content = $this->renderPartial('reporte_odontologia', [
            'arrayAsistencias' => $arrayRegistros->all(),
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
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .4rem 4px .4rem}',
            'methods' => [
                'SetTitle' => 'REPORTE ODONTOLOGIA-HISTORICO',
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_odontologia', null, array());

        return $pdf->render();
    }

    public function actionReactivate($id)
    {
        $hasRolAdminGeneral = Mds_odontologia::tieneRol(Mds_odontologia::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            $model = Mds_odontologia::findOne($id);
            if ($model) {
                $model->deleted_at = null;
                $model->idusuario_borra = null;
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente el registro.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el registro.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_odontologia', $model->idodontologia, $model->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "El registro no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathOdontologia = __DIR__ . '/../web/uploads/odontologia/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "odontologia_{$model->idodontologia}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathOdontologia  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_odontologia', 'registro_odontologia', $model->idodontologia, $nameFile);
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

        $model = new Mds_odontologia();
        $where = "deleted_at IS NULL";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND created_at >= '$fechaInicio' AND created_at <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND created_at >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND created_at <= '$fechaFin'";
        }
        $totalRegistros = $model->find()->where($where)->all();

        $idConfiguracionTipoOdontologia = Sds_com_configuracion_tipo::TIPO_DISPOSITIVO_ODONTOLOGIA;
        $idConfiguracionTipoOdontologiaTipoIntervencion = Sds_com_configuracion_tipo::TIPO_INTERVENCION_ODONTOLOGIA;
        $idConfiguracionEscuela = Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO;
        $arrayDispositivos = $model->find()->select(['idconfiguracion', 'descripcion'])->join("inner join", "sds_com_configuracion as configuracion", "configuracion.idconfiguracion = mds_odontologia.iddispositivo")->where("$where AND idconfiguraciontipo = $idConfiguracionTipoOdontologia")->groupBy(['configuracion.idconfiguracion'])->asArray()->all();
        $arrayTipoIntervencion = $model->find()->select(['idconfiguracion', 'descripcion'])->join("inner join", "sds_com_configuracion as configuracion", "configuracion.idconfiguracion = mds_odontologia.idtipointervencion")->where("$where AND idconfiguraciontipo = $idConfiguracionTipoOdontologiaTipoIntervencion")->groupBy(['configuracion.idconfiguracion'])->asArray()->all();
        $arrayEducacion = $model->find()->select(['idconfiguracion', 'descripcion'])->join("inner join", "sds_com_configuracion as configuracion", "configuracion.idconfiguracion = mds_odontologia.idescolaridad")->where("$where AND idconfiguraciontipo = $idConfiguracionEscuela")->groupBy(['configuracion.idconfiguracion'])->asArray()->all();

        foreach ($totalRegistros as $registro) {
            $indexDispositivos = 0;
            $flagDispositivos = true;
            while ($flagDispositivos && $indexDispositivos < count($arrayDispositivos)) {
                $arrayDispositivos[$indexDispositivos]['titulo'] = 'Dispositivo';
                $arrayDispositivos[$indexDispositivos]['cantidadRegistros'] = isset($arrayDispositivos[$indexDispositivos]['cantidadRegistros']) ? $arrayDispositivos[$indexDispositivos]['cantidadRegistros'] :  0;
                if ($registro['iddispositivo'] == $arrayDispositivos[$indexDispositivos]['idconfiguracion']) {
                    $arrayDispositivos[$indexDispositivos]['cantidadRegistros']++;
                    $flagDispositivos = false;
                }
                $indexDispositivos++;
            }

            $indexTipoIntervencion = 0;
            $flagTipoIntervencion = true;
            while ($flagTipoIntervencion && $indexTipoIntervencion < count($arrayTipoIntervencion)) {
                $arrayTipoIntervencion[$indexTipoIntervencion]['titulo'] = 'Intervención';
                $arrayTipoIntervencion[$indexTipoIntervencion]['cantidadRegistros'] = isset($arrayTipoIntervencion[$indexTipoIntervencion]['cantidadRegistros']) ? $arrayTipoIntervencion[$indexTipoIntervencion]['cantidadRegistros'] : 0;
                if ($registro['idtipointervencion'] == $arrayTipoIntervencion[$indexTipoIntervencion]['idconfiguracion']) {
                    $arrayTipoIntervencion[$indexTipoIntervencion]['cantidadRegistros']++;
                    $flagTipoIntervencion = false;
                }
                $indexTipoIntervencion++;
            }

            $indexEducacion = 0;
            $flagEducacion = true;
            while ($flagEducacion && $indexEducacion < count($arrayEducacion)) {
                $arrayEducacion[$indexEducacion]['titulo'] = 'Educación';
                $arrayEducacion[$indexEducacion]['cantidadRegistros'] = isset($arrayEducacion[$indexEducacion]['cantidadRegistros']) ? $arrayEducacion[$indexEducacion]['cantidadRegistros'] : 0;
                if ($registro['idescolaridad'] == $arrayEducacion[$indexEducacion]['idconfiguracion']) {
                    $arrayEducacion[$indexEducacion]['cantidadRegistros']++;
                    $flagTipoIntervencion = false;
                }
                $indexEducacion++;
            }
        }

        $arrayIndicadores = array_merge($arrayDispositivos, $arrayTipoIntervencion, $arrayEducacion);

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

    protected function getFilterTipos($type)
    {
        $attribute = "";
        $data = array();
        $configuracionDescripcion = "configuracion.descripcion";
        switch ($type) {
            case "INTERVENCION":;
                $attribute = "idtipointervencion";
                break;
            case "DISPOSITIVO":;
                $attribute = "iddispositivo";
                $configuracionDescripcion = "UPPER($configuracionDescripcion)";
                break;
            case "ESCOLARIDAD":;
                $attribute = "idescolaridad";
                break;
            default:
                break;
        }
        if ($attribute) {
            $data = Mds_odontologia::findBySql(
                "SELECT
                configuracion.idconfiguracion as idtipo,
                $configuracionDescripcion as descripciontipo
                FROM mds_odontologia odontologia
                INNER JOIN sds_com_configuracion configuracion
                ON odontologia.$attribute = configuracion.idconfiguracion
                WHERE odontologia.deleted_at IS NULL AND configuracion.activo = 1
                ORDER BY descripciontipo ASC
                "
            )->asArray()->all();
        }
        $tiposFiltro = ArrayHelper::map($data, 'idtipo', 'descripciontipo');
        return $tiposFiltro;
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_odontologia_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    public function actionMigracionodontologia()
    {
        $resultado = "";

        $data = Mds_odontologia::findBySql(
            "SELECT idodontologia, persona.documento, fecha_atencion
            FROM mds_odontologia odontologia
            INNER JOIN sds_com_persona persona
            ON odontologia.idpersona = persona.idpersona
            WHERE odontologia.deleted_at IS NULL
            "
        )->asArray()->all();

        foreach ($data as $index => $odontologia) {
            $resultado .= "[
                'documento' => {$odontologia['documento']},
                'fecha' => '{$odontologia['fecha_atencion']}',
                'id' => {$odontologia['idodontologia']}
            ], <br>";
        }

        echo $resultado;
    }
}
