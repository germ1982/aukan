<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_org_contactoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documentos'.($searchModel->medicina==0 ? '':' de Medicina Laboral');
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
        <section class="panel">
            <div class="panel-body">
                <div class="mds-org-contacto-index">
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
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create','medicina'=>$searchModel->medicina],
                                        ['role' => 'modal-remote', 'title' => 'Nuevo Documento', 'class' => 'btn btn-default']
                                    ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            ['', 'medicina'=>($searchModel->medicina==0 ? '0':'1')],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
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
                                'after' => false,
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
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
<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
    if(!$(\"#modal_abm\").hasClass('fade modal in'))
        location.reload();
})"
);
?>