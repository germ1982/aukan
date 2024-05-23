<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_tipo_encuesta = ($_REQUEST['id_tipo_encuesta'] ) ? $_REQUEST['id_tipo_encuesta'] : 0;
$id_user = $_REQUEST['id_user'];

$bd = new baseDatos();
$bd->Conectarse();
$bd->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
if ($bd->numero_filas() > 0) {
    $tipo_encuesta = $bd->registro();
    $nombre = $tipo_encuesta['nombre'];
    $descripcion = $tipo_encuesta['descripcion'];
    $texto_inicial = $tipo_encuesta['texto_inicial'];
    $texto_final = $tipo_encuesta['texto_final'];
} else {
    $tipo_encuesta = [];
    $nombre = "";
    $descripcion = "";
    $texto_inicial = "";
    $texto_final = "";
}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Tipo de Encuesta</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form class="form-horizontal" id="formSeccion" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" value="<?php echo $id_tipo_encuesta; ?>" />       
        <input type="hidden" id="id_user" name="id_user" value="<?php echo $id_user; ?>" />       
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
                        <label for="descripcion" class="col-sm-3 control-label">Texto Inicial</label>
                        <div class="col-sm-9">
                            <textarea id="texto_inicial" name="texto_inicial" type="text" class="form-control"><?php echo $texto_inicial; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-3 control-label">Texto Final</label>
                        <div class="col-sm-9">
                            <textarea id="texto_final" name="texto_final" type="text" class="form-control"><?php echo $texto_final; ?></textarea>
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
    $("#guardarTipoEncuesta").click(function () {
        //alert( $("#id").val()+'*'+$("#nombre").val()+$("#descripcion").val()  ); 
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'guardarTipoEncuesta', id: $("#id").val(), tipo_encuesta: $("#nombre").val(), descripcion: $("#descripcion").val(),texto_inicial:$("#texto_inicial").val(),texto_final:$("#texto_final").val(),id_user:$("#id_user").val()}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        alert('Se ha guardado con éxito');
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerEncuestas'}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#tablaEncuestas").html(event.data);
                                    } else {
                                        $("#tablaEncuestas").html('No se pudieron traer los tipos de encuestas');
                                    }
                                });              
                    } else {
                        $("#tablaEncuestas").html('No se pudieron traer los tipos de encuestas');
                    }
                });
    })
</script>
