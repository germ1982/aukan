<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Menu;

$array_padres = Menu::find()->where(['activo' => 1])->orderBy(['title' => SORT_ASC])->all();

$array_iconos = Menu::find()->select(['id', 'icon_yii'])->distinct()->orderBy(['icon_yii' => SORT_ASC])->orderBy('icon_yii')->all();

foreach ($array_iconos as $icon) {
      $icon->icon_yii = '<span class= "' . $icon->icon_yii . '"> </span> - ' . $icon->icon_yii;
}
?>

<div id="form_principal" class="menu-form">

      <?php $form = ActiveForm::begin(); ?>

      <div class="row">
            <div class="col-md-6">
                  <?= SiteController::actionGet_input_select2($form, $model, 'padre', 'cmb_padre', $array_padres, 'id', 'title', 'Padre', 'seleccione padre...', null, 'mostrar_listado($(this).val())') ?>
            </div>
            <div class="col-md-6">
                  <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
      </div>
      <div class="row">
            <div class="col-md-4">
                  <?= SiteController::actionGet_input_select2($form, $model, 'icono', 'cmb_iconos', $array_iconos, 'id', 'icon_yii', 'Selector opcional de iconos ya existentes', 'seleccione icono...', null, 'setear_icono($(this).find("option:selected").text())') ?>
            </div>
            <div class="col-md-3">
                  <?= $form->field($model, 'icon_yii')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-5">
                  <?= $form->field($model, 'link_yii')->textInput(['maxlength' => true]) ?>

            </div>

      </div>

      <div class="row">

            <div class="col-md-2">
                  <?= $form->field($model, 'orden')->textInput() ?>
            </div>
            <div class="col-md-2" style="padding-top:30px;">
                  <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
            </div>
            <div class="col-md-4" id="children_list">

            </div>

      </div>











      <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<?php
$script = <<<JS

function setear_icono(descripcion) {

      var parts = descripcion.split('</span> - ');

    // La segunda parte contiene el texto después de "</span> - "
    var textAfterSpan = parts[1];
    $('#menu-icon_yii').val(textAfterSpan);
    }

    function mostrar_listado(padreId) {
    $.ajax({
        url: 'index.php?r=/menu/get_children&padre=' + padreId, // Ruta al controlador que manejará la solicitud
        type: 'post',
        success: function(response) {
            // Suponiendo que la respuesta es un HTML con la lista de hijos
            console.log(response);
            $('#children_list').html(response);
        },
        error: function() {
            $('#children_list').html('<p>Error al cargar los datos.</p>');
        }
    });
}


JS;
$this->registerJs($script);
?>