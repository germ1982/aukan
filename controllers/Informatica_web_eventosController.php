<?php

namespace app\controllers;

use Yii;
use app\models\InformaticaWebEventos;
use app\models\InformaticaWebEventosSearch;
use app\models\LogPlataforma;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Informatica_web_eventosController  InformaticaWebEventos model.
 */
class Informatica_web_eventosController extends Controller
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
       * Lists all InformaticaWebEventos models.
       * @return mixed
       */
      public function actionIndex()
      {
            $searchModel = new InformaticaWebEventosSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                  'searchModel' => $searchModel,
                  'dataProvider' => $dataProvider,
            ]);
      }


      /**
       * Displays a single InformaticaWebEventos model.
       * @param integer $id
       * @return mixed
       */
      public function actionView($id)
      {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return [
                        'title' => "Evento Numero " . $id,
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
       * Creates a new InformaticaWebEventos model.
       * For ajax request will return json object
       * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
       * @return mixed
       */
      public function actionCreate()
      {
            $request = Yii::$app->request;
            $model = new InformaticaWebEventos();

            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nuevo Evento',
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

                              $imageFiles = UploadedFile::getInstances($model, 'imageFile');
                              $imageNames = [];

                              if (isset($imageFiles)) {
                                    $numero = 1;

                                    foreach ($imageFiles as $file) {
                                          $nuevo_nombre = "evento-$model->idevento-foto-$numero.$file->extension";
                                          $file->saveAs('img/evento-fotos/' . $nuevo_nombre);
                                          //echo "<script>console.log('$nuevo_nombre')</script>";
                                          $imageNames[] = $nuevo_nombre; // Guardamos el nombre en el array
                                          $numero++;
                                    }
                                    //echo "<script>console.log(".json_encode($imageNames).")</script>";
                                    $model->fotos = implode(',', $imageNames);
                              } else {
                                    $model->fotos = "evento_0_foto_0.jpg";
                              }
                              //echo "<script>console.log('$model->fotos')</script>";
                              $model->save();

                              return [
                                    'title' => "Nuevo Evento",
                                    'content' => '<span class="text-success">Evento Creado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                              ];
                        }
                  }
                  return [
                        'title' => "Nuevo Evento, Faltan datos!!! Complete Los datos Faltantes!!!",
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
                              'title' => 'Editar Evento',
                              'content' => $this->renderAjax('update', [
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

                        $imageFiles = UploadedFile::getInstances($model, 'imageFile');
                        $imageNames = explode(',', $model->fotos);

                        if (isset($imageFiles)) {
                              $imageNames = [];
                              $numero = 1;

                              foreach ($imageFiles as $file) {
                                    $nuevo_nombre = "evento-$model->idevento-foto-$numero.$file->extension";
                                    $file->saveAs('img/evento-fotos/' . $nuevo_nombre);
                                    $imageNames[] = $nuevo_nombre; // Guardamos el nombre en el array
                                    $numero++;
                              }

                              $model->fotos = implode(',', $imageNames);
                        }

                        $model->save();
                        if ($guardado && $model->save()) {
                              $transaction->commit();


                              return [
                                    'title' => "Editar Evento",
                                    'content' => '<span class="text-success">Evento Editado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                              ];
                        }
                  }
                  return [
                        'title' => "Editar Evento, Faltan datos!!! Complete Los datos Faltantes!!!",
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
       * Delete multiple existing InformaticaWebEventos model.
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
       * Finds the InformaticaWebEventos model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return InformaticaWebEventos the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($id)
      {
            if (($model = InformaticaWebEventos::findOne($id)) !== null) {
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