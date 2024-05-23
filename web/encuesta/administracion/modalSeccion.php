<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_seccion = $_REQUEST['id_seccion'];
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$bd = new baseDatos();
$bd->Conectarse();

$bd->select("SELECT * FROM mds_encuesta_seccion WHERE id_seccion = $id_seccion");
$seccion = $bd->registro();
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Sección</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form class="form-horizontal" id="formSeccion">
        <input type='hidden' id='id' name="id" value='<?php echo $id_seccion; ?>'>    
        <input type='hidden' id='id_tipo_encuesta' name="id_tipo_encuesta" value='<?php echo $id_tipo_encuesta; ?>'>    
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">                    
                    <div class="form-group">
                        <label for="seccion" class="col-sm-3 control-label">Sección</label>
                        <div class="col-sm-9">
                            <input id="seccion" name="seccion" type="text" class="form-control" value='<?php echo $seccion['seccion']; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="explicacion" class="col-sm-3 control-label">Explicación</label>
                        <div class="col-sm-9">
                            <textarea id="explicacion" name="explicacion" class="form-control"  rows="4"><?php echo $seccion['explicacion']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="orden" class="col-sm-3 control-label">Orden</label>
                        <div class="col-sm-9">
                            <input id="orden" name="orden" type="text" class="form-control" value='<?php echo $seccion['orden']; ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success"  data-dismiss="modal" id="guardarSeccion">Guardar</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $("#guardarSeccion").click(function () {
       
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'guardarSeccion', id: $("#id").val(), seccion: $("#seccion").val(), explicacion: $("#explicacion").val(), orden: $("#orden").val(), id_tipo_encuesta: $("#id_tipo_encuesta").val()}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        alert('Se ha guardado con éxito');
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerSecciones', id_tipo_encuesta: $('#id_tipo_encuesta').val()}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#administracion").html(event.data);
                                    } else {
                                        alert('No se pudieron traer las secciones');
                                    }
                                });
                    } else {
                        alert('No se pudieron traer las secciones');
                    }
                });
    })
</script>
