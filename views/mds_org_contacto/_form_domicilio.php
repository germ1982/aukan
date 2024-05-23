<?php

use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
?>
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
            max-width: 800px;
            /* New width for default modal */
        }
    }
</style>
<div class="mds-org-contacto-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <!-- <div class="col-md-3" style="text-align: center;">
            <img id="renaper_foto" src="" alt="" height="150px" />
        </div> -->
        <div class="col-md-12" style="text-align: center;">
            <div class="row">
                <div id="dni" class="col-md-4" style="text-align: left;">

                </div>
                <div id="fecha_nacimiento" class="col-md-6" style="text-align: left;">

                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="text-align: left;">
                    <b>Nombre:</b>
                </div>
                <div id="nombre_apellido" class="col-md-8" style="text-align: left;">

                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="text-align: left;">
                    <b>Domicilio:</b>
                </div>
                <div id="domicilio" class="col-md-8" style="text-align: left;">

                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="text-align: left;">
                    <b>Localidad:</b>
                </div>
                <div id="localidad" class="col-md-8" style="text-align: left;">

                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <?= $form
                ->field($model, 'idlocalidad')
                ->widget(Select2::classname(), [
                    'data' => [],
                    'options' => [
                        'placeholder' => 'Seleccionar Localidad ...',
                        'id' => 'cmb_localidad',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
                ->label('Localidad') ?>
        </div>
        <div class="col-md-5">
            <?= $form
                ->field($model, 'calle')
                ->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'numero')
                ->textInput(['maxlength' => true]) ?>
        </div>
        <?= $form
            ->field($model, 'codigo_postal')
            ->hiddenInput()
            ->label('') ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<<JS
    var dni = "$model->documento"!="" ? "$model->documento" : "0";

    $(document).ready(function() {
        datos_renaper(dni)
    });

    function datos_renaper(dni) {
        $("#loading").show();
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                $("#nombre_apellido").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");                    
                $("#loading").hide();
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var provincia = "";
                var fecha_nacimiento = null;
                var nacionalidad = "";
                var calle = "";
                var numero = "";
                var codigoPostal = "";
                $.each(data, function(ind, elem) {
                    if (ind == 'records') {
                        console.log(elem[0]);
                        nombre = elem[0].result.nombres;
                        apellido = elem[0].result.apellido;
                        localidad = elem[0].result.ciudad;
                        if (localidad.includes("NEUQU?")){
                            localidad = "NEUQUÉN";
                        }
                        provincia = elem[0].result.provincia;
                        calle = elem[0].result.calle;
                        numero = elem[0].result.numero;
                        //foto = elem[0].result.foto;
                        fecha_nacimiento = elem[0].result.fecha_nacimiento;
                        codigoPostal = elem[0].result.cpostal;
                    }
                });
                if (fecha_nacimiento != null) {
                    domicilio = calle + " Nº" + numero;
                    $("#mds_org_contacto-idpersona").val('0');
                    $("#nombre_apellido").html(corregir_palabra(apellido)+", "+corregir_palabra(nombre));
                    /* $("#mds_org_contacto-fecha_nacimiento").val(fecha_nacimiento);
                    $("#mds_org_contacto-nacionalidad").val('');
                    $("#mds_org_contacto-sexo").val('');*/
                    $("#dni").html("<b>DNI:</b> "+dni);
                    $("#fecha_nacimiento").html("<b>Fch.Nac.:</b> "+fecha_nacimiento);
                    $("#domicilio").html(corregir_palabra(domicilio));
                    $("#localidad").html(corregir_palabra(localidad + " ("+provincia+")"));
                    $("#mds_org_contacto-calle").val(calle);
                    $("#mds_org_contacto-numero").val(numero);
                    /* $("#renaper_foto").attr("src", foto); */
                    $("#mds_org_contacto-codigo_postal").val(codigoPostal);
                    getIdLocalidad(codigoPostal, corregir_palabra(localidad));
                }
                else {
                    $("#loading").hide();
                }
            }          
        });
    }

    function getIdLocalidad(codigoPostal, localidad) {
        $.post("index.php?r=sds_com_localidad/get_localidades_similares&codigo_postal="+codigoPostal+"&localidad=" + localidad, function(data) {
            if (data.length === 0) {
                return "";
            } else {
                /* var o = new Option(localidad, data['idlocalidad']);
                $(o).html(localidad);
                $("select#cmb_localidad").html(o); */
                $("select#cmb_localidad").html(data);
            }
            $("#loading").hide();
        });
    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

JS;

$this->registerJs($script);

