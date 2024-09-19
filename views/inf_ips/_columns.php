<?php

use app\controllers\OrganismoController;
use yii\helpers\Url;
use app\models\Persona;
use app\models\Empleado;
use app\models\Organismo;
use app\models\OrganismoDispositivo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$mysql_sectores = "SELECT d.iddispositivo as iddispositivo, concat(o.abreviatura,' - ', d.descripcion) as descripcion 
                    from organismo o 
                    join organismo_dispositivo d on d.idorganismo = o.idorganismo
                    where d.iddispositivo in (select e.iddispositivo from empleado e join inf_ips i on i.idempleado = e.idempleado) order by o.abreviatura, d.descripcion";

return [
 
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idip',
        'width' => '5%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ip',
        'width' => '15%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idempleado',
        'value' => function ($model) {
            $empleado = Empleado::findOne($model->idempleado);
            $persona = Persona::findOne($empleado->idpersona);
            return "$persona->apellido $persona->nombre";
        },
        'width' => '25%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'width' => '45%',
        'value' => function ($model) {
            $empleado = Empleado::findOne($model->idempleado);
            $id = $empleado->iddispositivo;
            if ($id != null) {
                $dispositivo = OrganismoDispositivo::findOne($id);
                $organismo = Organismo::findOne($dispositivo->idorganismo);
                return "$organismo->abreviatura - $dispositivo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(OrganismoDispositivo::findBySql($mysql_sectores)->all(), 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
 
    ],
   
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => '10%',
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

];   