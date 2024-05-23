<?php

use app\controllers\Sds_stk_articuloController;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'orden',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'rubro',
        'value' => function ($model) {
            $id_config = $model->rubro;
            if ($id_config != null) {
                $config = Sds_com_configuracion::findOne($id_config);
                return $config->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        //'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_RUBRO), 'idconfiguracion', 'descripcion'),
        
        'filter' => ArrayHelper::map(Sds_com_configuracion::findBySql(
            "select idconfiguracion, descripcion from sds_com_configuracion 
            where idconfiguracion = 1 or idconfiguraciontipo = " . Sds_com_configuracion_tipo::TIPO_RUBRO . " and activo = 1 order by descripcion"
        )->all(), 'idconfiguracion', 'descripcion'),'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Todas...'],
        'format' => 'raw',
        'width' => '15%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'width' => '30%',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'ingresado',
        'label'=>'Ingresado',
        'width' => '8%',
       /*  'value'=> function ($model)
                {
                    $stock_ingresado = $this->context->actionGet_stock_ingresado($model->idarticulo);
                    return $stock_ingresado;
                } */
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'entregado',
        'label'=>'Entregado',
        'width' => '8%',
        /* 'value'=> function ($model)
            {
                $stock_entregado = $this->context->actionGet_stock_entregado($model->idarticulo);
                return $stock_entregado;
            } */
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'disponible',
        //'label'=>'Disponible',
        'width' => '8%',
        /* 'value'=> function ($model)
            {
                $stock_disponible = $this->context->actionGet_stock_disponible($model->idarticulo);
                return $stock_disponible;
            } */
    ],


    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'unidad_medida',
        'value' => function ($model) {
            $id_config = $model->unidad_medida;
            if ($id_config != null) {
                $unidad_medida = Sds_com_configuracion::findOne($id_config);
                return $unidad_medida->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_UNIDAD_MEDIDA), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Todas...'],
        'format' => 'raw',
        'width' => '10%',
    ],


    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            if ($model->activo==1)
                return "Si";
            else
                return "No";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array('0'=>"No",'1'=>"Si",' '=>"Ambos"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '...'],
        'format' => 'raw',
        'width' => '8%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'devolucion',
        'value' => function ($model) {
            if ($model->devolucion==1)
                return "Si";
            else
                return "No";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array('0'=>"No",'1'=>"Si",' '=>"Ambos"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '...'],
        'format' => 'raw',
        'width' => '8%',
    ],
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organismo',
    ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {safipro} {delete}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
        'buttons' => [
        'safipro' => function ($url, $model) {
            $url =  Url::to(['/sds_stk_articulo/form_safipro', 'id' => $model->idarticulo]);
            return Html::a('<img src="img/accesos_directos_home/safipro.png" width="18" height="18">',
            $url,
            ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Safipro', 'data-toggle' => 'tooltip']);
                },

        ],    
    ],

];   