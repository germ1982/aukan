<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use Yii;
use app\models\Sds_stk_entrega_solicitud;
use app\models\Sds_stk_entrega_solicitudSearch;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_stk_entrega_solicitudController implements the CRUD actions for Sds_stk_entrega_solicitud model.
 */
class Sds_stk_entrega_solicitudController extends Controller
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
                    'index', 'create', 'update', 'view',
                    'get_persona', 'set_persona',
                    'get_formpersona', 'set_formpersona', 'manage_items',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'view',
                            'get_persona', 'set_persona',
                            'get_formpersona', 'set_formpersona', 'manage_items',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_ENTREGA],
                    ],
                ],

            ],
        ];
    }

    /**
     * Lists all Sds_stk_entrega_solicitud models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_stk_entrega_solicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->user->identity->organismo_stock == null) {
            Yii::$app->session->setFlash('error_modulo', "Usted no posee permisos para ingresar al módulo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_stk_entrega_solicitud model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_entrega_solicitud', $model->identregasolicitud, $model->getAttributes());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => '',
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_stk_entrega_solicitud model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_entrega_solicitud();
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model->idorganismo = Yii::$app->user->identity->organismo_stock;
        if ($request->isAjax) {
            if ($model->load($request->post())) {
                if ($model->idpersona == null) {
                    $persona = new Sds_com_persona();
                    $persona->conviviente = 0;
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($request->post('Sds_com_persona') != null) {
                            $persona->documento_tipo = Sds_com_persona::TIPO_DNI;
                            $persona->documento = $model->dni;
                            $persona->nombre = $request->post('Sds_com_persona')['nombre'];
                            $persona->apellido = $request->post('Sds_com_persona')['apellido'];
                            $persona->genero = $request->post('Sds_com_persona')['genero'];
                            $persona->nacionalidad = $request->post('Sds_com_persona')['nacionalidad'];
                            $persona->domicilio_calle = $request->post('Sds_com_persona')['domicilio_calle'];
                            $persona->domicilio_numero = $request->post('Sds_com_persona')['domicilio_numero'];
                            //$persona->idlocalidad = $request->post('Sds_com_persona')['idlocalidad']; --Se va a ver despues
                            //var_dump($request->post('Sds_com_persona')['fecha_nacimiento']);
                            if (($request->post('Sds_com_persona')['fecha_nacimiento']) != null) {
                                //var_dump($persona->fecha_nacimiento,'maradona');
                                $fecha = str_replace('/', '-', $request->post('Sds_com_persona')['fecha_nacimiento']);
                                $persona->fecha_nacimiento = date('Y-m-d', strtotime($fecha));
                            }
                            // if($model->save() && !$persona->save()){
                            //     throw new Exception ("La solicitud se guardó, pero no la persona");
                            //     Yii::$app->session->setFlash('fail_save_solicitud');
                            // }
                            if ($persona->save()) {
                                $model->idpersona = $persona->idpersona;
                                $model->fecha_hora = date('Y-m-d H:i:s');
                                //var_dump();
                                if ($model->save()) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_entrega_solicitud', $model->identregasolicitud, $model->getAttributes());
                                    $transaction->commit();
                                    $persona = new Sds_com_persona();
                                    $persona->conviviente = 0;
                                    $model = new Sds_stk_entrega_solicitud();
                                    $model->fecha_hora = date('d/m/Y H:i:s'); //Fecha en formato de muestra al usuario
                                    Yii::$app->session->setFlash('save_solicitud', 'La solicitud y la persona se cargaron');
                                    return [
                                        'title' => "Alta de solicitud entrega",
                                        'content' => $this->renderAjax('create', [
                                            'model' => $model,
                                            'persona' => $persona
                                        ]),
                                        'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btn-submit', 'style' => isset($error) ? '' : 'display:none;'])
                                    ];
                                } else {
                                    $model->idpersona = null;
                                    Yii::$app->session->setFlash('fail_save_solicitud', 'No fue posible guardar la solicitud');
                                }
                            } else {
                                $error = true;
                                Yii::$app->session->setFlash('fail_save_persona', 'No fue posible guardar la persona');
                                return [
                                    'title' => "Alta de solicitud entrega",
                                    'content' => $this->renderAjax('create', [
                                        'model' => $model,
                                        'persona' => $persona
                                    ]),
                                    'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btn-submit', 'style' => isset($error) ? '' : 'display:none;'])
                                ];
                            }
                            //añadimos variable error para esconder boton submit
                            $error = true;
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('fail_save_persona', "La operación no se realizó de manera correcta: " . $e->getMessage());
                    }
                } else {
                    //Persona ya existe en la base;
                    $model->fecha_hora = date('Y-m-d H:i:s'); //setea formato de la base
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_entrega_solicitud', $model->identregasolicitud, $model->getAttributes());
                        $model = new Sds_stk_entrega_solicitud();
                        $model->fecha_hora = date('d/m/Y H:i:s'); //Fecha en formato de muestra al usuario
                        Yii::$app->session->setFlash('save_solicitud', 'Se guardo correctamente!');
                    } else {
                        Yii::$app->session->setFlash('fail_save_solicitud', 'Error al guardar solicitud.');
                    }
                    //añadimos variable error para esconder boton submit
                    if ($model->getErrors()) {
                        $error = true;
                    }
                }
            }
        }
        return [
            'title' => "Alta de solicitud entrega",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'persona' => (isset($persona) ? $persona : null)
            ]),
            'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btn-submit', 'style' => isset($error) ? '' : 'display:none;'])
        ];
    }
    /**
     * Updates an existing Sds_stk_entrega_solicitud model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $persona = Sds_com_persona::findOne($model->idpersona);
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        $consulta_idpersona = Sds_com_persona::find()->where(['documento' => $model->dni])->one();
        if ($consulta_idpersona != null) {
            $model->idpersona = $consulta_idpersona->idpersona;
        } else {
        }
        if ($request->isAjax) {
            if ($model->load($request->post())) {
                // $persona = new Sds_com_persona();
                if ($model->idpersona == null) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($request->post('Sds_com_persona') != null) {
                            $persona_datos = new Sds_com_persona();
                            $persona_datos->documento_tipo = Sds_com_persona::TIPO_DNI;
                            $persona_datos->documento = $model->dni;
                            $persona_datos->nombre = $request->post('Sds_com_persona')['nombre'];
                            $persona_datos->apellido = $request->post('Sds_com_persona')['apellido'];
                            $persona_datos->genero = $request->post('Sds_com_persona')['genero'];
                            $persona_datos->nacionalidad = $request->post('Sds_com_persona')['nacionalidad'];
                            $persona_datos->domicilio_calle = $request->post('Sds_com_persona')['domicilio_calle'];
                            $persona_datos->domicilio_numero = $request->post('Sds_com_persona')['domicilio_numero'];
                            //$persona->idlocalidad = $request->post('Sds_com_persona')['idlocalidad']; --Se va a ver despues
                            if (($request->post('Sds_com_persona')['fecha_nacimiento']) != null) {
                                $fecha = str_replace('/', '-', $request->post('Sds_com_persona')['fecha_nacimiento']);
                                $persona_datos->fecha_nacimiento = date('Y-m-d', strtotime($fecha));
                            }
                            if ($persona_datos->save()) {
                                $model->idpersona = $persona_datos->idpersona;
                                $model->fecha_hora = date('Y-m-d H:i:s');
                                if ($model->save()) {
                                    $model->idpersona = $persona_datos->idpersona;
                                    $persona = $persona_datos;
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_entrega_solicitud', $model->identregasolicitud, $model->getAttributes());
                                    $transaction->commit();
                                    Yii::$app->session->setFlash('save_solicitud', 'Se guardo correctamente! el caso 2');
                                    $persona_datos = new Sds_com_persona();
                                }
                            } else {
                                $persona = $persona_datos;
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('fail_save_persona', "La operación no se realizó de manera correcta: " . $e->getMessage());
                    }
                } else {
                    $model->fecha_hora = date('Y-m-d H:i:s'); //setea formato de la base
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_entrega_solicitud', $model->identregasolicitud, $model->getAttributes());
                        $model->fecha_hora = date('d/m/Y H:i:s'); //Fecha en formato de muestra al usuario
                        Yii::$app->session->setFlash('save_solicitud', 'Se guardo correctamente!');
                    } else {
                        Yii::$app->session->setFlash('fail_save_solicitud', 'Error al guardar solicitud.');
                    }
                }
            }
            return [
                'title' => "Editar solicitud entrega",
                'content' => $this->renderAjax('update', [
                    'persona' => $persona,
                    'model' => $model,
                    'persona_datos' => (isset($persona_datos) ? $persona_datos : null)

                ]),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit', 'id' => 'btn-submit'])
            ];
        }
    }
    protected function findModel($id)
    {
        if (($model = Sds_stk_entrega_solicitud::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_persona($dni)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $persona = Sds_com_persona::find()->where(['documento' => $dni])->one();
        if ($persona != null) {
            return [
                'idpersona' => $persona->idpersona,
                'nombre' => strtoupper($persona->nombre),
                'apellido' => strtoupper($persona->apellido),
                'pais' => Sds_com_configuracion::findOne($persona->nacionalidad)->descripcion,
                'fecha_nacimiento' => (($persona->fecha_nacimiento == '1900-01-01') ? '-SIN DATOS- ' : date('d/m/Y', strtotime($persona->fecha_nacimiento))),
                'genero' => Sds_com_configuracion::findOne($persona->genero)->descripcion,
                'calle' => $persona->domicilio_calle,
                'numero' => $persona->domicilio_numero
            ];
        } else {
            return false;
        }
    }

    public function actionSet_persona($dni, $pais, $genero, $fecha_nacimiento, $nombre, $apellido, $calle, $numero, $localidad)
    {
        if ($genero == 'F') {
            $genero = 81;
        } else {
            $genero = 82;
        }
        if ($pais != null) {
            $pais = 70;
        } else {
            $pais = 80;
        }
        //$model_localidad=Sds_com_localidad::find()->where(['like','descripcion', $localidad, false, false])->one();
        $persona = new Sds_com_persona();
        $persona->documento = $dni;
        $persona->documento_tipo = Sds_com_persona::TIPO_DNI;
        $persona->nacionalidad = $pais;
        $persona->genero = $genero;
        $persona->fecha_nacimiento = $fecha_nacimiento;
        $persona->nombre = $nombre;
        $persona->apellido = $apellido;
        $persona->domicilio_calle = $calle;
        $persona->domicilio_numero = $numero;
        $persona->idlocalidad = null;

        if ($persona->save()) {
            return $persona->idpersona;
        }
    }
    public function actionGet_formpersona()
    {
    }
    public function actionSet_formpersona()
    {
    }
    public function actionManage_items($idsolicitud)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($idsolicitud);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_entrega_solicitud', $model->identregasolicitud, $model->getAttributes());
        return [
            'title' => "Administrar Items Solicitud Entrega",
            'content' => $this->renderAjax('create_item', [
                'model' => $model
            ]),
            'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a(
                    '<i class="glyphicon glyphicon-plus-sign"></i> Añadir Item',
                    ['sds_stk_entrega_solicitud_item/create', 'entregasolicitud' => $model->identregasolicitud],
                    [
                        'class' => 'btn btn-success pull-right col-md-3',
                        'style' => 'margin: -5px 10px 10px',
                        'role' => 'modal-remote',
                        'title' => 'Añadir Item',
                        //'data-request-method'=>'post',
                        'data-toggle' => 'tooltip'
                    ]
                )
        ];
    }
}
