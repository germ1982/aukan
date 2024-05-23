<?php

use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_tipo;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$permiso_entrega = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_ENTREGAS . ")")->one();

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
        'attribute' => 'identrega',
        'width' => '7%'
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
        'attribute' => 'dni',
        'width' => '10%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cantidad',
        'width' => '5%'
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
        'label' => 'Número / Detalle',
        'attribute' => 'observaciones',
        'format' => 'html'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $usuario = $model->idusuario;
            if ($usuario != null) {
                $user = Mds_seg_usuario::findOne($usuario);
                return $user->user;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_seg_usuario::find()->orderBy(['user' => SORT_ASC])->all(), 'idusuario', 'user'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'width' => '14%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'entidad',
        'value' => function ($model) {
            $entidad = Mds_org_organismo_externo::findOne($model->entidad);
            return $entidad->descripcion;
        },
        'filter' => false
        /* 'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo_externo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Entidad...'],
        'format' => 'raw',
        'width' => '14%', */
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
        'attribute' => 'estado_acta',
        'value' => function ($model) {
            return $model->estado_acta == 1 ? 'Auditadas' : 'Pendientes';
        },
        'width' => '10%',
        'filter' => [-1 => 'Todas', 0 => 'Pendientes', 1 => 'Auditadas']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {update} {imprimir}' . ($permiso_entrega->baja ? ' {delete}' : ''),
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_entrega/update', 'id' => $model->identrega]);
                return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_entrega/reporte_entrega', 'identrega' => $model->identrega]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'deleteOptions' => [
                'role' => 'modal-remote', 'title' => 'Eliminar',
                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                'data-request-method' => 'post',
                'data-toggle' => 'tooltip',
                'data-confirm-title' => '¿Está Seguro?',
                'data-confirm-message' => 'El item seleccionado procederá a eliminarse'
            ],
        ]
    ],


];
