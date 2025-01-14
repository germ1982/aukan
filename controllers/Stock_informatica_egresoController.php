<?php

namespace app\controllers;

use Yii;
use app\models\StockInformaticaEgreso;
use app\models\StockInformaticaEgresoDetalle;
use app\models\StockInformaticaEgresoSearch;
use app\models\ViewStockArticulosCantidades;
use app\models\ViewStockArticulosCantidadesSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

/**
 * Stock_informatica_egresoController implements the CRUD actions for StockInformaticaEgreso model.
 */
class Stock_informatica_egresoController extends Controller
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
     * Lists all StockInformaticaEgreso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockInformaticaEgresoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single StockInformaticaEgreso model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "StockInformaticaEgreso #" . $id,
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
     * Creates a new StockInformaticaEgreso model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */



    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new StockInformaticaEgreso();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Egreso',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                            'onclick' => 'guardarFormulario()',  // Llamada a la función JavaScript
                        ]),
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $fecha = ArmarDateParaMySql($model->fecha);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha = $fecha;


                if ($guardado && $model->save()) {

                    // Aquí procesas el detallesArray recibido
                    $boton_acta = Html::button(
                        'Imprimir Acta de Entrega',
                        [
                            'title' => "Imprimir Acta de Entrega",
                            'onclick' => "window.open('" . Url::to(['/stock_informatica_egreso/imprimir_acta_entrega', 'idegreso' => $model->idegreso]) . "', '_blank')",
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-primary pull-left', // Clase opcional para estilizar como botón
                        ]
                    );
                    
                    $detallesArray = [];
                    $detallesArray = Json::decode($request->post('detallesArray')); // Deserializas el array JSON

                    
                    foreach ($detallesArray as $detalle) {
                        $detalleModel = new StockInformaticaEgresoDetalle();
                        $detalleModel->idegreso = $model->idegreso;
                        $detalleModel->idarticulo = $detalle['idarticulo'];
                        $detalleModel->cantidad = $detalle['cantidad'];
                        $detalleModel->save();
                    }

                    $transaction->commit();

                    return [
                        'title' => "Nuevo Egreso",
                        'content' => '<span class="text-success">Egreso Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]).$boton_acta 

                            
                    ];
                }
            }
            return [
                'title' => "Nuevo Egreso, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Editar Egreso',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',  // Cambiar de 'submit' a 'button'
                            'onclick' => 'guardarFormulario()',  // Llamada a la función JavaScript
                        ]),
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $fecha = ArmarDateParaMySql($model->fecha);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha = $fecha;

                $idUsuario = Yii::$app->user->identity->id;
                $model->idusuario_edicion = $idUsuario;

                // Aquí procesas el detallesArray recibido
                $detallesArray = Json::decode($request->post('detallesArray')); // Deserializas el array JSON

                if ($guardado && $model->save()) {

                    $boton_acta = Html::button(
                        'Imprimir Acta de Entrega',
                        [
                            'title' => "Imprimir Acta de Entrega",
                            'onclick' => "window.open('" . Url::to(['/stock_informatica_egreso/imprimir_acta_entrega', 'idegreso' => $model->idegreso]) . "', '_blank')",
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-primary pull-left', // Clase opcional para estilizar como botón
                        ]
                    );
                    
                    // Guardar los detalles relacionados (puedes iterar sobre $detallesArray y guardarlos en la base de datos)
                    // 1. Obtener los detalles existentes
                    $detallesExistentes = StockInformaticaEgresoDetalle::findAll(['idegreso' => $model->idegreso]);

                    // 2. Comprobar qué detalles ya no están en el nuevo array y eliminarlos
                    $detallesArrayIds = array_column($detallesArray, 'idarticulo'); // Obtener solo los ids de los artículos en el nuevo array
                    foreach ($detallesExistentes as $detalleExistente) {
                        if (!in_array($detalleExistente->idarticulo, $detallesArrayIds)) {
                            // Si el detalle ya no está en el nuevo array, lo eliminamos
                            $detalleExistente->delete();
                        }
                    }

                    // 3. Ahora iteramos sobre el nuevo array de detalles
                    foreach ($detallesArray as $detalle) {
                        // Buscar si ya existe el detalle en la base de datos
                        $detalleExistente = StockInformaticaEgresoDetalle::findOne([
                            'idegreso' => $model->idegreso,
                            'idarticulo' => $detalle['idarticulo']
                        ]);

                        if ($detalleExistente) {
                            // Si existe, actualizamos la cantidad
                            if ($detalle['cantidad'] == 0) {
                                // Si la cantidad es 0, eliminamos el registro
                                $detalleExistente->delete();
                            } else {
                                // Si no es 0, actualizamos la cantidad
                                $detalleExistente->cantidad = $detalle['cantidad'];
                                $detalleExistente->save();
                            }
                        } else {
                            // Si no existe, creamos un nuevo detalle
                            $detalleModel = new StockInformaticaEgresoDetalle();
                            $detalleModel->idegreso = $model->idegreso;
                            $detalleModel->idarticulo = $detalle['idarticulo'];
                            $detalleModel->cantidad = $detalle['cantidad'];
                            $detalleModel->save();
                        }
                    }
                    $transaction->commit();

                    return [
                        'title' => "Editar Egreso",
                        'content' => '<span class="text-success">Egreso editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]).$boton_acta,
                    ];
                }
            }
            return [
                'title' => "Editar Egreso, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    /* public function actionView_stock_articulos_cantidades()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $searchModel = new ViewStockArticulosCantidadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return [
            'title' => "Articulos",
            'content' => $this->renderAjax('_view_stock_articulos_cantidades', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]),
            'footer' => '<div class="text-center">' . Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default text-center', 'data-dismiss' => "modal"]) . '</div>',
        ];
    } */
    public function actionImprimir_acta_entrega($idegreso)
    {
        $content = $this->renderPartial('imprimir_acta_entrega', [
            'idegreso' => $idegreso,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }


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
     * Delete multiple existing StockInformaticaEgreso model.
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
     * Finds the StockInformaticaEgreso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockInformaticaEgreso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockInformaticaEgreso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
function ArmarDateParaMySql($Fecha)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
