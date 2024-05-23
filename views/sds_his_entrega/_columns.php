<?php
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
            if(decode($model->servicio)){
                return utf8_decode($model->servicio);
            }else{
                return $model->servicio;
            }
        }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantidad',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'destino',
        'label'=>'Observaciones',
        'value'=>function($model){
            if(decode($model->destino)){
                return utf8_decode($model->destino);
            }else{
                return $model->destino;
            }
        }
    ],
];
function decode($string){
    $caracteres=['Ã','©'];
    for($i=0;count($caracteres)>$i;$i++){
        if(stripos($string, $caracteres[$i])){
            return true;
        }else{
            return false;
        }
    }
}