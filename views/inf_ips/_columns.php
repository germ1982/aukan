<?php

use app\controllers\OrganismoController;
use app\models\Edificio;
use app\models\EdificioOficina;
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

$mysql_oficinas = "SELECT o.idoficina, concat(e.descripcion_fija, ' - ' ,o.descripcion) as descripcion
                    from edificio_oficina o
                    join edificio e on o.idedificio = e.idedificio
                    where o.activo = 1
                    order by e.descripcion_fija, o.descripcion";

$columna1 = "4%";
$columna2 = "8%";
$columna3 = "18%";
$columna4 = "18%";
$columna5 = "23%";
$columna6 = "10%";
$columna7 = "8%";

return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idip',
        'width' => $columna1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ip',
        'width' => $columna2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idoficina',
        'width' => $columna3,
        'value' => function ($model) {
            if ($model->idoficina) {
                $oficina = EdificioOficina::findOne($model->idoficina);
                $id = $oficina->idoficina;
                if ($id != null) {
                    $edificio = Edificio::findOne($oficina->idedificio);
                    return "$oficina->descripcion - $edificio->descripcion_fija";
                }
                return "";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(EdificioOficina::findBySql($mysql_oficinas)->all(), 'idoficina', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Oficina...'],
        'format' => 'raw',

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado',
        'value' => function ($model) {
            if ($model->idempleado) {
                $empleado = Empleado::findOne($model->idempleado);
                $persona = Persona::findOne($empleado->idpersona);
                return "$persona->apellido $persona->nombre";
            }
            return "";
        },
        'width' => $columna4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'width' => $columna5,
        'value' => function ($model) {
            if ($model->idempleado) {
                $empleado = Empleado::findOne($model->idempleado);
                $id = $empleado->iddispositivo;
                if ($id != null) {
                    $dispositivo = OrganismoDispositivo::findOne($id);
                    $organismo = Organismo::findOne($dispositivo->idorganismo);
                    return "$organismo->abreviatura - $dispositivo->descripcion";
                }
                return "";
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observacion',
        'value' =>function ($model) {
            return $model->observacion ? $model->observacion : '';
        },
        'width' => $columna6,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} ',
        'vAlign' => 'middle',
        'width' => $columna7,
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
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
