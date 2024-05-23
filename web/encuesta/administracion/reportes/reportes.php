<?php
$dirBase = "../../";
require_once $dirBase . "comun/conectarse.php";
require_once $dirBase . "comun/libreria.php";
//require_once $dirBase . "comun/clases/clase_profesionales.php";
//$profesional = new clase_profesionales($_REQUEST['idprofesional']);
$desde = $_REQUEST['desde'];
$id_user = $_REQUEST['id_user'];
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
        <link rel="stylesheet" href="../../comun/lib/bootstrap/css/bootstrap.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../comun/lib/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="../../css/ionicons.min.css">
        <!-- jvectormap -->
        <!--<link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">-->
        <!-- Theme style -->
        <link rel="stylesheet" href="../../css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="../../css/skins/_all-skins.min.css">

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
                                        <img src="../../images/user-160x160.jpg" class="img-circle" alt="User Image">
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-right">
                                            <a href="../../login.php" class="btn btn-default btn-flat">Cerrar Sesión</a>
                                        </div>
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
                            <img src="../../images/user-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php //echo $profesional->nombre(); ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MENÚ OPCIONES</li>
                        <?php if ($desde == 'mis_encuestas'){ ?>
                            <li class="treeview">
                                <a href="../../mis_encuestas.php?id_user=<?php echo $id_user; ?>">
                                    <i class="fa fa-chain"></i>
                                    <span>Mis Encuestas</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                            </li>
                        <?php }else{ ?>
                        <li class="treeview">
                            <a href="../administrar_encuesta.php?id_user=<?php echo $id_user; ?>">
                                <i class="fa fa-laptop"></i>
                                <span>Administrar Encuestas</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="treeview">
                            <a href="reportes.php?id_user=<?php echo $id_user; ?>&desde=<?php echo $desde; ?>">
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
                </section>

                <!-- Main content -->
                <!-- Main content -->
                <section class="content">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Reportes</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
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
                        <div class="box-footer clearfix">
                            <div class="form-group">
                                <input type="hidden" id="id_user" value="<?php echo $id_user; ?>" />
                                <label class="label-lg">Seleccione Tipo de Encuesta</label>
                                <select id="id_tipo_encuesta" class="form-control">
                                    <option value="-1"></option>
                                    <?php
                                        $query = "SELECT mds_encuesta_tipo.nombre,mds_encuesta.id_tipo_encuesta FROM mds_encuesta JOIN mds_encuesta_tipo ON (id_tipo = id_tipo_encuesta) WHERE mds_encuesta.completa = 1 GROUP BY id_tipo_encuesta ";
                                        $bd = new baseDatos();
                                        $bd->Conectarse();
                                        $bd->select($query);
                                        while ($empresa = $bd->registro()){                           
                                    ?>
                                    <option value="<?php echo $empresa['id_tipo_encuesta']; ?>"><?php echo $empresa['nombre']; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="label-lg">Fecha Desde: </label>
                                <input type="text" onkeyup="mascara(this, '/', patron, true)" id="fecha_desde" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="label-lg">Fecha Hasta: </label>
                                <input type="text" onkeyup="mascara(this, '/', patron, true)" id="fecha_hasta" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="label-lg">Seleccione el Reporte</label>
                            <select class="form-control" id="tipo_reporte">
                                <option value="-1"></option>
                                <option value="1" selected="">Resultados de Encuestas</option>
                            </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info" id="generarReporte">Generar Reporte</button>
                            </div>
                        </div>
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
<script src="../../comun/funcionesVarias.js"></script>
<!-- jQuery 2.2.3 -->
<script src="../../comun/lib/jquery/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../comun/lib/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../js/app.min.js"></script>
<!-- Sparkline -->
<script src="../../plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="js/dashboard2.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="../../js/demo.js"></script>
<!-- ./wrapper -->
<script type="text/javascript">
    $('#generarReporte').click(function(){
        window.open('funcionesReportes.php?funcion='+$('#tipo_reporte').val()+'&empresa='+$('#id_tipo_encuesta').val()+'&id_user='+$('#id_user').val()+'&fecha_desde='+$('#fecha_desde').val()+'&fecha_hasta='+$('#fecha_hasta').val(),'_blank' );
    });

</script>
</body>
</html>
