<?php 
	require_once '../../Lib/FuncionesComunes.php';
	require_once '../../Lib/db.php';



		$data = data_submitted();
		print_object($data);
		$IdUsuarioCargaRetiro = $data->VarIdUsuarioLogueado;
		$IdTecnico = $data->VarIdTecnico;
		$Herramientas = $data->VarHerramientas;
		$TipoUso = $data->VarTipoUso;
		$FechaRetiro = $data->VarFechaRetiro;

		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($consulta);
		
		$consulta = "INSERT INTO ControlHerramientas (IdUsuarioCargaRetiro,IdTecnico,Herramientas,TipoUso,FechaRetiro, FechaDevolucion, IdUsuarioCargaDevolucion) VALUES ($IdUsuarioCargaRetiro, $IdTecnico, '$Herramientas', $TipoUso, '$FechaRetiro', '0000-00-00', 0)";
		//$consulta = "UPDATE IpControl SET IdArea=$IdArea, IdUsuario=$IdUsuario, Observacion='$Observacion', IdUsuarioEdicion=$IdUsuarioEdicion, FechaEdicion='$FechaEdicion' WHERE Id = $Id";

		echo "<br>$consulta";
		$dbh->Ejecutar($consulta);
	

		$dbh->Cerrar();
		$dbh = NULL;

		header('Location: ControlHerramientas.php');

	
?>