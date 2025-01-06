<?php

use app\models\Configuracion;
use yii\widgets\DetailView;

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}

$marca = Configuracion::findOne($model->idmarca);

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

<div class="vehiculo-oficial-view">

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <?= campo('Vehiculo', "$model->idvehiculo") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('marca', "$marca->descripcion") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('modelo', "$model->modelo") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('color', "$model->color") ?>
                </div>
                

            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= campo('anio', "$model->anio") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Dominio', "$model->dominio") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Poliza', "$model->poliza") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('VTO', "$model->VTO") ?>
                </div>
            </div>
            
        </div>
    </div>


  <!--   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idvehiculo',
            'dominio',
            'poliza',
            'VTO',
            'idmarca',
            'modelo',
            'color',
            'anio',            
        ],
    ]) ?> -->

</div>
