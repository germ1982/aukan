<?php

use app\models\ConstantesGlobales;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var array $movimientos
 */

// Mapeo de IDs de empleados a nombres si los pasás resueltos, 
// o los legibles del array si se procesan en la vista.
?>

<?php if (empty($movimientos)): ?>
    <div class="alert alert-warning text-center" style="margin: 0;">
        No hay movimientos registrados para esta incidencia. Debe agregar al menos uno.
    </div>
<?php else: ?>
    <table class="table table-bordered table-striped text-center" style="margin: 0; background: #fff;">
        <thead>
            <tr class="active">
                <th class="text-center" style="width: 12%;">Fecha / Hora</th>
                <th class="text-center" style="width: 20%;">Tipo Movimiento</th>
                <th>Descripción / Observaciones</th>
                <th class="text-center" style="width: 8%;">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $index => $mov): ?>
                <?php 
                    // Obtener la etiqueta del movimiento desde las constantes
                    $tipoId = $mov['idmovimiento_nombre'] ?? null;
                    $nombreMovimiento = isset(ConstantesGlobales::ESTADOS_INCIDENCIAS[$tipoId]) 
                        ? ConstantesGlobales::ESTADOS_INCIDENCIAS[$tipoId]['movimiento'] 
                        : 'Sin Especificar';
                ?>
                <tr>
                    <td><?= Html::encode($mov['fecha']) ?> <?= Html::encode($mov['hora']) ?></td>
                    <td><strong><?= Html::encode($nombreMovimiento) ?></strong></td>
                    <td class="text-left"><?= nl2br(Html::encode($mov['descripcion'])) ?></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" onclick="eliminar_movimiento(<?= $index ?>)" title="Quitar movimiento">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>