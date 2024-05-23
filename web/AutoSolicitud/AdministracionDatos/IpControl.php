<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="refresh" content="60" />
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
    <?php 
		include_once '../Lib/db.php';
		include_once '../Lib/FuncionesComunes.php';
		header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros
		VerificarSession();
    ?>
  </head>
  <body>
  	<h1>Control De Ips</h1>
  	
		<hr>
			<button type="button" name="SubRed73" value="73" onclick = "MostrarSubred(this.value)">Ver Subred 10.1.73.*</button>
			<button type="button" name="SubRed77" value="77" onclick = "MostrarSubred(this.value)">Ver Subred 10.1.77.*</button>
			<button type="button" name="SubRed169" value="169" onclick = "MostrarSubred(this.value)">Ver Subred 10.1.169.*</button>
			<button type="button" name="SubRed137" value="137" onclick = "MostrarSubred(this.value)">Ver Subred 10.1.137.*</button>
			<button type="button" name="SubRed176" value="176" onclick = "MostrarSubred(this.value)">Ver Subred 10.1.176.*</button>
			<button type="button" name="Volver" onclick = "location='MenuAdministracionDatos.php'" >Volver</button><br>
		<hr>
			<form name="FormOrden">
				Ordenar por:<br>
				<input type="radio" name="Orden" value="Id" onclick="MostrarOrden(this.value)">Ip<br>
		  		<input type="radio" name="Orden" value="Area" onclick="MostrarOrden(this.value)">Sector 
	  		</form>
  		<hr>
	<?php

		$Consulta = GenerarConsulta();	
		//AltaIps();
		CargarGrilla($Consulta);
	?>


  </body>

</html>

<?php


function AltaIps()
	{
		$IdUsuarioDeCarga = $_SESSION['gIdUsuario'];
		//echo($IdUsuarioDeCarga);
		$fecha = $mydate=getdate(date("U"));
		$fecha = "$fecha[mday]/$fecha[mon]/$fecha[year]";
		$fecha = GetFechaActual();
		//echo ($fecha);
		
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($consulta);


		for($i=1;$i<=255;$i++)
			{
				$ip = "10.1.176.$i";
					//echo "<br>$ip";
				$consulta = "INSERT INTO IpControl (Ip, IdArea, IdUsuario, Observacion, IdUsuarioEdicion, FechaEdicion) VALUES('$ip', 196, 2241, '', $IdUsuarioDeCarga, '$fecha')"; 
				echo "<br>$consulta";
				$dbh->Ejecutar($consulta);
			}		

		$dbh->Cerrar();
		$dbh = NULL;
	}

function CargarGrilla($Consulta)
	{

		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);

		echo "<table id='TablaRegistros' border='1'>";
		echo "<tr>"; 
		    echo "<th>Id</th>";
		    echo "<th>Ip</th>";
		    echo "<th>Area</th>"; 
		    echo "<th>Usuario</th> ";
		    echo "<th>Observacion</th>";
		    echo "<th>Ultima Edicion</th>"; 
		echo "</tr>";

		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					echo "<tr>"; 

						if ($result['Observacion']=='Libre')
							{
							    echo "<font color=green>"; 
							} 
						else 
							{
							    echo "<font color=red>"; 
							}
						echo "<td>".$result['Id']."</td>"; 
						echo "<td>".$result['Ip']."</td>"; 
						echo "<td>".$result['Area']."</td>"; 
						echo "<td>".$result['Usuario']."</td>"; 
						echo "<td>".$result['Observacion']."</td>"; 
						$Edicion = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $result['IdUsuarioEdicion']);
						$Edicion = $result['FechaEdicion']." por $Edicion"; 
						echo "<td>".$Edicion."</td>"; 
						echo "<td><input type=submit value='Editar' id=".$result['Id']." onclick='verBoton(this.id)'></td>"; 
					echo "</tr>";   
				}
			}

		echo "</table>";	
		$dbh->Cerrar();
		$dbh = NULL;

		MarcarSubRed();
	}

function GenerarConsulta()
	{
		$data = data_submitted();
		//print_object($data); 
		$SR = $data->VarSubred;
		$Orden = $data->VarOrden;
		if (!$SR=='')	
			{$SR="10.1.$SR";} 
		else
			{$SR="10.1.73";}


		if (!$Orden=='')	
			{$Orden="Order by $Orden";} 

		//echo "<br><br>$SR<br><br>";

		$Consulta = "Select Id, Ip, Areas.Area, Usuarios.Usuario, Observacion, IdUsuarioEdicion, FechaEdicion from IpControl inner join Areas on IpControl.IdArea = Areas.IdArea inner join Usuarios on IpControl.IdUsuario = Usuarios.IdUsuario Where Ip like '$SR%' $Orden";

		
		return $Consulta;
	}	

function MarcarSubRed()
	{
		$data = data_submitted();
		//print_object($data); 
		$SR = $data->VarSubred;
		if ($SR=='')	
			{$SR="73";};

		echo "<input type=hidden value='$SR' id='InputSubRed'>"; 
	}	

?>


<script type="text/javascript">	

	function getRadioButtonSelectedValue(ctrl)
			{
			    for(i=0;i<ctrl.length;i++)
			        if(ctrl[i].checked) return ctrl[i].value;
			}
		
	function MostrarOrden(Orden)
			{
				var SubRed = document.getElementById("InputSubRed").value;
				location.href = "IpControl.php?VarSubred="+SubRed+"&VarOrden="+Orden;
			}

	function MostrarSubred(clicked_value)
		{
			var Orden = getRadioButtonSelectedValue(document.FormOrden.Orden);
			if(Orden === undefined) Orden="Id";
			location.href = "IpControl.php?VarSubred="+clicked_value+"&VarOrden="+Orden;
		}

	function verBoton(clicked_id)
		{
			var aux = clicked_id;
			var SubRed = document.getElementById("InputSubRed").value;
			//alert(aux);
			location.href ="EditarIp.php?VarIp="+aux+"&VarSubred="+SubRed;
		}


</script>