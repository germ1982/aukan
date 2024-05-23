<?php
// Leer datos de config
    $con = new baseDatos();
	$con->Conectarse();
	$config = array("sistemanombre"=>"", "sistemaversion"=>"", "loginredir"=>"", "horasevo"=>"", "minsesion"=>"");
	//$pedido = mysql_query ("SELECT * FROM config");
	$con->select("SELECT * FROM config");
	//$res = mysql_fetch_assoc($pedido)
	while ( $res = $con->registro() )
	{
		extract($res,EXTR_PREFIX_ALL,"cfg");
		$config[$cfg_tag] = $cfg_valor;
	}
	//$con->cerrar();
	define ("sesTime", $config['minsesion'] );
?>
