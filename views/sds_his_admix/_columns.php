<?php
use yii\helpers\Url;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
        'value'=>function ($model) {
            $fecha = date_create($model->fecha);
            $fecha = date_format($fecha, 'd/m/Y');
            return $fecha;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'servicio',
        'value'=>function($model){
            if($model->servicio===null){
                return 'No declarado';
            }else{
                return $model->servicio;

            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'importe',
        'value'=>function($model){
            if($model->importe===null){
                return 'No declarado';
            }else{
                return round($model->importe, 2);
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'extracto',
        'value'=>function($model){
            if($model->extracto===null){
                return '';
            }else{
                return $model->extracto;
            }
        }
    ],
    

];   