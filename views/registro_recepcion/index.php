<?php

use app\assets\AppAsset;
use app\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Registro Recepcion';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'empleado-index';

CrudAsset::register($this);

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
                            'tableOptions' => ['class' => 'custom-grid'],
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                '<div class="row">' .
                                    '<div class="col-md-9"> 

                                        </div>' .
                                    '<div class="col-md-3"> ' .
                                    '<div class="botones_b">' .
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
                                    '</div>' .
                                    '</div>' .
                                    '</div>'],
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

<?php
$this->registerJs(
    "
        // Cuando se cierra el modal, recarga la página para actualizar la grilla
        $('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        });

        // Cuando se muestra el modal
        $('#ajaxCrudModal').on('shown.bs.modal', function() {
            var modal = $(this); // referencia al modal
            var content = modal.find('.modal-content'); // obtiene el contenido principal del modal

            // Permite que elementos como Select2 funcionen correctamente
            modal.css('overflow', 'visible');

            // Define el alto máximo del modal y configura su layout como columna
            content.css({
                'max-height': 'calc(100vh - 60px)', // limita el alto al 100% de la ventana menos 60px
                'display': 'flex', // organiza hijos en columna
                'flex-direction': 'column' // fuerza el orden de arriba hacia abajo (header > body > footer)
            });

            var body = content.find('.modal-body'); // obtiene el cuerpo del modal
            var footer = content.find('.modal-footer'); // obtiene el pie del modal

            // Verifica que no se haya creado ya el wrapper (evita duplicación)
            if (content.find('.modal-body-footer-wrapper').length === 0 && body.length && footer.length) {
                var wrapper = $('<div class=\"modal-body-footer-wrapper\"></div>'); // crea un contenedor envolvente
                body.after(wrapper); // inserta el wrapper después del body
                wrapper.append(body).append(footer); // mete el body y el footer dentro del wrapper para que compartan el scroll
            }
        });
        ",
    \yii\web\View::POS_READY // indica que el script se ejecute cuando el DOM esté listo
);
?>

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