<?php

use app\models\InformaticaControlInsumosEventos;
use yii\helpers\Html;

/* @var $this yii\web\View */

// 1. Evaluación de estados en la base de datos
$hayPrestamo = InformaticaControlInsumosEventos::find()
    ->where(['estado' => InformaticaControlInsumosEventos::ESTADO_EN_PRESTAMO])
    ->exists();

$haySolicitado = InformaticaControlInsumosEventos::find()
    ->where(['estado' => InformaticaControlInsumosEventos::ESTADO_SOLICITADO])
    ->exists();

if ($hayPrestamo) {
    $claseAlerta = 'eva-alert-badge-red';
    $estado = "PRESTADOS";
} elseif ($haySolicitado) {
    $claseAlerta = 'eva-alert-badge-yellow';
    $estado = "SOLICITADOS";
} else {
    $claseAlerta = 'eva-alert-badge-green';
    $estado = "EN CASA";
}

$alertaInsumosPrestados = [
    'estado' => $estado,
    'badgeClass' => $claseAlerta,
    'mensaje' => 'INSUMOS EVENTOS'
];

// Consulta de tóneres (<= 5)
$tonersCriticosCount = (new \yii\db\Query())
    ->from('view_stock_informatica_articulos_cantidades s')
    ->where(['like', 's.descripcion', 'toner'])
    ->andWhere(['<=', 's.disponible', 5])
    ->count();

if ($tonersCriticosCount > 0) {
    $alertaToners = [
        'estado' => 'BAJO',
        'badgeClass' => 'eva-alert-badge-yellow',
        'mensaje' => 'STOCK DE TÓNERS',
        'boxClass' => 'warning'
    ];
} else {
    $alertaToners = [
        'estado' => 'BUENO',
        'badgeClass' => 'eva-alert-badge-green',
        'mensaje' => 'STOCK DE TÓNERS',
        'boxClass' => 'ok'
    ];
}

// Detalle completo para la grilla del modal
$toneresDetalle = (new \yii\db\Query())
    ->select(['descripcion', 'disponible'])
    ->from('view_stock_informatica_articulos_cantidades')
    ->where(['like', 'descripcion', 'toner'])
    ->orderBy(['disponible' => SORT_ASC, 'descripcion' => SORT_ASC])
    ->all();

?>
<style>
    .eva-card-container {
        position: relative;
        color: #ff3333;
        font-family: 'JetBrains Mono', 'Segoe UI', monospace;
        box-sizing: border-box;
        padding: 0px;
        margin-top: -5px;
    }

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

    .eva-alert-box {
        background: rgba(255, 42, 42, 0.05);
        border: 1px solid rgba(255, 42, 42, 0.3);
        border-left: 3px solid #ff2a2a;
        padding: 5px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 2px;
        margin-bottom: 4px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .eva-alert-box.warning {
        border-color: rgba(255, 176, 59, 0.4);
        border-left-color: #ffb03b;
        background: rgba(255, 176, 59, 0.05);
    }

    .eva-alert-box.ok {
        border-color: rgba(72, 255, 0, 0.3);
        border-left-color: #48ff00;
        background: rgba(72, 255, 0, 0.05);
        cursor: default;
    }

    .eva-alert-box:hover:not(.ok) {
        background: rgba(255, 176, 59, 0.15);
        border-color: #ffb03b;
        box-shadow: 0 0 8px rgba(255, 176, 59, 0.4);
    }

    .eva-alert-text {
        font-size: 9px;
        font-weight: 700;
        color: #ff8888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .eva-alert-box.warning .eva-alert-text {
        color: #ffcc66;
    }

    .eva-alert-box.ok .eva-alert-text {
        color: #a3e116;
    }

    @keyframes evaBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* Estilos NERV para el Modal Flotante */
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

    .eva-modal-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    .eva-modal-table th {
        font-size: 9px;
        color: #ffb03b;
        border-bottom: 1px solid rgba(255, 176, 59, 0.3);
        padding: 6px 4px;
        text-align: left;
        text-transform: uppercase;
    }

    .eva-modal-table td {
        font-size: 10px;
        padding: 6px 4px;
        border-bottom: 1px solid rgba(255, 42, 42, 0.15);
        color: #ffcc66;
    }

    /* Scrollbar personalizado estilo NERV */
    .eva-modal-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .eva-modal-scroll::-webkit-scrollbar-track {
        background: rgba(12, 12, 14, 0.8);
        border-left: 1px solid rgba(255, 176, 59, 0.2);
    }

    .eva-modal-scroll::-webkit-scrollbar-thumb {
        background: #ffb03b;
        border-radius: 1px;
        box-shadow: 0 0 6px rgba(255, 176, 59, 0.6);
    }

    .eva-modal-scroll::-webkit-scrollbar-thumb:hover {
        background: #ff2a2a;
        box-shadow: 0 0 6px rgba(255, 42, 42, 0.8);
    }
</style>

<div class="eva-card-container">
    <div class="eva-hazard-stripes"></div>

    <!-- LÍNEA 1: INSUMOS PRESTADOS -->
    <div class="eva-alert-box warning">
        <span class="eva-alert-text"><?= Html::encode($alertaInsumosPrestados['mensaje']) ?></span>
        <span class="<?= $alertaInsumosPrestados['badgeClass'] ?>"><?= $alertaInsumosPrestados['estado'] ?></span>
    </div>

    <!-- LÍNEA 2: TÓNERES (Dispara el modal local) -->
    <div class="eva-alert-box <?= $alertaToners['boxClass'] ?>" data-toggle="modal" data-target="#modal-toneres-local">
        <span class="eva-alert-text"><?= Html::encode($alertaToners['mensaje']) ?></span>
        <span class="<?= $alertaToners['badgeClass'] ?>"><?= $alertaToners['estado'] ?></span>
    </div>

    <!-- LÍNEAS EXTRAS -->
    <div class="eva-alert-box ok">
        <span class="eva-alert-text">Ambiente Laboral</span>
        <span class="eva-alert-badge-green">OK</span>
    </div>

    <div class="eva-hazard-stripes"></div>
</div>

<!-- MODAL LOCAL FLOTANTE -->
<div class="modal fade" id="modal-toneres-local" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="eva-modal-dialog modal-dialog" role="document">
        <div class="eva-modal-content-custom">

            <div class="eva-hazard-stripes" style="margin-top: 0;"></div>

            <div class="eva-modal-header">
                <span class="eva-modal-title">ESTADO TÁCTICO DE INVENTARIO: TÓNERES</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ff3333; text-shadow: none; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="eva-hazard-stripes"></div>

            <div class="eva-modal-scroll" style="max-height: 350px; overflow-y: auto;">
                <table class="eva-modal-table">
                    <thead>
                        <tr>
                            <th>Modelo de Artículo</th>
                            <th style="text-align: center;">Stock Disponible</th>
                            <th style="text-align: center;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($toneresDetalle)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #888;">SIN REGISTROS DE TÓNERES EN EL SISTEMA</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($toneresDetalle as $t): ?>
                                <?php
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

            <div class="eva-hazard-stripes"></div>

        </div>
    </div>
</div>

<?php
// Script de JS para sacar el modal del contenedor de la tarjeta y moverlo al body al abrir
$js = "$('#modal-toneres-local').on('show.bs.modal', function () {
    $(this).appendTo('body');
});";
$this->registerJs($js);
?>