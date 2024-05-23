<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mds-conc-solicitud-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idconcurso')->dropdownList(
                $concursoOptions,
                [
                    'id' => 'idconcurso',
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => true]
                    ],
                ]
            )
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textinput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6">
            <?= $form->field($model, 'orden')->textinput(['min' => 0, 'max' => 20, 'type' => 'number']) ?>
        </div>
        <div class="col-12 col-sm-6">
            <?=
            $form->field($model, 'estado')->dropdownList(
                [
                    1 => "Activo",
                    0 => "Inactivo"
                ],
                [
                    'prompt' => [
                        'text' => 'Seleccione',
                        'options' => ['disabled' => true, 'selected' => true]
                    ],
                ]
            )
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6">
            <?= $form->field($model, 'fecha_inicio')->widget(DateTimePicker::class, [
                'name' => 'date_inicio',
                'language' => 'es',
                'readonly' => false,
                'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_inicio',
                    'class' => 'form-control input-md',
                    'autocomplete' => 'off'
                ],
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'autoclose' => false,
                    'format' => 'dd-mm-yyyy H:ii',
                ]
            ])->label('Fecha inicio'); ?>
        </div>
        <div class="col-12 col-sm-6">
            <?= $form->field($model, 'fecha_fin')->widget(DateTimePicker::class, [
                'name' => 'date_fin',
                'language' => 'es',
                'readonly' => false,
                'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_fin',
                    'class' => 'form-control input-md',
                    'autocomplete' => 'off'
                ],
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'autoclose' => false,
                    'format' => 'dd-mm-yyyy H:ii',
                ]
            ])->label('Fecha fin'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'detalle')->textarea(['rows' => 6])->label('Detalle'); ?>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar Datos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>