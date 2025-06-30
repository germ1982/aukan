<?php

use app\assets\AppAsset;
use app\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Registro Recepcion';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'registro-recepcion-index';

CrudAsset::register($this);

$this->registerCssFile('@web/css/css_index_views.css', [
    'depends' => [AppAsset::class],
]);

?>

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
                            'tableOptions' => ['class' => 'custom-grid'],
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),

                            'toolbar' => [
                                [
                                    'content' =>
                                    '<div style="display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 15px;">' .
                                        Html::button('<i class="glyphicon glyphicon-stats"></i> Estadísticas', [
                                            'class' => 'btn btn-default',
                                            'id' => 'btn-estadisticas',
                                            'title' => 'Ver estadísticas',
                                        ]) .
                                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                                            'role' => 'modal-remote',
                                            'title' => 'Nuevo',
                                            'class' => 'btn btn-default',
                                        ]) .
                                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], [
                                            'data-pjax' => 1,
                                            'class' => 'btn btn-default',
                                            'title' => 'Refrescar Grilla',
                                        ]) .
                                        '<div>{toggleData}</div>' .
                                        '<div>{export}</div>' .
                                        '</div>',
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
$this->registerJs("
    // Recargar página al cerrar modal (si querés)
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    });

    // Ajustes visuales del modal al mostrarse
    $('#ajaxCrudModal').on('shown.bs.modal', function() {
        var modal = $(this);
        var content = modal.find('.modal-content');
        modal.css('overflow', 'visible');
        content.css({
            'max-height': 'calc(100vh - 60px)',
            'display': 'flex',
            'flex-direction': 'column'
        });

        var body = content.find('.modal-body');
        var footer = content.find('.modal-footer');
        if (content.find('.modal-body-footer-wrapper').length === 0 && body.length && footer.length) {
            var wrapper = $('<div class=\"modal-body-footer-wrapper\"></div>');
            body.after(wrapper);
            wrapper.append(body).append(footer);
        }
    });

    // Al hacer clic en el botón Estadísticas, mostrar modal y cargar contenido por AJAX
    $('#btn-estadisticas').on('click', function() {
        var modal = $('#ajaxCrudModal');
        modal.modal('show');

        // Título del modal
        modal.find('.modal-header').html('<h4>Estadisticas</h4>'); // <--- Añade esta línea


        // Spinner de carga en el modal-body
        modal.find('.modal-body').html('<div style=\"padding: 20px; text-align:center;\"><i class=\"fa fa-spinner fa-spin fa-2x fa-fw\"></i> Cargando...</div>');

        // Cargar contenido del modal por AJAX
        $.get('" . Url::to(['registro_recepcion/estadisticas']) . "', function(data) {
            modal.find('.modal-body').html(data);
        });
    });
    // ⬇️ NUEVO: capturar el submit del formulario para evitar redirección
    $(document).on('submit', '#form-estadisticas', function(e) {
        e.preventDefault();
        var form = $(this);
        var actionUrl = form.attr('action');
        var formData = form.serialize();

        // Mostrar spinner mientras se procesan los filtros
        $('#ajaxCrudModal .modal-body').html('<div style=\"padding: 20px; text-align:center;\"><i class=\"fa fa-spinner fa-spin fa-2x fa-fw\"></i> Cargando resultados...</div>');

        $.get(actionUrl, formData, function(response) {
            $('#ajaxCrudModal .modal-body').html(response);
        });
        setTimeout(function() {
        renderGraficoDispositivos();
        }, 100);


        return false;
    });
    
");
?>

<?php Modal::begin([
    'id' => 'ajaxCrudModal',
    //'header' => '<h4>Estadísticas de Derivación</h4>',
    'options' => ['tabindex' => false],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => ['backdrop' => 'static'],
    'footer' => Html::button('Cerrar', [
        'class' => 'btn btn-default',
        'data-dismiss' => 'modal'
    ]),
]) ?>

<?php Modal::end(); ?>