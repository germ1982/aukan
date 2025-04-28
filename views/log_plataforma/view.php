<?php

use app\models\LogPlataforma;
use app\models\Persona;
use app\models\Articulo;
use app\models\Usuarios;
use kartik\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;


$usuario = Usuarios::findOne($model->idusuario);
$persona = Persona::findOne($usuario->idpersona);


$moduloInfo = LogPlataforma::MODULOS[$model->modulo];
$modeloClase = $moduloInfo['modelo'];
$registro = $modeloClase::findOne($model->idregistro);
$detalle = '';
//$rutaVistaParcial = "@app/views/" . strtolower((new \ReflectionClass($registro))->getShortName()) . "/view";
$rutaVistaParcial = "@app/views/" . Inflector::underscore((new \ReflectionClass($registro))->getShortName()) . "/view";
if (is_file(Yii::getAlias($rutaVistaParcial) . ".php")) {
    $detalle = $this->render($rutaVistaParcial, ['model' => $registro]);
}


if (!function_exists('campo')) {

    function campo($titulo, $contenido)
    {
        echo "<h5><b>$titulo: </b></h5>
      <p class='campo'>
          $contenido
      </p>";
    }
}
?>


<style>
    #base64image {
        display: block;
        border: ridge 1px;
        padding: 8px;
        border-color: #E6E6E6;
        max-width: 90%;
    }

    .campo {
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .detalle{

        border: 1px solid #ccc;
        border-radius: 4px;
padding: 20px;

    }
</style>

<div class="usuarios-view">

    <div class="row">
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-8">
                    <?= campo('Modulo', LogPlataforma::getModuloNombre($model->modulo)) ?>
                </div>
                <div class="col-md-4">
                    <?= campo('Accion', LogPlataforma::getAccionNombre($model->accion)) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?= campo('Usuario', "$persona->apellido $persona->nombre") ?>
                </div>
                <div class="col-md-4">
                    <?= campo('Fecha', date_format(date_create($model->fecha), 'd/m/Y') . date_format(date_create($model->fecha), 'H:i')) ?>
                </div>

            </div>


        </div>
        <div class="col-md-3">
            <?= Html::img('img/usuarios-avatares/' . $usuario->avatar, ['id' => 'base64image']); ?>
        </div>
    </div>
    <hr>
    <h5><b>Detalle Registro</b></h5>
    <div class="row">
        <div class="col-md-12 ">
            <div class="detalle"><?= $detalle ?></div>
        </div>

    </div>
</div>