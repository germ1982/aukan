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

/* $datosGrafico = [
    ['Tipo' => 'Soporte Software', 'Total' => 25, 'Resueltos' => 22],
    ['Tipo' => 'Hardware / PC', 'Total' => 15, 'Resueltos' => 10],
    ['Tipo' => 'Redes e Internet', 'Total' => 12, 'Resueltos' => 4],
    ['Tipo' => 'Telefonía IP', 'Total' => 8, 'Resueltos' => 8],
]; */

// Pasamos los datos a formato JSON para el gráfico
$labels = Json::encode(array_column($datosGrafico, 'tipo'));
$totales = Json::encode(array_column($datosGrafico, 'total'));
$resueltos = Json::encode(array_column($datosGrafico, 'resueltos'));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MONITOR GENERAL - DATAFAM</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #0f0f1e;
            color: #fff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding-top: 20px;
        }

        .card-tecnica {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: transform 0.3s, border-color 0.3s;
            margin-bottom: 20px;
        }

        .card-tecnica:hover {
            transform: translateY(-5px);
            border-color: #4ecca3;
        }

        .valor-kpi {
            font-size: 3.5rem;
            font-weight: bold;
            display: block;
            line-height: 1;
            margin-top: 10px;
        }

        .label-kpi {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            color: #a2a2a2;
        }

        .bg-critico {
            border-left: 5px solid #ff4b2b;
        }

        .bg-proceso {
            border-left: 5px solid #4facfe;
        }

        .bg-exito {
            border-left: 5px solid #4ecca3;
        }

        .bg-exito-mes {
            border-left: 5px solid #00ff37;
        }

        .progress {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 10px;
            margin-bottom: 0;
        }

        .progress-bar-glow {
            box-shadow: 0 0 10px rgba(0, 242, 254, 0.5);
            background: linear-gradient(90deg, #00f2fe 0%, #4facfe 100%);
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
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
        <h2 class="text-center" style="margin-bottom: 40px; font-weight: 200; letter-spacing: 1px;">
            CENTRO DE MONITOREO INFORMATICO <span style="font-weight: 800; color: #4ecca3;">DATAFAM</span>
        </h2>

        <div class="row">
            <div class="col-md-3">
                <div class="card-tecnica bg-critico">
                    <span class="label-kpi">Tickets Pendientes</span>
                    <span class="valor-kpi" style="color: #ff4b2b;"><?= $pendientes ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-tecnica bg-proceso">
                    <span class="label-kpi">En Asistencia</span>
                    <span class="valor-kpi" style="color: #4facfe;"><?= $enAsistencia ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-tecnica bg-exito">
                    <span class="label-kpi">Resueltos Hoy</span>
                    <span class="valor-kpi" style="color: #4ecca3;"><?= $solucionadosHoy ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-tecnica bg-exito-mes">
                    <span class="label-kpi">Resueltos Mes</span>
                    <span class="valor-kpi" style="color: #00ff37;"><?= $solucionadosMes ?></span>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-7">
                <div class="card-tecnica" style="height: 400px;">
                    <h4 style="margin-bottom: 20px; font-weight: 300;">Carga de Trabajo por Categoría</h4>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="graficoNeon"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card-tecnica" style="height: 400px; overflow-y: auto;">
                    <h4 style="margin-bottom: 25px; font-weight: 300;">Eficacia de Respuesta</h4>

                    <?php foreach ($datosGrafico as $dato):
                        $porc = ($dato['total'] > 0) ? round(($dato['resueltos'] / $dato['total']) * 100) : 0;
                    ?>
                        <div style="margin-bottom: 22px;">
                            <div class="flex-container">
                                <span style="font-size: 0.95rem; color: #e0e0e0;"><?= $dato['tipo'] ?></span>
                                <span style="color: #4ecca3; font-weight: bold;"><?= $porc ?>%</span>
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
        // 1. Configuración y renderizado del gráfico de barras
        var ctx = document.getElementById('graficoNeon').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Solicitados',
                    data: <?= $totales ?>,
                    backgroundColor: 'rgba(79, 172, 254, 0.3)',
                    borderColor: '#4facfe',
                    borderWidth: 2,
                    borderRadius: 5
                }, {
                    label: 'Solucionados',
                    data: <?= $resueltos ?>,
                    backgroundColor: 'rgba(78, 204, 163, 0.3)',
                    borderColor: '#4ecca3',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#a2a2a2'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#a2a2a2'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff',
                            font: {
                                family: 'Helvetica'
                            }
                        }
                    }
                }
            }
        });

        // 2. Configuración de la alerta sonora en segundo plano
        var urlSonido = "https://tmpfiles.org/dl/wtw0A5zfVOP7/registro.wav";
        var sonido = new Audio(urlSonido);

        // Activamos el permiso de audio con la primera interacción en la pantalla
        document.addEventListener('click', function() {
            sonido.play().then(() => {
                sonido.pause();
                sonido.currentTime = 0;
            }).catch(e => console.log("Audio en espera de interacción."));
        }, {
            once: true
        });

        // 3. Intervalo de consulta periódica (Loop de 30 segundos)
        setInterval(function() {
            // Ejecuta la consulta silenciosa al backend de Yii
            fetch('<?= $urlCheckAlerta ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.disparar) {
                        sonido.play().catch(err => console.log("Reproducción bloqueada por el navegador."));
                    }
                })
                .catch(err => console.error("Error en la comprobación de alertas de red:", err));

            // Si precisás que refresque toda la pantalla para actualizar las tarjetas duras:
            // location.reload();
        }, 30000);
    </script>

</body>

</html>