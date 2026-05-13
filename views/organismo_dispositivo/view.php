<?php

use app\models\OrganismoDispositivo;
use app\models\Organismo;
/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDispositivo */
function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}


$organismo = Organismo::findOne($model->idorganismo)

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

<div class="dispositivo-view">

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                    <?= campo('iddispositivo', "$model->iddispositivo") ?>
                </div>
                <div class="col-md-5">
                    <?= campo('descripcion', "$model->descripcion") ?>
                </div>
                <div class="col-md-5">
                    <?= campo('idorganismo', "$organismo->descripcion") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= campo('es_ofical', $model->es_oficial ? 'Si' : 'No') ?>
                </div>
                <div class="col-md-3">
                    <?= campo('es_organismo', "$model->es_organismo" ? 'Si' : 'No') ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Activo', "$model->activo" ? 'Si':'No') ?>
                </div>
                <div class="col-md-3">
                    <?= campo('idcapaitem', "$model->idcapaitem" ? '0': '1') ?>
                </div>
            </div>
            <div class="row">               
                <div class="col-md-4">
                    aca poner plano de oficina  y mapa de direccion del edificio
                </div>
                <div class="col-md-4">
                    <?= campo('Alias', OrganismoDispositivo::findOne("$model->iddispositivo")->alias) ?>
                </div>
                
                <div class="col-md-4">
                    <?= campo('Telefono', OrganismoDispositivo::findOne("$model->iddispositivo")->telefono) ?>
                </div>
            </div>
        </div>
    </div>
</div>              

