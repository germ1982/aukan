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
$model_empleado = Empleado::findOne($model->idempleado);
$model_persona = Persona::findOne($model_empleado->idpersona);
$dispositivo = OrganismoDispositivo::get_dispositivo($model_empleado->iddispositivo)

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

<div class="empleado-view">

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-7">
                    <?= campo('Empleado', "$model_persona->apellido $model_persona->nombre") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Documento', "$model_persona->documento") ?>
                </div>
                <div class="col-md-2">
                    <?= campo('Legajo', "$model_empleado->legajo") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <?= campo('Sector', "$dispositivo->descripcion") ?>
                </div>
                <div class="col-md-5">
                    <?= campo('Funcion', Configuracion::findOne($model_empleado->funcion)->descripcion) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= campo('Contratacion', Configuracion::findOne($model_empleado->contratacion)->descripcion) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('Categoria', Configuracion::findOne($model_empleado->categoria)->descripcion) ?>
                </div>
                <div class="col-md-2">
                    <?= campo('Orden', $model->orden) ?>
                </div>
                <div class="col-md-2">
                    <?= campo('Activo', $model->activo ? 'Si' : 'No') ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?= Html::img('img/empleados-fotos/' . $model_empleado->foto, ['id' => 'base64image']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= campo('Reseña', $model->descripcion) ?>
        </div>
    </div>

</div>