<?php

use app\models\Configuracion;
use yii\helpers\Url;
use app\models\Persona;
use app\models\Empleado;
use app\models\OrganismoDispositivo;

return [
 
        [
        'class'=>'\kartik\grid\DataColumn',
        'width' => '1px',
        'attribute'=>'idvehiculo',
    ],  
    
    [
        'class' => '\kartik\grid\DataColumn',
        'width' => '100px',
        'attribute' => 'idmarca',        
        'value' => function ($model) {
            $id = $model->idmarca;
            if ($id != null) {
                $tipo = Configuracion::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        
    ],
   
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => '5px',
        'attribute'=>'dominio',
    ],
    
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => '10px',
        'attribute'=>'color',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'width' => '200px',
        'attribute' => 'idempleado',
        'value' => function ($model) {
            if ($model->idempleado) {
                $empleado = Empleado::findOne($model->idempleado);
                $sector = OrganismoDispositivo::get_dispositivo($empleado->iddispositivo);
                $persona = Persona::findOne($empleado->idpersona);
                return "$persona->apellido $persona->nombre - $sector->descripcion - $empleado->telefono";
            }
            return "";
        },
        
    ],
   
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => '10px',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{view} {update} ',
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