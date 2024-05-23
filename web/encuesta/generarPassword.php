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
                <label>Complete y envíe el siguiente formulario para generar una contraseña </label>                 
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Nombre y Apellido" id="nombre_apellido" >
                        <span class="glyphicon glyphicons-user form-control-feedback"></span>
                    </div>                
                    <div class="form-group has-feedback">
                        <input type="number" class="form-control" placeholder="Dni" id="dni">
                        <span class="glyphicon glyphicons-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Fecha de Nacimiento" id="fecha_nacimiento" onkeyup="mascara(this, '/', patron, true)">
                        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                    </div>       
                    <div class="form-group has-img">
                        <select id="id_tipo_encuesta">
                            <option value="-1">Seleccione la empresa a la que pertenece</option>
                            <?php
                                $bd = new baseDatos();
                                $bd->Conectarse();
                                $bd->select("SELECT * FROM financiacion WHERE empresa = 1");
                                while ($empresa = $bd->registro()){
                            ?>  
                            <option value="<?php echo $empresa['idfinanciacion']; ?>"><?php echo $empresa['nombre']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                    </div>       
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Mail" id="mail" >
                        <span class="glyphicon glyphicon-mail form-control-feedback"></span>
                    </div>       
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary" id="recuperar_pass">Generar Contraseña</button>                        
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
                $('#recuperar_pass').click(function(){
                    var nombre = $('#nombre_apellido').val();
                    var dni = $('#dni').val();
                    var mail = $('#mail').val();
                    var fecha_nacimiento = $('#fecha_nacimiento').val();
                    var id_tipo_encuesta = $('#id_tipo_encuesta').val();
                    if (nombre === "" || dni === "" || fecha_nacimiento === "" || mail === "" || id_tipo_encuesta === "-1"){
                        alert('Debe completar todos los campos para generar su contraseña');
                        $('#nombre_apellido').focus();
                    }else{
                        $.ajax({
                            method: "POST",
                            url: "enviarPassword.php",
                            dataType: 'json',
                            data: {web:'1',nombre_apellido: nombre,dni:dni,fecha_nacimiento:fecha_nacimiento,mail:mail,id_tipo_encuesta:id_tipo_encuesta,generar:1}//,fecha_desde:fecha_desde,fecha_hasta:fecha_hasta}
                        })
                        .done(function (event) {
                            if (event.result == 'ok'){
                                alert('Se ha enviado un mail a su casilla de correo '+event.mail+'\ncon la nueva contraseña');
                                window.location.href = 'login.php'; 
                            }else{
                                alert('No se ha podido generar el usuario en este momento');
                                window.location.href = 'login.php'; 
                            } 
                        });
                    }
                });
            });
        </script>
    </body>
</html>
