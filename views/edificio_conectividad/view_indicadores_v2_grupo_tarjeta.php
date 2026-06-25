<?php
// C:\xampp_datafam\htdocs\datafam\views\edificio_conectividad\view_indicadores_v2_grupo_tarjeta.php

/** @var yii\web\View $this */

use yii\helpers\Url;

/** @var array $edificio */
/** @var int $index */
/** @var string $accentColor */

// Determinamos el color del estado de la conexión para el badge técnico
$estadoLower = strtolower($edificio['estado']);
$colorEstado = 'var(--color-desconocido)';
if (strpos($estadoLower, 'bueno') !== false || strpos($estadoLower, 'excelente') !== false) {
    $colorEstado = 'var(--color-bueno)';
} elseif (strpos($estadoLower, 'malo') !== false || strpos($estadoLower, 'caido') !== false) {
    $colorEstado = 'var(--color-caido)';
} elseif (strpos($estadoLower, 'regular') !== false) {
    $colorEstado = 'var(--color-regular)';
}
?>

<div class="col-md-4 col-sm-6 col-xs-12 grupo-tarjeta-container">
    <div class="react-card edificio-card" style="border-top: 3px solid <?= $accentColor ?>;">

        <div class="card-component-header" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">

            <div style="flex-grow: 1; padding-right: 15px; min-height: 40px; display: flex; align-items: center;">
                <h3 class="component-title" style="margin: 0; padding: 0; line-height: 1.2; font-size: 14px;">
                    <?= htmlspecialchars($edificio['descripcion_gestion']) ?>
                </h3>
            </div>

            <div style="margin-top: -10px; text-align: right; flex-shrink: 0; display: flex; align-items: center;">
                <span class="label <?= $edificio['activo'] ? 'label-success' : 'label-danger' ?>"
                    style="margin: 0; padding: 4px; display: inline-block; line-height: 1; font-size:8px">
                    <?= $edificio['activo'] ? 'EDIFICIO ACTIVO' : 'INACTIVO' ?>
                </span>
            </div>

        </div>

        <div class="edificio-card-body">
            <div class="edificio-direccion-block">
                <?php if (!empty($edificio['geolocalizacion'])): ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode(trim($edificio['geolocalizacion'])) ?>"
                        target="_blank"
                        class="edificio-direccion-link"
                        style="text-decoration: none; display: flex; gap: 6px; align-items: center;">
                        <span class="glyphicon glyphicon-map-marker" style="color: <?= $accentColor ?>;"></span>
                        <span class="edificio-direccion-text" style="border-bottom: 1px dashed <?= $accentColor ?>22;">
                            <?= htmlspecialchars($edificio['direccion'] ?: 'Ver en mapa') ?>
                        </span>
                    </a>
                <?php else: ?>
                    <span class="glyphicon glyphicon-map-marker" style="color: var(--text-muted); integrity: 0.5;"></span>
                    <span class="edificio-direccion-text" style="color: var(--text-muted);">
                        <?= htmlspecialchars($edificio['direccion'] ?: 'Sin dirección registrada') ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="edificio-tech-row">
                <div>
                    <span class="tech-label">Infraestructura: </span>
                    <strong class="tech-value"><?= htmlspecialchars($edificio['infraestructura']) ?></strong>
                </div>
                <div>
                    <span class="tech-label">Conexión: </span>
                    <strong class="tech-value"><?= htmlspecialchars($edificio['tipo_conexion']) ?></strong>
                </div>

                <div>
                    <span class="tech-label">Servicio: </span>
                    <strong class="tech-value"><?= htmlspecialchars($edificio['servicio']) ?></strong>
                    <?php if ((int)$edificio['velocidad_en_mb'] > 0): ?>
                        <span class="tech-value-speed">/ <?= $edificio['velocidad_en_mb'] ?> MB</span>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">/ ---</span>
                    <?php endif; ?>
                </div>

                <div>
                    <span class="tech-label">Estado: </span>
                    <span class="status-bullet" style="background-color: <?= $colorEstado ?>; box-shadow: 0 0 8px <?= $colorEstado ?>;"></span>
                    <span class="status-text" style="color: <?= $colorEstado ?>;">
                        <?= htmlspecialchars($edificio['estado']) ?>
                    </span>
                </div>
            </div>


        </div>
        <div class="edificio-card-footer" style="display: flex; justify-content: flex-end; gap: 8px; padding-top: 5px; border-top: 1px solid var(--border-card); margin-top: -5px;">

            <a href="#"
                class="btn btn-xs btn-info"
                onclick="abrirModalDashboard(event, '<?= Url::to(['edificio_conectividad/view', 'id' => $edificio['idconectividad'],'dash' =>true]) ?>')"
                title="VER"
                style="font-family: 'JetBrains Mono', monospace; font-weight: bold; ">
                <span class="glyphicon glyphicon-eye-open"></span> 
            </a>

            <a href="#"
                class="btn btn-xs btn-warning"
                onclick="abrirModalDashboard(event, '<?= Url::to(['edificio_conectividad/update', 'id' => $edificio['idconectividad'],'dash' =>true]) ?>')"
                title="EDITAR"
                style="font-family: 'JetBrains Mono', monospace; font-weight: bold;  color: #000;">
                <span class="glyphicon glyphicon-pencil"></span> 
            </a>

        </div>

    </div>
</div>