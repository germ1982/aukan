<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

CrudAsset::register($this);
$this->title = "Vincular una nueva persona al requerimiento";
$this->params['breadcrumbs'][] = $this->title;

$stringButtonsIndex = '';
$botonAgregar = '';

if ($permissions['hasRolAdminGeneral'] || $permissions['permissionCreate']) {
    $botonAgregar =   Html::a(
        '<i class="glyphicon glyphicon-plus"></i> Agregar',
        Url::to(['/mds_legales_oficio_vinculado/create', 'idlegalesoficio' => $searchModel->idlegalesoficio]),
        ['role' => 'modal-remote', 'title' => 'Agregar persona', 'class' => 'btn btn-success']
    );
}

if ($permissions['hasRolAdminGeneral'] || $permissions['permissionUpdate']) {
    $stringButtonsIndex = "{update}";
}

if ($permissions['hasRolAdminGeneral'] || $permissions['permissionDelete']) {
    $stringButtonsIndex .= "  {delete}";
}

if ($permissions['hasRolAdminGeneral']) {
    $stringButtonsIndex .= " {reactivate}";
}

if ($permissions['hasRolAdminGeneral'] || $permissions['permissionRead']) {
    $stringButtonsIndex .= " {view}";
}
?>
<div class="sds-com-persona-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'content' => $botonAgregar
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'after' => '<div class="clearfix"></div>',
            ],
        ]) ?>
    </div>
</div>