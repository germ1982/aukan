<?php

use app\controllers\SiteController;
use app\helpers\AppCheckboxListHelper;
use app\models\Empleado;
use app\models\InformaticaControlInsumosEventos;
use app\models\InformaticaControlInsumosEventosAsistente;
use app\models\OrganismoDispositivo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaControlInsumosEventos */
/* @var $form yii\widgets\ActiveForm */

$model->estado = $model->isNewRecord ? InformaticaControlInsumosEventos::ESTADO_SOLICITADO : $model->estado;

$solicitantes = Empleado::get_empleados();
$decreto_activo = $model->isNewRecord ? true : false;
$sectores = OrganismoDispositivo::get_dispositivos_con_decreto($decreto_activo);
$tecnicos_asistencia = Empleado::get_asistentes_informaticos();
$estados = InformaticaControlInsumosEventos::get_estados();

if ($model->fecha) {
    $model->fecha = date('d/m/Y', strtotime($model->fecha));
    $model->hora = substr($model->hora, 0, 5);
} else {
    $model->fecha = date('d/m/Y');
    $model->hora = substr($model->hora, 0, 5);
}


$asistentes_seleccionados = [];
if (!$model->isNewRecord) {
    $selectedIds = InformaticaControlInsumosEventosAsistente::find()
        ->select('idtecnico')
        ->where(['identrega' => $model->identrega])
        ->column();
    $asistentes_seleccionados = $selectedIds;
}


?>



<div class="informatica-control-insumos-eventos-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'input_fecha') ?>
        </div>
        <div class="col-md-2">
            <?= SiteController::actionGet_input_hora($form, $model, 'hora', 'input_hora') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select($form, $model, 'estado', 'cmb_estado', $estados, 'id', 'descripcion', 'Estado', 'Seleccione un estado...') ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idsolicitante', 'input_idsolicitante', $solicitantes, 'idempleado', 'descripcion') ?>
        </div>
        <div class="col-md-9">
            <?= SiteController::actionGet_input_select2($form, $model, 'idsector_solicitante', 'input_idsector_solicitante', $sectores, 'iddispositivo', 'descripcion') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idresponsable', 'input_idresponsable', $solicitantes, 'idempleado', 'descripcion') ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($model, 'destino_prestamo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label>Asistencia en Destino</label>
            <?= AppCheckboxListHelper::render($tecnicos_asistencia, 'idempleado', 'descripcion', 'asistentes', $asistentes_seleccionados ?? []) ?>
        </div>
    </div>

    <div class="row" style="margin-top: 25px;">
        <div class="col-md-6">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
    $this->registerJs(file_get_contents(__DIR__ . '/_form.js'));
?>