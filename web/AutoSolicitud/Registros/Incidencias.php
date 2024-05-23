<!doctype html>
<html>
  <head>
  	<meta http-equiv="refresh" content="30" />
	<link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/> 
	<!-- <link href="../../template/vendor/bootsrap/bootstrap.min.css" rel="stylesheet" type="text/css"/> -->
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>
	<body onload="SetControles()">
		<!-- <form id="FormRegistrosPendientes" method="post" action="RegistroEditar.php" onSubmit="return ValidarDatos();">-->
		<form id="FormRegistrosPendientes" method="post" action="Incidencia.php" onSubmit="return ValidarDatos();">
			<?php CargarDatonIniciales();?> 
			<hr>
			<?php CargarGrilla();?> 
			<hr>
			<input type="hidden" id="idregistro" name="idregistro">

		</form>
  </body>

</html>


<script type="text/javascript">

	function SetControles()
		{
			//var aux = document.getElementById('VarTexto').value;
			//alert(aux);
			//document.getElementById("TextoHtml").innerHTML = aux;

		}

	function verBoton(clicked_id)
		{
			var aux = clicked_id;
			//alert (clicked_id);
			document.getElementById('idregistro').value = aux;
		}

</script>


<?php 

	function CargarDatonIniciales()
		{
			require_once '../../config/db.php';
			include_once('../Lib/FuncionesComunes.php');
			$data = data_submitted();
			//print_object($data);

			$UserId = $data->idusuario;

			echo("<h1>Incidencias Pendientes</h1>");
			
			echo "<input type='hidden' id='idusuario' name='idusuario' value='$UserId'>";
		}

	function CargarGrilla()
		{
			require_once '../../config/db.php';
			include_once('../Lib/FuncionesComunes.php');

		    $Consulta = "SELECT * FROM sds_reg_registro WHERE registro_abierto = 1 and incidencia_relacionada = 1 ORDER BY idregistro";
		    //echo $Consulta;

			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($Consulta);	

			if (!$result) 
				{
					echo "<p>Error en la consulta.</p>"; 
				}
			else 
				{
					$ban=1;
					while ($result = $dbh->Registro())
					{
						if($ban==1)
							{
								$Letra = "<FONT SIZE=3>";
								echo "<table border='1'>"; 
								echo "<tr>"; 
									echo "<th>$Letra Id</th>";
									echo "<th>$Letra Equipo</th>"; 
									echo "<th>$Letra Fecha</th>";
									echo "<th>$Letra Sector</th> ";
									echo "<th>$Letra Usuario</th>";
									echo "<th>$Letra Derivador</th>"; 
									echo "<th>$Letra Movimientos</th> ";
								echo "</tr>";
							}
						$ban=0;
						
						echo "<tr>"; 
							echo "<td>".$result['idregistro']."</td>"; 
							echo "<td>".$result['equipo_detalle']."</td>"; 
							$date = date_create($result['fecha_hora']);
							$fecha=date_format($date, 'd/m/Y');
							echo "<td>$fecha</td>"; 

							$Sector = getDatoPorId("mds_org_dispositivo", "iddispositivo", "descripcion", $result['iddispositivo']);
							echo "<td>$Sector</td>";

							//$Solicitante = getDatoPorId("mds_org_contacto", "idcontacto", "user", $result['usuario_solicitante']);
							$Solicitante = GetContactoValue($result['usuario_solicitante']);
							echo "<td>$Solicitante</td>";

							$Derivador = getDatoPorId("mds_seg_usuario", "idusuario", "user", $result['usuario_derivacion']);
							echo "<td>$Derivador</td>";

							echo "<td>";MostrarMovimientos($result['idregistro'],0,1);"</td>"; 

							echo "<td><input type=submit value='Asistir' id=".$result['idregistro']." onclick='verBoton(this.id)'></td>"; //cambiar por un boton comun y pasar el this id y el iduser
						echo "</tr>";   
					}
				}	

			echo "</table>";
			$dbh->Cerrar();
			$dbh = NULL;

			
			
		}

	
?>