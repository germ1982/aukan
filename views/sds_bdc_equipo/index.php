<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_bdc_equipoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equipos';
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
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <?= Html::beginForm('', 'post', ['target' => '_blank']); ?>
            <div class="panel-body" style="padding-top:0;">
                <div class="sds-bdc-equipo-index">
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
                                        '<i class="fas fa-print"></i> Imprimir QR -A4-',
                                        Url::to(['reporte_qr', 'format'=>'a4']),
                                        ['role' => 'post', 'data' => ['method' => 'post'], 'target' => '_blank', 'title' => 'Imprimir QR', 'class' => 'btn btn-primary']
                                    ).
                                    /* Html::a(
                                        '<i class="fas fa-print"></i> Imprimir QR -A6-',
                                        Url::to(['reporte_qr', 'format'=>'a6']),
                                        ['role' => 'post', 'data' => ['method' => 'post'], 'target' => '_blank', 'title' => 'Imprimir QR', 'class' => 'btn btn-info']
                                    ). */
                                    Html::a('<i class="glyphicon glyphicon-plus"></i> Cargar Equipo', ['create'],
                                    ['role' => 'post', 'class'=>'btn btn-success', 'title'=>'Cargar Equipo']).
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Recargar Grilla']).
                                    '{toggleData}'.
                                    '{export}'
                                ],
                            ],          
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,          
                            'panel' => [
                                'type' => 'primary', 
                                'heading' => false,
                                /*'before'=>BulkButtonWidget::widget([
                                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Todos',
                                                ["bulk-delete"] ,
                                                [
                                                    "class"=>"btn btn-danger btn-xs",
                                                    'role'=>'modal-remote-bulk',
                                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                                    'data-request-method'=>'post',
                                                    'data-confirm-title'=>'¿Seguro que desea eliminar todos los registros seleccionados?',
                                                    'data-confirm-message'=>'¡Los registros se eliminaron de manera correcta!'
                                                ]),
                                        ]).                        
                                        '<div class="clearfix"></div>',*/
                            ]
                        ])?>
                    </div>
                </div>
            </div>
            <?= Html::endForm(); ?>
        </section>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [
        'tabindex' => false
    ],
    "footer"=>"",// always need it for jquery plugin
    "size" => Modal::SIZE_LARGE,
])?>
<?php Modal::end(); ?>
