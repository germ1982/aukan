<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
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

			<datalist id="ListaInsumosTipos"> <?php GenerarListadoGenerico("Select * From InsumosTipo","IdInsumoTipo", "InsumoTipo", "Baja");?> </datalist>
			
			<h1>Tipos de Insumo</h1>
			<hr>
			
 			<div>
				<button type="button" name="NuevoInsumoTipo" onclick="MostrarDivNuevoInsumoTipo()">Nuevo Tipo Insumo</button>
				<button type="button" name="Volver" onclick = "location='MenuEquiposSalientes.php'" >Volver</button>
			</div>

			<div id="DivNuevoInsumoTipo" style='display:none;'>
				<hr>
				<br>
				Tipo Insumo:
				<input type="text" id="InputInsumoTipo" placeholder="Tipo Insumo"/>	
				<input type="checkbox" name="CheckboxBaja" id=CheckboxBaja>Baja
				<br><br>
				<button type="button" name="GuardarInsumoTipo" onclick="ValidarDatos()">Guardar Tipo Insumo</button>
				<button type="button" name="Cancelar" onclick="OcultarDivNuevoInsumoTipo()">Cancelar</button>
				<br><br>
			</div>

				<hr>

			<div id="resultadoBusqueda">
				<hr>
			</div>
			<input type="hidden" id="InputVarIdInsumoTipo"/>	
			<input type="hidden" id="InputVarInsumoTipo"/>	
			
  </body>

</html>



<script type="text/javascript">

	buscar();

	function ValidarDatos()
		{
			var aux = document.getElementById("InputInsumoTipo").value;
	   		var InsumoTipoOriginal = document.getElementById("InputVarInsumoTipo").value;
	   		//alert('Nuevo: ' + aux + ', Original: ' + InsumoTipoOriginal);

	   		if(!(InsumoTipoOriginal==aux))
	   			{
	   				//alert('No Igual');
	   				if (ValidarDatoRepetido("ListaInsumosTipos", aux))
	   					{return false;}
	   			}

	   		GuardarInsumoTipo();
		}

	function GuardarInsumoTipo()
		{
			/* InputVarIdInsumoTipo se define como 0 al apretar el boton de nueva marca, 
			o se define la id del la marca a editar al apretar el boton noton editar*/		
			var InsumoTipo = document.getElementById("InputInsumoTipo").value;
			var IdInsumoTipo = document.getElementById("InputVarIdInsumoTipo").value;//
			var Baja = document.getElementById('CheckboxBaja').checked;
			if (Baja == true)
				{Baja = 1;}
			else
				{Baja=0;}
			
			aux = 'GuardarInsumoTipo.php?VarIdInsumoTipo=' + IdInsumoTipo + '&VarInsumoTipo=' + InsumoTipo + '&VarBaja=' + Baja;
			//alert(aux);
			location.href=aux;		
		}

	function MostrarDivNuevoInsumoTipo()
		{
			
			document.getElementById('DivNuevoInsumoTipo').style.display = 'block';
			document.getElementById('resultadoBusqueda').style.display = 'none';
			PrepararAlta();
			
		}

	function PrepararAlta()
		{	
			document.getElementById('InputInsumoTipo').value = '';
			document.getElementById('InputVarIdInsumoTipo').value = 0;
			document.getElementById('CheckboxBaja').checked = false;
			document.getElementById("InputVarInsumoTipo").value = '';
		}

	function OcultarDivNuevoInsumoTipo()
		{		
			document.getElementById('DivNuevoInsumoTipo').style.display = 'none';
			document.getElementById('resultadoBusqueda').style.display = 'block';
		}

	function BotonEditar(clicked_id)
		{
			var IdInsumoTipo = clicked_id;
			document.getElementById('DivNuevoInsumoTipo').style.display = 'block';
			document.getElementById('resultadoBusqueda').style.display = 'none';
			MostrarInsumoTipo(IdInsumoTipo);
		}

	function MostrarInsumoTipo(IdInsumoTipo)
		{
			document.getElementById('InputVarIdInsumoTipo').value = IdInsumoTipo;
			var aux = getValueDeListaGenerica('ListaInsumosTipos', IdInsumoTipo);
			document.getElementById('InputInsumoTipo').value = aux;
			document.getElementById('InputVarInsumoTipo').value = document.getElementById('InputInsumoTipo').value;
			aux = getIdVinculoDeListaGenerica('ListaInsumosTipos', IdInsumoTipo);
			if (aux==1)
				{
					document.getElementById('CheckboxBaja').checked = true;
				}
			else
				{
					document.getElementById('CheckboxBaja').checked = false;
				}

		}

	function buscar() 
		{
			        $.post("MostrarInsumoTipos.php", function(mensaje) 
			        	{
			            $("#resultadoBusqueda").html(mensaje);
			         	}); 
		}


</script>


