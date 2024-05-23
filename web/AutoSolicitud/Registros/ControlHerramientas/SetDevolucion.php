<?php 
	require_once '../../Lib/FuncionesComunes.php';
	require_once '../../Lib/db.php';
	VerificarSession();


		$data = data_submitted();
		print_object($data);
		$Id = $data->VarId;
		$FechaDevolucion = GetFechaActualParaMySql();
		$IdUsuarioCargaDevolucion = $_SESSION['gIdUsuario'];


		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($consulta);
		
		//$consulta = "INSERT INTO ControlHerramientas (IdUsuarioCargaRetiro,IdTecnico,Herramientas,TipoUso,FechaRetiro, FechaDevolucion, IdUsuarioCargaDevolucion) VALUES ($IdUsuarioCargaRetiro, $IdTecnico, '$Herramientas', $TipoUso, '$FechaRetiro', '0000-00-00', 0)";
		$consulta = "UPDATE ControlHerramientas SET FechaDevolucion='$FechaDevolucion', IdUsuarioCargaDevolucion=$IdUsuarioCargaDevolucion WHERE Id = $Id";

		echo "<br>$consulta";
		$dbh->Ejecutar($consulta);
	

		$dbh->Cerrar();
		$dbh = NULL;

		header('Location: ControlHerramientas.php');

	
?>