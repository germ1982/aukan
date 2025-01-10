<?php

use app\models\Empleado;
use app\models\VehiculoOficial;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}



$vehiculos = ArrayHelper::map(
    VehiculoOficial::find()->all(),
    'idvehiculo', // El valor de la clave (ID del vehículo)
    function($model) {
        // Obtener la descripción de la marca a través de la relación
        $marca = $model->marca ? $model->marca->descripcion : 'Marca no disponible'; // 'marca' es la relación en el modelo

        // Concatenar la información del vehículo (marca, modelo, dominio, año)
        return $marca . ' - ' . $model->modelo . ' - ' . $model->dominio . ' - ' . $model->anio;
    }
);

$vehiculo = VehiculoOficial::getVehiculoOficial($model->idvehiculo);
$choferInformacion = Empleado::get_empleado($model->chofer)->descripcion;


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




<div class="movim-vehi-oficial-view">

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?= campo('idmovimiento', "$model->idmovimiento") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('idvehiculo', "$vehiculo") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('chofer', "$choferInfo") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= campo('salida', "$model->salida") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('regreso', "$model->regreso") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('finalidad_viaje', "$model->finalidad_viaje") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('fecha', "$model->fecha") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= campo('lugar', "$model->lugar") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('hora', "$model->hora") ?>
                </div>
                <div class="col-md-12">
                    <?= campo('kilometraje', "$model->kilometraje") ?>
                </div>
            </div>
        </div>

    </div>
    <!-- <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idmovimiento',
            'idvehiculo',            
            'chofer',
            'salida',
            'regreso',
            'finalidad_viaje',
            'fecha',
            'lugar',
            'hora',
            'kilometraje',
        ],
    ]) ?>
 -->
</div>
