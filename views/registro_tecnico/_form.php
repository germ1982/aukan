<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$solicitantes = Empleado::get_empleados();
$sectores = OrganismoDispositivo::get_dispositivos();
$tipos_registros = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_REGISTRO_TECNICO);

$tecnicos_asistencia = Empleado::get_asistentes_informaticos();
?>

<div class="registro-tecnico-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_solicitud', 'input_fecha_solicitud') ?>
        </div>
        <div class="col-md-2">
            <?= SiteController::actionGet_input_hora($form, $model, 'fecha_solicitud', 'input_hora_solicitud') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <?= SiteController::actionGet_input_select2($form, $model, 'idsolicitante', 'input_idsolicitante', $solicitantes, 'idempleado', 'descripcion') ?>
        </div>

        <div class="col-md-7">
            <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'input_iddispositivo', $sectores, 'iddispositivo', 'descripcion') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'problema')->textarea(['rows' => 2]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3">

            <!-- crear un widgets que use checkbox -->
            <?= SiteController::actionGet_input_select2($form, $model, 'idtipo_registro', 'input_idtipo_registro', $tipos_registros, 'id_configuracion', 'descripcion') ?>
        </div>

        <div class="col-md-9">
            <!-- crear un widgets que use checkbox -->
            <?= SiteController::actionGet_input_select2($form, $model, 'asistentes_informaticos', 'input_asistentes_informaticos', $tecnicos_asistencia, 'idempleado', 'descripcion') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_solucion', 'input_fecha_solucion') ?>
        </div>
        <div class="col-md-2">
            <?= SiteController::actionGet_input_hora($form, $model, 'fecha_solucion', 'input_hora_solucion') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'solucion')->textarea(['rows' => 2]) ?>
        </div>
    </div>

   
    <?php ActiveForm::end(); ?>

</div>