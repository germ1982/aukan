<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_direccion;
use app\models\Mds_certificacion_direccion_usuario;
use app\models\Mds_certificacion_direccion_usuarioSearch;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use \yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use \yii\filters\AccessControl;

/**
 * Mds_certificacion_direccion_usuarioController implements the CRUD actions for Mds_certificacion_direccion_usuario model.
 */
class Mds_certificacion_direccion_usuarioController extends Controller
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
                        }
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Mds_certificacion_direccion_usuario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        $searchModel = new Mds_certificacion_direccion_usuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterDirecciones' => $this->getFilterDirecciones(),
            'filterUsuarios' => $this->getFilterUsuarios(),
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
        ]);
    }

    /**
     * Displays a single Mds_certificacion_direccion_usuario model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_direccion_usuario', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Registro #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id)
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id)
            ]);
        }
    }

    /**
     * Creates a new Mds_certificacion_direccion_usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mds_certificacion_direccion_usuario();
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo registro",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listUsuarios' => $this->getListUsuarios()
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            } else if ($model->load($request->post())) {
                $model_direccion_usuario = Mds_certificacion_direccion_usuario::find()
                    ->where([
                        'idcertificaciondireccion' => $model->idcertificaciondireccion,
                        'idusuario' => $model->idusuario, 'deleted_at' => null
                    ])
                    ->all();
                if (!$model_direccion_usuario) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $model->created_at = date('Y-m-d H:i:s');

                    if ($model->save()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_direccion_usuario', $model->iddireccionusuario, $model->getAttributes());
                        return [
                            //'forceReload' => '#crud-datatable-pjax',
                            'title' => "Nuevo registro creado",
                            'content' => '<span class="text-success">Creado Exitosamente! </span><br>' .
                                '<span>Dirección: ' . $model->idcertificaciondireccion0->direccion0->descripcion . ' </span><br>' .
                                '<span>Usuario: ' . $model->usuario->apellido . ' ' .  $model->usuario->nombre . ' </span>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    } else {
                        return [
                            //'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error al crear el registro",
                            'content' => '<span>Dirección: ' . $model->idcertificaciondireccion0->direccion0->descripcion . ' </span><br>',
                            'footer' =>
                            Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    }
                } else {
                    return [
                        'title' => "Error al crear el registro",
                        'content' => '<span>El usuario ya cuenta con la direccíon asignada</span><br>',
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
                    'listUsuarios' => $this->getListUsuarios()
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idusuario]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listUsuarios' => $this->getListUsuarios()
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_certificacion_direccion_usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id = null)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar registro #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listUsuarios' => $this->getListUsuarios()
                    ]),
                    'footer' =>
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $model->updated_at = date('Y-m-d H:i:s');

                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_direccion_usuario', $model->iddireccionusuario, $model->getAttributes());
                    return [
                        //'forceReload' => '#crud-datatable-pjax',
                        'title' => "Actualización",
                        'content' => '<span class="text-success">Se actualizó correctamente el registro</span><br>' .
                            '<span>Dirección: ' . $model->idcertificaciondireccion0->direccion0->descripcion . ' </span><br>' .
                            '<span>Usuario: ' . $model->usuario->apellido . ' ' .  $model->usuario->nombre . ' </span><br>
                                ',
                        'footer' =>
                        Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    Yii::$app->session->setFlash('error', "Error al actualizar el registro.");
                }
            }
        } else {
            if ($model->load($request->post())) {
                $model->updated_at = date('Y-m-d H:i:s');

                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_direccion_usuario', $id, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se generó correctamente el registro.");
                    return $this->redirect(['mds_certificacion_direccion_usuario/index']);
                } else {
                    Yii::$app->session->setFlash('success', "Error al generar el registro.");
                    return $this->redirect(['mds_certificacion_direccion_usuario/index']);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listUsuarios' => $this->getListUsuarios()
                ]);
            }
        }
    }

    /**
     * Deletes an existing Mds_certificacion_direccion_usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        if ($model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', " Se borro correctamente el registro.");
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_direccion_usuario', $model->iddireccionusuario, $model->getAttributes());
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', "Error al borrar el registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
        }
    }

    public function actionReactivate($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->deleted_at = null;
            if ($model->validate()) {
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente el registro.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_direccion_usuario', $model->iddireccionusuario, $model->getAttributes());
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el registro.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos del registro.");
            }
        } else {
            Yii::$app->session->setFlash('error', "El registro no existe.");
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mds_certificacion_direccion_usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_certificacion_direccion_usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_certificacion_direccion_usuario::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListDirecciones()
    {
        $direcciones = Mds_certificacion_direccion::find()
            ->select(['mds_certificacion_direccion.idcertificaciondireccion as idcertificaciondireccion', 'UPPER(sds_com_configuracion.descripcion) as descripcion'])
            ->innerJoin('mds_certificacion_programa', 'mds_certificacion_direccion.iddireccion=mds_certificacion_programa.iddireccion')
            ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
            ->groupBy('mds_certificacion_direccion.iddireccion')
            ->where(['mds_certificacion_direccion.deleted_at' => null, 'mds_certificacion_programa.deleted_at' => null])
            ->orderBy('sds_com_configuracion.descripcion')
            ->asArray()
            ->all();

        $direccionesFiltro = ArrayHelper::map($direcciones, 'idcertificaciondireccion', 'descripcion');
        return $direccionesFiltro;
    }

    protected function getListUsuarios()
    {
        //Busqueda de usuarios cargadas en sds usuario
        $usuarios = Mds_seg_usuario::findBySql(
            "SELECT u.idusuario, UCASE(CONCAT(u.apellido, ', ',u.nombre,' (',u.dni,')')) nombre
            FROM mds_seg_usuario u 
            WHERE idcontacto IS NOT NULL
            AND activo = 1 
            ORDER BY apellido ASC
            "
        )->all();
        $listado = ArrayHelper::map($usuarios, 'idusuario', 'nombre');
        return $listado;
    }

    protected function getFilterDirecciones()
    {
        $direccionesFiltro = Mds_certificacion_direccion_usuario::findBySql(
            "SELECT
            direccion_usuario.idcertificaciondireccion as idcertificaciondireccion,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_direccion_usuario direccion_usuario
            INNER JOIN mds_certificacion_direccion direccion
            ON direccion_usuario.idcertificaciondireccion = direccion.idcertificaciondireccion
            INNER JOIN sds_com_configuracion configuracion
            ON direccion.iddireccion = configuracion.idconfiguracion
            WHERE direccion_usuario.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();
        $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'idcertificaciondireccion', 'descripcion');
        return $direccionesFiltro;
    }

    protected function getFilterUsuarios()
    {
        $usuariosFiltro = Mds_certificacion_direccion_usuario::findBySql(
            "SELECT
            usuario.idusuario as idusuario,
            UPPER (CONCAT (usuario.apellido, ' ', usuario.nombre)) as usuario_nombre
            FROM mds_certificacion_direccion_usuario direccion_usuario
            INNER JOIN mds_seg_usuario usuario
            ON direccion_usuario.idusuario = usuario.idusuario
            WHERE direccion_usuario.deleted_at IS NULL
            ORDER BY usuario_nombre ASC
        "
        )->asArray()->all();
        $usuariosFiltro = ArrayHelper::map($usuariosFiltro, 'idusuario', 'usuario_nombre');
        return $usuariosFiltro;
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
