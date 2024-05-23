<?php

use app\models\Mds_conc_solicitud;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$ESTADO_SELECCIONADO = Mds_conc_solicitud::ESTADO_SELECCIONADO;
$ESTADO_ADMITIDO = Mds_conc_solicitud::ESTADO_ADMITIDO;
$ESTADO_NO_ADMITIDO = Mds_conc_solicitud::ESTADO_NO_ADMITIDO;
?>

<div class="mds-conc-solicitud-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6 form-group">
            <label class="control-label" for="estadoActual">Estado actual</label>
            <input class="form-control" type="text" readonly value="<?= (isset($modelPostulacion) && isset($modelPostulacion->estado0)) ? $modelPostulacion->estado0->descripcion : (($model->isNewRecord) ? $modelPostulacion->estado0->descripcion : $model->nuevoEstado->descripcion) ?>" />
        </div>
        <div class="col-md-6 form-group">
            <?= $form
                ->field($model, 'estado_nuevo')
                ->dropDownList(
                    $estadosTipos,
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => [
                                'disabled' => true,
                                'selected' => true,
                            ],
                        ],
                        'onchange' => 'mostrarPuntaje()',
                        'disabled' => $model->isNewRecord ? false : true
                    ]
                )
                ->label('Nuevo estado') ?>
        </div>
        <div class="col-md-12 form-group" style="display: none" id="PUNTAJE_CONTAINER">
            <label class="control-label" for="puntaje">Puntaje</label>
            <input class="form-control" type="number" name="puntaje" id="puntaje" oninput="validarNumero(this)" value="<?= $model->isNewRecord && isset($modelPostulacion) && isset($modelPostulacion->puntaje) ? $modelPostulacion->puntaje : ((isset($model->postulacion0)) ? $model->postulacion0->puntaje : '') ?>" />
        </div>
        <div class="col-md-12 form-group" style="display: none" id="MOTIVOS_IMPUGNACION_CONTAINER">
            <label class="control-label" for="puntaje">Motivo Impugnación</label>
            <div style="padding-top:6px;">
                <?= Select2::widget([
                    'name' => 'motivos_impugnacion',
                    'id'  => 'motivos_impugnacion',
                    'value' => $motivosImpugnacion,
                    'data' => $motivosImpugnacionOptions,
                    'options' => ['multiple' => true],
                    'showToggleAll' => false,
                ]); ?>
            </div>
        </div>
        <div class="col-md-12 form-group" style="display: <?= $model->estado_nuevo == Mds_conc_solicitud::ESTADO_IMPUGNADO && $motivosImpugnacionString ? '' : 'none' ?>">
            <label>Motivo de impugnación</label>
            <textarea class="form-control" readonly rows="5"><?= $motivosImpugnacionString ? $motivosImpugnacionString : '' ?></textarea>
        </div>
        <div class="col-md-12 form-group">
            <?php echo $form->field($model, 'observacion')->textarea(['rows' => 6])->label('Observación'); ?>
        </div>
        <div class="col-md-12 form-group">
            <?php echo $form->field($model, 'observacion_publica')->textarea(['rows' => 6])->label('Observación pública'); ?>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar Datos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>

<script>
    function mostrarPuntaje() {
        const estadoNuevo = $("#mds_conc_historial-estado_nuevo").val();
        const estadoSeleccionado = '<?= $ESTADO_SELECCIONADO ?>';
        const estadoAdmitido = '<?= $ESTADO_ADMITIDO ?>';
        const estadoNoAdmitido = '<?= $ESTADO_NO_ADMITIDO ?>';

        switch (estadoNuevo) {
            case estadoSeleccionado:
            case estadoAdmitido:
                $("#PUNTAJE_CONTAINER").show();
                $("#MOTIVOS_IMPUGNACION_CONTAINER").hide();
                $("#motivos_impugnacion").val([]).trigger("change");
                break;
            case estadoNoAdmitido:
                $("#MOTIVOS_IMPUGNACION_CONTAINER").show();
                $("#PUNTAJE_CONTAINER").hide();
                $("#puntaje").val("");
                break;
            default:
                $("#PUNTAJE_CONTAINER, #MOTIVOS_IMPUGNACION_CONTAINER").hide();
                $("#puntaje").val("");
                $("#motivos_impugnacion").val([]).trigger("change");
                break;
        }
    }

    function validarNumero(input) {
        const valor = input.value.trim();
        if (valor === "" || valor === "0") {
            return;
        }

        if (!/^(?!0*0$)(?!0*$)(100|[0-9][0-9]?)$/.test(valor)) {
            input.value = valor.slice(0, -1); // Eliminar el último carácter inválido
        }
    }
</script>

<?php
$this->registerJs(
    "$(document).ready(function() {
        mostrarPuntaje();
    })"
);
?>