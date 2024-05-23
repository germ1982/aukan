<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$info = "";
$dni = "";

if ($postulacion) {
    $info = ucwords("{$postulacion->solicitud->nombre} {$postulacion->solicitud->apellido}");
    $dni = is_numeric($postulacion->solicitud->documento) ? number_format($postulacion->solicitud->documento, 0, '', '.') : $postulacion->solicitud->documento;
}

$dniString = $dni ? "(DNI <b>$dni</b>)" : '';
$this->title = $postulacion ? "Estados de la postulación #<b>{$postulacion->idpostulacion}</b> de $info $dniString" : "Estado postulaciones";
$subtitle = $postulacion ? "" :  "Estado postulaciones";
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
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
                <div class="mds-conc-historial-index">
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
                                    'content' => ($postulacion && $permission['permissionCreate'] ? Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create', 'idpostulacion' => $postulacion->idpostulacion],
                                        [
                                            'role' => 'modal-remote',
                                            'title' => 'Crear nuevo registro',
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