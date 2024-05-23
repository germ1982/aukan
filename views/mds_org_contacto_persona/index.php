<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_org_contacto_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Datos Contacto/Persona';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<style>
    .content-body{
        padding-top:10px !important;
    }
    .panel-body{
        padding-top:0px !important;
    }
    .kv-panel-before{
        padding-top:5px !important;
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
                <a href="index.php?r=mds_org_contacto">
                    Contactos
                </a>
            </li>
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-org-contacto-persona-index">
                    <div id="ajaxCrudDatatable">
                        <?=GridView::widget([
                            'id'=>'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax'=>true,
                            'columns' => require(__DIR__.'/_columns.php'),
                            'toolbar'=> [
                                ['content'=>
                                /*
                                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                                    ['role'=>'modal-remote','title'=> 'Create new Mds Org Contacto Personas','class'=>'btn btn-default']).
                                */
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
                                //'type' => 'primary', 
                                'heading' => false,
                                'before'=>'',
                                'after'=>''/*BulkButtonWidget::widget([
                                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                                ["bulk-delete"] ,
                                                [
                                                    "class"=>"btn btn-danger btn-xs",
                                                    'role'=>'modal-remote-bulk',
                                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                                    'data-request-method'=>'post',
                                                    'data-confirm-title'=>'Are you sure?',
                                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                                ]),
                                        ]).                        
                                        '<div class="clearfix"></div>',*/
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
