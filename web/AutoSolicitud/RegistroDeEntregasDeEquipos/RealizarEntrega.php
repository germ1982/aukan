<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  	<meta http-equiv="refresh" content="6000" />
    <link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
    <script src="../Lib/awesomplete.min.js"></script>
  </head>
  <body onload="SetControles()">
			<?php
				include_once 'db.php';
				require_once '../Lib/FuncionesComunes.php';
				header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros
				VerificarSession();
			?>

			<datalist id="ListaUsuarios"> <?php GenerarListadoUsuarios(); ?> </datalist>
			<datalist id="ListaInsumos"> <?php GenerarListadoInsumos(); ?> </datalist>

			<h1>Realizar Entrega</h1>
	  		<hr>

 				Fecha de entrega de Insumos:
				<input type="date" id="InputFecha"><br>	

	 			Persona que entrega los insumos:
	 			<input class="awesomplete" list="ListaUsuarios" id="InputListaUsuariosQueEntrega" placeholder="Usuario"/><br>

	 			Persona que retira Insumos:
	 			<input class="awesomplete" list="ListaUsuarios" id="InputListaUsuariosQueRetira" placeholder="Usuario"/>

	  			<div id="ListadoDeInsumos"></div>


	  			<form id="FormNuevaEntrega" method="post" action="GuardarNuevaEntrega.php" onSubmit="return ValidarDatos();">		
					<button type="submit" name="Guardar">Guardar</button>
					<button type="button" name="Cancelar" onclick = "location='MenuEquiposSalientes.php'" >Cancelar</button>
		  			<input type="hidden" id="InputVarFechaEntrega" name="InputVarFechaEntrega"/>
					<input type="hidden" id="InputVarIdUsuarioDespachante" name="InputVarIdUsuarioDespachante"/>
					<input type="hidden" id="InputVarIdUsuarioRecepcion" name="InputVarIdUsuarioRecepcion"/>
					<input type="hidden" id="InputVarJSonInsumos" name="InputVarJSonInsumos"/>
				</form>	

			<hr>
			<form name="FormOrden">
				Ordenar por:
				<input type="radio" id="Articulo" name="Orden" value="Insumo" onclick="MostrarEntrega(this.value)">Articulos
		  		<input type="radio" id="Orden" name="Orden" value="Orden" onclick="MostrarEntrega(this.value)">Ordenes 
	  		</form>
	<?php
		$data = data_submitted();
		$Orden = $data->VarOrden;
		echo "<input type='hidden' id='InputVarOrden' value='$Orden'/>";
		$Consulta = GenerarConsulta($Orden);

		if($Orden=='Orden')	
			{
				CargarGrillaPorOrden($Consulta);
			}
		else
			{
				CargarGrillaPorArticulo($Consulta);
			}
	?>		
  </body>

</html>


<?php

	function CargarGrillaPorOrden($Consulta)
		{

			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($Consulta);

			echo "<table id='TablaRegistros' border='1'>";
			echo "<tr>"; 
				echo "<th>Orden</th>"; 
			    echo "<th>Articulo</th>";
			    echo "<th>Stock</th>";	
			    echo "<th>Expediente</th> ";
			    echo "<th>Solicitud</th>";
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
							echo "<td>".$result['Orden']."</td>";
							echo "<td>".$result['Insumo']."</td>"; 
							echo "<td>".$result['StockDisponible']."</td>"; 
							echo "<td>".$result['Expediente']."</td>"; 
							echo "<td>".$result['Solicitante']." - ".$result['Area']."</td>"; 
							echo "<td><input type=submit value='Añadir' id=".$result['IdOrdenDetalle']." onclick='ValidarAñadirInsumos(this.id)'></td>";

						echo "</tr>";   
					}
				}

			echo "</table>";	
			$dbh->Cerrar();
			$dbh = NULL;


		}

	function CargarGrillaPorArticulo($Consulta)
		{

			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($Consulta);

			echo "<table id='TablaRegistros' border='1'>";
			echo "<tr>"; 
			    echo "<th>Articulo</th>";
			    echo "<th>Stock</th>";
			    echo "<th>Orden</th>"; 
			    echo "<th>Expediente</th> ";
			    echo "<th>Solicitud</th>";
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
							echo "<td>".$result['Insumo']."</td>"; 
							echo "<td>".$result['StockDisponible']."</td>"; 
							echo "<td>".$result['Orden']."</td>"; 
							echo "<td>".$result['Expediente']."</td>"; 
							echo "<td>".$result['Solicitante']." - ".$result['Area']."</td>"; 
							echo "<td><input type=submit value='Añadir' id=".$result['IdOrdenDetalle']." onclick='ValidarAñadirInsumos(this.id)'></td>";

						echo "</tr>";   
					}
				}

			echo "</table>";	
			$dbh->Cerrar();
			$dbh = NULL;


		}
	
	function GenerarConsulta()
		{
			$data = data_submitted();
			//print_object($data); 
			$Orden = $data->VarOrden;

			if (!$Orden=='')	
				{$Orden="Order by $Orden";} 

			$Consulta = "Select * From VistaEntregasPendientesPorInsumos $Orden";
			return $Consulta;
		}	

	function GenerarListadoUsuarios() 
		{
			$IdArea = getId('Areas', 'IdArea', 'Area','Baja');
			$Consulta = "Select * from Usuarios Where not IdArea = $IdArea Order by Usuario";	
			GenerarListadoGenerico($Consulta, "IdUsuario", "Usuario", "IdArea");	
		}

	function GenerarListadoInsumos()
		{
			$consulta = "Select IdOrdenDetalle, concat(Insumo,' (Orden: ', Orden, ' Expediente: ', Expediente) as Insumo, StockDisponible From VistaEntregasPendientesPorInsumos";
			GenerarListadoGenerico($consulta,"IdOrdenDetalle","Insumo","StockDisponible");
		}

?>


<script type="text/javascript">

	var ArraySeleccionados = [];

	MostrarFechaActual("InputFecha");

	function SetControles()
		{
			var Orden = document.getElementById("InputVarOrden").value;
			if (Orden=='Orden')
				{
					document.getElementById("Orden").checked= true;
					document.getElementById("Articulo").checked=false;
				}
				
			else
				{
					document.getElementById("Articulo").checked=true;
					document.getElementById("Orden").checked= false;
				}
		}

	function ValidarDatos()
		{
	   		var usuario = document.querySelector('#InputListaUsuariosQueEntrega').value;
		   		if (!ValidarDatoExistente("ListaUsuarios", usuario))
		   			{return false;}

	   		var usuario = document.querySelector('#InputListaUsuariosQueRetira').value;
	   		if (!ValidarDatoExistente("ListaUsuarios", usuario))
	   			{return false;}

	   		if (ArraySeleccionados=="")
	   			{
	   				alert("No agrego Insumos Para Entregar");
	   				return false;
	   			}

	   		PrepararVariablesSubmit();
		}

	function PrepararVariablesSubmit()
		{
			var Aux = document.getElementById("InputFecha").value;
			document.getElementById("InputVarFechaEntrega").value = Aux;
			//alert("InputVarFechaIngreso: " + Aux);

			Aux = document.getElementById("InputListaUsuariosQueEntrega").value;
			Aux = $('#ListaUsuarios [value="' + Aux + '"]').data('idvalue');
			document.getElementById("InputVarIdUsuarioDespachante").value = Aux;
			
			Aux = document.getElementById("InputListaUsuariosQueRetira").value;
			Aux = $('#ListaUsuarios [value="' + Aux + '"]').data('idvalue');
			document.getElementById("InputVarIdUsuarioRecepcion").value = Aux;
			//alert("InputVarIdUsuarioRecepcion: " + Aux);

			Aux  = JSON.stringify(ArraySeleccionados);
			document.getElementById("InputVarJSonInsumos").value = Aux;
			//alert("InputVarJSonOrden: " + Aux);
		}

	function getRadioButtonSelectedValue(ctrl)
			{
			    for(i=0;i<ctrl.length;i++)
			        if(ctrl[i].checked) return ctrl[i].value;
			}

	function MostrarEntrega(Orden)
				{
					location.href = "RealizarEntrega.php?VarOrden="+Orden;
				}

	/*function ValidarAñadirInsumos(clicked_id)
		{
			var devolver = MostrarListadoInsumosJS('ListaInsumos');
			alert(devolver);
			document.getElementById('ListadoDeInsumos').innerHTML="<p>" + devolver + "</p>";
		}

	function MostrarListadoInsumosJS(ListaDeDatos){
   		var x = document.getElementById(ListaDeDatos).options.length;
		var v;
		var devolver = "";
		for (var i= 0;i<x;i++)
		{
			v = document.getElementById(ListaDeDatos).options[i].value;
			Id = $('#' + ListaDeDatos + ' [value="' + v + '"]').data('idvalue');
			IdVinculo = $('#' + ListaDeDatos + ' [value="' + v + '"]').data('idvinculo');
			devolver = devolver + "Id: " + Id + " Value:" + v + ' Vinculo: ' + IdVinculo + "<br>";
		}
		return devolver;}*/

	function ValidarAñadirInsumos(clicked_id)
		{

			var Insumo = getValueDeListaGenerica("ListaInsumos", clicked_id);
			var Stock = getIdVinculoDeListaGenerica("ListaInsumos", clicked_id);
			var StockSolicitado = prompt("Ingrese la cantidad de insumos a entregar", Stock);
			//alert ('IdOrdenDetalle: '+clicked_id+'\nInsumo: '+Insumo+'\nStock: '+Stock);
			
			if (VerificarSiYaFueAgregado(clicked_id))
				{
					alert("Articulo ya añadido, si desea modificar la cantidad, quite el articulo de la lista y vuelva a agregar con la cantidad deseada");
					return;
				}

			if(StockSolicitado>Stock)
				{
					alert("La cantidad solicitada supera la cantidad existente..");
					return;
				}

			AgregarInsumo(clicked_id, StockSolicitado, Insumo, Stock);
			MostrarListadoInsumos();


		}

	function VerificarSiYaFueAgregado(IdInsumo)
			{
				var op;
				LenArray = ArraySeleccionados.length;
				for (var i = 0;i<LenArray;i++)
					{
						op = ArraySeleccionados[i];
						if (IdInsumo == op[0])
							{
								return true;
							}
						else
							{
								return false;
							}
					}
			}

	function AgregarInsumo(IdOrdenDetalle, StockSolicitado, Insumo, Stock)
		{
			var Opcion = [IdOrdenDetalle, StockSolicitado, Insumo, Stock];
			ArraySeleccionados.push(Opcion);	
		}

	function MostrarListadoInsumos()
		{
	   		var len = ArraySeleccionados.length;
	   		var op;
			var devolver = '';

			for (var i=0;i<len;i++)
				{

					op = ArraySeleccionados[i];
					devolver = devolver + '<tr><td>' + op[1] + ' de ' + op[3]  + ' - ' + op[2] + '</td><td> <input type=submit value="Quitar" id=' + op[0] + ' onclick="QuitarInsumoBoton(this.id)"></td></tr>';
				}
			
			devolver = '<table border="1">' + devolver + '</table>';

			document.getElementById('ListadoDeInsumos').innerHTML="<p>" + devolver + "</p>";
		}

	function QuitarInsumoBoton(clicked_id)
			{
				var aux = clicked_id;
				//alert("indice a borrar: " + aux);
				EliminarInsumo(aux);
				MostrarListadoInsumos(); 
				//
			}

	function EliminarInsumo(IdInsumo)
			{
				var op;
				LenArray = ArraySeleccionados.length;
				for (var i = 0;i<LenArray;i++)
					{
						op = ArraySeleccionados[i];
						if (IdInsumo == op[0])
							{
								//alert("Antes del Splice, Indice Array: " + i + "IdInsumo Del Indice: " + op[1] + ", IdInsumo a borrar = " + IdInsumo + "");
								ArraySeleccionados.splice(i,1);
								//alert("Despues del Splice");
								return;
							}
					}
			}

</script>