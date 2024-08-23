<strong>Orden</strong>
<?php foreach ($hijos as $hijo): ?>
    <div>
        <?= $hijo->orden ?>:  <?= $hijo->title ?>
    </div>
<?php endforeach; ?>
