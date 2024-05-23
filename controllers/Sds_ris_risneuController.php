<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Sds_com_barrio;
use app\models\Sds_com_calle;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Mds_seg_permiso;
use app\models\Sds_ris_risneu;
use app\models\Sds_ris_risneu_alimentacion;
use app\models\Sds_ris_risneuSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_com_persona;
use app\models\Sds_com_provincia;
use app\models\Sds_ris_persona;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_rol;

/**
 * Sds_ris_risneuController implements the CRUD actions for Sds_ris_risneu model.
 */
class Sds_ris_risneuController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'view', 'logout', 'imprimir',
                    'create_barrio', 'create_calle', 'validar_dni', 'reactivate',
                    'guardarlogmanualusuario', 'dashboard'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete', 'update', 'view', 'logout', 'create_barrio',
                            'create_calle', 'validar_dni', 'reactivate', 'guardarlogmanualusuario', 'dashboard'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RIS_RISNEU,
                        ],
                    ],
                    [
                        'actions' => ['imprimir'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_RIS_RISNEU_IMPRIMIR
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_ris_risneu models.
     * @return mixed
     */
    public function actionIndex($oficial = null, $fechaInicio = null, $fechaFin = null, $idlocalidad = null, $idencuestador = null, $estado = null, $idrealizadopor = null, $idarea = null)
    {
        $searchModel = new Sds_ris_risneuSearch();
        $searchModel->oficial = ($oficial != null) ? $oficial : null;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $fechaInicio, $fechaFin, $idlocalidad, $idencuestador, $estado, $idrealizadopor, $idarea);
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RIS_RISNEU, $usuarioAuth->idusuario);
        $permissionsImprimirRisneu = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RIS_RISNEU_IMPRIMIR, $usuarioAuth->idusuario);

        $permissionRisneuCreate = false;
        $permissionRisneuRead = false;
        $permissionRisneuUpdate = false;
        $permissionRisneuDelete = false;
        $permissionRisneuPrint = false;
        $stringButtonsIndex = "";
        foreach ($permissions as $permission) {
            if ($permission->alta && !$permissionRisneuCreate) {
                $permissionRisneuCreate = true;
                $stringButtonsIndex .= "{create}";
            }
            if ($permission->ver && !$permissionRisneuRead) {
                $permissionRisneuRead = true;
                $stringButtonsIndex .= "{view}";
            }
            if (!empty($permissionsImprimirRisneu) && !$permissionRisneuPrint) {
                $permissionRisneuPrint = true;
                $stringButtonsIndex .= "{imprimirRisneu}";
            }
            if ($permission->modifica && !$permissionRisneuUpdate) {
                $permissionRisneuUpdate = true;
                $stringButtonsIndex .= "{update}";
            }
            if ($permission->baja && !$permissionRisneuDelete) {
                $permissionRisneuDelete = true;
                $stringButtonsIndex .= "{delete}{reactivate}";
            }
        }


        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_ris_risneu', null, array());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'localidades' => $this->getFilterLocalidades($oficial),
            'encuestadoresFiltro' => $this->getFilterEncuestadores($oficial),
            'stringButtonsIndex' => $stringButtonsIndex,
            'permissionRisneuCreate' => $permissionRisneuCreate,
        ]);
    }


    /**
     * Displays a single Sds_ris_risneu model.
     * @param integer $id
     * @return mixed
     */

    public function actionView($id, $dni = null, $origen = null, $idllamada = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $modelRisPersona = new Sds_ris_persona();
        $personaJefe = $modelRisPersona->getJefeByIdRisneu($id);
        if (!$dni) {
            if (!$personaJefe && $model->dni) {
                $dni = $model->dni;
            } else if ($personaJefe) {
                $dni = $personaJefe->persona->documento;
            }
        }
        $existeJefe =  $personaJefe ? true : false;
        $jefeNombreCompleto = '';
        if ($existeJefe) {
            $jefe = Sds_com_persona::findOne($personaJefe->idpersona);
            if ($jefe) {
                $jefeApellido = mb_strtoupper($jefe->apellido);
                $jefeNombre = mb_strtoupper($jefe->nombre);
                $jefeNombreCompleto = "$jefeApellido, $jefeNombre";
            }
        }
        $model->dni_beneficiario = $dni;
        $origen = urldecode($origen);

        $model->idlocalidad = $model->idbarrio0->idlocalidad;
        $model->cod_postal = $model->idbarrio0->localidad->codigo_postal;
        $model->idprovincia = $model->idbarrio0->localidad->idprovincia;

        $encuestadores = ArrayHelper::map(Sds_ris_risneu::getEncuestadores(), 'idconfiguracion', 'descripcion');
        $realizadoPor = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_REALIZADO_POR), 'idconfiguracion', 'descripcion');
        $localidades = ArrayHelper::map(Sds_com_localidad::getLocalidadesByIdProvincia($model->idprovincia), 'idlocalidad', 'descripcion');
        $barrios = ArrayHelper::map(Sds_com_barrio::getBarriosByIdLocalidad($model->idlocalidad), 'idbarrio', 'nombre');
        $areas = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_AREA), 'idconfiguracion', 'descripcion');
        $calles = ArrayHelper::map(Sds_com_calle::find()->where(["activo" => 1])->orderBy(['idcalle' => SORT_ASC])->all(), 'idcalle', 'descripcion');
        $callesInterseccion = ArrayHelper::map(Sds_com_calle::find()->where(["activo" => 1])->orderBy(['idcalle' => SORT_ASC])->all(), 'idcalle', 'descripcion');
        $provincias = ArrayHelper::map(Sds_com_provincia::getProvinciasMostrar(), 'idprovincia', 'descripcion');

        //Alimentacion-vivienda
        $tipos_alimentacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ALIMENTACION);
        $risneu_alims = Sds_ris_risneu_alimentacion::find()->where("idrisneu = $model->idrisneu AND deleted_at IS NULL")->all();
        $selectViviendaUso = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_USO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaUbicacion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_UBIC, false), 'idconfiguracion', 'descripcion');
        $selectViviendaPropiedad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PROP, false), 'idconfiguracion', 'descripcion');
        $selectViviendaTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_TIPO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaPiso = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PISO, false), 'idconfiguracion', 'descripcion');

        $selectViviendaObtieneAgua = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_AGUA_OBT, false), 'idconfiguracion', 'descripcion');
        $selectViviendaAgua = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_AGUA, false), 'idconfiguracion', 'descripcion');
        $selectViviendaBano = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_BANO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaDesague = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_BANO_DES, false), 'idconfiguracion', 'descripcion');
        $selectViviendaIluminacion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_ILUMINACION, false), 'idconfiguracion', 'descripcion');
        $selectViviendaMedidor = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_MEDIDOR, false), 'idconfiguracion', 'descripcion');
        $selectViviendaCalefaccion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_COMB_CALEF, false), 'idconfiguracion', 'descripcion');
        $selectViviendaCocina = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_COMB_COCINA, false), 'idconfiguracion', 'descripcion');
        $selectViviendaTecho = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_TECHO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaParedes = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PAREDES, false), 'idconfiguracion', 'descripcion');

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_ris_risneu', $id, array());

        return $this->render('view', [
            'model' => $model,
            'origen' => $origen,
            'idllamada' => $idllamada,
            'encuestadores' => $encuestadores,
            'realizadoPor' => $realizadoPor,
            'localidades' => $localidades,
            'barrios' => $barrios,
            'areas' => $areas,
            'calles' => $calles,
            'callesInterseccion' => $callesInterseccion,
            'provincias' => $provincias,
            'tipos_alimentacion' => $tipos_alimentacion,
            'risneu_alims' => $risneu_alims,
            'selectViviendaUso' => $selectViviendaUso,
            'selectViviendaUbicacion' => $selectViviendaUbicacion,
            'selectViviendaPropiedad' => $selectViviendaPropiedad,
            'selectViviendaTipo' => $selectViviendaTipo,
            'selectViviendaPiso' => $selectViviendaPiso,
            'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
            'selectViviendaAgua' => $selectViviendaAgua,
            'selectViviendaBano' => $selectViviendaBano,
            'selectViviendaDesague' => $selectViviendaDesague,
            'selectViviendaIluminacion' => $selectViviendaIluminacion,
            'selectViviendaMedidor' => $selectViviendaMedidor,
            'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
            'selectViviendaCocina' => $selectViviendaCocina,
            'selectViviendaTecho' => $selectViviendaTecho,
            'selectViviendaParedes' => $selectViviendaParedes,
            'existeJefe' => $existeJefe,
            'jefeNombreCompleto' => $jefeNombreCompleto
        ]);
    }

    public function actionCreate_barrio($idlocalidad)
    {
        $model_barrio = new Sds_com_barrio();
        $model_barrio->idlocalidad = $idlocalidad;
        $request = Yii::$app->request;
        if ($model_barrio->load($request->post())) {
            if ($model_barrio->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_barrio', $model_barrio->idbarrio, $model_barrio->getAttributes());
                echo 1;
            } else {
                return $this->renderAjax('//sds_com_barrio/create', [
                    'model' => $model_barrio,
                    'botones' => true,
                ]);
            }
        } else {
            return $this->renderAjax('//sds_com_barrio/create', [
                'model' => $model_barrio,
                'botones' => true,
            ]);
        }
    }

    public function actionCreate_calle()
    {
        $model_calle = new Sds_com_calle();
        $request = Yii::$app->request;
        if ($model_calle->load($request->post())) {
            if ($model_calle->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_calle', $model_calle->idcalle, $model_calle->getAttributes());
                return $model_calle->idcalle;
            } else {
                return $this->renderAjax('//sds_com_calle/create', [
                    'model' => $model_calle,
                    'botones' => true,
                ]);
            }
        } else {
            return $this->renderAjax('//sds_com_calle/create', [
                'model' => $model_calle,
                'botones' => true,
            ]);
        }
    }


    /**
     * Creates a new Sds_ris_risneu model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($finalizar = false, $dni = null, $origen = null, $oficial = false, $idllamada = null)
    {
        $request = Yii::$app->request;
        $model = new Sds_ris_risneu();
        $this->inicializarRisneu($model);
        if ($dni != null) {
            $model->dni_beneficiario = $dni;
        }
        $origen = urldecode($origen);

        $model->idlocalidad = Sds_ris_risneu::ID_LOCALIDAD_NEUQUEN_CAPITAL;
        $model->idprovincia = Sds_ris_risneu::ID_PROVINCIA_NEUQUEN;
        $model->cod_postal = Sds_ris_risneu::CODIGO_POSTAL_NEUQUEN_CAPITAL;
        $model->oficial = $oficial;


        $encuestadores = ArrayHelper::map(Sds_ris_risneu::getEncuestadores(), 'idconfiguracion', 'descripcion');
        $realizadoPor = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_REALIZADO_POR), 'idconfiguracion', 'descripcion');
        $localidades = ArrayHelper::map(Sds_com_localidad::getLocalidadesByIdProvincia($model->idprovincia), 'idlocalidad', 'descripcion');
        $barrios = ArrayHelper::map(Sds_com_barrio::getBarriosByIdLocalidad($model->idlocalidad), 'idbarrio', 'nombre');
        $areas = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_AREA), 'idconfiguracion', 'descripcion');
        $calles = ArrayHelper::map(Sds_com_calle::find()->where(["activo" => 1])->orderBy(['idcalle' => SORT_ASC])->all(), 'idcalle', 'descripcion');
        $callesInterseccion = ArrayHelper::map(Sds_com_calle::find()->where(["activo" => 1])->orderBy(['idcalle' => SORT_ASC])->all(), 'idcalle', 'descripcion');
        $provincias = ArrayHelper::map(Sds_com_provincia::getProvinciasMostrar(), 'idprovincia', 'descripcion');

        //alimentacion-vivienda
        $tipos_alimentacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ALIMENTACION);
        $risneu_alims = array();
        $selectViviendaUso = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_USO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaUbicacion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_UBIC, false), 'idconfiguracion', 'descripcion');
        $selectViviendaPropiedad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PROP, false), 'idconfiguracion', 'descripcion');
        $selectViviendaTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_TIPO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaPiso = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PISO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaObtieneAgua = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_AGUA_OBT, false), 'idconfiguracion', 'descripcion');
        $selectViviendaAgua = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_AGUA, false), 'idconfiguracion', 'descripcion');
        $selectViviendaBano = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_BANO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaDesague = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_BANO_DES, false), 'idconfiguracion', 'descripcion');
        $selectViviendaIluminacion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_ILUMINACION, false), 'idconfiguracion', 'descripcion');
        $selectViviendaMedidor = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_MEDIDOR, false), 'idconfiguracion', 'descripcion');
        $selectViviendaCalefaccion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_COMB_CALEF, false), 'idconfiguracion', 'descripcion');
        $selectViviendaCocina = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_COMB_COCINA, false), 'idconfiguracion', 'descripcion');
        $selectViviendaTecho = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_TECHO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaParedes = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PAREDES, false), 'idconfiguracion', 'descripcion');


        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            /* Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Sds_ris_risneu",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ris_risneu', $model->idrisneu, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Sds_ris_risneu",
                    'content' => '<span class="text-success">Create Sds_ris_risneu success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Sds_ris_risneu",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } */
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $guardar = true;
                if ($model->fecha != null) {
                    $model->fecha = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha)));
                } else {
                    $model->addError('fecha', "Debe ingresar la fecha");
                    $guardar = false;
                }

                $model->dni = $request->post("Sds_ris_risneu")["dni_beneficiario"];

                if ($guardar) {
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ris_risneu', $model->idrisneu, $model->getAttributes());
                        $tipos_alimentacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ALIMENTACION);
                        $tipo_alim_form = isset($request->post("Sds_ris_risneu")["tipo_alim"]) ? $request->post("Sds_ris_risneu")["tipo_alim"] : array();
                        if (!empty($tipo_alim_form)) {
                            foreach ($tipos_alimentacion as $tipo_alim) {
                                foreach ($tipo_alim_form as $idalim) {
                                    if ($idalim == $tipo_alim->idconfiguracion) {
                                        $alim_risneu = new Sds_ris_risneu_alimentacion();
                                        $alim_risneu->idrisneu = $model->idrisneu;
                                        $alim_risneu->alimentacion = $tipo_alim->idconfiguracion;
                                        $alim_risneu->created_at = date('Y-m-d H:i:s');
                                        $alim_risneu->idusuario_carga = Yii::$app->user->id;
                                        $alim_risneu->save();
                                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ris_risneu_alimentacion', $alim_risneu->idrisneu, $alim_risneu->getAttributes());
                                    }
                                }
                            }
                        }
                        if (!$finalizar) {
                            return $this->redirect([
                                'update',
                                'id' => $model->idrisneu,
                                'finalizar' => false,
                                'dni' => $model->dni_beneficiario,
                                'idllamada' => $idllamada,
                                'origen' => $origen
                            ]);
                        } else {
                            if ($origen == null) {
                                return $this->redirect(['index']);
                            } else {
                                if ($idllamada) {
                                    return $this->redirect($origen . "&dni=" . $model->dni_beneficiario . "&idllamada=" . $idllamada, 301);
                                } else {
                                    return $this->redirect($origen . "&dni=" . $model->dni_beneficiario, 301);
                                }
                            }
                        }
                    }
                }
            }

            $usuarioAuth = Yii::$app->user->identity;
            $permissions = Mds_seg_permiso::getAllPermissions(Sds_ris_risneu::ID_ITEM_SEGURIDAD, $usuarioAuth->idusuario);
            $hasOnePermission = $this->hasOnePermission($permissions, "alta");
            if ($hasOnePermission) {

                return $this->render('create', [
                    'model' => $model,
                    'origen' => $origen,
                    'idllamada' => $idllamada,
                    'encuestadores' => $encuestadores,
                    'realizadoPor' => $realizadoPor,
                    'localidades' => $localidades,
                    'barrios' => $barrios,
                    'areas' => $areas,
                    'calles' => $calles,
                    'callesInterseccion' => $callesInterseccion,
                    'provincias' => $provincias,
                    'tipos_alimentacion' => $tipos_alimentacion,
                    'risneu_alims' => $risneu_alims,
                    'selectViviendaUso' => $selectViviendaUso,
                    'selectViviendaUbicacion' => $selectViviendaUbicacion,
                    'selectViviendaPropiedad' => $selectViviendaPropiedad,
                    'selectViviendaTipo' => $selectViviendaTipo,
                    'selectViviendaPiso' => $selectViviendaPiso,
                    'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
                    'selectViviendaAgua' => $selectViviendaAgua,
                    'selectViviendaBano' => $selectViviendaBano,
                    'selectViviendaDesague' => $selectViviendaDesague,
                    'selectViviendaIluminacion' => $selectViviendaIluminacion,
                    'selectViviendaMedidor' => $selectViviendaMedidor,
                    'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
                    'selectViviendaCocina' => $selectViviendaCocina,
                    'selectViviendaTecho' => $selectViviendaTecho,
                    'selectViviendaParedes' => $selectViviendaParedes,
                    'existeJefe' => false,
                    'jefeNombreCompleto' => '',
                ]);
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
    }

    protected function inicializarRisneu($model)
    {
        $model->isNewRecord = true;
        $model->fecha = date('d-m-Y');
        $model->fecha_carga = date('Y-m-d');
        $user  = Yii::$app->user->identity;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->idusuario = $user->idusuario;
        $model->vivienda_habitaciones = 0;
        $model->vivienda_uso = 1;
        $model->vivienda_ubicacion = 1;
        $model->vivienda_propiedad = 1;
        $model->vivienda_habitaciones = 0;
        $model->vivienda_tipo = 1;
        $model->vivienda_piso = 1;
        $model->vivienda_agua_obtiene = 1;
        $model->vivienda_agua = 1;
        $model->vivienda_bano = 1;
        $model->vivienda_desague = 1;
        $model->vivienda_iluminacion = 1;
        $model->vivienda_medidor = 1;
        $model->vivienda_combustible_calefaccion = 1;
        $model->vivienda_combustible_cocina = 1;
        $model->vivienda_techo = 1;
        $model->vivienda_paredes = 1;
    }

    public function actionValidar_dni($idrisneu, $dni, $origen = null, $isCreate = true)
    {
        $idParentescoJefe = Sds_ris_persona::ID_PARENTESCO_JEFE;
        $result = [
            'isNew' => true,
            'message' => 'Nuevo Responsable',
        ];
        if ($dni != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Sds_ris_risneu::findBySql("select risneu.*
            from sds_ris_risneu as risneu
            left join (SELECT idrisneu,documento
                    FROM sds_ris_persona risper
                    JOIN sds_com_persona persona on persona.idpersona=risper.idpersona
                    where parentezco=$idParentescoJefe)
                    as temp_pers on temp_pers.idrisneu=risneu.idrisneu
            where documento=$dni and risneu.idrisneu!=$idrisneu and activo = 1")->one();

            if (!$model) {
                $model = Sds_ris_risneu::find()->where("dni = $dni AND activo = 1")->one();
            }

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona', $idrisneu, array());
            if ($isCreate) {
                if ($model == null) {
                    $model_actual = Sds_ris_risneu::findOne($idrisneu);
                    if ($model_actual != null) {
                        $result = [
                            'isNew' => false,
                            'method' => "create",
                            'dni' => $dni
                        ];
                    }
                } else {
                    $result = [
                        'isNew' => false,
                        'method' => "update",
                        'id' => $model->idrisneu,
                        'finalizar' => false,
                        'dni' => $dni,
                        'origen' => $origen
                    ];
                }
            } else {
                if ($model) {
                    $result = [
                        'isNew' => false,
                        'message' => "Responsable Existente <a href='index.php?r=sds_ris_risneu%2Fupdate&id=$model->idrisneu' target='_blank'>RISNeu N° $model->idrisneu</a>",
                    ];
                }
            }
        }

        return json_encode($result);
    }
    /**
     * Updates an existing Sds_ris_risneu model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $finalizar = false, $dni = null, $origen = null, $idllamada = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $modelRisPersona = new Sds_ris_persona();
        $personaJefe = $modelRisPersona->getJefeByIdRisneu($id);
        if (!$dni) {
            if (!$personaJefe && $model->dni) {
                $dni = $model->dni;
            } else if ($personaJefe) {
                $dni = $personaJefe->persona->documento;
            }
        }
        $existeJefe =  $personaJefe ? true : false;
        $jefeNombreCompleto = '';
        if ($existeJefe) {
            $jefe = Sds_com_persona::findOne($personaJefe->idpersona);
            if ($jefe) {
                $jefeApellido = mb_strtoupper($jefe->apellido);
                $jefeNombre = mb_strtoupper($jefe->nombre);
                $jefeNombreCompleto = "$jefeApellido, $jefeNombre";
            }
        }
        $model->dni_beneficiario = $dni;
        $origen = urldecode($origen);

        $model->idlocalidad = $model->idbarrio0->idlocalidad;
        $model->cod_postal = $model->idbarrio0->localidad->codigo_postal;
        $model->idprovincia = $model->idbarrio0->localidad->idprovincia;

        $encuestadores = ArrayHelper::map(Sds_ris_risneu::getEncuestadores(), 'idconfiguracion', 'descripcion');
        $realizadoPor = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_REALIZADO_POR), 'idconfiguracion', 'descripcion');
        $localidades = ArrayHelper::map(Sds_com_localidad::getLocalidadesByIdProvincia($model->idprovincia), 'idlocalidad', 'descripcion');
        $barrios = ArrayHelper::map(Sds_com_barrio::getBarriosByIdLocalidad($model->idlocalidad), 'idbarrio', 'nombre');
        $areas = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_AREA), 'idconfiguracion', 'descripcion');
        $calles = ArrayHelper::map(Sds_com_calle::find()->where(["activo" => 1])->orderBy(['idcalle' => SORT_ASC])->all(), 'idcalle', 'descripcion');
        $callesInterseccion = ArrayHelper::map(Sds_com_calle::find()->where(["activo" => 1])->orderBy(['idcalle' => SORT_ASC])->all(), 'idcalle', 'descripcion');
        $provincias = ArrayHelper::map(Sds_com_provincia::getProvinciasMostrar(), 'idprovincia', 'descripcion');

        //Alimentacion-vivienda
        $tipos_alimentacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ALIMENTACION);
        $risneu_alims = Sds_ris_risneu_alimentacion::find()->where("idrisneu = $model->idrisneu AND deleted_at IS NULL")->all();
        $selectViviendaUso = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_USO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaUbicacion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_UBIC, false), 'idconfiguracion', 'descripcion');
        $selectViviendaPropiedad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PROP, false), 'idconfiguracion', 'descripcion');
        $selectViviendaTipo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_TIPO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaPiso = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PISO, false), 'idconfiguracion', 'descripcion');

        $selectViviendaObtieneAgua = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_AGUA_OBT, false), 'idconfiguracion', 'descripcion');
        $selectViviendaAgua = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_AGUA, false), 'idconfiguracion', 'descripcion');
        $selectViviendaBano = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_BANO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaDesague = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_BANO_DES, false), 'idconfiguracion', 'descripcion');
        $selectViviendaIluminacion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_ILUMINACION, false), 'idconfiguracion', 'descripcion');
        $selectViviendaMedidor = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_MEDIDOR, false), 'idconfiguracion', 'descripcion');
        $selectViviendaCalefaccion = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_COMB_CALEF, false), 'idconfiguracion', 'descripcion');
        $selectViviendaCocina = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_COMB_COCINA, false), 'idconfiguracion', 'descripcion');
        $selectViviendaTecho = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_TECHO, false), 'idconfiguracion', 'descripcion');
        $selectViviendaParedes = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VIVIENDA_PAREDES, false), 'idconfiguracion', 'descripcion');

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Sds_ris_risneu #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'origen' => null,
                        'idllamada' => null,
                        'encuestadores' => $encuestadores,
                        'realizadoPor' => $realizadoPor,
                        'localidades' => $localidades,
                        'barrios' => $barrios,
                        'areas' => $areas,
                        'calles' => $calles,
                        'callesInterseccion' => $callesInterseccion,
                        'provincias' => $provincias,
                        'tipos_alimentacion' => $tipos_alimentacion,
                        'risneu_alims' => $risneu_alims,
                        'selectViviendaUso' => $selectViviendaUso,
                        'selectViviendaUbicacion' => $selectViviendaUbicacion,
                        'selectViviendaPropiedad' => $selectViviendaPropiedad,
                        'selectViviendaTipo' => $selectViviendaTipo,
                        'selectViviendaPiso' => $selectViviendaPiso,
                        'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
                        'selectViviendaAgua' => $selectViviendaAgua,
                        'selectViviendaBano' => $selectViviendaBano,
                        'selectViviendaDesague' => $selectViviendaDesague,
                        'selectViviendaIluminacion' => $selectViviendaIluminacion,
                        'selectViviendaMedidor' => $selectViviendaMedidor,
                        'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
                        'selectViviendaCocina' => $selectViviendaCocina,
                        'selectViviendaTecho' => $selectViviendaTecho,
                        'selectViviendaParedes' => $selectViviendaParedes,
                        'existeJefe' => $existeJefe,
                        'jefeNombreCompleto' => $jefeNombreCompleto
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_ris_risneu', $model->idrisneu, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Sds_ris_risneu #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Sds_ris_risneu #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'encuestadores' => $encuestadores,
                        'realizadoPor' => $realizadoPor,
                        'localidades' => $localidades,
                        'barrios' => $barrios,
                        'areas' => $areas,
                        'calles' => $calles,
                        'callesInterseccion' => $callesInterseccion,
                        'provincias' => $provincias,
                        'tipos_alimentacion' => $tipos_alimentacion,
                        'risneu_alims' => $risneu_alims,
                        'selectViviendaUso' => $selectViviendaUso,
                        'selectViviendaUbicacion' => $selectViviendaUbicacion,
                        'selectViviendaPropiedad' => $selectViviendaPropiedad,
                        'selectViviendaTipo' => $selectViviendaTipo,
                        'selectViviendaPiso' => $selectViviendaPiso,
                        'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
                        'selectViviendaAgua' => $selectViviendaAgua,
                        'selectViviendaBano' => $selectViviendaBano,
                        'selectViviendaDesague' => $selectViviendaDesague,
                        'selectViviendaIluminacion' => $selectViviendaIluminacion,
                        'selectViviendaMedidor' => $selectViviendaMedidor,
                        'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
                        'selectViviendaCocina' => $selectViviendaCocina,
                        'selectViviendaTecho' => $selectViviendaTecho,
                        'selectViviendaParedes' => $selectViviendaParedes,
                        'existeJefe' => $existeJefe,
                        'jefeNombreCompleto' => $jefeNombreCompleto
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */

            if ($model->load($request->post())) {
                $guardar = true;
                if ($model->fecha != null) {
                    $model->fecha = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha)));
                } else {
                    $model->addError('fecha', "Debe ingresar la fecha");
                    $guardar = false;
                }
                if ($guardar) {
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->idusuario_actualiza = Yii::$app->user->id;
                    $model->dni = $request->post("Sds_ris_risneu")["dni_beneficiario"];

                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_ris_risneu', $model->idrisneu, $model->getAttributes());
                        $barrios = ArrayHelper::map(
                            Sds_com_barrio::getBarriosByIdLocalidad($model->idlocalidad),
                            'idbarrio',
                            'nombre'
                        );
                        $tipo_alim_form = isset($request->post("Sds_ris_risneu")["tipo_alim"]) ? $request->post("Sds_ris_risneu")["tipo_alim"] : array();
                        if (!empty($tipo_alim_form)) {

                            $alimentacionToDelete = Sds_ris_risneu_alimentacion::find()->where("idrisneu = $model->idrisneu AND deleted_at IS NULL")->andWhere(['NOT IN', 'alimentacion', $tipo_alim_form])->all();
                            foreach ($alimentacionToDelete as $ris_alim) {
                                $ris_alim->deleted_at = date('Y-m-d H:i:s');
                                $ris_alim->idusuario_borra = Yii::$app->user->id;
                                $ris_alim->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_ris_risneu_alimentacion', $ris_alim->idrisneualimentacion, $ris_alim->getAttributes());
                            }

                            foreach ($tipo_alim_form as $idalim) {
                                $alimentacionToCreate = Sds_ris_risneu_alimentacion::find()->where("idrisneu = $model->idrisneu AND alimentacion = $idalim AND deleted_at IS NULL")->one();
                                if (!$alimentacionToCreate) {
                                    $alim_risneu = new Sds_ris_risneu_alimentacion();
                                    $alim_risneu->idrisneu = $model->idrisneu;
                                    $alim_risneu->alimentacion = intval($idalim);
                                    $alim_risneu->created_at = date('Y-m-d H:i:s');
                                    $alim_risneu->idusuario_carga = Yii::$app->user->id;
                                    $alim_risneu->save();
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ris_risneu_alimentacion', $alim_risneu->idrisneu, $alim_risneu->getAttributes());
                                }
                            }
                        }
                        if (!$finalizar) {
                            $risneu_alims = Sds_ris_risneu_alimentacion::find()->where("idrisneu = $model->idrisneu AND deleted_at IS NULL")->all();
                            return $this->render('update', [
                                'model' => $model,
                                'origen' => $origen,
                                'idllamada' => $idllamada,
                                'encuestadores' => $encuestadores,
                                'realizadoPor' => $realizadoPor,
                                'localidades' => $localidades,
                                'barrios' => $barrios,
                                'areas' => $areas,
                                'calles' => $calles,
                                'callesInterseccion' => $callesInterseccion,
                                'provincias' => $provincias,
                                'tipos_alimentacion' => $tipos_alimentacion,
                                'risneu_alims' => $risneu_alims,
                                'selectViviendaUso' => $selectViviendaUso,
                                'selectViviendaUbicacion' => $selectViviendaUbicacion,
                                'selectViviendaPropiedad' => $selectViviendaPropiedad,
                                'selectViviendaTipo' => $selectViviendaTipo,
                                'selectViviendaPiso' => $selectViviendaPiso,
                                'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
                                'selectViviendaAgua' => $selectViviendaAgua,
                                'selectViviendaBano' => $selectViviendaBano,
                                'selectViviendaDesague' => $selectViviendaDesague,
                                'selectViviendaIluminacion' => $selectViviendaIluminacion,
                                'selectViviendaMedidor' => $selectViviendaMedidor,
                                'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
                                'selectViviendaCocina' => $selectViviendaCocina,
                                'selectViviendaTecho' => $selectViviendaTecho,
                                'selectViviendaParedes' => $selectViviendaParedes,
                                'existeJefe' => $existeJefe,
                                'jefeNombreCompleto' => $jefeNombreCompleto
                            ]);
                        } else {
                            if ($origen != null) {
                                if ($idllamada) {
                                    return $this->redirect($origen . "&dni=" . $model->dni_beneficiario . "&idllamada=" . $idllamada, 301);
                                } else {
                                    return $this->redirect($origen . "&dni=" . $model->dni_beneficiario, 301);
                                }
                            }
                            return $this->redirect(['index']);
                        }
                    }
                }
            }
            return $this->render('update', [
                'model' => $model,
                'origen' => $origen,
                'idllamada' => $idllamada,
                'encuestadores' => $encuestadores,
                'realizadoPor' => $realizadoPor,
                'localidades' => $localidades,
                'barrios' => $barrios,
                'areas' => $areas,
                'calles' => $calles,
                'callesInterseccion' => $callesInterseccion,
                'provincias' => $provincias,
                'tipos_alimentacion' => $tipos_alimentacion,
                'risneu_alims' => $risneu_alims,
                'selectViviendaUso' => $selectViviendaUso,
                'selectViviendaUbicacion' => $selectViviendaUbicacion,
                'selectViviendaPropiedad' => $selectViviendaPropiedad,
                'selectViviendaTipo' => $selectViviendaTipo,
                'selectViviendaPiso' => $selectViviendaPiso,
                'selectViviendaObtieneAgua' => $selectViviendaObtieneAgua,
                'selectViviendaAgua' => $selectViviendaAgua,
                'selectViviendaBano' => $selectViviendaBano,
                'selectViviendaDesague' => $selectViviendaDesague,
                'selectViviendaIluminacion' => $selectViviendaIluminacion,
                'selectViviendaMedidor' => $selectViviendaMedidor,
                'selectViviendaCalefaccion' => $selectViviendaCalefaccion,
                'selectViviendaCocina' => $selectViviendaCocina,
                'selectViviendaTecho' => $selectViviendaTecho,
                'selectViviendaParedes' => $selectViviendaParedes,
                'existeJefe' => $existeJefe,
                'jefeNombreCompleto' => $jefeNombreCompleto
            ]);
        }
    }

    /**
     * Delete an existing Sds_ris_risneu model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = Yii::$app->user->id;
        $model->activo = 0;
        $model->dni_beneficiario = 0;

        if ($model->update()) {
            Yii::$app->session->setFlash('success', "Se eliminó correctamente el risneu.");
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_ris_risneu', $id, $model->getAttributes());
        } else {
            Yii::$app->session->setFlash('error', "Error al eliminar el risneu.");
        }

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
    }

    public function actionReactivate($id)
    {

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Sds_ris_risneu::ID_ITEM_SEGURIDAD, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "baja");

        if ($hasOnePermission) {
            $risneu = Sds_ris_risneu::findOne($id);
            if ($risneu) {
                $risneu->activo = 1;
                $risneu->deleted_at = null;
                $risneu->idusuario_borra = null;
                $risneu->dni_beneficiario = 0;
                if ($risneu->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente el risneu.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el risneu.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_ris_risneu', $risneu->idrisneu, $risneu->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "El risneu no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Sds_ris_risneu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_ris_risneu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_ris_risneu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImprimir($id)
    {
        $risneu = $this->findModel($id);
        //$risneu->sdsRisPersonas
        $content = $this->renderPartial('impresion', ['risneu' => $risneu]); // setup kartik\mpdf\Pdf component
        //    print_r($content);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_LEGAL,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            //'marginTop'=>Pdf::MARG
            'defaultFontSize' => 12,
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '../web/css/impresion_ris.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'RISNEU',
                'SetHeader' => null,
                'SetFooter' => ["RISNEU N° {$risneu->idrisneu} |Ministerio de Desarrollo Social y Trabajo | Página {PAGENO} de {nb}"],
            ]
        ]);
        $pdf->marginTop = 3;
        $pdf->marginLeft = 8;
        $pdf->marginRight = 8;
        //$pdf->marginFooter = 3;
        //$pdf->marginBottom = 3;


        return $pdf->render();
    }

    protected function getFilterLocalidades($oficial)
    {
        $oficialSql = "";
        if ($oficial != null) {
            $oficialSql = " AND risneu.oficial = $oficial";
        }
        //Busqueda localidades
        $localidadesFiltro = Sds_com_localidad::findBySql(
            "SELECT localidad.idlocalidad as loc_idlocalidad, 
                    localidad.descripcion as loc_descripcion 
            FROM sds_ris_risneu risneu 
            INNER JOIN sds_com_barrio barrio
            ON risneu.idbarrio = barrio.idbarrio
            INNER JOIN sds_com_localidad localidad 
            ON barrio.idlocalidad = localidad.idlocalidad 
            WHERE risneu.activo = 1 AND barrio.activo = 1 AND localidad.activo = 1 $oficialSql
            ORDER BY loc_descripcion ASC
            "
        )->asArray()->all();

        $localidadesFiltro = ArrayHelper::map($localidadesFiltro, 'loc_idlocalidad', 'loc_descripcion');
        return $localidadesFiltro;
    }

    protected function getFilterEncuestadores($oficial)
    {
        $modelRisneu = new Sds_ris_risneu();
        $oficialSql = "";
        if ($oficial != null) {
            $oficialSql = " AND risneu.oficial = $oficial";
        }
        //Busqueda encuestadores
        $encuestadoresFiltro = $modelRisneu->getEncuestadoresCargadosEnRisneu($oficialSql);

        $encuestadoresFiltro = ArrayHelper::map($encuestadoresFiltro, 'idencuestador', 'descripcion');
        return $encuestadoresFiltro;
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_ris_risneu_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
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

    public function actionDashboard()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_ris_risneu::ID_ROL_RISNEU_ADMINISTRADOR_GENERAL);
        $hasRolDashboard = Mds_seg_usuario_rol::hasRol(Sds_ris_risneu::ID_ROL_DASHBOARD);

        if ($hasRolAdminGeneral || $hasRolDashboard) {
            /*
        Cantidad de risneu (activos)
        */
            $idSituacionCalle = 4929;
            $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
            $fechaFin = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;


            $model = new Sds_ris_risneu();
            $where = "sds_ris_risneu.activo = 1";
            if ($fechaInicio && $fechaFin) {
                $where .= " AND fecha_carga >= '$fechaInicio' AND fecha_carga <= '$fechaFin'";
            } else if ($fechaInicio) {
                $where .= " AND fecha_carga >= '$fechaInicio'";
            } else if ($fechaFin) {
                $where .= " AND fecha_carga <= '$fechaFin'";
            }
            $totalRisneu = $model->find()
                ->select([
                    'localidad.idlocalidad',
                    'encuestador',
                    'realizado_por',
                    'area',
                    "if(sds_ris_risneu.activo,
                    if (
                        risPersonaEstado.idpersonarisneu IS NULL or 
                        (
                            vivienda_uso!=$idSituacionCalle and 
                            (
                                vivienda_uso=1 or 
                                vivienda_ubicacion=1 or 
                                vivienda_propiedad=1 or
                                vivienda_habitaciones=0 or 
                                vivienda_tipo=1 or 
                                vivienda_piso=1 or 
                                vivienda_agua_obtiene=1 or 
                                vivienda_agua=1 or 
                                vivienda_bano=1 or 
                                vivienda_desague=1 or 
                                vivienda_iluminacion=1 or 
                                vivienda_medidor=1 or
                                vivienda_combustible_calefaccion=1 or 
                                vivienda_combustible_cocina=1 or 
                                vivienda_techo=1 or 
                                vivienda_paredes=1
                            )
                        ),
                        0,1
                    )
                ,2) estado"
                ])
                ->join("inner join", "sds_com_barrio as barrio", "barrio.idbarrio = sds_ris_risneu.idbarrio")
                ->join("inner join", "sds_com_localidad as localidad", "localidad.idlocalidad = barrio.idlocalidad")
                ->join("left join", "sds_ris_persona as risPersonaEstado", "risPersonaEstado.idrisneu = sds_ris_risneu.idrisneu AND risPersonaEstado.activo = 1")
                ->where($where)
                ->groupBy("sds_ris_risneu.idrisneu")
                ->all();

            $arrayLocalidades = $model->find()
                ->select(['localidad.idlocalidad', 'UPPER(CONCAT(localidad.descripcion, \' (\', provincia.descripcion, \') \')) AS descripcion'])
                ->join("inner join", "sds_com_barrio as barrio", "barrio.idbarrio = sds_ris_risneu.idbarrio")
                ->join("inner join", "sds_com_localidad as localidad", "localidad.idlocalidad = barrio.idlocalidad")
                ->join("inner join", "sds_com_provincia as provincia", "provincia.idprovincia = localidad.idprovincia")
                ->where($where)
                ->groupBy(['localidad.idlocalidad'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayEncuestadores = $model->find()
                ->select([
                    'encuestador.idconfiguracion as idencuestador',
                    'encuestador.descripcion as descripcion',
                ])
                ->join("inner join", "sds_com_configuracion as encuestador", "encuestador.idconfiguracion = sds_ris_risneu.encuestador")
                ->where($where)
                ->groupBy(['sds_ris_risneu.encuestador'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayEstados = [
                [
                    'descripcion' => 'Completos',
                    'titulo' => 'Estados',
                    'cantidadRegistros' => 0,
                    'url' => '&estado=1',
                ],
                [
                    'descripcion' => 'Incompletos',
                    'titulo' => 'Estados',
                    'cantidadRegistros' => 0,
                    'url' => '&estado=0',
                ]
            ];

            $arrayRealizadoPor = $model->find()
                ->select([
                    'realizadoPor.idconfiguracion as idrealizadopor',
                    'realizadoPor.descripcion as descripcion',
                ])
                ->join("inner join", "sds_com_configuracion as realizadoPor", "realizadoPor.idconfiguracion = sds_ris_risneu.realizado_por")
                ->where($where)
                ->groupBy(['sds_ris_risneu.realizado_por'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            $arrayAreas = $model->find()
                ->select([
                    'area.idconfiguracion as idarea',
                    'area.descripcion as descripcion',
                ])
                ->join("inner join", "sds_com_configuracion as area", "area.idconfiguracion = sds_ris_risneu.area")
                ->where($where)
                ->groupBy(['sds_ris_risneu.area'])
                ->orderBy(['descripcion' => SORT_ASC])
                ->asArray()
                ->all();

            foreach ($totalRisneu as $risneu) {
                $indexLocalidades = 0;
                $indexEncuestadores = 0;
                $indexRealizadoPor = 0;
                $indexArea = 0;
                $flagLocalidades = true;
                $flagEncuestadores = true;
                $flagRealizadoPor = true;
                $flagArea = true;
                while ($flagLocalidades && $indexLocalidades < count($arrayLocalidades)) {
                    $arrayLocalidades[$indexLocalidades]['titulo'] = 'Localidades';
                    $arrayLocalidades[$indexLocalidades]['cantidadRegistros'] = isset($arrayLocalidades[$indexLocalidades]['cantidadRegistros']) ? $arrayLocalidades[$indexLocalidades]['cantidadRegistros'] :  0;
                    if ($risneu['idlocalidad'] == $arrayLocalidades[$indexLocalidades]['idlocalidad']) {
                        $arrayLocalidades[$indexLocalidades]['cantidadRegistros']++;
                        $arrayLocalidades[$indexLocalidades]['url'] = "&idlocalidad={$arrayLocalidades[$indexLocalidades]['idlocalidad']}";
                        $flagLocalidades = false;
                    }
                    $indexLocalidades++;
                }

                while ($flagEncuestadores && $indexEncuestadores < count($arrayEncuestadores)) {
                    $arrayEncuestadores[$indexEncuestadores]['titulo'] = 'Encuestadores';
                    $arrayEncuestadores[$indexEncuestadores]['cantidadRegistros'] = isset($arrayEncuestadores[$indexEncuestadores]['cantidadRegistros']) ? $arrayEncuestadores[$indexEncuestadores]['cantidadRegistros'] :  0;
                    if ($risneu['encuestador'] == $arrayEncuestadores[$indexEncuestadores]['idencuestador']) {
                        $arrayEncuestadores[$indexEncuestadores]['cantidadRegistros']++;
                        $arrayEncuestadores[$indexEncuestadores]['url'] = "&idencuestador={$arrayEncuestadores[$indexEncuestadores]['idencuestador']}";
                        $flagEncuestadores = false;
                    }
                    $indexEncuestadores++;
                }

                if ($risneu['estado'] == 0) {
                    $arrayEstados[1]['cantidadRegistros']++;
                } else if ($risneu['estado'] == 1) {
                    $arrayEstados[0]['cantidadRegistros']++;
                }

                while ($flagRealizadoPor && $indexRealizadoPor < count($arrayRealizadoPor)) {
                    $arrayRealizadoPor[$indexRealizadoPor]['titulo'] = 'Realizado Por';
                    $arrayRealizadoPor[$indexRealizadoPor]['cantidadRegistros'] = isset($arrayRealizadoPor[$indexRealizadoPor]['cantidadRegistros']) ? $arrayRealizadoPor[$indexRealizadoPor]['cantidadRegistros'] :  0;
                    if ($risneu['realizado_por'] == $arrayRealizadoPor[$indexRealizadoPor]['idrealizadopor']) {
                        $arrayRealizadoPor[$indexRealizadoPor]['cantidadRegistros']++;
                        $arrayRealizadoPor[$indexRealizadoPor]['url'] = "&idrealizadopor={$arrayRealizadoPor[$indexRealizadoPor]['idrealizadopor']}";
                        $flagRealizadoPor = false;
                    }
                    $indexRealizadoPor++;
                }

                while ($flagArea && $indexArea < count($arrayAreas)) {
                    $arrayAreas[$indexArea]['titulo'] = 'Áreas';
                    $arrayAreas[$indexArea]['cantidadRegistros'] = isset($arrayAreas[$indexArea]['cantidadRegistros']) ? $arrayAreas[$indexArea]['cantidadRegistros'] :  0;
                    if ($risneu['area'] == $arrayAreas[$indexArea]['idarea']) {
                        $arrayAreas[$indexArea]['cantidadRegistros']++;
                        $arrayAreas[$indexArea]['url'] = "&idarea={$arrayAreas[$indexArea]['idarea']}";
                        $flagArea = false;
                    }
                    $indexArea++;
                }
            }

            $arrayIndicadores = array_merge($arrayLocalidades, $arrayEncuestadores, $arrayEstados, $arrayRealizadoPor, $arrayAreas);

            return $this->render('dashboard/index', [
                'totalRisneu' => $totalRisneu,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'arrayIndicadores' => $arrayIndicadores,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
}
