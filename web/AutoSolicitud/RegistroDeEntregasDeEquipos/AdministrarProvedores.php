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

			<datalist id="ListaProvedores"> <?php GenerarListadoGenerico("Select * From Provedores","IdProvedor", "Provedor", "Baja");?> </datalist>
			
			<h1>Provedores</h1>
			<hr>
			
 			<div>
				<button type="button" name="NuevoProvedor" onclick="MostrarDivNuevoProvedor()">Nuevo Provedor</button>
				<button type="button" name="Volver" onclick = "location='MenuEquiposSalientes.php'" >Volver</button>
			</div>

			<div id="DivNuevoProvedor" style='display:none;'>
				<hr>
				<br>
				Provedor:
				<input type="text" id="InputProvedor" placeholder="Provedor"/>	
				<input type="checkbox" name="CheckboxBaja" id=CheckboxBaja>Baja
				<br><br>
				<button type="button" name="GuardarProvedor" onclick="ValidarDatos()">Guardar Provedor</button>
				<button type="button" name="Cancelar" onclick="OcultarDivNuevoProvedor()">Cancelar</button>
				<br><br>
			</div>

				<hr>

			<div id="resultadoBusqueda">
				<hr>
			</div>
			<input type="hidden" id="InputVarIdProvedor"/>	
			<input type="hidden" id="InputVarProvedor"/>	
			
  </body>

</html>



<script type="text/javascript">

	buscar();

	function ValidarDatos()
		{
			var aux = document.getElementById("InputProvedor").value;

	   		var ProvedorOriginal = document.getElementById("InputVarProvedor").value;
	   		if (!(ProvedorOriginal==aux))
	   			{
	   				if (ValidarDatoRepetido("ListaProvedores", aux))
	   					{return false;}
	   			}
	   		GuardarProvedor();
		}

	function GuardarProvedor()
		{
			/* InputVarIdProvedor se define como 0 al apretar el boton de nueva Provedor, 
			o se define la id del la Provedor a editar al apretar el boton noton editar*/		
			var Provedor = document.getElementById("InputProvedor").value;
			var IdProvedor = document.getElementById("InputVarIdProvedor").value;//
			var Baja = document.getElementById('CheckboxBaja').checked;
			if (Baja == true)
				{Baja = 1;}
			else
				{Baja=0;}
			
			aux = 'GuardarProvedor.php?VarIdProvedor=' + IdProvedor + '&VarProvedor=' + Provedor + '&VarBaja=' + Baja;
			location.href=aux;		
		}

	function MostrarDivNuevoProvedor()
		{
			
			document.getElementById('DivNuevoProvedor').style.display = 'block';
			document.getElementById('resultadoBusqueda').style.display = 'none';
			PrepararAlta();
			
		}

	function PrepararAlta()
		{	
			document.getElementById('InputProvedor').value = '';
			document.getElementById('InputVarIdProvedor').value = 0;
			document.getElementById('CheckboxBaja').checked = false;
			document.getElementById("InputVarProvedor").value = '';
		}

	function OcultarDivNuevoProvedor()
		{		
			document.getElementById('DivNuevoProvedor').style.display = 'none';
			document.getElementById('resultadoBusqueda').style.display = 'block';
		}

	function BotonEditar(clicked_id)
		{
			var IdProvedor = clicked_id;
			document.getElementById('DivNuevoProvedor').style.display = 'block';
			document.getElementById('resultadoBusqueda').style.display = 'none';
			MostrarProvedor(IdProvedor);
		}

	function MostrarProvedor(IdProvedor)
		{
			document.getElementById('InputVarIdProvedor').value = IdProvedor;
			document.getElementById('InputProvedor').value = getValueDeListaGenerica('ListaProvedores', IdProvedor);
			document.getElementById('InputVarProvedor').value = document.getElementById('InputProvedor').value;
			var aux = getIdVinculoDeListaGenerica('ListaProvedores', IdProvedor);
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
			        $.post("MostrarProvedores.php", function(mensaje) 
			        	{
			            $("#resultadoBusqueda").html(mensaje);
			         	}); 
		}


</script>


