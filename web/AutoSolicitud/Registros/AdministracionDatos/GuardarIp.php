<?php 
	require_once '../Lib/FuncionesComunes.php';
	require_once '../Lib/db.php';



		$data = data_submitted();
		print_object($data);
		$Id = $data->VarId;
		$IdUsuario = $data->VarUsuario;
		$IdArea = $data->VarArea;
		$Observacion = $data->VarObservacion;
		$IdUsuarioEdicion = $data->VarIdUsuarioEdicion;
		$FechaEdicion = $data->VarFechaEdicion;

		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($consulta);

		$consulta = "UPDATE IpControl SET IdArea=$IdArea, IdUsuario=$IdUsuario, Observacion='$Observacion', IdUsuarioEdicion=$IdUsuarioEdicion, FechaEdicion='$FechaEdicion' WHERE Id = $Id";

		echo "<br>$consulta";
		$dbh->Ejecutar($consulta);
	

		$dbh->Cerrar();
		$dbh = NULL;

		header('Location: IpControl.php');

	
?>