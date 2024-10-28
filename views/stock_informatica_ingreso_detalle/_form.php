<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="stock-informatica-ingreso-detalle-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-10">
            <?= $form->field($model, 'idarticulo')->textInput() ?>
            </div>
            <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput() ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    
</div>
