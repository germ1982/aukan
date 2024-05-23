<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$bd = new baseDatos();
$bd->Conectarse();
$bd2 = new baseDatos();
$bd2->Conectarse();

$bd->select("SELECT * FROM mds_encuesta_tipo where baja_fecha is null ORDER BY nombre ASC");
$opciones = "";
 while ($encuesta = $bd->registro()) {
        $opciones .= '<option value="'.$encuesta['id_tipo'].'">'. $encuesta['nombre'].'</option>';
        
        }

$bd2->select("SELECT * FROM mds_encuesta_tipo WHERE baja_fecha is null AND id_tipo NOT IN (SELECT id_tipo_encuesta from mds_encuesta_seccion) ORDER BY nombre ASC");
$opcionesnew = "<option value='-1'> no se existen encuestas vacias</option>";
if($bd2->numero_filas()>0){ // encontro
    $opcionesnew = "";
}
 while ($encuesta = $bd2->registro()) {
        $opcionesnew .= '<option value="'.$encuesta['id'].'">'. $encuesta['nombre'].'</option>';
}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Clonar Encuesta</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form class="form-horizontal" id="formSeccion">
        
         <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">                  
                    <div class="form-group">
                    <label for="seccion" class="col-sm-3 control-label">Seleccione la encuesta a clonar: </label>
                        <div class="col-sm-9">
                            <select id="id_tipo_encuesta" name="id_tipo_encuesta">
                                <?php echo $opciones;?>
                            </select>
                        </div>
                        </div>
                        <div class="form-group">
                    <label for="seccion" class="col-sm-3 control-label">Seleccione nueva: </label>
                        <div class="col-sm-9">
                            <select id="id_tipo_encuesta_new" name="id_tipo_encuesta_new">
                            <?php echo $opcionesnew;?>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <button type="button" class="btn btn-success"  data-dismiss="modal" id="clonar">Clonar Encuesta</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<style>
    #ajaxSpinnerImage {
        display: none;
    }
</style>
<div id="ajaxSpinnerContainer">
    <img src="../images/spinner.gif" id="ajaxSpinnerImage" title="clonando..." />
</div>
<script>
    $("#clonar").click(function () {
      // alert( $("#id_tipo_encuesta").val()+'*' +$("#id_tipo_encuesta_new").val()  ); 

        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'clonarEncuesta', id_tipo_encuesta: $("#id_tipo_encuesta").val(), id_tipo_encuesta_new: $("#id_tipo_encuesta_new").val()},
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        alert('Se ha clonado con éxito');
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
                                        $("#tablaEncuestas").html('No hay encuestas disponibles');
                                    }
                                });
                    } else {
                        alert('No se pudo clonar la encuesta');
                    }
                });
        alert('Por favor espere mientras se clona la encuesta ..');

    })
</script>
