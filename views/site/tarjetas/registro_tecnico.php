<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

// Consultas para Registros Técnicos
$regPendientes = (new \yii\db\Query())
    ->from('registro_tecnico')
    ->where(['estado' => 0])
    ->count();

$regAsistidos = (new \yii\db\Query())
    ->from('registro_tecnico')
    ->where(['estado' => 1])
    ->count();

// Lógica de condición para Registros
if ($regPendientes == 0) {
    $regCondicion = 'OK';
    $regBadgeClass = 'eva-alert-badge-green';
} elseif ($regPendientes >= 5) {
    $regCondicion = 'CRITIC';
    $regBadgeClass = 'eva-alert-badge-red';
} else {
    $regCondicion = 'ALERT';
    $regBadgeClass = 'eva-alert-badge-yellow';
}


// Consultas para Incidencias Técnicas
$incPendientes = (new \yii\db\Query())
    ->from('registro_tecnico_incidencia')
    ->where(['or', ['idestado' => 0], ['idestado' => 1]])
    ->count();

$incAtencion = (new \yii\db\Query())
    ->from('registro_tecnico_incidencia')
    ->where(['idestado' => 3])
    ->count();

// Lógica de condición para Incidencias
if ($incPendientes == 0) {
    $incCondicion = 'OK';
    $incBadgeClass = 'eva-alert-badge-green';
} elseif ($incPendientes >= 5) {
    $incCondicion = 'CRITIC';
    $incBadgeClass = 'eva-alert-badge-red';
} else {
    $incCondicion = 'ALERT';
    $incBadgeClass = 'eva-alert-badge-yellow';
}


// Consulta unificada de técnicos en asistencia (Registros + Incidencias sin duplicados)
$tecnicosEnOperacion = Yii::$app->db->createCommand("
    SELECT idempleado, foto, nombre FROM (
        SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
        FROM registro_tecnico_incidencia_movimiento_asistencia a
        JOIN empleado e ON a.idtecnico = e.idempleado
        JOIN personas p ON p.idpersona = e.idpersona
        JOIN registro_tecnico_incidencia_movimiento m on m.idmovimiento = a.idmovimiento
        JOIN registro_tecnico_incidencia i on i.idincidencia = m.idincidencia
        WHERE i.idestado != 4
        GROUP BY a.idtecnico

        UNION

        SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
        FROM registro_tecnico_asistencia a
        JOIN empleado e ON a.idtecnico = e.idempleado
        JOIN personas p ON p.idpersona = e.idpersona
        JOIN registro_tecnico r on r.idregistro = a.idregistro
        WHERE r.estado = 1
        GROUP BY a.idtecnico
    ) as tecnicos_activos
    GROUP BY idempleado, foto, nombre
    ORDER BY nombre
")->queryAll();

?>
<style>
    /* Estilos NERV / EVA HUD Táctico */
    .eva-card-container {
        position: relative;
        color: #ff3333;
        font-family: 'JetBrains Mono', 'Segoe UI', monospace;
        box-sizing: border-box;
        padding: 0px;
        margin-top: -5px;
    }

    /* Franja de peligro NERV con animación de movimiento */
    .eva-hazard-stripes {
        height: 5px;
        background: repeating-linear-gradient(-45deg,
                #ffb03b,
                #ffb03b 8px,
                #000 8px,
                #000 16px);
        background-size: 200% 100%;
        border: 1px solid #ffb03b;
        margin: 6px 0;
        animation: evaStripesMove 4s linear infinite;
    }

    @keyframes evaStripesMove {
        0% { background-position: 0 0; }
        100% { background-position: 50px 0; }
    }

    /* Fila contenedora principal para cada bloque (Registros / Incidencias) */
    .eva-block-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0px;
    }

    /* Lado Izquierdo: Título del Bloque y Condición */
    .eva-block-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 95px;
    }

    .eva-title {
        font-size: 11px;
        font-weight: 800;
        color: #2aebff;
        text-shadow: 0 0 8px rgba(255, 42, 42, 0.6);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .eva-condition-box {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .eva-condition-label {
        font-size: 8px;
        color: #ffb03b;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .eva-alert-badge-red {
        background: #ff2a2a;
        color: #000;
        font-size: 7px;
        font-weight: 900;
        padding: 1px 5px;
        border-radius: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        animation: evaBlink 1.5s infinite;
        line-height: 1.2;
    }

    .eva-alert-badge-green {
        background: #48ff00;
        color: #000;
        font-size: 7px;
        font-weight: 900;
        padding: 1px 5px;
        border-radius: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        animation: evaBlink 1.5s infinite;
        line-height: 1.2;
    }

    .eva-alert-badge-yellow {
        background: #eeff00;
        color: #000;
        font-size: 7px;
        font-weight: 900;
        padding: 1px 5px;
        border-radius: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        animation: evaBlink 1.5s infinite;
        line-height: 1.2;
    }

    /* Lado Derecho: Cajas de métricas */
    .eva-block-metrics {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .eva-metric-box {
        background: rgba(255, 42, 42, 0.05);
        border: 1px solid rgba(255, 42, 42, 0.3);
        border-left: 3px solid #ff2a2a;
        padding: 3px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 2px;
    }

    .eva-metric-box.warning {
        border-color: rgba(255, 176, 59, 0.4);
        border-left-color: #ffb03b;
        background: rgba(255, 176, 59, 0.05);
    }

    .eva-metric-label {
        font-size: 9px;
        font-weight: 700;
        color: #ff8888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .eva-metric-box.warning .eva-metric-label {
        color: #ffcc66;
    }

    .eva-metric-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 15px;
        font-weight: 900;
        color: #ff2a2a;
        text-shadow: 0 0 8px rgba(255, 42, 42, 0.8);
        line-height: 1;
    }

    .eva-metric-box.warning .eva-metric-value {
        color: #ffb03b;
        text-shadow: 0 0 8px rgba(255, 176, 59, 0.8);
    }

    /* Subtítulo Sección Técnicos */
    .eva-section-title {
        font-size: 9px;
        font-weight: 800;
        color: #ffb03b;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 8px 0 6px 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .eva-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255, 176, 59, 0.3);
    }

    /* Grilla Hexagonal */
    .eva-hex-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        justify-content: flex-start;
        align-items: center;
        padding: 2px 0 4px 0;
        overflow: visible;
    }

    .eva-hex-item {
        position: relative;
        width: 30px;
        height: 32px;
        cursor: pointer;
        transition: transform 0.2s ease, filter 0.2s ease;
    }

    .eva-hex-item:hover {
        transform: scale(1.2);
        z-index: 10;
        filter: drop-shadow(0 0 6px #ff2a2a);
    }

    .eva-hex-border {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #ff2a2a, #ffb03b);
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        padding: 1.5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .eva-hex-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background-color: #0f172a;
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    }

    .eva-hex-status-dot {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #a3e116;
        box-shadow: 0 0 4px #a3e116;
        border: 1px solid #000;
        z-index: 2;
    }

    @keyframes evaBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* Estilo Cyberpunk para los links de título */
    .eva-title-link {
        color: #2aebff;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .eva-title-link:hover {
        color: #00ffcc;
        text-shadow: 0 0 10px rgba(0, 255, 204, 0.8);
        text-decoration: none;
    }
</style>

<div class="eva-card-container">
    <div class="eva-hazard-stripes"></div>

    <!-- BLOQUE 1: REGISTROS -->
    <div class="eva-block-row">
        <div class="eva-block-info">
            <span class="eva-title">
                <?= Html::a('REGISTROS', Url::to(['/registro_tecnico']), ['class' => 'eva-title-link']) ?>
            </span>
            <div class="eva-condition-box">
                <span class="eva-condition-label">CONDITION:</span>
                <span class="<?= $regBadgeClass ?>"><?= $regCondicion ?></span>
            </div>
        </div>
        <div class="eva-block-metrics">
            <div class="eva-metric-box">
                <div class="eva-metric-label">Pendientes</div>
                <div class="eva-metric-value"><?= sprintf('%02d', $regPendientes) ?></div>
            </div>
            <div class="eva-metric-box warning">
                <div class="eva-metric-label">Asistidos</div>
                <div class="eva-metric-value"><?= sprintf('%02d', $regAsistidos) ?></div>
            </div>
        </div>
    </div>

    <div class="eva-hazard-stripes"></div>

    <!-- BLOQUE 2: INCIDENCIAS -->
    <div class="eva-block-row">
        <div class="eva-block-info">
            <span class="eva-title">
                <?= Html::a('INCIDENCIAS', Url::to(['/registro_tecnico_incidencia']), ['class' => 'eva-title-link']) ?>
            </span>
            <div class="eva-condition-box">
                <span class="eva-condition-label">CONDITION:</span>
                <span class="<?= $incBadgeClass ?>"><?= $incCondicion ?></span>
            </div>
        </div>
        <div class="eva-block-metrics">
            <div class="eva-metric-box">
                <div class="eva-metric-label">Pendientes</div>
                <div class="eva-metric-value"><?= sprintf('%02d', $incPendientes) ?></div>
            </div>
            <div class="eva-metric-box warning">
                <div class="eva-metric-label">Asistidos</div>
                <div class="eva-metric-value"><?= sprintf('%02d', $incAtencion) ?></div>
            </div>
        </div>
    </div>

    <div class="eva-hazard-stripes"></div>

    <!-- SECCIÓN TÉCNICOS OPERATIVOS -->
    <div class="eva-section-title">
        <span>OPERATIVOS EN ASISTENCIA</span>
    </div>

    <div class="eva-hex-grid">
        <?php foreach ($tecnicosEnOperacion as $tec): ?>
            <div class="eva-hex-item" data-toggle="tooltip" data-placement="top" title="<?= Html::encode($tec['nombre']) ?>">
                <div class="eva-hex-border">
                    <img src="<?= !empty($tec['foto']) ? \yii\helpers\Url::base(true) . '/img/empleados-fotos/' . $tec['foto'] 
                    : \yii\helpers\Url::base(true) . '/img/empleados-fotos/default.jpg' ?>" class="eva-hex-img" alt="<?= Html::encode($tec['nombre']) ?>">
                </div>
                <span class="eva-hex-status-dot"></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>