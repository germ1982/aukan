<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cor_intervencionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Intervenciones';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

//este header es el de color negro
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
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-cor-intervencion-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false, //ANOTEZE: ACA SACO EL PJAX, porque sino lo de fecha no anda, si, es una poronga del componente este...
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                Html::a(
                                    '<i class="glyphicon glyphicon-plus"></i>',
                                    ['create'],
                                    ['role' => 'post', 'data-pjax' => 0, 'title' => 'Nueva Intervención', 'class' => 'btn btn-success', 'style' => 'margin-right:10px']
                                ) .
                                    '{toggleData}' .
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
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
    //ANOTEZE: acá agrego el tamaño grande para el modal, y además el bloqueo que se salga del modal al hacer click afuera, lo pueden sacar si quieren, es a gusto.
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
])
?>
<?php Modal::end(); ?>

<?php
//ESTE EVENTO ESTAVA ENTRE EL Modal::begin y  Modal::end, o sea seguramente no se estaba tomando correctamente. Asi que lo saco afuera 
//de la creación del modal
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
); ?>