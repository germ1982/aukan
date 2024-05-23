<?php 
use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<style>
  .d-none{
    display: none;
  }
</style>
<header class="page-header">
    <h2>Fichada DNI</h2>
</header>
<?php $form = ActiveForm::begin(['id'=>'form-dni']); ?>
  <div id="container-blue" class="bg-primary" style="min-height: 525px; padding-top: 8%;">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="row">
          <div class="col-md-6 col-md-offset-1">
            <img src="img/sur_trans.png" width="500">
          </div>
        </div>
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <?= $form->field($model, 'dni')->textInput([
                'id' => 'dni',
                'placeholder' => 'DNI A Fichar',
                'readonly' => true,
                ])->label(false);
            ?>
            <input type="text" id="scan-dni" class="form-control" style="position: absolute; top:-420px;">
          </div>
        </div>
        <br>
        <div class="row">
          <?php if (Yii::$app->session->hasFlash('success')):?>
            <div class="alert alert-success" id="alert-success">
                <h4><i class="icon fa fa-check"></i> Ingreso Permitido</h4>
                <b><?= Yii::$app->session->getFlash('success') ?></b>
            </div>
          <?php endif; ?>
          <?php if (Yii::$app->session->hasFlash('error')):?>
            <div class="alert alert-danger" id="alert-error">
                <h4 class="text-center"><i class="icon fa fa-times"></i> Ingreso NO Permitido</h4>
                <b><?= !empty($model->getErrors()) ? implode('<br>', $model->getErrorSummary(true)) : Yii::$app->session->getFlash('error')?></b>
            </div>
          <?php endif;?>
        </div>
      </div>
    </div>
    <?= Html::button('Enviar', ['id' => 'submit', 'class'=>'btn btn-primary d-none', 'type'=>'submit'])?>
  </div>
<?php ActiveForm::end(); 

$script = <<<  JS
$(document).ready(function(){
  $("#scan-dni").focus();
  $('#container-blue').on('click', function() {
    $("#scan-dni").focus();
  });

  $("#scan-dni").on('input', function(){
    let scan_dni = $("#scan-dni").val();
    let array_scan = scan_dni.split('@');
    $("#dni").val(array_scan[4]);
    //Si la posicion 8 está definida es porque ya se finalizó la lectura/escritura del DNI (el lector escribe caracter por caracter)
    if(array_scan[8]){
      $("#scan-dni").off('input');
      $('#container-blue').off('click');
      $("#dni").trigger('change');
      setTimeout(() => {
        $("#submit").click();
      },200);
    }
  });

  $("#dni").change(function(){
    $("#submit").click();
  });

});
JS;
$this->registerJs($script);
?>