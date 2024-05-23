<?php

use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$sectores = 
$columna1 = '10%';
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'attribute' => 'fecha',
        'width' => $columna1,
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            //$fc = date_format($fc, 'd/m/Y H:i');
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'sector_descripcion',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> ArrayHelper::map(
            Sds_com_configuracion::findBySql(
                "SELECT * FROM sds_com_configuracion WHERE idconfiguracion IN(SELECT sector FROM sds_bdc_visita group by sector)"
            )->all(),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Sector...'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update} &nbsp;  {delete} &nbsp;  {admin_eq}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Mirar','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Eliminar visita?',
                        'data-confirm-message'=>'<span class="text-danger">Estas seguro que quieres borrar los datos de la visita?</span>'],
        'buttons' => [
            'admin_eq'=> function ($url, $model) {
                $url =  Url::to(['/sds_bdc_visita_equipo', 'idvisita' => $model->idvisita]);
                return Html::a('<span class="glyphicon glyphicon-th-list"></span>', $url, [
                    'title' => "Administrar Equipos",
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
    ],

];