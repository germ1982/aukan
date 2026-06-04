<?php

use app\models\RegistroTecnico;
use app\models\ConfiguracionTipo;
use yii\helpers\Url;
use yii\helpers\Json;

// Mantenemos tu ruta de consulta para las alertas
$urlCheckAlerta = Url::to(['registro_tecnico/check_alerta']);

$pendientes = RegistroTecnico::find()->where(['estado' => RegistroTecnico::ESTADO_PENDIENTE])->count();
$enAsistencia = RegistroTecnico::find()->where(['estado' => RegistroTecnico::ESTADO_ASISTENCIA])->count();
$solucionadosHoy = RegistroTecnico::find()->where(['estado' => RegistroTecnico::ESTADO_FINALIZADO])->andWhere(['>=', 'fecha_solucion', date('Y-m-d')])->count();
$solucionadosMes = RegistroTecnico::find()->where(['estado' => RegistroTecnico::ESTADO_FINALIZADO])->andWhere(['>=', 'fecha_solucion', date('Y-m-01')])->count();

$sql = "SELECT 
    c.descripcion AS tipo,
    COUNT(r.idregistro) AS total,
    SUM(CASE WHEN r.estado = 0 THEN 1 ELSE 0 END) AS pendientes,
    SUM(CASE WHEN r.estado = 1 THEN 1 ELSE 0 END) AS asistencia,
    SUM(CASE WHEN r.estado = 2 THEN 1 ELSE 0 END) AS resueltos
FROM familia.configuracion c
LEFT JOIN familia.registro_tecnico r ON c.id_configuracion = r.idtipo_registro
WHERE c.id_configuracion_tipo = ". ConfiguracionTipo::TIPO_REGISTRO_TECNICO." 
GROUP BY c.id_configuracion, c.descripcion;";
$datosGrafico = Yii::$app->db->createCommand($sql)->queryAll();

// Pasamos los datos a formato JSON para el gráfico
$labels = Json::encode(array_column($datosGrafico, 'tipo'));
$totales = Json::encode(array_column($datosGrafico, 'total'));
$resueltos = Json::encode(array_column($datosGrafico, 'resueltos'));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONITOR GENERAL - DATAFAM</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* Variables de Entorno del Dashboard */
            --bg-dashboard: #0b0f17;
            --bg-card: #121824;
            --border-card: rgba(255, 255, 255, 0.05);
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            
            /* Tu Paleta SUR / DATAFAM Integrada en la semántica */
            --sur-dark: #2b3e4c;
            --sur-green: #87b867;
            --sur-yellow: #f4dfb9;
            
            /* Variaciones de semántica para los estados críticos */
            --status-critico: #ef4444;    /* Pendientes */
            --status-proceso: #3b82f6;    /* Asistencia */
            --status-exito: #87b867;      /* Resueltos Hoy */
            --status-mes: #10b981;        /* Resueltos Mes */
        }

        body {
            background-color: var(--bg-dashboard);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, sans-serif;
            padding-top: 30px;
            padding-bottom: 30px;
            -webkit-font-smoothing: antialiased;
        }

        /* Encabezado Principal */
        .dashboard-header {
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-card);
            padding-bottom: 20px;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: 300;
            letter-spacing: -0.5px;
            color: var(--text-primary);
            margin: 0;
        }

        .dashboard-title span {
            color: var(--sur-green);
            font-weight: 700;
        }

        /* ATOMIC CARD COMPONENT */
        .card-tecnica {
            background-color: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 14px;
            padding: 22px 24px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .card-tecnica::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--indicator-color, var(--sur-green));
        }

        .card-tecnica:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
        }

        /* KPI inner metrics */
        .label-kpi {
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .valor-kpi {
            font-family: 'JetBrains Mono', monospace;
            font-size: 42px;
            font-weight: 700;
            display: block;
            line-height: 1;
            margin-top: 12px;
            color: var(--indicator-color, #fff);
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.05);
        }

        /* Mapeo de indicadores semánticos nativos en CSS sin sobrecargar clases */
        .bg-critico { --indicator-color: var(--status-critico); }
        .bg-proceso { --indicator-color: var(--status-proceso); }
        .bg-exito { --indicator-color: var(--status-exito); }
        .bg-exito-mes { --indicator-color: var(--status-mes); }

        /* SECCIÓN INFERIOR Y GRÁFICOS */
        .section-card-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-top: 0;
            margin-bottom: 24px;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .metric-label {
            font-size: 13px;
            color: var(--text-primary);
            font-weight: 400;
        }

        .metric-percentage {
            font-family: 'JetBrains Mono', monospace;
            color: var(--sur-green);
            font-weight: 700;
            font-size: 13px;
        }

        /* PROGRESS COMPONENT (Tailwind Inspired) */
        .progress {
            background-color: rgba(255, 255, 255, 0.03);
            border-radius: 9999px;
            height: 8px;
            margin-bottom: 0;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.02);
        }

        .progress-bar-glow {
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--sur-green) 0%, #a3db7d 100%);
            box-shadow: 0 0 12px rgba(135, 184, 103, 0.4);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* =========================================================================
           5. PERSONALIZACIÓN DEL SCROLLBAR (Estilo React / Dark UI)
           ========================================================================= */
        
        /* Contenedor del scroll (La pista por donde corre) */
        .card-tecnica::-webkit-scrollbar {
            width: 6px;               /* Bien finita para que no moleste */
            height: 6px;
        }

        /* El fondo del canal del scroll */
        .card-tecnica::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.01); 
            border-radius: 9999px;
        }

        /* El "pulgarcito" (La barra que arrastrás con el mouse) */
        .card-tecnica::-webkit-scrollbar-thumb {
            background: rgba(135, 184, 103, 0.3); /* Tu verde SUR con transparencia */
            border-radius: 9999px;
            transition: background 0.2s ease;
        }

        /* Cuando le pasás el mouse por arriba a la barra, se ilumina un toque más */
        .card-tecnica::-webkit-scrollbar-thumb:hover {
            background: rgba(135, 184, 103, 0.6); /* Verde SUR más sólido */
        }

        /* Soporte estándar para navegadores modernos como Firefox */
        .card-tecnica {
            scrollbar-width: thin;
            scrollbar-color: rgba(135, 184, 103, 0.3) rgba(255, 255, 255, 0.01);
        }

        
    </style>
</head>

<body>

    <div class="container-fluid">
        
        <div class="row">
            <div class="col-xs-12">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">Centro de Monitoreo Informático / <span>DATAFAM</span></h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card-tecnica bg-critico">
                    <span class="label-kpi">Tickets Pendientes</span>
                    <span class="valor-kpi"><?= $pendientes ?></span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card-tecnica bg-proceso">
                    <span class="label-kpi">En Asistencia</span>
                    <span class="valor-kpi"><?= $enAsistencia ?></span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card-tecnica bg-exito">
                    <span class="label-kpi">Resueltos Hoy</span>
                    <span class="valor-kpi"><?= $solucionadosHoy ?></span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card-tecnica bg-exito-mes">
                    <span class="label-kpi">Resueltos Mes</span>
                    <span class="valor-kpi"><?= $solucionadosMes ?></span>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-7">
                <div class="card-tecnica" style="height: 420px; padding-bottom: 30px;">
                    <h4 class="section-card-title">Carga de Trabajo por Categoría</h4>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="graficoNeon"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card-tecnica" style="height: 420px; overflow-y: auto; padding-right: 15px;">
                    <h4 class="section-card-title">Eficacia de Respuesta</h4>

                    <?php foreach ($datosGrafico as $dato):
                        $porc = ($dato['total'] > 0) ? round(($dato['resueltos'] / $dato['total']) * 100) : 0;
                    ?>
                        <div style="margin-bottom: 24px;">
                            <div class="flex-container">
                                <span class="metric-label"><?= htmlspecialchars($dato['tipo']) ?></span>
                                <span class="metric-percentage"><?= $porc ?>%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-glow" style="width: <?= $porc ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Instanciación del Gráfico con Estética Glassmorphism
            var ctx = document.getElementById('graficoNeon').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= $labels ?>,
                    datasets: [{
                        label: 'Solicitados',
                        data: <?= $totales ?>,
                        backgroundColor: 'rgba(59, 130, 246, 0.15)', // Soft Blue translúcido
                        borderColor: '#3b82f6',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }, {
                        label: 'Solucionados',
                        data: <?= $resueltos ?>,
                        backgroundColor: 'rgba(135, 184, 103, 0.15)', // Tu Verde SUR translúcido
                        borderColor: '#87b867',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.04)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: { family: 'Inter', size: 11 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { family: 'Inter', size: 11 }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                color: '#f3f4f6',
                                boxWidth: 12,
                                boxHeight: 12,
                                useBorderRadius: true,
                                borderRadius: 3,
                                font: { family: 'Inter', size: 12, weight: 500 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#121824',
                            titleColor: '#f3f4f6',
                            bodyColor: '#9ca3af',
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                            padding: 10,
                            bodyFont: { family: 'Inter' },
                            titleFont: { family: 'Inter', weight: 600 }
                        }
                    }
                }
            });

            // 2. Control de Alertas Sonoras de Red en Background
            var urlSonido = "https://tmpfiles.org/dl/wtw0A5zfVOP7/registro.wav";
            var sonido = new Audio(urlSonido);

            // Desbloqueo del hilo de AudioContext ante la primera interacción
            document.addEventListener('click', function() {
                sonido.play().then(() => {
                    sonido.pause();
                    sonido.currentTime = 0;
                }).catch(e => console.log("AudioContext inicializado en espera de dispatch."));
            }, { once: true });

            // 3. Loop de consulta asíncrona mediante Fetch API (Cada 30 segundos)
            setInterval(function() {
                fetch('<?= $urlCheckAlerta ?>')
                    .then(response => response.json())
                    .then(data => {
                        if (data.disparar) {
                            sonido.play().catch(err => console.log("Fallo el disparo automático de audio:", err));
                        }
                    })
                    .catch(err => console.error("Error en la telemetría de red:", err));
            }, 30000);
        });
    </script>

</body>
</html>