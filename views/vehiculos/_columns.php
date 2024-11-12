<?php

use app\models\Configuracion;
use yii\helpers\Url;
use app\models\Persona;
use app\models\Empleado;
use app\models\OrganismoDispositivo;

$columna_1 = '10%';
$columna_2 = '20%';
$columna_3 = '60%';
$columna_4 = '10%';


return [

    [
        'class' => '\kartik\grid\DataColumn',
        'width' => $columna_2,
        'attribute' => 'vehiculo',
        'value' => function ($model) {

            $marca = Configuracion::findOne($model->idmarca);

            return "$marca->descripcion $model->modelo $model->anio $model->color";
        },

        'headerOptions' => [
            'style' => 'color: #87b867;', // Cambia el color del texto del encabezado
        ],

    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'width' => $columna_1,
        'attribute' => 'dominio',
    ],



    [
        'class' => '\kartik\grid\DataColumn',
        'width' => $columna_3,
        'attribute' => 'empleado',
        'value' => function ($model) {
            if ($model->idempleado) {
                $empleado = Empleado::findOne($model->idempleado);
                $sector = OrganismoDispositivo::get_dispositivo($empleado->iddispositivo);
                $persona = Persona::findOne($empleado->idpersona);
                return "$persona->apellido $persona->nombre - $sector->descripcion - $empleado->telefono";
            }
            return "";
        },
        'headerOptions' => [
            'style' => 'color: #87b867;', // Cambia el color del texto del encabezado
        ],

    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna_4,
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{view} {update} ',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
