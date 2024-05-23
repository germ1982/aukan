<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_not_nota;
use app\models\Mds_not_notaSearch;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;

/**
 * Mds_not_notaController implements the CRUD actions for Mds_not_nota model.
 */
class Mds_not_notaController extends Controller
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
                            Mds_seg_item::MODULO_NOT_NOTAS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_not_nota models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_not_notaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_not_nota', null, array());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_not_nota model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_not_nota', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_not_nota #" . $id,
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

    /**
     * Creates a new Mds_not_nota model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_not_nota();
        $model->expediente_anio = date('Y');
        $model->fecha = date('d-m-Y');
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->idusuario = $usuario->idusuario;
        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
        $dispositivo =  $contacto->getDispositivo()->one();
        $model->idorganismo = $dispositivo->idorganismo;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_not_nota",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_not_nota', $model->idnota, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_not_nota",
                    'content' => '<span class="text-success">Create Mds_not_nota success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_not_nota",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Guardado sin modal
            */
            if ($model->load($request->post())) {
                $fecha_not = date_create($model->fecha);
                $fecha_not_guardar = date_format($fecha_not, 'Y-m-d');
                $model->fecha = $fecha_not_guardar;
                $model->fecha_carga = date('Y-m-d');
                $nota = Mds_not_nota::findBySql("select * from mds_not_nota 
                where year(fecha)=year('$fecha_not_guardar') and idorganismo=" .
                    $model->idorganismo . " order by numero desc limit 1")->one();
                if ($nota != null) {
                    $numero = $nota->numero;
                    $model->numero = $numero + 1;
                } else {
                    $model->numero = 1;
                }
                if ($model->expediente_numero == null) {
                    $model->expediente_anio = null;
                }
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_not_nota', $model->idnota, $model->getAttributes());
                    return $this->redirect(['mds_not_nota/index']);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_not_nota model.
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
                    'title' => "Update Mds_not_nota #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_not_nota', $model->idnota, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_not_nota #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_not_nota #" . $id,
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
            if ($model->load($request->post())) {
                $fecha_not = date_create($model->fecha);
                $fecha_not_guardar = date_format($fecha_not, 'Y-m-d');
                $model->fecha = $fecha_not_guardar;
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_not_nota', $model->idnota, $model->getAttributes());
                    return $this->redirect(['index']);
                }
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionEnviar($id)
    {
        $nota = $this->findModel($id);
        if ($nota->updateAttributes(['enviada' => true])) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_not_nota/enviar', $id, $nota->getAttributes());
            return $this->redirect(['index']);
        }
    }

    public function actionAnular($id)
    {
        $nota = $this->findModel($id);
        if ($nota->updateAttributes(['anulada' => 1])) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_not_nota/anular', $id, $nota->getAttributes());
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete an existing Mds_not_nota model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_not_nota', $id, $model->getAttributes());
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
     * Delete multiple existing Mds_not_nota model.
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
     * Finds the Mds_not_nota model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_not_nota the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_not_nota::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_nota($idnota)
    {
        $content = $this->renderPartial('reporte_nota', ['idnota' => $idnota]); // setup kartik\mpdf\Pdf component 
        //        print_r($content);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'NOTA PDF',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }
}
