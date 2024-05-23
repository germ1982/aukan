<?php

use yii\helpers\Url;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\date\DatePicker;

function crear_celda($label, $contenido, $ancho)
{
    echo "
    <div class='col-xs-$ancho'>  
        <h6><b>$label</b></h6>
        <p style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px;'>
                $contenido
        </p>
    </div>";
}

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$permiso_stock = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_STK_RECEPCION . ")")->one();
$permiso_stock = $permiso_stock != null ? 1 : 0;

return [

    /*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idregistro',
    ], */
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            /*return $model->problema != null ?
                Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";*/
            return Yii::$app->controller->renderPartial('vinculacion/respuestas_para_enviar/_expand', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlegalesoficio',
        'value' => function ($model) {
            return $model['idlegalesoficio'];
        },
        // 'width' => '4%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarea',
        'label' => 'Área',
        'value' => function ($model) {
            return ($model->areaOficio) ? $model->areaOficio->descripcion : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $areaOficioFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caso',
        'label' => 'Caso',
        'value' => function ($model) {
            return $model['caso'];
        },
        // 'width' => '20%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'lugar_libramiento',
        'label' => 'Entidad Requirente',
        'value' => function ($model) {
            return $model['lugar_libramiento'];
        },
        // 'width' => '30%',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caratula',
        'label' => 'Carátula',
        'value' => function ($model) {
            return $model['caratula'];
        },
        'format' => 'raw',
        // 'width' => '18%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_oficio',
        'label' => 'Tipo requerimiento',
        // 'width' => '18%',
        'value' => function ($model) {
            return $model->tipoOficio->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoOficioFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numero_expediente',
        'label' => 'Numero de expediente',
        'value' => function ($model) {
            return $model['numero_expediente'];
        },
        'format' => 'raw',
        // 'width' => '18%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anio_expediente',
        'label' => 'Año',
        'value' => function ($model) {
            return $model['anio_expediente'];
        },
        'format' => 'raw',
        // 'width' => '18%',
    ],
    [
        'attribute' => 'fecha_inicio',
        'value' => function ($model) {
            $ultimaRespuestaAprobada = $model->getLastRespuestasEstadoByEstado('APROBADO');
            if ($ultimaRespuestaAprobada) {
                $indexLastRespuesta = count($ultimaRespuestaAprobada) -1;
                $fr = date_create($ultimaRespuestaAprobada[$indexLastRespuesta]['fecha_inicio']);
                $fr = date_format($fr, 'd/m/Y');
            } else {
                $fr = '';
            }
            return $fr;
        },
        // 'format' => ['date', 'php:d-m-Y H:i:s'],
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fechaAprobado',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'layout' => '{input} {remove}',
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'todayHighlight' => true,
            ]
        ])
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        //'template' => '{view} ' . ($permiso_edicion == 1 ? '{update}' : ''),
        'template' => $string,
        // 'width' => '7%',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        //'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        //'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'buttons' =>
        [
            'view' => function ($url, $model, $key) {
                $idlegalesoficio = $model['idlegalesoficio'];
                $url = Url::to(['mds_legales_oficio/view', 'idlegalesoficio' => $idlegalesoficio]);

                return Html::a('<span style="margin-left:0.5rem" class="fas fa-eye"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,  'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver'
                ]);
            },
        ],
    ],

    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'fecha_ingreso',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'usuario_ingreso',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'usuario_derivacion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'fecha_solucion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'equipo_detalle',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'ip',
    // ],


];
