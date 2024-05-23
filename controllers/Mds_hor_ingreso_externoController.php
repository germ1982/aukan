<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_ingreso_externo;
use app\models\Mds_hor_ingreso_externoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Sds_com_persona;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use yii\base\Model;
use yii\db\Transaction;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class Mds_hor_ingreso_externoController extends Controller
{

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
                    'index', 'create', 'update', 'delete','bulk-delete', 'view', 'logout',
                    'aceptar_externos', 'recepciones_pendientes', 'validar_dni','grilla_ingresos'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete','bulk-delete', 'update', 'view',
                            'logout', 'aceptar_externos', 'validar_dni'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::INGRESO_EXTERNOS,
                        ],
                    ],
                    [
                        'actions' => ['recepciones_pendientes'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::ING_EXT_PENDIENTES,
                        ],
                    ],
                    [
                        'actions' => ['grilla_ingresos'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new Mds_hor_ingreso_externoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_ingreso_externo', null, array());
        $filter = $this->filter();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ]);
    }

    protected function filter($all = false)
    {
        /* if($all){
            $dataContacto=Mds_org_contacto::findBySql(
                "SELECT * FROM mds_org_contacto c 
                JOIN sds_com_persona p ON p.idpersona=c.idpersona 
                WHERE c.idcontacto IN (SELECT idcontacto FROM mds_hor_ingreso_externo) ORDER BY nombre ASC, apellido ASC;")->all();
        }else{
           $dataMarca=Sds_com_configuracion::findBySql(
                "SELECT c.* FROM sds_veh_vehiculo v
                JOIN sds_veh_modelo vm ON v.modelo=vm.idmodelo 
                JOIN sds_com_configuracion c ON vm.idmarca=c.idconfiguracion"
            )->all(); 
        } */

        $filter = [
            'contacto' => ArrayHelper::map(
                Mds_org_contacto::findBySql(
                    "SELECT * FROM mds_org_contacto c 
                    JOIN sds_com_persona p ON p.idpersona=c.idpersona 
                    WHERE c.idcontacto IN (SELECT idcontacto FROM mds_hor_ingreso_externo) ORDER BY nombre ASC, apellido ASC;"
                )->all(),
                'idpersona',
                function ($model) {
                    return $model->nombre . " " . $model->apellido;
                }
            ),
            'persona' => ArrayHelper::map(
                Sds_com_persona::find()->where("idpersona in (select idpersona from mds_hor_ingreso_externo)")->orderBy(['apellido' => SORT_ASC, 'nombre' => SORT_ASC])->all(),
                'idpersona',
                function ($model) {
                    return "$model->apellido, $model->nombre";
                }
            )
        ];
        return $filter;
    }


    public function actionView($id)
    {

        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_ingreso_externo', $id, array());
        $model = $this->findModel($id);
        $model->fecha_hora = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $model->fecha_hora)));


        if ($model->idpersona != null) {
            $persona = Sds_com_persona::findOne($model->idpersona);
        } else {
            $persona = new Sds_com_persona();
        }


        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Ingreso Externo",
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'persona' => $persona,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-primary pull-right', 'data-dismiss' => "modal"])

            ];
        } else {
            return $this->render('view', [
                'model' => $model,
                'persona' =>  $persona,

            ]);
        }
    }


    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_ingreso_externo();
        $model_persona = new Sds_com_persona();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                //echo "<script>alert('idpersona: $model->idpersona');</script>";

                /* $fecha_registro = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d H:i'); */

                $model_persona->idpersona = $request->post('Sds_com_persona')['idpersona'];
                if ($model_persona->idpersona != null && $model_persona->idpersona != 0) {
                    $model_persona = Sds_com_persona::findOne($model_persona->idpersona);
                }

                $f_nac = $request->post('Sds_com_persona')['fecha_nacimiento'];
                $f_nac = date('Y-m-d', strtotime(str_replace('/', '-', $f_nac)));

                $model_persona->documento = $request->post('Sds_com_persona')['documento'];
                $model_persona->apellido = $request->post('Sds_com_persona')['apellido'];
                $model_persona->genero = $request->post('Sds_com_persona')['genero'];
                $model_persona->nombre = $request->post('Sds_com_persona')['nombre'];
                $model_persona->documento_tipo = $request->post('Sds_com_persona')['documento_tipo'];
                $model_persona->nacionalidad = $request->post('Sds_com_persona')['nacionalidad'];
                $model_persona->fecha_nacimiento = $f_nac;
                $model_persona->conviviente = 0;
                $model->sector = $request->post('Mds_hor_ingreso_externo')['sector'];
                $model->observaciones = $request->post('Mds_hor_ingreso_externo')['observaciones'];
                $model->motivo = $request->post('Mds_hor_ingreso_externo')['motivo'];
                $model->motivo = $model->motivo == null ? 1 : $model->motivo;
                $model->fecha_hora = $request->post('Mds_hor_ingreso_externo')['fecha_hora'];
                $model->fecha_hora = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $model->fecha_hora)));

                //verificador de errores
                if (!$model_persona->save()) {
                    $guardado = false;                                           // solucionado el 11-10-22 PARA FRANCO
                    $transaction->rollBack();                                    //... se deshace el proceso y se envía hacía atras. 
                }

                $model->idpersona = $model_persona->idpersona;

                if ($guardado) {
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_hor_ingreso_externo', $model->idingresoexterno, $model->getAttributes());
                        $transaction->commit();
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "<b>¡Excelente!</b>",
                            'content' => '<span class="text-success">Ingreso Externo Creado Correctamente</span>',
                            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
                        ];
                    }
                } else {

                    return [

                        'title' => "Nuevo Ingreso Externo",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'model_persona' => $model_persona
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }

            return [
                'title' => "Nuevo Ingreso Externo",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else {
            if ($model->load($request->post())) {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->idingresoexterno]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                ]);
            }
        }
    }

    public function actionRecepciones_pendientes()
    {
        $searchModel = new Mds_hor_ingreso_externoSearch();
        $searchModel->idcontacto = -1;
        $searchModel->fecha_hora_ingreso = -1;
        $searchModel->idorganismo = Yii::$app->user->identity->organismo_stock;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model_persona = new Sds_com_persona();


        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Nuevo Ingreso Externo",
                'content' => $this->renderAjax('recepciones_pendientes', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model_persona' => $model_persona,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }
        if ($searchModel->fecha_hora_ingreso != null && $searchModel->idcontacto != null) {
            $guardado = true;
        }

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_ingreso_externo', null, array());

        return $this->render('recepciones_pendientes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        //return $this->render("recepciones_pendientes");
    }


    public function actionAceptar_externo($id = null, $aceptar = 1)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($aceptar == 1) {
                if ($model->load($request->post())) {

                    $model->fecha_hora_ingreso = date('Y-m-d H:i:s');
                    if ($model->save()) {

                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_ingreso_externo', $model->idingresoexterno, $model->getAttributes());
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "<b>¡Excelente!</b>",
                            'content' => '<span class="text-success">Ingreso Aceptado con exito</span>',
                            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"]),

                        ];
                    }
                }
                return [
                    'title' => "Aceptar ingreso de Externo",
                    'content' => $this->renderAjax('_form_aceptar_externo', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Confirmar', ['id' => 'btnGuardar', 'class' => 'btn btn-success', 'type' => "submit",])
                ];
            } else {
                if ($model->load($request->post())) {
                    $model->fecha_hora_ingreso = date('Y-m-d H:i:s');
                    if ($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_ingreso_externo', $model->idingresoexterno, $model->getAttributes());
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "<b>¡Excelente!</b>",
                            'content' => '<span class="text-danger">Ingreso Rechazado con exito</span>',
                            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
                        ];
                    }
                }
                return [

                    'title' => "Rechazar ingreso de Externo",
                    'content' => $this->renderAjax('_form_rechazar_externo', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cancelar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Confirmar', ['id' => 'btnGuardar', 'class' => 'btn btn-danger', 'type' => "submit"])
                ];
            }
        }
    }




    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model_persona = Sds_com_persona::findOne($model->idpersona,);


        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Ingreso Externo",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'model_persona' => $model_persona,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                // $fecha_registro = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                // $fecha_registro = date_create($fecha_registro);
                // $fecha_registro = date_format($fecha_registro, 'Y-m-d H:i');
                // $model->fecha_hora = $fecha_registro;

                if ($model->idpersona == 0) {
                    $model_persona = new Sds_com_persona();
                    $model_persona->documento_tipo = $model->documento_tipo;
                    $fecha_registro = ArmarDateParaMySql($model->fecha_nacimiento, $model->hora);
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'd-m-Y');
                    $model_persona->fecha_nacimiento =  $fecha_registro;
                    $model_persona->documento = $model->dni;
                    $model_persona->nacionalidad = $model->nacionalidad;
                    $model_persona->genero = $model->genero;
                    $model_persona->nombre = $model->nombre;
                    $model_persona->apellido = $model->apellido;
                    $model_persona->conviviente = 0;
                    if (!$model_persona->save(false)) {
                        //$aux = "Entro en save false";
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        //$aux = "Entro en save true id persona :$model_com_persona->idpersona fecha naciiento: $model_com_persona->fecha_nacimiento";
                        $model->idpersona = $model_persona->idpersona;
                    }
                }

                if ($guardado && $model->save(false)) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_ingreso_externo', $model->idingresoexterno, $model->getAttributes());
                    $transaction->commit();
                    return [
                        'title' => "Editar Ingreso Externo",
                        'content' => '<span class="text-success">Ingreso Externo Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])

                    ];
                }
            } else {
                return [
                    'title' => "Editar Ingreso Externo ",
                    'content' => $this->renderAjax('update', [
                        'model' => $model = $this->findModel($id),
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {

            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idingresoexterno]);
            } else {
                return $this->render('update', [
                    'model' => $model = $this->findModel($id),
                ]);
            }
        }
    }


    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_hor_ingreso_externo', $id, $model->getAttributes());
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


    protected function findModel($id)
    {
        if (($model = Mds_hor_ingreso_externo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGrilla_ingresos($idpersona)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Mds_hor_ingreso_externo::findBySql("SELECT * FROM mds_hor_ingreso_externo WHERE idpersona = $idpersona ORDER BY fecha_hora DESC LIMIT 10"),

        ]);
        return GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => '',
            'id' => 'grilla_movimientos',
            'columns' => [

                [
                    'attribute' => 'fecha_hora',
                    'label' => 'Fecha de Ingreso',
                    'value' => function ($model) {
                        if ($model->fecha_hora != null) {
                            $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));
                            return "$fecha";
                        }
                        return "";
                    },
                ],
                [
                    'attribute' => 'fecha_hora',
                    'label' => 'Hora de Ingreso',
                    'value' => function ($model) {
                        if ($model->fecha_hora != null) {
                            $hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha_hora)));
                            return "$hora";
                        }
                        return "";
                    },
                ],
                [
                    'attribute' => 'idcontacto',
                    'label' => 'Contacto Responsable',
                    'value' => function ($model) {
                        if ($model->idcontacto != null) {
                            $contacto = Mds_org_contacto::findOne($model->idcontacto);
                            $persona = Sds_com_persona::findOne($contacto->idpersona);
                            return "$persona->apellido, $persona->nombre";
                        }
                        return "";
                    },
                ],
                [
                    'attribute' => 'observaciones',
                ],
            ],
        ]);
    }




    public function actionValidar_dni($dni_persona)
    {
        $result = array();
        $model_persona = Sds_com_persona::find()->where(["documento" => $dni_persona])->one();
        if ($model_persona != null) {
            array_push($result, $model_persona->getAttributes());
        }
        return json_encode($result);
    }
}

function ArmarDateParaMySql($Fecha, $Hora)

{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$dia/$mes/$anio $H:$m:00";
    return $DT;
}
