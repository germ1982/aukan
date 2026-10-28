<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

// Consulta unificada con indicador 'es_movil'
$efemeridesHoy = Yii::$app->db->createCommand("
    -- 1. EFEMÉRIDES FIJAS (Sin regla asignada)
    SELECT 
        e.idefemeride,
        e.fecha,
        e.titulo,
        e.descripcion,
        e.activo,
        f.foto,
        0 AS es_movil
    FROM efemerides e
    LEFT JOIN efemerides_fotos f ON f.idefemeride = e.idefemeride AND f.orden = 1
    WHERE DAY(e.fecha) = DAY(CURRENT_DATE())
      AND MONTH(e.fecha) = MONTH(CURRENT_DATE())
      AND e.activo = 1
      AND NOT EXISTS (
          SELECT 1 FROM efemerides_reglas r WHERE r.idefemeride = e.idefemeride
      )

    UNION ALL

    -- 2. EFEMÉRIDES MÓVILES (Con regla matemática que coincide con hoy)
    SELECT 
        e.idefemeride,
        e.fecha,
        e.titulo,
        e.descripcion,
        e.activo,
        f.foto,
        1 AS es_movil
    FROM efemerides e
    JOIN efemerides_reglas r ON e.idefemeride = r.idefemeride
    LEFT JOIN efemerides_fotos f ON f.idefemeride = e.idefemeride AND f.orden = 1
    WHERE MONTH(e.fecha) = MONTH(CURRENT_DATE())
      AND e.activo = 1
      AND (WEEKDAY(CURRENT_DATE()) + 1) % 7 = r.dia_semana
      AND FLOOR((DAY(CURRENT_DATE()) - 1) / 7) + 1 = r.num_semana
")->queryAll();

$totalEfemerides = count($efemeridesHoy);

// Función para obtener las fotos de la galería de una efeméride específica
function getFotosEfemeride($idefemeride)
{
    return (new \yii\db\Query())
        ->from('efemerides_fotos')
        ->where(['idefemeride' => $idefemeride])
        ->orderBy(['orden' => SORT_ASC])
        ->all();
}

// Función para formatear fecha completa
function formatearFechaEfemeride($fecha, $esMovil)
{
    $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    if ($esMovil) {
        $timestampHoy = time();
        $nombreDia = $diasSemana[date('w', $timestampHoy)];
        $dia = date('d', $timestampHoy);
        $nombreMes = $meses[date('n', $timestampHoy) - 1];
        $anio = date('Y', $timestampHoy);
        return "$nombreDia $dia de $nombreMes de $anio";
    } else {
        $timestamp = strtotime($fecha);
        $nombreDia = $diasSemana[date('w', $timestamp)];
        $dia = date('d', $timestamp);
        $nombreMes = $meses[date('n', $timestamp) - 1];
        $anio = date('Y', $timestamp);
        return "$nombreDia $dia de $nombreMes de $anio";
    }
}
?>

<style>
    .efem-carousel-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .efem-slide {
        display: none;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        justify-content: space-between;
        animation: efemFade 0.4s ease-in-out forwards;
    }

    .efem-slide.active {
        display: flex;
    }

    @keyframes efemFade {
        from {
            opacity: 0;
            transform: scale(0.98);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .efem-fecha-badge {
        font-size: 0.65rem;
        font-weight: 700;
        color: #ffb03b;
        text-shadow: 0 0 6px rgba(255, 176, 59, 0.4);
        letter-spacing: 0.3px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .efem-titulo {
        font-size: 0.85rem;
        font-weight: 800;
        color: #00ffcc;
        text-shadow: 0 0 6px rgba(0, 255, 204, 0.4);
        text-transform: uppercase;
        line-height: 1.15;
        max-height: 2.3em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 4px;
        width: 100%;
    }

    .efem-foto-box {
        width: 100%;
        height: 130px;
        border: 1px solid rgba(0, 191, 255, 0.4);
        border-radius: 6px;
        overflow: hidden;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.4);
        margin-bottom: 4px;
    }

    .efem-foto-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: transparent;
    }

    .efem-controls {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        padding-top: 4px;
        border-top: 1px solid rgba(0, 191, 255, 0.2);
    }

    .efem-btn-nav {
        background: rgba(0, 191, 255, 0.1);
        border: 1px solid #00bfff;
        color: #00ffcc;
        border-radius: 4px;
        padding: 3px 8px;
        cursor: pointer;
        font-size: 11px;
        transition: all 0.2s ease;
    }

    .efem-btn-nav:hover {
        background: rgba(0, 255, 204, 0.25);
        box-shadow: 0 0 8px #00ffcc;
        color: #fff;
    }

    .efem-btn-vermas {
        flex: 1;
        background: rgba(0, 255, 204, 0.1);
        border: 1px solid #00ffcc;
        color: #00ffcc;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 4px 6px;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .efem-btn-vermas:hover {
        background: #00ffcc;
        color: #000;
        box-shadow: 0 0 10px rgba(0, 255, 204, 0.8);
        text-decoration: none;
    }

    .efem-empty-box {
        text-align: center;
        color: #88a0b8;
        font-size: 12px;
        padding: 35px 10px;
    }

    /* Estilos del modal estilo NERV / Táctico */
    .eva-modal-dialog {
        max-width: 600px;
        margin: 50px auto;
    }

    .eva-modal-content-custom {
        background-color: #0c0c0e;
        border: 1px solid #ffb03b;
        box-shadow: 0 0 25px rgba(255, 176, 59, 0.6);
        border-radius: 3px;
        padding: 10px;
        color: #e0f0ff;
        font-family: 'JetBrains Mono', 'Segoe UI', monospace;
    }

    .eva-modal-header {
        border-bottom: 1px solid rgba(255, 176, 59, 0.3);
        padding-bottom: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .eva-modal-title {
        font-size: 11px;
        font-weight: 800;
        color: #2aebff;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 0 0 8px rgba(42, 235, 255, 0.6);
    }
</style>

<?php if ($totalEfemerides > 0): ?>
    <div class="efem-carousel-container" id="efemCarousel">
        <?php foreach ($efemeridesHoy as $index => $efem):
            $fotosEfemeride = getFotosEfemeride($efem['idefemeride']);
        ?>
            <div class="efem-slide <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
                <div>
                    <div class="efem-fecha-badge">
                        <?= formatearFechaEfemeride($efem['fecha'], $efem['es_movil']) ?>
                    </div>
                    <div class="efem-titulo" title="<?= Html::encode($efem['titulo']) ?>">
                        <?= Html::encode($efem['titulo']) ?>
                    </div>
                </div>

                <div class="efem-foto-box">
                    <img src="<?= !empty($efem['foto']) ? Url::base(true) . '/img/efemerides/' . $efem['foto'] : Url::base(true) . '/img/tarjetas/efemerides.jpg' ?>"
                        alt="<?= Html::encode($efem['titulo']) ?>">
                </div>

                <div class="efem-controls">
                    <button type="button" class="efem-btn-nav" onclick="cambiarEfemeride(-1)" title="Anterior">◀</button>

                    <!-- Botón que activa el modal local sin pasar por controlador -->
                    <button type="button" class="efem-btn-vermas" data-toggle="modal" data-target="#modal-efem-<?= $efem['idefemeride'] ?>">
                        Ver Más
                    </button>

                    <button type="button" class="efem-btn-nav" onclick="cambiarEfemeride(1)" title="Siguiente">▶</button>
                </div>
            </div>

            <!-- MODAL LOCAL PARA CADA EFEMÉRIDE -->
            <div class="modal fade modal-efem" id="modal-efem-<?= $efem['idefemeride'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="eva-modal-dialog modal-dialog" role="document">
                    <div class="eva-modal-content-custom">
                        <div class="eva-hazard-stripes" style="margin-top: 0;"></div>

                        <div class="efem-fecha-badge" style="font-size: 1.1rem;">
                            <?= formatearFechaEfemeride($efem['fecha'], $efem['es_movil']) ?>
                        </div>

                        <div class="eva-modal-header">
                            <span class="eva-modal-title"><?= Html::encode($efem['titulo']) ?></span>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ff3333; text-shadow: none; opacity: 0.8;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>



                        <div style="padding: 10px 0;">

                            <?php if (!empty($fotosEfemeride)): ?>
                                <div id="carouselModal-<?= $efem['idefemeride'] ?>" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner" role="listbox">
                                        <?php foreach ($fotosEfemeride as $fIndex => $foto): ?>
                                            <div class="item <?= $fIndex === 0 ? 'active' : '' ?>">
                                                <img src="<?= Url::base(true) . '/img/efemerides/' . $foto['foto'] ?>" class="img-responsive center-block" style="max-height: 300px; object-fit: contain; width: 100%;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (count($fotosEfemeride) > 1): ?>
                                        <a class="left carousel-control" href="#carouselModal-<?= $efem['idefemeride'] ?>" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        </a>
                                        <a class="right carousel-control" href="#carouselModal-<?= $efem['idefemeride'] ?>" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <p style="font-size: 1.3rem; color: #e0f0ff; line-height: 1.4; text-align: center; margin-top: 15px;">
                                <?= nl2br(Html::encode($efem['descripcion'])) ?>
                            </p>

                        </div>

                        <div class="eva-hazard-stripes"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="eva-empty-box" style="text-align: center; color: #88a0b8; font-size: 12px; padding: 35px 10px;">
        <span>📅</span>
        <p>No hay efemérides registradas para el día de hoy.</p>
    </div>
<?php endif; ?>

<?php if ($totalEfemerides > 1): ?>
    <script>
        (function() {
            let currentIndex = 0;
            const totalSlides = <?= $totalEfemerides ?>;
            let timer = null;

            function showSlide(index) {
                const slides = document.querySelectorAll('#efemCarousel .efem-slide');
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
                currentIndex = index;
            }

            window.cambiarEfemeride = function(direction) {
                clearInterval(timer);
                let nextIndex = currentIndex + direction;
                if (nextIndex >= totalSlides) nextIndex = 0;
                if (nextIndex < 0) nextIndex = totalSlides - 1;
                showSlide(nextIndex);
                startTimer();
            };

            function startTimer() {
                timer = setInterval(() => {
                    let nextIndex = (currentIndex + 1) % totalSlides;
                    showSlide(nextIndex);
                }, 4000);
            }

            document.addEventListener('DOMContentLoaded', startTimer);
        })();
    </script>
<?php endif; ?>

<?php
$js = "$('.modal-efem').on('show.bs.modal', function () {
    $(this).appendTo('body');
});";
$this->registerJs($js);
?>