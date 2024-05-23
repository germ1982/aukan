<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .btn-consultar-dni {
        height: 34px;
        align-self: center;
        margin: 17px 0 0 10px;
    }

    .field-txtDNI {
        width: 100%;
    }

    .field-hidden_nueva_persona {
        display: none;
    }
</style>

<div class="mds-cap-persona-form">
    <?php $formPersona = ActiveForm::begin([
        'action' => ['sds_com_persona/create'],
        'id' => 'formNewPersona',
        'options' => [
            'enctype' => 'multipart/form-data',
            'name' => 'formNewPersona',
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-6 form-group">
            <?php $model->documento_tipo = 83;
            //Precargamos DNI
            ?>
            <?= $formPersona
                ->field($model, 'documento_tipo')
                ->widget(Select2::class, [
                    'data' => $tiposDocumentos,
                    'options' => [
                        'placeholder' => 'Seleccionar tipo de documento ...',
                        'tabIndex' => '1',
                        'id' => 'documento_tipo',
                        'disabled' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
                ->label('Tipo de documento') ?>
        </div>
        <div class="col-md-6" style="margin-bottom: 10px">
            <div style="display: flex;">
                <?= $formPersona
                    ->field($model, 'documento')
                    ->textInput(['id' => 'txtDNI', 'maxlength' => 10, 'readonly' => true])
                    ->label('Documento') ?>
                <?php if ($model->isNewRecord) { ?>
                    <?php echo Html::a(
                        '<i class="glyphicon glyphicon-search"></i>',
                        null,
                        [
                            'name' => 'btn_dni_benef',
                            'id' => 'btn_dni_benef',
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-primary btn-consultar-dni',
                            'title' => Yii::t('app', 'Consultar DNI'),
                            'onclick' => 'iniciarBusqueda()',
                            'style' => 'display: none;'
                        ]
                    ); ?>
                <?php } ?>
            </div>
            <span id="txt_mensaje_persona"></span>
        </div>
    </div>

    <div id="DATOS_PERSONA_CONTAINER" style="display: none;">
        <div class="row">
            <div class="col-md-4">
                <?= $formPersona
                    ->field($model, 'nombre')
                    ->textInput(['id' => 'nombre_persona'])
                    ->label('Nombre') ?>
            </div>
            <div class="col-md-4">
                <?= $formPersona
                    ->field($model, 'apellido')
                    ->textInput(['id' => 'apellido_persona'])
                    ->label('Apellido') ?>
            </div>
            <div class="col-md-4">
                <?php
                if ($model->fecha_nacimiento != null) {
                    $model->fecha_nacimiento = date(
                        'd/m/Y',
                        strtotime(str_replace('/', '-', $model->fecha_nacimiento))
                    );
                }
                echo $formPersona
                    ->field($model, 'fecha_nacimiento')
                    ->widget(DatePicker::class, [
                        'name' => 'check_issue_date',
                        'language' => 'es',
                        'readonly' => false,
                        'layout' => '{picker}{input}{remove}',
                        'options' => [
                            'id' => 'fecha_nacimiento',
                            'class' => 'form-control input-md',
                            'disabled' => false,
                        ],
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            'endDate' => date('d/m/Y'),
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ],
                    ])
                    ->label('Fecha de Nacimiento');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">

                <?= $formPersona
                    ->field($model, 'nacionalidad')
                    ->widget(Select2::class, [
                        'data' => $nacionalidades,
                        'options' => [
                            'placeholder' => 'Seleccionar Nacionalidad ...',
                            'tabIndex' => '1',
                            'id' => 'nacionalidad_persona',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])
                    ->label('Nacionalidad') ?>
            </div>
            <div class="col-md-4">
                <?= $formPersona
                    ->field($model, 'genero')
                    ->widget(Select2::class, [
                        'data' => $generos,
                        'options' => [
                            'placeholder' => 'Seleccionar Genero ...',
                            'tabIndex' => '1',
                            'id' => 'sexo_persona',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])
                    ->label('Genero') ?>
            </div>
        </div>
    </div>

</div>

<?= $formPersona
    ->field($model, 'idpersona')
    ->hiddenInput(['id' => 'hidden_nueva_persona'])
    ->label(false) ?>
<?php if (isset($botones)) { ?>
    <br>
    <div class="form-group">
        <?= Html::button('Guardar <i id="iSpinner" aria-hidden="true"></i>', [
            'id' => 'btnNewPersona',
            'class' => 'btn btn-success',
            'disabled' => true,
        ]) ?>

        <?= Html::button('Cerrar', [
            'id' => 'btnCerrarInterno',
            'class' => 'btn btn-default',
            'onclick' => "$('.modal.in').modal('hide')",
        ]) ?>
    </div>

<?php } ?>

<?php ActiveForm::end(); ?>

</div>

<?php $this->registerJs("
        $(document).ready(function(){
            if ($('#txtDNI').val()!='')
            {iniciarBusqueda();}
            
        }); 
        // $('#btn_dni_benef').on('click',function(){iniciarBusqueda()});
        $('#txtDNI').keyup(function(e){ValidaringresoDni()});

        $('#btnNewPersona').on('click',function(){
            newPersona();
            $('#txt_mensaje_persona').html(``);
            $('#txtDNI_search').css('border-color', '#ccc');
    });
    
    "); ?>

<script>
    function newPersona() {

        let documento = $('#txtDNI').val();
        let documento_tipo = $("#documento_tipo").val()
        let nacionalidad = $("#nacionalidad_persona").val();
        let genero = $("#sexo_persona").val();
        let fecha_nacimiento = $("#fecha_nacimiento").val();
        let nombre = $("#nombre_persona").val();
        let apellido = $("#apellido_persona").val();
        let conviviente = 0;

        if (documento && documento_tipo && nacionalidad && genero && fecha_nacimiento && nombre && apellido) {
            if (fecha_nacimiento) {
                fecha_nacimiento = fecha_nacimiento.slice(6, 10) + "-" + fecha_nacimiento.slice(3, 5) + "-" + fecha_nacimiento.slice(0, 2)
            }
            $("#iSpinner").addClass("fa fa-circle-o-notch fa-spin")
            //$('#btnNewPersona').prop('disabled', true);

            const token = "<?= $token ?>";
            const headers = {
                Authorization: `Bearer ${token}`,
            };
            const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_PERSONA_CREATE') ?>";
            $.ajax({
                data: {
                    documento,
                    documento_tipo,
                    nacionalidad,
                    genero,
                    fecha_nacimiento,
                    nombre,
                    apellido,
                    conviviente,
                },
                type: "POST",
                dataType: "json",
                headers,
                async: true,
                url,
                success: function(data) {
                    if (data.status === 'success' && data?.records?.idpersona) {
                        alert("Se ha cargado correctamente la persona");
                        const record = data.records;
                        $('input[name="Mds_certificacion[idbeneficiario]"]').val(record.idpersona)
                        $('input[name="Mds_certificacion[idbeneficiario]"]').trigger("input");
                        $("#mds_certificacion-idbeneficiario").val(record.idpersona);
                        $('.modal.in').modal('hide')
                        getPersonaByDNI(documento);
                    } else {
                        $("#txt_mensaje_persona").html("<b>Error!</b><i>No se ha podido cargar la persona</i>");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                    const responseJSON = xhr.responseJSON;

                    if (responseJSON && responseJSON.message) {
                        errorMessage += `${responseJSON.message}`;
                    } else {
                        errorMessage += `No se ha podido cargar a la persona`;
                    }

                    errorMessage += "</i></span>"
                    $("#txt_mensaje_persona").html(errorMessage);
                    limpiarDatos();
                },
                complete: function(data) {
                    $("#iSpinner").removeClass("fa fa-circle-o-notch fa-spin")
                    $('#btnNewPersona').prop('disabled', false);
                }
            })
        } else {
            alert("Debe completar todos los campos")
        }
    }

    function ValidaringresoDni() {
        var aux = event.which;
        if (aux == 13) //pregunto si fue el enter
        {
            iniciarBusqueda();
        }
    }

    function iniciarBusqueda() {
        $('#DATOS_PERSONA_CONTAINER').hide();
        $('#btnNewPersona').prop('disabled', true);
        const dni_persona = $('#txtDNI').val();
        getDatosRenaper(dni_persona);

    }

    function getDatosRenaper(dni_campo) {
        $('#txt_mensaje_persona').html("Buscando datos de Persona...");
        $.ajax({
            type: 'POST',
            async: true,
            url: `index.php?r=sds_com_persona/get_xroad_ren&dni=${dni_campo}`,
            error: function(data) {
                $("#txt_mensaje_persona").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            },
            success: function(data) {
                if (data.status === "success") {
                    $("#txt_mensaje_persona").html("");
                    let nombre = "";
                    let apellido = "";
                    let domicilio = "";
                    let localidad = "";
                    let fecha_nacimiento = "";
                    let genero = "";
                    let nacionalidad = "";

                    if (data?.records?.length && data.records[0].result) {
                        const record = data.records[0].result;
                        nombre = record.nombres;
                        apellido = record.apellido;
                        fecha_nacimiento = record.fecha_nacimiento;
                        //foto = record.foto;
                        genero = record.genero;
                        nacionalidad = record.pais;
                        nombre = corregirPalabra(nombre);
                        apellido = corregirPalabra(apellido);
                        $("#nombre_persona").val(nombre);
                        $("#apellido_persona").val(apellido);
                        $("#fecha_nacimiento").val(fecha_nacimiento);
                        $("#nacionalidad_persona").val(nacionalidad === 'ARGENTINA' ? 70 : 80).trigger("change");
                        $("#sexo_persona").val(genero === "M" ? 82 : 81).trigger("change");
                    }
                } else {
                    $("#txt_mensaje_persona").html("<span style='color: red;'><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i></span>");
                    limpiarDatos();
                }
            },
            complete: function() {
                habilitar_controles();
                $('#DATOS_PERSONA_CONTAINER, #btn_dni_benef').show();
                $('#btnNewPersona').prop('disabled', false);
            },
            timeout: 20000
        });
    }

    function corregirPalabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#nombre_persona").val('');
        $("#apellido_persona").val('');
        $("#fecha_nacimiento").val('');
        $("#nacionalidad_persona").val('');
        $("#sexo_persona").val('');
    }

    function habilitar_controles() {
        $('#nombre_persona').prop("disabled", false);
        $('#apellido_persona').prop("disabled", false);
        $('#fecha_nacimiento').prop("disabled", false);
        $('#nacionalidad_persona').prop("disabled", false);
        $('#sexo_persona').prop("disabled", false);
        $('#telefono_persona').prop("disabled", false);
        $('#mail_persona').prop("disabled", false);
    }
</script>