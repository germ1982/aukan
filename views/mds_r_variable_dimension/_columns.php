<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_r_planilla;
use app\models\Mds_r_plantilla;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
return [
   /* [
        'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idvardimension',
            'label' => 'ID',                        
        ],
    [
            'class' => '\kartik\grid\DataColumn',
                'attribute' => 'activo',
                'label' => 'Activo',
                
        ],*/
    [
        'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idvariable',
            'label' => 'Variable',
            'value' => function ($model) {
                $tipo = Sds_com_configuracion::findOne($model->idvariable);
                return $tipo->descripcion;                        
            },        
            
            'format' => 'raw',
            'width' => '18%',  
        ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'origen',
        'value' => function ($model) {
            $tipo = sds_com_configuracion::findOne($model->origen);
            return $tipo->descripcion;                        
        },    
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddimension',
        'value' => function ($model) {
            $tipo = sds_com_configuracion_tipo::findOne($model->iddimension);           
            return $tipo->descripcion;                        
        },     
        'label'=>'Dimensión',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_carga',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_actualizacion',
    // ],
   
    [
        'class' => 'kartik\grid\BooleanColumn',
        'attribute' => 'mapear', 
        'vAlign' => 'middle'
    ], 
    [
        'class' => '\kartik\grid\DataColumn',
            'attribute' => 'detalle',
            'label' => 'Detalle', 
            'width' => '20%',  
            'value' => function ($model) {
                if($model->detalle==null){ return "";}
                else{return $model->detalle;}                        
            },  
    ], 
   /* [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'idplanilla',
         'value' => function ($model) {


            $unaplanilla = Mds_r_planilla::findOne($model->idplanilla);
            //$una_plantilla=Mds_r_plantilla::findOne($model->idplantilla);
            return $unaplanilla->idplantilla;                                           
        }, 
        'label'=>'idplantilla',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'origen',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddimension',
   ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tipomapa',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => ' {ver} {actualizar}  {diagnosticos} {borrar}' ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
            'actualizar' => function ($url, $model,$namegridid) {
                $url =  Url::to([
                    '/mds_r_variable_dimension/update', 'id' => $model->idvardimension, 'idplanilla' => $model->idplanilla
                ]);
                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Editar Dimensión',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'ver' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_r_variable_dimension/view', 'id' => $model->idvardimension,  
                ]);
                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Ver Dimensión',
                    'data-toggle' => 'tooltip',
                ]);

            },
            'diagnosticos' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_r_diagnostico/index_diagnostico', 'idvardimension' => $model->idvardimension, 
                ]);
                return Html::a('<i class="fas fa-comment-medical"></i>', $url, [ 
                    'role' => 'modal-remote',
                    'title' => 'Diagnósticos',
                    'data-toggle' => 'tooltip',
                ]);
            },            
            'borrar' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_r_variable_dimension/delete', 'id' => $model->idvardimension,
                ]);
                return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [   
                    'role' => 'modal-remote',                 
                    'title' => 'Eliminar Dimensión',
                    'data-request-method'=>'post',
                    'data-toggle' => 'tooltip',  
                    'data-confirm-title'=>'Variable Dimensión',
                    'data-confirm-message'=>'Seguro desea eliminar este registro?'                  
                ]);
               
            },
        ],        
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],        
    ],

];   