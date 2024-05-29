<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
?>

<div class="usuarios-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-9">

                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'activo')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'avatar')->textInput(['maxlength' => true]) ?>
        </div>
    </div>




    


    <?php ActiveForm::end(); ?>

</div>