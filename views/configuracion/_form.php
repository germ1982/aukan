<?php

use app\controllers\SiteController;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$array_tipos = ConfiguracionTipo::find()->where(['activo' => 1])->orderBy('descripcion')->all()
?>



<div class="configuracion-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-5">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_configuracion_tipo', 'cmb_tipo', $array_tipos, 'id_configuracion_tipo', 'descripcion', 'Tipo de Dato', 'seleccione tipo...') ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>
    </div>








    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>