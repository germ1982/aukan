<?php
$dirBase = "";
require_once $dirBase . "comun/conectarse.php";
//require_once $dirBase . "comun/clases/clase_encuesta.php";
$id_user = $_REQUEST['id_user'];
$name_user = $_REQUEST['name_user'];
$apellido = $_REQUEST['apellido '];
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$bd = new baseDatos();
$bd->Conectarse();
$bd->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
$tipo_encuesta = $bd->registro();

if (isset($id_user)) {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Sistema de Encuestas- Portal Web</title>
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
                        <!-- Navbar Right Menu -->
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <!-- User Account: style can be found in dropdown.less -->
                                <li class="dropdown user user-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <img src="images/user-160x160.jpg" class="user-image" alt="User Image">
                                        <span class="hidden-xs"><?php echo $name_user; ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <!-- User image -->
                                        <li class="user-header">
                                            <img src="images/user-160x160.jpg" class="img-circle" alt="User Image">

                                            <p>
                                                <?php echo $name_user; ?>
                                            </p>
                                        </li>
                                        <!-- Menu Footer-->
                                        <!--<li class="user-footer">
                                            <div class="pull-left">
                                                <a href="perfil.php?user=<?php echo $name_user; ?>" class="btn btn-default btn-flat">Perfil</a>
                                            </div>
                                            <div class="pull-right">
                                                <a href="login.php" class="btn btn-default btn-flat">Cerrar Sesión</a>
                                            </div>
                                        </li>-->
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </header>
                <!-- Left side column. contains the logo and sidebar -->
                <aside class="main-sidebar">
                    <!-- sidebar: style can be found in sidebar.less -->
                    <section class="sidebar">
                        <!-- Sidebar user panel -->
                        <div class="user-panel">
                            <div class="pull-left image">
                                <img src="images/user-160x160.jpg" class="img-circle" alt="User Image">
                            </div>
                            <div class="pull-left info">
                                <p><?php echo $name_user; ?></p>
                                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                            </div>
                        </div>
                        <!-- /.search form -->
                        <!-- sidebar menu: : style can be found in sidebar.less -->
                        <ul class="sidebar-menu">
                            <li class="header">MENÚ OPCIONES</li>
                            <li class="treeview">
                                <a href="mis_encuestas.php?id_user=<?php echo $id_user; ?>">
                                    <i class="fa fa-chain"></i>
                                    <span>Mis Encuestas</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                            </li>
                           <!-- <li class="treeview">
                                <a href="administracion/reportes/reportes.php?id_user=<?php echo $id_user; ?>&desde=mis_encuestas">
                                    <i class="fa fa-laptop"></i>
                                    <span>Reportes</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                            </li> -->
                            <!--  <li class="treeview">
                                 <a href="encuesta.php?user=<?php echo $id_user; ?>">
                                     <i class="fa fa-laptop"></i>
                                     <span>Encuesta</span>
                                     <span class="pull-right-container">
                                         <i class="fa fa-angle-left pull-right"></i>
                                     </span>
                                 </a>
                             </li>
                            <li class="treeview">
                                 <a href="perfil.php?user=<?php echo $id_user; ?>">
                                     <i class="fa fa-user"></i> <span>Perfil</span>
                                     <span class="pull-right-container">
                                         <i class="fa fa-angle-left pull-right"></i>
                                     </span>
                                 </a>
                             </li> -->
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
                            <div class="box-header with-border" id="titulo">
                                <h3 class="box-title"><?php echo $tipo_encuesta['nombre']; ?></h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- paginacion -->
                           <div class="box-body" id="content">    
                               <div class="text-left"><input type="text" id="mySearch" class="form-control" placeholder="Debe ingresar al menos 5 caracteres"></div>
                                <table id="example" class="display nowrap table table-striped table-bordered" style="width:100%"></table>
                                <!--<div class="table-responsive text-justify text-primary" id="tablaEncuestas"></div>
                            </div> -->
                        </div>
                        <!-- /.box -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
            <footer class="main-footer">
                <strong>Copyright &copy; 2020 <a href="#" target="_blank">Sistema de Encuestas</a>.</strong> Todos los derechos reservados.
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
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/datatables_spanish.json"></script>
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
    <!-- ./wrapper -->
    <script type="text/javascript">
        function imprimirEncuesta(id_encuesta,id_tipo_encuesta){
            window.open("imprimir_encuesta.php?id_encuesta="+id_encuesta+"&id_tipo="+id_tipo_encuesta,'_blank');
        }
        function verEncuesta(id_encuesta,id_tipo_encuesta,seccion){
            $.ajax({
                url: "encuesta.php?id_user=" + <?php echo $id_user; ?> + "&id_encuesta=" + id_encuesta + "&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=" + id_tipo_encuesta+"&ver=1&id_seccion="+seccion,
                type: 'GET',
                success: function (data) {
                    $('#content').html(data);
                    $('#titulo').append("&nbsp;&nbsp;<button class='btn btn-primary' title='Ver' onclick='window.open(\"mis_encuestas_busqueda_externo.php?id_user=<?php echo $id_user; ?>&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>\",\"_self\")'><i class='fa fa-arrow-left'></i> Volver a todos los registros</button>");
                }
            });
        }
       /* function eliminarResultadoEncuesta(id_user,id_encuesta){
            if (confirm('¿Desea eliminar la encuesta #'+id_encuesta+'?')){
                $.ajax({
                    method: "POST",
                    url: "administracion/funciones.php",
                    dataType: 'json',
                    data: {funcion: 'eliminarResultadoEncuesta', id_user: id_user, id_encuesta: id_encuesta}
                })
                .done(function (event) {
                    if (event.result == 'ok') {              
                        $.ajax({
                            method: "POST",
                            url: "administracion/funciones.php",
                            dataType: 'json',
                            data: {funcion: 'traerMisEncuestasBusqueda', id_user: <?php echo $id_user; ?>, id_tipo_encuesta:<?php echo $id_tipo_encuesta; ?>}
                        })
                        .done(function (event) {
                            $('#example').DataTable().ajax.reload();
                        });
                    }else{
                        alert('No se ha podido almacenar la información: '+event.message);
                    }      
                });
            }
            
        }*/
      /*  function editarEncuesta(iduser, id_encuesta, id_tipo_encuesta) {
            $.ajax({
                url: "encuesta.php?id_user=" + iduser + "&id_encuesta=" + id_encuesta + "&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=" + id_tipo_encuesta,
                type: 'GET',
                success: function (data) {
                    $('#content').html(data);
                    $('#titulo').append("&nbsp;&nbsp;<button class='btn btn-primary' title='Ver' onclick='window.open(\"mis_encuestas_busqueda.php?id_user=<?php echo $id_user; ?>&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>\",\"_self\")'><i class='fa fa-arrow-left'></i> Volver a todos los registros</button>");
                }
            });
        }*/
       /* function clonarResultadoEncuesta(iduser,id_encuesta,id_tipo_encuesta){
            $.ajax({
                method: "POST",
                url: "administracion/funciones.php",
                dataType: 'json',
                data: {funcion: 'clonarResultadoEncuesta', id_user: iduser, id_encuesta: id_encuesta, id_tipo_encuesta:id_tipo_encuesta}
            })
            .done(function (event) {
                if (event.result == 'ok') {
                    $.ajax({
                        url: "encuesta.php?id_user=" + iduser + "&id_encuesta=" + event.id_encuesta + "&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=" + id_tipo_encuesta,
                        type: 'GET',
                        success: function (data) {
                            $('#content').html(data);
                            $('#titulo').append("&nbsp;&nbsp;<button class='btn btn-primary' title='Ver' onclick='window.open(\"mis_encuestas_busqueda.php?id_user=<?php echo $id_user; ?>&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>\",\"_self\")'><i class='fa fa-arrow-left'></i> Volver a todos los registros</button>");
                        }
                    });
                }else{
                    alert('No se ha podido almacenar la información: '+event.message);
                }
            });
        }*/
        $(document).ready(function () {
            $.ajax({
                method: "POST",
                url: "administracion/funciones.php",
                dataType: 'json',
                data: {funcion: 'traerMisEncuestasBusquedaExterna', id_user: <?php echo $id_user; ?>, id_tipo_encuesta:<?php echo $id_tipo_encuesta; ?>}
            })
            .done(function (event) {
                 var table = $('#example').DataTable({
                    dom: 'lrtip',
                    "language": {
                        "url": "js/datatables_spanish.json"
                    },
                    ajax: "administracion/funciones.php?funcion=traerMisEncuestasBusquedaExterna&id_user=<?php echo $id_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>",
                    columns: event.columns,
                    order: [[ 2, "asc" ],[1, "desc"]]
                  });

                  $('#example').hide();


                  $('#mySearch').keyup( function() {
                     if (($('#mySearch').val().trim()).length > 5 && $('#mySearch').val() != ''){
                        $('#example').show();
                        table.search($(this).val()).draw();
                    }else{
                        $('#example').hide();
                    }
                  } );
                /*$('#example').DataTable({
                    "language": {
                        "url": "js/datatables_spanish.json"
                    },
                    ajax: "administracion/funciones.php?funcion=traerMisEncuestasBusquedaExterna&id_user=<?php echo $id_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>",
                    columns: event.columns,
                    order: [[ 2, "asc" ],[1, "desc"]]
                });*/
            });
            $("#nuevaRespuesta").click(function () {
                $.ajax({
                    url: "encuesta.php?id_user=<?php echo $id_user; ?>&id_encuesta=0&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>",
                    type: 'GET',
                    success: function (data) {
                        $('#content').html(data);
                        $('#titulo').append("&nbsp;&nbsp;<button class='btn btn-primary' title='Ver' onclick='window.open(\"mis_encuestas_busqueda.php?id_user=<?php echo $id_user; ?>&name_user=<?php echo $name_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>\",\"_self\")'><i class='fa fa-arrow-left'></i> Volver a todos los registros</button>");
                    }
                });
            });
        });
    </script>
    </body>
    </html>

    <?php
}
?>