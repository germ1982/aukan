<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_ent_entrega;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;


/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_ent_entregaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->estado == Sds_ent_entrega::ESTADO_FINAL ?
    'Entregas Finales'
    : ($searchModel->estado == Sds_ent_entrega::ESTADO_INTERMEDIA ?
        'Entregas Intermedias'
        : ($searchModel->estado == Sds_ent_entrega::ESTADO_DEUDOR ? 'Deudores de Entregas'
            : 'Entregas Iniciales'));

$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$permiso_todas = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=" . $usuario->idusuario . ")
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_VER_TODAS . ")")->one();
$visor_global = $permiso_todas != null;
$entregas = Sds_ent_entrega::find()->where("dni_frente is not null
                                        and dni_frente like '%base64%'")->limit(50)->all();
$mostrar_migrar = $idusuario == 1 && $searchModel->estado > -1
    && !empty($entregas);

if (isset($_GET['error'])) {
    echo '<script>alert("' . $_GET['error'] . '");window.close();</script>';
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

<!-- start: page -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <?= Html::beginForm('', 'post', ['target' => '_blank']); ?>
            <div class="panel-body">
                <div class="sds-ent-entrega-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ .
                                ($searchModel->estado == Sds_ent_entrega::ESTADO_FINAL ? '/_columns.php'
                                    : ($searchModel->estado == Sds_ent_entrega::ESTADO_DEUDOR ? '/_columns_deudor.php' : '/_columns_interm.php'))),
                            'toolbar' => [
                                [
                                    'content' => ($mostrar_migrar ? Html::a(
                                        '<i class="fas fa-sync-alt"></i> Migrar DNIs',
                                        null,
                                        ['title' => 'Migrar imágenes de DNI', 'class' => 'btn btn-default', 'id' => 'btnMigrar']
                                    ) : "") .
                                        ($searchModel->estado == Sds_ent_entrega::ESTADO_INTERMEDIA ? Html::a(
                                            '<i class="fas fa-print"></i> Imprimir Remito',
                                            Url::to(['reporte_entrega_remito']),
                                            ['role' => 'post', 'data' => ['method' => 'post'], 'target' => '_blank', 'title' => 'Remito Entregas', 'class' => 'btn btn-default']
                                        ) : "") .
                                        ($searchModel->estado == Sds_ent_entrega::ESTADO_INTERMEDIA
                                            || $searchModel->estado == Sds_ent_entrega::ESTADO_DEUDOR ? "" : Html::a(
                                                '<i class="glyphicon glyphicon-plus"></i>',
                                                Url::to($searchModel->estado == Sds_ent_entrega::ESTADO_FINAL ? ['create']
                                                    : ['create_interm', 'estado' => $searchModel->estado]),
                                                ['role' => 'post', 'title' => 'Nueva Entrega Final', 'class' => 'btn btn-default']
                                            )) .
                                        /* Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            [
                                                'data-pjax' => 1, 'class' => 'btn btn-default',
                                                'title' => 'Refrescar Grilla'
                                            ]
                                        ) . */
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
            <?= Html::endForm(); ?>
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
Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "size" => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end();
$script = <<<  JS
$('#btnMigrar').click(function() {
        $("#btnMigrar").html("<i class=\"fas fa-spinner fa-pulse\"></i>");
        $("#btnMigrar").prop("disabled", true);
        $.post("index.php?r=sds_ent_entrega/migrar_dni", function(data) {
            console.log(data);
            $("#btnMigrar").html("<i class=\"fas fa-sync-alt\"></i> Migrar DNIs");
            $("#btnMigrar").prop("disabled", false);
            if (data.length>0){
                $("#btnMigrar").show();
            }
            else {
                $("#btnMigrar").hide();
            }
        });
    });
JS;

$this->registerJs($script); ?>