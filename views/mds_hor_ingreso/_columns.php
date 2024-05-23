<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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
        'attribute' => 'idcontacto',
        'value' => function ($model) {
            $idcontacto = $model->idcontacto;
            if ($idcontacto != null) {
                $contacto = Mds_org_contacto::findOne($idcontacto);
                $idpersona = $contacto->idpersona;
                if ($idpersona != null) {
                    $persona = Sds_com_persona::findOne($idpersona);
                    $aux = "$persona->apellido, $persona->nombre";
                    return $aux;
                }
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => false,/* ArrayHelper::map(
            Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                join sds_com_persona p on p.idpersona=c.idpersona")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
            'idcontacto',
            function ($model) {
                return $model->nombre . " " . $model->apellido;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Empleado...'], */
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'attribute' => 'fecha_hora',
        'width' => '11%',
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => false /* DatePicker::widget([
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
        ]) */,
        'width' => '15%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'temperatura',
        'value' => function ($model) {
            $numero = number_format($model->temperatura, 2, ",", ".");
            return $numero;
        },
        'filter' => false,
        'width' => '5%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observaciones',
        'value' => function ($model) {
            return $model->observaciones != null ? $model->observaciones : "";
        },
        'filter' => false,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{update}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        /* 'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'], */
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Completar Temperatura', 'data-toggle' => 'tooltip'],
        /* 'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ], */
    ],

];
