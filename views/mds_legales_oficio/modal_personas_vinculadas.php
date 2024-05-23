<?php

use yii\helpers\Html;
use kartik\date\DatePicker;

?>

<style>
    .botones-persona-vinculada {
        margin-top: 10px;
    }
</style>

<div class="modal fade" id="modalPersonasVinculadas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Personas vinculadas</h4>
            </div>
            <div class="modal-body">
                <div>
                    <p><u>Listado de personas vinculadas al requerimiento:</u></p>
                    <p id="TEXTO_NO_EXISTEN_PERSONAS">No existen personas vinculadas.</p>
                    <ul id="LISTADO_PERSONAS_VINCULADAS">
                    </ul>
                </div>
                <hr>
                <div>
                    <button id="AGREGAR_PERSONA" type="button" class="btn btn-success" onclick="mostrarPersonaVinculadaContainer()">Agregar persona</button>
                </div>
                <div id="PERSONA_VINCULADA_CONTAINER" style="display:none;">
                    <div class="alert alert-info" role="alert" id="ALERT_BUSCAR_PERSONA" style="display:none;">
                        Debe verificar si la persona a la que desea vincular <b>ya existe en el sistema</b>. Para ello, debe seleccionar el <b>"Tipo de documento"</b>, escribir el <b>"Nro. de documento"</b> (sin puntos ni espacios) y presionar en la <b>lupa</b>. De no existir, deberá <b>completar como mínimo un dato de la misma</b>.
                    </div>
                    <input type="hidden" id="hidden_idpersona">
                    <div class="row form-group">
                        <div class="col-md-12">
                            ¿Conoce el Nro. de documento?
                            <div style="padding-top:6px;">
                                <?=
                                Html::dropdownList(
                                    'CONOCE_DOCUMENTO',
                                    '',
                                    [1 => 'Si', 0 => 'No'],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione...',
                                            'options' => ['disabled' => true, 'selected' =>  true]
                                        ],
                                        'id' => 'CONOCE_DOCUMENTO',
                                        'class' => 'form-control input-md padding-top:6px;',
                                        'onChange' => 'conoceDocumento()'
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger" role="alert" id="ALERT_COMPLETAR_DATOS" style="display: none;">
                        Debe completar <b>"Parentesco"</b> y como mínimo <b>un dato de la persona</b>.
                    </div>
                    <div class="alert alert-danger" role="alert" id="ALERT_DNI_REPETIDO" style="display: none;">
                        El <b>"Nro. de documento" ya está vinculado</b> en este requerimiento.
                    </div>
                    <div class="row form-group" id="DATOS_DNI_CONTAINER" style="display: none;">
                        <div class="col-md-6 form-group">
                            Tipo de documento
                            <div style="padding-top:6px;">
                                <?=
                                Html::dropdownList(
                                    'PERSONA_VINCULADA_TIPO_DOCUMENTO',
                                    '',
                                    $listTipoDocumento,
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione...',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'PERSONA_VINCULADA_TIPO_DOCUMENTO',
                                        'class' => 'form-control input-md padding-top:6px;',
                                        'disabled' => false,
                                    ]
                                );
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Nro. de documento</label>
                            <div style="display: flex;">
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_DNI">
                                <?php echo Html::a(
                                    '<i class="glyphicon glyphicon-search"></i>',
                                    null,
                                    [
                                        'name' => 'btn_dni',
                                        'id' => 'btn_dni',
                                        'data-request-method' => 'post',
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-primary',
                                        'title' => Yii::t('app', 'Consultar DNI'),
                                        'style' => 'margin-left: 10px;'
                                    ]
                                ); ?>
                            </div>
                            <span id="txt_mensaje"></span>
                        </div>

                    </div>

                    <div id="DATOS_PERSONA_CONTAINER" style="display: none;">
                        <div class="row form-group">
                            <div class="col-md-12">
                                Parentesco
                                <div style="padding-top:6px;">
                                    <?=
                                    Html::dropdownList(
                                        'mds_legales_oficio_vinculado[idparentesco]',
                                        '',
                                        $listParentesco,
                                        [
                                            'prompt' => [
                                                'text' => 'Seleccione...',
                                                'options' => ['disabled' => true, 'selected' => true]
                                            ],
                                            'id' => 'PERSONA_VINCULADA_ID_PARENTESCO',
                                            'class' => 'form-control input-md padding-top:6px;',
                                        ]
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 form-group">
                                <label>Apellido</label>
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_APELLIDO">
                            </div>
                            <div class="col-md-6">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_NOMBRE">
                            </div>
                        </div>
                        <div class="row form-group" id="DATOS_SDS_COM_PERSONA_CONTAINER">
                            <div class="col-md-4 form-group">
                                Género
                                <div style="padding-top:6px;">
                                    <?= Html::dropDownList(
                                        'PERSONA_VINCULADA_GENERO',
                                        '',
                                        $tipoGenero,
                                        [
                                            'prompt' => [
                                                'text' => 'Seleccione...',
                                                'options' => ['disabled' => true, 'selected' =>  true]
                                            ],
                                            'id' => 'PERSONA_VINCULADA_GENERO',
                                            'class' => 'form-control input-md padding-top:6px;',
                                        ]
                                    ) ?>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                Nacionalidad
                                <div style="padding-top:6px;">
                                    <?= Html::dropDownList(
                                        'PERSONA_VINCULADA_NACIONALIDAD',
                                        '',
                                        $tipoNacionalidad,
                                        [
                                            'prompt' => [
                                                'text' => 'Seleccione...',
                                                'options' => ['disabled' => true, 'selected' =>  true]
                                            ],
                                            'id' => 'PERSONA_VINCULADA_NACIONALIDAD',
                                            'class' => 'form-control input-md padding-top:6px;',
                                        ]
                                    ) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="">Fecha Nacimiento</label>
                                <?php
                                echo DatePicker::widget([
                                    'name' => 'check_issue_date',
                                    'language' => 'es',
                                    'readonly' => false,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'PERSONA_VINCULADA_FECHA_NACIMIENTO',
                                        'class' => 'form-control input-md',
                                        'disabled' => false,
                                        'autocomplete' => 'off',
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'format' => 'dd/mm/yyyy',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 form-group">
                                <label>Domicilio Calle</label>
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_DOMICILIO_CALLE">
                            </div>
                            <div class="col-md-6">
                                <label>Domicilio Número</label>
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_DOMICILIO_NUMERO">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 form-group">
                                <label>Mail</label>
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_MAIL">
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono</label>
                                <input type="text" class="form-control" id="PERSONA_VINCULADA_TELEFONO">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Observaciones</label>
                                <textarea class="form-control" id="PERSONA_VINCULADA_OBSERVACIONES" cols="5" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <button type="button" class="btn btn-danger" onclick="resetPersonaVinculada()">Cancelar</button>
                        <button type="button" id="boton-guardar-vincular-persona" class="btn btn-success" disabled="" onclick="agregarPersonaVinculada()">Guardar</button>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    "$(document).ready(function() {
        $(`#PERSONA_VINCULADA_NOMBRE, 
            #PERSONA_VINCULADA_APELLIDO,
            #PERSONA_VINCULADA_DOMICILIO_CALLE,
            #PERSONA_VINCULADA_DOMICILIO_NUMERO,
            #PERSONA_VINCULADA_MAIL,
            #PERSONA_VINCULADA_TELEFONO,
            #PERSONA_VINCULADA_OBSERVACIONES
        `).on('input', function() {
            validarFormulario();
        });

        $(`#PERSONA_VINCULADA_DNI`).on('input', function() {
            const value = this.value;
            this.value = this.value.replace(/\D+/g, '');
            if (value == this.value || !this.value) {
                validarDocumento();
            }
        });

        $('#PERSONA_VINCULADA_ID_PARENTESCO').change(function() {
            validarFormulario();
        });
    });

    $('#btn_dni').click(function(){
        limpiarDatosPersona();
        $('#DATOS_PERSONA_CONTAINER').hide();
        datos_persona();
    });

    "
); ?>

<script>
    function datos_persona() {
        let dni = 0;
        const idTipoDocumento = $('#PERSONA_VINCULADA_TIPO_DOCUMENTO').val();
        const dni_campo = $('#PERSONA_VINCULADA_DNI').val();
        let esDniRepetido = false;
        let arrayPersonasVinculadas = $("#array_personas_vinculadas").val();
        arrayPersonasVinculadas = arrayPersonasVinculadas ? JSON.parse(arrayPersonasVinculadas) : null;
        $("#ALERT_DNI_REPETIDO").hide();
        //Cuando se agregue id tipo documento a sds_com_persona, validar que este no sea vacio para hacer la llamada a getPersonaByDniAndIdTipoDocumento
        if (dni_campo != '' && dni != dni_campo) {

            if (arrayPersonasVinculadas && arrayPersonasVinculadas.filter(persona => persona.nroDocumento === dni_campo).length) {
                esDniRepetido = true;
                $("#ALERT_DNI_REPETIDO").show();
            }

            if (!esDniRepetido) {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;

                $.ajax({
                    url: `index.php?r=sds_com_persona/get_persona_by_dni_and_tipo&idTipoDocumento=${idTipoDocumento}&dni=${dni}&llamadoDesde=LEGALES`,
                    error: function() {
                        $("#txt_mensaje").html("La conexión con RENAPER no pudo realizarse. Por favor, haga la carga manual");
                        $("#DATOS_PERSONA_CONTAINER").show();
                        $("#DATOS_SDS_COM_PERSONA_CONTAINER").hide();
                        limpiarDatosPersona();
                    },
                    success: function(response) {
                        response = $.parseJSON(response);
                        if (response?.success) {
                            $("#hidden_idpersona").val(response?.data.idpersona);
                            $("#PERSONA_VINCULADA_APELLIDO").val(`${response?.data.apellido ? response?.data.apellido : ''}`);
                            $("#PERSONA_VINCULADA_NOMBRE").val(`${response?.data.nombre ? response?.data.nombre : ''}`);
                            $("#PERSONA_VINCULADA_FECHA_NACIMIENTO").val(`${response?.data.fecha_nacimiento ? response?.data.fecha_nacimiento : ''}`);
                            $("#PERSONA_VINCULADA_GENERO").val(`${response?.data.genero ? response?.data.genero : ''}`);
                            $("#PERSONA_VINCULADA_NACIONALIDAD").val(`${response?.data.nacionalidad ? response?.data.nacionalidad : ''}`);
                            $("#PERSONA_VINCULADA_DOMICILIO_CALLE").val(`${response?.data.domicilio_calle ? response?.data.domicilio_calle : ''}`);
                            $("#PERSONA_VINCULADA_DOMICILIO_NUMERO").val(`${response?.data.domicilio_numero ? response?.data.domicilio_numero : ''}`);
                            $("#PERSONA_VINCULADA_MAIL").val(`${response?.data.mail ? response?.data.mail : ''}`);
                            $("#PERSONA_VINCULADA_TELEFONO").val(`${response?.data.telefono ? response?.data.telefono : ''}`);
                            $("#PERSONA_VINCULADA_OBSERVACIONES").val(`${response?.data.observaciones ? response?.data.observaciones : ''}`);
                            $('#txt_mensaje').html("");
                            $("#DATOS_PERSONA_CONTAINER, #DATOS_SDS_COM_PERSONA_CONTAINER").show();
                        } else {
                            $("#txt_mensaje").html(response?.message);
                            $("#DATOS_PERSONA_CONTAINER").show();
                            $("#DATOS_SDS_COM_PERSONA_CONTAINER").hide();
                            limpiarDatosPersona();
                        }
                        validarFormulario();
                    },
                    timeout: 20000
                });
            }
        }
    }

    function limpiarDatos() {
        $(`#PERSONA_VINCULADA_DNI,
        #PERSONA_VINCULADA_TIPO_DOCUMENTO,
        #PERSONA_VINCULADA_ID_PARENTESCO,
        #PERSONA_VINCULADA_APELLIDO,
        #PERSONA_VINCULADA_NOMBRE,
        #PERSONA_VINCULADA_GENERO,
        #PERSONA_VINCULADA_NACIONALIDAD,
        #PERSONA_VINCULADA_FECHA_NACIMIENTO,
        #PERSONA_VINCULADA_DOMICILIO_CALLE,
        #PERSONA_VINCULADA_DOMICILIO_NUMERO,
        #PERSONA_VINCULADA_MAIL,
        #PERSONA_VINCULADA_TELEFONO,
        #PERSONA_VINCULADA_OBSERVACIONES,
        #hidden_idpersona
        `).val('');
        $("#txt_mensaje").html('');
        $("#ALERT_COMPLETAR_DATOS, #ALERT_DNI_REPETIDO").hide();
    }

    function limpiarDatosPersona() {
        $(`#PERSONA_VINCULADA_ID_PARENTESCO,
        #PERSONA_VINCULADA_APELLIDO,
        #PERSONA_VINCULADA_NOMBRE,
        #PERSONA_VINCULADA_GENERO,
        #PERSONA_VINCULADA_NACIONALIDAD,
        #PERSONA_VINCULADA_FECHA_NACIMIENTO,
        #PERSONA_VINCULADA_DOMICILIO_CALLE,
        #PERSONA_VINCULADA_DOMICILIO_NUMERO,
        #PERSONA_VINCULADA_MAIL,
        #PERSONA_VINCULADA_TELEFONO,
        #PERSONA_VINCULADA_OBSERVACIONES,
        #hidden_idpersona
        `).val('');
        $("#ALERT_COMPLETAR_DATOS, #ALERT_DNI_REPETIDO").hide();
    }

    function getIdLocalidad(localidad) {
        $.post("index.php?r=sds_800_llamada/get_id_localidad&localidad=" + localidad, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                return "";
            } else {
                $("#sds_800_llamada-localidad").val(data['idlocalidad']);
            }
        });
    }

    function habilitar_controles() {
        $("#PERSONA_VINCULADA_DNI").prop("disabled", false);
        $("#PERSONA_VINCULADA_NOMBRE").prop("disabled", false);
        $("#PERSONA_VINCULADA_ID_PARENTESCO").prop("disabled", false);
        $("#PERSONA_VINCULADA_DOMICILIO_CALLE").prop("disabled", false);
        $("#PERSONA_VINCULADA_DOMICILIO_NUMERO").prop("disabled", false);
        $("#PERSONA_VINCULADA_MAIL").prop("disabled", false);
        $("#PERSONA_VINCULADA_TELEFONO").prop("disabled", false);
        $("#PERSONA_VINCULADA_OBSERVACIONES").prop("disabled", false);
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function conoceDocumento() {
        limpiarDatos();
        const conoceDocumento = $("#CONOCE_DOCUMENTO").val();
        definirConoceDocumento(conoceDocumento);
    }

    function definirConoceDocumento(conoceDocumento) {
        validarFormulario();
        if (conoceDocumento == 1) {
            $("#DATOS_DNI_CONTAINER, #ALERT_BUSCAR_PERSONA").show();
            $("#DATOS_PERSONA_CONTAINER, #ALERT_COMPLETAR_DATOS").hide();
        } else if (conoceDocumento == 0) {
            $("#DATOS_PERSONA_CONTAINER").show();
            $("#DATOS_DNI_CONTAINER, #DATOS_SDS_COM_PERSONA_CONTAINER, #ALERT_BUSCAR_PERSONA").hide();
        } else {
            $("#DATOS_PERSONA_CONTAINER, #DATOS_DNI_CONTAINER, #DATOS_SDS_COM_PERSONA_CONTAINER, #ALERT_BUSCAR_PERSONA, #ALERT_COMPLETAR_DATOS").hide();

        }
    }

    function validarFormulario() {
        const nroDocumento = $('#PERSONA_VINCULADA_DNI').val();
        const apellido = $('#PERSONA_VINCULADA_APELLIDO').val();
        const nombre = $('#PERSONA_VINCULADA_NOMBRE').val();
        const parentesco = $('#PERSONA_VINCULADA_ID_PARENTESCO').val();
        const domicilioCalle = $('#PERSONA_VINCULADA_DOMICILIO_CALLE').val();
        const domicilioNumero = $('#PERSONA_VINCULADA_DOMICILIO_NUMERO').val();
        const mail = $('#PERSONA_VINCULADA_MAIL').val();
        const telefono = $('#PERSONA_VINCULADA_TELEFONO').val();
        const observaciones = $('#PERSONA_VINCULADA_OBSERVACIONES').val();
        const datosCompletos = parentesco && (nroDocumento || apellido || nombre || domicilioCalle || domicilioNumero || mail || telefono || observaciones);
        if (datosCompletos) {
            $('#ALERT_COMPLETAR_DATOS').hide();
            $('#boton-guardar-vincular-persona').prop('disabled', false);
        } else {
            $('#ALERT_COMPLETAR_DATOS').show();
            $('#boton-guardar-vincular-persona').prop('disabled', true);
        }
    }

    function validarDocumento() {
        const nroDocumento = $('#PERSONA_VINCULADA_DNI').val();
        $("#txt_mensaje").html("");
        $('#ALERT_COMPLETAR_DATOS, #ALERT_DNI_REPETIDO, #DATOS_PERSONA_CONTAINER').hide();
        $('#boton-guardar-vincular-persona').prop('disabled', true);
        limpiarDatosPersona();
    }

    function agregarPersonaVinculada(personaVinculadaUltimoRequerimiento = null) {
        let idPersona = $('#hidden_idpersona').val();
        let tipoDocumento = $('#PERSONA_VINCULADA_TIPO_DOCUMENTO').val();
        let nroDocumento = $('#PERSONA_VINCULADA_DNI').val() ? $('#PERSONA_VINCULADA_DNI').val() : '';
        let nombre = $('#PERSONA_VINCULADA_NOMBRE').val() ? $('#PERSONA_VINCULADA_NOMBRE').val().toUpperCase() : '';
        let apellido = $('#PERSONA_VINCULADA_APELLIDO').val() ? $('#PERSONA_VINCULADA_APELLIDO').val().toUpperCase() : '';
        let apellidoString = $('#PERSONA_VINCULADA_APELLIDO').val() ? (nombre ? $('#PERSONA_VINCULADA_APELLIDO').val().toUpperCase() + ", " : $('#PERSONA_VINCULADA_APELLIDO').val().toUpperCase()) : '';
        let parentesco = $('#PERSONA_VINCULADA_ID_PARENTESCO').val() ? $('#PERSONA_VINCULADA_ID_PARENTESCO').val() : '';
        let parentescoTexto = $('#PERSONA_VINCULADA_ID_PARENTESCO').val() ? $("#PERSONA_VINCULADA_ID_PARENTESCO option:selected").text() : '';
        let parentescoPointStart = parentescoTexto ? parentescoTexto.indexOf(".") + 1 : 0;
        parentescoTexto = parentescoTexto ? parentescoTexto.substring(parentescoPointStart, parentescoTexto.length) : '';
        let domicilioCalle = $('#PERSONA_VINCULADA_DOMICILIO_CALLE').val() ? $('#PERSONA_VINCULADA_DOMICILIO_CALLE').val() : '';
        let domicilioNumero = $('#PERSONA_VINCULADA_DOMICILIO_NUMERO').val() ? $('#PERSONA_VINCULADA_DOMICILIO_NUMERO').val() : '';
        let mail = $('#PERSONA_VINCULADA_MAIL').val() ? $('#PERSONA_VINCULADA_MAIL').val() : '';
        let telefono = $('#PERSONA_VINCULADA_TELEFONO').val() ? $('#PERSONA_VINCULADA_TELEFONO').val() : '';
        let observaciones = $('#PERSONA_VINCULADA_OBSERVACIONES').val() ? $('#PERSONA_VINCULADA_OBSERVACIONES').val() : '';
        let genero = $('#PERSONA_VINCULADA_GENERO').val() ? $('#PERSONA_VINCULADA_GENERO').val() : '';
        let nacionalidad = $('#PERSONA_VINCULADA_NACIONALIDAD').val() ? $('#PERSONA_VINCULADA_NACIONALIDAD').val() : '';
        let fechaNacimiento = $('#PERSONA_VINCULADA_FECHA_NACIMIENTO').val() ? $('#PERSONA_VINCULADA_FECHA_NACIMIENTO').val() : '';

        if (personaVinculadaUltimoRequerimiento) {
            idPersona = personaVinculadaUltimoRequerimiento.idpersona;
            tipoDocumento = personaVinculadaUltimoRequerimiento.idtipodocumento;
            nroDocumento = personaVinculadaUltimoRequerimiento.documento ? personaVinculadaUltimoRequerimiento.documento : '';
            nombre = personaVinculadaUltimoRequerimiento.nombre ? personaVinculadaUltimoRequerimiento.nombre.toUpperCase() : '';
            apellido = personaVinculadaUltimoRequerimiento.apellido ? personaVinculadaUltimoRequerimiento.apellido.toUpperCase() : '';
            apellidoString = apellido ? (nombre ? `${apellido}, ` : apellido) : '';
            parentesco = personaVinculadaUltimoRequerimiento.idparentesco ? personaVinculadaUltimoRequerimiento.idparentesco : '';
            parentescoTexto = parentesco ? personaVinculadaUltimoRequerimiento.parentescoDescripcion : '';
            parentescoPointStart = parentescoTexto ? parentescoTexto.indexOf(".") + 1 : 0;
            parentescoTexto = parentescoTexto ? parentescoTexto.substring(parentescoPointStart, parentescoTexto.length) : '';
            domicilioCalle = personaVinculadaUltimoRequerimiento.domicilio_calle ? personaVinculadaUltimoRequerimiento.domicilio_calle : '';
            domicilioNumero = personaVinculadaUltimoRequerimiento.domicilio_numero ? personaVinculadaUltimoRequerimiento.domicilio_numero : '';
            mail = personaVinculadaUltimoRequerimiento.mail ? personaVinculadaUltimoRequerimiento.mail : '';
            telefono = personaVinculadaUltimoRequerimiento.telefono ? personaVinculadaUltimoRequerimiento.telefono : '';
            observaciones = personaVinculadaUltimoRequerimiento.observaciones ? personaVinculadaUltimoRequerimiento.observaciones : '';
            genero = personaVinculadaUltimoRequerimiento.genero ? personaVinculadaUltimoRequerimiento.genero : '';
            nacionalidad = personaVinculadaUltimoRequerimiento.nacionalidad ? personaVinculadaUltimoRequerimiento.nacionalidad : '';
            fechaNacimiento = personaVinculadaUltimoRequerimiento.fecha_nacimiento ? personaVinculadaUltimoRequerimiento.fecha_nacimiento : '';
        }

        const nroDocumentoString = nroDocumento ? `<b>DNI:</b> ${nroDocumento}` : '';
        const parentescoString = parentescoTexto ? `<b>Parentesco:</b> ${parentescoTexto}` : '';
        const nombreString = apellidoString || nombre ? `<b>Nombre:</b> ${apellidoString} ${nombre}` : '';
        const domicilioString = domicilioCalle || domicilioNumero ? `<b>Domicilio:</b> ${domicilioCalle} ${domicilioNumero}` : '';
        const mailString = mail ? `<b>Mail:</b> ${mail}` : '';
        const telefonoString = telefono ? `<b>Teléfono:</b> ${telefono}` : '';
        const observacionesString = observaciones ? `<b>Observaciones:</b> ${observaciones}` : '';
        const stringDatosPersona = `${nroDocumentoString} ${parentescoString} ${nombreString} ${domicilioString} ${mailString} ${telefonoString} ${observacionesString}`;
        let arrayPersonasVinculadas = $("#array_personas_vinculadas").val();
        arrayPersonasVinculadas = arrayPersonasVinculadas ? JSON.parse(arrayPersonasVinculadas) : [];
        const keyPersonaVinculada = arrayPersonasVinculadas.length == 0 ? 0 : arrayPersonasVinculadas[arrayPersonasVinculadas.length - 1].keyPersonaVinculada + 1; //Si no tiene elementos el key es 0, sino me fijo el key del ultimo elemento y le sumo 1

        const personaVinculada = {
            idPersona,
            tipoDocumento,
            nroDocumento,
            apellido,
            nombre,
            parentesco,
            domicilioCalle,
            domicilioNumero,
            mail,
            telefono,
            observaciones,
            genero,
            nacionalidad,
            fechaNacimiento,
            keyPersonaVinculada
        }

        arrayPersonasVinculadas ? arrayPersonasVinculadas.push(personaVinculada) : arrayPersonasVinculadas = [personaVinculada];

        $("#array_personas_vinculadas").val(JSON.stringify(arrayPersonasVinculadas));

        const lengthPersonasVinculadas = arrayPersonasVinculadas.length;
        const liPersonaVinculada = `<li id='LI_PERSONA_VINCULADA_${keyPersonaVinculada}'><button type="button" class="btn btn-link" style="padding: 0;" onclick="eliminarPersonaVinculada(${keyPersonaVinculada})"><i class="glyphicon glyphicon-trash"></i></button> <b>#${lengthPersonasVinculadas}</b> - ${stringDatosPersona} </li>`;
        $('#TEXTO_NO_EXISTEN_PERSONAS').hide();
        $('#LISTADO_PERSONAS_VINCULADAS').append(liPersonaVinculada);
        resetPersonaVinculada();
    }

    function mostrarPersonaVinculadaContainer() {
        limpiarDatos();
        $("#AGREGAR_PERSONA").hide();
        $("#PERSONA_VINCULADA_CONTAINER").show();
    }

    function eliminarPersonaVinculada(keyPersonaVinculada) {
        $(`#LI_PERSONA_VINCULADA_${keyPersonaVinculada}`).remove();
        let arrayPersonasVinculadas = $("#array_personas_vinculadas").val();
        arrayPersonasVinculadas = JSON.parse(arrayPersonasVinculadas);
        const resultFilterArrayPersonasVinculadas = arrayPersonasVinculadas.filter(personaVinculada => personaVinculada.keyPersonaVinculada != keyPersonaVinculada);

        if (resultFilterArrayPersonasVinculadas.length == 0) {
            $('#TEXTO_NO_EXISTEN_PERSONAS').show();
        }
        $("#array_personas_vinculadas").val(JSON.stringify(resultFilterArrayPersonasVinculadas));
    }

    function resetPersonaVinculada() {
        limpiarDatos();
        $("#CONOCE_DOCUMENTO").val("").trigger("change");
        $("#PERSONA_VINCULADA_CONTAINER").hide();
        $("#AGREGAR_PERSONA").show();
    }
</script>