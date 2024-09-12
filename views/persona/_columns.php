<?php

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Localidades;
use app\models\Persona;
use app\models\Provincias;
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

$mysql_nacionalidades = "SELECT * from configuracion 
                        where id_configuracion in (select nacionalidad from personas ) 
                        order by descripcion";

$mysql_generos = "SELECT * from configuracion 
                        where id_configuracion in (select genero from personas ) 
                        order by descripcion";
$columna1 = "25%";
$columna2 = "10%";
$columna3 = "10%";
$columna4 = "10%";
$columna5 = "10%";
$columna6 = "25%";
$columna7 = "10%";
                    

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_apellido', // Debe ser el nombre del atributo virtual
        'value' => function ($model) {
            return $model->apellido . ' ' . $model->nombre;
        },
        'format' => 'raw',
        'width' => $columna1,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento', // Debe ser el nombre del atributo virtual
        'value' => function ($model) {
            $tipo = Configuracion::findOne($model->documento_tipo)->descripcion;
            return $tipo . ' ' . $model->documento;
        },

        'format' => 'raw',
        'width' => $columna2,
    ],
    [
        'attribute' => 'fecha_nacimiento',
        'width' => $columna3,
        'label' => 'Nacimiento',
        'value' => function ($model) {
            $fc = date_create($model->fecha_nacimiento);
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nacionalidad',
        'value' => function ($model) {
            $id = $model->nacionalidad;
            if ($id != null) {
                $tipo = Configuracion::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::findBySql($mysql_nacionalidades)->all(), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de Dato...'],
        'format' => 'raw',
        'width' => $columna4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero',
        'value' => function ($model) {
            $id = $model->genero;
            if ($id != null) {
                $tipo = Configuracion::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::findBySql($mysql_generos)->all(), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Genero...'],
        'format' => 'raw',
        'width' => $columna5,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'direccion_completa', // Debe ser el nombre del atributo virtual
        'value' => function ($model) {
            $localidad = Localidades::findOne($model->idlocalidad);
            $provincia = Provincias::findOne($localidad->id_provincia);
            return "$provincia->provincia";
        },
        'format' => 'raw',
        'width' => $columna6,
    ],


    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'domicilio',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'domicilio_calle',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'domicilio_numero',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'idlocalidad',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} ',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
        'width' => $columna7,
    ],

];
