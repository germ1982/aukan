<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = 'Concursos Etapas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);


$botonManual = Html::button('<i class="glyphicon glyphicon-save"></i> Manual de Usuario', ['id' => 'boton-manual-concurso', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);

?>

<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnPrint {
        background: grey !important;
        color: white !important;
    }

    .btnManual {
        margin-left: 5px !important;
    }

    .btnPostulaciones {
        background: grey !important;
        color: white !important;
        margin-right: 1rem;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Etapa</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-conc-etapa-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' =>
                            [
                                [
                                    'content' => ($permission['permissionCreate'] ? Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create'],
                                        [
                                            'role' => 'modal-remote',
                                            'title' => 'Crear nueva etapa',
                                            'class' => 'btn btn-success',
                                            'style' => 'margin-right:10px',
                                        ]
                                    ) : "") .
                                        ($permission['permissionRead'] ? Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            [
                                                'data-pjax' => 1, 'class' => 'btn btn-default',
                                                'title' => 'Refrescar Grilla'
                                            ]
                                        ) : "") .
                                        '{toggleData}' .
                                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' =>
                            [
                                'type' => 'default',
                                'responsive' => true,
                                'before' =>  $botonManual,
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
$this->registerJs(
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    });
    mostrarManual();
    "
);
?>
<script>
    function mostrarManual() {
        $('#boton-manual-concurso').click(function() {
            $.ajax({
                type: 'POST',
                url: "Url::to(['/mds_conc_solicitud/guardarlogmanualusuario'])",
                data: {},

                success: function(success) {
                    if (success) {
                        window.open('" . Url::base() . "/instructivos/instructivo_legales.pdf', '_blank');
                    } else {
                        console.log(errormessage);
                        alert('Ocurrió un error');
                    }
                },
                error: function(errormessage) {
                    console.log(errormessage);
                    alert('Ocurrió un error');
                }
            });
        })
    }
</script>