<?php
use yii\helpers\Url;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

if(!($lineanro==''))
        {$aux = true;}
    else
        {$aux = false;}

return [
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idmovimiento',
        'width' => '5%',
        'label' => 'Id',
    ], */
    [
        'attribute' => 'fecha',
        'width' => '11%',
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'linea',
        'filterInputOptions' => ['value' => $lineanro,'class' => 'form-control'],
        'width' => '10%',
        'label' => 'Linea Inicial',
        'hidden'=>$aux,
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'numero',
        'width' => '10%',
        'label' => 'Numero',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'organismo',
        'label' => 'Organismo',
        'value' => function ($model) {
            $idorganismo = $model->organismo;
            if ($idorganismo != null) {
                $organismo = Sds_com_configuracion::findOne($idorganismo);
                return $organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::findBySql(
            "select idconfiguracion, descripcion from sds_com_configuracion 
            where idconfiguracion = 1 or idconfiguraciontipo = " . Sds_com_configuracion_tipo::TIPO_ORGANISMO_LINEA . " and activo = 1 order by descripcion"
        )->all(), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '30%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observaciones',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'baja',
        'label' => 'Baja',
        'value' => function ($model) {
            if ($model->baja == 1)
                return "Si";
            else
                return "No";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array('0' => "No", '1' => "Si", ' ' => "Ambos"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '...'],
        'format' => 'raw',
        'width' => '8%',
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{delete}',
        'vAlign'=>'middle',
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