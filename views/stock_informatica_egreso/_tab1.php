<?php

use app\controllers\SiteController;
use app\helpers\AppBuscarPersonaHelper;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use app\models\StockInformaticaEgresoDetalle;
use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var app\models\Empleado $model */
/** @var yii\widgets\ActiveForm $form */

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));

$idUsuario = Yii::$app->user->identity->id;
$model->idusuario_carga = $model->isNewRecord ? $idUsuario : $model->idusuario_carga;
$model->idusuario_edicion = $idUsuario;


$persona_nombre_solicitante = '';
$persona_nombre_receptor = '';

if (isset($model->idpersona_solicitante)) {
    $persona = Persona::findOne($model->idpersona_solicitante);
    if ($persona !== null) {
        $model->documento_solicitante = $persona->documento;
        $persona_nombre_solicitante = "$persona->apellido, $persona->nombre";
    } else {
        $model->documento_solicitante = null;
        $persona_nombre_solicitante = 'No encontrado';
    }
}

if (isset($model->idpersona_recibe)) {
    $persona = Persona::findOne($model->idpersona_recibe);
    if ($persona !== null) {
        $model->documento_receptor = $persona->documento;
        $persona_nombre_receptor = "$persona->apellido, $persona->nombre";
    } else {
        $model->documento_receptor = null;
        $persona_nombre_receptor = 'No encontrado';
    }
}


?>

<?= $form->field($model, 'idegreso')->hiddenInput(["id" => "hidden_input_id_model"])->label(false) ?>
<?= $form->field($model, 'documento_solicitante')->hiddenInput(['id' => 'input_documento_solicitante'])->label(false) ?>
<?= $form->field($model, 'documento_receptor')->hiddenInput(['id' => 'input_documento_receptor'])->label(false) ?>


<div class="row">
    <div class="col-md-2">
        <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
    </div>
    <div class="col-md-5">
        <?= AppBuscarPersonaHelper::widgetBuscarPersona($model, 'idpersona_solicitante', 'Solicitante', 5, 7) ?>
    </div>


    <div class="col-md-5">
        <?= SiteController::actionGet_input_select2($form, $model, 'id_dispositivo_destino', 'cmb_id_dispositivo_destino', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Destino') ?>
    </div>

</div>

<div class="row">
    <div class="col-md-3">
        <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_autorizacion', 'cmb_idempleado_autorizacion', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Autorizacion') ?>
    </div>
    <div class="col-md-3">
        <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_despacha', 'cmb_idempleado_despacha', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Despachante') ?>
    </div>

    <div class="col-md-5">
        <?= AppBuscarPersonaHelper::widgetBuscarPersona($model, 'idpersona_recibe', 'Receptor', 5, 7) ?>
    </div>



</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'observacion')->textarea(['rows' => 4]) ?>
    </div>
</div>



<?php
$script = <<<JS


// Callback automático para el Solicitante
function asignar_datos_idpersona_solicitante(data) {
    // Setea el DNI en el buscador
    $('#input_documento_idpersona_solicitante').val(data['documento']);
    
    // Muestra el nombre en el div de estado
    let nombre = data['apellido'] + ', ' + data['nombre'];
    $('#txt_mensaje_idpersona_solicitante').html(nombre);
}

// Callback automático para el Receptor (Persona que recibe)
function asignar_datos_idpersona_recibe(data) {
    // Setea el DNI en el buscador
    $('#input_documento_idpersona_recibe').val(data['documento']);
    
    // Muestra el nombre en el div de estado
    let nombre = data['apellido'] + ', ' + data['nombre'];
    $('#txt_mensaje_idpersona_recibe').html(nombre);
}

function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }
JS;
$this->registerJs($script);
?>

<?php
// Si estamos editando un egreso existente, inicializamos los datos limpiamente
if (!$model->isNewRecord) {
    // Inicializar Solicitante
    if ($model->idpersona_solicitante) {
        $solicitante = Persona::findOne($model->idpersona_solicitante);
        if ($solicitante !== null) {
            $solicitanteJson = Json::encode($solicitante->attributes);
            $this->registerJs("asignar_datos_idpersona_solicitante($solicitanteJson);", \yii\web\View::POS_READY);
        }
    }

    // Inicializar Receptor
    if ($model->idpersona_recibe) {
        $receptor = Persona::findOne($model->idpersona_recibe);
        if ($receptor !== null) {
            $receptorJson = Json::encode($receptor->attributes);
            $this->registerJs("asignar_datos_idpersona_recibe($receptorJson);", \yii\web\View::POS_READY);
        }
    }
}
?>