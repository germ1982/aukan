<?php

use app\models\Sds_com_persona;
use yii\helpers\Url;
use app\models\Mds_org_contacto;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;


return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idequipo',
        'value'=>function($model){
            return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT);
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> ArrayHelper::map(
            $equipos,
            'idequipo',
            function($model){
                return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT);
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Equipo..'],
        'width' => '9%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ip',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idresponsable',
        'value'=>'responsable',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> ArrayHelper::map(
            $responsables,
            'idcontacto',
            function($model){
                return "$model->legajo - $model->nombre";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Responsable..'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observaciones',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Estas Seguro?',
                        'data-confirm-message'=>'<span class="text-danger">Estas seguro de que quieres eliminar este equipo?'],
    ],
];   