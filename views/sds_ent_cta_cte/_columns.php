<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_ent_entrega;
use yii\helpers\Url;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_hora',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'filter' => false
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'responsable',
        'value'=> function ($model){
            $entrega = Sds_ent_entrega::findOne($model->identrega);            
            if ($model->debe !=0){
                $entregaEmisor = Sds_ent_entrega::findOne($entrega->emisor);
                if ($entregaEmisor==null){
                    return "Primer Ingreso";
                }
                $responsable = Sds_com_configuracion::findOne($entregaEmisor->receptor)->descripcion;
                return "Entrega de ".$responsable;
            }
            if ($entrega->receptor == null){
                return "Entrega a DNI ".$entrega->dni;    
            }
            $responsable = Sds_com_configuracion::findOne($entrega->receptor)->descripcion;
            return "Entrega a ".$responsable;
        },
        'filter' => false
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'debe',
        'filter' => false
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'haber',
        'filter' => false
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'saldo_acumulado',                
        'filter' => false
    ],
   /*  [
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
    ], */

];   