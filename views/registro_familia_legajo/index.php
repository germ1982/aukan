<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RunneuLegajoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Legajos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .custom-grid {
    font-size: 13px!important; /* Cambia el tamaño según tus necesidades */
}

.kv-grid-toolbar .btn {
    height: 30px;  /* Ajusta la altura de todos los botones */
    line-height: 1.42857143;  /* Esto centra el contenido verticalmente */
}

#ajaxCrudModal .modal-dialog {
    width: 90vw; /* 90% del ancho de la ventana */
    max-width: 90vw; /* Asegura que no exceda el 90% */
    margin: auto; /* Centra el modal horizontalmente */
    padding-top: 20px;
    padding-left: 30px;
}

#ajaxCrudModal .modal-content {
    /*height: 90%; /* Ajusta la altura según el contenido */
    max-height: auto; /* Opcional: limita la altura al 90% de la pantalla */
}
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>


<div class="registro-familia-legajo-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'tableOptions' => ['class' => 'custom-grid'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Nuevo Legajo','class'=>'btn btn-default']).
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
                'heading' =>false,
                
                
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    'size' => Modal::SIZE_LARGE,
])?>
<?php Modal::end(); ?>
