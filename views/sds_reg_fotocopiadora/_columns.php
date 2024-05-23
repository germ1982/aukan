<?php

use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_reg_fotocopiadora;
use app\models\Sds_stk_articulo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

return [
   /* [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idfotocopiadora',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idproveedor',
        'value' => function($model){
            $proveedor= Sds_com_configuracion::findOne($model->idproveedor);
            return $proveedor->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
         'filter' =>  ArrayHelper::map(
            Sds_com_configuracion::findBySql(
                "SELECT c.*
                FROM sds_reg_fotocopiadora f
                LEFT JOIN sds_com_configuracion c ON f.idproveedor=c.idconfiguracion"
            )->all(),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Proveedor...'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'expediente_fisico',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'expediente_gde',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'safipro',
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idorganismo',
        'value' => function($model){
            $organismo= Mds_org_organismo::findOne($model->idorganismo);
            return $organismo->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
         'filter' =>  ArrayHelper::map(
            Mds_org_organismo::findBySql(
                "SELECT o.*
                FROM sds_reg_fotocopiadora f
                LEFT JOIN mds_org_organismo o ON f.idorganismo=o.idorganismo"
            )->all(),
            'idorganismo',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Organismo...'],
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'lugar',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'idequipo',
         /*'value'=> function(){
            return '<a href="#">algo</a>'
         }*/
         'value' => function($model){
                $equipo=Sds_bdc_equipo::findOne($model->idequipo);
                $marca=Sds_com_configuracion::findOne($equipo->marca);
                return '#' . str_pad($equipo->idequipo, 6, "0", STR_PAD_LEFT). ' - ' . $marca->descripcion . ($equipo->modelo != '' ? ' - ' . $equipo->modelo : '');
         },
         'filterType' => GridView::FILTER_SELECT2,
         'filter' =>  ArrayHelper::map(
            Sds_bdc_equipo::findBySql(
                "SELECT e.*
                FROM sds_reg_fotocopiadora f
                LEFT JOIN sds_bdc_equipo e ON f.idequipo=e.idequipo"
            )->all(),
            'idequipo',
            function($equipo){
                $marca=Sds_com_configuracion::findOne($equipo->marca);
                return '#' . str_pad($equipo->idequipo, 6, "0", STR_PAD_LEFT). ' - ' . $marca->descripcion . ($equipo->modelo != '' ? ' - ' . $equipo->modelo : '');
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Equipo...'],
     ],
     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'copias',
        'filter' => false
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'vencimiento',
         'value' => function ($model) {
            return date('d/m/Y', strtotime($model->vencimiento));
        },
        'filter' => false
     ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observaciones',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template'=> '{view} {update} {delete} {equipo}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'equipo' => function ($url, $model) {
                $url =  Url::to([
                    '/sds_bdc_equipo/view', 'id' => $model->idequipo
                ]);
                return Html::a('<span class= "glyphicon glyphicon-print"></span>', $url, [
                    'title' => "Ver Fotocopiadora",
                    'role' => 'modal-remote', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'¡Cuidado! Estás por borrar esta Fotocopiadora.',
                          'data-confirm-message'=>'¿Seguro de que deseas hacerlo?'], 
    ],

];   