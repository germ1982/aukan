<?php

use app\models\OrganismoDispositivo;
use yii\bootstrap\Carousel;

$imageNames = explode(',', $model->fotos); // Convertir el string de fotos a un array
$items = [];

foreach ($imageNames as $imageName) {
      $items[] = [
            'content' => '<img src="img/evento-fotos/' . $imageName . '" class="d-block w-100">',
            //'caption' => '<h5>' . $imageName . '</h5>',
      ];
}


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
                  <div class="neon">
                        <?= Carousel::widget([
                              'items' => $items,
                              'options' => [
                                    'class' => 'carousel slide',
                                    'data-ride' => 'carousel',
                                    'id' => 'myCarousel', // Añadir un ID único si no está
                                    'data-interval' => '4000',
                              ],
                              'controls' => [
                                    '<span class="fas fa-angle-left" style="font-size:36px;padding-top: 200%;" aria-hidden="true"></span>',
                                    '<span class="fas fa-angle-right" style="font-size:36px; padding-top: 200%;" aria-hidden="true"></span>',
                              ],
                              'showIndicators' => true, // Opcional, para mostrar indicadores
                        ]) ?>
                  </div>

            </div>
      </div>


</div>