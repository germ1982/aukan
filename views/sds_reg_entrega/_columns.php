<?php

use app\models\Sds_stk_articulo;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;


if(!($idregistro==''))
        {$aux = true;}
    else
        {$aux = false;}

return [

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idregistroentrega',
        'width' => '10%',
        'label' => 'Id Entrega',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idregistro',
        'filterInputOptions' => ['value' => $idregistro,'class' => 'form-control'],
        'width' => '10%',
        'label' => 'Id Registro',
        'hidden'=>$aux,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarticulo',
        'label' => 'Articulo',
        'value' => function ($model) {
                $idarticulo = $model->idarticulo;
                if ($idarticulo != null) 
                    {
                        $articulo = Sds_stk_articulo::findOne($idarticulo);
                        return $articulo->descripcion;
                    }
                return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Sds_stk_articulo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'idarticulo','descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar articulo...'],
        'format' => 'raw',
        'width' => '60%',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantidad',
        'width' => '10%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => '10%',
        'template' => '{delete}',
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
    ],

];   