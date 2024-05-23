<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Sds_com_persona;
use app\models\Mds_inv_entrega;


return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'DNI',
        'width' => '10%',
    ],
    
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'persona',    
        'format' => 'raw',
        'width' => '28%',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'grupo_familiar',
        'label' =>'Grupo Familiar',
        'value' => function ($model) {

            return $model->grupo_familiar==-1 ? 'No sabe/No contesta' : $model->grupo_familiar;            
        },
        'width' => '10%',
    ],    
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
        'width' => '20%',
    ],    
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantplantines',
        'width' => '8%',
        'label' =>'#Plant.',
        'value' => function ($model) {
            $entregas = Mds_inv_entrega::find()->where(["idpersona" => $model->idpersona])->all();
            $cant=0;
            foreach ( $entregas as $una_entrega ) {
               $cant=$cant+ $una_entrega->cantidad;
            }
            return $cant;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantplantines',
        'width' => '8%',
        'label' =>'#Entregas',
        'value' => function ($model) {
            $entregas2 = Mds_inv_entrega::find()->where(["idpersona" => $model->idpersona])->all();
            $cant=0;
            foreach ( $entregas2 as $una_entrega2 ) {
               $cant++;
            }
            return $cant;
        },
    ],    
    
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'domicilio',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update}  {plantines} ' ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
            'plantines' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_inv_entrega/index_plantines', 'idpersona' => $model->idpersona, 
                ]);
                return Html::a('<i class="fas fa-seedling"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Registrar Plantines',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ], 
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   