<?php
$dirBase = "../../../";
require_once $dirBase . "comun/conectarse.php";
require_once $dirBase . "comun/libreria.php";
require_once $dirBase . "comun/clases/clase_profesionales.php";
$profesional = new clase_profesionales($_REQUEST['idprofesional']);
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
        <link rel="stylesheet" href="../../../ambulatorio/turnero/historia_clinica/mockup/lib/bootstrap/css/bootstrap.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../../ambulatorio/turnero/historia_clinica/mockup/lib/font-awesome/css/font-awesome.min.css">
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

                <!-- Logo -->
                <a href="../../index2.html" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>TC</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Sistema de Encuestas</b></span>
                </a>

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
                                    <img src="../../images/user-160x160.jpg" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo $profesional->nombre(); ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="../../images/user-160x160.jpg" class="img-circle" alt="User Image">

                                        <p>
                                            <?php echo $profesional->nombre(); ?>
                                        </p>
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
                            <p><?php echo $profesional->nombre(); ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MENÚ OPCIONES</li>
                        <li class="treeview">
                            <a href="../administrar_encuesta.php?user=<?php echo $user; ?>">
                                <i class="fa fa-laptop"></i>
                                <span>Administrar Encuestas</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="reportes/reportes.php?user=<?php echo $user; ?>">
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
                                <label class="label-lg">Seleccione Empresa</label>
                                <select id="id_tipo_encuesta" class="form-control">
                                    <option value="-1"></option>
                                    <?php
                                        $query = "SELECT distinct(SUBSTR(mail,INSTR(mail,'@')+1)) as empresa FROM pacientes_temp JOIN encuesta ON (pacientes_temp.idpaciente = encuesta.idpaciente_temp) WHERE encuesta.completa = 1 and (SUBSTR(mail,INSTR(mail,'@')+1)) NOT IN ('hotmail.com','gmail.com','yahoo.com.ar','hotmail.com.ar','speedy.com.ar')";
                                        $bd = new baseDatos();
                                        $bd->Conectarse();
                                        $bd->select($query);
                                        while ($empresa = $bd->registro()){
                                    ?>
                                    <option value="<?php echo $empresa['empresa']; ?>"><?php echo $empresa['empresa']; ?></option>
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
                                <option value="1">Resultados de Encuestas</option>
                                <option value="2">Reporte de Peso,IMC,Circ.Cintura</option>
                                <option value="3">Reporte de Problemas</option>
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
            <strong>Copyright &copy; 2017 <a href="#" target="_blank">Sistema de Encuestas</a>.</strong> Todos los derechos reservados.
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
<script src="../../../comun/funcionesVarias.js"></script>
<!-- jQuery 2.2.3 -->
<script src="../../../ambulatorio/turnero/historia_clinica/mockup/lib/jquery/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../../ambulatorio/turnero/historia_clinica/mockup/lib/bootstrap/js/bootstrap.min.js"></script>
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
        window.open('funcionesReportes.php?funcion='+$('#tipo_reporte').val()+'&empresa='+$('#id_tipo_encuesta').val()+'&fecha_desde='+$('#fecha_desde').val()+'&fecha_hasta='+$('#fecha_hasta').val(),'_blank' );
    });

</script>
</body>
</html>
