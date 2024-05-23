<?php
$dirBase = "../";
error_reporting(E_ALL);
require_once $dirBase . "comun/conectarse.php";
require_once $dirBase . "comun/libreria.php";
require_once "funciones.php";

$id_user = ($_REQUEST['id_user']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistema de Encuestas - Portal Web</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="../comun/lib/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../comun/lib/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="../css/ionicons.min.css">
        <script src="../css/dropzone.css"></script>
        <!-- jvectormap -->
        <!--<link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">-->
        <!-- Theme style -->
        <link rel="stylesheet" href="../css/AdminLTE.min.css">
        <link rel="stylesheet" href="../css/dropzone.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="../css/skins/_all-skins.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                   <!-- <div class="user-panel">
                        <div class="pull-left image">
                            <img src="../images/user-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php //echo $id_user; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div> -->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MENÚ OPCIONES</li>
                        <li class="treeview">
                            <a href="administrar_encuesta.php?id_user=<?php echo $id_user; ?>">
                                <i class="fa fa-laptop"></i>
                                <span>Administrar Encuestas</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="asignar_encuesta.php?id_user=<?php echo $id_user; ?>">
                                <i class="fa fa-laptop"></i>
                                <span>Asignar Encuestas</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                        </li>
                        <!-- <li class="treeview">
                             <a href="administrar_tipos_encuestas.php?id_user=<?php echo $id_user; ?>">
                                 <i class="fa fa-laptop"></i>
                                 <span>Administrar Tipos Encuestas</span>
                                 <span class="pull-right-container">
                                     <i class="fa fa-angle-left pull-right"></i>
                                 </span>
                             </a>
                         </li>-->
                        <li class="treeview">
                            <a href="reportes/reportes.php?id_user=<?php echo $id_user; ?>&desde=administrar_encuesta">
                                <i class="fa fa-laptop"></i>
                                <span>Reportes</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Panel Principal
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Panel Principal</li>
                    </ol>
                </section>

                <!-- Main content -->
                <!-- Main content -->
                <section class="content">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Administrar Encuestas</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-footer clearfix">       
                            <button class="btn btn-info btn-flat pull-left" id="tipo_encuesta" data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Tipo de Encuesta'  data-target='#encuesta_modal' href='modalCrearEncuesta.php?id_user=<?php echo $id_user; ?>'>Nuevo Tipo de Encuesta</button>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="content">
                            <div class="table-responsive text-justify text-primary" id="tablaEncuestas">
                            </div>
                            <div class="table-responsive text-justify text-primary" id="administracion">
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.box-body -->       

                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="#" target="_blank">MDSyT</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>
    <!-- /.content-wrapper -->



    <div class="control-sidebar-bg"></div>

</div>
<div class="modal fade" id="encuesta_modal" tabindex='-1' role="dialog" aria-labelledby="global-modal-title">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <br>
                <h4 class="modal-title" id="global-modal-title"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<!-- jQuery 2.2.3 -->
<script src="../comun/lib/jquery/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../comun/lib/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../js/app.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="../js/dropzone.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="js/dashboard2.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="../js/demo.js"></script>
<!-- ./wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
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
    });
    function administrarSecciones(id_tipo_encuesta) {
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'traerSecciones', id_tipo_encuesta: id_tipo_encuesta}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        $("#secciones_"+id_tipo_encuesta).html(event.data);
                    } else {
                        alert('No se pudieron traer las secciones');
                    }
                });
    }
    function borrarSeccion(id, id_tipo_encuesta) {
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'borrarSeccion', id: id}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerSecciones', id_tipo_encuesta: id_tipo_encuesta}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#secciones_"+id_tipo_encuesta).html(event.data);
                                    } else {
                                        alert('No se pudieron traer las secciones');
                                    }
                                });
                    } else {
                        alert('No se pudo borrar la seccion');
                    }
                });
    }
    function ocultarSecciones(id_tipo_encuesta){        
         $("#secciones_"+id_tipo_encuesta).html("");
    }
    function borrarPregunta(idpregunta, idseccion, id_tipo_encuesta) {
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'borrarPregunta', id: idpregunta}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerPreguntas', id: idseccion, id_tipo_encuesta: id_tipo_encuesta}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#preguntas_" + idseccion).html(event.data);
                                    } else {
                                        alert('No se pudieron traer las preguntas');
                                    }
                                });
                    } else {
                        alert('No se pudo borrar la seccion');
                    }
                });
    }
    function eliminarEncuesta(id_tipo_encuesta) {
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'eliminarEncuesta', id_tipo_encuesta: id_tipo_encuesta}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
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
                        alert('No se pudo borrar la encuesta');
                    }
                });
    }
    function administrarPreguntas(id, id_tipo_encuesta) {
        //alert( $("#id").val()+'*'+ id_tipo_encuesta   ); 
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'traerPreguntas', id: id, id_tipo_encuesta: id_tipo_encuesta}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        $("#preguntas_" + id).html(event.data);
                    } else {
                        alert('No se pudieron traer las preguntas');
                    }
                });
    }
    function ocultarPreguntas(id_seccion){        
         $("#preguntas_"+id_seccion).html("");
    }
    function administrarRespuestas(idpregunta, id_tipo_encuesta) {
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'traerRespuestas', id: idpregunta, id_tipo_encuesta: id_tipo_encuesta}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        $("#respuestas_" + idpregunta).html(event.data);
                    } else {
                        alert('No se pudieron traer las respuestas');
                    }
                });
    }
    function ocultarRespuestas(id_pregunta){        
         $("#respuestas_"+id_pregunta).html("");
    }
    function borrarRespuesta(id, id_pregunta) {
        $.ajax({
            method: "POST",
            url: "funciones.php",
            dataType: 'json',
            data: {funcion: 'borrarRespuesta', id: id}
        })
                .done(function (event) {
                    if (event.result == 'ok') {
                        $.ajax({
                            method: "POST",
                            url: "funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerRespuestas', id: id_pregunta}
                        })
                                .done(function (event) {
                                    if (event.result == 'ok') {
                                        $("#respuestas_" + id_pregunta).html(event.data);
                                    } else {
                                        alert('No se pudieron traer las preguntas');
                                    }
                                });
                    } else {
                        alert('No se pudo borrar la seccion');
                    }
                });
    }
    $('#encuesta_modal').on('hidden.bs.modal', function (e) {
        $(this).data('bs.modal', null);
    });
    $('#encuesta_modal').on('show.bs.modal', function (e) {
        //get data-id attribute of the clicked element
        //get data-id attribute of the clicked element
        var id = $(e.relatedTarget).data('id');

        //populate the textbox
        $(e.currentTarget).find('input[name="id"]').val(id);
        var title = $(e.relatedTarget).data('title');
        $(e.currentTarget).find('h4[id="global-modal-title"]').html(title);
    });
</script>
</body>
</html>
