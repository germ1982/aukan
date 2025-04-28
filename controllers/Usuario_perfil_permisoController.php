<?php

namespace app\controllers;

use Yii;
use app\models\UsuarioPerfilPermiso;
use app\models\UsuarioPerfilPermisoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Usuario_perfil_permisoController  UsuarioPerfilPermiso model.
 */
class Usuario_perfil_permisoController extends Controller
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
       * Lists all UsuarioPerfilPermiso models.
       * @return mixed
       */
      public function actionIndex()
      {
            $searchModel = new UsuarioPerfilPermisoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                  'searchModel' => $searchModel,
                  'dataProvider' => $dataProvider,
            ]);
      }


      /**
       * Displays a single UsuarioPerfilPermiso model.
       * @param integer $id
       * @return mixed
       */
      public function actionView($id)
      {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return [
                        'title' => "UsuarioPerfilPermiso #" . $id,
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
       * Creates a new UsuarioPerfilPermiso model.
       * For ajax request will return json object
       * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
       * @return mixed
       */
      public function actionCreate()
      {
            $request = Yii::$app->request;
            $model = new UsuarioPerfilPermiso();

            if ($request->isAjax) {

                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nuevo Permiso de Perfil',
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

                        if ($model->save()) {
                              return [
                                    'title' => "Nuevo Permiso de Perfil",
                                    'content' => '<span class="text-success">Permiso De Perfil creado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                          Html::a('Crear Mas', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                              ];
                        }
                  }
                  return [
                        'title' => "Nuevo Permiso de Perfil Faltan datos!!! Complete Los datos Faltantes!!!",
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
                  /*
            *   Process for ajax request
            */
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nuevo Permiso de Perfil',
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
                        if ($model->save()) {
                              return [
                                    'title' => "Nuevo Permiso de Perfil",
                                    'content' => '<span class="text-success">Permiso De Perfil creado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                          Html::a('Crear Mas', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                              ];
                        }
                  }
                  return [
                        'title' => "Nuevo Permiso de Perfil Faltan datos!!! Complete Los datos Faltantes!!!",
                        'content' => $this->renderAjax('create', [
                              'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                              Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                  ];
            }
      }

      /**
       * Delete an existing UsuarioPerfilPermiso model.
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
       * Delete multiple existing UsuarioPerfilPermiso model.
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
       * Finds the UsuarioPerfilPermiso model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return UsuarioPerfilPermiso the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($id)
      {
            if (($model = UsuarioPerfilPermiso::findOne($id)) !== null) {
                  return $model;
            } else {
                  throw new NotFoundHttpException('The requested page does not exist.');
            }
      }
}
