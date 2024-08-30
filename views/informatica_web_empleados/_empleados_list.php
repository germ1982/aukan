<strong>Orden</strong>
<?php foreach ($empleados as $e): ?>
    <div style="font-size: 10px;">
        <?= $e->orden ?>:  <?= $e->descripcion ?>
    </div>
<?php endforeach; ?>
