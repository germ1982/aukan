<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_ris_risneuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planillas de RISNeu ' . ($searchModel->oficial != null ? ($searchModel->oficial == 1 ? "Oficiales" : "No Oficiales") : '');
$this->params['breadcrumbs'][] = $this->title;
$botonManual = Html::button('Manual de Usuario', ['id' => 'boton-manual', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);

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
                <?= $this->render('components/flash_messages') ?>
                <div class="sds-ris-risneu-index table-responsive">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),

                            'toolbar' => [
                                ['content' => (($permissionRisneuCreate) ? Html::a(
                                    '<i class="glyphicon glyphicon-plus"></i>',
                                    ['create', 'oficial' => $searchModel->oficial],
                                    [
                                        'data-pjax' => 0,
                                        'role' => 'post',
                                        'title' => 'Nuevo RISNeu',
                                        'class' => 'btn btn-success',
                                        'style' => 'margin-right: 10px',
                                    ]
                                ) : "") .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                    ) .
                                    '{toggleData}' .
                                    '{export}'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'default',
                                'heading' => "",
                                'after' => '<div class="clearfix"></div>',
                                'before' => $botonManual
                            ]
                        ]) ?>
                    </div>
                </div>
                <?php Modal::begin([
                    "id" => "ajaxCrudModal",
                    'options' => [
                        'tabindex' => false // important for Select2 to work properly
                    ],
                    "footer" => "", // always need it for jquery plugin
                ]) ?>
                <?php Modal::end(); ?>
            </div>
        </section>
    </div>
</div>

<?php

$this->registerJs(
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');

    $('#boton-manual').click(function() {
        $.ajax({
                type: 'POST',
                url: '" . Url::to(['/sds_ris_risneu/guardarlogmanualusuario']) . "', 
                data: { },

                success: function (success) {
                    if (success) {
                        window.open('" . Url::base() . "/instructivos/instructivo_risneu.pdf', '_blank');
                    } else {
                        console.log(errormessage);
                        alert('Ocurrió un error');
                    }
                },
                error: function (errormessage) {
                    console.log(errormessage);
                    alert('Ocurrió un error');
                }
            });
    })
"
);
