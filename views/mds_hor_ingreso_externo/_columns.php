<?php

use app\models\Mds_hor_ingreso;
use app\models\Mds_hor_ingreso_externo;
use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Mds_org_padron;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;



$columna0 = '10%';
$columna1 = '20%';
$columna2 = '12%';
$columna3 = '15%';
$columna4 = '15%';
$columna5 = '10%';
$columna6 = '15%';
$columna7 = '14%';

return [

    [
        'attribute' => 'fecha_hora',
        'width' => $columna0,
        'label' => 'Fecha de llegada',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },

        'options' => ['readonly' => true,],
        'filter' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'persona',
        'value' => function ($model) {
            $persona = Sds_com_persona::findOne($model->idpersona);
            if (!($persona == null)) {
                $aux = "$persona->apellido, $persona->nombre";
            } else {
                $aux = "No encontrado";
            }

            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::find()->where("idpersona in (select idpersona from mds_hor_ingreso_externo)")->orderBy(['apellido' => SORT_ASC, 'nombre' => SORT_ASC])->all(),
            'idpersona',
            function ($model) {
                return "$model->apellido, $model->nombre";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona...'],
        'format' => 'raw',
        'width' => $columna1,
        'label' => 'Persona ingresante',
    ],
    [

        'label' => 'Estado',
        'attribute' => 'estado',
        'class' => '\kartik\grid\DataColumn',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_hor_ingreso_externo::findBySql("select ie.*, if(isnull(ie.idcontacto) and isnull(ie.fecha_hora_ingreso), 'Pendiente', if(isnull(ie.idcontacto) and not isnull(ie.fecha_hora_ingreso), 'Rechazado', 'Aceptado')) estado from mdsyt.mds_hor_ingreso_externo ie ;")->all(),
            'estado',
            function ($model) {
                return $model->estado;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'placeholder' => 'Estado...',
                'allowClear' => true
            ],
        ],
        'width' => $columna2
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'contacto',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['contacto'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'contacto...'],
        'format' => 'raw',
        'width' => $columna3,
        'label' => 'Contacto encargado',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'width' => $columna5,
        'value' => function ($model) {
            $model = Mds_org_organismo::findOne($model->idorganismo);
            if (!($model == null)) {
                $aux = "$model->descripcion";
            } else {
                $aux = "No encontrado";
            }

            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_organismo::findBySql(
                "SELECT org.* FROM mds_hor_ingreso_externo ie
                JOIN mds_org_organismo org ON ie.idorganismo=org.idorganismo")->all(),
            'idorganismo',
            'abreviatura'
        ),

        'options' => ['readonly' => false],
        'filterInputOptions' => [
            'placeholder' => 'Organismo...',
            'allowClear' => true,
        ],
        'width' => $columna3,
        'label' => 'Organismo',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ]
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observaciones',
        'label' => 'Observaciones',
        'width' => $columna4,
        'filter' => false
    ],

    [
        'attribute' => 'fecha_hora_ingreso',
        'width' => $columna5,
        'label' => 'Respuesta',
        'value' => function ($model) {
            return $model->fecha_hora_ingreso != null ? $model->fecha_hora_ingreso = date('d/m/Y H:i', strtotime(str_replace('-', '/', $model->fecha_hora_ingreso))) : '';
        },
        'options' => ['readonly' => true],
        'filter' => false
    ],


    [
        'attribute' => 'motivo',
        'width' => $columna5,
        'label' => 'Motivo',
        'value' => function ($model) {
            $model = Sds_com_configuracion::findOne($model->motivo);
            if (!($model == null)) {
                $aux = "$model->descripcion";
            } else {
                $aux = "No encontrado";
            }

            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::findBySql("select * from sds_com_configuracion where idconfiguraciontipo=197")->all(),
            'idconfiguraciontipo',
            'descripcion'
        ),

        'options' => ['readonly' => true],
    ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} {delete} {aceptar} {rechazar}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'aceptar' => function ($url, $model) {
                $url =  Url::to(['aceptar_externo', 'id' => $model->idingresoexterno, 'aceptar' => 1, /* 'current_url' => Url::current() */]);
                if ($model->estado == 'Pendiente') {
                    return Html::a('<span class= "glyphicon glyphicon-ok"></span>', $url, [
                        'title' => "Aceptar",
                        'role' => 'modal-remote',
                        'target' => '',
                        'data-toggle' => 'tooltip',
                        'class' => 'text-success'
                    ]);
                }
            },
            'rechazar' => function ($url, $model) {
                $url =  Url::to(['aceptar_externo', 'id' => $model->idingresoexterno, 'aceptar' => 0, /* 'current_url' => Url::current() */]);
                if ($model->estado == 'Pendiente') {
                    return Html::a('<span class= "glyphicon glyphicon-ban-circle"></span>', $url, [
                        'title' => "Rechazar",
                        'role' => 'modal-remote',
                        'content' => '¿Seguro que quiere rechazar la peticion de ingreso?',
                        'target' => '',
                        'data-toggle' => 'tooltip',
                        'class' => 'text-danger'
                    ]);
                }
            },
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Está por eliminar un registro de ingreso',
            'data-confirm-message' => '¿Confirmamos la operación?'
        ],
        'width' => $columna6,
    ],

];
