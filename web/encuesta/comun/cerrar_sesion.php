<?
    require_once "conectarse.php";
	$bd = new baseDatos();
	$bd->Conectarse();
    $miIP = "";
	$miUser = $_POST['userName'];
//	echo $miUser;
	$bd->select("UPDATE usuarios_sistema SET sesion='0',ip='$miIP',ultima_accion='$datetimeAccion' WHERE username='$miUser'");
	//$bd->cerrar();
	session_destroy();
?>