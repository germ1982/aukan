<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = 'Concursos Solicitud';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);


$botonManual = Html::button('<i class="glyphicon glyphicon-save"></i> Manual de Usuario', ['id' => 'boton-manual-concurso', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);

$urlPostulaciones =  Url::to(['/mds_conc_postulacion/index']);
$botonVerPostulaciones =  Html::a('Ver listado de postulaciones', $urlPostulaciones, [
    'role' => 'post', 'data-pjax' => 0,
    'class' => 'btn btn-default pull-left btnPostulaciones'
]);
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
            <li><span>Solicitud</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-conc-solicitud-index">
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
                                    'content' =>
                                    $botonVerPostulaciones .
                                        (($permission['permissionRead']) ?  Html::button(
                                            '<i class="glyphicon glyphicon-save"></i>',
                                            [
                                                'name' => 'btn_print_solicitud',
                                                'id' => 'btn_print_solicitud',
                                                'data-request-method' => 'post',
                                                'data-toggle' => 'tooltip',
                                                'class' => 'btn btn-info',
                                                'title' => Yii::t('app', 'Imprimir solicitudes'),
                                                'onclick' => 'printSolicitudes()',
                                                'style' => 'margin-right:10px',
                                                'disabled' => true
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
    changeCheckbox();
    mostrarManual();
    "
);
?>
<script>
    function changeCheckbox() {
        $('input:checkbox').change(function() {
            let cant = $('#crud-datatable').yiiGridView('getSelectedRows').length;
            $('#btn_print_solicitud').prop('disabled', true);
            if (cant > 0) {
                $('#btn_print_solicitud').prop('disabled', false);
            }
        });
    }

    function printSolicitudes() {
        let getCheck = [];
        getCheck = $('#crud-datatable').yiiGridView('getSelectedRows');
        $("input:checkbox").prop('checked', false);
        $('#btn_print_solicitud').prop('disabled', true);
        window.open("index.php?r=mds_conc_solicitud/reporte&ids=" + getCheck, '_blank');
    }

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