<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Sds_800_atencion_familia;
use app\models\Sds_800_atencion_familiaSearch;
use app\models\Sds_800_llamada;
use app\models\Sds_800_persona;
use app\models\Sds_com_localidad;
use app\models\Sds_com_persona;
use app\models\Sds_ris_persona;
use app\models\Sds_ris_risneu;
use app\models\Mds_sys_log;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * Sds_800_atencion_familiaController implements the CRUD actions for Sds_800_atencion_familia model.
 */
class Sds_800_atencion_familiaController extends Controller
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
                    'validar_dni' => ['post'],
                    'get_id_localidad' => ['post'],
                    'get_id_risneu' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'validar_dni', 'get_id_localidad', 'get_id_risneu'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'validar_dni', 'get_id_localidad', 'get_id_risneu'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA_FAMILIA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_800_atencion_familia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $searchModel = new Sds_800_atencion_familiaSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion_familia', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Sds_800_atencion_familia model.
     * @param string $id
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
            $model->localidad = $model_800_persona->idlocalidad;

            $model_com_persona1 = Sds_com_persona::findOne($model->idpersona_referente);
            $model_800_persona1 = Sds_800_persona::findOne($model->idpersona_referente);
            $model->dni1 = $model_com_persona1->documento;
            $model->nombre1 = $model_com_persona1->nombre;
            $model->apellido1 = $model_com_persona1->apellido;
            $model->fecha_nacimiento1 = $model_com_persona1->fecha_nacimiento;
            $model->nacionalidad1 = $model_com_persona1->nacionalidad;
            $model->sexo1 = $model_com_persona1->genero;
            $model->localidad1 = $model_800_persona1->idlocalidad;
            $model->telefono1 = $model_800_persona1->telefono;
            $model->domicilio1 = $model_800_persona1->domicilio;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion_familia', $id, array());
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Sds_800_atencion_familia #" . $id,
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
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_800_atencion_familia model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $dni = null)
    {
        $canCreate = $this->verificarCondicionCreacion($id);
        if ($canCreate) {
            $request = Yii::$app->request;
            $model = new Sds_800_atencion_familia();
            $model->idpersona = 0;
            $model->idpersona_referente = 0;
            $model->idllamada = $id;
            $model->fecha_intervencion = date('Y-m-d H:i');
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                    'model' => $model,
                ]);
            }
            $user  = Yii::$app->user->identity;
            $model->idusuario = $user->idusuario;
            $model->dia_hora = date("Y-m-d H:i");

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Create new Sds_800_atencion_familia",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else if ($model->load($request->post()) && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_atencion_familia', $model->idllamada, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Create new Sds_800_atencion_familia",
                        'content' => '<span class="text-success">Create Sds_800_atencion_familia success</span>',
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Create new Sds_800_atencion_familia",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

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

                    $model_com_persona1 = new Sds_com_persona;
                    $model_com_persona1->documento_tipo = '83';
                    $model_800_persona1 = null;


                    $ban_800_persona_existe = 0;
                    if ($model->idpersona > 0) {
                        $ban_800_persona_existe = 1;
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }
                    $ban_persona1_existe = 0;
                    if ($model->idpersona_referente > 0) {
                        $ban_persona1_existe = 1;
                        $model_com_persona1 = Sds_com_persona::findOne($model->idpersona_referente);
                        $model_800_persona1 = Sds_800_persona::findOne($model->idpersona_referente);
                    }
                    /*    $model_com_persona->documento =  $model->dni;
                        $model_com_persona->nacionalidad = $model->nacionalidad;
                        $model_com_persona->genero = $model->sexo;
                        $model_com_persona->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                        $model_com_persona->nombre = $model->nombre;
                        $model_com_persona->apellido = $model->apellido;
                    */
                    $model_com_persona1->documento = $model->dni1;
                    $model_com_persona1->nacionalidad = $model->nacionalidad1;
                    $model_com_persona1->genero = $model->sexo1;
                    $model_com_persona1->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento1)));
                    $model_com_persona1->nombre = $model->nombre1;
                    $model_com_persona1->apellido = $model->apellido1;

                    $model->dia_hora = date('Y-m-d H:i', strtotime(str_replace('/', '-', $model->dia_hora . ' ' . $model->hora)));

                    // Upload archivo adjunto
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                    if (isset($tmpfile)) {
                        if ($tmpfile->getExtension() != 'pdf') {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_adjunto = "data:image/png;base64," . base64_encode($tmpfile_contents);
                        } else {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_adjunto = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                        }
                    }

                    if (!$model_com_persona1->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                        // return print_r($model_com_persona, true);
                    } else {
                        $model->idpersona = $model_com_persona->idpersona;
                        $model->idpersona_referente = $model_com_persona1->idpersona;

                        if ($model_800_persona == null) {
                            $model_800_persona = new Sds_800_persona();
                            $model_800_persona->idpersona = $model->idpersona;
                        }
                        $model_800_persona->idlocalidad = $model->localidad1;
                        $model_800_persona->telefono = $model->telefono;

                        if ($model_800_persona1 == null) {
                            $model_800_persona1 = new Sds_800_persona();
                            $model_800_persona1->idpersona = $model->idpersona_referente;
                        }
                        $model_800_persona1->idlocalidad = $model->localidad1;
                        $model_800_persona1->telefono = $model->telefono1;
                        $model_800_persona1->domicilio = $model->domicilio1;

                        if (!$model_800_persona1->save() || !$model_800_persona->save()) {
                            $guardado = false;
                            $transaction->rollBack();
                        }

                        if ($guardado && $model->save(false)) {
                            $model_llamada = Sds_800_llamada::findOne($model->idllamada);
                            if ($model_llamada->updateAttributes(["estado" => Sds_800_llamada::ESTADO_ATENDIDA])) {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_atencion_familia', $model->idllamada, $model->getAttributes());
                                if ($ban_800_persona_existe == 1) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                } else {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                                }
                                if ($ban_persona1_existe == 1) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona_referente, $model->getAttributes());
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona_referente, $model->getAttributes());
                                } else {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona_referente, $model->getAttributes());
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona_referente, $model->getAttributes());
                                }
                                return $this->redirect(['sds_800_llamada/index', 'area' => 1]);
                            }
                        } else {
                            $guardado = false;
                            $transaction->rollBack();
                        }
                    }
                }
                $listProvincias = Sds_com_provinciaController::getListProvincias();

                return $this->render('create', [
                    'model' => $model,
                    'listProvincias' => $listProvincias,
                    'listLocalidades' => null,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_800_atencion_familia model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
            $model->telefono = $model_800_persona->telefono;
            $model->localidad = $model_800_persona->idlocalidad;

            $model_com_persona1 = Sds_com_persona::findOne($model->idpersona_referente);
            $model_800_persona1 = Sds_800_persona::findOne($model->idpersona_referente);
            $model->dni1 = $model_com_persona1->documento;
            $model->nombre1 = $model_com_persona1->nombre;
            $model->apellido1 = $model_com_persona1->apellido;
            $model->fecha_nacimiento1 = $model_com_persona1->fecha_nacimiento;
            $model->nacionalidad1 = $model_com_persona1->nacionalidad;
            $model->sexo1 = $model_com_persona1->genero;
            $model->localidad1 = $model_800_persona1->idlocalidad;
            $model->domicilio1 = $model_800_persona1->domicilio;
            $model->telefono1 = $model_800_persona1->telefono;

            $model->dia_hora = date("Y-m-d H:i");

            if ($request->isAjax) {
            } else {

                if ($model->load($request->post())) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = true;
                    $model_com_persona = new Sds_com_persona;
                    $model_com_persona->documento_tipo = '83';
                    $model_800_persona = null;

                    $model_com_persona1 = new Sds_com_persona;
                    $model_com_persona1->documento_tipo = '83';
                    $model_800_persona1 = null;

                    $ban_800_persona_existe = 0;
                    if ($model->idpersona > 0) {
                        $ban_800_persona_existe = 1;
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }
                    $ban_persona1_existe = 0;
                    if ($model->idpersona_referente > 0) {
                        $ban_persona1_existe = 1;
                        $model_com_persona1 = Sds_com_persona::findOne($model->idpersona_referente);
                        $model_800_persona1 = Sds_800_persona::findOne($model->idpersona_referente);
                    }

                    $model_com_persona->documento =  $model->dni;
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;

                    $model_com_persona1->documento = $model->dni1;
                    $model_com_persona1->nacionalidad = $model->nacionalidad1;
                    $model_com_persona1->genero = $model->sexo1;
                    $model_com_persona1->fecha_nacimiento =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha_nacimiento1)));
                    $model_com_persona1->nombre = $model->nombre1;
                    $model_com_persona1->apellido = $model->apellido1;

                    $model->dia_hora = date('Y-m-d H:i', strtotime(str_replace('/', '-', $model->dia_hora . ' ' . $model->hora)));

                    // Upload archivo adjunto
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                    if (isset($tmpfile)) {
                        if ($tmpfile->getExtension() != 'pdf') {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_adjunto = "data:image/png;base64," . base64_encode($tmpfile_contents);
                        } else {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_adjunto = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                        }
                    } else {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if ($model->borrar_adjunto && $model->archivo_adjunto) {
                            $model->archivo_adjunto = null;
                        }
                    }


                    if (!$model_com_persona1->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        $model->idpersona = $model_com_persona->idpersona;
                        $model->idpersona_referente = $model_com_persona1->idpersona;

                        if ($model_800_persona == null) {
                            $model_800_persona = new Sds_800_persona();
                            $model_800_persona->idpersona = $model->idpersona;
                        }
                        $model_800_persona->idlocalidad = $model->localidad;
                        $model_800_persona->telefono = $model->telefono;

                        if ($model_800_persona1 == null) {
                            $model_800_persona1 = new Sds_800_persona();
                            $model_800_persona1->idpersona = $model->idpersona_referente;
                        }
                        $model_800_persona1->idlocalidad = $model->localidad1;
                        $model_800_persona1->telefono = $model->telefono1;
                        $model_800_persona1->domicilio = $model->domicilio1;


                        if (!$model_800_persona1->save() || !$model_800_persona->save()) {
                            $guardado = false;
                            $transaction->rollBack();
                        }

                        if ($guardado && $model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_atencion_familia', $model->idllamada, $model->getAttributes());
                            if ($ban_800_persona_existe == 1) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                            }
                            if ($ban_persona1_existe == 1) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona_referente, $model->getAttributes());
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona_referente, $model->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona_referente, $model->getAttributes());
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona_referente, $model->getAttributes());
                            }
                            return $this->redirect(['sds_800_llamada/index', 'area' => 1]);
                        }
                    }
                } else {
                    $listProvincias = Sds_com_provinciaController::getListProvincias();
                    $listLocalidades = Sds_com_provinciaController::getListLocalidadesByProvincia($model->idpersona0->idlocalidad0->idprovincia);
                    $model->provincia = $model->idpersona0->idlocalidad0->idprovincia;
                    $model->provincia1 = $model->idpersonaReferente->idlocalidad0->idprovincia;

                    return $this->render('create', [
                        'model' => $model,
                        'listProvincias' => $listProvincias,
                        'listLocalidades' => $listLocalidades,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_800_atencion_familia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_800_atencion_familia', $id, $model->getAttributes());
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
     * Finds the Sds_800_atencion_familia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Sds_800_atencion_familia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_800_atencion_familia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidar_dni($dni, $idllamada)
    {
        //Busco la persona, si existe traigo los datos para editar
        if ($dni != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Sds_ris_persona::findBySql("SELECT risper.*
                                                FROM sds_ris_persona risper
                                                JOIN sds_com_persona persona ON persona.idpersona=risper.idpersona
                                                JOIN sds_ris_risneu risneu ON risneu.idrisneu=risper.idrisneu
                                                WHERE documento=$dni and risper.activo = 1
                                                ORDER BY risneu.updated_at DESC, risneu.idrisneu DESC")->one();
            $model_persona = null;
            //aca queria verificar si era create o update. Pero vi que siempre es create porque en el editar el botón de buscar dni esta deshabilitado.
            //$createUpdate = $editar ? "update&id=".$idllamada : "create&id=".$idllamada;
            if ($model == null) {
                $model = Sds_ris_risneu::findBySql("SELECT risneu.idrisneu
                FROM sds_ris_risneu risneu
                WHERE dni=$dni and activo = 1")->one();
                if ($model) {
                    return $this->redirect([
                        'sds_ris_risneu/update',
                        'finalizar' => false,
                        'id' => $model->idrisneu,
                        'dni' => $dni,
                        'origen' => 'index.php?r=sds_800_atencion_familia/create&id=' . $idllamada
                    ]);
                } else {
                    return $this->redirect([
                        'sds_ris_risneu/create',
                        'finalizar' => false,
                        'dni' => $dni,
                        'origen' => 'index.php?r=sds_800_atencion_familia/create&id=' . $idllamada
                    ]);
                }
            } else {
                $model_persona = Sds_com_persona::findOne($model->idpersona);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion_familia/validar_dni', $idllamada, array());
            }
            $result = array();
            array_push($result, $model->getAttributes());
            array_push($result, $model_persona->getAttributes());
            return json_encode($result);
        }
        return null;
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

    public function actionGet_id_risneu($dni, $llamada)
    {
        $risneu = Sds_ris_persona::findBySql("
        select r.*, p.* 
        from sds_ris_persona r 
        inner join sds_com_persona p on r.idpersona  = p.idpersona 
        inner join sds_ris_risneu risneu on risneu.idrisneu = r.idrisneu
        where p.documento = $dni and r.activo = 1
        order by risneu.updated_at DESC, risneu.idrisneu DESC;")
            ->all();
        return $risneu[0]->idrisneu;
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
        $existeIntervencion = Sds_800_atencion_familia::findOne(['idllamada' => $id]);
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
        $model = Sds_800_atencion_familia::findOne(['idllamada' => $id]);
        $canUpdate = false;
        if ($model) {
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
            $estaAtendida = Sds_800_llamada::estaAtendida($id);
            $canUpdate = ($estaAtendida || $hasRolAdminGeneral);
        }
        return $canUpdate;
    }
}
