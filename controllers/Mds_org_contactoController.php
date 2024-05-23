<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_org_contactoSearch;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_documento;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_gis_capa_item;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\ForbiddenHttpException;

/**
 * Mds_org_contactoController implements the CRUD actions for Mds_org_contacto model.
 */
class Mds_org_contactoController extends Controller
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
                    // 'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'view', 'logout',
                    'certificacion_laboral', 'validar_dni', 'get_contacto',
                    'get_id_contacto_por_legajo', 'create_ext', 'cmb_contacto',
                    'get_documentos', 'foto_dni',
                    'domicilio', 'certificacion_laboral', 'pase_planta', 'get_cmb_contacto'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete', 'update', 'view',
                            'logout', 'validar_dni', 'certificacion_laboral',
                            'domicilio', 'pase_planta'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ORG_CONTACTOS,
                        ],
                    ],
                    [
                        'actions' => [
                            'logout', 'get_contacto',
                            'get_id_contacto_por_legajo', 'cmb_contacto',
                            'get_documentos',
                            'get_cmb_contacto'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_org_contacto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_org_contactoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mds_org_contacto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Contacto #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionValidar_dni($idcontacto, $dni)
    {
        $result = array();
        $model_persona = Sds_com_persona::find()->where(["documento" => $dni])->one();
        if ($model_persona != null) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto/validar_dni', $model_persona->idpersona, array());
            $contacto_existente = Mds_org_contacto::find()->where(['!=', 'idcontacto', $idcontacto])->andWhere(['idpersona' => $model_persona->idpersona])->one();
            if ($contacto_existente != null) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto', $contacto_existente->idcontacto, array());
                return json_encode(['idcontacto' => $contacto_existente->idcontacto]);
            } else {
                array_push($result, $model_persona->getAttributes());
            }
        }
        return json_encode($result);
    }

    /**
     * Creates a new Mds_org_contacto model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_org_contacto();
        $model->crear_usuario = false;
        $model->activo = true;
        $model->interno = true;
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Contacto",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar']),

                ];
            } elseif ($model->load($request->post())) {
                $model_com_persona = new Sds_com_persona();
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $ban_persona = 0;
                if ($model->idpersona > 0) {
                    $ban_persona = 1;
                    $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                }
                $model_com_persona->documento_tipo = '83';
                $model_com_persona->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                $model_com_persona->documento = $model->documento;
                $model_com_persona->nacionalidad = $model->nacionalidad;
                $model_com_persona->genero = $model->sexo;
                $model_com_persona->nombre = $model->nombre;
                $model_com_persona->apellido = $model->apellido;
                $model_com_persona->conviviente = 0;
                if (!$model_com_persona->save()) {
                    $guardado = false;
                    $model->addError("documento", "Error al guardar la persona asociada al DNI ingresado.");
                    $transaction->rollBack();
                } else {
                    $model->idpersona = $model_com_persona->idpersona;
                    if (!$model->activo) {
                        $model->iddispositivo = 330;
                    }
                    $model->eventual = $model->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES ? 1 : 0;
                    $model->planta_politica = $model->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA ? 1 : 0;
                    if ($model->fecha_ingreso_planta != '') {
                        $ingresoPlanta = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_ingreso_planta)));
                    } else {
                        $ingresoPlanta = null;
                    }
                    $model->fecha_ingreso_planta = $ingresoPlanta;
                    if ($model->fecha_ingreso != '') {
                        $ingreso = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_ingreso)));
                    } else {
                        $ingreso = null;
                    }
                    $model->fecha_ingreso = $ingreso;
                    if ($guardado && $model->save()) {
                        $idusuario = 0;
                        if ($model->crear_usuario) {
                            $usuario = new Mds_seg_usuario();
                            $usuario->idcontacto = $model->idcontacto;
                            $usuario->activo = true;
                            $primer_nombre = Mds_org_contacto::eliminar_tildes(strtolower(strpos($model_com_persona->nombre, ' ') > 0 ? substr($model_com_persona->nombre, 0, strpos($model_com_persona->nombre, ' ')) : $model_com_persona->nombre));
                            $primer_apellido = Mds_org_contacto::eliminar_tildes(strtolower(strpos($model_com_persona->apellido, ' ') > 0 ? substr($model_com_persona->apellido, 0, strpos($model_com_persona->apellido, ' ')) : $model_com_persona->apellido));
                            $usuario->user = $primer_nombre . '.' . $primer_apellido;
                            $usuario->pass = substr($primer_nombre, 0, 2) . substr($primer_apellido, 0, 2) . (strlen($primer_nombre) * strlen($primer_apellido));
                            $pass_sin_hash = $usuario->pass;
                            $usuario->setPassHash($pass_sin_hash);
                            $usuario->nombre = $model_com_persona->nombre;
                            $usuario->apellido = $model_com_persona->apellido;
                            $usuario->mail = $model->mail;
                            $usuario->externo = 0;
                            $usuario->dni = $model_com_persona->documento;
                            if (!$usuario->save(false)) {
                                $guardado = false;
                                $transaction->rollBack();
                                $model->addError("documento", "Error al generar Usuario.");
                            } else {
                                $idusuario = $usuario->idusuario;
                            }
                        }
                        if ($guardado) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_contacto', $model->idcontacto, $model->getAttributes());
                            if ($ban_persona == 1) { //aca la bandera se toma como que existe la persona por eso va a editar
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                            }
                            if ($idusuario > 0) { //aca la bandera dice que hay que crear el usuario //EZE: Cambio bandera por variable idusuario. El contacto no tiene idusuario, se queria recuperar del mismo y daba error.
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario', $idusuario, $usuario->getAttributes());
                            }
                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Nuevo Contacto",
                                'content' => '<span class="text-success">Creado Exitosamente! </span><br>' .
                                    ($model->crear_usuario ?
                                        '<span>Se generó el usuario: ' . $usuario->user . '<br> pass: ' . $pass_sin_hash . '</span>'
                                        : ''),
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),

                            ];
                        }
                    } else {
                        $model->addError("documento", print_r($model->getErrors(), true));
                    }
                }
            }
            return [
                'title' => "Nuevo Contacto",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar']),
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_contacto', $model->idcontacto, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idcontacto]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionGet_contacto($id)
    {
        $contacto = Mds_org_contacto::findOne($id);
        $persona = Sds_com_persona::findOne($contacto->idpersona);
        $persona->nombre = ($persona->nombre);
        $persona->apellido = ($persona->apellido);
        $nombre_sin_tildes = Mds_org_contacto::eliminar_tildes($persona->nombre);
        $apellido_sin_tildes = Mds_org_contacto::eliminar_tildes($persona->apellido);
        $primer_nombre = strtolower(strpos($nombre_sin_tildes, ' ') > 0 ?
            substr($nombre_sin_tildes, 0, strpos($nombre_sin_tildes, ' ')) :
            $nombre_sin_tildes);
        $primer_apellido = strtolower(strpos($apellido_sin_tildes, ' ') > 0 ?
            substr($apellido_sin_tildes, 0, strpos($apellido_sin_tildes, ' ')) :
            $apellido_sin_tildes);
        $user = $primer_nombre . '.' . $primer_apellido;
        $pass = substr($primer_nombre, 0, 2) . substr($primer_apellido, 0, 2) . (strlen($primer_nombre) * strlen($primer_apellido));
        $nombre = $persona->nombre;
        $apellido = $persona->apellido;
        $mail = $contacto->mail;
        $result = array(
            "user" => $user,
            "pass" => $pass,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "mail" => $mail,
        );
        return json_encode($result);
    }

    public function actionGet_id_contacto_por_legajo($legajo)
    {
        $contacto = Mds_org_contacto::findBySql("SELECT * FROM mds_org_contacto WHERE legajo = $legajo")->one();
        $idcontacto = 0;
        if ($contacto != null) {
            $idcontacto = $contacto->idcontacto;
        }
        return $idcontacto;
    }

    public function actionCreate_ext()
    {
        $request = Yii::$app->request;
        $model = new Mds_org_contacto();
        $request = Yii::$app->request;
        if ($model->load($request->post())) {
            if ($model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_contacto', $model->idcontacto, $model->getAttributes());
                return $model->idcontacto;
            } else {
                return $this->renderAjax('//mds_org_contacto/create', [
                    'model' => $model,
                    'botones' => true,
                ]);
            }
        } else {
            return $this->renderAjax('//mds_org_contacto/create', [
                'model' => $model,
                'botones' => true,
            ]);
        }
    }

    /**
     * Updates an existing Mds_org_contacto model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $organigrama = 0)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->idorganismo = Mds_org_dispositivo::findOne($model->iddispositivo)->idorganismo;
        $persona = Sds_com_persona::findOne($model->idpersona);
        if ($persona != null) {
            $model->documento = $persona->documento;
            $model->nombre = $persona->nombre;
            $model->apellido = $persona->apellido;
            $model->sexo = $persona->genero;
            $model->nacionalidad = $persona->nacionalidad;
            $model->fecha_nacimiento = $persona->fecha_nacimiento;
        }
        $model->crear_usuario = false;
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => "Actualizar Contacto #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'organigrama' => $organigrama,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar']),
                ];
            } elseif ($model->load($request->post())) {
                $model_com_persona = new Sds_com_persona();
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $ban_persona = 0;
                $idusuario = 0;

                if ($model->idpersona > 0) {
                    $ban_persona = 1;
                    $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                }
                $model_com_persona->documento_tipo = Sds_com_persona::TIPO_DNI;
                $model_com_persona->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                $model_com_persona->documento = $model->documento;
                $model_com_persona->nacionalidad = $model->nacionalidad;
                $model_com_persona->genero = $model->sexo;
                $model_com_persona->nombre = $model->nombre;
                $model_com_persona->apellido = $model->apellido;
                $model_com_persona->conviviente = 0;
                if (!$model_com_persona->save()) {
                    $guardado = false;
                    $transaction->rollBack();
                    $model->addError("documento", "Error al guardar Persona");
                } else {
                    $model->idpersona = $model_com_persona->idpersona;
                    if (!$model->activo) {
                        $model->iddispositivo = Mds_org_dispositivo::INACTIVOS;
                    }
                    $model->eventual = $model->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES ? 1 : 0;
                    $model->planta_politica = $model->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA ? 1 : 0;
                    if ($model->fecha_ingreso_planta != '') {
                        $ingresoPlanta = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_ingreso_planta)));
                    } else {
                        $ingresoPlanta = null;
                    }
                    $model->fecha_ingreso_planta = $ingresoPlanta;
                    if ($model->fecha_ingreso != '') {
                        $ingreso = date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_ingreso)));
                    } else {
                        $ingreso = null;
                    }
                    $model->fecha_ingreso = $ingreso;
                    if ($guardado && $model->save()) {
                        if ($model->crear_usuario) {
                            $usuario = new Mds_seg_usuario();
                            $usuario->idcontacto = $model->idcontacto;
                            $usuario->activo = true;
                            $primer_nombre = strtolower(strpos($model_com_persona->nombre, ' ') > 0 ? substr($model_com_persona->nombre, 0, strpos($model_com_persona->nombre, ' ')) : $model_com_persona->nombre);
                            $primer_apellido = strtolower(strpos($model_com_persona->apellido, ' ') > 0 ? substr($model_com_persona->apellido, 0, strpos($model_com_persona->apellido, ' ')) : $model_com_persona->apellido);
                            $usuario->user = $primer_nombre . '.' . $primer_apellido;
                            $existente = Mds_seg_usuario::findByUsername($usuario->user);
                            if ($existente != null) {
                                $guardado = false;
                                $transaction->rollBack();
                                $model->addError("documento", "No se puede crear un usuario para el contacto. Ya hay uno existente");
                            } else {
                                $usuario->pass = substr($primer_nombre, 0, 2) . substr($primer_apellido, 0, 2) . (strlen($primer_nombre) * strlen($primer_apellido));
                                $usuario->nombre = $model_com_persona->nombre;
                                $usuario->apellido = $model_com_persona->apellido;
                                $usuario->mail = $model->mail;
                                $usuario->externo = 0;
                                $usuario->dni = $model_com_persona->documento;
                                if (!$usuario->save(false)) {
                                    $guardado = false;
                                    $transaction->rollBack();
                                    $model->addError("documento", "Error al guardar usuario para el contacto");
                                } else {
                                    $idusuario = $usuario->idusuario;
                                }
                            }
                        }
                        if ($guardado) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_contacto', $model->idcontacto, $model->getAttributes());
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_contacto', $model->idcontacto, $model->getAttributes());

                            if ($ban_persona == 1) { //aca la bandera se toma como que existe la persona por eso va a editar
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                            }

                            if ($idusuario > 0) { //aca la bandera dice que hay que crear el usuario
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario', $idusuario, $usuario->getAttributes());
                            }
                            if ($organigrama == 0) {
                                if ($model->crear_usuario) {
                                    return [
                                        'forceReload' => '#crud-datatable-pjax',
                                        'title' => "Nuevo Contacto",
                                        'content' => '<span class="text-success">Creado Exitosamente! </span><br>' .
                                            '<span>Se generó el usuario: ' . $usuario->user . '<br> pass: ' . $usuario->pass . '</span>',
                                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),

                                    ];
                                } else {
                                    return [
                                        'forceReload' => '#crud-datatable-pjax',
                                        'title' => "Contacto #" . $id,
                                        'content' => $this->renderAjax('view', ['model' => $model]),
                                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                                    ];
                                }
                            } else {
                                return [
                                    'title' => "Actualizar Contacto",
                                    'content' => '<span class="text-success">Datos Actualizados!</span>',
                                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                                ];
                            }
                        }
                    } else {
                        $model->addError("documento", print_r($model->getErrors(), true));
                    }
                }
            }
            return [
                'title' => "Actualizar Contacto #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'organigrama' => $organigrama,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar']),
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_contacto', $model->idcontacto, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idcontacto]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_org_contacto model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $contacto = $this->findModel($id);
        $usuario = Mds_seg_usuario::find()->where(["idcontacto" => $contacto->idcontacto])->one();
        if ($usuario != null) {
            $usuario->delete();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_usuario', $usuario->idusuario, $contacto->getAttributes());
        }
        if ($contacto->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_org_contacto', $id, $contacto->getAttributes());
        }
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceReload' => '#crud-datatable-pjax',
                'title' => "Contacto Eliminado",
                'content' => '<span class="text-success">Eliminado Exitosamente! </span><br>' .
                    ($usuario != null ? '<span>Se eliminó además el usuario vinculado <b>' . $usuario->user . '</b>' : ''),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Mds_org_contacto model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionBulkDelete()
    // {
    //     $request = Yii::$app->request;
    //     $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
    //     foreach ($pks as $pk) {
    //         $model = $this->findModel($pk);
    //         $model->delete();
    //     }

    //     if ($request->isAjax) {
    //         /* Process for ajax request */
    //         Yii::$app->response->format = Response::FORMAT_JSON;
    //         return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    //     } else {
    //         /* Process for non-ajax request */
    //         return $this->redirect(['index']);
    //     }
    // }

    /**
     * Finds the Mds_org_contacto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_contacto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_org_contacto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCmb_contacto($legajo = "", $rotativo = "0")
    {
        $contactos = Mds_org_contacto::findBySql("select * from mds_org_contacto c
        join sds_com_persona p on p.idpersona=c.idpersona
        where legajo is not null and activo
        and legajo like '%" . $legajo . "%' and rotativo=" . $rotativo . "
        order by apellido,nombre")->all();
        $cmbContactos = "";
        if (sizeof($contactos) > 0) {
            foreach ($contactos as $contacto) {
                $cmbContactos = $cmbContactos .
                    "<option value='" . $contacto->idcontacto . "'>" . ($contacto->legajo != null ? $contacto->legajo : "00000") . " - " . $contacto->nombre . " " . $contacto->apellido . "</option>";
            }
        } else {
            $cmbContactos = "<option value=null></option>";
        }
        return $cmbContactos;
    }

    public function actionGet_documentos($idcontacto, $idtipo)
    {
        $contacto = Mds_org_contacto::findOne($idcontacto);
        $persona = Sds_com_persona::findOne($contacto->idpersona);
        $sql = "SELECT doc.* FROM mds_org_documento doc
        INNER JOIN sds_com_configuracion conf on doc.tipo = conf.idconfiguracion
        WHERE idcontacto = $idcontacto and doc.tipo = $idtipo
        order by conf.descripcion, doc.nombre";

        $documentos = Mds_org_documento::findBySql($sql)->all();
        $html_result = "";
        if ($documentos) {
            $html_result = "<div class='row'>
                                <div class='col-md-12'>
                                    <ul class='list-group list-group-flush'>";
            $html_filas = "";
            foreach ($documentos as $documento) {
                $ruta = '@web/' . $documento['path'];
                $html_filas = $html_filas . "<li class=\"list-group-item\">" .
                    Html::a($documento['nombre'], Url::to($ruta, true), ['target' => '_blank'])
                    . "</li>";
            }
            $html_result = $html_result . $html_filas .
                "</ul>
                </div>
            </div>";
        } else {
            $html_result = "<div class='row'>
                                <div class='col-md-12' style='padding-top: 15px;'>
                                    <b>El contacto no tiene documentos cargados</b>
                                </div>
                            </div>";
        }

        return $html_result;
    }

    public function actionReporte_credencial($idcontacto)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto/reporte_credencial', $idcontacto, array());
        if (Yii::$app->user->identity && Yii::$app->user->identity->idcontacto) {
            $permissionCrud = $this->getPermissionsCrud(Mds_seg_item::MODULO_ORG_CONTACTOS);
            if (!$permissionCrud || !$permissionCrud['permissionRead']) {
                // No tiene item y solo puede imprimir su contacto
                $idcontacto = Yii::$app->user->identity->idcontacto;
            }

            $content = $this->renderPartial('reporte_credencial', ['idcontacto' => $idcontacto]); // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'methods' => [
                    'SetTitle' => 'CREDENCIAL PDF',
                    'SetHeader' => null,
                    'SetFooter' => null,
                ],
            ]);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto/reporte_credencial', $idcontacto, array(['success' => true]));
            return $pdf->render();
        } else {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto/reporte_credencial', $idcontacto, array(['success' => false]));
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function getPermissionsCrud($iditem)
    {
        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $permisos = [];
        $idusuario = Yii::$app->user->identity->idusuario;

        if ($iditem) {
            $permisos = Mds_seg_permiso::findBySql(
                "SELECT *
                FROM mds_seg_permiso
                WHERE idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario)
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

        $response = [
            'permissionCreate' => $permissionCreate,
            'permissionRead' => $permissionRead,
            'permissionUpdate' => $permissionUpdate,
            'permissionDelete' => $permissionDelete,
        ];
        return $response;
    }

    public function actionFoto_dni($idcontacto)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($idcontacto);
        $persona = Sds_com_persona::findOne($model->idpersona);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto/foto_dni', $idcontacto, array());
        if ($persona != null) {
            $model->documento = $persona->documento;
            $model->nombre = $persona->nombre;
            $model->apellido = $persona->apellido;
            $model->sexo = $persona->genero;
            $model->nacionalidad = $persona->nacionalidad;
            $model->fecha_nacimiento = $persona->fecha_nacimiento;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!$request->isGet && $model->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            //Crear y Guardar documento persona
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                    'model' => $model,
                ]);
            }
            $user = Yii::$app->user->identity;
            $ruta = 'uploads/contactos/' . $model->legajo . '_' . $model->apellido . '_' . $model->nombre;
            $image_parts = explode(";base64,", $model->foto_dni);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $nombre = "1472_foto_dni." . $image_type;
            $path = $ruta . '/' . $nombre;
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            file_put_contents($path, $image_base64);
            $documento = Mds_org_documento::find()->where("tipo=1472 and idcontacto=" . $model->idcontacto)->one();
            $ban_documento = 1;
            if ($documento == null) {
                $ban_documento = 0;
                $documento = new Mds_org_documento();
            }
            $documento->idusuario = $user->idusuario;
            $documento->tipo = 1472;
            $documento->nombre = $nombre;
            $documento->fecha = date('Y-m-d');
            $documento->path = $path;
            $documento->detalle = "Foto de DNI Generada";
            $documento->idcontacto = $model->idcontacto;
            $documento->save(false);
            if (!$model->save()) {
                $transaction->rollBack();
                $model->addError("documento", "Error al guardar Persona");
            } else {
                //Devuelve exito
                $transaction->commit();
                if ($ban_documento == 1) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_documento', $documento->iddocumento, $model->getAttributes());
                } else {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_documento', $documento->iddocumento, $model->getAttributes());
                }
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Nuevo Contacto",
                    'content' => '<span class="text-success">Creado Exitosamente! </span><br>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),

                ];
            }
        }
        return [
            'title' => "Foto DNI Contacto #" . $idcontacto,
            'content' => $this->renderAjax('foto_dni', [
                'model' => $model,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                Html::button('Confirmar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar']),
        ];
    }

    public function actionDomicilio($idcontacto)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($idcontacto);
        $persona = Sds_com_persona::findOne($model->idpersona);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_contacto/domicilio', $idcontacto, array());
        if ($persona != null) {
            $model->documento = $persona->documento;
            $model->nombre = $persona->nombre;
            $model->apellido = $persona->apellido;
            $model->sexo = $persona->genero;
            $model->nacionalidad = $persona->nacionalidad;
            $model->fecha_nacimiento = $persona->fecha_nacimiento;
            $model->calle = $persona->domicilio_calle;
            $model->numero = $persona->domicilio_numero;
            $model->idlocalidad = $persona->idlocalidad;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!$request->isGet && $model->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $persona = Sds_com_persona::findOne($model->idpersona);
            $persona->domicilio_calle = $model->calle;
            $persona->domicilio_numero = $model->numero;
            $persona->idlocalidad = $model->idlocalidad;
            $model_localidad = Sds_com_localidad::findOne($model->idlocalidad);
            if ($model_localidad->codigo_postal == "00000000") {
                $model_localidad->codigo_postal = $model->codigo_postal;
            }
            $model_localidad->save();
            if (!$persona->save()) {
                $transaction->rollBack();
                $model->addError("documento", "Error al guardar Domicilio");
            } else {
                //Devuelve exito
                $transaction->commit();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_contacto/domicilio', $model->idcontacto, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Domicilio Contacto",
                    'content' => '<span class="text-success">Datos Actualizados Exitosamente! </span><br>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                ];
            }
        }
        return [
            'title' => "Datos Domicilio Contacto #" . $idcontacto,
            'content' => $this->renderAjax('_form_domicilio', [
                'model' => $model,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                Html::button('Confirmar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar']),
        ];
    }

    public function actionCertificacion_laboral($idcontacto = null)
    {
        if (is_null($idcontacto)) {
            $content = '<b>Debe proporcionar el identificador del usuario.</b>';
        } else {
            $contacto = Mds_org_contacto::findOne($idcontacto);
            if (!is_null($contacto)) {
                $persona = Sds_com_persona::findOne($contacto->idpersona);
                if ($contacto->activo) {
                    if ($contacto->legajo > 0) {
                        if (is_null($contacto->fecha_ingreso)) {
                            $content = $this->renderPartial('certificacion_laboral', [
                                'contacto' => $contacto,
                                'persona' => $persona,
                                'error' => '<i><b>NO</b></i> registra fecha de ingreso',
                            ]);
                        } else {
                            $categoria = Sds_com_configuracion::findOne($contacto->categoria);
                            $dispositivo = Mds_org_dispositivo::findOne($contacto->iddispositivo);
                            $edificio = Sds_gis_capa_item::findOne($dispositivo->idcapaitem);
                            $query = new \yii\db\Query();
                            $query->select(['datediff(curdate(), fecha_ingreso) dias'])
                                ->from(['mds_org_contacto'])
                                ->where('idcontacto=' . $idcontacto);
                            $command = $query->createCommand();
                            $antiguedad = $command->queryOne();
                            $content = $this->renderPartial('certificacion_laboral', [
                                'contacto' => $contacto,
                                'persona' => $persona,
                                'categoria' => $categoria->descripcion,
                                'dispositivo' => $dispositivo->descripcion,
                                'edificio' => $edificio,
                                'antiguedad' => $antiguedad,
                            ]);
                        }
                    } else {
                        $content = $this->renderPartial('certificacion_laboral', [
                            'contacto' => $contacto,
                            'persona' => $persona,
                            'error' => '<i><b>NO</b></i> posee legajo',
                        ]);
                    }
                } else {
                    $content = $this->renderPartial('certificacion_laboral', [
                        'contacto' => $contacto,
                        'persona' => $persona,
                        'error' => '<i><b>NO</b></i> es un empleado activo',
                    ]);
                }
            } else {
                $content = $this->renderPartial('certificacion_laboral', [
                    'error' => 'Error al cargar los datos de contacto. Por favor intente nuevamente.',
                ]);
            }
        }
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Certificación Laboral',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);
        return $pdf->render();
    }

    public function actionPase_planta()
    {
        $model = new Mds_org_contacto();
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Importar Excel de Pase a Planta Permanente",
                    'content' => $this->renderAjax('planta_permanente', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Importar', ['class' => 'btn btn-primary', 'id' => 'btn_importar']),
                ];
            } else {
                $data_post = Yii::$app->request->post();
                if ($data_post != null) {
                    $fecha_array = explode("/", $data_post['fecha_ingreso']);
                    $fecha_ingreso = $fecha_array[2] . '-' . $fecha_array[1] . '-' . $fecha_array[0];
                    $registros = $data_post['registros'];
                    $registros = json_decode($registros);
                    $cant_guardados = 0;
                    $errores = array();
                    $transaction = Yii::$app->db->beginTransaction();
                    $i = 2;
                    foreach ($registros as $registro) {
                        if (is_numeric($registro->legajo)) {
                            $contacto = Mds_org_contacto::find()->where('legajo=' . $registro->legajo)->one();
                            if ($contacto != null) {
                                if ($contacto->updateAttributes(['fecha_ingreso_planta' => $fecha_ingreso])) {
                                    $cant_guardados++;
                                } else {
                                    array_push($errores, $contacto->getErrors());
                                    array_push($errores, 'Error al intentar guardar la fila ' . $i . ' (legajo n° ' . $contacto->legajo . '). ');
                                }
                            } else {
                                array_push($errores, 'El legajo ' . $registro->legajo . ' no tiene contacto asignado.');
                            }
                        } else {
                            array_push($errores, '¡Los datos de la fila ' . $i . ' no tienen el formato esperado!. Por favor intente nuevamente.');
                        }
                        $i++;
                    }
                    if ($cant_guardados > 0) {
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }
                    return array('guardados' => $cant_guardados, 'errores' => $errores);
                }
                return [
                    'title' => "Importar Excel de Pase a Planta Permanente",
                    'content' => $this->renderAjax('planta_permanente', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Confirmar', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btnGuardar']),
                ];
            }
        } else {
            return $this->render('planta_permanente', [
                'model' => $model,
            ]);
        }
    }

    public static function actionGet_cmb_contacto($form, $model, $atributo, $id_combo, $label = null, $disabled = false)
    {
        /* Esta funcion crea el combo de contactos usando una sola linea desde donde sea invocada,
        se pasan como parametros el form y model que se este usando, el atributo del modelo para el que 
        se quiere usar el combo, el id del combo para usar con javascrip, y opcional el label. */
        return $form->field($model, $atributo)->widget(Select2::classname(), [
            'data' => ArrayHelper::map(
                Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                                        join sds_com_persona p on p.idpersona=c.idpersona 
                                        order by trim(p.nombre), trim(p.apellido)")->all(),
                'idcontacto',
                function ($model) {
                    return $model->nombre . " " . $model->apellido . " - " . $model->legajo;
                }
            ),
            'options' => ['placeholder' => '...', 'id' => $id_combo, 'disabled' => $disabled],
            'pluginOptions' => [
                'allowClear' => true
            ],

        ])->label($label);
    }
}
