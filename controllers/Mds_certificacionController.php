<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_direccion;
use app\models\Mds_certificacion_estado;
use app\models\Mds_certificacion_direccion_usuario;
use app\models\Mds_certificacion_director;
use app\models\Mds_certificacionSearch;
use app\models\Mds_legales_archivo;
use app\models\Mds_seg_usuario;
use app\models\Mds_certificacion_responsable;
use app\models\Mds_certificacion_monto;
use app\models\Mds_certificacion_programa;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use app\models\Sds_ris_persona;
use app\models\Sds_com_persona;
use app\models\Sds_ris_risneu;
use yii\helpers\Url;

use \yii\web\Response;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\db\Expression;

/**
 * Mds_certificacionController implements the CRUD actions for Mds_certificacion model.
 */
class Mds_certificacionController extends Controller
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
                    'reactivate' => ['POST'],
                    'autorizar' => ['POST'],
                    'actualizarestado' => ['POST'],
                    'aprobarindex' => ['POST'],
                    'validar_dni' => ['POST'],
                    'guardarlogmanualusuario' => ['POST'],
                    'get_adjuntos' => ['POST'],
                    'responsable_asignado' => ['POST'],
                    'filter_direcciones_previas' => ['POST'],
                    //'xls_reporte' => ['POST'],
                    'certificacion_incremento' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'view',
                    'create',
                    'guardar_solicitud',
                    'update',
                    'delete',
                    'reactivate',
                    'autorizar',
                    'actualizarestado',
                    'aprobarindex',
                    'certificacion_detalle',
                    'reporte_certificaciones',
                    'certificacion_historica',
                    'historial_responsables',
                    'ver_responsables',
                    'historial_estados',
                    'ver_estados',
                    'ver_montos',
                    'validar_dni',
                    'dashboard',
                    'guardarlogmanualusuario',
                    'get_adjuntos',
                    'responsable_asignado',
                    'filter_direcciones_previas',
                    'modal_xls_reporte',
                    'xls_reporte',
                    'certificacion_incremento',

                    //funciones comentadas
                    'store',
                    'get_id_risneu',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'guardar_solicitud',
                            'update',
                            'delete',
                            'reactivate',
                            'autorizar',
                            'actualizarestado',
                            'aprobarindex',
                            'certificacion_detalle',
                            'reporte_certificaciones',
                            'certificacion_historica',
                            'historial_responsables',
                            'ver_responsables',
                            'historial_estados',
                            'ver_estados',
                            'ver_montos',
                            'validar_dni',
                            'dashboard',
                            'guardarlogmanualusuario',
                            'get_adjuntos',
                            'responsable_asignado',
                            'filter_direcciones_previas',
                            'modal_xls_reporte',
                            'xls_reporte',
                            'certificacion_incremento',

                            //funciones comentadas
                            'store',
                            'get_id_risneu',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    private function getPermissionsCrud($iditem = null)
    {
        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $permissionReactivate = false;
        $permissionDashboard = false;
        $permisos = [];

        $idusuario = Yii::$app->user->identity->idusuario;
        $rolesCertificaciones = implode(',', Mds_certificacion::ID_ROLES_CERTIFICACIONES);

        if ($iditem) {
            $permisos = Mds_seg_permiso::findBySql(
                "SELECT *
                FROM mds_seg_permiso
                where idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario)
                AND idrol IN ({$rolesCertificaciones})
                AND iditem = {$iditem}"
            )->all();
        }
        $countPermisos = count($permisos);
        $i = 0;

        while ((!$permissionCreate || !$permissionRead || !$permissionUpdate || !$permissionDelete) && $i < $countPermisos) {
            $permiso = $permisos[$i];
            if (!$permissionCreate) {
                $permissionCreate = $permiso->alta;
            }
            if (!$permissionRead) {
                $permissionRead = $permiso->ver;
            }
            if (!$permissionUpdate) {
                $permissionUpdate = $permiso->modifica;
            }
            if (!$permissionDelete) {
                $permissionDelete = $permiso->baja;
            }
            $i++;
        }


        if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)) {
            $permissionReactivate = true;
        }
        if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_DASHBOARD) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)) {
            $permissionDashboard = true;
        }

        $response = [
            'permissionCreate' => $permissionCreate,
            'permissionRead' => $permissionRead,
            'permissionUpdate' => $permissionUpdate,
            'permissionDelete' => $permissionDelete,
            'permissionReactivate' => $permissionReactivate,
            'permissionDashboard' => $permissionDashboard
        ];
        return $response;
    }
    /**
     * $tipo can be = ['solicitudes', 'direccion_simple', 'direccion_general', 'direccion_provincial', 'funcionario', 'subsecretaria', 'administracion']
     * return permissions
     */
    private function verifyPermissionsByRol($area)
    {
        $authorized = false;
        $permissionGlobal = false;
        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $permissionReactivate = false;
        $permissionDashboard = false;
        $permissionAutorizar = false;
        $permissionImprimir = false;
        $permissionVerResponsables = false;
        $permissionVerEstados = false;
        $permissionVerMontos = false;
        $permissionExcel = false;
        $permissionNota = false;

        $idnivel = null;
        $iditem = null;

        if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)) {
            $authorized = true;
            $permissionImprimir = true;
            $permissionVerResponsables = true;
            $permissionVerMontos = true;
            $permissionVerEstados = true;

            switch ($area) {
                case Mds_certificacion::AREA_SOLICITANTE:
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_SOLICITUD;
                    $permissionNota = true;
                    break;
                case Mds_certificacion::AREA_NA1:
                    $idnivel = Mds_certificacion::ID_NIVEL1;
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_SIMPLE;
                    $permissionNota = true;
                    if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)) {
                        $permissionAutorizar = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA2:
                    $idnivel = Mds_certificacion::ID_NIVEL2;
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_GENERAL;
                    $permissionExcel = true;
                    $permissionNota = true;
                    if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)) {
                        $permissionAutorizar = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA3:
                    $idnivel = Mds_certificacion::ID_NIVEL3;
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_PROVINCIAL;
                    $permissionExcel = true;
                    $permissionNota = true;
                    if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)) {
                        $permissionAutorizar = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA4:
                    $idnivel = Mds_certificacion::ID_NIVEL4;
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_SUBSECRETARIA;
                    $permissionExcel = true;
                    $permissionNota = true;
                    if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)) {
                        $permissionAutorizar = true;
                    }
                    break;
                case Mds_certificacion::AREA_ADMINISTRACION:
                    $idnivel = Mds_certificacion::ID_NIVEL5;
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_ADMINISTRACION;
                    $permissionExcel = true;
                    $permissionNota = true;
                    if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)) {
                        $permissionAutorizar = true;
                    }
                    break;
                case Mds_certificacion::AREA_FUNCIONARIO:
                    $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_FUNCIONARIO;
                    $permissionExcel = true;
                    break;
                default:
                    $authorized = false;
                    break;
            }

            switch ($area) {
                case Mds_certificacion::AREA_SOLICITANTE:
                case Mds_certificacion::AREA_NA1:
                case Mds_certificacion::AREA_NA2:
                case Mds_certificacion::AREA_NA3:
                case Mds_certificacion::AREA_NA4:
                case Mds_certificacion::AREA_ADMINISTRACION:
                case Mds_certificacion::AREA_FUNCIONARIO:
                    $permissionCrud = self::getPermissionsCrud($iditem);
                    $permissionCreate = $permissionCrud['permissionCreate'];
                    $permissionRead =   $permissionCrud['permissionRead'];
                    $permissionUpdate = $permissionCrud['permissionUpdate'];
                    $permissionDelete = $permissionCrud['permissionDelete'];
                    $permissionReactivate = $permissionCrud['permissionReactivate'];
                    $permissionDashboard = $permissionCrud['permissionDashboard'];
                    break;
                default:
                    $permissionCreate = false;
                    $permissionRead =   false;
                    $permissionUpdate = false;
                    $permissionDelete = false;
                    $permissionReactivate = false;
                    break;
            }
        } else {
            // Puede tener rol de solicitante, direccion simple/general/provincial, subsecretaria o administracion na = nivel de autorizacion
            // Puede tener el rol Funcionario

            switch ($area) {
                case Mds_certificacion::AREA_SOLICITANTE:
                    if (
                        Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
                    ) {
                        $authorized = true;
                        $permissionAutorizar = false;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_SOLICITUD;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionNota = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA1:
                    if (
                        Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
                    ) {
                        $authorized = true;
                        $permissionAutorizar = true;
                        $idnivel = Mds_certificacion::ID_NIVEL1;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_SIMPLE;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionNota = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA2:
                    if (
                        Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
                    ) {
                        $authorized = true;
                        $permissionAutorizar = true;
                        $idnivel = Mds_certificacion::ID_NIVEL2;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_GENERAL;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionExcel = true;
                        $permissionNota = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA3:
                    if (
                        Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
                    ) {
                        $authorized = true;
                        $permissionAutorizar = true;
                        $idnivel = Mds_certificacion::ID_NIVEL3;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_PROVINCIAL;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionExcel = true;
                        $permissionNota = true;
                    }
                    break;
                case Mds_certificacion::AREA_NA4:
                    if (
                        Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
                    ) {
                        $authorized = true;
                        $permissionAutorizar = true;
                        $idnivel = Mds_certificacion::ID_NIVEL4;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_SUBSECRETARIA;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionExcel = true;
                        $permissionNota = true;
                    }
                    break;
                case Mds_certificacion::AREA_ADMINISTRACION:
                    if (
                        Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
                    ) {
                        $authorized = true;
                        $permissionAutorizar = true;
                        $idnivel = Mds_certificacion::ID_NIVEL5;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_ADMINISTRACION;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionExcel = true;
                        $permissionNota = true;
                    }
                    break;
                case Mds_certificacion::AREA_FUNCIONARIO:
                    if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)) {
                        $authorized = true;
                        $permissionAutorizar = false;
                        $iditem = Mds_seg_item::MODULO_CERTIFICACIONES_FUNCIONARIO;
                        $permissionImprimir = true;
                        $permissionVerResponsables = true;
                        $permissionVerMontos = true;
                        $permissionVerEstados = true;
                        $permissionExcel = true;
                    }
                    break;
                default:
                    $idnivel = null;
                    $authorized = false;
                    $permissionImprimir = false;
                    $permissionVerResponsables = false;
                    $permissionVerMontos = false;
                    $permissionVerEstados = false;
                    break;
            }

            switch ($area) {
                case Mds_certificacion::AREA_SOLICITANTE:
                case Mds_certificacion::AREA_NA1:
                case Mds_certificacion::AREA_NA2:
                case Mds_certificacion::AREA_NA3:
                case Mds_certificacion::AREA_NA4:
                case Mds_certificacion::AREA_ADMINISTRACION:
                case Mds_certificacion::AREA_FUNCIONARIO:
                    $permissionCrud = self::getPermissionsCrud($iditem);
                    $permissionCreate = $permissionCrud['permissionCreate'];
                    $permissionRead =   $permissionCrud['permissionRead'];
                    $permissionUpdate = $permissionCrud['permissionUpdate'];
                    $permissionDelete = $permissionCrud['permissionDelete'];
                    $permissionReactivate = $permissionCrud['permissionReactivate'];
                    $permissionDashboard = $permissionCrud['permissionDashboard'];
                    break;
                default:
                    $permissionCreate = false;
                    $permissionRead =   false;
                    $permissionUpdate = false;
                    $permissionDelete = false;
                    $permissionReactivate = false;
                    break;
            }
        }
        $response = [
            'authorized' => $authorized,
            'permissionGlobal' => $permissionGlobal,
            'permissionCreate' => $permissionCreate,
            'permissionRead' => $permissionRead,
            'permissionUpdate' => $permissionUpdate,
            'permissionDelete' => $permissionDelete,
            'permissionReactivate' => $permissionReactivate,
            'permissionDashboard' => $permissionDashboard,
            'permissionImprimir' => $permissionImprimir,
            'permissionAutorizar' => $permissionAutorizar,
            'permissionVerResponsables' => $permissionVerResponsables,
            'permissionVerMontos' => $permissionVerMontos,
            'permissionVerEstados' => $permissionVerEstados,
            'permissionExcel' => $permissionExcel,
            'permissionNota' => $permissionNota,
            'idnivel' => $idnivel,
            'iditem' => $iditem,
        ];
        return $response;
    }

    /**
     * Lists all Mds_certificacion models.
     * @return mixed
     */
    public function actionIndex($area = Mds_certificacion::AREA_SOLICITANTE, $fechaInicio = null, $fechaFin = null, $idlocalidad = null, $idprograma = null, $idcaracter = null, $tipocertificacion = null, $idorganismosolicitante = null, $jubilacion = null, $iddireccion = null)
    {
        // Primero verificamos roles y acceso a paginas
        $permissions = self::verifyPermissionsByRol($area);
        if (!$permissions['authorized']) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        } else {
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
            $hasRolSolicitante = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE);
            $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;
            $idusuario = Yii::$app->user->identity->idusuario;
            $idnivelUser = $permissions['idnivel'];
            $nivelDescripcion = Sds_com_configuracion::findOne($idnivelUser);

            $searchModel = new Mds_certificacionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $fechaInicio, $fechaFin, $idlocalidad, $idprograma, $idcaracter, $tipocertificacion, $idorganismosolicitante, $jubilacion, $iddireccion);

            //Agregar Filtrar por direccion area
            $nivelUser = $permissions['idnivel'];
            $idusuario = Yii::$app->user->identity->idusuario;
            $direccionesUser = Mds_certificacion_direccion::getDireccionesUsuarioByNivel($idusuario, $nivelUser);
            $direccionesUser = array_column($direccionesUser, 'idcertificaciondireccion');

            if ($idnivelUser) {
                // $dataProvider->query->andWhere(['mds_certificacion_direccion.idnivelautorizacion' => $idnivelUser]);
                $dataProvider->query->andWhere(['in', 'mds_certificacion_direccion.idcertificaciondireccion', $direccionesUser]);
            } else {
                if ($area == Mds_certificacion::AREA_SOLICITANTE && $hasRolSolicitante && !$hasRolAdminGeneral) {
                    // $dataProvider->query->andWhere(['mds_certificacion.idusuario_carga' => $idusuario = Yii::$app->user->identity->idusuario]);
                    $dataProvider->query->andWhere(
                        [
                            'or',
                            ['mds_certificacion.idusuario_carga' => $idusuario],
                            ['mds_certificacion.idestado' => Mds_certificacion_estado::ESTADO_OBSERVADA],
                            ['mds_certificacion_estado.idusuario' => $idusuario]
                        ]
                    );
                }
            }

            $direccionesAsignadasUser = Mds_certificacion_direccion_usuario::find()->select('idcertificaciondireccion')->where(['idusuario' => $idusuario, 'deleted_at' => null])->asArray()->all();
            $direccionAsig = array_column($direccionesAsignadasUser, 'idcertificaciondireccion');

            $dataProvider->query->andWhere(['in', 'mds_certificacion.idarea', $direccionAsig]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'idnivelUser' => $idnivelUser,
                'programasFiltro' => $this->getFilterProgramas(),
                'direccionesFiltro' => $this->getFilterDirecciones(),
                'estadosFiltro' => $this->getFilterEstados(),
                'usuarioCargaFiltro' => $this->getFilterUsuarioCarga(),
                'permissionGlobal' => $permissions['permissionGlobal'],
                'permissionCreate' => $permissions['permissionCreate'],
                'permissionRead'   => $permissions['permissionRead'],
                'permissionUpdate' => $permissions['permissionUpdate'],
                'permissionDelete' => $permissions['permissionDelete'],
                'permissionReactivate' => $permissions['permissionReactivate'],
                'permissionImprimir' => $permissions['permissionImprimir'],
                'permissionAutorizar' => $permissions['permissionAutorizar'],
                'permissionVerResponsables' => $permissions['permissionVerResponsables'],
                'permissionVerMontos' => $permissions['permissionVerMontos'],
                'permissionVerEstados' => $permissions['permissionVerEstados'],
                'permissionExcel' => $permissions['permissionExcel'],
                'permissionNota' => $permissions['permissionNota'],
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'hasRolSolicitante' => $hasRolSolicitante,
                'idusuario' => $idusuario,
                'nivelDescripcion' => $nivelDescripcion ? ($nivelDescripcion->idconfiguracion == Mds_certificacion::ID_NIVEL5 ? 'Administración ' : $nivelDescripcion->descripcion) : '',
                'nivelVista' => $permissions['idnivel'] ? $permissions['idnivel'] : 'funcionario',
                'listadoPosiblesEstados' => $listadoPosiblesEstados,
                'area' => $area,
            ]);
        }
    }

    /**
     * Displays a single Mds_certificacion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $sector = null)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area = array_key_exists('area', $params) ? $params['area'] : $sector;

        $permissions = self::verifyPermissionsByRol($area);
        $permissionRead = $permissions['permissionRead'];

        $idusuario = Yii::$app->user->identity->idusuario;

        $direccionesAsignadasUser = Mds_certificacion_direccion_usuario::find()->select('idcertificaciondireccion')
            ->where(['idusuario' => $idusuario, 'deleted_at' => null])->asArray()->all();
        $direccionAsig = array_column($direccionesAsignadasUser, 'idcertificaciondireccion');

        $model = Mds_certificacion::find()->where(['idcertificacion' => $id])
            ->andWhere(['in', 'mds_certificacion.idarea', $direccionAsig])
            ->one();

        if ($permissionRead && $model && ($model->deleted_at == null)) {
            $permissionAction = $this->permisssionAction($id, $area);
            $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion', $id, array());

            $model_responsable = Mds_certificacion_responsable::find()->where(['idcertificacion' => $id, 'deleted_at' => null])->one();
            $model_certificacion_monto = Mds_certificacion_monto::find()->where(['idcertificacion' => $id, 'deleted_at' => null])->one();
            $model_certificacion_estado = Mds_certificacion_estado::getEstadoactual($id);

            $date = date_create($model->periodo_desde);
            $date = date_format($date, 'Y-m-d H:i:s');

            $director_asignado = Mds_certificacion_director::find()
                ->select(['mds_certificacion_director.idcertificaciondirector', 'mds_seg_usuario.apellido', 'mds_seg_usuario.nombre'])
                ->where(['idcertificaciondireccion' => $model->iddireccion, 'idfuncion' => Mds_certificacion_director::ID_FUNCION_DIRECTOR, 'deleted_at' => null])
                ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_director.idusuario')
                ->asArray()
                ->one();
            $director = $director_asignado ? "({$director_asignado['apellido']})" : '';

            $certificacionesResponsable = $this->responsableAsignado($model_responsable->dni, $id);

            $lengthCertificaciones =  count($certificacionesResponsable);
            $infoResponsable = '';
            if ($lengthCertificaciones > 0) {
                $infoRespo = '<p><i class="fa-solid fa-circle-info"></i>El DNI ingresado se encuentra como Responsable de cobro/Tutor especial en ';
                $infoResponsable = ($lengthCertificaciones > 2)  ? "{$infoRespo} las certificaciones: " : "{$infoRespo} la certificación: ";
            }

            foreach ($certificacionesResponsable as $key => $certificacion) {
                $infoResponsable .= "<a href='index.php?r=mds_certificacion/view&id={$certificacion['idcertificacion']}&sector={$area}' target='_blank'>#<b>{$certificacion['idcertificacion']}</b></a>";
                $infoResponsable .=  ($lengthCertificaciones - 2  > $key)  ? ', ' : (($lengthCertificaciones - 1 > $key)  ? ' y ' : '');
            }

            switch ($model->idestado) {
                case $listadoPosiblesEstados['ESTADO_OBSERVADA']:
                    $adjunto_especial = $model->getOtrosAdjuntosObservada();
                    break;
                case $listadoPosiblesEstados['ESTADO_RECHAZADA']:
                    $adjunto_especial = $model->getOtrosAdjuntosRechazada();
                    break;
                case $listadoPosiblesEstados['ESTADO_BAJA']:
                    $adjunto_especial = $model->getOtrosAdjuntosBaja();
                    break;
                default:
                    $adjunto_especial = [];
            }

            return $this->render('view', [
                'model' => $model,
                'model_responsable' => $model_responsable,
                'model_certificacion_monto' => $model_certificacion_monto,
                'model_certificacion_estado' => $model_certificacion_estado,
                'director' => $director,
                'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION,
                'listadoPosiblesEstados' => $listadoPosiblesEstados,
                'adjuntos' => $model->getOtrosAdjuntos(),
                'adjuntosEspeciales' => $adjunto_especial,
                'infoResponsable' => $infoResponsable,
                'area' => $area,
                'ADJUNTO_BAJA' => Mds_certificacion_programa::ADJUNTO_BAJA,
                'ADJUNTO_OBSERVAR' => Mds_certificacion_programa::ADJUNTO_OBSERVAR,
                'ADJUNTO_RECHAZAR' => Mds_certificacion_programa::ADJUNTO_RECHAZAR,
                'permissionAction' => $permissionAction
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCreate()
    {
        // parse_str(Yii::$app->request->url, $params);
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area = array_key_exists('area', $params) ? $params['area'] : 'solicitudes';

        $permissions = self::verifyPermissionsByRol($area);
        $permissionCreate = $permissions['permissionCreate'];

        if ($permissionCreate) {
            $model = new Mds_certificacion();
            $localidades  = [];
            $programas = [];
            $direcciones = [];
            $caracteres = [];
            $tipos_jubilacion = [];
            $adjuntos =  [];
            $tiposDocumentos = $this->getListTiposDocumentos();
            $generos = $this->getListGeneros();
            $nacionalidades = $this->getListNacionalidades();

            $localidades = $this->getListLocalidades();
            $programas = $this->getListProgramas();
            $caracteres = $this->getListCondiciones();
            $tipos_jubilacion = $this->getListTiposJubilacion();
            $organismo_solicitante = $this->getListOrganismoExterno();
            $parentesco = $this->getListParentesco();
            $tipo_responsable = $this->getListTipoResponsable();

            $direcciones = $this->getListDirecciones();
            $niveles_autorizacion = $this->getListDireccionesReceptoras();
            $cantNiveles = count($niveles_autorizacion) - 1; // Menos el ultimo nivel 4
            $model_responsable = new Mds_certificacion_responsable();
            $model_certificacion_monto = new Mds_certificacion_monto();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

            return $this->render('create', [
                'username' => Yii::$app->user->identity->user,
                'model' => $model,
                'generos' => $generos,
                'nacionalidades' => $nacionalidades,
                'tiposDocumentos' => $tiposDocumentos,
                'CARACTER_VIA_RAPIDA' => Mds_certificacion::CARACTER_VIA_RAPIDA,
                'TIPO_JUBILACION_OTRO' => Mds_certificacion::TIPO_JUBILACION_OTRO,
                'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION,
                'PARENTESCO_TITULAR' => mds_certificacion::PARENTESCO_TITULAR,
                'localidades' => $localidades,
                'programas' => $programas,
                'caracteres' => $caracteres,
                'tipos_jubilacion' => $tipos_jubilacion,
                'organismo_solicitante' => $organismo_solicitante,
                'direcciones' => $direcciones,
                'niveles_autorizacion' => $niveles_autorizacion,
                'parentesco' => $parentesco,
                'tipo_responsable' => $tipo_responsable,
                'model_responsable' => $model_responsable,
                'model_certificacion_monto' => $model_certificacion_monto,
                'ID_NIVEL4' => Mds_certificacion::ID_NIVEL4,
                'area' => $area,
                'cantNiveles' => $cantNiveles,
                'adjuntos' => $adjuntos,
                'ID_INCREMENTO' => Mds_certificacion::ID_INCREMENTO,
                'token' => $token
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGuardar_solicitud($area)
    {
        $permissions = self::verifyPermissionsByRol($area);
        $permissionCreate = $permissions['permissionCreate'];

        if ($permissionCreate) {
            $model = new Mds_certificacion();
            $model_responsable = new Mds_certificacion_responsable();

            $model->load(Yii::$app->request->post());
            $transaction = Yii::$app->db->beginTransaction();

            $usuarioAuth = Yii::$app->user->identity;
            $model->idusuario_carga = $usuarioAuth->idusuario;

            $model->created_at = date('Y-m-d H:i:s');
            $validarFechas = compararFechas($model);

            if ($model['deleted_at'] == '0') {
                $model->deleted_at = date('Y-m-d H:i:s');
                $model->idusuario_borra = Yii::$app->user->id;
            } else {
                $model->deleted_at = null;
                $model->idusuario_borra = null;
            }

            $programa = Mds_certificacion_programa::find()->where(['idprograma' => $model->idprograma, 'idcertificaciondireccion' => $model->idarea, 'deleted_at' => NULL])->one();

            if ($programa->requiere_autorizacion == 0) { //el programa va direcamente al nivel 4 (subsecretaria)
                $model->idnivel_autorizacion = Mds_certificacion::ID_NIVEL4;
            }

            if ($validarFechas && $model->validate()) {
                $model->idestado = Mds_certificacion_estado::ESTADO_PENDIENTE;
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

                $model->codigo = $this->generarCodigo();
                $persona = Sds_com_persona::findOne($model->idbeneficiario);

                if ($persona && $persona->documento) {
                    $model->idrisneu = Sds_ris_risneu::getLastIdRisneuByDni($persona->documento);
                }
                if ($model->save()) {
                    $model_certificacion_estado = new Mds_certificacion_estado();
                    $model_certificacion_estado->idcertificacion = $model->idcertificacion;
                    $model_certificacion_estado->idestado = Mds_certificacion_estado::ESTADO_PENDIENTE;
                    $model_certificacion_estado->idusuario = Yii::$app->user->id;
                    $model_certificacion_estado->fecha_inicio = date('Y-m-d H:i:s');
                    $model_certificacion_estado->created_at = date('Y-m-d H:i:s');
                    $model_certificacion_estado->iddireccion = $model->iddireccion;
                    $model_certificacion_estado->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_estado', $model_certificacion_estado->idcertificacionestado, $model_certificacion_estado->getAttributes());

                    if (isset(Yii::$app->request->post()['Mds_certificacion_monto'])) {
                        $model_certificacion_monto = new Mds_certificacion_monto();
                        $model_certificacion_monto->load(Yii::$app->request->post());
                        $model_certificacion_monto->idcertificacion = $model->idcertificacion;
                        $model_certificacion_monto->idusuario_carga = Yii::$app->user->id;
                        $model_certificacion_monto->created_at = date('Y-m-d H:i:s');
                        $model_certificacion_monto->save();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_monto', $model_certificacion_monto->idcertificacionmonto, $model_certificacion_monto->getAttributes());
                    }

                    if (isset(Yii::$app->request->post()['Mds_certificacion_responsable'])) {
                        $nuevoResponsable = Yii::$app->request->post()['Mds_certificacion_responsable'];
                        $model_responsable->idcertificacion = $model->idcertificacion;
                        $model_responsable->nombre_apellido =  $nuevoResponsable['nombre_apellido'];
                        $model_responsable->dni =  $nuevoResponsable['dni'];
                        $model_responsable->cbu_alias =  $nuevoResponsable['cbu_alias'];
                        $esBeneficiario = $model->beneficiario->documento ==  $nuevoResponsable['dni'];

                        if (!$esBeneficiario) { //Si la persona responsable es distinto al beneficiario
                            $model_responsable->curador_legal =  $nuevoResponsable['curador_legal'];
                            $model_responsable->tipo_responsable =  $nuevoResponsable['tipo_responsable'];
                            $model_responsable->rendicion =  $nuevoResponsable['rendicion'];
                            $model_responsable->idparentesco =  array_key_exists('idparentesco', $nuevoResponsable) ? $nuevoResponsable['idparentesco'] : null;
                            $model_responsable->parentesco_otro =  $nuevoResponsable['parentesco_otro'];
                        }
                        if ($esBeneficiario) {
                            $model_responsable->idparentesco = mds_certificacion::PARENTESCO_TITULAR;
                        }

                        $model_responsable->created_at = date('Y-m-d H:i:s');
                        $model_responsable->idusuario_carga = Yii::$app->user->id;
                        $model_responsable->save();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_responsable', $model_responsable->idresponsable, $model_responsable->getAttributes());
                    }

                    // Upload archivo adjunto
                    if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                        $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                        $this->storeAdjuntoOtros($adjuntos, $model);
                    }

                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion', $model->idcertificacion, $model->getAttributes());
                    $apellido = mb_strtoupper($model->beneficiario->apellido);
                    $nombre = mb_strtoupper($model->beneficiario->nombre);
                    Yii::$app->session->setFlash('success', " Se generó correctamente la solicitud al beneficiario <b>{$apellido} {$nombre} ({$model->beneficiario->documento})</b>");
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al guardar la solicitud.");
                }
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
            }
            return $this->redirect(['mds_certificacion/index', 'area' => $area]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_certificacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $sector = null)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area = $sector ? $sector : (array_key_exists('area', $params) ? $params['area'] : 'solicitudes');

        $permissions = self::verifyPermissionsByRol($area);
        $permissionUpdate = $permissions['permissionUpdate'];

        $model = $this->findModel($id);
        $permissionUpdate = $model->permissionUpdate($permissionUpdate, $area);

        if ($permissionUpdate) {
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
            $idusuario = Yii::$app->user->identity->idusuario;
            $request = Yii::$app->request;
            $model_responsable = Mds_certificacion_responsable::find()->where(['idcertificacion' => $model->idcertificacion, 'deleted_at' => null])->one();
            $model_certificacion_monto = Mds_certificacion_monto::find()->where(['idcertificacion' => $model->idcertificacion, 'deleted_at' => null])->one();

            $deletedTemporal = $model->deleted_at;

            if ($model->load($request->post())) {
                if ($deletedTemporal == null) {
                    // Estaba activo y no eliminado
                    if ($model->deleted_at == 0) {
                        // Ahora el registro editado debe eliminarse
                        $model->deleted_at = date('Y-m-d H:i:s');
                        $model->idusuario_borra = $idusuario;
                    } else {
                        $model->deleted_at = null;
                    }
                } else {
                    // Estaba eliminado (no activo)
                    if ($model->deleted_at == 1) {
                        $model->idusuario_borra = null;
                        $model->deleted_at = null;
                    } else {
                        $model->deleted_at = $deletedTemporal;
                    }
                }

                if ($model->jubilacion == '0') {
                    $model->tipo_jubilacion = NULL;
                }
                if ($model->tipo_certificacion == '0') {
                    $model->idorganismo_solicitante = NULL;
                }
                if ($model->periodo_hasta) {
                    $periodo_hasta = armarDateParaMySql($model->periodo_hasta);
                    $periodo_hasta = date_create($periodo_hasta);
                    $periodo_hasta = date_format($periodo_hasta, 'Y-m-d');
                    $model->periodo_hasta = $periodo_hasta;
                }
                $model->updated_at = date('Y-m-d h:i:s');

                $model_estado_previo = Mds_certificacion_estado::find()
                    ->where(['idcertificacion' => $id, 'fecha_fin' => null])
                    ->one();

                //Si Modifico con rol distinto de administracion
                if (!$hasRolAdminGeneral) {
                    $model->idestado = Mds_certificacion_estado::ESTADO_PENDIENTE;

                    $model_estado_previo->fecha_fin = date('Y-m-d H:i:s');
                    $model_estado_previo->updated_at = date('Y-m-d H:i:s');
                    $model_estado_previo->save(); // actualizamos el estado previo en la tabla estado
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_estado', $model_estado_previo->idcertificacionestado, $model_estado_previo->getAttributes());

                    //agregamos el nuevo estado:
                    $model_certificacion_estado = new Mds_certificacion_estado();
                    $model_certificacion_estado->idcertificacion = $id;
                    $model_certificacion_estado->idusuario = $idusuario;
                    $model_certificacion_estado->fecha_inicio = date('Y-m-d H:i:s');
                    $model_certificacion_estado->created_at = date('Y-m-d H:i:s');

                    $model_certificacion_estado->idestado = Mds_certificacion_estado::ESTADO_PENDIENTE;
                    $model_certificacion_estado->iddireccion = $model_estado_previo->iddireccion;

                    if ($model_estado_previo->idestado == Mds_certificacion_estado::ESTADO_OBSERVADA) {
                        //Si el que edita es Solicitante vuelve a estado PENDIENTE AUTORIZACION
                        //Si el que edita es un Nivel vuelve a estado APROBADO
                        $iddireccionActual = null;
                        $model_direccion = Mds_certificacion_direccion::find()
                            ->where(['idcertificaciondireccion' => $model_estado_previo->iddireccion, 'deleted_at' => null])
                            ->one();
                        if ($model_direccion) {
                            $iddireccionActual = $model_direccion->idcertificaciondireccion;
                            $model_certificacion_estado->idestado = Mds_certificacion_estado::ESTADO_APROBADA;
                            $model->idestado = Mds_certificacion_estado::ESTADO_APROBADA;
                        }
                        $direccionSiguiente = Mds_certificacion::getDireccionEstadoSiguente($id, $iddireccionActual);
                        $model_certificacion_estado->iddireccion = $direccionSiguiente;
                    }
                }

                $transaction = Yii::$app->db->beginTransaction();

                if ($model->validate()) {
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion', $model->idcertificacion, $model->getAttributes());
                        if (!$hasRolAdminGeneral) {
                            $model_certificacion_estado->save();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_estado', $model_certificacion_estado->idcertificacionestado, $model_certificacion_estado->getAttributes());
                        }

                        if (isset(Yii::$app->request->post()['Mds_certificacion_monto'])) {
                            $valor = Yii::$app->request->post()['Mds_certificacion_monto']['monto'];
                            if ($valor != $model_certificacion_monto->monto) {
                                //elimino
                                $model_certificacion_monto->idusuario_borra = $idusuario;
                                $model_certificacion_monto->deleted_at = date('Y-m-d H:i:s');
                                $model_certificacion_monto->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_monto', $model_certificacion_monto->idcertificacionmonto, $model_certificacion_monto->getAttributes());

                                //creo nueva
                                $model_certificacion_monto_nuevo = new Mds_certificacion_monto();
                                $model_certificacion_monto_nuevo->load(Yii::$app->request->post());
                                $model_certificacion_monto_nuevo->idcertificacion = $model->idcertificacion;
                                $model_certificacion_monto_nuevo->idusuario_carga = $idusuario;
                                $model_certificacion_monto_nuevo->created_at = date('Y-m-d H:i:s');
                                $model_certificacion_monto_nuevo->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_monto', $model_certificacion_monto_nuevo->idcertificacionmonto, $model_certificacion_monto_nuevo->getAttributes());
                            }
                        }

                        if (isset(Yii::$app->request->post()['Mds_certificacion_responsable'])) {
                            //validamos si cambio algun valor del responsable
                            $nuevoResponsable = Yii::$app->request->post()['Mds_certificacion_responsable'];
                            $cambio = $this->getCambioresponsable($model->idcertificacion, $nuevoResponsable);
                            $responsable = Mds_certificacion_responsable::find()
                                ->where(['idcertificacion' => $model->idcertificacion, "deleted_at" => null])
                                ->one();
                            if ($cambio === true) {
                                //quitamos el anterior
                                $responsable->idusuario_modifica = $idusuario;
                                $responsable->deleted_at = date('Y-m-d H:i:s');
                                $responsable->motivo_cambio =  $nuevoResponsable['motivo_cambio'];
                                $responsable->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_responsable', $responsable->idresponsable, $responsable->getAttributes());
                                //agregamos al nuevo
                                $model_responsable_nuevo = new Mds_certificacion_responsable();
                                $model_responsable_nuevo->idcertificacion = $model->idcertificacion;
                                $model_responsable_nuevo->nombre_apellido =  mb_strtoupper($nuevoResponsable['nombre_apellido']);
                                $model_responsable_nuevo->dni =  $nuevoResponsable['dni'];
                                $model_responsable_nuevo->cbu_alias =  $nuevoResponsable['cbu_alias'];
                                $model_responsable_nuevo->curador_legal =  $nuevoResponsable['curador_legal'];
                                $model_responsable_nuevo->tipo_responsable =  $nuevoResponsable['tipo_responsable'];
                                $model_responsable_nuevo->rendicion =  $nuevoResponsable['rendicion'];
                                $model_responsable_nuevo->idparentesco =  array_key_exists('idparentesco', $nuevoResponsable) ? $nuevoResponsable['idparentesco'] : null;
                                $model_responsable_nuevo->parentesco_otro =  $nuevoResponsable['parentesco_otro'];
                                $model_responsable_nuevo->created_at = date('Y-m-d H:i:s');
                                $model_responsable_nuevo->idusuario_carga = $idusuario;
                                $model_responsable_nuevo->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_responsable', $model_responsable_nuevo->idresponsable, $model_responsable_nuevo->getAttributes());
                            } else {
                                $nuevoResponsable = Yii::$app->request->post()['Mds_certificacion_responsable'];
                                $esBeneficiario = $model->beneficiario->documento ==  $nuevoResponsable['dni'];
                                if ($esBeneficiario) {
                                    $responsable->idparentesco = mds_certificacion::PARENTESCO_TITULAR;
                                    $responsable->parentesco_otro =  null;
                                    $responsable->curador_legal = null;
                                    $responsable->tipo_responsable = null;
                                    $responsable->rendicion = null;
                                } else {
                                    $ingresosResponsable = array_key_exists('curador_legal', $nuevoResponsable) && array_key_exists('tipo_responsable', $nuevoResponsable) && array_key_exists('rendicion', $nuevoResponsable);
                                    if ($ingresosResponsable) {
                                        $responsable->curador_legal = $nuevoResponsable['curador_legal'];
                                        $responsable->tipo_responsable = $nuevoResponsable['tipo_responsable'];
                                        $responsable->rendicion = $nuevoResponsable['rendicion'];
                                    }
                                    $responsable->idparentesco =  array_key_exists('idparentesco', $nuevoResponsable) ? $nuevoResponsable['idparentesco'] : null;
                                    $responsable->parentesco_otro =  $nuevoResponsable['parentesco_otro'];
                                }
                                $responsable->idusuario_modifica = $idusuario;
                                $responsable->updated_at = date('Y-m-d H:i:s');
                                $responsable->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_responsable', $responsable->idresponsable, $responsable->getAttributes());
                            }
                        }
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

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion', $model->idcertificacion, $model->getAttributes());
                        Yii::$app->session->setFlash('success', "Se actualizó correctamente la solicitud #{$model->idcertificacion}, beneficiario {$model->beneficiario->apellido} {$model->beneficiario->nombre}");
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al generar la solicitud.");
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                    $localidades  = [];
                    $programas = [];
                    $direcciones = [];
                    $nacionalidades = [];
                    $generos = [];
                    $tiposDocumentos = [];
                    $caracteres = [];
                    $tipos_jubilacion = [];
                    $nacionalidades = $this->getListNacionalidades();
                    $generos = $this->getListGeneros();
                    $tiposDocumentos = $this->getListTiposDocumentos();
                    $localidades = $this->getListLocalidades();
                    $programas = $this->getListProgramas();
                    $caracteres = $this->getListCondiciones();
                    $tipos_jubilacion = $this->getListTiposJubilacion();
                    $organismo_solicitante = $this->getListOrganismoExterno();
                    $direcciones = $this->getListDirecciones();
                    $niveles_autorizacion = $this->getListDireccionesReceptoras();
                    $cantNiveles = count($niveles_autorizacion) - 1; // Menos el ultimo nivel 4
                    $parentesco = $this->getListParentesco();
                    $adjuntos =  $model->getOtrosAdjuntos();
                    $tipo_responsable = $this->getListTipoResponsable();

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
                        'username' => Yii::$app->user->identity->user,
                        'model' => $model,
                        'generos' => $generos,
                        'nacionalidades' => $nacionalidades,
                        'tiposDocumentos' => $tiposDocumentos,
                        'CARACTER_VIA_RAPIDA' => Mds_certificacion::CARACTER_VIA_RAPIDA,
                        'TIPO_JUBILACION_OTRO' => Mds_certificacion::TIPO_JUBILACION_OTRO,
                        'PARENTESCO_TITULAR' => mds_certificacion::PARENTESCO_TITULAR,
                        'direcciones' => $direcciones,
                        'localidades' => $localidades,
                        'programas' => $programas,
                        'caracteres' => $caracteres,
                        'organismo_solicitante' => $organismo_solicitante,
                        'niveles_autorizacion' => $niveles_autorizacion,
                        'tipos_jubilacion' => $tipos_jubilacion,
                        'parentesco' => $parentesco,
                        'tipo_responsable' => $tipo_responsable,
                        'model_responsable' => $model_responsable,
                        'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION,
                        'model_certificacion_monto' => $model_certificacion_monto,
                        'ID_NIVEL4' => Mds_certificacion::ID_NIVEL4,
                        'cantNiveles' => $cantNiveles,
                        'adjuntos' => $adjuntos,
                        'ID_INCREMENTO' => Mds_certificacion::ID_INCREMENTO,
                        'token' => $token
                    ]);
                }
                return $this->redirect(['mds_certificacion/index', 'area' => $area]);
            } else {
                $localidades  = [];
                $programas = [];
                $direcciones = [];
                $nacionalidades = [];
                $generos = [];
                $tiposDocumentos = [];
                $caracteres = [];
                $tipos_jubilacion = [];
                $nacionalidades = $this->getListNacionalidades();
                $generos = $this->getListGeneros();
                $tiposDocumentos = $this->getListTiposDocumentos();
                $localidades = $this->getListLocalidades();
                $programas = $this->getListProgramas();
                $caracteres = $this->getListCondiciones();
                $tipos_jubilacion = $this->getListTiposJubilacion();
                $organismo_solicitante = $this->getListOrganismoExterno();
                $direcciones = $this->getListDirecciones();
                $niveles_autorizacion = $this->getListDireccionesReceptoras();
                $cantNiveles = count($niveles_autorizacion) - 1; // Menos el ultimo nivel 4
                $parentesco = $this->getListParentesco();
                $adjuntos =  $model->getOtrosAdjuntos();
                $tipo_responsable = $this->getListTipoResponsable();

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
                    'username' => Yii::$app->user->identity->user,
                    'model' => $model,
                    'generos' => $generos,
                    'nacionalidades' => $nacionalidades,
                    'tiposDocumentos' => $tiposDocumentos,
                    'CARACTER_VIA_RAPIDA' => Mds_certificacion::CARACTER_VIA_RAPIDA,
                    'TIPO_JUBILACION_OTRO' => Mds_certificacion::TIPO_JUBILACION_OTRO,
                    'PARENTESCO_TITULAR' => mds_certificacion::PARENTESCO_TITULAR,
                    'direcciones' => $direcciones,
                    'localidades' => $localidades,
                    'programas' => $programas,
                    'caracteres' => $caracteres,
                    'organismo_solicitante' => $organismo_solicitante,
                    'niveles_autorizacion' => $niveles_autorizacion,
                    'tipos_jubilacion' => $tipos_jubilacion,
                    'parentesco' => $parentesco,
                    'tipo_responsable' => $tipo_responsable,
                    'model_responsable' => $model_responsable,
                    'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION,
                    'model_certificacion_monto' => $model_certificacion_monto,
                    'ID_NIVEL4' => Mds_certificacion::ID_NIVEL4,
                    'cantNiveles' => $cantNiveles,
                    'adjuntos' => $adjuntos,
                    'ID_INCREMENTO' => Mds_certificacion::ID_INCREMENTO,
                    'token' => $token,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_certificacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area = $params['area'];
        $permissions = self::verifyPermissionsByRol($area);
        $model = $this->findModel($id);
        $permissionDelete = $model->permissionDelete($permissions['permissionDelete']);

        if ($permissionDelete) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;
            $estado_nuevo = Mds_certificacion_estado::ESTADO_ELIMINADA;
            $model->idestado = $estado_nuevo;

            if ($model->validate()) {
                $observacion = null;
                $fecha = null;
                $guardarEstado = $this->modificarEstadoCertificacion($id, $estado_nuevo, $observacion, $fecha);
                if ($guardarEstado) {
                    $model->save();
                    $apellido = mb_strtoupper($model->beneficiario->apellido);
                    $nombre = mb_strtoupper($model->beneficiario->nombre);
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente la solicitud #{$id}, beneficiario <b>{$apellido} {$nombre} ({$model->beneficiario->documento})</b>");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion', $model->idcertificacion, $model->getAttributes());
                } else {
                    Yii::$app->session->setFlash('error', "Error al borrar la solicitud.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_certificacion/index', 'area' => $area]);
    }

    public function actionReactivate($id)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area = $params['area'];
        $permissions = self::verifyPermissionsByRol($area);
        $model_certificacion = $this->findModel($id);
        $permissionReactivate = $model_certificacion->permissionReactivate($permissions['permissionReactivate']);

        if ($permissionReactivate) {
            $transaction = Yii::$app->db->beginTransaction();

            //Elimino el registro del estado eliminado
            $model_estado_actual = Mds_certificacion_estado::find()
                ->where(['idcertificacion' => $id, 'fecha_fin' => null])
                ->one();
            $model_estado_actual->fecha_fin = date('Y-m-d H:i:s');
            $model_estado_actual->deleted_at = date('Y-m-d H:i:s');

            //Busco el estado anterior a la eliminacion
            $model_estado = Mds_certificacion_estado::find()
                ->where(['idcertificacion' => $id])
                ->andWhere(['<>', 'idestado', Mds_certificacion_estado::ESTADO_ELIMINADA])
                ->orderBy(['idcertificacionestado' => SORT_DESC])
                ->one();
            $model_estado->fecha_fin =  null;

            // Al registro de certificacion lo activo y cambio el estado que guarda
            $model_certificacion->deleted_at = null;
            $model_certificacion->idusuario_borra = null;
            $model_certificacion->idestado = $model_estado->idestado;

            if ($model_estado_actual->update() && $model_estado->update() && $model_certificacion->update()) {
                $transaction->commit();
                $apellido = mb_strtoupper($model_certificacion->beneficiario->apellido);
                $nombre = mb_strtoupper($model_certificacion->beneficiario->nombre);
                Yii::$app->session->setFlash('success', "Se reactivó correctamente la solicitud #{$id}, beneficiario <b>{$apellido} {$nombre} ({$model_certificacion->beneficiario->documento})</b>");
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Error al reactivar la solicitud.");
            }
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion', $model_certificacion->idcertificacion, $model_certificacion->getAttributes());
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_certificacion/index', 'area' => $area]);
    }

    public function actionAprobarindex($valores)
    {
        if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)) {
            parse_str(Yii::$app->request->headers['referer'], $params);
            $area = $params['area'];
            $permissions = self::verifyPermissionsByRol($area);
            $arrIdCertificaciones = explode(",", $valores);
            $arrayIdCertificacionesAprobadas = [];

            foreach ($arrIdCertificaciones as $idcertificacion) {
                $model_certificacion = $this->findModel($idcertificacion);
                $permissionAprobar = $model_certificacion->permissionAprobar($permissions['permissionAutorizar']);

                if ($permissionAprobar) {
                    if ($model_certificacion) {
                        //vemos si el rol es administrador
                        $areaUser = $this->getAreaUser();
                        $accion = $areaUser == 'administracion' ? 'enviar' : 'aprobar';
                        $this->actionActualizarestado($idcertificacion, $accion);
                        array_push($arrayIdCertificacionesAprobadas, $model_certificacion->idcertificacion);
                    }
                }
            }

            $countArrayIdCertificacionesAprobadas = count($arrayIdCertificacionesAprobadas);
            if (($countArrayIdCertificacionesAprobadas) > 0) {
                if ($countArrayIdCertificacionesAprobadas == 1) {
                    $messageSuccess = "Se aprobó correctamente la certificación: {$arrayIdCertificacionesAprobadas[0]}";
                } else {
                    $messageSuccess = " Se aprobaron correctamente las certificaciones: ";
                    $idsCertificaciones = "";
                    foreach ($arrayIdCertificacionesAprobadas as $idcertificacion) {
                        $idsCertificaciones .= "#$idcertificacion, ";
                    }
                    $idsCertificaciones = substr($idsCertificaciones, 0, -2);
                    $messageSuccess .= $idsCertificaciones;
                }
                Yii::$app->session->setFlash('success', $messageSuccess);
            } else {
                $messageSuccess = " Las certificaciones NO pudieron ser aprobadas.";
                Yii::$app->session->setFlash('error', $messageSuccess);
            }
            return $this->redirect(['mds_certificacion/index', 'area' => $params['area']]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionAprobarcolumn($idcertificacion)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area = $params['area'];
        $permissions = self::verifyPermissionsByRol($area);
        $model_certificacion = $this->findModel($idcertificacion);
        $permissionAprobar = $model_certificacion->permissionAprobar($permissions['permissionAutorizar']);

        if ($permissionAprobar) {
            if ($model_certificacion) {
                //vemos si el rol es administrador
                $areaUser = $this->getAreaUser();
                $accion = $areaUser == 'administracion' ? 'enviar' : 'aprobar';
                $this->actionActualizarestado($idcertificacion, $accion);
            } else {
                Yii::$app->session->setFlash('error', "La certificación no existe o ya esta aprobada.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionActualizarestado($id = null, $estado = null)
    {
        if (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)) {
            $estado = $estado ? $estado : Yii::$app->request->post()['estado'];
            $idcertificacion = $id ? $id : Yii::$app->request->post()['idcertificacion_para_actualizar'];
            $observacion = array_key_exists('observaciones', Yii::$app->request->post()) ? Yii::$app->request->post()['observaciones'] : null;
            $sector = array_key_exists('sector', Yii::$app->request->post()) ? Yii::$app->request->post()['sector'] : null;
            $fecha =  array_key_exists('fecha', Yii::$app->request->post()) ? Yii::$app->request->post()['fecha'] : null;
            //form indica de que url proviene, para redireccionar

            switch ($estado) {
                case 'aprobar':
                    $estado_nuevo = Mds_certificacion_estado::ESTADO_APROBADA;
                    $validar = true;
                    break;
                case 'rechazar':
                    $estado_nuevo = Mds_certificacion_estado::ESTADO_RECHAZADA;
                    $validar = $observacion == '' ? false : true;
                    break;
                case 'observar':
                    $estado_nuevo = Mds_certificacion_estado::ESTADO_OBSERVADA;
                    $validar = $observacion == '' ? false : true;
                    break;
                case 'baja':
                    $estado_nuevo = Mds_certificacion_estado::ESTADO_BAJA;
                    $validar = $observacion == '' ? false : true;
                    break;
                case 'enviar':
                    $estado_nuevo = Mds_certificacion_estado::ESTADO_ENVIADA;
                    $validar = true;
                    break;
                default:
                    $estado_nuevo = null;
            }

            if ($validar) {
                if ($idcertificacion && $estado_nuevo) {
                    $model = Mds_certificacion::find()
                        ->where(['idcertificacion' => $idcertificacion])
                        ->andWhere(['deleted_at' => null])
                        ->one();

                    if ($model) {
                        $model->idestado = $estado_nuevo;
                        if ($model->save()) { // actualizo el estado en la certificacion
                            $guardarEstado = $this->modificarEstadoCertificacion($idcertificacion, $estado_nuevo, $observacion, $fecha);
                            if ($guardarEstado) {
                                // Upload archivo adjunto
                                if (isset(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) && !empty(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'])) {
                                    $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                                    $this->storeAdjuntoOtros($adjuntos, $model);
                                }
                                Yii::$app->session->setFlash('success', " Se actualizó correctamente el estado de la certificación.");
                            }
                        } else {
                            Yii::$app->session->setFlash('error', " Error al actualizar el estado en la certificación.");
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', " Error al actualizar el estado de la certificación.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Debe ingresar el motivo para poder {$estado} la certificación.");
            }
            return $this->redirect(['view', 'id' => $idcertificacion, 'sector' => $sector]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCertificacion_detalle($idcertificacion, $area = null)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area =  array_key_exists('area', $params)  ? $params['area'] : $area;
        $permissions = self::verifyPermissionsByRol($area);

        if (!$permissions['permissionImprimir']) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        } else {
            $arrCertificaciones = explode(",", $idcertificacion);
            $certificaciones = [];

            foreach ($arrCertificaciones as $id) {

                $model_certificacion = Mds_certificacion::getCertificacionById($id);

                $listado = [];
                $listado['datos'] = $model_certificacion;

                $model_certificacion_estado = Mds_certificacion_estado::getEstadoactual($id);
                $listado['estado'] = $model_certificacion_estado;

                $model_certificacion_monto = new Mds_certificacion_monto();
                $listado = $model_certificacion_monto->getMontoHistorial($listado, $id);

                $model_certificacion_responsable = new Mds_certificacion_responsable();
                $listado = $model_certificacion_responsable->getResponsablesHistorial($listado, $id);

                $listado['adjuntos'] = $this->getAdjuntosById($id);
                $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;

                switch ($model_certificacion_estado['idestado']) {
                    case $listadoPosiblesEstados['ESTADO_OBSERVADA']:
                        $listado['adjunto_especial'] = $this->getAdjuntosObservadaById($id);
                        break;
                    case $listadoPosiblesEstados['ESTADO_RECHAZADA']:
                        $listado['adjunto_especial'] = $this->getAdjuntosRechazadaById($id);
                        break;
                    case $listadoPosiblesEstados['ESTADO_BAJA']:
                        $listado['adjunto_especial'] = $this->getAdjuntosBajaById($id);
                        break;
                    default:
                        $listado['adjunto_especial'] = null;
                }

                if ($model_certificacion) {
                    array_push($certificaciones, $listado);
                }
            }

            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');

            $content = $this->renderPartial('reporte_detalle_certificacion', [
                'model' => $certificaciones,
                'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => "Certificación_{$idcertificacion}.pdf",
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}div.saltopagina{page-break-after:always}',
                'methods' => [
                    'SetTitle' => "DETALLE DE CERTIFICACIÓN {$idcertificacion}",
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion', $idcertificacion, array());
            return $pdf->render();
        }
    }

    public function actionReporte_certificaciones()
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area =  array_key_exists('area', $params)  ? $params['area'] : '';
        $permissions = self::verifyPermissionsByRol($area);

        if (!$permissions['permissionImprimir']) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        } else {
            $request = Yii::$app->request;
            $get = $request->get();

            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');

            $certificacion = $get['idcertificacion'];
            $monto = $get['monto'];
            $programa = $get['idprograma'];
            $direccion = $get['iddireccion'];
            $expediente = $get['nro_expediente'];

            $desde = $get['periodo_desde'];
            $hasta = $get['periodo_hasta'];

            $model = Mds_certificacion::find()
                ->select('*,mds_certificacion.idcertificacion as id_certificacion,configuracion_programa.descripcion as programa,configuracion_direccion.descripcion as direccion,sds_com_localidad.descripcion as localidadDescripcion, mds_certificacion_monto.monto as monto, configuracion_estado.descripcion as estado, UPPER(mds_certificacion_responsable.nombre_apellido) as responsable')
                ->where(['mds_certificacion.deleted_at' => null])
                ->innerJoin('mds_certificacion_responsable', 'mds_certificacion.idcertificacion = mds_certificacion_responsable.idcertificacion')
                ->innerJoin('sds_com_persona', 'mds_certificacion.idbeneficiario = sds_com_persona.idpersona')
                ->innerJoin('sds_com_configuracion configuracion_programa', 'mds_certificacion.idprograma = configuracion_programa.idconfiguracion')
                //->innerJoin('sds_com_configuracion configuracion_direccion', 'mds_certificacion.iddireccion = configuracion_direccion.idconfiguracion')
                ->innerJoin('sds_com_configuracion configuracion_estado', 'mds_certificacion.idestado = configuracion_estado.idconfiguracion')
                ->leftJoin('sds_com_localidad', 'mds_certificacion.idlocalidad = sds_com_localidad.idlocalidad')
                ->innerJoin('mds_certificacion_monto', 'mds_certificacion.idcertificacion = mds_certificacion_monto.idcertificacion')
                ->andWhere(['mds_certificacion_monto.deleted_at' => null, 'mds_certificacion_responsable.deleted_at' => null])

                ->innerJoin('mds_certificacion_estado', 'mds_certificacion_estado.idcertificacion = mds_certificacion.idcertificacion')
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
                ->innerJoin('sds_com_configuracion configuracion_direccion', 'mds_certificacion_direccion.iddireccion = configuracion_direccion.idconfiguracion');


            if (!empty($programa)) {
                $model->andWhere(['idprograma' => $programa]);
            }

            if (!empty($direccion)) {
                $model->andWhere(['iddireccion' => $direccion]);
            }

            if (!empty($certificacion)) {
                $model->andWhere(['idcertificacion' => $certificacion]);
            }

            if (!empty($expediente)) {
                $model->andWhere(['like', 'nro_expediente', $expediente]);
            }

            if (!empty($monto)) {
                $model->andWhere(['like', 'monto', $monto]);
            }

            if ($desde != "0000-00-00") {
                $model->andFilterWhere(['>=', 'periodo_desde', $desde]);
            }

            if ($hasta != "0000-00-00") {
                $model->andFilterWhere(['<=', 'periodo_hasta', $hasta]);
            }

            $model_certificacion = $model->asArray()->all();
            $content = $this->renderPartial('reporte_detalle_certificaciones', [
                'model' => $model_certificacion,
                'title' => 'REPORTE DE CERTIFICACIONES'
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $content,
                'defaultFontSize' => 12,
                'filename' => 'Certificaciones_reporte.pdf',
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
                'methods' => [
                    'SetTitle' => 'REPORTE CERTIFICACIONES',
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion', null, array());

            return $pdf->render();
        }
    }

    public function actionCertificacion_historica()
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area =  array_key_exists('area', $params)  ? $params['area'] : '';
        $permissions = self::verifyPermissionsByRol($area);

        if (!$permissions['permissionImprimir']) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        } else {

            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');

            $request = Yii::$app->request;
            $get = $request->get();
            $persona = $get['id'];

            $arrayRegistros = Mds_certificacion::find()
                ->select('*,mds_certificacion.idcertificacion as id_certificacion,configuracion_programa.descripcion as programa,
                            configuracion_direccion.descripcion as direccion,
                            sds_com_localidad.descripcion as localidadDescripcion,
                            configuracion_estado.descripcion as estado,
                            UPPER(mds_certificacion_responsable.nombre_apellido) as responsable')
                ->where(['mds_certificacion.idbeneficiario' => $persona])
                ->andWhere(['mds_certificacion.deleted_at' => null])
                ->innerJoin('mds_certificacion_responsable', 'mds_certificacion.idcertificacion = mds_certificacion_responsable.idcertificacion')
                ->innerJoin('sds_com_persona', 'mds_certificacion.idbeneficiario = sds_com_persona.idpersona')
                ->innerJoin('sds_com_configuracion configuracion_programa', 'mds_certificacion.idprograma = configuracion_programa.idconfiguracion')
                //->innerJoin('sds_com_configuracion configuracion_direccion', 'mds_certificacion.iddireccion = configuracion_direccion.idconfiguracion')
                ->innerJoin('sds_com_configuracion configuracion_estado', 'mds_certificacion.idestado = configuracion_estado.idconfiguracion')
                ->innerJoin('mds_certificacion_monto', 'mds_certificacion.idcertificacion = mds_certificacion_monto.idcertificacion')

                ->innerJoin('mds_certificacion_estado certificacion_estado', 'certificacion_estado.idcertificacion = mds_certificacion.idcertificacion')
                ->innerJoin('mds_certificacion_direccion certificacion_direccion', 'certificacion_direccion.idcertificaciondireccion = certificacion_estado.iddireccion')
                ->innerJoin('sds_com_configuracion configuracion_direccion', 'certificacion_direccion.iddireccion = configuracion_direccion.idconfiguracion')

                ->leftJoin('sds_com_localidad', 'mds_certificacion.idlocalidad = sds_com_localidad.idlocalidad')
                ->andWhere(['mds_certificacion_monto.deleted_at' => null, 'mds_certificacion_responsable.deleted_at' => null])
                ->orderBy(['mds_certificacion.periodo_desde' => SORT_DESC])
                ->asArray()
                ->all();

            $content = $this->renderPartial('reporte_detalle_certificaciones', [
                'model' => $arrayRegistros, 'title' => 'REPORTE HISTORICO DE CERTIFICACIONES'
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => 'Certificación.pdf',
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
                'methods' => [
                    'SetTitle' => 'REPORTE CERTIFICACION-HISTORICO',
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion', null, array());
            return $pdf->render();
        }
    }

    public function actionHistorial_responsables()
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $area =  array_key_exists('area', $params)  ? $params['area'] : '';
        $permissions = self::verifyPermissionsByRol($area);

        if ($permissions['permissionVerResponsables']) {
            $listado = [];
            $model_certificacion_responsable = new Mds_certificacion_responsable();
            $model_certificacion = Mds_certificacion::find()
                ->where(['idcertificacion' => Yii::$app->request->queryParams['idcertificacion']])
                ->one();

            $responsables = $model_certificacion_responsable->getResponsablesHistorial($listado, Yii::$app->request->queryParams['idcertificacion']);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_responsable', null, array());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Listado Responsable de cobro/Tutor especial<br>
                        Certificación <b>#{$model_certificacion->idcertificacion}, {$model_certificacion->beneficiario->apellido} {$model_certificacion->beneficiario->nombre}</b>",
                'content' => $this->renderAjax('modal_responsable', [
                    'model' => $responsables['responsables'],
                    'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION,
                ])
            ];
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionVer_responsables()
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE) ||
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $listado = [];
            $model_certificacion_responsable = new Mds_certificacion_responsable();
            $responsables = $model_certificacion_responsable->getResponsablesHistorial($listado, Yii::$app->request->queryParams['idcertificacion']);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_responsable', null, array());

            return $this->renderAjax('modal_responsable', [
                'model' => $responsables['responsables'],
                'PARENTESCO_OTRO_OPTION' => Mds_certificacion::PARENTESCO_OTRO_OPTION,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionHistorial_estados()
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE) ||
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5) || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
        ) {
            $listado = [];
            $idcertificacion = Yii::$app->request->queryParams['idcertificacion'];
            $beneficiario = Mds_certificacion::find()->select('beneficiario.nombre,beneficiario.apellido')
                ->where(['idcertificacion' => $idcertificacion])
                ->innerJoin('sds_com_persona beneficiario', 'mds_certificacion.idbeneficiario = beneficiario.idpersona')
                ->asArray()->one();
            $model_certificacion_estado = new Mds_certificacion_estado();
            $estados = $model_certificacion_estado->getEstadosHistorial($listado, Yii::$app->request->queryParams['idcertificacion']);
            $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_estados', null, array());
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => "Listado estados previos certificación <b>#{$idcertificacion}, {$beneficiario['apellido']} {$beneficiario['nombre']}</b>",
                'content' => $this->renderAjax('modal_estado', [
                    'model' => $estados['estados'],
                    'listadoPosiblesEstados' => $listadoPosiblesEstados
                ])
            ];
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionVer_estados()
    {
        $listado = [];
        $model_certificacion_estado = new Mds_certificacion_estado();
        $estados = $model_certificacion_estado->getEstadosHistorial($listado, Yii::$app->request->queryParams['idcertificacion']);
        $listadoPosiblesEstados = Mds_certificacion_estado::LISTADO_ESTADOS;

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_estados', null, array());

        return $this->renderAjax('modal_estado', [
            'model' => $estados['estados'],
            'listadoPosiblesEstados' => $listadoPosiblesEstados
        ]);
    }

    public function actionVer_montos()
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $modelCertificacionMonto = new Mds_certificacion_monto();
            $idCertificacion = Yii::$app->request->queryParams['idcertificacion'];
            $montos = $modelCertificacionMonto->getMontoHistorial([], $idCertificacion);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_monto', null, array());
            $viewParams = ['model' => $montos['montos']];

            if (isset(Yii::$app->request->queryParams['calledFrom'])) {
                $beneficiario = Mds_certificacion::find()->select('beneficiario.nombre,beneficiario.apellido')
                    ->where(['idcertificacion' => $idCertificacion])
                    ->innerJoin('sds_com_persona beneficiario', 'mds_certificacion.idbeneficiario = beneficiario.idpersona')
                    ->asArray()->one();

                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'title' => "Montos de certificación <b>#{$idCertificacion}, {$beneficiario['apellido']} {$beneficiario['nombre']}</b>",
                    'content' => $this->renderAjax('modal_montos', $viewParams)
                ];
            }
            return $this->renderAjax('modal_montos', $viewParams);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionValidar_dni($dni, $idllamada = null)
    {  //se comento en form y tambien aca por que ya no es obligatorio tener risneu.

        //Busco la persona, si existe traigo los datos para editar
        // if (($dni != '') && (is_numeric($dni))) {
        //     Yii::$app->response->format = Response::FORMAT_JSON;
        //     $result = array();

        //     $connection = Yii::$app->getDb();
        //     $model = $connection->createCommand(
        //         "SELECT
        //         risneu.idrisneu,
        //         risper.idpersona,
        //         (case when (risneu.updated_at)
        //         THEN
        //         CONCAT('fecha de modificación ',DATE_FORMAT(risneu.updated_at,'%d/%m/%Y %H:%ih'))
        //         ELSE
        //         CONCAT('fecha de creación ',DATE_FORMAT(risneu.fecha_carga,'%d/%m/%Y %H:%ih'))
        //         END) as fechaRisneu                
        //         FROM sds_ris_persona risper
        //         JOIN sds_com_persona persona ON persona.idpersona=risper.idpersona
        //         JOIN sds_ris_risneu risneu ON risneu.idrisneu=risper.idrisneu
        //         WHERE
        //         persona.documento='$dni'
        //         and risper.activo = 1
        //         and risneu.activo = 1
        //         and risneu.deleted_at is null
        //         ORDER BY risneu.updated_at DESC, risneu.idrisneu DESC"
        //     )->queryOne();

        //     if ($model == null) {
        //         $model = $connection->createCommand(
        //             "SELECT risneu.idrisneu,
        //              (case when (risneu.updated_at)
        //              THEN
        //              CONCAT('fecha de modificación ',DATE_FORMAT(risneu.updated_at,'%d/%m/%Y %H:%ih'))
        //              ELSE
        //              CONCAT('fecha de creación ',DATE_FORMAT(risneu.fecha_carga,'%d/%m/%Y %H:%ih'))
        //              END) as fechaRisneu     
        //              FROM sds_ris_risneu risneu
        //              WHERE 
        //              risneu.dni='$dni' 
        //              and risneu.activo = 1 
        //              and risneu.deleted_at is null"
        //         )->queryOne();

        //         if ($model) {
        //             Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion/validar_dni', $dni, array());
        //             array_push($result, $model);
        //         } else {
        //             return $this->redirect([
        //                 'sds_ris_risneu/create',
        //                 'finalizar' => false,
        //                 'dni' => $dni,
        //                 'origen' => 'index.php?r=mds_certificacion/create',
        //                 'idllamada' => $idllamada
        //             ]);
        //         }
        //     } else {
        //         Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion/validar_dni', $dni, array());
        //         array_push($result, $model);
        //     }
        //     return json_encode($result);
        // }
        return null;
    }

    public function actionDashboard()
    {
        $permissions = self::getPermissionsCrud();

        if ($permissions['permissionDashboard']) {
            /*
            Cantidad de registros certificaciones (activas)
            */
            $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
            $fechaFin = null;
            $fechaFinOriginal = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;
            if ($fechaFinOriginal) {
                $fechaFin = date_create($fechaFinOriginal);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }

            $model = new Mds_certificacion();
            $where = "mds_certificacion.deleted_at IS NULL";
            if ($fechaInicio && $fechaFin) {
                $where .= " AND mds_certificacion.created_at >= '$fechaInicio' AND mds_certificacion.created_at <= '$fechaFin'";
            } else if ($fechaInicio) {
                $where .= " AND mds_certificacion.created_at >= '$fechaInicio'";
            } else if ($fechaFin) {
                $where .= " AND mds_certificacion.created_at <= '$fechaFin'";
            }
            $totalCertificaciones = $model->find()
                ->select([
                    'idlocalidad',
                    'idprograma',
                    'idcaracter',
                    'iddireccion',
                    'tipo_certificacion',
                    'idorganismo_solicitante',
                    'jubilacion'
                ])
                ->where($where)
                ->all();

            $arrayLocalidades = $model->find()
                ->select(['localidad.idlocalidad', 'UPPER(CONCAT(localidad.descripcion, \' (\', provincia.descripcion, \') \')) AS descripcion'])
                ->join("inner join", "sds_com_localidad as localidad", "localidad.idlocalidad = mds_certificacion.idlocalidad")
                ->join("inner join", "sds_com_provincia as provincia", "provincia.idprovincia = localidad.idprovincia")
                ->where($where)
                ->groupBy(['localidad.idlocalidad'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayProgramas = $model->find()
                ->select([
                    'programa.idconfiguracion as idprograma',
                    'programa.descripcion as descripcion',
                ])
                ->join("inner join", "sds_com_configuracion as programa", "programa.idconfiguracion = mds_certificacion.idprograma")
                ->where($where)
                ->groupBy(['mds_certificacion.idprograma'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayCaracteres = $model->find()
                ->select([
                    'caracter.idconfiguracion as idcaracter',
                    'caracter.descripcion as descripcion',
                ])
                ->join("inner join", "sds_com_configuracion as caracter", "caracter.idconfiguracion = mds_certificacion.idcaracter")
                ->where($where)
                ->groupBy(['mds_certificacion.idcaracter'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayTipoCertificacion = [
                [
                    'descripcion' => 'Interna',
                    'titulo' => 'Tipo Certificación',
                    'cantidadRegistros' => 0,
                    'url' => '&tipocertificacion=0',
                ],
                [
                    'descripcion' => 'Externa',
                    'titulo' => 'Tipo Certificación',
                    'cantidadRegistros' => 0,
                    'url' => '&tipocertificacion=1',
                ]
            ];

            $arrayOrganismosSolicitantes = $model->find()
                ->select([
                    'organismoSolicitante.idconfiguracion as idorganismo_solicitante',
                    'organismoSolicitante.descripcion as descripcion',
                ])
                ->join("inner join", "sds_com_configuracion as organismoSolicitante", "organismoSolicitante.idconfiguracion = mds_certificacion.idorganismo_solicitante")
                ->where($where)
                ->groupBy(['mds_certificacion.idorganismo_solicitante'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayDirecciones = $model->find()
                ->select([
                    'mds_certificacion.iddireccion',
                    'direccion.descripcion',
                ])
                ->join("inner join", "mds_certificacion_direccion as certificacionDireccion", "certificacionDireccion.idcertificaciondireccion = mds_certificacion.iddireccion")
                ->join("inner join", "sds_com_configuracion as direccion", "direccion.idconfiguracion = certificacionDireccion.iddireccion")
                ->where($where)
                ->groupBy(['mds_certificacion.iddireccion'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayJubilacion = [
                [
                    'descripcion' => 'Si',
                    'titulo' => 'Recibe jubilación/pensión',
                    'cantidadRegistros' => 0,
                    'url' => '&jubilacion=1',
                ],
                [
                    'descripcion' => 'No',
                    'titulo' => 'Recibe jubilación/pensión',
                    'cantidadRegistros' => 0,
                    'url' => '&jubilacion=0',
                ],
                [
                    'descripcion' => 'Sin Datos',
                    'titulo' => 'Recibe jubilación/pensión',
                    'cantidadRegistros' => 0,
                    'url' => '&jubilacion=2',
                ]
            ];

            foreach ($totalCertificaciones as $certificacion) {
                $this->contarCantidadRegsitros($arrayLocalidades, $certificacion, 'idlocalidad', 'Localidades');
                $this->contarCantidadRegsitros($arrayProgramas, $certificacion, 'idprograma', 'Programas');
                $this->contarCantidadRegsitros($arrayCaracteres, $certificacion, 'idcaracter', 'Carácter');
                $this->contarCantidadRegsitros($arrayOrganismosSolicitantes, $certificacion, 'idorganismo_solicitante', 'Organismo Solicitante (Tipo Certificación Externa)');
                $this->contarCantidadRegsitros($arrayDirecciones, $certificacion, 'iddireccion', 'Direcciones');

                if ($certificacion['jubilacion'] == 1) {
                    $arrayJubilacion[0]['cantidadRegistros']++;
                } else if (is_null($certificacion['jubilacion'])) {
                    $arrayJubilacion[2]['cantidadRegistros']++;
                } else {
                    $arrayJubilacion[1]['cantidadRegistros']++;
                }
            }

            $this->usortArrayByCantidadRegistros($arrayDirecciones);
            $this->usortArrayByCantidadRegistros($arrayProgramas);
            $this->usortArrayByCantidadRegistros($arrayLocalidades);
            $this->usortArrayByCantidadRegistros($arrayCaracteres);
            $this->usortArrayByCantidadRegistros($arrayTipoCertificacion);
            $this->usortArrayByCantidadRegistros($arrayOrganismosSolicitantes);
            $this->usortArrayByCantidadRegistros($arrayJubilacion);

            $arrayIndicadores = array_merge(
                $arrayDirecciones,
                $arrayProgramas,
                $arrayLocalidades,
                $arrayCaracteres,
                $arrayTipoCertificacion,
                $arrayOrganismosSolicitantes,
                $arrayJubilacion,
            );

            switch (true) {
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO):
                    $urlArea = "funcionario";
                    break;
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5):
                    $urlArea = "na5";
                    break;
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4):
                    $urlArea = "na4";
                    break;
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3):
                    $urlArea = "na3";
                    break;
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2):
                    $urlArea = "na2";
                    break;
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1):
                    $urlArea = "na1";
                    break;
                case Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE):
                    $urlArea = "solicitudes";
                    break;
                default:
                    $urlArea = null; //si ninguna condición se cumple.
            }

            $urlIndex = "index.php?r=mds_certificacion&area=" . $urlArea;
            return $this->render('dashboard/index', [
                'totalCertificaciones' => $totalCertificaciones,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFinOriginal,
                'arrayIndicadores' => $arrayIndicadores,
                'urlIndex' => $urlIndex,
                'urlArea' => $urlArea,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    public function actionGet_adjuntos($id)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $adjuntos =  Mds_legales_archivo::find()
                ->select(['mds_legales_archivo.*', 'configuracion.descripcion as tipoAdjunto'])
                ->where(
                    [
                        'mds_legales_archivo.objeto' => 'mds_certificacion',
                        'mds_legales_archivo.activo' => true
                    ]
                )
                ->innerJoin('sds_com_configuracion configuracion', 'mds_legales_archivo.tipo = configuracion.idconfiguracion')
                ->andWhere(['=', 'id_objeto', $id])
                ->asArray()
                ->all();
            return json_encode($adjuntos);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionResponsable_asignado($dniResponsable, $idcertificacion)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $responsablesCertificacion = Mds_certificacion_responsable::find()
                ->select('certificacion.idcertificacion')
                ->where(['mds_certificacion_responsable.dni' => $dniResponsable, 'mds_certificacion_responsable.deleted_at' => NULL, 'certificacion.deleted_at' => null])
                ->andWhere(['<>', 'certificacion.idcertificacion', $idcertificacion])
                ->innerJoin('mds_certificacion certificacion', 'mds_certificacion_responsable.idcertificacion = certificacion.idcertificacion')
                ->orderBy(['certificacion.idcertificacion' => SORT_ASC])
                ->asArray()
                ->all();
            return json_encode($responsablesCertificacion);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    private function responsableAsignado($dniResponsable, $idcertificacion)
    {
        $responsablesCertificacion = Mds_certificacion_responsable::find()
            ->select('certificacion.idcertificacion')
            ->innerJoin('mds_certificacion certificacion', 'mds_certificacion_responsable.idcertificacion = certificacion.idcertificacion')
            ->where(['mds_certificacion_responsable.dni' => $dniResponsable, 'mds_certificacion_responsable.deleted_at' => NULL, 'certificacion.deleted_at' => null])
            ->andWhere('certificacion.idcertificacion !=' . $idcertificacion)
            ->orderBy(['certificacion.idcertificacion' => SORT_ASC])
            ->asArray()
            ->all();
        return $responsablesCertificacion;
    }

    /**
     * Finds the Mds_certificacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_certificacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_certificacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListDirecciones0()
    {
        //Busqueda direcciones
        $idUsuarioAuth = Yii::$app->user->identity->idusuario;
        $direccionesAsignadas = Mds_certificacion_direccion_usuario::find()
            ->select('mds_certificacion_direccion_usuario.iddireccion,configuracion.idconfiguracion,configuracion.descripcion')
            ->where(['mds_certificacion_direccion_usuario.idusuario' => $idUsuarioAuth, 'mds_certificacion_direccion_usuario.deleted_at' => null, 'mds_certificacion_direccion.idnivelautorizacion' => Mds_certificacion::ID_NIVEL3])
            ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=mds_certificacion_direccion_usuario.iddireccion')
            ->innerJoin('sds_com_configuracion configuracion', 'configuracion.idconfiguracion=mds_certificacion_direccion_usuario.iddireccion')
            ->asArray()
            ->all();

        $direcciones = ArrayHelper::map($direccionesAsignadas, 'idconfiguracion', 'descripcion');
        return $direcciones;
    }

    protected function getListDirecciones()
    {
        $idUsuarioAuth = Yii::$app->user->identity->idusuario;
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $direccionesUsuario = Mds_certificacion_direccion::find()
                ->select(['sds_com_configuracion.idconfiguracion as iddireccion', 'mds_certificacion_direccion.idcertificaciondireccion as idcertificaciondireccion', 'UPPER(sds_com_configuracion.descripcion) as descripcion'])
                ->innerJoin('mds_certificacion_programa', 'mds_certificacion_direccion.idcertificaciondireccion=mds_certificacion_programa.idcertificaciondireccion')
                ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->groupBy('mds_certificacion_direccion.iddireccion')
                ->where(['mds_certificacion_direccion.deleted_at' => null, 'mds_certificacion_programa.deleted_at' => null])
                ->orderBy('sds_com_configuracion.descripcion')
                ->asArray()
                ->all();
        } else {
            $direccionesUsuario = Mds_certificacion_direccion_usuario::findBySql(
                "SELECT
                configuracion.idconfiguracion as iddireccion,
                direccion.idcertificaciondireccion as idcertificaciondireccion,
                UPPER (configuracion.descripcion) as descripcion
                FROM mds_certificacion_direccion_usuario direccion_usuario
                INNER JOIN mds_certificacion_direccion direccion
                ON  direccion_usuario.idcertificaciondireccion = direccion.idcertificaciondireccion
                INNER JOIN sds_com_configuracion configuracion
                ON direccion.iddireccion = configuracion.idconfiguracion
                WHERE direccion_usuario.deleted_at IS NULL AND direccion.deleted_at IS NULL AND direccion_usuario.idusuario = $idUsuarioAuth
                ORDER BY descripcion ASC
            "
            )->asArray()->all();
        }
        $direccionesUsuario = ArrayHelper::map($direccionesUsuario, 'idcertificaciondireccion', 'descripcion');
        return $direccionesUsuario;
    }

    protected function getListLocalidades()
    {
        //Busqueda localidades
        $localidades = Sds_com_localidad::find()->where(['idprovincia' => Mds_certificacion::ID_PROVINCIA_NEUQUEN, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');
        return $localidades;
    }

    protected function getListProgramas()
    {
        //Busqueda programas
        $programas = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_PROGRAMA, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()->all();
        $programas = ArrayHelper::map($programas, 'idconfiguracion', 'descripcion');
        return $programas;
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

    protected function getListCondiciones()
    {
        //Busqueda condicion
        $condicion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_CONDICION, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $condiciones = ArrayHelper::map($condicion, 'idconfiguracion', 'descripcion');
        return $condiciones;
    }

    protected function getListTiposJubilacion()
    {
        //Busqueda riesgos
        $tipo_jubilacion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_TIPO_JUBILACION, "activo" => 1])->asArray()->all();
        $tipos = ArrayHelper::map($tipo_jubilacion, 'idconfiguracion', 'descripcion');
        return $tipos;
    }

    protected function getListDireccionesReceptoras()
    {
        //Busqueda direcciones receptoras
        $direccion = Sds_com_configuracion::find() // quitamos la opcion subsecretaria y administracion
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_DIRECCION_RECEPTORA, 'activo' => 1])
            //->andWhere('idconfiguracion !=' . Mds_certificacion::ID_NIVEL4)
            ->andWhere('idconfiguracion !=' . Mds_certificacion::ID_NIVEL5)
            ->asArray()
            ->all();
        $direcciones = ArrayHelper::map($direccion, 'idconfiguracion', 'descripcion');
        return $direcciones;
    }

    protected function getListOrganismoExterno()
    {
        //Busqueda organismos externos
        $organismo = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_TIPO_EXTERNA, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()->all();
        $organismos = ArrayHelper::map($organismo, 'idconfiguracion', 'descripcion');
        return $organismos;
    }

    protected function getListParentesco()
    {
        //Busqueda relaciones vinculares
        $relacion = Sds_com_configuracion::find()
            ->select(['sds_com_configuracion.idconfiguracion', 'SUBSTRING_INDEX(sds_com_configuracion.descripcion, ".", -1) AS descripcion'])
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_PARENTEZCO, "activo" => 1])
            ->andWhere('idconfiguracion != 60') // traemos todos menos jefe
            ->asArray()
            ->all();
        $relaciones = ArrayHelper::map($relacion, 'idconfiguracion', 'descripcion');
        return $relaciones;
    }
    protected function getListTipoResponsable()
    {
        //Busqueda tipo de responable de cobro
        $tipo_responsable = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_TIPO_RESPONSABLE, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()->all();
        $tipos = ArrayHelper::map($tipo_responsable, 'idconfiguracion', 'descripcion');
        return $tipos;
    }

    protected function getFilterProgramas()
    {
        //Busqueda de programas en configuracion
        $programasFiltro = Sds_com_configuracion::findBySql(
            "SELECT idcertificacion,
                configuracion.idconfiguracion as conf_idconfiguracion,
                configuracion.descripcion as conf_descripcion
                FROM mds_certificacion certificacion
                INNER JOIN sds_com_configuracion configuracion
                ON certificacion.idprograma = configuracion.idconfiguracion
                WHERE certificacion.idprograma
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY conf_descripcion ASC
                "
        )->asArray()->all();

        $programasFiltro = ArrayHelper::map($programasFiltro, 'conf_idconfiguracion', 'conf_descripcion');
        return $programasFiltro;
    }

    protected function getFilterDirecciones()
    {
        $idusuario = Yii::$app->user->identity->idusuario;
        $direccionesAsignadasUser = Mds_certificacion_direccion_usuario::find()->select('idcertificaciondireccion')->where(['idusuario' => $idusuario, 'deleted_at' => null])->asArray()->all();

        $direccionAsig = [];
        if ($direccionesAsignadasUser) {
            foreach ($direccionesAsignadasUser as $direccion) {
                $direccionAsig[] = $direccion['idcertificaciondireccion'];
            }
        }

        $arrDirecciones = [];
        foreach ($direccionAsig as $direccion) {

            //Provincial
            $direccion =
                Sds_com_configuracion::find()
                ->select('sds_com_configuracion.idconfiguracion AS conf_idconfiguracion, UPPER(sds_com_configuracion.descripcion) as descripcionDireccion, mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion, mds_certificacion_direccion.iddireccion AS iddireccion, mds_certificacion_direccion.iddireccion_padre AS iddireccion_padre')
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->where(['mds_certificacion_direccion.idcertificaciondireccion' => $direccion, 'mds_certificacion_direccion.deleted_at' => null])
                ->asArray()
                ->one();

            array_push($arrDirecciones, $direccion);

            //Subse
            $direccionN5 =
                Sds_com_configuracion::find()
                ->select('sds_com_configuracion.idconfiguracion AS conf_idconfiguracion, UPPER(sds_com_configuracion.descripcion) as descripcionDireccion, mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion, mds_certificacion_direccion.iddireccion AS iddireccion, mds_certificacion_direccion.iddireccion_padre AS iddireccion_padre')
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->where(['mds_certificacion_direccion.iddireccion' => $direccion['iddireccion_padre'], 'mds_certificacion_direccion.deleted_at' => null])
                ->asArray()
                ->one();
            array_push($arrDirecciones, $direccionN5);

            //Admin
            $administracion =
                Sds_com_configuracion::find()
                ->select('sds_com_configuracion.idconfiguracion AS conf_idconfiguracion, UPPER(sds_com_configuracion.descripcion) as descripcionDireccion, mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion, mds_certificacion_direccion.iddireccion AS iddireccion, mds_certificacion_direccion.iddireccion_padre AS iddireccion_padre')
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->where(['mds_certificacion_direccion.iddireccion' => $direccionN5['iddireccion_padre'], 'mds_certificacion_direccion.deleted_at' => null])
                ->asArray()
                ->one();
            array_push($arrDirecciones, $administracion);

            //Genereales
            $direcciones =
                Sds_com_configuracion::find()
                ->select('sds_com_configuracion.idconfiguracion AS conf_idconfiguracion, UPPER(sds_com_configuracion.descripcion) as descripcionDireccion, mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion, mds_certificacion_direccion.iddireccion AS iddireccion')
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->where(['mds_certificacion_direccion.iddireccion_padre' => $direccion['iddireccion'], 'mds_certificacion_direccion.deleted_at' => null])->asArray()
                ->asArray()
                ->all();
            $arrDirecciones = array_merge($arrDirecciones, $direcciones);

            //Simples
            foreach ($direcciones as $direccion) {
                $direcciones =
                    Sds_com_configuracion::find()
                    ->select('sds_com_configuracion.idconfiguracion AS conf_idconfiguracion, UPPER(sds_com_configuracion.descripcion) as descripcionDireccion, mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion, mds_certificacion_direccion.iddireccion AS iddireccion')
                    ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                    ->where(['mds_certificacion_direccion.iddireccion_padre' => $direccion['iddireccion'], 'mds_certificacion_direccion.deleted_at' => null])->asArray()
                    ->asArray()
                    ->all();
                $arrDirecciones = array_merge($arrDirecciones, $direcciones);
            }
        }

        $direccionesFiltro = ArrayHelper::map($arrDirecciones, 'idcertificaciondireccion', 'descripcionDireccion');
        $direccionesFiltro[0] = 'SOLICITANTE';
        asort($direccionesFiltro);
        return $direccionesFiltro;
    }

    protected function getFilterDireccionesPrevias($nivelUser)
    {
        $idusuario = Yii::$app->user->identity->idusuario;

        $direccionesUser = Mds_certificacion_direccion::getDireccionesUsuarioByNivel($idusuario, $nivelUser);
        $direccionesUser = array_column($direccionesUser, 'idcertificaciondireccion');

        $direcionesPrevias = Mds_certificacion_direccion::getDireccionesPrevias($direccionesUser);
        $direcionesPrevias = array_column($direcionesPrevias, 'idcertificaciondireccion');

        $direccionesFiltro = Sds_com_configuracion::find()
            ->select('sds_com_configuracion.idconfiguracion as configDireccion,UPPER(sds_com_configuracion.descripcion) as descripcionDireccion,mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion')
            ->innerJoin('mds_certificacion_direccion', 'sds_com_configuracion.idconfiguracion = mds_certificacion_direccion.iddireccion')
            ->where(['IN', 'mds_certificacion_direccion.idcertificaciondireccion', $direcionesPrevias])
            ->orderBy('sds_com_configuracion.descripcion')
            ->asArray()
            ->all();

        $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'idcertificaciondireccion', 'descripcionDireccion');
        if ($nivelUser == Mds_certificacion::ID_NIVEL2 || $nivelUser == Mds_certificacion::ID_NIVEL3 || $nivelUser == Mds_certificacion::ID_NIVEL4) {
            $direccionesFiltro[1] = 'SOLICITANTE';
        }
        asort($direccionesFiltro);
        return $direccionesFiltro;
    }

    protected function getFilterEstados()
    {
        //Busqueda estados
        $estadosFiltro = Sds_com_configuracion::findBySql(
            "SELECT idcertificacion,
                configuracion.idconfiguracion as conf_idconfiguracion,
                configuracion.descripcion as conf_descripcion
                FROM mds_certificacion certificacion
                INNER JOIN sds_com_configuracion configuracion
                ON certificacion.idestado = configuracion.idconfiguracion
                WHERE certificacion.idestado
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY conf_descripcion ASC
                "
        )->asArray()->all();
        $estadosFiltro = ArrayHelper::map($estadosFiltro, 'conf_idconfiguracion', 'conf_descripcion');
        return $estadosFiltro;
    }

    protected function getFilterUsuarioCarga()
    {
        //Busqueda de usuarios que cargaron certificaciones
        $usuarioFiltro = Mds_certificacion::findBySql(
            "SELECT certificacion.idcertificacion, 
                certificacion.idusuario_carga,
                CONCAT(UPPER(usuario.apellido),', ', UPPER(usuario.nombre)) as usuarioNombre
                FROM mds_certificacion certificacion 
                INNER JOIN mds_seg_usuario usuario 
                ON certificacion.idusuario_carga = usuario.idusuario
                WHERE certificacion.idusuario_carga 
                ORDER BY usuarioNombre ASC
                "
        )->asArray()->all();
        $usuarioFiltro = ArrayHelper::map($usuarioFiltro, 'idusuario_carga', 'usuarioNombre');
        return $usuarioFiltro;
    }

    protected function getAdjuntosById($idcertificacion)
    {
        //Busqueda archivos de la certificacion
        $adjuntos_especiales = [Mds_certificacion_programa::ADJUNTO_OBSERVAR, Mds_certificacion_programa::ADJUNTO_BAJA, Mds_certificacion_programa::ADJUNTO_RECHAZAR];
        $adjuntos = Mds_legales_archivo::find()
            ->select('sds_com_configuracion.descripcion')
            ->where(['id_objeto' => $idcertificacion, 'objeto' => 'mds_certificacion', "mds_legales_archivo.activo" => 1])
            ->andWhere(['NOT IN', 'idconfiguracion', $adjuntos_especiales])
            ->innerJoin('sds_com_configuracion', 'mds_legales_archivo.tipo=sds_com_configuracion.idconfiguracion')
            ->groupBy('mds_legales_archivo.tipo')
            ->asArray()
            ->all();
        return $adjuntos;
    }

    protected function getAdjuntosBajaById($idcertificacion)
    {
        //Busqueda archivos de la certificacion
        $adjuntos = Mds_legales_archivo::find()
            ->where(['id_objeto' => $idcertificacion, 'objeto' => 'mds_certificacion', 'tipo' => Mds_certificacion_programa::ADJUNTO_BAJA, "mds_legales_archivo.activo" => 1])
            ->asArray()
            ->all();
        return $adjuntos;
    }

    protected function getAdjuntosObservadaById($idcertificacion)
    {
        //Busqueda archivos de la certificacion
        $adjuntos = Mds_legales_archivo::find()
            ->where(['id_objeto' => $idcertificacion, 'objeto' => 'mds_certificacion', 'tipo' => Mds_certificacion_programa::ADJUNTO_OBSERVAR, "mds_legales_archivo.activo" => 1])
            ->asArray()
            ->all();
        return $adjuntos;
    }

    protected function getAdjuntosRechazadaById($idcertificacion)
    {
        //Busqueda archivos de la certificacion
        $adjuntos = Mds_legales_archivo::find()
            ->where(['id_objeto' => $idcertificacion, 'objeto' => 'mds_certificacion', 'tipo' => Mds_certificacion_programa::ADJUNTO_RECHAZAR, "mds_legales_archivo.activo" => 1])
            ->asArray()
            ->all();
        return $adjuntos;
    }

    protected function getCambioresponsable($idcertificacion, $valores)
    {
        //Busqueda del responsable de esa certificacion y esta activo
        $responsable = Mds_certificacion_responsable::find()
            ->where(['idcertificacion' => $idcertificacion, "deleted_at" => null])
            ->asArray()
            ->one();
        $cambia = false;
        if (($valores['nombre_apellido'] != $responsable['nombre_apellido']) || ($valores['dni'] != $responsable['dni']) || ($valores['cbu_alias'] != $responsable['cbu_alias'])) {
            $cambia = true;
        }
        return $cambia;
    }

    protected function getAreaUser()
    {
        $idusuario = Yii::$app->user->identity->idusuario;
        $rolesCertificaciones = implode(',', Mds_certificacion::ID_ROLES_CERTIFICACIONES);
        $roles = Mds_seg_permiso::findBySql("select * from mds_seg_usuario_rol where idusuario=$idusuario AND idrol IN ({$rolesCertificaciones})")->all();

        if (count($roles) > 0) {
            foreach ($roles as $rol) {
                switch ($rol['idrol']) {
                    case  Mds_certificacion::ID_ROL_SOLICITANTE:
                        $area = 'solicitante';
                        break;
                    case Mds_certificacion::ID_ROL_NIVEL1:
                        $area = 'direccion_simple';
                        break;
                    case Mds_certificacion::ID_ROL_NIVEL2:
                        $area = 'direccion_general';
                        break;
                    case Mds_certificacion::ID_ROL_NIVEL3:
                        $area = 'direccion_provincial';
                        break;
                    case Mds_certificacion::ID_ROL_NIVEL4:
                        $area = 'subsecretaria';
                        break;
                    case Mds_certificacion::ID_ROL_NIVEL5:
                        $area = 'administracion';
                        break;
                    case Mds_certificacion::ID_ROL_FUNCIONARIO:
                        $area = 'funcionario';
                        break;
                    case Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL:
                        $area = 'solicitudes';
                        break;
                    default:
                        $area = null;
                }
            }
        }
        return $area;
    }

    private function modificarEstadoCertificacion($idcertificacion, $idestado, $observacion, $fecha)
    {
        $exito = false;
        $idusuario = Yii::$app->user->identity->idusuario;
        //vemos el estado previo
        $model_estado_actual = Mds_certificacion_estado::find()
            ->where(['idcertificacion' => $idcertificacion, 'fecha_fin' => null])
            ->one();

        $model_estado_actual->fecha_fin = date('Y-m-d H:i:s');
        $model_estado_actual->updated_at = date('Y-m-d H:i:s');

        //guardamos el estado nuevo
        $model_certificacion_estado = new Mds_certificacion_estado();
        $model_certificacion_estado->idcertificacion = $idcertificacion;
        $model_certificacion_estado->idestado = $idestado;
        $model_certificacion_estado->idusuario = Yii::$app->user->id;
        $model_certificacion_estado->fecha_inicio = date('Y-m-d H:i:s');
        $model_certificacion_estado->created_at = date('Y-m-d H:i:s');
        $model_certificacion_estado->fecha = $fecha;
        $model_certificacion_estado->observaciones = $observacion;

        //Si el estado es aprobada busco el idcertificaciondireccion siguiente, en otros casos busco desde los estados
        switch ($idestado) {
            case Mds_certificacion_estado::ESTADO_APROBADA: //iddireccion siguiente
                $direccionSiguiente = Mds_certificacion_direccion::getCertificacionDireccionPadre($model_estado_actual->iddireccion);
                $model_certificacion_estado->iddireccion = $direccionSiguiente ? $direccionSiguiente->idcertificaciondireccion : null;
                break;
            case Mds_certificacion_estado::ESTADO_ENVIADA:
                $model_certificacion_estado->iddireccion = null;
                break;
            case Mds_certificacion_estado::ESTADO_OBSERVADA: //iddireccion anterior o null (solicitante)
                $direccionAnterior = Mds_certificacion::getDireccionEstadoAnterior($idcertificacion, $model_estado_actual->iddireccion);
                $model_certificacion_estado->iddireccion = $direccionAnterior;
                break;
            case Mds_certificacion_estado::ESTADO_RECHAZADA: //iddireccion actual
                $model_certificacion_estado->iddireccion = $model_estado_actual->iddireccion;
                break;
            case Mds_certificacion_estado::ESTADO_BAJA: //iddireccion actual
                $model_certificacion_estado->iddireccion = $model_estado_actual->iddireccion;

                //Cuando cualquier usuario podia dar de baja
                // $idcertificaciondireccion = Mds_certificacion_direccion::getDireccionesUsuarioByNivel($idusuario, $nivelUsuario);
                // $model_certificacion_estado->iddireccion = $idcertificaciondireccion ? $idcertificaciondireccion[0]['idcertificaciondireccion'] : null;
                break;
            case Mds_certificacion_estado::ESTADO_ELIMINADA:
                // $model_certificacion_estado->iddireccion = $model_estado_actual['iddireccion'];
                $model_certificacion_estado->iddireccion = null;
                break;
        }

        if ($model_estado_actual->save() && $model_certificacion_estado->save()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_estado', $model_estado_actual->idcertificacionestado, $model_estado_actual->getAttributes());
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_estado', $model_certificacion_estado->idcertificacionestado, $model_certificacion_estado->getAttributes());
            $exito = true;
        }

        return $exito;
    }


    /**
     * TODO: Despues se actualizará una vez que se defina el formato
     */
    private function generarCodigo()
    {
        $length = 20;
        $exito = false;

        while (!$exito) {
            //     $codigo = bin2hex(random_bytes($length));

            $codigo = '';
            $keys = array_merge(range('A', 'Z'));

            for ($i = 0; $i < $length; $i++) {
                $codigo .= $keys[array_rand($keys)];
            }

            $model = Mds_certificacion::find()->where(['codigo' => $codigo])->one();

            if (empty($model)) {
                $exito = true;
            }
        }
        return $codigo;
    }

    private function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathCertificaciones = __DIR__ . '/../web/uploads/certificaciones/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "certificacion_{$model->idcertificacion}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathCertificaciones  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_certificacion', $adjunto['tipo'], $model->idcertificacion, $nameFile);
            }
        }
    }

    public function actionFilter_direcciones_previas($direccionesUser)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            // $direccionesUser = explode(',', $direccionesUser);
            $direccion = Mds_certificacion_direccion::find()->where(['idcertificaciondireccion' => $direccionesUser, 'deleted_at' => NULL])->asArray()->one();
            $nivelDireccion = $direccion ? $direccion['idnivelautorizacion'] : NULL;

            $direcionesPrevias = Mds_certificacion_direccion::getDireccionesPrevias($direccionesUser);
            $direcionesPrevias = array_column($direcionesPrevias, 'idcertificaciondireccion');

            $direccionesFiltro = Sds_com_configuracion::find()
                ->select('sds_com_configuracion.idconfiguracion as configDireccion,UPPER(sds_com_configuracion.descripcion) as descripcionDireccion,mds_certificacion_direccion.idcertificaciondireccion AS idcertificaciondireccion')
                ->innerJoin('mds_certificacion_direccion', 'sds_com_configuracion.idconfiguracion = mds_certificacion_direccion.iddireccion')
                ->where(['IN', 'mds_certificacion_direccion.idcertificaciondireccion', $direcionesPrevias])
                ->orderBy('sds_com_configuracion.descripcion')
                ->asArray()
                ->all();

            $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'idcertificaciondireccion', 'descripcionDireccion');

            if ($nivelDireccion == Mds_certificacion::ID_NIVEL2 || $nivelDireccion == Mds_certificacion::ID_NIVEL3 || $nivelDireccion == Mds_certificacion::ID_NIVEL4) {
                $direccionesFiltro[1] = 'SOLICITANTE';
            }
            asort($direccionesFiltro);
            return json_encode($direccionesFiltro);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionModal_xls_reporte($area)
    {
        $permissions = self::verifyPermissionsByRol($area);
        $nivelVista = $permissions['idnivel'] ? $permissions['idnivel'] : 'funcionario';

        if (!$permissions['authorized'] || !$permissions['permissionExcel']) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        } else {
            $direccionPosicion = [];
            $direccionesFiltro = [];
            if ($nivelVista == 'funcionario') {
                $direccionPosicion = $this->getFilterDirecciones();
            } else {
                $direccionesFiltro = $this->getFilterDireccionesPrevias($permissions['idnivel']);
            }
            $programasFiltro = $this->getFilterProgramas();

            $estados = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CERTIFICACION_ESTADOS);
            $estadosOptions = ArrayHelper::map($estados, 'idconfiguracion', 'descripcion');
            $elementoTodos = array('select_all' => 'Todos');
            $estadosOptions = $elementoTodos + $estadosOptions;

            Yii::$app->response->format = Response::FORMAT_JSON;
            $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
            $botonExportar = Html::button('<i class="far fa-file-excel" style="margin-right: 5px"></i>Exportar', [
                'id' => 'btn_buscar_todos',
                'class' => 'btn btn-success btn-exportar',
                'title' => "Exportar Excel",
                'target' => '_blank'
            ]);
            $buttons = "$botonVolver";
            return [
                'title' => "Exportar Excel",
                'content' => $this->renderAjax('modal_xls_reporte', [
                    'nivelVista' => $nivelVista,
                    'botonExportar' => $botonExportar,
                    'programas' => $programasFiltro,
                    'direccionPosicion' => $direccionPosicion,
                    'direcciones' => $direccionesFiltro,
                    'estados' => $estadosOptions,
                    'area' => $area,
                ]),
                'footer' => $buttons
            ];
        }
    }

    public function actionXls_reporte($area, $periodoDesde, $periodoHasta, $programas, $direccionesPosicion, $direcciones, $estados)
    {
        $arrayProgramas = explode(',', $programas);
        $arrayDirecciones = $direcciones ? explode(',', $direcciones) : null;
        $arrayEstados = explode(',', $estados);
        $arrayDireccionesPosicion = explode(',', $direccionesPosicion);

        $permissions = self::verifyPermissionsByRol($area);
        if ($permissions['permissionExcel']) {
            $registros = array();
            $monto_total = 0;

            $certificaciones = Mds_certificacion::find()
                ->addSelect([
                    'mds_certificacion.*',
                    'mds_certificacion_monto.monto as monto',
                    'mds_certificacion_responsable.nombre_apellido as responsable',
                    'mds_certificacion_responsable.dni as responsable_dni',
                    'configuracion_programa.descripcion as programa_descripcion',
                    'DATE_FORMAT(mds_certificacion.periodo_desde,"%d/%m/%Y") as fecha_desde',
                    'DATE_FORMAT(mds_certificacion.periodo_hasta,"%d/%m/%Y") as fecha_hasta',
                    'mds_certificacion_estado.idestado as estado_actual'
                ])
                ->where(['mds_certificacion.deleted_at' => null]);

            if ($area != Mds_certificacion::AREA_FUNCIONARIO) {
                $nivelUser = $permissions['idnivel'];
                $idusuario = Yii::$app->user->identity->idusuario;
                $direccionesUser = Mds_certificacion_direccion::getDireccionesUsuarioByNivel($idusuario, $nivelUser);
                $direccionesUser = array_column($direccionesUser, 'idcertificaciondireccion');
            } else {
                $direccionesUser = $arrayDireccionesPosicion;
            }

            $certificacionesUser = Mds_certificacion::find()
                ->addSelect(['mds_certificacion.idcertificacion'])
                ->innerJoin('mds_certificacion_estado', 'mds_certificacion.idcertificacion = mds_certificacion_estado.idcertificacion AND mds_certificacion_estado.deleted_at IS NULL')
                ->where(['mds_certificacion.deleted_at' => null])
                ->andWhere(['in', 'mds_certificacion_estado.iddireccion', $direccionesUser])
                ->asArray()
                ->all();
            $certificacionesUser = array_column($certificacionesUser, 'idcertificacion');

            if ($arrayDirecciones) {
                $certificacionesDirecciones = Mds_certificacion::find()
                    ->addSelect(['mds_certificacion.idcertificacion'])
                    ->innerJoin('mds_certificacion_estado', 'mds_certificacion.idcertificacion = mds_certificacion_estado.idcertificacion AND mds_certificacion_estado.deleted_at IS NULL')
                    ->where(['mds_certificacion.deleted_at' => null])
                    ->andWhere(['in', 'mds_certificacion.idcertificacion', $certificacionesUser])
                    ->andWhere(['in', 'mds_certificacion_estado.iddireccion', $arrayDirecciones])
                    ->asArray()
                    ->all();

                $certificacionSolicitante = [];
                if (in_array(1, $arrayDirecciones)) {
                    $estadoCertificaciones = [];
                    foreach ($certificacionesUser as $certificacion) {
                        //Busco el primer registro de Estado creado para la certificacion
                        $primerEstadoCertificacion = Mds_certificacion_estado::find()
                            ->select([
                                'mds_certificacion_estado.idcertificacion',
                                'mds_certificacion_estado.idcertificacionestado',
                                'mds_certificacion_estado.iddireccion'
                            ])
                            ->innerJoin('mds_certificacion', 'mds_certificacion_estado.idcertificacion= mds_certificacion.idcertificacion')
                            ->where(
                                [
                                    'mds_certificacion_estado.deleted_at' => null,
                                    'mds_certificacion.idcertificacion' => $certificacion
                                ]
                            )
                            ->asArray()
                            ->one();
                        array_push($estadoCertificaciones, $primerEstadoCertificacion);
                    }

                    //Si en ese primer registro se encuentra alguna de las direcciones del usuario
                    foreach ($estadoCertificaciones as $certificacion) {
                        if (in_array($certificacion['iddireccion'], $direccionesUser)) {
                            array_push($certificacionSolicitante, $certificacion);
                        }
                    }

                    $certificacionSolicitante = array_column($certificacionSolicitante, 'idcertificacion');
                }

                $certificacionesDirecciones = array_column($certificacionesDirecciones, 'idcertificacion');
                $certificacionesDirecciones = array_merge($certificacionesDirecciones, $certificacionSolicitante);
            } else {
                $certificacionesDirecciones = $certificacionesUser;
            }
            $certificaciones = $certificaciones->andWhere(['in', 'mds_certificacion.idcertificacion', $certificacionesDirecciones]);

            if ($estados) {
                $certificaciones = $certificaciones->andWhere(['in', 'mds_certificacion.idestado', $arrayEstados]);
            }
            if ($periodoDesde) {
                $certificaciones = $certificaciones->andWhere(['>=', 'mds_certificacion.periodo_desde',  $periodoDesde]);
            }
            if ($periodoHasta) {
                $certificaciones = $certificaciones->andWhere(['<=', 'mds_certificacion.periodo_hasta',  $periodoHasta]);
            }
            if ($programas) {
                $certificaciones = $certificaciones->andWhere(['in', 'mds_certificacion.idprograma', $arrayProgramas]);
            }

            $certificaciones = $certificaciones
                ->innerJoin('mds_certificacion_responsable', 'mds_certificacion.idcertificacion = mds_certificacion_responsable.idcertificacion AND mds_certificacion_responsable.deleted_at IS NULL')
                ->innerJoin('mds_certificacion_monto', 'mds_certificacion.idcertificacion = mds_certificacion_monto.idcertificacion AND mds_certificacion_monto.deleted_at IS NULL')
                ->innerJoin('mds_certificacion_estado', 'mds_certificacion.idcertificacion = mds_certificacion_estado.idcertificacion AND mds_certificacion_estado.deleted_at IS NULL')
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_estado.iddireccion = mds_certificacion_direccion.idcertificaciondireccion')
                ->innerJoin('sds_com_configuracion configuracion_programa', 'mds_certificacion.idprograma = configuracion_programa.idconfiguracion')
                ->orderBy(['mds_certificacion.idcertificacion' => SORT_ASC])
                ->all();

            $this->Exportexcel($certificaciones);

            // Yii::$app->response->format = Response::FORMAT_JSON;
            // foreach ($certificaciones as $certificacion) {
            //     $direccionesCertificacion = $this->getCircuitoDirecciones($certificacion['idcertificacion']);
            //     $solicitante = $certificacion['idorganismo_solicitante'] ? sds_com_configuracion::findOne($certificacion['idorganismo_solicitante']) : null;
            //     $expediente_nota = "EN TRAMITE";
            //     if ($certificacion['nro_expediente'] || $certificacion['nro_nota']) {
            //         $expediente_nota = $certificacion['nro_expediente'];
            //         if ($certificacion['nro_nota']) {
            //             $expediente_nota = $expediente_nota ? "$expediente_nota, {$certificacion['nro_nota']}" : $certificacion['nro_nota'];
            //         }
            //     }
            //     $monto_total += $certificacion['monto'];

            //     $registro = array(
            //         "N°" => $certificacion['idcertificacion'],
            //         "Localidad" => $certificacion->localidad->descripcion,
            //         "APELLIDOS Y NOMBRES de PERSONAS USUARIAS" => mb_strtoupper("{$certificacion->beneficiario->apellido} {$certificacion->beneficiario->nombre}"),
            //         "DNI" => $certificacion->beneficiario->documento,
            //         "APELLIDO Y NOMBRE RESPONSABLE DE COBRO/TUTOR ESPECIAL" => mb_strtoupper($certificacion['responsable']),
            //         "DNI RESPONSABLE" => $certificacion['responsable_dni'],
            //         "MONTO" => $certificacion['monto'],
            //         "DISPOSITIVO/PROGRAMA SOCIAL" => $certificacion['programa_descripcion'],
            //         "DIRECCIÓN" => array_key_exists('simple', $direccionesCertificacion) ? $direccionesCertificacion['simple']->descripcion : '',
            //         "DIRECCIÓN GENERAL" => array_key_exists('general', $direccionesCertificacion) ? $direccionesCertificacion['general']->descripcion : '',
            //         "DIRECCIÓN PROVINCIAL" => array_key_exists('provincial', $direccionesCertificacion) ? $direccionesCertificacion['provincial']->descripcion : '',
            //         "EXPEDIENTE/NOTA" => $expediente_nota,
            //         "PERIODO D" => $certificacion['fecha_desde'],
            //         "PERIODO H" => $certificacion['fecha_hasta'],
            //         "ORGANISMO SOLICITANTE" => $solicitante ? $solicitante->descripcion : 'INTERNA'
            //     );
            //     array_push($registros, $registro);
            // }
            // array_push($registros, array("MONTO" => "Total: $$monto_total"));
            // return $registros;
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    private function Exportexcel($certificaciones)
    {
        //obtenemos los datos
        $data = "";
        $registros = array();
        $monto_total = 0;

        $data .= '<head>';
        $data .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        $data .= '<!--[if gte mso 9]><xml>';
        $data .= '<x:ExcelWorkbook>';
        $data .= '<x:ExcelWorksheets>';
        $data .= '<x:ExcelWorksheet>';
        $data .= '<x:Name>Altas</x:Name>';
        $data .= '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>';
        $data .= '</x:ExcelWorksheet>';
        $data .= '</x:ExcelWorksheets>';
        $data .= '</x:ExcelWorkbook>';
        $data .= '</xml><![endif]-->';
        $data .= '</head>';
        $data .= '<body>';

        // Agrega encabezados de columna
        $data .= '<table border="1">';
        $data .= '<tr><td colspan="15" style="height: 50px;vertical-align:middle;"><img class="img_banner1" src="../web/img/banner1.png"></td></tr>';
        $data .= '<tr style="height: 50px;vertical-align:middle;"><td colspan="15">Ministerio de Desarrollo Social y Trabajo - Subsecretaría de Familia</td></tr>';
        $data .= '<tr style="background-color: skyblue ;">';
        $data .= '<td><b>N°</b></td>';
        $data .= '<td><b>Localidad</b></td>';
        $data .= '<td><b>APELLIDOS Y NOMBRES de PERSONAS USUARIAS</b></td>';
        $data .= '<td><b>DNI</b></td>';
        $data .= '<td><b>RESPONSABLE DE COBRO/TUTOR ESPECIAL</b></td>';
        $data .= '<td><b>DNI RESPONSABLE</b></td>';
        $data .= '<td><b>MONTO</b></td>';
        $data .= '<td><b>DISPOSITIVO/PROGRAMA SOCIAL</b></td>';
        $data .= '<td><b>DIRECCIÓN</b></td>';
        $data .= '<td><b>DIRECCIÓN GENERAL</b></td>';
        $data .= '<td><b>DIRECCIÓN PROVINCIAL</b></td>';
        $data .= '<td><b>EXPEDIENTE/NOTA</b></td>';
        $data .= '<td><b>PERIODO D</b></td>';
        $data .= '<td><b>PERIODO H</b></td>';
        $data .= '<td><b>ORGANISMO SOLICITANTE</b></td>';
        $data .= '</tr>';


        // Agrega datos a la hoja de cálculo
        foreach ($certificaciones as $certificacion) {
            $direccionesCertificacion = $this->getCircuitoDirecciones($certificacion['idcertificacion']);
            $solicitante = $certificacion['idorganismo_solicitante'] ? sds_com_configuracion::findOne($certificacion['idorganismo_solicitante']) : null;
            $expediente_nota = "EN TRAMITE";
            if ($certificacion['nro_expediente'] || $certificacion['nro_nota']) {
                $expediente_nota = $certificacion['nro_expediente'];
                if ($certificacion['nro_nota']) {
                    $expediente_nota = $expediente_nota ? "$expediente_nota, {$certificacion['nro_nota']}" : $certificacion['nro_nota'];
                }
            }
            $monto_total += $certificacion['monto'];
            $data .= '<tr>';
            $data .= '<td>' . $certificacion->idcertificacion . '</td>';
            $data .= '<td>' . $certificacion->localidad->descripcion . '</td>';
            $data .= '<td>' . mb_strtoupper($certificacion->beneficiario->apellido) . ' ' . mb_strtoupper($certificacion->beneficiario->nombre)  . '</td>';
            $data .= '<td>' . $certificacion->beneficiario->documento . '</td>';
            $data .= '<td>' . mb_strtoupper($certificacion['responsable']) . '</td>';
            $data .= '<td>' . $certificacion['responsable_dni'] . '</td>';
            $data .= '<td>' . $certificacion['monto'] . '</td>';
            $data .= '<td>' . $certificacion['programa_descripcion'] . '</td>';
            $data .= '<td>' . (array_key_exists('simple', $direccionesCertificacion) ? $direccionesCertificacion['simple']->descripcion : '') . '</td>';
            $data .= '<td>' . (array_key_exists('general', $direccionesCertificacion) ? $direccionesCertificacion['general']->descripcion : '') . '</td>';
            $data .= '<td>' . (array_key_exists('provincial', $direccionesCertificacion) ? $direccionesCertificacion['provincial']->descripcion : '') . '</td>';
            $data .= '<td>' . $expediente_nota . '</td>';
            $data .= '<td>' . $certificacion['fecha_desde'] . '</td>';
            $data .= '<td>' . $certificacion['fecha_hasta'] . '</td>';
            $data .= '<td>' . ($solicitante ? $solicitante->descripcion : 'INTERNA') . '</td>';
            $data .= '</tr>';
        }
        $data .= '<tr>';
        $data .= '<td colspan="6"></td>';
        $data .= '<td>Total <b>$' . $monto_total . '</b></td>';
        $data .= '</tr>';
        $data .= '</table>';
        $data .= '</body>';

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=Certificaciones.xls");
        echo $data;
    }

    private function getCircuitoDirecciones($idcertificacion)
    {
        $circuitoDirecciones = [];
        $seguir = true;

        $estadoInicial =  Mds_certificacion_estado::find()
            ->select(['mds_certificacion_estado.iddireccion'])
            ->where(['idcertificacion' => $idcertificacion, 'deleted_at' => NULL])
            ->asArray()
            ->one();

        $iddireccion = $estadoInicial['iddireccion'];
        array_push($circuitoDirecciones, $iddireccion);

        $direccion = Mds_certificacion_direccion::find()->where(['idcertificaciondireccion' => $iddireccion])->one();

        $arrayidniveles = Mds_certificacion::ID_NIVELES_CERTIFICACIONES;

        $key = array_search($direccion->idnivelautorizacion, $arrayidniveles);
        $key = $key + 1;
        $arrayidnivel = array_slice($arrayidniveles, $key);
        $idnivelAutorizacionSiguiente = $arrayidnivel[0];
        $iddireccionSiguiente = $direccion->iddireccion_padre;

        while ($seguir) {
            $direccion = Mds_certificacion_direccion::find()->where(['iddireccion' => $iddireccionSiguiente, 'idnivelautorizacion' => $idnivelAutorizacionSiguiente, 'deleted_at' => null])->one();
            $seguir = $direccion ? true : false;

            if ($seguir) {
                array_push($circuitoDirecciones, $direccion->idcertificaciondireccion);

                $iddireccionSiguiente = $direccion->iddireccion_padre;
                $key = array_search($direccion->idnivelautorizacion, $arrayidniveles);
                $key = $key + 1;
                $arrayidnivel = array_slice($arrayidniveles, $key);
                if (count($arrayidnivel) > 0) {
                    $idnivelAutorizacionSiguiente = $arrayidnivel[0];
                } else {
                    $seguir = false;
                }
            }
        }
        $result = [];
        foreach ($circuitoDirecciones as $direccion) {
            // nivel 2 = direccion general , nivel 3 direccion provincial
            $direccionSimple = Sds_com_configuracion::find()
                ->select(['sds_com_configuracion.idconfiguracion', 'sds_com_configuracion.descripcion'])
                ->where(['idcertificaciondireccion' => $direccion, 'idnivelautorizacion' => Mds_certificacion::ID_NIVEL1, 'deleted_at' => null])
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->one();
            if ($direccionSimple) {
                $result['simple'] = $direccionSimple;
            }
            $direccionGeneral = Sds_com_configuracion::find()
                ->select(['sds_com_configuracion.idconfiguracion', 'sds_com_configuracion.descripcion'])
                ->where(['idcertificaciondireccion' => $direccion, 'idnivelautorizacion' => Mds_certificacion::ID_NIVEL2, 'deleted_at' => null])
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->one();
            if ($direccionGeneral) {
                $result['general'] = $direccionGeneral;
            }
            $direccionProvincial = Sds_com_configuracion::find()
                ->select(['sds_com_configuracion.idconfiguracion', 'sds_com_configuracion.descripcion'])
                ->where(['idcertificaciondireccion' => $direccion, 'idnivelautorizacion' => Mds_certificacion::ID_NIVEL3, 'deleted_at' => null])
                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                ->one();
            if ($direccionProvincial) {
                $result['provincial'] = $direccionProvincial;
            }
        }
        return $result;
    }

    public function actionCertificacion_incremento($dni)
    {
        $certificacionesByBeneficiario = Mds_certificacion::getCertificacionesByDNI($dni);
        return json_encode($certificacionesByBeneficiario);
    }

    private function contarCantidadRegsitros(&$array, $certificacion, $idKey, $titulo)
    {
        $flag = true;
        $index = 0;

        while ($flag && $index < count($array)) {
            $array[$index]['titulo'] = $titulo;
            $array[$index]['cantidadRegistros'] = isset($array[$index]['cantidadRegistros']) ? $array[$index]['cantidadRegistros'] :  0;
            if ($certificacion[$idKey] == $array[$index][$idKey]) {
                $array[$index]['cantidadRegistros']++;
                $array[$index]['url'] = "&$idKey={$array[$index][$idKey]}";
                $flag = false;
            }
            $index++;
        }
    }

    private function usortArrayByCantidadRegistros(&$array)
    {
        usort($array, function ($a, $b) {
            return $b['cantidadRegistros'] - $a['cantidadRegistros'];
        });
    }

    private function permisssionAction($idcertificacion, $area)
    {
        //permisos de acciones para el usuario logueado segun direcciones asignadas,nivel y si es director/a
        $permissionAprobar = false;
        $permissionRechazar = false;
        $permissionObservar = false;
        $permissionBaja = false;
        $permissionEnviar = false;

        $idusuario = Yii::$app->user->identity->idusuario;
        // tiene la direccion asignada
        $direccionesAsignadasUser = Mds_certificacion_direccion_usuario::find()->select('idcertificaciondireccion')
            ->where(['idusuario' => $idusuario, 'deleted_at' => null])->asArray()->all();

        if ($direccionesAsignadasUser) {
            $direccionAsig = array_column($direccionesAsignadasUser, 'idcertificaciondireccion');
        }

        $certificacion = Mds_certificacion::find()
            ->where(['idcertificacion' => $idcertificacion])
            ->andWhere(['in', 'mds_certificacion.idarea', $direccionAsig]);
        if (!Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)) {
            $certificacion = $certificacion
                ->andWhere(['mds_certificacion.deleted_at' => null]);
        }
        $certificacion = $certificacion->one();

        if ($certificacion) {
            //ultimo estado de la certificacion
            $model_certificacion_estado = Mds_certificacion_estado::find()->where(['idcertificacion' => $idcertificacion, 'fecha_fin' => null, 'deleted_at' => null])->one();

            //dado el usuario y el nivel desde donde accedió obtengo sus direcciones en ese nivel
            $permissions = self::verifyPermissionsByRol($area);
            $direccionesUsuario = Mds_certificacion_direccion::getDireccionesUsuarioByNivel($idusuario, $permissions['idnivel']);
            $arrayDirecciones = array_column($direccionesUsuario, 'idcertificaciondireccion');

            // si es director
            $model_director = Mds_certificacion_director::find()
                ->where([
                    'idusuario' => $idusuario,
                    'idcertificaciondireccion' => $model_certificacion_estado->iddireccion,
                    'deleted_at' => null, 'idfuncion' => Mds_certificacion_director::ID_FUNCION_DIRECTOR,
                ])
                ->andWhere(['<=', 'mds_certificacion_director.fecha_desde', $certificacion->periodo_desde])
                ->andWhere(['or', ['mds_certificacion_director.fecha_hasta' => null], ['>=', 'mds_certificacion_director.fecha_hasta', $certificacion->periodo_desde]])
                ->asArray()->all();

            if ($certificacion && in_array($model_certificacion_estado->iddireccion, $arrayDirecciones) && count($model_director) > 0 && $model_certificacion_estado->idestado != Mds_certificacion_estado::ESTADO_RECHAZADA && $model_certificacion_estado->idestado != Mds_certificacion_estado::ESTADO_BAJA) {
                if ($model_certificacion_estado->idestado == Mds_certificacion_estado::ESTADO_OBSERVADA) {
                    $permissionObservar = true;
                } else {
                    $permissionAprobar = $permissions['idnivel'] != Mds_certificacion::ID_NIVEL5;
                    $permissionRechazar = true;
                    $permissionObservar = true;
                    $permissionBaja = true;
                    $permissionEnviar = $permissions['idnivel'] == Mds_certificacion::ID_NIVEL5;
                }
            }
        }
        $response = [
            'permissionAprobar' => $permissionAprobar,
            'permissionRechazar' => $permissionRechazar,
            'permissionObservar' => $permissionObservar,
            'permissionBaja' => $permissionBaja,
            'permissionEnviar' => $permissionEnviar
        ];
        return $response;
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
