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
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_ENTREGA_INTERMEDIA . ") 
                                                order by alta desc, baja desc, modifica desc, ver desc")->one();

function getPermisoPrimerIngreso($model)
{
    $permisos = Mds_seg_permiso::getPermisosByIdUsuario(Yii::$app->user->identity->idusuario)->all();
    $primer_ingreso = false;
    foreach ($permisos as $r) :
        switch ($r->iditem) {
            case Mds_seg_item::MODULO_ENT_PRIMER_INGRESO:
                $primer_ingreso = true;
                break;
        }
    endforeach;

    return $primer_ingreso || ($model->emisor != null && !$primer_ingreso);
}

function getPuedeCerrarse($model){
    $intermedias = Sds_ent_entrega::find()->where(["emisor"=>$model->identrega])->all();
    return $model->emisor!=null || empty($intermedias);
}

$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => 'yii\grid\CheckboxColumn',
        'checkboxOptions' => function ($data) {
            return ['value' => $data->identrega];
        },
    ],
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
        'attribute' => 'identrega',
        'width' => '6%',
        'vAlign' => 'middle',
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
        'attribute' => 'estado_cierre',
        'value' => function ($model) {
            return $model->estado_cierre == 0 ? "NO" : ($model->estado_cierre == 2 ? "N/C" : "SI");
        },
        'width' => '5%',
        'filter' => ["0" => "NO", "1" => "SI", "2" => "N/C"],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observaciones',
        'value' => function ($model) {
            $detalle = "<b>Observaciones: </b>" . $model->observaciones;
            $detalle = ($model->fecha_cierre != null ? "<b>Fecha Cierre:</b> " . date('d/m/Y', strtotime($model->fecha_cierre)) . "<br>" : "") . $detalle;
            $detalle = ($model->numero_desde != null ? "<b>Número Desde:</b> " . $model->numero_desde
                . "<br><b>Número Hasta:</b> " . $model->numero_hasta . "<br>" : "") . $detalle;
            $detalle = ($model->saldo != null ? "<b>Saldo:</b> " . $model->saldo . "<br>" : "") . $detalle;
            if (strlen($detalle) > 250) {
                return nl2br(substr($detalle, 0, 100)) . "...";
            }
            return $detalle;
        },
        'format' => 'html',
        'width' => '30%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => ' {view} ' . ($permiso_entrega->modifica ? '{update} ' : '') . '{imprimir} {cerrar} ' . ($permiso_entrega->baja ? ' {delete}' : ''),
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_entrega/view_interm', 'id' => $model->identrega]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_entrega/update_interm', 'id' => $model->identrega]);
                return getPermisoPrimerIngreso($model) ? Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]) : '';
            },
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_entrega/reporte_entrega_interm', 'identrega' => $model->identrega]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'cerrar' => function ($url, $model) {
                $url =  Url::to(['/sds_ent_cierre/cerrar', 'identrega' => $model->identrega, 'estado' => $model->estado_cierre]);
                return getPuedeCerrarse($model) ? Html::a('<span class="fas fa-clipboard-check"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'title' => 'Rendición Final',
                    'data-toggle' => 'tooltip',
                ]):"";
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
