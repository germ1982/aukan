<?php

use app\controllers\SiteController;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnicoAsistencia;
use app\models\RegistroTecnicoIncidenciaMovimiento;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\RegistroTecnicoIncidencia $model
 * @var app\models\RegistroTecnico|null $registroTecnico Registro de origen (null si es alta directa)
 */

$registroTecnico = $registroTecnico ?? null;

// Listas necesarias
$ingresantes = Empleado::get_empleados();
$dispositivos = OrganismoDispositivo::get_dispositivos_con_decreto($model->isNewRecord);
$tecnicos_asistencia = Empleado::get_asistentes_informaticos();

// Movimientos que se pre-cargan en JS
$array_movimientos = [];
$movimientoInicialDefaults = null;

if ($model->isNewRecord) {
    // Alta (desde Registro Técnico o directa): el JS abre el ABM del movimiento inicial con estos defaults
    $asistentes_iniciales = [];
    $descripcion_inicial = 'Se ingresa equipo para revisión y diagnóstico.';

    if ($registroTecnico !== null) {
        $asistentes_iniciales = RegistroTecnicoAsistencia::find()
            ->select('idtecnico')
            ->where(['idregistro' => $registroTecnico->idregistro])
            ->column();

        $descripcion_inicial = 'Ingreso desde Registro Técnico: ' . $registroTecnico->idregistro;
    }

    $movimientoInicialDefaults = [
        'fecha' => date('d/m/Y'),
        'hora' => date('H:i'),
        'idmovimiento_nombre' => 1,
        'descripcion' => $descripcion_inicial,
        'asistentes' => $asistentes_iniciales,
    ];
} else {
    // Edición: movimientos guardados en la BD
    $movimientosBD = RegistroTecnicoIncidenciaMovimiento::find()
        ->where(['idincidencia' => $model->idincidencia])
        ->all();

    foreach ($movimientosBD as $mov) {
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

// Persona y sector (iddispositivo / idingresante) ya vienen seteados desde el controller si hay registro de origen.

$json_movimientos = Json::htmlEncode($array_movimientos);
$json_defaults = Json::htmlEncode($movimientoInicialDefaults);

// var (no let): el modal se puede abrir varias veces sin recargar la página
$this->registerJs("
    var movimientosArray = $json_movimientos;
    var movimientoInicialDefaults = $json_defaults;
", \yii\web\View::POS_HEAD);

// El form tiene que postear SIEMPRE al create de incidencias. Sin action explícito, Yii usa la URL de la request actual,
// y cuando el form se renderiza derivado desde registro_tecnico/create el POST volvía al controller de registros.
if ($model->isNewRecord) {
    $action = ['registro_tecnico_incidencia/create'];
    if ($model->idregistro) {
        $action['idregistro'] = $model->idregistro;
    }
} else {
    $action = ['registro_tecnico_incidencia/update', 'id' => $model->idincidencia];
}
?>
<style>
    <?php include __DIR__ . '/_form.css'; ?>
</style>


<div id="formulario_principal_incidencia">
    <?php $form = ActiveForm::begin(['id' => 'formulario_incidencia', 'action' => $action]); ?>

    <!-- El JS mantiene este hidden igual a movimientosArray; el controller lo deserializa -->
    <input type="hidden" id="movimientosArrayInput" name="movimientosArrayInput">

    <div class="row" id="div_principal">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idingresante', 'input_idingresante_inc', $ingresantes, 'idempleado', 'descripcion', 'Ingresante / Solicitante') ?>
        </div>
        <div class="col-md-9">
            <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'input_iddispositivo_inc', $dispositivos, 'iddispositivo', 'descripcion', 'Dispositivo / Sector') ?>
        </div>
    </div>

    <!-- Inventario: se muestra sólo si el sector/dispositivo tiene ítems asignados -->
    <div class="row" id="div_row_inventario" style="display: none; margin-top: 10px; margin-bottom: 10px;">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="input_idinventario_inc">Equipo / Ítem de Inventario Asignado</label>
                <select id="input_idinventario_inc" name="RegistroTecnicoIncidencia[idinventario]" class="form-control">
                    <option value="">Seleccione un ítem del inventario...</option>
                </select>
            </div>
        </div>
    </div>

        <!-- este div debe mostrarse solo si existe un movimiento con estado 4 de entregado -->
    <div class="row" id="div_row_retiro" style="display: none; margin-top: 10px; margin-bottom: 10px;">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idretira', 'input_idretira_inc', $ingresantes, 'idempleado', 'descripcion', 'Retira...') ?>
        </div>
    </div>

    <!-- GRILLA DE MOVIMIENTOS -->
    <div class="row" id="div_grilla" style="border-radius: 5px; padding: 15px;">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-8">
                <label style="margin-top: 5px;">Movimientos de la Incidencia</label>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-success btn-sm" onclick="mostrar_abm_movimiento()">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>
        <div id="div_grilla_movimientos" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div>
    </div>


     <!--  -->

    <?php ActiveForm::end(); ?>
</div>

<!-- SUBFORMULARIO DE MOVIMIENTO (ABM) -->
<div class="row" id="abm_movimiento" style="display:none;">
    <div class="col-md-12">
        <section class="abm_movimiento_custom">
            <header class="abm_movimiento_header_custom">
                <h5><strong id="titulo_abm_movimiento"></strong></h5>
            </header>
            <div class="abm_movimiento_body_custom">
                <?= $this->render('_form_movimiento', [
                    'tecnicos_asistencia' => $tecnicos_asistencia,
                    'esAlta' => $model->isNewRecord,
                ]) ?>
            </div>
        </section>
    </div>
</div>

<script>
    <?= file_get_contents(__DIR__ . '/_form.js'); ?>
</script>