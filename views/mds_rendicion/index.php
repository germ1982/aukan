<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\bootstrap\Modal;
use yii\web\View;

$this->title = 'Rendición';
$this->params['breadcrumbs'][] = $this->title;

$botonManual = Html::button('<i class="glyphicon glyphicon-save"></i> Manual de Usuario', ['id' => 'boton-manual-rendicion', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);

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

    .btn-rendicion-comprobante {
        background-color: transparent;
        border: none;
        color: #08c;
        text-decoration: underline;
        cursor: pointer;
        padding: 0;
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
            <li><span>Rendición</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-rendicion-index">
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
                                            'data-pjax' => 0, 'role' => 'post',
                                            'title' => 'Nueva solicitud',
                                            'class' => 'btn btn-success',
                                            'style' => 'margin-right:10px'
                                        ]
                                    ) : "") .

                                        (($permission['permissionPrint']) ?  Html::button(
                                            '<i class="glyphicon glyphicon-save"></i>',
                                            [
                                                'name' => 'btn_print_rendicion',
                                                'id' => 'btn_print_rendicion',
                                                'data-request-method' => 'post',
                                                'data-toggle' => 'tooltip',
                                                'class' => 'btn btn-info',
                                                'title' => Yii::t('app', 'Imprimir rendiciones'),
                                                'onclick' => 'printRendiciones()',
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

<?php

require(__DIR__ . '/modal_comprobante.php');

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');


Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]);
Modal::end();

CrudAsset::register($this);



$this->registerJs(
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');
    
    changeCheckbox();
    mostrarManual();

    $('.btn-rendicion-comprobante').click(function() {
        let idrendicion = $(this).attr('data-idrendicion');
        $('#idrendicion').val(idrendicion);
    })
    "
);

?>

<script>
    function changeCheckbox() {
        $('input:checkbox').change(function() {
            let cant = $('#crud-datatable').yiiGridView('getSelectedRows').length;
            $('#btn_print_rendicion').prop('disabled', true);
            if (cant > 0) {
                $('#btn_print_rendicion').prop('disabled', false);
            }
        });
    }

    function printRendiciones() {
        let getCheck = [];
        getCheck = $('#crud-datatable').yiiGridView('getSelectedRows');
        $("input:checkbox").prop('checked', false);
        $('#btn_print_rendicion').prop('disabled', true);
        window.open("index.php?r=mds_rendicion/reporte&id=" + getCheck, '_blank');
    }

    function mostrarManual() {
        $('#boton-manual-rendicion').click(function() {
            $.ajax({
                type: 'POST',
                url: "index.php?r=mds_rendicion/guardarlogmanualusuario",
                data: {},

                success: function(success) {
                    if (success) {
                        window.open('<?= Url::base() ?>/instructivos/instructivo_rendicion.pdf', '_blank');
                    } else {
                        // console.log(errormessage);
                        alert('Ocurrió un error');
                    }
                },
                error: function(errormessage) {
                    // console.log(errormessage);
                    alert('Ocurrió un error');
                }
            });
        })
    }
</script>