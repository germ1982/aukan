<?php
// C:\xampp_datafam\htdocs\datafam\views\edificio_conectividad\view_indicadores_v2_grupo.php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\Modal; // <-- Importante heredar el widget de Bootstrap

// Traemos el archivo central de consultas
require_once __DIR__ . '/view_indicadores_v2_consultas.php';

// Desactivamos el layout para que corra aislado y limpio
$this->context->layout = false;

// Capturamos los parámetros de la URL usando el nuevo criterio
$grupoSeleccionado = Yii::$app->request->get('grupo', 'No definido');
$todosLosGrupos   = Yii::$app->request->get('grupos', []); // <-- Leemos 'grupos'

// Ejecutamos tu consulta nativa pasándole el listado para que calcule el RESTO si hace falta
$edificios = get_desglose_grupo($grupoSeleccionado, $todosLosGrupos);
// ACTIVAMOS LOS ASSETS IDÉNTICOS AL HELPER GENERAL
/* \johnitvn\ajaxcrud\CrudAsset::register($this); */
\app\assets\CommonIndexAsset::register($this);
$this->registerCssFile('@web/css/css_index_views.css', ['depends' => [\app\assets\AppAsset::class]]);

// Inyectamos el CSS personalizado del helper para mantener el mismo ancho del modal (600px)
$this->registerCss("
    .modal-lg { 
        max-width: 600px !important; 
        width: 600px !important; 
        margin: 1.75rem auto; 
    }
");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Grupo: <?= htmlspecialchars($grupoSeleccionado) ?> - DATAFAM</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        <?= file_get_contents(__DIR__ . '/view_indicadores_v2.css') ?>
    </style>

    <script>
        <?= file_get_contents(__DIR__ . '/view_indicadores_v2.js') ?>
    </script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="dashboard-header">
                <h2 class="dashboard-title">Centro de Monitoreo de Conectividad / Grupo: <span><?= htmlspecialchars($grupoSeleccionado) ?></span></h2>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($edificios)): ?>
            <?php foreach ($edificios as $index => $edificio): ?>
                <?= $this->render('view_indicadores_v2_grupo_tarjeta', [
                    'edificio'    => $edificio,
                    'index'       => $index,
                    // Usamos el color de acento correspondiente al grupo actual
                    'accentColor' => 'var(--accent-' . strtolower($grupoSeleccionado) . ', #3b82f6)'
                ]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-xs-12">
                <div class="react-card" style="text-align: center; padding: 40px;">
                    <p style="color: var(--text-muted); font-size: 16px; margin: 0;">
                        No se encontraron registros activos para este grupo.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
\yii\bootstrap\BootstrapPluginAsset::register($this); 
// RENDERIZAMOS EL MODAL FANTASMA DONDE SE INYECTAN LAS VISTAS AJAX
echo Modal::widget([
    'id' => 'ajaxCrudModal',
    'header' => '<h5 class="modal-title" style="color: black"></h5>',
    'options' => [
        'tabindex' => false,
        'style' => "margin-left:auto !important; margin-right:auto !important;"
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => ['backdrop' => 'static'],
    'footer' => '',
]);
?>


<script>
function abrirModalDashboard(event, url) {
    console.log('modal=', typeof $.fn.modal);
console.log('jquery=', $.fn.jquery);
    event.preventDefault(); // Frena la recarga de página

    var $modal = $('#ajaxCrudModal');

    // Hacemos la petición AJAX manual para capturar el JSON del controlador
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json', // Le avisamos a jQuery que va a recibir el JSON de Yii2
        success: function(response) {
            // Si el controlador devolvió el formato de Krajee ({title, content, footer})
            if (response && response.content) {
                $modal.find('.modal-title').html(response.title || 'Detalle');
                $modal.find('.modal-body').html(response.content);
                
                // Si el controlador manda botones para el footer, los ponemos, sino un botón de cerrar por defecto
                if (response.footer) {
                    $modal.find('.modal-footer').html(response.footer);
                } else {
                    $modal.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
                }
                console.log('antes de abrir', typeof $.fn.modal);
                // Levantamos el modal nativamente
                $modal.modal('show');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Plan B: Si falla o si devolvió HTML puro, intentamos cargarlo directo
            $modal.find('.modal-body').load(url, function() {
                $modal.modal('show');
            });
        }
    });
}

$('#ajaxCrudModal').on('hidden.bs.modal', function () {
    location.reload();
});
</script>

</body>
</html>

<style>
        <?= file_get_contents(__DIR__ . '/view_indicadores_v2.css') ?>
        <?= file_get_contents(__DIR__ . '/view_indicadores_v2_grupo_tarjeta.css') ?> 
</style>


