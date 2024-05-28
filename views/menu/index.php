<?php

use app\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Edicion de Menu';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'menu-index';

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

    .modal-header {
    background: linear-gradient(135deg, #6b8cff 0%, #89f7fe 100%);
    color: white;
    padding: 12px;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Roboto', sans-serif;
    align-items: center;
    height: 45px!important;
}

.modal-header .close {
    margin-top: -4px;
    color: white;
    opacity: 1;
    font-size: 3.5rem;
    position: relative;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 60%, rgba(0,0,0,0) 70%);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.5), 0 0 20px rgba(255, 255, 255, 0.3);
    transition: background 0.3s, box-shadow 0.3s;
}

.modal-header .close:hover {
    background: radial-gradient(circle, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0.4) 60%, rgba(0,0,0,0) 70%);
    box-shadow: 0 0 30px rgba(255, 255, 255, 1.0), 0 0 60px rgba(255, 255, 255, 0.8);
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
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>

                                Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create'],
                                        ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']
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