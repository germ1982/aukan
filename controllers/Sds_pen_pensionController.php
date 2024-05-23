<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Sds_com_barrio;
use Yii;
use app\models\Sds_pen_pension;
use app\models\Sds_pen_pensionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Sds_com_persona;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use yii\helpers\ArrayHelper;

/**
 * Sds_pen_pensionController implements the CRUD actions for Sds_pen_pension model.
 */
class Sds_pen_pensionController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'view', 'logout',
                    'validar_dni', 'cmb_barrio',
                    'imprimir_informe_personal', 'imprimir_informe'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'delete', 'update', 'view', 'logout',
                            'validar_dni', 'cmb_barrio',
                            'imprimir_informe_personal', 'imprimir_informe'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_PEN_PENSION,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_pen_pension models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_pen_pensionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_pen_pension', null, array());
        $filters = $this->filters();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filters' => $filters
        ]);
    }

    protected function filters()
    {
        $filters = [
            'causa_baja' => ArrayHelper::map(
                Sds_com_configuracion::findBySql(
                    "SELECT c.*
                    FROM sds_pen_pension p
                    JOIN sds_com_configuracion c ON p.causa_baja=c.idconfiguracion
                    WHERE c.activo = 1
                    GROUP BY p.causa_baja
                    ORDER BY c.descripcion"
                )->all(),
                'idconfiguracion',
                'descripcion'
            ),
            'estado' => ArrayHelper::map(
                Sds_com_configuracion::findBySql(
                    "SELECT c.*
                    FROM sds_pen_pension p
                    JOIN sds_com_configuracion c ON p.estado=c.idconfiguracion
                    WHERE c.activo = 1
                    GROUP BY p.estado"
                )->all(),
                'idconfiguracion',
                'descripcion'
            ),
            'programa' => ArrayHelper::map(
                Sds_com_configuracion::findBySql(
                    "SELECT c.*
                    FROM sds_pen_pension p
                    JOIN sds_com_configuracion c ON p.programa=c.idconfiguracion
                    WHERE c.activo = 1
                    GROUP BY p.programa"
                )->all(),
                'idconfiguracion',
                'descripcion'
            ),
            'pensionado' => ArrayHelper::map(
                Sds_com_persona::findBySql(
                    "SELECT pers.idpersona, UPPER(CONCAT(pers.apellido,', ', pers.nombre)) nombre
                    FROM sds_pen_pension p
                    JOIN sds_com_persona pers ON p.idpersona=pers.idpersona
                    GROUP BY pers.idpersona
                    ORDER BY pers.apellido ASC"
                )->all(),
                'idpersona',
                'nombre'
            ),
        ];
        return $filters;
    }

    /**
     * Displays a single Sds_pen_pension model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_pen_pension', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Pension Numero " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_pen_pension model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_pen_pension();

        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Pension",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $aux = "";
                //validaciones de las fechas
                if ($model->fecha_carga) {
                    $fecha_registro = ArmarDateParaMySql($model->fecha_carga, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model->fecha_carga =  $fecha_registro;
                }

                if ($model->fecha_otorgado) {
                    $fecha_registro = ArmarDateParaMySql($model->fecha_otorgado, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model->fecha_otorgado =  $fecha_registro;
                }

                if ($model->fecha_baja) {
                    $fecha_registro = ArmarDateParaMySql($model->fecha_baja, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model->fecha_baja =  $fecha_registro;
                }
                $ban_log_persona = 0;
                if ($model->idpersona == 0) {
                    $model_com_persona = new Sds_com_persona();
                    $model_com_persona->documento_tipo = $model->documento_tipo;
                    if ($model->fecha_nacimiento) {
                        $fecha_registro = ArmarDateParaMySql($model->fecha_nacimiento, '00:00');
                        $fecha_registro = date_create($fecha_registro);
                        $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                        $model_com_persona->fecha_nacimiento =  $fecha_registro;
                    }
                    $model_com_persona->documento = $model->documento;
                    $model_com_persona->nacionalidad = $model->nacionalidad;
                    $model_com_persona->genero = $model->genero;
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    $model_com_persona->conviviente = 0;
                    if (!$model_com_persona->save()) {
                        $aux = "Entro en save false";
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        $aux = "Entro en save true id persona :$model_com_persona->idpersona fecha naciiento: $model_com_persona->fecha_nacimiento";
                        $ban_log_persona = 1;
                        $model->idpersona = $model_com_persona->idpersona;
                    }
                }
                if ($guardado && $model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_pen_pension', $model->idpension, $model->getAttributes());
                    if ($ban_log_persona == 1) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model->getAttributes());
                    }
                    return [
                        'title' => "Pensiones ley 809",
                        'content' => '<span class="text-success">Pension Creada Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Nueva Pension Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else {

            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_pen_pension', $model->idpension, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idpension]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_pen_pension model.
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
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Pension numero " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $aux = "";

                if ($model->fecha_carga) {
                    $fecha_registro = ArmarDateParaMySql($model->fecha_carga, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model->fecha_carga =  $fecha_registro;
                }

                if ($model->fecha_otorgado) {
                    $fecha_registro = ArmarDateParaMySql($model->fecha_otorgado, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model->fecha_otorgado =  $fecha_registro;
                }

                if ($model->fecha_baja) {
                    $fecha_registro = ArmarDateParaMySql($model->fecha_baja, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model->fecha_baja =  $fecha_registro;
                }



                if ($model->idpersona == 0) {
                    $model_com_persona = new Sds_com_persona();
                    $model_com_persona->documento_tipo = $model->documento_tipo;
                    $fecha_registro = ArmarDateParaMySql($model->fecha_nacimiento, '00:00');
                    $fecha_registro = date_create($fecha_registro);
                    $fecha_registro = date_format($fecha_registro, 'Y-m-d');
                    $model_com_persona->fecha_nacimiento =  $fecha_registro;
                    $model_com_persona->documento = $model->documento;
                    $model_com_persona->nacionalidad = $model->nacionalidad;
                    $model_com_persona->genero = $model->genero;
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    if (!$model_com_persona->save(false)) {
                        //$aux = "Entro en save false";
                        $guardado = false;
                        $transaction->rollBack();
                    } else {
                        //$aux = "Entro en save true id persona :$model_com_persona->idpersona fecha naciiento: $model_com_persona->fecha_nacimiento";
                        $model->idpersona = $model_com_persona->idpersona;
                    }
                }

                if ($guardado && $model->save(false)) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_pen_pension', $model->idpension, $model->getAttributes());
                    return [
                        'title' => "Pensiones ley 809",
                        'content' => '<span class="text-success">Pension Editada Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Vista', ['view', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            /* else if($model->load($request->post()) && $model->save())
                    {
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Sds_pen_pension #".$id,
                            'content'=>$this->renderAjax('view', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        ];    
                    } */ else {
                return [
                    'title' => "Editar Pension numero " . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_pen_pension', $model->idpension, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idpension]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_pen_pension model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_pen_pension', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_pen_pension model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /* public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) { */
    /*
            *   Process for ajax request
            */
    /*  Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else { */
    /*
            *   Process for non-ajax request
            */
    /* return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Sds_pen_pension model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_pen_pension the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_pen_pension::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidar_dni($dni_persona)
    {
        $result = array();
        $model_persona = Sds_com_persona::find()->where(["documento" => $dni_persona])->one();
        if ($model_persona != null) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_pen_pension/validar_dni', $dni_persona, array());
            array_push($result, $model_persona->getAttributes());

            $contacto = Mds_org_contacto::find()->where(["idpersona" => $model_persona->idpersona])->one();
            if ($contacto != null) {
                array_push($result, $contacto->getAttributes());
            }
        }
        return json_encode($result);
    }
    public function actionCmb_barrio($idlocalidad)
    {
        $barrios = Sds_com_barrio::find()->where(["idlocalidad" => $idlocalidad])->orderBy(['nombre' => SORT_ASC])->all();
        $cmbbarrios = "";
        if (sizeof($barrios) > 0) {
            foreach ($barrios as $barrio) {
                $cmbbarrios = $cmbbarrios . "<option value='" . $barrio->idbarrio . "'>" . $barrio->nombre . "</option>";
            }
        } else {
            $cmbbarrios = "<option value=null></option>";
        }
        return $cmbbarrios;
    }
    public function actionImprimir_informe_personal($idpension)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_pen_pension/informe_personal', $idpension, array());
        $content = $this->renderPartial('imprimir_informe_personal', ['idpension' => $idpension]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'INFORME DE PENSIONADO LEY 809',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }
    public function actionImprimir_informe($php, $idlocalidad, $idbarrio, $programa, $estado, $titulo)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_pen_pension/imprimir_informe', null, array());
        $content = $this->renderPartial($php, [
            'idlocalidad' => $idlocalidad,
            'idbarrio' => $idbarrio,
            'programa' => $programa,
            'estado' => $estado,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => $titulo,
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }
}
function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}
