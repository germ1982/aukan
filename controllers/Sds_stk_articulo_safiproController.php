<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Sds_stk_articulo;
use Yii;
use app\models\Sds_stk_articulo_safipro;
use app\models\Sds_stk_articulo_safiproSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;

/**
 * Sds_stk_articulo_safiproController implements the CRUD actions for Sds_stk_articulo_safipro model.
 */
class Sds_stk_articulo_safiproController extends Controller
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
                    'validar_item_existente', 'create_ajax', 'grilla_items', 'delete'
                ],
                'rules' => [
                    [
                        'actions' => ['validar_item_existente', 'create_ajax', 'grilla_items', 'delete'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_ARTICULO],
                    ],
                ],

            ],
        ];
    }

    public function actionValidar_item_existente($idarticulo, $clase, $item)
    {
        $aux = 0;
        $model_item = Sds_stk_articulo_safipro::find()->where("idarticulo = $idarticulo and clase = $clase and item = $item")->one();
        if ($model_item) {
            $model_articulo = Sds_stk_articulo::findOne($idarticulo);
            $aux  = $model_articulo->descripcion;
        }
        return $aux;
    }

    public function actionCreate_ajax($idarticulo, $clase, $item)
    {
        $model = new Sds_stk_articulo_safipro();
        $model->idarticulo = $idarticulo;
        $model->clase = $clase;
        $model->item = $item;

        if ($model->save()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_stk_articulo_safipro', $model->idarticulosafipro, $model->getAttributes());
            return 1;
        } else {
            return 0;
        }
    }

    public function actionGrilla_items($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_articulo_safipro::findBySql('Select * from sds_stk_articulo_safipro where idarticulo = ' . $id),
            'sort' => ['attributes' => ['idarticulosafipro', 'clase', 'item'],]
        ]);

        $dataProvider->pagination = false;

        $aux_alta = Html::button('<i class="glyphicon glyphicon-plus"></i>', [
            'class' => 'btn btn-primary',
            'id' => 'btnItem',
            'title' => "Nuevo Item",
            'data-toggle' => 'tooltip',
            'onclick' => "js:mostrar_abm_item();"
        ]);


        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'idarticulosafipro',
                    'headerOptions' => ['style' => 'width:30%'],
                    'label' => 'ID',
                ],
                [
                    'attribute' => 'clase',
                    'headerOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'item',
                    'headerOptions' => ['style' => 'width:30%'],
                ],
                [
                    'header' =>  $aux_alta,
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:5%'],
                    'template' => ' {eliminar}',  // the default buttons + your custom button
                    'buttons' => [
                        'eliminar' => function ($url, $model) {
                            $id_item = $model->idarticulosafipro;
                            return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'title' => "Eliminar Item",
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-link',
                                'onclick' => "js:eliminar_item($id_item);"
                            ]);
                        },
                    ]
                ]

            ],
        ]);
    }

    /**
     * Delete an existing Sds_stk_articulo_safipro model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$request = Yii::$app->request;
        $model = $this->findModel($id);
        $ban = 0;
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_stk_articulo_safipro', $id, $model->getAttributes());
            $ban = 1;
        }
        return $ban;
    }

    /*     public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{

            return $this->redirect(['index']);
        }


    } */

    /**
     * Delete multiple existing Sds_stk_articulo_safipro model.
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
    /*  Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{ */
    /*
            *   Process for non-ajax request
            */
    /*  return $this->redirect(['index']);
        }
       
    } */

    /**
     * Finds the Sds_stk_articulo_safipro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_stk_articulo_safipro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_stk_articulo_safipro::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
