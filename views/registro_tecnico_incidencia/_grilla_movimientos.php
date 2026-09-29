<?php

use app\models\ConstantesGlobales;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var array $movimientos
 */
?>

<style>
    <?php include __DIR__ . '/_grilla_movimientos.css'; ?>
</style>

<?php if (empty($movimientos)): ?>
    <div class="alert alert-warning text-center" style="margin: 0; font-size: 12px;">
        No hay movimientos registrados para esta incidencia. Debe agregar al menos uno.
    </div>
<?php else: ?>
    <table class="table table-bordered table-striped text-center table-movimientos">
        <thead>
            <tr>
                <th class="text-center" style="width: 13%;">Fecha / Hora</th>
                <th class="text-center" style="width: 20%;">Tipo Movimiento</th>
                <th>Descripción / Observaciones</th>
                <th class="text-center" style="width: 15%;">Asistentes</th>
                <th class="text-center" style="width: 8%;">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $index => $mov): ?>
                <?php 
                    $tipoId = $mov['idmovimiento_nombre'] ?? null;
                    $nombreMovimiento = isset(ConstantesGlobales::ESTADOS_INCIDENCIAS[$tipoId]) 
                        ? ConstantesGlobales::ESTADOS_INCIDENCIAS[$tipoId]['movimiento'] 
                        : 'Sin Especificar';

                    $asistentesIds = $mov['asistentes'] ?? [];
                ?>
                <tr>
                    <td><?= Html::encode($mov['fecha']) ?> <?= Html::encode($mov['hora']) ?></td>
                    <td><strong><?= Html::encode($nombreMovimiento) ?></strong></td>
                    <td class="text-left"><?= nl2br(Html::encode($mov['descripcion'])) ?></td>
                    
                    <td>
                        <?php if (empty($asistentesIds)): ?>
                            <span class="text-muted">-</span>
                        <?php else: ?>
                            <div style="display:flex; gap:3px; flex-wrap:wrap; justify-content:center;">
                                <?php foreach ($asistentesIds as $idTecnico): ?>
                                    <?php 
                                        $sql = "SELECT e.idempleado, e.foto, CONCAT(p.apellido, ' ', p.nombre) as nombre
                                                FROM empleado e
                                                JOIN personas p ON p.idpersona = e.idpersona
                                                WHERE e.idempleado = :id";
                                        $tec = Yii::$app->db->createCommand($sql, [':id' => (int)$idTecnico])->queryOne();

                                        if ($tec): 
                                            $fotoNombre = !empty($tec['foto']) ? $tec['foto'] : 'default.jpg';
                                            $src = Url::base(true) . '/img/empleados-fotos/' . $fotoNombre;
                                            $urlView = Url::to(['empleado/view', 'id' => $tec['idempleado']]);
                                    ?>
                                        <a href="<?= $urlView ?>" role="modal-remote" title="<?= Html::encode($tec['nombre']) ?>">
                                            <img src="<?= $src ?>" class="imagen-avatar-grilla" style="width:20px; height:20px; border-radius:50%; object-fit:cover; border: 1px solid #ccc; cursor:pointer;">
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <!-- Botón Editar Linea -->
                        <button type="button" class="btn btn-accion-grilla btn-editar" onclick="editar_movimiento(<?= $index ?>)" title="Editar movimiento">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </button>
                        <!-- Botón Eliminar Linea -->
                        <button type="button" class="btn btn-accion-grilla btn-eliminar" onclick="eliminar_movimiento(<?= $index ?>)" title="Quitar movimiento">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>