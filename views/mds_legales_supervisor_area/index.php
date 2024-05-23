<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_reproam_mandatoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supervisores/as de áreas';
$this->params['breadcrumbs'][] = $this->title;
$usuarioAuth = Yii::$app->user->identity;

$string = "";
$string .= "{create}";
$string .= "{view}";
$string .= "{update}";
$string .= "{print}";
$string .= "{delete}";

if ($hasRolAdminGeneral) {
    $string .= "{reactivate}";
}

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
                <a href="index.php">
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
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-certificaciones-index table-responsive">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' => Html::a(
                                    '<i class="glyphicon glyphicon-plus"></i>',
                                    ['create'],
                                    [
                                        'data-pjax' => 0, 'role' => 'post', 'title' => 'Nueva solicitud',
                                        'class' => 'btn btn-success',
                                        'style' => 'margin-right: 10px;',
                                    ]
                                ) .
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
                            'responsive' => true,
                            'panel' => [
                                'type' => 'default',
                                'heading' => '',
                                'after' => '<div class="clearfix"></div>'
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
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');
    "
);
