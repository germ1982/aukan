<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="mds-cor-intervencion-usuario-form" id="interv_form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'editar')->dropDownList(
                [
                    0 => "No",
                    1 => "Si"
                ]
            ) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

<?php
