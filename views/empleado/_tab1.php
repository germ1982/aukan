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

/** @var yii\web\View $this */
/** @var app\models\Empleado $model */
/** @var yii\widgets\ActiveForm $form */

if (isset($model->idpersona)) {
    $persona = Persona::findOne($model->idpersona);
    $model->documento = $persona->documento;
    //$persona_nombre = "$persona->apellido, $persona->nombre";
}

if ($model->isNewRecord) {
    $model->activo = 1; // Por defecto, activo al crear un nuevo registro
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
        <?= $form->field($model, 'cuil')->textInput(['id' => 'input_cuil']) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'telefono')->textInput(['id' => 'input_telefono']) ?>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'email')->textInput(['id' => 'input_email']) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if ($model->origen_alta == 0): ?>
            <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivos', OrganismoDispositivo::get_dispositivos_con_decreto_inverso(), 'iddispositivo', 'descripcion', 'Sector', 'Seleccione Sector...') ?>
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



function asignar_datos_idpersona(data, esNuevo = true) {
    console.log('asignar_datos_idpersona:', data);
    
    // Si NO es un nuevo alta (ej: carga inicial del update), solo seteamos los campos y salimos
    if (!esNuevo) {
        $('#input_documento_idpersona').val(data['documento']);
        let nombre_idpersona = data['apellido'] + ', ' + data['nombre'];
        $('#txt_mensaje_idpersona').html(nombre_idpersona);
        return;
    }

    // Si es un alta o una nueva búsqueda en el buscador, validamos en la BD
    $.ajax({
        url: 'index.php?r=empleado/check-empleado',
        type: 'POST',
        data: { idpersona: data['idpersona'], documento: data['documento'] },
        success: function(response) {
            if (response.esEmpleado) {
                
                let texto = '<div style="text-align:center"><h2>El empleado ya existe</h2></div><br><div style="text-align:center"><img src="https://c.tenor.com/AacEyKSHWx4AAAAd/tenor.gif" alt="gif" style="width:150px; height:100px;"><br><h4>Ir a edición...</h4></div>';
                
                $.alert({
                    title: '',
                    content: texto,
                    type: 'orange',
                    onClose: function() {
                        let urlUpdate = 'index.php?r=empleado/update&id=' + response.idempleado;
                        
                        let instance = $('#ajaxCrudModal').data('modalRemoteInstance') || window.modalRemote;
                        
                        if (instance && typeof instance.doRemote === 'function') {
                            instance.doRemote(urlUpdate, 'GET');
                        } else {
                            $('<a>', {
                                'href': urlUpdate,
                                'role': 'modal-remote'
                            }).appendTo('body').trigger('click').remove();
                        }
                    }
                });

            } else {
                // Si NO es empleado, completamos el alta normalmente
                $('#input_documento_idpersona').val(data['documento']);
                let nombre_idpersona = data['apellido'] + ', ' + data['nombre'];
                $('#txt_mensaje_idpersona').html(nombre_idpersona);
                get_cuil(data['documento'], data['genero']);
            }
        }
    });
}



function get_cuil(documento,genero){
    console.log('ingreso a get_cuil con: ' + documento + ' y genero: ' + genero);
        $('#loading').show();
        texto = $('#txt_mensaje_idpersona').html();
        $('#txt_mensaje_idpersona').html('Buscando persona con dni: ' + documento);
        $.post("index.php?r=persona/get_cuil&dni=" + documento + "&genero=" + genero, function (data) {
                    //$.post("index.php?r=persona/get_persona_renaper&dni=" + dni_persona + "&genero=F", function (data) {
                        console.log('data de get_cuil:');
                        console.log(data);

                        if (data) {
                            $('#input_cuil').val(data);
                            $('#txt_mensaje_idpersona').html(texto);
                        } else {
                            $('#txt_mensaje_idpersona').html(texto + " - No se encontro cuil en RENAPER");
                        }
                        $('#loading').hide();
                    });
    }
JS;


$this->registerJs($script);


if (!$model->isNewRecord) {
    $model_persona = Persona::findOne($model->idpersona);
    $modelJson = \yii\helpers\Json::encode($model_persona->attributes);

    // Evitamos declarar variables JS con let/const que choquen en re-renders
    $this->registerJs(<<<JS_UPDATE
        asignar_datos_idpersona($modelJson, false); 
    JS_UPDATE, WebView::POS_READY);
}

