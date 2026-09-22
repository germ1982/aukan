<?php

use app\controllers\SiteController;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnico;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroTecnicoIncidencia */
/* @var $form yii\widgets\ActiveForm */


/**
 * @var yii\web\View $registroTecnico
 */
// Cargar listas necesarias
$ingresantes = Empleado::get_empleados();
$dispositivos = OrganismoDispositivo::get_dispositivos_con_decreto(false);
$tecnicos_asistencia = Empleado::get_asistentes_informaticos();

// Pre-cargamos movimientos si es edición
$array_movimientos = [];

// Si viene de un Registro Técnico y es nuevo, precargamos el movimiento de recepción automático
if ($model->isNewRecord && $registroTecnico !== null) {
    $array_movimientos[] = [
        'idtemp' => time(),
        'fecha' => date('d/m/Y'),
        'hora' => date('H:i'),
        'idmovimiento_nombre' => 1, // ID del tipo de movimiento "Recepción / Ingreso"
        'descripcion' => 'Ingreso desde Registro Técnico: ' . $registroTecnico->observacion, // o el campo que corresponda
        'asistentes' => [$registroTecnico->idtecnico], // Técnico que recepcionó
    ];
}


if (!$model->isNewRecord) {
    // Buscar movimientos guardados en la BD
    $movimientosBD = \app\models\RegistroTecnicoIncidenciaMovimiento::find()
        ->where(['idincidencia' => $model->idincidencia])
        ->all();

    foreach ($movimientosBD as $mov) {
        // Obtener los IDs de técnicos asociados al movimiento directamente desde DB
        $asistentesIds = (new \yii\db\Query())
            ->select(['idtecnico'])
            ->from('registro_tecnico_incidencia_movimiento_asistencia')
            ->where(['idmovimiento' => $mov->idmovimiento])
            ->column();

        $array_movimientos[] = [
            'idmovimiento' => $mov->idmovimiento,
            'fecha' => date('d/m/Y', strtotime($mov->fecha)),
            'hora' => substr($mov->hora, 0, 5),
            'idmovimiento_nombre' => $mov->idmovimiento_nombre,
            'descripcion' => $mov->descripcion,
            'asistentes' => $asistentesIds,
        ];
    }
}

$json_movimientos = Json::htmlEncode($array_movimientos);
$esAltaDirecta = $model->isNewRecord && $registroTecnico === null ? 'true' : 'false';

if ( $registroTecnico !== null) {
    $model_registro = RegistroTecnico::findOne($registroTecnico);
    $model->iddispositivo = $model_registro->iddispositivo;
}

$this->registerJs("
    let movimientosArray = $json_movimientos;
    let esAltaDirecta = $esAltaDirecta;
", \yii\web\View::POS_HEAD);
?>



<div id="formulario_principal_incidencia">
    <?php $form = ActiveForm::begin(['id' => 'formulario_incidencia']); ?>

    <!-- Campo hidden para pasar el JSON deserializado al controller -->
    <input type="hidden" id="movimientosArrayInput" name="movimientosArrayInput">

    <?= $form->field($model, 'idincidencia')->hiddenInput(['id' => 'hidden_idincidencia'])->label(false) ?>
    <?= $form->field($model, 'idregistro')->hiddenInput(['id' => 'hidden_idregistro'])->label(false) ?>

    <div class="row">
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idingresante', 'input_idingresante_inc', $ingresantes, 'idempleado', 'descripcion', 'Ingresante / Solicitante') ?>
        </div>
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'input_iddispositivo_inc', $dispositivos, 'iddispositivo', 'descripcion', 'Dispositivo / Sector') ?>
        </div>
    </div>

    <!-- GRILLA DE MOVIMIENTOS -->
    <div class="row" style="border-radius: 5px; padding: 15px;">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-8">
                <label style="margin-top: 5px;">Movimientos de la Incidencia</label>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-success btn-sm" onclick="mostrar_abm_movimiento()">
                    <i class="glyphicon glyphicon-plus"></i> Agregar Movimiento
                </button>
            </div>
        </div>
        <div id="div_grilla_movimientos" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- SUBFORMULARIO ALTA DE MOVIMIENTO -->
<div class="row" id="abm_movimiento" style="display:none;">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h5><strong>Agregar Movimiento</strong></h5>
            </header>
            <div class="panel-body">
                <?php
                // Subformulario parcial de movimiento
                echo $this->render('_form_movimiento', [
                    'tecnicos_asistencia' => $tecnicos_asistencia,
                    'esPrimerMovimiento' => count($array_movimientos) === 0
                ]);
                ?>
            </div>
        </section>
    </div>
</div>



