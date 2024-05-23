<?php

use app\controllers\Sds_cel_movimientoController;
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\helpers\Html;
use kartik\grid\GridView;

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
        'attribute' => 'lineanro',
        'label' => 'Linea inicial',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cuenta',
        'filter' => Yii::$app->user->identity->celular_cuenta == null
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ultimo_movimiento',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'empresa',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'organismo',
        'label' => 'Organismo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dependecia',
        'label' => 'Dependencia',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsable',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'imei',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'plan',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'baja',
        'value' => function ($model) {
            return $model->baja == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],

];
