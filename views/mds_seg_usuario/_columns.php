<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo_externo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'user',
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'pass',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcontacto',
        'value' => function ($model) {
            $contacto = $model->idcontacto;
            if ($contacto != null) {
                $contacto = Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                join sds_com_persona p on p.idpersona=c.idpersona
                where idcontacto=" . $contacto)->one();
                return $contacto->nombre . " " . $contacto->apellido;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
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
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'format' => 'raw',
        'width' => '15%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'externo',
        'value' => function ($model) {
            $entidad = Mds_org_organismo_externo::findOne($model->externo);
            return $entidad != null ? $entidad->descripcion : "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo_externo::find()
            ->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '14%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{edificios} {view} {update} {blanqueo} {delete}',
        'width' => '10%',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'buttons' => [
            'edificios' => function ($url, $model) {
                $url =  Url::to(['/mds_seg_usuario_capa_item/index', 'idusuario' => $model->idusuario]);
                return  Html::a('<i class="fas fa-map-marked-alt"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Administrar Edificios',
                    'data-toggle' => 'tooltip'
                ]);
            },
            'blanqueo' => function ($url, $model) {
                $url =  Url::to(['/mds_seg_usuario/blanqueo', 'idusuario' => $model->idusuario]);
                return  $model->idcontacto != null ? Html::a('<i class="fas fa-user-lock"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Blanquear Contraseña',
                    'data-toggle' => 'tooltip',
                    /* 'data' => [
                        'confirm' => Yii::t('app', 'La contraseña del usuario '.$model->user.' será reiniciada. Desea continuar?'),
                        'method' => 'post',
                        'role' => 'modal-remote',
                    ] */
                ]) : "";
            },
        ],
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Borrar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
