<?php

use app\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm; // Necesario para el formulario de búsqueda

use app\assets\CommonIndexAsset; // Importa tu nuevo Asset Bundle
CommonIndexAsset::register($this);

$this->title = 'Articulos';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'menu-index';

CrudAsset::register($this);
?>




<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="neon fa fa-home"></i>
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
                <div class="<?= $clase ?>">

                    <div class="global-search-container">
                        <?php $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'get',
                            'options' => ['data-pjax' => 0], // Para que la búsqueda no se cargue por AJAX Pjax
                        ]); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($searchModel, 'busquedaGlobal')->textInput([
                                    'placeholder' => 'Buscar en todos los campos...',
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                ])->label(false) ?>
                            </div>
                            <div class="col-md-2">
                                <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Buscar', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>




                        <?php ActiveForm::end(); ?>
                    </div>



                    <div id="ajaxCrudDatatable">

                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'tableOptions' => ['class' => 'custom-grid'],
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                    '<div class="row">' .
                                        '<div class="col-md-9"> 
                                        <div class="botones_a">' .

                                        '</div>
                                    </div>' .
                                        '<div class="col-md-3"> 
                                        <div class="botones_b">' .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            ['create'],
                                            ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                        ) .
                                        '{toggleData}' .
                                        '{export}' .
                                        '</div>
                                    </div>' .
                                        '</div>'
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
                        ]); ?>

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
<?php Modal::end(); ?>