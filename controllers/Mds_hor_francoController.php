<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_franco;
use app\models\Mds_hor_francoSearch;
use app\models\Mds_hor_licencia;
use app\models\Mds_hor_registro;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\View_franco;
use app\models\View_francoSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Mds_hor_francoController implements the CRUD actions for Mds_hor_franco model.
 */
class Mds_hor_francoController extends Controller
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
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout', 'refresh_index',
                    'clonar_francos', 'set_periodo', 'exportar_francos', 'create_ext', 'update_ext'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete', 'bulk-delete', 'update', 'view', 'logout', 'refresh_index',
                            'clonar_francos', 'set_periodo', 'exportar_francos', 'create_ext', 'update_ext'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RRHH,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_franco models.
     * @return mixed
     */
    public function actionIndex($idcontacto = -1)
    {
        /* Dejo sin utilizar el search. Se va a manipular la lista de francos por javascript desde la vista.
        $searchModel = new Mds_hor_francoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); */
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_franco', null, array());
        return $this->render('index', ['idcontacto' => $idcontacto]);
    }

    public function actionRefresh_index($idcontacto = -1, $start = null, $end = null)
    {
        $francos_events = $this->getFrancoEvents($idcontacto, $start, $end);
        header('Content-type: application/json');
        ob_start();
        echo Json::encode($francos_events);
        return ob_get_clean();
    }

    /* 
        //TODO: COPIADO DE CONTACTOS (idcontacto desde, idcontacto hasta. Fechas desde y hasta)
        insert into mds_hor_franco
        select null,2,fecha,descripcion
        from mds_hor_franco where idcontacto=1 and month(fecha)=2 and year(fecha)=2021; 
    */
    private function getFrancoEvents($idcontacto = -1, $start = null, $end = null)
    {
        $francos = Mds_hor_franco::findBySql("select * from mds_hor_franco "
            . "where fecha >= '" . $start . "' and fecha<= '" . $end . "' 
            and (idcontacto=" . $idcontacto . " or " . $idcontacto . "=-1)")->all();
        $francos_events = [];
        foreach ($francos as $franco) {
            $tipo_franco = Sds_com_configuracion::findOne($franco->tipo);
            $event = new \yii2fullcalendar\models\Event();
            $event->id = $franco->idfranco;
            $color = '#446f8f';
            $event->backgroundColor = $color;
            $event->borderColor = $color;
            $event->title = ['tipo' => $tipo_franco->descripcion, 'descripcion' => $franco->descripcion];
            $event->allDay = true;
            $event->start = $franco->fecha;
            $event->end = $franco->fecha;
            $francos_events[] = $event;
        }
        return $francos_events;
    }

    /**
     * Displays a single Mds_hor_franco model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_franco', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_hor_franco #" . $id,
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

    public function actionClonar_francos($idcontacto, $mes, $anio)
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_franco();
        $model->idcontacto = $idcontacto;
        $model->mes_clonar = $mes;
        $model->anio_clonar = $anio;
        $request = Yii::$app->request;
        if ($model->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $guardado = true;
            $francos_clonar = Mds_hor_franco::findBySql("select * from mds_hor_franco where idcontacto=" . $model->idcontacto_clonar . " and 
                                month(fecha)=" . $model->mes_clonar . " and year(fecha)=" . $model->anio_clonar)->all();
            foreach ($francos_clonar as $franco) {
                //return json_encode($franco->getAttributes());
                $franco_clonar = new Mds_hor_franco();
                $franco_clonar->fecha = $franco->fecha;
                $franco_clonar->descripcion = $franco->descripcion;
                $franco_clonar->idcontacto = $model->idcontacto;
                $existente = Mds_hor_franco::find()->where(['idcontacto' => $franco_clonar->idcontacto, 'fecha' => $franco_clonar->fecha])->one();
                if ($existente == null) {
                    $guardado = $guardado && $franco_clonar->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_franco', $franco_clonar->idfranco, $franco_clonar->getAttributes());
                }
            }
            if ($guardado) {
                $transaction->commit();
                return 1;
            } else {
                $transaction->rollBack();
                $model->addError('idcontacto_clonar', 'Hubo un error y no pudieron clonarse todos los francos');
                return $this->renderAjax('//mds_hor_franco/clonar_francos', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->renderAjax('//mds_hor_franco/clonar_francos', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate_ext($idcontacto, $fecha)
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_franco();
        $model->idcontacto = $idcontacto;
        $model->fecha = $fecha;
        $tipos_franco = ArrayHelper::map(
            Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::FRANCO_TIPO, 'activo' => 1])->all(),
            'idconfiguracion',
            'descripcion'
        );
        $request = Yii::$app->request;
        if ($model->load($request->post())) {
            $model->fecha =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha)));
            $francos = Mds_hor_franco::findBySql(
                "SELECT * FROM mds_hor_franco WHERE (idcontacto=$model->idcontacto) AND (fecha = '$model->fecha')"
            )->all();
            $licencias = Mds_hor_licencia::findBySql(
                "SELECT * FROM `mds_hor_licencia` WHERE (idcontacto=$model->idcontacto) AND ('$model->fecha' BETWEEN desde AND hasta)"
            )->all();
            $fichadas = Mds_hor_registro::findBySql(
                "SELECT * FROM mds_hor_registro WHERE (idcontacto=$model->idcontacto) AND fecha like '%$model->fecha%'"
            )->all();
            if (empty($francos) && empty($licencias) && empty($fichadas)) {
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_franco', $model->idfranco, $model->getAttributes());
                    return $model->idfranco;
                } else {
                    return $this->renderAjax('//mds_hor_franco/create', [
                        'model' => $model,
                        'tipos_franco' => $tipos_franco,
                    ]);
                }
            } else {
                if (!empty($licencias)) {
                    $errors = 'El contacto registra licencia en la fecha ' . $fecha;
                }
                if (!empty($francos)) {
                    $errors = 'El contacto registra franco en la fecha ' . $fecha;
                }
                if (!empty($fichadas)) {
                    $url =  Url::to([
                        'index',
                        'idcontacto' => $model->idcontacto,
                        'desde' => str_replace('/', '-', $fecha),
                        'hasta' => str_replace('/', '-', $fecha)
                    ]);
                    $errors = 'El contacto registra ' . Html::a(
                        'fichadas',
                        $url,
                        ['target' => '_blank', 'title' => 'Ver Fichadas']
                    ) . ' en la fecha ' . $fecha;
                }
            }
            return $errors;
        }
        return $this->renderAjax('//mds_hor_franco/create', [
            'model' => $model,
            'tipos_franco' => $tipos_franco,
            'errors' => isset($errors) ? $errors : null
        ]);
    }

    public function actionUpdate_ext($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $tipos_franco = ArrayHelper::map(
            Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::FRANCO_TIPO, 'activo' => 1])->all(),
            'idconfiguracion',
            'descripcion'
        );
        if ($model->load($request->post())) {
            $model->fecha =  date('Y-m-d', strtotime(str_replace('/', '-', $model->fecha)));
            if ($model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_franco', $model->idfranco, $model->getAttributes());
                return $model->idfranco;
            } else {
                return $this->renderAjax('//mds_hor_franco/update', [
                    'model' => $model,
                    'tipos_franco' => $tipos_franco,
                    'errors' => $model->getErrors()
                ]);
            }
        } else {
            return $this->renderAjax('//mds_hor_franco/update', [
                'model' => $model,
                'tipos_franco' => $tipos_franco
            ]);
        }
    }
    /**
     * Creates a new Mds_hor_franco model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_franco();

        $tipos_franco = ArrayHelper::map(
            Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::FRANCO_TIPO, 'activo' => 1])->all(),
            'idconfiguracion',
            'descripcion'
        );

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_hor_franco",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'tipos_franco' => $tipos_franco
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_hor_franco",
                    'content' => '<span class="text-success">Create Mds_hor_franco success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_hor_franco",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'tipos_franco' => $tipos_franco
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_franco', $model->idfranco, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idfranco]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'tipos_franco' => $tipos_franco
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_hor_franco model.
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
                    'title' => "Update Mds_hor_franco #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_franco', $model->idfranco, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_hor_franco #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Guardar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_hor_franco #" . $id,
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
                return $this->redirect(['view', 'id' => $model->idfranco]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_hor_franco model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_hor_franco', $id, $model->getAttributes());
        }

        return $this->redirect(['index', 'idcontacto' => $model->idcontacto]);
    }

    /**
     * Delete multiple existing Mds_hor_franco model.
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
     * Finds the Mds_hor_franco model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_hor_franco the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_hor_franco::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSet_periodo()
    {
        $model = new Mds_hor_franco();
        $request = Yii::$app->request;
        //Variables para enviar al views los mensajes de exito/error:
        $save = ['with_fail' => [], 'not_fail' => []];
        $errors = [];
        //Si hubo submmit del form:
        if ($model->load($request->post())) {
            //Para manejar mejor las fechas creo objetos DateTime:
            $desde = new DateTime($model->desde);
            $hasta = new DateTime($model->hasta);
            //Verifico que la fecha Hasta sea posterior a Desde:
            if ($desde >= $hasta) {
                $model->addError("hasta", "La fecha 'Hasta' debe ser posterior a 'Desde'");
                return $this->render('set_periodo', [
                    'model' => $model
                ]);
            }
            //Proceso por cada contacto el periodo:
            foreach ($model->contactos as $id_contacto) {
                //Para contar los fallos por cada contacto
                $fail = 0;
                //Cargo los valores de $model para cada contacto
                $dias_laboral = $model->dias_laborales;
                $dias_franco = $model->dias_franco;
                $desde = new DateTime($model->desde);
                /*Realizo una trasaction para c/contacto para evitar la perdida de datos en caso de falla
                al guardarlos francos nuevos*/
                $transaction = Yii::$app->db->beginTransaction();
                $francos_existentes = Mds_hor_franco::find()
                    ->where([
                        'idcontacto' => $id_contacto,
                        'YEAR(fecha)' => $desde->format('Y'),
                        'MONTH(fecha)' => $desde->format('m')
                    ])->all();
                foreach ($francos_existentes as $franco) {
                    $franco->delete();
                }
                //Durante el perido:
                while ($desde <= $hasta) {
                    //Hago una cuenta regresiva con los días laborales
                    if ($dias_laboral == 0) {
                        /*En caso de haber llegado a 0 con los días laborales 
                        hago cuenta regresiva con los días de franco*/
                        if ($dias_franco != 0) {
                            $franco = new Mds_hor_franco();
                            $franco->idcontacto = $id_contacto;
                            $franco->fecha = $desde->format('Y-m-d');
                            $franco->descripcion = "Cronograma " . $model->dias_laborales . "x" . $model->dias_franco . " de " . $model->desde . " a " . $model->hasta . ". " . $model->descripcion;
                            $franco->tipo = 4436;
                            //Si falla el guardado del franco cargo el mensaje de error
                            if (!$franco->save()) {
                                $fail++;
                                array_push($errors, [
                                    'contacto' => $id_contacto,
                                    'msg' => 'Falló la carga del franco del día ' . $desde->format('d-m-Y') . '.'
                                ]);
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_franco', $franco->idfranco, $franco->getAttributes());
                            }
                            $dias_franco--;
                            $dias_laboral++;
                        } else {
                            $dias_laboral = $model->dias_laborales;
                            $dias_franco = $model->dias_franco;
                        }
                    }
                    $dias_laboral--;
                    //Sumo un día a desde para 'avanzar en el calendario'
                    date_add($desde, date_interval_create_from_date_string("1 days"));
                }
                //Si no hubo fallas al cargar los francos del contacto:
                if ($fail == 0) {
                    array_push($save['not_fail'], $id_contacto);
                    $transaction->commit();
                } else {
                    array_push($save['with_fail'], $id_contacto);
                    $transaction->rollBack();
                }
            }
            if (empty($save['with_fail'])) {
                $model = new Mds_hor_franco();
            }
        }
        return $this->render('set_periodo', [
            'model' => $model,
            'save' => $save,
            'errors' => $errors
        ]);
    }

    public function actionExportar_francos()
    {
        return $this->render('exportar_francos');
    }

    public function actionXls_francos($desde, $hasta, $tipo)
    {

        $desde = $desde ? ArmarDateParaMySql($desde) : '1970-01-01';
        $hasta = $hasta ? ArmarDateParaMySql($hasta) : date('Y-m-d');
        $tipo = $tipo ? " AND tipo = $tipo" : '';

        $mysql = "SELECT * FROM view_franco WHERE fecha BETWEEN '$desde' AND '$hasta' $tipo";


        $array_francos = array();
        Yii::$app->response->format = Response::FORMAT_JSON;

        $francos = View_franco::findBySql($mysql)->all();

        //return $mysql;

        foreach ($francos as $f) {
            $franco = array(
                "idfranco" => $f->idfranco,
                "fecha" => $f->fecha,
                "anio" => $f->anio,
                "idcontacto" => $f->idcontacto,
                "legajo" => $f->legajo,
                "documento" => $f->documento,
                "nombre" => $f->nombre,
                "apellido" => $f->apellido,
                "dispositivo" => $f->dispositivo,
                "organismo" => $f->organismo,
                "tipo" => $f->tipo,
                "tipo_descripcion" => $f->tipo_descripcion,
                "descripcion" => $f->descripcion
            );
            array_push($array_francos, $franco);
        }
        return $array_francos;
    }
}
function ArmarDateParaMySql($Fecha)
{
    $anio = substr($Fecha, 6, 4);
    $mes = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
