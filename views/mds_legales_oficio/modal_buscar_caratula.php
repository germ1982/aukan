<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="modal fade" id="modalBuscarCaratula" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Búsqueda de carátula / número expediente / caso</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="alert alert-info" role="alert">
                        Antes de crear un nuevo requerimiento <b>debe verificar si la carátula / número expediente / caso ya existe</b>.
                        <ul>
                            <li>Si existe:</li>
                            <ul>
                                <li>Seleccione la carátula que corresponda y luego presione en <b>"Crear requerimiento"</b>.</li>
                                <li>Si ninguna carátula es la deseada, seleccione <b>"Ninguna de las anteriores"</b> y luego presione en <b>"Crear requerimiento"</b>.</li>
                            </ul>
                            <li>Si no existe</li>
                            <ul>
                                <li>Presione en <b>"Crear requerimiento"</b>.</li>
                            </ul>
                        </ul>
                    </div>
                    <div class="col-12">
                        <label>Ingrese la carátula / número expediente / caso:</label>
                        <div style="display: flex;">
                            <input type="text" class="form-control" id="INPUT_SEARCH">
                            <?php echo Html::a(
                                '<i class="glyphicon glyphicon-search"></i>',
                                null,
                                [
                                    'name' => 'btn_buscar_caratula',
                                    'id' => 'btn_buscar_caratula',
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-primary',
                                    'title' => Yii::t('app', 'Buscar'),
                                    'style' => 'margin-left: 10px;'
                                ]
                            ); ?>
                        </div>
                        <span id="txt_mensaje_personas_vinculadas"></span>
                    </div>
                </div>
                <div class="col-12" style="display: none;" id="CARATULAS_CONTAINER">
                    <div style="margin-bottom: 2rem">
                        <div id="LISTADO_CARATULAS_CONTAINER" class="overflow-y">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-9">
                            <select class="form-control" style="width: 100%; margin-bottom: 15px;" id="SELECT_CARATULAS" name="SELECT_CARATULAS" onchange="habilitarGuardar()">
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <button id="btn_crear_requerimiento" class="btn btn-success" style="width: 100%;" onclick="redirectGuardar()" disabled>Crear requerimiento</button>
                        </div>
                    </div>
                </div>
                <div style="display: none;" id="SIN_RESULTADO_CONTAINER">
                    <p><b>No se encontraron requerimientos que coincidan con la carátula / número expediente / caso.</b></p>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                        <?=
                        Html::a(
                            'Crear requerimiento',
                            ['create'],
                            [
                                'data-pjax' => 0,
                                'role' => 'post',
                                'title' => 'Nuevo Requerimiento',
                                'class' => 'btn btn-success',
                                'style' => 'width: 100%;',
                            ]
                        )
                        ?>
                        </div>
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
    });

    $('#btn_buscar_caratula').click(function(){
        buscarCaratula();
    });

    "
); ?>

<script>
    function buscarCaratula() {
        $("#CARATULAS_CONTAINER, #SIN_RESULTADO_CONTAINER").hide();
        $("#SELECT_CARATULAS, #LISTADO_CARATULAS_CONTAINER").html("");
        $("#btn_crear_requerimiento").prop("disabled", true);
        const inputSearch = $('#INPUT_SEARCH').val();
        if (inputSearch) {
            $('#txt_mensaje_personas_vinculadas').html("Buscando requerimiento...");
            $.post(`index.php?r=mds_legales_caratula/search_caratula&inputSearch=${inputSearch}`, function(response) {
                response = $.parseJSON(response);
                if (response?.success) {
                    if (response?.data && response?.data.length) {
                        const caratulas = response?.data;
                        let optionsCaratula = "<option value='' selected disabled>Seleccione...</option>";
                        let accordionCaratula = "";
                        $.each(caratulas, function(index, caratula) {
                            const caratulaDescripcion = caratula.idlegalescaratula ? `<b>Carátula #${caratula.idlegalescaratula}</b>` : '';
                            const nroExpediente = caratula.numero_expediente ? `<b>Nro. Exp.:</b> ${caratula.numero_expediente}` : '';
                            const caso = caratula.caso ? `<b>Caso:</b> ${caratula.caso}` : '';
                            const anio = caratula.anio_expediente ? `<b>Año:</b> ${caratula.anio_expediente}` : '';

                            //Para el select de caratulas
                            const guionExpediente = caratula.caratula ? ' - ' : '';
                            const guionCaso = caratula.caratula || caratula.numero_expediente ? ' - ' : '';
                            const guionAnio = caratula.caratula || caratula.numero_expediente || caratula.caso ? ' - ' : '';

                            const selectNroExpediente = caratula.numero_expediente ? `${guionExpediente}${nroExpediente}` : '';
                            const selectCaso = caratula.caso ? `${guionCaso}${caso}` : '';
                            const selectAnio = caratula.anio_expediente ? `${guionAnio}${anio}` : '';

                            const optionCaratulaDescripcion = `${caratulaDescripcion} ${selectNroExpediente} ${selectCaso} ${selectAnio}`;
                            optionsCaratula += `<option value="${caratula.idlegalescaratula}">${optionCaratulaDescripcion}</option>`;

                            //Para el accordion de caratulas
                            const accordionCaratulaDetalle = caratula.caratula ? `<p><b>Carátula:</b> ${caratula.caratula}</p>` : '';
                            const accordionNroExpediente = nroExpediente ? `<p>${nroExpediente}</p>` : '';
                            const accordionCaso = caso ? `<p>${caso}</p>` : '';
                            const accordionAnio = anio ? `<p>${anio}</p>` : '';
                            let listadoRequerimientos = "";
                            if (caratula.requerimientos.length) {
                                const base_url = "<?= Url::base(); ?>";
                                listadoRequerimientos = "<p><u>Listado de requerimientos:</u></p><ul>";
                                caratula.requerimientos.forEach(requerimiento => {
                                    const botonVerRequerimiento = `<a href="${base_url}/index.php?r=mds_legales_oficio%2Fview&idlegalesoficio=${requerimiento.idlegalesoficio}" target="_blank" title="Ver" class="btn btn-link" style="padding: 0 5px 0 0;"><i class="fas fa-eye"></i></a>`;
                                    listadoRequerimientos += `<li>${botonVerRequerimiento}<b>Requerimiento #${requerimiento.idlegalesoficio}</b></li>`;
                                });
                                listadoRequerimientos += "</ul>";
                            }

                            accordionCaratula += `
                            <div class="panel-group">
                                <div class="panel panel-accordion" id="accordion_caratula_${caratula.idlegalescaratula}">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_caratula_${caratula.idlegalescaratula}" href="#detalle_caratula_${caratula.idlegalescaratula}">
                                                ${optionCaratulaDescripcion}
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="detalle_caratula_${caratula.idlegalescaratula}" class="accordion-body collapse">
                                        <div class="panel-body">
                                            ${accordionCaratulaDetalle}
                                            ${accordionNroExpediente}
                                            ${accordionCaso}
                                            ${accordionAnio}
                                            ${listadoRequerimientos}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;



                        });
                        optionsCaratula += "<option value=''>Ninguna de las anteriores</option>";
                        $("#SELECT_CARATULAS").append(optionsCaratula);
                        $("#LISTADO_CARATULAS_CONTAINER").html(accordionCaratula);
                        $("#CARATULAS_CONTAINER").show();
                    } else {
                        $("#SIN_RESULTADO_CONTAINER").show();
                    }
                    $('#txt_mensaje_personas_vinculadas').html("");
                } else {
                    $("#txt_mensaje_personas_vinculadas").html(response?.message);
                }
            });
        }
    }

    function habilitarGuardar() {
        $("#btn_crear_requerimiento").prop("disabled", false);
    }

    function redirectGuardar() {
        const base_url = "<?= Url::base(); ?>";
        const caratulaSeleccionada = $("#SELECT_CARATULAS").val();
        const urlGuardar = `${base_url}/index.php?r=mds_legales_oficio%2Fcreate`;
        const redirectHref = caratulaSeleccionada ? `${urlGuardar}&idlegalescaratula=${caratulaSeleccionada}` : urlGuardar;
        window.location.href = redirectHref;
    }

    function limpiarDatosCaratula() {
        $("#INPUT_SEARCH").val("");
        $("#CARATULAS_CONTAINER, #SIN_RESULTADO_CONTAINER").hide();
    }
</script>