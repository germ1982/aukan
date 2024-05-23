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

			<datalist id="ListaMarcas"> <?php GenerarListadoGenerico("Select * From Marcas","IdMarca", "Marca", "Baja");?> </datalist>
			
			<h1>Marcas</h1>
			<hr>
			
 			<div>
				<button type="button" name="NuevaMarca" onclick="MostrarDivNuevaMarca()">Nueva Marca</button>
				<button type="button" name="Volver" onclick = "location='MenuEquiposSalientes.php'" >Volver</button>
			</div>

			<div id="DivNuevaMarca" style='display:none;'>
				<hr>
				<br>
				Marca:
				<input type="text" id="InputMarca" placeholder="Marca"/>	
				<input type="checkbox" name="CheckboxBaja" id=CheckboxBaja>Baja
				<br><br>
				<button type="button" name="GuardarMarca" onclick="ValidarDatos()">Guardar Marca</button>
				<button type="button" name="Cancelar" onclick="OcultarDivNuevaMarca()">Cancelar</button>
				<br><br>
			</div>

				<hr>

			<div id="resultadoBusqueda">
				<hr>
			</div>
			<input type="hidden" id="InputVarIdMarca"/>	
			<input type="hidden" id="InputVarMarca"/>	
			
  </body>

</html>



<script type="text/javascript">

	buscar();

	function ValidarDatos()
		{
			var aux = document.getElementById("InputMarca").value;

	   		var MarcaOriginal = document.getElementById("InputVarMarca").value;
	   		if (!(MarcaOriginal==aux))
	   			{
	   				if (ValidarDatoRepetido("ListaMarcas", aux))
	   					{return false;}
	   			}
	   		GuardarMarca();
		}

	function GuardarMarca()
		{
			/* InputVarIdMarca se define como 0 al apretar el boton de nueva marca, 
			o se define la id del la marca a editar al apretar el boton noton editar*/		
			var Marca = document.getElementById("InputMarca").value;
			var IdMarca = document.getElementById("InputVarIdMarca").value;//
			var Baja = document.getElementById('CheckboxBaja').checked;
			if (Baja == true)
				{Baja = 1;}
			else
				{Baja=0;}
			
			aux = 'GuardarMarca.php?VarIdMarca=' + IdMarca + '&VarMarca=' + Marca + '&VarBaja=' + Baja;
			location.href=aux;		
		}

	function MostrarDivNuevaMarca()
		{
			
			document.getElementById('DivNuevaMarca').style.display = 'block';
			document.getElementById('resultadoBusqueda').style.display = 'none';
			PrepararAlta();
			
		}

	function PrepararAlta()
		{	
			document.getElementById('InputMarca').value = '';
			document.getElementById('InputVarIdMarca').value = 0;
			document.getElementById('CheckboxBaja').checked = false;
			document.getElementById("InputVarMarca").value = '';
		}

	function OcultarDivNuevaMarca()
		{		
			document.getElementById('DivNuevaMarca').style.display = 'none';
			document.getElementById('resultadoBusqueda').style.display = 'block';
		}

	function BotonEditar(clicked_id)
		{
			var IdMarca = clicked_id;
			document.getElementById('DivNuevaMarca').style.display = 'block';
			document.getElementById('resultadoBusqueda').style.display = 'none';
			MostrarMarca(IdMarca);
		}

	function MostrarMarca(IdMarca)
		{
			document.getElementById('InputVarIdMarca').value = IdMarca;
			document.getElementById('InputMarca').value = getValueDeListaGenerica('ListaMarcas', IdMarca);
			document.getElementById('InputVarMarca').value = document.getElementById('InputMarca').value;
			var aux = getIdVinculoDeListaGenerica('ListaMarcas', IdMarca);
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
			        $.post("MostrarMarcas.php", function(mensaje) 
			        	{
			            $("#resultadoBusqueda").html(mensaje);
			         	}); 
		}


</script>


