<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_stk_orden_compra;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Mds_r_plantilla;


/**
 * Sds_com_configuracionController implements the CRUD actions for Sds_com_configuracion model.
 */
class Sds_com_configuracionController extends Controller
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
                'only' => [
                    'index', 'create', 'create_ext', 'crear_plantilla', 'update', 'delete',
                    'bulkDelete', 'view', 'cmb_idvariable', 'cmb_idvariable_update',
                    'cmb_iddimension_update', 'cmb_iddimension', 'cmb_proveedor', 'cmb_config',
                    'get_cmb_dropdown_configuracion', 'get_cmb_widget_configuracion'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'create_ext', 'crear_plantilla', 'update', 'delete',
                            'bulkDelete', 'view', 'cmb_idvariable', 'cmb_idvariable_update',
                            'cmb_iddimension_update', 'cmb_iddimension', 'cmb_proveedor', 'cmb_config',
                            'get_cmb_dropdown_configuracion', 'get_cmb_widget_configuracion'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_com_configuracion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_com_configuracionSearch();
        $searchModel->idconfiguraciontipo = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_configuracion', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_com_configuracion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_configuracion', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_com_configuracion #" . $id,
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

    public function actionCreate_ext($tipo)
    {
        $model_conf = new Sds_com_configuracion();
        $model_conf->idconfiguraciontipo = $tipo;
        $request = Yii::$app->request;
        if ($model_conf->load($request->post())) {
            $model_conf->idconfiguraciontipo = str_replace('r', '', $model_conf->idconfiguraciontipo);
            if ($model_conf->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_configuracion', $model_conf->idconfiguracion, $model_conf->getAttributes());
                echo $model_conf->idconfiguracion;
            } else {
                return $this->renderAjax('//sds_com_configuracion/create', [
                    'model' => $model_conf,
                    'botones' => true,
                ]);
            }
        } else {
            return $this->renderAjax('//sds_com_configuracion/create', [
                'model' => $model_conf,
                'botones' => true,
            ]);
        }
    }

    /**
     * Creates a new Sds_com_configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCrear_plantilla($tipo)
    {
        $request = Yii::$app->request;
        $model = new Sds_com_configuracion();
        $model->idconfiguraciontipo = $tipo;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'content' => $this->renderAjax('create_plantilla', [
                        'model' => $model,

                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_configuracion', $model->idconfiguracion, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Nueva Configuración",
                    'content' => '<span class="text-success">Creada Exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Agregar Otra', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Nueva Configuración",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_configuracion', $model->idconfiguracion, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idconfiguracion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionCreate($tipo)
    {
        $request = Yii::$app->request;
        $model = new Sds_com_configuracion();
        $model->idconfiguraciontipo = $tipo;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Configuración",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_configuracion', $model->idconfiguracion, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Nueva Configuración",
                    'content' => '<span class="text-success">Creada Exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Agregar Otra', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Nueva Configuración",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_configuracion', $model->idconfiguracion, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idconfiguracion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_com_configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCmb_idvariable($id_plantilla = -1)
    {
        $datos_plantilla = Mds_r_plantilla::find()
            ->where(["idtipoplantilla" => $id_plantilla])->groupBy(['variable_diagnostico'])
            ->all();
        $cmb_idvariable = "";
        if (sizeof($datos_plantilla) > 0) {
            $cmb_idvariable = $cmb_idvariable . "<option value='null'>Seleccione...</option>";
            foreach ($datos_plantilla as $unaplantilla) {
                $conf_plantilla = Sds_com_configuracion::find()
                    ->where(["idconfiguracion" => $unaplantilla->variable_diagnostico])
                    ->one();

                $cmb_idvariable = $cmb_idvariable . "<option value='" . $conf_plantilla->idconfiguracion . "' ";
                $cmb_idvariable = $cmb_idvariable .  ">" .
                    $conf_plantilla->descripcion . "</option>";
            }
        } else {
            $cmb_idvariable = "<option value=null>Seleccione...</option>";
        }
        return $cmb_idvariable;
    }

    public function actionCmb_idvariable_update($id_plantilla = -1, $idvariable)
    {
        $datos_plantilla = Mds_r_plantilla::find()
            ->where(["idtipoplantilla" => $id_plantilla])->groupBy(['variable_diagnostico'])
            ->all();
        $cmb_idvariable = "";
        if (sizeof($datos_plantilla) > 0) {
            $cmb_idvariable = $cmb_idvariable . "<option value='null'>Seleccione...</option>";
            foreach ($datos_plantilla as $unaplantilla) {
                $conf_plantilla = Sds_com_configuracion::find()
                    ->where(["idconfiguracion" => $unaplantilla->variable_diagnostico])
                    ->one();


                $cmb_idvariable = $cmb_idvariable . "<option value='" . $conf_plantilla->idconfiguracion . "' ";
                if ($conf_plantilla->idconfiguracion == $idvariable) {
                    $cmb_idvariable = $cmb_idvariable . " selected ";
                }
                $cmb_idvariable = $cmb_idvariable .  ">" .
                    $conf_plantilla->descripcion . "</option>";
            }
        } else {
            $cmb_idvariable = "<option value=null>Seleccione...</option>";
        }
        return $cmb_idvariable;
    }
    public function actionCmb_iddimension_update($idvariable = -1, $iddimension)
    {
        $datos_plantilla = mds_r_plantilla::find()
            ->where(["variable_diagnostico" => $idvariable])
            ->all();
        $cmb_dimension = "";
        if (sizeof($datos_plantilla) > 0) {
            $cmb_dimension = $cmb_dimension . "<option value='null'>Seleccione...</option>";
            foreach ($datos_plantilla as $unaplantilla) {
                $conf_plantilla = Sds_com_configuracion_tipo::find()
                    ->where(["idconfiguraciontipo" => $unaplantilla->dimension])
                    ->one();
                $cmb_dimension = $cmb_dimension . "<option value='" . $conf_plantilla->idconfiguraciontipo . "' ";

                if ($conf_plantilla->idconfiguraciontipo == $iddimension) {
                    $cmb_dimension = $cmb_dimension . " selected ";
                }
                $cmb_dimension = $cmb_dimension . ">";
                $cmb_dimension = $cmb_dimension . $conf_plantilla->descripcion . "</option>";
            }
        } else {
            $cmb_dimension = "<option value=null></option>";
        }
        return $cmb_dimension;
    }
    public function actionCmb_iddimension($idvariable = -1)
    {
        $datos_plantilla = mds_r_plantilla::find()
            ->where(["variable_diagnostico" => $idvariable])
            ->all();
        $cmb_dimension = "";
        if (sizeof($datos_plantilla) > 0) {
            $cmb_dimension = $cmb_dimension . "<option value='null'>Seleccione...</option>";
            foreach ($datos_plantilla as $unaplantilla) {
                $conf_plantilla = Sds_com_configuracion_tipo::find()
                    ->where(["idconfiguraciontipo" => $unaplantilla->dimension])
                    ->one();
                $cmb_dimension = $cmb_dimension . "<option value='" . $conf_plantilla->idconfiguraciontipo . "'>" .
                    $conf_plantilla->descripcion . "</option>";
            }
        } else {
            $cmb_dimension = "<option value=null></option>";
        }
        return $cmb_dimension;
    }
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
                    'title' => "Actualizar configuración #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_configuracion', $model->idconfiguracion, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Configuración #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Actualizar configuración #" . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_configuracion', $model->idconfiguracion, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idconfiguracion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_com_configuracion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_com_configuracion', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_com_configuracion model.
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
     * Finds the Sds_com_configuracion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_com_configuracion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_com_configuracion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCmb_proveedor($idordencompra = null)
    {
        $configs = Sds_com_configuracion::getConfiguraciones(69);
        if (sizeof($configs) > 0) {
            $option = "";
            $idproveedor = null;
            if ($idordencompra != null) {
                $oc = Sds_stk_orden_compra::findOne($idordencompra);
                $idproveedor = $oc->proveedor;
            }
            foreach ($configs as $conf) {
                if ($idproveedor == null || $idproveedor == $conf->idconfiguracion) {
                    $option = $option . "<option value='" . $conf->idconfiguracion . "'>" . $conf->descripcion . "</option>";
                }
            }
            return $option;
        } else {
            return "<option value=null></option>";
        }
    }

    public function actionCmb_config($tipo)
    {
        $configs = Sds_com_configuracion::getConfiguraciones(str_replace('r', '', $tipo));
        if (sizeof($configs) > 0) {
            $option = "";
            if ($tipo == Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA && strpos($tipo, 'r') <= 0) {
                $option = $option . "<option value='0'>Primer Ingreso</option>";
            }
            foreach ($configs as $conf) {
                $option = $option . "<option value='" . $conf->idconfiguracion . "'>" . $conf->descripcion . "</option>";
            }
            return $option;
        } else {
            return "<option value=null></option>";
        }
    }

    public static function actionGet_cmb_dropdown_configuracion($form, $model, $atributo, $id_combo, $label = null, $idtipo, $disabled = false)
    {
        /* Esta funcion crea el combo simple de configuraciones usando una sola linea desde donde sea invocada,
        se pasan como parametros el form y model que se este usando, el atributo del modelo para el que 
        se quiere usar el combo, el id del combo para usar con javascrip, opcional el label y el tipo de configuracion. */
        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1 order by descripcion";
        $datos = ArrayHelper::map(Sds_com_configuracion::findBySql($consulta)->all(), 'idconfiguracion', 'descripcion');
        return $form->field($model, $atributo)->dropdownList($datos, ['id' => $id_combo, 'disabled' => $disabled])->label($label);
    }

    public function actionGet_cmb_widget_configuracion($form, $model, $atributo, $id_combo, $label = null, $idtipo, $onclick = null)
    {
        /* Esta funcion crea el combo select2 de configuraciones para plantarlo por ajax adentro de un div.. */

        return $form->field($model, $atributo)->widget(Select2::classname(), [
            'data' => ArrayHelper::map(
                Sds_com_configuracion::getConfiguraciones($idtipo),
                'idconfiguracion',
                'descripcion'
            ),
            'options' => [
                'placeholder' => '...',
                'id' => $id_combo,
                'disabled' => false,
                'onchange' => $onclick
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label($label);
    }
}
