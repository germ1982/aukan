<?php

namespace app\controllers;

use Yii;
use app\models\StockInformaticaEgreso;
use app\models\StockInformaticaEgresoDetalle;
use app\models\StockInformaticaEgresoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;

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
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "StockInformaticaEgreso #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
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
    public function actionCreate_original()
    {
        $request = Yii::$app->request;
        $model = new StockInformaticaEgreso();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new StockInformaticaEgreso",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new StockInformaticaEgreso",
                    'content'=>'<span class="text-success">Create StockInformaticaEgreso success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new StockInformaticaEgreso",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idegreso]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing StockInformaticaEgreso model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate_original($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update StockInformaticaEgreso #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "StockInformaticaEgreso #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update StockInformaticaEgreso #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idegreso]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }


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
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
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
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
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
    /**
     * Delete an existing StockInformaticaEgreso model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
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
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
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
