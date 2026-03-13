<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\file\FileInput;

if (isset($model->idpersona)) {
    $persona = Persona::findOne($model->idpersona);
    $model->documento = $persona->documento;
    $persona_nombre = "$persona->apellido, $persona->nombre";
}

?>



<?php
$script = <<<JS

function datos_persona() {
        $('#input_idpersona').val('0');
        
        let dni_persona = $("#input_dni_persona").val();

        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=persona/validar_dni&dni=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            console.log("console.log('funcion datos_persona'); // POST a index.php?r=persona/validar_dni&dni=" + dni_persona);
            if (data.length === 0) {
                $('#txt_mensaje').html("No se encontraron datos de Persona con dni " + dni_persona);
                //buscar_en_renaper(dni_persona,tipo_persona);
            } else {
                console.log('funcion datos_persona // encontro');
                console.log(data);
                $('#input_idpersona').val(data[0]['idpersona']);

                aux = data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje').html(aux);
            }

        });


    }

    function ValidarIngresoDni() {
        var aux = event.which;

        if (aux == 13) //pregunto si fue el enter
        {
            datos_persona();
        }
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

<style>
    .linea_busqueda {
        margin-top: -20px;
    }
</style>



<?= $form->field($model, 'idpersona')->hiddenInput(['id' => 'input_idpersona'])->label(false) ?>

<div class="row linea_busqueda">
    <!-- Linea de busqueda -->
    <div class="col-md-5">
        <div class="input-group">
            <?= $form->field($model, 'documento')->textInput([
                'id' => 'input_dni_persona',
                'onkeyup' => 'ValidarIngresoDni();',
                //'disabled' => $generada
            ])
                ->label($model->isNewRecord ? 'Buscar Persona Por DNI' : 'DNI Persona') ?>
            <span class="input-group-btn" style="padding-top:27px;">
                <?= SiteController::actionGet_boton_buscar_x_documento(
                    'btn_dni',
                    'Buscar Dni',
                    'datos_persona(0);'
                ) ?>
            </span>
        </div>
    </div>
    <div class="col-md-7" style="padding-top:30px;" id="txt_mensaje"><?= $persona_nombre ?></div>
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
        <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivos', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Sector', 'Seleccione Sector...') ?>
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