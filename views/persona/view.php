<?php

use app\models\Configuracion;
use app\models\Persona;
use yii\helpers\Html;
use yii\widgets\DetailView;

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

<div class="row">
    <div class="col-md-3">
        <?= campo('ID', "$model->idpersona") ?>
    </div>
    <div class="col-md-9">
        <?= campo('Nombre', "$model->apellido $model->nombre") ?>
    </div>

</div>

<div class="row">
    <div class="col-md-3">
        <?= campo('Documento', Configuracion::findOne($model->documento_tipo)->descripcion . ": $model->documento") ?>
    </div>
    <div class="col-md-3">
        <?= campo('Nacimiento', date_format(date_create($model->fecha_nacimiento), 'd/m/Y')) ?>
    </div>

    <div class="col-md-3">
        <?= campo('Nacionalidad', Configuracion::findOne($model->nacionalidad)->descripcion) ?>
    </div>

    <div class="col-md-3">
        <?= campo('Genero', Configuracion::findOne($model->genero)->descripcion) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= campo('Direccion', Persona::get_direccion($model->idpersona)) ?>
    </div>
</div>