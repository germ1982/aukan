<?php

use yii\widgets\DetailView;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}

$articulo = Articulo::get_articulo($model->idarticulo);
$empleado = Empleado::findOne($model->idempleado);
$persona = Persona::findOne($empleado->idpersona);

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
        height: 27px;
    }
</style>

<div class="inventario-view">

    <div class="row">



        <div class="col-md-7">
            <?= campo('Articulo', "$articulo->descripcion") ?>
        </div>
        <div class="col-md-2">
            <?= campo('Cantidad', "$model->cantidad") ?>
        </div>

        <div class="col-md-2">
            <?= campo('Estado', $model->idestado ? Configuracion::findOne($model->idestado)->descripcion : '') ?>
        </div>

        <div class="col-md-1">
            <?= campo('activo', $model->activo ? "SI" : "NO") ?>
        </div>
    </div>
    <div class="row">
        

        <div class="col-md-6">
            <?= campo('Sector', OrganismoDispositivo::get_dispositivo($model->iddispositivo)->descripcion) ?>

        </div>
        <div class="col-md-6">
            <?= campo('Empleado a Cargo', "$persona->nombre $persona->apellido") ?>
        </div>

    </div>






    <div class="row">
    <div class="col-md-12">
            <?= campo('Observacion', "$model->observacion") ?>
        </div>
    </div>




</div>


<!-- <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'idInventario',
                'idarticulo',
                'cantidad',
                'iddispositivo',
                'idempleado',
                'idestado',
                'activo',
                'observacion:ntext',

            ],
        ]) ?> -->