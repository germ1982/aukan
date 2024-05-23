<?php
$dirBase = "";
require_once $dirBase . "comun/conectarse.php";
?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
            <title>Familias solidarias</title>
            <!-- Tell the browser to be responsive to screen width -->
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <!-- Bootstrap 3.3.6 -->
            <link rel="stylesheet" href="comun/lib/bootstrap/css/bootstrap.min.css">
            <!-- Font Awesome -->
            <link rel="stylesheet" href="comun/lib/font-awesome/css/font-awesome.min.css">
            <!-- Ionicons -->
            <link rel="stylesheet" href="css/ionicons.min.css">
            <!-- jvectormap -->
            <!--<link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">-->
            <!-- Theme style -->
            <link rel="stylesheet" href="css/AdminLTE.min.css">
            <!-- AdminLTE Skins. Choose a skin from the css/skins
                 folder instead of downloading all of them to reduce the load. -->
            <link rel="stylesheet" href="css/skins/_all-skins.min.css">

        </head>
        <body class="hold-transition skin-blue sidebar-mini">
            <div class="wrapper">
                <header class="main-header">

                </header>
                <!-- Content Wrapper. Contains page content -->
                <div class="content">
                    <!-- Main content -->
                    <section class="content">
                        <!-- TABLE: LATEST ORDERS -->
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Datos Personales</h3>                        
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="Nombre y Apellido" id="nombre_apellido" >
                                    <span class="glyphicon glyphicons-user form-control-feedback"></span>
                                </div>                
                                <div class="form-group has-feedback">
                                    <input type="number" class="form-control" placeholder="Dni" id="dni">
                                    <span class="glyphicon glyphicons-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="date" class="form-control" placeholder="Fecha de Nacimiento" id="fecha_nacimiento" >
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="number" class="form-control" placeholder="Edad" id="edad">
                                    <span class="glyphicon glyphicons-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="number" class="form-control" placeholder="Teléfono" id="telefono">
                                    <span class="glyphicon glyphicons-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="Mail" id="mail">
                                    <span class="glyphicon glyphicons-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                <select class="form-control" id="localidad">
                                    <option value="0">Seleccione..</option>
                                    <option value="Neuquén Capital">Neuquén Capital</option>
                                    <option value="Plottier">Plottier</option>
                                    <option value="Senillosa">Senillosa</option>
                                    <option value="Centenario">Centenario</option>
                                    <option value="Localidades contiguas (radio 20km NQN Cap)">Localidades contiguas (radio 20km NQN Cap)</option>
                                </select>
                                <span class="glyphicon glyphicons-user form-control-feedback"></span>
                            </div> 
                                <div class="row">
                                    <!-- /.col -->
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-success" id="cargarFamilia">Guardar</button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
            <footer class="main">
                <strong>Copyright &copy; 2021 <a href="#" target="_blank">Ministerio de Desarrollo Social y Trabajo</a>.</strong> Todos los derechos reservados.
            </footer>
        </div>
        <!-- /.content-wrapper -->
        <div class="control-sidebar-bg"></div>

    </div>
    <!-- jQuery 2.2.3 -->
    <script src="comun/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="comun/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="js/dashboard2.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <script src="js/demo.js"></script>
    <script>
            $(function () {
              
                $('#cargarFamilia').click(function(){
                    var nombre = $('#nombre_apellido').val();
                    var dni = $('#dni').val();
                    var fecha_nacimiento = $('#fecha_nacimiento').val();
                    var edad = $('#edad').val();
                    var telefono = $('#telefono').val();
                    var mail = $('#mail').val();
                    var localidad = $('#localidad').val();

                    if (nombre === "" || dni === "" || fecha_nacimiento === "" || edad === "" || telefono === "" || mail === "" || localidad === "0"){
                        alert('Debe completar todos los campos');
                        $('#nombre_apellido').focus();
                    }else{
                      //alert(nombre+dni+fecha_nacimiento+edad+telefono+mail+localidad);
                        $.ajax({
                            method: "POST",
                            url: "administracion/funciones.php",
                            dataType: 'json',
                            data: {funcion: 'guardarFamiliasolidaria',nombre_apellido: nombre,dni:dni,fecha_nacimiento:fecha_nacimiento,edad:edad,telefono:telefono,mail:mail,localidad:localidad}
                        })
                        .done(function (event) {
                            if (event.result == 'ok'){
                                alert('Se ha registrado correctamente');
                                location.reload();
                            }else{
                                alert('No se ha podido crear el registro');
                            } 
                        });
                    }
                });
            });
        </script>
    </body>
    </html>
    <?php
?>