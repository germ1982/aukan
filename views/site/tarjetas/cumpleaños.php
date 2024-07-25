<?php

use app\models\Persona;
use yii\db\Expression;
use yii\db\Query;

// Obtén el día y mes actuales
$hoy = date('m-d');

$agrupados_por_dia = [];

// Encuentra a las personas que cumplen años hoy
$cumpleañeros = Persona::find()
    ->where(new Expression("DATE_FORMAT(fecha_nacimiento, '%m-%d') = :hoy"))
    ->andWhere(['in', 'idpersona', (new Query())->select('idpersona')->from('empleado')])
    ->addParams([':hoy' => $hoy])
    ->all();


$proximos_cumpleaños = Persona::find()
    ->where(new Expression("DATE_FORMAT(fecha_nacimiento, '%m-%d') >= :hoy"))
    ->andWhere(['in', 'idpersona', (new Query())->select('idpersona')->from('empleado')])
    ->addParams([':hoy' => $hoy])
    ->orderBy(new Expression("DATE_FORMAT(fecha_nacimiento, '%m-%d')"))
    ->limit(10) // Limitar a los próximos 10 cumpleaños, puedes ajustar según tus necesidades
    ->all();


function formatFecha($fecha)
{
    $timestamp = strtotime(date('Y') . '-' . date('m-d', strtotime($fecha)));
    $dia = date('d', $timestamp);
    $nombreDia = getNombreDia($timestamp);
    $nombreMes = getNombreMes($timestamp);
    return "$nombreDia $dia de $nombreMes";
}

function getNombreDia($timestamp)
{
    $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    return $dias[date('w', $timestamp)];
}

// Función para obtener el nombre del mes en español
function getNombreMes($timestamp)
{
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    return $meses[date('n', $timestamp) - 1];
}
?>

<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin: 5px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        display: inline-block;
        width: 200px;
        text-align: center;
    }

    .icon {
        font-size: 25px;
        color: #f39c12;
    }

    .name {
        font-size: 18px;
        font-weight: bold;
margin-top: 5px;
    }

    .date {
        font-size: 14px;
        color: #555;
    }

    .contenedor_cumple {
padding-bottom: 10px;
        height: 300px;
        overflow: auto;
    }
</style>
<div class="contenedor_cumple">
    <?php if (!empty($cumpleañeros)) : ?>
        <h3>Cumpleañeros de Hoy</h3>
        <div class="cards-container">
            <?php foreach ($cumpleañeros as $persona) : ?>
                <div class="card">
                <div class="icon">🎂</div> <!-- Icono de cumpleaños -->
                    <div class="name">
                        <?= htmlspecialchars($persona['nombre'], ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars($persona['apellido'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <h3>Hoy no hay Cumpleaños</h3>
        <h5>proximos cumpleaños</h5>
        <div class="cards-container">
            <?php foreach ($proximos_cumpleaños as $persona) : ?>
                <div class="card">
                <div class="icon">🎂</div> <!-- Icono de cumpleaños -->
                    <div class="name">
                        <?= htmlspecialchars($persona['nombre'], ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars($persona['apellido'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="date">
                        <?= htmlspecialchars(formatFecha($persona['fecha_nacimiento']), ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


</div>