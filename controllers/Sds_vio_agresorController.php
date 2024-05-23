<?php

namespace app\controllers;

use Yii;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_vio_agresor;
use app\models\Sds_vio_agresorSearch;
use app\models\Sds_vio_intervencion;
use app\models\Sds_vio_intervencion_agresor;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_vio_agresor_consumo;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_800_llamada;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\Response;

use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Sds_vio_agresorController implements the CRUD actions for Sds_vio_agresor model.
 */
class Sds_vio_agresorController extends Controller
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
                    'reactivate' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_vio_agresor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            $searchModel = new Sds_vio_agresorSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_agresor/index', null, array());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'generosFiltro' => $this->getFilterGeneros(),
                'hasRolAdminGeneral' => $hasRolAdminGeneral
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Lists all Sds_vio_intervencion_agresor models.
     * @return mixed
     */
    public function actionIndex_interv_agresor($idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = new Sds_vio_intervencion_agresor();
            $model->idintervencion = $idintervencion;
            $model->agresores = $this->searchAgresores($idintervencion);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion&id=' . $idintervencion, null, array());
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
            $model_intervencion = Sds_vio_intervencion::findOne($idintervencion);
            $model_llamada = Sds_800_llamada::findOne($model_intervencion->idllamada);
            $estaAtendida = $model_llamada ? $model_llamada->estado == Sds_800_llamada::ESTADO_ATENDIDA : true;

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Listado de Agresores",
                    'content' => $this->renderAjax('index_interv_agresor', [
                        'model' => $model,
                        'estaAtendida' => $estaAtendida,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Sds_vio_agresor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;

            $model = $this->findModel($id);
            $vioConsumoSelectOptions = $this->getListConsumoProblematico();

            if ($idintervencion) {
                $intervencionAgresor = Sds_vio_intervencion_agresor::getIntervencion($idintervencion, $id);
                $model->parentezco = $intervencionAgresor['parentezco'];
            }

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_agresor', $id, $model->getAttributes());
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'title' => "Ver Agresor DNI: " . $model->dni,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                    ]),
                    'footer' =>
                    $idintervencion ?
                        Html::a(
                            'Volver',
                            ['index_interv_agresor', 'idintervencion' => $idintervencion],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        ) :
                        Html::button('Volver', ['class' => 'btn btn-info pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                if (!$idintervencion) {
                    return $this->render('view', [
                        'model' => $model,
                        'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                    ]);
                } else {
                    return $this->redirect(['/sds_vio_intervencion/index']);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_vio_agresor model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        $canCreate = $idintervencion ? Sds_vio_intervencion::estaAtendida($idintervencion) : true;
        if ($hasRolViolencia && $canCreate) {
            $request = Yii::$app->request;
            $model = new Sds_vio_agresor();
            $vioVinculoSelectOptions = $this->getListVinculo();
            $vioConsumoSelectOptions = $this->getListConsumoProblematico();
            $escolaridad = $this->getListEscolaridad();

            $consumosCargados = array();
            if (isset(Yii::$app->request->post()['consumos'])) {
                $consumosCargados = Yii::$app->request->post()['consumos'];
            }

            if ($request->isAjax) {  //Entra si viene del boton "Agregar" desde "Asignar Agresores" en intervencion

                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    //Si no llega nada por post (es la primera que entra)
                    return [
                        'title' => "Crear agresor",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
                            'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                            'escolaridad' => $escolaridad,
                        ]),
                        'footer' =>
                        Html::a(
                            'Volver',
                            ['index_interv_agresor', 'idintervencion' => $idintervencion],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                        )
                            . Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit", 'id' => 'boton-guardar-agresor'])
                    ];
                } else if ($model->load($request->post())) {
                    //Si quiero guardar
                    $model->activo = 1;
                    $parentezco = $model->parentezco;

                    $modelAgresor = $this->verificarAgresor($model);
                    $guardado = $modelAgresor->save();

                    if ($guardado) { //Si pudo guardar el agresor
                        $this->actualizarConsumosAgresor($modelAgresor->idagresor, $consumosCargados);
                        $comp = Sds_vio_intervencion_agresor::findOne(['idintervencion' => $idintervencion, 'idagresor' => $modelAgresor->idagresor, 'activo' => 1]);

                        if (!$comp) { //Si no existe una intervencion_agresor ya creada, creo una nueva
                            $model_intervencion_agresor = new Sds_vio_intervencion_agresor();
                            $model_intervencion_agresor->idintervencion = $idintervencion;
                            $model_intervencion_agresor->idagresor = $modelAgresor->idagresor;
                            $model_intervencion_agresor->parentezco = $parentezco;
                            $model_intervencion_agresor->activo = 1;
                            if (!($model_intervencion_agresor->save())) {
                                //No pudo crear la intervencion_agresor porque no valido el modelo (no llegaron los campos obligatorios)
                                return [
                                    'title' => "Agresores",
                                    'content' => $this->renderAjax('create', [
                                        'model' => $model,
                                        'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
                                        'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                                        'escolaridad' => $escolaridad,
                                    ]),
                                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                                ];
                            }
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_intervencion_agresor', $model_intervencion_agresor->idintervencion, $model_intervencion_agresor->getAttributes());
                        } else { //Ya existe una intervecion_agresor pero pudo haber actualizado el parentezco, entonces lo actualizo

                            $comp->idagresor = $modelAgresor->idagresor;
                            $comp->activo = 1;
                            $comp->parentezco = $parentezco;

                            if (!$comp->save()) { //No pudo actualizar porque no valido el modelo 

                                return [
                                    'title' => "Agresores",
                                    'content' => $this->renderAjax('create', [
                                        'model' => $model,
                                        'idintervencion' => $idintervencion,
                                        'vinculo_personal_seguridad' => $vioVinculoSelectOptions,
                                        // 'inicializarSelect' => false,
                                    ]),
                                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                                ];
                            }
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion_agresor', $comp->idintervencionagresor, $comp->getAttributes());
                        }

                        //Pudo crear el agresor y la intervencion_agresor 
                        return [
                            'title' => "Listado de Agresores",
                            'content' => '<span class="text-success">Agresor asignado exitosamente!</span>',
                            'footer' => Html::a(
                                ' Volver a la Grilla',
                                ['index_interv_agresor', 'idintervencion' => $idintervencion],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            ) .
                                Html::a('Agregar Otro', ['create', 'idintervencion' => $idintervencion], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    } else {
                        //No pudo crear el agresor porque no valido el modelo
                        return [
                            'title' => "Agresores",
                            'content' => '<span class="text-danger">Error al crear el agresor</span>',
                            'footer' => Html::a(
                                ' Volver a la Grilla',
                                ['index_interv_agresor', 'idintervencion' => $idintervencion],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            ) .
                                Html::a('Agregar', ['create', 'idintervencion' => $idintervencion], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                }
            } else {
                //Viene desde el ABM de agresor
                if (!$idintervencion) {
                    if ($model->load($request->post())) {
                        //Si quiero guardar
                        $model->activo = 1;
                        $modelActualizado = $this->verificarAgresor($model);
                        $guardado = $modelActualizado->save();

                        if ($guardado) {
                            $this->actualizarConsumosAgresor($modelActualizado->idagresor, $consumosCargados);
                            Yii::$app->session->setFlash('success', " Se generó correctamente el agresor.");
                            return $this->redirect(['index']);
                        }
                    } else {
                        return $this->render('create', [
                            'model' => $model,
                            'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
                            'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                            'escolaridad' => $escolaridad,
                        ]);
                    }
                }
                return $this->redirect(['/sds_vio_intervencion/index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_vio_agresor model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $parentezco = '';
            $consumosCargados = array();
            $escolaridad = $this->getListEscolaridad();
            $vioVinculoSelectOptions = $this->getListVinculo();
            $vioConsumoSelectOptions = $this->getListConsumoProblematico();
            $vioConsumosPreCargados = ArrayHelper::map(
                $model->getConsumos(),
                'idconsumo',
                function ($model) {
                    return $model->idconsumo;
                }
            );

            $existDescuento = isset(Yii::$app->request->post()['Sds_vio_agresor']['desc_actividad']);
            if ($existDescuento && Yii::$app->request->post()['Sds_vio_agresor']['desc_actividad'] == 0) {
                $model->desc_jubilacion = null;
            }

            $comp = Sds_vio_intervencion_agresor::findOne(['idintervencion' => $idintervencion, 'idagresor' => $model->idagresor]);
            if ($comp) {
                $parentezco = $comp->parentezco;
            }

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Actualizar Agresor DNI: " . $model->dni,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                            'parentezco' => $parentezco,
                            'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
                            'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                            'vioConsumosPreCargados' => $vioConsumosPreCargados,
                            'escolaridad' => $escolaridad,
                        ]),
                        'footer' => ($idintervencion ?
                            Html::a(
                                'Volver',
                                ['index_interv_agresor', 'idintervencion' => $idintervencion],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            ) :
                            Html::button('Volver', ['class' => 'btn btn-info pull-left', 'data-dismiss' => "modal"])
                        )
                            . Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"])
                    ];
                } else if ($model->load($request->post())) {

                    if ($model->validate()) {

                        if (isset(Yii::$app->request->post()['consumos'])) {
                            $consumosCargados = Yii::$app->request->post()['consumos'];
                        } else {
                            $model->consumo_problematico = 0;
                        }

                        if ($model->save()) {

                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_agresor', $model->idagresor, $model->getAttributes());

                            $this->actualizarConsumosAgresor($model->idagresor, $consumosCargados);

                            $model_intervencion_agresor = Sds_vio_intervencion_agresor::findOne(['idintervencion' => $idintervencion, 'idagresor' => $model->idagresor]);
                            if ($model_intervencion_agresor) {
                                $model_intervencion_agresor->parentezco = $model->parentezco;
                                $model_intervencion_agresor->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_intervencion_agresor', $model_intervencion_agresor->idintervencionagresor, $model_intervencion_agresor->getAttributes());
                            }

                            if ($idintervencion) {
                                return [
                                    'title' => "Listado de Agresores",
                                    'content' => '<span class="text-success">Agresor actualizado correctamente</span>',
                                    'footer' => Html::a(
                                        ' Volver a la Grilla',
                                        ['index_interv_agresor', 'idintervencion' => $idintervencion],
                                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                                    )
                                ];
                            } else {
                                return [
                                    'title' => "Ver Agresor DNI: " . $model->dni,
                                    'content' => $this->renderAjax('view', [
                                        'model' => $model,
                                        'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                                    ]),
                                    'footer' =>
                                    Html::button('Volver', ['class' => 'btn btn-info pull-left', 'data-dismiss' => "modal"])
                                ];
                            }
                        }
                    } else {
                        return [
                            'title' => "Listado de Agresores",
                            'content' => '<span class="text-danger">Error al guardar agresor</span>',
                            'footer' => Html::a(
                                ' Volver a la Grilla',
                                ['index_interv_agresor', 'idintervencion' => $idintervencion],
                                ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                            )
                        ];
                    }
                }
            } else {
                /*
                *   Process for non-ajax request
                */
                if (!$idintervencion) {
                    if ($model->load($request->post())) {

                        if (isset(Yii::$app->request->post()['consumos'])) {
                            $consumosCargados = Yii::$app->request->post()['consumos'];
                        } else {
                            $model->consumo_problematico = 0;
                        }

                        if ($model->save()) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_agresor', $model->idagresor, $model->getAttributes());
                            $this->actualizarConsumosAgresor($model->idagresor, $consumosCargados);
                            return $this->redirect(['index']);
                        }
                    } else {
                        return $this->render('update', [
                            'model' => $model,
                            'parentezco' => $parentezco, //Parentezco es ''
                            'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
                            'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
                            'vioConsumosPreCargados' => $vioConsumosPreCargados,
                            'escolaridad' => $escolaridad,
                        ]);
                    }
                } else {
                    return $this->redirect(['/sds_vio_intervencion/index']);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_vio_intervencion_agresor model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idintervencion
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = $this->findModel($id, $idintervencion);
            $model->activo = 0;
            $model->save();

            if ($request->isAjax) { //Entra si viene del boton "Agregar" desde "Asignar Agresores" en intervencion
                /*
                *   Process for ajax request
                */
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion_agresor', $model->idintervencionagresor, $model->getAttributes());
                // Carga index
                $model = Sds_vio_intervencion::findOne($idintervencion);
                $model->agresores = $this->searchAgresores($idintervencion);
                $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Listado de Agresores",
                    'content' => $this->renderAjax('index_interv_agresor', [
                        'model' => $model,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else { //Viene desde el ABM de agresor
                /*
                *   Process for non-ajax request
                */
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_agresor', $model->idagresor, $model->getAttributes());
                Yii::$app->session->setFlash('success', " Se eliminó correctamente el agresor.");
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id,  $idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = $this->findModel($id, $idintervencion);
            $model->activo = 1;
            $model->update();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_agresor',  $model->idagresor, $model->getAttributes());

            if ($request->isAjax) { //Entra si viene del boton "Agregar" desde "Asignar Agresores" en intervencion
                /*
                *   Process for ajax request
                */
                // Carga index
                $model_intervencion = Sds_vio_intervencion::findOne($idintervencion);
                $model_intervencion->agresores = $this->searchAgresores($idintervencion);
                $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Listado de Agresores",
                    'content' => $this->renderAjax('index_interv_agresor', [
                        'model' => $model_intervencion,
                        'hasRolAdminGeneral' => $hasRolAdminGeneral
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else { //Viene desde el ABM de agresor
                /*
                *   Process for non-ajax request
                */

                if ($idintervencion) {
                    return $this->redirect(['/sds_vio_intervencion/index']);
                } else {
                    Yii::$app->session->setFlash('success', " Se reactivo correctamente el agresor.");
                    return $this->redirect(['index']);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionIntervenciones_asociadas()
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $listado = [];
            $idagresor = Yii::$app->request->queryParams['idagresor'];
            $listado = Sds_vio_intervencion_agresor::find()
                ->addSelect(["DATE_FORMAT(sds_vio_intervencion.fecha, '%d-%m-%Y') as fecha,sds_vio_intervencion.idintervencion,sds_vio_intervencion_agresor.idagresor,sds_vio_agresor.nombre,sds_vio_agresor.apellido"])
                ->where(['sds_vio_intervencion_agresor.idagresor' => $idagresor])
                ->innerJoin('sds_vio_intervencion', 'sds_vio_intervencion.idintervencion = sds_vio_intervencion_agresor.idintervencion')
                ->innerJoin('sds_vio_agresor', 'sds_vio_intervencion_agresor.idagresor = sds_vio_agresor.idagresor')
                ->asArray()
                ->all();
            $request = Yii::$app->request;

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if (count($listado) > 0) {
                    return [
                        'title' => "Listado de Intervenciones asociadas al agresor <br>" . $listado[0]['apellido'] . " " . $listado[0]['nombre'],
                        'content' => $this->renderAjax('intervencion_asociada', [
                            'model' => $listado
                        ]),
                        'footer' => Html::button('Volver', ['class' => 'btn btn-info pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => "Listado de Intervenciones asociadas",
                        'content' => $this->renderAjax('intervencion_asociada', [
                            'model' => $listado
                        ]),
                        'footer' => Html::button('Volver', ['class' => 'btn btn-info pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Sds_vio_agresor Sds_vio_intervencion_agresor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idintervencion
     * @param integer $idagresor
     * @return Sds_vio_intervencion_agresor the loaded model
     * @return Sds_vio_agresor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $idintervencion = null)
    {
        $model =  $idintervencion ?
            Sds_vio_intervencion_agresor::findOne(['idintervencion' => $idintervencion, 'idagresor' => $id])
            :
            Sds_vio_agresor::findOne($id);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getFilterGeneros()
    {
        //Busqueda localidades
        $generosFiltro = Sds_com_configuracion::findBySql(
            "SELECT idagresor, 
                configuracion.idconfiguracion as genero, 
                configuracion.descripcion  as genero_descripcion 
                FROM sds_vio_agresor agresor
                INNER JOIN sds_com_configuracion configuracion 
                ON agresor.genero = configuracion.idconfiguracion 
                WHERE agresor.activo = 1 AND agresor.genero 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY genero_descripcion ASC
                "
        )->asArray()->all();

        $generosFiltro = ArrayHelper::map($generosFiltro, 'genero', 'genero_descripcion');
        return $generosFiltro;
    }

    protected function getListEscolaridad()
    {
        //Busqueda escolaridad
        $escolaridad = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $escolaridad = ArrayHelper::map($escolaridad, 'idconfiguracion', 'descripcion');
        return $escolaridad;
    }

    protected function getListVinculo()
    {
        $vinculo = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_AGRESOR_VINCULO_SEGURIDAD, true);
        $listvinculo = ArrayHelper::map($vinculo, 'idconfiguracion', 'descripcion');
        return $listvinculo;
    }

    protected function getListConsumoProblematico()
    {
        $consumo = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::VIO_AGRESOR_CONSUMO_PROB, true);
        $listconsumo = ArrayHelper::map($consumo, 'idconfiguracion', 'descripcion');
        return $listconsumo;
    }

    private function verificarAgresor($model)
    {
        $modelAgresor = new Sds_vio_agresor();

        if ($model->idagresor) { //precargo un agresor desde el select de agresores (ya existia el agresor) entonces lo actualiza

            $model_vio_agresor =  $this->findModel($model->idagresor);
            $model_vio_agresor->idagresor = $model->idagresor;
            $model_vio_agresor->nombre = $model->nombre;
            $model_vio_agresor->apellido = $model->apellido;
            $model_vio_agresor->genero = $model->genero;
            $model_vio_agresor->agresor_dav = $model->agresor_dav;
            $model_vio_agresor->agresor_dav_datos = $model->agresor_dav_datos;
            $model_vio_agresor->agresor_dato_denuncia = $model->agresor_dato_denuncia;
            $model_vio_agresor->activo = $model->activo;

            $model_vio_agresor->escolaridad = $model->escolaridad;
            $model_vio_agresor->funcionario = $model->funcionario;
            $model_vio_agresor->desc_actividad = $model->desc_actividad;
            $model_vio_agresor->desc_jubilacion = $model->desc_jubilacion;
            $model_vio_agresor->acceso_armas = $model->acceso_armas;
            $model_vio_agresor->antecedente_penales = $model->antecedente_penales;
            $model_vio_agresor->antecedente_violencia = $model->antecedente_violencia;
            $model_vio_agresor->antecedente_restricciones = $model->antecedente_restricciones;
            $model_vio_agresor->vinculo_ilicito = $model->vinculo_ilicito;
            $model_vio_agresor->vinculo_personal_seguridad = $model->vinculo_personal_seguridad;
            $model_vio_agresor->consumo_problematico = $model->consumo_problematico;

            $modelAgresor = $model_vio_agresor;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_agresor', $modelAgresor->idagresor, $modelAgresor->getAttributes());
        } else { //No precargo un agresor desde el select de agresores pero hay que verificar que el DNI no este repetido

            $getAgresorByDni = $model->getAgresorByDni($model->dni);

            if ($getAgresorByDni && $getAgresorByDni->dni) { // El DNI esta repetido, debo actualizar el agresor

                $getAgresorByDni->nombre = $model->nombre;
                $getAgresorByDni->apellido = $model->apellido;
                $getAgresorByDni->genero = $model->genero;
                $getAgresorByDni->agresor_dav = $model->agresor_dav;
                $getAgresorByDni->agresor_dav_datos = $model->agresor_dav_datos;
                $getAgresorByDni->agresor_dato_denuncia = $model->agresor_dato_denuncia;
                $getAgresorByDni->activo = $model->activo;

                $getAgresorByDni->escolaridad = $model->escolaridad;
                $getAgresorByDni->funcionario = $model->funcionario;
                $getAgresorByDni->desc_actividad = $model->desc_actividad;
                $getAgresorByDni->desc_jubilacion = $model->desc_jubilacion;
                $getAgresorByDni->acceso_armas = $model->acceso_armas;
                $getAgresorByDni->antecedente_penales = $model->antecedente_penales;
                $getAgresorByDni->antecedente_violencia = $model->antecedente_violencia;
                $getAgresorByDni->antecedente_restricciones = $model->antecedente_restricciones;
                $getAgresorByDni->vinculo_ilicito = $model->vinculo_ilicito;
                $getAgresorByDni->vinculo_personal_seguridad = $model->vinculo_personal_seguridad;
                $getAgresorByDni->consumo_problematico = $model->consumo_problematico;

                $modelAgresor = $getAgresorByDni;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_vio_agresor', $modelAgresor->idagresor, $modelAgresor->getAttributes());
            } else { // El DNI no estaba repetido, creo el agresor
                $modelAgresor = $model;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_agresor', $modelAgresor->idagresor, $modelAgresor->getAttributes());
            }
        }

        // $modelAgresor->save();
        return $modelAgresor;
    }

    public function searchAgresores($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

        $where = "idintervencion ='$id'";
        if (!$hasRolAdminGeneral) {
            $where .= " AND activo = 1";
        }
        $intervenciones = Sds_vio_intervencion_agresor::find()->where($where)->all();

        $agresores = [];
        foreach ($intervenciones as $intervencion) {
            $agresor = Sds_vio_agresor::findOne(['idagresor' => $intervencion->idagresor]);
            $parentezco =  Sds_com_configuracion::findOne(['idconfiguracion' => $intervencion->parentezco]);
            if ($agresor) {
                array_push($agresores, [
                    'dni' => $agresor->dni,
                    'nombre' => $agresor->nombre,
                    'apellido' => $agresor->apellido,
                    'genero' => $agresor->genero,
                    'parentezco' => $parentezco ? $parentezco->descripcion : "",
                    'agresor_dato_denuncia' => $agresor->agresor_dato_denuncia,
                    'agresor_dav' => $agresor->agresor_dav,
                    'agresor_dav_datos' => $agresor->agresor_dav_datos,
                    'activo' => $intervencion->activo,
                    'idagresor' => $agresor->idagresor,
                    'idintervencion' => $intervencion->idintervencion,
                ]);
            }
        }
        return new ArrayDataProvider([
            'allModels' => $agresores,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['nombre', 'dni'],
            ],
        ]);
    }

    private function actualizarConsumosAgresor($idagresor, $array_nuevos_consumos)
    {
        $array_consumos = Sds_vio_agresor_consumo::find()->where(["idagresor" => $idagresor, "deleted_at" => null])->all();

        foreach ($array_consumos as $elemento) {
            $consumo = $elemento->idconsumo;
            $key = array_search($consumo, $array_nuevos_consumos);

            if (in_array($consumo, $array_nuevos_consumos)) { //El consumo ya esta creado, se debe quitar del array_nuevos_consumos
                unset($array_nuevos_consumos[$key]);
            } else { //El consumo ya no esta relacionado con el agresor, se debe eliminar
                $consumo = Sds_vio_agresor_consumo::find()->where(['idagresorconsumo' => $elemento->idagresorconsumo])->one();
                $consumo->deleted_at = date('Y-m-d H:i:s', time());
                $consumo->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_agresor_consumo', $consumo->idagresorconsumo, $consumo);
            }
        }

        foreach ($array_nuevos_consumos as $idconsumoNuevos) { //Se crearán los nuevos consumos al agresor
            $vioConsumoAgresor = new Sds_vio_agresor_consumo();
            $vioConsumoAgresor->idagresor = $idagresor;
            $vioConsumoAgresor->idconsumo = $idconsumoNuevos;
            $vioConsumoAgresor->created_at = date('Y-m-d_H_i_s', time());
            $vioConsumoAgresor->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_vio_agresor_consumo', $vioConsumoAgresor->idagresor, $vioConsumoAgresor);
        }
    }
}
