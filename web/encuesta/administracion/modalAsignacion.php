<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$id = ($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$bd = new baseDatos();
$bd->Conectarse();
$bd1 = new baseDatos();
$bd1->Conectarse();

$bd1->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
$tipo_encuesta = $bd1->registro();

$bd->select("SELECT * FROM mds_encuesta_usuario_tipo JOIN mds_seg_usuario ON (id_usuario = idusuario) WHERE id_usuario_tipo = $id");
$asignacion = $bd->registro();
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Asignación de Usuarios para <?php echo $tipo_encuesta['nombre']; ?> <small><?php echo $tipo_encuesta['descroćopm']; ?></small></h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form class="form-horizontal" id="formSeccion">
        <input type='hidden' id='id' name="id" value='<?php echo $id; ?>'>
        <input type='hidden' id='id_tipo_encuesta' name="id_tipo_encuesta" value='<?php echo $id_tipo_encuesta; ?>'>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <?php
                        if ($id != 0) {
                            echo '<div class="col-sm-12"><strong>' . $asignacion['apellido'] . ' ' . $asignacion['nombre'] . ' ' . "(DNI: " . $asignacion['dni'] . ")" . '</strong></div>';
                            echo '<input type="hidden" id="users" name="users[]" />';
                        } else {
                        ?>
                            <label for="pregunta" class="col-sm-3 control-label">Seleccione Usuarios</label>
                            <div class="col-sm-9">
                                <select class="js-example-basic-multiple form-control input-lg" id="users" name="users[]" multiple="multiple" style="width: 100%">
                                    <?php
                                    $bd1->select("SELECT idusuario, nombre, apellido, dni FROM mds_seg_usuario WHERE activo = 1 ORDER BY apellido ASC, nombre ASC");
                                    while ($usuario = $bd1->registro()) {
                                        echo '<option value="' . $usuario['idusuario'] . '">' . strtoupper($usuario['apellido']) . ' ' . strtoupper($usuario['nombre']) . ' (DNI:' . $usuario['dni'] . ')' . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="requerida" class="col-sm-3 control-label">Puede contestar más de una encuesta de este tipo?</label>
                        <div class="col-sm-9 radio input-lg">
                            <label>
                                <input name="respuesta_multiple" id="respuesta_multiple" type="checkbox" <?php if ($asignacion['respuesta_multiple'] == 1) echo "checked"; ?>>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="requerida" class="col-sm-3 control-label">Puede continuar encuestas de otros?</label>
                        <div class="col-sm-9 radio input-lg">
                            <label>
                                <input name="respuesta_general" id="respuesta_general" type="checkbox" <?php if ($asignacion['respuesta_general'] == 1) echo "checked"; ?>>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="requerida" class="col-sm-3 control-label">Puede ver los reportes generales?</label>
                        <div class="col-sm-9 radio input-lg">
                            <label>
                                <input name="reportes_generales" id="reportes_generales" type="checkbox" <?php if ($asignacion['reportes_generales'] == 1) echo "checked"; ?>>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success" data-dismiss="modal" id="guardarAsignacion">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    $("#guardarAsignacion").click(function() {
        var respuesta_multiple;
        var reportes_generales;
        var respuesta_general;
        if ($("#respuesta_multiple").prop('checked'))
            respuesta_multiple = 1;
        else
            respuesta_multiple = 0;
        if ($("#respuesta_general").prop('checked'))
            respuesta_general = 1;
        else
            respuesta_general = 0;
        if ($("#reportes_generales").prop('checked'))
            reportes_generales = 1;
        else
            reportes_generales = 0;
        $.ajax({
                method: "POST",
                url: "funciones.php",
                dataType: 'json',
                data: {
                    funcion: 'guardarAsignacion',
                    id: $("#id").val(),
                    id_tipo_encuesta: $("#id_tipo_encuesta").val(),
                    respuesta_multiple,
                    respuesta_general,
                    reportes_generales,
                    users: $("#users").val()
                }
            })
            .done(function(event) {
                if (event.result == 'ok') {
                    alert('Se ha guardado con éxito');
                    $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {
                                funcion: 'traerAsignaciones',
                                id_tipo_encuesta: $("#id_tipo_encuesta").val()
                            }
                        })
                        .done(function(event) {
                            if (event.result == 'ok') {
                                $("#asignaciones_" + $("#id_tipo_encuesta").val()).html(event.data);
                            } else {
                                alert('No se pudieron traer las secciones');
                            }
                        });
                } else {
                    //alert(event.result);
                }
            });
    })
</script>