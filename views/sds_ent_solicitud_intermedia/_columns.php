<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_solicitud_intermedia;
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
function getPermisoAprobar($irregular)
{
    $usuario = Yii::$app->user->identity;
    $idusuario = $usuario != null ? $usuario->idusuario : null;
    $permiso_aprobar = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_SOLICITUD_APROBAR . ")")->one();

    return $permiso_aprobar != null || !$irregular;
}

function getPermisoEliminar()
{
    $usuario = Yii::$app->user->identity;
    $idusuario = $usuario != null ? $usuario->idusuario : null;
    $permiso = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_SOLICITUD . ")
                                                order by alta desc, baja desc, modifica desc, ver desc")->one();

    return $permiso->baja > 0;
}

$permiso_todas = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=" . $usuario->idusuario . ")
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_VER_TODAS . ")")->one();
$visor_global = $permiso_todas != null;

$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $usuario = $model->usuario_carga;
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
        'attribute' => 'datos_emisor',
        'filter' => true,
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_receptor',
        'label' => 'Receptor',
        'filter' => true,
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cantidad',
        'width' => '5px'
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
        'attribute' => 'estado',
        'value' => function ($model) {
            switch ($model->estado) {
                case Sds_ent_solicitud_intermedia::ESTADO_PENDIENTE:
                    return "Pendiente";
                case Sds_ent_solicitud_intermedia::ESTADO_APROBADA:
                    return "Aprobada";
                case Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA:
                    return "Rechazada";
                case Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA:
                    return "Entregada";
            }
            return "";
        },
        'width' => '7%',
        'filter' => [
            Sds_ent_solicitud_intermedia::ESTADO_PENDIENTE => 'Pendientes',
            Sds_ent_solicitud_intermedia::ESTADO_APROBADA => 'Aprobadas',
            Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA => 'Rechazadas',
            Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA => 'Entregadas'
        ]
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observaciones',
        'value' => function ($model) {
            $detalle = $model->observaciones;
            if (strlen($detalle) > 100) {
                return nl2br(substr($detalle, 0, 100)) . "...";
            }
            return $detalle;
        },
        'format' => 'html',
        'width' => '20%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {update} {aprobar} {rechazar} {entregar} {imprimir} {delete}',
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_solicitud_intermedia/view', 'id' => $model->idsolicitudintermedia]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_solicitud_intermedia/update', 'id' => $model->idsolicitudintermedia]);
                return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'aprobar' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_solicitud_intermedia/cambiar_estado', 'id' => $model->idsolicitudintermedia, 'estado' => Sds_ent_solicitud_intermedia::ESTADO_APROBADA]);
                return  $model->estado != Sds_ent_solicitud_intermedia::ESTADO_PENDIENTE
                    || !getPermisoAprobar($model->irregular) ? '' : Html::a('<span class= "far fa-thumbs-up"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Aprobar solicitud',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => 'Aprobar Solicitud de Entrega',
                        'data-confirm-message' => 'La solicitud seleccionada de <b> ' . (Sds_com_configuracion::findOne($model->receptor)->descripcion) . '</b> será <b>APROBADA</b> ¿Desea continuar?'
                    ]);
            },
            'rechazar' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_solicitud_intermedia/cambiar_estado', 'id' => $model->idsolicitudintermedia, 'estado' => Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA]);
                return  $model->estado != Sds_ent_solicitud_intermedia::ESTADO_PENDIENTE
                    || !getPermisoAprobar($model->irregular) ? '' : Html::a('<span class= "far fa-thumbs-down"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Rechazar solicitud',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => 'Rechazar Solicitud de Entrega',
                        'data-confirm-message' => 'La solicitud seleccionada de <b> ' . (Sds_com_configuracion::findOne($model->receptor)->descripcion) . '</b> será <b>RECHAZADA</b> ¿Desea continuar?'
                    ]);
            },
            'entregar' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_solicitud_intermedia/cambiar_estado', 'id' => $model->idsolicitudintermedia, 'estado' => Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA]);
                return  $model->estado != Sds_ent_solicitud_intermedia::ESTADO_APROBADA ? '' : Html::a('<span class= "fas fa-people-carry"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Generar Entrega',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => 'Entregar Solicitud seleccionada',
                    'data-confirm-message' => 'La solicitud seleccionada de <b> ' . (Sds_com_configuracion::findOne($model->receptor)->descripcion) . '</b> será <b>ENTREGADA</b> ¿Desea continuar?'
                ]);
            },
            'imprimir' => function ($url, $model) {
                if ($model->estado == Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA) {
                    $entrega_asociada = Sds_ent_entrega::find()->where(["idsolicitudintermedia" => $model->idsolicitudintermedia])->one();
                    if ($entrega_asociada != null) {
                        $url =  Url::to(['/sds_ent_entrega/reporte_entrega_interm', 'identrega' => $entrega_asociada->identrega]);
                        return Html::a('<span class= "fas fa-print"></span>', $url, [
                            'title' => "Imprimir Entrega Asociada",
                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                            'data-toggle' => 'tooltip',
                        ]);
                    }
                }
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_solicitud_intermedia/delete', 'id' => $model->idsolicitudintermedia]);
                return !getPermisoEliminar() ? '' : Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está Seguro?',
                    'data-confirm-message' => 'La Solicitud seleccionada procederá a eliminarse'
                ]);
            },
        ]
    ],


];
