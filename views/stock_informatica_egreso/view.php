<?php

use app\models\Empleado;
use app\models\Persona;
use app\models\StockInformaticaEgresoDetalle;


function campo($titulo, $contenido)
{
      echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}

function crear_label_articulo($articulo, $cantidad, $unidad)
{
    echo "<div class='col-xs-12' style='width: 100%'><ul><li><span style='font-size: 11px;'>$articulo: <b>$cantidad $unidad</b></span></li></ul></div>";
}
?>

<style>
      #base64image {
            display: block;
            border: ridge 1px;
            padding: 8px;
            border-color: #E6E6E6;
            max-width: 100%;
      }

      .campo {
            padding: 6px 12px;
            font-size: 12px;
            line-height: 1.42857143;
            color: #555555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
      }
</style>

<div class="row">
    <div class="col-md-3">
            <?= campo('Numero de Egreso', "$model->idegreso") ?>
      </div>
      <div class="col-md-3">
            <?= campo('Fecha', "$model->fecha") ?>
      </div>
      <div class="col-md-4">
            <?= campo('Solicitante', Persona::get_persona_ayn($model->idpersona_solicitante)) ?>
      </div>
</div>
 
<div class="row">
    <div class="col-md-4">
        <?= campo('Autorizacion', Empleado::get_empleado($model->idempleado_autorizacion)->descripcion) ?>
    </div>
    <div class="col-md-4">
        <?= campo('Despachante', Empleado::get_empleado($model->idempleado_despacha)->descripcion) ?>
    </div>
    <div class="col-md-4">
        <?= campo('Recibe', Persona::get_persona_ayn($model->idpersona_recibe)) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= campo('Observacion', "$model->observacion") ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

    </div>
</div>
