<?php

use app\models\Sds_com_persona;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_com_personaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

CrudAsset::register($this);
$model_padre = Sds_com_persona::findOne($searchModel->padre);
$this->title = ($model_padre->nombre . ' ' . $model_padre->apellido) . ' - Listado de Hijos ';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sds-com-persona-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns_hijos.php'),
            'toolbar' => [
                ['content' => ($estaAtendida || $hasRolAdminGeneral) ?
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i> Agregar Hijo',
                        ['create', 'idpadre' => $searchModel->padre],
                        ['role' => 'modal-remote', 'title' => 'Asignar un nuevo hijo a la persona', 'class' => 'btn btn-success']
                    ) : ""],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
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
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>