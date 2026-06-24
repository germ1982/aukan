<?php

namespace app\controllers;

use app\models\ConstantesGlobales;
use app\models\LogPlataforma;
use Yii;
use app\models\RunneuLegajo;
use app\models\RunneuLegajoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
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
                'title' => "Runneu - Legajo - $num_legajo - DNI: $dni",
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
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $model->archivo_adjunto = "legajo_runneu_0.jpg";

                if ($guardado && $model->save()) {

                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::LEGAJOS_RUNNEU,ConstantesGlobales::CREACION,$model->id);
                    $tmpfile = UploadedFile::getInstance($model, 'archivo_adjunto_file');

                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;

                        $nuevo_nombre = "legajo_runneu_$model->dni.$extension";
                        $model->archivo_adjunto = $nuevo_nombre;
                        $tmpfile->saveAs('uploads/legajo_runneu/' . $nuevo_nombre);
                        $model->save();
                    }
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Runneu Legajo",
                        'content' => '<span class="text-success">Legajo Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                        //Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                }
            }
            return [
                'title' => "Nuevo Legajo, Faltan datos!!! Complete Los datos Faltantes!!!",
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
                    'title' => "Actualizar Legajo " . $id,
                    'content' => $this->renderAjax('update', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            } else if ($model->load($request->post())) {

                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $tmpfile = UploadedFile::getInstance($model, 'archivo_adjunto_file');

                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "legajo_runneu_$model->dni.$extension";
                    $model->archivo_adjunto = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/legajo_runneu/' . $nuevo_nombre);
                }

                if ($guardado && $model->save()) {

                    $transaction->commit();

                    LogPlataforma::registrar(ConstantesGlobales::LEGAJOS_RUNNEU,ConstantesGlobales::MODIFICACION,$model->id);
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Editar Legajo Dni " . $id,
                        'content' => '<span class="text-success">Articulo Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Editar Articulo, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', ['model' => $model,]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }




    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            LogPlataforma::registrar(ConstantesGlobales::LEGAJOS_RUNNEU,ConstantesGlobales::ELIMINACION,$id);
            return [
                'title' => "Eliminado",
                'content' => '<span class="text-success">Legajo Eliminado Correctamente</span>',
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'center btn btn-default pull-left', 'data-dismiss' => "modal"])
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
