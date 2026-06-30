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
WHERE c.id_configuracion_tipo = " . ConfiguracionTipo::TIPO_REGISTRO_TECNICO . " 
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
    <title>MONITOR GENERAL - DATAFAM</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            padding: 20px 0;
            height: 100vh;
            color: #00d2ff;
            /* Cyan HUD global */
            font-family: 'Courier New', Courier, monospace;
            /* Tipografía estilo consola */
            overflow-x: hidden;
            background-color: #02060d;
        }

        /* Fondo de nave espacial / cibernético con blur integrado */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1920&q=80');
            background-image: url('https://image.slidesdocs.com/responsive-images/background/modern-technology-corridor-a-futuristic-view-of-illuminated-light-tunnel-in-science-fiction-theme-powerpoint-background_596e41a19d__960_540.jpg');
            background-image: url('');
            background-image: url('');
            background-image: url('');
            background-image: url('');
            background-image: url('https://w0.peakpx.com/wallpaper/639/429/HD-wallpaper-flag-of-neuquen-grunge-art-rhombus-grunge-texture-argentine-province-neuquen-flag-argentina-national-symbols-neuquen-provinces-of-argentina-creative-art.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(4px);
            /* Desenfoque del fondo */
            z-index: -1;
            transform: scale(1.05);
            /* Evita bordes blancos por el blur */
        }

        #video-fondo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            /* Clave: hace que el video llene la pantalla sin deformarse */
            filter: blur(5px);
            /* El blur para que los datos del HUD sigan siendo legibles */
            z-index: -1;
            /* Lo manda al fondo de todo */
            transform: scale(1.05);
            /* Evita líneas blancas en los bordes por el blur */
        }

        .video-background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            filter: blur(0px);
            /* Ajustá el desenfoque para que no moleste a las KPI */
            transform: scale(1.07);
            /* Oculta los bordes blancos del blur */
            pointer-events: none;
            /* Clave: hace que el video sea "fantasma" y puedas hacer clic en el dash */
        }

        .video-background-container iframe {
            width: 100vw;
            height: 56.25vw;
            /* Relación de aspecto 16:9 estándar */
            min-height: 100vh;
            min-width: 177.77vh;
            /* Asegura que cubra todo el monitor sin importar la resolución */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Centrado absoluto perfecto */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            /* Truco Pro: Activa la aceleración por GPU para que el blur sea fluido y no altere los frames */
            transform: translate(-50%, -50%) translateZ(0);
            will-change: transform;
        }

        h2,
        h4 {
            color: #00d2ff;
            text-shadow: 0 0 6px rgba(0, 210, 255, 0.6);
            letter-spacing: 2px;
        }

        /* Tarjeta con efecto Glassmorphism Cyberpunk */
        .card-tecnica {
            background: rgba(0, 20, 40, 0.4);
            /* Capa azulada traslúcida */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 150, 255, 0.4);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 150, 255, 0.15), inset 0 0 10px rgba(0, 150, 255, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .card-tecnica:hover {
            transform: translateY(-3px);
            border-color: #00ffaa;
            box-shadow: 0 0 20px rgba(0, 255, 170, 0.3), inset 0 0 10px rgba(0, 255, 170, 0.1);
        }

        .valor-kpi {
            font-size: 3.5rem;
            font-weight: bold;
            display: block;
            line-height: 1;
            margin-top: 10px;
            text-shadow: 0 0 10px currentColor;
        }

        .label-kpi {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            color: #a2c8ff;
        }

        /* Variaciones de bordes HUD neón */
        .bg-critico {
            border-left: 4px solid #ff4b2b;
            box-shadow: inset 4px 0 10px rgba(255, 75, 43, 0.1);
        }

        .bg-proceso {
            border-left: 4px solid #00d2ff;
            box-shadow: inset 4px 0 10px rgba(0, 210, 255, 0.1);
        }

        .bg-exito {
            border-left: 4px solid #00ffaa;
            box-shadow: inset 4px 0 10px rgba(0, 255, 170, 0.1);
        }

        .bg-exito-mes {
            border-left: 4px solid #00ff37;
            box-shadow: inset 4px 0 10px rgba(0, 255, 55, 0.1);
        }

        /* Barras de progreso de carga estilo energía */
        .progress {
            background-color: rgba(0, 210, 255, 0.1);
            border: 1px solid rgba(0, 210, 255, 0.2);
            border-radius: 4px;
            height: 10px;
            margin-bottom: 0;
            box-shadow: inset 0 0 5px rgba(0, 210, 255, 0.2);
        }

        .progress-bar-glow {
            box-shadow: 0 0 8px #00ffaa;
            background: linear-gradient(90deg, #00d2ff 0%, #00ffaa 100%);
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        /* Scrollbar del panel derecho acoplado al HUD */
        .card-tecnica::-webkit-scrollbar {
            width: 5px;
        }

        .card-tecnica::-webkit-scrollbar-track {
            background: rgba(0, 210, 255, 0.05);
        }

        .card-tecnica::-webkit-scrollbar-thumb {
            background: rgba(0, 210, 255, 0.4);
            border-radius: 4px;
        }

        .card-tecnica::-webkit-scrollbar-thumb:hover {
            background: #00ffaa;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <h2 class="text-center" style="margin-bottom: 40px; font-weight: 400;">
            CENTRO DE MONITOREO INFORMATICO // <span style="font-weight: 800; color: #00ffaa; text-shadow: 0 0 8px #00ffaa;">DATAFAM</span>
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
                    <span class="valor-kpi" style="color: #00d2ff;"><?= $enAsistencia ?></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-tecnica bg-exito">
                    <span class="label-kpi">Resueltos Hoy</span>
                    <span class="valor-kpi" style="color: #00ffaa;"><?= $solucionadosHoy ?></span>
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
                    <h4 style="margin-bottom: 20px; font-weight: 400;">Carga de Trabajo por Categoría</h4>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="graficoNeon"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card-tecnica" style="height: 400px; overflow-y: auto;">
                    <h4 style="margin-bottom: 25px; font-weight: 400;">Eficacia de Respuesta</h4>

                    <?php foreach ($datosGrafico as $dato):
                        $porc = ($dato['total'] > 0) ? round(($dato['resueltos'] / $dato['total']) * 100) : 0;
                    ?>
                        <div style="margin-bottom: 22px;">
                            <div class="flex-container">
                                <span style="font-size: 0.95rem; color: #a2c8ff;"><?= $dato['tipo'] ?></span>
                                <span style="color: #00ffaa; font-weight: bold; text-shadow: 0 0 4px #00ffaa;"><?= $porc ?>%</span>
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
        var ctx = document.getElementById('graficoNeon').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Solicitados',
                    data: <?= $totales ?>,
                    backgroundColor: 'rgba(0, 210, 255, 0.25)',
                    borderColor: '#00d2ff',
                    borderWidth: 2,
                    borderRadius: 3
                }, {
                    label: 'Solucionados',
                    data: <?= $resueltos ?>,
                    backgroundColor: 'rgba(0, 255, 170, 0.25)',
                    borderColor: '#00ffaa',
                    borderWidth: 2,
                    borderRadius: 3
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 210, 255, 0.08)'
                        },
                        ticks: {
                            color: '#00d2ff'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#00d2ff'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#00d2ff',
                            font: {
                                family: 'Courier New'
                            }
                        }
                    }
                }
            }
        });

        var urlSonido = "https://tmpfiles.org/dl/wtw0A5zfVOP7/registro.wav";
        var sonido = new Audio(urlSonido);

        document.addEventListener('click', function() {
            sonido.play().then(() => {
                sonido.pause();
                sonido.currentTime = 0;
            }).catch(e => console.log("Audio en espera de interacción."));
        }, {
            once: true
        });

        setInterval(function() {
            fetch('<?= $urlCheckAlerta ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.disparar) {
                        sonido.play().catch(err => console.log("Reproducción bloqueada por el navegador."));
                    }
                })
                .catch(err => console.error("Error en la comprobación de alertas de red:", err));
        }, 30000);
    </script>
</body>
<div class="video-background-container">
    <!-- <iframe
        src="https://www.youtube.com/embed/CQDtI7DrU7o?autoplay=1&mute=1&loop=1&playlist=CQDtI7DrU7o&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3"
        frameborder="0"
        allow="autoplay; encrypted-media"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe> -->
    <!-- <iframe
        src="https://www.youtube.com/embed/EGs_5X8Hiyw?autoplay=1&mute=1&loop=1&playlist=EGs_5X8Hiyw&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3"
        frameborder="0"
        allow="autoplay; encrypted-media"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe> -->
    <iframe
        src="https://www.youtube.com/embed/z8JMxm9tXZQ?autoplay=1&mute=1&loop=1&playlist=z8JMxm9tXZQ&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&vq=hd2160"
        frameborder="0"
        allow="autoplay; encrypted-media"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe>
</div>

</html>