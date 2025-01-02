<?php

namespace app\controllers;

use app\models\Articulo;
use Yii;
use app\models\StockInformaticaIngresoDetalle;
use app\models\StockInformaticaIngresoDetalleSearch;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Stock_informatica_ingreso_detalleController implements the CRUD actions for StockInformaticaIngresoDetalle model.
 */
class Stock_informatica_ingreso_detalleController extends Controller
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
        ];
    }

    /**
     * Lists all StockInformaticaIngresoDetalle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockInformaticaIngresoDetalleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single StockInformaticaIngresoDetalle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "StockInformaticaIngresoDetalle #" . $id,
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
     * Creates a new StockInformaticaIngresoDetalle model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new StockInformaticaIngresoDetalle();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new StockInformaticaIngresoDetalle",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new StockInformaticaIngresoDetalle",
                    'content' => '<span class="text-success">Create StockInformaticaIngresoDetalle success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new StockInformaticaIngresoDetalle",
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
                return $this->redirect(['view', 'id' => $model->iddetalle]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing StockInformaticaIngresoDetalle model.
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
                    'title' => "Update StockInformaticaIngresoDetalle #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "StockInformaticaIngresoDetalle #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update StockInformaticaIngresoDetalle #" . $id,
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
                return $this->redirect(['view', 'id' => $model->iddetalle]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing StockInformaticaIngresoDetalle model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return $this->redirect(['/']);
        }
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->delete()) {

            $transaction->commit();
            return 1;
        }
        $transaction->rollBack();
        return 0;
    }

    /**
     * Delete multiple existing StockInformaticaIngresoDetalle model.
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
     * Finds the StockInformaticaIngresoDetalle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockInformaticaIngresoDetalle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockInformaticaIngresoDetalle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
    public function actionGrilla_items()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return $this->redirect(['/']);
        }

        // Recibir el array desde la solicitud POST
        $detallesJson = $request->post('detalles', '[]'); // Default vacío si no hay datos
        $detallesArray = Json::decode($detallesJson);

        // Crear un DataProvider basado en el array recibido
        $dataProvider = new ArrayDataProvider([
            'allModels' => $detallesArray,
            'pagination' => false,
            'sort' => [
                'attributes' => ['idarticulo', 'cantidad'],
            ],
        ]);


        $aux_alta = Html::button('<i class="glyphicon glyphicon-plus"></i>', [
            'class' => 'btn btn-primary',
            'id' => 'btnItem',
            'title' => "Nuevo Item",
            'data-toggle' => 'tooltip',
            'onclick' => "js:mostrar_abm_item();"
        ]);


        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'idarticulo',
                    'headerOptions' => ['style' => 'width:65%'],
                    'value' => function ($model) {
                        $articulo = Articulo::get_articulo($model->idarticulo);
                        return "$articulo";
                    },
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:15%'],
                    'value' => function ($model) {
                        $aux = truncate($model->cantidad, 2);
                        return "$aux";
                    },
                ],
                [
                    'header' =>  $aux_alta,
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:5%'],
                    'template' => '{eliminar}',  // the default buttons + your custom button
                    'buttons' => [
                        'eliminar' => function ($url, $model) {
                            $id_item = $model->iddetalle;
                            return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'title' => "Eliminar Item",
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-link',
                                'onclick' => "eliminar_item({$model['idarticulo']});",
                            ]);
                        },
                    ]
                ]
            ],
        ]);
    }
}


function truncate($number, $precision = 0)
{
    // warning: precision is limited by the size of the int type
    $shift = pow(10, $precision);
    return intval($number * $shift) / $shift;
}
