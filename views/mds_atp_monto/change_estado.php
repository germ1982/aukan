<?php

use app\models\Mds_atp_monto;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="mds-atp-solicitud-change-estado">
    <div class="mds-atp-monto-form">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?php echo $form->field($model, 'estado')->dropDownList([
                        Mds_atp_monto::GENERADO => 'Generado',
                        Mds_atp_monto::ACEPTADO => 'Aceptado',
                        Mds_atp_monto::RECHAZADO => 'Rechazado'
                    ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $form->field($model, 'observaciones')->textarea(['rows' => 6])->label('Detalle Cambio de Estado'); ?>
            </div>
        </div>


        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar Datos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>