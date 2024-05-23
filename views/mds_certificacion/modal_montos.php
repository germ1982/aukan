<style>
    <?php include_once('components/time-line.css'); ?>
</style>
<div class="container" style="width: 100%;">
    <ul class="timeline">
        <?php $next = false ?>
        <?php foreach ($model as $monto) {  ?>
            <?php echo  $next === false ? '<li class="timeline-inverted">' : '<li>' ?>
            <div class="<?php echo $monto['deleted_at'] ? 'timeline-badge warning' : ' timeline-badge success' ?>"><i class="glyphicon glyphicon-check"></i></div>
            <div class="timeline-panel">
                <div class="timeline-heading">
                    <h4 class="timeline-title">$<?= $monto['monto']; ?></h4>
                </div>
                <div class="timeline-body">
                    <p><i class="glyphicon glyphicon-time"></i> <b>Desde</b> <?= $monto['created_at']; ?> <?php echo $monto['deleted_at'] ? ', <b>hasta</b> ' . $monto['deleted_at'] : ' <b>hasta</b> la actualidad'; ?></p>
                    <p>Cargado por <?= $monto['user']; ?></p>
                </div>
            </div>
            </li>
            <?php $next === false ? $next = true : $next = false ?>
        <?php } ?>
    </ul>
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="Cerrar">Cerrar</button>
</div>