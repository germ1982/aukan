<?php
use yii\helpers\Url;
use app\models\Mds_data_categoria;


return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idtablero',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idcategoria',
        'value' => function ($model) {                                   
            $categoria= Mds_data_categoria::findOne($model->idcategoria);           
            return $categoria->nombre; 
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'url',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'orden',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'value'=> function($model) {
            return $model->estado ? 'Activo' : 'Inactivo';
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iditem',
        'value'=> function($model) {
            return $model->iditem ? 'Privado' : 'Público';
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{update} {delete}',
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Confirmar eliminación',
                          'data-confirm-message'=>'¿Realmente quiere eliminar el registro?'], 
    ],

];   