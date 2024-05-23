<?php
use yii\helpers\Url;

return [

   
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
        'value' => function($model){
            return date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
        },
        'width' => '100px',

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'detalle',
        'width' => '500px',
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'km',
        'width' => '100px',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => '50px',
        'template' => '{update} {delete}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Estás a punto de eliminar el item',
                          'data-confirm-message'=>'<div class="row">
                                <div class="alert alert-danger col-md-8 col-md-offset-2 text-center">
                                    <b>¿Está seguro de continuar?</b><br>
                                    Haga click en OK para confirmar.
                                </div></div>'], 
    ],

];   