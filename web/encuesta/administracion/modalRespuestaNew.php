<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_pregunta = $_REQUEST['id_pregunta'];
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Nueva Respuesta</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <div class="form-horizontal">
        <input type='hidden' id='id_pregunta' name="id_pregunta" value='<?php echo $id_pregunta; ?>'>    
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">          
                    <div class="form-group">
                        <label for="tipo" class="col-sm-3 control-label" >Seleccione el tipo de respuesta</label>
                        <select id="tipo" name="tipo" onchange="ocultarCampos();$('#type_' + this.value).show();">
                            <option value="">Seleccionar</option>
                            <option value="radio">Selección Única</option>
                            <option value="radio_otro">Selección Única c/descripcion</option>
                            <option value="radio_varios">Escala Lineal</option>
                            <option value="checkbox">Selección múltiple</option>
                            <option value="radio_varios_imagen">Escala Lineal c/imágen</option>
                            <option value="textarea">Párrafo</option>
                            <option value="input">Texto corto</option>
                        </select>
                    </div>
                    <div class="form-group" id="type_radio">
                        <label for="respuesta" class="col-sm-3 control-label">Texto de respuesta de selección única</label>
                        <div class="col-sm-9">
                            <input id="respuesta" name="respuesta" type="text" class="form-control" value=''>
                        </div>
                    </div>
                    <div class="form-group" id="type_radio_otro">
                        <label for="respuesta_otro" class="col-sm-3 control-label">Texto de respuesta de selección única c/descripción</label>
                        <div class="col-sm-9">
                            <input id="respuesta_otro" name="respuesta_otro" type="text" class="form-control" value=''>
                        </div>
                    </div>
                    <div id="type_radio_varios">
                        <div class="form-group">
                            <label for="texto_previo_desde_varios" class="col-sm-3 control-label">Texto Previo escala lineal</label>
                            <div class="col-sm-9">
                                <input id="texto_previo_desde_varios" name="texto_previo_desde_varios" type="text" class="form-control" value=''>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="radio_desde_varios" class="col-sm-3 control-label">Valor inicial escala lineal</label>
                            <div class="col-sm-9">
                                <input id="radio_desde_varios" name="radio_desde_varios" type="text" class="form-control" value=''>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="radio_hasta_varios" class="col-sm-3 control-label">Valor Final escala lineal</label>
                            <div class="col-sm-9">
                                <input id="radio_hasta_varios" name="radio_hasta_varios" type="text" class="form-control" value=''>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="texto_posterior_hasta_varios" class="col-sm-3 control-label">Texto Posterior escala lineal</label>
                            <div class="col-sm-9">
                                <input id="texto_posterior_hasta_varios" name="texto_posterior_hasta_varios" type="text" class="form-control" value=''>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="type_checkbox">
                        <label for="respuesta_checkbox" class="col-sm-3 control-label">Respuesta de selección múltiple</label>
                        <div class="col-sm-9">
                            <input id="respuesta_checkbox" name="respuesta_checkbox" type="text" class="form-control" value=''>
                        </div>
                    </div>
                    <div id="type_radio_varios_imagen">
                        <form id="form_varios_imagen" name="form_varios_imagen" action="uploadFile.php" target="_blank" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="radio_desde_imagen" class="col-sm-3 control-label">Valor inicial escala lineal c/imagen</label>
                                <div class="col-sm-9">
                                    <input id="radio_desde_imagen" name="radio_desde_imagen" type="text" class="form-control" value=''>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="radio_hasta_imagen" class="col-sm-3 control-label">Valor Final escala lineal c/imagen</label>
                                <div class="col-sm-9">
                                    <input id="radio_hasta_imagen" name="radio_hasta_imagen" type="text" class="form-control" value=''>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="imagen" class="col-sm-3 control-label">Imágen</label>
                                <div class="col-sm-9">
                                    <img src="../images/<?php echo $respuesta['imagen']; ?>" width="40%"/>
                                    <div class="form-group">
                                        <input type="file" name="imagen" id="imagen"  onchange="form_varios_imagen.submit();">
                                        <input type="hidden" id="img" />
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group">
                        <label for="orden" class="col-sm-3 control-label">Orden</label>
                        <div class="col-sm-9">
                            <input id="orden" name="orden" type="number" value=''>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success"  data-dismiss="modal" id="guardarRespuesta">Guardar</button>

                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
<script>
    $("#type_radio").hide();
    $("#type_radio_otro").hide();
    $("#type_radio_varios").hide();
    $("#type_checkbox").hide();
    $("#type_radio_varios_imagen").hide();
    function ocultarCampos() {
        $("#type_radio").hide();
        $("#type_radio_otro").hide();
        $("#type_radio_varios").hide();
        $("#type_checkbox").hide();
        $("#type_varios_imagen").hide();
    }
    $("#guardarRespuesta").click(function () {
        var tipo = $("#tipo").val();
        var params = {};
        if (tipo == 'radio') {
            params = {funcion: 'guardarRespuestaNew', id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                respuesta: $("#respuesta").val()}
        }
        if (tipo == 'radio_otro') {
            params = {funcion: 'guardarRespuestaNew',id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                respuesta: $("#respuesta_otro").val()}
        }
        if (tipo === 'radio_varios') {
            params = {funcion: 'guardarRespuestaNew',id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                texto_previo_desde: $("#texto_previo_desde_varios").val(), radio_desde: $("#radio_desde_varios").val(), radio_hasta: $("#radio_hasta_varios").val(),
                texto_posterior_hasta: $("#texto_posterior_hasta_varios").val()}
        }
        if (tipo == 'checkbox') {
            params = {funcion: 'guardarRespuestaNew',id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                respuesta: $("#respuesta_checkbox").val()}
        }
        if (tipo === 'radio_varios_imagen') {
            params = {funcion: 'guardarRespuestaNew',id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                radio_desde: $("#radio_desde_imagen").val(), radio_hasta: $("#radio_hasta_imagen").val(), imagen: $("#imagen").val().replace(/C:\\fakepath\\/i, '')}
        }
        if (tipo == 'textarea') {
            params = {funcion: 'guardarRespuestaNew', id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val()}
        }
        if (tipo == 'input') {
            params = {funcion: 'guardarRespuestaNew', id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val()}
        }
       //alert(JSON.stringify(params));
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: params
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        alert('Se ha guardado con éxito');
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerRespuestas', id: $("#id_pregunta").val()}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#respuestas_" + $("#id_pregunta").val()).html(event.data);
                                    } else {
                                        alert('No se pudieron traer las respuestas');
                                    }
                                });
                    } else {
                        alert(event.result);
                    }
                });
    })
</script>
