<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Movimientos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>

<?php if (!Yii::$app->request->isAjax) : ?>
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

<?php endif; ?>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-vio-intervencion-movimiento-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => (Yii::$app->request->isAjax) ? true : false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' => (
                                        (!Yii::$app->request->isAjax) ?
                                        (Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                        ) .
                                            '{toggleData} ' . '{export}'
                                        )
                                        : (($estaAtendida || $hasRolAdminGeneral) ? Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i> Agregar',
                                            Url::to(['/sds_vio_intervencion_movimiento/create', 'idintervencion' => $searchModel->idintervencion]),
                                            ['role' => 'modal-remote',  'title' => 'Crear Nuevo Movimiento', 'class' => 'btn btn-success']
                                        ) : "")
                                    )
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' =>
                            [
                                'heading' => (!Yii::$app->request->isAjax) ? '' : false,
                                'type' => 'default',
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>