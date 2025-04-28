<?php

namespace app\controllers;

use app\models\LogPlataforma;

use app\models\Menu;
use app\models\MenuSearch;
use phpDocumentor\Reflection\Types\This;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\helpers\Html;

/**
 * MenuController  Menu model.
 */
class MenuController extends Controller
{
      /**
       * @inheritDoc
       */
      public function behaviors()
      {
            return array_merge(
                  parent::behaviors(),
                  [
                        'verbs' => [
                              'class' => VerbFilter::className(),
                              'actions' => [
                                    'delete' => ['POST'],
                              ],
                        ],
                  ]
            );
      }

      /**
       * Lists all Menu models.
       *
       * @return string
       */
      public function actionIndex()
      {
            $searchModel = new MenuSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);


            $dataProvider->pagination->pageSize = 50;
            return $this->render('index', [
                  'searchModel' => $searchModel,
                  'dataProvider' => $dataProvider,
            ]);
      }

      /**
       * Displays a single Menu model.
       * @param int $id ID
       * @return string
       * @throws NotFoundHttpException if the model cannot be found
       */
      public function actionView($id)
      {
            $request = Yii::$app->request;

            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return [
                        'title' => "Nodo de Menu Id " . $id,
                        'content' => $this->renderAjax('view', [
                              'model' => $this->findModel($id),
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                              Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                  ];
            } else {
                  return $this->render('view', [
                        'model' => $this->findModel($id),
                  ]);
            }
      }

      /**
       * Creates a new Menu model.
       * If creation is successful, the browser will be redirected to the 'view' page.
       * @return string|\yii\web\Response
       */
      public function actionCreate()
      {
            $request = Yii::$app->request;
            $model = new Menu();

            if ($request->isAjax) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  if ($request->isGet) {
                        return [
                              'title' => 'Nuevo Nodo de Menu',
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

                        /* [['title', 'type', 'icon', 'link', 'orden'], 'required'], */
                        $model->padre = $model->padre == '' ? 0 : $model->padre;
                        $model->type = 'basic';
                        $model->icon = $model->icon_yii;
                        $model->link = $model->link_yii . '-grilla';

                        if ($guardado && $model->save()) {
                              $transaction->commit();

                              $this->actionReacomodar_orden($model->id, true);


                              return [
                                    'title' => "Nuevo Nodo de Menu",
                                    'content' => '<span class="text-success">Nodo Creado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                              ];
                        }
                  }
                  return [
                        'title' => "Nuevo Nodo de Menu Faltan datos!!! Complete Los datos Faltantes!!!",
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
                              'title' => 'Editar Nodo de Menu',
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

                        /* [['title', 'type', 'icon', 'link', 'orden'], 'required'], */
                        $model->padre = $model->padre == '' ? 0 : $model->padre;
                        $model->type = 'basic';
                        $model->icon = $model->icon_yii;
                        $model->link = $model->link_yii . '-grilla';

                        if ($guardado && $model->save()) {
                              $transaction->commit();
                              $this->actionReacomodar_orden($model->id, true);
                              return [
                                    'title' => "Editar Nodo de Menu",
                                    'content' => '<span class="text-success">Nodo Editado Correctamente</span>',
                                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                              ];
                        }
                  }
                  return [
                        'title' => "Editar Nodo de Menu Faltan datos!!! Complete Los datos Faltantes!!!",
                        'content' => $this->renderAjax('create', [
                              'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                              Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                  ];
            }
      }

      /**
       * Deletes an existing Menu model.
       * If deletion is successful, the browser will be redirected to the 'index' page.
       * @param int $id ID
       * @return \yii\web\Response
       * @throws NotFoundHttpException if the model cannot be found
       */
      public function actionDelete($id)
      {

            if ($this->findModel($id)->delete()) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return [
                        'title' => "Eliminado",
                        'content' => '<span class="text-success">Nodo Eliminado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                  ];
            }
      }

      /**
       * Finds the Menu model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param int $id ID
       * @return Menu the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($id)
      {
            if (($model = Menu::findOne(['id' => $id])) !== null) {
                  return $model;
            }

            throw new NotFoundHttpException('The requested page does not exist.');
      }

      public function actionSubir($id)
      {
            $model = Menu::findOne($id);
            if ($model !== null) {
                  $model->orden--;
                  $model->save();
            }

            $this->actionReacomodar_orden($model->id);

            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                  'title' => 'Resetear Contraseña',
                  'content' => "Se ah subido el orden del Nodo a $model->orden", // . json_encode($model->getErrors()),
                  'footer' =>
                  Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                  ])
            ];
      }


      public function actionReacomodar_orden($id_model_editado, $edicion = false)
      {
            $model_editado = $this->findModel($id_model_editado);

            $submenu = Menu::find()
                  ->where(['padre' => $model_editado->padre])
                  ->andWhere(['<>', 'id', $model_editado->id])
                  ->orderBy(['orden' => SORT_ASC])
                  ->all();

                  $max_orden = 1;

                  if(!empty($submenu)){
                        $max_orden =  max(array_map(function ($item) {
                              return $item->orden;
                        }, $submenu));
                  }
           

            if ($model_editado->orden == $max_orden && $edicion == true) {
                  $model_editado->orden++;
                  $model_editado->save();
            }


            foreach ($submenu as $nodo) {

                  if ($nodo->orden >= $model_editado->orden) {
                        $model = $this->findModel($nodo->id);
                        $model->orden++;
                        $model->save();
                  }
            }

            $submenu = Menu::find()
                  ->where(['padre' => $model_editado->padre])
                  ->orderBy(['orden' => SORT_ASC])
                  ->all();
            $new_orden = 1;
            foreach ($submenu as $nodo) {

                  $model = $this->findModel($nodo->id);
                  $model->orden = $new_orden;
                  $model->save();
                  $new_orden++;
            }
      }

      public function actionGet_children($padre)
      {
            $hijos = Menu::find()
                  ->where(['padre' => $padre])
                  ->orderBy(['orden' => SORT_ASC])
                  ->all();

            // Renderizar un archivo parcial con la lista de hijos
            return $this->renderPartial('_children_list', ['hijos' => $hijos]);
      }
}
