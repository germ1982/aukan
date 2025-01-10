<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$columna_1 = '5%';
$columna_2 = '20%';
$columna_3 = '60%';
$columna_4 = '5%';
$columna_5 = '5%';
$columna_6 = '5%';

?>

<style>
    .custom-grid {
        font-size: 13px;
        /* Cambia el tamaño según tus necesidades */
    }

    .kv-grid-toolbar .btn {
        height: 30px;
        /* Ajusta la altura de todos los botones */
        line-height: 1.42857143;
        /* Esto centra el contenido verticalmente */
    }
</style>


<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">

            <?= GridView::widget([
                'id' => 'crud-datatable_articulo',
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'custom-grid'],
                'filterModel' => $searchModel,
                'pjax' => true,
                'columns' => [
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'idarticulo',
                        'width' => $columna_1,
                        'label' => 'ID',
                        'filter' => false,  // Desactivar filtro
                        'enableSorting' => false,  // Desactivar ordenamiento
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'rubro',
                        'width' => $columna_2,

                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'descripcion',
                        'width' => $columna_3,

                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'ingresado',
                        'width' => $columna_4,
                        'filter' => false,  // Desactivar filtro
                        'enableSorting' => false,  // Desactivar ordenamiento

                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'entregado',
                        'width' => $columna_5,
                        'filter' => false,  // Desactivar filtro
                        'enableSorting' => false,  // Desactivar ordenamiento
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'disponible',
                        'width' => $columna_6,
                        'filter' => false,  // Desactivar filtro
                        'enableSorting' => false,  // Desactivar ordenamiento
                    ],
                ],
                'toolbar' => [
                    ['content' =>
                    Html::a(
                        '<i class="glyphicon glyphicon-repeat"></i>',
                        [''],
                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                    ) .
                        '{toggleData}' .
                        '{export}'],
                ],
                'striped' => true,
                'condensed' => true,
                'responsive' => false,
                'panel' => [
                    'type' => 'primary',
                    'heading' => false,
                    'after' => '<div class="clearfix"></div>',
                ]
            ]); ?>

        </div>

</div>