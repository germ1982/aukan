<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_ent_entrega;
use Yii;
use app\models\Sds_ent_solicitud_intermedia;
use app\models\Sds_ent_solicitud_intermediaSearch;
use app\models\Sds_com_persona;
use app\models\Sds_ent_saldo;
use app\models\Sds_ent_tipo;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Sds_ent_solicitud_intermediaController implements the CRUD actions for Sds_ent_solicitud_intermedia model.
 */
class Sds_ent_solicitud_intermediaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ENT_SOLICITUD,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_ent_solicitud_intermedia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_ent_solicitud_intermediaSearch();
        $searchModel->fdesde = new DateTime();
        $searchModel->fdesde->modify("-7 day");
        $searchModel->fdesde = $searchModel->fdesde->format("d-m-Y");
        $searchModel->fhasta = date("d-m-Y");
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_ent_solicitud_intermedia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_ent_solicitud_intermedia #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_ent_solicitud_intermedia model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        $request = Yii::$app->request;
        $model = new Sds_ent_solicitud_intermedia();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->usuario_carga = $idusuario;
        $fecha_hora = date("Y-m-d H:i");
        $model->fecha_hora = $fecha_hora;

        if($request->isPost){
            /* Process for POST request */
            if ($model->load($request->post())){
                $guardar = true;
                if ($model->receptor == null) {
                    $model->addError("receptor", "Debe asignar un receptor");
                    $guardar = false;
                }
                if ($model->emisor == null) {
                    $model->addError("emisor", "Debe asignar un emisor");
                    $guardar = false;
                }
                if ($model->saldo < $model->cantidad) {
                    $model->addError("cantidad", "La cantidad debe ser menor o igual al saldo informado.");
                    $guardar = false;
                }
                if ($guardar) {
                    $model->fecha_hora = date('Y-m-d H:i', strtotime(str_replace('/', '-', $model->fecha_hora . ' ' . $model->hora)));
                    $entregas_pendientes = Sds_ent_entrega::getRendicionesPendientes($model->receptor, $model->idtipo, $model->fecha_hora);
                    if (empty($entregas_pendientes)) {
                        $model->irregular = false;
                    } else {
                        $model->irregular = true;
                        $rendiciones_detalle = "";
                        foreach ($entregas_pendientes as $rend) {
                            $rendicion_detalle = date('d/m/Y', strtotime(str_replace('-', '/', $rend->fecha_hora)))
                                . " - " . $rend->detalle_tipo . " - Cant.: " . $rend->cantidad . " | Saldo: " . $rend->saldo;
                            $rendiciones_detalle = $rendiciones_detalle . $rendicion_detalle . "<br>";
                        }
                        $model->rendiciones_pendientes = $rendiciones_detalle;
                    }
                    
                    if ($model->save(false)) {


                        $permiso_entrega = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where
                        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                        and (iditem=" . Mds_seg_item::MODULO_ENT_SOLICITUD_APROBAR.")")->one();
                        $id_solicitud = $model->idsolicitudintermedia;
                        $url =  Url::to(['/sds_ent_solicitud_intermedia/cambiar_estado', 'id' => $model->idsolicitudintermedia, 'estado' => Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA, 'for_get'=>($request->isAjax ? null:true)]);
                        $receptor = Sds_com_configuracion::findOne($model->receptor)->descripcion;
                        if($request->isAjax){
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return [
                                'title' => "Carga de Solicitud de Entrega",
                                'content' => '<span class="text-success">Solicitud generada correctamente</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                ($permiso_entrega!=null ?
                                Html::a('<span class= "fas fa-people-carry"></span> Realizar Entrega', $url, [
                                    'class' => 'btn btn-info',
                                    'role' => 'modal-remote', 'title' => 'Generar Entrega',
                                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip'
                                ]) : '').
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                        
                        $model = new Sds_ent_solicitud_intermedia();
                        $model->usuario_carga = $idusuario;
                        $model->fecha_hora = date("Y-m-d H:i");
                        return $this->render('create', [
                            'model' => $model,
                            'messageOk' => true,
                            'permiso_entrega' => $permiso_entrega,
                            'receptorEntrega' => $receptor,
                            'urlEntrega' => $url,
                            'id_solicitud' => $id_solicitud,
                        ]);
                    }
                }
            }
        }
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Nueva Solicitud de Entrega",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }
        if($request->isGet){
            /* Process for GET request */
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sds_ent_solicitud_intermedia model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $guardar = true;
                if ($model->receptor == null) {
                    $model->addError("receptor", "Debe asignar un receptor");
                    $guardar = false;
                }
                if ($model->emisor == null) {
                    $model->addError("emisor", "Debe asignar un emisor");
                    $guardar = false;
                }
                if ($model->saldo < $model->cantidad) {
                    $model->addError("cantidad", "La cantidad debe ser menor o igual al saldo informado.");
                    $guardar = false;
                }
                if ($guardar) {
                    $model->fecha_hora = date('Y-m-d H:i', strtotime(str_replace('/', '-', $model->fecha_hora . ' ' . $model->hora)));
                    if ($model->save(false)) {
                        return [
                            'title' => "Solicitud de entrega #" . $id,
                            'content' => $this->renderAjax('view', [
                                'model' => $model,
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                }
            }
            return [
                'title' => "Solicitud de entrega #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idsolicitudintermedia]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionCambiar_estado($id, $estado, $for_get=null)
    {
        $solicitud = $this->findModel($id);
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if ($estado == Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA) {
            $saldo = Sds_ent_saldo::getSaldo($solicitud->idtipo, $solicitud->emisor);
            if ($saldo >= $solicitud->cantidad) {
                //GENERO LA ENTREGA INTERMEDIA Y MUESTRO EL REPORTE DE ENTREGA INTERMEDIA
                $entrega = new Sds_ent_entrega();
                $entrega->fecha_hora = $solicitud->fecha_hora;
                $entrega->idtipo = $solicitud->idtipo;
                $entrega->emisor = $solicitud->emisor;
                $entrega->receptor = $solicitud->receptor;
                $entrega->cantidad = $solicitud->cantidad;
                $entrega->observaciones = $solicitud->observaciones;
                $entrega->idusuario = $solicitud->usuario_carga;
                $entrega->idsolicitudintermedia = $solicitud->idsolicitudintermedia;
                $entrega->usuario_entrega =  $idusuario;
                $entrega->persona_retira =  0;
                if ($request->isGet) {
                    return [
                        'title' => "Entregar Solicitud",
                        'content' => $this->renderAjax('entregar',  [
                            'model' => $entrega,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Entregar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else if ($entrega->load($request->post())) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $model_com_persona = new Sds_com_persona();
                    if ($entrega->persona_retira > 0) {
                        $model_com_persona = Sds_com_persona::findOne($entrega->persona_retira);
                    } else {
                        $model_com_persona->documento_tipo = '83';
                        $model_com_persona->fecha_nacimiento =  '1900-01-01';
                        $model_com_persona->documento = $entrega->dni;
                        $model_com_persona->nacionalidad = 70;
                        $model_com_persona->genero = 82;
                    }
                    $model_com_persona->nombre = $entrega->nombre;
                    $model_com_persona->apellido = $entrega->apellido;
                    $model_com_persona->conviviente = 0;
                    $guardar = true;
                    if ($entrega->dni == null) {
                        $entrega->addError("dni", "Debe ingresar el dni de la persona");
                        $guardar = false;
                    }
                    if ($entrega->nombre == null) {
                        $entrega->addError("nombre", "Debe ingresar el nombre");
                        $guardar = false;
                    }
                    if ($entrega->apellido == null) {
                        $entrega->addError("apellido", "Debe ingresar el apellido");
                        $guardar = false;
                    }
                    $idtipo = $entrega->idtipo;
                    $tiene_numero = $idtipo > 0 ? Sds_ent_tipo::findOne($idtipo)->tiene_numero : 0;
                    if ($tiene_numero == 1) {
                        if ($entrega->numero_desde == null || $entrega->numero_desde == "") {
                            $entrega->addError("numero_desde", "Debe asignar el número desde");
                            $guardar = false;
                        } else if ($entrega->numero_hasta == null || $entrega->numero_hasta == "") {
                            $entrega->addError("numero_hasta", "Debe asignar el número hasta");
                            $guardar = false;
                        } else if (is_int($entrega->numero_desde)) {
                            $entrega->addError("numero_desde", "El número desde debe ser numérico y sin decimales.");
                            $guardar = false;
                        } else if (is_int($entrega->numero_hasta)) {
                            $entrega->addError("numero_hasta", "El número hasta debe ser numérico y sin decimales.");
                            $guardar = false;
                        } else if ($entrega->numero_desde > $entrega->numero_hasta) {
                            $entrega->addError("numero_hasta", "El número hasta debe ser mayor que el desde.");
                            $guardar = false;
                        } else {
                            $cantidad_numeracion = $entrega->numero_hasta - $entrega->numero_desde + 1;
                            if ($entrega->cantidad != $cantidad_numeracion) {
                                $entrega->addError("numero_hasta", "Las cantidad ingresada no coincide con la numeración desde/hasta");
                                $guardar = false;
                            } else {
                                //Al entregar una solicitud intermedia, validar que el numero_desde>=numero_desde_padre y 
                                //numero_hasta<=numero_hasta_padre. La validación de solapamiento debe ser sólo del mismo emisor. 
                                //Todo si el tipo de entrega lleva número
                                $entrega_padre = Sds_ent_entrega::findOne($entrega->emisor);
                                if ($entrega_padre->numero_desde != null && $entrega_padre->numero_hasta != null) {
                                    if (
                                        !($entrega->numero_desde >= $entrega_padre->numero_desde && $entrega->numero_desde <= $entrega_padre->numero_hasta
                                        && $entrega->numero_hasta <= $entrega_padre->numero_hasta && $entrega->numero_hasta >= $entrega_padre->numero_desde)
                                    ) {
                                        $entrega->addError("numero_hasta", "Los números desde y hasta deben estar entre el número desde y hasta del emisor. 
                                        Entre el n°".$entrega_padre->numero_desde." y el n°".$entrega_padre->numero_hasta);
                                        $guardar = false;
                                    }
                                }
                                if ($guardar) {
                                    $entrega_existente = Sds_ent_entrega::find()->where("idtipo=$idtipo and 
                                    ((numero_desde>=" . $entrega->numero_desde . " and numero_desde<=" . $entrega->numero_hasta . ")
                                    or (numero_hasta<=" . $entrega->numero_hasta . " and numero_hasta>=" . $entrega->numero_desde . ")
                                    or (" . $entrega->numero_desde . ">=numero_desde and " . $entrega->numero_desde . "<=numero_hasta)
                                    or (" . $entrega->numero_hasta . "<=numero_hasta and " . $entrega->numero_hasta . ">=numero_desde))
                                    and " . $entrega->emisor . "=emisor
                                    and YEAR(fecha_hora)=YEAR('" . $entrega->fecha_hora . "')")->one();
                                    if ($entrega_existente != null) {
                                        $entrega->addError("numero_desde", "Ya existe una entrega guardada que coincide con el rango de números ingresados.");
                                        $entrega->addError("numero_hasta", "Entrega Nº " . $entrega_existente->identrega . " - Desde: " . $entrega_existente->numero_desde
                                            . " / Hasta: " . $entrega_existente->numero_hasta);
                                        $guardar = false;
                                    }
                                }
                            }
                        }
                    }
                    if ($guardar) {
                        if (!$model_com_persona->save()) {
                            $entrega->addError("dni", "No se ha podido guardar la persona. Lo siento.");
                            $transaction->rollBack();
                        } else {
                            $entrega->persona_retira = $model_com_persona->idpersona;
                            $entrega->dni = null;
                            if ($entrega->save(false)) {
                                if ($solicitud->updateAttributes(['estado' => $estado])) {
                                    Mds_sys_log::guardarLog(
                                        Mds_sys_log::ACCION_EDITAR,
                                        'sds_ent_solicitud_intermedia/cambiar_estado',
                                        $id,
                                        array(
                                            'estado' => $estado,
                                            'fecha_aprobacion' => date('Y-m-d')
                                        )
                                    );
                                    $transaction->commit();
                                    $url =  Url::to(['/sds_ent_entrega/reporte_entrega_interm', 'identrega' => $entrega->identrega]);
                                    /*
                                    Si la solicitud NO es realizada desde el modal/Ajax, vendrá seteado $for_get, en ese caso el boton cerrar
                                    del modal redirigirá a $url_redirect
                                    */
                                    $url_redirect =  Url::to(['/sds_ent_solicitud_intermedia/create']);
                                    return [
                                        'title' => "Entrega Generada",
                                        'content' => '<span class="text-success">Entrega generada Correctamente</span>',
                                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'onclick'=>($for_get!=null ? 'window.location.href ="'.$url_redirect.'";':'')]) .
                                            Html::a('<span class= "fas fa-print"></span> Ver Reporte', $url, [
                                                'class' => 'btn btn-primary',
                                                'title' => "Imprimir",
                                                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                                'data-toggle' => 'tooltip',
                                            ])
                                    ];
                                }
                            }
                        }
                    }
                }
                return [
                    'title' => "Entregar Solicitud",
                    'content' => $this->renderAjax('entregar',  [
                        'model' => $entrega,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Entregar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                $estado = Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA;
            }
        }
        if ($estado == Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA) {
            $solicitud->estado = $estado;
            if (isset($saldo)) {
                $solicitud->motivo_rechazo = "El saldo de la Entrega emisora es insuficiente 
                                                (Saldo: " . $saldo . " - Cant. Solic: " . $solicitud->cantidad . ") ";
            }
            if ($request->isGet) {
                return [
                    'title' => "Rechazar Solicitud de Entrega",
                    'content' => $this->renderAjax('update',  [
                        'model' => $solicitud,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Rechazar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($solicitud->load($request->post())) {
                if ($solicitud->motivo_rechazo == null || empty(trim($solicitud->motivo_rechazo))) {
                    $solicitud->addError("motivo_rechazo", "Debe ingresar el motivo del rechazo.");
                } else {
                    $solicitud->observaciones = $solicitud->observaciones . "<br>Motivo de Rechazo: <br>" . $solicitud->motivo_rechazo;
                    $solicitud->fecha_aprobacion = date('Y-m-d');
                    $solicitud->usuario_aprobacion = $idusuario;
                    if ($solicitud->save()) {
                        Mds_sys_log::guardarLog(
                            Mds_sys_log::ACCION_EDITAR,
                            'sds_ent_solicitud_intermedia/cambiar_estado',
                            $id,
                            array(
                                'estado' => $estado,
                                'fecha_aprobacion' => date('Y-m-d')
                            )
                        );
                        return [
                            'title' => "Estado de Solicitud de Entrega",
                            'content' => '<span class="text-success">Solicitud Rechazada Correctamente</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                }
            }
            return [
                'title' => "Rechazar Solicitud de Entrega",
                'content' => $this->renderAjax('update',  [
                    'model' => $solicitud,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Rechazar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
        if ($solicitud->updateAttributes(['estado' => $estado, 'usuario_aprobacion' => $idusuario, 'fecha_aprobacion' => date('Y-m-d')])) {
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_EDITAR,
                'sds_ent_solicitud_intermedia/cambiar_estado',
                $id,
                array(
                    'estado' => $estado,
                    'fecha_aprobacion' => date('Y-m-d')
                )
            );
            return [
                'title' => "Estado de Solicitud de Entrega",
                'content' => '<span class="text-success">Solicitud Aprobada Correctamente</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
        return [
            'title' => "Estado de Solicitud de Entrega",
            'content' => '<span class="text-danger">Hubo un error al cambiar el estado de la entrega</span><br>' .  print_r($solicitud->getAttributes(), true),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }


    /**
     * Delete an existing Sds_ent_solicitud_intermedia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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

    /**
     * Delete multiple existing Sds_ent_solicitud_intermedia model.
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
            return ['forceClose' => true];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Sds_ent_solicitud_intermedia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_ent_solicitud_intermedia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_ent_solicitud_intermedia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
