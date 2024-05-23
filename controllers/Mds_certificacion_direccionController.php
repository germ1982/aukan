<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_direccion;
use app\models\Mds_certificacion_direccionSearch;
use app\models\Mds_certificacion_director;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_rol;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use \yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\db\conditions\BetweenColumnsCondition;

/**
 * Mds_certificacion_direccionController implements the CRUD actions for Mds_certificacion_direccion model.
 */
class Mds_certificacion_direccionController extends Controller
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
                    'lista_direcciones' => ['POST'],
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
     * Lists all Mds_certificacion_direccion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        $searchModel = new Mds_certificacion_direccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterDirectores' => $this->getListDirectores(),
            'filterDirecciones' => $this->getFilterDirecciones(),
            'filterDireccionesDependiente' => $this->getFilterDireccionesDependientes(),
            'filterNivelAutorizacion' => $this->getListNivelAutorizacion(),
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
        ]);
    }

    /**
     * Displays a single Mds_certificacion_direccion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_certificacion_direccion', $id, array());
        $dateToday = date('Y-m-d H:i:s');
        $model = $this->findModel($id);
        $model_director = Mds_certificacion_director::find()
            ->where(['idcertificaciondireccion' => $id, 'deleted_at' => null])
            ->andWhere(
                [
                    'or',
                    new BetweenColumnsCondition($dateToday, 'BETWEEN', 'fecha_desde', 'fecha_hasta'),
                    [
                        'and',
                        ['<=', 'fecha_desde', $dateToday],
                        ['fecha_hasta' => NULL]
                    ]
                ]
            )
            ->one();
        if (!$model_director) {
            $model_director = new Mds_certificacion_director;
        }

        $directores_list = Mds_certificacion_director::find()
            ->select(['usuario.nombre', 'usuario.apellido', 'DATE_FORMAT(mds_certificacion_director.fecha_desde,"%d/%m/%Y") AS fecha_desde', 'DATE_FORMAT(mds_certificacion_director.fecha_hasta,"%d/%m/%Y") AS fecha_hasta'])
            ->where(['idcertificaciondireccion' => $id, 'deleted_at' => null])
            ->innerJoin('mds_seg_usuario usuario', 'usuario.idusuario=mds_certificacion_director.idusuario')
            ->asArray()->orderBy(['mds_certificacion_director.fecha_desde' => SORT_DESC])->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Ver registro #' . $model->idcertificaciondireccion,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'model_director' => $model_director,
                    'directores_list' => $directores_list
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
                'model_director' => $model_director,
                'directores_list' => $directores_list
            ]);
        }
    }

    /**
     * Creates a new Mds_certificacion_direccion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mds_certificacion_direccion();
        $request = Yii::$app->request;
        //$model_director = new Mds_certificacion_director();
        $guarda_direccion = null;
        $director_asignado = null;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo registro",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        //'model_director' => $model_director,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listNivelAutorizacion' => $this->getListNivelAutorizacion(),
                        'listDirectores' => $this->getListDirectores(),
                        'listFuncion' => $this->getListFuncion()
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit", 'id' => 'btnGuardar', 'disabled' => true])
                ];
            } else if (isset(Yii::$app->request->post()['Mds_certificacion_direccion'])) {
                $data = Yii::$app->request->post();
                $data_direccion = Yii::$app->request->post()['Mds_certificacion_direccion'];
                //$data_director = Yii::$app->request->post()['Mds_certificacion_director'];

                $direccion_existe = Mds_certificacion_direccion::find()
                    ->where(['iddireccion' => $data_direccion['iddireccion'], 'iddireccion_padre' => $data_direccion['iddireccion_padre'] ? $data_direccion['iddireccion_padre'] : null, 'deleted_at' => null])
                    ->one();
                if (!$direccion_existe) {
                    $model_certificacion_direccion = new Mds_certificacion_direccion();
                    $model_certificacion_direccion->load($data_direccion);
                    $model_certificacion_direccion->iddireccion = $data_direccion['iddireccion'];
                    $model_certificacion_direccion->iddireccion_padre = $data_direccion['iddireccion_padre'];
                    $model_certificacion_direccion->idnivelautorizacion = $data_direccion['idnivelautorizacion'];
                    $model_certificacion_direccion->idusuario_carga = Yii::$app->user->id;
                    $model_certificacion_direccion->created_at = date('Y-m-d H:i:s');
                    $guarda_direccion = $model_certificacion_direccion->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_direccion', $model_certificacion_direccion->idcertificaciondireccion, $model_certificacion_direccion->getAttributes());

                    // if ($data_director['idusuario'] && $guarda_direccion) {
                    //     $director_asignado = $this->guardarDirector($data, $model_certificacion_direccion);
                    // }
                }

                if ($guarda_direccion) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $transaction->commit();
                    $string_director = '';
                    // if ($director_asignado) {
                    //     $string_director =  '<br><span>Director: ' . $director_asignado->usuario->apellido . ',' . $director_asignado->usuario->nombre;
                    // }

                    return [
                        'title' => "Nuevo registro creado",
                        'content' => '<span class="text-success">Creado exitosamente!</span><br>' .
                            '<span>Dirección: ' . !$direccion_existe ? $model_certificacion_direccion->direccion0->descripcion : '' . ' </span>' . $string_director .  ' </span>',
                        'footer' =>
                        Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {

                    return [
                        'title' => 'La dirección ya existe',
                        'content' => '<span class="text-danger">Ya existe una dirección cargada con los datos ingresados</span><br>' .
                            '<span>Dirección: ' . $direccion_existe->direccion0->descripcion . ' </span><br></span><br>',
                        'footer' =>
                        Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                    $transaction = Yii::$app->db->beginTransaction();
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Nuevo",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    //'model_director' => $model_director,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listNivelAutorizacion' => $this->getListNivelAutorizacion(),
                    'listDirectores' => $this->getListDirectores(),
                    'listFuncion' => $this->getListFuncion()
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar', 'readonly' => true])
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
                    //'model_director' => $model_director,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listNivelAutorizacion' => $this->getListNivelAutorizacion(),
                    'listDirectores' => $this->getListDirectores(),
                    'listFuncion' => $this->getListFuncion()
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
        $dateToday = date('Y-m-d H:i:s');
        $usuario = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $nuevo_director = null;
        $model_director = Mds_certificacion_director::find()
            ->where(['idcertificaciondireccion' => $id, 'deleted_at' => null])
            ->andWhere(
                [
                    'or',
                    new BetweenColumnsCondition($dateToday, 'BETWEEN', 'fecha_desde', 'fecha_hasta'),
                    [
                        'and',
                        ['<=', 'fecha_desde', $dateToday],
                        ['fecha_hasta' => NULL]
                    ]
                ]
            )
            ->one();
        if (!$model_director) {
            $model_director = new Mds_certificacion_director;
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar registro #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'model_director' => $model_director,
                        'listDirecciones' => $this->getListDirecciones(),
                        'listNivelAutorizacion' => $this->getListNivelAutorizacion(),
                        'listDirectores' => $this->getListDirectores(),
                        'listFuncion' => $this->getListFuncion()
                    ]),
                    'footer' =>
                    Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Actualizar', ['class' => 'btn btn-success', 'type' => "submit", 'disabled' => true, 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post())) {
                $data_director = $request->post()['Mds_certificacion_director'];
                $data_direccion = $request->post()['Mds_certificacion_direccion'];
                $model->updated_at = date('Y-m-d H:i:s');
                $model->idnivelautorizacion = $data_direccion['idnivelautorizacion'];

                //verificamos director
                if ($data_director['fecha_desde']) {
                    $fecha_desde = armarDateParaMySql($data_director['fecha_desde']);
                    $fecha_desde = date_create($fecha_desde);
                    $fecha_desde = date_format($fecha_desde, 'Y-m-d H:i:s');
                    $data_director['fecha_desde'] = $fecha_desde;
                }
                if ($data_director['fecha_hasta']) {
                    $fecha_hasta = armarDateParaMySql($data_director['fecha_hasta']);
                    $fecha_hasta = date_create($fecha_hasta);
                    $fecha_hasta = date_format($fecha_hasta, 'Y-m-d H:i:s');
                    $data_director['fecha_hasta'] = $fecha_hasta;
                }

                if (($model_director->idusuario != $data_director['idusuario']) || ($model_director->idfuncion != $data_director['idfuncion']) || ($model_director->fecha_desde != $data_director['fecha_desde']) || ($model_director->fecha_hasta != $data_director['fecha_hasta'])) {
                    $nuevo_director = new Mds_certificacion_director();
                    $nuevo_director->idusuario = $data_director['idusuario'];
                    $nuevo_director->idcertificaciondireccion = $id;
                    $nuevo_director->fecha_desde = $data_director['fecha_desde'];
                    $nuevo_director->fecha_hasta = $data_director['fecha_hasta'];
                    $nuevo_director->idfuncion = $data_director['idfuncion'];
                    $nuevo_director->observaciones = $data_director['observaciones'];
                    $nuevo_director->idusuario_carga = Yii::$app->user->id;
                    $nuevo_director->created_at = date('Y-m-d H:i:s');
                    if ($nuevo_director->save()) {
                        $model_director->deleted_at = date('Y-m-d H:i:s');
                        $model_director->idusuario_borra =  Yii::$app->user->id;
                        $model_director->save();
                    }
                }

                if ($data_director['observaciones'] != $model_director->observaciones) {
                    $model_director->observaciones = $data_director['observaciones'];
                    $model_director->updated_at = date('Y-m-d H:i:s');
                    $model_director->save();
                }

                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_direccion', $model->idcertificaciondireccion, $model->getAttributes());;
                    $infoDirector = $nuevo_director ? '<span>Director: <b>' . $nuevo_director->usuario->apellido . ' ' .  $nuevo_director->usuario->nombre . '</b></span><br>' : '';
                    return [
                        'title' => "Actualización",
                        'content' => '<b><span class="text-success">Se actualizó correctamente!</span></b><br>' .
                            '<span>Dirección: <b>' . $model->direccion0->descripcion . '</b></span><br>' . $infoDirector,
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

                $fecha_desde = armarDateParaMySql($model->fecha_desde);
                $fecha_desde = date_create($fecha_desde);
                $fecha_desde = date_format($fecha_desde, 'Y-m-d H:i:s');
                $model->fecha_desde = $fecha_desde;

                $fecha_hasta = armarDateParaMySql($model->fecha_hasta);
                $fecha_hasta = date_create($fecha_hasta);
                $fecha_hasta = date_format($fecha_hasta, 'Y-m-d H:i:s');
                $model->fecha_hasta = $fecha_hasta;

                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_direccion', $id, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se actualizó correctamente el registro.");
                    return $this->redirect(['mds_certificacion_direccion/index']);
                } else {
                    Yii::$app->session->setFlash('success', "Error al generar el registro.");
                    return $this->redirect(['mds_certificacion_direccion/index']);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'listDirecciones' => $this->getListDirecciones(),
                    'listNivelAutorizacion' => $this->getListNivelAutorizacion(),
                    'listDirectores' => $this->getListDirectores(),
                    'model_director' => $model_director,
                    'listFuncion' => $this->getListFuncion()
                ]);
            }
        }
    }

    /**
     * Deletes an existing Mds_certificacion_direccion model.
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
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', " Se borro correctamente el registro.");
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_certificacion_direccion', $model->idcertificaciondireccion, $model->getAttributes());
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_certificacion_direccion', $model->idcertificaciondireccion, $model->getAttributes());
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
     * Finds the Mds_certificacion_direccion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_certificacion_direccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_certificacion_direccion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListDirecciones()
    {
        //Busqueda de direcciones cargadas en sds configuracion
        $iddireccion = Sds_com_configuracion::find()->select(['idconfiguracion', 'UPPER(descripcion) as descripcion'])->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_DIRECCION, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $iddireccion = ArrayHelper::map($iddireccion, 'idconfiguracion', 'descripcion');
        return $iddireccion;
    }

    protected function getFilterDirecciones()
    {
        $direccionesFiltro = Mds_certificacion_direccion::findBySql(
            "SELECT
            configuracion.idconfiguracion as iddireccion,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_direccion direccion
            INNER JOIN sds_com_configuracion configuracion
            ON direccion.iddireccion = configuracion.idconfiguracion
            WHERE direccion.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();

        $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'iddireccion', 'descripcion');
        return $direccionesFiltro;
    }

    protected function getFilterDireccionesDependientes()
    {
        $direccionesFiltro = Mds_certificacion_direccion::findBySql(
            "SELECT
            configuracion.idconfiguracion as iddireccion,
            UPPER (configuracion.descripcion) as descripcion
            FROM mds_certificacion_direccion direccion
            INNER JOIN sds_com_configuracion configuracion
            ON direccion.iddireccion_padre = configuracion.idconfiguracion
            WHERE direccion.deleted_at IS NULL
            ORDER BY descripcion ASC
        "
        )->asArray()->all();

        $direccionesFiltro = ArrayHelper::map($direccionesFiltro, 'iddireccion', 'descripcion');
        return $direccionesFiltro;
    }

    protected function getListNivelAutorizacion()
    {
        //Busqueda de tipo de direcciones cargadas en sds configuracion
        $nivelesautorizacion = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_DIRECCION_RECEPTORA, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $listNivelesAutorizacion = ArrayHelper::map($nivelesautorizacion, 'idconfiguracion', 'descripcion');
        return $listNivelesAutorizacion;
    }

    protected function getListDirectores()
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

    protected function getListFuncion()
    {
        //Busqueda de funcion de direcciones cargadas en sds configuracion
        $funciones = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CERTIFICACION_FUNCION_USUARIO, "activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $listFunciones = ArrayHelper::map($funciones, 'idconfiguracion', 'descripcion');
        return $listFunciones;
    }

    protected function guardarDirector($data, $model_certificacion_direccion)
    {
        $director = null;
        $model_certificacion_director = new Mds_certificacion_director();
        $model_certificacion_director->idusuario = $data['Mds_certificacion_director']['idusuario'];
        $model_certificacion_director->idcertificaciondireccion = $model_certificacion_direccion->idcertificaciondireccion;
        //$model_certificacion_director->iddireccion = $data['Mds_certificacion_direccion']['iddireccion'];
        $model_certificacion_director->observaciones = $data['Mds_certificacion_director']['observaciones'];
        $model_certificacion_director->idfuncion = $data['Mds_certificacion_director']['idfuncion'];
        $fecha_desde = armarDateParaMySql($data['Mds_certificacion_director']['fecha_desde']);
        $fecha_desde = date_create($fecha_desde);
        $fecha_desde = date_format($fecha_desde, 'Y-m-d H:i:s');
        $model_certificacion_director->fecha_desde = $fecha_desde;

        if ($data['Mds_certificacion_director']['fecha_hasta']) {
            $fecha_hasta = armarDateParaMySql($data['Mds_certificacion_director']['fecha_hasta']);
            $fecha_hasta = date_create($fecha_hasta);
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d H:i:s');
            $model_certificacion_director->fecha_hasta = $fecha_hasta;
        }

        $model_certificacion_director->idusuario_carga = Yii::$app->user->id;
        $model_certificacion_director->created_at = date('Y-m-d H:i:s');

        if ($model_certificacion_director->save()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_certificacion_director', $model_certificacion_director->idcertificaciondirector, $model_certificacion_director->getAttributes());
            $director = $model_certificacion_director;
        }
        return $director;
    }

    // public function actionList_director($iddireccion = null)
    // {
    //     $fecha_actual = date('Y-m-d H:i:s');
    //     $direccion = mds_certificacion_direccion::find()
    //         ->select('UPPER(mds_seg_usuario.nombre),UPPER(mds_seg_usuario.apellido)')
    //         ->leftJoin('mds_certificacion_director', 'mds_certificacion_direccion.idcertificaciondireccion = mds_certificacion_director.idcertificaciondireccion')
    //         ->leftJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_director.idusuario')
    //         ->where(['mds_certificacion_direccion.iddireccion' => $iddireccion])
    //         ->andWhere(['<=', 'mds_certificacion_director.fecha_desde', $fecha_actual])
    //         ->andWhere(['>=', 'mds_certificacion_director.fecha_hasta', $fecha_actual])
    //         ->asArray()
    //         ->one();
    //     $director = '';
    //     if ($direccion) {
    //         $director = $direccion['apellido'] . ' ' . $direccion['nombre'];
    //     }
    //     return $director;
    // }

    public function actionLista_direcciones($idAreaSelected, $nivel = null, $fechaDesde, $fechaHasta)
    {
        if (
            Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_SOLICITANTE)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL1)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL2)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL3)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL4)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_NIVEL5)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_FUNCIONARIO)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL)
            || Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_DASHBOARD)
        ) {
            $arrDirecciones = [];
            $arrDireccion = [];

            $date_desde = armarDateParaMySql($fechaDesde);
            $date_desde = date_create($date_desde);
            $date_desde = date_format($date_desde, 'Y-m-d H:i:s');

            $date_hasta = armarDateParaMySql($fechaHasta);
            $date_hasta = date_create($date_hasta);
            $date_hasta = date_format($date_hasta, 'Y-m-d H:i:s');

            if ($nivel && $nivel != Mds_certificacion::ID_NIVEL4) {
                $direccionesProvinciales = Mds_certificacion_direccion::find()
                    ->where(['idcertificaciondireccion' => $idAreaSelected, 'deleted_at' => null])->asArray()
                    ->one();
                array_push($arrDirecciones, $direccionesProvinciales);

                //buscamos los que dependen de esa area
                $direccionesGenerales = Mds_certificacion_direccion::find()
                    ->where(['iddireccion_padre' => $direccionesProvinciales['iddireccion'], 'deleted_at' => null])->asArray()
                    ->all();
                $arrDirecciones = array_merge($arrDirecciones, $direccionesGenerales);

                foreach ($direccionesGenerales as $direccionGeneral) {
                    //buscamos los que dependen de esa area
                    $direccionesSimples = Mds_certificacion_direccion::find()
                        ->where(['iddireccion_padre' => $direccionGeneral['iddireccion'], 'deleted_at' => null])->asArray()
                        ->all();

                    $arrDirecciones = array_merge($arrDirecciones, $direccionesSimples);
                }

                if (sizeof($arrDirecciones) > 0) {
                    foreach ($arrDirecciones as $direccion) {
                        if ($direccion && $direccion['idnivelautorizacion'] == $nivel) {
                            $director = Mds_certificacion_director::find()->select('mds_seg_usuario.apellido,mds_seg_usuario.nombre,sds_com_configuracion.descripcion,mds_certificacion_direccion.idcertificaciondireccion')
                                ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario=mds_certificacion_director.idusuario')
                                ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.idcertificaciondireccion=mds_certificacion_director.idcertificaciondireccion')
                                ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                                ->where(['mds_certificacion_director.idcertificaciondireccion' => $direccion['idcertificaciondireccion'], 'mds_certificacion_director.idfuncion' => Mds_certificacion_director::ID_FUNCION_DIRECTOR, 'mds_certificacion_director.deleted_at' => null])
                                ->andWhere(['<=', 'mds_certificacion_director.fecha_desde', $date_desde])
                                ->andWhere([
                                    'or',
                                    ['mds_certificacion_director.fecha_hasta' => NULL],
                                    ['>=', 'mds_certificacion_director.fecha_hasta', $date_desde],
                                ])
                                ->asArray()
                                ->one();
                            if ($director) {
                                array_push($arrDireccion, $director);
                            }
                        }
                    }
                }
            } else {
                $direccionProvincial = Mds_certificacion_direccion::find()
                    ->where(['idcertificaciondireccion' => $idAreaSelected, 'deleted_at' => null])->asArray()
                    ->one();
                $subsecretaria = Mds_certificacion_direccion::find()
                    ->where(['iddireccion' => $direccionProvincial['iddireccion_padre'], 'deleted_at' => null])->asArray()
                    ->one();
                array_push($arrDirecciones, $subsecretaria);

                if (sizeof($arrDirecciones) > 0) {
                    foreach ($arrDirecciones as $direccion) {
                        $director = Mds_certificacion_director::find()
                            ->select('mds_seg_usuario.nombre,mds_seg_usuario.apellido,sds_com_configuracion.descripcion,mds_certificacion_direccion.idcertificaciondireccion')
                            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario=mds_certificacion_director.idusuario')
                            ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_direccion.idcertificaciondireccion=mds_certificacion_director.idcertificaciondireccion')
                            ->innerJoin('sds_com_configuracion', 'mds_certificacion_direccion.iddireccion=sds_com_configuracion.idconfiguracion')
                            ->where(['mds_certificacion_director.idcertificaciondireccion' => $direccion['idcertificaciondireccion'], 'mds_certificacion_director.idfuncion' => Mds_certificacion_director::ID_FUNCION_DIRECTOR, 'mds_certificacion_director.deleted_at' => null])
                            ->asArray()
                            ->one();
                        array_push($arrDireccion, $director);
                    }
                }
            }
            $listado = json_encode($arrDireccion);
            return $listado;
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
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
