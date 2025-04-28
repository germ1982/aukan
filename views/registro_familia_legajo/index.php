<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

$this->title = 'Legajos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .custom-grid {
        font-size: 13px !important;
        /* Cambia el tamaño según tus necesidades */
    }

    .kv-grid-toolbar .btn {
        height: 30px;
        /* Ajusta la altura de todos los botones */
        line-height: 1.42857143;
        /* Esto centra el contenido verticalmente */
    }

    #ajaxCrudModal .modal-dialog {
        width: 90vw;
        /* 90% del ancho de la ventana */
        max-width: 90vw;
        /* Asegura que no exceda el 90% */
        margin: auto;
        /* Centra el modal horizontalmente */
        padding-top: 20px;
        padding-left: 30px;
    }

    #ajaxCrudModal .modal-content {
        /*height: 90%; /* Ajusta la altura según el contenido */
        max-height: auto;
        /* Opcional: limita la altura al 90% de la pantalla */
    }
</style>

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


<div class="registro-familia-legajo-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'tableOptions' => ['class' => 'custom-grid'],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'content' =>

                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        ['create'],
                        ['role' => 'modal-remote', 'title' => 'Nuevo Legajo', 'class' => 'btn btn-default']
                    ) .
                        Html::a(
                            '<i class="glyphicon glyphicon-repeat"></i>',
                            [''],
                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                        ) .
                        Html::button('Exportar Filtrado', [
                            'class' => 'btn btn-default',
                            'id' => 'exportar-pdf-button',
                        ]) .
                        '{toggleData}' .
                        '{export}'.
                        Html::a(
                            'Manual',
                            Url::to('descargables/manuales/manual_de_carga_de_legajos_en_datafam.pdf'),
                            [
                                'data-pjax' => 0,
                                'data-toggle' => 'tooltip',
                                'title' => 'Ver Manual de Uso',
                                'class' => 'btn btn-default',
                                'target' => '_blank'
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


            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
    'size' => Modal::SIZE_LARGE,
]) ?>
<?php Modal::end(); ?>


<?php
$script = <<<JS

$('#ajaxCrudModal').on('hidden.bs.modal', function () {
    $.pjax.reload({container: '#crud-datatable-pjax'});
});


$(document).on('click', '#exportar-pdf-button', function () {
    let ids = [];

    $('#crud-datatable tbody tr').each(function() {
        let id = $(this).data('key');
        if (id) {
            ids.push(id);
        }
    });

    $.ajax({
        url: 'index.php?r=registro_familia_legajo/exportar_pdf',
        type: 'POST',
        data: { ids: ids },
        success: function(response, status, xhr) {
            // Crear un Blob a partir de la respuesta
            let blob = new Blob([response], { type: 'application/pdf' });

            // Crear una URL para el Blob
            let url = window.URL.createObjectURL(blob);

            // Crear un enlace temporal
            let a = document.createElement('a');
            a.href = url;
            a.target = '_blank'; // Abrir en una nueva pestaña

            // Simular un clic en el enlace para abrir el PDF
            a.click();
        },
        error: function(xhr, status, error) {
            console.error('Error al generar el PDF:', error);
        },
        xhrFields: {
            responseType: 'blob' // Indicar que la respuesta es un Blob
        }
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_READY);
?>

