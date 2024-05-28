<?php
use yii\helpers\Url;
use app\models\Menu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

return [

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'title',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'icon_yii',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'link_yii',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'padre',
        'value' => function ($model) {
            $idpadre = $model->padre;
            if($idpadre == 0) return 'Raiz';
            if ($idpadre != null) {
                $padre = Menu::findOne($idpadre);
                return $padre->title;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Menu::find()->orderBy(['title' => SORT_ASC])->all(), 'id', 'title'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Padre...'],
        'format' => 'raw',
    ],

    [
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
    ],

];   