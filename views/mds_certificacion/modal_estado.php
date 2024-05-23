<style>
    <?php include_once('components/time-line.css'); ?>
</style>

<div class="container" style="width: 100%;">
    <ul class="timeline">
        <?php $next = false ?>
        <?php foreach ($model as $estado) {  ?>
            <?php echo  $next === false ? '<li class="timeline-inverted">' : '<li>' ?>
            <div class="<?php echo $estado['fecha_fin'] ? 'timeline-badge warning' : ' timeline-badge success' ?>"><i class="glyphicon glyphicon-check"></i></div>
            <div class="timeline-panel">
                <div class="timeline-heading">
                    <h4 class="timeline-title"><?= $estado['estadoDescripcion']; ?></h4>
                </div>
                <div class="timeline-body">
                    <p><i class="glyphicon glyphicon-time"></i> <b>Desde</b> <?= $estado['fecha_inicio']; ?> <?php echo $estado['fecha_fin'] ? ', <b>hasta</b> ' . $estado['fecha_fin'] : ' <b>hasta</b> la actualidad'; ?></p>
                    <?php if ($estado['observaciones']) { ?>
                        <p>Observaciones: <?= $estado['observaciones']; ?></p>
                    <?php } ?>
                    <?php if ($estado['idestado'] != $listadoPosiblesEstados['ESTADO_PENDIENTE']) { ?>
                        <p>
                            <?= ($estado['idestado'] == $listadoPosiblesEstados['ESTADO_BAJA']) ? 'Baja realizada por' : 'Actualizado por' ?>
                            <?= $estado['usuarioNombre']; ?>
                        </p>
                        <?php if($estado['idestado'] == $listadoPosiblesEstados['ESTADO_BAJA']){?> 
                            <p>Fecha de baja: <?= $estado['fecha']; ?></p>
                        <?php }  ?>
                        <?php if (array_key_exists('direccionDescripcion', $estado)) { ?>
                            <p>Perteneciente a <?= $estado['direccionDescripcion']; ?></p>
                            <p><b><?= $estado['nivelDescripcion']; ?></b></p>
                        <?php } ?>

                    <?php } else { ?>
                        <p>Solicitud ingresada por <?= $estado['usuarioNombre']; ?></p>
                    <?php } ?>
                </div>
            </div>
            </li>
            <?php $next === false ? $next = true : $next = false ?>
        <?php } ?>
    </ul>
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
</div>