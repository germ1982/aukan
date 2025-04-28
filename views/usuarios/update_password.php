<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'id' => 'form-cambiar-pass',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
]);
?>
<style>
@keyframes parpadeo {
    0%   { opacity: 1; }
    50%  { opacity: 0; }
    100% { opacity: 1; }
}
</style>

<div class="row mb-3">
    <div class="col-sm-9">
        <?= $form->field($model, 'password_actual')->passwordInput([
            'id' => 'password_actual',
            'class' => 'form-control',
            'required' => true,
        ])->label('Contraseña actual') ?>
    </div>
    <div class="col-sm-3 d-flex align-items-center pt-3"style="padding-top:30px;">
        <?= Html::checkbox('mostrar_actual', false, [
            'class' => 'form-check-input mostrar-checkbox',
            'data-target' => '#password_actual',
            'label' => 'Mostrar',
        ]) ?>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-9">
        <?= $form->field($model, 'password_nueva')->passwordInput([
            'id' => 'password_nueva',
            'class' => 'form-control',
            'required' => true,
        ])->label('Nueva contraseña') ?>
    </div>
    <div class="col-sm-3 d-flex align-items-center pt-3"style="padding-top:30px;">
        <?= Html::checkbox('mostrar_nueva', false, [
            'class' => 'form-check-input mostrar-checkbox',
            'data-target' => '#password_nueva',
            'label' => 'Mostrar',
        ]) ?>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-9">
        <?= $form->field($model, 'password_nueva_confirmacion')->passwordInput([
            'id' => 'password_nueva_confirmacion',
            'class' => 'form-control',
            'required' => true,
        ])->label('Repetir nueva contraseña') ?>
    </div>
    <div class="col-sm-3 d-flex align-items-center pt-3" style="padding-top:30px;">
        <?= Html::checkbox('mostrar_repetir', false, [
            'class' => 'form-check-input mostrar-checkbox',
            'data-target' => '#password_nueva_confirmacion',
            'label' => 'Mostrar',
        ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


<?php
$script = <<< JS
$('.mostrar-checkbox').on('change', function() {
    let target = $(this).data('target');
    let input = $(target);
    if ($(this).is(':checked')) {
        input.attr('type', 'text');
    } else {
        input.attr('type', 'password');
    }
});
JS;
$this->registerJs($script);
?>