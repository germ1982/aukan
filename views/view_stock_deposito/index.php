<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use xstreamka\mobiledetect\Device;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stock General';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'sds_stk_recepcion-index';
$title_for_new = 'Nueva Recepcion';

CrudAsset::register($this);
$ban_row = 0;
?>
<style>
@media only screen and (max-device-width: 640px) {
.germ td{
    margin-left: 10px;
    margin-right: 20px;
    width: 95% !important;
    border-color: #08c!important;
    border: 1px solid !important;

   
}

.germ td:nth-child(1){
    border-top-left-radius: 15px!important;
    border-top-right-radius: 15px!important;
    background: #ccc !important;
    color: #08c!important;
    font-size: 20px!important;
    border: 1px solid !important;
    
}

.germ td:nth-child(2){
    border: 1px solid !important;
    border-bottom-left-radius: 15px!important;
    border-bottom-right-radius: 15px!important;
    font-size: 15px!important;
    border-color: #08c!important;
    text-align: left!important;

    
}

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: red !important;
    }
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
                            'rowOptions' => function($model, $key, $index, $column){ return ['class' => 'germ'];},
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'striped' => false,
                            'condensed' => true,
                            'responsive' => false,
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
        })"
);
?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
