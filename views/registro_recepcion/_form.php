<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\OrganismoDispositivo;
use app\models\Empleado;
use yii\helpers\Url;
use app\models\Persona;


$persona_nombre = "";
$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y',strtotime($model->fecha));



?>



<div class="registro-recepcion-form">
    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'registro-recepcion-form',
        'enableAjaxValidation' => false,
        'options' => ['data-pjax' => false] // importante
    ]); ?>

    <div class="row linea_busqueda">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_select2($form, $model, 'documento_tipo', 'cmb_documento_tipo', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_DOCUMENTO), 'id_configuracion', 'descripcion', 'Tipo Documento', 'seleccione tipo documento...') ?>
        </div>
        <!-- Linea de busqueda -->
        <div class="col-md-3">
            <div class="input-group">
                <?= $form->field($model, 'dni')->textInput([
                    'id' => 'input_dni_persona',
                    'onkeyup' => 'ValidarIngresoDni();',
                    //'disabled' => $generada
                ])
                    ->label($model->isNewRecord ? 'Buscar Persona Por DNI' : 'DNI Persona') ?>
                <span class="input-group-btn" style="padding-top:27px;">
                    <?= SiteController::actionGet_boton_buscar_x_documento(
                        'btn_dni',
                        'Buscar Dni',
                        'datos_persona();'
                    ) ?>
                </span>
            </div>
        </div>
        <div class="col-md-7" style="padding-top:30px;" id="txt_mensaje"><?= $persona_nombre ?></div>
    </div>
    <hr>
    <div class="row">




        <div class="col-md-4    ">
            <?= $form->field($model, 'apellido')->textInput(['id' => 'registrorecepcion-apellido']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nombre')->textInput(['id' => 'registrorecepcion-nombre']) ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_nacimiento', 'input_fecha_nacimiento', 'Fecha Nacimiento') ?>
        </div>

    </div>
    <div class="row">

        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'nacionalidad', 'cmb_nacionalidad', Configuracion::get_configuraciones(ConfiguracionTipo::NACIONALIDAD), 'id_configuracion', 'descripcion', 'Nacionalidad', 'seleccione nacionalidad...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'genero', 'cmb_genero', Configuracion::get_configuraciones(ConfiguracionTipo::GENERO), 'id_configuracion', 'descripcion', 'Genero', 'seleccione genero...') ?>
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_fecha($form,$model,'fecha','input_fecha','Fecha')?>
        </div>


        <div class="col-md-2">
            <?= SiteController::actionGet_input_hora($form, $model, 'hora', 'input_hora', 'Hora') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'acceso')->dropDownList(
                \app\models\EdificioAcceso::getListaAccesos(),
                ['prompt' => 'Seleccione tipo de acceso...']
            ) ?>
        </div>

        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_tipo_recepcion', 'cmb_id_tipo_recepcion', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_RECEPCION), 'id_configuracion', 'descripcion', 'Tipo Recepcion', 'seleccione tipo recepcion...') ?>
        </div>

    </div>



    <div class="row">
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_responsable_derivacion', 'cmb_id_responsable_derivacion', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Responsable Derivacion', 'seleccione empleado...') ?>
        </div>
        <div class="col-md-8">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_dispositivo_derivacion', 'cmb_id_dispositivo_derivacion', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Dispositivo Derivacion', 'seleccione dispositivo...') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'motivo')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6, 'placeholder' => 'Ingrese una observación (opcional)']) ?>
        </div>
    </div>
                


    <?php ActiveForm::end(); ?>
</div>