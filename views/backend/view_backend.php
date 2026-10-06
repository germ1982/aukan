<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\BackendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

\yii\web\JqueryAsset::register($this);

$registrosBackend = isset($dataProvider) ? $dataProvider->getModels() : [];
$esTarjeta = $esTarjetaHome ?? false; // Detecta si viene desde la tarjeta del Home
?>

<!-- Estilos y Scripts -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    <?= include 'view_backend.css'; ?>
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php if (!$esTarjeta): ?>
    <!-- SPLASH SCREEN (Sólo si NO es tarjeta) -->
    <div id="intro-splash-overlay">
        <div class="intro-card-frame">
            <video autoplay loop muted playsinline class="intro-media">
                <source src="<?= Yii::getAlias('@web') ?>/img/aukan_home_banner.mp4" type="video/mp4">
            </video>
        </div>
    </div>

    <!-- VIDEO DE FONDO (Sólo si NO es tarjeta) -->
    <div class="video-background-container">
        <iframe
            src="https://www.youtube.com/embed/eWRDwD6cVe8?autoplay=1&mute=1&loop=1&playlist=eWRDwD6cVe8&controls=0&showinfo=0&rel=0&modestbranding=1&vq=hd2160&start=8&end=1224"
            frameborder="0"
            allow="autoplay; encrypted-media"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen>
        </iframe>
    </div>
<?php endif; ?>

<div class="<?= $esTarjeta ? 'tarjeta-backend-container' : 'overlay-div-dashboard' ?>">

    <?php if (!$esTarjeta): ?>
        <!-- HEADER (Sólo si es la vista completa) -->
        <header class="dashboard-header-bar">
            <div class="header-content-wrapper">
                <?= Html::img('@web/img/logo_aukan.png', ['alt' => 'Logo', 'class' => 'header-logo']) ?>
                <div class="header-titles-container">
                    <h1 class="header-title">Módulo Backend - Servicios</h1>
                    <div id="header-breadcrumb" class="header-breadcrumb">Listado General</div>
                </div>
            </div>
        </header>
    <?php endif; ?>

    <!-- BARRA GLOBAL DE CONTROLES (BUSCADOR SELECT2) -->
    <div class="dashboard-toolbar-container">
        <div id="toolbar-actions" class="toolbar-content-wrapper">
            <div class="search-select-container">
                <select id="buscador-backend" style="width: 100%;">
                    <option value="">🔍 Buscar endpoint...</option>
                    <?php foreach ($registrosBackend as $item): ?>
                        <option value="<?= $item->idbackend ?>">
                            <?= Html::encode($item->nombre) ?> (<?= Html::encode($item->endpoint) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button id="btn-reset-search" class="btn-volver-custom" style="display: none;">Ver Todos</button>
        </div>
    </div>

    <!-- BODY CON TARJETAS -->
    <main class="dashboard-body-container" id="contenedor-tarjetas" style="max-height: 400px; overflow-y: auto;">
        <?php if (!empty($registrosBackend)): ?>
            <?php foreach ($registrosBackend as $backend): ?>
                <div class="data-card card-backend-item" data-id="<?= $backend->idbackend ?>">

                    <h3 class="box-title"><?= Html::encode($backend->nombre) ?></h3>

                    <div class="backend-details" style="margin: 10px 0;">
                        <p style="margin: 5px 0; font-size: 0.85rem; color: #e0f0ff;">
                            <strong style="color: #00bfff;">Endpoint:</strong><br>
                            <code style="background: rgba(0,0,0,0.4); padding: 2px 6px; border-radius: 4px; color: #00ffcc;">
                                <?= Html::a(
                                    Html::encode($backend->endpoint) . ' <i class="fas fa-external-link-alt"></i>',
                                    $backend->endpoint,
                                    [
                                        'target' => '_blank',
                                        'rel' => 'noopener noreferrer',
                                        'style' => 'text-decoration: none; color: #00ffcc;',
                                    ]
                                ) ?>
                            </code>
                        </p>

                        <p style="margin: 8px 0; font-size: 0.8rem; color: #a2c8ff; max-height: 80px; overflow-y: auto;">
                            <strong>Descripción:</strong><br>
                            <?= Html::encode($backend->descripcion) ?>
                        </p>
                    </div>

                    <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(0, 191, 255, 0.3); padding-top: 8px;">
                        <span class="badge-estado <?= $backend->estado == 1 ? 'activo' : 'inactivo' ?>">
                            <?= $backend->estado == 1 ? '● Activo' : '○ Inactivo' ?>
                        </span>

                        <div>
                            <?= Html::a('Editar', ['backend/update', 'id' => $backend->idbackend], ['class' => 'btn-volver-custom', 'style' => 'text-decoration: none; font-size: 0.75rem;', 'target' => '_blank']) ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #a2c8ff; text-align: center;">No se encontraron registros de Backend.</p>
        <?php endif; ?>
    </main>

</div>

<script>
    <?= include 'view_backend.js'; ?>
</script>