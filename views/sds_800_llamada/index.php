<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use app\models\Sds_800_llamada;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_800_llamadaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$area = '';
switch ($searchModel->area) {
    case Sds_800_llamada::AREA_SITUACIONDECALLE:
        $area = 'Situación de Calle';
        break;
    case Sds_800_llamada::AREA_FAMILIA:
        $area = 'Familia';
        break;
    case Sds_800_llamada::AREA_ADULTOSMAYORES:
        $area = 'Adultos Mayores';
        break;
    case Sds_800_llamada::AREA_INTERIOR:
        $area = 'Interior';
        break;
    case Sds_800_llamada::AREA_VIOLENCIA:
        $area = 'Violencia';
        break;
    default;
        echo 'Por favor indique un área';
        break;
}

$this->title = 'Ingresos de ' . $area;
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-800-llamada-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                Html::a(
                                    '<i class="glyphicon glyphicon-plus"></i>',
                                    ['create', 'area' => $searchModel->area],
                                    ['role' => 'post', 'data-pjax' => 0, 'title' => 'Nuevo Ingreso', 'class' => 'btn btn-default']
                                ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                    ) .
                                    '{toggleData} ' .
                                    '{export}'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => 'default',
                                'heading' => '',
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
$this->registerJsFile('https://cdn.quilljs.com/1.3.7/quill.min.js');
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
);
?>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>