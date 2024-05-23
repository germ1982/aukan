<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_bdc_movimiento_equipoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Movimientos de Equipo #'.str_pad($searchModel->idequipo,6,"0", STR_PAD_LEFT);
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<style>
    .content-body{
        padding-top: 15px;
    }
    
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
            <li>
                <a href="index.php?r=sds_bdc_equipo">Equipos</a>
            </li>
            <li>
                <a href="index.php?r=sds_bdc_movimiento">Movimientos Equipos</a>
            </li>
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body" style="padding-top:15px;">
                <div class="sds-bdc-movimiento-equipo-index">
                    <div id="ajaxCrudDatatable">
                        <?=GridView::widget([
                            'id'=>'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax'=>false,
                            'columns' => require(__DIR__.'/_columns.php'),
                            'toolbar'=> [
                                ['content'=>
                                    '{toggleData}'.
                                    '{export}'
                                ],
                            ], 
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => 'info',
                                'heading' => false,
                                'before'=> '',
                                'after'=>'<div class="clearfix"></div>',
                            ]
                        ])?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
