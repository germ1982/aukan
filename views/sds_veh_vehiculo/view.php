<div class="sds-veh-vehiculo-view container" style="padding:5px 10px;">
    <div class="col-md-9" style="border: 1px solid #dcf; border-radius: 3px; padding:10px; text-align:center;">
        <div class="row">
            <div class="col-md-4">
                Año: <b><?=$model->anio?></b>
            </div>
            <div class="col-md-4">
                Estado: <b><?=$model->estado_descripcion?></b>
            </div>
            <div class="col-md-4">
                Alquilado: <?=$model->alquilado? '<b class="text-success">SI</b>':'<b class="text-danger">NO</b>'?>
            </div>
        </div>
        <?php if($model->detalle!=null):?>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-left" style="border-top: 1px solid #ccc; margin-top:10px;">
                    <b>Detalle:</b>
                     <br><?=$model->detalle?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
