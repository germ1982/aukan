<?php

namespace app\controllers;

use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
use app\models\LogPlataforma;
use Yii;
use app\models\UsuarioPerfil;
use app\models\UsuarioPerfilSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Usuario_perfilController implements the CRUD actions for UsuarioPerfil model.
 */
class Usuario_perfilController extends Controller
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
       * Lists all UsuarioPerfil models.
       * @return mixed
       */
      public function actionIndex()
      {
            $searchModel = new UsuarioPerfilSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                  'searchModel' => $searchModel,
                  'dataProvider' => $dataProvider,
            ]);
      }


      /**
       * Displays a single UsuarioPerfil model.
       * @param integer $id
       * @return mixed
       */
      public function actionView($id)
      {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return [
                        'title' => "Perfil de Usuario " . $id,
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
            $model = new UsuarioPerfilSearch();

            if ($request->isAjax) {

                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nuevo Perfil',
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
                        $model->id_configuracion_tipo = ConfiguracionTipo::PERFIL_DE_USUARIO;

                        if ($model->save()) {
                              LogPlataforma::registrar(ConstantesGlobales::PERFILES_DE_USUARIO , ConstantesGlobales::CREACION, $model->id_configuracion, "Perfil de Usuario");
                              return [
                                    'title' => "Nuevo Perfil",
                                    'content' => '<span class="text-success">Perfil creado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                          Html::a('Crear Mas', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                              ];
                        }
                  }
                  return [
                        'title' => "Nuevo Perfil Faltan datos!!! Complete Los datos Faltantes!!!",
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
                              'title' => 'Nuevo Perfil',
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
                              LogPlataforma::registrar(ConstantesGlobales::PERFILES_DE_USUARIO, ConstantesGlobales::MODIFICACION, $model->id_configuracion, "Perfil de Usuario");
                              return [
                                    'title' => "Nuevo Perfil",
                                    'content' => '<span class="text-success">Perfil creado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                          Html::a('Crear Mas', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                              ];
                        }
                  }
                  return [
                        'title' => "Nuevo Perfil Faltan datos!!! Complete Los datos Faltantes!!!",
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
            LogPlataforma::registrar(ConstantesGlobales::PERFILES_DE_USUARIO, ConstantesGlobales::ELIMINACION, $id, "Perfil de Usuario");
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
       * Delete multiple existing UsuarioPerfil model.
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
       * Finds the UsuarioPerfil model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return UsuarioPerfil the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($id)
      {
            if (($model = UsuarioPerfil::findOne($id)) !== null) {
                  return $model;
            } else {
                  throw new NotFoundHttpException('The requested page does not exist.');
            }
      }
}
