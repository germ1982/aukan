<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_data_categoria;
use app\models\Mds_data_categoriaSearch;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Mds_data_categoriaController implements the CRUD actions for Mds_data_categoria model.
 */
class Mds_data_categoriaController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_DATA_SUR_WEB
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_data_categoria models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_data_categoriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_data_categoria model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Categoria " . $id,
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
     * Creates a new Mds_data_categoria model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_data_categoria();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear Categoria",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                // Upload icono
                $tmpfile = UploadedFile::getInstance($model, 'temp_icono');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/categoria', $extension);
                    $model->icono = $nombre;
                    if (!file_exists('uploads/categoria/' . $model->idcategoria . '/icono/')) {
                        mkdir('uploads/categoria/' . $model->idcategoria . '/icono/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/categoria/' . $model->idcategoria . '/icono/' . $nombre);
                    $model->save();
                }

                // Upload imagen_fondo
                $tmpfile = UploadedFile::getInstance($model, 'temp_imagen_fondo');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/categoria', $extension);
                    $model->imagen_fondo = $nombre;
                    if (!file_exists('uploads/categoria/' . $model->idcategoria . '/imagen_fondo/')) {
                        mkdir('uploads/categoria/' . $model->idcategoria . '/imagen_fondo/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/categoria/' . $model->idcategoria . '/imagen_fondo/' . $nombre);
                    $model->save();
                }

                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear Categoria",
                    'content' => '<span class="text-success">Crear DataSur Categoria </span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear Más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Crear Categoria",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcategoria]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_data_categoria model.
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
                    'title' => "Editar Categoria " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                // Upload icono
                $tmpfile = UploadedFile::getInstance($model, 'temp_icono');
                if (isset($tmpfile)) {

                    // elimino el actual si existe
                    if ($model->icono) {
                        $path = Yii::$app->basePath . '/web/uploads/categoria/' . $model->idcategoria . '/icono/' . $model->icono;
                        if(file_exists($path)) unlink($path);
                    }

                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/categoria', $extension);
                    $model->icono = 'uploads/categoria/' . $model->idcategoria . '/icono/' . $nombre;
                    if (!file_exists('uploads/categoria/' . $model->idcategoria . '/icono/')) {
                        mkdir('uploads/categoria/' . $model->idcategoria . '/icono/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/categoria/' . $model->idcategoria . '/icono/' . $nombre);
                }

                if($model->icono && $model->borrar_icono) {
                    $path = Yii::$app->basePath . '/web/uploads/categoria/' . $model->idcategoria . '/icono/' . $model->icono;
                    unlink($path);
                }

                // Upload imagen_fondo
                $tmpfile = UploadedFile::getInstance($model, 'temp_imagen_fondo');
                if (isset($tmpfile)) {

                    // elimino el actual si existe
                    if ($model->imagen_fondo) {
                        $path = Yii::$app->basePath . '/web/uploads/categoria/' . $model->idcategoria . '/imagen_fondo/' . $model->imagen_fondo;
                        if(file_exists($path)) unlink($path);
                    }

                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/categoria', $extension);
                    $model->imagen_fondo = 'uploads/categoria/' . $model->idcategoria . '/imagen_fondo/' . $nombre;
                    if (!file_exists('uploads/categoria/' . $model->idcategoria . '/imagen_fondo/')) {
                        mkdir('uploads/categoria/' . $model->idcategoria . '/imagen_fondo/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/categoria/' . $model->idcategoria . '/imagen_fondo/' . $nombre);
                }

                if($model->imagen_fondo && $model->borrar_imagen_fondo) {
                    $path = Yii::$app->basePath . '/web/uploads/categoria/' . $model->idcategoria . '/imagen_fondo/' . $model->imagen_fondo;
                    unlink($path);
                }

               $guardado = $model->save(false);
                if ($guardado) {
                    $transaction->commit();
                    return [
                        'title' => "Categoría " . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Editar Categoria " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcategoria]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_data_categoria model.
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
     * Finds the Mds_data_categoria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_data_categoria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_data_categoria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
