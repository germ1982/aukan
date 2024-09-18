<?php

use app\models\Empleado;
use app\models\Persona;
use yii\widgets\DetailView;
use app\models\OrganismoDispositivo;

/* @var $this yii\web\View */
/* @var $model app\models\InfIps */

function campo($titulo, $contenido)
{
    echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
}

$model_persona = Persona::findOne($model->idpersona);
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

<div class="inf-ips-view">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <?= campo('id', "$model->id") ?>
                </div>        
             
                <div class="col-md-3">
                    <?= campo('Direccion ip',"$model->idip") ?>
                </div>               
                
                <div class="col-md-3">
                    <?= campo('Dispositivo', "$dispositivo->descripcion") ?>
                </div>
                
                <div class="col-md-3">
                    <?= campo('Empleado', "$model_persona->idpersona") ?>
                </div>
            </div>    
        </div>

            
    </div>
</div>
<!-- <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idip',
            'ip',
            'iddispositivo',
            [
                'attribute' => 'idempleado',
                'value' => function ($model) {
                    $empleado = Empleado::findOne($model->idempleado);
                    $persona = Persona::findOne($empleado->idpersona);
                    return "$persona->apellido $persona->nombre";
                },
            ],
        ],
    ]) ?> -->