<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_seccion = $_REQUEST['id_seccion'];
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$id = $_REQUEST['id'];
$bd = new baseDatos();
$bd->Conectarse();

$bd->select("SELECT * FROM mds_encuesta_pregunta WHERE id_pregunta = $id ORDER BY orden ASC");
$seccion = $bd->registro();
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Pregunta</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form class="form-horizontal" id="formSeccion">
        <input type='hidden' id='id' name="id" value='<?php echo $id; ?>'>    
        <input type='hidden' id='id_tipo_encuesta' name="id_tipo_encuesta" value='<?php echo $id_tipo_encuesta; ?>'>    
        <input type='hidden' id='id_seccion' name="id_seccion" value='<?php echo $id_seccion; ?>'>    
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">                    
                    <div class="form-group">
                        <label for="pregunta" class="col-sm-3 control-label">Pregunta</label>
                        <div class="col-sm-9">
                            <input id="pregunta" name="pregunta" type="text" class="form-control" value='<?php echo $seccion['pregunta']; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="requerida" class="col-sm-3 control-label">Requerida</label>
                        <div class="col-sm-9 radio input-lg">
                            <label>
                                <input name="requerida" id="requerida" type="checkbox" <?php if ($seccion['requerida'] == 1) echo "checked"; ?> >
                            </label>
                        </div>  
                    </div>
                    <div class="form-group">
                        <label for="requerida" class="col-sm-3 control-label">Sirve para mostrar como encabezado (como por ej es dni, nombre, etc?)</label>
                        <div class="col-sm-9 radio input-lg">
                            <label>
                                <input name="encabezado" id="encabezado" type="checkbox" <?php if ($seccion['encabezado'] == 1) echo "checked"; ?> >
                            </label>
                        </div>  
                    </div>
                    <div class="form-group">
                        <label for="requerida" class="col-sm-3 control-label">Sirve para realizar búsquedas (como por ej es dni?)</label>
                        <div class="col-sm-9 radio input-lg">
                            <label>
                                <input name="busqueda" id="busqueda" type="checkbox" <?php if ($seccion['busqueda'] == 1) echo "checked"; ?> >
                            </label>
                        </div>  
                    </div>
                    <div class="form-group">
                        <label for="orden" class="col-sm-3 control-label">Orden</label>
                        <div class="col-sm-9">
                            <input id="orden" name="orden" type="number" class="form-control" value='<?php echo $seccion['orden']; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success"  data-dismiss="modal" id="guardarPregunta">Guardar</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $("#guardarPregunta").click(function () {
       // alert( $("#pregunta").val()+'*'+ $("#id").val()+'*'+$("#orden").val()+'*' +$("#requerida").val()+$("#id_seccion").val()); 
        var requerida;
        if ($("#requerida").prop('checked'))
            requerida = 1;
        else
            requerida = 0;
        var encabezado;
        if ($("#encabezado").prop('checked'))
            encabezado = 1;
        else
            encabezado = 0;
        var busqueda;
        if ($("#busqueda").prop('checked'))
            busqueda = 1;
        else
            busqueda = 0;
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'guardarPregunta', id: $("#id").val(), pregunta: $("#pregunta").val(), requerida: requerida, encabezado: encabezado, orden: $("#orden").val(),busqueda:busqueda, id_seccion: $("#id_seccion").val(),id_tipo_encuesta: $("#id_tipo_encuesta").val()  }
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        alert('Se ha guardado con éxito');
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerPreguntas',id:$("#id_seccion").val(), id_tipo_encuesta: $('#id_tipo_encuesta').val()}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#preguntas_"+$("#id_seccion").val()).html(event.data);
                                    } else {
                                        alert('No se pudieron traer las preguntas');
                                    }
                                });
                    } else {
                        //alert(event.result);
                    }
                });
    })
</script>
