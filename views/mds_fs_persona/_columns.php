<?php

use app\models\Mds_fs_persona;
use yii\helpers\Url;
use app\models\Sds_com_localidad;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\date\DatePicker;

$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [
    /* [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ], */
        /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idfspersona',
    ], */
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dni',
        'label'=>'Dni',
        'width' => '9%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
        'label'=>'Nombres',
        'width' => '18%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'apellido',
        'label'=>'Apellidos',
        'width' => '18%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idlocalidad',
        'label' => 'Localidad',
        'value' => function ($model) {                             
            $una_localidad=Sds_com_localidad::findOne($model->idlocalidad);           
            $cad=$una_localidad->descripcion;
            return $cad;        
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_localidad::find()->where(['idprovincia' => 58])            
            ->orderBy('descripcion')
            ->all(), 'idlocalidad', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Localidad...'],
        'format' => 'raw',
        'width' => '15%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono',
        'width' => '8%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'mail',
        'width' => '16%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '22%',
        'value' => function ($model) {
            return Mds_fs_persona::getEstado($model->estado);
        },
        'format' => 'raw',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            0 => 'Inscripto',
            1 => 'En proceso de evaluación',
            2 => 'Reúne condiciones',
            3 => 'No reúne condiciones',
            4 => 'No continúa proceso de evaluación',
            5 => 'No corresponde localidad'
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
        'attribute' => 'fecha_hora',
        'label' => 'Fecha de Inscripcion',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },

        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_desde',
            'attribute2' => 'fecha_hasta',
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
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {delete} {informe} {adjunto}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' => [
            'informe' => function ($url, $model) {
                $url =  Url::to(['/mds_fs_persona/reporte_familia', 'idfspersona' => $model->idfspersona]);
                return  Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'adjunto' => function ($url, $model) {
                $url =  'uploads/familiassolidarias/'.$model->idfspersona.'/informe/'.$model->informe_adjunto_path;
                return  !$model->informe_adjunto_path ? '' : Html::a('<span class= "glyphicon glyphicon-paperclip"></span>', $url, ['target' => '_blank', 'data-pjax' => 0, 'role' => 'post', 'title' => 'Descargar Adjunto', 'data-toggle' => 'tooltip']);
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Eliminar Registro',
                          'data-confirm-message'=>'Esta seguro de eliminar este registro de familias solidarias?'], 
    ],

];   