<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Mds_rum_persona;
use app\models\Sds_com_persona;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_rum_observacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$una_persona = Mds_rum_persona::findOne($id_usuario);//$id_usuario de seg_usuario
$una_com_persona = Sds_com_persona::findOne($una_persona->id_com_persona);

//$nombre_completo=ucfirst(strtolower($una_com_persona->nombre)).' '.ucfirst(strtolower($una_com_persona->apellido));
$nombre_completo=$una_com_persona->nombre.' '.$una_com_persona->apellido;

$this->title = 'Observaciones para CVs';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<header class="page-header">
    <h2><?php  echo 'Rumbo:: Observaciones de '.$nombre_completo.', DNI: '.$una_com_persona->documento; ?></h2>
    
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

<div class="mds-rum-observacion-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create', 'id_cv' => $id_usuario],
                    ['role'=>'modal-remote','title'=> 'Create new Mds Rum Observacions','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
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
                'before'=>'',
                'after'=>'<div class="clearfix"></div>',
            ]
        ])?>
    </div>
    <?php $link_anterior=$_SERVER["HTTP_REFERER"];
    $link="index.php?r=mds_rum_persona";
    
    ?>
<a class="btn btn-primary" href="<?= $link;?>">Volver a listado de CVs</a>
</div>
</div>
</section>
</div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [  // esto es diferente
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

