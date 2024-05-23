<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_direccion;
use app\models\Mds_certificacion_programa;
use app\models\Mds_certificacion_programaSearch;
use app\models\Mds_certificacion_programa_monto;
use app\models\Mds_certificacion_programa_adjunto;
use app\models\Mds_certificacion_programa_requisito;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;
use yii\web\Controller;
use \yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_item;
use yii\web\ForbiddenHttpException;

/**
 * Mds_certificacion_programaController implements the CRUD actions for Mds_certificacion_programa model.
 */
class Mds_certificacion_programaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'reactivate' => ['POST'],
                    'listado_programas' => ['POST'],
                    'listado_adjuntos' => ['POST'],
                    'precargarmonto' => ['POST'],
                    'permite_cambioresponsable' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'view', 'update', 'delete', 'reactivate'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'update', 'delete', 'reactivate'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL));
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_certificacion_programa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        $searchModel = new Mds_certificacion_programaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterDirecciones' => $this->getFilterDirecciones(),
            'filterProgramas' => $this->getFilterProgramas(),
            'filterTipoSubsidio' => $this->getListTipoSubsidio(),
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
        ]);
    }

    /**
     * Displays a single Mds_certificacion_programa model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model_certificacion_programa_monto = Mds_certificacion_programa_monto::find()
            ->where(['idcertificacionprograma' => $id, 'deleted_at' => null, 'fecha_fin' => null])
            ->one();
        $model_certificacion_programa_adjunto = Mds_certificacion_programa_adjunto::find()
            ->where(['idcertificacionprograma' => $id, 'obligatorio' => 1, 'deleted_at' => null])
            ->all();

        $selectAdjuntos = '';
        foreach ($model_certificacion_programa_adjunto as $elemento) {
            $selectAdjuntos .= Sds_com_configuracion::getDescripcion($elemento->idadjunto) . ', ';
        }

        $model_certificacion_programa_adjunto_sugerida = Mds_certificacion_programa_adjunto::find()
            ->where(['idcertificacionprograma' => $id, 'obligatorio' => 0, 'deleted_at' => null])
            ->all();

        $selectAdjuntosSugeridos = '';
        foreach ($model_certificacion_programa_adjunto_sugerida as $elemento) {
            $selectAdjuntosSugeridos .= Sds_com_configuracion::getDescripcion($elemento->idadjunto) . ', ';
        }

        $model_certificacion_programa_requisito = Mds_certificacion_programa_requisito::find()
            ->where(['idcertificacionprograma' => $id, 'deleted_at' => null])
            ->all();

        $selectRequisitos = '';
        foreach ($model_certificacion_programa_requisito as $elemento) {
            $selectRequisitos .= Sds_com_configuracion::getDescripcion($elemento->idrequisito) . ', ';
        }
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_programa', $id, $model->getAttributes());

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Ver registro #{$id}",
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
                    'selectAdjuntos' => $selectAdjuntos,
                    'selectRequisitos' => $selectRequisitos,
                    'selectAdjuntosSugeridos' => $selectAdjuntosSugeridos,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
                'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
                'selectAdjuntos' => $selectAdjuntos,
                'selectRequisitos' => $selectRequisitos,
                'selectAdjuntosSugeridos' => $selectAdjuntosSugeridos,
            ]);
        }
    }

    /**
     * Creates a new Mds_certificacion_programa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mds_certificacion_programa();
        $model_certificacion_programa_monto = new Mds_certificacion_programa_monto();
        $monto_guardado = true;
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo registro",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listProgramas' => $this->getListProgramas(),
                        'listTipoSubsidio' => $this->getListTipoSubsidio(),
                        'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
                        'listTipoAdjuntos' => $this->getListTiposAdjuntos(),
                        'listRequisitos' => $this->getListRequisitos(),
                        'cantidadNiveles' => $this->getListCantidadNivelesAutorizacion(),
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $model->created_at = date('Y-m-d H:i:s');
                $model->idusuario_carga = Yii::$app->user->id;

                $existente = Mds_certificacion_programa::find()
                    ->where(['idcertificaciondireccion' => $model->idcertificaciondireccion, 'idprograma' => $model->idprograma])
                    ->andWhere('deleted_at IS NULL')
                    ->all();

                if (count($existente) == 0) {
                    if ($model->save()) {

                        $monto = $request->post()['Mds_certificacion_programa_monto']['monto'];
                        if ($monto) {
                            $monto_guardado = $this->crearProgramaMonto($model->idcertificacionprograma, $monto);
                        }

                        $existeAdjuntos = isset(Yii::$app->request->post()['adjunto']);
                        $existeRequisito = isset(Yii::$app->request->post()['requisito']);
                        $existeAdjuntoSugerido = isset(Yii::$app->request->post()['adjunto_sugerido']);

                        if ($monto_guardado && $existeAdjuntos && $existeRequisito) {
                            $adjuntosCargados = Yii::$app->request->post()['adjunto'];
                            $requisitosCargados = Yii::$app->request->post()['requisito'];

                            $this->actualizarProgramaAdjuntosObligatorios($model->idcertificacionprograma, $adjuntosCargados);
                            $this->actualizarProgramaRequisito($model->idcertificacionprograma, $requisitosCargados);
                            if ($existeAdjuntoSugerido) {
                                $adjuntosSugeridosCargados = Yii::$app->request->post()['adjunto_sugerido'];
                                $this->actualizarProgramaAdjuntosSugeridos($model->idcertificacionprograma, $adjuntosSugeridosCargados);
                            }

                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa', $model->idcertificacionprograma, $model->getAttributes());
                            return [
                                //'forceReload' => '#crud-datatable-pjax',
                                'title' => "Nuevo registro creado",
                                'content' => '<span class="text-success">Creado Exitosamente! </span><br>' .
                                    '<span>Dirección: ' . $model->direccion0->direccion0->descripcion . ' </span><br>' .
                                    '<span>Programa:  ' .  $model->programa0->descripcion . ' </span>',
                                'footer' =>
                                Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        } else {
                            $transaction->rollBack();
                            return [
                                'title' => "Nuevo registro",
                                'content' => '<span class="text-danger">Error al crear registro!</span>',
                                'footer' =>
                                Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                            ];
                        }
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    return [
                        'title' => "El registro ya existe",
                        'content' => '<span class="text-danger">El registro ya existe! </span><br>' .
                            '<span>Dirección: ' . $model->direccion0->direccion0->descripcion . ' </span><br>' .
                            '<span>Programa:  ' .  $model->programa0->descripcion . ' </span>',
                        'footer' =>
                        Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Nuevo",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listProgramas' => $this->getListProgramas(),
                    'listTipoSubsidio' => $this->getListTipoSubsidio(),
                    'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
                    'listTipoAdjuntos' => $this->getListTiposAdjuntos(),
                    'listRequisitos' => $this->getListRequisitos(),
                    'cantidadNiveles' => $this->getListCantidadNivelesAutorizacion(),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                //Hacer algo con adjunto_sugerida
                $transaction = Yii::$app->db->beginTransaction();
                $model->created_at = date('Y-m-d H:i:s');
                $model->idusuario_carga = Yii::$app->user->id;

                $existente = Mds_certificacion_programa::find()
                    ->where(['idcertificaciondireccion' => $model->idcertificaciondireccion, 'idprograma' => $model->idprograma])
                    ->andWhere('deleted_at IS NULL')
                    ->all();

                if (count($existente) == 0) {
                    if ($model->save()) {
                        $monto = $request->post()['Mds_certificacion_programa_monto']['monto'];
                        if ($monto) {
                            $monto_guardado = $this->crearProgramaMonto($model->idcertificacionprograma, $monto);
                        }
                        $existeAdjuntos = isset(Yii::$app->request->post()['adjunto']);
                        $existeRequisito = isset(Yii::$app->request->post()['requisito']);
                        $existeAdjuntoSugerido = isset(Yii::$app->request->post()['adjunto_sugerido']);

                        if ($monto_guardado && $existeAdjuntos && $existeRequisito) {
                            $adjuntosCargados = Yii::$app->request->post()['adjunto'];
                            $requisitosCargados = Yii::$app->request->post()['requisito'];
                            $this->actualizarProgramaAdjuntosObligatorios($model->idcertificacionprograma, $adjuntosCargados);
                            $this->actualizarProgramaRequisito($model->idcertificacionprograma, $requisitosCargados);
                            if ($existeAdjuntoSugerido) {
                                $adjuntosSugeridosCargados = Yii::$app->request->post()['adjunto_sugerido'];
                                $this->actualizarProgramaAdjuntosSugeridos($model->idcertificacionprograma, $adjuntosSugeridosCargados);
                            }

                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa', $model->idcertificacionprograma, $model->getAttributes());
                            Yii::$app->session->setFlash('success', " Se creó correctamente el registro.");
                            return $this->redirect(['mds_certificacion_programa/index']);
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', "Error al crear el registro.");
                            return $this->redirect(['mds_certificacion_programa/index']);
                        }
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al crear el registro.");
                        return $this->redirect(['mds_certificacion_programa/index']);
                    }
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listProgramas' => $this->getListProgramas(),
                    'listTipoSubsidio' => $this->getListTipoSubsidio(),
                    'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
                    'listTipoAdjuntos' => $this->getListTiposAdjuntos(),
                    'listRequisitos' => $this->getListRequisitos(),
                    'cantidadNiveles' => $this->getListCantidadNivelesAutorizacion(),
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_certificacion_programa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model_certificacion_programa_monto = Mds_certificacion_programa_monto::find()
            ->where(['idcertificacionprograma' => $id, 'deleted_at' => null, 'fecha_fin' => null])
            ->one();
        $model_certificacion_programa_monto_nuevo = new Mds_certificacion_programa_monto();
        $monto_guardado = true;
        $existeAdjuntos = isset(Yii::$app->request->post()['adjunto']);
        $existeRequisito = isset(Yii::$app->request->post()['requisito']);
        $existeAdjuntoSugerido = isset(Yii::$app->request->post()['adjunto_sugerido']);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar registro #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listProgramas' => $this->getListProgramas(),
                        'listTipoSubsidio' => $this->getListTipoSubsidio(),
                        'model_certificacion_programa_monto' => ($model_certificacion_programa_monto ? $model_certificacion_programa_monto :  $model_certificacion_programa_monto_nuevo),
                        'listTipoAdjuntos' => $this->getListTiposAdjuntos(),
                        'selectAdjuntos' => $model->getAdjuntosObligatorios(),
                        'selectAdjuntosSugeridos' => $model->getAdjuntosSugeridos(),
                        'listRequisitos' => $this->getListRequisitos(),
                        'selectRequisitos' => $model->getRequisitos(),
                        'cantidadNiveles' => $this->getListCantidadNivelesAutorizacion(),
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $model->updated_at = date('Y-m-d H:i:s');
                if ($model->save()) {

                    $monto = $request->post()['Mds_certificacion_programa_monto']['monto'];
                    if ($model_certificacion_programa_monto) { //Existe guardado un monto
                        if ($monto) { //Se ingreso al imput un valor (el mismo o uno nuevo)
                            if ($model_certificacion_programa_monto->monto != $monto) {
                                $monto_guardado = $this->actualizarProgramaMonto($model->idcertificacionprograma, $monto);
                            }
                        } else { //Se borra del imput el valor
                            $monto_guardado = $this->eliminarProgramaMonto($model->idcertificacionprograma);
                        }
                    } else { //NO existe guardado un monto
                        if ($monto) { //Se ingreso al imput un valor
                            $monto_guardado = $this->crearProgramaMonto($model->idcertificacionprograma, $monto);
                        }
                    }

                    if ($monto_guardado && $existeAdjuntos && $existeRequisito) {
                        $adjuntosCargados = Yii::$app->request->post()['adjunto'];
                        $requisitosCargados = Yii::$app->request->post()['requisito'];
                        $adjuntosSugeridosCargados = ($existeAdjuntoSugerido ? Yii::$app->request->post()['adjunto_sugerido'] : []);

                        $this->actualizarProgramaAdjuntosObligatorios($model->idcertificacionprograma, $adjuntosCargados);
                        $this->actualizarProgramaRequisito($model->idcertificacionprograma, $requisitosCargados);
                        $this->actualizarProgramaAdjuntosSugeridos($model->idcertificacionprograma, $adjuntosSugeridosCargados);

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_programa', $id, $model->getAttributes());
                        return [
                            //'forceReload' => '#crud-datatable-pjax',
                            'title' => "Actualizar registro #" . $id,
                            'content' => '<span class="text-success">Se actualizó correctamente el registro </span><br>' .
                                '<span>Dirección: ' . $model->direccion0->direccion0->descripcion . ' </span><br>' .
                                '<span>Programa:  ' .  $model->programa0->descripcion . ' </span>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        $transaction->rollBack();
                        return [
                            'title' => "Actualizar registro #" . $id,
                            'content' => '<span class="text-danger">Error al actualizar registro!</span>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                } else {
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Actualizar registro #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listProgramas' => $this->getListProgramas(),
                    'listTipoSubsidio' => $this->getListTipoSubsidio(),
                    'model_certificacion_programa_monto' => ($model_certificacion_programa_monto ? $model_certificacion_programa_monto :  $model_certificacion_programa_monto_nuevo),
                    'listTipoAdjuntos' => $this->getListTiposAdjuntos(),
                    'selectAdjuntos' => $model->getAdjuntosObligatorios(),
                    'selectAdjuntosSugeridos' => $model->getAdjuntosSugeridos(),
                    'listRequisitos' => $this->getListRequisitos(),
                    'selectRequisitos' => $model->getRequisitos(),
                    'cantidadNiveles' => $this->getListCantidadNivelesAutorizacion(),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            if ($model->load($request->post())) {
                $model->updated_at = date('Y-m-d H:i:s');
                if ($model->save()) {

                    $monto = $request->post()['Mds_certificacion_programa_monto']['monto'];
                    if ($model_certificacion_programa_monto) { //Existe guardado un monto
                        if ($monto) { //Se ingreso al imput un valor (el mismo o uno nuevo)
                            if ($model_certificacion_programa_monto->monto != $monto) {
                                $monto_guardado = $this->actualizarProgramaMonto($model->idcertificacionprograma, $monto);
                            }
                        } else { //Se borra del imput el valor
                            $monto_guardado = $this->eliminarProgramaMonto($model->idcertificacionprograma);
                        }
                    } else { //NO existe guardado un monto
                        if ($monto) { //Se ingreso al imput un valor
                            $monto_guardado = $this->crearProgramaMonto($model->idcertificacionprograma, $monto);
                        }
                    }

                    if ($monto_guardado && $existeAdjuntos) {
                        $adjuntosCargados = Yii::$app->request->post()['adjunto'];
                        $adjuntosSugeridosCargados = ($existeAdjuntoSugerido ? Yii::$app->request->post()['adjunto_sugerido'] : []);
                        $this->actualizarProgramaAdjuntosObligatorios($model->idcertificacionprograma, $adjuntosCargados);
                        $this->actualizarProgramaAdjuntosSugeridos($model->idcertificacionprograma, $adjuntosSugeridosCargados);

                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_programa', $id, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se actualizó correctamente el registro.");
                        return $this->redirect(['mds_certificacion_programa/index']);
                    } else {
                        Yii::$app->session->setFlash('error', "Error al actualizar el registro.");
                        return $this->redirect(['mds_certificacion_programa/index']);
                    }
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listProgramas' => $this->getListProgramas(),
                    'listTipoSubsidio' => $this->getListTipoSubsidio(),
                    'model_certificacion_programa_monto' => ($model_certificacion_programa_monto ? $model_certificacion_programa_monto :  $model_certificacion_programa_monto_nuevo),
                    'listTipoAdjuntos' => $this->getListTiposAdjuntos(),
                    'selectAdjuntos' => $model->getAdjuntosObligatorios(),
                    'selectAdjuntosSugeridos' => $model->getAdjuntosSugeridos(),
                    'listRequisitos' => $this->getListRequisitos(),
                    'selectRequisitos' => $model->getRequisitos(),
                    'cantidadNiveles' => $this->getListCantidadNivelesAutorizacion(),
                ]);
            }
        }
    }

    /**
     * Deletes an existing Mds_certificacion_programa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = Yii::$app->user->id;
        if ($model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', " Se eliminó correctamente el registro.");
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_programa', $model->idcertificacionprograma, $model->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "Error al borrar el registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
        }
        return $this->redirect(['index']);
    }

    public function actionReactivate($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->deleted_at = null;
            $model->idusuario_borra = null;
            if ($model->validate()) {
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente el registro.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_programa', $model->idcertificacionprograma, $model->getAttributes());
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el registro.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al reactivar el registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "El registro no existe.");
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mds_certificacion_programa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_certificacion_programa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_certificacion_programa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function getListDirecciones()
    {
        //Busqueda de direcciones cargadas en sds configuracion
        $iddireccion = Mds_certificacion_direccion::find()
            ->select(['idcertificaciondireccion', 'UPPER(descripcion) as descripcion'])
            ->where(['mds_certificacion_direccion.deleted_at' => NULL])
            ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion = sds_com_configuracion.idconfiguracion')
            ->orderBy(['sds_com_configuracion.descripcion' => SORT_ASC])->asArray()->all();
        $iddireccion = ArrayHelper::map($iddireccion, 'idcertificaciondireccion', 'descripcion');
        return $iddireccion;
    }

    protected function getListProgramas()
    {
        //Busqueda de programas cargados en sds configuracion
        $idprograma = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_PROGRAMA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $idprograma = ArrayHelper::map($idprograma, 'idconfiguracion', 'descripcion');
        return $idprograma;
    }

    protected function getFilterProgramas()
    {
        $programasFiltro = Mds_certificacion_programa::findBySql(
            "SELECT
            configuracion.idconfiguracion as idprograma,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_programa programa
            INNER JOIN sds_com_configuracion configuracion
            ON programa.idprograma = configuracion.idconfiguracion
            WHERE programa.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();

        $programasFiltro = ArrayHelper::map($programasFiltro, 'idprograma', 'descripcion');
        return $programasFiltro;
    }

    protected function getFilterDirecciones()
    {
        $direccionesFiltro = Mds_certificacion_programa::findBySql(
            "SELECT
            programa.idcertificaciondireccion,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_programa programa
            INNER JOIN mds_certificacion_direccion direccion
            ON programa.idcertificaciondireccion = direccion.idcertificaciondireccion
            INNER JOIN sds_com_configuracion configuracion
            ON direccion.iddireccion = configuracion.idconfiguracion
            WHERE programa.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();

        $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'idcertificaciondireccion', 'descripcion');
        return $direccionesFiltro;
    }

    protected function getListTiposAdjuntos()
    {
        //Busqueda tipos de adjuntos
        $adjuntos_especiales = [Mds_certificacion_programa::ADJUNTO_OBSERVAR, Mds_certificacion_programa::ADJUNTO_BAJA, Mds_certificacion_programa::ADJUNTO_RECHAZAR];
        $tipos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_TIPO_ADJUNTO, "activo" => 1])
            ->andWhere(['NOT IN', 'idconfiguracion', $adjuntos_especiales])
            ->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $tipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $tipos;
    }
    protected function getListRequisitos()
    {
        //Busqueda de requisitos que son necesarios para solicitar el programa
        $requisitos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_PROGRAMA_REQUISITO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $requisitos = ArrayHelper::map($requisitos, 'idconfiguracion', 'descripcion');
        return $requisitos;
    }

    protected function getListCantidadNivelesAutorizacion()
    {
        $niveles = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_DIRECCION_RECEPTORA, "activo" => 1])
            ->andWhere(['!=', 'idconfiguracion', Mds_certificacion::ID_NIVEL4])
            ->andWhere(['!=', 'idconfiguracion', Mds_certificacion::ID_NIVEL5])
            ->asArray()->all();

        $num_elements = count($niveles);
        $arrayNiveles = [];

        for ($i = 1; $i <= $num_elements; $i++) {
            $arrayNiveles[$i] = $i;
        }
        return $arrayNiveles;
    }

    protected function getListTipoSubsidio()
    {
        //Busqueda de tipos de subsidios cargados en sds configuracion
        $arrTipos = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_TIPO_SUBSIDIO, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $listTipos = ArrayHelper::map($arrTipos, 'idconfiguracion', 'descripcion');
        return $listTipos;
    }

    public function actionListado_programas($id = null)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = $this->getPermissions($usuarioAuth);
        $listado = null;
        if ($id && count($permissions) > 0) {
            $programas = Mds_certificacion_programa::find()
                ->select(['mds_certificacion_programa.idprograma', 'mds_certificacion_programa.cambio_responsable', 'sds_com_configuracion.descripcion as descripcion'])
                ->where(['mds_certificacion_programa.deleted_at' => NULL, 'mds_certificacion_programa.idcertificaciondireccion' => $id])
                ->innerJoin('sds_com_configuracion', 'mds_certificacion_programa.idprograma = sds_com_configuracion.idconfiguracion')
                ->orderBy(['sds_com_configuracion.descripcion' => SORT_ASC])
                ->asArray()
                ->all();
            if (sizeof($programas) > 0) {
                $listado = json_encode($programas);
            }
        }
        return $listado;
    }

    public function actionListado_adjuntos($iddireccion, $idprograma)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $listado = null;
            $programa = Mds_certificacion_programa::find()
                ->select('idcertificacionprograma')
                ->where(['idcertificaciondireccion' => $iddireccion, 'idprograma' => $idprograma, 'deleted_at' => null])
                ->one();

            if ($programa) {
                $adjuntos = Mds_certificacion_programa_adjunto::find()
                    ->select(['mds_certificacion_programa_adjunto.idadjunto', 'sds_com_configuracion.descripcion as descripcion', 'mds_certificacion_programa_adjunto.obligatorio'])
                    ->innerJoin('sds_com_configuracion', 'mds_certificacion_programa_adjunto.idadjunto = sds_com_configuracion.idconfiguracion')
                    ->where(['idcertificacionprograma' => $programa->idcertificacionprograma, 'deleted_at' => null])
                    ->orderBy(['sds_com_configuracion.descripcion' => SORT_ASC])
                    ->asArray()
                    ->all();

                if (sizeof($adjuntos) > 0) {
                    $listado = json_encode($adjuntos);
                }
            }
            return $listado;
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionPrecargarmonto($iddireccion, $idprograma)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            //|| Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            //|| Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $data = null;

            $fechaActual = date('Y-m-d H:i:s');

            $programa = Mds_certificacion_programa::find()
                ->select('idcertificacionprograma,requiere_autorizacion,cant_niveles_autorizacion')
                ->where(['idcertificaciondireccion' => $iddireccion, 'idprograma' => $idprograma, 'deleted_at' => null])
                ->one();

            if ($programa) {
                $data['requiere_autorizacion'] = $programa['requiere_autorizacion'];
                $data['cant_niveles_autorizacion'] = $programa['cant_niveles_autorizacion'];
                $programa_monto = Mds_certificacion_programa_monto::find()
                    ->select('monto')
                    ->where(['idcertificacionprograma' => $programa->idcertificacionprograma, 'deleted_at' => null])
                    ->andWhere(['fecha_fin' => null])
                    // ->andWhere(['<=', 'fecha_inicio', $fechaActual])
                    // ->andWhere(new BetweenColumnsCondition($fechaActual, 'BETWEEN', 'fecha_inicio', 'fecha_fin'))
                    ->one();
                if ($programa_monto) {
                    $data['monto'] = $programa_monto['monto'];
                }
            }
            $data = json_encode($data);
            return $data;
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionPermite_cambioresponsable($iddireccion, $idprograma)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            // || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            // || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
        ) {
            $data = null;
            $programa = Mds_certificacion_programa::find()
                ->select('idcertificacionprograma,cambio_responsable')
                ->where(['idcertificaciondireccion' => $iddireccion, 'idprograma' => $idprograma, 'deleted_at' => null])
                ->one();

            if ($programa) {
                $data['cambio_responsable'] = $programa['cambio_responsable'];
            }
            $data = json_encode($data);
            return $data;
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    private function crearProgramaMonto($idcertificacionprograma, $monto)
    {
        $guardado = false;
        $programa_monto = Mds_certificacion_programa_monto::find()
            ->where(['idcertificacionprograma' => $idcertificacionprograma, 'deleted_at' => null, 'fecha_fin' => null])
            ->one();

        if (!$programa_monto) {
            $model_certificacion_programa_monto_nuevo = new Mds_certificacion_programa_monto();
            $model_certificacion_programa_monto_nuevo->idcertificacionprograma = $idcertificacionprograma;
            $model_certificacion_programa_monto_nuevo->monto = $monto;
            $model_certificacion_programa_monto_nuevo->fecha_inicio = date('Y-m-d H:i:s');
            $model_certificacion_programa_monto_nuevo->created_at = date('Y-m-d H:i:s');
            $model_certificacion_programa_monto_nuevo->idusuario_carga = Yii::$app->user->id;

            if ($model_certificacion_programa_monto_nuevo->save()) {
                $guardado = true;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa_monto', $model_certificacion_programa_monto_nuevo->idcertificacionprogramamonto, $model_certificacion_programa_monto_nuevo->getAttributes());
            }
        }
        return $guardado;
    }

    private function actualizarProgramaMonto($idcertificacionprograma, $monto)
    {

        $guardado = false;
        $programa_monto = Mds_certificacion_programa_monto::find()
            ->where(['idcertificacionprograma' => $idcertificacionprograma, 'deleted_at' => null, 'fecha_fin' => null])
            ->one();

        $programa_monto->fecha_fin = date('Y-m-d H:i:s');
        $programa_monto->updated_at = date('Y-m-d H:i:s');

        if ($programa_monto->save()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_programa_monto', $programa_monto->idcertificacionprogramamonto, $programa_monto->getAttributes());

            $model_certificacion_programa_monto_nuevo = new Mds_certificacion_programa_monto();
            $model_certificacion_programa_monto_nuevo->idcertificacionprograma = $idcertificacionprograma;
            $model_certificacion_programa_monto_nuevo->monto = $monto;
            $model_certificacion_programa_monto_nuevo->fecha_inicio = date('Y-m-d H:i:s');
            $model_certificacion_programa_monto_nuevo->created_at = date('Y-m-d H:i:s');
            $model_certificacion_programa_monto_nuevo->idusuario_carga = Yii::$app->user->id;

            if ($model_certificacion_programa_monto_nuevo->save()) {
                $guardado = true;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa_monto', $model_certificacion_programa_monto_nuevo->idcertificacionprogramamonto, $model_certificacion_programa_monto_nuevo->getAttributes());
            }
        }
        return $guardado;
    }

    private function eliminarProgramaMonto($idcertificacionprograma)
    {
        $guardado = false;
        $programa_monto = Mds_certificacion_programa_monto::find()
            ->where(['idcertificacionprograma' => $idcertificacionprograma, 'deleted_at' => null, 'fecha_fin' => null])
            ->one();

        $programa_monto->fecha_fin = date('Y-m-d H:i:s');
        $programa_monto->updated_at = date('Y-m-d H:i:s');

        if ($programa_monto->save()) {
            $guardado = true;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_programa_monto', $programa_monto->idcertificacionprogramamonto, $programa_monto->getAttributes());
        }
        return $guardado;
    }

    private function actualizarProgramaAdjuntosObligatorios($idcertificacionprograma, $array_nuevos_adjuntos)
    {
        $obligatorio = 1;
        $array_adjuntos = Mds_certificacion_programa_adjunto::find()->where(["idcertificacionprograma" => $idcertificacionprograma, "obligatorio" => $obligatorio, "deleted_at" => null])->all();
        foreach ($array_adjuntos as $elemento) {
            $idadjunto = $elemento->idadjunto;
            $key = array_search($idadjunto, $array_nuevos_adjuntos);
            if (in_array($idadjunto, $array_nuevos_adjuntos)) { //El programa_adjunto ya esta creado, se debe quitar del array_nuevos_adjuntos
                unset($array_nuevos_adjuntos[$key]);
            } else { //El programa_adjunto ya no esta relacionado con el programa, se debe eliminar
                $model_certificacion_programa_adjunto = Mds_certificacion_programa_adjunto::find()->where(['idprogramaadjunto' => $elemento->idprogramaadjunto])->one();
                $model_certificacion_programa_adjunto->deleted_at = date('Y-m-d H:i:s');
                $model_certificacion_programa_adjunto->idusuario_borra = Yii::$app->user->id;
                if ($model_certificacion_programa_adjunto->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_programa_adjunto', $model_certificacion_programa_adjunto->idprogramaadjunto, $model_certificacion_programa_adjunto->getAttributes());
                }
            }
        }

        foreach ($array_nuevos_adjuntos as $key => $idadjunto) { //Se crearán los nuevos programa_adjunto al programa
            $model_certificacion_programa_adjunto_nuevo = new Mds_certificacion_programa_adjunto();
            $model_certificacion_programa_adjunto_nuevo->idcertificacionprograma = $idcertificacionprograma;
            $model_certificacion_programa_adjunto_nuevo->idadjunto = $idadjunto;
            $model_certificacion_programa_adjunto_nuevo->obligatorio = $obligatorio;
            $model_certificacion_programa_adjunto_nuevo->created_at = date('Y-m-d H:i:s');
            $model_certificacion_programa_adjunto_nuevo->idusuario_carga = Yii::$app->user->id;

            if ($model_certificacion_programa_adjunto_nuevo->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa_adjunto', $model_certificacion_programa_adjunto_nuevo->idprogramaadjunto, $model_certificacion_programa_adjunto_nuevo->getAttributes());
            }
        }
    }

    private function actualizarProgramaAdjuntosSugeridos($idcertificacionprograma, $array_nuevos_adjuntos)
    {
        $obligatorio = 0;
        $array_adjuntos = Mds_certificacion_programa_adjunto::find()->where(["idcertificacionprograma" => $idcertificacionprograma, "obligatorio" => $obligatorio, "deleted_at" => null])->all();
        foreach ($array_adjuntos as $elemento) {
            $idadjunto = $elemento->idadjunto;
            $key = array_search($idadjunto, $array_nuevos_adjuntos);
            if (in_array($idadjunto, $array_nuevos_adjuntos)) { //El programa_adjunto ya esta creado, se debe quitar del array_nuevos_adjuntos
                unset($array_nuevos_adjuntos[$key]);
            } else { //El programa_adjunto ya no esta relacionado con el programa, se debe eliminar
                $model_certificacion_programa_adjunto = Mds_certificacion_programa_adjunto::find()->where(['idprogramaadjunto' => $elemento->idprogramaadjunto])->one();
                $model_certificacion_programa_adjunto->deleted_at = date('Y-m-d H:i:s');
                $model_certificacion_programa_adjunto->idusuario_borra = Yii::$app->user->id;
                if ($model_certificacion_programa_adjunto->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_programa_adjunto', $model_certificacion_programa_adjunto->idprogramaadjunto, $model_certificacion_programa_adjunto->getAttributes());
                }
            }
        }

        foreach ($array_nuevos_adjuntos as $key => $idadjunto) { //Se crearán los nuevos programa_adjunto al programa
            $model_certificacion_programa_adjunto_nuevo = new Mds_certificacion_programa_adjunto();
            $model_certificacion_programa_adjunto_nuevo->idcertificacionprograma = $idcertificacionprograma;
            $model_certificacion_programa_adjunto_nuevo->idadjunto = $idadjunto;
            $model_certificacion_programa_adjunto_nuevo->obligatorio = $obligatorio;
            $model_certificacion_programa_adjunto_nuevo->created_at = date('Y-m-d H:i:s');
            $model_certificacion_programa_adjunto_nuevo->idusuario_carga = Yii::$app->user->id;

            if ($model_certificacion_programa_adjunto_nuevo->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa_adjunto', $model_certificacion_programa_adjunto_nuevo->idprogramaadjunto, $model_certificacion_programa_adjunto_nuevo->getAttributes());
            }
        }
    }

    private function actualizarProgramaRequisito($idcertificacionprograma, $array_nuevos_requisitos)
    {
        $array_requisitos = Mds_certificacion_programa_requisito::find()
            ->where(["idcertificacionprograma" => $idcertificacionprograma, "deleted_at" => null])
            ->all();

        foreach ($array_requisitos as $elemento) {
            $idrequisito = $elemento->idrequisito;
            $key = array_search($idrequisito, $array_nuevos_requisitos);
            if (in_array($idrequisito, $array_nuevos_requisitos)) { //El programa_adjunto ya esta creado, se debe quitar del array_nuevos_adjuntos
                unset($array_nuevos_requisitos[$key]);
            } else { //El programa_adjunto ya no esta relacionado con el programa, se debe eliminar
                $model_certificacion_programa_requisito = Mds_certificacion_programa_requisito::find()->where(['idprogramarequisito' => $elemento->idprogramarequisito])->one();
                $model_certificacion_programa_requisito->deleted_at = date('Y-m-d H:i:s');
                $model_certificacion_programa_requisito->idusuario_borra = Yii::$app->user->id;
                if ($model_certificacion_programa_requisito->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_programa_requisito', $model_certificacion_programa_requisito->idprogramarequisito, $model_certificacion_programa_requisito->getAttributes());
                }
            }
        }

        foreach ($array_nuevos_requisitos as $key => $idrequisito) { //Se crearán los nuevos programa_adjunto al programa
            $model_certificacion_programa_requisito_nuevo = new Mds_certificacion_programa_requisito();
            $model_certificacion_programa_requisito_nuevo->idcertificacionprograma = $idcertificacionprograma;
            $model_certificacion_programa_requisito_nuevo->idrequisito = $idrequisito;
            $model_certificacion_programa_requisito_nuevo->created_at = date('Y-m-d H:i:s');
            $model_certificacion_programa_requisito_nuevo->idusuario_carga = Yii::$app->user->id;

            if ($model_certificacion_programa_requisito_nuevo->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa_requisito', $model_certificacion_programa_requisito_nuevo->idprogramarequisito, $model_certificacion_programa_requisito_nuevo->getAttributes());
            }
        }
    }

    private function getPermissions($idusuario)
    {
        $result = [];
        $items = [
            Mds_seg_item::MODULO_CERTIFICACIONES_FUNCIONARIO,
            Mds_seg_item::MODULO_CERTIFICACIONES_SOLICITUD,
            Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_SIMPLE,
            Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_GENERAL,
            Mds_seg_item::MODULO_CERTIFICACIONES_DIRECCION_PROVINCIAL,
            Mds_seg_item::MODULO_CERTIFICACIONES_SUBSECRETARIA,
            Mds_seg_item::MODULO_CERTIFICACIONES_ADMINISTRACION,
            Mds_seg_item::MDS_CERTIFICACION_ADMINISTRADOR
        ];
        foreach ($items as $item) {
            $permiso =
                Mds_seg_permiso::findBySql(
                    "SELECT * 
                     FROM mds_seg_permiso 
                     WHERE idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario->idusuario) 
                     AND iditem = $item"
                )
                ->all();
            if ($this->hasOnePermission($permiso, "alta") || $this->hasOnePermission($permiso, "modifica")) {
                $result[] = $permiso;
            }
        };
        return $result;
    }

    protected function hasOnePermission($permissions, $action)
    {
        $hasOnePermission = false;
        $i = 0;
        while (!$hasOnePermission && $i < count($permissions)) {
            $permission = $permissions[$i];
            $hasOnePermission = $permission[$action];
            $i++;
        }

        return $hasOnePermission;
    }
}
