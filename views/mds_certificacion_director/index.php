<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$string = "";
$string .= "{create}";
$string .= "{update}";
$string .= "{delete}";
$string .= "{reactivate}";
?>

<div class="mds-certificacion-director-index">
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
                        '<i class="glyphicon glyphicon-plus"></i> Agregar ',
                        ['create', 'idcertificaciondireccion' => $searchModel->idcertificaciondireccion],
                        [
                            'role' => 'modal-remote',
                            'title' => 'Asignar un nuevo usuario a la dirección',
                            'class' => 'btn btn-success'
                        ]
                    )
                ],
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