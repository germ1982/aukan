<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Url;

$title_for_new = 'Nuevo Ingreso';

$this->title = 'Ingresos Externos';

$this->params['breadcrumbs'][] = $this->title;
$clase = 'mds_hor_ingreso_externo-index';

CrudAsset::register($this);

?>
<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
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
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="<?= $clase ?>">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'rowOptions' => function ($model, $key, $index, $column) {
                                $aux =  ['myurl' => $model->idingresoexterno, 'style' => 'background-color: #C4FFD6; color: #000; cursor: pointer'];



                                if ($model->estado) {

                                    if ($model->estado == 'Aceptado') {
                                        $aux =  ['myurl' => $model->idingresoexterno, 'style' => 'background-color: #96F952; color: #000;'];
                                    } else {
                                        if ($model->estado == 'Rechazado') {
                                            $aux =  ['myurl' => $model->idingresoexterno, 'style' => 'background-color: #FFC4C4; color: #000;'];
                                        } else {

                                            
                                            $aux =  ['myurl' => $model->idingresoexterno, 'style' => 'background-color: #F9E252; color: #000;'];
                                            if($model->idorganismo == null){
                                                $aux =  ['myurl' => $model->idingresoexterno, 'style' => 'background-color: yellow; color: #000;'];
                                            }
                                        }
                                    }

                                    
                                }

                                
                                return $aux;
                            },
                            'toolbar' => [
                                [
                                    'content' =>
                                        Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            ['create'],
                                            ['role' => 'modal-remote', 'title' => $title_for_new, 'class' => 'btn btn-default']
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                        ) .
                                        '{export}',
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
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>