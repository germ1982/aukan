<?php

use kartik\date\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-persona-form">
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'documento_tipo')
                ->dropDownList(
                    $tipoDoc,
                    [
                        'disabled' => $isCreate ? false : true
                    ]
                ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'documento')->textInput(['readonly' => $isCreate ? false : true, 'maxlength' => 10]) ?>
        </div>
        <?php if ($isCreate) : ?>
            <div class="col-md-4" style="padding-top:25px;">
                <?php echo Html::a(
                    '<i class="glyphicon glyphicon-search"></i>',
                    null,
                    [
                        'id' => 'btn_dni',
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Consultar DNI'),
                    ]
                ); ?>
                <label style="padding-left: 10px;" id="mensaje_risneu">
                    <?php if ($model->isNewRecord && $model->documento != null) {
                        echo 'Nueva Persona';
                    } elseif (!$model->isNewRecord) {
                        echo 'Persona Existente RISNeu N°' . $idrisneu;
                    } ?>
                </label>
            </div>
            <div class="col-md-4" style="padding-top:30px;" id="txt_mensaje">

            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'apellido')
                ->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nombre')
                ->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'genero')
                ->dropDownList(
                    $tipoGenero,
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                        ],
                        'disabled' => true
                    ]
                ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form
                ->field($model, 'nacionalidad')
                ->dropDownList(
                    $tipoNacionalidad,
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                        ],
                        'disabled' => true,
                    ]
                ) ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($model->fecha_nacimiento != null) {
                $model->fecha_nacimiento = date(
                    'd/m/Y',
                    strtotime(str_replace('/', '-', $model->fecha_nacimiento))
                );
            }
            echo $form
                ->field($model, 'fecha_nacimiento')
                ->widget(DatePicker::ClassName(), [
                    'id' => 'fecha_nacimiento',
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}{remove}',
                    'options' => [
                        'class' => 'form-control input-md',
                        'disabled' => true,
                        'placeholder' => 'DD / MM / YYYY',
                    ],
                    'pluginOptions' => [
                        'value' => null,
                        'format' => 'dd/mm/yyyy',
                        'endDate' => date('d/m/Y'),
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ],
                ]);
            ?>
        </div>
    </div>
</div>

<?php $this->registerJs(
    "$(document).ready(function() {
        const esPrimerPersona = $('#ES_PRIMERA_PERSONA').val();
        if (esPrimerPersona) {
            const dni = $('#sds_ris_risneu-dni_beneficiario').val();
            $('#sds_com_persona-documento').val(dni);
        }
        datos_persona(true);        
    });
    
    $('#btn_dni').click(function(){        
        datos_persona(false);
    }); "
); ?>

<script>
    var dni = <?php echo isset($model->dni) ? $model->dni : 0; ?>;

    function datos_persona(primera_vez = false) {
        var dni_campo = $('#sds_com_persona-documento').val();
        if (dni != dni_campo || primera_vez) {
            if (dni_campo != '') {
                $('#txt_mensaje').html("Buscando datos de Persona...");
                dni = dni_campo;
                $.post("index.php?r=sds_ris_persona/validar_dni&dni=" + dni + "&idrisneu=<?= $idrisneu ?>", function(data) {
                    var data = $.parseJSON(data);
                    if (data.length === 0) {
                        datos_renaper(dni);
                    } else {
                        $("#sds_com_persona-apellido").val(data[0].apellido);
                        $("#sds_com_persona-nombre").val(data[0].nombre);
                        $("#sds_com_persona-genero").val(data[0].genero);
                        $("#sds_com_persona-genero_autopercibido").val(data[0].genero_autopercibido);
                        $("#sds_com_persona-nacionalidad").val(data[0].nacionalidad);
                        $("#sds_com_persona-fecha_nacimiento").val(formatearFecha(data[0]['fecha_nacimiento']));
                        $("#sds_ris_persona-idpersona").val(data[0].idpersona);
                        if (data.length > 1) {
                            //ANOTEZE: Aca ver para los que tienen información en otro risneu cargarla (en un futuro cercano) 
                        } else {

                        }
                        $('#txt_mensaje').html("");
                        habilitar_controles();
                    }
                });
            }
        }
    }

    function datos_renaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni)
            .done(function(data) {
                if (data.status == "error") {
                    $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                    limpiarDatos();
                } else {
                    var nombre = "";
                    var apellido = "";
                    var domicilio = "";
                    var localidad = "";
                    var foto = "";
                    var fecha_nacimiento = null;
                    $.each(data, function(ind, elem) {
                        if (ind == 'records') {
                            nombre = elem[0].result.nombres;
                            apellido = elem[0].result.apellido;
                            domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                            localidad = elem[0].result.ciudad;
                            //foto = elem[0].result.foto;
                            fecha_nacimiento = elem[0].result.fecha_nacimiento;
                        }
                    });
                    if (fecha_nacimiento != null) {
                        $("#sds_ris_persona-idpersona").val('0');
                        $("#sds_com_persona-nombre").val(corregir_palabra(nombre));
                        $("#sds_com_persona-apellido").val(corregir_palabra(apellido));
                        $("#sds_com_persona-fecha_nacimiento").val(fecha_nacimiento);
                        $("#sds_com_persona-nacionalidad").val('');
                        $("#sds_com_persona-genero").val('');
                        $("#sds_com_persona-genero_autopercibido").val('');
                        $("#sds_com_persona-domicilio").val(domicilio);
                        $("#sds_com_persona-localidad").val(getIdLocalidad(corregir_palabra(localidad)));
                        $('#txt_mensaje').html("");
                        habilitar_controles();
                    }
                }
            })
            .fail(function(data) {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            });
    }

    function limpiarDatos() {
        habilitar_controles();
        $("#sds_com_persona-nombre").val('');
        $("#sds_com_persona-apellido").val('');
        $("#fecha_nacimiento").val('');
        $("#sds_com_persona-nacionalidad").val('');
        $("#sds_com_persona-sexo").val('');
        $("#sds_com_persona-telefono").val("");
        $("#sds_com_persona-domicilio").val("");
        $("#sds_com_persona-localidad").val("");
        /* $("#renaper_foto").attr("src", ''); */
        $("#sds_ris_persona-idpersona").val('0');
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
        const esPrimeraPersona = $("#ES_PRIMERA_PERSONA").val();
        const idParentescoJefe = 60;

        $("#sds_com_persona-nombre").prop("disabled", false);
        $("#sds_com_persona-apellido").prop("disabled", false);
        $("#sds_com_persona-fecha_nacimiento").prop("disabled", false);
        $("#sds_com_persona-nacionalidad").prop("disabled", false);
        $("#sds_com_persona-genero").prop("disabled", false);
        $("#sds_com_persona-genero_autopercibido").prop("disabled", false);
        $("#sds_com_persona-telefono").prop("disabled", false);
        $("#sds_com_persona-domicilio").prop("disabled", false);
        $("#sds_com_persona-localidad").prop("disabled", false);
        if (esPrimeraPersona) {
            $("#sds_ris_persona-parentezco").val(idParentescoJefe)
        } else {
            $("#sds_ris_persona-parentezco").prop("disabled", false);
        }
        $("#sds_ris_persona-situacion_conyugal").prop("disabled", false);
        $("#cmb_escolaridad").prop("disabled", false);
        $("#sds_ris_persona-ultimo_ano_aprobado").prop("disabled", false);
        $("#sds_ris_persona-tipo_establecimiento_educativo").prop("disabled", false);
        $("#cmb_trabajo").prop("disabled", false);
        $("#sds_ris_persona-trabajo_horas").prop("disabled", false);
        $("#sds_ris_persona-trabajo_dias").prop("disabled", false);
        $("#sds_ris_persona-vinculo_contractual").prop("disabled", false);
        $("#sds_ris_persona-trabajo_tipo").prop("disabled", false);
        $("#sds_ris_persona-ingreso").prop("disabled", false);
        // $("#sds_ris_persona-discapacidad").prop("disabled", false);
        $("#sds_ris_persona-cud").prop("disabled", false);
        $("#enfermedades").prop("disabled", false);
        $("#discapacidades").prop("disabled", false);
        $("#sds_ris_persona-cobertura_salud").prop("disabled", false);
        $("#sds_ris_persona-condicion_hacinamiento").prop("disabled", false);
        $("#sds_ris_persona-observaciones").prop("readonly", false);
        $("#sds_ris_persona-pueblo_originario_pertenece").prop("disabled", false);
        // $("#reconoce_pueblo_originario").prop("disabled", false);
        // $("#sds_ris_persona-pueblo_originario").prop("disabled", false);
        $("#sds_ris_persona-consume_sustancia").prop("disabled", false);
        // $("#sustancias").prop("disabled", false);
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }
</script>