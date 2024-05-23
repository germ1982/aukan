<?php
	session_start();	
	if ($_SESSION['userName'] != '' && $_SESSION['userName'] != 'userName')
	{
		//echo time() - $_SESSION['LAST_ACTIVITY'];
	    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 56000)) 
	    { 
	    	// last request was more than 30 minutes ago 
	    	session_unset(); 
	    	// unset $_SESSION variable for the run-time .
	    	session_destroy(); 
	    	// destroy session data in storage 
	    }
	    else 
	        $_SESSION['LAST_ACTIVITY'] = time(); 
	}
    	// update last activity time stamp
	/*session_start();
	session_name($_SERVER['REMOTE_ADDR']);
	ini_set("session.gc_maxlifetime", "1800000000");*/ 

	// Crear la cookie para el usuario
	function iniciaSesion ($arrayUser)
	{
		$_SESSION['userName'] = $arrayUser['userName'];
		$_SESSION['userIP'] = $arrayUser['userIP'];
		$_SESSION['userIDProf'] = $arrayUser['userIDProf'];
		$_SESSION['userPermisos'] = $arrayUser['userPermisos'];
		$_SESSION['userEmail'] = $arrayUser['userEmail'];
		$_SESSION['userUltimoIng'] = $arrayUser['userUltimoIng'];
		$_SESSION['userRol'] = $arrayUser['userRol'];
		$_SESSION['userInicioSesion'] = time();
		$_SESSION['userNameProfesional'] = $arrayUser['userNameProfesional'];
		$_SESSION['LAST_ACTIVITY'] = time();
		$datetimeIngreso = date("Y-m-d H:i:s");
		$miIP = $arrayUser['userIP'];
		$miUser = $arrayUser['userName'];
		$pedido = mysql_query ("UPDATE usuarios_sistema SET sesion='1', ip='$miIP', ultimo_ingreso='$datetimeIngreso' WHERE username='$miUser'");
		session_write_close();
	}

	// Esto va a verificar que la sesi�n est� iniciada correctamente
	function verificarSesion()
	{
		if (array_key_exists("userName",$_SESSION))
		{
			define ("userName", $_SESSION['userName']);	
			define ("userIP",$_SESSION['userIP']);
			define ("userIDProf",$_SESSION['userIDProf']);
			define ("userPermisos",$_SESSION['userPermisos']);
			define ("userEmail",$_SESSION['userEmail']);
			define ("userUltimoIng",$_SESSION['userUltimoIng']);
			define ("userRol",$_SESSION['userRol']);
			define ("userInicioSesion",$_SESSION['userInicioSesion']);
			$user = userName;
			$duracionSesion = intval ( ( time() - userInicioSesion ) / 60 );
			$verificaIPq = mysql_query ("SELECT * FROM usuarios_sistema WHERE username='$user'");
			$verificaIP = mysql_fetch_array ($verificaIPq);
//			return true;
			switch (userRol)
			{
				case 1:
					define ("userSuperAdmin",true);
					break;

				case 2:
					define ("userAdmin",true);
					break;

				case 3:
					define ("userAdminTablas",true);
					break;

				case 4:
					define ("userAdminUsuarios",true);
					break;

				case 5:
					define ("userAdminEnfermeria",true);
					break;

				case 6:
					define ("userAdminSala",true);
					break;

				case 7:
					define ("userAdminTerapia",true);
					break;

				case 8:
					define ("userAdminQuirofano",true);
					break;

				case 9:
					define ("userProfesional",true);
					break;

				case 10:
					define ("userEnfermero",true);
					break;

				case 11:
					define ("userAdmisionista",true);
					break;

				case 12:
					define ("userImagenes",true);
					break;

				case 13:
					define ("userLaboratorio",true);
					break;
				case 14:
					define ("userCostos",true);
					break;
				case 15:
				    define ("userOtrosEstudios",true);
					break; 	
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	// Desloguea del sistema, borra la sesion, y almacena en la base la �ltima fecha en la que estuvo
	// el usuario que se est� desconectando. Probablemente tambi�n sea buena idea guardar la �ltima IP.
	function cierraSesion ()
	{
		//session_unset();
		//session_write_close();
		$miIP = "";
		$miUser = $_SESSION['userName'];
		$pedido = mysql_query ("UPDATE usuarios_sistema SET sesion='0',ip='$miIP',ultima_accion='$datetimeAccion' WHERE username='$miUser'");
		session_destroy();
	}

	// Cada vez que se haga alg�n cambio, hay que invocar esto
	function almacenaLog ($stringLogfile, $stringDirBase, $stringUsuario, $stringAccion)
	{
		$pathLog = $stringDirBase."logs/";
		$datosLog = date("Y/m/d,H:i:s").",".$stringUsuario.",".$stringAccion."\r\n";
		$datetimeAccion = date("Y-m-d H:i:s");
		$pedido = mysql_query ("UPDATE usuarios_sistema SET ultima_accion='$datetimeAccion' WHERE username='$stringUsuario'");
		$archivoLog = fopen($pathLog.$stringLogfile, "a");
		//echo $datosLog;
		fwrite($archivoLog, $datosLog);
		fclose($archivoLog);
	}

	// Si la sesi�n muere por inactividad, con esto la refrescamos.
	// La diferencia con re-loguearse es que le podemos pasar por par�metro una URL, entonces
	// al refrescar la sesi�n el usuario vuelve a donde estaba con anterioridad.
	function refrescaSesion ($pathReload)
	{
		
	}

	// Verifica si la p�gina actual est� dentro de los permisos del usuario activo.
	function permisoPagina ($arrayRoles)
	{
		$resp = false;
		if (in_array("0",$arrayRoles))
		{
			$resp = true;
		}
		else
		{
			if (verificarSesion())
			{
				if (in_array(userRol,$arrayRoles)) 
				    $resp=true;  
				else 
				    $resp=false; 
			}
		}
		return $resp;
	}

	function accesoRestringido ()
	{
		echo "
		<html>
		<head>
		<title>".$config['sistemanombre']." v".$config['sistemaversion']." || ".$sistemaPagina."
		</title>
		<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		
		</head>	
		<body>
			<div style='width:100%;'>
				<div class='divsContenido' style='vertical-align:middle; text-align:center; ' align='center' >
					<font size='4' color='#CC0000'>Acceso Restringido</font><br />
					Lo siento, pero no tiene acceso a esta p&aacute;gina.<br />
					<br />
					<font size='1'>Presione <a href='http://".$_SERVER['SERVER_NAME'] ."/cnc/login.php' title='Saltear espera'>aqu&iacute;</a>.</font>
				</div>
			</div>
			
		</body>";
		die();
	}

	function loguearUsuario($loginUsuario, $loginPassword)
	{
		//$error = 0;
		//$loginUsuario = "admin";
		//$loginPassword = "12345";
	
		if (get_magic_quotes_gpc())
		{
			$loginUsuario = stripslashes($loginUsuario);
		}
		if (!is_numeric($loginUsuario))
		{
			$loginUsuario = mysql_escape_string($loginUsuario);
		}
	
		if (get_magic_quotes_gpc())
		{
			$loginPassword = stripslashes($loginPassword);
		}
		if (!is_numeric($loginPassword))
		{
			$loginPassword = mysql_escape_string($loginPassword);
		}

		// Usuario existe o me est�n boludeando?
		$pedido = mysql_query ("SELECT usuarios_sistema.*,profesionales.nombre FROM usuarios_sistema LEFT JOIN profesionales 
		                       ON (profesionales.idprofesional=idprof) WHERE usuarios_sistema.username='$loginUsuario'");
	
		if ($userDataDB = mysql_fetch_array($pedido))
		{
			// Ok, el usuario existe...
			if ( $userDataDB['sesion'] == 1 ) {
				if ( $userDataDB['ip'] <> $_SERVER['REMOTE_ADDR'] )
				{
					echo ("Su sesi&oacute;n anterior no se cerr&oacute; correctamente, o se ha conectado desde otra IP");
					echo ("Cerrando su sesi&oacute;n correctamente");
					$pedido = mysql_query ("UPDATE usuarios_sistema SET sesion='0',ip='$miIP' WHERE username='$loginUsuario'");
					echo "Redireccionando...<meta http-equiv='refresh' content='2;url=login.php' />";
					die;
				}
			}
			$userPasswordDB = $userDataDB['password'];
			if ($userPasswordDB == sha1($loginPassword))
			{
				// Y la clave la puso bien!
				$error = 0;

				$arrayUser = array (
					"userName" => $userDataDB['username'],
					"userIP" => $_SERVER['REMOTE_ADDR'],
					"userIDProf" => $userDataDB['idprof'],
					"userPermisos" => $userDataDB['permisos'],
					"userEmail" => $userDataDB['email'],
					"userUltimoIng" => $userDataDB['ultimo_ingreso'],
					"userRol" => $userDataDB['rol'],
				    'userNameProfesional'=>$userDataDB['nombre']);
				
				iniciaSesion ($arrayUser);
				if (verificarSesion()) {define ("userValid",true);}

			}
			else
			{
				$error = 2;
			}
		}
		else
		{
			$error = 1;
		}
	
		if ($error <> 0) {
			// Usuario o contrase�a incorrectos. O sea: cagaste viteh.
			define ("userValid",false);
		}
		return $error;
	}
	function permisoPaginaNew($arrayRoles,$idprofesional)
	{
	    $resp = false;
	    $bd = new baseDatos();
	    $bd->Conectarse();
	    $bd->select("SELECT rol FROM profesionales_roles WHERE idprofesional=$idprofesional");
     	    $i = 0;
	    while ($arreglo = $bd->registro())
	    { 
    		if (in_array($arreglo['rol'],$arrayRoles)!= false) 
        	    return true;        	
    	     }
	     return $resp;
	}
	function passwordcheck($user, $pass, $chars=6) {
        $word_file = '../admin/password/words.txt';
        $lc_pass = strtolower($pass);

        // also check password with numbers or punctuation subbed for letters
        $denum_pass = strtr($lc_pass,'5301!4','seoila');
        $lc_user = strtolower($user);

        // the password must be at least six characters
        if (strlen($pass) < $chars) {
            return 'La contrase�a es muy corta.';
        }

        // the password can't be the username (or reversed username) 
        if (($lc_pass == $lc_user) || ($lc_pass == strrev($lc_user)) ||
            ($denum_pass == $lc_user) || ($denum_pass == strrev($lc_user))) {
            return 'La contrase�a est� basada en el nombre de usuario.';
        }

        // count how many lowercase, uppercase, and digits are in the password 
        $uc = 0; $lc = 0; $num = 0; $other = 0;
        for ($i = 0, $j = strlen($pass); $i < $j; $i++) {
            $c = substr($pass,$i,1);
            if (preg_match('/^[[:upper:]]$/',$c)) {
                $uc++;
            } elseif (preg_match('/^[[:lower:]]$/',$c)) {
                $lc++;
            } elseif (preg_match('/^[[:digit:]]$/',$c)) {
                $num++;
            } else {
                $other++;
            }
        }

        // the password must have more than two characters of at least 
        // two different kinds 
        $max = $j - 2;
        if ($uc > $max) {
            return "La contrase�a tiene demasiados caracteres en may�sculas.";
        }
        if ($lc > $max) {
            return "La contrase�a tiene demasiados caracteres en mmin�sculas.";
        }
        if ($num > $max) {
            return "La contrase�a tiene demasiados n�meros.";
        }
        if ($other > $max) {
            return "La contrase�a tiene demasiados caracteres especiales.";
        }

        // the password must not contain a dictionary word
        if (is_readable($word_file)) {
            if ($fh = fopen($word_file,'r')) {
                $found = false;
                while (! ($found || feof($fh))) {
                    $word = preg_quote(trim(strtolower(fgets($fh,1024))),'/');
                    if (preg_match("/$word/",$lc_pass) ||
                        preg_match("/$word/",$denum_pass)) {
                        $found = true;
                    }
                }
                fclose($fh);
                if ($found) {
                    return 'La contrase�a est� basada en una palabra muy com�n.';
                }
            }
        }

        return false;
    }
?>
