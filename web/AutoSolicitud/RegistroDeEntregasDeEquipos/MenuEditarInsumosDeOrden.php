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
				require_once '../Lib/FuncionesComunes.php';
				VerificarSession();
			?>
			<datalist id="ListaInsumos"> <?php GenerarListadoGenerico("Select * From VistaInsumos", "IdInsumo","Insumo","IdInsumo");?> </datalist>
			<h1>Editar insumos</h1>
			<p id="TextoHtml"></p> 
			<hr>
			Agregar Insumo: <br>
			<td><input class="awesomplete" list="ListaInsumos" id="InputListaInsumos" style="width:400px;" placeholder="Insumo"/><td/>
			<td><button type="button" name="AdministrarInsumos" onclick = "location='AdministrarInsumos.php'" style="width:90px;margin-left:110px;">Administrar</button></td>
			<br>Cantidad: <input type="text" id="InputCantidadInsumo" placeholder="1" style="width:20px;"/><td/>
			<button type="button" name="AñadirInsumo" onclick = "AñadirInsumo();" >Añadir Insumo</button>
			
			<hr>
			<br><br>
			Insumos Existentes:
			<div id="resultadoBusqueda">
				<hr>
			</div>		
			<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Volver</button>	
			<hr>
  </body>

</html>

<script type="text/javascript">

	function EjecutarCancelar()
		{
			var IdElemento = document.getElementById('VarIdOrdenDetalle').value;
			var aux = 'MenuOpcionesOrden.php?VarIdOrdenDetalle=' + IdElemento;
			location.href=aux;
		}

	function SetControles()
		{
			var aux = document.getElementById('VarTexto').value;
			document.getElementById("TextoHtml").innerHTML = aux;
			buscar();
		}

	function AñadirInsumo()
		{
			var AuxInsumo = document.getElementById("InputListaInsumos").value;
			if (!ValidarDatoExistente("ListaInsumos", AuxInsumo))
	   			{return false;}

	   		var AuxIdInsumo = $('#ListaInsumos [value="' + AuxInsumo + '"]').data('idvalue');

	   		var AuxCantidad = document.getElementById("InputCantidadInsumo").value;
	   		if(!esNumeroEnteroPositivo(AuxCantidad))
	   			{return false;}

	   		AuxIdOrden = document.getElementById("VarIdOrden").value;

	   		var aux = 'AddInsumoAOrden.php?VarIdOrden=' + AuxIdOrden + '&VarIdInsumo=' + AuxIdInsumo + '&VarCantidad=' + AuxCantidad;
			location.href=aux;
	   		

		}

	function QuitarInsumo(IdOrdenDetalle)
		{
			AuxIdOrden = document.getElementById("VarIdOrden").value;
			var aux = 'DeleteOrdenDetalle.php?VarIdOrdenDetalle=' + IdOrdenDetalle + '&VarIdOrden=' + AuxIdOrden;
			location.href=aux;
		}
	function CambiarCantidad(IdOrdenDetalle,cantidad,stock)
		{
			var Num;
			var ban = 0;
			var StockEntregado;

			do{
				Num = prompt('Ingrese Nueva Cantidad ' );
				StockEntregado = cantidad - stock;
				if (esNumeroEnteroPositivo(Num))
					{	
						if (StockEntregado>Num)
							{alert('la cantidad solicitada no alcanza a cubrir el stock ya entregado ('+StockEntregado+')...');}
						else
							{ban=1;}	
					}
			}
			while(ban==0)

			stock = Num - StockEntregado;

			AuxIdOrden = document.getElementById("VarIdOrden").value;

			var aux = 'SetCantidadOrdenDetalle.php?VarIdOrdenDetalle=' + IdOrdenDetalle + '&VarCantidadInsumo=' + Num + '&VarIdOrden=' + AuxIdOrden + '&VarStock=' + stock;
			location.href=aux;

		}

	function esNumeroEnteroPositivo(numero)
		{
		    var ban = 0;
		    if (isNaN(numero))
			    {
			        alert(" no es un número.");
			        return false;
			    }


		    if (!(numero % 1 == 0))
		        {
		            alert ("Ingrese un Entero");
		            return false;
		        }

		    if (!(numero > 0))
		        {
		            alert ("Ingrese un Entero Positivo");
		            return false;
		        }
			return true;    
		}

	function buscar() 
		{	
		    var IdElemento = document.getElementById('VarIdOrden').value;
    		//alert(IdElemento);
    		var aux = "MostrarInsumosDeOrden.php?VarIdOrden=" + IdElemento;
			$.post(aux, function(mensaje) {$("#resultadoBusqueda").html(mensaje);});
   		}

</script>


<?php
	include_once 'db.php';
	include_once '../Lib/FuncionesComunes.php';
	header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

	PrepararFormulario();

	function PrepararFormulario()
		{
				$data = data_submitted();
				//print_object($data);
				$IdOrden = $data->VarIdOrden;
				$IdOrdenDetalle = getDatoPorId('OrdenDetalle', 'IdOrden', 'IdOrdenDetalle', $IdOrden);
				$Orden = getDatoPorId('Ordenes', 'IdOrden', 'Orden', $IdOrden);
				$Texto = "Orden: $Orden"; // IdOrdenDetalle: $IdOrdenDetalle";

				echo "<input type='hidden' id='VarIdOrden' name='VarIdOrden' value=$IdOrden>";
				echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$IdOrdenDetalle>";
				echo "<input type='hidden' id='VarTexto' name='VarTexto' value='$Texto'>";
				echo "<input type='hidden' id='VarIdOrden' name='VarIdOrden' value=$IdOrden>";
				echo "<input type='hidden' id='VarOrden' name='VarOrden' value='$Orden'>";
		}


?>