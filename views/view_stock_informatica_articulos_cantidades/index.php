<?php

use app\models\Persona;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Saldos de Articulos de Stock de Informatica';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'view-stock-articulos-cantidades-index';

CrudAsset::register($this);
?>

<style>
    .active-row {
        background-color: #f4dfb9 !important;
    }

    .custon-row:hover {
        background-color: #f4dfb9 !important;
        /* cursor: pointer; */
    }

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

    .kv-grid-toolbar {

        display: flex;
        /* background-color: red; */

    }

    .btn-toolbar {
        width: 100%;

    }

    .btn-group {
        width: 100%;

    }

    .botones_a {
        text-align: left;
        display: flex;
        gap: 10px;
    }

    .botones_b {
        justify-content: flex-end;
        /* Alineación derecha */
        display: flex;
        width: 100%;
        /* Asegura que el contenedor ocupe todo el ancho de la columna */
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>


<div class="row">

    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="<?= $clase ?>">
                    <div id="ajaxCrudDatatable">

                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'custom-grid'],
                            'rowOptions' => ['class' => 'custon-row', 'onClick' => 'marcarRow(this)'],
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                    '<div class="row">' .
                                        '<div class="col-md-9"> 
                                    <div class="botones_a">' .
                                        Html::a(
                                            'Ingresos',
                                            ['/stock_informatica_ingreso'],
                                            ['title' => 'Egresos', 'class' => 'btn btn-primary neon']
                                        ) .
                                        Html::a(
                                            'Egresos',
                                            ['/stock_informatica_egreso'],
                                            ['title' => 'Egresos', 'class' => 'btn btn-primary neon']
                                        ) .
                                        Html::a(
                                            'Articulos',
                                            ['/articulo'],
                                            ['title' => 'Articulos', 'class' => 'btn btn-primary neon']
                                        ) .

                                        '</div>
                                </div>' .
                                        '<div class="col-md-3"> 
                                    <div class="botones_b">' .

                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                        ) .
                                        '{toggleData}' .
                                        '{export}' .
                                        '</div>
                                </div>' .
                                        '</div>'
                                ],
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
            </div>
        </section>
    </div>
</div>
<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
<script>
    function marcarRow(element) {
        // Quita la clase 'active-row' de todas las filas en la tabla con id 'crud-datatable'
        document.querySelectorAll('#crud-datatable tbody tr').forEach(function(row) {
            row.classList.remove('active-row');
        });
        // Añade la clase 'active-row' a la fila que fue clicada
        element.classList.add('active-row');

    }
</script>