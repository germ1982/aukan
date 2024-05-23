<?php
$idpaciente = $_REQUEST['idpaciente'];
$dirBase = "../";
            function deleteFile($i) {
        global $archivos;
        unset($archivos[$i]);
    }   
    
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="minimum-scale=1.0,user-scalable=no,width=device-width,initial-scale=1.0" />

        <link rel="stylesheet" href="../styles/bootstrap-overrides.css">
        <link rel="stylesheet" href="../comun/lib/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../comun/lib/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../comun/lib/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="../comun/lib/animate.css/animate.css">
        <link rel="stylesheet" href="../styles/base.css">
        <link rel="icon" type="../image/x-icon" href="favicon.ico">
    </head>
    <body>
        <form action="uploadFile.php?idpaciente=<?php echo $idpaciente; ?>" method="post" enctype="multipart/form-data" name="form1">
            <div class="form-group">
                <label for="fecha" class="col-sm-3 control-label">Fecha</label>
                <div class="col-sm-3">
                    <input id="fecha" name="fecha"  onkeyup="mascara(this, '/', patron, true)" type="text" class="form-control" placeholder="dd/mm/aaaa" value="<?php echo date('d/m/Y'); ?>">
                </div>
            </div>
            <div class="form-group">
                <input type="file" name="archivo[]" id="archivo" multiple="multiple" onchange="form1.submit();">
            </div>
        </form>
    </body>
</html>       
<script src="<?php echo $dirBase; ?>/comun/funcionesVarias.js"></script> 