<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_seg_usuario_capa_itemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mds Seg Usuario Capa Items';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="mds-seg-usuario-capa-item-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'content' =>
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i> Asignar Edificio',
                        ['create', 'idusuario' => $searchModel->idusuario],
                        ['role' => 'modal-remote', 'title' => 'Asignar Edificio', 'class' => 'btn btn-success']
                    )
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'after' => '<div class="clearfix"></div>',
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>