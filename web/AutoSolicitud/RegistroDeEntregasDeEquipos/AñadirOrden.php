<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/jquery-2.0.3.min.js" type="text/javascript"></script>   
    <script src="../Lib/FuncionesComunes.js" type="text/javascript"></script>   
    <script src="../Lib/awesomplete.min.js"></script>
  </head>
  <body>
  		
			<?php
				require_once '../Lib/FuncionesComunes.php';
				VerificarSession();
			?>

			<datalist id="ListaInsumos"> <?php GenerarListadoGenerico("Select * From VistaInsumos", "IdInsumo","Insumo","IdInsumo");?> </datalist>
			<datalist id="ListaInsumosSeleccionados"> </datalist>
			<datalist id="ListaUsuarios"> <?php GenerarListadoUsuarios() ?> </datalist>
			<datalist id="ListaProvedores"> <?php GenerarListadoProvedores() ?> </datalist>
			<datalist id="ListaOrdenes"> <?php GenerarListadoGenerico("Select * From Ordenes","IdOrden", "Orden", "IdOrden");?> </datalist>
			
			<form id="FormNuevaOrden" method="post" action="GuardarOrden.php" onSubmit="return ValidarDatos();">		
			<h1>Nueva Orden</h1>
			<hr>

			<?php MostrarExpediente(); ?>

			<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Cancelar</button>
			<button type="submit" name="Guardar">Guardar</button>


 			<hr>
 			
 			<table>
				<tr>
			 		<td>Numero de Orden:</td>
					<td><input type="text" id="InputOrden" placeholder="Orden" required/></td>
					<td>Fecha de Ingreso:</td>
					<td><input type="date" id="InputFechaActual" required/></td>	
				</tr>

				</tr>
					<td>Proveedor:</td>
					<td><input class="awesomplete" list="ListaProvedores" id="InputListaProvedores" style="width:200px;" placeholder="Proveedor" required/></td>
					<td>Recepcion de Insumos:</td>
					<td><input class="awesomplete" list="ListaUsuarios" id="InputListaUsuarioRecepcion" style="width:200px;" placeholder="Usuario que Recepciona" required/></td>
				</tr>
			</table>

			<hr>

			<table>
				<tr>
						<td>Insumo:</td> 
						<td><input class="awesomplete" list="ListaInsumos" id="InputListaInsumos" style="width:400px;" placeholder="Insumo" required/></td>
				</tr>
				<tr>
						<td>Cantidad:</td>
						<td><input type="text" id="InputCantidadInsumo" placeholder="1" style="width:20px;" required/>
						<button type="button" name="InputAñadirInsumo" onclick="ValidarAgregarInsumo()">Agregar Insumo</button></td>

				</tr>
			</table>

			<hr>

			<div id="ListadoDeInsumos"></div>
			
			<hr>

			<br>
				<input type="hidden" id="InputVarOrden" name="InputVarOrden"/>
				<input type="hidden" id="InputVarFechaIngreso" name="InputVarFechaIngreso"/>
				<input type="hidden" id="InputVarIdProvedor" name="InputVarIdProvedor"/>
				<input type="hidden" id="InputVarIdUsuarioRecepcion" name="InputVarIdUsuarioRecepcion"/>
				<input type="hidden" id="InputVarJSonOrden" name="InputVarJSonOrden"/>		
			</form>

			

  </body>

</html>



<script type="text/javascript">

var ArraySeleccionados = [];

document.getElementById("InputFechaActual").value = getFechaActual();

	function ValidarDatos()
		{

	   		var provedor = document.querySelector('#InputListaProvedores').value;
	   		if (!ValidarDatoExistente("ListaProvedores", provedor))
	   			{return false;}

	   		var usuario = document.querySelector('#InputListaUsuarioRecepcion').value;
	   		if (!ValidarDatoExistente("ListaUsuarios", usuario))
	   			{return false;}

	   		if (ArraySeleccionados=="")
	   			{
	   				alert("No agrego Insumos a la orden");
	   				return false;
	   			}


	   		aux = document.getElementById("InputOrden").value;
	   		DatoOriginal = document.getElementById("InputVarOrden").value;

	   		if (!(DatoOriginal==aux))
	   			{
	   				if (ValidarDatoRepetido("ListaOrdenes", aux))
	   					{return false;}
	   			}

	   		PrepararVariablesSubmit();
		}



	function PrepararVariablesSubmit()
		{
			var Aux = document.getElementById("InputOrden").value;
			document.getElementById("InputVarOrden").value = Aux;
			//alert("InputVarOrden: " + Aux);
			
			var Aux = document.getElementById("InputFechaActual").value;
			document.getElementById("InputVarFechaIngreso").value = Aux;
			//alert("InputVarFechaIngreso: " + Aux);

			Aux = document.getElementById("InputListaProvedores").value;
			Aux = $('#ListaProvedores [value="' + Aux + '"]').data('idvalue');
			document.getElementById("InputVarIdProvedor").value = Aux;
			//alert("InputVarIdProvedor: " + Aux);

			Aux = document.getElementById("InputListaUsuarioRecepcion").value;
			Aux = $('#ListaUsuarios [value="' + Aux + '"]').data('idvalue');
			document.getElementById("InputVarIdUsuarioRecepcion").value = Aux;
			//alert("InputVarIdUsuarioRecepcion: " + Aux);

			Aux  = JSON.stringify(ArraySeleccionados);
			document.getElementById("InputVarJSonOrden").value = Aux;
			//alert("InputVarJSonOrden: " + Aux);
		}

	function ValidarAgregarInsumo()
		{
			var AuxValue = document.getElementById("InputListaInsumos").value;
			if (!ValidarDatoExistente("ListaInsumos", AuxValue))
	   			{return false;}

			var AuxId = $('#ListaInsumos [value="' + AuxValue + '"]').data('idvalue');
			

			var AuxCantidad = document.getElementById("InputCantidadInsumo").value;
			if (AuxCantidad=="")
	   			{
	   				alert("Ingrese cantidad de insumos");
	   				return false;
	   			}

	   		if (VerificarExistenciaDeInsumoEnArray(AuxId)==1)
	   			{
	   				var Cantidad = GetCantidadInsumo(AuxId);
	   				EliminarInsumo(AuxId);
	   				//alert("Despues de EliminarInsumo, preparando sumar cantidades");
	   				AuxCantidad= parseInt(AuxCantidad) + parseInt(Cantidad);
	   				//alert("despues de sumar Cantidad: " + AuxCantidad);
	   			}

	   		AgregarInsumo(AuxValue, AuxId, AuxCantidad);
			MostrarListadoInsumos();
		}

	function EliminarInsumo(IdInsumo)
		{
			var op;
			LenArray = ArraySeleccionados.length;
			for (var i = 0;i<LenArray;i++)
				{
					op = ArraySeleccionados[i];
					if (IdInsumo == op[1])
						{
							//alert("Antes del Splice, Indice Array: " + i + "IdInsumo Del Indice: " + op[1] + ", IdInsumo a borrar = " + IdInsumo + "");
							ArraySeleccionados.splice(i,1);
							//alert("Despues del Splice");
							return;
						}
				}
		}

	function VerificarExistenciaDeInsumoEnArray(IdInsumo)
		{
			var Ban = 0;
	   		var op;
	   		var len = ArraySeleccionados.length;
	   		for (var i = 0;i<len;i++)
				{
					op = ArraySeleccionados[i];
					if (op[1]==IdInsumo)
						{
							Ban=1;
							//alert("Ya Existe");
						}

				}
			return Ban;
		}

	function GetCantidadInsumo(IdInsumo)
		{
			var Ban = 0;
	   		var op;
	   		var Len = ArraySeleccionados.length;
	   		for (var i = 0;i<Len;i++)
				{
					op = ArraySeleccionados[i];
					if (op[1]==IdInsumo)
						{
							Ban=op[0];
						}

				}
			return Ban;
		}

	function AgregarInsumo(OptionValue, OptionIdValue, OptionCantidadValue)
		{
			//OptionCantidadValue = ChequearCantidades(OptionCantidadValue, OptionIdValue);
			//alert("Array antes del push: " + ArraySeleccionados);
			var Opcion = [OptionCantidadValue, OptionIdValue, OptionValue];
			ArraySeleccionados.push(Opcion);
		 	//alert("Array Despues del push: " + ArraySeleccionados);
		 	
		}

	function MostrarListadoInsumos()
		{
	   		var len = ArraySeleccionados.length;
	   		var op;
			var devolver = '';

			for (var i=0;i<len;i++)
				{

					op = ArraySeleccionados[i];
					devolver = devolver + '<tr><td>' + op[0] + ' - ' + op[2] + '</td><td> <input type=submit value="Quitar" id=' + op[1] + ' onclick="QuitarInsumoBoton(this.id)"></td></tr>';
				}
			
			devolver = '<table border="1">' + devolver + '</table>';

			document.getElementById('ListadoDeInsumos').innerHTML="<p>" + devolver + "</p>";
		}

	function QuitarInsumoBoton(clicked_id)
		{
			var aux = clicked_id;
			EliminarInsumo(aux);
			MostrarListadoInsumos(); 
			//alert("indice a borrar: " + aux);
		}

	function EjecutarCancelar()
		{
			var IdElemento = document.getElementById('VarIdOrdenDetalle').value;
			var aux = 'MenuOpcionesOrden.php?VarIdOrdenDetalle=' + IdElemento;
			location.href=aux;
		}

</script>

<?php  

	

	function GenerarListadoUsuarios() 
		{
			$IdArea = getId('Areas', 'IdArea', 'Area','Baja');
			$Consulta = "Select * from Usuarios Where not IdArea = $IdArea Order by Usuario";	
			GenerarListadoGenerico($Consulta, "IdUsuario", "Usuario", "IdArea");	
		}


	function GenerarListadoProvedores() 
		{
			$Consulta = "Select * from Provedores WHERE NOT Baja = 1 order by Provedor";	
			GenerarListadoGenerico($Consulta, "IdProvedor", "Provedor", "IdProvedor");	
		}

	function MostrarExpediente()
		{
			$data = data_submitted();
			//print_object($data);
			$VarIdOrdenDetalle = $data->VarIdOrdenDetalle;
			
			//
			if ($VarIdOrdenDetalle)
				{
						$IdExpediente = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'IdExpediente', $VarIdOrdenDetalle);
				}
			else
				{
						$IdExpediente = $data->VarIdExpediente;
						$IdOrden = getDatoPorId('Ordenes','IdExpediente','IdOrden',$IdExpediente);
						$IdOrden = getDatoPorId('OrdenDetalle','IdOrden','IdOrdenDetalle',$IdOrden);
						//echo ("<br>IdOrdenDetalle: $VarIdOrdenDetalle<br>");
				}
				
			$Expediente = getDatoPorId('Expedientes', 'IdExpediente', 'Expediente', $IdExpediente);
			$Sector = getDatoPorId('Expedientes', 'IdExpediente', 'IdArea', $IdExpediente);
			$Sector = getDatoPorId('Areas', 'IdArea', 'Area', $Sector);
			echo "<p id='demo'>Añadir Orden a expediente $Expediente iniciado en $Sector</p>";
			echo "<input type='hidden' id='VarIdExpediente' name='VarIdExpediente' value=$IdExpediente>";
			echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$VarIdOrdenDetalle>";
			
				
		}
?>