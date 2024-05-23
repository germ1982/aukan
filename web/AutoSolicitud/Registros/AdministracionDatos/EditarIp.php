<!doctype html>
<html>
  <head>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/awesomplete.min.js"></script>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>
  <body onload="setcontroles()">
  		<form id="EditarIp" method="post" action="GuardarIp.php" onSubmit="return ValidarDatos();">
			<?php
				require_once '../Lib/FuncionesComunes.php';
				VerificarSession();
				$aux = GetIp();
				echo "<h1>Editar Ip $aux</h1>";	
			?>
			
			<hr>

			<datalist id="ListaUsuarios"> <?php GenerarListadoUsuarios() ?> </datalist>
			<datalist id="ListaSectores"> <?php GenerarListadoSectores() ?> </datalist>
			
			<table>
				<tr>
					<td>Usuario:</td>
					<td><input class="awesomplete" list="ListaUsuarios" id="InputListaUsuarios" placeholder="Usuario"/></td>		
				</tr>
				<tr>
					<td>Sector:</td>
					<td><input class="awesomplete" list="ListaSectores" id="InputListaSectores" placeholder="Sector"/></td>		
				</tr>
			</table>
			

			<br>Observacion:<br>
			
			<textarea id="InputObservacion" name="InputObservacion" rows=3 placeholder="Observacion"/></textarea>
			<br><br>
			
			
			<button type="submit" name="Guardar">Guardar</button>
			<button type="button" name="Cancelar" onclick = "DestinoCancelar()">Cancelar</button>
			<button type="button" name="LiberarIp" onclick = "DestinoLiberarIp()">Liberar Ip</button>
			<?php AbrirIp(); ?>
		</form>
  </body>

</html>


<script type="text/javascript">



   	document.querySelector('#InputListaUsuarios').addEventListener("awesomplete-selectcomplete", function(e){
		var Usuario = e.text.value;
		var IdSector = $('#ListaUsuarios [value="' + Usuario + '"]').data('idvinculo');
		var Sector = getValueDeListaGenerica("ListaSectores", IdSector);
	    document.getElementById("InputListaSectores").value = Sector;
	}, false);

   	document.querySelector('#InputListaSectores').addEventListener("awesomplete-selectcomplete", function(e){
		var Area = e.text.value;
		var IdArea = $('#ListaSectores [value="' + Area + '"]').data('idvalue');
		var ArrayUsuariosSector = CrearArrayConValuesDeListaVinculada("ListaUsuarios", IdArea);
		var txt = ListarArrayNumericamente(ArrayUsuariosSector);
		var num;
		do {
			Num = prompt('Seleccione el usuario de ' + Area + ': \n\n' + txt);
			if (Num == null) {return;}
		}
		while (!ValidarIntervaloNumerico (Num, ArrayUsuariosSector.length));
		document.getElementById("InputListaUsuarios").value = ArrayUsuariosSector[Num];
	}, false);


	
	function setcontroles()
		{		
				document.getElementById("InputListaUsuarios").value = document.getElementById("VarUsuario").value;
				document.getElementById("InputListaSectores").value = document.getElementById("VarArea").value;
				document.getElementById("InputObservacion").value = document.getElementById("VarObservacion").value;	
		}


	function DestinoCancelar()
		{
					//alert("algo	");
					location.href="IpControl.php";
		}

	function DestinoLiberarIp()
		{
			//alert ("aLGO");
			var Id = document.getElementById('VarId').value;
			var IdUsuarioEdicion = document.getElementById('VarIdUsuarioEdicion').value;
			var FechaEdicion = document.getElementById('VarFechaEdicion').value;
			
			var Aux = "GuardarIp.php?VarId="+Id+"&VarUsuario=2241&VarArea=196&VarObservacion=Libre&VarIdUsuarioEdicion="+IdUsuarioEdicion+"&VarFechaEdicion="+FechaEdicion;
			//alert (Aux);
			location.href =Aux;

		}

	function ValidarDatos()   	
   		{   		
   		var usuario = document.querySelector('#InputListaUsuarios').value;
	   		if (!ValidarDatoExistente("ListaUsuarios", usuario))
	   			{return false;}

   		var area = document.querySelector('#InputListaSectores').value;
	   		if (!ValidarDatoExistente("ListaSectores", area))
	   			{return false;}

   		PrepararVariables();	
		return true;}

	function PrepararVariables() 
		{
			var aux = document.getElementById("InputListaUsuarios").value;
			document.getElementById('VarUsuario').value = $('#ListaUsuarios [value="' + aux + '"]').data('idvalue');

			var aux = document.getElementById("InputListaSectores").value;
			document.getElementById('VarArea').value = $('#ListaSectores [value="' + aux + '"]').data('idvalue');

			document.getElementById("VarObservacion").value = document.getElementById("InputObservacion").value;
		}



</script>


<?php 
	require_once '../Lib/FuncionesComunes.php';
	require_once '../Lib/db.php';


	function GetIp()
		{
			$data = data_submitted();
			//print_object($data);
			$Id = $data->VarIp;
			$Ip = getDatoPorId('IpControl', 'Id', 'Ip', $Id);
			return $Ip;
		}

	function AbrirIp() 
		{
			require_once '../Lib/db.php';
			$data = data_submitted(); 
			$Id = $data->VarIp;
			echo "<input type='hidden' id='VarId' name='VarId' value='$Id'><br>";
			$consulta = "SELECT * from IpControl where Id = $Id";
			
			$AuxDato="";
			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($consulta);
			$result = $dbh->Registro();
			if (!$result) 
				{
					echo "<p>Error en la consulta.</p>"; 
				}
			else 
				{	
					$AuxDato = $result["IdUsuario"];
					$AuxDato = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $AuxDato);
					echo "<input type='hidden' id='VarUsuario' name='VarUsuario' value='$AuxDato'><br>";

					$AuxDato = $result["IdArea"];
					$AuxDato = getDatoPorId('Areas', 'IdArea', 'Area', $AuxDato);
					echo "<input type='hidden' id='VarArea' name='VarArea' value='$AuxDato'><br>";

					$AuxDato = $result["Observacion"];
					echo "<input type='hidden' id='VarObservacion' name='VarObservacion' value='$AuxDato'><br>";

					$AuxDato = $_SESSION['gIdUsuario'];
					echo "<input type='hidden' id='VarIdUsuarioEdicion' name='VarIdUsuarioEdicion' value='$AuxDato'><br>";

					$AuxDato = GetFechaActual();
					echo "<input type='hidden' id='VarFechaEdicion' name='VarFechaEdicion' value='$AuxDato'><br>";
				}	
			
			$dbh->Cerrar();
			$dbh = NULL;
		}

	function GenerarListadoUsuarios() 
		{
			$IdArea = getId('Areas', 'IdArea', 'Area','Baja');
			$Consulta = "Select * from Usuarios Where not IdArea = $IdArea Order by Usuario";	
			GenerarListadoGenerico($Consulta, "IdUsuario", "Usuario", "IdArea");	
		}

	function GenerarListadoSectores() 
		{
			$Consulta = "Select * from Areas WHERE NOT Referencia = 'Baja' order by Area";	
			GenerarListadoGenerico($Consulta, "IdArea", "Area", "IdArea");	
		}
	
?>