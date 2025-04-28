<?php

namespace app\controllers;

use app\models\LogPlataforma;
use Yii;
use app\models\VehiculoOficialMovimiento;
use app\models\VehiculoOficialMovimientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Vehiculo_oficial_movimientoController  VehiculoOficialMovimiento model.
 */
class Vehiculo_oficial_movimientoController extends Controller
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
     * Lists all VehiculoOficialMovimiento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VehiculoOficialMovimientoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single VehiculoOficialMovimiento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "VehiculoOficialMovimiento #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
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
     * Creates a new VehiculoOficialMovimiento model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new VehiculoOficialMovimiento();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                  return [
                        'title' => 'Nuevo Movimiento',
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
                        $transaction->commit();
                        LogPlataforma::registrar(29,1,$model->idmovimiento); 

                        return [
                              'title' => "Nuevo Movimiento",
                              'content' => '<span class="text-success">Movimiento Creado Correctamente</span>',
                              'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                        ];
                  }
            }
            return [
                  'title' => "Nuevo Movimiento, Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                        'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
      }
    }

    /**
     * Updates an existing VehiculoOficialMovimiento model.
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
                        'title' => 'Editar Movimiento',
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
                        $transaction->commit();
                        LogPlataforma::registrar(29,2,$model->idmovimiento); 

                        return [
                              'title' => "Editar Movimiento",
                              'content' => '<span class="text-success">Movimiento Creado Correctamente</span>',
                              'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                        ];
                  }
            }
            return [
                  'title' => "Editar Movimiento, Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                        'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
      }
    }

    /**
     * Delete an existing VehiculoOficialMovimiento model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(29,3,$id); 
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
     * Delete multiple existing VehiculoOficialMovimiento model.
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
     * Finds the VehiculoOficialMovimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VehiculoOficialMovimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VehiculoOficialMovimiento::findOne($id)) !== null) {
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