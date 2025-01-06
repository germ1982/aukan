<?php

use app\controllers\SiteController;
use app\models\Empleado;
use app\models\Persona;
use app\models\StockInformaticaEgresoDetalle;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));

$array_detalles = $model->isNewRecord
    ? []
    : StockInformaticaEgresoDetalle::find()
    ->select(['idarticulo', 'cantidad']) // Selecciona solo los campos necesarios
    ->where(['idegreso' => $model->idegreso])
    ->asArray() // Obtiene un array simple
    ->all();

// Convertir el array PHP a JSON
$json_detalles = Json::htmlEncode($array_detalles);
$this->registerJs("let detallesArray = $json_detalles;", \yii\web\View::POS_HEAD);

$idUsuario = Yii::$app->user->identity->id;
$model->idusuario_carga = $model->isNewRecord ? $idUsuario : $model->idusuario_carga;
$model->idusuario_edicion = $idUsuario;


$persona_nombre_solicitante = '';
$persona_nombre_receptor = '';

if(isset($model->idpersona_solicitante)){
    $persona = Persona::findOne($model->idpersona_solicitante);
    $model->documento_solicitante = $persona->documento;
    $persona_nombre_solicitante = "$persona->apellido, $persona->nombre";
}

if(isset($model->idpersona_recibe)){
    $persona = Persona::findOne($model->idpersona_recibe);
    $model->documento_receptor = $persona->documento;
    $persona_nombre_receptor = "$persona->apellido, $persona->nombre";
}

?>

<div id="formulario_principal">
    <?php $form = ActiveForm::begin(['id' => 'formulario']); ?>

    <?= $form->field($model, 'idegreso')->hiddenInput(["id" => "hidden_input_id_model"])->label(false) ?>
    <?= $form->field($model, 'idpersona_solicitante')->hiddenInput(['id' => 'input_idpersona_solicitante'])->label(false) ?>
    <?= $form->field($model, 'idpersona_recibe')->hiddenInput(['id' => 'input_idpersona_recibe'])->label(false) ?>

    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <?= $form->field($model, 'documento_solicitante')->textInput([
                    'id' => 'input_dni_solicitante',
                    'onkeyup' => 'ValidarIngresoDniSolicitante();',
                ])
                    ->label($model->isNewRecord ? 'Buscar Solicitante' : 'DNI Solicitante') ?>
                <span class="input-group-btn" style="padding-top:27px;">
                    <?= SiteController::actionGet_boton_buscar_x_documento(
                        'btn_dni_solicitante',
                        'Buscar Dni',
                        'datos_persona_solicitante();'
                    ) ?>
                </span>
            </div>
        </div>
        <div class="col-md-3" style="padding-top:30px;" id="txt_mensaje_solicitante"><?= $persona_nombre_solicitante ?></div>
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_autorizacion', 'cmb_idempleado_autorizacion', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Autorizacion') ?>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_despacha', 'cmb_idempleado_despacha', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Despachante') ?>
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <?= $form->field($model, 'documento_receptor')->textInput([
                    'id' => 'input_dni_receptor',
                    'onkeyup' => 'ValidarIngresoDniReceptor();',
                ])
                    ->label($model->isNewRecord ? 'Buscar Receptor' : 'DNI Receptor') ?>
                <span class="input-group-btn" style="padding-top:27px;">
                    <?= SiteController::actionGet_boton_buscar_x_documento(
                        'btn_dni_receptor',
                        'Buscar Dni',
                        'datos_persona_receptor();'
                    ) ?>
                </span>
            </div>

        </div>

        <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje_receptor"><?= $persona_nombre_receptor ?></div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>