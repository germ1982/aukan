<?php
use yii\helpers\Url;
use app\models\Sds_stk_articulo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Mds_seg_usuario;

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$id_organismo = $usuario->organismo_stock;

return [
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idarticulo',
    ], */
    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idarticulo',
        'format' => 'html',
        'value' => function ($model) {

            if ($model->idarticulo != null) {
                $articulo = Sds_stk_articulo::findOne($model->idarticulo);
                return $articulo->descripcion;
            }
            return "";
        },
        'label' => false,

    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Articulo',
        'attribute' => 'idarticulo',
        'format' => 'html',
        'value' => function ($model) {


            if ($model->idarticulo != null) {
                $articulo = Sds_stk_articulo::findOne($model->idarticulo);
                return $articulo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Sds_stk_articulo::findBySql("select idarticulo,descripcion from sds_stk_articulo where idarticulo in(select DISTINCT(idarticulo) from view_stock_deposito) and organismo = $id_organismo order by descripcion")->all(),
            'idarticulo','descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Articulo...'],


    ],
    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'deposito',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organismo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'deposito_descripcion',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'detalle_depositos',
        'format' => 'html',
        'label' => false,
/*         'value' => function ($model) {

            $aux = " $model->detalle_depositos";
            return $aux;
        }, */
    ],
    /*[
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ], */

];   