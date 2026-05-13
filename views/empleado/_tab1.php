<?php

use app\controllers\SiteController;
use app\helpers\AppBuscarPersonaHelper;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\web\View as WebView;

if (isset($model->idpersona)) {
    $persona = Persona::findOne($model->idpersona);
    $model->documento = $persona->documento;
    //$persona_nombre = "$persona->apellido, $persona->nombre";
}

?>


<?= Html::activeHiddenInput($model, 'documento', ['id' => 'input_documento']); ?>

<div class="row linea_busqueda">
    <!-- Linea de busqueda -->
    <div class="col-md-12">
        <?= AppBuscarPersonaHelper::widgetBuscarPersona($model, 'idpersona', 'Documento', 5, 7) ?>
    </div>

</div>

<br>

<div class="row">

    <div class="col-md-3">
        <?= $form->field($model, 'legajo')->textInput() ?>
    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'cuil')->textInput() ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'telefono')->textInput() ?>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if ($model->origen_alta == 0): ?>
                        <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivos', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Sector', 'Seleccione Sector...') ?>
                    <?php else: ?>


                        <label class="control-label"><?= $model->getAttributeLabel('iddispositivo') ?></label>
                        <p class="form-control-static" style="background: #eee; padding: 6px 12px; border-radius: 4px;">
                            <?= $model->iddispositivo ? OrganismoDispositivo::findOne($model->iddispositivo)->descripcion : '' ?>
                        </p>

                        <?= $form->field($model, 'iddispositivo')->hiddenInput()->label(false) ?>

                    <?php endif; ?>
        
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <?= SiteController::actionGet_input_select2($form, $model, 'funcion', 'cmb_funcion', Configuracion::get_configuraciones(ConfiguracionTipo::FUNCION_LABORAL), 'id_configuracion', 'descripcion', 'Funcion', 'Seleccione Funcion...') ?>
    </div>

    <div class="col-md-4" style="padding-top:30px;">
        <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
    </div>
</div>



<?php
$script = <<< JS

function asignar_datos_idpersona(data){
    $('#input_documento_idpersona').val(data['documento']);
    let nombre_idpersona = data['apellido'] + ', ' + data['nombre'];
    $('#txt_mensaje_idpersona').html(nombre_idpersona);
    }
JS;


$this->registerJs($script);



if (!$model->isNewRecord) {
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
}

$this->registerJs($script);