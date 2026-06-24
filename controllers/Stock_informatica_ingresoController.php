<?php

namespace app\controllers;

use app\models\ConstantesGlobales;
use app\models\LogPlataforma;
use Yii;
use app\models\StockInformaticaIngreso;
use app\models\StockInformaticaIngresoDetalle;
use app\models\StockInformaticaIngresoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;

class Stock_informatica_ingresoController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new StockInformaticaIngresoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "StockInformaticaIngreso #" . $id,
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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new StockInformaticaIngreso();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Ingreso',
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
                    $detallesArray = Json::decode($request->post('detallesArray')); // Deserializas el array JSON

                    foreach ($detallesArray as $detalle) {
                            $detalleModel = new StockInformaticaIngresoDetalle();
                            $detalleModel->idingreso = $model->idingreso;
                            $detalleModel->idarticulo = $detalle['idarticulo'];
                            $detalleModel->cantidad = $detalle['cantidad'];
                            $detalleModel->save();
                        }
                    
                    $transaction->commit(); 
                    LogPlataforma::registrar(ConstantesGlobales::STOCK_INFORMATICA_INGRESO,ConstantesGlobales::CREACION,$model->idingreso);
                    return [
                        'title' => "Nuevo Ingreso",
                        'content' => '<span class="text-success">Ingreso Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Nuevo Ingreso, Faltan datos!!! Complete Los datos Faltantes!!!",
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
                    'title' => 'Editar Ingreso',
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

                $idUsuario = Yii::$app->user->identity->id;
                $model->idusuario_edicion = $idUsuario;

                // Aquí procesas el detallesArray recibido
                $detallesArray = Json::decode($request->post('detallesArray')); // Deserializas el array JSON

                if ($guardado && $model->save()) {
                    // Guardar los detalles relacionados (puedes iterar sobre $detallesArray y guardarlos en la base de datos)
                    // 1. Obtener los detalles existentes
                    $detallesExistentes = StockInformaticaIngresoDetalle::findAll(['idingreso' => $model->idingreso]);

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
                        $detalleExistente = StockInformaticaIngresoDetalle::findOne([
                            'idingreso' => $model->idingreso,
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
                            $detalleModel = new StockInformaticaIngresoDetalle();
                            $detalleModel->idingreso = $model->idingreso;
                            $detalleModel->idarticulo = $detalle['idarticulo'];
                            $detalleModel->cantidad = $detalle['cantidad'];
                            $detalleModel->save();
                        }
                    }
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::STOCK_INFORMATICA_INGRESO,ConstantesGlobales::MODIFICACION,$model->idingreso);
                    return [
                        'title' => "Editar Ingreso",
                        'content' => '<span class="text-success">Ingreso Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Editar Ingreso, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(ConstantesGlobales::STOCK_INFORMATICA_INGRESO,ConstantesGlobales::ELIMINACION,$id);
        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {

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

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {

            return $this->redirect(['index']);
        }
    }


    protected function findModel($id)
    {
        if (($model = StockInformaticaIngreso::findOne($id)) !== null) {
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
