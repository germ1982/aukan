<?php

namespace app\controllers;

use app\models\Empleado;
use Yii;
use app\models\InformaticaWebEmpleados;
use app\models\InformaticaWebEmpleadosSearch;
use app\models\LogPlataforma;

use PhpParser\Node\Stmt\TryCatch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Informatica_web_empleadosController  InformaticaWebEmpleados model.
 */
class Informatica_web_empleadosController extends Controller
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
       * Lists all InformaticaWebEmpleados models.
       * @return mixed
       */
      public function actionIndex()
      {
            $searchModel = new InformaticaWebEmpleadosSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                  'searchModel' => $searchModel,
                  'dataProvider' => $dataProvider,
            ]);
      }


      /**
       * Displays a single InformaticaWebEmpleados model.
       * @param integer $id
       * @return mixed
       */
      public function actionView($id)
      {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return [
                        'title' => "Reseña de Empleado numero " . $id,
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

      public function actionCreate()
      {
            $request = Yii::$app->request;
            $model = new InformaticaWebEmpleados();

            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nueva Reseña de Empleado',
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

                        try {
                              if ($guardado && $model->save()) {
                                    $transaction->commit();

                                    return [
                                          'title' => "Nueva Reseña de Empleado",
                                          'content' => '<span class="text-success">Nueva Reseña de Empleado Creada Correctamente</span>',
                                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                                    ];
                              }
                        } catch (\Exception  $e) {
                              // Imprime la última consulta SQL ejecutada
                              Yii::error($model->getErrors()); // Para ver los errores de validación si hay alguno
                              $lastQuery = Yii::$app->db->createCommand()->getRawSql();
                              throw new \Exception("Consulta ejecutada: " . $lastQuery);
                        }
                  }
                  return [
                        'title' => "Nueva Reseña de Empleado, Faltan datos!!! Complete Los datos Faltantes!!!",
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
                              'title' => 'Editar Reseña de Empleado',
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



                        if ($guardado && $model->save()) {
                              $transaction->commit();
                              return [
                                    'title' => "Editar Reseña de Empleado",
                                    'content' => '<span class="text-success">Reseña de Empleado Editada Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                              ];
                        }
                  }
                  return [
                        'title' => "Editar Reseña de Empleado, Faltan datos!!! Complete Los datos Faltantes!!!",
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
       * Delete multiple existing InformaticaWebEmpleados model.
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
       * Finds the InformaticaWebEmpleados model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return InformaticaWebEmpleados the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($id)
      {
            if (($model = InformaticaWebEmpleados::findOne($id)) !== null) {
                  return $model;
            } else {
                  throw new NotFoundHttpException('The requested page does not exist.');
            }
      }

      public function actionGet_empleados()
      {
            $sql = "SELECT e.idempleado, 
                       concat(p.apellido, ' ', p.nombre) as descripcion, 
                       we.orden 
                FROM empleado e
                JOIN personas p ON p.idpersona = e.idpersona
                JOIN organismo_dispositivo d ON e.iddispositivo = d.iddispositivo
                JOIN informatica_web_empleados we ON we.idempleado = e.idempleado
                WHERE e.activo = 1 AND d.idorganismo = 6
                ORDER BY we.orden ASC, p.apellido ASC, p.nombre ASC";

            $empleados = Empleado::findBySql($sql)->all();
            //return "hola mundo";
            return $this->renderPartial('_empleados_list', ['empleados' => $empleados]);
      }
}
