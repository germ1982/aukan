<?php

namespace app\controllers;

use app\models\Mds_sys_log;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_item;

use app\models\Sds_800_atencion;
use app\models\Sds_800_atencion_interior;
use app\models\Sds_800_atencion_am;
use app\models\Sds_800_atencion_familia;
use app\models\Sds_800_derivacion;
use app\models\Sds_800_llamada;
use app\models\Sds_800_llamadaSearch;
use app\models\Sds_800_persona;
use app\models\Sds_vio_intervencion;
use app\models\Sds_vio_intervencion_violencias;
use app\models\Sds_vio_intervencion_agresor;
use app\models\Sds_vio_agresor_consumo;
use app\models\Sds_vio_intervencion_movimiento;
use app\models\Sds_vio_intervencion_violencias_frecuencia;
use app\models\Sds_vio_persona;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_com_localidad;
use app\models\Sds_com_provincia;
use app\models\Sds_ris_risneu;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;


// function write_to_console($data)
// {
//     $console = $data;
//     if (is_array($console))
//         $console = implode(',', $console);

//     echo "<script>console.log('Console: " . $console . "' );</script>";
// }


/**
 * Sds_800_llamadaController implements the CRUD actions for Sds_800_llamada model.
 */
class Sds_800_llamadaController extends Controller
{
    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'reactivate' => ['post'],
                    'nc' => ['post'],
                    'validar_dni' => ['post'],
                    'get_datos_derivacion' => ['post'],
                    'get_id_localidad' => ['post'],
                ],
            ],

            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'create',
                    'view',
                    'update',
                    'delete',
                    'reactivate',
                    'cerrar',
                    'derivar',
                    'nc',
                    'reporte_llamada',
                    'dashboard',
                    'validar_dni',
                    'get_datos_derivacion',
                    'get_id_localidad'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'reactivate',
                            'cerrar',
                            'derivar',
                            'nc',
                            'reporte_llamada',
                            'dashboard',
                            'validar_dni',
                            'get_datos_derivacion',
                            'get_id_localidad'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        // 'matchCallback' => function () {
                        // parse_str(Yii::$app->request->url, $params);
                        //Necesito el area
                        //     return ($this->hasRol800());
                        // },
                        // 'denyCallback' => function ($rule, $action) {
                        //     throw new \Exception('You are not allowed to access this page');
                        // }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_800_llamada models.
     * @return mixed
     */
    public function actionIndex($area, $fechaInicio = null, $fechaFin = null)
    {
        $searchModel = new Sds_800_llamadaSearch();
        $searchModel->area = $area;
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $hasRol800 =  $this->hasRol800($area);

        $usuarioAuth = Yii::$app->user->identity;
        $permissionsImprimirRisneu = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RIS_RISNEU_IMPRIMIR, $usuarioAuth->idusuario);
        $stringButtonsIndex = '{view} {update} {derivar} {atender} {nc} {despejar} {cerrar} {previa} {pdf} {intervencion}';
        if (!empty($permissionsImprimirRisneu)) {
            $stringButtonsIndex .= ' {imprimirRisneu}';
        }
        $stringButtonsIndex .= ' {delete} {reactivate}';

        if ($hasRol800 && $area >= 0) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $fechaInicio, $fechaFin);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_llamada', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'usuariosFiltro' => $this->getFilterUsuarioCarga($area),
                'profesionalFiltro' => $this->getFilterProfesionales(),
                'tipoFiltro' => $this->getFilterTipo(),
                'generoFiltro' => $this->getFilterGenero(),
                'derivacionFiltro' => $this->getFilterDerivacion(),
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'stringButtonsIndex' => $stringButtonsIndex
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Sds_800_llamada model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_llamada', $id, array());
        $model = $this->findModel($id);
        $hasRol800 =  $this->hasRol800($model->area);

        if ($hasRol800) {
            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
            $model->usuario = Mds_seg_usuario::findOne($model->idusuario);
            $model->usuarioDeriva = Mds_seg_usuario::findOne($model->idusuario_deriva);
            $model->dni = $model_com_persona->documento;
            $model->nombre = $model_com_persona->nombre;
            $model->apellido = $model_com_persona->apellido;
            $model->fecha_nacimiento = $model_com_persona->fecha_nacimiento;
            $model->nacionalidad = $model_com_persona->nacionalidad;
            $model->sexo = $model_com_persona->genero;
            $localidad = Sds_com_localidad::findOne($model_800_persona->idlocalidad);
            $model->localidad = $localidad->descripcion;
            $provincia = Sds_com_provincia::findOne($localidad->idprovincia);
            $model->provincia = $provincia->descripcion;
            $model->telefono = $model_800_persona->telefono;
            $model->domicilio = $model_800_persona->domicilio;
            $model->edad = Sds_com_persona::getEdad($model_com_persona->fecha_nacimiento);
            //$model->direccion = $model->calculaDireccionGM($model->longitud, $model->latitud);
            switch ($model->afectado_tratamiento) {
                case '0':
                    $model->afectado_tratamiento = 'Paciente de adicciones';
                    break;
                case '1':
                    $model->afectado_tratamiento = 'Paciente salud mental';
                    break;
                case '2':
                    $model->afectado_tratamiento = 'Paciente dual';
                    break;
            };
            $model_atencion = Sds_800_atencion::findOne($id);
            if ($model_atencion != null) {
                $model_com_persona_atencion = Sds_com_persona::findOne($model_atencion->idpersona);
                $model_800_persona_atencion = Sds_800_persona::findOne($model_atencion->idpersona);
                $model_atencion->dni = $model_com_persona_atencion->documento;
                $model_atencion->nombre = $model_com_persona_atencion->nombre;
                $model_atencion->apellido = $model_com_persona_atencion->apellido;
                $model_atencion->fecha_nacimiento = $model_com_persona_atencion->fecha_nacimiento;
                $model_atencion->nacionalidad = $model_com_persona_atencion->nacionalidad;
                $model_atencion->sexo = $model_com_persona_atencion->genero;
                $model_atencion->localidad = $model_800_persona_atencion->idlocalidad;
                $model_atencion->telefono = $model_800_persona_atencion->telefono;
            }

            switch ($model->area) {
                case Sds_800_llamada::AREA_SITUACIONDECALLE:
                    $model_atencion = Sds_800_atencion::findOne($id);
                    if ($model_atencion != null) {
                        $model_com_persona_atencion = Sds_com_persona::findOne($model_atencion->idpersona);
                        $model_800_persona_atencion = Sds_800_persona::findOne($model_atencion->idpersona);
                        $model_atencion->dni = $model_com_persona_atencion->documento;
                        $model_atencion->nombre = $model_com_persona_atencion->nombre;
                        $model_atencion->apellido = $model_com_persona_atencion->apellido;
                        $model_atencion->fecha_nacimiento = $model_com_persona_atencion->fecha_nacimiento;
                        $model_atencion->nacionalidad = $model_com_persona_atencion->nacionalidad;
                        $model_atencion->sexo = $model_com_persona_atencion->genero;
                        $model_atencion->localidad = $model_800_persona_atencion->idlocalidad;
                        $model_atencion->telefono = $model_800_persona_atencion->telefono;
                    }
                    break;
                case Sds_800_llamada::AREA_FAMILIA:
                    $model_atencion = Sds_800_atencion_familia::findOne($id);
                    if ($model_atencion != null) {
                        $model_com_persona_atencion = Sds_com_persona::findOne($model_atencion->idpersona);
                        $model_800_persona_atencion = Sds_800_persona::findOne($model_atencion->idpersona);
                        $model_atencion->dni = $model_com_persona_atencion->documento;
                        $model_atencion->nombre = $model_com_persona_atencion->nombre;
                        $model_atencion->apellido = $model_com_persona_atencion->apellido;
                        $model_atencion->localidad = $model_800_persona_atencion->idlocalidad;
                        $model_atencion->telefono = $model_800_persona_atencion->telefono;


                        $model_com_persona_atencion1 = Sds_com_persona::findOne($model_atencion->idpersona_referente);
                        $model_800_persona_atencion1 = Sds_800_persona::findOne($model_atencion->idpersona_referente);
                        $model_atencion->dni1 = $model_com_persona_atencion1->documento;
                        $model_atencion->nombre1 = $model_com_persona_atencion1->nombre;
                        $model_atencion->apellido1 = $model_com_persona_atencion1->apellido;
                        $model_atencion->fecha_nacimiento1 = $model_com_persona_atencion1->fecha_nacimiento;
                        $model_atencion->nacionalidad1 = $model_com_persona_atencion1->nacionalidad;
                        $model_atencion->sexo1 = $model_com_persona_atencion1->genero;
                        $model_atencion->localidad1 = $model_800_persona_atencion1->idlocalidad;
                        $model_atencion->telefono1 = $model_800_persona_atencion1->telefono;
                        $model_atencion->domicilio1 = $model_800_persona_atencion1->domicilio;
                    }
                    break;
                case Sds_800_llamada::AREA_ADULTOSMAYORES:
                    $model_atencion = Sds_800_atencion_am::findOne($id);
                    if ($model_atencion != null) {
                        $model_com_persona_atencion = Sds_com_persona::findOne($model_atencion->idpersona);
                        $model_800_persona_atencion = Sds_800_persona::findOne($model_atencion->idpersona);
                        $model_atencion->dni = $model_com_persona_atencion->documento;
                        $model_atencion->nombre = $model_com_persona_atencion->nombre;
                        $model_atencion->apellido = $model_com_persona_atencion->apellido;
                        $model_atencion->localidad = $model_800_persona_atencion->idlocalidad;
                        $model_atencion->telefono = $model_800_persona_atencion->telefono;
                    }
                    break;
                case Sds_800_llamada::AREA_INTERIOR:
                    $model_atencion = Sds_800_atencion_interior::findOne($id);
                    if ($model_atencion != null) {
                        $model_com_persona_atencion = Sds_com_persona::findOne($model_atencion->idpersona);
                        $model_800_persona_atencion = Sds_800_persona::findOne($model_atencion->idpersona);
                        $model_atencion->dni = $model_com_persona_atencion->documento;
                        $model_atencion->nombre = $model_com_persona_atencion->nombre;
                        $model_atencion->apellido = $model_com_persona_atencion->apellido;
                        $model_atencion->localidad = $model_800_persona_atencion->idlocalidad;
                        $model_atencion->telefono = $model_800_persona_atencion->telefono;

                        $model_com_persona_atencion1 = Sds_com_persona::findOne($model_atencion->idpersona_referente);
                        $model_800_persona_atencion1 = Sds_800_persona::findOne($model_atencion->idpersona_referente);
                        $model_atencion->dni1 = $model_com_persona_atencion1->documento;
                        $model_atencion->nombre1 = $model_com_persona_atencion1->nombre;
                        $model_atencion->apellido1 = $model_com_persona_atencion1->apellido;
                        $model_atencion->fecha_nacimiento1 = $model_com_persona_atencion1->fecha_nacimiento;
                        $model_atencion->nacionalidad1 = $model_com_persona_atencion1->nacionalidad;
                        $model_atencion->sexo1 = $model_com_persona_atencion1->genero;
                        $model_atencion->localidad1 = $model_800_persona_atencion1->idlocalidad;
                        $model_atencion->telefono1 = $model_800_persona_atencion1->telefono;
                        $model_atencion->domicilio1 = $model_800_persona_atencion1->domicilio;
                    }
                case Sds_800_llamada::AREA_VIOLENCIA:
                    $model_atencion = Sds_800_atencion_interior::findOne($id);
                    if ($model_atencion != null) {
                        $model_com_persona_atencion = Sds_com_persona::findOne($model_atencion->idpersona);
                        $model_800_persona_atencion = Sds_800_persona::findOne($model_atencion->idpersona);
                        $model_atencion->dni = $model_com_persona_atencion->documento;
                        $model_atencion->nombre = $model_com_persona_atencion->nombre;
                        $model_atencion->apellido = $model_com_persona_atencion->apellido;
                        $model_atencion->localidad = $model_800_persona_atencion->idlocalidad;
                        $model_atencion->telefono = $model_800_persona_atencion->telefono;
                    }
                    break;
                default:
                    break;
            }

            $localidadModelAtencion = $model_atencion ? Sds_com_localidad::findOne($model_atencion->localidad) : null;
            $localidadDescripcion = $localidadModelAtencion ? "$localidadModelAtencion->descripcion ({$localidadModelAtencion->provincia->descripcion})" : "";
            $localidad1ModelAtencion =  $model_atencion && ($model->area == 1 || $model->area == 3)  ? Sds_com_localidad::findOne($model_atencion->localidad1) : null;
            $localidad1Descripcion = $localidad1ModelAtencion ?  "$localidad1ModelAtencion->descripcion ({$localidad1ModelAtencion->provincia->descripcion})" : "";
            $nacionalidadDescripcion = $model ? Sds_com_configuracion::findOne($model->nacionalidad) : null;
            $nacionalidad1Descripcion = $model_atencion && ($model->area == 1 || $model->area == 3) ? Sds_com_configuracion::findOne($model_atencion->nacionalidad1) : null;
            $generoDescripcion = $model ? Sds_com_configuracion::findOne($model->sexo) : null;
            $genero1Descripcion = $model_atencion && ($model->area == 1 || $model->area == 3) ? Sds_com_configuracion::findOne($model_atencion->sexo1) : null;
            $parentescoDescripcion = $model_atencion && ($model->area == 1 || $model->area == 3 || $model->area == 4) ? Sds_com_configuracion::findOne($model_atencion->parentezco) : null;
            $situacionTipoDescripcion = $model ? Sds_com_configuracion::findOne($model->tipo) : null;
            $selectDerivacion = ArrayHelper::map(Sds_800_derivacion::findAll(['activo' => 1]), 'idderivacion', 'descripcion'); //No se cambio porque el select tiene un onchange que muestra info del telefono y la direccion de la derivacion seleccionada

            $generoModelAtencionDescripcion = $model->area == 0 && $model_atencion ? Sds_com_configuracion::findOne($model_atencion->sexo) : null;
            $nacionalidadModelAtencionDescripcion = $model->area == 0 && $model_atencion ? Sds_com_configuracion::findOne($model_atencion->nacionalidad) : null;
            $selectTipoAyuda = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::TIPO_AYUDA])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectExpectativaCortoPlazo = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::EXPECTATIVA_CORTO_PLAZO])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectMotivoAbandono = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::MOTIVO_ABANDONO])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectSituacionSalud = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::SITUACION_SALUD])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectConsumoProblematico = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::CONSUMO_PROBLEMATICO])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectCapacidadLimitada =  $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::CAPACIDAD_LIMITADA])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectSituacionLaboral = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::R_SITUACION_LABORAL])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;
            $selectAportesEconomicos = $model->area == 0 ? ArrayHelper::map(Sds_com_configuracion::find()->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::APORTES_ECONOMICOS])->orderBy(["descripcion" => SORT_ASC])->all(), "idconfiguracion", "descripcion") : null;

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Sds_800_llamada #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'model_atencion' => $model_atencion,
                        'generoDescripcion' => $generoDescripcion,
                        'generoModelAtencionDescripcion' => $generoModelAtencionDescripcion,
                        'nacionalidadModelAtencionDescripcion' => $nacionalidadModelAtencionDescripcion,
                        'localidadDescripcion' => $localidadDescripcion,
                        'nacionalidadDescripcion' => $nacionalidadDescripcion,
                        'situacionTipoDescripcion' => $situacionTipoDescripcion,
                        'selectDerivacion' => $selectDerivacion,
                        'selectTipoAyuda' => $selectTipoAyuda,
                        'selectExpectativaCortoPlazo' => $selectExpectativaCortoPlazo,
                        'selectMotivoAbandono' => $selectMotivoAbandono,
                        'selectSituacionSalud' => $selectSituacionSalud,
                        'selectConsumoProblematico' => $selectConsumoProblematico,
                        'selectCapacidadLimitada' => $selectCapacidadLimitada,
                        'selectSituacionLaboral' => $selectSituacionLaboral,
                        'selectAportesEconomicos' => $selectAportesEconomicos,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                if ($model->area == Sds_800_llamada::AREA_SITUACIONDECALLE) {
                    //view de situacion de calle
                    return $this->render('view', [
                        'model' => $model,
                        'model_atencion' => $model_atencion,
                        'generoDescripcion' => $generoDescripcion,
                        'generoModelAtencionDescripcion' => $generoModelAtencionDescripcion,
                        'nacionalidadModelAtencionDescripcion' => $nacionalidadModelAtencionDescripcion,
                        'localidadDescripcion' => $localidadDescripcion,
                        'nacionalidadDescripcion' => $nacionalidadDescripcion,
                        'situacionTipoDescripcion' => $situacionTipoDescripcion,
                        'selectDerivacion' => $selectDerivacion,
                        'selectTipoAyuda' => $selectTipoAyuda,
                        'selectExpectativaCortoPlazo' => $selectExpectativaCortoPlazo,
                        'selectMotivoAbandono' => $selectMotivoAbandono,
                        'selectSituacionSalud' => $selectSituacionSalud,
                        'selectConsumoProblematico' => $selectConsumoProblematico,
                        'selectCapacidadLimitada' => $selectCapacidadLimitada,
                        'selectSituacionLaboral' => $selectSituacionLaboral,
                        'selectAportesEconomicos' => $selectAportesEconomicos,
                    ]);
                } else if ($model->area == Sds_800_llamada::AREA_FAMILIA) {
                    //view de familia
                    return $this->render('view_familia', [
                        'model' => $model,
                        'model_atencion' => $model_atencion,
                        'localidadDescripcion' => $localidadDescripcion,
                        'nacionalidadDescripcion' => $nacionalidadDescripcion,
                        'nacionalidad1Descripcion' => $nacionalidad1Descripcion,
                        'localidad1Descripcion' => $localidad1Descripcion,
                        'generoDescripcion' => $generoDescripcion,
                        'genero1Descripcion' => $genero1Descripcion,
                        'parentescoDescripcion' => $parentescoDescripcion,
                        'situacionTipoDescripcion' => $situacionTipoDescripcion,
                        'selectDerivacion' => $selectDerivacion,
                    ]);
                } else if ($model->area == Sds_800_llamada::AREA_ADULTOSMAYORES) {
                    //view de adultos mayores
                    return $this->render('view_adultos', [
                        'model' => $model,
                        'model_atencion' => $model_atencion,
                        'localidadDescripcion' => $localidadDescripcion,
                        'nacionalidadDescripcion' => $nacionalidadDescripcion,
                        'generoDescripcion' => $generoDescripcion,
                        'situacionTipoDescripcion' => $situacionTipoDescripcion,
                        'selectDerivacion' => $selectDerivacion,
                    ]);
                } else if ($model->area == Sds_800_llamada::AREA_INTERIOR) {
                    return $this->render('view_interior', [
                        'model' => $model,
                        'model_atencion' => $model_atencion,
                        'localidadDescripcion' => $localidadDescripcion,
                        'localidad1Descripcion' => $localidad1Descripcion,
                        'nacionalidadDescripcion' => $nacionalidadDescripcion,
                        'nacionalidad1Descripcion' => $nacionalidad1Descripcion,
                        'generoDescripcion' => $generoDescripcion,
                        'genero1Descripcion' => $genero1Descripcion,
                        'parentescoDescripcion' => $parentescoDescripcion,
                        'situacionTipoDescripcion' => $situacionTipoDescripcion,
                        'selectDerivacion' => $selectDerivacion,
                    ]);
                } else if ($model->area == Sds_800_llamada::AREA_VIOLENCIA) {
                    return $this->render('view_violencia', [
                        'model' => $model,
                        'model_atencion' => $model_atencion,
                        'nacionalidadDescripcion' => $nacionalidadDescripcion,
                        'generoDescripcion' => $generoDescripcion,
                        'situacionTipoDescripcion' => $situacionTipoDescripcion,
                        'selectDerivacion' => $selectDerivacion,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_800_llamada model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($area = false)
    {
        $hasRol800 =  $this->hasRol800($area);
        if ($hasRol800) {
            $request = Yii::$app->request;
            $model = new Sds_800_llamada();
            $model->idpersona = 0;
            $model->estado = 0;
            $model->area = $area;
            $model->fecha_hora = date('Y-m-d H:i');
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                    'model' => $model,
                ]);
            }
            $user  = Yii::$app->user->identity;
            $model->idusuario = $user->idusuario;
            $listProvincias = Sds_com_provinciaController::getListProvincias();

            $listprofesionales = $area == Sds_800_llamada::AREA_FAMILIA ? $this->getListProfesionales() : [];

            $selectNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
            $selectGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
            $selectSituacionTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SITUACION_TIPO, true), 'idconfiguracion', 'descripcion');
            $selectDerivacion = ArrayHelper::map(Sds_800_derivacion::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(), 'idderivacion', 'descripcion');
            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */
            } else {
                /*
            *   Process for non-ajax request
            */
                if ($model->load($request->post())) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = true;
                    $model_com_persona = new Sds_com_persona;
                    $model_com_persona->documento_tipo = '83';
                    $model_800_persona = null;
                    $ban_persona_existe = 0;
                    if ($model->idpersona > 0) {
                        $ban_persona_existe = 1;
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }
                    $model_com_persona->documento = $model->dni;
                    $model_com_persona->nacionalidad = $model->nacionalidad;
                    $model_com_persona->genero = $model->sexo;
                    $model_com_persona->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    $model_com_persona->conviviente = 0;
                    if (!$model_com_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        $model->idpersona = $model_com_persona->idpersona;
                        if ($model_800_persona == null) {
                            $model_800_persona = new Sds_800_persona();
                            $model_800_persona->idpersona = $model->idpersona;
                        }
                        $model_800_persona->telefono = $model->telefono;
                        $model_800_persona->domicilio = $model->domicilio;
                        $model_800_persona->idlocalidad = $model->localidad;
                        if (!$model_800_persona->save()) {
                            $guardado = false;
                            $transaction->rollBack();
                        }

                        $model->idrisneu = Sds_ris_risneu::getLastIdRisneuByDni($model->dni);
                        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                        $model->iddispositivo = $contacto->iddispositivo; // Este es el id dispositivo de la persona logeada

                        if ($guardado && $model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_llamada', $model->idllamada, $model->getAttributes());
                            if ($ban_persona_existe == 1) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                            }
                            return $this->redirect(['index', 'area' => $area]);
                        }
                    }
                }
                return $this->render('create', [
                    'model' => $model,
                    'listProvincias' => $listProvincias,
                    'selectNacionalidad' => $selectNacionalidad,
                    'selectGenero' => $selectGenero,
                    'selectSituacionTipo' => $selectSituacionTipo,
                    'selectDerivacion' => $selectDerivacion,
                    'listprofesionales' => $listprofesionales
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_800_llamada model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $hasRol800 =  $this->hasRol800($model->area);
        if ($model->estado != Sds_800_llamada::ESTADO_PENDIENTE && $model->estado != Sds_800_llamada::ESTADO_ATENDIDA) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        } else {
            if ($hasRol800) {
                $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                $model->dni = $model_com_persona->documento;
                $model->localidad = $model_800_persona->idlocalidad;
                $localidad = Sds_com_localidad::findOne($model_800_persona->idlocalidad);
                $provincia = Sds_com_provincia::findOne($localidad->idprovincia);
                $model->provincia = $provincia->idprovincia;

                $model->provincia =  $provincia;
                $model->edad = Sds_com_persona::getEdad($model_com_persona->fecha_nacimiento);

                $listProvincias = Sds_com_provinciaController::getListProvincias();
                $listprofesionales = $this->getListProfesionales();

                $selectNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
                $selectGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
                $selectSituacionTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SITUACION_TIPO, true), 'idconfiguracion', 'descripcion');
                $selectDerivacion = ArrayHelper::map(Sds_800_derivacion::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(), 'idderivacion', 'descripcion');

                // $area_old = $model->area; 
                if ($request->isAjax) {
                    //Process for ajax request
                } else {
                    //Process for non-ajax request
                    if ($model->load($request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $guardado = true;
                        $model_com_persona = new Sds_com_persona;
                        $model_com_persona->documento_tipo = '83';
                        $model_800_persona = null;
                        $ban_persona_existe = 0;
                        if ($model->idpersona > 0) {
                            $ban_persona_existe = 1;
                            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                        }
                        $model_com_persona->documento = $model->dni;
                        $model_com_persona->nacionalidad = $model->nacionalidad;
                        $model_com_persona->genero = $model->sexo;
                        $model_com_persona->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                        $model_com_persona->nombre = $model->nombre;
                        $model_com_persona->apellido = $model->apellido;
                        $model_com_persona->conviviente = 0;
                        if (!$model_com_persona->save()) {
                            $guardado = false;
                            $transaction->rollBack();
                        } else {
                            $model->idpersona = $model_com_persona->idpersona;
                            if ($model_800_persona == null) {
                                $model_800_persona = new Sds_800_persona();
                                $model_800_persona->idpersona = $model->idpersona;
                            }
                            $model_800_persona->telefono = $model->telefono;
                            $model_800_persona->domicilio = $model->domicilio;
                            $model_800_persona->idlocalidad = $model->localidad;
                            $model_800_persona->idgeneroautopercibido = null;
                            $model_800_persona->idlocalidadoriundo = null;

                            if (!$model_800_persona->save()) {
                                $guardado = false;
                                $transaction->rollBack();
                            }

                            $usuario = Yii::$app->user->identity;
                            $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                            $model->iddispositivo = $contacto->iddispositivo; // Este es el id dispositivo de la persona logeada
                            if ($guardado && $model->save()) {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_llamada', $model->idllamada, $model->getAttributes());
                                if ($ban_persona_existe == 1) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                                } else {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                                }
                                return $this->redirect(['index', 'area' => $model->area]);
                            }
                        }
                    }
                    //return $this->render('update_atender', [
                    return $this->render('update', [
                        'model' => $model,
                        'listProvincias' => $listProvincias,
                        'listprofesionales' => $listprofesionales,
                        'selectNacionalidad' => $selectNacionalidad,
                        'selectGenero' => $selectGenero,
                        'selectSituacionTipo' => $selectSituacionTipo,
                        'selectDerivacion' => $selectDerivacion,
                    ]);
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
    }

    /**
     * Delete an existing Sds_800_llamada model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model_800 = $this->findModel($id);
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

        if (
            $model_800 &&
            (
                (
                    ($model_800->idusuario === Yii::$app->user->identity->idusuario)
                    && ($model_800->estado == Sds_800_llamada::ESTADO_PENDIENTE)
                    && is_null($model_800->deleted_at)
                )
                || ($hasRolAdminGeneral && is_null($model_800->deleted_at))
            )
        ) {
            $request = Yii::$app->request;
            $iduser = Yii::$app->user->id;
            $model_800->deleted_at = date('Y-m-d H:i:s');
            $model_800->idusuario_borra = $iduser;
            $model_intervencion_violencia =  Sds_vio_intervencion::find()->where(['idllamada' => $id, 'deleted_at' => NULL])->One();
            if ($model_intervencion_violencia) {
                $model_800->estado = Sds_800_llamada::ESTADO_PENDIENTE; //se vuelve a pendiente
            }

            if ($model_800->save(false)) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_800_llamada', $id, $model_800->getAttributes());

                if ($model_intervencion_violencia) {
                    if ($this->eliminarIntervencion($model_intervencion_violencia->idintervencion)) {
                        Yii::$app->session->setFlash('success', " Se reactivó correctamente la intervención.");
                    } else {
                        Yii::$app->session->setFlash('error', " Error al reactivar la intervención.");
                    }
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }

            if ($request->isAjax) {
                // Process for ajax request
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['forceClose' => true];
            } else {
                //Process for non-ajax request
                return $this->redirect(['index', $this->findModel($id)->area]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $request = Yii::$app->request;
            $model_0800 = Sds_800_llamada::findOne($id);

            if ($model_0800) {
                $model_0800->deleted_at = null;
                $model_0800->idusuario_borra = null;
                $model_intervencion_violencia =  Sds_vio_intervencion::find()->where(['idllamada' => $id])->orderBy(['idintervencion' => SORT_DESC])->limit(1)->one();
                if ($model_intervencion_violencia) {
                    $model_0800->estado = Sds_800_llamada::ESTADO_ATENDIDA; //se vuelve a atendida
                }

                if ($model_0800->save(false)) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_llamada', $id, $model_0800->getAttributes());

                    if ($model_intervencion_violencia) {
                        if ($this->reactivarIntervencion($model_intervencion_violencia->idintervencion)) {
                            Yii::$app->session->setFlash('success', " Se reactivó correctamente la intervención.");
                        } else {
                            Yii::$app->session->setFlash('error', " Error al reactivar la intervención.");
                        }
                    }
                } else {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                Yii::$app->session->setFlash('error', " La intervención no existe.");
            }


            if ($request->isAjax) {
                //Process for ajax request
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['forceClose' => true];
            } else {
                //Process for non-ajax request
                return $this->redirect(['index', $this->findModel($id)->area]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDerivar($id)
    {
        $model = $this->findModel($id);
        $puedeDerivar = !($model->estado != Sds_800_llamada::ESTADO_PENDIENTE && $model->estado != Sds_800_llamada::ESTADO_ATENDIDA);
        $hasRol800 =  $this->hasRol800($model->area);

        if ($puedeDerivar &&  $hasRol800) {
            $request = Yii::$app->request;
            $model_com_persona = Sds_com_persona::findOne($model->idpersona);

            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
            $model_localidad = Sds_com_localidad::findOne($model_800_persona->idlocalidad);
            $model->localidad = $model_800_persona->idlocalidad;
            $model->provincia = $model_localidad->idprovincia;

            $model->dni = $model_com_persona->documento;
            $model->estado = Sds_800_llamada::ESTADO_DERIVADA;
            $area_old = $model->area; //guardo el area de la llamada original
            $listProvincias = Sds_com_provinciaController::getListProvincias();

            $usuarioDeriva = Yii::$app->user->identity;
            $idusuario_deriva = $usuarioDeriva != null ? $usuarioDeriva->idusuario : null;
            // $request = Yii::$app->request;
            // Yii::$app->response->format = Response::FORMAT_JSON;

            $selectNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
            $selectGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
            $selectSituacionTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SITUACION_TIPO, true), 'idconfiguracion', 'descripcion');
            $selectDerivacion = ArrayHelper::map(Sds_800_derivacion::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(), 'idderivacion', 'descripcion');

            $listprofesionales = $this->getListProfesionales();
            if ($request->isGet) {
                return $this->render('update', [
                    'model' => $model,
                    'listProvincias' => $listProvincias,
                    'selectNacionalidad' => $selectNacionalidad,
                    'selectGenero' => $selectGenero,
                    'selectSituacionTipo' => $selectSituacionTipo,
                    'selectDerivacion' => $selectDerivacion,
                    'listprofesionales' => $listprofesionales,
                ]);
            } else if ($model->load($request->post())) {
                $model->derivacion_fecha = date('Y-m-d');
                $model->idusuario_deriva = $idusuario_deriva;
                if ($model->derivacion_detalle == null || empty(trim($model->derivacion_detalle))) {
                    $model->addError("derivacion_detalle", "Debe ingresar un detalle para la derivación.");
                } else {
                    $model->area = $area_old;
                    //clona llamada
                    $model_clon = new Sds_800_llamada();
                    $model_clon->isNewRecord = true;
                    $model_clon->idpersona = $model->idpersona;
                    $model_clon->institucion = $model->institucion;
                    $model_clon->vinculo = $model->vinculo;
                    $model_clon->detalle = $model->detalle;
                    $model_clon->afectado_dni = $model->afectado_dni;
                    $model_clon->afectado_nombre = $model->afectado_nombre;
                    $model_clon->afectado_apodo = $model->afectado_apodo;
                    $model_clon->fecha_hora = $model->fecha_hora;
                    $model_clon->idusuario = $model->idusuario;
                    $model_clon->estado = 0;
                    $model_clon->latitud = $model->latitud;
                    $model_clon->longitud = $model->longitud;
                    $model_clon->direccion = $model->direccion;
                    $model_clon->afectado_tratamiento = $model->afectado_tratamiento;

                    switch ($model->idderivacion) {
                        case 6:
                            // etear area con el valor correspondiente al area familia y idorigen con $model->idllamada
                            $model_clon->area = 1;
                            $model_clon->idorigen = $id;
                            $model_clon->save(false);
                            break;
                        case 7:
                            //setear area con el valor correspondiente a situacion de calle  y idorigen con $model->idllamada
                            $model_clon->area = 0;
                            $model_clon->idorigen = $id;
                            $model_clon->save(false);
                            break;
                        case 8:
                            //setear area con el valor correspondiente a Adultos Mayores  y idorigen con $model->idllamada
                            $model_clon->area = 2;
                            $model_clon->idorigen = $id;
                            $model_clon->save(false);
                            break;
                        case 9:
                            //setear area con el valor correspondiente aL INTERIORy idorigen con $model->idllamada               
                            $model_clon->area = 3;
                            $model_clon->idorigen = $id;
                            $model_clon->save(false);
                            break;
                        case 18:
                            //setear area con el valor correspondiente a VIOLENCIA  idorigen con $model->idllamada               
                            $model_clon->area = 4;
                            $model_clon->idorigen = $id;
                            $model_clon->save(false);
                            break;
                    }
                    if ($model->save()) {
                        if (isset($model_clon))
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sd_800_llamada/derivar', $id, $model_clon->getAttributes());
                        return $this->redirect(['index', 'area' => $model->area]);
                    }
                }
            }
            return $this->render('update', [
                'model' => $model,
                'listProvincias' => $listProvincias,
                'selectNacionalidad' => $selectNacionalidad,
                'selectGenero' => $selectGenero,
                'selectSituacionTipo' => $selectSituacionTipo,
                'selectDerivacion' => $selectDerivacion,
                'listprofesionales' => $listprofesionales,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCerrar($id)
    {
        $model = $this->findModel($id);
        $puedeCerrar = ($model->estado == Sds_800_llamada::ESTADO_ATENDIDA);
        $hasRol800 =  $this->hasRol800($model->area);

        if ($puedeCerrar && $hasRol800) {
            $request = Yii::$app->request;
            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            $model->dni = $model_com_persona->documento;
            $model->estado = Sds_800_llamada::ESTADO_CERRADA;
            $listProvincias = Sds_com_provinciaController::getListProvincias();

            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
            $model_localidad = Sds_com_localidad::findOne($model_800_persona->idlocalidad);
            $model->localidad = $model_800_persona->idlocalidad;
            $model->provincia = $model_localidad->idprovincia;

            $selectNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
            $selectGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
            $selectSituacionTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SITUACION_TIPO, true), 'idconfiguracion', 'descripcion');
            $selectDerivacion = ArrayHelper::map(Sds_800_derivacion::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(), 'idderivacion', 'descripcion');
            $listprofesionales = $this->getListProfesionales();

            if ($request->isGet) {
                return $this->render('update', [
                    'model' => $model,
                    'listProvincias' => $listProvincias,
                    'selectNacionalidad' => $selectNacionalidad,
                    'selectGenero' => $selectGenero,
                    'selectSituacionTipo' => $selectSituacionTipo,
                    'selectDerivacion' => $selectDerivacion,
                    'listprofesionales' => $listprofesionales,
                ]);
            } else if ($model->load($request->post())) {
                $model->cierre_fecha = date('Y-m-d');
                if ($model->cierre_detalle == null || empty(trim($model->cierre_detalle))) {
                    $model->addError("cierre_detalle", "Debe ingresar un detalle para el cierre.");
                } else if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sd_800_llamada/cerrar', $id, $model->getAttributes());
                    return $this->redirect(['index', 'area' => $model->area]);
                }
            }
            return $this->render('update', [
                'model' => $model,
                'listProvincias' => $listProvincias,
                'selectNacionalidad' => $selectNacionalidad,
                'selectGenero' => $selectGenero,
                'selectSituacionTipo' => $selectSituacionTipo,
                'selectDerivacion' => $selectDerivacion,
                'listprofesionales' => $listprofesionales,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDespejar($id)
    {
        $model = $this->findModel($id);
        $puedeDespejar = ($model->estado == Sds_800_llamada::ESTADO_PENDIENTE);
        $hasRol800 =  $this->hasRol800($model->area);

        if ($puedeDespejar && $hasRol800) {
            $request = Yii::$app->request;
            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            $model->dni = $model_com_persona->documento;
            $model->estado = Sds_800_llamada::ESTADO_DESPEJADA;
            $listProvincias = Sds_com_provinciaController::getListProvincias();

            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
            $model_localidad = Sds_com_localidad::findOne($model_800_persona->idlocalidad);
            $model->localidad = $model_800_persona->idlocalidad;
            $model->provincia = $model_localidad->idprovincia;

            $selectNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
            $selectGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
            $selectSituacionTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SITUACION_TIPO, true), 'idconfiguracion', 'descripcion');
            $selectDerivacion = ArrayHelper::map(Sds_800_derivacion::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all(), 'idderivacion', 'descripcion');
            $listprofesionales = $this->getListProfesionales();

            if ($request->isGet) {
                return $this->render('update', [
                    'model' => $model,
                    'listProvincias' => $listProvincias,
                    'selectNacionalidad' => $selectNacionalidad,
                    'selectGenero' => $selectGenero,
                    'selectSituacionTipo' => $selectSituacionTipo,
                    'selectDerivacion' => $selectDerivacion,
                    'listprofesionales' => $listprofesionales,
                ]);
            } else if ($model->load($request->post())) {
                $model->cierre_fecha = date('Y-m-d');
                if ($model->cierre_detalle == null || empty(trim($model->cierre_detalle))) {
                    $model->addError("cierre_detalle", "Debe ingresar un detalle.");
                } else if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sd_800_llamada/despejar', $id, $model->getAttributes());
                    return $this->redirect(['index', 'area' => $model->area]);
                }
            }
            return $this->render('update', [
                'model' => $model,
                'listProvincias' => $listProvincias,
                'selectNacionalidad' => $selectNacionalidad,
                'selectGenero' => $selectGenero,
                'selectSituacionTipo' => $selectSituacionTipo,
                'selectDerivacion' => $selectDerivacion,
                'listprofesionales' => $listprofesionales,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionNc($id)
    {
        $model = $this->findModel($id);
        $puedeNoCorresponde = ($model->estado == Sds_800_llamada::ESTADO_PENDIENTE);
        $hasRol800 =  $this->hasRol800($model->area);

        if ($puedeNoCorresponde && $hasRol800) {

            if ($model->updateAttributes(["estado" => 1])) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sd_800_llamada/nc', $id, $model->getAttributes());
                return [
                    'title' => "Marcar No Corresponde",
                    'content' => '<span class="text-success">Marcada como NO CORRESPODE exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReporte_llamada($idllamada, $area)
    {
        $hasRol800 =  $this->hasRol800($area);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sd_800_llamada/reporte_llamada', $idllamada, array('area' => $area));
        if ($hasRol800) {
            $llamadaDatos = Sds_800_llamada::find()->select(["
                    case  llamada.afectado_tratamiento
                        WHEN 0 then 'Paciente Adicciones'
                        WHEN 1 then 'Paciente Salud Mental'
                        WHEN 2 then 'Paciente Duales'
                    END llamadaTratamiento,
                    case llamada.area	
                        WHEN 0 then 'Situación de Calle'
                        WHEN 1 then 'Familia'
                        WHEN 2 then 'Adultos Mayores'
                        when 3 then 'Interior'
                        when 4 then 'Violencia'
                    END llamadaArea,
                    case llamada.estado	
                            WHEN 0 then 'Pendiente de Evaluación'
                            WHEN 1 then 'No Corresponde'
                            WHEN 2 then 'Derivada'
                            WHEN 3 then 'Atendida'
                            WHEN 4 then 'Cerrada'
                            WHEN 5 then 'Situación Despejada'
                    END as llamadaEstado,
                    llamada.solicitante as llamadasolicitante, llamada.institucion as llamadainstitucion,llamada.vinculo as llamadavinculo,llamada.detalle as llamadadetalle,llamada.afectado_dni as llamadaafectadodni,llamada.afectado_nombre as llamadaafectadonombre,llamada.afectado_apodo as llamadaafectadoapodo,llamada.derivacion_referente as llamadaderivacionreferente,llamada.derivacion_detalle as llamadaderivaciondetalle,llamada.cierre_detalle as llamadacierredetalle,DATE_FORMAT(llamada.fecha_hora,'%d/%m/%Y %H:%i') as fechaLlamada,DATE_FORMAT(llamada.derivacion_fecha,'%d/%m/%Y') as fechaDerivacion, DATE_FORMAT(llamada.cierre_fecha,'%d/%m/%Y') as fechaCierre,llamada.latitud as llamadalatitud,llamada.longitud as llamadalongitud,llamada.direccion as llamadadireccion,
                    persona0800.domicilio as 800domicilio,persona0800.telefono as 800telefono,localidad0800.descripcion as 800localidad,provincia0800.descripcion as 800provincia,
                    nacionalidad.descripcion as nacionalidad,
                    genero.descripcion as genero,
                    documento_tipo.descripcion as documentotipo,
                    atencion.idllamada as idatencion,
                    tipo.descripcion as tipo, 
                    derivacion.descripcion as derivaciondetalle, derivacion.direccion as derivaciondireccion, derivacion.telefonos as derivaciontelefonos,
                    UPPER(usuario.nombre) as usuarionombre, UPPER(usuario.apellido) as usuarioapellido,
                    usuario_deriva.nombre as usuario_deriva_nombre, usuario_deriva.apellido as usuario_deriva_apellido,
                    llamada.idderivacion as idderivacion, llamada.idorigen, llamada.estado,
                    UPPER(persona.nombre) as personanombre,UPPER(persona.apellido) as personaapellido,DATE_FORMAT(persona.fecha_nacimiento,'%d/%m/%Y') personafechanacimiento,persona.documento as personadocumento,UPPER(CONCAT(profesional.apellido,', ',profesional.nombre)) as nombre_profesional"])
                ->from(["sds_800_llamada llamada"])
                ->join("inner join", "sds_800_persona as persona0800", "persona0800.idpersona=llamada.idpersona")
                ->join("inner join", "sds_com_localidad as localidad0800", "persona0800.idlocalidad=localidad0800.idlocalidad")
                ->join("inner join", "sds_com_provincia as provincia0800", "localidad0800.idprovincia=provincia0800.idprovincia")
                ->join("inner join", "sds_com_persona as persona", "persona.idpersona=persona0800.idpersona")
                ->join("left join", "sds_800_llamada as atencion", "llamada.idllamada=atencion.idllamada")
                ->join("left join", "sds_800_derivacion as derivacion", "llamada.idderivacion=derivacion.idderivacion")
                ->join("left join", "sds_com_configuracion as nacionalidad", "persona.nacionalidad=nacionalidad.idconfiguracion")
                ->join("left join", "sds_com_configuracion as genero", "persona.genero=genero.idconfiguracion")
                ->join("left join", "sds_com_configuracion as documento_tipo", "persona.documento_tipo=documento_tipo.idconfiguracion")
                ->join("left join", "sds_com_configuracion as tipo", "tipo.idconfiguracion=llamada.tipo")
                ->join("left join", "mds_seg_usuario as usuario", "usuario.idusuario=llamada.idusuario")
                ->join("left join", "mds_seg_usuario as usuario_deriva", "usuario_deriva.idusuario=llamada.idusuario_deriva")
                ->join("left join", "sds_800_llamada as origen", "origen.idllamada=llamada.idorigen")
                ->join("left join", "mds_seg_usuario as profesional", "profesional.idusuario=llamada.profesional_interviniente")
                ->where(["llamada.idllamada" => $idllamada])->asArray()->one();;

            if ($llamadaDatos['idatencion']) {
                switch ($area) {
                    case Sds_800_llamada::AREA_FAMILIA:
                        $atencion_datos = Sds_800_atencion_familia::find()
                            ->select(["CASE atencion.lugar_intervencion 
                        WHEN 0 THEN 'Comisaria'
                        WHEN 1 THEN 'Escuela'
                        WHEN 2 THEN 'Centro de Salud/Hospital'
                        WHEN 3 THEN 'Familia - Admisión'
                        WHEN 4 THEN 'Familia - Ley 2302'
                        WHEN 5 THEN 'Otro'
                    END lugar_intervencion,
                    CASE atencion.sabe_leer WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END sabe_leer,
                    CASE atencion.beneficio_social WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END beneficio_social,
                    CASE atencion.centro_salud WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END centro_salud,
                    CASE atencion.obra_social WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END obra_social,
                    CASE atencion.tratamiento_medico WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END tratamiento_medico,
                    CASE atencion.nivel_estudio
                        WHEN 0 THEN 'Sin Datos'
                        WHEN 1 THEN 'Primario Incompleto'
                        WHEN 2 THEN 'Primario Completo'
                        WHEN 3 THEN 'Secundario Incompleto'
                        WHEN 4 THEN 'Secundario Completo'
                    END nivel_estudio,DATE_FORMAT(atencion.fecha_intervencion,'%d/%m/%Y') as fechaatencion,DATE_FORMAT(atencion.dia_hora,'%d/%m/%Y %H:%i') as fechasalida,
                    CASE atencion.trabaja WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END trabaja,
                    CASE atencion.atendido WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END atendido,
                    CASE atencion.orientado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END orientado,
                    CASE atencion.intoxicado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END intoxicado,
                    CASE atencion.violentado WHEN 0 then 'Sin Datos' WHEN 1 then 'Si' WHEN 2 then 'No' END violentado,
                    atencion.lugar_especificacion, atencion.defensora, atencion.edad, atencion.alojado, atencion.hogar,
                    atencion.operador,atencion.equipo_tecnico,atencion.establecimiento,atencion.tipo_trabajo,atencion.nombre_obra_social,atencion.plan_accion,
                    atencion.institucion,atencion.nombre_profesionales,atencion.area_beneficio,atencion.nombre_centro_salud,atencion.tratamiento_institucion,atencion.archivo_adjunto,
                    persona0800.telefono as 800telefono,localidad0800.descripcion as 800localidad,provincia0800.descripcion as 800provincia,
                    UPPER(persona.nombre) as personanombre,UPPER(persona.apellido) as personaapellido,persona.documento as personadocumento,
                    referente0800.telefono as referente800telefono,referente0800.domicilio as referente800domicilio,referentelocalidad0800.descripcion as referente800localidad,referenteprovincia0800.descripcion as referente800provincia,
                    referente.nombre as referentenombre,referente.apellido as referenteapellido,referente.documento as referentedocumento,DATE_FORMAT(referente.fecha_nacimiento,'%d/%m/%Y') as referentefechanacimiento,
                    nacionalidad.descripcion as nacionalidad,
                    parentezco.descripcion as parentezco,
                    genero.descripcion as genero,
                    documento_tipo.descripcion as documentotipo"])
                            ->from(["sds_800_atencion_familia atencion"])
                            ->join("left join", "sds_800_persona as persona0800", "persona0800.idpersona=atencion.idpersona")
                            ->join("left join", "sds_com_localidad as localidad0800", "persona0800.idlocalidad=localidad0800.idlocalidad")
                            ->join("left join", "sds_com_provincia as provincia0800", "localidad0800.idprovincia=provincia0800.idprovincia")
                            ->join("left join", "sds_com_persona as persona", "persona.idpersona=persona0800.idpersona")
                            ->join("left join", "sds_800_persona as referente0800", "referente0800.idpersona=atencion.idpersona_referente")
                            ->join("left join", "sds_com_localidad as referentelocalidad0800", "referente0800.idlocalidad=referentelocalidad0800.idlocalidad")
                            ->join("left join", "sds_com_provincia as referenteprovincia0800", "referentelocalidad0800.idprovincia=referenteprovincia0800.idprovincia")
                            ->join("left join", "sds_com_persona as referente", "referente.idpersona=referente0800.idpersona")
                            ->join("left join", "sds_com_configuracion as nacionalidad", "referente.nacionalidad=nacionalidad.idconfiguracion")
                            ->join("left join", "sds_com_configuracion as genero", "referente.genero=genero.idconfiguracion")
                            ->join("left join", "sds_com_configuracion as parentezco", "atencion.parentezco=parentezco.idconfiguracion")
                            ->join("left join", "sds_com_configuracion as documento_tipo", "referente.documento_tipo=documento_tipo.idconfiguracion")
                            ->join("left join", "mds_seg_usuario as usuario", "usuario.idusuario=atencion.idusuario")
                            ->where(["atencion.idllamada" => $llamadaDatos['idatencion']])
                            ->asArray()->one();
                        break;
                    case Sds_800_llamada::AREA_VIOLENCIA:
                        $atencion_datos = Sds_vio_intervencion::find()
                            ->select(["comper.idpersona, intervencion.idintervencion, comper.documento AS doc_victima, comper.nombre AS nombre_victima, comper.apellido AS ape_victima,
                            (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=comper.genero)  AS sex_victima,
                            (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=vioper.genero_autopercibido)  AS genero_autopercibido, 
                            (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=comper.nacionalidad) AS nac_victima,
                            DATE_FORMAT(comper.fecha_nacimiento,'%d/%m/%Y') as nacimiento_victima,
                            vioper.telefono AS tel_victima, vioper.domicilio AS dom_victima, 
                            (SELECT descripcion FROM sds_com_localidad WHERE idlocalidad= vioper.idlocalidad) AS loc_victima,
                            (SELECT idlocalidad FROM sds_com_localidad WHERE idlocalidad= vioper.idlocalidad) AS id_loc_victima,
                            (SELECT descripcion FROM sds_com_localidad WHERE idlocalidad= vioper.localidad_oriunda) AS loc_oriunda,
                                DATE_FORMAT(intervencion.fecha,'%d/%m/%Y') as fechaatencion,
                            usuario.nombre as nombre_atencion, usuario.apellido as apellido_atencion,
                            case  intervencion.ingreso
                            WHEN 0 then 'Re-ingreso'
                            WHEN 1 then 'Nuevo Ingreso'
                            END as ingreso, (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.tipo) AS tipo,
                            (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.derivacion) AS derivacion,
	                        (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.tipo_modalidad) AS modalidad,
                            case  intervencion.denuncia
                            WHEN 0 then 'No realizó denuncia'
                            WHEN 1 then 'Si realizó denuncia'
                            END as denuncia, intervencion.juzgado, intervencion.detalle,intervencion.detalle_plataforma, intervencion.profesionales_intervinientes,
                            intervencion.tipo_violencia_fisica,intervencion.tipo_violencia_psicologica,intervencion.tipo_violencia_sexual,
                            intervencion.tipo_violencia_economica_patrimonial,intervencion.tipo_violencia_simbolica,intervencion.tipo_violencia_negligencia_abandono,intervencion.tipo_violencia_ambiental,
                            referente_nombre,
                            referente_telefono, referente_vinculo, 
                            case  intervencion.tipo_situacion
                            WHEN 0 then 'Código A'
                            WHEN 1 then 'Código B'
                            when 2 then 'Asesoramiento'
                            END as tipo_situacion, 
                            abordaje_complementario,
                            (SELECT descripcion FROM sds_com_localidad WHERE idlocalidad= intervencion.localidad_hecho) AS loc_hecho,
                            intervencion.idllamada, 
                                (SELECT descripcion FROM sds_com_configuracion WHERE idconfiguracion=intervencion.tipo_violencia) AS tipo_violencia,
                            case  intervencion.consumo_problematico
                            WHEN 1 then 'Presenta consumo problemático'
                            WHEN 0 then 'No presenta consumo problemático'
                            END as consoumo_problemático"])
                            ->from(["sds_vio_intervencion intervencion"])
                            ->join("join", "sds_vio_persona as vioper", "vioper.idpersona=intervencion.idpersona")
                            ->join("join", "sds_com_persona as comper", "comper.idpersona = vioper.idpersona")
                            ->join("join", "mds_seg_usuario as usuario", "usuario.idusuario= intervencion.idusuario ")
                            ->where(['intervencion.idllamada' => $llamadaDatos['idatencion'], 'intervencion.deleted_at' => null])
                            ->asArray()->one();

                        if ($atencion_datos) {
                            $localidad = Sds_com_localidad::find()
                                ->where(['idlocalidad' => $atencion_datos['id_loc_victima'], 'activo' => 1])
                                ->one();
                            $provincia = Sds_com_provincia::find()
                                ->where(['idprovincia' => $localidad->idprovincia, 'activo' => 1])
                                ->orderBy(['descripcion' => SORT_ASC])
                                ->asArray()
                                ->one();
                            $atencion_datos['prov_victima'] = $provincia ? $provincia['descripcion'] : "";
                        }
                        $agresores = [];
                        $hijos = [];
                        $arrayViolencias = [];
                        $arrayAgresores = [];
                        $arrayMovimientos = [];

                        if ($atencion_datos) {
                            $intervencion = $atencion_datos['idintervencion'];

                            //arreglo de movimientos
                            $arrayMovimientos = Sds_vio_intervencion_movimiento::getMovimientosByIntervencion($intervencion);

                            //arreglo de agresores
                            $agresores = Sds_vio_intervencion_agresor::find()
                                ->select('*,
                                        sds_com_configuracion.descripcion as parentesco,
                                        generoConfiguracion.descripcion as generoDetalle,
                                        vinculoConfiguracion.descripcion as vinculoPersonalSeguridad,
                                        escolaridadConfiguracion.descripcion as escolaridadDetalle
                                        ')
                                ->innerJoin('sds_vio_agresor', 'sds_vio_intervencion_agresor.idagresor = sds_vio_agresor.idagresor')
                                ->leftJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = sds_vio_intervencion_agresor.parentezco')
                                ->leftJoin('sds_com_configuracion generoConfiguracion', 'generoConfiguracion.idconfiguracion = sds_vio_agresor.genero')
                                ->leftJoin('sds_com_configuracion vinculoConfiguracion', 'vinculoConfiguracion.idconfiguracion = sds_vio_agresor.vinculo_personal_seguridad')
                                ->leftJoin('sds_com_configuracion escolaridadConfiguracion', 'escolaridadConfiguracion.idconfiguracion = sds_vio_agresor.escolaridad')
                                ->where(['idintervencion' => $intervencion])
                                ->andWhere(['sds_vio_intervencion_agresor.activo' => 1])
                                ->asArray()
                                ->all();
                            if ($agresores) {
                                foreach ($agresores as $agresor => $value) {
                                    $arrayConsumos = Sds_vio_agresor_consumo::getConsumoByAgresor($value['idagresor']);
                                    $value['consumoDetalle'] = $arrayConsumos;
                                    $arrayAgresores[$agresor] = $value;
                                }
                            }
                            //arreglo de hijos
                            $hijos = sds_com_persona::find()
                                ->select(["*"])
                                ->from(["sds_com_persona"])
                                ->where(["padre" => $atencion_datos['idpersona']])
                                ->asArray()->all();

                            $arrayViolencias['violencia']['fisica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
                            $arrayViolencias['violencia']['psicologica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
                            $arrayViolencias['violencia']['sexual'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
                            $arrayViolencias['violencia']['economicaPatrimonial'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
                            $arrayViolencias['violencia']['simbolica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
                            $arrayViolencias['violencia']['negligenciaAbandono'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);
                            $arrayViolencias['violencia']['ambiental'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($atencion_datos['idintervencion'], Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);

                            $modelFrecuenciaFisica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['fisicaFrecuencia'] = $modelFrecuenciaFisica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaFisica->idfrecuencia) : null;
                            $arrayViolencias['violencia']['fisicaOcurrencia'] = $modelFrecuenciaFisica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaFisica->idocurrencia) : null;
                            $arrayViolencias['violencia']['fisicaVigencia'] = $modelFrecuenciaFisica ? $modelFrecuenciaFisica->vigencia_actualidad : null;

                            $modelFrecuenciaPsicologica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['psicologicaFrecuencia'] = $modelFrecuenciaPsicologica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaPsicologica->idfrecuencia) : null;
                            $arrayViolencias['violencia']['psicologicaOcurrencia'] = $modelFrecuenciaPsicologica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaPsicologica->idocurrencia) : null;
                            $arrayViolencias['violencia']['psicologicaVigencia'] = $modelFrecuenciaPsicologica ? $modelFrecuenciaPsicologica->vigencia_actualidad : null;

                            $modelFrecuenciaSexual = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['sexualFrecuencia'] = $modelFrecuenciaSexual ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSexual->idfrecuencia) : null;
                            $arrayViolencias['violencia']['sexualOcurrencia'] = $modelFrecuenciaSexual ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSexual->idocurrencia) : null;
                            $arrayViolencias['violencia']['sexualVigencia'] = $modelFrecuenciaSexual ? $modelFrecuenciaSexual->vigencia_actualidad : null;

                            $modelFrecuenciaEconomicaPatrimonial = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['economicaPatrimonialFrecuencia'] = $modelFrecuenciaEconomicaPatrimonial ? Sds_com_configuracion::getDescripcion($modelFrecuenciaEconomicaPatrimonial->idfrecuencia) : null;
                            $arrayViolencias['violencia']['economicaPatrimonialOcurrencia'] = $modelFrecuenciaEconomicaPatrimonial ? Sds_com_configuracion::getDescripcion($modelFrecuenciaEconomicaPatrimonial->idocurrencia) : null;
                            $arrayViolencias['violencia']['economicaPatrimonialVigencia'] = $modelFrecuenciaEconomicaPatrimonial ? $modelFrecuenciaEconomicaPatrimonial->vigencia_actualidad : null;

                            $modelFrecuenciaSimbolica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['simbolicaFrecuencia'] = $modelFrecuenciaSimbolica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSimbolica->idfrecuencia) : null;
                            $arrayViolencias['violencia']['simbolicaOcurrencia'] = $modelFrecuenciaSimbolica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSimbolica->idocurrencia) : null;
                            $arrayViolencias['violencia']['simbolicaVigencia'] = $modelFrecuenciaSimbolica ? $modelFrecuenciaSimbolica->vigencia_actualidad : null;

                            $modelFrecuenciaNegligenciaAbandono = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['negligenciaAbandonoFrecuencia'] = $modelFrecuenciaNegligenciaAbandono ? Sds_com_configuracion::getDescripcion($modelFrecuenciaNegligenciaAbandono->idfrecuencia) : null;
                            $arrayViolencias['violencia']['negligenciaAbandonoOcurrencia'] = $modelFrecuenciaNegligenciaAbandono ? Sds_com_configuracion::getDescripcion($modelFrecuenciaNegligenciaAbandono->idocurrencia) : null;
                            $arrayViolencias['violencia']['negligenciaAbandonoVigencia'] = $modelFrecuenciaNegligenciaAbandono ? $modelFrecuenciaNegligenciaAbandono->vigencia_actualidad : null;

                            $modelFrecuenciaAmbiental = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $atencion_datos['idintervencion'], 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL, 'deleted_at' => null])->one();
                            $arrayViolencias['violencia']['ambientalFrecuencia'] = $modelFrecuenciaAmbiental ? Sds_com_configuracion::getDescripcion($modelFrecuenciaAmbiental->idfrecuencia) : null;
                            $arrayViolencias['violencia']['ambientalOcurrencia'] = $modelFrecuenciaAmbiental ? Sds_com_configuracion::getDescripcion($modelFrecuenciaAmbiental->idocurrencia) : null;
                            $arrayViolencias['violencia']['ambientalVigencia'] = $modelFrecuenciaAmbiental ? $modelFrecuenciaAmbiental->vigencia_actualidad : null;
                        }
                        break;
                }
            }

            switch ($area) {
                case Sds_800_llamada::AREA_SITUACIONDECALLE:
                    $content = $this->renderPartial('reporte_atencion', ['idllamada' => $idllamada]);
                    break;
                case Sds_800_llamada::AREA_FAMILIA:
                    $content = $this->renderPartial('reporte_atencion_familia', ['idllamada' => $idllamada, 'llamadaDatos' => $llamadaDatos, 'atencionDatos' => $atencion_datos]);
                    break;
                case Sds_800_llamada::AREA_ADULTOSMAYORES:
                    $content = $this->renderPartial('reporte_atencion_am', ['idllamada' => $idllamada]);
                    break;
                case Sds_800_llamada::AREA_INTERIOR:
                    $content = $this->renderPartial('reporte_atencion_interior', ['idllamada' => $idllamada]);
                    break;
                case Sds_800_llamada::AREA_VIOLENCIA:
                    $content = $this->renderPartial('reporte_violencia', ['idllamada' => $idllamada, 'llamadaDatos' => $llamadaDatos, 'atencionDatos' => $atencion_datos, 'agresores' => $arrayAgresores, 'hijos' => $hijos, 'arrayViolencias' => $arrayViolencias, 'arrayMovimientos' => $arrayMovimientos,]);
                    break;
                case 'default':
                    $content = '';
                    break;
            }
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
                    'SetTitle' => 'REPORTE LLAMADA ' . $idllamada,
                    'SetHeader' => null,
                    'SetFooter' => null,
                ]
            ]);

            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDashboard()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $hasRolDashboard = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_DASHBOARD);

        if ($hasRolAdminGeneral || $hasRolDashboard) {
            $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
            $fechaFinOriginal = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;
            $fechaFin = null;
            if ($fechaFinOriginal) {
                $fechaFin = date_create($fechaFinOriginal);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }

            $model = new Sds_800_llamada();
            $modelVioIntervencion = new Sds_vio_intervencion();
            $where = "sds_800_llamada.deleted_at IS NULL";
            $whereVioIntervencion = "sds_vio_intervencion.deleted_at IS NULL";
            $whereFecha = "";
            $whereFechaIntervencion = "";
            if ($fechaInicio && $fechaFin) {
                $whereFecha = " AND fecha_hora >= '$fechaInicio' AND fecha_hora <= '$fechaFin'";
                $whereFechaIntervencion = " AND fecha >= '$fechaInicio' AND fecha <= '$fechaFinOriginal'";
            } else if ($fechaInicio) {
                $whereFecha = " AND fecha_hora >= '$fechaInicio'";
                $whereFechaIntervencion = " AND fecha >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFecha = " AND fecha_hora <= '$fechaFin'";
                $whereFechaIntervencion = " AND fecha <= '$fechaFinOriginal'";
            }
            $where .= $whereFecha;
            $whereVioIntervencion .= $whereFechaIntervencion;
            $totalGuardias = $model->find()
                ->select([
                    'area',
                ])
                ->where($where)
                ->groupBy("sds_800_llamada.idllamada")
                ->all();

            $totalVioIntervencion = $modelVioIntervencion->find()
                ->select([
                    'idintervencion',
                ])
                ->where($whereVioIntervencion)
                ->groupBy("sds_vio_intervencion.idintervencion")
                ->all();

            $arrayIngresos = [
                [
                    'descripcion' => 'Situación de Calle',
                    'titulo' => 'Ingresos',
                    'cantidadRegistros' => 0,
                    'url' => '&area=0',
                ],
                [
                    'descripcion' => 'Familia',
                    'titulo' => 'Ingresos',
                    'cantidadRegistros' => 0,
                    'url' => '&area=1',
                ],
                [
                    'descripcion' => 'Adultos Mayores',
                    'titulo' => 'Ingresos',
                    'cantidadRegistros' => 0,
                    'url' => '&area=2',
                ],
                [
                    'descripcion' => 'Interior',
                    'titulo' => 'Ingresos',
                    'cantidadRegistros' => 0,
                    'url' => '&area=3',
                ],
                [
                    'descripcion' => 'Violencia',
                    'titulo' => 'Ingresos',
                    'cantidadRegistros' => 0,
                    'url' => '&area=4',
                ],
            ];

            foreach ($totalGuardias as $guardia) {
                if (!is_null($guardia['area'])) {
                    $arrayIngresos["{$guardia['area']}"]['cantidadRegistros']++;
                }
            }

            $arrayIndicadores = array_merge($arrayIngresos);

            return $this->render('dashboard/index', [
                'totalGuardias' => $totalGuardias,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFinOriginal,
                'arrayIndicadores' => $arrayIndicadores,
                'totalVioIntervencion' => $totalVioIntervencion,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionValidar_dni($dni)
    {
        $hasRol800 =  $this->hasRol800(null);

        if ($hasRol800) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_llamada/validar_dni', $dni, array());
            $result = array();
            $model_persona = Sds_com_persona::find()->where(["documento" => $dni])->one();
            $idlocalidad = null;
            if ($model_persona != null) {
                array_push($result, $model_persona->getAttributes());
                $idlocalidad = $model_persona->idlocalidad ? $model_persona->idlocalidad : null;
                $model_800_persona = Sds_800_persona::findOne($model_persona->idpersona);
                if ($model_800_persona != null) {
                    array_push($result, $model_800_persona->getAttributes());
                    $idlocalidad = $idlocalidad ? $idlocalidad : ($model_800_persona->idlocalidad ? $model_800_persona->idlocalidad : null);
                }
            }

            if ($idlocalidad) {
                $provincia = Sds_com_localidad::find()
                    ->innerJoin('sds_com_provincia', 'sds_com_localidad.idprovincia = sds_com_provincia.idprovincia')
                    ->where(["idlocalidad" => $idlocalidad])
                    ->one();
                $result += ["idlocalidad" => $idlocalidad];
                $result += ["idprovincia" => $provincia->idprovincia];
            }
            if ($dni) {
                $risneu = Sds_ris_risneu::find()
                    ->where(["dni" => $dni, 'activo' => 1, 'deleted_at' => null])
                    ->one();
                if ($risneu) {
                    $result += ["idrisneu" => $risneu->idrisneu];
                }
            }

            return json_encode($result);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGet_datos_derivacion($idderivacion)
    {
        $hasRol800 =  $this->hasRol800(null);

        if ($hasRol800) {
            $result = array();
            $model = Sds_800_derivacion::findOne($idderivacion);
            if ($model != null) {
                $result = array("direccion" => $model->direccion, "telefonos" => $model->telefonos);
            }
            return json_encode($result);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGet_id_localidad($localidad)
    {
        $hasRol800 =  $this->hasRol800(null);

        if ($hasRol800) {
            $result = array();
            $model_localidad = Sds_com_localidad::find()->where("descripcion like '%" . $localidad . "%'")->orderBy(["descripcion" => SORT_ASC])->limit(1)->one();
            if ($model_localidad != null) {
                $result = array("idlocalidad" => $model_localidad->idlocalidad);
            }
            return json_encode($result);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Sds_800_llamada model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_800_llamada the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_800_llamada::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getListProfesionales()
    {
        //Busqueda usuarios con rol operador 0800_familia
        $profesionales = Mds_seg_usuario_rol::usersWithRol(Sds_800_llamada::ROL_OPERADOR0800_FAMILIA);
        $profesionales = ArrayHelper::map($profesionales, 'idusuario', 'apellido_nombre');
        return $profesionales;
    }

    protected function getFilterUsuarioCarga($area)
    {
        //Busqueda de usuarios que cargaron intervenciones
        $usuarioFiltro = Sds_800_llamada::findBySql(
            "
                SELECT 
                    idllamada, 
                    usuario.idusuario as idusuario,
                    usuario.user as user    
                FROM sds_800_llamada llamada 
                INNER JOIN mds_seg_usuario usuario ON llamada.idusuario = usuario.idusuario
                WHERE llamada.deleted_at IS NULL AND llamada.area = $area
                GROUP BY usuario.idusuario
                ORDER BY usuario.user ASC
            "
        )->asArray()->all();
        $usuarioFiltro = ArrayHelper::map($usuarioFiltro, 'idusuario', 'user');
        return $usuarioFiltro;
    }

    protected function getFilterProfesionales()
    {
        //Busqueda de usuarios profesionales intervinientes que se encuentran cargados en intervenciones
        $usuarioFiltro = Sds_800_llamada::findBySql(
            "
                SELECT 
                    idllamada, 
                    usuario.idusuario as idusuario,
                    usuario.user as user,
                    UPPER(CONCAT(usuario.apellido,', ',usuario.nombre)) as user_nombre   
                FROM sds_800_llamada llamada 
                INNER JOIN mds_seg_usuario usuario ON llamada.profesional_interviniente = usuario.idusuario
                GROUP BY usuario.idusuario
                ORDER BY user_nombre ASC
            "
        )->asArray()->all();
        $usuarioFiltro = ArrayHelper::map($usuarioFiltro, 'idusuario', 'user_nombre');
        return $usuarioFiltro;
    }

    protected function getFilterTipo()
    {
        $tipoFiltro = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SITUACION_TIPO, true);
        $tipoFiltro = ArrayHelper::map($tipoFiltro, 'idconfiguracion', 'descripcion');
        return $tipoFiltro;
    }

    protected function getFilterGenero()
    {
        $generoFiltro = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, true);
        $generoFiltro = ArrayHelper::map($generoFiltro, 'idconfiguracion', 'descripcion');
        return $generoFiltro;
    }

    protected function getFilterDerivacion()
    {
        $derivacionFiltro = Sds_800_derivacion::find()->where(['activo' => 1])->orderBy(['descripcion' => SORT_ASC])->all();
        $derivacionFiltro = ArrayHelper::map($derivacionFiltro, 'idderivacion', 'descripcion');
        return $derivacionFiltro;
    }

    protected function eliminarIntervencion($idintervencion)
    {
        $iduser = Yii::$app->user->id;
        $model =  Sds_vio_intervencion::findOne($idintervencion);

        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = $iduser;

        $model_vio_persona = null;
        if ($model->idpersona > 0) {
            $model_vio_persona = Sds_vio_persona::findOne($model->idpersona);
        }
        $model->genero_autopercibido = $model_vio_persona->genero_autopercibido;
        $model->dni = $model_vio_persona->genero_autopercibido;
        $model->domicilio = $model_vio_persona->domicilio;
        $model->localidad = $model_vio_persona->idlocalidad;
        $model->telefono = $model_vio_persona->telefono;
        if ($model->localidad_hecho) {
            $localidad_hecho = Sds_com_localidad::findOne($model->localidad_hecho);
        }
        $model->provincia_hecho = $localidad_hecho->idprovincia;

        if ($model_vio_persona->idlocalidad) {
            $localidad = Sds_com_localidad::findOne($model_vio_persona->idlocalidad);
        }
        $model->provincia = $localidad->idprovincia;
        $model->idusuario_borra = Yii::$app->user->id;

        $guardado = $model->update();
        if ($guardado) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion', $idintervencion, $model->getAttributes());
        }
        return $guardado;
    }

    protected function reactivarIntervencion($idintervencion)
    {
        $model =  Sds_vio_intervencion::findOne($idintervencion);

        $model->deleted_at = null;
        $model->idusuario_borra = null;

        $model_vio_persona = null;
        if ($model->idpersona > 0) {
            $model_vio_persona = Sds_vio_persona::findOne($model->idpersona);
        }
        $model->genero_autopercibido = $model_vio_persona->genero_autopercibido;
        $model->dni = $model_vio_persona->genero_autopercibido;
        $model->domicilio = $model_vio_persona->domicilio;
        $model->localidad = $model_vio_persona->idlocalidad;
        $model->telefono = $model_vio_persona->telefono;
        if ($model->localidad_hecho) {
            $localidad_hecho = Sds_com_localidad::findOne($model->localidad_hecho);
        }
        $model->provincia_hecho = $localidad_hecho->idprovincia;

        if ($model_vio_persona->idlocalidad) {
            $localidad = Sds_com_localidad::findOne($model_vio_persona->idlocalidad);
        }
        $model->provincia = $localidad->idprovincia;
        $model->idusuario_borra = Yii::$app->user->id;

        $guardado = $model->update();
        if ($guardado) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion', $idintervencion, $model->getAttributes());
        }
        return $guardado;
    }

    private function hasRol800($area)
    {
        if (is_null($area)) {
            $hasRol800 = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL)
                || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800)
                || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800_FAMILIA)
                || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800_ADULTOSMAYORES)
                || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800_INTERIOR)
                || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_JERARQUICO)
                || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_ADMINISTRACION)
                || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_PROFESIONAL);
        } else {
            switch ($area) {
                case Sds_800_llamada::AREA_SITUACIONDECALLE:
                    $hasRol800 = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800)
                        || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
                    break;
                case Sds_800_llamada::AREA_FAMILIA:
                    $hasRol800 = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800_FAMILIA)
                        || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
                    break;
                case Sds_800_llamada::AREA_ADULTOSMAYORES:
                    $hasRol800 = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800_ADULTOSMAYORES)
                        || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
                    break;
                case Sds_800_llamada::AREA_INTERIOR:
                    $hasRol800 = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ROL_OPERADOR0800_INTERIOR)
                        || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
                    break;
                case Sds_800_llamada::AREA_VIOLENCIA:
                    $hasRol800 = Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_JERARQUICO)
                        || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_ADMINISTRACION)
                        || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_PROFESIONAL)
                        || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
                    break;
                default:
                    $hasRol800 = false;
                    break;
            }
        }
        return $hasRol800;
    }
}
