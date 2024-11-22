<?php

use app\models\Configuracion;
use app\models\OrganismoDispositivo;
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

$model_persona = Persona::findOne($model->idpersona);
$dispositivo = OrganismoDispositivo::get_dispositivo($model->iddispositivo);
$contratacion = $model->contratacion ? Configuracion::findOne($model->contratacion)->descripcion : 'Completar';
$categoria = $model->categoria ? Configuracion::findOne($model->categoria)->descripcion : 'Completar';
$afiliacion = $model->afiliacion ? Configuracion::findOne($model->afiliacion)->descripcion : 'Completar';
$ingreso_real = ($model->ingreso_real == '0000-00-00') ? 'Completar' : date_format(date_create($model->ingreso_real), 'd/m/Y');
$ingreso_administrativo = ($model->ingreso_administrativo == '0000-00-00') ? 'Completar' : date_format(date_create($model->ingreso_administrativo), 'd/m/Y');
$antiguedad_legal = $model->antiguedad_legal ? $model->antiguedad_legal : 'Completar';
$antiguedad_total = $model->antiguedad_total ? $model->antiguedad_total : 'Completar';
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
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-5">
                    <?= campo('Empleado', "$model_persona->apellido $model_persona->nombre") ?>
                </div>
                <div class="col-md-2">
                    <?= campo('Legajo', "$model->legajo") ?>
                </div>

                <div class="col-md-5">
                    <?= campo('Funcion', Configuracion::findOne($model->funcion)->descripcion) ?>
                </div>


            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= campo('Sector', "$dispositivo->descripcion") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Documento', "$model_persona->documento") ?>
                </div>
                <div class="col-md-3">
                    <?= campo('Cuil', "$model->cuil") ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= campo('Nacimiento', date_format(date_create($model_persona->fecha_nacimiento), 'd/m/Y')) ?>
                </div>
                <div class="col-md-6">
                    <?= campo('Email', "$model->email") ?>
                </div>

                <div class="col-md-3">
                    <?= campo('Telefono', "$model->telefono") ?>
                </div>

            </div>



        </div>
        <div class="col-md-3">
            <?= Html::img('img/empleados-fotos/' . $model->foto, ['id' => 'base64image']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= campo('Contratacion', $contratacion) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Categoria', $categoria) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Ingreso Administrativo', $ingreso_administrativo) ?>
        </div>
        <div class="col-md-3">
            <?= campo('Antiguedad Legal', $antiguedad_legal) ?>
        </div>


    </div>

    <div class="row">


        <div class="col-md-3">
            <?= campo('Ingreso Real', $ingreso_real) ?>
        </div>
        <div class="col-md-2">
            <?= campo('Antiguedad Real', "$antiguedad_total") ?>
        </div>

        <div class="col-md-3">
            <?= campo('Afiliacion Gremial', $afiliacion) ?>
        </div>
        <div class="col-md-1">
            <?= campo('Fichado', $model->fichado ? 'Si' : 'No') ?>
        </div>
        <div class="col-md-1">
            <?= campo('Activo', $model->activo ? 'Si' : 'No') ?>
        </div>

    </div>


</div>


