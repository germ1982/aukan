<?php

use app\models\Sds_reg_registro;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

switch ($searchModel->entidad) {
    case Sds_reg_registro::ENT_INFORMATICA:
        $this->title = 'Registro de Trabajo Técnico';
        $columns = '/_columns.php';
        break;
    case Sds_reg_registro::ENT_MANTENIMIENTO:
        $this->title = 'Registro de Mantenimiento';
        $columns = '/_columns_mantenimiento.php';
        break;
    case Sds_reg_registro::ENT_RUMBO:
        $this->title = 'Registro Rumbo';
        $columns = '/_columns_rumbo.php';
        break;
}

//$this->title = ($searchModel->entidad == Sds_reg_registro::ENT_INFORMATICA ? 'Registro de Trabajo Técnico' : 'Registro de Mantenimiento');
CrudAsset::register($this);
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
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
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
                <div class="sds-vio-intervencion-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            //Según la entidad serán las columnas a mostrar. Informatica:2072; Mantenimineto:2073;
                            'columns' => require(__DIR__ . $columns),
                            'toolbar' =>
                            [
                                [
                                    'content' =>
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create', 'entidad' => $searchModel->entidad],
                                        ['role' => 'modal-remote', 'title' => 'Nueva Solicitud', 'class' => 'btn btn-default']
                                    ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            ['', 'entidad' => $searchModel->entidad],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'id' => 'btn_refresh', 'title' => 'Refrescar Grilla']
                                        ) .
                                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'primary',
                                'heading' => false,
                                /* 'heading' => '<i class="glyphicon glyphicon-list"></i> Sds Vio Intervencions listing',
                                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>', */
                                /* 'after'=>BulkButtonWidget::widget([
                                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                                ["bulk-delete"] ,
                                                [
                                                    "class"=>"btn btn-danger btn-xs",
                                                    'role'=>'modal-remote-bulk',
                                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                                    'data-request-method'=>'post',
                                                    'data-confirm-title'=>'Are you sure?',
                                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                                ]),
                                        ]).   */
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
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
        });
    setInterval(function(){if (!$(\"#ajaxCrudModal\").hasClass('fade modal in')) location.reload(); }, 300000);"
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static',
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<!-- Cambio el z-index del Modal para evitar que otros elementos se superpongan -->
<?php

$this->registerJs(
    "$('#crud-datatable-filters').children('td').children().css('z-index', '0')"
);
?>