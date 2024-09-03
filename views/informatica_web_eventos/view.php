<?php

use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\helpers\Html;
use yii\widgets\DetailView;


function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}

$dispositivo = OrganismoDispositivo::get_dispositivo($model->iddispositivo)

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
<div class="informatica-web-eventos-view">

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-3">
                <?= campo('Fecha', date_format(date_create($model->fecha), 'd/m/Y')) ?>
                </div>
                <div class="col-md-9">
                    <?= campo('Titulo', "$model->titulo") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <?= campo('Sector', "$dispositivo->descripcion") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Activo', $model->activo ? 'Si' : 'No') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <?= campo('Descripcion', "$model->descripcion") ?>
                </div>
                
                
            </div>
        </div>
        <div class="col-md-6">
            <!-- <?php //Html::img('img/empleados-fotos/' . ->foto, ['id' => 'base64image']); ?> -->
        </div>
    </div>


</div>