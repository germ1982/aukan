<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Localidades;
use app\models\Provincias;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="persona-form">

      <?php $form = ActiveForm::begin(); ?>

      <div class="row">
            <div class="col-md-4">
                  <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-5">
                  <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                  <?= SiteController::actionGet_input_fecha($form, $model, "fecha_nacimiento", "input_fecha_nacimiento", "Fecha Nacimiento") ?>
            </div>
      </div>

      <div class="row">
            <div class="col-md-4">
                  <?= SiteController::actionGet_input_select2($form, $model, 'documento_tipo', 'cmb_documento_tipo', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_DOCUMENTO), 'id_configuracion', 'descripcion', 'Tipo Documento', 'seleccione tipo documento...') ?>
            </div>
            <div class="col-md-2">
                  <?= $form->field($model, 'documento')->textInput() ?>
            </div>
            <div class="col-md-3">
                  <?= SiteController::actionGet_input_select2($form, $model, 'nacionalidad', 'cmb_nacionalidad', Configuracion::get_configuraciones(ConfiguracionTipo::NACIONALIDAD), 'id_configuracion', 'descripcion', 'Nacionalidad', 'seleccione nacionalidad...') ?>
            </div>
            <div class="col-md-3">
                  <?= SiteController::actionGet_input_select2($form, $model, 'genero', 'cmb_genero', Configuracion::get_configuraciones(ConfiguracionTipo::GENERO), 'id_configuracion', 'descripcion', 'Genero', 'seleccione genero...') ?>
            </div>
      </div>


      <div class="row">
            <div class="col-md-4">
                  <?= SiteController::actionGet_input_select2($form, $model, 'idprovincia', 'cmb_provincia', Provincias::find()->orderBy('provincia')->all(), 'id', 'provincia', 'Provincia', 'seleccione provincia...') ?>
            </div>

            <div class="col-md-6">
                  <?= SiteController::actionGet_input_select2($form, $model, 'idlocalidad', 'cmb_localidad', [], 'id', 'localidad', 'Localidad', 'seleccione localidad...') ?>
            </div>
      </div>

      <div class="row">
            <div class="col-md-5">
                  <?= $form->field($model, 'domicilio_calle')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                  <?= $form->field($model, 'domicilio_numero')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-5">
                  <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?>
            </div>
      </div>

      <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS

$('#cmb_provincia').on('change', function() {
    var provinciaId = $(this).val();
    
    // Hacer una petición AJAX al servidor
    $.ajax({
      url: "index.php?r=localidades/localidades&id_provincia=" + provinciaId, //php que recibe la peticion
      type: 'post',
      async: false,
        success: function(data) {
            console.log(data);
            var localidades = JSON.parse(data);
            var localidadSelect = $('#cmb_localidad');
            
            // Limpiar el combo de localidades
            localidadSelect.empty();

                // Convertir el objeto en un array de pares [id, localidad] y ordenarlo por localidad
            var localidadesOrdenadas = Object.entries(localidades).sort(function(a, b) {
                  return a[1].localeCompare(b[1]);  // Ordenar alfabéticamente por el nombre de la localidad
            });
            
            // Añadir opciones al combo de localidades, ya ordenadas
            $.each(localidadesOrdenadas, function(index, pair) {
                  var id = pair[0];
                  var localidad = pair[1];
                  localidadSelect.append(new Option(localidad, id));
            });

        }
    });
});
JS;

$this->registerJs($script);
?>