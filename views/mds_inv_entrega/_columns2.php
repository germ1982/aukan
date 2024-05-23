<?php
use yii\helpers\Url;
use app\models\Sds_com_configuracion;
use yii\helpers\Html;
use app\models\Sds_gis_capa_item;
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idespecie',
        'label' => 'Especie',
        'value' => function ($model) {
            $especie = Sds_com_configuracion::findOne($model->idespecie);
            return $especie->descripcion;                        
        },          
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantidad',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_entrega',        
        'width' => '12%',
        'label' => 'Fecha Entrega',
        'value' => function ($model) {
            if ($model->fecha_entrega==null)
            {
                $fc='sin especificar';
            }
            else
            {
                $fc = date_create($model->fecha_entrega);
                $fc = date_format($fc, 'd/m/Y');
            }                                   
            return $fc;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idlugar',        
        'width' => '12%',
        'label' => 'Lugar Entrega',
        'value' => function ($model) {
            $el_lugar = Sds_gis_capa_item::findOne($model->idlugar);
                return $el_lugar->descripcion;
        },
    ],
    
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'temporada',
        'label' => 'Temporada',
        'value' => function ($model) {
            $temporada = Sds_com_configuracion::findOne($model->temporada);
            if ($temporada!=null)
                return $temporada->descripcion;   
            else
                return "";                               
        },          
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{ver} {actualizar} {delete}' ,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        
        'buttons' => [
            'actualizar' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_inv_entrega/update', 'id' => $model->identrega,'idpersona' => $model->idpersona,
                ]);
                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Editar Entrega Plantines',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'ver' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_inv_entrega/view', 'id' => $model->identrega,'idpersona' => $model->idpersona,  
                ]);
                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Ver Entrega Plantines',
                    'data-toggle' => 'tooltip',
                ]);

            },
            
            /*'borrar' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_inv_entrega/delete', 'id' => $model->identrega, 
                ]);
                return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [   
                    'role' => 'modal-remote',                 
                    'title' => 'Eliminar Entrega de Plantin',
                    'data-toggle' => 'tooltip',                    
                ]);
               
            },*/
        ], 
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Borrar Entrega?',
                          'data-confirm-message'=>'Seguro desea eliminar esta entrega?'],   
         
    ],

];   