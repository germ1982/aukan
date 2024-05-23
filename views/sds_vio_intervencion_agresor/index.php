<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_vio_intervencion_agresorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sds Vio Intervencion Agresors';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="sds-vio-intervencion-agresor-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $model->agresores,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'content' =>
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i> Agregar',
                        Url::to(['/sds_vio_agresor/create', 'idintervencion' => $model->idintervencion]),
                        ['role' => 'modal-remote', 'title' => 'Crear Nuevo Agresor', 'class' => 'btn btn-success']
                    ) 
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