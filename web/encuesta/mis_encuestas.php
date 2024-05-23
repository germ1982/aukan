<?php
$dirBase = "";
require_once $dirBase . "comun/conectarse.php";
//require_once $dirBase . "comun/clases/clase_encuesta.php";
$id_user = $_REQUEST['id_user'];
$name_user = $_REQUEST['name_user'];
$bd = new BaseDatos();
$bd->Conectarse();
if (isset($id_user)) {
        $bd->select("SELECT externo FROM mds_seg_usuario WHERE idusuario = $id_user");
        $user = $bd->registro();
        $externo = $user['externo'];
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
                                    <ul class="dropdown-menu">
                                        <!-- User image -->
                                        <li class="user-header">
                                            <img src="images/user-160x160.jpg" class="img-circle" alt="User Image">
                                            <p>
                                                <?php echo $name_user; ?>
                                            </p>
                                        </li>
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
                            <?php if ($externo == 0){ ?>
                            <li class="treeview">
                                <a href="administracion/reportes/reportes.php?id_user=<?php echo $id_user; ?>&desde=mis_encuestas">
                                    <i class="fa fa-laptop"></i>
                                    <span>Reportes</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                            </li>
                            <?php } ?>
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
                            <div class="box-header with-border">
                                <h3 class="box-title">Mis Encuestas</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <?php
                            if (!$id_user) {
                                ?>
                                <div class="box-footer text-justify text-primary">
                                    No tiene encuestas asignadas
                                </div>
                                <?php
                            } else {
                                ?>
                                <!-- paginacion -->
                                <div class="box-body" id="content">    
                                    <div class="table-responsive text-justify text-primary" id="tablaEncuestas"></div>
                                </div>
                                <?php
                            }
                            ?>
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
    <!-- ./wrapper -->
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                method: "POST",
                url: "administracion/funciones.php",
                dataType: 'json',
                data: {funcion: 'traerMisEncuestas',id_user: <?php echo $id_user; ?>}
            })
                    .done(function (event) {
                        if (event.result == 'ok') {
                            $("#tablaEncuestas").html(event.data);
                        } else {
                            $("#tablaEncuestas").html('No tiene encuestas asignadas');
                        }
                    });
        });
    </script>
    </body>
    </html>

    <?php
}
?>