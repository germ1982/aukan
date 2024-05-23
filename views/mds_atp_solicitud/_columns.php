<?php

use yii\helpers\Url;
use kartik\date\DatePicker;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use app\models\Mds_atp_solicitud;

date_default_timezone_set('America/Argentina/Buenos_Aires');
$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_nacimiento',

        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_nacimiento);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'email',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '14%',
        'value' => function ($model) {
            switch ($model->estado) {
                case Mds_atp_solicitud::INSCRIPTO:
                    return "Inscripto";
                case Mds_atp_solicitud::RECHAZADO:
                    return "Rechazado";
                case Mds_atp_solicitud::APROBADO:
                    return "Aprobado";
                case Mds_atp_solicitud::PENDIENTE_ALTA:
                    return "Pendiente Alta";
                default:
                    return "Estado erroneo - N°: " . $model->estado;
            }
        },
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            Mds_atp_solicitud::INSCRIPTO => "Inscripto",
            Mds_atp_solicitud::PENDIENTE_ALTA => "Pendiente Alta",
            Mds_atp_solicitud::APROBADO => "Aprobado",
            Mds_atp_solicitud::RECHAZADO => "Rechazado",
            5 => "No definido",
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => ''
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',

        'value' => function ($model) {
            $mil = $model->created_at;
            $seconds = $mil / 1000;
            //return gmdate('Y-m-d H:i:sP',$model->created_at);
            return date('d/m/Y H:i:s', $seconds);
        }


    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'retirada',
        'width' => '5%',
        'value' => function ($model) {
            return $model->retirada ? "Si" : "No";
        },
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            1 => "Si",
            0 => "No",
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'allowClear' => true
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => ''
        ],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {update} {imprimir} {whatsapp} {estado} {historialestado}', //new
        'dropdown' => false,
        'width' => '5%',
        'vAlign' => 'middle',
        'hAlign' => 'left', //new
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],

        'buttons' => [
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/mds_atp_solicitud/reporte_solicitud', 'id' => $model->id]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'whatsapp' => function ($url, $model) {
                $url =  'https://wa.me/54' . $model->telefono;
                return Html::a('<span class="fab fa-whatsapp"></span>', $url, ['target' => '_blank']);
            },
            'estado' => function ($url, $model) {
                $url =  Url::to(['/mds_atp_solicitud/cambiar_estado', 'id' => $model->id]);
                return Html::a('<span class= "fab fa-ioxhost"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Cambiar estado de la solicitud',
                ]);
            },
            'historialestado' => function ($url, $model) {
                $url =  Url::to(['/mds_atp_historial/index2', 'id' => $model->id]);
                return Html::a('<span class= "far fa-clipboard"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Historial de cambios',
                ]);
            }
        ],
    ],

];
