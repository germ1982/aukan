<!doctype html>
<html lang="es">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/jquery-2.0.3.min.js" type="text/javascript"></script>   
    <script src="../Lib/FuncionesComunes.js" type="text/javascript"></script>   
</head>

<body onload="SetControles()">
	
	<?php 
		include_once 'db.php';
		include_once '../Lib/FuncionesComunes.php';
	?>

	<datalist id="ListaInsumos"> 
		<?php 
			$Consulta="SELECT IdInsumo, concat_ws('-',IdInsumoTipo,IdMarca,Modelo) as InsumoCompleto, Baja From Insumos";
			GenerarListadoGenerico($Consulta,"IdInsumo", "InsumoCompleto", "Baja");
		?> 
	</datalist>
	
	<h1>Editar Articulo</h1>
	<hr>
	
	<table id="Nuevo"> 
		<tr> 
			<td>Tipo Insumo:</td>
			<td><select name="ComboInsumoTipos" id ="ComboInsumoTipos">
				<?php LlenarCombo('Select * From InsumosTipo order by InsumoTipo','IdInsumoTipo', 'InsumoTipo');?>
			</select></td>
			<td><button type="button" name="AdministrarInsumosTipos" onclick="location.href='AdministrarInsumosTipos.php'">Administrar</button></td>
		</tr> 

		<tr> 
			<td>Marca:</td>
			<td><select name="ComboMarcas" id ="ComboMarcas">
				<?php LlenarCombo('Select * From Marcas order by Marca','IdMarca', 'Marca');?>
			</select></td>
			<td><button type="button" name="AdministrarMarcas" onclick="location.href='AdministrarMarcas.php'">Administrar</button></td>
		</tr> 

		<tr>
			<td>Modelo:</td>
			<td><input type="text" id="InputModelo" placeholder="Modelo"/>	</td>
		</tr>

		<tr>
			<td><input type="checkbox" name="CheckboxBaja" id=CheckboxBaja>Baja</td>
			<td></td>
		</tr>
	</table>
	
	<table>

		<tr>
			<td>Caracteristicas(Opcional):</td>
		</tr>
		
		<tr>
			<td><input type="text" id="InputCaracteristicas" placeholder="Caracteristicas" style="width:800px;"/></td>
		</tr>
	</table>

	<hr>
	<br>
	<button type="button" name="GuardarInsumo" onclick='ValidarDatos();'>Guardar Insumo</button>
	<button type="button" name="Cancelar" onclick="location.href='AdministrarInsumos.php'">Cancelar</button>
	<br><br>

	<input type="hidden" id="InputVarInsumoOriginal" value="0"/>	
	<?php EjecutarOperacion()?>
	
</body>

</html>

<script type="text/javascript">

function SetControles()
	{
		var aux  = document.getElementById('VarEditIdInsumo').value;
		if(aux == 0)
			{
				PrepararAlta();
			}
		else
			{
				MostrarInsumo(aux);
			} 
	}

function PrepararAlta()
	{

		document.getElementById("InputVarInsumoOriginal").value = '0';
		var aux = document.getElementById("InputVarInsumoOriginal").value;
		//alert("InputVarInsumoOriginal: " + aux);
		document.getElementById('InputModelo').value = '';
		document.getElementById('InputCaracteristicas').value = '';
		document.getElementById('CheckboxBaja').checked = false;
	}

function MostrarInsumo(IdInsumo)
	{	
		var aux = document.getElementById('VarEditIdInsumoTipo').value;
		document.getElementById("ComboInsumoTipos").value = aux;

		var aux = document.getElementById('VarEditIdMarca').value;
		document.getElementById("ComboMarcas").value = aux;

		aux = document.getElementById('VarEditModelo').value;
		document.getElementById('InputModelo').value = aux;

		aux = document.getElementById('VarEditCaracteristicas').value;
		document.getElementById('InputCaracteristicas').value = aux;

		aux = document.getElementById('VarEditBaja').value;
		if (aux==1)
			{
				document.getElementById('CheckboxBaja').checked = true;
			}
		else
			{
				document.getElementById('CheckboxBaja').checked = false;
			}

		aux = document.getElementById("VarEditIdInsumoTipo").value;
		aux = aux + '-' + document.getElementById("VarEditIdMarca").value;
		aux = aux + '-' + document.getElementById("VarEditModelo").value;
		document.getElementById("InputVarInsumoOriginal").value = aux;
	}

function ValidarDatos()
	{
		
		var aux = document.getElementById("InputModelo").value;
		if(aux == "")
			{
				alert("Falta El Modelo");
				return false;
			}

		aux = document.getElementById("ComboInsumoTipos").value;
		aux = aux + '-' + document.getElementById("ComboMarcas").value;
		aux = aux + '-' + document.getElementById("InputModelo").value;
   		var InsumoOriginal = document.getElementById("InputVarInsumoOriginal").value;
   		//alert('Nuevo: ' + aux + ', Original: ' + InsumoOriginal);

   		if(!(InsumoOriginal==aux))
   			{
   				
   				if (ValidarDatoRepetido("ListaInsumos", aux))
   					{
   						//alert('No Igual');
   						return false;
   					}
   			}
   		
   		GuardarInsumo();
	}

//

function GuardarInsumo()
	{	
		var IdInsumo = document.getElementById("VarEditIdInsumo").value;
		var IdInsumoTipo = document.getElementById("ComboInsumoTipos").value;
		var IdMarca = document.getElementById("ComboMarcas").value;
		var Modelo = document.getElementById("InputModelo").value;
		var Caracteristicas = document.getElementById("InputCaracteristicas").value;
		var Baja = document.getElementById('CheckboxBaja').checked;
		if (Baja == true)
			{Baja = 1;}
		else
			{Baja=0;}
		
		aux = 'GuardarInsumo.php?VarIdInsumo=' + IdInsumo + '&VarIdInsumoTipo=' + IdInsumoTipo + '&VarIdMarca=' + IdMarca + '&VarModelo=' + Modelo + '&VarCaracteristicas=' + Caracteristicas + '&VarBaja=' + Baja;
		//alert(aux);
		location.href=aux;		
	}

</script>

<?php
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

function EjecutarOperacion()
	{
		$data = data_submitted();
		//print_object($data);
		$IdInsumo = $data->VarIdInsumo;


		if ($IdInsumo == 0)
			{
				PrepararAltaInsumo();
			}
		else
			{
				AbrirInsumo($IdInsumo);
			}
	}

function PrepararAltaInsumo()
	{
		echo "<input type='hidden' id='VarEditIdInsumo' value='0'>"; 
		echo "<input type='hidden' id='VarEditIdInsumoTipo' value='0'>";
		echo "<input type='hidden' id='VarEditIdMarca' value='0'>";
		echo "<input type='hidden' id='VarEditModelo' value=''>";
		echo "<input type='hidden' id='VarEditCaracteristicas' value=''>"; 
		echo "<input type='hidden' id='VarEditBaja' value='0'";   
	}

function AbrirInsumo($IdInsumo)
	{
		$Consulta = "SELECT * From Insumos WHERE IdInsumo = $IdInsumo" ;
		//echo "<br>$Consulta<br>";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);
		$result = $dbh->Registro();

		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				echo "<input type='hidden' id='VarEditIdInsumo' value='".$result['IdInsumo']."'>"; 
				echo "<input type='hidden' id='VarEditIdInsumoTipo' value='".$result['IdInsumoTipo']."'>";
				echo "<input type='hidden' id='VarEditIdMarca' value='".$result['IdMarca']."'>";
				echo "<input type='hidden' id='VarEditModelo' value='".$result['Modelo']."'>";
				echo "<input type='hidden' id='VarEditCaracteristicas' value='".$result['Caracteristicas']."'>"; 
				echo "<input type='hidden' id='VarEditBaja' value='".$result['Baja']."'>";   
			}

		echo "</table>";	
		$dbh->Cerrar();
		$dbh = NULL;
	}
?>