<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\helpers\Html;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

function crear_celda($label, $contenido, $ancho)
{
    echo "
    <div class='col-xs-$ancho'>  
        <h6><b>$label</b></h6>
        <p style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px;'>
                $contenido
        </p>
    </div>";
}

$columna0 = '1%';
$columna1 = '8%';
$columna2 = '20%';
$columna3 = '20%';
$columna4 = '20%';
$columna5 = '7%';
$columna6 = '10%';
$columna7 = '5%';


return [
    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idingresoexterno',
    ], */
    // [
    //     'class' => 'kartik\grid\ExpandRowColumn',
    //     'width' => $columna0,
    //     'value' => function ($model, $key, $index, $column) {
    //         return GridView::ROW_COLLAPSED;
    //     },
    //     'detail' => function ($model, $key, $index, $column) {
    //         return $model->observaciones != null ?
    //             Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";
    //     },
    //     'headerOptions' => ['class' => 'kartik-sheet-style'],
    //     'detailOptions' => ['class' => ''],
    //     'options' => ['style' => 'color:red'],
    //     'expandOneOnly' => true,

    // ],


    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
        'value' => function($model)
        {
            $persona = Sds_com_persona::findOne($model->idpersona);
            if(!($persona==null))
            {$aux = "$persona->apellido, $persona->nombre";}
            else    
            {$aux = "No encontrado";}
            
            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => false,
    'filterWidgetOptions' => [
        'pluginOptions' => ['allowClear' => true],
    ],
    'filterInputOptions' => ['placeholder' => 'Persona...'],
    'format' => 'raw',
    'width' => $columna2,
    'label' => 'Persona ingresante',
],


     [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'  ',
        'label' => 'Motivo del ingreso',
        'width' => $columna4,
        'filter' => false,
    ], 


    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idcontacto',
        'value' => function($model)
        {
            $contacto = null;
            $persona = null;
            if($contacto != null){
                $contacto = Mds_org_contacto::findOne($model->idcontacto);
                $persona = Sds_com_persona::findOne($contacto->idpersona);
            }
            if(!($persona==null))
            {$aux = "$persona->apellido, $persona->nombre";}
            else    
            {$aux = "No encontrado";}
            
            return $aux;
        },
        
        'filter' => false,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'contacto...'],
            'format' => 'raw',
            'width' => $columna3,
            'label' => 'Contacto encargado',
        ],


    

    [
        'attribute' => 'fecha_hora',
        'width' => $columna1,
        'label' => 'Fecha de Llegada',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => false,
        //DatePicker::widget([
        //     'model' => $searchModel,
        //     'attribute' => 'fdesde',
        //     'attribute2' => 'fhasta',
        //     'options' => ['placeholder' => 'Desde'],
        //     'options2' => ['placeholder' => 'Hasta'],
        //     'type' => DatePicker::TYPE_RANGE,
        //     'layout' => $layoutDate,
        //     'separator' => ' ',
        //     'readonly' => true,
        //     'pluginOptions' => [
        //         'format' => 'dd-mm-yyyy',
        //         'autoclose' => true
        //     ]
        // ])
    ],
    
    
    
];   