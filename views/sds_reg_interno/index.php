<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_reg_internoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Internos Telefónicos';
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
<div class="sds-reg-interno-index" style="background-color: #f5f5f5;">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Interno', ['create'],
                    ['role'=>'modal-remote','title'=> 'Crear Nuevo Interno','class'=>'btn btn-default', 'style'=>'margin-right:15px']).
                    
                    Html::a('<i class="glyphicon glyphicon-ok-circle"></i> Reporte de Recepcion', ['edificio_reporte', 'type'=>'recepcion'],
                    ['role'=>'modal-remote','title'=> 'Crear Nuevo Interno','class'=>'btn btn-success']).

                    Html::a('<i class="glyphicon glyphicon-list"></i> Reporte Completo', ['edificio_reporte', 'type'=>'completo'],
                    ['role'=>'modal-remote','title'=> 'Crear Nuevo Interno','class'=>'btn btn-primary'])
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'default',
                'heading' => false,
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>