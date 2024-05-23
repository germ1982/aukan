<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_responsable;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use Yii;
use app\models\Sds_ent_responsable;
use app\models\Sds_ent_responsableSearch;
use app\models\Sds_ent_solicitud;
use app\models\Sds_ent_solicitud_intermedia;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Sds_ent_responsableController implements the CRUD actions for Sds_ent_responsable model.
 */
class Sds_ent_responsableController extends Controller
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
                            Mds_seg_item::MODULO_ENT_RESPONSABLE,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_ent_responsable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_ent_responsableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUnificar($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $exito = true;
                $error = "";
                $transaction = Yii::$app->db->beginTransaction();
                //select * from sds_ent_responsable where idresponsable=1692;
                //select * from sds_ent_entrega where receptor=1692
                $idreceptor_reemp = $model->idresponsable_reemp;
                $entregas_reemp = Sds_ent_entrega::find()->where("receptor=" . $idreceptor_reemp)->all();
                foreach ($entregas_reemp as $ent_reemp) {
                    $ent_reemp->receptor = $model->idresponsable;
                    if (!$ent_reemp->save()) {
                        $exito = false;
                        $error = $error . "<br>" . print_r($ent_reemp, true);
                    }
                }
                $solic_reemp = Sds_ent_solicitud_intermedia::find()->where("receptor=" . $idreceptor_reemp)->all();
                foreach ($solic_reemp as $ent_reemp) {
                    $ent_reemp->receptor = $model->idresponsable;
                    if (!$ent_reemp->save()) {
                        $exito = false;
                        $error = $error . "<br>" . print_r($ent_reemp, true);
                    }
                }
                $usuarios_reemp = Mds_seg_usuario::find()->where("responsable=" . $idreceptor_reemp)->all();
                foreach ($usuarios_reemp as $usu_reemp) {
                    $usu_reemp->responsable = $model->idresponsable;
                    if (!$usu_reemp->save()) {
                        $exito = false;
                        $error = $error . "<br>" . print_r($ent_reemp, true);
                    }
                }
                $usuarios__resp_reemp = Mds_seg_usuario_responsable::find()->where("idresponsable=" . $idreceptor_reemp)->all();
                foreach ($usuarios__resp_reemp as $usu_resp_reemp) {
                    $usu_resp_reemp->idresponsable = $model->idresponsable;
                    if (!$usu_resp_reemp->save()) {
                        $exito = false;
                        $error = $error . "<br>" . print_r($ent_reemp, true);
                    }
                }
                $datos_eliminar = $this->findModel($idreceptor_reemp);
                if ($datos_eliminar != null) {
                    $datos_eliminar->delete();
                    $exito = Sds_com_configuracion::findOne($idreceptor_reemp)->delete() > 0;
                }
                if ($exito) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_ent_entrega/unificar', $model->responsable, $model->getAttributes());
                    $transaction->commit();
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Unificar Responsable ",
                        'content' => '<span class="text-success">El Responsable ha sido modificado exitosamente!</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    $model->addError("idresponsable_reemp", "No se pudo unificar el responsable! ");
                }
            }
            return [
                'title' => "Unificar Responsable " . Sds_com_configuracion::findOne($model->idresponsable)->descripcion,
                'content' => $this->renderAjax('unificar', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Unificar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        }
    }


    /**
     * Displays a single Sds_ent_responsable model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Consulta de Responsable #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Modificar Datos', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Sds_ent_responsable model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_ent_responsable();
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $sds_com_configuracion = new Sds_com_configuracion();
                $sds_com_configuracion->idconfiguraciontipo = Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA;
                $sds_com_configuracion->descripcion = $model->responsable;
                $sds_com_configuracion->activo = 1;
                if ($sds_com_configuracion->save()) {
                    $model->idresponsable = $sds_com_configuracion->idconfiguracion;
                    $tmpfile = UploadedFile::getInstance($model, 'archivo_dni_frente');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nombre =  $model->dni . '_frente.' . $extension;
                        //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                        $ruta = 'uploads/entregas/';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }
                        $model->dni_frente = $ruta . $nombre;
                        $tmpfile->saveAs($model->dni_frente);
                    } else {
                        $model->dni_frente = 'img/dni_sin_foto.png';
                    }
                    $tmpfile = UploadedFile::getInstance($model, 'archivo_dni_dorso');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nombre = $model->dni . '_dorso.' . $extension;
                        //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                        $ruta = 'uploads/entregas/';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }
                        $model->dni_dorso = $ruta . $nombre;
                        $tmpfile->saveAs($model->dni_dorso);
                    } else {
                        $model->dni_dorso = 'img/dni_sin_foto.png';
                    }
                    if ($model->save()) {
                        $transaction->commit();
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Cargar Nuevo Responsable",
                            'content' => '<span class="text-success">Responsable Agregado Correctamente!</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                        ];
                    }
                } else {
                    $model->addError("responsable", "No se pudo crear el Responsable. Intente de nuevo.");
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Cargar Nuevo Responsable",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idresponsable]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_ent_responsable model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->responsable = Sds_com_configuracion::findOne($id)->descripcion;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $tmpfile = UploadedFile::getInstance($model, 'archivo_dni_frente');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre =  $model->dni . '_frente.' . $extension;
                    //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                    $ruta = 'uploads/entregas/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->dni_frente = $ruta . $nombre;
                    $tmpfile->saveAs($model->dni_frente);
                }
                $tmpfile = UploadedFile::getInstance($model, 'archivo_dni_dorso');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = $model->dni . '_dorso.' . $extension;
                    //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                    $ruta = 'uploads/entregas/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->dni_dorso = $ruta . $nombre;
                    $tmpfile->saveAs($model->dni_dorso);
                }
                if ($model->save()) {
                    $model_conf = Sds_com_configuracion::findOne($id);
                    $model_conf->descripcion = $model->responsable;
                    $model_conf->save();
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Responsable #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Actualizar Datos', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            return [
                'title' => "Actualizar Datos Responsable #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idresponsable]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_ent_responsable model.
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Sds_ent_responsable model.
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

    /**
     * Finds the Sds_ent_responsable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_ent_responsable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_ent_responsable::findOne($id)) !== null) {
            return $model;
        } else {
            $model = new Sds_ent_responsable();
            $model->idresponsable = $id;
            return $model;
        }
    }
}
