<style>
    <?= include 'tarjeta_base.css'; ?>
</style>

<div class="data-card">
    <h2 class="box-title"><?= $titulo ?></h2>
    <div class="panel_contenido">
        <div class="contenedor">
            <?php include "$archivo_contenido_tarjeta" ?>
        </div>
    </div>
</div>