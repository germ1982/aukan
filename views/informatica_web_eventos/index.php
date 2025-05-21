<?php

use app\assets\AppAsset;
use app\models\Persona;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;


$this->title = 'Web Informatica - Eventos';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'informatica-web-eventos-index';

CrudAsset::register($this);
use app\assets\CommonIndexAsset; // Importa tu nuevo Asset Bundle
CommonIndexAsset::register($this);

// --- INCLUYE TU ARCHIVO CSS EXTERNO DIRECTAMENTE DESDE AQUÍ ---
// '@web' se resuelve a la ruta base de tu aplicación web (web/)
$this->registerCssFile('@web/css/css_index_views.css', [
    // Opcional pero recomendado: Asegura que se cargue después de AppAsset
    // para que tus estilos puedan sobrescribir los de Bootstrap o site.css
    'depends' => [AppAsset::class],
]);
// --- FIN DE LA INCLUSIÓN CSS ---

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
                    <div id="ajaxCrudDatatable">

                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'custom-grid'],
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' =>
                                    '<div class="row">' .
                                        '<div class="col-md-9"> 
                                    <div class="botones_a">' .
                                        Html::a(
                                            'Sectores',
                                            ['/informatica_web_sectores'],
                                            ['title' => 'Sectores', 'class' => 'btn btn-primary boton_menu neon']
                                        ) .
                                        Html::a(
                                            'Staff',
                                            ['/informatica_web_empleados'],
                                            ['title' => 'Staff', 'class' => 'btn btn-primary boton_menu neon']
                                        ) .
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