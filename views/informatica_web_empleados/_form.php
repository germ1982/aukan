<?php

use app\controllers\SiteController;
use app\models\Empleado;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="informatica-web-empleados-form">

      <?php $form = ActiveForm::begin(); ?>

      <div class="row">
            <div class="col-md-9">
                  <div class="row">
                        <div class="col-md-8">
                              <?= SiteController::actionGet_input_select2($form, $model, 'idempleado', 'cmb_empleado', Empleado::get_empleados_organismo(6), 'idempleado', 'descripcion', 'Empleado', 'Empleado...') ?>
                        </div>
                        <div class="col-md-2">
                              <?= $form->field($model, 'orden')->textInput() ?>
                        </div>
                        <div class="col-md-2" style="padding-top:30px;">
                              <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
                        </div>
                  </div>

                  <div class="row">
                        <div class="col-md-12">
                              <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                        </div>
                  </div>
            </div>
            <div class="col-md-3" id="empleados_list">

            </div>
      </div>



      <?php ActiveForm::end(); ?>

</div>


<?php $this->registerJsFile('@web/js/stock.js'); ?>
<?php
$script = <<<JS

$(document).ready(function() {
      mostrar_listado();
});


    function mostrar_listado() {
    $.ajax({
        url: 'index.php?r=/informatica_web_empleados/get_empleados', // Ruta al controlador que manejará la solicitud
        type: 'post',
        success: function(response) {
            // Suponiendo que la respuesta es un HTML con la lista de hijos
            console.log(response);
            $('#empleados_list').html(response);
        },
        error: function() {
            $('#empleados_list').html('<p>Error al cargar los datos.</p>');
        }
    });
}


JS;
$this->registerJs($script);
?>