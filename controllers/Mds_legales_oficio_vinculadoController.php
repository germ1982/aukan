<?php

namespace app\controllers;

use app\models\Mds_legales_oficio;
use app\models\Mds_legales_oficio_vinculado;
use app\models\Mds_sys_log;
use app\models\Mds_seg_item;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_legales_oficio_vinculadoSearch;
use app\models\Sds_com_persona;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;


class Mds_legales_oficio_vinculadoController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'store', 'view', 'reactivate'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'store', 'view', 'reactivate'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($idlegalesoficio)
    {
        if (Yii::$app->user && Yii::$app->user->identity && Yii::$app->user->identity->idcontacto && Yii::$app->request->isAjax) {
            $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

            $searchModel = new Mds_legales_oficio_vinculadoSearch();
            $searchModel->idlegalesoficio = $idlegalesoficio;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_CONSULTA,
                '/mds_legales_oficio_vinculado/index',
                $idlegalesoficio,
                []
            );
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => "Listado de personas vinculadas - requerimiento #$idlegalesoficio",
                'content' => $this->renderAjax('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'permissions' => $permissions,
                ]),
                'footer' => Html::button('Cerrar', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]),
            ];
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCreate($idlegalesoficio)
    {
        $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($permissions['hasRolAdminGeneral'] || $permissions['permissionCreate']) {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    $model = new Mds_legales_oficio_vinculado();
                    $listParentesco = $this->getListParentesco();
                    $listTipoDocumento = $this->getListTiposDocumentos();
                    $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
                    $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');

                    return [
                        'title' => "Vincular persona al requerimiento #$idlegalesoficio",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'listParentesco' => $listParentesco,
                            'listTipoDocumento' => $listTipoDocumento,
                            'idlegalesoficio' => $idlegalesoficio,
                            'tipoGenero' => $tipoGenero,
                            'tipoNacionalidad' => $tipoNacionalidad,
                        ]),
                        'footer' =>
                        Html::a(
                            'Volver',
                            ['index', 'idlegalesoficio' => $idlegalesoficio],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        )
                            . Html::button('Guardar', ['class' => 'btn btn-success', 'type' => 'submit', 'id' => 'boton-guardar-vincular-persona', 'disabled' => true])
                    ];
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionUpdate($id)
    {
        $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($permissions['hasRolAdminGeneral'] || $permissions['permissionUpdate']) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $listParentesco = $this->getListParentesco();
            $listTipoDocumento = $this->getListTiposDocumentos();
            $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
            $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Actualizar persona vinculada al requerimiento #$model->idlegalesoficio",
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                            'listParentesco' => $listParentesco,
                            'listTipoDocumento' => $listTipoDocumento,
                            'idlegalesoficio' => $model->idlegalesoficio,
                            'tipoGenero' => $tipoGenero,
                            'tipoNacionalidad' => $tipoNacionalidad,
                        ]),
                        'footer' =>
                        Html::a(
                            'Volver',
                            ['index', 'idlegalesoficio' => $model->idlegalesoficio],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        )
                            . Html::button('Guardar', ['class' => 'btn btn-success', 'type' => 'submit', 'id' => 'boton-guardar-vincular-persona', 'disabled' => true])
                    ];
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionView($id)
    {
        $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($permissions['hasRolAdminGeneral'] || $permissions['permissionRead']) {
            $request = Yii::$app->request;

            $model = $this->findModel($id);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_oficio_vinculado', $id, $model->getAttributes());
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'title' => "Ver persona #$model->idlegalesoficiovinculado vinculada al requerimiento #$model->idlegalesoficio",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        'Volver',
                        ['index', 'idlegalesoficio' => $model->idlegalesoficio],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    )
                ];
            } else {
                return $this->redirect(['/mds_legales_oficio_vinculado/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDelete($id)
    {
        $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($permissions['hasRolAdminGeneral'] || $permissions['permissionDelete']) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $searchModel = new Mds_legales_oficio_vinculadoSearch();
            $searchModel->idlegalesoficio = $model->idlegalesoficio;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $model->idusuario_borra = Yii::$app->user->id;
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->save();

            Yii::$app->response->format = Response::FORMAT_JSON;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_oficio_vinculado', $model->idlegalesoficiovinculado, $model->getAttributes());

            if ($model->documento || $model->persona->documento) {
                $externalApiRequest = new ExternalApiRequestController();
                $externalApiRequest->runneuIntervencionByModulo(array($model->documento ? $model->documento : $model->persona->documento), Mds_legales_oficio::RUNNEU_API_MODULO, $model->idlegalesoficio, 'delete', Mds_legales_oficio::RUNNEU_API_TIPO_REQUERIMIENTO);
            }

            if ($request->isAjax) {

                return [
                    'title' => "Listado de personas vinculadas - requerimiento #$model->idlegalesoficio",
                    'content' => $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'permissions' => $permissions,
                    ]),
                    'footer' => Html::button('Cerrar', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]),
                ];
            } else {
                Yii::$app->session->setFlash('success', " Se eliminó correctamente la persona.");
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id)
    {
        $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($permissions['hasRolAdminGeneral']) {
            $personaVinculada = Mds_legales_oficio_vinculado::findOne($id);
            if ($personaVinculada) {
                $request = Yii::$app->request;
                $searchModel = new Mds_legales_oficio_vinculadoSearch();
                $searchModel->idlegalesoficio = $personaVinculada->idlegalesoficio;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $personaVinculada->deleted_at = null;
                $personaVinculada->idusuario_borra = null;
                if ($personaVinculada->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente la persona vinculada.");

                    if ($personaVinculada->documento || $personaVinculada->persona->documento) {
                        $externalApiRequest = new ExternalApiRequestController();
                        $externalApiRequest->runneuIntervencionByModulo(array($personaVinculada->documento ? $personaVinculada->documento : $personaVinculada->persona->documento), Mds_legales_oficio::RUNNEU_API_MODULO, $personaVinculada->idlegalesoficio, 'reactivate', Mds_legales_oficio::RUNNEU_API_TIPO_REQUERIMIENTO);
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar la persona vinculada.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio_vinculado', $personaVinculada->idlegalesoficiovinculado, $personaVinculada->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "La persona vinculada no existe.");
            }

            if ($request->isAjax) {
                return [
                    'title' => "Listado de personas vinculadas - requerimiento #$personaVinculada->idlegalesoficio",
                    'content' => $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'permissions' => $permissions,
                    ]),
                    'footer' => Html::button('Cerrar', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]),
                ];
            } else {
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStore($idlegalesoficiovinculado, $idlegalesoficio)
    {
        $permissions = $this->getPermissionsCrud(Mds_seg_item::MODULO_LEGALES_VINCULAR_PERSONAS, Mds_legales_oficio::ID_ROLES_LEGALES, Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        if ($permissions['hasRolAdminGeneral'] || $permissions['permissionCreate']) {
            if ($idlegalesoficiovinculado) {
                $model = $this->findModel($idlegalesoficiovinculado);
            } else {
                $model = new Mds_legales_oficio_vinculado();
                $model->created_at = date('Y-m-d H:i:s');
                $model->idusuario_alta = Yii::$app->user->id;
                $model->idlegalesoficio = $idlegalesoficio;
            }
            if (Yii::$app->request->post()) {
                $documento = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['documento']) && Yii::$app->request->post()['mds_legales_oficio_vinculado']['documento'] ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['documento'] : null;
                $idPersona = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['idpersona']) && Yii::$app->request->post()['mds_legales_oficio_vinculado']['idpersona'] ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['idpersona'] : null;
                $genero = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['genero']) && Yii::$app->request->post()['mds_legales_oficio_vinculado']['genero'] ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['genero'] : null;
                $nacionalidad = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['nacionalidad']) && Yii::$app->request->post()['mds_legales_oficio_vinculado']['nacionalidad'] ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['nacionalidad'] : null;
                $fechaNacimiento = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['fecha_nacimiento']) && Yii::$app->request->post()['mds_legales_oficio_vinculado']['fecha_nacimiento'] ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['fecha_nacimiento'] : null;

                if ($idPersona || ($genero && $nacionalidad && $fechaNacimiento)) {
                    $model->idtipodocumento = null;
                    $model->documento = null;
                    $model->apellido = null;
                    $model->nombre = null;
                    $model->domicilio_calle = null;
                    $model->domicilio_numero = null;
                    $model->idparentesco = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['idparentesco']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['idparentesco'] : null;
                    $model->telefono = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['telefono']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['telefono'] : null;
                    $model->mail = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['mail']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['mail'] : null;
                    $model->observaciones = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['observaciones']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['observaciones'] : null;

                    if ($idPersona) {
                        $persona = Sds_com_persona::findOne($idPersona);
                        $accion = Mds_sys_log::ACCION_EDITAR;
                    } else {
                        $persona = new Sds_com_persona();
                        $persona->documento = $documento;
                        $accion = Mds_sys_log::ACCION_NUEVO;
                    }

                    if ($persona) {
                        $persona->documento_tipo = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['idtipodocumento']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['idtipodocumento'] : ($persona->documento_tipo ? $persona->documento_tipo : null);
                        $persona->apellido = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['apellido']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['apellido'] : null;
                        $persona->nombre = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['nombre']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['nombre'] : null;
                        $persona->domicilio_calle = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['domicilio_calle']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['domicilio_calle'] : null;
                        $persona->domicilio_numero = isset(Yii::$app->request->post()['mds_legales_oficio_vinculado']['domicilio_numero']) ? Yii::$app->request->post()['mds_legales_oficio_vinculado']['domicilio_numero'] : null;
                        $persona->genero = $genero;
                        $persona->nacionalidad = $nacionalidad;
                        $persona->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $fechaNacimiento)));
                        if ($persona->save()) {
                            Mds_sys_log::guardarLog($accion, 'sds_com_persona', $persona->idpersona, $persona->getAttributes());
                            $model->idpersona = $persona->idpersona;
                        }
                    }
                } else {
                    $model->load(Yii::$app->request->post());
                    $model->idpersona = null;
                }

                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->save()) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        $transaction->commit();
                        $accion = $idlegalesoficiovinculado ? Mds_sys_log::ACCION_EDITAR : Mds_sys_log::ACCION_NUEVO;
                        Mds_sys_log::guardarLog($accion, 'mds_legales_oficio_vinculado', $model->idlegalesoficiovinculado, $model->getAttributes());

                        if ($documento) {
                            $externalApiRequest = new ExternalApiRequestController();
                            $externalApiRequest->runneuIntervencionByModulo(array($documento), Mds_legales_oficio::RUNNEU_API_MODULO, $idlegalesoficio, 'create', Mds_legales_oficio::RUNNEU_API_TIPO_REQUERIMIENTO);
                        }

                        $textoSuccess = $idlegalesoficiovinculado ? 'actualizada' : 'vinculada';
                        $botonVolver = Html::a(
                            ' Volver a la Grilla',
                            ['index', 'idlegalesoficio' => $idlegalesoficio],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        );
                        $botonAgregarOtro = Html::a('Agregar Otro', ['create', 'idlegalesoficio' => $idlegalesoficio], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);
                        $botonesFooter = $idlegalesoficiovinculado ? "$botonVolver" :  "$botonVolver $botonAgregarOtro";
                        return [
                            'title' => "Listado de personas vinculadas - requerimiento #$idlegalesoficio",
                            'content' => "<span class='text-success'>Persona $textoSuccess exitosamente!</span>",
                            'footer' => $botonesFooter
                        ];
                    } else {
                        $transaction->rollBack();
                        // Yii::$app->session->setFlash('error', "Error al crear la persona.");
                        return [
                            'title' => "Listado de personas vinculadas - requerimiento #$idlegalesoficio",
                            'content' => '<span class="text-danger">Error al crear la persona</span>',
                            'footer' => Html::a(
                                ' Volver a la Grilla',
                                ['index', 'idlegalesoficio' => $idlegalesoficio],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            ) .
                                Html::a('Agregar', ['create', 'idlegalesoficio' => $idlegalesoficio], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                } else {
                    // Yii::$app->session->setFlash('error', "Error al validar los datos.");
                    //No pudo crear el agresor porque no valido el modelo
                    return [
                        'title' => "Listado de personas vinculadas - requerimiento #$idlegalesoficio",
                        'content' => '<span class="text-danger">Error al validar los datos.</span>',
                        'footer' => Html::a(
                            ' Volver a la Grilla',
                            ['index', 'idlegalesoficio' => $idlegalesoficio],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        ) .
                            Html::a('Agregar', ['create', 'idlegalesoficio' => $idlegalesoficio], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            } else {
                return [
                    'title' => "Listado de personas vinculadas - requerimiento #$idlegalesoficio",
                    'content' => '<span class="text-danger">Error en el envio de los datos.</span>',
                    'footer' => Html::a(
                        ' Volver a la Grilla',
                        ['index', 'idlegalesoficio' => $idlegalesoficio],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::a('Agregar', ['create', 'idlegalesoficio' => $idlegalesoficio], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function getPermissionsCrud($iditem, $arrayRoles, $idRolAdminGeneral)
    {
        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $hasRolAdminGeneral = false;
        $permisos = [];

        $idusuario = Yii::$app->user->identity->idusuario;
        $roles = implode(',', $arrayRoles);

        if ($iditem) {
            $permisos = Mds_seg_permiso::findBySql(
                "SELECT *
                FROM mds_seg_permiso
                WHERE idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario)
                AND idrol IN ({$roles})
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


        if (Mds_seg_usuario_rol::hasRol($idRolAdminGeneral)) {
            $hasRolAdminGeneral = true;
        }

        $response = [
            'permissionCreate' => $permissionCreate,
            'permissionRead' => $permissionRead,
            'permissionUpdate' => $permissionUpdate,
            'permissionDelete' => $permissionDelete,
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
        ];
        return $response;
    }

    protected function getListParentesco()
    {
        //Busqueda relaciones vinculares
        $relacion = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_PARENTEZCO, "activo" => 1])
            ->andWhere('idconfiguracion != 60') // traemos todos menos jefe
            ->asArray()
            ->all();
        $relaciones = ArrayHelper::map($relacion, 'idconfiguracion', 'descripcion');
        return $relaciones;
    }

    protected function getListTiposDocumentos()
    {
        //Busqueda Tipos de documentos
        $tipos = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_TIPO_DOC, "activo" => 1])
            ->asArray()
            ->all();
        $arrayTipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $arrayTipos;
    }

    protected function findModel($id)
    {
        if (($model = Mds_legales_oficio_vinculado::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
