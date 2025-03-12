<?php

namespace app\controllers;

use Yii;
use app\models\RunneuLegajo;
use app\models\RunneuLegajoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

class Runneu_legajoController extends Controller
{
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
        $searchModel = new RunneuLegajoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        // Si el archivo no está disponible, asigna un valor predeterminado
        $num_legajo = $model->num_legajo;
        $dni = $model->dni;
        $archivo_adjunto = $model->archivo_adjunto ?: 'No disponible';

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "RunneuLegajo",
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'num_legajo' => $num_legajo,
                    'dni' => $dni,
                    'archivo_adjunto' => $archivo_adjunto,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
                'num_legajo' => $num_legajo,
                'dni' => $dni,
                'archivo_adjunto' => $archivo_adjunto,
            ]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new RunneuLegajo();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nuevo Legajo",
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            } else if ($model->load($request->post())) {
                $model->archivo_adjunto = UploadedFile::getInstance($model, 'archivo_adjunto');

                // Validar si el archivo se ha subido correctamente
                $filePath = $model->upload();

                if ($filePath && $model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "RunneuLegajo",
                        'content' => '<span class="text-success">Create RunneuLegajo success</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                } else {
                    return [
                        'title' => "Crear nuevo Legajo",
                        'content' => $this->renderAjax('create', ['model' => $model]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                    ];
                }
            } else {
                return [
                    'title' => "Crear nuevo Legajo",
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }
        } else {
            if ($model->load($request->post())) {
                $model->archivo_adjunto = UploadedFile::getInstance($model, 'archivo_adjunto');
                $filePath = $model->upload();

                if ($filePath && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->num_legajo]);
                }
            }
            return $this->render('create', ['model' => $model]);
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
                    'title' => "Actualizar Legajo " . $id,
                    'content' => $this->renderAjax('update', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            } else if ($model->load($request->post())) {
                $model->archivo_adjunto = UploadedFile::getInstance($model, 'archivo_adjunto');
                $filePath = $model->upload();

                if ($filePath && $model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "RunneuLegajo #" . $id,
                        'content' => $this->renderAjax('view', ['model' => $model]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                }
            }
            return [
                'title' => "Actualizar Legajo",
                'content' => $this->renderAjax('update', ['model' => $model]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
            ];
        }
    }

    public function findModel($id)
    {
        if (($model = RunneuLegajo::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
