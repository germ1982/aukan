<?php
use yii\helpers\Url;

return [   
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cupo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'activo',
        'value'=>function($model){
            if($model->activo){
                return "Si";
            }else{
                return "No";
            }
        },
        'filter'=>[
            0=>"No",
            1=>"Si"
        ]
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
                          'data-confirm-title'=>'¿Está seguro que desea eliminar los datos de esta oficina?',
                          'data-confirm-message'=>'<strong class="text-danger col-md-12" style="text-align:center;">¡Confirme que desea eliminar este item!</strong><br>'], 
    ],

];   