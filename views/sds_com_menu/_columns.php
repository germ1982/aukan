<?php

use app\models\Mds_seg_item;
use app\models\Sds_com_menu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'padre',
        'value'=>function($model){
            if($model->padre!=null){ 
                $padre=Sds_com_menu::findOne($model->padre);
                return $model->padre." - ".$padre->descripcion;
            }
            return '';
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> ArrayHelper::map(
            Sds_com_menu::find()->where(['padre'=>null])->orderBy(['idmenu'=>SORT_ASC])->all(),
            'idmenu',
            function($model){
                return $model->idmenu .' - '.$model->descripcion;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Padre...'],
        'width' => '15%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'orden',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ruta',
        'value'=>function($model){
            if($model->ruta!=null){ 
                return $model->ruta;
            }
            return '';
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iditem',
        'label'=>'Seg_item',
        'value'=>function($model){
            if($model->iditem!=null){ 
                $descripcion=Mds_seg_item::findOne($model->iditem);
                return $model->iditem." - ".$descripcion->descripcion;
            }
            return '';
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> ArrayHelper::map(
            Mds_seg_item::find()->orderBy(['iditem'=>SORT_ASC])->all(),
            'iditem',
            function($model){
                return $model->iditem.' - '.$model->descripcion;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Item...'],
        'width' => '15%'
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'orden',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'icono',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template'=>'{view} {update} {delete}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'delete'=>function($url, $model){
                $child=Sds_com_menu::find()->where(['padre'=>$model->idmenu])->all();
                if($child==null){
                    $url =  Url::to(['/sds_com_menu/delete', 'id' => $model->idmenu]);
                    return Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                        'title' => "Borrar Menú",
                        'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '',
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Vas a borrar este menú',
                        'data-confirm-message'=>'<div class="alert alert-danger">¿Estas seguro?</div>',
                    ]);
                }else{
                    return '<span class= "glyphicon glyphicon-trash"></span>';
                }
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'], 
    ],

];   