<?php

use app\models\LogPlataforma;
use app\models\Persona;
use app\models\Usuarios;
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

$columna_1 = '5%';//id
$columna_2 = '25%';//usuario
$columna_3 = '10%';//fecha
$columna_4 = '7%';//hora
$columna_5 = '25%';//modulo
$columna_6 = '10%';//accion
$columna_7 = '7%';//id registro
$columna_8 = '5%';//accion

$mysql_personas = "SELECT p.idpersona, concat(p.apellido,' ', p.nombre) as nombre
                    from personas p 
                    where p.idpersona in (
                    select u.idpersona
                    from usuarios u join
                    log_plataforma l on l.idusuario = u.id
                    )";


return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idlog',
        'width' => $columna_1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'modulo',
        'value' => fn($model) => LogPlataforma::getModuloNombre($model->modulo),
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => LogPlataforma::getModulosLista(),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true],],
        'filterInputOptions' => ['placeholder' => 'Modulo...'],
        'format' => 'raw',
        'width' => $columna_5,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'accion',
        'value' => fn($model) => LogPlataforma::getAccionNombre($model->accion),
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => LogPlataforma::getAccionesLista(),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true],],
        'filterInputOptions' => ['placeholder' => 'Accion...'],
        'format' => 'raw',
        'width' => $columna_6,
    ],
    
    [
        'attribute' => 'fecha',
        'width' => $columna_3,
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
        'attribute'=>'hora',
        'width' => $columna_4,

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $usuario = Usuarios::findOne($model->idusuario);
              $id = $usuario->idpersona;
              if ($id != null) {
                    $persona = Persona::findOne($id); return "$persona->apellido $persona->nombre";
              }
              return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Persona::findBySql($mysql_personas)->all(), 'idpersona', 'nombre'),
        'filterWidgetOptions' => [
              'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'label'  => 'Usuario',
        'width' => $columna_2,
  ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idregistro',
        'width' => $columna_7,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna_8,
        'template' => '{view}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,//for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   