<?php
$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
$bd = new BaseDatos();
$bd->Conectarse();
$id_tipo_encuesta = ($_REQUEST['id_tipo_encuesta'] ) ? $_REQUEST['id_tipo_encuesta'] : 0;
$id_user = $_REQUEST['id_user'];
$bd->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
$tipo_encuesta = $bd->registro();
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br>
    <h4 class="modal-title" id="global-modal-title">Personalización para Impresión</h4>
</div>
<div class="container-fluid">
    <br>
    <!-- Contenido -->
    <form action="uploadFile.php?id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>&id_user=<?php echo $id_user; ?>" method="post" enctype="multipart/form-data" name="form1">
        <input type="hidden" id="id" name="id" value="<?php echo $id_tipo_encuesta; ?>" />       
        <input type="hidden" id="id_user" name="id_user" value="<?php echo $id_user; ?>" />       
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">                    
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-3 control-label">Encabezado para impresión</label>
                        <div class="col-sm-9">
                            <input type="file" name="header" id="header"/>
                            <?php if ($tipo_encuesta['header']) { ?>
                                <img src="../../img/uploads_encuesta/<?php echo $tipo_encuesta['header']; ?>" width="20%" height="20%" />
                            <?php } ?>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-3 control-label">Pie para impresión</label>
                        <div class="col-sm-9">
                            <input type="file" name="footer" id="footer"/>            
                            <?php if ($tipo_encuesta['footer']) { ?>
                            <img src="../../img/uploads_encuesta/<?php echo $tipo_encuesta['footer']; ?>" width="20%" height="20%" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success"  data-dismiss="modal" onclick="form1.submit();">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

