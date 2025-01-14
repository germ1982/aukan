<?php

namespace app\controllers;

use app\models\Persona;
use app\models\Empleado;
use Yii;
use app\models\MovimVehiOficial;
use app\models\MovimVehiOficialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\VehiculoOficial;
use yii\helpers\ArrayHelper;

/**
 * Movim_vehi_oficialController implements the CRUD actions for MovimVehiOficial model.
 */
class Movim_vehi_oficialController extends Controller
{
    /**
     * Acción para obtener los datos de un vehículo seleccionado.
     * @param integer $idVehiculo
     * @return mixed
     */
    public function actionDatosVehiculo($idvehiculo)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Obtener los detalles del vehículo seleccionado
        $vehiculoOficial = VehiculoOficial::find()->where(['idvehiculo' => $idvehiculo])->one();

        // Verificar si el vehículo existe
        if ($vehiculoOficial) {
            return [

                'dominio' => $vehiculoOficial->dominio,
                'modelo' => $vehiculoOficial->modelo,
                'anio' => $vehiculoOficial->anio,
                'color' => $vehiculoOficial->color,
            ];
        }

        return null;  // Si no se encuentra el vehículo
    }



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
     * Lists all MovimVehiOficial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MovimVehiOficialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single MovimVehiOficial model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Obtener el empleado y chofer

            $model = $this->findModel($id);

            $empleado = Empleado::findOne($model->chofer);
            if ($empleado) {
                $chofer = Persona::findOne($empleado->idpersona);
                $choferInfo = $chofer ? $chofer->nombre . ' - ' . $chofer->apellido . ' - ' . $empleado->legajo : 'No encontrado';
            } else {
                $choferInfo = 'Empleado no encontrado';
            }

            return [
                'title' => "Movimiento Vehiculo Oficial" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                    'choferInfo' => $choferInfo,  // Aquí pasas la información del chofer
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
     * Creates a new MovimVehiOficial model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MovimVehiOficial();

        // Obtener vehículos
        $vehiculos = ArrayHelper::map(
            VehiculoOficial::find()->all(),
            'idvehiculo', // El valor de la clave (ID del vehículo)
            function ($model) {
                // Obtener la descripción de la marca a través de la relación
                $marca = $model->marca ? $model->marca->descripcion : 'Marca no disponible'; // 'marca' es la relación en el modelo

                // Concatenar la información del vehículo (marca, modelo, dominio, año)
                return $marca . ' - ' . $model->modelo . ' - ' . $model->dominio . ' - ' . $model->anio;
            }
        );

        // Obtener choferes
        $choferes = ArrayHelper::map(
            Empleado::find()->all(),
            'idempleado', // ID del chofer
            function ($model) {
                $persona = $model->persona; // Relación con la tabla de personas
                return $persona ? $persona->nombre . ' ' . $persona->apellido . ' - Legajo ' . $model->legajo : 'No disponible';
            }
        );

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => "Crear Movimiento",
                    'content' => $this->renderAjax('_form', [
                        'model' => $model,
                        'vehiculos' => $vehiculos, // Pasamos los vehículos a la vista
                        'choferes' => $choferes,   // Pasamos los choferes
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new MovimVehiOficial",
                    'content' => '<span class="text-success">Create MovimVehiOficial success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                ];
            } else {
                return [
                    'title' => "Crear Movimiento",
                    'content' => $this->renderAjax('_form', [
                        'model' => $model,
                        'vehiculos' => $vehiculos,
                        'choferes' => $choferes, // Pasamos los choferes
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idmovimiento]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'vehiculos' => $vehiculos,
                    'choferes' => $choferes, // Pasamos los choferes a la vista
                ]);
            }
        }
    }








    /**
     * Updates an existing MovimVehiOficial model.
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
                    'title' => "Actualizar Movimiento" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "MovimVehiOficial #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Actualizar Movimiento" . $id,
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
                return $this->redirect(['view', 'id' => $model->idmovimiento]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing MovimVehiOficial model.
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
     * Delete multiple existing MovimVehiOficial model.
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
     * Finds the MovimVehiOficial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MovimVehiOficial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MovimVehiOficial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
