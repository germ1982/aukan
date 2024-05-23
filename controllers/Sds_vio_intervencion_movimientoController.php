<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use \yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use app\models\Sds_vio_intervencion;
use app\models\Sds_vio_intervencion_movimiento;
use app\models\Sds_vio_intervencion_movimientoSearch;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_800_llamada;
use app\models\Mds_org_contacto;
use app\models\Mds_sys_log;


/**
 * Sds_vio_intervencion_movimientoController implements the CRUD actions for Sds_vio_intervencion_movimiento model.
 */
class Sds_vio_intervencion_movimientoController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_vio_intervencion_movimiento models.
     * @return mixed
     */
    public function actionIndex($idintervencion = null)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {

            $request = Yii::$app->request;
            $searchModel = new Sds_vio_intervencion_movimientoSearch();
            $searchModel->idintervencion = $idintervencion;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $model_intervencion = Sds_vio_intervencion::findOne($idintervencion);
            $model_llamada = $model_intervencion ? Sds_800_llamada::findOne($model_intervencion->idllamada) : null ;
            $estaAtendida = $model_llamada ? $model_llamada->estado == Sds_800_llamada::ESTADO_ATENDIDA : true;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion_movimiento/index', $idintervencion, array());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                // $dataProvider->sort->route = '/sds_vio_intervencion/index';
                return [
                    'title' => " Listado de Movimientos",
                    'content' => $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral,
                        'estaAtendida' => $estaAtendida
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Sds_vio_intervencion_movimiento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        $model = $this->findModel($id);
        $canView = Sds_vio_intervencion::estaAtendida($model->idintervencion);

        if ($hasRolViolencia && $canView) {
            $request = Yii::$app->request;

            $fecha = armarDate($model->fecha);
            $fecha_alta = armarDate($model->created_at);
            $movimiento = "";
            $username = "";

            if ($model && isset($model->tipoMovimiento)) {
                $movimiento = $model->tipoMovimiento->descripcion;
            }
            if ($model && isset($model->idUsuario)) {
                $username = $model->idUsuario->apellido . ", " . $model->idUsuario->nombre;
            }

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion_movimiento', $id, array());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Ver Movimiento #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'movimiento' => $movimiento,
                        'fecha' => $fecha,
                        'username' => $username,
                        'fecha_alta' => $fecha_alta,
                    ]),
                    'footer' =>
                    Html::a(
                        'Volver',
                        ['index', 'idintervencion' => $model->idintervencion],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    )
                        .
                        (is_null($model->deleted_at) || $hasRolAdminGeneral ?
                            Html::a(
                                'Editar',
                                ['update', 'id' => $id],
                                ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                            )
                            : ""),
                ];
            } else {
                return $this->render('view', [
                    'model' => $model,
                    'movimiento' => $movimiento,
                    'fecha' => $fecha,
                    'username' => $username,
                    'fecha_alta' => $fecha_alta,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_vio_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        $canCreate = Sds_vio_intervencion::estaAtendida($idintervencion);

        if ($hasRolViolencia && $canCreate) {
            $request = Yii::$app->request;
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;

            $model = new Sds_vio_intervencion_movimiento();
            $model->idusuario = $idusuario;
            $model->created_at = date('Y-m-d H:i:s');
            $model->idintervencion = $idintervencion;
            $tipo_movimiento =  $this->getListTipoMovimiento();

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Crear Movimiento",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'tipo_movimiento' => $tipo_movimiento,
                        ]),
                        'footer' => Html::a(
                            ' Volver',
                            ['index', 'idintervencion' => $model->idintervencion],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        ) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'boton-guardar-movimiento'])

                    ];
                } else if ($request->isPost) {

                    $model->load($request->post());

                    $fecha_vio = ArmarDateParaMySql($model->fecha);
                    $fecha_vio = date_create($fecha_vio);
                    $fecha_vio = date_format($fecha_vio, 'Y-m-d');
                    $model->fecha = $fecha_vio;

                    $saveMovimiento = $model->save();

                    if ($saveMovimiento) { // Guardo correctamente
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_intervencion_movimiento', $model->idintervencion, $model->getAttributes());

                        $modelIntervencion =  New Sds_vio_intervencion();
                        $intervencion = $modelIntervencion->findOne($idintervencion);

                        //TODO: Aca enviar a optic
                        if ($intervencion){
                           
                            $usuario = env('SUR_USER');
                            $password = env('SUR_PASSWORD');
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

                            if ($records->records && $records->records[0]){
                                $token = $records->records[0]->token; 
                                $recurseroId = 23;
                                $idcontacto  = Yii::$app->user->identity->idcontacto;
                                if ($idcontacto) {
                                    $contacto = Mds_org_contacto::findOne($idcontacto);
                                    if ($contacto && $contacto->dispositivo) {
                                        $recurseroId =  $contacto->dispositivo->idcapaitem; // Obtiene el dispositivo del usuario logueado
                                    }
                                }
                                $idLocalidad = 58035070000; //TODO Pendiente realizar mapeo con localidades OPTIC
                                $persona = $intervencion->persona0;
                                $modalidad = $intervencion->tipo_modalidad ?? null;

                                $codigoSituacion = $modelIntervencion::opticMapCodigoSituacion($intervencion->tipo_situacion);
                                $opticFechaAbordaje = date_format( date_create($intervencion->fecha), 'd/m/Y');
                                $opticSexoPersonaId =  $persona ? $persona->genero : null;
                                $opticSexoPersona = Sds_vio_intervencion::opticMapSexoPersona($opticSexoPersonaId);
                                $opticModalidad = Sds_vio_intervencion::opticMapModalidad($modalidad);
                                $opticObservacion = $model->detalle ? "Movimiento: $model->detalle" : 'Sin Observacion';

                                $opticViolenciaFisica =  Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_FISICA;
                                $opticViolenciaPsicologica = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_PSICOLOGICA;
                                $opticViolenciaSexual = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_SEXUAL;
                                $opticViolenciaEconomicaPatrimonial = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_PATRIMONIAL;
                                $opticViolenciaSimbolica = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_SIMBOLICA;
                                $opticViolenciaNegligenciaAbandono = Sds_vio_intervencion::ID_OPTIC_TIPO_VIOLENCIA_NEGLIGENCIA_ABANDONO;
                                $opticArrayTipoViolencia = array();

                                $fecha_vio_format = date_create($fecha_vio);
                                $fecha_vio_format = date_format($fecha_vio_format, 'd/m/Y');
                                

                                if ($intervencion->tipo_violencia_fisica){
                                    array_push($opticArrayTipoViolencia, $opticViolenciaFisica);
                                };
                                if ($intervencion->tipo_violencia_psicologica){
                                    array_push($opticArrayTipoViolencia, $opticViolenciaPsicologica);
                                };
                                if ($intervencion->tipo_violencia_sexual){
                                    array_push($opticArrayTipoViolencia, $opticViolenciaSexual);
                                };
                                if ($intervencion->tipo_violencia_economica_patrimonial){
                                    array_push($opticArrayTipoViolencia, $opticViolenciaEconomicaPatrimonial);
                                };
                                if ($intervencion->tipo_violencia_simbolica){
                                    array_push($opticArrayTipoViolencia, $opticViolenciaSimbolica);
                                };
                                if ($intervencion->tipo_violencia_negligencia_abandono){
                                    array_push($opticArrayTipoViolencia, $opticViolenciaNegligenciaAbandono);
                                };


                                $callCurl = curl_init();
                                $idSistema = env('OPTIC_VIOLENCIA_ID_SISTEMA_SUR');
                                $filtroBase = "dni={$persona->documento}&genero={$opticSexoPersona}&dniReportado={$persona->documento}&nroReferencia={$intervencion->idintervencion}&observacion={$opticObservacion}&fechaDelHecho={$opticFechaAbordaje}&idModalidad={$opticModalidad}&idSistema={$idSistema}&idLocalidad={$idLocalidad}&codigoSituacion={$codigoSituacion}&idRecursero={$recurseroId}&fechaIntervencion={$fecha_vio_format}";
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
                        }

                        return [
                            'title' => "Crear Movimiento",
                            'content' => '<span class="text-success">Movimiento asignado exitosamente!</span>',
                            'footer' => Html::a(
                                ' Volver a la Grilla',
                                ['index', 'idintervencion' => $model->idintervencion],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            ) .
                                Html::a('Agregar Otro', ['create', 'idintervencion' => $model->idintervencion], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    } else { // Mostramos mensaje de error
                        return [
                            'title' => "Crear Movimiento",
                            'content' => '<span class="text-error">Error al crear movimiento!</span>',
                            'footer' => Html::a(
                                ' Volver a la Grilla',
                                ['index', 'idintervencion' => $model->idintervencion],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            ) .
                                Html::a('Agregar Otro', ['create', 'idintervencion' => $model->idintervencion], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                }
            } else {
                /*
                *   Process for non-ajax request
                */
                $searchModel = new Sds_vio_intervencion_movimientoSearch();
                $searchModel->idintervencion = $idintervencion;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral
                ]);

                // if ($model->load($request->post())) {

                //     $fecha_vio = ArmarDateParaMySql($model->fecha);
                //     $fecha_vio = date_create($fecha_vio);
                //     $fecha_vio = date_format($fecha_vio, 'Y-m-d');
                //     $model->fecha = $fecha_vio;

                //     if ($model->save()) {
                //         Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_intervencion_movimiento', $model->idintervencion, $model->getAttributes());
                //         return  $this->redirect(['sds_vio_intervencion/index']);
                //     }
                // } else {
                //     return $this->render('create', [
                //         'action' => $action,
                //         'model' => $model,
                //         'tipo_movimiento' => $tipo_movimiento,
                //     ]);
                // }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_vio_intervencion_movimiento model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        $model = $this->findModel($id);
        $canUpdate = Sds_vio_intervencion::estaAtendida($model->idintervencion);

        if ($hasRolViolencia && $canUpdate) {
            $request = Yii::$app->request;
            $tipo_movimiento =  $this->getListTipoMovimiento();

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Actualizar Movimiento #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                            'tipo_movimiento' => $tipo_movimiento,
                        ]),
                        'footer' => Html::a(
                            ' Volver',
                            ['index', 'idintervencion' => $model->idintervencion],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        ) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else if ($model->load($request->post())) {
                    $model->updated_at = date('Y-m-d H:i:s');

                    $fecha_vio = ArmarDateParaMySql($model->fecha);
                    $fecha_vio = date_create($fecha_vio);
                    $fecha_vio = date_format($fecha_vio, 'Y-m-d');
                    $model->fecha = $fecha_vio;

                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion_movimiento', $id, $model->getAttributes());

                        $searchModel = new Sds_vio_intervencion_movimientoSearch();
                        $searchModel->idintervencion = $model->idintervencion;
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion_movimiento/index', $model->idintervencion, array());

                        return [
                            'title' => " Listado de Movimientos",
                            'content' => $this->renderAjax('index', [
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                }
            } else {
                /*
                *   Process for non-ajax request
                */
                if ($model->load($request->post())) {
                    $model->updated_at = date('Y-m-d H:i:s');

                    $fecha_vio = ArmarDateParaMySql($model->fecha);
                    $fecha_vio = date_create($fecha_vio);
                    $fecha_vio = date_format($fecha_vio, 'Y-m-d');
                    $model->fecha = $fecha_vio;

                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion_movimiento', $id, $model->getAttributes());
                        return  $this->redirect(['sds_vio_intervencion_movimiento/index']);
                    }
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'tipo_movimiento' => $tipo_movimiento,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_vio_intervencion_movimiento model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        $model = $this->findModel($id);
        $canDelete = Sds_vio_intervencion::estaAtendida($model->idintervencion);

        if ($hasRolViolencia && $canDelete) {

            $request = Yii::$app->request;
            $model->deleted_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion_movimiento', $id, $model->getAttributes());
            }

            if ($request->isAjax) {
                /*
                *   Process for non-ajax request
                */
                $searchModel = new Sds_vio_intervencion_movimientoSearch();
                $searchModel->idintervencion = $model->idintervencion;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion_movimiento/index', $id, array());
                return [
                    'title' => " Listado de Movimientos",
                    'content' => $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                /*
                *   Process for non-ajax request
                */
                return  $this->redirect(['sds_vio_intervencion_movimiento/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    public function actionReactivate($id)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        $model = $this->findModel($id);
        $canReactivate = Sds_vio_intervencion::estaAtendida($model->idintervencion);

        if ($hasRolViolencia && $canReactivate) {
            $request = Yii::$app->request;
            $model->deleted_at = NULL;

            if ($model->update()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion_movimiento', $id, $model->getAttributes());
            }

            if ($request->isAjax) {
                /*
                *   Process for non-ajax request
                */
                $searchModel = new Sds_vio_intervencion_movimientoSearch();
                $searchModel->idintervencion = $model->idintervencion;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion_movimiento/index', $id, array());
                return [
                    'title' => " Listado de Movimientos",
                    'content' => $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                /*
                *   Process for non-ajax request
                */
                return  $this->redirect(['sds_vio_intervencion_movimiento/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Sds_vio_intervencion_movimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_vio_intervencion_movimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_vio_intervencion_movimiento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getListTipoMovimiento()
    {
        $tipomovimiento = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_MOVIMIENTO_TIPO_MOVIMIENTO, false);
        $listmovimiento = ArrayHelper::map($tipomovimiento, 'idconfiguracion', 'descripcion');
        return $listmovimiento;
    }
}


function ArmarDateParaMySql($fecha)
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

function armarDate($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 0, 4);
    $mes  = substr($fecha, 5, 2);
    $dia = substr($fecha, 8, 2);
    $fecha = "$dia/$mes/$anio";
    // $DT = strtotime("$mes/$dia/$anio");
    return $fecha;
}
