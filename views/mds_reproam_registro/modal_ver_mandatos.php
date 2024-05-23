<div class="modal fade" id="modalVerMandatos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ver Mandatos de <?php echo $model->nombre ?> </h4>
      </div>
      <div class="modal-body" style="padding-right: 50px">
        <ul id="listaMandatos" name="listaMandatos">
          <input type='hidden' id='tieneMandatos' name='tieneMandatos' value='<?php echo ($mandatos) ? 1 : 0 ?>'>
          <?php
          if ($mandatos) {
            foreach ($mandatos as $mandato) { ?>
              <li style="text-align:justify"> <b>Periodo:</b> Desde <?php echo $mandato['fecha_desde'] ?> - Hasta: <?php echo $mandato['fecha_hasta'] ?> <br> <b>Carácter: </b> <?php echo (($mandato['titular']) == '1') ? 'Titular' : 'Suplente'  ?> <br> <b> Observaciones: </b> <?php echo $mandato['observaciones'] ?></li>
              <hr>
            <?php }
          } else { ?>
            <p id="parrafoMandatos" name="parrafoMandatos">El Registro <?php echo $model->nombre ?> (#<?php echo $model->idregistro ?>) aún no posee ningún mandato. </p>
          <?php } ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>