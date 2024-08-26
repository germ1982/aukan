<strong>Orden</strong>
<?php foreach ($empleados as $e): ?>
    <div>
        <?= $e->orden ?>:  <?= $e->title ?>
    </div>
<?php endforeach; ?>
