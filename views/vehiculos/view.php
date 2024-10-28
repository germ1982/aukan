<?php

use app\models\Configuracion;
use yii\widgets\DetailView;
use app\models\Persona;
use app\models\OrganismoDispositivo;
use app\models\Vehiculos;

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}


/* @var $this yii\web\View */
/* @var $model app\models\Vehiculos */
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
                <div class="col-md-4">
                    <?= campo('idvehiculo', "$model->idvehiculo") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('Empleado', "$model->idempleado") ?>
                </div>                
                <div class="col-md-4">
                    <?= campo('dominio', "$model->dominio") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <!-- <?= campo('idmarca', "$model->idmarca") ?> --> 
                    <?= campo('idmarca', Configuracion::findOne($model->idmarca)->descripcion) ?>                  
                </div>
                <div class="col-md-3">
                    <?= campo('modelo', "$model->modelo") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('color', "$model->color") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('vehiculo_oficial', "$model->vehiculo_oficial") ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--                         
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'idvehiculo',
                        'idempleado',
                        'idpersona',
                        'dominio',
                        'idmarca',
                        'modelo',
                        'color',
                        'vehiculo_oficial',
                    ],
                ]) ?>

            </div> -->