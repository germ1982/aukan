<?php

use app\models\Organismo;
use app\models\OrganismoDispositivo;
use app\models\Articulo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$sectores = "SELECT d.iddispositivo, concat(o.abreviatura,' - ', d.descripcion) as descripcion 
FROM organismo o 
join organismo_dispositivo d on o.idorganismo = d.idorganismo
where o.activo = 1 and d.activo = 1 and iddispositivo in (Select iddispositivo from inventario)
order by o.abreviatura, d.descripcion";
return [
    
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idInventario',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idarticulo',
        'value' => function ($model) {
                  $idarticulo = $model->idarticulo;
                  $articulo = Articulo::find($idarticulo); 
                  return   $articulo->descripcion;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Articulo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idarticulo', 'descripcion'),
            'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Articulo...'],
            'format' => 'raw',
            'width' => '20%',
    ],
[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddispositivo',
        'value' => function ($model) {
                  $iddispositivo = $model->iddispositivo;
                  $dispositivo = OrganismoDispositivo::find($iddispositivo); 
                  $organismo = Organismo::find($dispositivo->idorganismo);
                  return   "$organismo->descripcion - $iddispositivo->descripcion";
            },
            'filterType' => GridView::FILTER_SELECT2,
            //'filter' => ArrayHelper::map(OrganismoDispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'iddispositivo', 'descripcion'),
            'filter' => ArrayHelper::map(OrganismoDispositivo::findBySql("$sectores")->all(), 'iddispositivo', 'descripcion'),
            'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'dispositivo...'],
            'format' => 'raw',
            'width' => '20%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idempleado',
    ], 
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idestado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'activo',
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
        'deleteOptions'=>[
            'role'=>'modal-remote','title'=>'Delete', 
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Esta Seguro?',
            'data-confirm-message'=>'Esta seguro que quiere eliminar este item?'
        ], 
    ],

];   