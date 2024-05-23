<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion_director;
use app\models\Mds_certificacion_directorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\filters\AccessControl;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_direccion;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_sys_log;
use app\models\Mds_seg_usuario;

/**
 * Mds_certificacion_directorController implements the CRUD actions for Mds_certificacion_director model.
 */
class Mds_certificacion_directorController extends Controller
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
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_certificacion_director models.
     * @return mixed
     */
    public function actionIndex($idcertificaciondireccion = null)
    {
        $request = Yii::$app->request;
        $searchModel = new Mds_certificacion_directorSearch();
        $searchModel->idcertificaciondireccion = $idcertificaciondireccion;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        $model_direccion = Mds_certificacion_direccion::find()
            ->where(['idcertificaciondireccion' => $idcertificaciondireccion])
            ->one();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Listado de usuarios dirección #' . $idcertificaciondireccion . ' - ' .
                ($idcertificaciondireccion ? $model_direccion->direccion0->descripcion : ''),
            'content' => $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'filterFunciones' => $this->getListFunciones()
            ]),
            'footer' => Html::button('Cerrar', [
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal',
            ]),
        ];
    }

    /**
     * Displays a single Mds_certificacion_director model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($idcertificaciondireccion = null)
    {
        $request = Yii::$app->request;
        $model_direccion = Mds_certificacion_direccion::find()->where(['idcertificaciondireccion' => $idcertificaciondireccion])->one();

        $model = new Mds_certificacion_director();
        $model->idcertificaciondireccion = $idcertificaciondireccion;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Asignar usuario dirección #' . $idcertificaciondireccion . ' '  . $model_direccion->direccion0->descripcion,
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'listUsuarios' => $this->getListUsuarios(),
                        'listFunciones' => $this->getListFunciones()
                    ]),
                    'footer' =>
                    Html::a(
                        ' Volver',
                        ['index', 'idcertificaciondireccion' => $idcertificaciondireccion],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info pull-left',
                        ]
                    ) .
                        Html::button('Guardar', [
                            'class' => 'btn btn-success',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($request->isPost) {
                $model->load($request->post());

                $model_certificacion_director = new Mds_certificacion_director();
                $model_certificacion_director->idusuario = $model['idusuario'];
                $model_certificacion_director->idcertificaciondireccion = $model['idcertificaciondireccion'];
                $model_certificacion_director->observaciones = $model['observaciones'];
                $model_certificacion_director->idfuncion = $model['idfuncion'];
                $fecha_desde = armarDateParaMySql($model['fecha_desde']);
                $fecha_desde = date_create($fecha_desde);
                $fecha_desde = date_format($fecha_desde, 'Y-m-d H:i:s');
                $model_certificacion_director->fecha_desde = $fecha_desde;

                if ($model['fecha_hasta']) {
                    $fecha_hasta = armarDateParaMySql($model['fecha_hasta']);
                    $fecha_hasta = date_create($fecha_hasta);
                    $fecha_hasta = date_format($fecha_hasta, 'Y-m-d H:i:s');
                    $model_certificacion_director->fecha_hasta = $fecha_hasta;
                }

                $model_certificacion_director->idusuario_carga = Yii::$app->user->id;
                $model_certificacion_director->created_at = date('Y-m-d H:i:s');

                if ($model_certificacion_director->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_director', $model_certificacion_director->idcertificaciondirector, $model_certificacion_director->getAttributes());
                    return [
                        'title' => 'Asignar Usuario',
                        'content' => '<span class="text-success">Usuario asignado exitosamente!</span>',
                        'footer' =>
                        Html::a(
                            ' Volver al listado',
                            ['index', 'idcertificaciondireccion' => $idcertificaciondireccion],
                            [
                                'role' => 'modal-remote',
                                'class' => 'btn btn-info pull-left',
                            ]
                        ) .
                            Html::a(
                                'Agregar Otro',
                                ['create', 'idcertificaciondireccion' => $idcertificaciondireccion],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            ),
                    ];
                } else {
                    // Mostramos mensaje de error
                    //TODO: Deberia almacenar log en algun lado?
                    return [
                        'title' => 'Asignar Usuario',
                        'content' =>
                        '<span class="text-error">Error al asignar usuario!</span>',
                        'footer' =>
                        Html::a(
                            ' Volver al listado',
                            ['index', 'idcertificaciondireccion' => $idcertificaciondireccion],
                            [
                                'role' => 'modal-remote',
                                'class' => 'btn btn-info pull-left',
                            ]
                        ) .
                            Html::a(
                                'Agregar Otro',
                                ['create', 'idcertificaciondireccion' => $idcertificaciondireccion],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            ),
                    ];
                }
            }
            return [
                'title' => 'Asignar Usuario',
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' =>
                Html::a(
                    ' Volver',
                    ['index', 'idcertificaciondireccion' => $idcertificaciondireccion],
                    [
                        'role' => 'modal-remote',
                        'class' => 'btn btn-info pull-left',
                    ]
                ) .
                    Html::button('Guardar', [
                        'class' => 'btn btn-primary',
                        'type' => 'submit',
                    ]),
            ];
        } else {
            /*
         *   Process for non-ajax request
         */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_director', $model->idcertificaciondirector, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idpersona]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_certificacion_director model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->idcertificaciondireccion = $model['idcertificaciondireccion'];
            $model->observaciones = $model['observaciones'];
            $model->idfuncion = $model['idfuncion'];
            $fecha_desde = armarDateParaMySql($model['fecha_desde']);
            $fecha_desde = date_create($fecha_desde);
            $fecha_desde = date_format($fecha_desde, 'Y-m-d H:i:s');
            $model->fecha_desde = $fecha_desde;

            if ($model['fecha_hasta']) {
                $fecha_hasta = armarDateParaMySql($model['fecha_hasta']);
                $fecha_hasta = date_create($fecha_hasta);
                $fecha_hasta = date_format($fecha_hasta, 'Y-m-d H:i:s');
                $model->fecha_hasta = $fecha_hasta;
            }

            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_director', $model->idcertificaciondirector, $model->getAttributes());
                return [
                    'title' => 'Editar Usuario #' . $model->idcertificaciondirector,
                    'content' => '<span class="text-success">Usuario EDITADO exitosamente!</span>',
                    'footer' =>
                    Html::a(
                        ' Volver al listado',
                        ['index', 'idcertificaciondireccion' => $model->idcertificaciondireccion],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info pull-left',
                        ]
                    )
                ];
            } else {
                // Mostramos mensaje de error
                //TODO: Deberia almacenar log en algun lado?
                return [
                    'title' => 'Editar Usuario #' . $model->idcertificaciondirector,
                    'content' =>
                    '<span class="text-error">Error al editar usuario!</span>',
                    'footer' =>
                    Html::a(
                        ' Volver al listado',
                        ['index', 'idcertificaciondireccion' => $model->idcertificaciondireccion],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info',
                        ]
                    )
                ];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Editar Usuario de dirección',
            'content' => $this->renderAjax('update', [
                'model' => $model,
                'listUsuarios' => $this->getListUsuarios(),
                'listFunciones' => $this->getListFunciones()
            ]),
            'footer' =>
            Html::a(
                ' Volver',
                ['index', 'idcertificaciondireccion' => $model->idcertificaciondireccion],
                [
                    'role' => 'modal-remote',
                    'class' => 'btn btn-info pull-left',
                ]
            ) .
                Html::button('Guardar', [
                    'class' => 'btn btn-primary',
                    'type' => 'submit',
                ]),
        ];
    }

    /**
     * Deletes an existing Mds_certificacion_director model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idcertificaciondirector)
    {
        $model = $this->findModel($idcertificaciondirector);
        $idcertificaciondireccion = $model->idcertificaciondireccion;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = Yii::$app->user->id;
        if ($model->validate()) {
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_director', $model->idcertificaciondirector, $model->getAttributes());
                return [
                    'title' => 'Asignar Usuario',
                    'content' => '<span class="text-success">Usuario eliminado!</span>',
                    'footer' =>
                    Html::a(
                        ' Volver al listado de usuarios',
                        ['index', 'idcertificaciondireccion' => $idcertificaciondireccion],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info pull-left',
                        ]
                    ) .
                        Html::a(
                            'Agregar Otro',
                            ['create', 'idcertificaciondireccion' => $idcertificaciondireccion],
                            [
                                'class' => 'btn btn-primary',
                                'role' => 'modal-remote',
                            ]
                        ),
                ];
            } else {
                Yii::$app->session->setFlash('error', "Error al borrar el registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
        }
    }

    public function actionReactivate($idcertificaciondirector)
    {
        $model = $this->findModel($idcertificaciondirector);
        $idcertificaciondireccion = $model->idcertificaciondireccion;
        $model->deleted_at = null;
        $model->idusuario_borra = null;
        if ($model->validate()) {
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_director', $model->idcertificaciondirector, $model->getAttributes());
                return [
                    'title' => 'Asignar Usuario',
                    'content' => '<span class="text-success">Usuario reactivado!</span>',
                    'footer' =>
                    Html::a(
                        ' Volver al listado de usuarios',
                        ['index', 'idcertificaciondireccion' => $idcertificaciondireccion],
                        [
                            'role' => 'modal-remote',
                            'class' => 'btn btn-info',
                        ]
                    )
                ];
            } else {
                Yii::$app->session->setFlash('error', "Error al borrar el registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
        }
    }

    /**
     * Finds the Mds_certificacion_director model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_certificacion_director the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_certificacion_director::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListFunciones()
    {
        //Busqueda de tipo de funciones cargadas en sds configuracion
        $funciones = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_FUNCION_USUARIO, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $listfunciones = ArrayHelper::map($funciones, 'idconfiguracion', 'descripcion');
        return $listfunciones;
    }
    protected function getListUsuarios()
    {
        //Busqueda de usuarios cargadas en sds usuario
        $usuarios = Mds_seg_usuario::findBySql(
            'SELECT u.idusuario,CONCAT(UCASE(u.apellido)," ",UCASE(u.nombre)," ",u.dni) nombre
            FROM mds_seg_usuario u
            WHERE idcontacto IS NOT NULL
            AND activo = 1
            ORDER BY apellido ASC
            '
        )->all();
        $listado = ArrayHelper::map($usuarios, 'idusuario', 'nombre');
        return $listado;
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
