<?php

use app\controllers\SiteController;
use app\helpers\AppCheckboxListHelper;
use app\helpers\AppRadioButtomsListHelper;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnicoAsistencia;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$solicitantes = Empleado::get_empleados();
$sectores = OrganismoDispositivo::get_dispositivos();
$tipos_registros = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_REGISTRO_TECNICO);

$tecnicos_asistencia = Empleado::get_asistentes_informaticos();

if ($model->fecha_solicitud) {
    $model->fecha_solicitud = date('d/m/Y', strtotime($model->fecha_solicitud));
    $model->hora_solicitud = substr($model->hora_solicitud, 0, 5);
} else {
    $model->fecha_solicitud = date('d/m/Y');
    $model->hora_solicitud = substr($model->hora_solicitud, 0, 5);
}

if ($model->fecha_solucion) {
    $model->fecha_solucion = date('d/m/Y', strtotime($model->fecha_solucion));
    $model->hora_solucion = substr($model->hora_solucion, 0, 5);
} else {
    $model->fecha_solucion = date('d/m/Y');
    $model->hora_solucion = substr($model->hora_solucion, 0, 5);
}

$asistentes_seleccionados = [];
if (!$model->isNewRecord) {
    $selectedIds = RegistroTecnicoAsistencia::find()
        ->select('idtecnico')
        ->where(['idregistro' => $model->idregistro])
        ->column();
    $asistentes_seleccionados = $selectedIds;
}
?>

<div class="registro-tecnico-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-5">
                    <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_solicitud', 'input_fecha_solicitud') ?>
                </div>
                <div class="col-md-5">
                    <?= SiteController::actionGet_input_hora($form, $model, 'hora_solicitud', 'input_hora_solicitud') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idsolicitante', 'input_idsolicitante', $solicitantes, 'idempleado', 'descripcion') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'input_iddispositivo', $sectores, 'iddispositivo', 'descripcion') ?>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'problema')->textarea(['rows' => 9]) ?>

        </div>
    </div>



    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-12">
                    <label>Asistentes</label>
                    <?= AppCheckboxListHelper::render($tecnicos_asistencia, 'idempleado', 'descripcion', 'asistentes', $asistentes_seleccionados ?? []) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Tipo de Registro</label>
                    <?= AppRadioButtomsListHelper::renderRadio(
                        $tipos_registros,
                        'id_configuracion',
                        'descripcion',
                        'idtipo_registro',
                        $model->idtipo_registro ?? null
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">

            <div class="row">
                <div class="col-md-6">
                    <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_solucion', 'input_fecha_solucion') ?>
                </div>
                <div class="col-md-6">
                    <?= SiteController::actionGet_input_hora($form, $model, 'hora_solucion', 'input_hora_solucion') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'solucion')->textarea(['rows' => 5]) ?>
                </div>
            </div>

        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS

// Al cambiar solicitante → setea el sector
$('#input_idsolicitante').on('change', function() {
    var idempleado = $(this).val();
    if (!idempleado) return;
    $.get('index.php?r=empleado/get_dispositivo&id=' + idempleado, function(data) {
        if (data) {
            $('#input_iddispositivo').val(data).trigger('change');
        }
    });
});

// Al cambiar sector → filtra solicitantes
$('#input_iddispositivo').on('change', function() {
    var iddispositivo = $(this).val();
    if (!iddispositivo) return;
    $.get('index.php?r=empleado/get_por_dispositivo&id=' + iddispositivo, function(data) {
        var select = $('#input_idsolicitante');
        select.empty();
        $.each(data, function(i, item) {
            select.append(new Option(item.descripcion, item.idempleado));
        });
        select.trigger('change');
    });
});

JS;
$this->registerJs($script);
?>