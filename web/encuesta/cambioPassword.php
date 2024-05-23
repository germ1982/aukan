<?php
$dirBase = "";
$page_title = "Login";
require_once $dirBase . "comun/variables_globales.php";
require_once $dirBase . "comun/conectarse.php";
require_once $dirBase . "comun/libreria.php";
require_once $dirBase . "comun/users.php";
//    require_once $dirBase."comun/tplHeaderJavascript.php";
require_once $dirBase . "comun/clases/clase_usuarios_sistema.php";
require_once $dirBase . "comun/clases/clase_pacientes.php";
$idpaciente = $_GET['user'];
$paciente = new clase_pacientes($idpaciente);
$passwd = $paciente->password();
$bd = new baseDatos();
$bd->Conectarse();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sistemas de Encuestas - Portal Web</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="comun/lib/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="comun/lib/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="login-box-body">
                <div class="login-logo">
                    <img src="../imagenes/logo_sistemadeencuestas.jpeg" width="20%"/>
                </div>

                <label>Por favor complete los datos y modifique su contraseña, luego podrá ingresar al sistema</label>       
                <div class="form-group has-feedback">
                    <input type="number" class="form-control" placeholder="Dni" id="dni">
                    <span class="glyphicon glyphicons-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Fecha de Nacimiento" id="fecha_nacimiento" onkeyup="mascara(this, '/', patron, true)">
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>       
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Nueva Contraseña" id="password_new">
                    <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Repita Nueva Contraseña" id="password_new_repeat">
                    <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary" id="modificar_pass">Modificar Datos</button>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.social-auth-links -->

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script src="comun/lib/jquery/jquery.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="comun/lib/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="comun/funcionesVarias.js"></script> 
        <script src="plugins/iCheck/icheck.min.js"></script>
        <script>
                        $(function () {
                            $('#modificar_pass').click(function () {
                                var dni = $('#dni').val();
                                var fecha_nacimiento = $('#fecha_nacimiento').val();
                                var password = $('#password_new').val();
                                var password_ant = $('#password_ant').val();
                                var password_repeat = $('#password_new_repeat').val();
                                if (password != password_repeat) {
                                    alert('Las contraseñas no coinciden');
                                    $('#password_new').focus();
                                } else {
                                    if (dni == "" || fecha_nacimiento == "" || password == ""){
                                        alert('Debe completar todos los datos para continuar');
                                    }else{
                                        $.ajax({
                                            method: "POST",
                                            url: "updatePassword.php",
                                            dataType: 'json',
                                            data: {web: '1', password: password, user:<?php echo $idpaciente; ?>,dni:dni,fecha_nacimiento:fecha_nacimiento}//,fecha_desde:fecha_desde,fecha_hasta:fecha_hasta}
                                        })
                                                .done(function (event) {
                                                    if (event.result == 'ok') {
                                                        alert('Los datos se han modificado correctamente, vuelva a ingresar con su nueva contraseña');
                                                        window.location.href = 'login.php';
                                                    } else {
                                                        if (event.result == 'igual') {
                                                            alert('La nueva contraseña no puede ser igual a la anterior');
                                                            $('#password_new').focus();
                                                        } else {
                                                            alert('No se han podido modificar los datos');
                                                            window.location.reload();
                                                        }
                                                    }
                                                });
                                    }
                                }
                            });
                        });
        </script>
    </body>
</html>
