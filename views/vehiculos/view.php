<?php

use app\models\Configuracion;
use yii\widgets\DetailView;
use app\models\Persona;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Vehiculos;

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}
$marca = Configuracion::findOne($model->idmarca);
$vehiculo = "$marca->descripcion $model->modelo $model->anio $model->color";


$empleado = Empleado::findOne($model->idempleado);
$sector = OrganismoDispositivo::get_dispositivo($empleado->iddispositivo);
$persona = Persona::findOne($empleado->idpersona);
$aux_persona =  "$persona->apellido $persona->nombre - $sector->descripcion";


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
        padding: 4px 8px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<div class="vehiculos-view">

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
                    <?= campo('Vehiculo', $vehiculo) ?>
                </div>
                <div class="col-md-2">
                    <?= campo('dominio', "$model->dominio") ?>
                </div>
                <div class="col-md-2">
                    <?= campo('Vehiculo Oficial', $model->vehiculo_oficial ? "SI" : "NO") ?>
                </div>
            </div>
            <div class="row">

                <div class="col-md-9">
                    <?= campo('Modelo', "$aux_persona") ?>
                </div>
                <div class="col-md-3">
                    <?=campo('Telefono',$empleado->telefono)?>
                </div>

            </div>
        </div>
    </div>
</div>