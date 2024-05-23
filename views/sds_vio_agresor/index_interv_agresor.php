<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_vio_intervencion_agresorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sds Vio Intervencion Agresors';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$this->registerJsFile('https://cdn.quilljs.com/1.3.7/quill.min.js');

?>
<div class="sds-vio-intervencion-agresor-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $model->agresores,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns_interv_agresor.php'),
            'toolbar' => [
                [
                    'content' =>
                    $estaAtendida || $hasRolAdminGeneral ?
                        Html::a(
                            '<i class="glyphicon glyphicon-plus"></i> Agregar',
                            Url::to(['/sds_vio_agresor/create', 'idintervencion' => $model->idintervencion]),
                            ['role' => 'modal-remote', 'title' => 'Crear Nuevo Agresor', 'class' => 'btn btn-success']
                        ) : ""
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'after' => '<div class="clearfix"></div>',
            ]
        ]) ?>
    </div>
</div>