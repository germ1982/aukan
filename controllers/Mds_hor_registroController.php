<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_hor_franco;
use app\models\Mds_hor_licencia;
use Yii;
use app\models\Mds_hor_registro;
use app\models\Mds_hor_registro_import;
use app\models\Mds_hor_registroSearch;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\View_contactos_activos;
use app\models\View_contadores_fichadas;
use phpDocumentor\Reflection\Types\Array_;
use yii\helpers\Url;

/**
 * Mds_hor_registroController implements the CRUD actions for Mds_hor_registro model.
 */
class Mds_hor_registroController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout',
                    'get_contactos_activos', 'get_contadores_fichadas', 'get_licencia', 'set_masivo', 'fichada_legajo',
                    'importar_horarios_excel_yii', 'procesar_horarios_excel',
                    'verificar_fecha_existente', 'importacion', 'fichada_dni'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete', 'bulk-delete', 'update', 'view', 'logout',
                            'get_contactos_activos', 'get_contadores_fichadas', 'get_licencia', 'set_masivo', 'fichada_legajo',
                            'importar_horarios_excel_yii', 'procesar_horarios_excel',
                            'verificar_fecha_existente', 'importacion'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RRHH,
                        ],
                    ],
                    [
                        'actions' => ['reporte_fichadas'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_REPORTE,
                        ],
                    ],
                    [
                        'actions' => ['fichada_dni'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_FICHADA_DNI,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_registro models.
     * @return mixed
     */
    public function actionIndex($idcontacto = null, $desde = null, $hasta = null)
    {
        $searchModel = new Mds_hor_registroSearch();
        /* $searchModel->fdesde = date('d-m-Y');
        $searchModel->fhasta = date('d-m-Y'); */
        $idcontacto != null ? $searchModel->idcontacto = $idcontacto : null;
        $desde != null ? $searchModel->fdesde = $desde : null;
        $hasta != null ? $searchModel->fhasta = $hasta : null;
        $search = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider = $search['dataProvider'];
        $pagination = $search['pagination'];
        $contadores = null;
        $contactos = [];

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_registro', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
            'contactos' => $contactos,
            'contadores' => $contadores
        ]);
    }

    public function actionGet_contactos_activos()
    {
        $contactos = View_contactos_activos::find()->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $output = [];
        foreach ($contactos as $contacto) {
            array_push($output, [
                'idcontacto' => $contacto->idcontacto,
                'contacto' => "$contacto->legajo - $contacto->apellido, $contacto->nombre"
            ]);
        }
        return $output;
    }

    public function actionGet_contadores_fichadas()
    {
        $contadores = View_contadores_fichadas::find()->one();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $contadores;
    }

    public function actionGet_licencia($idcontacto)
    {
        $licencia = Mds_hor_licencia::find()->where(['idcontacto' => $idcontacto])->orderBy(['desde' => SORT_DESC])->one();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $licencia;
    }

    /**
     * Displays a single Mds_hor_registro model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_registro', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Registro #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-danger pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_hor_registro model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_registro();
        $model_franco = new Mds_hor_franco();
        $empleados = Mds_org_contacto::findBySql(
            "SELECT * FROM mds_org_contacto c 
                JOIN sds_com_persona p ON p.idpersona=c.idpersona 
                WHERE legajo IS NOT NULL AND activo ORDER BY p.apellido,p.nombre"
        )->all();
        $tipos_franco = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::FRANCO_TIPO])->all();
        $model->fecha = date('d/m/Y');
        $model->origen = 1;
        $model->activo = 1;
        $model->presente = 1;
        $guardados = [0 => 'Datos guardados de manera correcta:'];
        $errores = [0 => 'Datos que no se guardaron de manera correcta:'];
        $hasFichadas = [];
        if ($request->isAjax) {
        } else {
            if ($model->load($request->post())) {
                $confirm = $request->post('confirm');
                $fechas = explode(',', $model->fecha);
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->presente) {
                    foreach ($fechas as $fecha) {
                        $guardar = false;
                        $fecha_registro = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
                        $ingreso = new Mds_hor_registro();
                        $ingreso->load($request->post());
                        $ingreso->origen = 1;
                        $ingreso->fecha  = date('Y-m-d H:i:s', strtotime($fecha_registro . ' ' . $model->ingreso));
                        $ingreso->idusuario = Yii::$app->user->identity->idusuario;
                        $egreso = new Mds_hor_registro();
                        $egreso->load($request->post());
                        $egreso->origen = 1;
                        $egreso->fecha  = date('Y-m-d H:i:s', strtotime($fecha_registro . ' ' . $model->egreso));
                        $egreso->idusuario = Yii::$app->user->identity->idusuario;
                        //Verifico si corresponde a horario nocturno:
                        if ($ingreso->fecha > $egreso->fecha) {
                            $ingreso->horario_nocturno = true;
                            $egreso->horario_nocturno = true;
                        }
                        $licencias = Mds_hor_licencia::findBySql(
                            "SELECT * FROM `mds_hor_licencia` WHERE (idcontacto=$model->idcontacto) AND ('$fecha_registro' BETWEEN desde AND hasta)"
                        )->all();
                        $francos = Mds_hor_franco::findBySql(
                            "SELECT * FROM mds_hor_franco WHERE (idcontacto=$model->idcontacto) AND (fecha = '$fecha_registro')"
                        )->all();
                        $fichadas = Mds_hor_registro::findBySql(
                            "SELECT * FROM mds_hor_registro WHERE (idcontacto=$model->idcontacto) AND fecha like '%$fecha_registro%'"
                        )->all();

                        if (empty($licencias) && empty($francos) && empty($fichadas)) {
                            $guardar = true;
                        } else {
                            if (!empty($licencias)) {
                                array_push($errores, 'El contacto registra licencia en la fecha ' . $fecha);
                            }
                            if (!empty($francos)) {
                                array_push($errores, 'El contacto registra franco en la fecha ' . $fecha);
                            }
                            if (!empty($fichadas) && $confirm == 0) {
                                $url =  Url::to([
                                    'index',
                                    'idcontacto' => $model->idcontacto,
                                    'desde' => str_replace('/', '-', $fecha),
                                    'hasta' => str_replace('/', '-', $fecha)
                                ]);
                                array_push($hasFichadas, 'El contacto registra ' . Html::a(
                                    'fichadas',
                                    $url,
                                    ['target' => '_blank', 'title' => 'Ver Fichadas']
                                ) . ' en la fecha ' . $fecha);
                            } else {
                                $guardar = true;
                            }
                        }
                        if ($guardar) {
                            $ingreso->validate();
                            $egreso->validate();
                            if ($ingreso->save() && $egreso->save()) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $ingreso->idregistrohorario, $ingreso->getAttributes());
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $egreso->idregistrohorario, $egreso->getAttributes());
                                array_push($guardados, 'Ingreso: ' . date('d/m/Y H:i:s', strtotime($ingreso->fecha)) . ' | Egreso: ' . date('d/m/Y H:i:s', strtotime($egreso->fecha)));
                            }
                        }
                    }
                    if (count($guardados) > 1 && empty($hasFichadas)) {
                        $transaction->commit();
                        if ($model->reset_form) {
                            $model = new Mds_hor_registro();
                            $model->fecha = date('d/m/Y');
                            $model->origen = 1;
                            $model->activo = 1;
                            $model->presente = 1;
                            $model->reset_form = 1;
                        }
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    foreach ($fechas as $fecha) {
                        $model_franco = new Mds_hor_franco();
                        $fecha_registro = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
                        if ($model_franco->load($request->post())) {
                            $model_franco->idcontacto = $model->idcontacto;
                            $model_franco->fecha = date('Y-m-d', strtotime($fecha_registro));
                            $model_franco->descripcion = $model->observaciones;
                            $francos = Mds_hor_franco::findBySql(
                                "SELECT * FROM mds_hor_franco WHERE (idcontacto=$model->idcontacto) AND (fecha = '$fecha_registro')"
                            )->all();
                            $licencias = Mds_hor_licencia::findBySql(
                                "SELECT * FROM `mds_hor_licencia` WHERE (idcontacto=$model->idcontacto) AND ('$fecha_registro' BETWEEN desde AND hasta)"
                            )->all();
                            $fichadas = Mds_hor_registro::findBySql(
                                "SELECT * FROM mds_hor_registro WHERE (idcontacto=$model->idcontacto) AND fecha like '%$fecha_registro%'"
                            )->all();
                        }
                        if (empty($francos) && empty($licencias) && empty($fichadas)) {
                            if ($model_franco->save()) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_franco', $model_franco->idfranco, $model_franco->getAttributes());
                                $tipo_franco = '';
                                foreach ($tipos_franco as $tipo) {
                                    if ($tipo->idconfiguracion == $model_franco->tipo) {
                                        $tipo_franco = $tipo->descripcion;
                                    }
                                }
                                array_push($guardados, "$tipo_franco: " . date('d/m/Y', strtotime($model_franco->fecha)));
                            }
                        } else {
                            if (!empty($licencias)) {
                                array_push($errores, 'El contacto registra licencia en la fecha ' . $fecha);
                            }
                            if (!empty($francos)) {
                                array_push($errores, 'El contacto registra franco en la fecha ' . $fecha);
                            }
                            if (!empty($fichadas)) {
                                $url =  Url::to([
                                    'index',
                                    'idcontacto' => $model->idcontacto,
                                    'desde' => str_replace('/', '-', $fecha),
                                    'hasta' => str_replace('/', '-', $fecha)
                                ]);
                                array_push($errores, 'El contacto registra ' . Html::a(
                                    'fichadas',
                                    $url,
                                    ['target' => '_blank', 'title' => 'Ver Fichadas']
                                ) . ' en la fecha ' . $fecha);
                            }
                        }
                    }
                    if (count($guardados) > 1) {
                        $confirm = false;
                        $transaction->commit();
                        if ($model->reset_form) {
                            $model = new Mds_hor_registro();
                            $model->fecha = date('d/m/Y');
                            $model->origen = 1;
                            $model->activo = 1;
                            $model->presente = 0;
                            $model->reset_form = 1;
                            $model_franco = new Mds_hor_franco();
                        }
                    } else {
                        $transaction->rollBack();
                    }
                }
            }
            return $this->render('create', [
                'model' => $model,
                'guardados' => $guardados,
                'errores' => $errores,
                'model_franco' => $model_franco,
                'empleados' => $empleados,
                'tipos_franco' => $tipos_franco,
                'hasFichadas' => $hasFichadas
            ]);
        }
    }

    public function actionSet_masivo()
    {
        $this->redirect(['mds_hor_registro/create']); //Se agrega este redirect para dejar sin uso la función actual pero sin eliminar el codigo, vio como es RRHH!
        $request = Yii::$app->request;
        $model = new Mds_hor_registro();
        $model->origen = Mds_hor_registro::ORIGEN_MANUAL;
        $model->activo = 1;
        $save = array();
        $errores = array();
        if ($model->load($request->post())) {
            $fechas = explode(',', $model->fecha);
            for ($i = 0; $i < 2; $i++) {
                //En la primer vuelta cargo los ingresos, en la segunda los egresos                    
                foreach ($fechas as $fecha) {
                    $registro = new Mds_hor_registro();
                    $registro->idcontacto = $model->idcontacto;
                    $registro->origen = $model->origen;
                    $registro->observaciones = $model->observaciones;
                    $registro->activo = $model->activo;
                    if (($i == 0) && ($model->ingreso != null)) {
                        $registro->fecha = date('Y-m-d H:i', strtotime(str_replace('/', '-', $fecha . ' ' . $model->ingreso)));
                    }
                    if (($i == 1) && ($model->egreso != null)) {
                        $registro->fecha = date('Y-m-d H:i', strtotime(str_replace('/', '-', $fecha . ' ' . $model->egreso)));
                    }
                    if ($registro->fecha != null) {
                        if ($registro->save()) {
                            array_push($save, 'El ' . ($i == 0 ? 'ingreso' : 'egreso') . ' del día ' . $fecha . ' se guardó de manera correcta');
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $registro->idregistrohorario, $registro->getAttributes());
                        } else {
                            array_push($errores, 'Está intentando duplicar el ' . ($i == 0 ? 'ingreso' : 'egreso') . ' del día ' . $fecha);
                        }
                    }
                }
            }
            if (empty($errores)) {
                $model->idcontacto = null;
                $model->fecha = null;
                $model->ingreso = null;
                $model->egreso = null;
                $model->observaciones = null;
            }
        }
        return $this->render('set_masivo', [
            'model' => $model,
            'save' => $save,
            'errores' => $errores
        ]);
    }

    public function actionFichada_legajo()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_registro();
        $model->fecha = date('Y-m-d H:i');
        $model->origen = Mds_hor_registro::ORIGEN_GUARDIA;
        $model->activo = 1;
        $save = false;

        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!$request->isGet && $model->load($request->post())) {
            $model->fecha = date('Y-m-d H:i');
            $contacto = Mds_org_contacto::find()->where(["legajo" => $model->legajo])->one();
            if ($contacto != null) {
                $idcontacto = $contacto->idcontacto;
                $model->idcontacto = $idcontacto;
                $model->observaciones = "Fichada Guardia";
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $model->idregistrohorario, $model->getAttributes());
                    $save = true;
                    return [
                        'title' => "Fichar Ingreso Legajo",
                        'content' => $this->renderAjax('fichada_legajo', [
                            'model' => $model,
                            'save' => $save
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Fichar', [
                                'name' => 'submit', 'value' => 'save_add',
                                'class' => 'btn btn-success', 'type' => "submit",
                                'id' => 'btnGuardarRegistroHorario'
                            ])

                    ];
                }
                $model->addError("legajo", "Error! No pudo realizarse la fichada. Intente de nuevo más tarde.");
            } else {
                $model->addError("legajo", "Legajo No Encontrado.");
            }
        }

        return [
            'title' => "Fichar Ingreso Legajo",
            'content' => $this->renderAjax('fichada_legajo', [
                'model' => $model,
                'save' => $save
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Agregar Fichada', [
                    'name' => 'submit', 'value' => 'save_add',
                    'class' => 'btn btn-success', 'type' => "submit",
                    'id' => 'btnGuardarRegistroHorario'
                ])
        ];
    }

    public function actionFichada_dni()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_registro();

        if ($request->post()){
            $model->fecha = date('Y-m-d H:i:s');
            $model->origen = Mds_hor_registro::ORIGEN_GUARDIA;
            $model->observaciones = "Fichado vía lectura DNI";
            $model->activo = 1;
            $model->dni = $request->post('Mds_hor_registro')['dni'];
            $contacto = Mds_org_contacto::findBySql(
                "SELECT c.*, p.apellido apellido, p.nombre nombre FROM mds_org_contacto c
                JOIN sds_com_persona p ON p.idpersona=c.idpersona
                WHERE p.documento=$model->dni"
            )->one();
            if (!is_null($contacto)) {
                if($contacto->activo){
                    $model->idcontacto = $contacto->idcontacto;
                    if($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $model->idregistrohorario, $model->getAttributes());
                        Yii::$app->session->setFlash("success", "$contacto->apellido, $contacto->nombre");
                    }else{
                        Yii::$app->session->setFlash("error", "");
                    }
                }else{
                    Yii::$app->session->setFlash("error", "El contacto no está activo.");
                }
            }else{
                Yii::$app->session->setFlash("error", "DNI no encontrado.");
            }
            $model = new Mds_hor_registro();
        }
        
        return $this->render('fichada_dni', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha)));
        $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));

        $empleados = Mds_org_contacto::findBySql(
            "SELECT * FROM mds_org_contacto c 
                JOIN sds_com_persona p ON p.idpersona=c.idpersona 
                WHERE legajo IS NOT NULL AND activo ORDER BY p.apellido,p.nombre"
        )->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $model->fecha = date('Y-m-d H:i', strtotime(str_replace('/', '-', $model->fecha . ' ' . $model->hora)));
                if ($model->save()) {
                    $model->foto = ($model->foto != null) ? "fotoFichadaBase64" : null;
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_registro', $model->idregistrohorario, $model->getAttributes());
                    return [
                        'title' => "Actualizar Horario",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            return [
                'title' => "Actualizar Horario",
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'empleados' => $empleados
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Editar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    /**
     * Delete an existing Mds_hor_registro model.
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
            $model->foto = "fotoFichadaBase64";
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_hor_registro', $id, $model->getAttributes());
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        }
    }

    /**
     * Delete multiple existing Mds_hor_registro model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        $transaction = Yii::$app->db->beginTransaction();
        $error = false;
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            if (!$model->delete()) {
                $transaction->rollBack();
                $error = true;
            }
        }
        $transaction->commit();
        if ($request->isAjax) {
            /*Process for ajax request*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Eliminar Registros Horarios",
                'content' => $error ? '<span class="text-danger">Hubo falla al intentar eliminar algún registro. La operación se canceló.</span>' :
                    '<span class="text-success">¡Los registros se eliminaron de manera correcta!</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Mds_hor_registro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_hor_registro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_hor_registro::findBySql(
            "SELECT r.*, CONCAT(p.apellido, ', ', p.nombre) contacto, u.user usuario_carga,
             log.fecha_hora fecha_carga
            FROM mds_hor_registro r
            JOIN mds_org_contacto c ON r.idcontacto=c.idcontacto
            JOIN sds_com_persona p ON c.idpersona=p.idpersona
            LEFT JOIN mds_sys_log log ON r.idregistrohorario=log.id 
            AND log.modulo='mds_hor_registro' 
            AND log.accion=1
            LEFT JOIN mds_seg_usuario u ON log.idusuario=u.idusuario
            WHERE r.idregistrohorario =$id"
        )->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionImportar_horarios_excel_yii()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_licencia();

        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => "Importar Excel con Horarios",
                'content' => $this->renderAjax('importar_horarios_excel_yii', [
                    'model' => $model,
                ]),


            ];
        }
    }

    public function actionProcesar_horarios_excel()
    {

        $json = Yii::$app->request->post('datos');
        $file_name = Yii::$app->request->post('file_name');
        //return $json;
        $array = json_decode($json, true);
        //return $array;
        $array_columnas = array(
            1 => "Reporte de Eventos de Asistencia", //de aca veo si el campo es ID: es la linea de una persona y a continuacion van sus horarios
            2 => "__EMPTY",
            3 => "__EMPTY_1",
            4 => "__EMPTY_2",
            5 => "__EMPTY_3", //de aca rescato el legajo
            6 => "__EMPTY_4",
            7 => "__EMPTY_5", //de aca rescato el mes
            8 => "__EMPTY_6",
            9 => "__EMPTY_7",
            10 => "__EMPTY_8",
            11 => "__EMPTY_9", //de aca rescato apellido
            12 => "__EMPTY_10",
            13 => "__EMPTY_11",
            14 => "__EMPTY_12",
            15 => "__EMPTY_13",
            16 => "__EMPTY_14",
            17 => "__EMPTY_15",
            18 => "__EMPTY_16",
            19 => "__EMPTY_17",
            20 => "__EMPTY_18",
            21 => "__EMPTY_19",
            22 => "__EMPTY_20",
            23 => "__EMPTY_21",
            24 => "__EMPTY_22",
            25 => "__EMPTY_23",
            26 => "__EMPTY_24",
            27 => "__EMPTY_25",
            28 => "__EMPTY_26",
            29 => "__EMPTY_27",
            30 => "__EMPTY_28",
            31 => "__EMPTY_29"
        );

        $ban_id = 0;
        $ban_arrancar = 0; //me sirve para saber cuando arranco
        $anuncio_log = ""; //es el texto que voy a devolver
        $idcontacto = 0;
        $fondo = "<div>";
        $mes = 0;
        $ban_guardar = 0;
        $fila_cont = 2;
        $cont_g_fichadas = 0;
        $cont_g_fichadas_repetidas = 0;
        $cont_g_contacto_inexistente = 0;
        $cont_g_contacto_sin_fichadas = 0;

        foreach ($array as $linea_exel) {
            $fila_cont = $fila_cont + 1;
            //---------------------------------------------------------
            if (isset($linea_exel["$array_columnas[1]"])) {
                $arranque = $linea_exel["$array_columnas[1]"];
            } else {
                $arranque = "";
            }
            //----------------------------------------------------------
            if ($arranque == 'Periodo:') {
                if (isset($linea_exel["$array_columnas[7]"])) {
                    $periodo = $linea_exel["$array_columnas[7]"];
                } else {
                    $periodo = $linea_exel["$array_columnas[3]"];
                }
                $dia = substr($periodo, 8, 2);
                $mes = substr($periodo, 5, 2);
                $anio = substr($periodo, 0, 4);
                //$anuncio_log = "<br>Periodo: $mes/$anio<br>";//comentar------------------------------------
            }
            //-------------------------------------------------------------
            if ($arranque == 'ID:') {
                $ban_arrancar = 1; //una vez que arranca ya queda fijo el 1
            }
            //-----------------------------------------------------------------
            if ($ban_arrancar == 1) {
                if ($arranque == 'ID:') {
                    if ($ban_id == 1) //si es 1 quiere decir que la fila anterior era in ID y no existian fichadas
                    {
                        //$anuncio_log = "$anuncio_log Fila $fila_cont No existen fichadas";
                        $anuncio_log = "$anuncio_log No existen fichadas";
                        $anuncio_log = "$anuncio_log <p style='color:blue; padding-left:10px;padding-bottom:0px'>No importado...</p></div>";
                        $fila_cont = $fila_cont + 1;
                        $cont_g_contacto_sin_fichadas = $cont_g_contacto_sin_fichadas + 1;
                    }
                    if (isset($linea_exel["$array_columnas[5]"])) {
                        $legajo = $linea_exel["$array_columnas[5]"];
                    } else {
                        $legajo = $linea_exel["$array_columnas[3]"];
                    }

                    $apellido = 'No tenia';
                    if (isset($linea_exel["$array_columnas[11]"])) {
                        $apellido = trim($linea_exel["$array_columnas[11]"]);
                    }
                    //----------------------------------------------------------------
                    $fondo = $this->get_fondo($fondo);
                    $anuncio_log = "$anuncio_log $fondo";
                    //-------------------------------------------
                    $idcontacto = $this->get_id_contacto($legajo);
                    if ($idcontacto === 0) {
                        $ban_guardar = 0;
                        //$anuncio_log = "$anuncio_log Fila $fila_cont Empleado: $apellido  Legajo: $legajo No existe en mds_org_contacto<br>";
                        $anuncio_log = "$anuncio_log Empleado: $apellido  Legajo: $legajo No existe en mds_org_contacto<br>";
                        $cont_g_contacto_inexistente = $cont_g_contacto_inexistente + 1;
                    } else {
                        //$anuncio_log = "$anuncio_log Fila $fila_cont Empleado: $apellido, Legajo: $legajo<br>";
                        $anuncio_log = "$anuncio_log Empleado: $apellido, Legajo: $legajo";
                        $ban_guardar = 1;
                    }
                    //-------------------------------------------
                    $ban_id = 1;
                } else {
                    if ($ban_guardar == 1) {
                        $aux = '';
                        $cont_guardadas = 0;
                        $cont_repetidas = 0;
                        $dia_inicio = ($dia == "16" ? 15 : 0);
                        for ($i = 1; $i <= 31; $i++) {

                            if (isset($linea_exel[$array_columnas[$i]])) {
                                $celda_fichadas = $linea_exel[$array_columnas[$i]];
                                $largo_celda_fichadas = strlen($celda_fichadas);
                                $cantidad_fichadas = ($largo_celda_fichadas) / 5;
                                $aux_fichadas = '';
                                for ($f = 0; $f < $largo_celda_fichadas; $f++) {
                                    if (($f % 5) == 0) {
                                        $k = $f - 5;
                                        $fichada = substr($celda_fichadas, $k, 5);
                                        $fecha_bd = $this->get_fecha($dia_inicio + $i, $mes, $anio, $fichada);

                                        if ($this->verificar_repeticion($idcontacto, $fecha_bd) == 1) {
                                            $aux_fichadas = "$aux_fichadas $fichada DUPLICADA, ";
                                            $cont_repetidas++;
                                            $cont_g_fichadas_repetidas++;
                                        } else {
                                            $this->guardar_fichada($idcontacto, $fecha_bd, $file_name);
                                            $cont_guardadas++;
                                            $aux_fichadas = "$aux_fichadas $fichada IMPORTADA, ";
                                            $cont_g_fichadas++;
                                        }
                                    }
                                }
                                $aux = "$aux Dia " . ($dia_inicio + $i) . " - $fecha_bd: $cantidad_fichadas Fichadas,$aux_fichadas<br>";
                            }
                        }
                        $anuncio_log = "$anuncio_log Largo $largo_celda_fichadas Fichadas: <br>$aux";
                        if ($cont_guardadas > 0) {
                            $anuncio_log = "$anuncio_log <p style='color:green; padding-left:10px;padding-bottom:0px'>$cont_guardadas Fichadas importadas</p></div>";
                        }
                        if ($cont_repetidas > 0) {
                            $anuncio_log = "$anuncio_log <p style='color:orange; padding-left:10px;padding-bottom:0px'>$cont_repetidas Fichadas duplicadas</p></div>";
                        }

                        //$anuncio_log = "$anuncio_log $cont_guardadas fichadas importadas y $cont_repetidas fichadas duplicadas...";
                        //$anuncio_log = "$anuncio_log <p style='color:green; padding-left:10px;padding-bottom:0px'>importado...</p></div>";
                    } else {
                        //$anuncio_log = "$anuncio_log Fila $fila_cont Solicite el Alta del Empleado para guardar las Fichadas";
                        $anuncio_log = "$anuncio_log Solicite el Alta del Empleado para guardar las Fichadas";
                        $anuncio_log = "$anuncio_log <p style='color:red; padding-left:10px;padding-bottom:0px'>No importado...</p></div>";
                    }
                    $ban_id = 0;
                }
            }
        }

        $anuncio_log = "Periodo: $mes/$anio<br>Total empleados inexistentes en base de datos: $cont_g_contacto_inexistente<br>Total empleados sin fichadas: $cont_g_contacto_sin_fichadas<br>Total fichadas guardadas: $cont_g_fichadas <br>Total fichadas duplicadas: $cont_g_fichadas_repetidas<br> $anuncio_log";
        return $anuncio_log;
    }

    /* public function actionImportacion_exell_por_yii()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_registro_import();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
            return [
                'title' => "Importar XLS Asistencia",
                'content' => $this->renderAjax('importacion_exell', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Importar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else if ($model->load($request->post())) {
            $tmpfile = UploadedFile::getInstance($model, 'archivo_xls'); //al parecer pasa lo del exel a un onda contexto xls de yii

            if (isset($tmpfile)) {
                $tmpfile_contents = file_get_contents($tmpfile->tempName); //extrae el contenido del xls de yii y lo mete en una variable
                $lineas_archivo = explode("\n", $tmpfile_contents); //arma un array de las lineas del exell

                $mensaje = "";
                foreach ($lineas_archivo as $linea) //empieza a recorrer las lineas
                {
                    //$mensaje = "$mensaje $linea <br>";

                    $contacto = explode(" ", trim(preg_replace('/\s+/', ' ', $linea))); //separa las lineas en celdas?

                    $dato  = iconv("CP1257", "UTF-8", $contacto[0]);

                    $mensaje = "$mensaje" . $dato . "<br>";
                }

                return [
                    'title' => "Resultado Importar TXT Asistencia",
                    'content' => "$mensaje",
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                $model->addError("archivo_xls", "Debe ingresar un archivo XLS para importar");
            }
        }
        return [
            'title' => "Importar XLS Asistencia",
            'content' => $this->renderAjax('importacion_exell', [
                'model' => $model,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Importar', ['class' => 'btn btn-primary', 'type' => "submit"])

        ];
    } */

    public function actionVerificar_fecha_existente($id_contacto = null, $fechas = null)
    {
        $licencia = '';
        $fechas = explode(',', $fechas);
        foreach ($fechas as $fecha) {
            $fecha =  date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
            $model_licencia = Mds_hor_licencia::find()->where("idcontacto = $id_contacto and '$fecha' BETWEEN desde and hasta")->one();
            if (!empty($model_licencia)) {
                $d = DateTime::createFromFormat('Y-m-d', $model_licencia->desde);
                $h = DateTime::createFromFormat('Y-m-d', $model_licencia->hasta);
                $desde = $d->format('d/m/Y');
                $hasta = $h->format('d/m/Y');
                if ($licencia != '') {
                    $licencia .= '<br>';
                }
                $licencia .= "- " . $desde . " hasta: " . $hasta;
            }
        }
        return $licencia;
    }

    public function actionImportacion()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_registro_import();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!$request->isGet && $model->load($request->post())) {
            $tmpfile = UploadedFile::getInstance($model, 'archivo_txt');
            if (isset($tmpfile)) {
                $tmpfile_contents = file_get_contents($tmpfile->tempName);
                $lineas_archivo = explode("\n", $tmpfile_contents);
                $error = false;
                $cant_importados = 0;
                $duplicados = array();
                $inexistentes = array();
                $error_fecha_hora = array();
                $error_legajo = array();
                foreach ($lineas_archivo as $linea) {
                    $contacto = explode(" ", trim(preg_replace('/\s+/', ' ', $linea)));
                    if (
                        sizeof($contacto) == 3
                        || sizeof($contacto) == 8
                        || sizeof($contacto) == 7
                    ) {
                        $tres_cols = sizeof($contacto) == 3;
                        $siete_cols = sizeof($contacto) == 7;
                        $legajo = $contacto[0];
                        if (ctype_digit($legajo)) {
                            $legajo = intval($legajo);
                            $fecha = trim($contacto[$tres_cols || $siete_cols ? 1 : 3]);
                            $fecha_anio_largo = $this->validarFecha($fecha, 'd/m/Y');
                            $fecha_anio_corto = $this->validarFecha($fecha, 'd/m/y');
                            $fecha_manca = $this->validarFecha($fecha, 'j/m/Y');
                            $fecha_db = $this->validarFecha($fecha, 'Y-m-d');
                            if ($fecha_anio_largo || $fecha_anio_corto || $fecha_manca || $fecha_db) {
                                $hora = trim($tres_cols || $siete_cols ? $contacto[2] : $contacto[4]);
                                if ($fecha_anio_corto) {
                                    $anio = substr($fecha, -2);
                                    $anio = "20" . $anio;
                                    $fecha = substr($fecha, 0, strlen($fecha) - 2) . $anio;
                                }
                                if ($this->validarFecha($hora, 'H:i') || $this->validarFecha($hora, 'H:i:s')) {
                                    $fecha =  date('Y-m-d H:i', strtotime(str_replace('/', '-', $fecha . ' ' . $hora)));
                                    $contacto = Mds_org_contacto::find()->where(["legajo" => $legajo])->one();
                                    if ($contacto != null) {
                                        $model_registro = new Mds_hor_registro();
                                        $model_registro->origen = 0;
                                        $idcontacto = $contacto->idcontacto;
                                        $model_registro->idcontacto = $idcontacto;
                                        $model_registro->fecha = $fecha;
                                        $model_registro->observaciones = $tmpfile->name;
                                        $model_registro->activo = 1;
                                        if ($model_registro->save()) {
                                            $cant_importados++;
                                        } else {
                                            if (!in_array($legajo, $duplicados))
                                                array_push($duplicados, $legajo);
                                        }
                                    } else {
                                        if (!in_array($legajo, $inexistentes))
                                            array_push($inexistentes, $legajo);
                                    }
                                } else {
                                    $registro = $legajo . " - Fecha: " . $fecha . " - Hora: " . $hora;
                                    if (!in_array($registro, $error_fecha_hora))
                                        array_push($error_fecha_hora, $registro);
                                }
                            } else {
                                $registro = $legajo . " - Fecha: " . $fecha;
                                if (!in_array($registro, $error_fecha_hora))
                                    array_push($error_fecha_hora, $registro);
                            }
                        } else {
                            if (trim($legajo) != 'Ac-No' && !in_array($legajo, $error_legajo))
                                array_push($error_legajo, $legajo);
                        }
                    } else { //Si el TXT tiene un formato distinto al de 3 u columnas
                        array_push($error_legajo, print_r(sizeof($contacto), true));
                    }
                }
                if (!$error) {
                    $duplicados_string = "";
                    foreach ($duplicados as $legajo) {
                        $duplicados_string = $duplicados_string . "<div class=\"col-md-2\">" . $legajo . "</div>";
                    }
                    $inexistentes_string = "";
                    foreach ($inexistentes as $legajo) {
                        $inexistentes_string = $inexistentes_string . "<div class=\"col-md-2\">" . $legajo . "</div>";
                    }
                    $error_fch_string = "";
                    foreach ($error_fecha_hora as $legajo) {
                        $error_fch_string = $error_fch_string . "<div class=\"col-md-4\">" . $legajo . "</div>";
                    }
                    $error_leg_string = "";
                    foreach ($error_legajo as $legajo) {
                        $error_leg_string = $error_leg_string . "<div class=\"col-md-2\">" . $legajo . "</div>";
                    }
                    return [
                        'title' => "Importar TXT/DAT Asistencia",
                        'content' => ($cant_importados > 0 ? '<span class="text-success">Importados ' . $cant_importados . ' Registros Exitosamente!</span>' : '') .
                            ($duplicados_string != "" ? '<span class="row text-warning">Advertencia! Hubieron legajos duplicados que no se importaron: <br>' . $duplicados_string . '</span>' : "") .
                            ($inexistentes_string != "" ? '<span class="row text-danger">Error! Legajos inexistentes: <br>' . $inexistentes_string . '</span>' : "") .
                            ($error_fch_string != "" ? '<span class="row text-danger">Error! Formato de fecha incorrecto: <br>' . $error_fch_string . '</span>' : "") .
                            ($error_leg_string != "" ? '<span class="row text-danger">Error! Formato de legajo incorrecto: <br>' . $error_leg_string . '</span>' : ""),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Importar Otro', ['importacion'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            } else {
                $model->addError("archivo_txt", "Debe ingresar un archivo TXT/DAT para importar");
            }
        }
        return [
            'title' => "Importar TXT/DAT Asistencia",
            'content' => $this->renderAjax('importacion', [
                'model' => $model,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Importar', ['class' => 'btn btn-primary', 'type' => "submit"])

        ];
    }


    public function actionReporte_fichadas($periodo = null, $certificados = 0)
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_registro();
        $contactos = null;
        if ($periodo != null) {
            $periodo = explode('/', $periodo);
            $contactos = Mds_org_contacto::findBySql(
                "SELECT cp.legajo, cp.documento, cp.nombre nombre, cp.apellido apellido, i.descripcion edificio,
                round(count(*)/2,0) fichadas
                FROM view_contactos_personas cp
                JOIN mds_hor_registro r on r.idcontacto=cp.idcontacto
                JOIN mds_org_dispositivo d on d.iddispositivo=cp.iddispositivo
                JOIN sds_gis_capa_item i on i.idcapaitem=d.idcapaitem
                WHERE MONTH(r.fecha)=$periodo[0] AND YEAR(r.fecha)=$periodo[1]" . ($certificados == 1 ? "" : " AND (r.observaciones IS NULL OR r.observaciones NOT LIKE 'Certificado por%')") .
                    " GROUP BY cp.legajo,cp.nombre,cp.apellido,i.descripcion
                ORDER BY cp.apellido,cp.nombre"
            )->all();

            Yii::$app->response->format = Response::FORMAT_JSON;
            $registros = [];
            foreach ($contactos as $contacto) {
                $registro = [
                    "Legajo" => $contacto->legajo,
                    "DNI" => $contacto->documento,
                    "Apellido" => $contacto->apellido,
                    "Nombre" => $contacto->nombre,
                    "Edificio" => $contacto->edificio,
                    "Fichadas" => $contacto->fichadas
                ];
                array_push($registros, $registro);
            }
            return $registros;
        }

        return $this->render('report_count_fichadas', [
            'model' => $model,
            'contactos' => $contactos
        ]);
    }

    private function validarFecha($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    private function get_fondo($fondo)
    {
        if ($fondo == "<div>") {
            $fondo = "<div style='background-color:#CDE1F9;'>";
        } else {
            $fondo = "<div>";
        }
        $fondo = "<div style='background-color:#CDE1F9;'>";
        return $fondo;
    }

    private function get_id_contacto($legajo)
    {
        $contacto = Mds_org_contacto::findBySql("SELECT * FROM mds_org_contacto WHERE legajo = $legajo")->one();
        $idcontacto = 0;

        if ($contacto != null) {
            $idcontacto = $contacto->idcontacto;
        }
        return $idcontacto;
    }

    private function verificar_repeticion($idcontacto, $fecha_bd)
    {
        $existe = 0;
        $registro = Mds_hor_registro::find()->where("idcontacto = $idcontacto and fecha = '$fecha_bd'")->all();

        if ($registro != null) {
            $existe = 1;
        }

        return $existe;
    }

    private function get_fecha($dia, $mes, $anio, $hora)
    {
        if ($dia < 10) {
            $dia = "0$dia";
        }
        $hora = "$hora:00";

        $fecha_bd = "$anio-$mes-$dia $hora";
        return $fecha_bd;
    }

    private function guardar_fichada($idcontacto, $fecha_bd, $file_name = null)
    {
        $file_name = explode(".", $file_name);
        $model = new Mds_hor_registro();
        $model->idcontacto = $idcontacto;
        $model->fecha = $fecha_bd;
        $model->observaciones = $file_name[0];
        if ($model->validate()) {
            if ($model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $model->idregistrohorario, $model->getAttributes());
            }
        }
        //return $model->getErrors();
    }
}
