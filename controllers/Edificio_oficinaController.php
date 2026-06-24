<?php

namespace app\controllers;

use app\models\ConstantesGlobales;
use app\models\Edificio;
use Yii;
use app\models\EdificioOficina;
use app\models\EdificioOficinaSearch;
use app\models\LogPlataforma;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Edificio_oficinaController implements the CRUD actions for EdificioOficina model.
 */
class Edificio_oficinaController extends Controller
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
     * Lists all EdificioOficina models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EdificioOficinaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single EdificioOficina model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Oficina",
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
        $model = new EdificioOficina();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nueva Oficina',
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


                $edificio = Edificio::findOne($model->idedificio);

                $tmpfile = UploadedFile::getInstance($model, 'imageFile');

                //
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "$model->idoficina-$edificio->descripcion_fija-$model->descripcion.$extension";
                    $model->plano_ubicacion = $nuevo_nombre;
                    $tmpfile->saveAs('img/oficinas-planos/' . $nuevo_nombre);
                } else {
                    $model->plano_ubicacion = null;
                }

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::OFICINAS, ConstantesGlobales::CREACION, $model->idoficina);
                    return [
                        'title' => "Nueva Oficina",
                        'content' => '<span class="text-success">Oficina Creada Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Nueva Oficina, Faltan datos!!! Complete Los datos Faltantes!!!",
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
                    'title' => 'Editar Oficina',
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

                $edificio = Edificio::findOne($model->idedificio);

                //Aca comienza el proceso de guardado de la imagen:
                //primero rescata los datos de la imagen cargados en el widget fileInput
                $tmpfile = UploadedFile::getInstance($model, 'imageFile');

                //
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "$model->idoficina-$edificio->descripcion_fija-$model->descripcion.$extension";
                    $model->plano_ubicacion = $nuevo_nombre;
                    $tmpfile->saveAs('img/oficinas-planos/' . $nuevo_nombre);
                }  /* else {
                    $model->plano_ubicacion = null;
                } */

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::OFICINAS, ConstantesGlobales::MODIFICACION, $model->idoficina);
                    return [
                        'title' => "Editar Oficina",
                        'content' => '<span class="text-success">Oficina Editada Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Editar Oficina, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    /**
     * Delete an existing EdificioOficina model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(ConstantesGlobales::OFICINAS, ConstantesGlobales::ELIMINACION, $id);


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
     * Delete multiple existing EdificioOficina model.
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
     * Finds the EdificioOficina model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EdificioOficina the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EdificioOficina::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_oficinas_edificio($idedificio)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sql = "SELECT idoficina, descripcion 
                    FROM edificio_oficina 
                    WHERE idedificio = $idedificio 
                    ORDER BY descripcion";

        $oficinas = EdificioOficina::findBySql($sql)->asArray()->all();

        return $oficinas;
    }

    public function actionGet_oficinas()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sql = "SELECT idoficina, descripcion 
                    FROM edificio_oficina 
                    ORDER BY descripcion";

        $oficinas = EdificioOficina::findBySql($sql)->asArray()->all();

        return $oficinas;
    }

    public function actionGet_edificio($idoficina)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sql = "SELECT idedificio 
                    FROM edificio_oficina 
                    WHERE idoficina = $idoficina";

        $oficinas = EdificioOficina::findBySql($sql)->asArray()->one();

        return $oficinas['idedificio'];
    }
}
