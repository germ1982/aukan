<?php

use app\controllers\SiteController;
use app\models\OrganismoDispositivo;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$model->fecha = date('d/m/Y', strtotime($model->fecha));

$initialPreview = [];
$initialPreviewConfig = [];

if (isset($model->fotos) && !empty($model->fotos)) {
      $fotosArray = explode(',', $model->fotos); // Divide el string en un array usando la coma como delimitador

      foreach ($fotosArray as $foto) {
            $imagePath = Url::to('img/evento-fotos/' . trim($foto)); // trim() para eliminar espacios en blanco

            // Agrega cada imagen a la vista previa inicial
            $initialPreview[] = Html::img($imagePath, [
                  'class' => 'file-preview-image',
                  'alt' => 'Foto',
                  'title' => $foto,
                  'width' => '100%',
                  'height' => 'auto',
            ]);

            // Opcional: Agrega configuraciones adicionales si es necesario
            $initialPreviewConfig[] = [
                  'caption' => $foto,
                  'width' => '50px',
                  'url' => Url::to(['/site/delete-image']), // URL para eliminar la imagen
                  'key' => $foto, // Identificador único para cada imagen
            ];
      }
}

?>


<style>
      .file-drop-zone {
            min-height: 100px !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            /* Cambia esto si prefieres otro tipo de alineación */
            align-items: flex-start;
            /* Cambia esto si prefieres otro tipo de alineación */
      }

      .file-preview-frame {
            margin: 5px !important;
            width: calc(33% - 31px) !important;
            height: 30px !important;
      }

      .file-preview-image {
            width: 100% !important;
            height: 46px !important;
            transition: transform 0.3s ease;
            /* Animación para suavizar el cambio de tamaño */
      }

      .krajee-default {
            min-height: 60px !important;
            float: 12px !important;
            height: 60px !important;
      }

      .kv-file-content {
            min-height: 100px !important;
            width: 100% !important;
            height: 100px;
      }
</style>

<div class="informatica-web-eventos-form">

      <?php $form = ActiveForm::begin(); ?>

      <div class="row">

            <div class="col-md-8">
                  <div class="row">
                        <div class="col-md-3">
                              <?= SiteController::actionGet_input_fecha($form, $model, "fecha", "input_fecha", "Fecha") ?>

                        </div>
                        <div class="col-md-9">
                              <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-10">
                              <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivos', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Sector', 'Seleccione Sector...') ?>
                        </div>
                        <div class="col-md-2" style="padding-top:30px;">
                              <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
                        </div>
                  </div>

                  <div class="row">
                        <div class="col-md-12">
                              <?= $form->field($model, 'descripcion')->textarea(['rows' => 10]) ?>
                        </div>
                  </div>

            </div>

            <div class="col-md-4">
                  <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*', 'multiple' => true],
                        'pluginOptions' => [
                              'initialPreview' => $initialPreview,
                              'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                              'showPreview' => true,
                              'showCaption' => false,
                              'showRemove' => true,
                              'showUpload' => false,
                              'showClose' => false,
                              'showCancel' => false,
                              'mainClass' => 'input-group-sm',
                              //'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                              'maxFileSize' => 100000,
                              'fileActionSettings' => [
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showZoom' => false,
                                    'showCaption' => false,
                                    'showCancel' => false
                              ],
                              'previewFileType' => 'image',
                              'layoutTemplates' => [
                                    'footer' => '',  // Remueve el footer en la vista previa si es necesario
                              ],
                              'initialPreviewConfig' => [
                                    ['width' => '50px'] // Define el ancho de la vista previa
                              ]
                        ]
                  ]);

                  ?>
            </div>

      </div>



      <?php ActiveForm::end(); ?>

</div>


<?php $this->registerJsFile('@web/js/stock.js'); ?>
<?php
$script = <<<JS

$(document).on('fileloaded', function(event, file, previewId, index, reader) {
    // Contar cuántas imágenes están actualmente en el preview
    var totalImages = $('.file-preview-frame').length;
    
    // Calcular el ancho para que todas las imágenes se ajusten al contenedor
    var imageWidth = Math.floor(100 / totalImages) + '%'; // Porcentaje de ancho basado en el número de imágenes
    
    // Ajustar el tamaño de cada imagen
    $('.file-preview-image').css('width', imageWidth);
});

JS;
$this->registerJs($script);
?>