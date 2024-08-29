<?php

use app\models\Organismo;
use app\models\OrganismoDispositivo;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$mysql_sectores = "SELECT d.iddispositivo as iddispositivo, concat(o.abreviatura,' - ', d.descripcion) as descripcion 
                    from organismo o 
                    join organismo_dispositivo d on d.idorganismo = o.idorganismo
                    where d.iddispositivo in (select iddispositivo from empleado) order by o.abreviatura, d.descripcion";


return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idevento',
        'width' => '5%',
    ],
    [
        'attribute' => 'fecha',
        'width' => '12%',
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'titulo',
        'width' => '35%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $id = $model->iddispositivo;
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
        'width' => '30%',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
        'width' => '8%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => '10%',
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
