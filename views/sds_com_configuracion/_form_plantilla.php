<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-configuracion-form">

<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'descripcion')->textInput (['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'activo')->checkbox (['checked' => true]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
