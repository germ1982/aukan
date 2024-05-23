<?php

use app\models\Sds_reg_tipo;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [

/* ################################################################################################################################################# */
    [
        'attribute' => 'fecha_hora',
        'label'=>'Fecha',
        'width' => '12%',
        'value' => function ($model) {
            if ($model->fecha_hora != null) {
                $fc = date_create($model->fecha_hora);
                $fc = date_format($fc, 'd/m/Y');
                return $fc;
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
                'format' => 'dd/mm/yyyy',
                'autoclose' => true
            ]
        ])
    ],

/* ################################################################################################################################################# */
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'problema',
        'label'=>'Detalle',
        'width' => '60%',
    ],
/* ################################################################################################################################################# */

    [
        'class' => '\kartik\grid\DataColumn',
        'label'=>'Tipo',
        'attribute' => 'idtipo',
        'value' => function ($model) {
            $idtipo = $model->idtipo;
            if ($idtipo != null) {
                $tipo = Sds_reg_tipo::findOne($idtipo);
                return $tipo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_reg_tipo::find()->where(
                ['entidad' => $searchModel->entidad])->orderBy(['descripcion' => SORT_ASC])->all(), 'idtipo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '15%'
    ],
/* ################################################################################################################################################# */
    

    [
        'class' => '\kartik\grid\DataColumn',
        'label'=>'Estado',
        'attribute' => 'estado',
        'value' => function ($model) {
            //esta funcion define primero que si el registro esta cerrado, sin importar si esta asignado o no, 
            //si no lo esta ahi si se fija si esta asignado o no.
            //este mismo criterio se usa en el search
            $estado = "";
            $idtipo = $model->idtipo;
            $cerrado = $model->registro_abierto;

            if ($cerrado == 0)
                {$estado = "Finalizado";}
            else
                {
                    if ($idtipo==7 or $idtipo==10)//7=Informatica 10=Mantenimiento
                        {$estado = "Pendiente";}
                    else
                        {$estado = "Asignado";}
                }

            return "$estado";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ['0' => 'Pendiente','1' => 'Asignado','2'=>'Finalizado'],
        //los id asigados en el array de arriba no tienen relacion a los ids del modelo, son solo ids para identificar la opcion elegida aca y en el search
        //el caption del item si se usa para filtrar pero el id no, es solo para saber que item estoy eligiendo
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '20%'
    ],
/* ################################################################################################################################################# */
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {cerrar}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_registro_autosolicitud/update', 'id' => $model->idregistro]);
                return $model->registro_abierto == 0 || $model->idtipo != 7 ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Editar'
                    ]);
            },
            'cerrar' => function ($url, $model) {
                //controllers\Sds_reg_registro_autosolicitudController.php
                $url =  Url::to(['/sds_reg_registro_autosolicitud/cerrar_registro', 'id' => $model->idregistro, 'entidad'=>$model->entidad]);
                return  $model->registro_abierto == 0 || $model->idtipo != 7 ? '' : Html::a('<i class="glyphicon glyphicon-ok"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Cerrar registro',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                    'data-toggle'=>'tooltip',
                    'data-confirm-title'=>'Esta seguro de cerrar?',
                    'data-confirm-message'=>'Esta seguro de cerrar este registro?',
                ]);
            },
        ],
    ],

];   