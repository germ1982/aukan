<?php

use app\models\Sds_reg_registro;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_reg_tipoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
switch($searchModel->entidad){
    case Sds_reg_registro::ENT_INFORMATICA:
        $this->title='Tipos de Registros Técnicos';
        break;
    case Sds_reg_registro::ENT_MANTENIMIENTO:
        $this->title='Tipos de Registros de Mantenimiento';
        break;
    case Sds_reg_registro::ENT_RUMBO:
        $this->title='Tipos de Registros Soporte Rumbo';
        break;
}

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

                <div class="sds-reg-tipo-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create', 'entidad'=>$searchModel->entidad],
                                        ['role' => 'modal-remote', 'title' => 'Nuevo Tipo', 'class' => 'btn btn-default']
                                    ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                                        ) .
                                        '{toggleData}' .
                                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'primary',
                                'heading' => false,
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
]) ?>
<?php Modal::end(); ?>
