<?php
// C:\xampp_datafam\htdocs\datafam\views\edificio_conectividad\view_indicadores_v2_tarjeta.php

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $accentColor */
/** @var string $chartId */
/** @var string $text_match */ 
/** @var array $tarjetas */ 
/** @var array $data */

if (!function_exists('renderCryptoDataList')) {
    function renderCryptoDataList($title, $items, $sectionId, $agrupar = false)
    {
        $html = '<div class="react-sub-section">';

        // Cabecera principal (Infraestructura / Servicio / Enlace)
        $html .= '<div class="react-list-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">';
        $html .= '  <h4 class="react-list-title" style="margin-bottom: 0;">' . htmlspecialchars($title) . '</h4>';
        $html .= '  <button type="button" class="cyber-toggle-btn" onclick="toggleCyberSection(\'' . $sectionId . '\', this)">';
        $html .= '    <span class="glyphicon glyphicon-chevron-down toggle-icon"></span>';
        $html .= '  </button>';
        $html .= '</div>';

        // Contenedor principal de la tarjeta
        $html .= '<div id="' . $sectionId . '" class="react-list-group cyber-collapsible expanded">';

        if (empty($items)) {
            $html .= '<div class="react-list-item empty text-muted">Sin registros activos</div>';
        } else {
            if ($agrupar) {
                $grupoContador = 0;
                $grupoItems = [];
                $itemSueltoNombre = '';
                $itemSueltoContador = null;

                foreach ($items as $name => $count) {
                    $nameClean = strtolower(trim($name));
                    // Detectamos si es el caso "Sin Servicio" o "Desconocida"
                    if ($nameClean === 'sin servicio' || $nameClean === 'desconocida') {
                        $itemSueltoNombre = $name; // Mantiene el formato original (Sin Servicio o Desconocida)
                        $itemSueltoContador = $count;
                    } else {
                        $grupoContador += $count;
                        $grupoItems[$name] = $count;
                    }
                }

                // 1. Si existe el elemento suelto (Sin Servicio / Desconocida), sale arriba directo
                if ($itemSueltoContador !== null) {
                    $html .= '<div class="react-list-item">';
                    $html .= '  <span class="react-item-name">' . htmlspecialchars($itemSueltoNombre) . '</span>';
                    $html .= '  <span class="react-item-badge">' . $itemSueltoContador . '</span>';
                    $html .= '</div>';
                }

                // 2. Todo lo demás se agrupa acá abajo con el colapsable por CSS
                if ($grupoContador > 0) {
                    $subSectionId = $sectionId . '-sub-grupo';
                    // Definimos el título del grupo según la sección
                    $tituloGrupo = (strtolower($title) == 'conexion') ? 'Con Conexión' : 'Con Servicio';
                    
                    $html .= '<div class="react-list-item" style="display: flex; flex-direction: column; align-items: stretch; padding: 0;">';
                    
                    // Input invisible para el estado
                    $html .= '  <input type="checkbox" id="check-' . $subSectionId . '" style="display: none;" />';
                    
                    // Fila del encabezado agrupado
                    $html .= '  <div style="display: flex; align-items: center; padding: 6px 3px; width: 100%; gap: 8px;">';
                    $html .= '    <span class="react-item-name" style="margin-right: auto;">' . $tituloGrupo . '</span>';
                    
                    // Flechita (Label del checkbox)
                    $html .= '    <label for="check-' . $subSectionId . '" class="cyber-toggle-btn sub-toggle" style="padding: 2px 0px; font-size: 10px; margin-right: 4px; cursor: pointer; margin-bottom: 0;">';
                    $html .= '      <span class="glyphicon glyphicon-chevron-down toggle-icon"></span>';
                    $html .= '    </label>';
                    
                    $html .= '    <span class="react-item-badge">' . $grupoContador . '</span>';
                    $html .= '  </div>';

                    // Subcontenedor interno desplegable
                    $html .= '  <div class="sub-cyber-content" style="padding-left: 0px; background: rgba(0,0,0,0.15); border-radius: 4px; margin: 0px 0px 6px 0px;">';
                    foreach ($grupoItems as $subName => $subCount) {
                        $html .= '<div class="react-list-item" style="padding: 4px 12px; border-bottom: 1px solid rgba(255,255,255,0.03);">';
                        $html .= '  <span class="react-item-name" style="font-size: 0.95em; opacity: 0.85;">' . htmlspecialchars($subName) . '</span>';
                        $html .= '  <span class="react-item-badge" style="opacity: 0.85;">' . $subCount . '</span>';
                        $html .= '</div>';
                    }
                    $html .= '  </div>';
                    
                    // CSS inline para controlar la magia del toggle limpio
                    $html .= '<style>';
                    $html .= '  #check-' . $subSectionId . ' ~ .sub-cyber-content { display: none; }';
                    $html .= '  #check-' . $subSectionId . ':checked ~ .sub-cyber-content { display: block; }';
                    $html .= '  #check-' . $subSectionId . ':checked ~ div .toggle-icon { transform: rotate(180deg); }';
                    $html .= '</style>';
                    
                    $html .= '</div>';
                }
            } else {
                // Modo plano clásico por si alguna tarjeta no usa agrupación
                foreach ($items as $name => $count) {
                    $html .= '<div class="react-list-item">';
                    $html .= '  <span class="react-item-name">' . htmlspecialchars($name) . '</span>';
                    $html .= '  <span class="react-item-badge">' . $count . '</span>';
                    $html .= '</div>';
                }
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
                <?= renderCryptoDataList('Infraestructura', $data['infraestructura'], $chartId . '-infra',true) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Servicio', $data['servicio'], $chartId . '-serv',true) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= renderCryptoDataList('Conexion', $data['enlace'], $chartId . '-enlace',true) ?>
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