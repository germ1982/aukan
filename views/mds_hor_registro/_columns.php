<?php
use app\models\Mds_hor_registro;
use app\models\Mds_org_contacto;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '1%',
        'checkboxOptions' => function ($model) {
            if($model->origen == Mds_hor_registro::ORIGEN_MANUAL && $model->idcertificacion==null){
                return [
                    'value' => $model->idregistrohorario,
                ];
            }
            return [
                'disabled' => true
            ];
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcontacto',
        'label'=>'Empleado',
        'value' => function ($model) {            
            return $model->contacto;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            $contactos,
            'idcontacto',
            function ($model) {
                return $model->legajo . " - " . $model->apellido . ", " . $model->nombre;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Empleado...'],
        'format' => 'raw',
        'width' => '25%',
    ],
    [
        'attribute' => 'fecha',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y H:i');
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
        'attribute'=>'origen',
        'value'=>function($model){
            return $model->origen == 0 ? 'Importación':($model->origen == 1 ? 'Carga Manual':($model->origen == 2 ? 'App Ciclo':'Ingreso Guardia'));
        },
        'filter'=>['0'=>'Importación','1'=>'Carga Manual','2'=>'App Ciclo','3'=>'Ingreso Guardia'],
        'width'=>'11%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observaciones',
        'value' => function($model){
            return $model->observaciones!=null ? $model->observaciones : '';
        },
        'width'=>'25%'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete} &nbsp;{logs}',
        'dropdown' => false,
        'vAlign' =>'middle',
        'width' => '5%',
        'urlCreator' => function($action, $model, $key, $index) {
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'logs' => function ($url, $model) {
                $url =  Url::to(['/mds_sys_log', 'id' => $model->idregistrohorario, 'modulos'=>'mds_hor_registro', 'is_reg_hor'=>1]);
                return Html::a('<span class= "fas fa-list"></span>', $url, [
                    'title' => "Logs del registro",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' =>function($url, $model){
                if($model->origen==Mds_hor_registro::ORIGEN_MANUAL && $model->idcertificacion==null){
                    $url =  Url::to(['/mds_hor_registro/delete', 'id' => $model->idregistrohorario]);
                    return Html::a('<span class= "glyphicon glyphicon-trash text-danger"></span>', $url, [
                        'title' => "Borrar Registro",
                        'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '',
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Está a punto de eliminar este registro',
                        'data-confirm-message'=>'<span class="text-danger">¿Desea continuar?</span>',
                    ]);
                }else{
                    return '<span class= "glyphicon glyphicon-trash"></span>';
                }
            },
            'update' =>function($url, $model){
                if($model->origen==Mds_hor_registro::ORIGEN_MANUAL && $model->idcertificacion==null){
                    $url =  Url::to(['/mds_hor_registro/update', 'id' => $model->idregistrohorario]);
                    return Html::a('<span class= "glyphicon glyphicon-pencil text-primary"></span>', $url, [
                        'title' => "Editar Registro",
                        'role' => 'modal-remote',
                        'data-toggle'=>'tooltip',
                    ]);
                }else{
                    return '<span class= "glyphicon glyphicon-pencil"></span>';
                }
            },
        ],
        'width'=>'8%',
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip']
    ],
];