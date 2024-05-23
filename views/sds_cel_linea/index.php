<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_cel_lineaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Líneas Corporativas';
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);
?>
<style>
    .content-body{
        padding: 15px;
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

<!-- start: page -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="sds-cel-linea-index">
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
                                            '<i class="glyphicon glyphicon-phone"></i> Nueva Linea',
                                            ['create'],
                                            ['role' => 'modal-remote', 'data-pjax' => 0, 'title' => 'Nueva Linea', 'class' => 'btn btn-success']
                                        ) .
                                        Html::a(
                                            '<i class="fas fa-desktop"></i> Nuevo Equipo',
                                            ['sds_bdc_equipo/create'],
                                            ['data-pjax' => 0, 'title' => 'Nuevo Equipo', 'class' => 'btn btn-primary', 'target'=>'_blank']
                                        ) .
                                        Html::a(
                                            '<i class="fas fa-exchange-alt"></i> Movimiento de Linea',
                                            ['sds_cel_movimiento_linea/create'],
                                            ['role' => 'modal-remote', 'data-pjax' => 0, 'title' => 'Cargar Movimiento Linea', 'class' => 'btn btn-info']
                                        ) .
                                        Html::a(
                                            '<i class="fas fa-print"></i> Imprimir Listado',
                                            Url::to(['reporte_corpo', 
                                                //'fdesde' => ($searchModel->fdesde !=null ? $searchModel->fdesde:''),
                                                //'fhasta' => ($searchModel->fhasta !=null ? $searchModel->fhasta:''), 
                                                'numero' => ($searchModel->numero!=null ? $searchModel->numero:''),
                                                'organismo_padre' => ($searchModel->organismo_padre !=null ? $searchModel->organismo_padre:''),
                                                'idplan' => ($searchModel->idplan!=null ? $searchModel->idplan:''),
                                                'idcontacto' => ($searchModel->idcontacto!=null ? $searchModel->idcontacto:''),
    
                                                'equipo_tipo' => ($searchModel->equipo_tipo!=null ? $searchModel->equipo_tipo:''), 
                                                'estado' => ($searchModel->estado!=null ? $searchModel->estado:''),
                                                'idorganismo' => ($searchModel->idorganismo!=null ? $searchModel->idorganismo:''),
                                                'iddispositivo' => ($searchModel->iddispositivo !=null ? $searchModel->iddispositivo:''),
                                                'idusuario' => ($searchModel->idusuario !=null ? $searchModel->idusuario:''),
                                                'observaciones' => ($searchModel->observaciones !=null ? $searchModel->observaciones:''),
                                                'activo' => ($searchModel->activo !=null ? $searchModel->activo:'')
                                            ]),
                                            [
                                                'role' => 'post', 'data' => ['method' => 'post'],
                                                'target' => '_blank', 'title' => 'Listado de Corporativos', 'class' => 'btn btn-default'
                                            ]
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar']
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
<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
    if(!$(\"#modal_abm\").hasClass('fade modal in'))
        location.reload();
})"
);
?>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ]
]) ?>
<?php Modal::end(); ?>