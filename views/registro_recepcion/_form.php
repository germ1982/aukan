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


$script = <<<JS

let dni_persona = $("#input_dni_persona").val();
if(dni_persona){
    datos_persona();
}

function datos_persona() {
        
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
                $('#txt_mensaje').html("No se encontraron datos en Personas con dni " + dni_persona);
                datos_persona_no_homo();
                //buscar_en_renaper(dni_persona,tipo_persona);
            } else {
                console.log('funcion datos_persona // encontro en persona');
                console.log(data);
                rellenar_campos(data,1);
            }

        });


        }

        function datos_persona_no_homo() {
            
            let dni_persona = $("#input_dni_persona").val();

            $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);
            $.post("index.php?r=personas_no_homologadas/validar_dni&dni=" + dni_persona, function(data) {
                data = $.parseJSON(data);
                console.log("console.log('funcion datos_persona'); // POST a index.php?r=persona/validar_dni&dni=" + dni_persona);
                if (data.length === 0) {
                    $('#txt_mensaje').html("No se encontraron datos en  Persona no Homologadas con dni " + dni_persona);
                    rellenar_campos([],0);
                } else {
                    console.log('funcion datos_persona // encontro en personas no homologadas');
                    console.log(data);
                    rellenar_campos(data,2);
                }

            });


        }


        function rellenar_campos(data,tabla){

            if(tabla==0){
                $('#txt_mensaje').html('No se encontraron datos, rellene los campos para guardar en personas no homologadas');
                return
            }
            aux = data[0]['apellido'] + ', ' + data[0]['nombre'];

            if(tabla==1){
                $('#txt_mensaje').html( aux + ' datos encontrados en personas ');
            }

            if(tabla==2){
                $('#txt_mensaje').html( aux + ' datos encontrados en personas no homologadas');
            }
            console.log('data final: ' + data);

            $('#registrorecepcion-nombre').val(data[0]['nombre']);
            $('#registrorecepcion-apellido').val(data[0]['apellido']);
            $('#cmb_documento_tipo').val(data[0]['documento_tipo']).trigger('change');
            $('#cmb_nacionalidad').val(data[0]['nacionalidad']).trigger('change');
            $('#cmb_genero').val(data[0]['genero']).trigger('change');
            $('#input_fecha_nacimiento').val(data[0]['fecha_nacimiento']);

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