<?php

use app\models\Mds_atp_monto;
use app\models\Mds_seg_usuario;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;

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
        'width' => '5%',
        'attribute' => 'idmonto',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'width' => '10%',
        'attribute' => 'fechahora',
        'value' => function ($model) {
            $fc = date_create($model->fechahora);
            $fc = date_format($fc, 'd/m/Y H:i:s');
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
        'attribute' => 'idusuario',
        'label' => 'Generado por',
        'value' => function ($model) {
            $idusuario = $model->idusuario;
            if ($idusuario != null) {
                $usuario = Mds_seg_usuario::findOne($idusuario);
                return $usuario->nombre . ' ' . $usuario->apellido;
            }
            return "";
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'value' => function ($model) {
            switch ($model->estado) {
                case Mds_atp_monto::GENERADO:
                    return "Generado";
                case Mds_atp_monto::ACEPTADO:
                    return "Aceptado";
                case Mds_atp_monto::RECHAZADO:
                    return "Rechazado";
                default:
                    return "";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            Mds_atp_monto::GENERADO => "Generado",
            Mds_atp_monto::ACEPTADO => "Aceptado",
            Mds_atp_monto::RECHAZADO => "Rechazado"
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width' => '20%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {open} {estado}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'buttons' => [
            'open' => function ($url, $model) {
                return  !$model->path ? '' : Html::a('<span class= "glyphicon glyphicon-download"></span>', $model->path, ['target' => '_blank', 'data-pjax' => 0, 'role' => 'post', 'title' => 'Descargar Archivo', 'data-toggle' => 'tooltip']);
            },
            'estado' => function ($url, $model) {
                $url =  Url::to(['/mds_atp_monto/change_estado', 'id' => $model->idmonto]);
                return Html::a('<span class= "fab fa-ioxhost"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Cambiar estado',
                ]);
            },
        ]
    ],

];
