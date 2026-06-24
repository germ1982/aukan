<?php

use app\models\LogPlataforma;
use app\models\Persona;
use app\models\Usuarios;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var array $searchModel */

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$columna_1 = '5%';//id
$columna_2 = '14%';//modulo
$columna_3 = '15%';//accion
$columna_4 = '7%';//fecha
$columna_5 = '7%';//hora
$columna_6 = '15%';//usuario
$columna_7 = '7%';//id registro
$columna_8 = '25%';//observacion
$columna_9 = '5%';//accion

$mysql_personas = "SELECT DISTINCT l.idusuario as id, CONCAT(p.apellido, ' ', p.nombre) AS nombre
                    FROM log_plataforma l
                    JOIN usuarios u ON l.idusuario = u.id
                    JOIN personas p ON p.idpersona = u.idpersona
                    order by p.apellido,p.nombre;";


return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idlog',
        'width' => $columna_1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idmodulo',
        'value' => fn($model) => LogPlataforma::getModuloNombre($model->idmodulo),
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => LogPlataforma::getModulosLista(),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true],],
        'filterInputOptions' => ['placeholder' => 'Modulo...'],
        'format' => 'raw',
        'width' => $columna_2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idaccion',
        'value' => fn($model) => LogPlataforma::getAccionNombre($model->idaccion),
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => LogPlataforma::getAccionesLista(),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true],],
        'filterInputOptions' => ['placeholder' => 'Accion...'],
        'format' => 'raw',
        'width' => $columna_3,
    ],
    
    [
        'attribute' => 'fecha',
        'width' => $columna_4,
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
        'width' => $columna_5,

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
        'filter' => ArrayHelper::map(Usuarios::findBySql($mysql_personas)->all(), 'id', 'nombre'),
        'filterWidgetOptions' => [
              'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'label'  => 'Usuario',
        'width' => $columna_6,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idregistro',
        'width' => $columna_7,
    ],
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
        'width' => $columna_8,
        'value' => function ($model) {
            return $model->observacion ?? '';
        },
        'format' => 'raw', // <--- ESTO es clave para que procese el <b>
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna_9,
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