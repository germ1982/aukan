<?php

use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_ent_entrega;
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
$permiso_unificar = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_RESPONSABLE_UNIFICAR . ")")->one();

$permiso_unificar = $permiso_unificar != null;

function fecha_sort($entrega_a, $entrega_b)
{
    return strtotime($entrega_a->fecha_hora) - strtotime($entrega_b->fecha_hora);
}

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsable',
        'width' => '15%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'value' => function ($model) {
            return $model->dni != null ? $model->dni : "";
        },
        'width' => '10%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
        'width' => '10%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
        'width' => '14%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismoexterno',
        'value' => function ($model) {
            $entidad = Mds_org_organismo_externo::findOne($model->idorganismoexterno);
            return $entidad != null ? $entidad->descripcion : "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo_externo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '14%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deudor',
        'value' => function ($model) {
            return $model->deudor ? "Deudor" : "Sin Deuda";
        },
        'filter' => ['-1' => 'Todos', '0' => 'Sin Deuda', '1' => 'Deudor']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ultima_adeuda',
        'format' => 'html',
        'value' => function ($model) {            
            $rendicion_detalle = "";
            if ($model->deudor) {
                $pendientes = Sds_ent_entrega::getRendicionesPendientes($model->idresponsable, -1, date("Y-m-d"));
                if (!empty($pendientes)) {
                    usort($pendientes, "fecha_sort");
                    $rend = $pendientes[sizeof($pendientes) - 1];
                    $rendicion_detalle = "<div class='col-md-12'>" . date('d/m/Y', strtotime(str_replace('-', '/', $rend->fecha_hora)))
                        . " <br> " . $rend->detalle_tipo . " <br> Rendidas: " . ($rend->cantidad - $rend->saldo) . "/" . $rend->cantidad . "</div>";
                }
            }
            return $rendicion_detalle;
        },
        'filter' => false
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} {update}' . ($permiso_unificar ? ' {unificar}' : ''),
        'width' => '6%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_responsable/view', 'id' => $model->idresponsable]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Consultar Datos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_responsable/update', 'id' => $model->idresponsable]);
                return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Completar Datos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'unificar' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_responsable/unificar', 'id' => $model->idresponsable]);
                return Html::a(' <i class="fas fa-compress-alt"></i> ', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Unificar Responsable',
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
