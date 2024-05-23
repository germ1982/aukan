<?php

use kartik\form\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'editar')->dropDownList(
            [
                0 => "No",
                1 => "Si"
            ],
            ['prompt' => '-- Seleccione una opción --']
        ) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>