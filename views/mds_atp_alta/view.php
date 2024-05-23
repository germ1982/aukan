<?php

use kartik\form\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_alta */
?>
<div class="mds-atp-alta-view">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'fechahora')->textInput(['disabled' => true, 'maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'idusuario')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'estado')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6, "readOnly" => true, 'maxlength' => true]) ?>
        </div>
    </div>
</div>