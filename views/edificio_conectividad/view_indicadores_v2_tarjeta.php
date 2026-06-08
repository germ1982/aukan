<?php
// C:\xampp_datafam\htdocs\datafam\views\edificio_conectividad\view_indicadores_v2_tarjeta.php

use yii\helpers\Json;
use yii\web\View;

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $accentColor */
/** @var string $chartId */
/** @var array $data */

if (!function_exists('renderCryptoDataList')) {
    function renderCryptoDataList($title, $items)
    {
        $html = '<div class="react-sub-section">';
        $html .= '<h4 class="react-list-title">' . htmlspecialchars($title) . '</h4>';
        $html .= '<div class="react-list-group">';

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
            <span class="component-counter " style="text-shadow: 0 0 20px <?= $accentColor ?>26;"><?= $data['total'] ?></span>
            <span class="component-title"><?= htmlspecialchars($title) ?></span>

        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Infraestructura', $data['infraestructura']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Servicio', $data['servicio']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Enlace', $data['enlace']) ?>
            </div>
        </div>

        <div class="status-wrapper">
            <div class="status-legend-container">
                <div class="react-list-title" style="margin-bottom:4px;">Estado Enlaces</div>
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