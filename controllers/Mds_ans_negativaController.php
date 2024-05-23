<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_ans_negativa;
use app\models\Mds_ans_negativaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Mds_ans_negativaController implements the CRUD actions for Mds_ans_negativa model.
 */
class Mds_ans_negativaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ANS_NEGATIVA,
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Mds_ans_negativa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_ans_negativaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_ans_negativa', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_ans_negativa model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->fallecido = $model->fallecido == 'N' ? 'NO' : 'SI';
        $model->fecha_fallecido = $model->fecha_fallecido == 0 ? null : $this->armarDateParaForm($model->fecha_fallecido);
        if ($model->trabajador_dependiente != '')
            $model->trabajador_dependiente = $model->trabajador_dependiente == 'N' ? 'NO' : 'SI';
        if ($model->autonomo != '')
            $model->autonomo = $model->autonomo == 'N' ? 'NO' : 'SI';
        if ($model->monotributista != '')
            $model->monotributista = $model->monotributista == 'N' ? 'NO' : 'SI';
        if ($model->ddjprovincial != '')
            $model->ddjprovincial = $model->ddjprovincial == 'N' ? 'NO' : 'SI';
        if ($model->casas_particulares != '')
            $model->casas_particulares = $model->casas_particulares == 'N' ? 'NO' : 'SI';
        if ($model->efectores_sociales != '')
            $model->efectores_sociales = $model->efectores_sociales == 'N' ? 'NO' : 'SI';
        if ($model->jubilado_pensionado != '')
            $model->jubilado_pensionado = $model->jubilado_pensionado == 'N' ? 'NO' : 'SI';
        if ($model->previsional_provincia != '')
            $model->previsional_provincia = $model->previsional_provincia == 'N' ? 'NO' : 'SI';
        if ($model->previsional_tramite != '')
            $model->previsional_tramite = $model->previsional_tramite == 'N' ? 'NO' : 'SI';
        if ($model->desempleo != '')
            $model->desempleo = $model->desempleo == 'N' ? 'NO' : 'SI';
        if ($model->programa_empleo != '')
            $model->programa_empleo = $model->programa_empleo == 'N' ? 'NO' : 'SI';
        if ($model->os_vigente != '')
            $model->os_vigente = $model->os_vigente == 'N' ? 'NO' : 'SI';
        if ($model->asignacion_familiar != '')
            $model->asignacion_familiar = $model->asignacion_familiar == 'N' ? 'NO' : 'SI';
        if ($model->auh != '')
            $model->auh = $model->auh == 'N' ? 'NO' : 'SI';
        if ($model->cuota_beca_progresar != '')
            $model->cuota_beca_progresar = $model->cuota_beca_progresar == 'N' ? 'NO' : 'SI';
        if ($model->beca_progresar != '')
            $model->beca_progresar = $model->beca_progresar == 'N' ? 'NO' : 'SI';
        if ($model->maternidad_casasparticulares != '')
            $model->maternidad_casasparticulares = $model->maternidad_casasparticulares == 'N' ? 'NO' : 'SI';
        if ($model->asignacion_familiar_jubilados != '')
            $model->asignacion_familiar_jubilados = $model->asignacion_familiar_jubilados == 'N' ? 'NO' : 'SI';
        if ($model->pnc != '')
            $model->pnc = $model->pnc == 'N' ? 'NO' : 'SI';
        if ($model->iniciacion_pnc != '')
            $model->iniciacion_pnc = $model->iniciacion_pnc == 'N' ? 'NO' : 'SI';
        if ($model->aaff_discontinuos != '')
            $model->aaff_discontinuos = $model->aaff_discontinuos == 'N' ? 'NO' : 'SI';
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_ans_negativa', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_ans_negativa #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    //Me voy a basar en lo que hizo el gran Gastón
    function armarDateParaForm($fecha)
    {
        if ($fecha == null) {
            return null;
        }
        $anio = substr($fecha, 4, 4);
        $mes  = substr($fecha, -6, 2);
        $dia = substr($fecha, 0, strlen($fecha) - 6);
        $DT = "$anio-$mes-$dia";
        return $DT;
    }

    /**
     * Creates a new Mds_ans_negativa model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_ans_negativa();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_ans_negativa",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_ans_negativa",
                    'content' => '<span class="text-success">Create Mds_ans_negativa success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_ans_negativa",
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
                return $this->redirect(['view', 'id' => $model->idnegativa]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_ans_negativa model.
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
                    'title' => "Update Mds_ans_negativa #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_ans_negativa #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_ans_negativa #" . $id,
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
                return $this->redirect(['view', 'id' => $model->idnegativa]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_ans_negativa model.
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
     * Finds the Mds_ans_negativa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_ans_negativa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_ans_negativa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
