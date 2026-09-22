<?php

namespace app\controllers;

use Yii;
use app\models\RegistroTecnicoIncidencia;
use app\models\RegistroTecnicoIncidenciaMovimiento;
use app\models\RegistroTecnicoIncidenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Registro_tecnico_incidenciaController implements the CRUD actions for RegistroTecnicoIncidencia model.
 */
class Registro_tecnico_incidenciaController extends Controller
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
     * Lists all RegistroTecnicoIncidencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistroTecnicoIncidenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RegistroTecnicoIncidencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "RegistroTecnicoIncidencia #" . $id,
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
     * Creates a new RegistroTecnicoIncidencia model.
     * For ajax request will return json response
     *
     * @param int|null $idregistro
     * @return mixed
     */
    public function actionCreate($idregistro = null)
    {
        $request = Yii::$app->request;
        $model = new RegistroTecnicoIncidencia();
        $registroTecnico = null;

        // Cargar el registro técnico de origen si viene el ID por parámetro
        if ($idregistro !== null) {
            $model->idregistro = $idregistro;
            $registroTecnico = \app\models\RegistroTecnico::findOne($idregistro);
            $model->iddispositivo = $registroTecnico->iddispositivo;
            $model->idingresante = $registroTecnico->idsolicitante;
        }

        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => "Crear Incidencia",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'registroTecnico' => $registroTecnico, // <--- Aquí
                    ]),
                    'footer' => \yii\helpers\Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        \yii\helpers\Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    // 1. Guardar modelo principal de la incidencia
                    if (!$model->save()) {
                        throw new \Exception('Error al guardar la incidencia.');
                    }

                    // 2. Recuperar array de movimientos deserializado desde el input hidden
                    $movimientosJson = $request->post('movimientosArrayInput', '[]');
                    $movimientos = json_decode($movimientosJson, true) ?? [];

                    foreach ($movimientos as $movData) {
                        $mov = new RegistroTecnicoIncidenciaMovimiento();
                        $mov->idincidencia = $model->idincidencia;
                        $mov->fecha = date('Y-m-d', strtotime(str_replace('/', '-', $movData['fecha'])));
                        $mov->hora = $movData['hora'];
                        $mov->idmovimiento_nombre = $movData['idmovimiento_nombre'];
                        $mov->descripcion = $movData['descripcion'];

                        if (!$mov->save()) {
                            throw new \Exception('Error al guardar un movimiento de la incidencia.');
                        }

                        // 3. Insertar las asistencias intervinientes mediante DAO directo
                        if (!empty($movData['asistentes']) && is_array($movData['asistentes'])) {
                            foreach ($movData['asistentes'] as $idTecnico) {
                                Yii::$app->db->createCommand()->insert('registro_tecnico_incidencia_movimiento_asistencia', [
                                    'idmovimiento' => $mov->idmovimiento,
                                    'idtecnico' => $idTecnico,
                                ])->execute();
                            }
                        }
                    }

                    $transaction->commit();

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear Incidencia",
                        'content' => '<span class="text-success">Incidencia registrada correctamente</span>',
                        'footer' => \yii\helpers\Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
                    ];
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return [
                        'title' => "Crear Incidencia",
                        'content' => '<span class="text-danger">' . $e->getMessage() . '</span>',
                        'footer' => \yii\helpers\Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal'])
                    ];
                }
            } else {
                return [
                    'title' => "Crear Incidencia",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'registroTecnico' => $registroTecnico, // <--- Aquí
                    ]),
                    'footer' => \yii\helpers\Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        \yii\helpers\Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            }
        } else {
            /*
            * Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idincidencia]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'registroTecnico' => $registroTecnico, // <--- Y aquí
                ]);
            }
        }
    }


    /**
     * Creates a new RegistroTecnicoIncidencia model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate_old($idregistro = null)
    {
        $request = Yii::$app->request;
        $model = new RegistroTecnicoIncidencia();

        // Si viene el idregistro desde la URL (o desde el botón de la grilla), se lo asignamos
        if ($idregistro !== null) {
            $model->idregistro = $idregistro;
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear Nueva Incidencia",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title' => "Crear Nueva Incidencia",
                    'content' => '<span class="text-success">Incidencia creada con éxito.</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear Mas', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Crear Nueva Incidencia",
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
                return $this->redirect(['view', 'id' => $model->idincidencia]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing RegistroTecnicoIncidencia model.
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
                    'title' => "Update RegistroTecnicoIncidencia #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "RegistroTecnicoIncidencia #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update RegistroTecnicoIncidencia #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idincidencia]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing RegistroTecnicoIncidencia model.
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
     * Delete multiple existing RegistroTecnicoIncidencia model.
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
     * Finds the RegistroTecnicoIncidencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistroTecnicoIncidencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistroTecnicoIncidencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Renders the partial view for the movements grid via AJAX.
     *
     * @return string
     */
    public function actionGrilla_movimientos()
    {
        // Recuperar JSON enviado por POST desde JS
        $movimientosJson = Yii::$app->request->post('movimientos', '[]');
        $movimientos = json_decode($movimientosJson, true) ?? [];

        return $this->renderAjax('_grilla_movimientos', [
            'movimientos' => $movimientos,
        ]);
    }
}
