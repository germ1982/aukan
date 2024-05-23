<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_org_contactoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contactos';
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
$idcontacto  = $usuario->idcontacto;

$permiso_contactos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_ORG_CONTACTOS . ") 
                                                order by alta desc, baja desc, modifica desc, ver desc")->one();

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
        color: #CCC;
        background-color: #FCFCFC !important;
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
                <div class="mds-org-contacto-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' => ($permiso_contactos->alta ? Html::a(
                                        '<i class="glyphicon glyphicon-export"></i> Listar y exportar a Excel',
                                        [''],
                                        ['id'=>'report_excel', 'title' => 'Listar y exportar a Excel', 'class' => 'btn btn-info']
                                    ).
                                    Html::a(
                                        '<i class="glyphicon glyphicon-save"></i> Remanente',
                                        ['mds_hor_remanente/importar'],
                                        ['role' => 'modal-remote', 'title' => 'Importar Excel de Remanente', 'class' => 'btn btn-default']
                                    ).
                                    Html::a(
                                        '<i class="glyphicon glyphicon-save"></i> Pase a Planta',
                                        ['pase_planta'],
                                        ['role' => 'modal-remote', 'title' => 'Importar Excel de Pase a Planta', 'class' => 'btn btn-default']
                                    ).
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create'],
                                        ['role' => 'modal-remote', 'title' => 'Nuevo Contacto', 'class' => 'btn btn-default']
                                    ) : "") .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
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
                                'heading' => ' ',
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
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); 

$script = <<<  JS
$(document).ready(function() {
    $('#report_excel').click(function(){
        window.location.replace("index.php?r=mds_org_contacto_persona");
    });
});
JS;
$this->registerJs($script);
?>
