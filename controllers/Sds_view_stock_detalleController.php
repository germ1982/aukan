<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_view_stock_detalle;
use ArrayObject;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

class Sds_view_stock_detalleController extends \yii\web\Controller
{
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
                        'actions' => [
                            'index', 'get_item_recepcion', 'get_deposito_solo',
                            'get_deposito', 'get_stock'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::STK_ARTICULO, Mds_seg_item::STK_ENTREGA,
                            Mds_seg_item::STK_MOVIMIENTO
                        ],
                    ],
                ],

            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGet_item_recepcion($id_articulo = null, $deposito = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $stock_detalle = Sds_view_stock_detalle::find()
            ->addSelect('item_recepcion,idarticulo,deposito,sum(cantidad) cantidad')
            ->where(['idarticulo' => $id_articulo, 'deposito' => $deposito])
            ->groupBy('item_recepcion,idarticulo,deposito')
            ->all();
        $out_item_recepcion = [];
        foreach ($stock_detalle as $stock) {
            $item_recepcion = Sds_stk_recepcion_item::findOne($stock->item_recepcion);
            $item_recepcion->cantidad = $stock->cantidad;
            if ($item_recepcion->cantidad > 0) {
                array_push($out_item_recepcion, $item_recepcion);
            }
        }
        return $out_item_recepcion;
    }

    public function actionGet_deposito_solo($iddeposito = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out_deposito = [];

        $depositos = Sds_stk_deposito::find()->where("iddeposito = $iddeposito")->all();
        foreach ($depositos as $deposito) {

            array_push($out_deposito, $deposito);
        }

        return $out_deposito;
    }

    public function actionGet_deposito($id_articulo = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $stock_detalle = Sds_view_stock_detalle::find()
            ->addSelect('item_recepcion,idarticulo,deposito,sum(cantidad) cantidad')
            ->where(['idarticulo' => $id_articulo])
            ->groupBy('deposito')
            ->all();
        $out_deposito = [];
        foreach ($stock_detalle as $stock) {
            if ($stock->cantidad > 0) {
                $deposito = Sds_stk_deposito::findOne($stock->deposito);
                array_push($out_deposito, $deposito);
            }
        }

        return $out_deposito;
    }

    /******************************
     *VER PORQUE NO ESTÁ DEVOLVIENDO LOS DATOS ESPERADOS!!!!
     *******************************/
    public function actionGet_stock($id_articulo = null, $deposito = null, $item_recepcion = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = Sds_view_stock_detalle::find()
            ->addSelect('item_recepcion,idarticulo,deposito,sum(cantidad) cantidad')
            ->where(['idarticulo' => $id_articulo, 'deposito' => $deposito, 'item_recepcion' => $item_recepcion])
            ->groupBy('item_recepcion,idarticulo,deposito')
            ->one();

        return $out;
        /*
        $out_stock=[];
        foreach($stock_detalle as $stock){
            $model_stock=Sds_stk_recepcion_item::findOne($stock->idrecepcionitem);
            array_push($out_item_recepcion, $item_recepcion);
        }
        return $out_item_recepcion;
        */
    }
}
