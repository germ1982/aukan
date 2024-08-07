<?php

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$mysql_tipo = "SELECT * from configuracion where activo=1 and id_configuracion_tipo = 12
order by descripcion";

$mysql_marca = "SELECT * from configuracion where activo=1 and id_configuracion_tipo = 14
order by descripcion";

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idarticulo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
   
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtipo',
        'value' => function ($model) {
            $id = $model->idtipo;
            if ($id != null) {
                $tipo = Configuracion::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::findBySql($mysql_tipo)->all(), 'id_configuracion_tipo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de Dato...'],
        'format' => 'raw',
    ],
   
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idmarca',
        'value' => function ($model) {
            $id = $model->idmarca;
            if ($id != null) {
                $tipo = Configuracion::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::findBySql($mysql_marca)->all(), 'id_configuracion_tipo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de Dato...'],
        'format' => 'raw',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'modelo',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idrubro',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_unidad_medida',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'activo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'imagen',
    // ],
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