<?php

namespace app\controllers;

use Yii;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;

use app\models\Sds_vio_intervencion;
use app\models\Sds_vio_intervencionSearch;
use app\models\Sds_vio_persona;
use app\models\Sds_com_persona;
use app\models\Sds_800_llamada;
use app\models\Sds_ris_persona;
use app\models\Sds_vio_agresor;
use app\models\Sds_vio_intervencion_movimiento;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_provincia;
use app\models\Sds_ris_risneu;
use app\models\Sds_vio_intervencion_violencias;
use app\models\Sds_vio_intervencion_violencias_frecuencia;
use app\models\Sds_vio_intervencion_agresor;
use app\models\Sds_vio_agresor_consumo;

use \yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * Sds_vio_intervencionController implements the CRUD actions for Sds_vio_intervencion model.
 */
class Sds_vio_intervencionController extends Controller
{
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
                    'bulk-delete' => ['post'],
                    'reactivate' => ['post'],
                    'validar_dni' => ['post'],
                    'get_id_risneu' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['index', 'create', 'view', 'update', 'delete', 'reactivate', 'reporte_intervencion', 'validar_dni', 'get_id_risneu'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'reactivate', 'reporte_intervencion', 'validar_dni', 'get_id_risneu'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Sds_vio_intervencion::hasRolViolencia());
                        },
                        'denyCallback' => function ($rule, $action) {
                            throw new \Exception('You are not allowed to access this page');
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_vio_intervencion models.
     * @return mixed
     */
    public function actionIndex($fechaInicio = null, $fechaFin = null)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

        $searchModel = new Sds_vio_intervencionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $fechaInicio, $fechaFin);

        $idusuario = Yii::$app->user->identity->idusuario;
        $permiso_violencia = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_VIO_VIOLENCIA . " or iditem=" . Mds_seg_item::MODULO_VIO_EXTERNO . ")")->one();

        $stringButtonsIndex = "{view}";
        if (($permiso_violencia->modifica && $permiso_violencia->iditem == Mds_seg_item::MODULO_VIO_VIOLENCIA) || $hasRolAdminGeneral) {
            $stringButtonsIndex .= " {update} {delete}";
        }

        $permissionsImprimirRisneu = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RIS_RISNEU_IMPRIMIR, $idusuario);
        if (!empty($permissionsImprimirRisneu)) {
            $stringButtonsIndex .= " {imprimirRisneu}";
        }

        $stringButtonsIndex .= " {imprimir} {hijos} {agresores} {movimiento} {reactivate}";

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
            'tipoIntervencionFiltro' => $this->getFilterTipoIntervencion(),
            'derivacionFiltro' => $this->getFilterDerivacion(),
            'usuarioCargaFiltro' => $this->getFilterUsuarioCarga(),
            'stringButtonsIndex' => $stringButtonsIndex,
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
            'idusuario' => $idusuario,
        ]);
    }

    /**
     * Displays a single Sds_vio_intervencion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $model = $this->findModel($id);

        if (is_null($model->deleted_at) || $hasRolAdminGeneral) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion', $id, array());


            //Busco la persona
            $idpersona = $model->idpersona;
            $data_persona = "";
            if ($idpersona != null) {
                $persona = $model->persona0;
                $data_persona = "$persona->apellido, $persona->nombre DNI: $persona->documento";
                $sexo = $persona->genero0->descripcion;
            }

            //Busco la persona de violencia para retornar el telefono
            $persona_violencia = $model->idpersona0;

            //Busco la localidad y provincia de la persona
            $localidad = "";
            $idlocalidad = $persona_violencia->idlocalidad;
            if ($idlocalidad != null) {
                $localidad = $persona_violencia->idlocalidad0->descripcion;
            }
            $provincia = "";
            $idprovincia = $persona_violencia->idlocalidad0->idprovincia;

            if ($idprovincia != null) {
                $provincia = Sds_com_provincia::find()->where(['idprovincia' => $idprovincia, 'activo' => 1])->one();
            }
            $provincia = $provincia->descripcion;
            $idprovincia_oriunda = null;
            $provincia_oriunda_desc = "";
            $localidadOriunda = null;
            if ($persona_violencia->localidad_oriunda) {
                $localidadOriunda = Sds_com_localidad::find()->where(['idlocalidad' => $persona_violencia->localidad_oriunda, 'activo' => 1])->one();
                $idprovincia_oriunda = Sds_com_provincia::find()->where(['idprovincia' => $localidadOriunda->idprovincia, 'activo' => 1])->one();
            }

            if ($idprovincia_oriunda != null) {
                $provincia_oriunda = Sds_com_provincia::find()->where(['idprovincia' => $idprovincia_oriunda, 'activo' => 1])->one();
                $provincia_oriunda_desc = $provincia_oriunda->descripcion;
            }


            //Armo la fecha
            $fecha = $model->fecha;
            $anio = substr($fecha, 0, 4);
            $mes  = substr($fecha, 5, 2);
            $dia = substr($fecha, 8, 2);
            $fecha = "$dia/$mes/$anio";

            //Busco el usuario que cargo
            $idusuario = $model->idusuario;
            if ($idusuario) {
                $usuario = $model->idusuario0;
            }

            //Determino el ingreso
            $ingreso = "No";
            if ($model->ingreso == 1) {
                $ingreso = "Si";
            }

            //Determino el tipo de situacion
            $tipo_situacionString = '';
            switch ($model->tipo_situacion) {
                case Sds_vio_intervencion::TIPO_SITUACION_CODIGO_A:
                    $tipo_situacionString =  "Código A";
                    break;
                case Sds_vio_intervencion::TIPO_SITUACION_CODIGO_B:
                    $tipo_situacionString = "Código B";
                    break;
                case Sds_vio_intervencion::TIPO_SITUACION_ASESORAMIENTO:
                    $tipo_situacionString = "Asesoramiento";
                    break;
                default:
                    break;
            }

            //Busco el tipo de intervencion
            $id_tipo_intervencion = $model->tipo;
            $tipo_intervencion = '';
            if ($id_tipo_intervencion) {
                $tipo_intervencion = $model->tipo0->descripcion;
            }

            //Busco el tipo de modalidad
            $id_tipo_modalidad = $model->tipo_modalidad;
            $tipo_modalidad = '';
            if ($id_tipo_modalidad) {
                $tipo_modalidad = $model->modalidad0->descripcion;
            }

            //Busco la derivacion
            $derivacion = '';
            if ($model->derivacion) {
                $derivacion =  $model->derivacion0->descripcion;
            }

            //Determino la denuncia
            $denuncia = "No";
            if ($model->denuncia == 1) {
                $denuncia = "Si";
            }

            //Determino el consumo problematico
            $consumo_problematico = "No";
            if ($model->consumo_problematico == 1) {
                $consumo_problematico = "Si";
            }

            //Busco la localidad del hecho
            $id_localidad_hecho = $model->localidad_hecho;
            $localidad_hechoString = "";
            if ($id_localidad_hecho) {
                $localidad_hecho = Sds_com_localidad::findOne($id_localidad_hecho);
                $localidad_hechoString = $localidad_hecho->descripcion;
            }
            $id_provincia_hecho = $model->localidadHecho->idprovincia;
            $provincia_hecho = "";
            if ($id_provincia_hecho) {
                $provincia_hecho_obj = Sds_com_provincia::find()->where(['idprovincia' => $id_provincia_hecho, 'activo' => 1])->one();
                $provincia_hecho = $provincia_hecho_obj->descripcion;
            }
            $modelFrecuencia = $this->getTipoViolenciaByIdintertencion($model->idintervencion);

            //Traemos los tipos de violencia
            $vioChecked = Sds_vio_intervencion_violencias::find()->where(['idintervencion' => $model->idintervencion, 'deleted_at' => null])->all();

            $vioFisicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
            $vioPsicologicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
            $vioSexualSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
            $vioEconomicapatrimonialSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
            $vioSimbolicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
            $vioAmbientalSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);
            $vioNegligenciaAbandonoSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);

            return $this->render('view', [
                'model' => $model,
                'data_persona' => $data_persona,
                'persona_violencia' =>  $persona_violencia,
                'provincia' => $provincia,
                'localidad' => $localidad,
                'provincia_oriunda' => $provincia_oriunda_desc,
                'fecha' => $fecha,
                'usuario_carga' => $usuario,
                'ingreso' => $ingreso,
                'tipo_situacion' => $tipo_situacionString,
                'tipo_intervencion' => $tipo_intervencion,
                'derivacion' => $derivacion,
                'denuncia' => $denuncia,
                'provincia_hecho' => $provincia_hecho,
                'localidad_hecho' => $localidad_hechoString,
                'sexo' => $sexo,
                'consumo_problematico' => $consumo_problematico,
                'vioChecked' => $vioChecked,
                'vioFisicaSelectOptions' => $vioFisicaSelectOptions,
                'vioPsicologicaSelectOptions' => $vioPsicologicaSelectOptions,
                'vioSexualSelectOptions' => $vioSexualSelectOptions,
                'vioEconomicapatrimonialSelectOptions' => $vioEconomicapatrimonialSelectOptions,
                'vioSimbolicaSelectOptions' => $vioSimbolicaSelectOptions,
                'vioAmbientalSelectOptions' => $vioAmbientalSelectOptions,
                'vioNegligenciaAbandonoSelectOptions' => $vioNegligenciaAbandonoSelectOptions,
                'model_frecuencia' => $modelFrecuencia,
                'tipo_modalidad' => $tipo_modalidad,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    // En el create por defecto, provincia = Neuquen y localidades de neuquen
    // En el update, ya le mandaria el listado de localidad de la provincia seleccionada por defecto

    /**
     * Creates a new Sds_vio_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idllamada = null, $origen = null)
    {
        $canCreate = $this->verificarCondicionCreacion($idllamada);

        if ($canCreate) {
            $request = Yii::$app->request;
            $model = new Sds_vio_intervencion();
            $model->fecha = date('d-m-Y');
            $user  = Yii::$app->user->identity;
            $model->idllamada = $idllamada;
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;

            $listProvincias = Sds_com_provinciaController::getListProvincias();
            $sexoOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
            $generoOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO, false), 'idconfiguracion', 'descripcion');
            $nacionalidadOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
            $tipoIntervOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_INTERVENCION_TIPO, false), 'idconfiguracion', 'descripcion');
            $derivacionOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_INTERVENCION_DERIVACION, false), 'idconfiguracion', 'descripcion');
            $modalidadOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_MODALIDAD, false), 'idconfiguracion', 'descripcion');

            $modelFrecuencia = new Sds_vio_intervencion_violencias_frecuencia();
            $vioFrecuenciaOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_FRECUENCIA);
            $vioFrecuenciaSelect = ArrayHelper::map($vioFrecuenciaOptions, 'idconfiguracion', 'descripcion');
            $vioOcurrenciasOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_OCURRENCIA);
            $vioOcurrenciasSelect = ArrayHelper::map($vioOcurrenciasOptions, 'idconfiguracion', 'descripcion');

            $vioChecked = null;
            $vioFisicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
            $vioPsicologicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
            $vioSexualSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
            $vioEconomicapatrimonialSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
            $vioSimbolicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
            $vioAmbientalSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);
            $vioNegligenciaAbandonoSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);

            $array_elementos_agregar = [];

            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                ]);
            }
            $model->idusuario = $user->idusuario;
            $origen = urldecode($origen);

            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */
            } else {
                if ($model->load($request->post())) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = true;
                    $model_vio_persona = null;
                    $ban_persona_existente = 0;
                    if ($model->idpersona > 0) {
                        $ban_persona_existente = 1;
                        $model_vio_persona = Sds_vio_persona::findOne($model->idpersona);
                    }

                    $ban_persona_vio_existente = 1;
                    if ($model_vio_persona == null) {
                        $ban_persona_vio_existente = 0;
                        $model_vio_persona = new Sds_vio_persona;
                        $model_vio_persona->idpersona = $model->idpersona;
                    }
                    $model_vio_persona->telefono = $model->telefono;
                    $model_vio_persona->domicilio = $model->domicilio;
                    $model_vio_persona->idlocalidad = $model->localidad;
                    $model_vio_persona->localidad_oriunda = $model->localidad_oriunda;
                    $model_vio_persona->nacionalidad = $model->nacionalidad_origen;
                    $model_vio_persona->genero_autopercibido = $model->genero_autopercibido;
                    if (!$model_vio_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    }

                    $tipos_form = $request->post("Sds_violencia") ? $request->post("Sds_violencia") : array();
                    $arrayViolencias['violencia']['fisica'] = [];
                    $arrayViolencias['violencia']['psicologica'] = [];
                    $arrayViolencias['violencia']['sexual'] = [];
                    $arrayViolencias['violencia']['economica_patrimonial'] = [];
                    $arrayViolencias['violencia']['simbolica'] = [];
                    $arrayViolencias['violencia']['ambiental'] = [];
                    $arrayViolencias['violencia']['negligencia_abandono'] = [];

                    //aca agregamos los nuevos
                    if ($tipos_form) {
                        foreach ($tipos_form['item'] as $elemento_nuevo) {
                            $arrayViolencias['violencia']['fisica'] = $arrayViolencias['violencia']['fisica'] ? $arrayViolencias['violencia']['fisica'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
                            $arrayViolencias['violencia']['psicologica'] = $arrayViolencias['violencia']['psicologica'] ? $arrayViolencias['violencia']['psicologica'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
                            $arrayViolencias['violencia']['sexual'] = $arrayViolencias['violencia']['sexual'] ? $arrayViolencias['violencia']['sexual'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
                            $arrayViolencias['violencia']['economica_patrimonial'] = $arrayViolencias['violencia']['economica_patrimonial'] ? $arrayViolencias['violencia']['economica_patrimonial'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
                            $arrayViolencias['violencia']['simbolica'] = $arrayViolencias['violencia']['simbolica'] ? $arrayViolencias['violencia']['simbolica'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
                            $arrayViolencias['violencia']['ambiental'] = $arrayViolencias['violencia']['ambiental'] ? $arrayViolencias['violencia']['ambiental'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);
                            $arrayViolencias['violencia']['negligencia_abandono'] = $arrayViolencias['violencia']['negligencia_abandono'] ? $arrayViolencias['violencia']['negligencia_abandono'] : Sds_vio_intervencion_violencias::getTipoViolenciaByIdConfiguracion($elemento_nuevo, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);
                        }
                    }

                    $modelPersona = Sds_com_persona::findOne($model->idpersona);


                    /** Inicio informacion adicional a enviar a optic */
                    $modalidad = $model->tipo_modalidad ?? null;
                    $opticFechaAbordaje =  $model->fecha;
                    $opticSexoPersonaId =  $modelPersona ? $modelPersona->genero : null;
                    $opticSexoPersona = Sds_vio_intervencion::opticMapSexoPersona($opticSexoPersonaId);
                    $opticModalidad = Sds_vio_intervencion::opticMapModalidad($modalidad);
                    $opticObservacion = $model->detalle_plataforma ? $model->detalle_plataforma : 'Sin Observacion';

                    $opticArrayTipoViolencia = array();
                    /** Fin informacion adicional a enviar a optic */

                    $opticViolenciaFisica =  Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_FISICA;
                    $opticViolenciaPsicologica = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_PSICOLOGICA;
                    $opticViolenciaSexual = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_SEXUAL;
                    $opticViolenciaEconomicaPatrimonial = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_PATRIMONIAL;
                    $opticViolenciaSimbolica = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_SIMBOLICA;
                    $opticViolenciaNegligenciaAbandono = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_NEGLIGENCIA_ABANDONO;

                    if (count($arrayViolencias['violencia']['fisica']) > 0) {
                        $model->tipo_violencia_fisica = 1;
                        array_push($opticArrayTipoViolencia, $opticViolenciaFisica);
                    } else {
                        $model->tipo_violencia_fisica = 0;
                    }
                    if (count($arrayViolencias['violencia']['psicologica']) > 0) {
                        $model->tipo_violencia_psicologica = 1;
                        array_push($opticArrayTipoViolencia, $opticViolenciaPsicologica);
                    } else {
                        $model->tipo_violencia_psicologica = 0;
                    }
                    if (count($arrayViolencias['violencia']['sexual']) > 0) {
                        $model->tipo_violencia_sexual = 1;
                        array_push($opticArrayTipoViolencia, $opticViolenciaSexual);
                    } else {
                        $model->tipo_violencia_sexual = 0;
                    }
                    if (count($arrayViolencias['violencia']['economica_patrimonial']) > 0) {
                        $model->tipo_violencia_economica_patrimonial = 1;
                        array_push($opticArrayTipoViolencia, $opticViolenciaEconomicaPatrimonial);
                    } else {
                        $model->tipo_violencia_economica_patrimonial = 0;
                    }
                    if (count($arrayViolencias['violencia']['simbolica']) > 0) {
                        $model->tipo_violencia_simbolica = 1;
                        array_push($opticArrayTipoViolencia, $opticViolenciaSimbolica);
                    } else {
                        $model->tipo_violencia_simbolica = 0;
                    }
                    if (count($arrayViolencias['violencia']['negligencia_abandono']) > 0) {
                        $model->tipo_violencia_negligencia_abandono = 1;
                        array_push($opticArrayTipoViolencia, $opticViolenciaNegligenciaAbandono);
                    } else {
                        $model->tipo_violencia_negligencia_abandono = 0;
                    }

                    $model->tipo_violencia_ambiental = count($arrayViolencias['violencia']['ambiental']) > 0 ? 1 : 0;

                    $fecha_vio = ArmarDateParaMySql($model->fecha);
                    $fecha_vio = date_create($fecha_vio);
                    $fecha_vio = date_format($fecha_vio, 'Y-m-d');
                    $model->fecha = $fecha_vio;

                    $fecha_vio_format = date_create($fecha_vio);
                    $fecha_vio_format = date_format($fecha_vio_format, 'd/m/Y');

                    $model->idrisneu = Sds_ris_risneu::getLastIdRisneuByDni($model->dni);
                    $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                    $model->iddispositivo = $contacto->iddispositivo; // Este es el id dispositivo de la persona logeada
                    $model->created_at = date('Y-m-d H:i:s');
                    if ($guardado && $model->save()) {
                        $tipos_form = $request->post("Sds_violencia") ? $request->post("Sds_violencia") : array();

                        //frecuencia ocurrencia y vigencia del tipo de violencia
                        $arrayVioFrecuencia = array_key_exists('Sds_vio_intervencion_violencias_frecuencia', $request->post()) ? $request->post()['Sds_vio_intervencion_violencias_frecuencia'] : null;
                        if ($arrayVioFrecuencia) {
                            $this->guardarViolenciasFrecuencias($model->idintervencion, $arrayVioFrecuencia);
                        }
                        //aca agregamos los nuevos check del tipo de violencia
                        if ($tipos_form) {
                            foreach ($tipos_form['item'] as $elemento_nuevo) {
                                $array_elementos_agregar[] = $elemento_nuevo;
                            }
                            $this->agregarTiposViolencias($model->idintervencion, $array_elementos_agregar);
                        }

                        // Upload archivo adjunto1
                        $tmpFile = UploadedFile::getInstance($model, 'temp_archivo_adjunto1');
                        $date = date('Y-m-d_H_i_s', time());
                        if (isset($tmpFile)) {
                            $extension = $tmpFile->extension;
                            $path_info = pathinfo($tmpFile);
                            $extension = $path_info['extension'];
                            $nameFile = "vio_intervencion_{$model->idintervencion}_{$date}.{$extension}";

                            $model->archivo_adjunto1 = $nameFile;
                            if (!file_exists('uploads/violencia/' . $model->idintervencion . '/')) {
                                mkdir('uploads/violencia/' . $model->idintervencion . '/', 0777, true);
                            }
                            $tmpFile->saveAs('uploads/violencia/' . $nameFile);
                            $model->save();
                        }
                        // Upload archivo adjunto2
                        $tmpFile2 = UploadedFile::getInstance($model, 'temp_archivo_adjunto2');

                        if (isset($tmpFile2)) {
                            $extension = $tmpFile2->extension;
                            $path_info = pathinfo($tmpFile2);
                            $extension = $path_info['extension'];
                            $nameFile = "vio_intervencion_2_{$model->idintervencion}_{$date}.{$extension}";

                            $model->archivo_adjunto2 = $nameFile;
                            if (!file_exists('uploads/violencia/' . $model->idintervencion . '/')) {
                                mkdir('uploads/violencia/' . $model->idintervencion . '/', 0777, true);
                            }
                            $tmpFile2->saveAs('uploads/violencia/' . $nameFile);
                            $model->save();
                        }

                        if ($model->idllamada != null) {
                            $model_llamada = Sds_800_llamada::findOne($model->idllamada);
                            $model_llamada->updateAttributes(["estado" => Sds_800_llamada::ESTADO_ATENDIDA]);
                        }
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_intervencion', $model->idintervencion, $model->getAttributes());
                        if ($ban_persona_existente == 1) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                        }
                        if ($ban_persona_vio_existente == 1) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'Sds_vio_persona', $model->idpersona, $model->getAttributes());
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_vio_persona', $model->idpersona, $model->getAttributes());
                        }

                        Yii::$app->session->setFlash('success', " Se generó correctamente la intervención.");
                        $usuario = env('SUR_USER');
                        $password = env('SUR_PASSWORD');
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        $ch = curl_init();
                        $myvars = [
                            'servicio' => 'login_sur',
                            'auditoria' => $usuario,
                            'usuario_auditoria' => $usuario,
                            'filtro' => "user=$usuario&pass=$password",
                            'tipo' => 0,
                        ];
                        curl_setopt(
                            $ch,
                            CURLOPT_URL,
                            env('ENDPOINT_BACKEND_SUR_PHP')
                        );
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $records = curl_exec($ch);
                        if ($records) {
                            $records = json_decode($records);
                        }
                        curl_close($ch);
                        if ($records->records && $records->records[0]) {
                            $token = $records->records[0]->token;
                            // Obtiene el dispositivo del usuario logueado
                            $recurseroId = 23;

                            $idcontacto  = Yii::$app->user->identity->idcontacto;
                            if ($idcontacto) {
                                $contacto = Mds_org_contacto::findOne($idcontacto);
                                if ($contacto && $contacto->dispositivo) {
                                    $recurseroId =  $contacto->dispositivo->idcapaitem;
                                }
                            }
                            $idLocalidad = 58035070000; //TODO Pendiente realizar mapeo con localidades OPTIC
                            $codigoSituacion = $model::opticMapCodigoSituacion($model->tipo_situacion);

                            $callCurl = curl_init();
                            $idSistema = env('OPTIC_VIOLENCIA_ID_SISTEMA_SUR');
                            $filtroBase = "dni={$model->dni}&genero={$opticSexoPersona}&dniReportado={$model->dni}&nroReferencia={$model->idintervencion}&observacion={$opticObservacion}&fechaDelHecho={$opticFechaAbordaje}&idModalidad={$opticModalidad}&idSistema={$idSistema}&idLocalidad={$idLocalidad}&codigoSituacion={$codigoSituacion}&idRecursero={$recurseroId}&fechaIntervencion={$fecha_vio_format}";
                            $payload = array(
                                'servicio' => 'save_vio_intervencion',
                                'auditoria' => $usuario,
                                'usuario_auditoria' => $usuario,
                                'filtro' => null,
                            );
                            $authorization = 'Authorization: Bearer ' . $token;
                            $cantTipoViolencia = count($opticArrayTipoViolencia); // Obtenemos la cantidad, porque varian los parametros a enviar si es 1 o +1
                            if ($cantTipoViolencia > 0) {
                                $arrayViolenciasOptic = array();
                                foreach ($opticArrayTipoViolencia as $index => $opticTipoViolencia) {
                                    if ($index === 0) {
                                        $filtroBase = $filtroBase . "&idNomenclador={$opticTipoViolencia}";
                                        $payload['filtro'] = $filtroBase;
                                    }
                                    array_push($arrayViolenciasOptic, $opticTipoViolencia);
                                }
                                if (count($arrayViolenciasOptic) > 0) {
                                    $arrayStringViolenciasOptic = implode(",", $arrayViolenciasOptic);
                                    $payload['filtro'] = $filtroBase . "&violencias=" . $arrayStringViolenciasOptic;
                                }

                                curl_setopt($callCurl, CURLOPT_HTTPHEADER, [$authorization]);
                                curl_setopt($callCurl, CURLOPT_URL, env('ENDPOINT_BACKEND_SUR_PHP'));
                                curl_setopt($callCurl, CURLOPT_POST, 1);
                                curl_setopt($callCurl, CURLOPT_POSTFIELDS, $payload);
                                curl_setopt($callCurl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($callCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
                                curl_setopt($callCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
                                $exec = curl_exec($callCurl);
                                curl_close($callCurl);
                            }
                        }
                        if ($origen == null) {
                            return $this->redirect(['index']);
                        } else {
                            return  $this->redirect(['sds_800_llamada/index', 'area' => $origen]);
                        }
                    } else {
                        Yii::$app->session->setFlash('error', " Error al generar la intervención.");
                    }
                }
                $action = 'create';
                return $this->render('create', [
                    'action' => $action,
                    'model' => $model,
                    'vioChecked' => $vioChecked,
                    'vioFisicaSelectOptions' => $vioFisicaSelectOptions,
                    'vioPsicologicaSelectOptions' => $vioPsicologicaSelectOptions,
                    'vioSexualSelectOptions' => $vioSexualSelectOptions,
                    'vioEconomicapatrimonialSelectOptions' => $vioEconomicapatrimonialSelectOptions,
                    'vioSimbolicaSelectOptions' => $vioSimbolicaSelectOptions,
                    'vioAmbientalSelectOptions' => $vioAmbientalSelectOptions,
                    'vioNegligenciaAbandonoSelectOptions' => $vioNegligenciaAbandonoSelectOptions,
                    'listProvincias' => $listProvincias,
                    'listLocalidades' => null,
                    'listLocalidadesOriunda' => null,
                    'listLocalidadesHecho' => null,
                    'modelFrecuencia' => $modelFrecuencia,
                    'vioFrecuenciaSelect' => $vioFrecuenciaSelect,
                    'vioOcurrenciasSelect' => $vioOcurrenciasSelect,
                    'sexoOptions' => $sexoOptions,
                    'generoOptions' => $generoOptions,
                    'nacionalidadOptions' => $nacionalidadOptions,
                    'tipoIntervOptions' => $tipoIntervOptions,
                    'derivacionOptions' => $derivacionOptions,
                    'modalidadOptions' => $modalidadOptions
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_vio_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $idllamada = null, $origen = null)
    {
        $canUpdate = $this->verificarCondicionActualizacion($id, $idllamada);

        if ($canUpdate) {

            $request = Yii::$app->request;
            $user  = Yii::$app->user->identity;
            $model = $this->findModel($id);
            $model->idllamada = $idllamada;
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;

            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            $model->dni = $model_com_persona->documento;
            $model->nombre = $model_com_persona->nombre;
            $model->apellido = $model_com_persona->apellido;
            $model->sexo = $model_com_persona->genero;
            //$model->nacionalidad = $model_com_persona->nacionalidad;

            $model_vio_persona = Sds_vio_persona::findOne($model->idpersona);
            $model->telefono = $model_vio_persona->telefono;
            $model->domicilio = $model_vio_persona->domicilio;
            $model->localidad = $model_vio_persona->idlocalidad;
            $model->nacionalidad_origen = $model_vio_persona->nacionalidad;
            $model->localidad_oriunda = $model_vio_persona->localidad_oriunda;
            $model->genero_autopercibido = $model_vio_persona->genero_autopercibido;

            $model->agresores = Sds_vio_agresor::findBySql('SELECT * FROM sds_vio_intervencion_agresor AS vio_agre 
            JOIN sds_vio_agresor  AS agresor
            ON agresor.idagresor = vio_agre.idagresor
            WHERE vio_agre.idintervencion=' . $model->idintervencion)->all();

            $sexoOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false), 'idconfiguracion', 'descripcion');
            $generoOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO, false), 'idconfiguracion', 'descripcion');
            $nacionalidadOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false), 'idconfiguracion', 'descripcion');
            $tipoIntervOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_INTERVENCION_TIPO, false), 'idconfiguracion', 'descripcion');
            $derivacionOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_INTERVENCION_DERIVACION, false), 'idconfiguracion', 'descripcion');
            $modalidadOptions = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_MODALIDAD, false), 'idconfiguracion', 'descripcion');

            $vioFisicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
            $vioPsicologicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
            $vioSexualSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
            $vioEconomicapatrimonialSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
            $vioSimbolicaSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
            $vioAmbientalSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);
            $vioNegligenciaAbandonoSelectOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);

            $vioChecked = Sds_vio_intervencion_violencias::find()->where(['idintervencion' => $model->idintervencion, 'deleted_at' => null])->all();

            $model->provincia = $model->localidad0->idprovincia;

            $provincia_oriunda = Sds_com_localidad::find()->where(['idlocalidad' => $model_vio_persona->localidad_oriunda])->one();
            $model->provincia_oriunda = $provincia_oriunda ? $provincia_oriunda->idprovincia : '';
            $provincia_hecho = Sds_com_localidad::find()->where(['idlocalidad' => $model->localidad_hecho])->one();
            $model->provincia_hecho = $provincia_hecho->idprovincia;

            $listProvincias = Sds_com_provinciaController::getListProvincias();
            $listLocalidades = Sds_com_provinciaController::getListLocalidadesByProvincia($model->localidad0->idprovincia);
            $listLocalidadesOriunda = $model_vio_persona->localidad_oriunda ? Sds_com_provinciaController::getListLocalidadesByProvincia($model_vio_persona->localidad_oriunda ? $provincia_oriunda->idprovincia : null) : null;
            $listLocalidadesHecho = $model->localidad_hecho ? Sds_com_provinciaController::getListLocalidadesByProvincia($model->localidadHecho->idprovincia) : null;

            $vioFrecuenciaOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_FRECUENCIA);
            $vioFrecuenciaSelect = ArrayHelper::map($vioFrecuenciaOptions, 'idconfiguracion', 'descripcion');
            $vioOcurrenciasOptions = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_VIOLENCIA_OCURRENCIA);
            $vioOcurrenciasSelect = ArrayHelper::map($vioOcurrenciasOptions, 'idconfiguracion', 'descripcion');

            $modelFrecuencia = $this->getTipoViolenciaByIdintertencion($model->idintervencion);

            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                    'model' => $model
                ]);
            }
            $model->idusuario = $user->idusuario;
            //  $origen = urldecode($origen);

            if ($request->isAjax) {
                /*
             *   Process for ajax request
             */
            } else {
                /*
             *   Process for non-ajax request
             *   Yii::$app->response->format = Response::FORMAT_JSON;
             */
                if ($model->load($request->post())) {

                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = true;

                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion_violencia', $model->idintervencion, $model->getAttributes());
                    $tipos_form = $request->post("Sds_violencia") ? $request->post("Sds_violencia") : array();
                    $array_elementos_agregar = [];
                    $array_elementos_eliminar = [];
                    $array_valores_cargados = [];
                    $checked_existentes = Sds_vio_intervencion_violencias::find()->select('idviolenciatipo')->where(['idintervencion' => $model->idintervencion, 'deleted_at' => null])->asArray()->all();

                    foreach ($checked_existentes as $elemento => $value) {
                        foreach ($value as $tipoviolencia => $valor) {
                            $array_valores_cargados[] = $valor;
                        }
                    }

                    //aca agregamos los nuevos
                    if ($tipos_form) {
                        foreach ($tipos_form['item'] as $elemento_nuevo) {
                            if (in_array($elemento_nuevo, $array_valores_cargados)) {
                            } else {
                                $array_elementos_agregar[] = $elemento_nuevo;
                            }
                        }
                        $this->agregarTiposViolencias($model->idintervencion, $array_elementos_agregar);
                    }

                    //aca aplicamos deleted_at los que no existen en el arrego que viene del form
                    if ($tipos_form) {
                        foreach ($array_valores_cargados as $elemento_viejo) {
                            if (in_array($elemento_viejo, $tipos_form['item'])) {
                            } else {
                                $array_elementos_eliminar[] = $elemento_viejo;
                            }
                        }
                        $this->quitarTiposViolencias($model->idintervencion, $array_elementos_eliminar);
                    } else { //si viene vacio
                        $checkedExistentesActivos = Sds_vio_intervencion_violencias::find()->select('idviolenciatipo')->where(['idintervencion' => $model->idintervencion, 'deleted_at' => null])->asArray()->all();
                        foreach ($checkedExistentesActivos as $elemento => $value) {
                            foreach ($value as $tipoviolencia => $valor) {
                                $array_valores_cargados_activos[] = $valor;
                            }
                            $this->quitarTiposViolencias($model->idintervencion, $array_valores_cargados_activos);
                        }
                    }

                    $arrayViolencias['violencia']['fisica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
                    $arrayViolencias['violencia']['psicologica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
                    $arrayViolencias['violencia']['sexual'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
                    $arrayViolencias['violencia']['economicaPatrimonial'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
                    $arrayViolencias['violencia']['simbolica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
                    $arrayViolencias['violencia']['ambiental'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);
                    $arrayViolencias['violencia']['negligenciaAbandono'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($model->idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);

                    $model->tipo_violencia_fisica = $arrayViolencias['violencia']['fisica'] ? 1 : '';
                    $model->tipo_violencia_psicologica = $arrayViolencias['violencia']['psicologica'] ? 1 : '';
                    $model->tipo_violencia_sexual = $arrayViolencias['violencia']['sexual'] ? 1 : '';
                    $model->tipo_violencia_economica_patrimonial = $arrayViolencias['violencia']['economicaPatrimonial'] ? 1 : '';
                    $model->tipo_violencia_simbolica = $arrayViolencias['violencia']['simbolica'] ? 1 : '';
                    $model->tipo_violencia_ambiental = $arrayViolencias['violencia']['ambiental'] ? 1 : '';
                    $model->tipo_violencia_negligencia_abandono = $arrayViolencias['violencia']['negligenciaAbandono'] ? 1 : '';

                    //frecuencia
                    $arrayVioFrecuencia = array_key_exists('Sds_vio_intervencion_violencias_frecuencia', $request->post()) ? $request->post()['Sds_vio_intervencion_violencias_frecuencia'] : null;
                    if ($arrayVioFrecuencia) {
                        $this->guardarViolenciasFrecuencias($model->idintervencion, $arrayVioFrecuencia);
                    }

                    // Upload archivo adjunto1
                    $tmpFile = UploadedFile::getInstance($model, 'temp_archivo_adjunto1');
                    $date = date('Y-m-d_H_i_s', time());
                    if ($model->borrar_archivo_adjunto1) {
                        $model->archivo_adjunto1 = null;
                    }
                    if ($model->borrar_archivo_adjunto2) {
                        $model->archivo_adjunto2 = null;
                    }

                    if (isset($tmpFile)) {
                        $extension = $tmpFile->extension;
                        $path_info = pathinfo($tmpFile);
                        $extension = $path_info['extension'];
                        $nameFile = "vio_intervencion_{$model->idintervencion}_{$date}.{$extension}";

                        $model->archivo_adjunto1 = $nameFile;
                        if (!file_exists('uploads/violencia/' . $model->idintervencion . '/')) {
                            mkdir('uploads/violencia/' . $model->idintervencion . '/', 0777, true);
                        }
                        $tmpFile->saveAs('uploads/violencia/' . $nameFile);
                    }
                    // Upload archivo adjunto2
                    $tmpFile2 = UploadedFile::getInstance($model, 'temp_archivo_adjunto2');

                    if (isset($tmpFile2)) {
                        $extension = $tmpFile2->extension;
                        $path_info = pathinfo($tmpFile2);
                        $extension = $path_info['extension'];
                        $nameFile = "vio_intervencion_2_{$model->idintervencion}_{$date}.{$extension}";

                        $model->archivo_adjunto2 = $nameFile;
                        if (!file_exists('uploads/violencia/' . $model->idintervencion . '/')) {
                            mkdir('uploads/violencia/' . $model->idintervencion . '/', 0777, true);
                        }
                        $tmpFile2->saveAs('uploads/violencia/' . $nameFile);
                    }

                    $model_com_persona->documento =  $model->dni;
                    $model_com_persona->genero = $model->sexo;
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    // $model_com_persona->nacionalidad = $model->nacionalidad;

                    $model_vio_persona->telefono = $model->telefono;
                    $model_vio_persona->domicilio = $model->domicilio;
                    $model_vio_persona->idlocalidad = $model->localidad;
                    $model_vio_persona->localidad_oriunda = $model->localidad_oriunda;
                    $model_vio_persona->nacionalidad = $model->nacionalidad_origen;
                    $model_vio_persona->genero_autopercibido = $model->genero_autopercibido;

                    $model->idpersona = $model_com_persona->idpersona;

                    if (!$model_vio_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    }
                    $fecha_vio = ArmarDateParaMySql($model->fecha);
                    $fecha_vio = date_create($fecha_vio);
                    $fecha_vio = date_format($fecha_vio, 'Y-m-d');
                    $model->fecha = $fecha_vio;
                    $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                    $model->iddispositivo = $contacto->iddispositivo; // Este es el id dispositivo de la persona logeada

                    if ($guardado && $model->save(false)) {

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion', $model->idintervencion, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se actualizó correctamente la intervención.");

                        if ($origen == null) {
                            return $this->redirect(['index']);
                        } else {
                            return  $this->redirect(['sds_800_llamada/index', 'area' => $origen]);
                        }
                    } else {
                        Yii::$app->session->setFlash('error', " Error al actualizar la intervención.");
                    }
                }
                $action = 'update';
                return $this->render('update', [
                    'action' => $action,
                    'model' => $model,
                    'vioChecked' => $vioChecked,
                    'vioFisicaSelectOptions' => $vioFisicaSelectOptions,
                    'vioPsicologicaSelectOptions' => $vioPsicologicaSelectOptions,
                    'vioSexualSelectOptions' => $vioSexualSelectOptions,
                    'vioEconomicapatrimonialSelectOptions' => $vioEconomicapatrimonialSelectOptions,
                    'vioSimbolicaSelectOptions' => $vioSimbolicaSelectOptions,
                    'vioAmbientalSelectOptions' => $vioAmbientalSelectOptions,
                    'vioNegligenciaAbandonoSelectOptions' => $vioNegligenciaAbandonoSelectOptions,
                    'listProvincias' => $listProvincias,
                    'listLocalidades' => $listLocalidades,
                    'listLocalidadesOriunda' => $listLocalidadesOriunda,
                    'listLocalidadesHecho' => $listLocalidadesHecho,
                    'modelFrecuencia' => $modelFrecuencia,
                    'vioFrecuenciaSelect' => $vioFrecuenciaSelect,
                    'vioOcurrenciasSelect' => $vioOcurrenciasSelect,
                    'sexoOptions' => $sexoOptions,
                    'generoOptions' => $generoOptions,
                    'nacionalidadOptions' => $nacionalidadOptions,
                    'tipoIntervOptions' => $tipoIntervOptions,
                    'derivacionOptions' => $derivacionOptions,
                    'modalidadOptions' => $modalidadOptions
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_vio_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionDelete($id, $idllamada = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($model) {
            $model->deleted_at = date('Y-m-d H:i:s');
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

            if ($model->update(false)) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion', $id, $model->getAttributes());
                Yii::$app->session->setFlash('success', " Se eliminó correctamente la intervención.");
                if ($idllamada) {
                    $model_0800 = Sds_800_llamada::findOne($idllamada);
                    if ($model_0800) {
                        $model_0800->estado = Sds_800_llamada::ESTADO_PENDIENTE; //se vuelve a pendiente el ingreso por llamada
                        if ($model_0800->save(false)) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_llamada', $idllamada, $model_0800->getAttributes());
                        }
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', " Error al eliminar la intervención.");
            }
        } else {
            Yii::$app->session->setFlash('error', " La intervención no existe.");
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionReactivate($id,  $idllamada = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model) {
            if (!is_null($model->deleted_at)) {
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

                if ($model->update(false)) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion', $id, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente la intervención.");
                    if ($idllamada) {
                        $model_0800 = Sds_800_llamada::findOne($idllamada);
                        if ($model_0800) {
                            $model_0800->estado = Sds_800_llamada::ESTADO_ATENDIDA; //se vuelve a pendiente el ingreso por llamada
                            if ($model_0800->save(false)) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_llamada', $idllamada, $model_0800->getAttributes());
                            }
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', " Error al reactivar la intervención.");
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            Yii::$app->session->setFlash('error', " La intervención no existe.");
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionReporte_intervencion($idintervencion)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $array_intervencion = Sds_vio_intervencion::find()->where(['idintervencion' => $idintervencion])->asArray()->one();
        if ($array_intervencion && (is_null($array_intervencion['deleted_at']) || $hasRolAdminGeneral)) {
            //Genera un PDF con el detalle de la asistencia para imprimirla
            $movimientos = Sds_vio_intervencion_movimiento::getMovimientosByIntervencion($idintervencion);
            $agresores = Sds_vio_intervencion_agresor::getAgresoresByIntervencion($idintervencion);

            $arrayAgresores = [];
            if ($agresores) {
                foreach ($agresores as $agresor => $value) {
                    $arrayConsumos = Sds_vio_agresor_consumo::getConsumoByAgresor($value['idagresor']);
                    $value['consumoDetalle'] = $arrayConsumos;
                    $arrayAgresores[$agresor] = $value;
                }
            }
            $usuarioAuth = Yii::$app->user->identity;

            $arrayViolencias['violencia']['fisica'] = Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA);
            $datosFrecuenciaFisica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA])->asArray()->one();
            $arrayViolencias['violencia']['fisicaFrecuencia'] = $datosFrecuenciaFisica ? Sds_com_configuracion::getDescripcion($datosFrecuenciaFisica['idfrecuencia']) : null;
            $arrayViolencias['violencia']['fisicaOcurrencia'] = $datosFrecuenciaFisica ? Sds_com_configuracion::getDescripcion($datosFrecuenciaFisica['idocurrencia']) : null;
            $arrayViolencias['violencia']['fisicaVigencia'] = $datosFrecuenciaFisica ? $datosFrecuenciaFisica['vigencia_actualidad'] : null;

            $arrayViolencias['violencia']['psicologica'] =   Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA);
            $datosFrecuenciaPsicologica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA])->asArray()->one();
            $arrayViolencias['violencia']['psicologicaFrecuencia'] = $datosFrecuenciaPsicologica ? Sds_com_configuracion::getDescripcion($datosFrecuenciaPsicologica['idfrecuencia']) : null;
            $arrayViolencias['violencia']['psicologicaOcurrencia'] = $datosFrecuenciaPsicologica ? Sds_com_configuracion::getDescripcion($datosFrecuenciaPsicologica['idocurrencia']) : null;
            $arrayViolencias['violencia']['psicologicaVigencia'] = $datosFrecuenciaPsicologica ? $datosFrecuenciaPsicologica['vigencia_actualidad'] : null;

            $arrayViolencias['violencia']['sexual'] =   Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL);
            $datosFrecuenciaSexual = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL])->asArray()->one();
            $arrayViolencias['violencia']['sexualFrecuencia'] = $datosFrecuenciaSexual ? Sds_com_configuracion::getDescripcion($datosFrecuenciaSexual['idfrecuencia']) : null;
            $arrayViolencias['violencia']['sexualOcurrencia'] = $datosFrecuenciaSexual ? Sds_com_configuracion::getDescripcion($datosFrecuenciaSexual['idocurrencia']) : null;
            $arrayViolencias['violencia']['sexualVigencia'] = $datosFrecuenciaSexual ? $datosFrecuenciaSexual['vigencia_actualidad'] : null;

            $arrayViolencias['violencia']['economicaPatrimonial'] =   Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA);
            $datosFrecuenciaEconomicaPatrimonial = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA])->asArray()->one();
            $arrayViolencias['violencia']['economicaPatrimonialFrecuencia'] = $datosFrecuenciaEconomicaPatrimonial ? Sds_com_configuracion::getDescripcion($datosFrecuenciaEconomicaPatrimonial['idfrecuencia']) : null;
            $arrayViolencias['violencia']['economicaPatrimonialOcurrencia'] = $datosFrecuenciaEconomicaPatrimonial ? Sds_com_configuracion::getDescripcion($datosFrecuenciaEconomicaPatrimonial['idocurrencia']) : null;
            $arrayViolencias['violencia']['economicaPatrimonialVigencia'] = $datosFrecuenciaEconomicaPatrimonial ? $datosFrecuenciaEconomicaPatrimonial['vigencia_actualidad'] : null;

            $arrayViolencias['violencia']['simbolica'] =   Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA);
            $datosFrecuenciaSimbolica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA])->asArray()->one();
            $arrayViolencias['violencia']['simbolicaFrecuencia'] = $datosFrecuenciaSimbolica ? Sds_com_configuracion::getDescripcion($datosFrecuenciaSimbolica['idfrecuencia']) : null;
            $arrayViolencias['violencia']['simbolicaOcurrencia'] = $datosFrecuenciaSimbolica ? Sds_com_configuracion::getDescripcion($datosFrecuenciaSimbolica['idocurrencia']) : null;
            $arrayViolencias['violencia']['simbolicaVigencia'] = $datosFrecuenciaSimbolica ? $datosFrecuenciaSimbolica['vigencia_actualidad'] : null;

            $arrayViolencias['violencia']['negligenciaAbandono'] =  Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO);
            $datosFrecuenciaNegligenciaAbandono = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO])->asArray()->one();
            $arrayViolencias['violencia']['negligenciaAbandonoFrecuencia'] = $datosFrecuenciaNegligenciaAbandono ? Sds_com_configuracion::getDescripcion($datosFrecuenciaNegligenciaAbandono['idfrecuencia']) : null;
            $arrayViolencias['violencia']['negligenciaAbandonoOcurrencia'] = $datosFrecuenciaNegligenciaAbandono ? Sds_com_configuracion::getDescripcion($datosFrecuenciaNegligenciaAbandono['idocurrencia']) : null;
            $arrayViolencias['violencia']['negligenciaAbandonoVigencia'] = $datosFrecuenciaNegligenciaAbandono ? $datosFrecuenciaNegligenciaAbandono['vigencia_actualidad'] : null;

            $arrayViolencias['violencia']['ambiental'] =   Sds_vio_intervencion_violencias::getViolenciaByTipoIntervencion($idintervencion, Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL);
            $datosFrecuenciaAmbiental = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'deleted_at' => null, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL])->asArray()->one();
            $arrayViolencias['violencia']['ambientalFrecuencia'] = $datosFrecuenciaAmbiental ? Sds_com_configuracion::getDescripcion($datosFrecuenciaAmbiental['idfrecuencia']) : null;
            $arrayViolencias['violencia']['ambientalOcurrencia'] = $datosFrecuenciaAmbiental ? Sds_com_configuracion::getDescripcion($datosFrecuenciaAmbiental['idocurrencia']) : null;
            $arrayViolencias['violencia']['ambientalVigencia'] = $datosFrecuenciaAmbiental ? $datosFrecuenciaAmbiental['vigencia_actualidad'] : null;

            $arrayPerAfectada = Sds_vio_persona::find()->where(['idpersona' => $array_intervencion['idpersona']])->asArray()->one();
            $localidadPerAfectada = Sds_com_localidad::find()->where(['idlocalidad' => $arrayPerAfectada['idlocalidad']])->asArray()->one();
            $provinciaPerAfectada = Sds_com_provincia::find()->where(['idprovincia' => $localidadPerAfectada['idprovincia']])->asArray()->one();
            $arrayPerAfectada['provincia'] = $provinciaPerAfectada ? $provinciaPerAfectada['descripcion'] : '';

            $localidadOriundaPerAfectada = $arrayPerAfectada['localidad_oriunda'] ? Sds_com_localidad::find()->where(['idlocalidad' => $arrayPerAfectada['localidad_oriunda']])->asArray()->one() : null;
            $provinciaOriundaPerAfectada = $arrayPerAfectada['localidad_oriunda'] ? Sds_com_provincia::find()->where(['idprovincia' => $localidadOriundaPerAfectada['idprovincia']])->asArray()->one() : null;
            $arrayPerAfectada['provincia_oriunda'] = $provinciaOriundaPerAfectada ? $provinciaOriundaPerAfectada['descripcion'] : '';

            $localidadHecho = $array_intervencion['localidad_hecho'] ? Sds_com_localidad::find()->where(['idlocalidad' => $array_intervencion['localidad_hecho']])->asArray()->one() : null;
            $provinciaHecho = $array_intervencion['localidad_hecho'] ? Sds_com_provincia::find()->where(['idprovincia' => $localidadHecho['idprovincia']])->asArray()->one() : null;
            $arrayAbordaje['provincia_hecho'] = $provinciaHecho ? $provinciaHecho['descripcion'] : '';

            $content = $this->renderPartial('reporte_violencia', [
                'arrayIntervencion' => $array_intervencion,
                'arrayTipoViolencia' => $arrayViolencias,
                'arrayPerAfectada' => $arrayPerAfectada,
                'arrayAbordaje' => $arrayAbordaje,
                'movimientos' => $movimientos,
                'agresores' => $arrayAgresores,
            ]);
            $dateToday = date('d/m/Y H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $content,
                'filename' => 'Intervencion_' . $idintervencion,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
                'methods' => [
                    'SetTitle' => 'INTERVENCION #' . $idintervencion,
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_violencia', $idintervencion, array());

            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionValidar_dni($dni, $idllamada = null)
    {
        //Busco la persona, si existe traigo los datos para editar
        if ($dni != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Sds_ris_persona::findBySql("SELECT risper.*
                                                FROM sds_ris_persona risper
                                                JOIN sds_com_persona persona ON persona.idpersona=risper.idpersona
                                                JOIN sds_ris_risneu risneu ON risneu.idrisneu=risper.idrisneu
                                                WHERE documento=$dni and risper.activo = 1
                                                ORDER BY risneu.updated_at DESC, risneu.idrisneu DESC")->one();
            $model_persona = null;
            //aca queria verificar si era create o update. Pero vi que siempre es create porque en el editar el botón de buscar dni esta deshabilitado.
            //$createUpdate = $editar ? "update&id=".$idintervencion : "create&id=".$idintervencion;
            if ($model == null) {
                $model = Sds_ris_risneu::findBySql("SELECT risneu.idrisneu
                FROM sds_ris_risneu risneu
                WHERE dni=$dni and activo = 1")->one();
                if ($model) {
                    return $this->redirect([
                        'sds_ris_risneu/update',
                        'finalizar' => false,
                        'id' => $model->idrisneu,
                        'dni' => $dni,
                        'origen' => 'index.php?r=sds_vio_intervencion/create',
                        'idllamada' => $idllamada
                    ]);
                } else {
                    return $this->redirect([
                        'sds_ris_risneu/create',
                        'finalizar' => false,
                        'dni' => $dni,
                        'origen' => 'index.php?r=sds_vio_intervencion/create',
                        'idllamada' => $idllamada
                    ]);
                }
            } else {
                $model_persona = Sds_com_persona::findOne($model->idpersona);
                $model_vio_persona = Sds_vio_persona::findOne($model->idpersona);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion/validar_dni', $model->idpersona, array());
            }
            $result = array();
            if ($model) {
                array_push($result, $model->getAttributes());
            }
            if ($model_persona) {
                array_push($result, $model_persona->getAttributes());
                $idlocalidad = $model_persona->idlocalidad ? $model_persona->idlocalidad : null;
            }
            if ($model_vio_persona) {
                array_push($result, $model_vio_persona->getAttributes());
                $idlocalidad = $idlocalidad ? $idlocalidad : ($model_vio_persona->idlocalidad ? $model_vio_persona->idlocalidad : null);
            }

            if ($idlocalidad) {
                $provincia = Sds_com_localidad::find()
                    ->innerJoin('sds_com_provincia', 'sds_com_localidad.idprovincia = sds_com_provincia.idprovincia')
                    ->where(["idlocalidad" => $idlocalidad])
                    ->one();
                $result += ["idlocalidad" => $idlocalidad];
                $result += ["idprovincia" => $provincia->idprovincia];
            }

            return json_encode($result);
        }
        return null;
    }

    public function actionGet_id_risneu($dni)
    {
        $risneu = Sds_ris_persona::findBySql("
        select r.*, p.* 
        from sds_ris_persona r 
        inner join sds_com_persona p on r.idpersona  = p.idpersona 
        inner join sds_ris_risneu risneu on risneu.idrisneu = r.idrisneu
        where p.documento = $dni and r.activo = 1
        order by risneu.updated_at DESC, risneu.idrisneu DESC;")
            ->all();
        return $risneu[0]->idrisneu;
    }

    /**
     * Finds the Sds_vio_intervencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_vio_intervencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_vio_intervencion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getTipoViolenciaByIdintertencion($idintervencion)
    {
        $modelFrecuencia = new Sds_vio_intervencion_violencias_frecuencia();
        $modelFrecuenciaFisica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoFisica['frecuencia'] = $modelFrecuenciaFisica ? $modelFrecuenciaFisica->idfrecuencia : null;
        $modelFrecuencia->tipoFisica['frecuenciaDetalle'] = $modelFrecuenciaFisica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaFisica->idfrecuencia) : null;
        $modelFrecuencia->tipoFisica['ocurrencia'] = $modelFrecuenciaFisica ? $modelFrecuenciaFisica->idocurrencia : null;
        $modelFrecuencia->tipoFisica['ocurrenciaDetalle'] = $modelFrecuenciaFisica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaFisica->idocurrencia) : null;
        $modelFrecuencia->tipoFisica['vigencia'] = $modelFrecuenciaFisica ? $modelFrecuenciaFisica->vigencia_actualidad : null;

        $modelFrecuenciaPsicologica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoPsicologica['frecuencia'] = $modelFrecuenciaPsicologica ? $modelFrecuenciaPsicologica->idfrecuencia : null;
        $modelFrecuencia->tipoPsicologica['frecuenciaDetalle'] = $modelFrecuenciaPsicologica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaPsicologica->idfrecuencia) : null;
        $modelFrecuencia->tipoPsicologica['ocurrencia'] = $modelFrecuenciaPsicologica ? $modelFrecuenciaPsicologica->idocurrencia : null;
        $modelFrecuencia->tipoPsicologica['ocurrenciaDetalle'] = $modelFrecuenciaPsicologica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaPsicologica->idocurrencia) : null;
        $modelFrecuencia->tipoPsicologica['vigencia'] = $modelFrecuenciaPsicologica ? $modelFrecuenciaPsicologica->vigencia_actualidad : null;

        $modelFrecuenciaSexual = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoSexual['frecuencia'] = $modelFrecuenciaSexual ? $modelFrecuenciaSexual->idfrecuencia : null;
        $modelFrecuencia->tipoSexual['frecuenciaDetalle'] = $modelFrecuenciaSexual ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSexual->idfrecuencia) : null;
        $modelFrecuencia->tipoSexual['ocurrencia'] = $modelFrecuenciaSexual ? $modelFrecuenciaSexual->idocurrencia : null;
        $modelFrecuencia->tipoSexual['ocurrenciaDetalle'] = $modelFrecuenciaSexual ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSexual->idocurrencia) : null;
        $modelFrecuencia->tipoSexual['vigencia'] = $modelFrecuenciaSexual ? $modelFrecuenciaSexual->vigencia_actualidad : null;

        $modelFrecuenciaEconomicaPatrimonial = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoEconomicaPatrimonial['frecuencia'] = $modelFrecuenciaEconomicaPatrimonial ? $modelFrecuenciaEconomicaPatrimonial->idfrecuencia : null;
        $modelFrecuencia->tipoEconomicaPatrimonial['frecuenciaDetalle'] = $modelFrecuenciaEconomicaPatrimonial ? Sds_com_configuracion::getDescripcion($modelFrecuenciaEconomicaPatrimonial->idfrecuencia) : null;
        $modelFrecuencia->tipoEconomicaPatrimonial['ocurrencia'] = $modelFrecuenciaEconomicaPatrimonial ? $modelFrecuenciaEconomicaPatrimonial->idocurrencia : null;
        $modelFrecuencia->tipoEconomicaPatrimonial['ocurrenciaDetalle'] = $modelFrecuenciaEconomicaPatrimonial ? Sds_com_configuracion::getDescripcion($modelFrecuenciaEconomicaPatrimonial->idocurrencia) : null;
        $modelFrecuencia->tipoEconomicaPatrimonial['vigencia'] = $modelFrecuenciaEconomicaPatrimonial ? $modelFrecuenciaEconomicaPatrimonial->vigencia_actualidad : null;

        $modelFrecuenciaSimbolica = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoSimbolica['frecuencia'] = $modelFrecuenciaSimbolica ? $modelFrecuenciaSimbolica->idfrecuencia : null;
        $modelFrecuencia->tipoSimbolica['frecuenciaDetalle'] = $modelFrecuenciaSimbolica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSimbolica->idfrecuencia) : null;
        $modelFrecuencia->tipoSimbolica['ocurrencia'] = $modelFrecuenciaSimbolica ? $modelFrecuenciaSimbolica->idocurrencia : null;
        $modelFrecuencia->tipoSimbolica['ocurrenciaDetalle'] = $modelFrecuenciaSimbolica ? Sds_com_configuracion::getDescripcion($modelFrecuenciaSimbolica->idocurrencia) : null;
        $modelFrecuencia->tipoSimbolica['vigencia'] = $modelFrecuenciaSimbolica ? $modelFrecuenciaSimbolica->vigencia_actualidad : null;

        $modelFrecuenciaAmbiental = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoAmbiental['frecuencia'] = $modelFrecuenciaAmbiental ? $modelFrecuenciaAmbiental->idfrecuencia : null;
        $modelFrecuencia->tipoAmbiental['frecuenciaDetalle'] = $modelFrecuenciaAmbiental ? Sds_com_configuracion::getDescripcion($modelFrecuenciaAmbiental->idfrecuencia) : null;
        $modelFrecuencia->tipoAmbiental['ocurrencia'] = $modelFrecuenciaAmbiental ? $modelFrecuenciaAmbiental->idocurrencia : null;
        $modelFrecuencia->tipoAmbiental['ocurrenciaDetalle'] = $modelFrecuenciaAmbiental ? Sds_com_configuracion::getDescripcion($modelFrecuenciaAmbiental->idocurrencia) : null;
        $modelFrecuencia->tipoAmbiental['vigencia'] = $modelFrecuenciaAmbiental ? $modelFrecuenciaAmbiental->vigencia_actualidad : null;

        $modelFrecuenciaNegligenciaAbandono = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO, 'deleted_at' => null])->one();
        $modelFrecuencia->tipoNegligenciaAbandono['frecuencia'] = $modelFrecuenciaNegligenciaAbandono ? $modelFrecuenciaNegligenciaAbandono->idfrecuencia : null;
        $modelFrecuencia->tipoNegligenciaAbandono['frecuenciaDetalle'] = $modelFrecuenciaNegligenciaAbandono ? Sds_com_configuracion::getDescripcion($modelFrecuenciaNegligenciaAbandono->idfrecuencia) : null;
        $modelFrecuencia->tipoNegligenciaAbandono['ocurrencia'] = $modelFrecuenciaNegligenciaAbandono ? $modelFrecuenciaNegligenciaAbandono->idocurrencia : null;
        $modelFrecuencia->tipoNegligenciaAbandono['ocurrenciaDetalle'] = $modelFrecuenciaNegligenciaAbandono ? Sds_com_configuracion::getDescripcion($modelFrecuenciaNegligenciaAbandono->idocurrencia) : null;
        $modelFrecuencia->tipoNegligenciaAbandono['vigencia'] = $modelFrecuenciaNegligenciaAbandono ? $modelFrecuenciaNegligenciaAbandono->vigencia_actualidad : null;

        return $modelFrecuencia;
    }

    protected function getFilterTipoIntervencion()
    {
        //Busqueda de Tipos de Intervenciones en configuracion
        $tipoIntervencionFiltro = Sds_com_configuracion::findBySql(
            "SELECT idintervencion, 
                configuracion.idconfiguracion as conf_idconfiguracion, 
                configuracion.descripcion as conf_descripcion 
                FROM sds_vio_intervencion intervencion 
                INNER JOIN sds_com_configuracion configuracion 
                ON intervencion.tipo = configuracion.idconfiguracion
                WHERE intervencion.tipo 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY conf_descripcion ASC
                "
        )->asArray()->all();

        $tipoIntervencionFiltro = ArrayHelper::map($tipoIntervencionFiltro, 'conf_idconfiguracion', 'conf_descripcion');
        return $tipoIntervencionFiltro;
    }

    protected function getFilterDerivacion()
    {
        //Busqueda de derivacion en configuracion
        $derivacionFiltro = Sds_com_configuracion::findBySql(
            "SELECT idintervencion, 
                configuracion.idconfiguracion as conf_idconfiguracion, 
                configuracion.descripcion as conf_descripcion 
                FROM sds_vio_intervencion intervencion 
                INNER JOIN sds_com_configuracion configuracion 
                ON intervencion.derivacion = configuracion.idconfiguracion
                WHERE intervencion.derivacion 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY conf_descripcion ASC
                "
        )->asArray()->all();

        $derivacionFiltro = ArrayHelper::map($derivacionFiltro, 'conf_idconfiguracion', 'conf_descripcion');
        return $derivacionFiltro;
    }

    protected function getFilterUsuarioCarga()
    {
        //Busqueda de usuarios que cargaron intervenciones
        $usuarioFiltro = Sds_com_configuracion::findBySql(
            "SELECT idintervencion, 
                usuario.idusuario as idusuario,
                CONCAT(UPPER(usuario.apellido),', ', UPPER(usuario.nombre)) as usuarioNombre
                FROM sds_vio_intervencion intervencion 
                INNER JOIN mds_seg_usuario usuario 
                ON intervencion.idusuario = usuario.idusuario
                WHERE intervencion.idusuario 
                ORDER BY usuarioNombre ASC
                "
        )->asArray()->all();
        $usuarioFiltro = ArrayHelper::map($usuarioFiltro, 'idusuario', 'usuarioNombre');
        return $usuarioFiltro;
    }

    protected function agregarTiposViolencias($idintervencion, $array_elementos_agregar)
    {
        foreach ($array_elementos_agregar as $elemento) {
            $vioChecked = new Sds_vio_intervencion_violencias();
            $vioChecked->idintervencion = $idintervencion;
            $vioChecked->idviolenciatipo = $elemento;
            $vioChecked->created_at = date('Y-m-d_H_i_s', time());
            $vioChecked->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_intervencion_violencias', $vioChecked->idintervencion, $vioChecked);
        }
    }
    protected function quitarTiposViolencias($idintervencion, $array_elementos_eliminar)
    {

        if ($array_elementos_eliminar) {
            foreach ($array_elementos_eliminar as $elemento) {
                $checked = Sds_vio_intervencion_violencias::find()->where(['idintervencion' => $idintervencion, 'idviolenciatipo' => $elemento])->one();
                $checked->deleted_at = date('Y-m-d_H_i_s', time());
                $checked->update();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion_violencias', $checked->idviolenciatipo, $checked);
            }
        }
    }

    protected function guardarViolenciasFrecuencias($idintervencion, $arrayVioFrecuencia)
    {
        foreach ($arrayVioFrecuencia as $elemento =>  $valor) {
            switch ($elemento) {
                case 'tipoFisica':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_FISICA;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'tipoPsicologica':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_PSICOLOGICA;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'tipoSexual':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SEXUAL;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'tipoEconomicaPatrimonial':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_ECONOMICA;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'tipoSimbolica':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_SIMBOLICA;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'tipoAmbiental':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_AMBIENTAL;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'tipoNegligenciaAbandono':
                    $tipoviolencia = Sds_com_configuracion_tipo::VIO_VIOLENCIA_TIPOS_NEGLIGENCIA_ABANDONO;
                    $idfrecuencia = array_key_exists('frecuencia', $valor) ? $valor['frecuencia'] : null;
                    $idocurrencia = array_key_exists('ocurrencia', $valor) ? $valor['ocurrencia'] : null;
                    $vigencia_actualidad = array_key_exists('vigencia', $valor) ? $valor['vigencia'] : null;
                    break;
                case 'default':
                    $tipoviolencia = null;
                    $idfrecuencia = null;
                    $vigencia_actualidad = null;
                    break;
            }
            $registroFrecuencia = Sds_vio_intervencion_violencias_frecuencia::find()->where(['idintervencion' => $idintervencion, 'idtipoviolencia' => $tipoviolencia, 'deleted_at' => null])->one();

            if ($registroFrecuencia) {
                if (($registroFrecuencia->idfrecuencia != $idfrecuencia) || ($registroFrecuencia->idocurrencia != $idocurrencia) || ($registroFrecuencia->vigencia_actualidad != $vigencia_actualidad)) {
                    $registroFrecuencia->idfrecuencia = $idfrecuencia;
                    $registroFrecuencia->idocurrencia = $idocurrencia;
                    $registroFrecuencia->vigencia_actualidad = $vigencia_actualidad;
                    $registroFrecuencia->updated_at = date('Y-m-d_H_i_s', time());
                    $registroFrecuencia->update();
                }
            } else {
                $modelVioFrecuencia = new Sds_vio_intervencion_violencias_frecuencia();
                $modelVioFrecuencia->idintervencion = $idintervencion;
                $modelVioFrecuencia->idtipoviolencia =  $tipoviolencia;
                $modelVioFrecuencia->idfrecuencia = $idfrecuencia;
                $modelVioFrecuencia->idocurrencia = $idocurrencia;
                $modelVioFrecuencia->vigencia_actualidad = $vigencia_actualidad;
                $modelVioFrecuencia->created_at = date('Y-m-d_H_i_s', time());
                $modelVioFrecuencia->idusuario_carga = Yii::$app->user->id;
                $modelVioFrecuencia->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_violencias_frecuencia', $modelVioFrecuencia->idviolenciafrecuencia, $modelVioFrecuencia);
            }
        }
    }

    /**
     * Verificar
     * 
     * Si envia idllamada:
     * La llamada esta en estado Pendiente
     * y
     * No existe una intervencion activa
     * 
     * Si no envia idllamada:
     * no verifico nada
     */
    private function verificarCondicionCreacion($idllamada)
    {
        $canCreate = true;
        if (!is_null($idllamada)) {
            $estaPendiente = Sds_800_llamada::estaPendiente($idllamada);
            $existeIntervencion = Sds_vio_intervencion::findOne(['idllamada' => $idllamada, 'deleted_at' => null]);
            $canCreate = $estaPendiente &&  is_null($existeIntervencion);
        }
        return $canCreate;
    }

    /**
     * Verificar
     * Para intervencion NO activa:
     * 
     *  solo puede modificarla el adminGeneral
     * 
     * Para intervencion Activa
     * 
     *  Si no envia idllamada:
     *      la intervencion tiene null en el campo idllamada
     * 
     *  Si envia idllamada:
     *      la intervencion guarde el idllamada en el campo idllamada
     *      y
     *      (la llamada este en estado atendida
     *      o
     *      soy el usuario AdminGeneral)
     */
    private function verificarCondicionActualizacion($idintervencion, $idllamada)
    {
        $model = Sds_vio_intervencion::findOne(['idintervencion' => $idintervencion, 'deleted_at' => null]); //Solo verifica intervenciones activas
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if (is_null($model)) {
            $model_inactivo = Sds_vio_intervencion::findOne($idintervencion);
            $canUpdate = $model_inactivo ?  $hasRolAdminGeneral : false;
        } else {
            if (is_null($idllamada)) {
                $canUpdate = is_null($model->idllamada);
            } else {
                $relacion = $model->idllamada == $idllamada;
                $estaAtendida = Sds_800_llamada::estaAtendida($idllamada);
                $canUpdate =  $relacion &&  ($estaAtendida || $hasRolAdminGeneral);
            }
        }
        return $canUpdate;
    }
}

function ArmarDateParaMySql($Fecha)
{
    if ($Fecha == null) {
        return null;
    }
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
