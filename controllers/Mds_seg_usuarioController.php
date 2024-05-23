<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use Yii;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_entrega_tipo;
use app\models\Mds_seg_usuario_responsable;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_usuarioSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use app\models\Mds_certificacion_direccion;
use app\models\Mds_certificacion_direccion_usuario;
use app\models\Mds_seg_usuario_status;
use yii\helpers\ArrayHelper;

/**
 * Mds_seg_usuarioController implements the CRUD actions for Mds_seg_usuario model.
 */
class Mds_seg_usuarioController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'view', 'logout',
                    'index_cuenta', 'create2', 'update_resp', 'update_pass', 'blanqueo', 'pass_hash'
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_SEG_SEGURIDAD,
                        ],
                    ],
                    [
                        'actions' => ['index_cuenta', 'create2', 'update_resp', 'update_pass', 'blanqueo', 'pass_hash'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_seg_usuario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_seg_usuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_usuario', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_seg_usuario model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_usuario', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Usuario #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    public function actionIndex_cuenta($id, $id_rum_persona) // aca se recupera el id de mds_seg_usuario
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->externo > 0) {
            $model->is_externo = true;
        }
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Datos de la cuenta",
                    'content' => $this->renderAjax('index_cuenta', [
                        'model' => $model, 'id_rum_persona' => $id_rum_persona
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post())) {
                $guardado = $model->save();
                if ($guardado) {
                    return [
                        'forceReload' => '#crud-datatable-usuarios',
                        'title' => "Ver Datos de la cuenta ",
                        'content' => $this->renderAjax('view_cuenta', [
                            'model' => $model, 'id_rum_persona' => $id_rum_persona
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['index_cuenta', 'id' => $id, 'id_rum_persona' => $id_rum_persona], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            return [
                'title' => "Datos de la Cuenta3 " . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idusuario]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    /**
     * Creates a new Mds_seg_usuario model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_seg_usuario();
        $model->activo = true;

        //direcciones que ya tienen un programa asignado en mds_Certificacion_programa
        $listDireccionesCertificaciones = $this->getListDireccionesCertificaciones();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Usuario",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->externo == null) {
                    $model->externo = 0;
                }
                if ($model->idcontacto != null) {
                    $contacto = Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                    join sds_com_persona p on p.idpersona=c.idpersona
                    where c.idcontacto=" . $model->idcontacto)->one();
                    $model->dni = $contacto != null ? $contacto->documento : 0;
                }
                //Encriptamiento de pass:
                $pass_sin_hash = $model->pass;
                $model->setPassHash($model->pass);
                $guardado = $model->save();
                if ($guardado) {
                    $roles = $model->roles != null ? $model->roles : array();
                    $roles_count = count($roles);
                    for ($index_rol = 0; $index_rol < $roles_count; $index_rol++) {
                        $usuario_rol = new Mds_seg_usuario_rol();
                        $usuario_rol->idusuario = $model->idusuario;
                        $usuario_rol->idrol = $roles[$index_rol];
                        if (!$usuario_rol->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_rol', $usuario_rol->idusuario, $usuario_rol->getAttributes());
                        }
                    }
                }
                if ($guardado) {
                    $tipos_entrega = $model->tipos_entrega != null ? $model->tipos_entrega : array();
                    $tipos_entrega_count = count($tipos_entrega);
                    for ($index_tipo_ent = 0; $index_tipo_ent < $tipos_entrega_count; $index_tipo_ent++) {
                        $usuario_ent_tipo = new Mds_seg_usuario_entrega_tipo();
                        $usuario_ent_tipo->idusuario = $model->idusuario;
                        $usuario_ent_tipo->idtipo = $tipos_entrega[$index_tipo_ent];
                        if (!$usuario_ent_tipo->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_entrega_tipo', $usuario_ent_tipo->idusuario, $usuario_ent_tipo->getAttributes());
                        }
                    }
                }
                if ($guardado && !$model->responsable_todos) {
                    $responsables_entrega = $model->responsables != null ? $model->responsables : array();
                    $responsables_count = count($responsables_entrega);
                    for ($index_resp = 0; $index_resp < $responsables_count; $index_resp++) {
                        $usuario_resp = new Mds_seg_usuario_responsable();
                        $usuario_resp->idusuario = $model->idusuario;
                        $usuario_resp->idresponsable = $responsables_entrega[$index_resp];
                        if (!$usuario_resp->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_responsable', $usuario_resp->idusuario, $usuario_resp->getAttributes());
                        }
                    }
                }
                if ($guardado) {
                    $direccionesCertificaciones = $model->direccionesCertificaciones != null ? $model->direccionesCertificaciones : array();
                    $direcciones_count = count($direccionesCertificaciones);
                    for ($index_direccion = 0; $index_direccion < $direcciones_count; $index_direccion++) {
                        $direccionUsuario = new Mds_certificacion_direccion_usuario();
                        $direccionUsuario->idusuario = $model->idusuario;
                        $direccionUsuario->idcertificaciondireccion = $direccionesCertificaciones[$index_direccion];
                        $direccionUsuario->created_at = date('Y-m-d H:i:s');
                        if (!$direccionUsuario->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificaciones_direccion_usuario', $direccionUsuario->idusuario, $direccionUsuario->getAttributes());
                        }
                    }
                }
                if ($guardado) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario', $model->idusuario, $model->getAttributes());
                    return [
                        'title' => "Usuario Creado",
                        'content' => '<span class="text-success">Creado Exitosamente! </span><br>' .
                            '<span>Se generó el usuario: ' . $model->user . '<br> pass: ' . $pass_sin_hash . '</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Nuevo Usuario",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idusuario]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                ]);
            }
        }
    }

    //INICIO CODIGO PARA RUMBO
    public function actionCreate2($id_empresa, $nombre_empresa)
    {
        $request = Yii::$app->request;
        $model = new Mds_seg_usuario();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                $titulo = "RUMBO:: Nuevo Usuario Asociado a la empresa:<br>" . $nombre_empresa;
                return [
                    'title' => $titulo,
                    'content' => $this->renderAjax('create2', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['mds_rum_empleador/update', 'id' => $id_empresa],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            } else if ($model->load($request->post())) {

                $transaction = Yii::$app->db->beginTransaction();
                //$roles = $model->roles != null ? $model->roles : array();
                //$roles_count = count($roles);
                //antes de guardar hay que verificar si el usuario ya no existe                               

                $seg_user_buscado = Mds_seg_usuario::find()
                    ->where(['user' => $model->user])
                    ->one();
                $ban_usuario_rol = 0;

                if ($seg_user_buscado == null)  // no existe user, se puede crear
                {
                    $pass_sin_hash = $model->pass;
                    $model->setPassHash($model->pass);
                    $guardado = $model->save();
                    if ($guardado) {

                        $usuario_rol = new Mds_seg_usuario_rol();
                        $usuario_rol->idusuario = $model->idusuario;
                        $usuario_rol->idrol = 38;
                        $usuario_rol->save();
                        $ban_usuario_rol = 1;
                        /*for ($index_rol = 0; $index_rol < $roles_count; $index_rol++) {
                            $usuario_rol = new Mds_seg_usuario_rol();
                            $usuario_rol->idusuario = $model->idusuario;
                            $usuario_rol->idrol = $roles[$index_rol];
                            if (!$usuario_rol->save()) {
                                $transaction->rollBack();
                                $guardado = false;
                            }
                        }*/
                    }
                    if ($guardado) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario', $model->idusuario, $model->getAttributes());
                        if ($ban_usuario_rol == 1) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_rol', $model->idusuario, $usuario_rol->getAttributes());
                        }
                        return [
                            'forceReload' => '#crud-datatable-usuarios',
                            'title' => "Usuario Creado",
                            'content' => 'Guardado exitosamente!<br>Recuerde Seleccionar el usuario Recien creado<br>y luego guarde los cambios de la empresa.<br>' .
                                '<span>Se generó el usuario: ' . $model->user . '<br> pass: ' . $pass_sin_hash . '</span>',
                            'footer' => Html::a(
                                ' Volver',
                                ['mds_rum_empleador/update', 'id' => $id_empresa],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            )


                        ];
                    } else {
                        $transaction->rollBack();
                    }
                } else // el user existe, no hay que crearlo
                {
                    return [
                        'title' => "Atención",
                        'content' => 'El nombre de usuario que quiere registrar ya existe!<br>Por favor, ingrese otro nombre de Usuario',
                        'footer' => Html::a(
                            ' Aceptar',
                            ['mds_seg_usuario/create2', 'id_empresa' => $id_empresa, 'nombre_empresa' => $nombre_empresa],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        )


                    ];
                }
            }
            return [
                'title' => "Nuevo Usuario",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario', $model->idusuario, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idusuario]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }
    //FIN CODIGO PARA RUMBO

    /**
     * Updates an existing Mds_seg_usuario model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->externo > 0) {
            $model->is_externo = true;
        }
        $roles_borrar = Mds_seg_usuario_rol::find()->where(["idusuario" => $model->idusuario])->all();
        $tipos_ent_borrar = Mds_seg_usuario_entrega_tipo::find()->where(["idusuario" => $model->idusuario])->all();
        $responsables_borrar = Mds_seg_usuario_responsable::find()->where(["idusuario" => $model->idusuario])->all();
        $direccionesCertificaciones_borrar = Mds_certificacion_direccion_usuario::find()->where(["idusuario" => $model->idusuario])->all();

        $listDireccionesCertificaciones = $this->getListDireccionesCertificaciones();

        $direccionesUsuario = Mds_certificacion_direccion_usuario::find()->select('idcertificaciondireccion')->where(['idusuario' => $model->idusuario, 'deleted_at' => null])->asArray()->all();
        $direcciones_id = array();
        foreach ($direccionesUsuario as $direccion) {
            $direcciones_id[] = $direccion['idcertificaciondireccion'];
        }
        $model->direccionesCertificaciones = $direcciones_id;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Usuario #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($roles_borrar != null) {
                    foreach ($roles_borrar as $rol) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_usuario_rol', $rol->idusuario, $rol->getAttributes());
                        $rol->delete();
                    }
                }
                if ($tipos_ent_borrar != null) {
                    foreach ($tipos_ent_borrar as $tipo_ent) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_usuario_entrega_tipo', $tipo_ent->idusuario, $tipo_ent->getAttributes());
                        $tipo_ent->delete();
                    }
                }
                if ($responsables_borrar != null) {
                    foreach ($responsables_borrar as $resp) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_usuario_responsable', $resp->idusuario, $resp->getAttributes());
                        $resp->delete();
                    }
                }
                if ($model->idcontacto != null) {
                    $contacto = Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                    join sds_com_persona p on p.idpersona=c.idpersona
                    where c.idcontacto=" . $model->idcontacto)->one();
                    $model->dni = $contacto != null ? $contacto->documento : 0;
                }
                if ($direccionesCertificaciones_borrar != null) {
                    foreach ($direccionesCertificaciones_borrar as $direccion) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_direccion_usuario', $direccion->idusuario, $direccion->getAttributes());
                        $direccion->deleted_at = date('Y-m-d H:i:s');
                        $direccion->save();
                    }
                }

                $guardado = $model->save();
                if ($guardado) {
                    $roles = $model->roles != null ? $model->roles : array();
                    $roles_count = count($roles);
                    for ($index_rol = 0; $index_rol < $roles_count; $index_rol++) {
                        $usuario_rol = new Mds_seg_usuario_rol();
                        $usuario_rol->idusuario = $model->idusuario;
                        $usuario_rol->idrol = $roles[$index_rol];
                        if (!$usuario_rol->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_rol', $usuario_rol->idusuario, $usuario_rol->getAttributes());
                        }
                    }
                }
                if ($guardado) {
                    $tipos_entrega = $model->tipos_entrega != null ? $model->tipos_entrega : array();
                    $tipos_entrega_count = count($tipos_entrega);
                    for ($index_tipo_ent = 0; $index_tipo_ent < $tipos_entrega_count; $index_tipo_ent++) {
                        $usuario_ent_tipo = new Mds_seg_usuario_entrega_tipo();
                        $usuario_ent_tipo->idusuario = $model->idusuario;
                        $usuario_ent_tipo->idtipo = $tipos_entrega[$index_tipo_ent];
                        if (!$usuario_ent_tipo->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_entrega_tipo', $usuario_ent_tipo->idusuario, $usuario_rol->getAttributes());
                        }
                    }
                }
                if ($guardado && !$model->responsable_todos) {
                    $responsables_entrega = $model->responsables != null ? $model->responsables : array();
                    $responsables_count = count($responsables_entrega);
                    for ($index_resp = 0; $index_resp < $responsables_count; $index_resp++) {
                        $usuario_resp = new Mds_seg_usuario_responsable();
                        $usuario_resp->idusuario = $model->idusuario;
                        $usuario_resp->idresponsable = $responsables_entrega[$index_resp];
                        if (!$usuario_resp->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_usuario_responsable', $usuario_resp->idusuario, $usuario_resp->getAttributes());
                        }
                    }
                }
                if ($guardado) {
                    $direccionesCertificaciones = $model->direccionesCertificaciones != null ? $model->direccionesCertificaciones : array();
                    $direcciones_count = count($direccionesCertificaciones);
                    for ($index_direccion = 0; $index_direccion < $direcciones_count; $index_direccion++) {
                        $direccionUsuario = new Mds_certificacion_direccion_usuario();
                        $direccionUsuario->idusuario = $model->idusuario;
                        $direccionUsuario->idcertificaciondireccion = $direccionesCertificaciones[$index_direccion];
                        $direccionUsuario->created_at = date('Y-m-d H:i:s');
                        if (!$direccionUsuario->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificaciones_direccion_usuario', $direccionUsuario->idusuario, $direccionUsuario->getAttributes());
                        }
                    }
                }
                if ($guardado) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_usuario', $model->idusuario, $model->getAttributes());
                    return [
                        'title' => "Usuario #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                            'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Actualizar Usuario #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_usuario', $model->idusuario, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idusuario]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'listDireccionesCertificaciones' => $listDireccionesCertificaciones
                ]);
            }
        }
    }

    public function actionUpdate_resp($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Modificar Responsable Usuario " . $model->user,
                    'content' => $this->renderAjax('update_resp', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Cambiar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post())) {
                if ($model->updateAttributes(['responsable'])) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_usuario/update_resp', $model->idusuario, $model->getAttributes());
                    return [
                        'title' => "Cambio de Responsable",
                        'content' => '<span class="text-success">El Responsable ha sido modificado exitosamente!</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Modificar Responsable Usuario " . $model->user,
                'content' => $this->renderAjax('update_resp', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Cambiar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                return $this->renderAjax('update_resp', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionUpdate_pass()
    {
        $request = Yii::$app->request;
        $model = Yii::$app->user->identity;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            $pass_ant = $model->pass;
            $model = Mds_seg_usuario::findOne($model->idusuario);
            if (!$request->isGet && $model->load($request->post())) {
                $guardar = true;
                if ($model->pass_anterior == "") {
                    $model->addError('pass_anterior', 'Debe ingresar la contraseña actual');
                    $guardar = false;
                }
                $pass_ant_hash = $model->setPassHash($model->pass_anterior, true);
                if ($pass_ant_hash != $pass_ant) {
                    $model->addError('pass_anterior', 'La contraseña ingresada es incorrecta');
                    $guardar = false;
                }
                if ($model->pass == "") {
                    $model->addError('pass', 'Debe ingresar una contraseña');
                    $guardar = false;
                }
                if ($model->pass_nueva == "") {
                    $model->addError('pass_nueva', 'Debe ingresar la nueva contraseña');
                    $guardar = false;
                }
                if ($model->pass_nueva != $model->pass) {
                    $model->addError('pass_nueva', 'Ambas contraseñas deben coincidir');
                    $guardar = false;
                }
                if (!preg_match('/[^0-9]/', $model->pass_nueva) || !preg_match('/[0-9]/', $model->pass_nueva)) {
                    $model->addError('pass_nueva', 'La nueva contraseña debe contener caracteres y números');
                    $guardar = false;
                }
                /* return [
                    'title' => "Cambio de Contraseña",
                    'content' => '<span class="text-success">Se cambió correctamente!</span><br>' .
                        '<span>Usuario: ' . $model->user . '<br> Nueva Pass: ' . json_encode($numbers) . '</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ]; */
                if ($guardar) {
                    $pass_sin_hash = $model->pass_nueva;
                    $model->setPassHash($pass_sin_hash);
                    if ($model->updateAttributes(['pass' => $model->pass])) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_usuario/update_pass', $model->idusuario, $model->getAttributes());
                        $modelSegUsuarioStatus = new Mds_seg_usuario_status();
                        $modelSegUsuarioStatus->idusuario = $model->idusuario;
                        $modelSegUsuarioStatus->created_at = date('Y-m-d H:i:s');
                        $modelSegUsuarioStatus->idusuario_carga = $model->idusuario;
                        $modelSegUsuarioStatus->idestado = Mds_seg_usuario_status::ESTADO_CAMBIO_CLAVE;
                        $modelSegUsuarioStatus->save();
                        return [
                            'title' => "Cambio de Contraseña",
                            'content' => '<span class="text-success">Se cambió correctamente!</span><br>' .
                                '<span>Usuario: ' . $model->user . '<br> Nueva Pass: ' . $pass_sin_hash . '</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        return [
                            'title' => "Cambio de Contraseña",
                            'content' => '<span class="text-error">Error! No se pudo actualizar la contraseña!</span><br>' .
                                '<span>Usuario: ' . $model->user . '</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                }
            } else {
                $model->pass = "";
            }
            return [
                'title' => "Actualizar Contraseña usuario " . $model->user,
                'content' => $this->renderAjax('update_pass', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Cambiar Contraseña', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            /*  if ($model->load($request->post()) && $model->save()) { */
            return $this->goBack();
            /*  } else {
                return $this->renderAjax('update_pass', [
                    'model' => $model,
                ]);
            } */
        }
    }

    /**
     * Delete an existing Mds_seg_usuario model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_usuario', $id, $model->getAttributes());
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-usuarios'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Mds_seg_usuario model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
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

    public function actionBlanqueo($idusuario, $blanquear = false)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModel($idusuario);
            if ($blanquear) {
                $primer_nombre = Mds_org_contacto::eliminar_tildes(strtolower(strpos($model->nombre, ' ') > 0 ? substr($model->nombre, 0, strpos($model->nombre, ' ')) : $model->nombre));
                $primer_apellido = Mds_org_contacto::eliminar_tildes(strtolower(strpos($model->apellido, ' ') > 0 ? substr($model->apellido, 0, strpos($model->apellido, ' ')) : $model->apellido));
                $contacto = Mds_org_contacto::findOne($model->idcontacto);
                $legajo = $contacto->legajo;
                $model->pass = substr($primer_nombre, 0, 2) . $legajo . rand(10, 99) . substr($primer_apellido, 0, 2);
                $pass_sin_hash = $model->pass;
                $model->setPassHash($pass_sin_hash);
                $model->updateAttributes(['pass' => $model->pass, 'attemps' => 0, 'activo' => 1]);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_usuario/blanqueo', $model->idusuario, $model->getAttributes());

                $modelSegUsuarioStatus = new Mds_seg_usuario_status();
                $modelSegUsuarioStatus->idusuario = $model->idusuario;
                $modelSegUsuarioStatus->created_at = date('Y-m-d H:i:s');
                $modelSegUsuarioStatus->idusuario_carga = Yii::$app->user->identity->idusuario;
                $modelSegUsuarioStatus->idestado = Mds_seg_usuario_status::ESTADO_DESBLOQUEADO;
                $modelSegUsuarioStatus->save();

                return [
                    'title' => "Blanqueo de Contraseña",
                    'content' => '<span class="text-success">Completado Exitosamente! </span><br>' .
                        '<span>Usuario: ' . $model->user . '<br> Nueva Pass: ' . $pass_sin_hash . '</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                $url =  Url::to(['/mds_seg_usuario/blanqueo', 'idusuario' => $model->idusuario, 'blanquear' => true]);
                return [
                    'title' => "Blanqueo de Contraseña usuario " . $model->user,
                    'content' => '<span>Se blanqueará la contraseña del usuario. Desea continuar?</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::a('Confirmar Blanqueo', $url, [
                            'class' => 'btn btn-info',
                            'role' => 'modal-remote',
                            'title' => 'Blanquear Contraseña',
                            'data-toggle' => 'tooltip',
                            /* 'data' => [
                                    'confirm' => Yii::t('app', 'La contraseña del usuario '.$model->user.' será reiniciada. Desea continuar?'),
                                    'method' => 'post',
                                    'role' => 'modal-remote',
                                ] */
                        ])
                ];
            }
        }
    }

    public function actionPass_hash()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $usuarios = Mds_seg_usuario::find()->where("pass not like '%pbkdf2_sha256%'")->orderBy(["idusuario" => SORT_DESC])->limit(1000)->all();
        foreach ($usuarios as $usuario) {
            $usuario->setPassHash($usuario->pass);
            if ($usuario->updateAttributes(['pass' => $usuario->pass]) > 0) {
            }
        }

        return $usuarios;
    }

    /**
     * Finds the Mds_seg_usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_seg_usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_seg_usuario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getListDireccionesCertificaciones()
    {
        $direcciones = Mds_certificacion_direccion::find()
            ->select(['mds_certificacion_direccion.idcertificaciondireccion as idcertificaciondireccion', 'UPPER(sds_com_configuracion.descripcion) as descripcion'])
            ->innerJoin('mds_certificacion_programa', 'mds_certificacion_direccion.iddireccion=mds_certificacion_programa.iddireccion')
            ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
            ->groupBy('mds_certificacion_direccion.iddireccion')
            ->where(['mds_certificacion_direccion.deleted_at' => null, 'mds_certificacion_programa.deleted_at' => null])
            ->orderBy('sds_com_configuracion.descripcion')
            ->asArray()
            ->all();

        $listadoDireccionesCertificaciones = ArrayHelper::map($direcciones, 'idcertificaciondireccion', 'descripcion');
        return $listadoDireccionesCertificaciones;
    }
}
