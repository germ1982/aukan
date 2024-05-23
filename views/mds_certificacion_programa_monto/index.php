<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_seg_usuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Programa/Monto';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$usuarioAuth = Yii::$app->user->identity;

$string = "";

$string .= "{create}";
$string .= "{view}";
$string .= "{update}";
$string .= "{print}";
$string .= "{delete}";

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

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-certificacion-programa-monto-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                    // Html::a(
                                    //     '<i class="glyphicon glyphicon-plus"></i>',
                                    //     ['create'],
                                    //     [
                                    //         'role' => 'modal-remote',
                                    //         'title' => 'Nuevo',
                                    //         'class' => 'btn btn-success',
                                    //         'style' => 'margin-right:10px'
                                    //     ]
                                    // ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        [
                                            'data-pjax' => 1,
                                            'class' => 'btn btn-default',
                                            'title' => 'Refrescar Grilla'
                                        ]
                                    ) .
                                        '{toggleData}' .
                                        '{export}'
                                ],
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
$this->registerJs(
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');

    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    })
    "
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
<?php Modal::end();
