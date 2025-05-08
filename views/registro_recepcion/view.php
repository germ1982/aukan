<?php

use app\models\Configuracion;
use app\models\Edificio;
use app\models\EdificioAcceso;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use yii\widgets\DetailView;




/* @var $this yii\web\View */
/* @var $model app\models\RegistroRecepcion */

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
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
        padding: 4px 8px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<div class="registro-recepcion-view">

    <div class="row">
        <div class=" col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <?= campo('id_registro_recepcion', "$model->id_registro_recepcion") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('fecha', $model->fechaFormateada) ?>
                </div>
                <div class="col-md-3">
                    <?= campo('hora', $model->horaFormateada) ?>
                </div>
                <div class="col-md-3">
                    <?= campo('dni', "$model->dni") ?>
                </div>
            </div>
        </div>

        <div class=" col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <?= campo('id_responsable_derivacion', Empleado::get_empleado($model->id_responsable_derivacion)->descripcion) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('acceso', EdificioAcceso::get_acceso_descripcion($model->acceso)) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('id_dispositivo_derivacion', OrganismoDispositivo::get_dispositivo($model->id_dispositivo_derivacion)->descripcion) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('motivo', "$model->motivo") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('id_tipo_recepcion', Configuracion::findOne($model->id_tipo_recepcion)->descripcion) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('observacion', $model->observacion ?: 'Sin observaciones') ?>

                </div>
            </div>
        </div>
    </div>

    <!-- <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_registro_recepcion',
                    'fecha',
                    'hora',
                    'dni',
                    'motivo:ntext',
                    'acceso',
                    'id_dispositivo_derivacion',
                    'id_responsable_derivacion',
                    'id_tipo_recepcion',
                    'observacion:ntext',
                ],
            ]) ?> -->
</div>