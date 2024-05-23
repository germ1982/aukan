<?php

use app\models\Mds_not_nota;
use app\models\Mds_seg_usuario;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
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
        'attribute' => 'numero',
        'value' => function ($model) {
            return $model->getNumeroFecha();
        },
        'contentOptions' => ['style' => 'text-align: right'],
        'width' => '7%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'referencia',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'destinatario_nombre',
        'width' => '25%',
        'value' => function ($model) {
            return $model->destinatario_nombre . ' - ' . $model->destinatario_area;
        }
    ],

    [
        'attribute' => 'fecha',
        //'header' => 'Organismo',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        // 'format' => ['date', 'php:d-m-Y H:i:s'],
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
        'value' => function ($model) {
            $usuario = $model->idusuario;
            if ($usuario != null) {
                $user = Mds_seg_usuario::findOne($usuario);
                return $user->user;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_seg_usuario::find()->orderBy(['user' => SORT_ASC])->all(), 'idusuario', 'user'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuarios...'],
        'format' => 'raw',
        'width' => '12%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'enviada',
        'value' => function ($model) {
            return $model->enviada == 1 ? 'SI' : 'NO';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anulada',
        'value' => function ($model) {
            return $model->anulada == 1 ? 'SI' : 'NO';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {enviar} {imprimir} {anular} {update} ',
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/mds_not_nota/update', 'id' => $model->idnota]);
                return $model->enviada || $model->anulada ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'enviar' => function ($url, $model) {
                $url =  Url::to(['/mds_not_nota/enviar', 'id' => $model->idnota]);
                return  $model->enviada || $model->anulada ? '' : Html::a('<span class= "far fa-paper-plane"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Confirmar envio',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '',
                    'data-confirm-message' => 'Está seguro que quiere marcar como enviada la nota seleccionada?'
                ]);
            },
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/mds_not_nota/reporte_nota', 'idnota' => $model->idnota]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'anular' => function ($url, $model) {
                $url =  Url::to(['/mds_not_nota/anular', 'id' => $model->idnota]);
                return $model->enviada || $model->anulada ? '' :
                    Html::a('<span class= "fas fa-ban"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Confirmar anulado',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => '',
                        'data-confirm-message' => 'Está seguro que quiere marcar como anulada la nota seleccionada?'
                    ]);
            }
        ]
    ],

];
