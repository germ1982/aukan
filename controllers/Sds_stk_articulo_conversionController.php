<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_stk_articulo;
use Yii;
use app\models\Sds_stk_articulo_conversion;
use app\models\Sds_stk_articulo_conversionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Sds_stk_articulo_conversionController implements the CRUD actions for Sds_stk_articulo_conversion model.
 */
class Sds_stk_articulo_conversionController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'cmb_conversiones'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'cmb_conversiones'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_ARTICULO],
                    ],
                ],

            ],
        ];
    }

    /**
     * Lists all Sds_stk_articulo_conversion models.
     * @return mixed
     */
    public function actionIndex()
    {    //Generacion de LOG
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_articulo_conversion', null, array());
        //Generacion de LOG 
        $searchModel = new Sds_stk_articulo_conversionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $filter = $this->filter();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ]);
    }


    /**
     * Displays a single Sds_stk_articulo_conversion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_stk_articulo_conversion #" . $id,
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


    protected function filter($all = false)
    {
        /*$dataArticulo busca en el modelo el ID de organismo perteneciente al usuario logueado que se obtiene mediante una funcion de YII */
        $dataArticulo = Sds_stk_articulo::find()->where(['organismo' => Yii::$app->user->identity->organismo_stock])->all();
        $filter = [
            'articulos' => ArrayHelper::map(
                $dataArticulo,
                'idarticulo',
                'descripcion'
            ),

        ];
        return $filter;
    }

    /**
     * Creates a new Sds_stk_articulo_conversion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_articulo_conversion();
        $filter = $this->filter();
        if ($request->isAjax) {
            /*Process for ajax request*/
            $forceReload = '';
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Conversion creada con exito!'); //seteamos el mensaje del flash que anteriormente agragamos en el _form.php
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_articulo_conversion', $model->idarticuloconversion, $model->getAttributes());
                    $forceReload = '#crud-datatable-pjax';
                    $model = new Sds_stk_articulo_conversion();
                } else {
                    Yii::$app->session->setFlash('faild', 'Algo ha fallado...');
                }

                // return [
                //     'title'=> "Crear nueva conversion",
                //     'content'=>$this->renderAjax('create', [
                //         'model' => $model,
                //         'filter' => $filter,

                //     ]),
                //     'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                //             Html::a('Guardar',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                // ];
            }
            /*Le pasamos al action create la variable $filter, contenedora de la funcion filter, que contiene los articulos, esto lo hicimos por que, como esta "modularizado" debemos pasarlo dependiendo donde querramos utilizarlo. */
            return [
                'forceReload' => $forceReload,
                'title' => "Crear nueva conversion",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'filter' => $filter,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /* Process for non-ajax request */
            if ($model->load($request->post()) && $model->save()) {
                return $this->renderAjax('create', [
                    'model' => $model,
                    'filter' => $filter
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_stk_articulo_conversion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $filter = $this->filter();
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $forceReload = '';
        if ($request->isAjax) {
            /*Process for ajax request*/

            /*LLAMAMOS AL MENSAJE DE ERROR/EXITO*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                if ($model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_articulo_conversion', $model->idarticuloconversion, $model->getAttributes()); //creamos el log que es como un historial
                    $forceReload = '#crud-datatable-pjax';
                    Yii::$app->session->setFlash('success', 'Se ha editado correctamente...'); //seteamos el mensaje del flash que anteriormente agragamos en el _form.php
                } else {
                    Yii::$app->session->setFlash('faild', 'Algo ha fallado...');
                }
            }
            return [
                'forceReload' => $forceReload,
                'title' => "Actualizar Conversion",
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'filter' => $filter,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Editar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
            /*FIN MENSAJE ERROR/EXITO */
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idarticuloconversion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_stk_articulo_conversion model.
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
     * Delete multiple existing Sds_stk_articulo_conversion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /* public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){ */
    /*
            *   Process for ajax request
            */
    /* Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{ */
    /*
            *   Process for non-ajax request
            */
    /* return $this->redirect(['index']);
        }
       
    } */

    /**
     * Finds the Sds_stk_articulo_conversion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_stk_articulo_conversion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_stk_articulo_conversion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCmb_conversiones($idarticulo = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $articulos = Sds_stk_articulo_conversion::find()->where(['articulo_base' => $idarticulo])->all();
        return $articulos;
    }
}
