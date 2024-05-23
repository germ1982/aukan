<?php
use yii\helpers\Url;

return [
    /*[
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],*/
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'autor',
        'width' => '20%'
    ],
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'comment_status',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'comment_count',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'titulo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'publicado',
        'value' => function ($model) {
            return $model->publicado == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],    
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contenido',
    ],*/
    [   'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'fecha_publicacion',     
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_publicacion);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },        
    ],
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'activo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fechamodificacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'horamodificacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fechaalta',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'horaalta',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_publicacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'publicado',
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
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta usted seguro?',
                          'data-confirm-message'=>'Seguro desea borrar esta publicacion institucional?'], 
    ],

];   