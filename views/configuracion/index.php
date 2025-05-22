<?php

use app\assets\AppAsset;
use app\models\Persona;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
CrudAsset::register($this);

use app\assets\CommonIndexAsset; // Importa tu nuevo Asset Bundle
CommonIndexAsset::register($this);

$this->title = 'Datos';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'configuracion-index';




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
                    <div id="ajaxCrudDatatable_egresos">

                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'custom-grid'],
                            'filterModel' => $searchModel,
                            'pjax' => true,
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
                                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']) .
                                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']) .
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
        'class' => 'custom-modal', // Clase personalizada para el modal
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE, // Punto de partida para el tamaño
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
// ... tu código index.php ...

$this->registerJs(
    <<<JS
    // Función para manejar el Enter en los inputs del formulario del modal
    $(document).on('keydown', '#ajaxCrudModal form input:not(textarea)', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault(); // Previene el envío por defecto del formulario

            // Simula el clic en el botón 'Guardar' del modal
            // Asumiendo que tu botón guardar tiene el ID 'btnGuardar'
            $('#btnGuardar').click();
            return false; // Evita cualquier otra acción predeterminada
        }
    });

    // Tu script para recargar la página al cerrar el modal (si aún lo usas)
    // Se recomienda quitarlo si usas forceReload de ajaxcrud
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        // Si usas Pjax y forceReload en el controlador, puedes comentar o eliminar la siguiente línea
        // location.reload();
    });
JS
);
?>