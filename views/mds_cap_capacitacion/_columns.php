<?php

use yii\helpers\Url;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Mds_org_organismo;
use app\models\Mds_org_organismo_externo;

return [

    /*         [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idcapacitacion',
    ], */

    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Temática',
        'attribute' => 'tematica',
        'value' => function ($model) {
            $idtematica = $model->tematica;
            if ($idtematica != null) {
                $tematica = Sds_com_configuracion::findOne($idtematica);
                return $tematica->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_CAP_TEMATICA), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Temática...'],
        'format' => 'raw',
        'width' => '20%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'label' => 'Nombre',
    ],

    /*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idusuario',
    ], */

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'label' => 'Organismo',
        'value' => function ($model) {
            $idorganismo = $model->idorganismo;
            $idorganismoexterno = $model->idorganismoexterno;

            if ($idorganismo != null) {
                $organismo = Mds_org_organismo::findOne($idorganismo);
                return $organismo->descripcion;
            }  else if ($idorganismoexterno != null){
                $organismo = Mds_org_organismo_externo::findOne($idorganismoexterno);
                return $organismo ? $organismo->descripcion : "";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filterOrganismos,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => $permiso_global != 0, 'disabled' => false],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '50%',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'detalle',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} ' . ($permiso_edicion == 1 ? '{update}' : ''),
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro de eliminar este item?'
        ],
    ],

];
