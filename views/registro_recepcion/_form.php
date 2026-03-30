<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\helpers\AppBuscarPersonaHelper;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\OrganismoDispositivo;
use app\models\Empleado;
use yii\helpers\Url;
use app\models\Persona;


$persona_nombre = "";
$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));



?>



<div class="registro-recepcion-form">
    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'registro-recepcion-form',
        'enableAjaxValidation' => false,
        'options' => ['data-pjax' => false] // importante
    ]); ?>

<div class="row" style="padding-left: 15px;">
        <?= AppBuscarPersonaHelper::widgetBuscarPersona($model) ?>
    </div>


    <div class="row">



        <div class="col-md-2    ">
            <?= $form->field($model, 'dni')->textInput(['id' => 'registrorecepcion-dni']) ?>
        </div>
        <div class="col-md-4    ">
            <?= $form->field($model, 'apellido')->textInput(['id' => 'registrorecepcion-apellido']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nombre')->textInput(['id' => 'registrorecepcion-nombre']) ?>
        </div>
        <div class="col-md-2">
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
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'input_fecha', 'Fecha') ?>
        </div>

        <div class="col-md-3">
            <?= SiteController::actionGet_input_hora($form, $model, 'hora', 'input_hora', 'Hora') ?>
        </div>

    </div>
    <hr>
    <div class="row">

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



<?php
$script = <<< JS

function asignar_datos_idpersona(data){
    console.log(data);
    $('#registrorecepcion-dni').val(data['documento']);
    $('#registrorecepcion-apellido').val(data['apellido']);
    $('#registrorecepcion-nombre').val(data['nombre']);

    $('#input_fecha_nacimiento').val(data['fecha_nacimiento']);
    $('#cmb_genero').val(data['genero']).trigger('change');
    $('#cmb_nacionalidad').val(data['nacionalidad']).trigger('change');
    var fecha_nac = data['fecha_nacimiento'].split('-').reverse().join('/');

    $('#input_fecha_nacimiento').val(fecha_nac).trigger('change');

    }



JS;


$this->registerJs($script);



/* if (!$model->isNewRecord) {
    $model_persona = Persona::findOne($model->idpersona);
    
    // 1. Convertir los atributos del modelo (PHP) a una cadena JSON
    $modelJson = \yii\helpers\Json::encode($model_persona->attributes);

    $this->registerJs(<<<JS_UPDATE
        // Esta función se ejecuta solo al cargar la página en modo UPDATE
        let datosModelo = $modelJson; 
        console.log(datosModelo);
        
        // Llamamos a la función centralizada para rellenar los campos
        asignar_datos_idpersona(datosModelo); 
    JS_UPDATE, WebView::POS_READY); // POS_READY asegura que el DOM esté listo
} */

$this->registerJs($script);