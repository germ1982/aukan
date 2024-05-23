<?php
    $dirBase = "";
    $page_title = "Login";
    require_once $dirBase."comun/variables_globales.php";
    require_once $dirBase."comun/conectarse.php";
    require_once $dirBase."comun/libreria.php";        
    require_once $dirBase."comun/users.php";    
//    require_once $dirBase."comun/tplHeaderJavascript.php";
    require_once $dirBase."comun/clases/clase_usuarios_sistema.php";	
    $bd = new baseDatos();
    $bd->Conectarse();
    
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

                    <div class="form-group has-feedback">
                        <input type="user" class="form-control" placeholder="Mail" id="user">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Contraseña" id="password" >
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-offset-8 col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" id="login">Ingresar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                <!-- /.social-auth-links -->
                <a href="recuperarPassword.php">Olvide mi contraseña</a><br>
                <a href="generarPassword.php">No tiene cuenta? Generar Contraseña</a>
                

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script src="comun/lib/jquery/jquery.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="comun/lib/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="plugins/iCheck/icheck.min.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
                $('#login').click(function(){
                    $.ajax({
                        method: "POST",
                        url: "validarUsuario.php",
                        dataType: 'json',
                        data: {web:'1',usuario: $('#user').val(),password:$('#password').val()}//,fecha_desde:fecha_desde,fecha_hasta:fecha_hasta}
                    })
                    .done(function (event) {
                        if (event.result == 'ok'){
                            var idpaciente = event.idpaciente;
                            window.location.href = 'index.php?user='+idpaciente; 
                        }else{
                            if (event.result == "cambio"){
                                var idpaciente = event.idpaciente;
                                window.location.href = 'cambioPassword.php?user='+idpaciente;                                 
                            }else{
                                alert('Usuario o Contraseña Incorrecta');
                                window.location.reload();
                            }
                        } 
                    });
                });
            });
        </script>
    </body>
</html>
