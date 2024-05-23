<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_ris_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="sds-ris-persona-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [],
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'panel' => [
                'type' => 'primary',
                'heading' => false,
                'after' => '<div class="clearfix"></div><div style="text-align:right">' . Html::a(
                    '<i class="glyphicon glyphicon-plus"></i> Agregar Persona',
                    [
                        '/sds_ris_persona/create',
                        'idrisneu' => $searchModel->idrisneu,
                        'dni' => $searchModel->documento,
                        'oficial' => $oficial
                    ],
                    [
                        'data-pjax' => 1, 'role' => 'modal-remote',
                        'title' => 'Agregar Integrante de la Familia',
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-info',
                        'id' => 'btn_agregar_persona'
                    ]
                ) . '</div>',
            ]
        ]) ?>
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
<?php Modal::end(); ?>