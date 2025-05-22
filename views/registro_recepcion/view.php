<?php

use app\models\Configuracion;
use app\models\EdificioAcceso;
use app\models\Empleado;
use app\models\OrganismoDispositivo;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroRecepcion */

function campo($titulo, $contenido)
{
    echo "
    <div class='form-group'>
        <label><strong>$titulo</strong></label>
        <div class='form-control campo'>$contenido</div>
    </div>";
}

?>

<style>
    .campo {
        background-color: #f9f9f9;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        color: #333;
    }
</style>

<div class="registro-recepcion-view">

    <div class="row">

        <div class="col-md-3">
            <?= campo('DNI', $model->dni) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Apellido', $model->persona ? $model->persona->apellido : 'No disponible') ?>
        </div>
        <div class="col-md-3">
            <?= campo('Nombre', $model->persona ? $model->persona->nombre : 'No disponible') ?>
        </div>
        <div class="col-md-3">
            <?= campo('Motivo', $model->motivo) ?>
        </div>
    </div>

    <div class="row">


        <div class="col-md-2">
            <?= campo('Fecha', $model->fechaFormateada) ?>
        </div>
        <div class="col-md-2">
            <?= campo('Hora', $model->horaFormateada) ?>
        </div>
        <div class="col-md-2">
            <?= campo('Acceso', EdificioAcceso::get_acceso_descripcion($model->acceso)) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Responsable Derivación', Empleado::get_empleado($model->id_responsable_derivacion)->descripcion) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Tipo de Recepción', Configuracion::findOne($model->id_tipo_recepcion)->descripcion) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= campo('Dispositivo Derivación', OrganismoDispositivo::get_dispositivo($model->id_dispositivo_derivacion)->descripcion) ?>
        </div>


    </div>

    <div class="row">

        <div class="col-md-12">
            <?= campo('Observación', $model->observacion ?: 'Sin observaciones') ?>
        </div>
    </div>

</div>