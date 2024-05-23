<?php
use yii\helpers\Url;
use app\models\Mds_seg_usuario;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'autor2',
        'label' => 'Autor',
        'value' => function ($model) {
            $un_seg_usuario=Mds_seg_usuario::findOne($model->autor);
            $cad=$un_seg_usuario->nombre.' '.$un_seg_usuario->apellido;
            return $cad;
        },         
        'format' => 'raw',
        'width' => '20%',                     
    ],
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
    [
        'attribute' => 'fecha_publicacion',
        'width' => '12%',
        'label' => 'Fecha Publicación',
        'value' => function ($model) {
            $fc = date_create($model->fecha_publicacion);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
   
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
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta Seguro?',
                          'data-confirm-message'=>'Esta usted seguro de eliminar este registro?'], 
    ],

];   