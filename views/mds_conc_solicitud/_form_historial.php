<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="mds-conc-solicitud-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <label class="control-label" for="estadoActual"><strong> Estado actual</strong>
            </label>
            <input class="form-control" type="text" readonly value="<?= $model->estado0 ? $model->estado0->descripcion : "" ?>" />
        </div>

        <div class="col-md-6">
            <?= $form
                ->field($model, 'estado')
                ->dropDownList(
                    $estadosTipos,
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => [
                                'disabled' => true,
                                'selected' => true,
                            ],
                        ]
                    ]
                )
                ->label('<b>Nuevo estado</b>') ?>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'desc_historial')->textarea(['rows' => 6])->label('Motivo'); ?>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar Datos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>