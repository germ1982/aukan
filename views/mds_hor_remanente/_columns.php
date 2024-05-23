<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    /*
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idcontacto',
        'value'=>function($model){
            $contacto = Mds_org_contacto::findOne($model->idcontacto);
            $persona = Sds_com_persona::findOne($contacto->idpersona);
            if(!is_null($persona)){
                return $persona->apellido.', '.$persona->nombre;
            }
         },
        'filterType' => GridView::FILTER_SELECT2,
        /*
        'filter' => ArrayHelper::map(
            $contacto = Mds_org_contacto::find()->where(),
            $persona = Sds_com_persona::findOne($contacto->idpersona),
        ),
        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona'],
        'format' => 'raw',
        'width' => '30%',
        'label' => 'Persona'
    ],
    */
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'anio',
        'label'=>'Año'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dias',
        'label'=>'Días'
    ],
    /*
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
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],
    */

];   