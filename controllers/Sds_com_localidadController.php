<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_sys_log;
use app\models\Sds_com_localidad;
use app\models\Sds_com_localidadSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\filters\AccessControl;

class Sds_com_localidadController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'delete' => ['post'],
                    // 'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => [
                    'index', 'create', 'update', 'view', 'logout', 'get_id_localidad', 'get_localidades_similares',
                    'cmb_localidad', 'get_cmb_localidad', 'get_id_localidad_por_codigo_postal'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'view', 'logout',
                            'get_id_localidad', 'get_localidades_similares',
                            'cmb_localidad', 'get_cmb_localidad', 'get_id_localidad_por_codigo_postal'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            '@'
                        ],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new Sds_com_localidadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_localidad', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_localidad', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Localidad #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
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
        $model = new Sds_com_localidad();

        if ($request->isAjax) {
            /*
                *   Process for ajax request
                */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_localidad', $model->idlocalidad, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Localidad Nueva",
                        'content' => '<span class="text-success">Localidad Creada Exitosamente!</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otra', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),

                    ];
                } else {
                    $model->addError("descripcion", "Se pinchó!");
                }
            }
            return [
                'title' => "Nueva Localidad",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),

            ];
        } else {
            /*
                *   Process for non-ajax request
                */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_localidad', $model->idlocalidad, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idlocalidad]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
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
            if (!$request->isGet && $model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_localidad', $model->idlocalidad, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Localidad #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                ];
            }
            return [
                'title' => "Actualizar Localidad #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
            ];
        } else {
            /*
                *   Process for non-ajax request
                */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_localidad', $model->idlocalidad, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idlocalidad]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_com_localidad', $id, $model->getAttributes());
        }

        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {

            return $this->redirect(['index']);
        }
    }


    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {

            return $this->redirect(['index']);
        }
    }


    protected function findModel($id)
    {
        if (($model = Sds_com_localidad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_id_localidad($localidad)
    {
        $result = array();
        $model_localidad = Sds_com_localidad::find()->where("descripcion like '" . $localidad . "%'")->orderBy(["descripcion" => SORT_ASC])->limit(1)->one();
        if ($model_localidad != null) {
            $result = array("idlocalidad" => $model_localidad->idlocalidad);
        }
        return json_encode($result);
    }

    public function actionGet_localidades_similares($codigo_postal, $localidad)
    {
        $cmbLocalidades = "";
        $models_localidad = Sds_com_localidad::findBySql(
            "select idlocalidad,concat(loc.descripcion,' (',prov.descripcion,')') descripcion,codigo_postal
                from sds_com_localidad loc,sds_com_provincia prov
                where prov.idprovincia=loc.idprovincia
                order by loc.descripcion"
        )->all();
        if (!empty($models_localidad)) {
            foreach ($models_localidad as $loc) {
                $marcar_loc = $codigo_postal == $loc->codigo_postal;
                if (!$marcar_loc) {
                    $marcar_loc = stripos($this->eliminar_acentos(strtok($loc->descripcion, '(')), $this->eliminar_acentos($localidad));
                }
                $cmbLocalidades = $cmbLocalidades . "<option value='" . $loc->idlocalidad . "' " .
                    ($marcar_loc === false ? "" : "selected='selected'") . ">" . $loc->descripcion . "</option>";
            }
        }
        return $cmbLocalidades;
    }

    private function eliminar_acentos($cadena)
    {

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }

    // INICIO CODIGO PARA RUMBO
    public function actionCmb_localidad($idprovincia = -1)
    {
        $localidades = Sds_com_localidad::find()
            ->where(["idprovincia" => $idprovincia])
            //->andWhere(["idprovincia"=> $idprovincia] )
            ->orderBy(['descripcion' => SORT_ASC])
            ->all();
        //print_r( $localidades);
        $cmb_localidad = "";
        if (sizeof($localidades) > 0) {

            foreach ($localidades as $unalocalidad) {

                $cmb_localidad = $cmb_localidad . "<option value='" . $unalocalidad->idlocalidad . "'>" .
                    $unalocalidad->descripcion . "</option>";
            }
        } else {
            $cmb_localidad = "<option value=null></option>";
        }
        return $cmb_localidad;
    }
    //FIN CODIGO PARA RUMBO
    public static function actionGet_cmb_localidad($form, $model, $atributo, $id_combo, $label = null, $idprovincia = null)
    {
        /* Esta funcion crea el combo de localidades usando una sola linea desde donde sea invocada,
            se pasan como parametros el form y model que se este usando, el atributo del modelo para el que 
            se quiere usar el combo, el id del combo para usar con javascrip, opcional el label y opcional el 
            idprovincia, si trae idprovinci, se muestra solo las localidades de esa provincia, si viene null 
            muestra todas las localidades. */
        $aux_where = $idprovincia ? " and l.idprovincia = $idprovincia " : "";
        return $form->field($model, $atributo)->widget(Select2::classname(), [
            'data' => ArrayHelper::map(
                Sds_com_localidad::findBySql("SELECT * FROM sds_com_localidad  l 
                                                    WHERE l.activo = 1 $aux_where
                                                    ORDER BY trim(l.descripcion)")->all(),
                'idlocalidad',
                function ($model) {
                    return $model->descripcion . " - " . $model->codigo_postal;
                }
            ),
            'options' => ['placeholder' => '...', 'id' => $id_combo],
            'pluginOptions' => [
                'allowClear' => true
            ],

        ])->label($label);
    }


    public function actionGet_id_localidad_por_codigo_postal($codigo_postal)
    {
        $model_localidad = Sds_com_localidad::find()->where("codigo_postal like '" . $codigo_postal . "%'")->orderBy(["descripcion" => SORT_ASC])->limit(1)->one();
        //$model_localidad = Sds_com_localidad::findOne()->where("codigo_postal like '" . $codigo_postal . "%'")->orderBy(["descripcion" => SORT_ASC]);
        return $model_localidad->idlocalidad;
    }
}
