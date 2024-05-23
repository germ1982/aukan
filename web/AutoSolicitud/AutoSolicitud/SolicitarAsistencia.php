<!doctype html>
<html>
  <head>
    <link href="../Css/awesompleteOriginal.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/AutoSolicitud.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/awesompleteO.min.js"></script>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>

  		
	<?php 
		require_once '../Lib/FuncionesComunes.php';
		require_once '../Lib/db.php';
	?>
  <body onload="setcontroles()">


		<div id='DivA'>

					<h1>Solicitar Asitencia Informatica</h1>
					<hr>

					<datalist id="ListaUsuarios"> <?php GenerarListadoUsuarios(); ?> </datalist>
					<datalist id="ListaSectores"> <?php GenerarListadoSectores(); ?> </datalist>
					
					<table>
						<tr>
							<td>Usuario:</td>
							<td><input class="awesomplete" list="ListaUsuarios" id="InputListaUsuarios" placeholder="Usuario" style="width:300px;"/></td>		
							<td><pre>                        Fecha:</pre></td>
							<td><input type="text" id="FechaActual" name="FechaActual" readonly="true"></td>	
						</tr>
						<tr>
							<td>Sector:</td>
							<td><input class="awesomplete" list="ListaSectores" id="InputListaSectores" placeholder="Sector" style="width:300px;"/></td>	
							<td><pre>                        Hora:</pre></td>
							<td><input type="text" id="HoraActual" name="HoraActual" readonly="true"></td>			
						</tr>
					</table>
					
					<br>Describir Problema:<br>
					
					<textarea id="InputProblema" name="InputProblema" rows=5 placeholder="Describir Problema" style="width:760px;"/></textarea>
					<br>

					<br>Telefono o Interno: <input id="InputTelefono" name="InputTelefono" placeholder="Telefono"/>(Opcional)

					<hr>
					<br>
				
				<form id="FormIdRegistroEditar" method="post" action="GuardarRegistroAutoSolicitado.php" onSubmit="return ValidarDatos();">
					<?php 
						PrepararAltaRegistro();
					?>
					<button type="submit" name="Solicitar">Solicitar</button>
				</form>

		</div>
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

   	function ValidarDatos()
	   	{

	   		
	   		var usuario = document.querySelector('#InputListaUsuarios').value;
		   		if (!ValidarDatoExistente("ListaUsuarios", usuario))
		   			{return false;}

	   		var area = document.querySelector('#InputListaSectores').value;
		   		if (!ValidarDatoExistente("ListaSectores", area))
		   			{return false;}

	   		var Problema = document.getElementById("InputProblema").value
		   		if (Problema=="")
		   			{
		   				alert("Detalle el Problema");
		   				return false;
		   			}



	   		PrepararVariables();	
			return true;
		}

	function PrepararVariables() 
		{

			document.getElementById('VarFecha').value = document.getElementById("FechaActual").value;

			document.getElementById('VarHora').value = document.getElementById("HoraActual").value;

			var aux = document.getElementById("InputListaSectores").value;
			document.getElementById('VarIdArea').value = $('#ListaSectores [value="' + aux + '"]').data('idvalue');

			var aux = document.getElementById("InputListaUsuarios").value;
			document.getElementById('VarIdUsuario').value = $('#ListaUsuarios [value="' + aux + '"]').data('idvalue');

			document.getElementById('VarProblema').value = document.getElementById("InputProblema").value;

			document.getElementById('VarTelefono').value = document.getElementById("InputTelefono").value;

			document.getElementById('VarTecnicos').value = '';
		}

	function setcontroles()
		{
			document.getElementById("FechaActual").value = getFechaActual();
			document.getElementById("HoraActual").value = getHoraActual();	
		}

	function DestinoCancelar()
		{

					//location.href=".php";
					location.href="javascript:window.close();";

		}

</script>


<?php 
		function PrepararAltaRegistro()
			{
				echo "<input type='hidden' id='VarFecha' name='VarFecha'>";
				echo "<input type='hidden' id='VarHora' name='VarHora'>";
				echo "<input type='hidden' id='VarIdArea' name='VarIdArea'>";
				echo "<input type='hidden' id='VarIdUsuario' name='VarIdUsuario'>";
				echo "<input type='hidden' id='VarProblema' name='VarProblema'>";
				echo "<input type='hidden' id='VarTelefono' name='VarTelefono'>";
				echo "<input type='hidden' id='VarTecnicos' name='VarTecnicos'>";
				echo "<input type='hidden' id='VarIdTipoDeProblema' name='VarIdTipoDeProblema' value='0'>";
				echo "<input type='hidden' id='VarIdElementoProblematico' name='VarIdElementoProblematico' value='0'>";
				echo "<input type='hidden' id='VarTrabajo' name='VarTrabajo' value=''>";
				echo "<input type='hidden' id='VarAutorizado' name='VarAutorizado' value='0'>";
				echo "<input type='hidden' id='VarRegistroAbierto' name='VarRegistroAbierto' value='1'>";	
				$aux = getRealIP();
				echo "<input type='hidden' id='VarIpOrigen' name='VarIpOrigen' value='$aux'>";
			}

		function GenerarListadoUsuarios() 
			{
				$IdArea = getId('Areas', 'IdArea', 'Area','Baja');
				$Consulta = "Select * from Usuarios Where not IdArea = $IdArea order by Usuario";	
				echo"<br>$Consulta<br>";
				GenerarListadoGenerico($Consulta, "IdUsuario", "Usuario", "IdArea");	
			}

		function GenerarListadoSectores() 
			{
				$Consulta = "Select * from Areas WHERE not Referencia = 'Baja' order by Area";	
				GenerarListadoGenerico($Consulta, "IdArea", "Area", "IdArea");	
			}
?>
