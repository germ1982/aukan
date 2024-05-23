<style>
    <?php include_once('components/time-line.css'); ?>
</style>
<div class="container" style="width: 100%;">
    <ul class="timeline">
        <?php $next = false ?>
        <?php foreach ($model as $responsable) {  ?>
            <?php echo  $next === false ? '<li class="timeline-inverted">' : '<li>' ?>
            <div class="<?php echo $responsable['deleted_at'] ? 'timeline-badge warning' : ' timeline-badge success' ?>"><i class="glyphicon glyphicon-check"></i></div>
            <div class="timeline-panel">
                <div class="timeline-heading">
                    <h4 class="timeline-title"><?= $responsable['nombre_apellido']; ?></h4>
                </div>
                <div class="timeline-body">
                    <p><i class="glyphicon glyphicon-time"></i> <b>Desde</b> <?= $responsable['created_at']; ?> <?php echo $responsable['deleted_at'] ? ', <b>hasta</b> ' . $responsable['deleted_at'] : ' <b>hasta</b> la actualidad'; ?></p>
                    <p>DNI: <?= $responsable['dni']  ? $responsable['dni'] : ''  ?></p>
                    <?php if ($responsable['curador_legal'] !== null) { ?>
                        <p>¿Es Curador/Tutor Legal? <?= $responsable['curador_legal'] === null ? '' : ($responsable['curador_legal'] == 1 ? 'Sí' : 'No')  ?></p>
                        <p>Tipo de responsable de cobro/Tutor especial: <?= $responsable['tipoResponsable'] ?></p>
                        <p>¿Debe presentar la rendición? <?= $responsable['rendicion'] === null ? '' : ($responsable['rendicion'] == 1 ? 'Sí' : 'No')  ?></p>
                    <?php } ?>
                    <p><?= $responsable['idparentesco'] ?  ($responsable['idparentesco'] == $PARENTESCO_OTRO_OPTION ?  'Parentesco: <b>' . $responsable['parentesco_otro'] . '</b>' : 'Parentesco: <b>' . $responsable['parentesco'] . '</b>') : "" ?></p>
                    <p><?= $responsable['motivo_cambio'] ? 'Motivo del cambio: ' . $responsable['motivo_cambio'] : '' ?></p>
                </div>
            </div>
            </li>
            <?php $next === false ? $next = true : $next = false ?>
        <?php } ?>
    </ul>
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" title="Cerrar">Cerrar</button>
</div>