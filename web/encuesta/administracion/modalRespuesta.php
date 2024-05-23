<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_pregunta = $_REQUEST['id_pregunta'];
$id = $_REQUEST['id'];
$bd = new baseDatos();
$bd->Conectarse();

$bd->select("SELECT * FROM mds_encuesta_respuesta WHERE id_respuesta = $id ORDER BY orden ASC");
$respuesta = $bd->registro();
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Respuesta</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <div class="form-horizontal">
        <input type='hidden' id='id' name="id" value='<?php echo $id; ?>'>    
        <input type='hidden' id='tipo' name="tipo" value='<?php echo $respuesta['tipo']; ?>'>     
        <input type='hidden' id='id_pregunta' name="id_pregunta" value='<?php echo $id_pregunta; ?>'>    
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">          
                    <?php if ($respuesta['tipo'] == 'radio') { ?>
                        <div class="form-group">
                            <label for="respuesta" class="col-sm-3 control-label">Respuesta de selección única</label>
                            <div class="col-sm-9">
                                <input id="respuesta" name="respuesta" type="text" class="form-control" value='<?php echo $respuesta['respuesta']; ?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="radio_a_checkbox" class="col-sm-3 control-label">Cambiar tipo de respuesta a selección múltiple?</label>
                            <div class="col-sm-9">
                                <input id="radio_a_checkbox" name="radio_a_checkbox" type="checkbox">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($respuesta['tipo'] == 'radio_otro') { ?>
                        <div class="form-group">
                            <label for="respuesta" class="col-sm-3 control-label">Respuesta de selección única c/descripción</label>
                            <div class="col-sm-9">
                                <input id="respuesta" name="respuesta" type="text" class="form-control" value='<?php echo $respuesta['respuesta']; ?>'>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($respuesta['tipo'] == 'radio_varios') { ?>
                        <div class="form-group">
                            <label for="texto_previo_desde" class="col-sm-3 control-label">Texto Previo escala lineal</label>
                            <div class="col-sm-9">
                                <input id="texto_previo_desde" name="texto_previo_desde" type="text" class="form-control" value='<?php echo $respuesta['texto_previo_desde']; ?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="radio_desde" class="col-sm-3 control-label">Valor inicial escala lineal</label>
                            <div class="col-sm-9">
                                <input id="radio_desde" name="radio_desde" type="text" class="form-control" value='<?php echo $respuesta['radio_desde']; ?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="radio_hasta" class="col-sm-3 control-label">Valor Final escala lineal</label>
                            <div class="col-sm-9">
                                <input id="radio_hasta" name="radio_hasta" type="text" class="form-control" value='<?php echo $respuesta['radio_hasta']; ?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="texto_posterior_hasta" class="col-sm-3 control-label">Texto Posterior escala lineal</label>
                            <div class="col-sm-9">
                                <input id="texto_posterior_hasta" name="texto_posterior_hasta" type="text" class="form-control" value='<?php echo $respuesta['texto_posterior_hasta']; ?>'>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($respuesta['tipo'] == 'checkbox') { ?>
                        <div class="form-group">
                            <label for="respuesta" class="col-sm-3 control-label">Respuesta de selección múltiple</label>
                            <div class="col-sm-9">
                                <input id="respuesta" name="respuesta" type="text" class="form-control" value='<?php echo $respuesta['respuesta']; ?>'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="respuesta" class="col-sm-3 control-label">Cambiar tipo de respuesta a selección única?</label>
                            <div class="col-sm-9">
                                <input id="checkbox_a_radio" name="checkbox_a_radio" type="checkbox" >
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($respuesta['tipo'] == 'radio_varios_imagen') { ?>
                    <form id="form_varios_imagen" name="form_varios_imagen" action="uploadFile.php" target="_blank" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="radio_desde" class="col-sm-3 control-label">Valor inicial escala lineal c/imagen</label>
                                <div class="col-sm-9">
                                    <input id="radio_desde" name="radio_desde" type="text" class="form-control" value='<?php echo $respuesta['radio_desde']; ?>'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="radio_hasta" class="col-sm-3 control-label">Valor Final escala lineal c/imagen</label>
                                <div class="col-sm-9">
                                    <input id="radio_hasta" name="radio_hasta" type="text" class="form-control" value='<?php echo $respuesta['radio_hasta']; ?>'>
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
                    <?php } ?>    
                    <?php if ($respuesta['tipo'] == 'textarea') { ?>
                        <div class="form-group">
                            <label for="textarea_a_input" class="col-sm-3 control-label">Cambiar tipo de respuesta a respuesta corta?</label>
                            <div class="col-sm-9">
                                <input id="textarea_a_input" name="textarea_a_input" type="checkbox" >
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($respuesta['tipo'] == 'input') { ?>
                        <div class="form-group">
                            <label for="input_a_textarea" class="col-sm-3 control-label">Cambiar tipo de respuesta a párrafo?</label>
                            <div class="col-sm-9">
                                <input id="input_a_textarea" name="input_a_textarea" type="checkbox" >
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="orden" class="col-sm-3 control-label">Orden</label>
                        <div class="col-sm-9">
                            <input id="orden" name="orden" type="number" class="form-control" value='<?php echo $seccion['orden']; ?>'>
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
    $("#guardarRespuesta").click(function () {
        var tipo = $("#tipo").val();
        var params = {};
        if (tipo == 'radio') {
            var radio_a_checkbox;
            if ($("#radio_a_checkbox").prop('checked'))
                radio_a_checkbox = 1;
            else
                radio_a_checkbox = 0;
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                respuesta: $("#respuesta").val(), radio_a_checkbox: radio_a_checkbox}
        }
        if (tipo == 'radio_otro') {
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                respuesta: $("#respuesta").val()}
        }
        if (tipo == 'radio_varios') {
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                texto_previo_desde: $("#texto_previo_desde").val(), radio_desde: $("#radio_desde").val(), radio_hasta: $("#radio_hasta").val(),
                texto_posterior_hasta: $("#texto_posterior_hasta")}
        }
        if (tipo == 'checkbox') {
            var checkbox_a_radio;
            if ($("#checkbox_a_radio").prop('checked'))
                checkbox_a_radio = 1;
            else
                checkbox_a_radio = 0;
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                respuesta: $("#respuesta").val(), checkbox_a_radio: checkbox_a_radio}
        }
        if (tipo == 'radio_varios_imagen') {
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                radio_desde: $("#radio_desde").val(), radio_hasta: $("#radio_hasta").val(), imagen:$("#imagen").val().replace(/C:\\fakepath\\/i, '')}
        }
        if (tipo == 'textarea') {
            var textarea_a_input;
            if ($("#textarea_a_input").prop('checked'))
                textarea_a_input = 1;
            else
                textarea_a_input = 0;
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                textarea_a_input: textarea_a_input}
        }
        if (tipo == 'input') {
            var input_a_textarea;
            if ($("#input_a_textarea").prop('checked'))
                input_a_textarea = 1;
            else
                input_a_textarea = 0;
            params = {funcion: 'guardarRespuesta', id: $("#id").val(), id_pregunta: $("#id_pregunta").val(), tipo: $("#tipo").val(), orden: $("#orden").val(),
                input_a_textarea: input_a_textarea}
        }
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
