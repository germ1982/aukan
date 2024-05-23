<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_atp_montoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Generar archivo de Montos';
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
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="alert alert-warning text-center">
            <b>¡AVISO!</b><br>Recordar que para la generación del Documento de Montos se toman en cuenta todas aquellas solicitudes que se encuentran en Estado: <b>APROBADA</b>.
        </div>
    </div>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-atp-monto-index">
                    <div id="ajaxCrudDatatable">
                        <?=GridView::widget([
                            'id'=>'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax'=>true,
                            'columns' => require(__DIR__.'/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i> Generar archivo de Montos',
                                        ['create'],
                                        ['role' => 'modal-remote', 'title' => 'Generar archivo de Montos', 'class' => 'btn btn-success']
                                    ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                                        ) .
                                        '{toggleData}' .
                                        '{export}'
                                ],
                            ],      
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,          
                            'panel' => [
                                'type' => 'primary', 
                                'heading' => false,
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
