<?php

use app\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistroRecepcionlSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personas No Homologadas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
use app\assets\CommonIndexAsset; // Importa tu nuevo Asset Bundle
CommonIndexAsset::register($this);

?>

<style>
    .custom-grid {
        font-size: 12px;
        /* Cambia el tamaño según tus necesidades */
    }

    .kv-grid-toolbar .btn {
        height: 30px;
        /* Ajusta la altura de todos los botones */
        line-height: 1.42857143;
        /* Esto centra el contenido verticalmente */
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

<div class="personas-no-homologadas-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'custom-grid'],
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                ['content' =>
                '<div class="row">' .
                    '<div class="col-md-9"> 
                                        <div class="botones_a">' .
                    Html::a(
                        'Registro Recepcion',
                        ['/registro_recepcion'],
                        ['title' => 'Registro Recepcion', 'class' => 'btn btn-primary neon']
                    ) .
                    '</div>
                                        </div>' .
                    '<div class="col-md-3"> ' .
                    '<div class="botones_b">' .
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        ['create'],
                        ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']
                    ) .



                    Html::a(
                        '<i class="glyphicon glyphicon-repeat"></i>',
                        [''],
                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                    ) .
                    '{toggleData}' .
                    '{export}' .
                    '</div>' .
                    '</div>' .
                    '</div>'],
            ],

            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'panel' => [
                'type' => 'primary',
                'heading' => false,

                /* 'after'=>BulkButtonWidget::widget([
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
                        '<div class="clearfix"></div>', */
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
    'size' => Modal::SIZE_LARGE,
]) ?>
<?php Modal::end(); ?>