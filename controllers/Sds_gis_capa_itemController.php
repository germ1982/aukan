<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\s;
use app\models\Sds_gis_item_tematica;
use Yii;
use app\models\Sds_gis_capa_item;
use app\models\Sds_gis_capa_itemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * Sds_gis_capa_itemController implements the CRUD actions for Sds_gis_capa_item model.
 */
class Sds_gis_capa_itemController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout', 'get_capaitem_by_idcapa'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_GIS_CAPAS,
                        ],
                    ],
                    [
                        'actions' => ['get_capaitem_by_idcapa'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_RENDICION,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_gis_capa_item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_gis_capa_itemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_gis_capa_item', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_gis_capa_item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_gis_capa_item', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_gis_capa_item #" . $id,
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
     * Creates a new Sds_gis_capa_item model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_gis_capa_item();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Sds_gis_capa_item",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/giscapaitem', $extension);
                    $ruta = 'uploads/giscapaitem/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->imagen = $ruta . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/giscapaitem/' . $nuevo_nombre);
                } else {
                };

                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_gis_capa_item', $model->idcapaitem, $model->getAttributes());
                    return [
                        'title' => "Nuevo item de capa GIS" . $model->imagen,
                        'forceReload' => '#crud-datatable-pjax',
                        'content' => '<span class="text-success">El item capa gis se creó exitosamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                    ];
                } else {
                    return [
                        'title' => "Nueva item de Capa GIS",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                return [
                    'title' => "Nuevo item de capa",
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
            //
            if ($model->load($request->post())) {
                $model->idubicacion = $request->post()['Sds_gis_capa_item']['idubicacion'];
                if ($request->post()['Sds_gis_capa_item']['tipo'] == 2) {
                    $model->coleccion_coordenadas = $request->post()['Sds_gis_capa_item']['coleccion_coordenadas'];
                    $lat_long_parseado = json_decode($request->post()['Sds_gis_capa_item']['coleccion_coordenadas'], true);
                    /*En los campos lat y long se guarda la primera coordenada de la coleccion*/
                    $model->latitud = $lat_long_parseado['coordinates'][0][0];
                    $model->longitud = $lat_long_parseado['coordinates'][0][1];
                    //$model->refresh();
                };

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/giscapaitem', $extension);
                    $ruta = 'uploads/giscapaitem/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->imagen = $ruta . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/giscapaitem/' . $nuevo_nombre);
                } else {
                };
                if ($model->save()) {
                    if (isset($request->post()['Sds_gis_capa_item']['tematicas'])) {
                        $this->guardarTematicas($request->post()['Sds_gis_capa_item']['tematicas'], $model);
                    }

                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_gis_capa_item', $model->idcapaitem, $model->getAttributes());
                    return $this->redirect(['index']);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
                //print_r($model);
                //die();
                //$this->guardarTematica($request->post()['Sds_gis_capa_item']['tematica'],$model);


            } else {
                //print_r($model->getErrors());
                //die();
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_gis_capa_item model.
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
                    'title' => "Update Sds_gis_capa_item #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/giscapaitem', $extension);
                    $ruta = 'uploads/giscapaitem/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->imagen = $ruta . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/giscapaitem/' . $nuevo_nombre);
                } else {
                };

                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_gis_capa_item', $model->idcapaitem, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Editar item de capa GIS",
                        'content' => '<span class="text-success">El item capa gis se editó exitosamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])


                    ];
                } else {
                    return [
                        'title' => "Editar item de Capa GIS",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                return [
                    'title' => "Actualizar item de capa #" . $id,
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
            if ($model->load($request->post())) {
                $model->idubicacion = $request->post()['Sds_gis_capa_item']['idubicacion'];
                if ($request->post()['Sds_gis_capa_item']['tipo'] == 2 && $request->post()['Sds_gis_capa_item']['coleccion_coordenadas']) {
                    $model->coleccion_coordenadas = $request->post()['Sds_gis_capa_item']['coleccion_coordenadas'];
                    $lat_long_parseado = json_decode($request->post()['Sds_gis_capa_item']['coleccion_coordenadas'], true);
                    /*En los campos lat y long se guarda la primera coordenada de la coleccion*/
                    $model->latitud = $lat_long_parseado['coordinates'][0][0];
                    $model->longitud = $lat_long_parseado['coordinates'][0][1];
                };

                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/giscapaitem', $extension);
                    $ruta = 'uploads/giscapaitem/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->imagen = $ruta . $nuevo_nombre;
                    $tmpfile->saveAs('uploads/giscapaitem/' . $nuevo_nombre);
                } else {
                };
                $model->save();
                $tematicas = $model->getTematicas();
                /*Se eliminan las tematicas asi se vuelven a crear*/
                foreach ($tematicas as $tematica) {
                    $tematica->delete();
                }
                if (isset($request->post()['Sds_gis_capa_item']['tematicas'])) {
                    $this->guardarTematicas($request->post()['Sds_gis_capa_item']['tematicas'], $model);
                }


                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_gis_capa_item', $model->idcapaitem, $model->getAttributes());
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_gis_capa_item model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_gis_capa_item', $id, $model->getAttributes());
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
     * Delete multiple existing Sds_gis_capa_item model.
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
     * Finds the Sds_gis_capa_item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_gis_capa_item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_gis_capa_item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function guardarTematicas($tematicas, $sdsCapaItem)
    {
        foreach ($tematicas as $tematicaId) {
            $model = new Sds_gis_item_tematica();
            $model->iditem = $sdsCapaItem->idcapaitem;
            $model->idtematica = $tematicaId;
            $model->save();
        }
    }


    // INICIO CODIGO PARA CONCURSO
    public function actionGet_capaitem_by_idcapa($idcapa)
    {
        $capasItem = Sds_gis_capa_item::find()
            ->where(["idcapa" => $idcapa])
            ->andWhere(["activo" => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->all();

        $capasItemOptions = "";
        if (sizeof($capasItem) > 0) {
            foreach ($capasItem as $capaItem) {
                $capasItemOptions .= "<option value='" . $capaItem->idcapaitem . "'>" .
                    $capaItem->descripcion . "</option>";
            }
        }
        return $capasItemOptions;
    }
}
