<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_legales_oficioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
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
                <div class="row placeholders">
                    <div class="col-xs-6 col-sm-4 placeholder">
                        <h4>TOTAL REQUERIMIENTOS</h4>
                        <strong style="font-size: 40px"><span><?php echo count($totalOficios); ?></span></strong>
                    </div>
                    <div class="col-xs-6 col-sm-4 placeholder">
                        <h4 style="color:red">TOTAL REQUERIMIENTOS FUERA DE TERMINO</h4>
                        <strong style="font-size: 40px"><span><?php echo count($oficiosFueraDeTermino); ?></span></strong>
                    </div>
                    <div class="col-xs-6 col-sm-4 placeholder">
                        <h4 style="color:orange">TOTAL REQUERIMIENTOS SIN RESPUESTAS</h4>
                        <strong style="font-size: 40px"><span><?php echo count($totalOficiosSinResponder); ?></span></strong>
                    </div>

                </div>
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-legales-oficio-index table-responsive">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => [
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'numero_expediente',
                                ],
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'caso',
                                ],
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'lugar_libramiento',
                                ],
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'area',
                                ],
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'juicio',
                                ],
                                [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'caratula',
                                ],
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'dropdown' => false,
                                    //'template' => '{view} ' . ($permiso_edicion == 1 ? '{update}' : ''),
                                    'template' => $string,
                                    'vAlign' => 'middle',
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        return Url::to([$action, 'id' => $key]);
                                    },
                                    //'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
                                    //'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
                                    'buttons' =>
                                    [
                                        'responder' => function ($url, $model, $key) {
                                            return Html::a('<i class="glyphicon glyphicon-list-alt"></i>', ['mds_legales_respuesta/create', 'id' => $model['idlegalesoficio']], ['title' => 'Responder', 'class' => 'btn btn-default']);
                                        },
                                        'respuestas' => function ($url, $model, $key) {
                                            return Html::a('<i class="glyphicon glyphicon-comment"></i>', ['mds_legales_oficio/respuestas', 'idOficio' => $model['idlegalesoficio']], ['title' => 'Respuestas', 'class' => 'btn btn-default']);
                                        }, 'misrespuestas' => function ($url, $model, $key) {
                                            return Html::a('<i class="glyphicon glyphicon-envelope"></i>', ['mds_legales_respuesta/mis-respuestas', 'idOficio' => $model['idlegalesoficio']], ['title' => 'Mis Respuestas', 'class' => 'btn btn-default']);
                                        }
                                    ],
                                    'updateOptions' => ['data-pjax' => 0, 'role' => 'post', 'title' => 'Actualizar', 'data-toggle' => 'tooltip'],
                                    'deleteOptions' => [
                                        'role' => 'modal-remote', 'title' => 'Borrar',
                                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                        'data-request-method' => 'post',
                                        'data-toggle' => 'tooltip',
                                        'data-confirm-title' => '¿Está Seguro?',
                                        'data-confirm-message' => '¿Está seguro que desea eliminar este elemento?'
                                    ],
                                ],
                            ],
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