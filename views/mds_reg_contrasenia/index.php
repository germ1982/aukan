<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_reg_contraseniaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de Contraseñas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .content-body{
        padding-top: 10px;
    }
    .table>thead>tr>td.danger,
    .table>tbody>tr>td.danger,
    .table>tfoot>tr>td.danger,
    .table>thead>tr>th.danger,
    .table>tbody>tr>th.danger,
    .table>tfoot>tr>th.danger,
    .table>thead>tr.danger>td,
    .table>tbody>tr.danger>td,
    .table>tfoot>tr.danger>td,
    .table>thead>tr.danger>th,
    .table>tbody>tr.danger>th,
    .table>tfoot>tr.danger>th {
        color: #111;
        background-color: #dee !important;
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
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12" style="background-color: #fff; border-radius: 5px;">
        <section class="panel">
            <?= Html::beginForm('', 'post', ['target' => '_blank']); ?>
                <div class="mds-reg-contrasenia-index">
                    <div id="ajaxCrudDatatable">
                        <?=GridView::widget([
                            'id'=>'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax'=>true,
                            'columns' => require(__DIR__.'/_columns.php'),
                            'toolbar'=> [
                                ['content'=>
                                    Html::a(
                                    '<i class="fas fa-print"></i> Reporte',
                                    Url::to(['reporte_contrasenia']),
                                    ['role' => 'post', 'data' => ['method' => 'post'], 'arget' => '_blank', 'title' => 'Reporte', 'class' => 'btn btn-primary']).
                                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                                    ['role'=>'modal-remote','title'=> 'Nueva Contraseña','class'=>'btn btn-default']).
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Actualizar'])
                                ],
                            ],          
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,          
                            'panel' => [
                                'type' => 'primary', 
                                'heading' => false,
                                'before'=> '',
                                'after'=>false,
                            ]
                        ])?>
                    </div>
                </div>
            <?= Html::endForm(); ?>
        </section>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    'size'=> Modal::SIZE_LARGE
])?>
<?php Modal::end(); ?>
