<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\helpers\Html;

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
            return Yii::$app->controller->renderPartial('vinculacion/_expand', ['model' => $model]);
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
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'lugar_libramiento',
        'label' => 'Entidad Requirente',
        'value' => function ($model) {
            return $model['lugar_libramiento'];
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caratula',
        'label' => 'Carátula',
        'value' => function ($model) {
            return $model['caratula'];
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_oficio',
        'label' => 'Tipo requerimiento',
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
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anio_expediente',
        'label' => 'Año',
        'value' => function ($model) {
            return $model['anio_expediente'];
        },
        'format' => 'raw',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'vAlign' => 'middle',
        'hAlign' => 'center',
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

];
