<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mds-certificacion-director-form" style="margin: 10px">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6 required" id="director-required">
            <?= $form->field($model, 'idusuario')->widget(Select2::class, [
                'data' => $listUsuarios,
                'options' => [
                    'placeholder' =>
                    'Seleccione...',
                    'tabIndex' => '1',
                    'id' => 'idusuario',
                    'disabled' => $model->isNewRecord ? false : true,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Usuario</b>');
            ?>
            <p id='director-msg'></p>
        </div>
        <div class="col-md-6 required" id="funcion-required">
            <?= $form->field($model, 'idfuncion')->widget(Select2::class, [
                'data' => $listFunciones,
                'options' => [
                    'placeholder' =>
                    'Seleccione...',
                    'tabIndex' => '1',
                    'id' => 'idfuncion'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Función que desempeña</b>');
            ?>
            <p id='funcion-msg'></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 required" id="divDatepickerInicio" name="divDatepickerInicio">
            <?php
            if ($model->fecha_desde != null) {
                $model->fecha_desde = date(
                    'd/m/Y',
                    strtotime(str_replace('/', '-', $model->fecha_desde))
                );
            }
            echo $form
                ->field($model, 'fecha_desde')
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha_desde',
                        'class' => 'form-control input-md',
                        'disabled' => false,
                        'autocomplete' => 'off',
                        'placeholder' => '--/--/----',
                        'onChange' => 'validarFechas()'
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ]
                ])->label('<b>Fecha desde</b>');
            ?>
            <p id='fecha_desde-msg'></p>
        </div>
        <div class="col-md-6" required id="divDatepickerFin" name="divDatepickerFin">
            <?php
            if ($model->fecha_hasta != null) {
                $model->fecha_hasta = date(
                    'd/m/Y',
                    strtotime(
                        str_replace(
                            '/',
                            '-',
                            $model->fecha_hasta
                        )
                    )
                );
            }
            echo $form
                ->field($model, 'fecha_hasta')
                ->widget(DatePicker::class, [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'fecha_hasta',
                        'class' => 'form-control input-md',
                        'disabled' => false,
                        'autocomplete' => 'off',
                        'placeholder' => '--/--/----'
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'todayHighlight' => true,
                        'autoclose' => true
                    ],
                ])->label('<b>Fecha hasta</b>');
            ?>
            <small id="smallFin"></small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 3])->label('<b>Observaciones</b>'); ?>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs(
    " $(document).ready(function() {
        $('#divDatepickerFin').on('changeDate', function(e) {
            validarFechas();
        });

        $('#divDatepickerInicio').on('changeDate', function(e) {
            validarFechas();
        });
    });
    "
); ?>

<script>
    function validarFechas() {
        fechaDesde = $('#fecha_desde').val();
        fechaFin = $('#fecha_hasta').val();
        fechaInicioDias = fechaDesde.substr(0, 2);
        fechaInicioMes = fechaDesde.substr(3, 2);
        fechaInicioAño = fechaDesde.substr(6, 4);
        fechaFinDias = fechaFin.substr(0, 2);
        fechaFinMes = fechaFin.substr(3, 2);
        fechaFinAño = fechaFin.substr(6, 4);
        const fecha_desde = new Date(fechaInicioAño, fechaInicioMes, fechaInicioDias);
        const fecha_hasta = new Date(fechaFinAño, fechaFinMes, fechaFinDias);

        if (fechaDesde && fechaFin) {
            if (fecha_desde < fecha_hasta) {
                $('#btnGuardar').prop('disabled', false);
                $('#divDatepickerFin').removeClass('has-success has-error').addClass('has-success');
                $('#smallFin').text('');
            } else {
                $('#btnGuardar').prop('disabled', true);
                $('#divDatepickerFin').removeClass('has-success has-error').addClass('has-error');
                $('#divDatepickerFin').css('color', '#a94442');
                $('#smallFin').text('Periodo Hasta debe ser mayor a Periodo Desde.');
            }
        }
    }
</script>