<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Mds_seg_usuario;
return [
            /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idobservacion',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],    
    [
        'attribute' => 'fecha',
        'width' => '12%',
        'label' => 'Fecha Obs.',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },        
    ],    
    [
        'attribute' => 'hora',
        'width' => '10%',
        'label' => 'Hora',
               
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'autor',
        'label' => 'Autor Obs.',
        'value' => function ($model) {
            $un_seg_usuario=Mds_seg_usuario::findOne($model->id_persona);
            $cad=$un_seg_usuario->nombre.' '.$un_seg_usuario->apellido;
            return $cad;
        },         
        'format' => 'raw',
        'width' => '17%',                     
    ],
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_cv',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id_persona',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,       
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver Observación','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar Observación', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar Observación', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Eliminar Observación',
                          'data-confirm-message'=>'Seguro desea eliminar la observación?'], 
    ],

];   