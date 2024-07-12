<?php
use app\models\Persona;
use yii\db\Expression;

// Obtén el día y mes actuales
$hoy = date('m-d');

// Encuentra a las personas que cumplen años hoy
$cumpleañeros = Persona::find()
    ->where(new Expression("DATE_FORMAT(fecha_nacimiento, '%m-%d') = :hoy"))
    ->addParams([':hoy' => $hoy])
    ->all()
?>


<h1>Cumpleañeros de Hoy</h1>
    <?php if (!empty($cumpleañeros)): ?>
        <ul>
            <?php foreach ($cumpleañeros as $persona): ?>
                <li>
                    <?= htmlspecialchars($persona['nombre'], ENT_QUOTES, 'UTF-8') ?> 
                    (<?= htmlspecialchars($persona['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay cumpleañeros hoy.</p>
    <?php endif; ?>