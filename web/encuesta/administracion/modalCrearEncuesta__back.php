<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_user = $_REQUEST['id_user'];

$bd = new baseDatos();
$bd->Conectarse();
$bd->select("SELECT * FROM encuesta_tipo WHERE baja_fecha IS NULL ");
if ($bd->numero_filas() > 0) {
    $tipo_encuesta = $bd->registro();
    $nombre = $tipo_encuesta['nombre'];
    $descripcion = $tipo_encuesta['descripcion'];
} else {
    $tipo_encuesta = [];
    $nombre = "";
    $descripcion = "";
}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Nueva Encuesta</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form class="form-horizontal" id="formSeccion">
        <input type="hidden" id="id" name="id" value="<?php echo $id_tipo_encuesta; ?>" />      
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">                    
                    <div class="form-group">
                        <label for="nombre" class="col-sm-3 control-label">Tipo de Encuesta</label>
                        <div class="col-sm-9">
                            <input id="nombre" name="nombre" type="text" class="form-control" value="<?php echo $nombre; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <input id="descripcion" name="descripcion" type="text" class="form-control" value="<?php echo $descripcion; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success"  data-dismiss="modal" id="guardarTipoEncuesta">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $("#guardarEncuesta").click(function () {
        //alert( $("#id_user").val()+'*'+$("#id_tipo_encuesta").val()+$("#fecha_creacion").val()   ); 
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'guardarEncuesta', id_user: $("#id_user").val(), id_tipo_encuesta: $("#id_tipo_encuesta").val(), fecha_creacion: $("#fecha_creacion").val()}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        alert('Se ha guardado con éxito');

                    } else {
                        $("#administracion").html('No se pudieron traer los tipos de encuestas');
                    }
                });
    })
</script>
