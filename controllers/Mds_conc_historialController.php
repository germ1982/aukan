<?php

namespace app\controllers;

use Yii;

use app\models\Mds_conc_historial;
use app\models\Mds_conc_historialSearch;
use app\models\Mds_conc_solicitud;
use app\models\Mds_conc_postulacion;

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Mds_conc_impugnacion_motivo;

use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\Response;

use kartik\mpdf\Pdf;

/**
 * Mds_conc_historialController implements the CRUD actions for Mds_conc_historial model.
 */
class Mds_conc_historialController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'reactivate', 'reporte'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'reactivate', 'reporte'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_CONCURSO
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Mds_conc_historial models.
     * @return mixed
     */
    public function actionIndex($idpostulacion = null)
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $searchModel = new Mds_conc_historialSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $idpostulacion);
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);

            $postulacion = $idpostulacion ? Mds_conc_postulacion::findOne($idpostulacion) : null;

            $urlReferer = $_SERVER["HTTP_REFERER"] ?? null;
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($urlReferer && !strpos($urlReferer, 'mds_conc_historial')) {
                $_SESSION["urlAnteriorMdsConcHistorial"] = $urlReferer;
            } else if (!$urlReferer) {
                $_SESSION["urlAnteriorMdsConcHistorial"] = null;
            }

            return $this->render(
                'index',
                [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'permission' => $permissionCrud,
                    'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    'estadoAnteriorFiltro' => $this->getEstadoFiltro('estado_anterior', $idpostulacion),
                    'estadoNuevoFiltro' => $this->getEstadoFiltro('estado_nuevo', $idpostulacion),
                    'urlAnterior' => $_SESSION["urlAnteriorMdsConcHistorial"] ?? null,
                    'postulacion' => $postulacion
                ]
            );
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_conc_historial model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionRead = $permissionCrud['permissionRead'];
        $permissionUpdate = $permissionCrud['permissionUpdate'];
        if ($permissionRead) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);

            $dateInput1 = explode('-', $model->created_at);
            $dateInput2 = $dateInput1[2];

            $dia_hora = explode(' ', $dateInput2);
            $dia = $dia_hora[0];
            $hora = $dia_hora[1];
            $hora = explode(':', $hora);
            $hora = "$hora[0] $hora[1]";
            $fechaFormateada = $dia . '/' . $dateInput1[1] . '/' . $dateInput1[0];

            $fechaReg = $fechaFormateada;
            $horaReg = $hora;

            $motivosImpugnacionString = '';
            if ($model->estado_nuevo === Mds_conc_solicitud::ESTADO_NO_ADMITIDO || $model->estado_nuevo === Mds_conc_solicitud::ESTADO_IMPUGNADO) {
                if ($model->estado_nuevo === Mds_conc_solicitud::ESTADO_NO_ADMITIDO) {
                    $motivosImpugnacion = Mds_conc_historial::getMotivosImpugnacionByIdHistorial($id);
                } else {
                    $motivosImpugnacion = Mds_conc_postulacion::getMotivosImpugnacionByIdPostulacion($model->idpostulacion);
                }

                if (count($motivosImpugnacion) > 0) {
                    foreach ($motivosImpugnacion as $key => $motivo) {
                        $motivosImpugnacionString .=  $key + 1 === count($motivosImpugnacion) ? "{$motivo['descripcion']}" : "{$motivo['descripcion']}, ";
                    }
                }
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Estado #<b>$id</b> de la postulación #<b>{$model->postulacion0->idpostulacion}</b>",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'fecha' => $fechaReg,
                        'hora' => $horaReg,
                        'motivosImpugnacion' => $motivosImpugnacionString
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) . ($permissionUpdate ?
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']) : "")
                ];
            } else {
                return $this->render('view', [
                    'model' => $model,
                    'fecha' => $fechaReg,
                    'hora' => $horaReg,
                    'motivosImpugnacion' => $motivosImpugnacionString
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_conc_historial model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idpostulacion)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionCreate = $permissionCrud['permissionCreate'];

        if ($permissionCreate) {
            $request = Yii::$app->request;

            $modelPostulacion = Mds_conc_postulacion::findOne($idpostulacion);
            $modelPostulacion->updated_at = date('Y-m-d h:i:s');
            $modelPostulacion->puntaje = Yii::$app->request->post('puntaje');

            $model = new Mds_conc_historial();
            $model->created_at = date('Y-m-d h:i:s');
            $model->idusuario = Yii::$app->user->identity->idusuario;
            $model->idpostulacion = $idpostulacion;
            $model->estado_anterior = $modelPostulacion->estado;

            $estadosTipos = ArrayHelper::map(
                Sds_com_configuracion::find('idconfiguracion', 'descripcion')
                    ->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::CONCURSO_SOLICITUD_ESTADO])
                    ->andWhere(['=', 'activo', 1])
                    ->orderBy(['descripcion' => SORT_ASC])
                    ->all(),
                'idconfiguracion',
                'descripcion'
            );

            $motivosImpugnacionOptions = ArrayHelper::map(
                Sds_com_configuracion::find('idconfiguracion', 'descripcion')
                    ->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::CONCURSO_IMPUGNACION_MOTIVO])
                    ->andWhere(['=', 'activo', 1])
                    ->orderBy(['descripcion' => SORT_ASC])
                    ->all(),
                'idconfiguracion',
                'descripcion'
            );

            switch ($modelPostulacion->estado) {
                case Mds_conc_solicitud::ESTADO_INSCRIPTO:
                    //Una postulación que está en estado ‘INSCRIPTO’, solo puede cambiar a ‘ADMITIDO’ o ‘NO ADMITIDO’.
                    $clavesDeseadas = array(Mds_conc_solicitud::ESTADO_ADMITIDO, Mds_conc_solicitud::ESTADO_NO_ADMITIDO);
                    $estadosTipos = array_intersect_key($estadosTipos, array_flip($clavesDeseadas));
                    break;
                case Mds_conc_solicitud::ESTADO_IMPUGNADO:
                    //Una postulación que está en estado ‘IMPUGNADO’, solo puede cambiar a ‘ADMITIDO’ , ‘RECHAZADO’ o REASIGNADO’.
                    $clavesDeseadas = array(Mds_conc_solicitud::ESTADO_ADMITIDO, Mds_conc_solicitud::ESTADO_RECHAZADO, Mds_conc_solicitud::ESTADO_REASIGNADO);
                    $estadosTipos = array_intersect_key($estadosTipos, array_flip($clavesDeseadas));
                    break;
                case Mds_conc_solicitud::ESTADO_ADMITIDO:
                    //Una postulación que está en estado 'ADMITIDO', solo puede cambiar a 'ASIGNACION PROVISORIA' o ‘NO ASIGNADO'.
                    $clavesDeseadas = array(Mds_conc_solicitud::ESTADO_ASIGNACION_PROVISORIA, Mds_conc_solicitud::ESTADO_NO_ASIGNADO);
                    $estadosTipos = array_intersect_key($estadosTipos, array_flip($clavesDeseadas));
                    break;
                case Mds_conc_solicitud::ESTADO_ASIGNACION_PROVISORIA:
                    //Una postulación que está en estado 'ASIGNACION PROVISORIA', solo puede cambiar a ‘NO ASIGNADO'.
                    $clavesDeseadas = array(Mds_conc_solicitud::ESTADO_NO_ASIGNADO);
                    $estadosTipos = array_intersect_key($estadosTipos, array_flip($clavesDeseadas));
                    break;
                case Mds_conc_solicitud::ESTADO_NO_ASIGNADO:
                    //Una postulación que está en estado 'NO ASIGNADO', solo puede cambiar a ‘ASIGNACION PROVISORIA'.
                    $clavesDeseadas = array(Mds_conc_solicitud::ESTADO_ASIGNACION_PROVISORIA);
                    $estadosTipos = array_intersect_key($estadosTipos, array_flip($clavesDeseadas));
                    break;
                default:
                    $keyAQuitar = $modelPostulacion->estado;
                    unset($estadosTipos[$keyAQuitar]);
                    break;
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $title = "Cambiar estado";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]);
                $buttons = $botonVolver;

                if ($request->isGet) {
                    $content = $this->renderAjax('create', [
                        'model' => $model,
                        'modelPostulacion' => $modelPostulacion,
                        'estadosTipos' => $estadosTipos,
                        'motivosImpugnacionOptions' => $motivosImpugnacionOptions
                    ]);

                    $buttons .= " $botonGuardar";
                } else if ($model->load($request->post()) && $model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();


                    $content = "<span class='text-danger'>Se ha registrado un error al realizar el cambio de estado de la postulacion #<b>$idpostulacion</b></span>";
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_historial', $model->idhistorial, $model->getAttributes());

                        $modelPostulacion->estado = $model->estado_nuevo;
                        if ($modelPostulacion->save()) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_postulacion', $modelPostulacion->idpostulacion, $model->getAttributes());

                            if (isset(Yii::$app->request->post()['motivos_impugnacion'])) {
                                $this->guardarMotivosImpugnacion($model, Yii::$app->request->post()['motivos_impugnacion'], 'create');
                            }

                            $transaction->commit();
                            $title = "Registro exitoso";
                            $content = "<span class='text-success'>Se ha registrado exitosamente el cambio de estado de la postulacion #<b>$idpostulacion</b></span>";
                        } else {
                            $transaction->rollBack();
                            $content = '<span class="text-danger">Error al guardar la postulación</span>';
                        }
                    } else {
                        $transaction->rollBack();
                        $content = '<span class="text-danger">Error al guardar el historial</span>';
                    }
                } else {
                    $content = '<span class="text-danger">Error al validar la postulacion</span>';
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                if ($model->load($request->post())) {
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();

                        if ($model->save()) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_historial', $model->idhistorial, $model->getAttributes());
                            $modelPostulacion->estado = $model->estado_nuevo;
                            if ($modelPostulacion->save()) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_postulacion', $modelPostulacion->idpostulacion, $model->getAttributes());
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', " Se generó correctamente el historial.");
                            } else {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', "Error al actualizar la postulación.");
                            }
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', "Error al actualizar el historial.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "Error al validar el historial.");
                    }
                    return $this->redirect(['index', 'idpostulacion' => $model->idpostulacion]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'modelPostulacion' => $modelPostulacion,
                        'estadosTipos' => $estadosTipos,
                        'motivosImpugnacionOptions' => $motivosImpugnacionOptions
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_conc_historial model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionUpdate = $permissionCrud['permissionUpdate'];

        if ($permissionUpdate) {
            $request = Yii::$app->request;

            $model = $this->findModel($id);
            $model->updated_at = date('Y-m-d h:i:s');
            $model->idusuario = Yii::$app->user->identity->idusuario;

            $modelPostulacion = Mds_conc_postulacion::findOne($model->idpostulacion);
            $modelPostulacion->updated_at = date('Y-m-d h:i:s');
            $modelPostulacion->puntaje = Yii::$app->request->post('puntaje');

            $estadosTipos = ArrayHelper::map(
                Sds_com_configuracion::find('idconfiguracion', 'descripcion')
                    ->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::CONCURSO_SOLICITUD_ESTADO])
                    ->andWhere(['=', 'activo', 1])
                    ->orderBy(['descripcion' => SORT_ASC])
                    ->all(),
                'idconfiguracion',
                'descripcion'
            );

            $motivosImpugnacionString = '';
            $motivosImpugnacion = array();
            if ($model->estado_nuevo === Mds_conc_solicitud::ESTADO_NO_ADMITIDO || $model->estado_nuevo === Mds_conc_solicitud::ESTADO_IMPUGNADO) {
                if ($model->estado_nuevo === Mds_conc_solicitud::ESTADO_NO_ADMITIDO) {
                    $motivosImpugnacionHistorial = Mds_conc_historial::getMotivosImpugnacionByIdHistorial($model->idhistorial);
                    if (count($motivosImpugnacionHistorial) > 0) {
                        foreach ($motivosImpugnacionHistorial as $motivo) {
                            array_push($motivosImpugnacion, $motivo['idconfiguracion']);
                        }
                    }
                } else {
                    $motivosImpugnacionPostulacion = Mds_conc_postulacion::getMotivosImpugnacionByIdPostulacion($model->idpostulacion);
                    if (count($motivosImpugnacionPostulacion) > 0) {
                        foreach ($motivosImpugnacionPostulacion as $key => $motivo) {
                            $motivosImpugnacionString .=  $key + 1 === count($motivosImpugnacionPostulacion) ? "{$motivo['descripcion']}" : "{$motivo['descripcion']}, ";
                        }
                    }
                }
            }

            $motivosImpugnacionOptions = ArrayHelper::map(
                Sds_com_configuracion::find('idconfiguracion', 'descripcion')
                    ->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::CONCURSO_IMPUGNACION_MOTIVO])
                    ->andWhere(['=', 'activo', 1])
                    ->orderBy(['descripcion' => SORT_ASC])
                    ->all(),
                'idconfiguracion',
                'descripcion'
            );

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                $title = "Actualizar estado #<b>$id</b> de la postulación #<b>{$model->postulacion0->idpostulacion}</b>";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonEditar =  Html::a('Editar', ['update', 'id' => $id], ['title' => 'Actualizar', 'class' => 'btn btn-primary', 'role' => 'modal-remote']);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]);
                $buttons = $botonVolver;

                if ($request->isGet) {
                    $content = $this->renderAjax('update', [
                        'model' => $model,
                        'estadosTipos' => $estadosTipos,
                        'motivosImpugnacion' => $motivosImpugnacion,
                        'motivosImpugnacionOptions' => $motivosImpugnacionOptions,
                        'motivosImpugnacionString' => $motivosImpugnacionString
                    ]);
                    $buttons .= " $botonGuardar";
                } else if ($model->load($request->post()) && $model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_historial', $model->idhistorial, $model->getAttributes());
                        if ($modelPostulacion->save()) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_postulacion', $modelPostulacion->idpostulacion, $model->getAttributes());

                            $motivosImpugnacion = array();
                            if (isset(Yii::$app->request->post()['motivos_impugnacion'])) {
                                $motivosImpugnacion = Yii::$app->request->post()['motivos_impugnacion'];
                            }
                            $this->guardarMotivosImpugnacion($model, $motivosImpugnacion, 'update');

                            $transaction->commit();
                            $content = "<span class='text-success'>Se guardó exitosamente!</span>";
                            $buttons .= " $botonEditar";
                        } else {
                            $transaction->rollBack();
                            $content = '<span class="text-danger">Error al guardar la postulación</span>';
                        }
                    } else {
                        $transaction->rollBack();
                        $content = '<span class="text-danger">Error al guardar el historial</span>';
                    }
                } else {
                    $content = '<span class="text-danger">Error al validar el historial</span>';
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                if ($model->load($request->post())) {
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_historial', $model->idhistorial, $model->getAttributes());
                            if ($modelPostulacion->save()) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_postulacion', $modelPostulacion->idpostulacion, $model->getAttributes());

                                $motivosImpugnacion = array();
                                if (isset(Yii::$app->request->post()['motivos_impugnacion'])) {
                                    $motivosImpugnacion = Yii::$app->request->post()['motivos_impugnacion'];
                                }
                                $this->guardarMotivosImpugnacion($model, $motivosImpugnacion, 'update');

                                $transaction->commit();
                                Yii::$app->session->setFlash('success', "Se actualizó correctamente el historial.");
                            } else {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', "Error al actualizar la postulación.");
                            }
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', "Error al actualizar el historial.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "Error al validar el historial.");
                    }
                    return $this->redirect(['index', 'idpostulacion' => $model->idpostulacion]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'estadosTipos' => $estadosTipos,
                        'motivosImpugnacion' => $motivosImpugnacion,
                        'motivosImpugnacionOptions' => $motivosImpugnacionOptions,
                        'motivosImpugnacionString' => $motivosImpugnacionString
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Mds_conc_historial model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionDelete = $permissionCrud['permissionDelete'];

        $model = $this->findModel($id);

        $modelPostulacion = Mds_conc_postulacion::findOne($model->idpostulacion);
        $ultimoEstadoPostulacion = $modelPostulacion->getUltimoEstado();

        if (!$model->deleted_at && $permissionDelete) {
            $success = true;
            $actualizarPostulacion = false;
            $textoError = "";

            if ($ultimoEstadoPostulacion) {
                //Si tiene estados y ademas el estado que estoy eliminando es el ultimo estado activo, debo setearle a la postulacion el estado del ultimo estado activo anterior al que estoy eliminando
                if ($model->idhistorial === $ultimoEstadoPostulacion->idhistorial) {
                    $ultimoEstadoPostulacionAnterior = $modelPostulacion->getUltimoEstado($model->idhistorial);
                    if ($ultimoEstadoPostulacionAnterior) {
                        $modelPostulacion->estado = $ultimoEstadoPostulacionAnterior->estado_nuevo;
                    } else {
                        //Si no existen estados debo setear a la postulacion el estado inicial (INSCRIPTO)
                        $modelPostulacion->estado = Mds_conc_solicitud::ESTADO_INSCRIPTO;
                    }
                    $actualizarPostulacion = true;
                }
            }

            $transaction = Yii::$app->db->beginTransaction();

            if ($actualizarPostulacion) {
                $modelPostulacion->updated_at = date('Y-m-d H:i:s');
                if ($modelPostulacion->validate()) {
                    if ($modelPostulacion->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_postulacion', $modelPostulacion->idpostulacion, $modelPostulacion->getAttributes());
                    } else {
                        $transaction->rollBack();
                        $textoError = "Error al actualizar el estado de la postulación.";
                        $success = false;
                    }
                } else {
                    $transaction->rollBack();
                    $textoError = "Error al validar los datos de la postulación.";
                    $success = false;
                }
            }

            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;

            if ($success) {
                if ($model->validate()) {
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_historial', $model->idhistorial, $model->getAttributes());
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', " Se eliminó correctamente el estado #" . $model->idhistorial);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al borrar el estado.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos del estado.");
                }
            } else {
                Yii::$app->session->setFlash('error', $textoError);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index', 'idpostulacion' => $model->idpostulacion]);
    }

    public function actionReactivate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionReactivate = $permissionCrud['permissionReactivate'];
        $model = $this->findModel($id);
        $modelPostulacion = Mds_conc_postulacion::findOne($model->idpostulacion);
        $ultimoEstadoPostulacion = $modelPostulacion->getUltimoEstado();

        if (!is_null($model->deleted_at) && $permissionReactivate) {
            $success = true;
            $actualizarPostulacion = false;
            $textoError = "";

            //Si no tiene estados o si tiene estados y ademas el estado que estoy reactivando se creo despues del ultimo estado activo, debo setearle a la postulacion el estado actual
            if (!$ultimoEstadoPostulacion || $model->idhistorial > $ultimoEstadoPostulacion->idhistorial) {
                $modelPostulacion->estado = $model->estado_nuevo;
                $actualizarPostulacion = true;
            }

            $transaction = Yii::$app->db->beginTransaction();

            if ($actualizarPostulacion) {
                $modelPostulacion->updated_at = date('Y-m-d H:i:s');
                if ($modelPostulacion->validate()) {
                    if ($modelPostulacion->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_postulacion', $modelPostulacion->idpostulacion, $modelPostulacion->getAttributes());
                    } else {
                        $transaction->rollBack();
                        $textoError = "Error al actualizar el estado de la postulación.";
                        $success = false;
                    }
                } else {
                    $transaction->rollBack();
                    $textoError = "Error al validar los datos de la postulación.";
                    $success = false;
                }
            }

            $model->deleted_at = null;
            $model->idusuario_borra = null;

            if ($success) {
                if ($model->validate()) {
                    if ($model->update()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_historial', $model->idhistorial, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se reactivó correctamente el historial #" . $model->idhistorial);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al reactivar la historial.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Error al validar los datos de la historial.");
                }
            } else {
                Yii::$app->session->setFlash('error', $textoError);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return $this->redirect(['index', 'idpostulacion' => $model->idpostulacion]);
    }

    /**
     * Finds the Mds_conc_historial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_conc_historial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_conc_historial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function getPermissionsCrud()
    {
        $response = null;

        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $permissionReactivate = false;

        $idusuario = Yii::$app->user->identity->idusuario;
        $rolesConcursos = implode(',', Mds_conc_solicitud::ID_ROLES_CONCURSOS);
        $iditem = Mds_seg_item::MODULO_CONCURSO;

        $permisos = Mds_seg_permiso::findBySql(
            "select *
                from mds_seg_permiso
                where idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                AND idrol IN ({$rolesConcursos})
                AND iditem = {$iditem}"
        )->all();

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

        if (Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL)) {
            $permissionReactivate = true;
        }

        if ($countPermisos) {
            $response = [
                'permissionCreate' => $permissionCreate,
                'permissionRead' => $permissionRead,
                'permissionUpdate' => $permissionUpdate,
                'permissionDelete' => $permissionDelete,
                'permissionReactivate' => $permissionReactivate,
            ];
        }
        return $response;
    }

    public function actionReporte($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionRead = $permissionCrud['permissionRead'];

        if ($permissionRead) {
            $model =  $this->findModel($id);

            if ($model) {
                $usuarioAuth = Yii::$app->user->identity;
                $dateToday = date('d/m/Y H:i:s');
                $idPostulacion = $model->idpostulacion;

                $fechaCarga = $model->getFechaCarga($model->created_at);
                $puntaje = null;
                if ($model->estado_nuevo === Mds_conc_solicitud::ESTADO_SELECCIONADO) {
                    $puntaje = $model->postulacion0->puntaje;
                }

                $motivosImpugnacion = '';
                if ($model->estado_nuevo === Mds_conc_solicitud::ESTADO_NO_ADMITIDO) {
                    $motivosImpugnacionHistorial = Mds_conc_historial::getMotivosImpugnacionByIdHistorial($id);
                    if (count($motivosImpugnacionHistorial) > 0) {
                        foreach ($motivosImpugnacionHistorial as $key => $motivo) {
                            $motivosImpugnacion .=  $key + 1 === count($motivosImpugnacionHistorial) ? "{$motivo['descripcion']}" : "{$motivo['descripcion']}, ";
                        }
                    }
                }

                $nombreCompleto = mb_strtoupper("{$model->postulacion0->solicitud->nombre} {$model->postulacion0->solicitud->apellido}");
                $dni = is_numeric($model->postulacion0->solicitud->documento) ? number_format($model->postulacion0->solicitud->documento, 0, '', '.') : $model->postulacion0->solicitud->documento;
                $usuarioCarga = mb_strtoupper("{$model->usuarioCarga->nombre} {$model->usuarioCarga->apellido}");
                $estadoAnterior = mb_strtoupper($model->anteriorEstado->descripcion);
                $estadoNuevo = mb_strtoupper($model->nuevoEstado->descripcion);

                $content = $this->renderPartial('reporte', [
                    'model' => $model,
                    'puntaje' => $puntaje,
                    'nombre' => $nombreCompleto,
                    'dni' => $dni,
                    'usuarioCarga' => $usuarioCarga,
                    'estadoAnterior' => $estadoAnterior,
                    'estadoNuevo' => $estadoNuevo,
                    'fecha' => $fechaCarga,
                    'motivosImpugnacion' => $motivosImpugnacion
                ]);

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => Pdf::FORMAT_A4,
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'filename' => 'Reporte_estados_postulacion_' . $idPostulacion . '.pdf',
                    'content' => $content,
                    'defaultFontSize' => 12,
                    'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}div.saltopagina{page-break-after:always}',
                    'methods' => [
                        'SetTitle' => 'DETALLE DEL ESTADO DE LA POSTULACION #' . $idPostulacion,
                        'SetHeader' => null,
                        'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                    ]
                ]);

                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_historial', $id, array());
                return $pdf->render();
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'No existe la solicitud concurso.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function getEstadoFiltro($stringAtributo, $idpostulacion)
    {
        $estados = Mds_conc_historial::getEstadoFiltro($stringAtributo, $idpostulacion);
        if ($estados) {
            return  ArrayHelper::map($estados, 'idconfiguracion', 'descripcion');
        }
        return [];
    }

    public function guardarMotivosImpugnacion($historial, $motivosImpugnacion, $action)
    {
        if ($action === 'update') {
            $motivosImpugnacionHistorial = Mds_conc_historial::getMotivosImpugnacionByIdHistorial($historial->idhistorial);

            if (count($motivosImpugnacion) > 0 && count($motivosImpugnacionHistorial) > 0) {
                foreach ($motivosImpugnacionHistorial as $keyHistorial => $motivoHistorial) {
                    foreach ($motivosImpugnacion as $keyMotivo => $motivo) {
                        if ($motivoHistorial['idconfiguracion'] == $motivo) {
                            unset($motivosImpugnacionHistorial[$keyHistorial]);
                            unset($motivosImpugnacion[$keyMotivo]);
                        }
                    }
                }
            }

            if (count($motivosImpugnacionHistorial) > 0) {
                foreach ($motivosImpugnacionHistorial as $motivoHistorial) {
                    $model = Mds_conc_impugnacion_motivo::findOne($motivoHistorial['idconcimpugnacionmotivo']);
                    $model->deleted_at = date('Y-m-d H:i:s');
                    $model->idusuario_borra = Yii::$app->user->identity->idusuario;
                    $model->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_impugnacion_motivo', $model->idconcimpugnacionmotivo, $model->getAttributes());
                }
            }
        }

        if (count($motivosImpugnacion) > 0) {
            foreach ($motivosImpugnacion as $motivo) {
                $model = new Mds_conc_impugnacion_motivo;
                $model->idhistorial = $historial->idhistorial;
                $model->idmotivo = $motivo;
                $model->idusuario_carga = Yii::$app->user->identity->idusuario;
                $model->created_at = date('Y-m-d H:i:s');
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_impugnacion_motivo', $model->idconcimpugnacionmotivo, $model->getAttributes());
            }
        }
    }
}
