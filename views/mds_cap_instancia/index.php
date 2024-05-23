<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_instanciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Instancias';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$permisos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
    idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario) order by iditem,alta,baja")->all();

$permiso_global = 0;
$permiso_alta = 0;
$permiso_edicion = 0;
foreach ($permisos as $permiso) {
    switch ($permiso->iditem) {
        case Mds_seg_item::MODULO_CAP_GLOBAL:
            $permiso_global = 1;
            break;
        case Mds_seg_item::MODULO_CAP_CAPACITACION:
            $permiso_alta = $permiso->alta;
            $permiso_edicion = $permiso->modifica;
            $permiso_baja = $permiso->baja;
            break;
    }
}

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
                <div class="sds-vio-intervencion-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' => ($permiso_alta == 1 ? Html::a(
                                    '<i class="glyphicon glyphicon-plus"></i>',
                                    ['create'],
                                    ['role' => 'post', 'title' => 'Nueva intervencion', 'class' => 'btn btn-default']
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
