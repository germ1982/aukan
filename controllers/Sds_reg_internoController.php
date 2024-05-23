<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Sds_gis_capa_item;
use Yii;
use app\models\Sds_reg_interno;
use app\models\Sds_reg_internoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;

/**
 * Sds_reg_internoController implements the CRUD actions for Sds_reg_interno model.
 */
class Sds_reg_internoController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_REG_INTERNO,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_reg_interno models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_reg_internoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_reg_interno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $edificio = Sds_gis_capa_item::findOne($model->idcapaitem);
        $model->edificio = $edificio->descripcion;

        $dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
        $model->dispositivo = $dispositivo->descripcion;

        $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
        $model->organismo = $organismo->descripcion;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Interno Telefónico #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
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
     * Creates a new Sds_reg_interno model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_reg_interno();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Interno Telefónico",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $model->idcapaitem = $model->edificio;
                if ($model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nuevo Interno Telefónico",
                        'content' => '<span class="text-success"><b>¡El interno se guardó de manera corrceta!</b></span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Nuevo Interno Telefónico",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                return [
                    'title' => "Nuevo Interno Telefónico",
                    'content' => $this->renderAjax('create', [
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
                //return $this->redirect(['view', 'id' => $model->idinterno]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_reg_interno model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
        $model->organismo = $dispositivo->idorganismo;
        $model->edificio = $model->idcapaitem;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Interno Telefónico #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $model->idcapaitem = $model->edificio;
                if ($model->save()) {
                    $edificio = Sds_gis_capa_item::findOne($model->idcapaitem);
                    $model->edificio = $edificio->descripcion;

                    $dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
                    $model->dispositivo = $dispositivo->descripcion;

                    $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                    $model->organismo = $organismo->descripcion;
                    return [
                        //'forceReload'=>'#crud-datatable-pjax',
                        'title' => "Interno Telefónico #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            //$model->addError('organismo', print_r($model->getErrors(), true));
            return [
                'title' => "Actualizar Interno Telefónico #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idinterno]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_reg_interno model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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
     * Delete multiple existing Sds_reg_interno model.
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
     * Finds the Sds_reg_interno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_reg_interno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_reg_interno::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionEdificio_reporte($type = null)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Seleccione el Edificio para el reporte " . $type,
                'content' => $this->renderAjax('reporte', [
                    'type' => $type,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Generar Reporte', ['reporte', 'type' => $type], ['id' => 'generate-report', 'class' => 'btn btn-primary', 'role' => 'modal', 'target' => '_blank'])
            ];
        }
    }

    public function actionReporte($type = null, $edificio = null)
    {
        if ($type == null || $edificio == null) {
            $content = '<b>Debe Proporcionar los datos correspondientes para generar el reporte.-</b>';
        } else {
            $content = $this->renderPartial('reporte', [
                'type' => $type,
                'edificio' => $edificio
            ]);
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => [
                '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                '@vendor/kartik-v/yii2-mpdf/src/assets/report_pdf.css'
            ],
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Reporte Internos Telefónicos',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);
        return $pdf->render();
    }

    public function actionCmb_dispositivo($idcapaitem = 0, $idorganismo = 0)
    {
        $dispositivosAll = Mds_org_dispositivo::find()->where('idcapaitem=' . $idcapaitem . ' and idorganismo=' . $idorganismo)
            ->orderBy(['descripcion' => SORT_ASC])->all();
        $cmbDispositivos = "<option value=''>Seleccione dispositivo...</option>";
        if (count($dispositivosAll) > 0) {
            foreach ($dispositivosAll as $dispositivo) {
                $cmbDispositivos = $cmbDispositivos . "<option value='" . $dispositivo->iddispositivo . "'" . ">" .
                    $dispositivo->descripcion . "</option>";
            }
        }
        return $cmbDispositivos;
    }

    public function actionCmb_organismo($idcapaitem = 0)
    {
        $organismosAll = Mds_org_dispositivo::find()
            ->select('o.idorganismo, o.descripcion')
            ->from('mds_org_dispositivo d')
            ->innerJoin('mds_org_organismo o', 'd.idorganismo=o.idorganismo')
            ->where('idcapaitem=' . $idcapaitem)
            ->groupBy('descripcion')
            ->orderBy(['descripcion' => SORT_ASC])->all();
        $cmbOrganismo = "<option value=''>Seleccione organismo...</option>";;
        if (count($organismosAll) > 0) {
            foreach ($organismosAll as $organismo) {
                $cmbOrganismo = $cmbOrganismo . "<option value='" . $organismo->idorganismo . "'" . ">" .
                    $organismo->descripcion . "</option>";
            }
        }
        return $cmbOrganismo;
    }
}
