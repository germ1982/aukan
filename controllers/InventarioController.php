<?php

namespace app\controllers;

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
use Yii;
use app\models\Inventario;
use app\models\InventarioSearch;
use app\models\LogPlataforma;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * InventarioController implements the CRUD actions for Inventario model.
 */
class InventarioController extends Controller
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
     * Lists all Inventario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Inventario model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Item Id " . $id,
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
     * Creates a new Persona model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($origen_alta = 0, $iddispositivo = null)
    {
        $request = Yii::$app->request;
        $model = new Inventario();

        $model->origen_alta = $origen_alta;

        if ($origen_alta == 1 && $iddispositivo != null) {
            $model->iddispositivo = $iddispositivo;
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Item',
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



                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::INVENTARIO_INFORMATICA, ConstantesGlobales::CREACION, $model->idinventario);
                    return [
                        'title' => "Nuevo Item",
                        'content' => '<span class="text-success">Inventario Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Nuevo Item Faltan datos!!! Complete Los datos Faltantes!!!",
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
                    'title' => 'Editar Item',
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


                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::INVENTARIO_INFORMATICA, ConstantesGlobales::MODIFICACION, $model->idinventario);
                    return [
                        'title' => "Editar Item",
                        'content' => '<span class="text-success">Item Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Editar Item Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }
    }

    /**
     * Delete an existing Inventario model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(ConstantesGlobales::INVENTARIO_INFORMATICA, ConstantesGlobales::ELIMINACION, $id);
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
     * Delete multiple existing Inventario model.
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
     * Finds the Inventario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inventario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inventario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // Carga la vista principal con la lista de tipos de artículos

    public function actionIndex_articulos_especiales()
{
    // Consulta con doble JOIN para evaluar Red y CPU al mismo tiempo
    $sql = "SELECT 
                c.id_configuracion,
                c.descripcion,
                IF(cr.id_configuracion IS NOT NULL, 1, 0) AS tiene_red,
                IF(cc.id_configuracion IS NOT NULL, 1, 0) AS tiene_cpu
            FROM configuracion c
            LEFT JOIN configuracion cr 
                ON cr.descripcion = CAST(c.id_configuracion AS CHAR)
               AND cr.id_configuracion_tipo = :tipoRed
            LEFT JOIN configuracion cc 
                ON cc.descripcion = CAST(c.id_configuracion AS CHAR)
               AND cc.id_configuracion_tipo = :tipoCpu
            WHERE c.id_configuracion_tipo = :tipoArticulo
            ORDER BY c.descripcion ASC";

    $articulosRaw = Yii::$app->db->createCommand($sql, [
        ':tipoArticulo' => ConfiguracionTipo::TIPO_ARTICULO,
        ':tipoRed'      => ConfiguracionTipo::TIPO_ARTICULO_RED,
        ':tipoCpu'      => ConfiguracionTipo::TIPO_ARTICULO_CPU,
    ])->queryAll();

    // Mapeo a objetos para el helper
    $articulos = array_map(function($item) {
        return (object)$item;
    }, $articulosRaw);

    $dataProvider = new ArrayDataProvider([
        'allModels' => $articulos,
        'key' => function ($model) {
            return $model->id_configuracion;
        },
        'pagination' => false,
    ]);

    return $this->render('index_articulos_especiales', [
        'dataProvider' => $dataProvider,
    ]);
}

    // Endpoint AJAX que guarda o elimina el registro pivote según el checkbox
    public function actionToggle_articulo_red()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $idConfiguracion = $request->post('id_configuracion'); // ID del tipo de artículo
        $estado          = $request->post('estado');          // 1 (activado) u 0 (desactivado)

        if (!$idConfiguracion) {
            return ['success' => false, 'message' => 'Faltan parámetros.'];
        }

        if ($estado == 1) {
            // Si se activó y no existe, creamos el registro pivote
            $existe = Configuracion::find()
                ->where([
                    'id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_RED,
                    'descripcion'           => (string)$idConfiguracion
                ])->exists();

            if (!$existe) {
                $nuevo = new Configuracion();
                $nuevo->id_configuracion_tipo = ConfiguracionTipo::TIPO_ARTICULO_RED;
                $nuevo->descripcion           = (string)$idConfiguracion;
                $nuevo->activo                = 1;
                $nuevo->save(false);
            }
        } else {
            // Si se desactivó, eliminamos la relación de la tabla
            Configuracion::deleteAll([
                'id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_RED,
                'descripcion'           => (string)$idConfiguracion
            ]);
        }

        return ['success' => true];
    }

    public function actionToggle_articulo_cpu()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Recepción de parámetros POST
    $idConfiguracion = Yii::$app->request->post('id_configuracion');
    $estado = Yii::$app->request->post('estado');

    if (!$idConfiguracion) {
        return ['success' => false, 'message' => 'Falta el ID de configuración.'];
    }

    try {
        if ($estado == 1) {
            // Se activa: Si no existe el registro en la tabla configuracion, lo creamos
            $existe = Configuracion::find()
                ->where([
                    'descripcion' => (string)$idConfiguracion,
                    'id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_CPU,
                ])->exists();

            if (!$existe) {
                $model = new Configuracion();
                $model->descripcion = (string)$idConfiguracion;
                $model->id_configuracion_tipo = ConfiguracionTipo::TIPO_ARTICULO_CPU;
                $model->activo = 1;
                $model->save(false);
            }
        } else {
            // Se desactiva: Eliminamos el registro asociado
            Configuracion::deleteAll([
                'descripcion' => (string)$idConfiguracion,
                'id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_CPU,
            ]);
        }

        return ['success' => true];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
}
