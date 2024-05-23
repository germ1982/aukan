<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_asistencia_reporte;
use app\models\Mds_hor_asistencia_reporteSearch;
use app\models\Mds_hor_registro;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Mds_hor_asistencia_reporteController implements the CRUD actions for Mds_hor_asistencia_reporte model.
 */
class Mds_hor_asistencia_reporteController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout', 'reporte_asistencia'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_REPORTE,
                        ],
                    ],
                    [
                        'actions' => ['reporte_asistencia', 'reporte_asistencia'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_asistencia_reporte models.
     * @return mixed
     */
    public function actionIndex($inasistencias = 0)
    {
        $searchModel = new Mds_hor_asistencia_reporteSearch();
        $searchModel->inasistencias = $inasistencias;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_asistencia_reporte&inasistencias=' . $inasistencias, null, "");
        return $this->render('index' . ($inasistencias == 0 ? '' : '_in'), [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionXls_todos($periodo)
    {
        $searchModel = new Mds_hor_asistencia_reporteSearch();
        $searchModel->inasistencias = 1;
        $searchModel->idorganismo = null;
        $searchModel->iddispositivo = null;
        $searchModel->eventuales = null;
        $searchModel->desde = $periodo;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $registros = array();
        foreach ($dataProvider->getModels() as $registro) {
            $registro = array(
                "Legajo" => $registro->legajo,
                "Empleado" => $registro->empleado,
                "PR" => $registro->pr_categoria,
                "Días" => $registro->dia
            );
            array_push($registros, $registro);
        }
        return $registros;
    }

    public function actionView($idregistro)
    {
        $model = Mds_hor_registro::findOne($idregistro);
        Yii::$app->response->format = Response::FORMAT_JSON;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_asistencia_reporte', $idregistro, "");
        return [
            'title' => "Detalle de Fichada " . date_format(date_create($model->fecha), "d/m H:i"),
            'content' => $this->renderAjax('view', [
                'model' => $model,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    /**
     * Creates a new Mds_hor_asistencia_reporte model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_asistencia_reporte();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_hor_asistencia_reporte",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_hor_asistencia_reporte",
                    'content' => '<span class="text-success">Create Mds_hor_asistencia_reporte success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_hor_asistencia_reporte",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
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
                return $this->redirect(['view', 'fecha' => $model->fecha, 'codContacto' => $model->codContacto]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_hor_asistencia_reporte model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $fecha
     * @param integer $codContacto
     * @return mixed
     */
    public function actionUpdate($fecha, $codContacto)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($fecha, $codContacto);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Mds_hor_asistencia_reporte #" . $fecha, $codContacto,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_hor_asistencia_reporte #" . $fecha, $codContacto,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'fecha, $codContacto' => $fecha, $codContacto], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_hor_asistencia_reporte #" . $fecha, $codContacto,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
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
                return $this->redirect(['view', 'fecha' => $model->fecha, 'codContacto' => $model->codContacto]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_hor_asistencia_reporte model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $fecha
     * @param integer $codContacto
     * @return mixed
     */
    public function actionDelete($fecha, $codContacto)
    {
        $request = Yii::$app->request;
        $this->findModel($fecha, $codContacto)->delete();

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
     * Finds the Mds_hor_asistencia_reporte model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $fecha
     * @param integer $codContacto
     * @return Mds_hor_asistencia_reporte the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fecha, $codContacto)
    {
        if (($model = Mds_hor_asistencia_reporte::findOne(['fecha' => $fecha, 'codContacto' => $codContacto])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_asistencia($idcontacto, $desde, $hasta,  $estado)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_asistencia_reporte/reporte_asistencia', $idcontacto, array());
        $contacto = Mds_org_contacto::findBySql(
            "SELECT c.*, UPPER(CONCAT(p.apellido, ', ', p.nombre)) AS nombre
            FROM mds_org_contacto c
            JOIN sds_com_persona p ON c.idpersona=p.idpersona
            WHERE c.idcontacto=" . $idcontacto
        )->one();
        $content = $this->renderPartial(
            'reporte_asistencia',
            ['idcontacto' => $idcontacto, 'desde' => $desde, 'hasta' => $hasta,  'estado' => $estado]
        ); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 6,
            'marginBottom' => 7,
            'marginLeft' => 8,
            'marginRight' => 8,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => "REPORTE DE ASISTENCIAS - $contacto->nombre - D_$desde-H_$hasta",
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }

    public function actionReporte_inasistencia($idorganismo, $iddispositivo, $periodo, $estado, $eventuales = -1)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_asistencia_reporte/reporte_asistencia', $idorganismo > 0 ? $idorganismo : null, array());
        ini_set('max_execution_time', '30000');
        ini_set("pcre.backtrack_limit", "10000000");
        $content = $this->renderPartial(
            'reporte_inasistencia',
            [
                'idorganismo' => $idorganismo, 'iddispositivo' => $iddispositivo,
                'periodo' => $periodo, 'estado' => $estado, 'eventuales' => $eventuales
            ]
        ); // setup kartik\mpdf\Pdf component         
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 6,
            'marginBottom' => 7,
            'marginLeft' => 8,
            'marginRight' => 8,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'REPORTE DE INASISTENCIAS',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }
}
