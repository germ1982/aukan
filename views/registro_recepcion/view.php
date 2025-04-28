<?php

use app\models\Edificio;
use app\models\EdificioAcceso;
use yii\widgets\DetailView;

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));
$model->hora = $model->isNewRecord ? date('H:i') : date('H:i', strtotime($model->hora));



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
                <div class="col-md-4">
                    <?= campo('id_registro_recepcion', "$model->id_registro_recepcion") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('fecha', "$model->fecha") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('hora', "$model->hora") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('dni', "$model->dni") ?>
                </div>
            </div>
        </div>

        <div class=" col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <?= campo('motivo', "$model->motivo") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('acceso', EdificioAcceso::get_acceso_descripcion($model->acceso)) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('id_dispositivo_derivacion', "$model->id_dispositivo_derivacion") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('id_responsable_derivacion', "$model->id_responsable_derivacion") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('id_tipo_recepcion', "$model->id_tipo_recepcion") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('observacion', "$model->observacion") ?>
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