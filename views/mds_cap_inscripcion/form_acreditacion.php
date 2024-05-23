<?php

use yii\helpers\Html;

?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<header class="page-header">
    <h2>Acreditación</h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Acreditación</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-acreditacion-form">
                    <div class="row">
                        <div class="col-md-12">
                            <span id="txt_mensaje"></span>
                            <div class="input-group">Ingrese DNI a buscar
                                <input class="form-control" type="text" maxlength="10" id="txtDNI_search" value="" name="txtDNI_search">
                                <span class="input-group-btn">
                                    <?php echo Html::button(
                                        '<i class="glyphicon glyphicon-search"></i>',
                                        [
                                            'class' =>
                                            'btn btn-primary btn-flat',
                                            'name' => 'btn_dni',
                                            'id' => 'btn_dni',
                                            'style' => 'margin-top:21px',
                                            'title' => Yii::t(
                                                'app',
                                                'Buscar DNI'
                                            ),
                                        ]
                                    ); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="divInscripciones" style="margin-top: 2rem;">
                    </div>


                </div>
                <div class="row"><br />
                    <div class="col-md-12">
                        <a class="btn btn-info" href="index.php?r=mds_cap_inscripcion">Volver</a>
                    </div>
                </div>
            </div>
    </div>
    </section>
</div>
</div>


<?php $this->registerJs(
    "$(document).ready(function() {  
        
        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del form
        $('#formAcompAsistencia').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });

        $('#btn_dni').click(function(){
            const numeroDNI = $('#txtDNI_search').val();
            if (numeroDNI){
                getPersonaByDNI(numeroDNI);
            } else {
                alert('Debe ingresar un DNI');
            }           
        });
      
        $('#txtDNI_search').on('input', function () { 
            this.value = this.value.replace(/[^0-9]/g,'');
            $('#txtDNI_search').css('border-color', '#ccc');
        });

    });"
); ?>
<script>
    function getPersonaByDNI(numeroDNI) {
        $("#div_duplicados").hide();
        $('#txt_mensaje').html("Buscando inscripciones de la persona...");
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_CAP_INSCRIPCION_GET') ?>";
        const params = `/${numeroDNI}`;
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        $.ajax({
            url: url + params,
            type: "GET",
            dataType: "json",
            async: true,
            headers,
            success: function(data) {
                if (data.status === "success") {
                    if (data?.records?.length) {
                        $("#divInscripciones").show();
                        let htmlInscripciones = "";
                        let htmlDetailInscripcion = "";
                        let htmlButtonInscripcion = "";
                        let estadoInscripcion = "";
                        data.records.map((item, index) => {
                            if (!htmlInscripciones) {
                                htmlInscripciones += `<h2>${item.personaApellido}, ${item.personaNombre} (${item.personaDNI})</h2>`;
                            }
                            htmlDetailInscripcion = "";

                            if (item.inscripcionFecha) {
                                const inscripcionFechaParsed = item.inscripcionFecha.slice(8, 10) + "-" + item.inscripcionFecha.slice(5, 7) + "-" + item.inscripcionFecha.slice(0, 4)
                                htmlDetailInscripcion +=
                                    `<b>Fecha de inscripción:</b> ${inscripcionFechaParsed} <br/>`;
                            }
                            if (item.inscripcionId) {
                                htmlDetailInscripcion +=
                                    `<b>Número de inscripción:</b> ${item.inscripcionId} <br/>`;
                            }
                            if (item.capinstanciaLugar) {
                                htmlDetailInscripcion +=
                                    `<b>Lugar:</b> ${item.capinstanciaLugar} <br/>`;
                            }
                            if (item.capinstanciaDesde) {
                                const capinstanciaDesdeParsed = item.capinstanciaDesde.slice(8, 10) + "-" + item.capinstanciaDesde.slice(5, 7) + "-" + item.capinstanciaDesde.slice(0, 4)
                                htmlDetailInscripcion +=
                                    `<b>Desde:</b> ${capinstanciaDesdeParsed} <br/>`;
                            }
                            if (item.capinstanciaHasta) {
                                const capinstanciaHastaParsed = item.capinstanciaHasta.slice(8, 10) + "-" + item.capinstanciaHasta.slice(5, 7) + "-" + item.capinstanciaHasta.slice(0, 4)
                                htmlDetailInscripcion +=
                                    ` <b>Hasta:</b> ${capinstanciaHastaParsed} <br/>`;
                            }

                            switch (item.inscripcionTermino) {
                                case "0":
                                    estadoInscripcion = "Inscripto";
                                    break;
                                case "1":
                                    estadoInscripcion = "En curso";
                                    break;
                                case "2":
                                    estadoInscripcion = "Aprobado";
                                    break;
                                case "3":
                                    estadoInscripcion = "Desaprobado";
                                    break;
                                case "4":
                                    estadoInscripcion = "Abandonado";
                                    break;
                                case "5":
                                    estadoInscripcion = "En espera";
                                    break;
                                case "6":
                                    estadoInscripcion = "Participa";
                                    break;
                                case "7":
                                    estadoInscripcion = "No corresponde";
                                    break;
                                default:
                                    break;
                            }
                            htmlDetailInscripcion +=
                                ` <b>Estado:</b> ${estadoInscripcion} <br/>`;

                            if (item.inscripcionTermino !== "6") {
                                htmlButtonInscripcion =
                                    `
                                        <button type="button" onclick="setAcreditacion(${item.inscripcionId})" style="margin-top: 1rem; margin-bottom: 1rem;" class="btn btn-primary">Acreditar</button> <br/>
                                    `
                            }


                            // if (item.capinstanciaDetalle) {
                            //     htmlDetailInscripcion +=
                            //         ` <b> Detalle: </b> ${item.capinstanciaDetalle} <br/> `;
                            // }

                            // if (item.capinstanciaObservacion) {
                            //     htmlDetailInscripcion +=
                            //         ` <b> Observación: </b> ${item.capinstanciaObservacion} <br/> `;
                            // }

                            htmlInscripciones +=
                                `
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="item${index}">
                                            <h5 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#faq" href="#detail${index}" aria-expanded="false"
                                                    aria-controls="detail${index}">
                                                    ${item.capinstanciaDescripcion} - ${estadoInscripcion}
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="detail${index}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="item${index}">
                                            <div class="panel-body">
                                                ${htmlButtonInscripcion}
                                                ${htmlDetailInscripcion}
                                            </div>
                                        </div>
                                    </div>
                                `
                        })

                        $("#divInscripciones").html(htmlInscripciones)

                        $("#txt_mensaje").html("");
                    } else {
                        $("#divInscripciones").hide();
                        $('#txt_mensaje').html(`El DNI: ${numeroDNI}, no tiene pendiente ninguna acreditación. Por favor, debe inscribirse en la capacitación deseada.`);
                        $('#txt_mensaje').css('color', 'red');
                        $('#txtDNI_search').css('border-color', 'red');
                    }
                } else {
                    $("#txt_mensaje").html("<b>Error!</b><i>" + data.message + "</i>");
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al buscar las inscripciones de la persona`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje").html(errorMessage);
            },
        });
    }

    function setAcreditacion(inscripcionId) {
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_CAP_INSCRIPCION_SET_ACREDITACION') ?>";
        const params = `/${inscripcionId}`;
        $.ajax({
            type: "POST",
            dataType: "json",
            headers,
            async: true,
            url: url + params,
            success: function(data) {
                alert(data.message)
                if (data.status === "success") {
                    $("#divInscripciones").html("")
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al acreditar la inscripción`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje").html(errorMessage);
            },
        });
    }
</script>