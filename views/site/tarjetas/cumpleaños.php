<?php

use app\models\Persona;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;

// Obtener día y mes actuales
$hoy = date('m-d');

// 1. Obtener cumpleañeros de hoy (incluye idempleado directamente para el link)
$cumpleaneros = Persona::find()
    ->select(['personas.idpersona', 'personas.nombre', 'personas.apellido', 'personas.fecha_nacimiento', 'empleado.idempleado'])
    ->innerJoin('empleado', 'empleado.idpersona = personas.idpersona')
    ->where(new Expression("DATE_FORMAT(personas.fecha_nacimiento, '%m-%d') = :hoy"), [':hoy' => $hoy])
    ->andWhere(['empleado.activo' => 1])
    ->asArray()
    ->all();

// 2. Si no hay cumpleañeros hoy, traer los próximos 10
$proximos_cumpleanos = [];
if (empty($cumpleaneros)) {
    $proximos_cumpleanos = Persona::find()
        ->select(['personas.idpersona', 'personas.nombre', 'personas.apellido', 'personas.fecha_nacimiento', 'empleado.idempleado'])
        ->innerJoin('empleado', 'empleado.idpersona = personas.idpersona')
        ->where(['empleado.activo' => 1])
        ->orderBy(new Expression("
            CASE 
                WHEN DATE_FORMAT(fecha_nacimiento, '%m-%d') >= :hoy THEN 0 
                ELSE 1 
            END, 
            DATE_FORMAT(fecha_nacimiento, '%m-%d')
        "), [':hoy' => $hoy])
        ->limit(10)
        ->asArray()
        ->all();
}

// Función auxiliar para formatear fecha de cumpleaños
function formatearFechaCumple($fecha)
{
    $timestamp = strtotime(date('Y') . '-' . date('m-d', strtotime($fecha)));
    $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $dia = date('d', $timestamp);
    $mes = $meses[date('n', $timestamp) - 1];
    return "$dia $mes";
}
?>

<style>
    /* Estilos Cyberpunk para el listado de cumpleaños */
    .cumple-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 4px;
    }

    .cumple-header-status {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #9ca3af;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cumple-header-status.hoy {
        color: #00ffcc;
        text-shadow: 0 0 8px rgba(0, 255, 204, 0.4);
    }

    .cumple-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 10px;
        background-color: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .cumple-item:hover {
        background-color: rgba(0, 191, 255, 0.08);
        border-color: rgba(0, 191, 255, 0.3);
    }

    .cumple-link {
        color: #f3f4f6;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .cumple-link:hover {
        color: #00ffcc;
        text-shadow: 0 0 6px rgba(0, 255, 204, 0.5);
    }

    .cumple-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 600;
        color: #00ffff;
        background: rgba(0, 255, 255, 0.1);
        border: 1px solid rgba(0, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .cumple-badge.hoy {
        color: #a3e116;
        background: rgba(163, 225, 22, 0.15);
        border-color: rgba(163, 225, 22, 0.4);
        box-shadow: 0 0 6px rgba(163, 225, 22, 0.3);
    }
</style>

<div class="cumple-container">
    <?php if (!empty($cumpleaneros)) : ?>
        <div class="cumple-header-status hoy">
            <span>🎂</span> Cumpleañeros de Hoy
        </div>
        <div class="react-list-group">
            <?php foreach ($cumpleaneros as $p) : ?>
                <div class="cumple-item">🎉
                    <?= Html::a(
                        Html::encode($p['apellido'] . ' ' . $p['nombre']),
                        Url::to(['/empleado/cumpleanos-empleado', 'id' => $p['idempleado']]),
                        [
                            'class' => 'cumple-link',
                            'role' => 'modal-remote',
                            'title' => 'Ver Ficha Cyberpunk',
                            'data-toggle' => 'tooltip',
                        ]
                    ) ?>

                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="cumple-header-status">
            <span>📅</span> Próximos Cumpleaños
        </div>
        <div class="react-list-group">
            <?php foreach ($proximos_cumpleanos as $p) : ?>
                <div class="cumple-item">
                    <?= Html::a(
                        Html::encode($p['apellido'] . ' ' . $p['nombre']),
                        Url::to(['/empleado/view', 'id' => $p['idempleado']]),
                        [
                            'class' => 'cumple-link',
                            'role' => 'modal-remote',
                            'title' => 'Ver Ficha de Empleado',
                            'data-toggle' => 'tooltip',
                        ]
                    ) ?>
                    <span class="cumple-badge">
                        <?= formatearFechaCumple($p['fecha_nacimiento']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>