<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_ans_negativa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-ans-negativa-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'cuit')->textInput() ?>
        </div>
    </div>
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'periodo')->textInput() ?>

    <?= $form->field($model, 'fallecido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_fallecido')->textInput() ?>

    <?= $form->field($model, 'trabajador_dependiente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'autonomo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'monotributista')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ddjprovincial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'casas_particulares')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'efectores_sociales')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jubilado_pensionado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'previsional_provincia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'previsional_tramite')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desempleo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'programa_empleo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'os_vigente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'asignacion_familiar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cuota_beca_progresar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'beca_progresar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maternidad_casasparticulares')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'asignacion_familiar_jubilados')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iniciacion_pnc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aaff_discontinuos')->textInput(['maxlength' => true]) ?>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>