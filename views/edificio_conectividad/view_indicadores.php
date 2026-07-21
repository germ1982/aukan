<?php

use yii\helpers\Url;
use yii\helpers\Json;

/* @var $this yii\web\View */

// =========================================================================
// 1. SERVICES & DATA LAYER (Simulando Hooks de extracción de datos en React)
// =========================================================================

/**
 * Obtiene la estructura de datos consolidada para un tipo de edificio.
 * @param string $terminoBusqueda Término para filtrar en descripcion_fija (Ej: 'CDI', 'HOGAR', 'DELEGACION')
 * @return array Datos procesados listos para los componentes visuales.
 */
function useEdificioConectividadData($terminoBusqueda) {
    $sql = "SELECT 
    COUNT(ec.idconectividad) as total_conexiones,
    
    -- Subconsulta Infraestructuras
    (SELECT GROUP_CONCAT(CONCAT(c_inf.descripcion, ':', conf_inf.cnt) SEPARATOR ',')
     FROM (SELECT infraestructura, COUNT(*) as cnt 
           FROM familia.edificio_conectividad ec2
           JOIN familia.edificio e2 ON ec2.idedificio = e2.idedificio
           WHERE e2.descripcion_gestion LIKE '%$terminoBusqueda%' GROUP BY infraestructura) conf_inf
     JOIN familia.configuracion c_inf ON conf_inf.infraestructura = c_inf.id_configuracion) as infraestructuras,
    
    -- Subconsulta Servicios (CORREGIDA LA TABLA ACA)
    (SELECT GROUP_CONCAT(CONCAT(c_ser.descripcion, ':', conf_ser.cnt) SEPARATOR ',')
     FROM (SELECT servicio, COUNT(*) as cnt 
           FROM familia.edificio_conectividad ec3
           JOIN familia.edificio e3 ON ec3.idedificio = e3.idedificio
           WHERE e3.descripcion_gestion LIKE '%$terminoBusqueda%' GROUP BY servicio) conf_ser
     JOIN familia.configuracion c_ser ON conf_ser.servicio = c_ser.id_configuracion) as servicios,
    
    -- Subconsulta Enlaces
    (SELECT GROUP_CONCAT(CONCAT(c_en.descripcion, ':', conf_en.cnt) SEPARATOR ',')
     FROM (SELECT tipo_conexion, COUNT(*) as cnt 
           FROM familia.edificio_conectividad ec4
           JOIN familia.edificio e4 ON ec4.idedificio = e4.idedificio
           WHERE e4.descripcion_gestion LIKE '%$terminoBusqueda%' GROUP BY tipo_conexion) conf_en
     JOIN familia.configuracion c_en ON conf_en.tipo_conexion = c_en.id_configuracion) as enlaces,
    
    -- Contadores de Estados para el gráfico de torta
    SUM(CASE WHEN c_est.descripcion LIKE '%bueno%' OR c_est.descripcion LIKE '%excelente%' THEN 1 ELSE 0 END) as estado_bueno,
    SUM(CASE WHEN c_est.descripcion LIKE '%malo%' THEN 1 ELSE 0 END) as estado_malo,
    SUM(CASE WHEN c_est.descripcion LIKE '%regular%' THEN 1 ELSE 0 END) as estado_regular,
    SUM(CASE WHEN c_est.descripcion LIKE '%no funciona%' OR c_est.descripcion LIKE '%caido%' THEN 1 ELSE 0 END) as estado_no_funciona
FROM familia.edificio_conectividad ec
JOIN familia.edificio e ON ec.idedificio = e.idedificio
LEFT JOIN familia.configuracion c_est ON ec.estado = c_est.id_configuracion
WHERE e.descripcion_gestion LIKE '%$terminoBusqueda%';
    ";

    $rawData = Yii::$app->db->createCommand($sql)->queryOne();

    // Helper interno para parsear los strings de agregación de MySQL
    $parseKeyValue = function($stringDatos) {
        if (empty($stringDatos)) return [];
        $resultado = [];
        foreach (explode(',', $stringDatos) as $par) {
            $partes = explode(':', $par);
            if (count($partes) == 2) {
                $resultado[$partes[0]] = (int)$partes[1];
            }
        }
        return $resultado;
    };

    return [
        'total' => (int)$rawData['total_conexiones'],
        'infraestructura' => $parseKeyValue($rawData['infraestructuras']),
        'servicio' => $parseKeyValue($rawData['servicios']),
        'enlace' => $parseKeyValue($rawData['enlaces']),
        'estados' => [
            'bueno' => (int)$rawData['estado_bueno'],
            'malo' => (int)$rawData['estado_malo'],
            'regular' => (int)$rawData['estado_regular'],
            'caido' => (int)$rawData['estado_no_funciona'],
        ]
    ];
}

// Consumo de datos para los tres entornos operacionales
$cdiData = useEdificioConectividadData('CDI');
$hogaresData = useEdificioConectividadData('HOGAR');
$delegacionesData = useEdificioConectividadData('DELEGACION');

// =========================================================================
// 2. REUSABLE UI COMPONENTS (Estructuración de componentes atómicos estilo React)
// =========================================================================

/**
 * Componente funcional para renderizar bloques de listas de subcategorías (Props)
 */
function renderCryptoDataList($title, $items) {
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Conectividad - AUKAN</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-dashboard: #0b0f19;
            --bg-card: #131a26;
            --border-card: rgba(255, 255, 255, 0.06);
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            
            /* Paleta de Estados Exacta */
            --color-bueno: #a3e116;
            --color-malo: #9ad0f5;
            --color-regular: #ffb03b;
            --color-caido: #ff2a2a;
            
            /* Variaciones de marcas de acento React */
            --accent-cdi: #3b82f6;
            --accent-hogares: #10b981;
            --accent-delegaciones: #f59e0b;
        }

        body {
            background-color: var(--bg-dashboard);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding-top: 30px;
            padding-bottom: 5px;
            -webkit-font-smoothing: antialiased;
        }

        .dashboard-header {
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-card);
            padding-bottom: 20px;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-primary);
        }

        .dashboard-title span {
            color: var(--accent-cdi);
            font-weight: 400;
        }

        /* CARD COMPONENT (React-Style shadow and borders) */
        .react-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .react-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--accent-color, var(--accent-cdi));
        }

        .react-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* HEADER DEL COMPONENTE */
        .card-component-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 12px;
        }

        .component-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
        }

        .component-counter {
            font-family: 'JetBrains Mono', monospace;
            font-size: 38px;
            font-weight: 700;
            line-height: 1;
            color: var(--accent-color, var(--accent-cdi));
            text-shadow: 0 0 20px rgba(59, 130, 246, 0.15);
        }

        /* DATA LISTS PROPERTIES */
        .react-sub-section {
            margin-bottom: 16px;
        }

        .react-list-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .react-list-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .react-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.02);
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.01);
            font-size: 13px;
        }

        .react-list-item:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }

        .react-item-name {
            color: var(--text-primary);
            font-weight: 400;
        }

        .react-item-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            color: #00ffff;
            background-color: rgba(0, 255, 255, 0.08);
            padding: 1px 6px;
            border-radius: 4px;
        }

        /* COMPONENTE DE ESTADOS INFERIORES */
        .status-wrapper {
            display: flex;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .status-legend-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
            width: 55%;
        }

        .react-status-row {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-weight: 500;
        }

        .react-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .dot-bueno { background-color: var(--color-bueno); box-shadow: 0 0 8px var(--color-bueno); }
        .dot-malo { background-color: var(--color-malo); box-shadow: 0 0 8px var(--color-malo); }
        .dot-regular { background-color: var(--color-regular); box-shadow: 0 0 8px var(--color-regular); }
        .dot-caido { background-color: var(--color-caido); box-shadow: 0 0 8px var(--color-caido); }

        .react-status-value {
            margin-left: auto;
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-muted);
        }

        .chart-box {
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .canvas-container {
            position: relative;
            width: 90px;
            height: 90px;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">Centro de Monitoreo de Conectividad / <span>AUKAN</span></h2>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-4">
                <div class="react-card" style="--accent-color: var(--accent-cdi);">
                    <div class="card-component-header">
                        <span class="component-title">Conexiones de CDI</span>
                        <span class="component-counter"><?= $cdiData['total'] ?></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-6" style="padding-right:8px;">
                            <?= renderCryptoDataList('Infraestructura', $cdiData['infraestructura']) ?>
                        </div>
                        <div class="col-xs-6" style="padding-left:8px;">
                            <?= renderCryptoDataList('Servicio', $cdiData['servicio']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?= renderCryptoDataList('Enlace', $cdiData['enlace']) ?>
                        </div>
                    </div>

                    <div class="status-wrapper">
                        <div class="status-legend-container">
                            <div class="react-list-title" style="margin-bottom:4px;">Estado Enlaces</div>
                            <div class="react-status-row"><span class="react-status-dot dot-bueno"></span>Bueno <span class="react-status-value"><?= $cdiData['estados']['bueno'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-malo"></span>Malo <span class="react-status-value"><?= $cdiData['estados']['malo'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-regular"></span>Regular <span class="react-status-value"><?= $cdiData['estados']['regular'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-caido"></span>No Funciona <span class="react-status-value"><?= $cdiData['estados']['caido'] ?></span></div>
                        </div>
                        <div class="chart-box">
                            <div class="canvas-container">
                                <canvas id="chartCdi"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="react-card" style="--accent-color: var(--accent-hogares);">
                    <div class="card-component-header">
                        <span class="component-title">Conexiones de Hogares</span>
                        <span class="component-counter" style="text-shadow: 0 0 20px rgba(16, 185, 129, 0.15);"><?= $hogaresData['total'] ?></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-6" style="padding-right:8px;">
                            <?= renderCryptoDataList('Infraestructura', $hogaresData['infraestructura']) ?>
                        </div>
                        <div class="col-xs-6" style="padding-left:8px;">
                            <?= renderCryptoDataList('Servicio', $hogaresData['servicio']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?= renderCryptoDataList('Enlace', $hogaresData['enlace']) ?>
                        </div>
                    </div>

                    <div class="status-wrapper">
                        <div class="status-legend-container">
                            <div class="react-list-title" style="margin-bottom:4px;">Estado Enlaces</div>
                            <div class="react-status-row"><span class="react-status-dot dot-bueno"></span>Bueno <span class="react-status-value"><?= $hogaresData['estados']['bueno'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-malo"></span>Malo <span class="react-status-value"><?= $hogaresData['estados']['malo'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-regular"></span>Regular <span class="react-status-value"><?= $hogaresData['estados']['regular'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-caido"></span>No Funciona <span class="react-status-value"><?= $hogaresData['estados']['caido'] ?></span></div>
                        </div>
                        <div class="chart-box">
                            <div class="canvas-container">
                                <canvas id="chartHogares"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="react-card" style="--accent-color: var(--accent-delegaciones);">
                    <div class="card-component-header">
                        <span class="component-title">Delegaciones</span>
                        <span class="component-counter" style="text-shadow: 0 0 20px rgba(245, 158, 11, 0.15);"><?= $delegacionesData['total'] ?></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-6" style="padding-right:8px;">
                            <?= renderCryptoDataList('Infraestructura', $delegacionesData['infraestructura']) ?>
                        </div>
                        <div class="col-xs-6" style="padding-left:8px;">
                            <?= renderCryptoDataList('Servicio', $delegacionesData['servicio']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?= renderCryptoDataList('Enlace', $delegacionesData['enlace']) ?>
                        </div>
                    </div>

                    <div class="status-wrapper">
                        <div class="status-legend-container">
                            <div class="react-list-title" style="margin-bottom:4px;">Estado Enlaces</div>
                            <div class="react-status-row"><span class="react-status-dot dot-bueno"></span>Bueno <span class="react-status-value"><?= $delegacionesData['estados']['bueno'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-malo"></span>Malo <span class="react-status-value"><?= $delegacionesData['estados']['malo'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-regular"></span>Regular <span class="react-status-value"><?= $delegacionesData['estados']['regular'] ?></span></div>
                            <div class="react-status-row"><span class="react-status-dot dot-caido"></span>No Funciona <span class="react-status-value"><?= $delegacionesData['estados']['caido'] ?></span></div>
                        </div>
                        <div class="chart-box">
                            <div class="canvas-container">
                                <canvas id="chartDelegaciones"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                cutout: '70%', // Hace que el gráfico de torta pase a ser un Doughnut refinado y moderno
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            };

            const colors = ['#a3e116', '#9ad0f5', '#ffb03b', '#ff2a2a'];

            // Mount Chart CDI
            new Chart(document.getElementById('chartCdi').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Bueno', 'Malo', 'Regular', 'No Funciona'],
                    datasets: [{
                        data: <?= Json::encode(array_values($cdiData['estados'])) ?>,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#131a26'
                    }]
                },
                options: chartOptions
            });

            // Mount Chart Hogares
            new Chart(document.getElementById('chartHogares').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Bueno', 'Malo', 'Regular', 'No Funciona'],
                    datasets: [{
                        data: <?= Json::encode(array_values($hogaresData['estados'])) ?>,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#131a26'
                    }]
                },
                options: chartOptions
            });

            // Mount Chart Delegaciones
            new Chart(document.getElementById('chartDelegaciones').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Bueno', 'Malo', 'Regular', 'No Funciona'],
                    datasets: [{
                        data: <?= Json::encode(array_values($delegacionesData['estados'])) ?>,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#131a26'
                    }]
                },
                options: chartOptions
            });
        });
    </script>
</body>
</html>