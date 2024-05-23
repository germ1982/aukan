<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ris_risneu_alimentacion;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<h3>Alimentación</h3>
<div class="row" style="margin: 5px">
    <?php
    $disabled = isset($view) ? 'disabled' : '';
    foreach ($tipos_alimentacion as $tipo_alim) {
        $checked = "";
        foreach ($risneu_alims as $ris_alim) {
            if ($ris_alim->alimentacion == $tipo_alim->idconfiguracion) {
                $checked = "checked";
                break;
            }
        }
        echo "<div class='col-md-4'>";
        echo "<div class='form-group'>
                    <label>
                        <input type='checkbox' tabindex='1' name='Sds_ris_risneu[tipo_alim][]' value=' {$tipo_alim->idconfiguracion}' $checked $disabled> 
                        {$tipo_alim->descripcion}
                    </label>
                </div>";
        echo "</div>";
    }
    ?>
</div>

<br>
<h3>Vivienda</h3>
<div class="alert alert-warning" role="alert" id="ALERTA_SITUACION_CALLE" style="display: none">
    Al seleccionar <b>"Situación de calle"</b> debe indiciar en <b>"Observaciones"</b> donde permanece la persona.
</div>
<div class="row" style="margin: 5px">
    <div class="col-md-4">
        <?= $form->field($model, 'vivienda_uso')->dropdownList(
            $selectViviendaUso,
            [
                'prompt' => [
                    'text' => 'Seleccionar Uso ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0],
                ],
                'tabindex' => '1',
                'onchange' =>   'mostrarAlertaSituacionCalle();'
            ]
        ); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'vivienda_ubicacion')->dropdownList(
            $selectViviendaUbicacion,
            [
                'prompt' => [
                    'text' => 'Seleccionar Ubicación ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'vivienda_propiedad')->dropdownList(
            $selectViviendaPropiedad,
            [
                'prompt' => [
                    'text' => 'Seleccionar Propiedad ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ],
        ); ?>
    </div>
</div>
<div class="row" style="margin: 5px">
    <div class="col-md-2">
        <?= $form->field($model, 'vivienda_habitaciones')->textInput(['type' => 'number', 'tabindex' => '1', 'min' => 0]) ?>
    </div>
    <div class="col-md-4 col-md-offset-2">
        <?= $form->field($model, 'vivienda_tipo')->dropdownList(
            $selectViviendaTipo,
            [
                'prompt' => [
                    'text' => 'Seleccionar Tipo ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'vivienda_piso')->dropdownList(
            $selectViviendaPiso,
            [
                'prompt' => [
                    'text' => 'Seleccionar Piso ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
</div>
<div class="row" style="margin: 5px">
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_agua_obtiene')->dropdownList(
            $selectViviendaObtieneAgua,
            [
                'prompt' => [
                    'text' => 'Seleccionar Obtención de Agua ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_agua')->dropdownList(
            $selectViviendaAgua,
            [
                'prompt' => [
                    'text' => 'Seleccionar Si Tiene Agua ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_bano')->dropdownList(
            $selectViviendaBano,
            [
                'prompt' => [
                    'text' => 'Seleccionar Baño ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_desague')->dropdownList(
            $selectViviendaDesague,
            [
                'prompt' => [
                    'text' => 'Seleccionar Desagüe ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
</div>
<div class="row" style="margin: 5px">
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_iluminacion')->dropdownList(
            $selectViviendaIluminacion,
            [
                'prompt' => [
                    'text' => 'Seleccionar Iluminación ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_medidor')->dropdownList(
            $selectViviendaMedidor,
            [
                'prompt' => [
                    'text' => 'Seleccionar Medidor ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_combustible_calefaccion')->dropdownList(
            $selectViviendaCalefaccion,
            [
                'prompt' => [
                    'text' => 'Seleccionar Calefacción ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_combustible_cocina')->dropdownList(
            $selectViviendaCocina,
            [
                'prompt' => [
                    'text' => 'Seleccionar Cocina ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
</div>
<div class="row" style="margin: 5px">
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_techo')->dropdownList(
            $selectViviendaTecho,
            [
                'prompt' => [
                    'text' => 'Seleccionar Techo ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'vivienda_paredes')->dropdownList(
            $selectViviendaParedes,
            [
                'prompt' => [
                    'text' => 'Seleccionar Paredes ...',
                    'options' => ['disabled' => true, 'selected' => true, 'value' => 0]
                ],
                'tabindex' => '1'
            ]
        ); ?>
    </div>
</div>
<div class="row" style="margin: 5px">
    <div class="col-md-12">
        <?= $form->field($model, 'observaciones')->textarea(['rows' => 6, 'tabindex' => '1']) ?>
    </div>
</div>
<?php if ($model->oficial == 0) { ?>
    <div class="row" style="margin: 5px">
        <div class="col-md-6">
            <?= $form->field($model, 'vivienda_tiempo_residencia')->textInput(['maxlength' => 255, 'tabIndex' => '1']) ?>
        </div>
    </div>
<?php } ?>

<?php
if (isset($view)) {
    $this->registerJs(
        "$(document).ready(function() {
            $('Sds_ris_risneu[tipo_alim]').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_uso').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_ubicacion').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_propiedad').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_habitaciones').prop('readonly', true);
            $('#sds_ris_risneu-vivienda_tipo').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_piso').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_agua_obtiene').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_agua').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_bano').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_desague').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_iluminacion').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_medidor').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_combustible_calefaccion').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_combustible_cocina').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_techo').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_paredes').prop('disabled', true);
            $('#sds_ris_risneu-vivienda_tiempo_residencia').prop('disabled', true);
            $('#sds_ris_risneu-observaciones').prop('readonly', true);
            $('#ALERTA_SITUACION_CALLE').hide();
        });"
    );
} else {
    $this->registerJs(
        "$(document).ready(function() {
            mostrarAlertaSituacionCalle();
        });"
    );
}
?>

<script>
    function mostrarAlertaSituacionCalle() {
        const idSituacionCalle = 4929;
        if ($('#sds_ris_risneu-vivienda_uso').val() == idSituacionCalle) {
            $('#ALERTA_SITUACION_CALLE').show();
        } else {
            $('#ALERTA_SITUACION_CALLE').hide();
        }
    }
</script>