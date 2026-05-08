<?php

namespace app\controllers;

use Yii;
use app\models\Configuracion;
use app\models\ConfiguracionSearch;
use app\models\ConfiguracionTipo;
use app\models\LogPlataforma;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ConfiguracionController implements the CRUD actions for Configuracion model.
 */
class ConfiguracionController extends Controller
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
     * Lists all Configuracion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConfiguracionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize = 50;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Configuracion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Dato id " . $id,
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
     * Creates a new Configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Configuracion();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                // Si es una solicitud GET (para mostrar el formulario modal vacío)
                return [
                    'title' => 'Nuevo Datooooo',
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
                            'type' => 'submit', // Asegúrate de que este botón envíe el formulario
                        ]),
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(12, 1, $model->id_configuracion); // Asegúrate de que id_configuracion tenga un valor válido aquí
                    return [
                        //'forceReload' => '#crud-datatable-pjax', // Recarga el GridView en el modal
                        'title' => 'Nuevo Dato',
                        'content' => '<span class="text-success">Dato Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                            Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                } else {
                    return [
                        'title' => "Nuevo Dato, Faltan datos!!! Complete Los datos Faltantes!!!",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }

            return [
                'title' => "Nuevo Dato, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    public function actionCreate_tipo()
    {
        $request = Yii::$app->request;
        $model = new Configuracion();
        $model->id_configuracion_tipo = Yii::$app->request->get('id_configuracion_tipo');
        $model_tipo = ConfiguracionTipo::findOne($model->id_configuracion_tipo);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                // Si es una solicitud GET (para mostrar el formulario modal vacío)
                return [
                    'title' => 'Nuevo ' . $model_tipo->descripcion,
                    'content' => $this->renderAjax('create_tipo', [
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
                            'type' => 'submit', // Asegúrate de que este botón envíe el formulario
                        ]),
                ];
            } else if ($model->load($request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    //LogPlataforma::registrar(12, 1, $model->id_configuracion); // Asegúrate de que id_configuracion tenga un valor válido aquí
                    return [
                        //'forceReload' => '#crud-datatable-pjax', // Recarga el GridView en el modal
                        'title' => 'Nuevo ' . $model_tipo->descripcion,
                        'content' => '<span class="text-success">Dato Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                            Html::a('Crear Otro', ['create_tipo', 'id_configuracion_tipo' => $model->id_configuracion_tipo], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                } else {
                    return [
                        'title' => "Nuevo " . $model_tipo->descripcion . ", Faltan datos!!! Complete Los datos Faltantes!!!",
                        'content' => $this->renderAjax('create_tipo', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }

            return [
                'title' => "Nuevo " . $model_tipo->descripcion . ", Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create_tipo', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    // ... (actionUpdate refactorizada de manera similar) ...
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => 'Editar Dato Id: ' . $id,
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
            } else { // Si es una solicitud POST (se ha enviado el formulario)
                if ($model->load($request->post()) && $model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($model->save()) {
                            $transaction->commit();
                            LogPlataforma::registrar(12, 2, $model->id_configuracion);
                            return [
                                'forceReload' => '#crud-datatable-pjax', // Recarga el GridView
                                'title' => 'Editar Dato Id: ' . $id,
                                'content' => '<span class="text-success">Dato Editado Correctamente</span>',
                                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                            ];
                        } else {
                            $transaction->rollBack();
                            Yii::error('Error al actualizar Configuracion (AJAX): ' . print_r($model->getErrors(), true), __METHOD__);
                            return [
                                'title' => 'Error al Editar',
                                'content' => '<span class="text-danger">Hubo un error al guardar los cambios. Por favor, intente de nuevo.</span><br>' . Html::errorSummary($model),
                                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"]),
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::error('Excepción al actualizar Configuracion (AJAX): ' . $e->getMessage(), __METHOD__);
                        return [
                            'title' => 'Error Interno',
                            'content' => '<span class="text-danger">Ocurrió un error inesperado: ' . $e->getMessage() . '</span>',
                            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"]),
                        ];
                    }
                } else {
                    return [
                        'title' => 'Error de Validación',
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"]),
                    ];
                }
            }
        } else { // Si no es una solicitud AJAX
            if ($model->load($request->post()) && $model->save()) {
                LogPlataforma::registrar(12, 2, $model->id_configuracion);
                return $this->redirect(['view', 'id' => $model->id_configuracion]);
            } else {
                return $this->render('update', [ // Aquí deberías renderizar 'update' no 'create'
                    'model' => $model,
                ]);
            }
        }
    }
    // ... (findModel igual) ...

public function actionUpdate_tipo($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => 'Editar Dato Id: ' . $id,
                    'content' => $this->renderAjax('create_tipo', [
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
            } else { // Si es una solicitud POST (se ha enviado el formulario)
                if ($model->load($request->post()) && $model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($model->save()) {
                            $transaction->commit();
                            //LogPlataforma::registrar(12, 2, $model->id_configuracion);
                            return [
                                //'forceReload' => '#crud-datatable-pjax', // Recarga el GridView
                                'title' => 'Editar Dato Id: ' . $id,
                                'content' => '<span class="text-success">Dato Editado Correctamente</span>',
                                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                            ];
                        } else {
                            $transaction->rollBack();
                            Yii::error('Error al actualizar Configuracion (AJAX): ' . print_r($model->getErrors(), true), __METHOD__);
                            return [
                                'title' => 'Error al Editar',
                                'content' => '<span class="text-danger">Hubo un error al guardar los cambios. Por favor, intente de nuevo.</span><br>' . Html::errorSummary($model),
                                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"]),
                            ];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::error('Excepción al actualizar Configuracion (AJAX): ' . $e->getMessage(), __METHOD__);
                        return [
                            'title' => 'Error Interno',
                            'content' => '<span class="text-danger">Ocurrió un error inesperado: ' . $e->getMessage() . '</span>',
                            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"]),
                        ];
                    }
                } else {
                    return [
                        'title' => 'Error de Validación',
                        'content' => $this->renderAjax('update_tipo', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"]),
                    ];
                }
            }
        } else { // Si no es una solicitud AJAX
            if ($model->load($request->post()) && $model->save()) {
                LogPlataforma::registrar(12, 2, $model->id_configuracion);
                return $this->redirect(['view', 'id' => $model->id_configuracion]);
            } else {
                return $this->render('create_tipo', [ // Aquí deberías renderizar 'update' no 'create'
                    'model' => $model,
                ]);
            }
        }
    }
    /**
     * Delete an existing Configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(12, 3, $id);
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
     * Delete multiple existing Configuracion model.
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
     * Finds the Configuracion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Configuracion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Configuracion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* public function actionCreateConfigAjax()
    {
        $model = new Configuracion();
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    } */

    public function actionCreate_ext($tipo)
    {
        
        $model = new \app\models\Configuracion();
        $model->id_configuracion_tipo = $tipo;
    
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // guardás y devolvés mensaje
            if($model->save()){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'id' => $model->id_configuracion,
                    'text' => $model->descripcion, // o el campo visible en el combo
                ];
            } // o con validación si preferís

            
        }
        
    
        // si hubo errores o es la primera carga, devolvés el form (con errores si los hay)
        return $this->renderAjax('_form', [
            'model' => $model,
            'ocultarTipo' => true,
        ]);
    }

    


}
