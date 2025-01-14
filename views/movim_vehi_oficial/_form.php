<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\VehiculoOficial;
use app\models\Empleado;

/* @var $this yii\web\View */
/* @var $model app\models\MovimVehiOficial */
/* @var $form yii\widgets\ActiveForm */

// No es necesario realizar la consulta de choferes aquí.
// Los choferes deben ser pasados desde el controlador.

?>

<div class="movim-vehi-oficial-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($model, 'idvehiculo')->dropDownList(
                        $vehiculos,
                        [
                            'prompt' => 'Selecciona un vehículo',
                            'onchange' => 'actualizarDatosVehiculo(this.value)', // Llamar a la función cuando cambie el vehículo
                            'class' => 'form-control'
                        ]
                    ) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'chofer')->dropDownList(
                        $choferes, // Aquí usas la lista de choferes que fue pasada desde el controlador
                        [
                            'prompt' => 'Selecciona un chofer', // Texto por defecto
                            'class' => 'form-control',          // Estilo
                        ]
                    ) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'salida')->textInput() ?>
                </div>
            </div>

            <!-- Otros campos -->
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'regreso')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'finalidad_viaje')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'fecha')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'lugar')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'hora')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'kilometraje')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Usar JavaScript para cargar los datos dinámicamente
$this->registerJs("
    function actualizarDatosVehiculo(idvehiculo) {
        if (idvehiculo) {
            $.ajax({
                url: '/movim-vehi-oficial/datos-vehiculo',
                type: 'GET',
                data: { idvehiculo: idvehiculo },
                success: function(data) {
                    // Si se obtienen datos, actualizar los campos
                    if (data) {
                        // Actualizar los campos específicos con los datos recibidos
                        $('#movimvehioficial-dominio').val(data.dominio);
                        $('#movimvehioficial-modelo').val(data.modelo);
                        $('#movimvehioficial-anio').val(data.anio);
                        $('#movimvehioficial-color').val(data.color);
                    }
                }
            });
        }
    }
", \yii\web\View::POS_END);
?>
