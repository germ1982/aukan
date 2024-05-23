<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_programa;
use app\models\Mds_certificacion_programa_monto;
use app\models\Mds_certificacion_programa_montoSearch;
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
use yii\db\conditions\BetweenColumnsCondition;


/**
 * Mds_certificacion_programa_montoController implements the CRUD actions for Mds_certificacion_programa_montoController model.
 */
class Mds_certificacion_programa_montoController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'view', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL));
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (false);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_certificacion_programa_monto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_certificacion_programa_montoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'direccionesFiltro' => $this->getFilterDirecciones(),
            'programasFiltro' => $this->getFilterProgramas()
        ]);
    }

    /**
     * Displays a single Mds_certificacion_programa_monto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_direccion', $id, array());
        $model = $this->findModel($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Ver Registro",
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $model
            ]);
        }
    }

    /**
     * Creates a new Mds_certificacion_programa_monto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mds_certificacion_programa_monto();
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo registro",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'listDirecciones' => $this->getListDirecciones()
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            } else if ($model->load($request->post())) {
                $iddireccion = $request->post()['iddireccion'];
                $idprograma = empty($request->post()['idprograma']) ? null : $request->post()['idprograma'];

                if (!empty($iddireccion) && $idprograma) {
                    $model->created_at = date('Y-m-d H:i:s');
                    $model->idusuario_carga = Yii::$app->user->id;

                    $fecha_inicio = armarDateParaMySql($model->fecha_inicio);
                    $fecha_inicio = date_create($fecha_inicio);
                    $fecha_inicio = date_format($fecha_inicio, 'Y-m-d H:i:s');
                    $model->fecha_inicio = $fecha_inicio;

                    if ($model->fecha_fin != null) {
                        $fecha_fin = armarDateParaMySql($model->fecha_fin);
                        $fecha_fin = date_create($fecha_fin);
                        $fecha_fin = date_format($fecha_fin, 'Y-m-d H:i:s');
                        $model->fecha_fin = $fecha_fin;
                    }

                    $programa = Mds_certificacion_programa::find()
                        ->where(['iddireccion' => $iddireccion, 'idprograma' => $idprograma, "deleted_at" => null])
                        ->one();

                    if ($model->fecha_fin == null) {
                        $programa_monto = Mds_certificacion_programa_monto::find()
                            ->where(['idcertificacionprograma' => $programa->idcertificacionprograma, 'deleted_at' => null, 'fecha_fin' => null])
                            ->one();
                    } else {
                        $programa_monto = Mds_certificacion_programa_monto::find()
                            ->where(['idcertificacionprograma' => $programa->idcertificacionprograma, 'deleted_at' => null])
                            ->andWhere(
                                [
                                    'or',
                                    new BetweenColumnsCondition($model->fecha_inicio, 'BETWEEN', 'fecha_inicio', 'fecha_fin'),
                                    new BetweenColumnsCondition($model->fecha_fin, 'BETWEEN', 'fecha_inicio', 'fecha_fin'),
                                    [
                                        'and',
                                        ['>=', 'fecha_inicio', $model->fecha_inicio],
                                        ['<=', 'fecha_fin', $model->fecha_fin]
                                    ]
                                ]
                            )
                            ->one();
                    }

                    if (!$programa_monto) {

                        $model->idcertificacionprograma = $programa->idcertificacionprograma;

                        $transaction = Yii::$app->db->beginTransaction();
                        $guardado = $model->save();
                        if ($guardado) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_programa_monto', $model->idcertificacionprogramamonto, $model->getAttributes());
                            return [
                                'title' => "Nuevo registro creado",
                                'content' => '<span class="text-success">Creado Exitosamente! </span>',
                                'footer' =>
                                Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        } else {
                            $transaction->rollBack();
                            return [
                                'title' => "Nuevo registro",
                                'content' => '<span class="text-danger"> Error al guardar</span>',
                                'footer' =>
                                Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        }
                    } else {
                        return [
                            'title' => '<span class="text-danger">El Programa ya posee un monto asignado</span>',
                            'content' =>
                            '<span>Dirección: ' . $programa_monto->certificacionPrograma->direccion0->descripcion . ' </span><br>' .
                                '<span>Programa: ' . $programa_monto->certificacionPrograma->programa0->descripcion . ' </span><br>' .
                                '<span>Monto: $' . $programa_monto->monto . ' </span><br>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                }

                return [
                    'title' => "Nuevo registro",
                    'content' => '<span class="text-danger"> Error al guardar</span>',
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones()
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_certificacion_direccion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id = null)
    {
        $request = Yii::$app->request;
        $usuario = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $action = 'update';

        $programa = Mds_certificacion_programa::find()
            ->where(['idcertificacionprograma' => $model->idcertificacionprograma, "deleted_at" => null])
            ->one();

        $model->iddireccion = $programa->iddireccion;
        $model->idprograma = $programa->idprograma;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar registro #" . $id,
                    'content' => $this->renderAjax('update', [
                        'action' => $action,
                        'model' => $model,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listProgramas' => $this->getListProgramas()
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $programa_monto = NULL;

                if ($model->fecha_inicio) {
                    $fecha_inicio = armarDateParaMySql($model->fecha_inicio);
                    $fecha_inicio = date_create($fecha_inicio);
                    $fecha_inicio = date_format($fecha_inicio, 'Y-m-d H:i:s');
                    $model->fecha_inicio = $fecha_inicio;
                }
                if ($model->fecha_fin) {
                    $fecha_fin = armarDateParaMySql($model->fecha_fin);
                    $fecha_fin = date_create($fecha_fin);
                    $fecha_fin = date_format($fecha_fin, 'Y-m-d H:i:s');
                    $model->fecha_fin = $fecha_fin;
                }

                if ($model->fecha_fin) {
                    $where =
                        [
                            'or',
                            new BetweenColumnsCondition($model->fecha_inicio, 'BETWEEN', 'fecha_inicio', 'fecha_fin'), //Contenida
                            new BetweenColumnsCondition($model->fecha_fin, 'BETWEEN', 'fecha_inicio', 'fecha_fin'), //Contenida
                            [ //Contenga
                                'and',
                                ['>=', 'fecha_inicio', $model->fecha_inicio],
                                ['<=', 'fecha_fin', $model->fecha_fin]
                            ],
                            [
                                'and',
                                ['fecha_fin' => null],
                                ['between', 'fecha_inicio', $model->fecha_inicio, $model->fecha_fin]
                            ]
                        ];
                } else {
                    $where =
                        [
                            'or',
                            new BetweenColumnsCondition($model->fecha_inicio, 'BETWEEN', 'fecha_inicio', 'fecha_fin'),
                            //Esta contenida
                            ['>=', 'fecha_inicio', $model->fecha_inicio],
                            [
                                'and',
                                ['fecha_fin' => null],
                                ['<=', 'fecha_inicio', $model->fecha_inicio]
                            ]
                        ];
                }


                $programa_monto = Mds_certificacion_programa_monto::find()
                    ->where(['idcertificacionprograma' => $programa->idcertificacionprograma, "deleted_at" => null])
                    ->andWhere(['<>', 'idcertificacionprogramamonto', $id])
                    ->andWhere($where)
                    ->one();

                if (!$programa_monto) {
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->idusuario_carga = Yii::$app->user->id;

                    $transaction = Yii::$app->db->beginTransaction();
                    $guardado = $model->save();
                    if ($guardado) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_programa_monto', $model->idcertificacionprogramamonto, $model->getAttributes());
                        return [
                            'title' => "Actualización",
                            'content' => '<span class="text-success">Se actualizó correctamente el registro</span>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    } else {
                        $transaction->rollBack();
                        return [
                            'title' => "Actualización",
                            'content' => '<span class="text-danger"> Error al actualizar</span>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                } else {
                    return [
                        'title' => '<span class="text-danger">El Programa ya posee un monto asignado</span>',
                        'content' =>
                        '<span>Dirección: ' . $programa_monto->certificacionPrograma->direccion0->descripcion . ' </span><br>' .
                            '<span>Programa: ' . $programa_monto->certificacionPrograma->programa0->descripcion . ' </span><br>' .
                            '<span>Monto: $' . $programa_monto->monto . ' </span><br>',
                        'footer' =>
                        Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
        } else {
            if ($model->load($request->post())) {
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'action' => $action,
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listProgramas' => $this->getListProgramas()
                ]);
            }
        }
    }

    /**
     * Deletes an existing Mds_certificacion_programa_monto model.
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
                Yii::$app->session->setFlash('success', " Se borro correctamente el registro.");
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_programa_monto', $model->idcertificacionprogramamonto, $model->getAttributes());
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', "Error al borrar el registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
        }
    }

    /**
     * Finds the Mds_certificacion_programa_monto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_certificacion_programa_monto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_certificacion_programa_monto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListDirecciones()
    {
        //Busqueda de direcciones cargadas en sds configuracion
        $iddireccion = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_DIRECCION, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $iddireccion = ArrayHelper::map($iddireccion, 'idconfiguracion', 'descripcion');
        return $iddireccion;
    }

    protected function getListProgramas()
    {
        //Busqueda programas
        $programas = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_PROGRAMA, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $programas = ArrayHelper::map($programas, 'idconfiguracion', 'descripcion');
        return $programas;
    }

    protected function getFilterProgramas()
    {
        $programasFiltro = Mds_certificacion_programa_monto::findBySql(
            "SELECT
            configuracion.idconfiguracion as idprograma,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_programa_monto programa_monto
            INNER JOIN  mds_certificacion_programa programa
            ON programa_monto.idcertificacionprograma = programa.idcertificacionprograma
            INNER JOIN sds_com_configuracion configuracion
            ON programa.idprograma = configuracion.idconfiguracion
            WHERE programa_monto.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();

        $programasFiltro = ArrayHelper::map($programasFiltro, 'idprograma', 'descripcion');
        return $programasFiltro;
    }

    protected function getFilterDirecciones()
    {
        $direccionesFiltro = Mds_certificacion_programa_monto::findBySql(
            "SELECT
            configuracion.idconfiguracion as iddireccion,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_programa_monto programa_monto
            INNER JOIN  mds_certificacion_programa programa
            ON programa_monto.idcertificacionprograma = programa.idcertificacionprograma
            INNER JOIN sds_com_configuracion configuracion
            ON programa.iddireccion = configuracion.idconfiguracion
            WHERE programa_monto.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();

        $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'iddireccion', 'descripcion');
        return $direccionesFiltro;
    }
}

function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
