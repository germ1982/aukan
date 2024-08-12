<?php

use app\models\Organismo;
use app\models\OrganismoDispositivo;
use app\models\Articulo;
use app\models\Empleado;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idInventario',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarticulo',
        'value' => function ($model) {
            $articulo = Articulo::get_articulo($model->idarticulo);
            return   "$articulo->descripcion";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Articulo::get_articulos("inventario"), 'idarticulo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Articulo...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $iddispositivo = $model->iddispositivo;
            $dispositivo = OrganismoDispositivo::get_dispositivo($iddispositivo);
            return   "$dispositivo->descripcion";
        },
        'filterType' => GridView::FILTER_SELECT2,
        //'filter' => ArrayHelper::map(OrganismoDispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'iddispositivo', 'descripcion'),
        'filter' => ArrayHelper::map(OrganismoDispositivo::get_dispositivos('inventario'), 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado',
        'value' => function ($model) {
            $idempleado = $model->idempleado;
            $empleado = Empleado::get_empleado($idempleado);
            return   "$empleado->descripcion";
        },
        'filterType' => GridView::FILTER_SELECT2,
        //'filter' => ArrayHelper::map(OrganismoDispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'iddispositivo', 'descripcion'),
        'filter' => ArrayHelper::map(Empleado::get_empleados('inventario'), 'idempleado', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
        'width' => '25%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado',
        'width' => '25%',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'idestado',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'observacion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'activo',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => '10%',
        'vAlign' => 'middle',
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
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro que quiere eliminar este item?'
        ],
    ],

];
