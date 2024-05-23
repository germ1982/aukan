<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Url;

$info = "";
$dni = "";

if ($solicitud) {
    $info = " de {$solicitud->nombre} {$solicitud->apellido}";
    $dni = is_numeric($solicitud->documento) ? number_format($solicitud->documento, 0, '', '.') : $solicitud->documento;
}
$dniString = $dni ? "(DNI <b>$dni</b>)" : '';

$this->title = $solicitud ? "Postulaciones de la solicitud #{$solicitud->idsolicitud} $info $dniString" : "Postulaciones";
$subtitle = $solicitud ? "" :  "Postulaciones";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$urlEstados =  Url::to(['/mds_conc_historial/index']);
$botonVerEstados =  Html::a('Ver listado de estados', $urlEstados, [
    'role' => 'post', 'data-pjax' => 0,
    'class' => 'btn btn-default pull-left btnEstados'
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

    .btnEstados {
        background: grey !important;
        color: white !important;
        margin-right: 1rem;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>
    <?php if ($subtitle) : ?>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.php">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span><?= $subtitle ?></span></li>
            </ol>
            <div class="sidebar-right-toggle"></div>
        </div>
    <?php endif; ?>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-conc-postulacion-index">
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
                                    $botonVerEstados .
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
                            'panel' => [
                                'type' => 'default',
                                'heading' => '',
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
                <?php if ($urlAnterior) : ?>
                    <a class="btn btn-info" href="<?= $urlAnterior ?>">Volver</a>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>


<?php
$this->registerJs("
$('#crud-datatable-filters').children('td').children().css('z-index', '0');
$('#ajaxCrudModal').on('hidden.bs.modal', function() {
    location.reload();
});
");

Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>