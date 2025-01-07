<?php

use app\controllers\SiteController;
use app\models\Empleado;
use app\models\Persona;
use app\models\StockInformaticaEgresoDetalle;
use yii\helpers\Json;

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));

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

<?= $form->field($model, 'idegreso')->hiddenInput(["id" => "hidden_input_id_model"])->label(false) ?>
    <?= $form->field($model, 'idpersona_solicitante')->hiddenInput(['id' => 'input_idpersona_solicitante'])->label(false) ?>
    <?= $form->field($model, 'idpersona_recibe')->hiddenInput(['id' => 'input_idpersona_recibe'])->label(false) ?>

    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
        </div>
        <div class="col-md-2">
            <div class="input-group">
                <?= $form->field($model, 'documento_solicitante')->textInput([
                    'id' => 'input_dni_solicitante',
                    'onkeyup' => 'ValidarIngresoDni(1);',
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
        
        <div class="col-md-4">
            <label for="txt_mensaje_solicitante" class="form-label">Solicitante</label>
            <div class="form-control" id="txt_mensaje_solicitante">
                <?= $persona_nombre_solicitante ?>
            </div>
        </div>
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_autorizacion', 'cmb_idempleado_autorizacion', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Autorizacion') ?>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_despacha', 'cmb_idempleado_despacha', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Despachante') ?>
        </div>

        <div class="col-md-2">
            <div class="input-group">
                <?= $form->field($model, 'documento_receptor')->textInput([
                    'id' => 'input_dni_receptor',
                    'onkeyup' => 'ValidarIngresoDni(2);',
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

        <div class="col-md-4">
            <label for="txt_mensaje_receptor" class="form-label">Receptor</label>
            <div class="form-control" id="txt_mensaje_receptor">
                <?= $persona_nombre_receptor ?>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
        </div>
    </div>


    
<?php
$script = <<<JS

function datos_persona_solicitante() {
        $('#input_idpersona_solicitante').val('0');
        
        let dni_persona = $("#input_dni_solicitante").val();

        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#txt_mensaje_solicitante').html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=persona/validar_dni&dni=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            
            if (data.length === 0) {
                $('#txt_mensaje_solicitante').html("No se encontraron datos de Persona con dni " + dni_persona);
                //buscar_en_renaper(dni_persona,tipo_persona);
            } else {

                $('#input_idpersona_solicitante').val(data[0]['idpersona']);

                aux = data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje_solicitante').html(aux);
            }

        });


    }

    function datos_persona_receptor() {
        $('#input_idpersona_recibe').val('0');
        
        let dni_persona = $("#input_dni_receptor").val();

        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#txt_mensaje_receptor').html("Buscando datos de Persona con dni " + dni_persona);
        $.post("index.php?r=persona/validar_dni&dni=" + dni_persona, function(data) {
            data = $.parseJSON(data);
           
            if (data.length === 0) {
                $('#txt_mensaje_receptor').html("No se encontraron datos de Persona con dni " + dni_persona);
                //buscar_en_renaper(dni_persona,tipo_persona);
            } else {

                $('#input_idpersona_recibe').val(data[0]['idpersona']);

                aux = data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje_receptor').html(aux);
            }

        });


    }

    function ValidarIngresoDni(tipo) {
        var aux = event.which;

        if (aux == 13) //pregunto si fue el enter
        {
            if(tipo == 1) //1 para solicitante 2 para rceptor
                {datos_persona_solicitante();}
            else
                {datos_persona_receptor();}
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