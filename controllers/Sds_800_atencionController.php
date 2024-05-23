<?php

namespace app\controllers;

use Yii;
use app\models\Mds_seg_item;
use app\models\Sds_800_atencion;
use app\models\Sds_800_atencionSearch;
use app\models\Sds_800_llamada;
use app\models\Sds_800_persona;
use app\models\Sds_com_localidad;
use app\models\Sds_com_persona;
use app\models\Mds_sys_log;
use app\models\Mds_seg_usuario_rol;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use yii\web\UploadedFile;
use yii\web\ForbiddenHttpException;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use app\components\AccessRule;

class Sds_800_atencionController extends Controller
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
                    'validar_dni' => ['post'],
                    'get_id_localidad' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'validar_dni', 'get_id_localidad'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'validar_dni', 'get_id_localidad'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_800_atencion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $searchModel = new Sds_800_atencionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    /**
     * Displays a single Sds_800_atencion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
            $model->dni = $model_com_persona->documento;
            $model->nombre = $model_com_persona->nombre;
            $model->apellido = $model_com_persona->apellido;
            $model->fecha_nacimiento = $model_com_persona->fecha_nacimiento;
            $model->nacionalidad = $model_com_persona->nacionalidad;
            $model->sexo = $model_com_persona->genero;
            $model->localidad = $model_800_persona->idlocalidad;
            $model->telefono = $model_800_persona->telefono;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion', $id, array());
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Detalle Atención",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->render('view', [
                    'model' => $model,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_800_atencion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $canCreate = $this->verificarCondicionCreacion($id);
        if ($canCreate) {
            $request = Yii::$app->request;
            $model = new Sds_800_atencion();
            $model->idllamada = $id;
            $model_llamada = Sds_800_llamada::findOne($model->idllamada);
            $model->idpersona = 0;
            $model->fecha_hora = date('Y-m-d H:i');
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

            if ($model_llamada->solicitante == 1) {
                $model_com_persona = Sds_com_persona::findOne($model_llamada->idpersona);
                $model_800_persona = Sds_800_persona::findOne($model_llamada->idpersona);
                $ban_persona_existe = 1;
                $model->dni = $model_com_persona->documento;
                $model->nombre = $model_com_persona->nombre;
                $model->apellido = $model_com_persona->apellido;
                $model->fecha_nacimiento = $model_com_persona->fecha_nacimiento;
                $model->nacionalidad = $model_com_persona->nacionalidad;
                $model->sexo = $model_com_persona->genero;
                $model->localidad = $model_800_persona->idlocalidad;
                $model->telefono = $model_800_persona->telefono;
                $model->generoautopercibido = $model_800_persona->idgeneroautopercibido;
                $model->idlocalidadoriundo = $model_800_persona->idlocalidadoriundo;
            }
            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */

                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Nueva Atención 0800",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else if ($model->load($request->post()) && $model->save()) {

                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_atencion', $model->idllamada, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nueva Atención 0800",
                        'content' => '<span class="text-success">Nueva Atención 0800 creada con éxito</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Nueva Atención 0800",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                /*
            *   Process for non-ajax request
            */
                if ($model->load($request->post())) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = true;
                    $model_com_persona = new Sds_com_persona;
                    $model_com_persona->documento_tipo = '83';
                    $model_800_persona = null;
                    $ban_persona_existe = 0;
                    if ($model->idpersona > 0 && !$model->sin_dni) {
                        $ban_persona_existe = 1;
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }
                    $model_com_persona->documento = $model->sin_dni ? 0 : $model->dni;
                    $model_com_persona->nacionalidad = $model->nacionalidad;
                    $model_com_persona->genero = $model->sexo;
                    $model_com_persona->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    $model_com_persona->conviviente = 0;
                    // $model_800_persona->telefono = $model->telefono;

                    // Upload archivo salud
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_salud');
                    if (isset($tmpfile)) {
                        if ($tmpfile->getExtension() != 'pdf') {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_salud = "data:image/png;base64," . base64_encode($tmpfile_contents);
                        } else {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_salud = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                        }
                    }
                    if (!$model_com_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        $model->idpersona = $model_com_persona->idpersona;
                        if ($model_800_persona == null) {
                            $model_800_persona = new Sds_800_persona();
                            $model_800_persona->idpersona = $model->idpersona;
                        }
                        $model_800_persona->idlocalidad = $model->localidad;
                        $model_800_persona->telefono = $model->telefono;
                        $model_800_persona->idgeneroautopercibido = $model->generoautopercibido;
                        $model_800_persona->idlocalidadoriundo = $model->idlocalidadoriundo;

                        if (!$model_800_persona->save()) {
                            $guardado = false;
                            $transaction->rollBack();
                        };
                        // $object = new MyObject();

                        if ($guardado && $model->save()) {

                            // $model_llamada = Sds_800_llamada::findOne($model->idllamada);
                            if ($model_llamada->updateAttributes(["estado" => Sds_800_llamada::ESTADO_ATENDIDA])) {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_atencion', $model->idllamada, $model->getAttributes());
                                if ($ban_persona_existe == 1) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                                } else {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                                }
                                var_dump($model->errors);
                                return $this->redirect(['sds_800_llamada/index', 'area' => 0]);
                            }
                        }
                    }
                }
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_800_atencion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $canUpdate = $this->verificarCondicionActualizacion($id);
        if ($canUpdate) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            $model_800_persona = Sds_800_persona::findOne($model->idpersona);
            $model->dni = $model_com_persona->documento;
            $model->nombre = $model_com_persona->nombre;
            $model->apellido = $model_com_persona->apellido;
            $model->fecha_nacimiento = $model_com_persona->fecha_nacimiento;
            $model->nacionalidad = $model_com_persona->nacionalidad;
            $model->sexo = $model_com_persona->genero;
            $model->localidad = $model_800_persona->idlocalidad;
            $model->telefono = $model_800_persona->telefono;
            $model->generoautopercibido = $model_800_persona->idgeneroautopercibido;
            $model->idlocalidadoriundo = $model_800_persona->idlocalidadoriundo;

            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */
            } else {
                /*
            *   Process for non-ajax request
            */
                if ($model->load($request->post())) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = true;
                    $model_com_persona = new Sds_com_persona;
                    $model_com_persona->documento_tipo = '83';
                    $model_800_persona = null;
                    if ($model->idpersona > 0) {
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }
                    $model_com_persona->documento = $model->dni;
                    $model_com_persona->nacionalidad = $model->nacionalidad;
                    $model_com_persona->genero = $model->sexo;
                    $model_com_persona->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    $model_com_persona->conviviente = 0;
                    //  $model_800_persona->telefono = $model->telefono;

                    // Upload archivo salud
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_salud');
                    if (isset($tmpfile)) {
                        if ($tmpfile->getExtension() != 'pdf') {
                            // print_r($tmpfile);
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_salud = "data:image/png;base64," . base64_encode($tmpfile_contents);
                        } else {
                            // print_r($tmpfile);
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_salud = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                        }
                    } else {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if ($model->borrar_adjunto && $model->archivo_salud) {
                            $model->archivo_salud = null;
                        }
                    }

                    if (!$model_com_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        $model->idpersona = $model_com_persona->idpersona;
                        if ($model_800_persona == null) {
                            $model_800_persona = new Sds_800_persona();
                            $model_800_persona->idpersona = $model->idpersona;
                        }
                        $model_800_persona->idlocalidad = $model->localidad;
                        $model_800_persona->telefono = $model->telefono;
                        $model_800_persona->idgeneroautopercibido = $model->generoautopercibido;
                        $model_800_persona->idlocalidadoriundo = $model->idlocalidadoriundo;
                        if (!$model_800_persona->save()) {
                            $guardado = false;
                            $transaction->rollBack();
                        }
                        if ($guardado && $model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_atencion', $model->idllamada, $model->getAttributes());
                            return $this->redirect(['sds_800_llamada/index', 'area' => 0]);
                        }
                    }
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_800_atencion model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_800_atencion', $id, $model->getAttributes());
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

    /**
     * Finds the Sds_800_atencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_800_atencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_800_atencion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidar_dni($dni)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion/validar_dni', $dni, array());
        $result = array();
        $model_persona = Sds_com_persona::find()->where(["documento" => $dni])->one();
        if ($model_persona != null) {

            array_push($result, $model_persona->getAttributes());
            $model_800_persona = Sds_800_persona::findOne($model_persona->idpersona);

            if ($model_800_persona != null) {
                array_push($result, $model_800_persona->getAttributes());
            }
        }
        return json_encode($result);
    }

    public function actionGet_id_localidad($localidad)
    {
        $result = array();
        $model_localidad = Sds_com_localidad::find()->where("descripcion like '%" . $localidad . "%'")->orderBy(["descripcion" => SORT_ASC])->limit(1)->one();
        if ($model_localidad != null) {
            $result = array("idlocalidad" => $model_localidad->idlocalidad);
        }
        return json_encode($result);
    }

    /**
     * Verificar
     * 
     * La llamada esta en estado Pendiente
     * y
     * No existe una intervencion
     * 
     */
    private function verificarCondicionCreacion($id)
    {
        $estaPendiente = Sds_800_llamada::estaPendiente($id);
        $existeIntervencion = Sds_800_atencion::findOne(['idllamada' => $id]);
        $canCreate = $estaPendiente &&  is_null($existeIntervencion);
        return $canCreate;
    }

    /**
     *  Verificar
     *  la llamada este en estado atendida
     *  o
     *  soy el usuario AdminGeneral
     */
    private function verificarCondicionActualizacion($id)
    {
        $model = Sds_800_atencion::findOne(['idllamada' => $id]);
        $canUpdate = false;
        if ($model) {
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
            $estaAtendida = Sds_800_llamada::estaAtendida($id);
            $canUpdate = ($estaAtendida || $hasRolAdminGeneral);
        }
        return $canUpdate;
    }
}
