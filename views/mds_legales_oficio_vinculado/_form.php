<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="sds-com-persona-form">
    <div class="alert alert-info" role="alert" id="ALERT_BUSCAR_PERSONA" style="display:none;">
        Debe verificar si la persona a la que desea vincular <b>ya existe en el sistema</b>. Para ello, debe seleccionar el <b>"Tipo de documento"</b>, escribir el <b>"Nro. de documento"</b> (sin puntos ni espacios) y presionar en la <b>lupa</b>. De no existir, deberá <b>completar como mínimo un dato de la misma</b>.
    </div>
    <?php $form = ActiveForm::begin(['action' => ["mds_legales_oficio_vinculado/store", 'idlegalesoficiovinculado' => $model->isNewRecord ? '' : $model->idlegalesoficiovinculado, 'idlegalesoficio' => $idlegalesoficio], 'id' => 'mds-legales-oficio-vinculado-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form
        ->field($model, 'idpersona')
        ->hiddenInput([
            'id' => 'hidden_idpersona',
        ])
        ->label(false) ?>
    <div class="row form-group">
        <div class="col-md-12">
            ¿Conoce el Nro. de documento?
            <div style="padding-top:6px;">
                <?=
                Html::dropdownList(
                    'CONOCE_DOCUMENTO',
                    $model->isNewRecord ? '' : ($model->idpersona || $model->documento ? '1' : '0'),
                    [1 => 'Si', 0 => 'No'],
                    [
                        'prompt' => [
                            'text' => 'Seleccione...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
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
                    'mds_legales_oficio_vinculado[idtipodocumento]',
                    $model->isNewRecord ? '' : ($model->idpersona ? $model->persona->documento_tipo : ($model->idtipodocumento ? $model->idtipodocumento : '')),
                    $listTipoDocumento,
                    [
                        'prompt' => [
                            'text' => 'Seleccione...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                        ],
                        'id' => 'mds_legales_oficio_vinculado-idtipodocumento',
                        'class' => 'form-control input-md padding-top:6px;',
                        //Cuando se filtre por tipo de documento descomentar el onchange
                        // 'onChange' => 'validarDocumento()'
                    ]
                );
                ?>
            </div>
        </div>

        <div class="col-md-6">
            <label class="control-label" for="txtDNI">Nro. de documento</label>
            <div style="display: flex;">
                <input type="text" id="txtDNI" class="form-control" name="mds_legales_oficio_vinculado[documento]" value="<?= $model->idpersona ? $model->persona->documento : $model->documento ?>">
                <?php
                echo Html::a(
                    '<i class="glyphicon glyphicon-search"></i>',
                    null,
                    [
                        'name' => 'btn_dni',
                        'id' => 'btn_dni',
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Consultar DNI'),
                        'style' => 'margin-left: 10px;',
                    ]
                );
                ?>
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
                        $model->isNewRecord ? '' : ($model->idparentesco ? $model->idparentesco : null),
                        $listParentesco,
                        [
                            'prompt' => [
                                'text' => 'Seleccione...',
                                'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                            ],
                            'id' => 'mds_legales_oficio_vinculado-idparentesco',
                            'class' => 'form-control input-md padding-top:6px;',
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <?= $form->field($model, 'apellido')->textInput(['value' => $model->idpersona ? $model->persona->apellido : $model->apellido, 'maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'nombre')->textInput(['value' => $model->idpersona ? $model->persona->nombre : $model->nombre, 'maxlength' => true]) ?>
            </div>
        </div>
        <div class="row form-group" id="DATOS_SDS_COM_PERSONA_CONTAINER">
            <div class="col-md-4 form-group">
                Género
                <div style="padding-top:6px;">
                    <?= Html::dropDownList(
                        'mds_legales_oficio_vinculado[genero]',
                        $model->isNewRecord ? '' : ($model->idpersona ? $model->persona->genero : null),
                        $tipoGenero,
                        [
                            'prompt' => [
                                'text' => 'Seleccione...',
                                'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                            ],
                            'id' => 'mds_legales_oficio_vinculado-genero',
                            'class' => 'form-control input-md padding-top:6px;',
                        ]
                    ) ?>
                </div>
            </div>
            <div class="col-md-4 form-group">
                Nacionalidad
                <div style="padding-top:6px;">
                    <?= Html::dropDownList(
                        'mds_legales_oficio_vinculado[nacionalidad]',
                        $model->isNewRecord ? '' : ($model->idpersona ? $model->persona->nacionalidad : null),
                        $tipoNacionalidad,
                        [
                            'prompt' => [
                                'text' => 'Seleccione...',
                                'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                            ],
                            'id' => 'mds_legales_oficio_vinculado-nacionalidad',
                            'class' => 'form-control input-md padding-top:6px;',
                        ]
                    ) ?>
                </div>
            </div>
            <div class="col-md-4">
                <label for="">Fecha Nacimiento</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'mds_legales_oficio_vinculado[fecha_nacimiento]',
                    'value' => $model->isNewRecord ? '' : ($model->idpersona ? date_format(date_create($model->persona->fecha_nacimiento), 'd/m/Y') : null),
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'id' => 'mds_legales_oficio_vinculado-fecha_nacimiento',
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

        <div class="row">
            <div class="col-md-6 form-group">
                <?= $form->field($model, 'domicilio_calle')->textInput(['value' => $model->idpersona ? $model->persona->domicilio_calle : $model->domicilio_calle]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'domicilio_numero')->textInput(['value' => $model->idpersona ? $model->persona->domicilio_numero : $model->domicilio_numero]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <?= $form->field($model, 'mail') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'telefono') ?>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-12">
                <?= $form->field($model, 'observaciones')->textarea(['rows' => 5, 'cols' => 5]) ?>
            </div>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', [
                'class' => $model->isNewRecord
                    ? 'btn btn-success'
                    : 'btn btn-primary',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJs(
    "$(document).ready(function() {
        setUpdateValues();

        $(`#mds_legales_oficio_vinculado-nombre, 
            #mds_legales_oficio_vinculado-apellido,
            #mds_legales_oficio_vinculado-domicilio_calle,
            #mds_legales_oficio_vinculado-domicilio_numero,
            #mds_legales_oficio_vinculado-mail,
            #mds_legales_oficio_vinculado-telefono,
            #mds_legales_oficio_vinculado-observaciones
        `).on('input', function() {
            validarFormulario();
        });

        $(`#txtDNI`).on('input', function() {
            const value = this.value;
            this.value = this.value.replace(/\D+/g, '');
            if (value == this.value || !this.value) {
                validarDocumento();
            }
        });

        $('#mds_legales_oficio_vinculado-idparentesco').change(function() {
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
        let dni = "<?= isset($model->dni) ? $model->dni : 0; ?>";
        const idlegalesoficio = "<?= $idlegalesoficio ?>"
        const idTipoDocumento = $('#mds_legales_oficio_vinculado-idtipodocumento').val();
        const dni_campo = $('#txtDNI').val();
        $("#ALERT_DNI_REPETIDO").hide();
        //Cuando se agregue id tipo documento a sds_com_persona, validar que este no sea vacio para hacer la llamada a getPersonaByDniAndIdTipoDocumento
        if (dni_campo != '' && dni != dni_campo) {
            $('#txt_mensaje').html("Buscando datos de Persona...");
            dni = dni_campo;
            $.ajax({
                url: `index.php?r=sds_com_persona/get_persona_by_dni_and_tipo&idTipoDocumento=${idTipoDocumento}&dni=${dni}&llamadoDesde=LEGALES&idlegalesoficio=${idlegalesoficio}`,
                error: function() {
                    $("#txt_mensaje").html("La conexión con RENAPER no pudo realizarse. Por favor, haga la carga manual");
                    $("#DATOS_PERSONA_CONTAINER").show();
                    $("#DATOS_SDS_COM_PERSONA_CONTAINER").hide();
                    limpiarDatosPersona();
                },
                success: function(response) {
                    response = $.parseJSON(response);
                    if (response?.repetido) {
                        $("#ALERT_DNI_REPETIDO").show();
                        $('#txt_mensaje').html("");
                    } else {
                        if (response?.success) {
                            $("#hidden_idpersona").val(response?.data.idpersona);
                            $("#mds_legales_oficio_vinculado-apellido").val(`${response?.data.apellido ? response?.data.apellido : ''}`);
                            $("#mds_legales_oficio_vinculado-nombre").val(`${response?.data.nombre ? response?.data.nombre : ''}`);
                            $("#mds_legales_oficio_vinculado-fecha_nacimiento").val(`${response?.data.fecha_nacimiento ? response?.data.fecha_nacimiento : ''}`);
                            $("#mds_legales_oficio_vinculado-genero").val(`${response?.data.genero ? response?.data.genero : ''}`);
                            $("#mds_legales_oficio_vinculado-nacionalidad").val(`${response?.data.nacionalidad ? response?.data.nacionalidad : ''}`);
                            $("#mds_legales_oficio_vinculado-domicilio_calle").val(`${response?.data.domicilio_calle ? response?.data.domicilio_calle : ''}`);
                            $("#mds_legales_oficio_vinculado-domicilio_numero").val(`${response?.data.domicilio_numero ? response?.data.domicilio_numero : ''}`);
                            $("#mds_legales_oficio_vinculado-mail").val(`${response?.data.mail ? response?.data.mail : ''}`);
                            $("#mds_legales_oficio_vinculado-telefono").val(`${response?.data.telefono ? response?.data.telefono : ''}`);
                            $("#mds_legales_oficio_vinculado-observaciones").val(`${response?.data.observaciones ? response?.data.observaciones : ''}`);
                            $('#txt_mensaje').html("");
                            $("#DATOS_PERSONA_CONTAINER, #DATOS_SDS_COM_PERSONA_CONTAINER").show();
                        } else {
                            $("#txt_mensaje").html(response?.message);
                            $("#DATOS_PERSONA_CONTAINER").show();
                            $("#DATOS_SDS_COM_PERSONA_CONTAINER").hide();
                            limpiarDatosPersona();
                        }
                        validarFormulario();
                    }
                },
                timeout: 30000
            });
        }
    }

    function limpiarDatos() {
        $(`#txtDNI,
        #hidden_idpersona,
        #mds_legales_oficio_vinculado-idtipodocumento,
        #mds_legales_oficio_vinculado-idparentesco,
        #mds_legales_oficio_vinculado-apellido,
        #mds_legales_oficio_vinculado-nombre,
        #mds_legales_oficio_vinculado-genero,
        #mds_legales_oficio_vinculado-nacionalidad,
        #mds_legales_oficio_vinculado-fecha_nacimiento,
        #mds_legales_oficio_vinculado-domicilio_calle,
        #mds_legales_oficio_vinculado-domicilio_numero,
        #mds_legales_oficio_vinculado-mail,
        #mds_legales_oficio_vinculado-telefono,
        #mds_legales_oficio_vinculado-observaciones
        `).val('');
        $("#txt_mensaje").html('');
        $("#ALERT_COMPLETAR_DATOS, #ALERT_DNI_REPETIDO").hide();
    }

    function limpiarDatosPersona() {
        $(`#hidden_idpersona,
        #mds_legales_oficio_vinculado-idparentesco,
        #mds_legales_oficio_vinculado-apellido,
        #mds_legales_oficio_vinculado-nombre,
        #mds_legales_oficio_vinculado-genero,
        #mds_legales_oficio_vinculado-nacionalidad,
        #mds_legales_oficio_vinculado-fecha_nacimiento,
        #mds_legales_oficio_vinculado-domicilio_calle,
        #mds_legales_oficio_vinculado-domicilio_numero,
        #mds_legales_oficio_vinculado-mail,
        #mds_legales_oficio_vinculado-telefono,
        #mds_legales_oficio_vinculado-observaciones
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
        $("#txtDNI").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-nombre").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-idparentesco").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-domicilio_calle").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-domicilio_numero").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-mail").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-telefono").prop("disabled", false);
        $("#mds_legales_oficio_vinculado-observaciones").prop("disabled", false);
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

    function setUpdateValues() {
        const isNewRecord = <?= $model->isNewRecord ? 1 : 2 ?>;
        if (isNewRecord == 2) {
            const conoceDocumento = <?= $model->idpersona || $model->documento ? '1' : '0' ?>;
            const parentesco = "<?= $model->idparentesco ? $model->idparentesco : null ?>";
            const idTipoDocumento = "<?= $model->idpersona && $model->persona->documento_tipo ? $model->persona->documento_tipo : ($model->idtipodocumento ? $model->idtipodocumento : null) ?>";
            const idPersona = "<?= $model->idpersona ? $model->idpersona : null ?>";

            definirConoceDocumento(conoceDocumento);

            if (!parentesco) {
                $('#mds_legales_oficio_vinculado-idparentesco option[value=""]').prop('selected', true);
            }

            if (conoceDocumento == '1') {
                $("#DATOS_PERSONA_CONTAINER, #DATOS_SDS_COM_PERSONA_CONTAINER").show();
                if (!idPersona) {
                    $("#DATOS_SDS_COM_PERSONA_CONTAINER").hide();
                }
            }

            if (!idTipoDocumento) {
                $('#mds_legales_oficio_vinculado-idtipodocumento option[value=""]').prop('selected', true);
            }
        }
    }

    function definirConoceDocumento(conoceDocumento) {
        validarFormulario();
        if (conoceDocumento == 1) {
            $("#DATOS_DNI_CONTAINER, #ALERT_BUSCAR_PERSONA").show();
            $("#DATOS_PERSONA_CONTAINER, #ALERT_COMPLETAR_DATOS").hide();
        } else {
            $("#DATOS_PERSONA_CONTAINER").show();
            $("#DATOS_DNI_CONTAINER, #DATOS_SDS_COM_PERSONA_CONTAINER, #ALERT_BUSCAR_PERSONA").hide();
        }
    }

    function validarFormulario() {
        const nroDocumento = $('#txtDNI').val();
        const apellido = $('#mds_legales_oficio_vinculado-apellido').val();
        const nombre = $('#mds_legales_oficio_vinculado-nombre').val();
        const parentesco = $('#mds_legales_oficio_vinculado-idparentesco').val();
        const domicilioCalle = $('#mds_legales_oficio_vinculado-domicilio_calle').val();
        const domicilioNumero = $('#mds_legales_oficio_vinculado-domicilio_numero').val();
        const mail = $('#mds_legales_oficio_vinculado-mail').val();
        const telefono = $('#mds_legales_oficio_vinculado-telefono').val();
        const observaciones = $('#mds_legales_oficio_vinculado-observaciones').val();
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
        const nroDocumento = $('#txtDNI').val();
        $("#txt_mensaje").html("");
        $('#ALERT_COMPLETAR_DATOS, #ALERT_DNI_REPETIDO, #DATOS_PERSONA_CONTAINER').hide();
        $('#boton-guardar-vincular-persona').prop('disabled', true);
        limpiarDatosPersona();
    }
</script>