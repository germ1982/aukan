<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_certificacion;
use app\models\Mds_hor_certificacionSearch;
use app\models\Mds_hor_feriado;
use app\models\Mds_hor_registro;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Sds_com_persona;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;

/**
 * Mds_hor_certificacionController implements the CRUD actions for Mds_hor_certificacion model.
 */
class Mds_hor_certificacionController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout', 'generar'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'bulk-delete', 'view', 'logout', 'generar'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_CERTIFICACION,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_certificacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_hor_certificacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_hor_certificacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificacion Numero " . $id,
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
     * Creates a new Mds_hor_certificacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_certificacion();
        $contactos = ArrayHelper::map(
            Mds_org_contacto::findBySql("SELECT * FROM mds_org_contacto c
                JOIN sds_com_persona p ON p.idpersona=c.idpersona
                ORDER BY trim(p.nombre), trim(p.apellido)")->all(),
            'idcontacto',
            function ($model) {
                return $model->legajo . " - " . $model->apellido . ", " . $model->nombre;
            }
        );

        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $model = $this->save_certificacion($model, $request);
            }
            return [
                'title' => "Nueva Certificación",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'contactos' => $contactos
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit'])
            ];
        } else {
            /* Process for non-ajax request */
            if ($model->load($request->post())) {
                $model = $this->save_certificacion($model, $request);
                return Yii::$app->getResponse()->redirect([
                    'mds_hor_certificacion/create',
                    'model' => $model
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Persistencia del modelo en la BD.
     * Valida si existen certifiaciones para el contacto seleccionado en el periodo seleccionado.
     * En caso de exito devuelve un modelo nuevo (new Mds_hor_certificacion) si la operacion no es un update con sus respectivos mensajes.
     * En caso de error devuelve el mismo modelo de parametro con sus respectivos mensajes.
     * @param Mds_hor_certificacion $model
     * @param Yii::$app/request $request
     * @param boolean $update (defect false)
     * @return $model
     */
    private function save_certificacion($model, $request, $update = false)
    {
        if ($model->desde >= $model->hasta) {
            $model->addError("desde", "el horario de ingreso debe ser menor al horario de salida");
        } else {
            $meses = explode(',', $model->periodo_mes);
            $save_ok = '<ul>';
            $exist_fichada = '<ul>';
            foreach ($meses as $periodo) {
                $full_periodo = explode('/', $periodo);
                $periodo_mes = $full_periodo[0];
                $periodo_anio = $full_periodo[1];
                $model->periodo_mes = $periodo_mes;
                $model->periodo_anio = $periodo_anio;
                /* $getFichadas=Mds_hor_registro::find()->where(['MONTH(fecha)'=>$periodo_mes, 'YEAR(fecha)'=>$periodo_anio, 
                'idcontacto'=>$model->certificado])->all(); */
                $getCertificacion = Mds_hor_certificacion::find()->where([
                    'certificado' => $model->certificado,
                    'periodo_mes' => $periodo_mes,
                    'periodo_anio' => $periodo_anio
                ])
                    ->andWhere($model->idcertificacion != null ? "idcertificacion <> $model->idcertificacion" : "")
                    ->one();
                if ($getCertificacion != null) {
                    $exist_fichada .= '<li>El contacto registra una certificación en el periodo ' . $periodo_mes . '/' . $periodo_anio . ' 
                    (' . $getCertificacion->desde . ' hrs. a ' . $getCertificacion->hasta . ' hrs.)</li>';
                } else {
                    //if($getFichadas==null){ Desestimar si es necesario
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_certificacion', $model->idcertificacion, $model->getAttributes());
                        $save_ok .= '<li>La certificación del periodo ' . $periodo_mes . '/' . $periodo_anio . ' se guardó de manera correcta</li>';
                        $model = new Mds_hor_certificacion();
                        $model->load($request->post());
                    } else {
                        $exist_fichada .= '<li>El sistema falló al guardar la certificación del periodo ' . $periodo_mes . '/' . $periodo_anio . '</li>';
                    }
                    /*
                    }else{
                        $exist_fichada.='<li>El contacto a certificar registra fichadas en el periodo '.$periodo_mes.'/'.$periodo_anio.' -La certificación no fue generada-</li>';
                    }
                    */
                }
            }

            $exist_fichada .= '</ul>';
            $save_ok .= '</ul>';
            if ($exist_fichada != '<ul></ul>') {
                Yii::$app->session->setFlash('warning', $exist_fichada);
            }
            if ($save_ok != '<ul></ul>') {
                Yii::$app->session->setFlash('save', $save_ok);
                if ($model->reset_form) {
                    $model = new Mds_hor_certificacion();
                    $model->reset_form = 1;
                }
            }
            /* if(!$update){
                $model = new Mds_hor_certificacion();
            } */
            return $model;
            //$model->periodo_mes=$request->post('Mds_hor_certificacion')['periodo_mes'];
        }
    }

    /**
     * Updates an existing Mds_hor_certificacion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $contactos = ArrayHelper::map(
            Mds_org_contacto::findBySql("SELECT * FROM mds_org_contacto c
                JOIN sds_com_persona p ON p.idpersona=c.idpersona
                ORDER BY trim(p.nombre), trim(p.apellido)")->all(),
            'idcontacto',
            function ($model) {
                return $model->legajo . " - " . $model->apellido . ", " . $model->nombre;
            }
        );

        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                $ban = $this->save_certificacion($model, $request, true);
                if ($ban->idcertificacion == null) {
                    if ($model->estado == 1) {
                        $transaction = Yii::$app->db->beginTransaction();
                        Yii::$app->db->createCommand("DELETE FROM mds_hor_registro WHERE idcertificacion=:paramName1")
                            ->bindValue(':paramName1', $model->idcertificacion)
                            ->execute();
                        return $this->actionGenerar($model->idcertificacion, $transaction);
                    }
                    Yii::$app->session->setFlash('save', 'La certificación se actualizó de manera correcta.');
                }
            }
            return [
                'title' => "Editar Certificacion #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'contactos' => $contactos
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /* Process for non-ajax request */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcertificacion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'contactos' => $contactos
                ]);
            }
        }
    }

    public function actionGenerar($id, $in_transaction = null)
    {
        $model = $this->findModel($id);
        $nombre_mes = get_mes($model->periodo_mes);
        $fecha = "$model->periodo_anio-$model->periodo_mes-01"; //esta fecha es de refeencia para establecer el periodo segun el mes y el año
        $cantidad_dias = date('t', strtotime($fecha)); //con la fecha de referencia traemos la cantidad de dias de ese periodo segun el mes y año

        $model_contacto = Mds_org_contacto::findOne($model->certificado);
        $model_persona = Sds_com_persona::findOne($model_contacto->idpersona);
        $certificado = "$model_persona->apellido, $model_persona->nombre";
        $model_contacto = Mds_org_contacto::findOne($model->certificante);
        $model_persona = Sds_com_persona::findOne($model_contacto->idpersona);
        $certificante = "$model_persona->apellido, $model_persona->nombre";
        $aux = "Se ha generado la certificacion de: $certificado <br>Responsable: $certificante <br>Periodo: $nombre_mes del $model->periodo_anio";
        $aux = $aux . '<div class="col-md-10 col-md-offset-1" style="margin-top:37px;">
        <table border="0" cellpadding="1" cellspacing="1" class="body table table-hover" >';
        $transaction = $in_transaction != null ? $in_transaction : Yii::$app->db->beginTransaction();
        $exito = true;
        for ($i = 1; $i <= $cantidad_dias; $i++) {
            $ban = 1;
            $fecha = "$model->periodo_anio-$model->periodo_mes-$i";
            $fecha = date("Y-m-d", strtotime($fecha));
            $nombre_dia = get_dia(date('l', strtotime($fecha)));
            if ($nombre_dia == "Domingo") {
                $ban = 0;
            }
            if ($nombre_dia == "Sabado") {
                $ban = 0;
            }
            $fichadas = Mds_hor_registro::findBySql(
                "SELECT * FROM mdsyt.mds_hor_registro WHERE idcontacto=$model->certificado AND fecha like '%$fecha%'"
            )->all();
            if ($fichadas != null) {
                $ban = 0;
                $aux = "$aux <tr><td><span class='text-warning'>El día $nombre_dia $i registra fichadas.</span></td>";
            }
            $sql = "SELECT * FROM mds_hor_feriado WHERE fecha = '$fecha'";
            $model_feriado = Mds_hor_feriado::findBySql("$sql")->one();
            if ($model_feriado) {
                $ban = 0;
            }
            if ($ban == 1) {
                $fecha_registro_ingreso = "$fecha $model->desde";
                $fecha_registro_egreso = "$fecha $model->hasta";
                $observacion = "Certificado por $certificante. $model->detalle";
                $model_registro = new Mds_hor_registro();
                $model_registro->idcontacto = $model->certificado;
                $model_registro->fecha = $fecha_registro_ingreso;
                $model_registro->origen = 1;
                $model_registro->observaciones = $observacion;
                $model_registro->idcertificacion = $model->idcertificacion;
                if ($model_registro->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $model_registro->idregistrohorario, $model_registro->getAttributes());
                } else {
                    $exito = false;
                }


                $model_registro = new Mds_hor_registro();
                $model_registro->idcontacto = $model->certificado;
                $model_registro->fecha = $fecha_registro_egreso;
                $model_registro->origen = 1;
                $model_registro->observaciones = $observacion;
                $model_registro->idcertificacion = $model->idcertificacion;
                if ($model_registro->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_registro', $model_registro->idregistrohorario, $model_registro->getAttributes());
                } else {
                    $exito = false;
                }

                if (!$exito) {
                    $aux = "$aux <tr><td>¡ERROR! NO SE PUDO GUARDAR EL DÍA: $nombre_dia $i</td>";
                    $aux = "$aux <td>Ingreso: " . date("H:i", strtotime($model->desde)) . "</td>";
                    $aux = "$aux <td>Egreso : " . date("H:i", strtotime($model->hasta)) . "</td></tr>";
                    break;
                } else {
                    $aux = "$aux <tr><td>$nombre_dia $i</td>";
                    $aux = "$aux <td>Ingreso: " . date("H:i", strtotime($model->desde)) . "</td>";
                    $aux = "$aux <td>Egreso : " . date("H:i", strtotime($model->hasta)) . "</td></tr>";
                }
            }
        }
        $aux = "$aux </table></div><div class='col-md-5'></div><br><br>";
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($exito) {
            $model->estado = 1;
            if ($model->save(false)) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } else {
            return [
                'title' => "Certificación #" . $id,
                'content' => $aux,
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
        Yii::$app->session->getFlash('save');
        return
            [
                'title' => "Certificacion #" . $id,
                'content' => $aux,
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'style' => 'margin-top:15px;', 'data-dismiss' => "modal"])
            ];
    }

    /**
     * Delete an existing Mds_hor_certificacion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->estado == 1) {
                $delete = Yii::$app->db->createCommand("DELETE FROM mds_hor_registro WHERE idcertificacion=:paramName1")
                    ->bindValue(':paramName1', $model->idcertificacion)
                    ->execute();
                if ($model->delete() && $delete > 1) {
                    $transaction->commit();
                    return [
                        'title' => "Certificación #" . $id,
                        'content' => "<span class='text-success'>La certificación y sus registros horarios ($delete) han sido eliminados.</span>",
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                if ($model->delete()) {
                    $transaction->commit();
                    return [
                        'title' => "Certificación #" . $id,
                        'content' => "<span class='text-success'>La certificación ha sido eliminada.",
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
        }
    }

    /**
     * Delete multiple existing Mds_hor_certificacion model.
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
     * Finds the Mds_hor_certificacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_hor_certificacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_hor_certificacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

function get_dia($day)
{
    switch ($day) {
        case "Sunday":
            $dia = "Domingo";
            break;
        case "Monday":
            $dia = "Lunes";
            break;
        case "Tuesday":
            $dia = "Martes";
            break;
        case "Wednesday":
            $dia = "Miercoles";
            break;
        case "Thursday":
            $dia = "Jueves";
            break;
        case "Friday":
            $dia = "Viernes";
            break;
        case "Saturday":
            $dia = "Sabado";
            break;
    }
    return $dia;
}

function get_mes($mes)
{
    switch ($mes) {
        case "1":
            $mes = "Enero";
            break;

        case "2":
            $mes =  "Febrero";
            break;

        case "3":
            $mes =  "Marzo";
            break;

        case "4":
            $mes =  "Abril";
            break;
        case "5":
            $mes = "Mayo";
            break;

        case "6":
            $mes =  "Junio";
            break;

        case "7":
            $mes =  "Julio";
            break;

        case "8":
            $mes =  "Agosto";
            break;
        case "9":
            $mes = "Septiembre";
            break;

        case "10":
            $mes =  "Octubre";
            break;

        case "11":
            $mes =  "Noviembre";
            break;

        case "12":
            $mes =  "Diciembre";
            break;
    }
    return $mes;
}
