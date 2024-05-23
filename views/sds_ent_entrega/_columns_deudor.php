<?php

use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_responsable;
use app\models\Sds_ent_tipo;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'attribute' => 'identrega',
        'width' => '6%',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'fecha_hora',
        //'header' => 'Organismo',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        // 'format' => ['date', 'php:d-m-Y H:i:s'],
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_emisor',
        'label' => 'Datos Emisor',
        'filter' => $searchModel->estado != Sds_ent_entrega::ESTADO_INICIAL,
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_receptor',
        'value' => function ($model) {
            $idconfiguracion = $model->receptor;
            if ($idconfiguracion != null) {
                $datos_responsable = Sds_ent_responsable::findOne($idconfiguracion);
                return $model->nombre_receptor . ($datos_responsable != null ? "<br> DNI " . $datos_responsable->dni : "");
            }
            return "";
        },
        'label' => 'Receptor',
        'filter' => true,
        'format' => 'html',
        'width' => '10%',
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
        'attribute' => 'cantidad',
        'width' => '5px'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'saldo',
        'width' => '5px'
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {enviar}',
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_entrega/view_interm', 'id' => $model->identrega]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0, 'title' => 'Consultar Datos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'enviar' => function ($url, $model) {
                //Enviar mail con el guardado en datos de responsable.
                $datos_responsable = Sds_ent_responsable::findOne($model->receptor);
                $url =  Url::to(['/sds_ent_entrega/enviar_deudor', 'id' => $model->identrega]);
                return $datos_responsable != null && $datos_responsable->mail != null ? Html::a('<span class= "far fa-paper-plane"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'title' => 'Enviar Mail',
                    'data-toggle' => 'tooltip',
                ]) : "";
            }
        ]
    ],


];
