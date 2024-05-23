<?php
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use app\models\Sds_com_persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\helpers\Html;


$columna1 = '8%';
$columna2 = '20%';
$columna3 = '15%';
$columna4 = '15%';
$columna5 = '22%';
$columna6 = '10%';
$columna7 = '10%';

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
        'width' => $columna1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
        'value' => function ($model) {
            return $model->apellido . ", " . $model->nombre;
        },
        'format' => 'raw',
        'width' => $columna2,
        'label' => 'Pensionado',        
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'programa_descripcion',
        'format' => 'raw',
        'width' => $columna3,
        'label' => 'Programa',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter' => $filters['programa'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Programa...']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado_descripcion',
        'format' => 'raw',
        'width' => $columna4,
        'label' => 'Estado',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter' => $filters['estado'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'causa_baja_descripcion',
        //'value' => function($model){return $model->causa_baja_descripcion==null ? '' : $model->causa_baja_descripcion;},
        'format' => 'raw',
        'width' => $columna5,
        'label' => 'Causa Baja',
        'filterType'=>GridView::FILTER_SELECT2,
        'filter' => $filters['causa_baja'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Causa Baja...']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_baja',
        'value'=>function($model){
            if($model->fecha_baja!=null){
                $date=date_create($model->fecha_baja);
                return date_format($date, 'd/m/Y');
            }
            return "";
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
            ]),


        /* 'value' => function ($model) {
            $fc = "";
            if ($model->fecha_baja) {
                $fc = date_create($model->fecha_baja);
                $fc = date_format($fc, 'd/m/Y');
            }
            return $fc;
        }, */
        'width' => $columna6,
        //'filter' => false,
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento_tipo',
        'value' => function ($model) {
            $persona = Sds_com_persona::findOne($model->idpersona);
            if (!($persona == null)) {
                $documento_tipo = Sds_com_configuracion::findOne($persona->documento_tipo);
                $aux = $documento_tipo->descripcion;
            } else {
                $aux = "No encontrado";
            }

            return $aux;
        },
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_TIPO_DOC), 'idconfiguracion', 'descripcion'),
        'width' => $columna2,

    ],
    
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
        'value' => function ($model) {
            $aux = "";
            if ($model->legajo) {
                $aux = $model->legajo;
            }

            return $aux;
        },
        'width' => $columna4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_carga',
        'value' => function ($model) {
            $fc = "";
            if ($model->fecha_carga) {
                $fc = date_create($model->fecha_carga);
                $fc = date_format($fc, 'd/m/Y');
            }
            return $fc;
        },
        'width' => $columna5,
        'filter' => false,
        'label' => 'Fecha Tramite',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_otorgado',
        'value' => function ($model) {
            $fc = "";
            if ($model->fecha_otorgado) {
                $fc = date_create($model->fecha_otorgado);
                $fc = date_format($fc, 'd/m/Y');
            }
            return $fc;
        },
        'width' => $columna6,
        'filter' => false,
    ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} {imprimir_informe_personal}',
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
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta muy seguro que desea eliminar estos datos?'
        ],
        'buttons' => [
            'imprimir_informe_personal' => function ($url, $model) {
                $url =  Url::to(['/sds_pen_pension/imprimir_informe_personal', 'idpension' => $model->idpension]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'title' => "Imprimir ",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },

        ],
        'width' => $columna7,
    ],

];
