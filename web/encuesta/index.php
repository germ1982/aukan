<?php
$dirBase = "";
require_once $dirBase . "comun/conectarse.php";
require_once $dirBase . "comun/libreria.php";

$id_user = $_REQUEST['id_user'];
$name_user = $_REQUEST['name_user'];
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
//$id_seccion = ($_REQUEST['id_seccion'] ) ?  $_REQUEST['id_seccion'] :0;
$id_seccion = 0;

if (isset($id_user)) {
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
                    <!-- Logo -->
                    <a href="index2.html" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <span class="logo-mini"><b>TC</b></span>
                        <!-- logo for regular state and mobile devices -->
                        
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
                                        <li class="user-footer">
                                            <div class="pull-left">
                                                <a href="perfil.php?user=<?php echo $name_user; ?>" class="btn btn-default btn-flat">Perfil</a>
                                            </div>
                                            <div class="pull-right">
                                                <a href="login.php" class="btn btn-default btn-flat">Cerrar Sesión</a>
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
                                <a href="encuesta.php?user=<?php echo $id_user; ?>">
                                    <i class="fa fa-laptop"></i>
                                    <span>Encuesta</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                            </li>
                            <!-- <li class="treeview">
                                 <a href="solicitar_turno.php?user=<?php echo $user; ?>">
                                 <i class="fa fa-laptop"></i>
                                 <span>Solicitud de Turnos</span>
                                 <span class="pull-right-container">
                                   <i class="fa fa-angle-left pull-right"></i>
                                 </span>
                               </a>
                             </li>
                             <li class="treeview">
                                 <a href="index.php?user=<?php echo $user; ?>">
                                 <i class="fa fa-edit"></i><span>Consulta de Turnos</span>
                                 <span class="pull-right-container">
                                   <i class="fa fa-angle-left pull-right"></i>
                                 </span>
                               </a>
                             </li> -->
                            <li class="treeview">
                                <a href="perfil.php?user=<?php echo $id_user; ?>">
                                    <i class="fa fa-user"></i> <span>Perfil</span>
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
                                <h3 class="box-title">Encuesta</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" id="content">
                                <div class="table-responsive text-justify text-primary">
                                    <p>Estimado/a:</p>

                                    <p>Estamos iniciando el programa de promoción de la salud, Potenciar Mi Salud. Le escribo para invitarlo a unirse a este programa que tiene como fin ayudar a mejorar la salud sus empleados y de su comunidad. </p>
                                    <p>A través de un convenio entre la Institución y el centro at work, ofrecemos un modelo de intervención diferente. Basado en la implementación de cambios de conducta desde un abordaje sustentable de prevención en salud de enfermedades crónicas, buscamos alcanzar un estilo de vida saludable basado en modificación de conductas individuales y apoyados desde el espacio laboral. 
                                        Este es un desafío importante, para lo que les pedimos nos ayudemos mutuamente, así podremos alcanzar un objetivo beneficioso para todos. </p>
                                    <p>Dado que la mayoría de las personas tienen factores de riesgo para Enfermedades crónicas y sus complicaciones:</p>
                                    <ul>
                                        <li>Enfermedad Coronaria</li>
                                        <li>Accidente Cerebro Vascular (ACV)</li>
                                        <li>Cáncer</li>
                                        <li>Diabetes</li>
                                        <li>Obesidad</li>
                                        <li>Depresión</li>
                                    </ul>
                                    <p>Prevemos realizar una evaluación individual de factores de riesgo de desarrollo de enfermedades y la elaboración de un plan de modificación de conductas. Además estaremos trabajando desde el espacio laboral para favorecer estos cambios, sorteando barreras para ir cambiando nuestro conocimiento sobre los hábitos saludables, cambiando actitudes y conductas, como así también acompañando desde la utilización de nuestros tiempos. Para ello dispondremos de una persona que los guiará con la organización de actividades, consultas médicas, realización de estudios médicos. 
                                        Toda la información personal recolectada se mantendrá en confidencialidad. La información individual será utilizada por profesionales de la salud y ayudará a establecer estrategias de promoción de salud en el espacio laboral, asumidos dentro del marco responsabilidad empresarial. Esta actividad no condicionará ninguna acción médica laboral previa ni futura. 
                                        Desde ya muy agradecido por su colaboración, que nos conducirá al éxito.</p>
                                    <p>Saludos cordiales.</p>

                                    <p>Coordinador de programa</p>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <button class="btn btn-lg btn-info btn-flat pull-left" id="encuesta">Comenzar Encuesta</button>
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
            $("#encuesta").click(function () {
                window.open('encuesta.php?id_user=<?php echo $id_user; ?>&name_user=<?php echo $name_user; ?>&id_seccion=<?php echo $id_seccion; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>','_self');
            });
        });
    </script>
    </body>
    </html>
    <?php
}
?>
