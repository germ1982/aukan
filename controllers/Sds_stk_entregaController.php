<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entregaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_responsable;
use kartik\mpdf\Pdf;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_stk_recepcion;
use app\models\Sds_stk_orden_compra;
use app\models\Sds_stk_movimiento;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_orden_compra_item;
use app\models\View_stock_detalle_ent_resp;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\UploadedFile;


class Sds_stk_entregaController extends Controller
{

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
                    'index', 'create', 'update', 'delete', 'view',
                    'imprimir_acta_entrega', 'get_grilla_entregas', 'get_grilla_responsables',
                    'imprimir_reporte_entregas', 'generar_ei',
                    'generar_ef'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'delete', 'view',
                            'imprimir_acta_entrega', 'get_grilla_entregas',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_ENTREGA],
                    ],
                    [
                        'actions' => [
                            'generar_ei',
                            'generar_ef'
                        ],
                        'allow' => true,
                        'roles' => [Mds_seg_item::STK_ENTREGA, Mds_seg_item::MODULO_STK_RECEPCION, Mds_seg_item::STK_MOVIMIENTO, Mds_seg_item::STK_OC],
                    ],
                    [
                        'actions' => [
                            'get_grilla_responsables',
                        ],
                        'allow' => true,
                        'roles' => [Mds_seg_item::STK_ENTREGA, Mds_seg_item::STK_GENERAL, Mds_seg_item::STK_ARTICULO],
                    ],
                    [
                        'actions' => [
                            'imprimir_reporte_entregas',
                        ],
                        'allow' => true,
                        'roles' => [Mds_seg_item::STK_ENTREGA, Mds_seg_item::STK_INVENTARIO, Mds_seg_item::MODULO_STK_RECEPCION, Mds_seg_item::STK_OC],
                    ]
                ],

            ],
        ];
    }

    public function actionIndex($celular = false)
    {
        if ($celular) {
            return $this->render('index_mobile', [
                'searchModel' => null,
                'dataProvider' => null,
                'abrir_modal' => true,
            ]);
        }
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);

        $searchModel = new Sds_stk_entregaSearch();

        if ($usuario->organismo_stock) {
            $id_organismo = $usuario->organismo_stock;
            $searchModel->organismo = $id_organismo;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_stk_entrega',
            null,
            []
        );
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $boton_editar = $this->findModel($id)->generada ? '' : Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);
            return [
                'title' => 'Entrega Numero' . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' =>
                Html::button('Cerrar', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]) . $boton_editar,
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate($celular = false)
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_entrega();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nueva Entrega',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button(
                        $celular ? 'Crear Otra' : 'Cerrar Entrega',
                        [
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]
                    ) .

                        Html::button('Guardar Entregaaaaaaaaaaaa', [
                            'id' => 'btnGuardarSubmit',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                            'style' => "visibility:hidden"
                        ])
                        .
                        Html::button('Guardar Entrega', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            //'type' => 'submit',
                            'onclick' => 'validar();',
                        ]),
                ];
            } elseif ($model->load($request->post())) {
                return $this->create_guardar($model, true, $celular);
            } else {
                return [
                    'title' => 'Nueva Entrega',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button(
                        $celular ? 'Crear Otra' : 'Cerrar Entrega',
                        [
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]
                    ) .
                        Html::button('Guardar Entrega', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            if ($model->load($request->post())) {
                return $this->create_guardar($model, false, $celular);
            }
            return $this->render('create', [
                'model' => $model,
                'title' => 'Entrega de stock',
            ]);
        }
    }

    protected function create_guardar($model, $ajax, $celular = false)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;

        $transaction = Yii::$app->db->beginTransaction();
        $guardado = true;

        $aux = 'Error al guardar: <br>';

        if ($model->idcontacto == null) {
            $model->addError('idcontacto', 'falta el responsable');
            $aux = "$aux falta el responsable <br>";
            $guardado = false;
        }

        $usuario = Mds_seg_usuario::findOne($idusuario);
        if ($usuario->organismo_stock) {
            $model->organismo = $usuario->organismo_stock;
        }

        $fecha = ArmarDateParaMySql($model->fecha_hora, $model->hora);
        $model->fecha_hora = date('Y-m-d H:i', strtotime($fecha));

        if ($model->idpersona == null) {
            $model->addError('documento', 'falta el documento');
            $aux = "$aux falta el idpersona <br>";
            $guardado = false;
        }

        if ($model->idpersona == 0) {
            $error_persona = 0;
            if ($model->documento_tipo == null) {
                $error_persona = 1;
            }
            if ($model->fecha_nacimiento == '') {
                $error_persona = 1;
            }
            if ($model->documento == '') {
                $error_persona = 1;
            }
            if ($model->nacionalidad == null) {
                $error_persona = 1;
            }
            if ($model->genero == null) {
                $error_persona = 1;
            }
            if ($model->nombre == '') {
                $error_persona = 1;
            }
            if ($model->apellido == '') {
                $error_persona = 1;
            }

            if ($error_persona == 0) {
                $model_com_persona = new Sds_com_persona();
                $model_com_persona->documento_tipo = $model->documento_tipo;
                $fecha_registro = ArmarDateParaMySql(
                    $model->fecha_nacimiento,
                    '00:00'
                );
                $fecha_registro = date_create($fecha_registro);
                $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                $model_com_persona->fecha_nacimiento = $fecha_registro;
                $model_com_persona->documento = $model->documento;
                $model_com_persona->nacionalidad = $model->nacionalidad;
                $model_com_persona->genero = $model->genero;
                $model_com_persona->nombre = $model->nombre;
                $model_com_persona->apellido = $model->apellido;
                $model_com_persona->domicilio_calle = $model->calle;
                $model_com_persona->domicilio_numero = $model->numero_calle;
                $model_com_persona->idlocalidad = $model->localidad;
                $model_com_persona->conviviente = 0;
                if (!$model_com_persona->save(false)) {
                    $guardado = false;
                    $transaction->rollBack();
                    $aux = 'Error en el guardado de la persona';
                } else {
                    $model->idpersona = $model_com_persona->idpersona;
                }
            } else {
                $aux =
                    'Error: Faltan datos de la persona, por lo tanto no se puede guardar, por lo tanto no se puede obtener un idpersona';
                $guardado = false;
                $transaction->rollBack();
            }
        }

        if ($model->persona_retira == 0) {
            if ($model->documento == $model->documento_retira) {
                $model->persona_retira = $model->idpersona;
            } else {
                $error_persona = 0;
                if ($model->documento_tipo_retira == null) {
                    $error_persona = 1;
                }
                if ($model->fecha_nacimiento_retira == '') {
                    $error_persona = 1;
                }
                if ($model->documento_retira == '') {
                    $error_persona = 1;
                }
                if ($model->nacionalidad_retira == null) {
                    $error_persona = 1;
                }
                if ($model->genero_retira == null) {
                    $error_persona = 1;
                }
                if ($model->nombre_retira == '') {
                    $error_persona = 1;
                }
                if ($model->apellido_retira == '') {
                    $error_persona = 1;
                }

                if ($error_persona == 0) {
                    $model_com_persona_retira = new Sds_com_persona();
                    $model_com_persona_retira->documento_tipo =
                        $model->documento_tipo_retira;
                    $fecha_registro = ArmarDateParaMySql(
                        $model->fecha_nacimiento_retira,
                        '00:00'
                    );
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model_com_persona_retira->fecha_nacimiento = $fecha_registro;
                    $model_com_persona_retira->documento =
                        $model->documento_retira;
                    $model_com_persona_retira->nacionalidad =
                        $model->nacionalidad_retira;
                    $model_com_persona_retira->genero = $model->genero_retira;
                    $model_com_persona_retira->nombre = $model->nombre_retira;
                    $model_com_persona_retira->apellido =
                        $model->apellido_retira;

                    $model_com_persona_retira->domicilio_calle =
                        $model->calle_retira;
                    $model_com_persona_retira->domicilio_numero =
                        $model->numero_calle_retira;
                    $model_com_persona_retira->idlocalidad =
                        $model->localidad_retira;
                    if (!$model_com_persona_retira->save(false)) {
                        $guardado = false;
                        $transaction->rollBack();
                        $aux = 'Error en el guardado de la persona';
                    } else {
                        $model->persona_retira =
                            $model_com_persona_retira->idpersona;
                    }
                } else {
                    $aux =
                        'Error: Faltan datos de la persona, por lo tanto no se puede guardar, por lo tanto no se puede obtener un idpersona';
                    $guardado = false;
                    $transaction->rollBack();
                }
            }
        }

        //---------------------------------------------------------------------------------------------------------------

        // Upload archivo adjunto
        $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto_entrega');
        if (isset($tmpfile)) {
            $extension = $tmpfile->extension;
            $nombre = "adjunto_acta_entrega_" . $model->identrega . "." . $extension;
            $model->adjunto_acta_entrega = $nombre;
            $ruta = 'uploads/entregas';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $tmpfile->saveAs($ruta . '/' . $nombre);
        }

        if ($guardado && $model->save(false)) {
            $transaction->commit();
            $this->verificar_referente($model->referente, $model->idpersona);
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_NUEVO,
                'sds_stk_entrega',
                $model->identrega,
                $model->getAttributes()
            );
            if ($ajax == true) {
                return [
                    'title' => "Se ha guardado la Entrega Numero: $model->identrega",
                    'content' => $this->renderAjax('view', ['model' => $model]),
                    'footer' =>
                    Html::button(
                        $celular ? 'Crear Otra' : 'Cerrar Entrega',
                        [
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]
                    ) .
                        Html::a(
                            'Añadir Articulos',
                            [
                                'update',
                                'id' => $model->identrega,
                                'celular' => $celular ? true : false,
                            ],
                            [
                                'class' => 'btn btn-primary',
                                'role' => 'modal-remote',
                            ]
                        ),
                ];
            } else {
                return $this->render('view', [
                    'model' => $model,
                    'title' => 'Entrega de stock',
                ]);
            }
        } else {
            if ($ajax == true) {
                $aux_guardado = $guardado ? 'true' : 'false';
                $aux_guardado = "Datos de guardado: <br>guardado: $aux_guardado <br>organismo: $model->organismo<br>fecha_hora: $model->fecha_hora<br>idpersona: $model->idpersona<br>idcontacto: $model->idcontacto";
                $aux_persona = "Datos de Persona: <br>documento_tipo: $model->documento_tipo<br>documento: $model->documento<br>nombre: $model->nombre<br>apellido: $model->apellido<br>fecha_nacimiento: $model->fecha_nacimiento<br>genero: $model->genero<br>nacionalidad: $model->nacionalidad<br>";
                return [
                    'title' =>
                    "<p style='color:red'>No se ha guardado, revise los datos</p>",
                    'content' => "<div class='row'><div class='col-md-6'>$aux_guardado</div><div class='col-md-6'>$aux_persona</div></div><br><br><div class='row'><div class='col-md-6'><p style='color:red'>$aux</p></div></div>",
                    'footer' => Html::button(
                        $celular ? 'Crear Otra' : 'Cerrar Entrega',
                        [
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]
                    ),
                ];
            } else {
                return 'JARANA!!!';
            }
        }
    }

    public function actionUpdate($id, $celular = false, $items = 0, $generada = 0)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $generada = $generada != 0;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Editar Entrega Numero ' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'items' => $items,
                        'generada' => $generada,
                    ]),
                    'footer' =>
                    Html::button(
                        $celular ? 'Crear Otra' : 'Cerrar Entrega',
                        [
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',

                        ]
                    )
                        .
                        Html::button('Guardar Entregaaaaaaaaaaaa', [
                            'id' => 'btnGuardarSubmit',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                            'style' => "visibility:hidden"
                        ])
                        .
                        Html::button('Guardar Entrega', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            //'type' => 'submit',
                            'onclick' => 'validar();',
                        ]),
                ];
            } elseif ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $aux = 'Error al guardar: <br>';

                if ($model->idcontacto == null) {
                    $model->addError('idcontacto', 'falta el responsable');
                    $aux = "$aux falta el responsable <br>";
                    $guardado = false;
                }

                $fecha = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $model->fecha_hora = date('Y-m-d H:i', strtotime($fecha));

                if ($model->idpersona == null) {
                    $model->addError('documento', 'falta el documento');
                    $aux = "$aux falta el idpersona <br>";
                    $guardado = false;
                }

                if ($model->idpersona == 0) {
                    $error_persona = 0;
                    if ($model->documento_tipo == null) {
                        $error_persona = 1;
                    }
                    if ($model->fecha_nacimiento == '') {
                        $error_persona = 1;
                    }
                    if ($model->documento == '') {
                        $error_persona = 1;
                    }
                    if ($model->nacionalidad == null) {
                        $error_persona = 1;
                    }
                    if ($model->genero == null) {
                        $error_persona = 1;
                    }
                    if ($model->nombre == '') {
                        $error_persona = 1;
                    }
                    if ($model->apellido == '') {
                        $error_persona = 1;
                    }

                    if ($error_persona == 0) {
                        $model_com_persona = new Sds_com_persona();
                        $model_com_persona->documento_tipo =
                            $model->documento_tipo;
                        $fecha_registro = ArmarDateParaMySql(
                            $model->fecha_nacimiento,
                            '00:00'
                        );
                        $fecha_registro = date_create($fecha_registro);
                        $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                        $model_com_persona->fecha_nacimiento = $fecha_registro;
                        $model_com_persona->documento = $model->documento;
                        $model_com_persona->nacionalidad = $model->nacionalidad;
                        $model_com_persona->genero = $model->genero;
                        $model_com_persona->nombre = $model->nombre;
                        $model_com_persona->apellido = $model->apellido;
                        $model_com_persona->conviviente = 0;
                        if (!$model_com_persona->save(false)) {
                            $guardado = false;
                            $transaction->rollBack();
                            $aux = 'Error en el guardado de la persona';
                        } else {
                            $model->idpersona = $model_com_persona->idpersona;
                        }
                    } else {
                        $aux =
                            'Error: Faltan datos de la persona, por lo tanto no se puede guardar, por lo tanto no se puede obtener un idpersona';
                        $guardado = false;
                        $transaction->rollBack();
                    }
                }

                if ($model->persona_retira == 0) {
                    if ($model->documento == $model->documento_retira) {
                        $model->persona_retira = $model->idpersona;
                    } else {
                        $error_persona = 0;
                        if ($model->documento_tipo_retira == null) {
                            $error_persona = 1;
                        }
                        if ($model->fecha_nacimiento_retira == '') {
                            $error_persona = 1;
                        }
                        if ($model->documento_retira == '') {
                            $error_persona = 1;
                        }
                        if ($model->nacionalidad_retira == null) {
                            $error_persona = 1;
                        }
                        if ($model->genero_retira == null) {
                            $error_persona = 1;
                        }
                        if ($model->nombre_retira == '') {
                            $error_persona = 1;
                        }
                        if ($model->apellido_retira == '') {
                            $error_persona = 1;
                        }

                        if ($error_persona == 0) {
                            $model_com_persona_retira = new Sds_com_persona();
                            $model_com_persona_retira->documento_tipo =
                                $model->documento_tipo_retira;
                            $fecha_registro = ArmarDateParaMySql(
                                $model->fecha_nacimiento_retira,
                                '00:00'
                            );
                            $fecha_registro = date_create($fecha_registro);
                            $fecha_registro = date_format(
                                $fecha_registro,
                                'Y-m-d'
                            );
                            $model_com_persona_retira->fecha_nacimiento = $fecha_registro;
                            $model_com_persona_retira->documento =
                                $model->documento_retira;
                            $model_com_persona_retira->nacionalidad =
                                $model->nacionalidad_retira;
                            $model_com_persona_retira->genero =
                                $model->genero_retira;
                            $model_com_persona_retira->nombre =
                                $model->nombre_retira;
                            $model_com_persona_retira->apellido =
                                $model->apellido_retira;

                            $model_com_persona_retira->domicilio_calle =
                                $model->calle_retira;
                            $model_com_persona_retira->domicilio_numero =
                                $model->numero_calle_retira;
                            $model_com_persona_retira->idlocalidad =
                                $model->localidad_retira;
                            if (!$model_com_persona_retira->save(false)) {
                                $guardado = false;
                                $transaction->rollBack();
                                $aux = 'Error en el guardado de la persona';
                            } else {
                                $model->persona_retira =
                                    $model_com_persona_retira->idpersona;
                            }
                        } else {
                            $aux =
                                'Error: Faltan datos de la persona, por lo tanto no se puede guardar, por lo tanto no se puede obtener un idpersona';
                            $guardado = false;
                            $transaction->rollBack();
                        }
                    }
                }

                //---------------------------------------------------------------------------------------------------------------

                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto_entrega');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = "adjunto_acta_entrega_" . $model->identrega . "." . $extension;
                    $model->adjunto_acta_entrega = $nombre;
                    $ruta = 'uploads/entregas';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $tmpfile->saveAs($ruta . '/' . $nombre);
                }

                if ($guardado && $model->save()) {

                    $this->verificar_referente($model->referente, $model->idpersona);
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_entrega', $model->identrega, $model->getAttributes());

                    $items_entrega = Sds_stk_entrega_item::find()->where("identrega=$model->identrega")->all();
                    $aux = "";

                    foreach ($items_entrega as $item_entrega) {
                        $aux_where = "item_entrega = $item_entrega->identregaitem and cantidad = $item_entrega->cantidad and tipo = 3";
                        $item_entrega_movimiento = Sds_stk_movimiento::find()->where($aux_where)->one();
                        $item_entrega_movimiento->fecha_hora = $model->fecha_hora;
                        if ($item_entrega_movimiento->validate()) {
                            if ($item_entrega_movimiento->save()) {
                                $aux = "Guardado";
                            } else {
                                $guardado = false;
                            }
                        } else {
                            $aux = "$aux <br> $item_entrega_movimiento->idmovimiento -  $item_entrega_movimiento->fecha_hora";
                            $aux = print_r($item_entrega_movimiento->getErrorSummary(true), true);
                        }
                    }
                    if ($guardado) {
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }

                    return [
                        'title' => "Se ha editado la entrega numero: $id",
                        'content' => $this->renderAjax('view', ['model' => $model,]),
                        //'content' => $aux,
                        'footer' =>
                        Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Entrega',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ) . ($generada ? '' :
                            Html::a(
                                'Añadir Articulos',
                                [
                                    'update',
                                    'id' => $model->identrega,
                                    'celular' => $celular ? true : false,
                                ],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            )),
                    ];
                } else {
                    $aux_guardado = $guardado ? 'true' : 'false';
                    $aux_guardado = "Datos de guardado: <br>guardado: $aux_guardado <br>organismo: $model->organismo<br>fecha_hora: $model->fecha_hora<br>idpersona: $model->idpersona<br>idcontacto: $model->idcontacto";
                    $aux_persona = "Datos de Persona: <br>documento_tipo: $model->documento_tipo<br>documento: $model->documento<br>nombre: $model->nombre<br>apellido: $model->apellido<br>fecha_nacimiento: $model->fecha_nacimiento<br>genero: $model->genero<br>nacionalidad: $model->nacionalidad<br>";
                    return [
                        'title' =>
                        "<p style='color:red'>No se ha guardado, revise los datos</p>",
                        'content' => "<div class='row'><div class='col-md-6'>$aux_guardado</div><div class='col-md-6'>$aux_persona</div></div><br><br><div class='row'><div class='col-md-6'><p style='color:red'>$aux</p></div></div>",
                        'footer' => Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Entrega',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ),
                    ];
                }
            } else {
                return [
                    'title' => 'Editar Entrega Numero ' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'generada' => $generada,
                    ]),
                    'footer' =>
                    Html::button(
                        $celular ? 'Crear Otra' : 'Cerrar Entrega',
                        [
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]
                    ) .
                        Html::button('Guardar Entrega', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_EDITAR,
                    'sds_stk_entrega',
                    $model->identrega,
                    $model->getAttributes()
                );
                return $this->redirect(['view', 'id' => $model->identrega]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'generada' => $generada,
                ]);
            }
        }
    }

    protected function verificar_referente($referente, $idpersona)
    {
        if ($referente) {
            $persona = Sds_com_persona::findOne($idpersona);
            $referente = Sds_ent_responsable::find()
                ->where(['dni' => $persona->documento])
                ->one();
            if ($referente == null) {
                $model_configuracion = new Sds_com_configuracion();
                $model_configuracion->idconfiguraciontipo = 44;
                $model_configuracion->descripcion = "$persona->apellido $persona->nombre";
                $model_configuracion->activo = 1;
                if ($model_configuracion->save(false)) {
                    $responsable = new Sds_ent_responsable();
                    $responsable->idresponsable =
                        $model_configuracion->idconfiguracion;
                    $responsable->mail = '';
                    $responsable->telefono = '';
                    $responsable->dni_frente = '';
                    $responsable->dni_dorso = '';
                    $responsable->dni = $persona->documento;
                    $responsable->save(false);
                }
            }
        }
    }
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            if ($model->delete() > 0) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_ELIMINAR,
                    'sds_stk_entrega',
                    $id,
                    $model->getAttributes()
                );
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Entrega Eliminada',
                'content' =>
                '<span class="text-danger">Se ha eliminado la entrega numero ' .
                    $id .
                    '</span>',
                'footer' => Html::button('Cerrar', [
                    'class' => 'btn btn-default pull-left col-md-offset-5',
                    'data-dismiss' => 'modal',
                ]),
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    /*  public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) { */
    /*
             *   Process for ajax request
             */
    /*  Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else { */
    /*
             *   Process for non-ajax request
             */
    /* return $this->redirect(['index']);
        }
    } */

    public function actionImprimir_acta_entrega($identrega)
    {
        $content = $this->renderPartial('imprimir_acta_entrega', [
            'identrega' => $identrega,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionGet_grilla_entregas(
        $responsable = null,
        $fecha_desde = null,
        $fecha_hasta = null,
        $observaciones = null,
        $id_orden_compra = null,
        $destinatario = null,
        $detalle_items = null,
        $idrecepcion = null,
        $organizacion_social = null
    ) {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $id_organismo = $usuario ? $usuario->organismo_stock : 0;

        if ($fecha_desde) {
            $fecha_desde = ArmarDateParaMySql($fecha_desde, '00:00');
        } else {
            $fecha_desde = ArmarDateParaMySql('01/01/2000', '00:00');
        }
        $fecha_desde = date('Y-m-d H:i', strtotime($fecha_desde));

        if ($fecha_hasta) {
            $fecha_hasta = ArmarDateParaMySql($fecha_hasta, '23:59');
        } else {
            $fecha_hasta = date('d/m/Y H:i:s');
        }

        $fecha_hasta = date('Y-m-d H:i', strtotime($fecha_hasta));

        $where_fechas = "and e.fecha_hora BETWEEN '$fecha_desde' AND '$fecha_hasta'";


        $consulta_responsables = "";
        if (is_array($responsable)) {
            $responsables = array();
            foreach ((array)$responsable as $resp) {
                array_push($responsables, "'" . $resp . "'");
            }
            $responsable_filter = implode(",", $responsables);
        } else {
            $responsable_filter = $responsable;
        }
        if ($responsable_filter) {
            $consulta_responsables = 'and e.idcontacto in (' . $responsable_filter . ')';
        }

        $consulta_oc = "";
        if ($id_orden_compra) {
            $consulta_oc = " and oci.idordencompra = $id_orden_compra ";
            $where_fechas = "";
        }

        $where_recepcion = "";
        if ($organizacion_social) {
            $where_recepcion = " and e.organizacion_social = $organizacion_social ";
            $where_fechas = "";
        }

        $where_destinatario = "";
        if ($destinatario) {
            $where_destinatario = " and e.idpersona = $destinatario ";
        }

        $where_detalle_items = "";
        if ($detalle_items) {
            $where_detalle_items = " and a.descripcion like '%$detalle_items%' ";
        }

        $where_recepcion = "";
        if ($idrecepcion) {
            $where_recepcion = " and ri.idrecepcion = $idrecepcion ";
            $where_fechas = "";
        }

        $where_recepcion = "";
        if ($organizacion_social) {
            $where_recepcion = " and e.organizacion_social = $organizacion_social ";
            $where_fechas = "";
        }

        $consulta_fechas = "SELECT e.identrega,date_format(e.fecha_hora, '%d/%m/%Y') as fecha_hora, 
                            CONCAT(p.nombre,' ',p.apellido) as responsable, 
                            CONCAT(pd.documento,' - ',pd.nombre,' ',pd.apellido) as apellido, 
                            e.observaciones as observaciones,
                            group_concat(a.idarticulo separator '|') idarticulos,
                            group_concat(a.descripcion separator '|') as articulos, 
                            group_concat(ei.cantidad separator '|') as cantidades,
                            group_concat(ROUND(ifnull(oci.idordencompraitem,0)) separator '|') as idocitems,
                            group_concat(ROUND(ifnull(oci.cantidad,0)) separator '|') as cantidades_oc,
                            e.referente,e.organizacion_social
                            FROM sds_stk_entrega e
                            INNER JOIN mds_org_contacto c on e.idcontacto = c.idcontacto
                            INNER JOIN sds_com_persona p on c.idpersona = p.idpersona
                            INNER JOIN sds_com_persona pd on e.idpersona = pd.idpersona
                            INNER JOIN sds_stk_entrega_item ei on e.identrega = ei.identrega
                            INNER JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
                            INNER JOIN sds_stk_articulo a on ri.idarticulo = a.idarticulo
                            left JOIN sds_stk_orden_compra_item oci on ri.idordencompraitem = oci.idordencompraitem
                            WHERE e.organismo = $id_organismo 
                            $where_fechas
                            and e.observaciones like '%$observaciones%'
                            $consulta_oc
                            $consulta_responsables
                            $where_destinatario
                            $where_detalle_items
                            $where_recepcion
                            group by date_format(e.fecha_hora, '%d/%m/%Y'),p.idpersona,e.identrega
                            order by e.fecha_hora,p.nombre,p.apellido,a.descripcion";

        //return $consulta_fechas;

        $entregas = Sds_stk_entrega::findBySql($consulta_fechas)->all();

        $tabla_titulos = "<table class='table table-striped table-hover' style='width:100%'>";
        $tabla_titulos = "$tabla_titulos<tr style='color:#0088cc;'>";
        $tabla_titulos = "$tabla_titulos<th>Fecha</th><th>Responsable</th><th>Destinatario</th>";
        $articulos = array();
        $items_oc = array();
        foreach ($entregas as $entrega) {
            $idarticulos = explode("|", $entrega->idarticulos);
            $descripciones = explode("|", $entrega->articulos);
            $idocitems = explode("|", $entrega->idocitems);
            $cantidades_oc = explode("|", $entrega->cantidades_oc);
            for ($index = 0; $index < sizeof($idarticulos); $index++) {
                //Genero articulos correspondientes para encabezados
                $articulo = new Sds_stk_articulo();
                $articulo->idarticulo = $idarticulos[$index];
                $articulo->descripcion = $descripciones[$index];
                $articulo->disponible = 0;
                $articulo->ingresado = 0;
                $existe = false;
                foreach ($articulos as $art_exist) {
                    if ($art_exist->idarticulo == $articulo->idarticulo) {
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    array_push($articulos, $articulo);
                }
                //Genero los items OC para cantidades en encabezados por articulo
                $item_oc = new Sds_stk_orden_compra_item();
                $item_oc->idordencompraitem = $idocitems[$index];
                $item_oc->cantidad = $cantidades_oc[$index];
                $item_oc->idarticulo = $idarticulos[$index];
                $existe = false;
                foreach ($items_oc as $it_oc) {
                    if ($it_oc->idordencompraitem == $item_oc->idordencompraitem) {
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    array_push($items_oc, $item_oc);
                }
            }
        }

        $tabla = "";
        foreach ($entregas as $entrega) {
            $os = $entrega->organizacion_social ? ' (' . Sds_com_configuracion::findOne($entrega->organizacion_social)->descripcion . ')' : '';
            $tabla = "$tabla<tr>";
            $tabla = "$tabla<td>$entrega->fecha_hora</td>";
            $tabla = "$tabla<td>$entrega->responsable</td>";
            $tabla = "$tabla<td>$entrega->apellido" . ($entrega->referente ? " <b>(R)</b>" : "") . ($entrega->observaciones ? " ($entrega->observaciones)" : "") . "$os</td>";
            foreach ($articulos as $articulo) {
                $idarticulos = explode("|", $entrega->idarticulos);
                $cantidades = explode("|", $entrega->cantidades);
                $cantidad = 0;
                for ($index = 0; $index < sizeof($idarticulos); $index++) {
                    $idarticulo = $idarticulos[$index];
                    if ($articulo->idarticulo == $idarticulo) {
                        $cantidad = $entrega->apellido != "TOTAL" ?  $cantidad + $cantidades[$index] : $articulo->disponible;
                    }
                }
                $articulo->disponible += $cantidad;
                $tabla = "$tabla<td>" . ($cantidad > 0 ? $cantidad : "") . "</td>";
            }
            $tabla = "$tabla</tr>";
        }
        $tabla = "$tabla<tr>";
        $tabla = "$tabla<td></td>";
        $tabla = "$tabla<td></td>";
        $tabla = "$tabla<td><b>TOTAL</b></td>";
        foreach ($articulos as $articulo) {
            $tabla = "$tabla<td><b>$articulo->disponible</b></td>";
        }


        foreach ($articulos as $articulo) {
            $cantidad_oc = 0;
            foreach ($items_oc as $item_oc) {
                if ($item_oc->idarticulo == $articulo->idarticulo) {
                    $cantidad_oc += $item_oc->cantidad;
                }
            }
            $tabla_titulos = "$tabla_titulos<th>$articulo->descripcion" .
                "($articulo->disponible" . ($cantidad_oc > 0 ? "/$cantidad_oc" : "") . ")</th>"; //los tr son las lineas, th son para los titulos, td para datos
        }

        $tabla_titulos = "$tabla_titulos</tr>";
        $tabla = "$tabla</tr>";

        $tabla = "$tabla</table>";
        return "$tabla_titulos$tabla";
        //return "$tabla_titulos $tabla <br> $consulta_fechas <br>" . print_r($items_oc, true);
    }

    public static function actionGet_grilla_responsables(
        $desde = null,
        $hasta = null,
        $idarticulo = -1,
        $idcontacto = -1
    ) {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $idorganismo = 0;
        if ($usuario->organismo_stock) {
            $idorganismo = $usuario->organismo_stock;
        }
        $desde = $desde != null ? "'" . date_format(date_create(str_replace('/', '-', $desde)), 'Y-m-d H:i:s') . "'" : "null";
        $hasta = $hasta != null ? "'" . date_format(date_create(str_replace('/', '-', $hasta)), 'Y-m-d H:i:s') . "'" : "null";
        $idarticulo = $idarticulo != null ? "($idarticulo)" : "(0)";
        $idcontacto = $idcontacto != null ? $idcontacto : "-1";
        /* select idcontacto,contacto,
        CONCAT('[',group_concat(CONCAT('{"idarticulo":',idarticulo,
        ',"descripcion":"',articulo,'","cantidad":',cantidad,'}') separator ','),']') idarticulos
        from (select er.idcontacto, er.contacto, er.organismo,er.idarticulo, 
                er.articulo,sum(er.cantidad) cantidad
        from view_stock_detalle_ent_resp er
        where DATEDIFF('2023-01-01',er.fecha_hora)<0
        and DATEDIFF(er.fecha_hora,'2023-02-01')<0
        and er.organismo=112
        group by er.idcontacto,er.idarticulo) temp
        group by idcontacto */

        $dataProvider = new ActiveDataProvider([
            'query' => View_stock_detalle_ent_resp::findBySql(
                "select idcontacto,contacto,group_concat(CONCAT(idarticulo,',',articulo,',',cantidad) separator '|') articulo
                from (select er.idcontacto, er.contacto, er.organismo,er.idarticulo, 
                        er.articulo,sum(er.cantidad) cantidad
                from view_stock_detalle_ent_resp er
                where ($desde is null or DATEDIFF($desde, er.fecha_hora)<=0) 
                and ($hasta is null or DATEDIFF(er.fecha_hora, $hasta)<=0)
                and er.organismo=$idorganismo and
                (idarticulo in $idarticulo or 0 in $idarticulo) and
                ($idcontacto=idcontacto or $idcontacto<0)
                group by er.idcontacto,er.idarticulo) temp
                group by idcontacto"
            )
        ]);
        $tabla = "<table class='table table-striped table-bordered table-hover' style='width:100%;border-collapse: separate;'>";
        $tabla = "$tabla<thead><tr><th style='position: sticky; top:0px;border: 1px solid #ddd;
        background-color: #FFF;'><span class='col-md-4' 
        style='padding-top:8px;'>Responsable</span></th>";
        $headers_articulos = array();
        foreach ($dataProvider->getModels() as $ent_responsable) {
            $array_articulos = explode('|', $ent_responsable->articulo);
            foreach ($array_articulos as $articulo) {
                $articulo = explode(',', $articulo);
                if (!in_array($articulo[1], $headers_articulos)) {
                    array_push($headers_articulos, $articulo[1]);
                }
            }
        }
        foreach ($headers_articulos as $header) {
            $tabla = "$tabla<th style='position: sticky; top:0px;border: 1px solid #ddd;
            background-color: #FFF;'><span class='col-md-4' 
            style='padding-top:8px;'>$header</span></th>";
        }
        $tabla = $tabla . "</tr></thead><tbody>";
        foreach ($dataProvider->getModels() as $ent_responsable) {
            $array_articulos = explode('|', $ent_responsable->articulo);
            $tabla = "$tabla<tr><td style='text-align:left'>$ent_responsable->contacto</td>";
            foreach ($headers_articulos as $header) {
                $cantidad = 0;
                foreach ($array_articulos as $articulo) {
                    $articulo = explode(',', $articulo);
                    if ($articulo[1] == $header) {
                        $cantidad = $articulo[2];
                        break;
                    }
                }
                $tabla = $tabla . "<td style='text-align:left'>" . $cantidad . "</td>";
            }
            $tabla = $tabla . "</tr>";
        }
        $tabla = "$tabla</tbody></table>";
        //return $consulta;

        return $tabla;
    }

    public function actionImprimir_reporte_entregas(
        $responsable = null,
        $fecha_desde = null,
        $fecha_hasta = null,
        $observaciones = null,
        $id_orden_compra = null,
        $destinatario = null,
        $detalle_items = null,
        $idrecepcion = null,
        $organizacion_social = null
    ) {
        /* if ($rubro == null) {
                $tabla = $this->actionGet_grilla_stock_general(null);
            } else {
                $tabla = $this->actionGet_grilla_stock_general($rubro);
            } */
        $tabla = $this->actionGet_grilla_entregas($responsable, $fecha_desde, $fecha_hasta, $observaciones, $id_orden_compra, $destinatario, $detalle_items, $idrecepcion, $organizacion_social);
        //$tabla = "$fecha_desde $fecha_hasta";

        $content = $this->renderPartial('imprimir_reporte_entregas', [
            'tabla' => $tabla,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta,
            'responsable' => $responsable,
            'id_orden_compra' => $id_orden_compra,
            'idrecepcion' => $idrecepcion,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/style_table.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'REPORTE ENTREGAS',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }
    protected function findModel($id)
    {
        if (($model = Sds_stk_entrega::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                'The requested page does not exist.'
            );
        }
    }

    public function actionGenerar_ei($identrega)
    {


        $items = Sds_stk_entrega_item::find()->where("identrega=$identrega")->all();
        $ban = 1;
        $mensaje = "";
        foreach ($items as $item) {
            $ri = Sds_stk_recepcion_item::findOne($item->recepcion_item);
            $articulo = Sds_stk_articulo::findOne($ri->idarticulo);
            if ($articulo->idtipo == null) {
                $ban = 0;
                $mensaje = "<p style='color: red;'>No se realizo la entrega intermedia porque hay articulos sin tipo.</p>";
            }
        }
        $model = $this->findModel($identrega);
        $p = Sds_com_persona::findOne($model->idpersona);
        $ser = Sds_ent_responsable::find()->where("dni=$p->documento")->one();
        if ($ser == null) {
            $ban = 0;
            $mensaje = "<p style='color: red;'>No se realizo la entrega intermedia porque falta el responsable en la tabla sds_ent_responsable sin tipo.</p>";
        }

        if ($ban == 1) {
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : 0;
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($items as $item) {
                $model_ent_entrega = new Sds_ent_entrega();

                $ri = Sds_stk_recepcion_item::findOne($item->recepcion_item);
                $recepcion = Sds_stk_recepcion::findOne($ri->idrecepcion);
                $oc = Sds_stk_orden_compra::findOne($recepcion->idordencompra);

                $fecha_oc = date_create("$oc->fecha_orden_compra 00:00");
                $fecha_oc_emision = date_create("$oc->fecha_emision 00:00");
                $fecha_ent_entrega = date_create("$model->fecha_hora");

                $fecha_dif = $fecha_oc->diff($fecha_oc_emision); //esta funcion resta fechas
                $aux = $fecha_dif->format("%R%a"); //el intervalo resultante es tipo date y para verlo nesesito formatearlo
                $fecha_ent_entrega = date_add($fecha_ent_entrega, date_interval_create_from_date_string("$aux days"));
                //para sumar uso el date add, y pasarle el intervalo en string, el cual convierte con ese chorizo
                $model_ent_entrega->fecha_hora = date_format($fecha_ent_entrega, "Y-m-d H:i");

                $model_ent_entrega->cantidad = $item->cantidad;

                $ri = Sds_stk_recepcion_item::findOne($item->recepcion_item);
                $articulo = Sds_stk_articulo::findOne($ri->idarticulo);

                $model_ent_entrega->idtipo = $articulo->idtipo ? $articulo->idtipo : 0;
                $model_ent_entrega->observaciones = $articulo->observaciones;
                $model_ent_entrega->idusuario = $idusuario;
                $model_ent_entrega->emisor = $ri->identrega;
                $model_ent_entrega->receptor = $ser->idresponsable;

                $model_ent_entrega->save(false);
                $item->entrega_rendicion = $model_ent_entrega->identrega;
                $item->save();
            }

            $model->generada = 1;
            if ($model->save(false)) {
                $transaction->commit();
            }
            $mensaje = "<p style='color: green;'>Primer entrega intermedia creada</p>";
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Entrega Intermedia",
            'content' => $mensaje,
            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    public function actionGenerar_ef($identrega)
    {
        $items = Sds_stk_entrega_item::find()->where("identrega=$identrega")->all();
        $ban = 1;
        $mensaje = "";
        foreach ($items as $item) {
            $ri = Sds_stk_recepcion_item::findOne($item->recepcion_item);
            $articulo = Sds_stk_articulo::findOne($ri->idarticulo);
            if ($articulo->idtipo == null) {
                $ban = 0;
                $mensaje = "<p style='color: red;'>No se realizo la entrega final porque hay articulos sin tipo.</p>";
            }
        }

        if ($ban == 1) {
            $model = $this->findModel($identrega);

            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : 0;
            $transaction = Yii::$app->db->beginTransaction();
            $p = Sds_com_persona::findOne($model->idpersona);

            foreach ($items as $item) {
                $model_ent_entrega = new Sds_ent_entrega();

                $ri = Sds_stk_recepcion_item::findOne($item->recepcion_item);
                $recepcion = Sds_stk_recepcion::findOne($ri->idrecepcion);
                $oc = Sds_stk_orden_compra::findOne($recepcion->idordencompra);

                $fecha_oc = date_create("$oc->fecha_orden_compra 00:00");
                $fecha_oc_emision = date_create("$oc->fecha_emision 00:00");
                $fecha_ent_entrega = date_create("$model->fecha_hora");

                $fecha_dif = $fecha_oc->diff($fecha_oc_emision); //esta funcion resta fechas
                $aux = $fecha_dif->format("%R%a"); //el intervalo resultante es tipo date y para verlo nesesito formatearlo
                $fecha_ent_entrega = date_add($fecha_ent_entrega, date_interval_create_from_date_string("$aux days"));
                //para sumar uso el date add, y pasarle el intervalo en string, el cual convierte con ese chorizo
                $model_ent_entrega->fecha_hora = date_format($fecha_ent_entrega, "Y-m-d H:i");

                $model_ent_entrega->cantidad = $item->cantidad;
                $model_ent_entrega->dni = $p->documento;
                $ri = Sds_stk_recepcion_item::findOne($item->recepcion_item);
                $articulo = Sds_stk_articulo::findOne($ri->idarticulo);
                $model_ent_entrega->idtipo = $articulo->idtipo ? $articulo->idtipo : 0;
                $model_ent_entrega->observaciones = $articulo->observaciones;
                $model_ent_entrega->idusuario = $idusuario;
                $model_ent_entrega->emisor = $ri->identrega;
                $model_ent_entrega->idpersona = $p->idpersona;

                $model_ent_entrega->save(false);
                $item->identrega = $model_ent_entrega->identrega;
                $item->save();
            }

            $model->generada = 1;
            if ($model->save(false)) {
                $transaction->commit();
            }
            $mensaje = "<p style='color: green;'>Entrega Final Generado</p>";
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Entrega Final",
            'content' => $mensaje,
            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }
}
function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}
