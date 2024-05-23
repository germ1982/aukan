<!doctype html>
	<head>
		<link href="../Css/Registros.css" rel="stylesheet" type="text/css"
	</head>
	
	<body>
		
			<div id="Contenedor">
				<h1>Entrega de Equipos</h1>
				<hr>
				Usando: 
				<?php 
					require_once '../Lib/FuncionesComunes.php';
					VerificarSession();
					$Usuario = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $_SESSION['gIdUsuario']);
					echo($Usuario);
				?>
				<br>
				
				<div id="Caja">
					<div id="Botonera">
							Movimientos
							<button type="button" onclick = "location='NuevoExpediente.php'">Nuevo Expediente</button><br>
							<button type="button" onclick = "location='NuevaOrden.php'">Nueva Orden</button><br>
							<button type="button" name="Volver" onclick = "location='RealizarEntrega.php?VarOrden=Insumo'" >Realizar Entrega</button>
							<button type="button" onclick = "location='EquiposPendientesAEntregar.php'">Entregas Pendientes</button><br>
							<button type="button" onclick = "location='EntregasRealizadas.php'">Entregas Realizadas</button><br>
					</div>	
				</div>
				<br>

				<div id="Caja">
					<div id="Botonera">
							Gestion de Datos
							<button type="button" onclick = "location='AdministrarInsumos.php'">Gestionar Articulos</button><br>
							<button type="button" onclick = "location='AdministrarInsumosTipos.php'">Gestionar Insumos</button><br>
							<button type="button" onclick = "location='AdministrarMarcas.php'">Gestionar Marcas</button><br>
							<button type="button" onclick = "location='AdministrarProvedores.php'">Gestionar Provedores</button><br>

					</div>	
				</div>
				<br>
				<div id="Caja">
					<div id="Botonera">
							<button type="button" onclick = "location='../Registros/MenuRegistros.php'">Volver</button><br>
					</div>	
				</div>
				<hr>		
			</div>
	</body>
</html>

