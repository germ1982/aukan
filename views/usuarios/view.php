<?php

use app\models\Persona;
use app\models\UsuarioAsignacionPerfil;
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
$mysql = "  SELECT GROUP_CONCAT(c.descripcion SEPARATOR ', ') AS descripcion
            FROM usuario_asignacion_perfil p
            JOIN configuracion c ON p.idperfil = c.id_configuracion
            WHERE p.idusuario = :idusuario";

// Ejecutar la consulta y obtener el resultado
$perfiles = Yii::$app->db->createCommand($mysql)
    ->bindValue(':idusuario', $model->id)
    ->queryScalar(); // Obtener un valor escalar
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
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
      }
</style>

<div class="usuarios-view">

      <div class="row">
            <div class="col-md-9">
                  <div class="row">
                        <div class="col-md-8">
                              <?= campo('Usuario', "$model_persona->apellido $model_persona->nombre") ?>
                        </div>
                        <div class="col-md-4">
                              <?= campo('Fecha de Nacimiento', date_format(date_create($model_persona->fecha_nacimiento), 'd/m/Y')) ?>
                        </div>

                  </div>
                  <div class="row">
                        <div class="col-md-8">
                              <?= campo('Email', "$model->email") ?>
                        </div>
                        <div class="col-md-4">
                              <?= campo('Activo', $model->activo ? 'Si' : 'No') ?>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <?= campo('Perfiles', "$perfiles") ?>
                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <?= Html::img('img/usuarios-avatares/' . $model->avatar, ['id' => 'base64image']); ?>
            </div>
      </div>
</div>


</div>