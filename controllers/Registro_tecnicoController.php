<?php

namespace app\controllers;

use app\models\Configuracion;
use app\models\ConfiguracionSearch;
use app\models\ConfiguracionTipo;
use app\models\LogPlataforma;
use Yii;
use app\models\RegistroTecnico;
use app\models\RegistroTecnicoAsistencia;
use app\models\RegistroTecnicoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Registro_tecnicoController implements the CRUD actions for RegistroTecnico model.
 */
class Registro_tecnicoController extends Controller
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
     * Lists all RegistroTecnico models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistroTecnicoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex_asistentes()
    {
        $searchModel = new ConfiguracionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_ASISTENCIA_INFORMATICA]);

        return $this->render('index_asistentes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex_tipos_registro()
    {
        $searchModel = new ConfiguracionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_REGISTRO_TECNICO]);

        return $this->render('index_tipos_registro', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RegistroTecnico model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "RegistroTecnico #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new RegistroTecnico model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new RegistroTecnico();
        $asistentes = Yii::$app->request->post('asistentes', []);
        $model->idtipo_registro = Yii::$app->request->post('idtipo_registro');

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Registro Tecnico",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $model->idtipo_registro = Yii::$app->request->post('idtipo_registro');

                $model->fecha_solicitud = $model->fecha_solicitud ? ArmarDateParaMySql($model->fecha_solicitud) : null;
                $model->hora_solicitud = $model->hora_solicitud ? date('H:i:s', strtotime($model->hora_solicitud)) : null;

                if ($model->solucion) {
                    $model->fecha_solucion = $model->fecha_solucion ? ArmarDateParaMySql($model->fecha_solucion) : null;
                    $model->hora_solucion = $model->hora_solucion ? date('H:i:s', strtotime($model->hora_solucion)) : null;
                    $model->estado = RegistroTecnico::ESTADO_FINALIZADO;
                }
                else {
                    if (!empty($asistentes)) {
                        $model->estado = RegistroTecnico::ESTADO_ASISTENCIA;
                    } else {
                        $model->estado = RegistroTecnico::ESTADO_PENDIENTE;
                    }

                }



                if ($model->save()) {

                    foreach ($asistentes as $idempleado) {
                        $registroAsistencia = new RegistroTecnicoAsistencia();
                        $registroAsistencia->idregistro = $model->idregistro;
                        $registroAsistencia->idtecnico = $idempleado;
                        $registroAsistencia->save();
                    }
                    return [
                        //'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nuevo Registro Tecnico",
                        'content' => '<span class="text-success">Create RegistroTecnico success</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
            } else {
                return [
                    'title' => "Nuevo Registro Tecnico",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idregistro]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing RegistroTecnico model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $asistentes = Yii::$app->request->post('asistentes', []);


        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Registro " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $model->idtipo_registro = Yii::$app->request->post('idtipo_registro');

                $model->fecha_solicitud = $model->fecha_solicitud ? ArmarDateParaMySql($model->fecha_solicitud) : null;
                $model->hora_solicitud = $model->hora_solicitud ? date('H:i:s', strtotime($model->hora_solicitud)) : null;

                if ($model->solucion) {
                    $model->fecha_solucion = $model->fecha_solucion ? ArmarDateParaMySql($model->fecha_solucion) : null;
                    $model->hora_solucion = $model->hora_solucion ? date('H:i:s', strtotime($model->hora_solucion)) : null;
                    $model->estado = RegistroTecnico::ESTADO_FINALIZADO;
                }
                else {

                    $model->fecha_solucion = null;
                    $model->hora_solucion = null;   
                    if (!empty($asistentes)) {
                        $model->estado = RegistroTecnico::ESTADO_ASISTENCIA;
                    } else {
                        $model->estado = RegistroTecnico::ESTADO_PENDIENTE;
                    }

                }


                if ($model->save()) {
                    RegistroTecnicoAsistencia::deleteAll(['idregistro' => $model->idregistro]);
                    foreach ($asistentes as $idempleado) {
                        $registroAsistencia = new RegistroTecnicoAsistencia();
                        $registroAsistencia->idregistro = $model->idregistro;
                        $registroAsistencia->idtecnico = $idempleado;
                        $registroAsistencia->save();
                    }

                    return [
                        //'forceReload' => '#crud-datatable-pjax',
                        'title' => "RegistroTecnico #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            } else {
                return [
                    'title' => "Editar Registro" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idregistro]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing RegistroTecnico model.
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
     * Delete multiple existing RegistroTecnico model.
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
     * Finds the RegistroTecnico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistroTecnico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistroTecnico::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate_asistentes()
    {
        $request = Yii::$app->request;
        $model = new Configuracion();
        $model->id_configuracion_tipo = Yii::$app->request->get('id_configuracion_tipo');
        $model_tipo = ConfiguracionTipo::findOne($model->id_configuracion_tipo);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => 'Nuevo ' . $model_tipo->descripcion,
                    'content' => $this->renderAjax('create_asistentes', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => 'submit']),
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                if ($model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(12, 1, $model->id_configuracion);
                    return [
                        'title' => 'Nuevo ' . $model_tipo->descripcion,
                        'content' => '<span class="text-success">Asistente Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                            Html::a('Crear Otro', ['create_asistentes', 'id_configuracion_tipo' => $model->id_configuracion_tipo], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                } else {
                    $transaction->rollBack();
                }
            }

            return [
                'title' => 'Nuevo ' . $model_tipo->descripcion . ' - Faltan datos!!!',
                'content' => $this->renderAjax('create_asistentes', ['model' => $model]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => 'submit'])
            ];
        }
    }


    public function actionUpdate_asistentes($id)
    {

        $request = Yii::$app->request;

        $model = Configuracion::findOne($id);



        $model_tipo = ConfiguracionTipo::findOne($model->id_configuracion_tipo);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => 'Actualizar ',
                    'content' => $this->renderAjax('update_asistentes', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => 'submit']),
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                if ($model->save()) {
                    $transaction->commit();
                    //LogPlataforma::registrar(12, 1, $model->id_configuracion);
                    return [
                        'title' => 'Actualizar ' . $model_tipo->descripcion,
                        'content' => '<span class="text-success">Asistente Actualizado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                            Html::a('Crear Otro', ['create_asistentes', 'id_configuracion_tipo' => $model->id_configuracion_tipo], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                } else {
                    $transaction->rollBack();
                }
            }

            return [
                'title' => 'Actualizar ' . $model_tipo->descripcion . ' - Faltan datos!!!',
                'content' => $this->renderAjax('update_asistentes', ['model' => $model]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => 'submit'])
            ];
        }
    }

    public function actionActivar_configuracion($id)
    {
        $model = Configuracion::findOne($id);
        $model->activo = 1;
        $model->save();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Activar',
            'content' => "Se ah Activado el Item", // . json_encode($model->getErrors()),
            'footer' =>
            Html::button('Cerrar', [
                'id' => 'btnCerrar',
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal',
            ])
        ];
    }

    public function actionDesactivar_configuracion($id)
    {
        $model = Configuracion::findOne($id);
        $model->activo = 0;
        $model->save();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Desactivar',
            'content' => "Se ah Desactivado el Item", // . json_encode($model->getErrors()),
            'footer' =>
            Html::button('Cerrar', [
                'id' => 'btnCerrar',
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal',
            ])
        ];
    }
}
function ArmarDateParaMySql($Fecha)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";

    $DT = date_create($DT);
    $DT = date_format($DT, 'Y-m-d');
    return $DT;
}




