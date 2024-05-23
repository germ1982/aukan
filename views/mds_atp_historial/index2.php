<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Mds_atp_solicitud;
/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_atp_historialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//id_search es el id de atp solicitud
$una_solicitud = Mds_atp_solicitud::findOne($id_search);
$info=ucwords($una_solicitud->nombre.' '.$una_solicitud->apellido);  
$el_dni=number_format($una_solicitud->documento, 0, '', '.'); 
$this->title = 'Registro de Observaciones de ATPCen';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<header class="page-header">

    <h2><?= $this->title.' de "'.$info.' ('.$el_dni.')"' ?></h2>

    <div class="right-wrapper pull-right"> 
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body"> 
<div class="mds-atp-historial-index">
    <div id="ajaxCrudDatatable"> 
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create','id_atp_solicitud' => $id_search],
                    ['role'=>'modal-remote','title'=> 'Crear nuevo registro de ATPCen','class'=>'btn btn-default']).                    
                    '{toggleData}'.
                    '{export}'
                ],
            ],               
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'default', 
                'heading' => '',                
                'after'=> '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php $link_anterior=$_SERVER["HTTP_REFERER"];?>
<a class="btn btn-info" href="<?= $link_anterior;?>">Volver a Tarjeta ATPCen</a>
</div>
</div>
        </section>
        </div>


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
