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

$existePersona = \app\models\Persona::findOne(['documento' => $model->dni]);
$existeNoHomo = \app\models\PersonasNoHomologadas::findOne(['documento' => $model->dni]);
$persona_aux = $existePersona ? $existePersona : $existeNoHomo;

// Evitar errores si falta algún dato
$documento_tipo = Configuracion::findOne($persona_aux->documento_tipo)?->descripcion ?? 'No disponible';
$nacionalidad = Configuracion::findOne($persona_aux->nacionalidad)?->descripcion ?? 'No disponible';
$genero = Configuracion::findOne($persona_aux->genero)?->descripcion ?? 'No disponible';

$tipoRecepcion = Configuracion::findOne($model->id_tipo_recepcion)?->descripcion ?? 'No disponible';
$responsable = Empleado::get_empleado($model->id_responsable_derivacion)?->descripcion ?? 'Sin asignar';
$dispositivo = OrganismoDispositivo::get_dispositivo($model->id_dispositivo_derivacion)?->descripcion ?? 'Sin asignar';
?>

<style>
    .campo {
        background-color: #f9f9f9;
        padding: 7px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        color: #333;
    }
</style>

<div class="registro-recepcion-view">

    <div class="row">
        <div class="col-md-2">
            <?= campo('documento_tipo', $documento_tipo) ?>
        </div>
        <div class="col-md-2">
            <?= campo('DNI', $model->dni) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Apellido', $persona_aux?->apellido ?? 'No disponible') ?>
        </div>
        <div class="col-md-5">
            <?= campo('Nombre', $persona_aux?->nombre ?? 'No disponible') ?>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-2">
            <?= campo('Fecha', $model->fechaFormateada) ?>
        </div>
        <div class="col-md-1">
            <?= campo('Hora', $model->horaFormateada) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Tipo de Recepción', $tipoRecepcion) ?>
        </div>
        <div class="col-md-2">
            <?= campo('Acceso', EdificioAcceso::get_acceso_descripcion($model->acceso)) ?>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12">
            <?= campo('Motivo', $model->motivo) ?>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-3">
            <?= campo('Responsable Derivación', $responsable) ?>
        </div>
        <div class="col-md-9">
            <?= campo('Dispositivo Derivación', $dispositivo) ?>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12">
            <?= campo('Observación', $model->observacion ?: 'Sin observaciones') ?>
        </div>
    </div>

    <br>
</div>