<?php

use app\models\Mds_hor_motivo_inasistencia;
use yii\helpers\Url;
use app\models\Sds_com_persona;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Mds_org_contacto;
use kartik\date\DatePicker;
$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$columna1 = '25%';
$columna2 = '10%';
$columna3 = '5%';
$columna4 = '10%';
$columna5 = '10%';
$columna6 = '25%';
$columna7 = '10%';
$columna8 = '10%';
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
        'label' => 'Persona',
        'value' => function ($model) {
            $idcontacto = $model->idcontacto;
            if ($idcontacto != null) {
                $contacto = Mds_org_contacto::findOne($idcontacto);
                $idpersona = $contacto->idpersona;
                if ($idpersona != null) {
                    $persona = Sds_com_persona::findOne($idpersona);
                    $aux = "$persona->apellido, $persona->nombre";
                    return $aux;
                }

            }
            return "";
        },

        'filterType' => GridView::FILTER_SELECT2,

        'filter' => ArrayHelper::map(
            Sds_com_persona::findBySql("SELECT idpersona, CONCAT(apellido,', ',nombre) as nombre from sds_com_persona Where idpersona in(select idpersona from mds_org_contacto Where idcontacto in(Select DISTINCT(idcontacto) from mds_hor_licencia)) order by trim(apellido),trim(nombre)")->all(), //el order debe ser asi'apellido' => SORT_ASC, 'nombre' => SORT_ASC etc
            'idpersona','nombre'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Persona...'],
        'format' => 'raw',
        'width' => $columna1,

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute'=>'legajo',
        'label' => 'Legajo',
        'value' => function ($model) {
            $idcontacto = $model->idcontacto;
            if ($idcontacto != null) {
                $contacto = Mds_org_contacto::findOne($idcontacto);
                return $contacto->legajo;
            }
        
            return "";
        },
        

        'format' => 'raw',
        'width' => $columna2,
    ], 
    
    [
     'class'=>'\kartik\grid\DataColumn',
     'attribute'=>'cantidad_dias',
     'label' => 'Dias',
     'width' => $columna3,
    ],
    [
        'attribute' => 'desde',


        'value' => function ($model) {
            $fc = date_create($model->desde);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },

        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde_desde',
            'attribute2' => 'fdesde_hasta',
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
        ]),
        'width' => $columna4,
    ],
    [
        'attribute' => 'hasta',

        'width' => $columna5,
        'value' => function ($model) {
            $fc = date_create($model->hasta);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },

        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fhasta_desde',
            'attribute2' => 'fhasta_hasta',
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
        'attribute' => 'detalle',
        'width' => $columna6,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idmotivoinasistencia',
        'value' => function ($model) {
            $idmotivoinasistencia = $model->idmotivoinasistencia;
            if ($idmotivoinasistencia != null) {
                $motivoinasistencia = Mds_hor_motivo_inasistencia::findOne($idmotivoinasistencia);
                return $motivoinasistencia->idrh;
                }
            return "";
        },
        'label' => 'Codigo',
        'width' => $columna7,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta seguro?',
            'data-confirm-message' => 'Esta seguro que quiere eliminar este item'
        ],
        'width' => $columna8,
    ],

];
