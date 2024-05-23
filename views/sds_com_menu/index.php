<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_com_menuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menú';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .content-body{
        padding-top: 10px;
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
            <div class="sds-com-menu-index">
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
                                ['role'=>'modal-remote','title'=> 'Crear Menú','class'=>'btn btn-default']).
                                Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Actualizar Grilla'])
                            ],
                        ],          
                        'striped' => true,
                        'condensed' => true,
                        'responsive' => true,          
                        'panel' => [
                            'type' => 'primary', 
                            'heading' => false,
                            'before'=>'',
                            'after'=>false,                       
                        ]
                    ])?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "options" => [
        'tabindex' => false
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
