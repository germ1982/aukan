<?php

namespace app\controllers;

use app\models\Edificio;
use Yii;
use app\models\EdificioConectividad;
use app\models\EdificioConectividadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Edificio_conectividadController implements the CRUD actions for EdificioConectividad model.
 */
class Edificio_conectividadController extends Controller
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
     * Lists all EdificioConectividad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EdificioConectividadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single EdificioConectividad model.
     * @param integer $id
     * @return mixed
     */



    /**
     * Creates a new EdificioConectividad model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idedificio)
    {
        $request = Yii::$app->request;
        $model = new EdificioConectividad();
        $model->idedificio = $idedificio;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Conexión para " . Edificio::get_edificio_descripcion($idedificio),
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                if ($model->validate()) {
                    $model->save();
                    return [
                        //'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nueva Conexión para " . Edificio::get_edificio_descripcion($idedificio),
                        'content' => '<span class="text-success">Excelente, Conexión Creada Para ' . Edificio::get_edificio_descripcion($idedificio) . '. </span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                    ];
                } else {
                    // SI FALLA LA VALIDACIÓN: Volvemos a escupir el formulario con los errores cargados
                    return [
                        'title' => "Nueva Conexión para " . Edificio::get_edificio_descripcion($idedificio),
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Nueva Conexión para " . Edificio::get_edificio_descripcion($idedificio),
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
                return $this->redirect(['view', 'id' => $model->idconectividad]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing EdificioConectividad model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $dash = false)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $boton_editar = $dash == true ? '' : Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);
            return [
                'title' => Edificio::get_edificio_descripcion($id),
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    $boton_editar
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    public function actionUpdate($id, $dash = false)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Conectividad de " . Edificio::get_edificio_descripcion($model->idedificio),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::submitButton('Guardar', [
                            'class' => 'btn btn-primary',
                            'form' => 'conectividad-form'
                        ])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                if ($dash == true) {

                    // ESTO REPLICA EL F5 TECLADO EXACTO MANTENIENDO TU URL LARGA
                        echo "<script>
                            // Forzamos al navegador a ir a la URL de fondo (la de indicadores)
                            window.location.href = document.referrer;
                        </script>";
                        exit;
                }
                return [
                    //'forceReload' => '#crud-datatable-pjax',
                    'title' => "Conectividad de " . Edificio::get_edificio_descripcion($model->idedificio),
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Editar Conectividad de " . Edificio::get_edificio_descripcion($model->idedificio),
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {


            if ($model->load($request->post()) && $model->save()) {
                // Si viene del dash, imprimimos el script directamente en crudo (String) para forzar el F5
            if ($dash == true || $request->get('dash') == 1 || $request->post('dash') == 1 || $request->post('is_dash') == 1) {
                echo "<script>
                    // Forzamos al navegador a ir a la URL de fondo (la de indicadores)
                    window.location.href = document.referrer;
                </script>";
                exit;
            }

                return $this->redirect(['view', 'id' => $model->idconectividad]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing EdificioConectividad model.
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
     * Delete multiple existing EdificioConectividad model.
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
     * Finds the EdificioConectividad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EdificioConectividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EdificioConectividad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionView_indicadores()
    {
        $this->layout = 'main-login';
        $this->layout = false;
        return $this->render('view_indicadores_v2');
    }

    /**
     * Renderiza el nuevo dashboard de un grupo específico sin layout general.
     * @param string $grupo El nombre del grupo (CDI, HOGAR, etc.) recibido por URL
     */
    public function actionView_indicadores_v2_grupo()
    {
        // Por ahora, solo atrapamos el parámetro y se lo mandamos directo a la vista
        $grupo  = Yii::$app->request->get('grupo', null);
        $grupos = Yii::$app->request->get('grupos', []);
        return $this->render('view_indicadores_v2_grupo', [
            'grupo' => $grupo,
            'grupos' => $grupos // <-- Ahora viaja como 'grupos' a la vista
        ]);
    }
}
