<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_tipo;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsable',
        'value' => function ($model) {
            $idconfiguracion = $model->responsable;
            if ($idconfiguracion != null) {
                $responsable = Sds_com_configuracion::findOne($idconfiguracion);
                return $responsable->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA, true),
            'idconfiguracion',
            'descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtipo',
        'value' => function ($model) {
            $tipo = $model->idtipo;
            return Sds_ent_tipo::findOne($tipo)->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_ent_tipo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idtipo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'format' => 'raw',
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ingresos',
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'egresos',
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'saldo',
        'filter' => false
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} ',
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to([
                    '/sds_ent_cta_cte/index', 'responsable' => $model->responsable,
                    'idtipo' => $model->idtipo
                ]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'title' => 'Consultar Cta Cte',
                    'data-toggle' => 'tooltip',
                ]);
            }
        ]
    ],

];
