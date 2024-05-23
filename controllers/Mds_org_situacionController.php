<?php

namespace app\controllers;

use app\models\Mds_org_contacto;
use app\models\Mds_org_documento;
use Yii;
use app\models\Mds_org_situacion;
use app\models\Mds_org_situacionSearch;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_persona;
use app\models\Sds_gis_capa_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;

/**
 * Mds_org_situacionController implements the CRUD actions for Mds_org_situacion model.
 */
class Mds_org_situacionController extends Controller
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
        ];
    }

    /**
     * Lists all Mds_org_situacion models.
     * @return mixed
     */
    public function actionIndex($idcontacto)
    {
        $searchModel = new Mds_org_situacionSearch();
        $searchModel->idcontacto = $idcontacto;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_situacion', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_org_situacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_situacion', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_org_situacion #" . $id,
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
     * Creates a new Mds_org_situacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idcontacto)
    {
        $request = Yii::$app->request;
        $model = new Mds_org_situacion();
        $model->idcontacto = $idcontacto;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Situación",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $model->inicio =  date('Y-m-d', strtotime(str_replace('/', '-', $model->inicio)));
                $model->fin =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fin)));
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $contacto  = Mds_org_contacto::findOne($model->idcontacto);
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    $edificio = Sds_gis_capa_item::findOne($model->idcapaitem);
                    $usuario = Yii::$app->user->identity;
                    $idusuario = $usuario != null ? $usuario->idusuario : null;
                    if (!isset($idusuario) || $idusuario == null) {
                        $model = new \app\models\LoginForm();
                        return Yii::$app->getResponse()->redirect([
                            'site/login',
                            'model' => $model,
                        ]);
                    }
                    $nombre = "1063_" . $edificio->descripcion . "_" . $model->inicio . '.' . $extension;
                    $ruta = 'uploads/contactos/' . $contacto->legajo . '_' . $persona->apellido . '_' . $persona->nombre;
                    $model->path = $ruta . '/' . $nombre;
                    $documento = new Mds_org_documento();
                    $documento->idusuario = $usuario->idusuario;
                    $documento->tipo = 1063;
                    $documento->nombre = $nombre;
                    $documento->fecha = date('Y-m-d');
                    $documento->path = $model->path;
                    $documento->detalle = "Cargado desde situaciones";
                    $documento->idcontacto = $contacto->idcontacto;
                    $documento->save(false);
                    $model->iddocumento = $documento->iddocumento;
                    //adjunto (crear documento con idusuario=logueado, tipo=1063, nombre=<lugar_inicio>, fecha=actual, 
                    //path=uploads/contactos/<legajo_apellido_nombre>/<idtipo_lugar_inicio>,
                    //detalle=Cargado desde situaciones, idcontacto=idcontacto)                    
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $tmpfile->saveAs($ruta . '/' . $nombre);
                    if ($model->save(false)) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_situacion', $model->idsituacion, $model->getAttributes());
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Nueva Situación",
                            'content' => '<span class="text-success">Creada exitosamente.</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                        ];
                    }
                } else {
                    $model->addError("temp_archivo_adjunto", "Debe agregar un adjunto válido.");
                }
            }
            return [
                'title' => "Nueva Situación",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_situacion', $model->idsituacion, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idsituacion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_org_situacion model.
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
                    'title' => "Actualizar Situación #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_situacion', $model->idsituacion, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Actualizar Situación #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Actualizar Situación #" . $id,
                    'content' => $this->renderAjax('update', [
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
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_situacion', $model->idsituacion, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idsituacion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_org_situacion model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_org_situacion', $id, $model->getAttributes());
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
     * Delete multiple existing Mds_org_situacion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /* public function actionBulkDelete()
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
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else { */
    /*
            *   Process for non-ajax request
            */
    /* return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Mds_org_situacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_situacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_org_situacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
