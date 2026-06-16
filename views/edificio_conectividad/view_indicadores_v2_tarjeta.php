<?php
// C:\xampp_datafam\htdocs\datafam\views\edificio_conectividad\view_indicadores_v2_tarjeta.php

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $accentColor */
/** @var string $chartId */
/** @var string $text_match */ // <-- DECLARAMOS LA VARIABLE ACÁ
/** @var array $tarjetas */ // <-- DECLARAMOS LA VARIABLE ACÁ
/** @var array $data */

if (!function_exists('renderCryptoDataList')) {
    function renderCryptoDataList($title, $items, $sectionId)
    {
        $html = '<div class="react-sub-section">';

        // Cabecera con flexbox para tirar el botón a la derecha
        $html .= '<div class="react-list-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">';
        $html .= '  <h4 class="react-list-title" style="margin-bottom: 0;">' . htmlspecialchars($title) . '</h4>';
        // Botón animado Cyberpunk (flecha)
        $html .= '  <button type="button" class="cyber-toggle-btn" onclick="toggleCyberSection(\'' . $sectionId . '\', this)">';
        $html .= '    <span class="glyphicon glyphicon-chevron-down toggle-icon"></span>';
        $html .= '  </button>';
        $html .= '</div>';

        // Contenedor colapsable con ID único
        $html .= '<div id="' . $sectionId . '" class="react-list-group cyber-collapsible expanded">';

        if (empty($items)) {
            $html .= '<div class="react-list-item empty text-muted">Sin registros activos</div>';
        } else {
            foreach ($items as $name => $count) {
                $html .= '<div class="react-list-item">';
                $html .= '  <span class="react-item-name">' . htmlspecialchars($name) . '</span>';
                $html .= '  <span class="react-item-badge">' . $count . '</span>';
                $html .= '</div>';
            }
        }

        $html .= '</div></div>';
        return $html;
    }
}
?>

<div class="col-md-2 col-sm-4 col-xs-12">
    <div class="react-card" style="--accent-color: <?= $accentColor ?>;">
        <div class="card-component-header">
            <span class="component-counter" style="text-shadow: 0 0 20px <?= $accentColor ?>26;"><?= $data['total'] ?></span>

            <a href="<?= Url::to(['edificio_conectividad/view_indicadores_v2_grupo', 'grupo' => $text_match]) ?>"
                class="component-title-link"
                target="_blank">
                <span class="component-title"><?= htmlspecialchars($title) ?></span>
            </a>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Infraestructura', $data['infraestructura'], $chartId . '-infra') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Servicio', $data['servicio'], $chartId . '-serv') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Enlace', $data['enlace'], $chartId . '-enlace') ?>
            </div>
        </div>

        <div class="status-wrapper">
            <div class="react-list-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div class="react-list-title" style="margin-bottom: 0;">Estado Enlaces</div>
                <button type="button" class="cyber-toggle-btn" onclick="toggleCyberSection('<?= $chartId ?>-status-block', this)">
                    <span class="glyphicon glyphicon-chevron-down toggle-icon"></span>
                </button>
            </div>

            <div id="<?= $chartId ?>-status-block" class="cyber-collapsible expanded">
                <div class="status-legend-container" style="margin-top: 8px;">
                    <div class="react-status-row"><span class="react-status-dot dot-bueno"></span>Bueno <span class="react-status-value"><?= $data['estados']['bueno'] ?></span></div>
                    <div class="react-status-row"><span class="react-status-dot dot-malo"></span>Malo <span class="react-status-value"><?= $data['estados']['malo'] ?></span></div>
                    <div class="react-status-row"><span class="react-status-dot dot-regular"></span>Regular <span class="react-status-value"><?= $data['estados']['regular'] ?></span></div>
                    <div class="react-status-row"><span class="react-status-dot dot-caido"></span>No Funciona <span class="react-status-value"><?= $data['estados']['caido'] ?></span></div>
                    <div class="react-status-row"><span class="react-status-dot dot-desconocido"></span>Desconocido <span class="react-status-value"><?= $data['estados']['desconocido'] ?></span></div>
                </div>

                <div class="chart-box">
                    <div class="canvas-container">
                        <canvas id="<?= $chartId ?>"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$estadosJson = Json::encode(array_values($data['estados']));

?>

<script>
    (function() {
        function tryInit() {
            var el = document.getElementById('<?= $chartId ?>');
            if (!el || typeof Chart === 'undefined') {
                setTimeout(tryInit, 100);
                return;
            }
            var ctx = el.getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Bueno', 'Malo', 'Regular', 'No Funciona', 'Desconocido'],
                    datasets: [{
                        data: <?= $estadosJson ?>,
                        backgroundColor: ['#a3e116', '#9ad0f5', '#ffb03b', '#ff2a2a', '#4b5563'],
                        borderWidth: 2,
                        borderColor: '#131a26'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        }
        tryInit();
    })();
</script>