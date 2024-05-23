<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$template = '';
$this->title = 'Movimientos de Stock';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'sds_stk_movimiento-index';
$title_for_new = 'Nuevo Movimiento';

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
                <div class="<?= $clase ?>">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'rowOptions' => function($model, $key, $index, $column){
                                if($model->generado==1)
                                    {
                                        return ['myurl' => $model->idmovimiento,'style' => 'background-color: #e0f5a6; color: #000; cursor: pointer'];
                                    }
                                
                            },
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require __DIR__ . '/_columns.php',
                            'toolbar' => [
                                [
                                    'content' =>
                                        Html::a(
                                            '<i class="glyphicon glyphicon-transfer"></i> Cambio de Deposito',
                                            ['create'],
                                            [
                                                'role' => 'modal-remote',
                                                'title' => $title_for_new,
                                                'class' => 'btn btn-info',
                                            ]
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i> Conversión Articulo',
                                            ['conversion'],
                                            [
                                                'role' => 'modal-remote',
                                                'title' => $title_for_new,
                                                'class' => 'btn btn-success',
                                            ]
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            [
                                                'data-pjax' => 1,
                                                'class' => 'btn btn-default',
                                                'title' => 'Refrescar Grilla',
                                            ]
                                        ) .
                                        '{toggleData}' .
                                        '{export}',
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
                                                    'data-confirm-title'=>'Esta seguro?',
                                                    'data-confirm-message'=>'Esta seguro que desea eliminar este movimiento?'
                                                ]),
                                        ]).   */
                                'after' => '<div class="clearfix"></div>',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php $this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
); ?>

<?php Modal::begin([
    'id' => 'ajaxCrudModal',
    'options' => [
        'tabindex' => false, // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static',
    ],
    'footer' => '', // always need it for jquery plugin
]); ?>
<?php Modal::end(); ?>
