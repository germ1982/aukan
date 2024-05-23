<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_direccion */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mds-certificacion-direccion-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4 required" id="direccion-required">
            <?= $form->field($model, 'iddireccion')->widget(Select2::class, [
                'data' => $listDirecciones,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'id' => 'iddireccion',
                    'disabled' => $model->isNewRecord ? false : true,
                    'onChange' => 'verificarCampos()'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Dirección/Área</b>');
            ?>
            <p id='direccion-msg'></p>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'iddireccion_padre')->widget(Select2::class, [
                'data' => $listDirecciones,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'id' => 'iddireccion_padre',
                    'disabled' => $model->isNewRecord ? false : true
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Dependiente de</b>');
            ?>
        </div>
        <div class="col-md-4 required" id="nivel-required">
            <?= $form->field($model, 'idnivelautorizacion')->widget(Select2::class, [
                'data' => $listNivelAutorizacion,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'id' => 'idnivelautorizacion',
                    'onChange' => 'verificarCampos()'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Nivel de autorización</b>');
            ?>
            <p id='nivel-msg'></p>
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
    function verificarCampos() {
        if (verificarDireccion() && verificarNivel()) {
            $('#btnGuardar').prop('disabled', false);
        } else {
            $('#btnGuardar').prop('disabled', true);
        }
    }

    function verificarDireccion() {
        $('#direccion-required').removeClass('has-success has-error').addClass('has-success');
        $('#direccion-msg').text('');
        if (!$('#iddireccion').val()) {
            $('#direccion-required').removeClass('has-success has-error').addClass('has-error');
            $('#direccion-msg').text('Debe seleccionar una dirección.').css('color', '#a94442');
            return false;
        }
        return true;
    }

    function verificarNivel() {
        $('#nivel-required').removeClass('has-success has-error').addClass('has-success');
        $('#nivel-msg').text('');
        if (!$('#idnivelautorizacion').val()) {
            $('#nivel-required').removeClass('has-success has-error').addClass('has-error');
            $('#nivel-msg').text('Debe seleccionar el nivel de autorización.').css('color', '#a94442');
            return false;
        }
        return true;
    }

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