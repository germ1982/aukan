<?php

namespace app\controllers;

use Yii;
use app\models\Sds_800_am_recreacion;
use app\models\Sds_800_atencion_am;
use app\models\Sds_800_atencion_amSearch;
use app\models\Sds_800_llamada;
use app\models\Sds_800_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use app\models\Sds_ris_persona;
use app\models\Sds_ris_risneu;
use app\models\Mds_sys_log;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;

use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use app\components\AccessRule;

/**
 * Sds_800_atencion_amController implements the CRUD actions for Sds_800_atencion_am model.
 */
class Sds_800_atencion_amController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'validar_dni'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'validar_dni'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA_ADULTOS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_800_atencion_am models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $searchModel = new Sds_800_atencion_amSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion_am', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Sds_800_atencion_am model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $request = Yii::$app->request;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion_am', $id, array());
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Sds_800_atencion_am #" . $id,
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
     * Creates a new Sds_800_atencion_am model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $dni = null)
    {
        $canCreate = $this->verificarCondicionCreacion($id);
        if ($canCreate) {
            $request = Yii::$app->request;
            $model = new Sds_800_atencion_am();
            $model->idpersona = 0;
            $model->idllamada = $id;
            $model->fecha_hora = date('Y-m-d H:i');
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            if (!isset($idusuario) || $idusuario == null) {
                $model = new \app\models\LoginForm();
                return Yii::$app->getResponse()->redirect([
                    'site/login',
                    'model' => $model,
                ]);
            }
            $model->idusuario = $idusuario;
            if ($dni != null) {
                $model->dni = $dni;
            }
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
                    $ban_persona_existe = 0;
                    $ban_guardar_recreacion = 0;
                    if ($model->idpersona > 0) {
                        $ban_persona_existe = 1;
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }


                    // Upload archivo adjunto seguridad
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_seguridad');
                    if (isset($tmpfile)) {
                        if ($tmpfile->getExtension() != 'pdf') {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_seguridad = "data:image/png;base64," . base64_encode($tmpfile_contents);
                        } else {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_seguridad = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                        }
                    }
                    // Upload archivo adjunto salud
                    $tmpfile1 = UploadedFile::getInstance($model, 'temp_archivo_salud');
                    if (isset($tmpfile1)) {
                        if ($tmpfile1->getExtension() != 'pdf') {
                            $tmpfile_contents1 = file_get_contents($tmpfile1->tempName);
                            $model->archivo_salud = "data:image/png;base64," . base64_encode($tmpfile_contents1);
                        } else {
                            $tmpfile_contents1 = file_get_contents($tmpfile1->tempName);
                            $model->archivo_salud = "data:application/pdf;base64," . base64_encode($tmpfile_contents1);
                        }
                    }
                    /* if (!$model_com_persona->save()) {
                    $guardado = false;
                    $transaction->rollBack();
                } else {
                    */
                    $model->idpersona = $model_com_persona->idpersona;
                    if ($model_800_persona == null) {
                        $model_800_persona = new Sds_800_persona();
                        $model_800_persona->idpersona = $model->idpersona;
                    }
                    $model_800_persona->idlocalidad = $model->localidad;
                    $model_800_persona->telefono = $model->telefono;

                    if (!$model_800_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    }

                    if ($guardado && $model->save()) {
                        //creo las instancias de 800_am_recreacion
                        $tipo_recreacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_0800_RECREACION);
                        $tipo_recre_form = isset($request->post("Sds_800_atencion_am")["tipo_re"]) ? $request->post("Sds_800_atencion_am")["tipo_re"] : array();
                        if (!empty($tipo_recre_form)) {
                            foreach ($tipo_recreacion as $tipo_recre) {
                                foreach ($tipo_recre_form as $idrecre) {
                                    if ($idrecre == $tipo_recre->idconfiguracion) {
                                        $recreacion_am = new Sds_800_am_recreacion();
                                        $recreacion_am->idatencionam = $model->idllamada;
                                        $recreacion_am->recreacion = $tipo_recre->idconfiguracion;
                                        $recreacion_am->save();
                                        $ban_guardar_recreacion = 1;
                                    }
                                }
                            }
                        }
                        $model_llamada = Sds_800_llamada::findOne($model->idllamada);
                        if ($model_llamada->updateAttributes(["estado" => Sds_800_llamada::ESTADO_ATENDIDA])) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_atencion_am', $model->idllamada, $model->getAttributes());
                            if ($ban_persona_existe == 1) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                            }

                            if ($ban_guardar_recreacion == 1) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_am_recreacion', $recreacion_am->idamrecreacion, $model->getAttributes());
                            }
                            return $this->redirect(['sds_800_llamada/index', 'area' => 2]);
                        }
                    } else {
                        $guardado = false;
                        $transaction->rollBack();
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
     * Updates an existing Sds_800_atencion_am model.
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
            $model->localidad = $model_800_persona->idlocalidad;
            $model->telefono = $model_800_persona->telefono;

            if ($request->isAjax) {
                /*
            *   Process for ajax request
            */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Update Sds_800_atencion_am #" . $id,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else if ($model->load($request->post()) && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_atencion_am', $model->idllamada, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Sds_800_atencion_am #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    return [
                        'title' => "Update Sds_800_atencion_am #" . $id,
                        'content' => $this->renderAjax('update', [
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
                    $ban_persona_existe = 0;
                    $ban_guardar_recreacion = 0;
                    if ($model->idpersona > 0) {
                        $ban_persona_existe = 1;
                        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                        $model_800_persona = Sds_800_persona::findOne($model->idpersona);
                    }

                    // Upload archivo adjunto seguridad
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_seguridad');
                    if (isset($tmpfile)) {
                        if ($tmpfile->getExtension() != 'pdf') {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_seguridad = "data:image/png;base64," . base64_encode($tmpfile_contents);
                        } else {
                            $tmpfile_contents = file_get_contents($tmpfile->tempName);
                            $model->archivo_seguridad = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                        }
                    } else {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if ($model->borrar_adjunto_seguridad && $model->archivo_seguridad) {
                            $model->archivo_seguridad = null;
                        }
                    }
                    // Upload archivo adjunto salud
                    $tmpfile1 = UploadedFile::getInstance($model, 'temp_archivo_salud');
                    if (isset($tmpfile1)) {
                        if ($tmpfile1->getExtension() != 'pdf') {
                            $tmpfile_contents1 = file_get_contents($tmpfile1->tempName);
                            $model->archivo_salud = "data:image/png;base64," . base64_encode($tmpfile_contents1);
                        } else {
                            $tmpfile_contents1 = file_get_contents($tmpfile1->tempName);
                            $model->archivo_salud = "data:application/pdf;base64," . base64_encode($tmpfile_contents1);
                        }
                    } else {
                        // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                        if ($model->borrar_adjunto_salud && $model->archivo_salud) {
                            $model->archivo_salud = null;
                        }
                    }

                    $model->idpersona = $model_com_persona->idpersona;
                    if ($model_800_persona == null) {
                        $model_800_persona = new Sds_800_persona();
                        $model_800_persona->idpersona = $model->idpersona;
                    }
                    $model_800_persona->idlocalidad = $model->localidad;
                    $model_800_persona->telefono = $model->telefono;

                    if (!$model_800_persona->save()) {
                        $guardado = false;
                        $transaction->rollBack();
                    }

                    if ($guardado && $model->save()) {
                        $risneu_alims = Sds_800_am_recreacion::find()->where(['idatencionam' => $model->idllamada])->all();
                        foreach ($risneu_alims as $ris_alim) {
                            $ris_alim->delete();
                        }
                        //creo las instancias de 800_am_recreacion
                        $tipo_recreacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_0800_RECREACION);
                        $tipo_recre_form = isset($request->post("Sds_800_atencion_am")["tipo_re"]) ? $request->post("Sds_800_atencion_am")["tipo_re"] : array();
                        if (!empty($tipo_recre_form)) {
                            foreach ($tipo_recreacion as $tipo_recre) {
                                foreach ($tipo_recre_form as $idrecre) {
                                    if ($idrecre == $tipo_recre->idconfiguracion) {
                                        $recreacion_am = new Sds_800_am_recreacion();
                                        $recreacion_am->idatencionam = $model->idllamada;
                                        $recreacion_am->recreacion = $tipo_recre->idconfiguracion;
                                        $recreacion_am->save();
                                        $ban_guardar_recreacion = 1;
                                    }
                                }
                            }
                        }
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_atencion_am', $model->idllamada, $model->getAttributes());
                        if ($ban_persona_existe == 1) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_persona', $model->idpersona, $model->getAttributes());
                        }
                        if ($ban_guardar_recreacion == 1) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_800_am_recreacion', $recreacion_am->idamrecreacion, $model->getAttributes());
                        }
                        return $this->redirect(['sds_800_llamada/index', 'area' => 2]);
                    } else {
                        $guardado = false;
                        $transaction->rollBack();
                    }
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_800_atencion_am model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_800_atencion_am', $id, $model->getAttributes());
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
            return $this->redirect(['index', 'area' => 0]);
        }
    }

    /**
     * Finds the Sds_800_atencion_am model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Sds_800_atencion_am the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_800_atencion_am::findOne($id)) !== null) {
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
                        'origen' => 'index.php?r=sds_800_atencion_am/create&id=' . $idllamada
                    ]);
                } else {
                    return $this->redirect([
                        'sds_ris_risneu/create',
                        'finalizar' => false,
                        'dni' => $dni,
                        'origen' => 'index.php?r=sds_800_atencion_am/create&id=' . $idllamada
                    ]);
                }
            } else {
                $model_persona = Sds_com_persona::findOne($model->idpersona);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_800_atencion_am/validar_dni', $idllamada, array());
            }
            $result = array();
            array_push($result, $model->getAttributes());
            array_push($result, $model_persona->getAttributes());
            return json_encode($result);
        }
        return null;
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
        $existeIntervencion = Sds_800_atencion_am::findOne(['idllamada' => $id]);
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
        $model = Sds_800_atencion_am::findOne(['idllamada' => $id]);
        $canUpdate = false;
        if ($model) {
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
            $estaAtendida = Sds_800_llamada::estaAtendida($id);
            $canUpdate = ($estaAtendida || $hasRolAdminGeneral);
        }
        return $canUpdate;
    }
}
