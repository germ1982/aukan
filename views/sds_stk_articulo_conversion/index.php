<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_stk_articulo_conversionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversion de Articulos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>

            <li><a href="index.php?r=sds_stk_movimiento">Movimientos de stock</a></li>
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="col-md-12 col-lg-12 col-xl-12" style="background-color: #fff; border-radius: 5px;">
<div class="sds-stk-articulo-conversion-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Nueva conversion','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reiniciar grilla']).
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'dark', 
                'heading' => false,                 
                        
            ]
        ])?>
    </div>
</div>
            </div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
