<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_pedido */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-reg-pedido-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'numero')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'expediente')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropdownList(
                ArrayHelper::map(
                    Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::REG_PEDIDO_ESTADO, true),
                    'idconfiguracion',
                    'descripcion'
                ),
                ['id' => 'estado', 'placeholder' => 'Seleccionar estado ...'],
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textArea() ?>
        </div>        
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>