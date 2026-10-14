<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

// Consultar todos los tóneres y su disponibilidad
$toneres = (new \yii\db\Query())
    ->select(['descripcion', 'disponible'])
    ->from('view_stock_informatica_articulos_cantidades')
    ->where(['like', 'descripcion', 'toner'])
    ->orderBy(['disponible' => SORT_ASC, 'descripcion' => SORT_ASC])
    ->all();
?>
<style>
    .eva-modal-content {
        font-family: 'JetBrains Mono', 'Segoe UI', monospace;
        color: #ff3333;
    }
    .eva-modal-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }
    .eva-modal-table th {
        font-size: 9px;
        color: #2aebff;
        border-bottom: 1px solid rgba(42, 235, 255, 0.3);
        padding: 4px;
        text-align: left;
        text-transform: uppercase;
    }
    .eva-modal-table td {
        font-size: 10px;
        padding: 6px 4px;
        border-bottom: 1px solid rgba(255, 176, 59, 0.15);
        color: #ffcc66;
    }

    
</style>

<div class="eva-modal-content">
    <div style="font-size: 11px; font-weight: 800; color: #2aebff; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px;">
        ESTADO TÁCTICO DE INVENTARIO: TÓNERES
    </div>
    
    <table class="eva-modal-table">
        <thead>
            <tr>
                <th>Modelo de Tóner</th>
                <th style="text-align: center;">Stock Disponible</th>
                <th style="text-align: center;">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($toneres)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #888;">SIN REGISTROS DE TÓNERES EN EL SISTEMA</td>
                </tr>
            <?php else: ?>
                <?php foreach ($toneres as $t): ?>
                    <?php 
                        // Lógica de colores según stock
                        if ($t['disponible'] == 0) {
                            $estado = 'CRITIC';
                            $badgeClass = 'eva-alert-badge-red';
                            $colorStock = '#ff2a2a';
                        } elseif ($t['disponible'] <= 5) {
                            $estado = 'ALERT';
                            $badgeClass = 'eva-alert-badge-yellow';
                            $colorStock = '#eeff00';
                        } else {
                            $estado = 'OK';
                            $badgeClass = 'eva-alert-badge-green';
                            $colorStock = '#48ff00';
                        }
                    ?>
                    <tr>
                        <td><?= Html::encode($t['descripcion']) ?></td>
                        <td style="text-align: center; font-weight: bold; color: <?= $colorStock ?>;">
                            <?= sprintf('%02d', $t['disponible']) ?> u.
                        </td>
                        <td style="text-align: center;">
                            <span class="<?= $badgeClass ?>"><?= $estado ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>