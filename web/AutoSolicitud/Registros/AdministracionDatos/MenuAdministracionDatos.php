<?php
require_once '../Lib/FuncionesComunes.php';
	
VerificarSession();
		
?>

<!doctype html>
	<head>
		<link href="../Css/Registros.css" rel="stylesheet" type="text/css"
	</head>
	
	<body>
		
			<div id="Contenedor">
				<h1>Menu Datos</h1>
				<hr>

					Usando: 
					<?php 
						$Usuario = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $_SESSION['gIdUsuario']);
						echo($Usuario);
					?>
				<br>
				<div id="Caja">
					<div id="Botonera">
							<br><button type="button" onclick = "ValidarIngresoPermisos()">Permisos de Aplicaciones</button><br>
							<br><button type="button" onclick = "location='IpControl.php'">Administrar Ips</button><br>
							<br><button type="button" onclick = "location='AdministrarAreas.php'">Administrar Areas</button><br>
							<br><button type="button" onclick = "location='AdministrarUsuarios.php'">Administrar Usuarios</button><br>
							<br><button type="button" onclick = "location='../Registros/MenuRegistros.php'">Volver</button><br><br>
							
					</div>	
				</div>
				<hr>		
			</div>
	</body>
</html>


<script type="text/javascript">
	function ValidarIngresoPermisos()
		{
			var RutaDestino = '../AdministracionDatos/AdministrarPermisos.php';
			var RutaVuelta = '../AdministracionDatos/MenuAdministracionDatos.php';
			var Aplicacion = 'Administrar Permisos';
			aux = '../Lib/ValidarIngresos.php?VarAplicacion='+ Aplicacion + '&VarRutaDestino=' + RutaDestino + '&VarRutaVuelta='+RutaVuelta;
			location.href=aux;	
		}
</script>