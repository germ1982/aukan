<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_veh_vehiculoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Habilitaciones de Vehículo';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .content-body{
        padding-top: 13px;
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
                <a href="index.php?r=sds_veh_vehiculo">
                    Vehículos
                </a>
            </li>
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 text-primary" style="background-color: #FCFFFF; border-radius: 3px; border-bottom: 2px solid #ACD1EC; margin-bottom:2px;">
        <h4><b><?=$vehiculo->tipo_descripcion?> <?=$vehiculo->marca?> <?=$vehiculo->modelo_descripcion?> - <?=$vehiculo->dominio?></b></h4>
    </div>
</div>
<div class="row" style="padding-top:1px;">
    <div class="col-md-12 col-lg-12 col-xl-12" style="background-color: #fff; border-radius: 5px;">
        <section class="panel">
            <div class="sds-veh-habilitacion-index">
                <div id="ajaxCrudDatatable">
                    <?=GridView::widget([
                        'id'=>'crud-datatable-habilitacion',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'pjax'=>true,
                        'columns' => require(__DIR__.'/_columns.php'),
                        'toolbar'=> [
                            ['content'=>
                                Html::a('<i class="glyphicon glyphicon-plus"></i>', 
                                    ['create', 
                                    'vehiculo'=>$searchModel->idvehiculo
                                    ],
                                ['role'=>'modal-remote','title'=> 'Nueva Habilitación','class'=>'btn btn-default']).
                                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'vehiculo'=>$searchModel->idvehiculo],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Recargar Grilla'])
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
        'tabindex' => false, // important for Select2 to work properly
    ],
    "footer"=>"",// always need it for jquery plugin
    //'size' => Modal::SIZE_LARGE,
])?>
<?php Modal::end(); ?>
