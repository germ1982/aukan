<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-cap-persona-form">


    <?php $formPersona = ActiveForm::begin([
        'action' => ['sds_com_persona/create'],
        'id' => 'formNewPersona',
        'options' => [
            'enctype' => 'multipart/form-data',
            'name' => 'formNewPersona',
        ],
    ]); ?>


    <div class="row" id="div_txt_mensaje_new_persona" style="display: none;">
        <div class="col-md-5 col-md-offset-5" id="txt_mensaje_new_persona">
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?php $model->documento_tipo = 83;
            //Precargamos DNI
            ?>
            <?= $formPersona
                ->field($model, 'documento_tipo')
                ->widget(Select2::class, [
                    'data' => $tiposDocumentos,
                    'options' => [
                        'placeholder' => 'Seleccionar tipo de documento...',
                        'tabIndex' => '1',
                        'id' => 'documento_tipo',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
                ->label('Tipo de documento') ?>
        </div>
        <div class="col-md-6">
            <?= $formPersona
                ->field($model, 'documento')
                ->textInput(['id' => 'txtDNI', 'maxlength' => 10])
                ->label('Documento') ?>
        </div>

        <?php if ($model->isNewRecord) { ?>
            <div class="col-md-1" style="padding-top:25px;">
                <?php echo Html::a(
                    '<i class="glyphicon glyphicon-search"></i>',
                    null,
                    [
                        'name' => 'btn_dni_benef',
                        'id' => 'btn_dni_benef',
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Consultar DNI'),
                        'onclick' => 'iniciarBusqueda()',
                    ]
                ); ?>
            </div>
        <?php } ?>

    </div>

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
                        'placeholder' => 'Seleccionar Nacionalidad...',
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
                        'placeholder' => 'Seleccionar Género...',
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
        $('#txtDNI').keyup(function(e){ValidaringresoDni()});

        $('#btnNewPersona').on('click',function(){
            newPersona();
            $('#txt_mensaje').html(``);
            $('#txt_mensaje').css('color', '#555555');
            $('#txtDNI_search').css('border-color', '#ccc');
            $('#divriesgo').show();
            $('#campos').show();
            $('#btnSave').show();
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
            $('#btnNewPersona').prop('disabled', true);

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
                        $('input[name="Mds_acomp_asistencia[idbeneficiario]"]').val(record.idpersona)
                        $('input[name="Mds_acomp_asistencia[idbeneficiario]"]').trigger("input");
                        $("#txtDNI_search").val(`${record.apellido} ${record.nombre} (${record.documento})`)
                        $("#mds_certificacion-idbeneficiario").val(record.idpersona);
                        $('.modal.in').modal('hide')
                        $("#div_txt_mensaje_new_persona").hide();
                    } else {
                        $("#txt_mensaje_new_persona").html("<span style='color: red;'><b>Error!</b><i>No se ha podido cargar la persona</i></span>");
                        $("#div_txt_mensaje_new_persona").show();
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
                    $("#txt_mensaje_new_persona").html(errorMessage);
                    $("#div_txt_mensaje_new_persona").show();
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
        const dni_persona = $('#txtDNI').val();
        getDatosRenaper(dni_persona);

    }

    function getDatosRenaper(dni_campo) {
        $("#iSpinner").addClass("fa fa-circle-o-notch fa-spin")
        $('#btnNewPersona').prop('disabled', true);
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_campo, function(data) {
            if (data.status == "error") {
                console.log(data.message);
                $("#txt_mensaje_new_persona").html("<span style='color: red;'><b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i></span>");
                $("#div_txt_mensaje_new_persona").show();
                limpiarDatos();
            } else {
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
                habilitar_controles();
                $('#txt_mensaje_new_persona').html("");
                $("#div_txt_mensaje_new_persona").hide();
            }
            $("#iSpinner").removeClass("fa fa-circle-o-notch fa-spin")
            $('#btnNewPersona').prop('disabled', false);
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