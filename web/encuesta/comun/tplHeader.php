<?php
    require_once $dirBase."comun/conectarse.php";
	require_once $dirBase."comun/config.php";
	if ($zona != 2)
	require_once $dirBase."comun/users.php";	
    require_once $dirBase."comun/variables_globales.php";	
	if ($zona != 2)
	{
	   if (!permisoPagina ($rolesPermitidos) ) 
	   {
	       accesoRestringido();
	   }
    }	
	require_once $dirBase."comun/libreria.php";	
?>