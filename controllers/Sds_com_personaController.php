<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_com_persona;
use app\models\Sds_com_personaSearch;
use app\models\Sds_vio_intervencion;
use app\models\Sds_800_llamada;
use app\models\Mds_legales_oficio_vinculado;

/**
 * Sds_com_personaController implements the CRUD actions for Sds_com_persona model.
 */
class Sds_com_personaController extends Controller
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
                    'delete_hijo' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'view', 'logout',
                    'get_xroad_ren', 'get_persona_by_dni_and_tipo'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete', 'update', 'view', 'logout',
                            'get_xroad_ren', 'get_persona_by_dni_and_tipo'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::COM_PERSONA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_com_persona models.
     * @return mixed
     */
    public function actionIndex($get_query = null)
    {
        $searchModel = new Sds_com_personaSearch();
        //Se utiliza para aplicar las subquerys cargadas en Sds_com_persona_georef_query
        if ($get_query != null) {
            $searchModel->georeferencia_query = 1;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona', null, []);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sds_com_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $current_url)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_com_persona',
            $id,
            []
        );
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Sds_com_persona #' . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' =>
                Html::button('Close', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]) .
                    Html::a(
                        'Edit',
                        ['update', 'id' => $id],
                        ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                    ),
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
                'current_url' => $current_url,
            ]);
        }
    }

    /**
     * Creates a new Sds_com_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idpadre = null)
    {
        $request = Yii::$app->request;
        $model = new Sds_com_persona();
        $model->padre = $idpadre;
        if ($request->isAjax) {
            /*
         *   Process for ajax request
         */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Asignar Nuevo Hijo',
                    'content' => $this->renderAjax('create_hijo', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::a(
                        ' Volver',
                        ['index_hijos', 'idpadre' => $idpadre],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info pull-left',
                        ]
                    ) .
                        Html::button('Guardar', [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($request->isPost) {
                $model->load($request->post());
                // Buscamos si ya existe la persona
                $modelPersona = Sds_com_persona::find()
                    ->where(['documento' => $model['documento']])
                    ->one();
                if ($modelPersona) {
                    // Ya existe, actualizamos idpadre
                    $modelPersona->load($request->post());
                    $modelPersona->fecha_nacimiento = date(
                        'Y-m-d',
                        strtotime(
                            str_replace('/', '-', $model['fecha_nacimiento'])
                        )
                    );
                    $modelPersona->documento_tipo = '83';
                    $modelPersona->padre = $idpadre;
                    $savePersona = $modelPersona->save();
                } else {
                    // No existe
                    $model->fecha_nacimiento = date(
                        'Y-m-d',
                        strtotime(
                            str_replace('/', '-', $model['fecha_nacimiento'])
                        )
                    );
                    $model->documento_tipo = '83';
                    $savePersona = $model->save();
                }
                if ($savePersona) {
                    // Guardo persona correctamente
                    Mds_sys_log::guardarLog(
                        Mds_sys_log::ACCION_NUEVO,
                        'sds_com_persona',
                        $model->idpersona,
                        $model->getAttributes()
                    );
                    return [
                        'title' => 'Asignar Nuevo Hijo',
                        'content' =>
                        '<span class="text-success">Hijo asignado exitosamente!</span>',
                        'footer' =>
                        Html::a(
                            ' Volver a la Grilla',
                            ['index_hijos', 'idpadre' => $idpadre],
                            [
                                'role' => 'modal-remote',
                                'class' => 'btn btn-info pull-left',
                            ]
                        ) .
                            Html::a(
                                'Agregar Otro',
                                ['create', 'idpadre' => $idpadre],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            ),
                    ];
                } else {
                    // Mostramos mensaje de error
                    //TODO: Deberia almacenar log en algun lado?
                    return [
                        'title' => 'Asignar Nuevo Hijo',
                        'content' =>
                        '<span class="text-error">Error al asignar hijo!</span>',
                        'footer' =>
                        Html::a(
                            ' Volver a la Grilla',
                            ['index_hijos', 'idpadre' => $idpadre],
                            [
                                'role' => 'modal-remote',
                                'class' => 'btn btn-info pull-left',
                            ]
                        ) .
                            Html::a(
                                'Agregar Otro',
                                ['create', 'idpadre' => $idpadre],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            ),
                    ];
                }
            }
            return [
                'title' => 'Asignar Nuevo Hijo',
                'content' => $this->renderAjax('create_hijo', [
                    'model' => $model,
                ]),
                'footer' =>
                Html::a(
                    ' Volver',
                    ['index_hijos', 'idpadre' => $idpadre],
                    [
                        'role' => 'modal-remote',
                        'class' => 'btn btn-info pull-left',
                    ]
                ) .
                    Html::button('Guardar', [
                        'class' => 'btn btn-primary',
                        'type' => 'submit',
                    ]),
            ];
        } else {
            /*
         *   Process for non-ajax request
         */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_NUEVO,
                    'sds_com_persona',
                    $model->idpersona,
                    $model->getAttributes()
                );
                return $this->redirect(['view', 'id' => $model->idpersona]);
            } else {
                return $this->render('create_hijo', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_com_persona model.
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
            if ($request->isGet) {
                return [
                    'title' => 'Actualizar Hijo #' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::a(
                        ' Volver',
                        ['index_hijos', 'idpadre' => $model->padre],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info pull-left',
                        ]
                    ) .
                        Html::button('Guardar', [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post())) {
                $model->fecha_nacimiento = date(
                    'Y-m-d',
                    strtotime(str_replace('/', '-', $model->fecha_nacimiento))
                );
                $model->documento_tipo = '83';
                if ($model->save()) {
                    Mds_sys_log::guardarLog(
                        Mds_sys_log::ACCION_EDITAR,
                        'sds_com_persona',
                        $model->idpersona,
                        $model->getAttributes()
                    );
                    $searchModel = new Sds_com_personaSearch();
                    $searchModel->padre = $model->padre;
                    $dataProvider = $searchModel->search(
                        Yii::$app->request->queryParams
                    );
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $padre = Sds_com_persona::findOne($model->padre);
                    return [
                        'title' =>
                        $padre->nombre .
                            ' ' .
                            $padre->apellido .
                            ' - Listado de Hijos',
                        'content' => $this->renderAjax('index_hijos', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]),
                        'footer' => Html::button('Cerrar', [
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]),
                    ];
                }
            }
            return [
                'title' => 'Actualizar Sds_com_persona #' . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' =>
                Html::button('Close', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]) .
                    Html::button('Save', [
                        'class' => 'btn btn-primary',
                        'type' => 'submit',
                    ]),
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_EDITAR,
                    'sds_com_persona',
                    $model->idpersona,
                    $model->getAttributes()
                );
                return $this->redirect(['view', 'id' => $model->idpersona]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_com_persona model.
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
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_ELIMINAR,
                'sds_com_persona',
                $id,
                $model->getAttributes()
            );
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    public function actionIndex_hijos($idpadre = 0, $idllamada = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                $searchModel = new Sds_com_personaSearch();
                $searchModel->padre = $idpadre;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $padre = Sds_com_persona::findOne($idpadre);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona/index_hijos', $idpadre, []);
                $model_llamada = Sds_800_llamada::findOne($idllamada);
                $estaAtendida = $model_llamada ? $model_llamada->estado == Sds_800_llamada::ESTADO_ATENDIDA : true;
                $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => $padre->nombre . ' ' . $padre->apellido . ' - Listado de Hijos',
                    'content' => $this->renderAjax('index_hijos', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'estaAtendida' => $estaAtendida,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral,
                    ]),
                    'footer' => Html::button('Cerrar', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]),
                ];
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionView_hijo($id)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();

        if ($hasRolViolencia) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona/view_hijos', $id, array());
            $request = Yii::$app->request;
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Persona",
                    'content' => $this->renderAjax('view_hijos', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Set attribute padre null of existing Sds_com_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete_hijo($id)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;

            if ($request->isAjax) {
                /*
                *   Process for non-ajax request
                */
                $model = $this->findModel($id);
                $idpadre = $model->padre;
                $model->padre = null;
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona/delete_hijo', $id, $model->getAttributes());
                }

                // Vuelve al index_hijos
                $searchModel = new Sds_com_personaSearch();
                $searchModel->padre = $idpadre;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $padre = Sds_com_persona::findOne($idpadre);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona/index_hijos', $idpadre, []);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => $padre->nombre . ' ' . $padre->apellido . ' - Listado de Hijos',
                    'content' => $this->renderAjax('index_hijos', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]),
                    'footer' => Html::button('Cerrar', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]),
                ];
            } else {
                /*
                *   Process for non-ajax request
                */
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete multiple existing Sds_com_persona model.
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
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Sds_com_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_com_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_com_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                'The requested page does not exist.'
            );
        }
    }

    public function actionGet_xroad_ren($dni)
    {
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
            'https://apisur.neuquen.gov.ar/index.php'
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $records = curl_exec($ch);
        if ($records) {
            $records = json_decode($records);
        }

        if ($records == null) {
            return 'error';
        }
        //return $records;
        $token = $records->records[0]->token;
        curl_close($ch);
        if ($token) {
            $data = [
                'servicio' => 'get_renaper',
                'auditoria' => $usuario,
                'usuario_auditoria' => $usuario,
                'filtro' => 'documento=' . $dni,
                'tipo' => 0,
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                'https://apisur.neuquen.gov.ar/index.php'
            );
            $authorization = 'Authorization: Bearer ' . $token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$authorization]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            /* $info = curl_getinfo($ch);
             return $info; */
            $records = curl_exec($ch);
            // Se guardan logs para el finde
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona/actionGet_xroad_ren', $dni, []);

            if ($records) {
                $records = json_decode($records);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona/actionGet_xroad_ren', $dni, $records);
            }
            curl_close($ch);
        }
        return $records;
    }

    public function actionGeoreferencia($id, $current_url)
    {
        $request = Yii::$app->request;
        $model = Sds_com_persona::find()->select([
            'p.*', "CONCAT(escuela,' | ',escuela_nombre) lugar_voto"
        ])->from('sds_com_persona p')
            ->leftJoin('mds_pad_padron2021 pad', 'pad.documento=p.documento')
            ->where(["p.idpersona" => $id])
            ->one();
        $renaper = $this->actionGet_xroad_ren($model->documento);
        Yii::$app->response->format = Response::FORMAT_RAW;
        if ($renaper->status == 'success') {
            $renaper = $renaper->records[0]->result;
        } else {
            $renaper = null;
        }
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $model->domicilio_calle = $request->post('Sds_com_persona')['domicilio_calle'];
                $model->domicilio_numero = $request->post('Sds_com_persona')['domicilio_numero'];
                $model->idlocalidad = $request->post('Sds_com_persona')['idlocalidad'];
                if ($model->domicilio_calle != null && $model->domicilio_numero != null && $model->idlocalidad != null) {
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                        return $this->redirect($current_url);
                    }
                } else {
                    Yii::$app->session->setFlash('error', "¡Faltan datos! Por favor compruebe si escribió algo en Calle, Nº y Localidad.");
                }
            }
        }
        return $this->render(
            'georeferencia',
            [
                'model' => $model,
                'renaper' => $renaper
            ]
        );
    }

    public function actionValidar_dni($dni)
    {
        $result = [];
        $model_persona = Sds_com_persona::find()
            ->where(['documento' => $dni])
            ->one();
        if ($model_persona != null) {
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_CONSULTA,
                'sds_com_persona/validar_dni',
                $dni,
                []
            );
            array_push($result, $model_persona->getAttributes());
        }
        return json_encode($result);
    }

    public function actionGet_persona_by_dni_and_tipo($idTipoDocumento, $dni, $llamadoDesde = null, $idlegalesoficio = null)
    {
        /*
        Esta funcion es para ser llamada por JS.
        */
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = Sds_com_personaController::getPersonaByDniAndTipo($idTipoDocumento, $dni, $llamadoDesde, $idlegalesoficio);
        return json_encode($result);
    }

    public static function getPersonaByDniAndTipo($idTipoDocumento, $dni, $llamadoDesde = null, $idlegalesoficio = null)
    {
        //llamadoDesde se puede utilizar para agregar casos al switch y buscar en otra tabla temporal donde esten los datos de la persona
        $success = true;
        $data = [];
        $message = "";
        $esDniRepetido = false;
        $responseRenaper = null;
        $responseTablaTemporal = null;

        $persona = Sds_com_persona::find()->where("documento = '$dni'")->orderBy(['idpersona' => SORT_DESC])->one();
        $where = "documento = '$dni' AND idtipodocumento = $idTipoDocumento";

        if ($persona) {
            //Si existe la persona en sds_com_persona debo buscar por el idpersona, sino por el documento y el tipo de documento
            $where = "idpersona = {$persona['idpersona']}";
        }

        if ($llamadoDesde) {
            switch ($llamadoDesde) {
                case 'LEGALES':
                    if ($idlegalesoficio) {
                        //Debo verificar que la persona que se quiere cargar no esta repetida
                        $modelOficioVinculado = new Mds_legales_oficio_vinculado();
                        $esDniRepetido = $modelOficioVinculado->checkDniRepetido($dni, $idlegalesoficio);
                    }

                    if (!$esDniRepetido) {
                        $responseTablaTemporal = Mds_legales_oficio_vinculado::find()->where($where)->orderBy(['idlegalesoficiovinculado' => SORT_DESC])->one();
                    }
                    break;
                default:
                    break;
            }
        }

        if (!$esDniRepetido) {
            if ($persona) {
                // Encuentra la persona
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_CONSULTA,
                    'sds_com_persona/getPersonaByDniAndTipo',
                    $dni,
                    []
                );
                $persona = $persona->getAttributes();
                $where = "idpersona = {$persona['idpersona']}";
            } else {
                // No tiene com persona, debemos buscar en renaper
                $responseRenaper = Sds_com_personaController::actionGet_xroad_ren($dni);
                if ($responseRenaper && $responseRenaper->status == 'success') {
                    $responseRenaper = $responseRenaper->records[0]->result;
                } else {
                    $message = $responseRenaper && $responseRenaper->message ? "<b>$responseRenaper->message</b>" : '<b>Error!</b> No se pudo conectar con el servicio.';
                    $responseRenaper = null;
                }
            }

            if ($persona || $responseRenaper || $responseTablaTemporal) {
                if ($persona) {
                    $idPersona = $persona['idpersona'];
                    $apellido = $persona['apellido'];
                    $nombre = $persona['nombre'];
                    $genero = $persona['genero'];
                    $nacionalidad = $persona['nacionalidad'];
                    $fechaNacimiento = $persona['fecha_nacimiento'] ? date_format(date_create($persona['fecha_nacimiento']), 'd/m/Y') : '';
                    $docimilioCalle = $persona['domicilio_calle'];
                    $docimilioNumero = $persona['domicilio_numero'];
                } else if ($responseRenaper) {
                    $idPersona = null;
                    $apellido = $responseRenaper->apellido;
                    $nombre = $responseRenaper->nombres;
                    $genero = Sds_com_personaController::mapRenaperToSurGenero($responseRenaper->genero);
                    $nacionalidad = Sds_com_personaController::mapRenaperToSurNacionalidad($responseRenaper->nacionalidad);
                    $fechaNacimiento = $responseRenaper->fecha_nacimiento;
                    $docimilioCalle = $responseRenaper->calle;
                    $docimilioNumero = $responseRenaper->numero;
                } else if ($responseTablaTemporal) {
                    $idPersona = null;
                    $genero = null;
                    $nacionalidad = null;
                    $fechaNacimiento = null;
                    $apellido = $responseTablaTemporal['apellido'];
                    $nombre = $responseTablaTemporal['nombre'];
                    $docimilioCalle = $responseTablaTemporal['domicilio_calle'];
                    $docimilioNumero = $responseTablaTemporal['domicilio_numero'];
                }

                $mail = $responseTablaTemporal ? $responseTablaTemporal['mail'] : '';
                $telefono = $responseTablaTemporal ? $responseTablaTemporal['telefono'] : '';
                $observaciones = $responseTablaTemporal ? $responseTablaTemporal['observaciones'] : '';

                $data =  [
                    'idpersona' => $idPersona,
                    'apellido' => $apellido,
                    'nombre' => $nombre,
                    'genero' => $genero,
                    'nacionalidad' => $nacionalidad,
                    'fecha_nacimiento' => $fechaNacimiento,
                    'domicilio_calle' => $docimilioCalle,
                    'domicilio_numero' => $docimilioNumero,
                    'mail' => $mail,
                    'telefono' => $telefono,
                    'observaciones' => $observaciones,
                ];
            } else {
                $success = false;
            }
        }


        $response = [
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'repetido' => $esDniRepetido
        ];
        return $response;
    }

    public static function mapRenaperToSurGenero($genero)
    {
        $mapeo = null;
        switch ($genero) {
            case 'F':
                $mapeo = 81;
                break;
            case 'M':
                $mapeo = 82;
                break;
            default:
                break;
        }
        return $mapeo;
    }

    public static function mapRenaperToSurNacionalidad($nacionalidad)
    {
        $mapeo = null;
        switch ($nacionalidad) {
            case 'ARGENTINA':
                $mapeo = 70;
                break;
            case 'BOLIVIANA':
                $mapeo = 71;
                break;
            case 'BRASILEñA':
                $mapeo = 72;
                break;
            case 'CHILENA':
                $mapeo = 73;
                break;
            case 'COLOMBIANA':
                $mapeo = 74;
                break;
            case 'ECUATORIANA':
                $mapeo = 75;
                break;
            case 'PARAGUAYA':
                $mapeo = 76;
                break;
            case 'PERUANA':
                $mapeo = 77;
                break;
            case 'URUGUAYA':
                $mapeo = 78;
                break;
            case 'VENEZOLANA':
                $mapeo = 79;
                break;
            case 'DOMINICANA':
                $mapeo = 4295;
                break;
            default:
                break;
        }
        return $mapeo;
    }
}
