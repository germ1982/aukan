<?php
	require_once '../../config/db.php';
	include_once('../Lib/FuncionesComunes.php');
?>

<!doctype html>
<html>
  <head>
		<link href="../Css/awesompleteOriginal.css" rel="stylesheet" type="text/css"/>
		<script src="../Lib/awesompleteO.min.js"></script>
		<script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
		<script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>  
		<link href="../Css/Registros.css" rel="stylesheet" type="text/css"> 
  </head>

  <body onload="setcontroles()">
  	<font size=2>
  		
  			<datalist id="ListaContactos"> <?php GenerarListadoContactos() ?> </datalist>
			<datalist id="ListaSectores"> <?php GenerarListadoSectores() ?> </datalist>

			*Direccion de Informatica * Servicio Tecnico * Incidencia
			<hr>
				<table> 
					<b>Datos Incidencia:</b><br>
					<tr> 
						<td>Equipo:</td>
						<td>
							<input type="text" id="InputEquipo" name="InputEquipo" style="width:300px;" placeholder="Matricula o descripcion del equipo">
							Ip:
							<input type="text" id="InputIp" name="InputIp" placeholder="Ingrese Ip o -">	
						</td>	
					</tr> 
				</table> 
			<font size=4><hr> </font>
				<table id="TableIngreso"> 

					<table> 
					
						<tr> 
							<b>Datos Iniciantes:</b>
						</tr> 
						
						<tr> 
							<td>Sector:</td>
							<td><input class="awesomplete" list="ListaSectores" id="InputListaSectores" style="width:700px;" placeholder="Sector"/></td>
						</tr> 
						
						<tr> 
							<td>Iniciante: </td>
							<td>
								<input class="awesomplete" list="ListaContactos" id="InputListaContactoInicio" placeholder="Contacto ingreso"/>
							
								Fecha:
								<input type="date" id="InputFechaInicio" name="InputFechaInicio" autocomplete="off"/>
							</td>	
						</tr> 

					</table> 
					<table> 
						<tr> 
							<td>Problema Reportado:</td>
						</tr> 
						<tr> 
							<td><textarea id="InputProblema" name="InputProblema" rows=3 placeholder="Describa el problema" style="width:800px;"></textarea></td>
						</tr> 
					</table> 

				</table> 
				
			<font size=4><hr> </font>
				<b>Movimientos de Incidencia:</b><br>
				<table >
					<hr style="border-top: dotted 1px;">
						<tr>Historial de movimientos:<br></tr> 
						<tr><?php MostrarSeguimiento();?></tr>
					<hr style="border-top: dotted 1px;">
				</table> 
				<table id="TableDesarrollo"> 	
					Movimiento nuevo:<br>
					<textarea id="InputMovimientoDescripcion" name="InputMovimientoDescripcion" rows=3 placeholder="Descripcion del movimiento" style="width:800px;"></textarea>
					<tr>
						<td>Tecnicos:<br>
						<?php CargarCheckboxTecnicos("Trabajo") ?></td>
					</tr>
					<table>
						<tr>
							<td>Fecha Trabajo:</td>
							<td><input type="date" id="InputFechaMovimiento" name="InputFechaMovimiento" autocomplete="off"></td>
							<td>Recibe: </td>
							<td><input class="awesomplete" list="ListaContactos" id="InputListaContactoEgreso" placeholder="Contacto"/>(Solo si es despacho de equipo)</td>
						</tr> 
					</table>
				</table> 
				

			<font size=3><hr> </font>


		<form id="FormIdIncidencia" method="post" action="GuardarIncidencia.php" onSubmit="return ValidarDatos();">
			<button type="button" name="Cancelar" onclick = "DestinoCancelar()">Cancelar</button>
			<button type="submit" name="Guardar">Guardar</button>
			<?php AbrirIncidencia(); ?>
		</form>
  	</font>
  </body>

</html>


<script type="text/javascript">
	

   	document.querySelector('#InputListaContactoInicio').addEventListener("awesomplete-selectcomplete", function(e){
			var Contacto = e.text.value;
			var IdSector = $('#ListaContactos [value="' + Contacto + '"]').data('idvinculo');
			var Sector = getValueDeListaGenerica("ListaSectores", IdSector);
			document.getElementById("InputListaSectores").value = Sector;
		}, false);

   	document.querySelector('#InputListaSectores').addEventListener("awesomplete-selectcomplete", function(e){
			var Area = e.text.value;
			var IdArea = $('#ListaSectores [value="' + Area + '"]').data('idvalue');
			var ArrayContactosSector = CrearArrayConValuesDeListaVinculada("ListaContactos", IdArea);
			var txt = ListarArrayNumericamente(ArrayContactosSector);
			var num;
			do {
				Num = prompt('Seleccione el Contacto de ' + Area + ': \n\n' + txt);
				if (Num == null) {return;}
			}
			while (!ValidarIntervaloNumerico (Num, ArrayContactosSector.length));
			document.getElementById("InputListaContactoInicio").value = ArrayContactosSector[Num];
		}, false);

	function setcontroles()
		{
			var id = "";
			var dato ="";
			var aux ="";
			
			//Datos Inicio-------------------------------------------------------------------------------
				aux = document.getElementById("VarFechaInicio").value;
				MostrarFecha("InputFechaInicio", aux);
				id = document.getElementById("VarIdDispositivo").value;
				if (id>0)
					{
						dato = getValueDeListaGenerica("ListaSectores",id);
						document.getElementById("InputListaSectores").value = dato;
					}

				id = document.getElementById("VarIdContactoInicio").value;
				if (id>0)
					{
						dato = getValueDeListaGenerica("ListaContactos",id);
						document.getElementById("InputListaContactoInicio").value = dato;
					}
				
				document.getElementById("InputProblema").value = document.getElementById("varProblema").value;
			//Datos Inidenca--------------------------------------------------------------------------------

				document.getElementById("InputEquipo").value = document.getElementById("VarEquipo").value;

				document.getElementById("InputIp").value = document.getElementById("VarIp").value;
			
			//Datos Movimiento------------------------------------------------------------------------------

				var Equipo = document.getElementById("VarEquipo").value;	
				if (Equipo=="")//tener en cuenta que el equipo se refiere a si venia uno ya guardado, no al que se haya escrito en el imput
					{
						document.getElementById("InputMovimientoDescripcion").value = "Ingreso de equipo.";
					}
				MostrarFechaActual('InputFechaMovimiento');
	
		}
	
	function ValidarDatos()   	
	   	{
		//Valida Datos Iniciantes------------------------------------------------------------------------------------------
			aux = document.getElementById("InputFechaInicio").value;//esta por lo general ya viene
				if (aux=="")
					{
						alert("Falta fecha de ingreso");
						return false;
					}
			
			aux = document.querySelector('#InputListaContactoInicio').value;//esta por lo general ya viene
				if (!ValidarDatoExistente("ListaContactos", aux))
					{return false;}

			aux = document.querySelector('#InputListaSectores').value;//esta por lo general ya viene
				if (!ValidarDatoExistente("ListaSectores", aux))
					{return false;}

			aux = document.getElementById("InputProblema").value;//esta por lo general ya viene
				if (aux=="")
					{
						alert("Falta detallar el problema");
						return false;
					}	

		//Valida Datos Incidencia----------------------------------------------------------------------------------------------

			aux = document.getElementById("InputEquipo").value;
				if (aux=="")
					{
						alert("Falta detalle del Equipo");
						return false;
					}

			aux = document.getElementById("InputIp").value;
				if (aux=="")
					{
						alert("Falta detalle de ip");
						return false;
					}

		//Valida Datos Movimiento--------------------------------------------------------------------------------------------
			ContactoEgreso = document.getElementById("InputListaContactoEgreso").value;	
			Movimiento = document.getElementById("InputMovimientoDescripcion").value;
			FechaMovimiento = document.getElementById("InputFechaMovimiento").value;
			TecnicosMovimiento = GetStringTecnicos('Trabajo');   	
			
			if (ContactoEgreso=="")//osea no es un egreso y el movimiento va si o si
				{
					if(Movimiento=="")
						{
							alert("Falta detallar el movimiento");
							return false;
						}
				}	
			else//Es un egreso
				{
					if(Movimiento=="")
						{
							document.getElementById("InputMovimientoDescripcion").value = "Se entrego el equipo a " + ContactoEgreso + ". " + Movimiento;
						}
				}

			if (FechaMovimiento=="")
				{
					alert("Falta fecha del Trabajo");
					return false;
				}
	
			if (TecnicosMovimiento=="")
				{
					alert("Tildar desarrolladores del trabajo");
					return false;	
				}

			PrepararVariables();	
			return true;
		}

	

	function PrepararVariables() //PRovar las validaciones y Seguir Aca
		{
				var BanTipo = 0;
			//Preparar Variables Iniciantes-------------------------------------------------------------------------------
				var Fecha = document.getElementById("InputFechaInicio").value;
				document.getElementById('VarFechaInicio').value = Fecha;
				var Hora = document.getElementById("VarHoraInicio").value;
				document.getElementById('VarHoraInicio').value = Hora;

				var aux = document.getElementById("InputListaContactoInicio").value;
				document.getElementById('VarIdContactoInicio').value = $('#ListaContactos [value="' + aux + '"]').data('idvalue');

				var aux = document.getElementById("InputListaSectores").value;
				document.getElementById('VarIdDispositivo').value = $('#ListaSectores [value="' + aux + '"]').data('idvalue');

				var aux = document.getElementById("InputProblema").value;
				document.getElementById('varProblema').value = aux;
			//Preparar Variables Incidencia-------------------------------------------------------------------------------
				var aux = document.getElementById("InputEquipo").value;
				if (document.getElementById('VarEquipo').value=="")
					{BanTipo = 1;}
				document.getElementById('VarEquipo').value = aux;

				var aux = document.getElementById("InputIp").value;
				document.getElementById('VarIp').value = aux;
			//Preparar Variables Movimiento	-------------------------------------------------------------------------------

				var aux = document.getElementById("InputMovimientoDescripcion").value;
				document.getElementById('VarMovimientoDescripcion').value = aux;

				aux = GetStringTecnicos('Trabajo');
				document.getElementById('VarTecnicoMovimiento').value = aux;

				var aux = document.getElementById("InputFechaMovimiento").value;
				document.getElementById('VarFechaMovimiento').value = aux;
		
				var aux = document.getElementById("InputListaContactoEgreso").value;
				if (aux == "")
					{
						document.getElementById('VarIdContactoEgreso').value =0;
					}
				else
					{
						document.getElementById('VarIdContactoEgreso').value = $('#ListaContactos [value="' + aux + '"]').data('idvalue');
						BanTipo=2;
					}
				document.getElementById('VarTipoMovimiento').value = BanTipo;
		} 

	function GetStringTecnicos(funcion) {
		var i = 0;
		var id="";
		var aux="";

		$("input:checkbox:checked").each(function(){
			id = $(this).attr("id");
			aux = funcion + $(this).val();
			aux = QuitarEspacios(aux);
			if (id==aux)
				{i++;}

		});

		//alert("termino el primer each " + i);
		var txt = "";
		
		$("input:checkbox:checked").each(function(){
			
			
			
			id = $(this).attr("id");
			aux = funcion + $(this).val();
			aux = QuitarEspacios(aux);
			//alert(id + ' = ' + aux);
			
			if (id==aux)
			{
				txt = txt + $(this).val();
				
				if (i>1)
				{
					txt = txt + ' - ';
					i--;
				}
			}
			

		});
		return txt;}
	


	function DestinoCancelar()

		{

			var IdTipo = document.getElementById("VarTipo").value;
			var IdDerivador = document.getElementById("VarIdDerivador").value;
			location.href="RegistrosPendientes.php?VarTipo=" + IdTipo + "&idusuario="+IdDerivador+"&VarSoloIncidencias=1";
		} 
	
	function EditarMovimiento(clicked_id)
		{
			var VarIdMovimiento = clicked_id;
			var VarIdUsuario = document.getElementById("VarIdDerivador").value;//tiene que pasar como idusuario
			var VarRegistroIdTipo = 0;	//tiene que pasar como	VarTipo
			var VarIdRegistro = document.getElementById("VarIdRegistro").value;	//tiene que pasar VarIdRegistro
			var VarIncidenciaRelacionada = 1;
			location.href="RegistroMovimientoEditar.php?VarRegistroIdTipo=" + VarRegistroIdTipo + "&VarIdUsuario="+VarIdUsuario + "&VarIdRegistro=" + VarIdRegistro + "&VarIdMovimiento=" + VarIdMovimiento + "&VarIncidenciaRelacionada=" + VarIncidenciaRelacionada;
		}

</script>


<?php 
	function AbrirIncidencia() 
		{
			require_once '../Lib/FuncionesComunes.php';
			require_once '../../config/db.php';
			date_default_timezone_set('America/Argentina/Buenos_Aires');

			$data = data_submitted();
			//print_object($data); //esto solo me muestra el id del derivador y el registro

			$VarTipo = $data->VarTipo;
			echo "<input type='hidden' id='VarTipo' name='VarTipo' value='$VarTipo'>";
			//para sds_reg_registro - 7 - usuario_derivacion
			$UserId = $data->idusuario;
			echo "<input type='hidden' id='VarIdDerivador' name='VarIdDerivador' value='$UserId'>";
			$DerivadorValue = getDatoPorId("mds_seg_usuario", "idusuario", "user", $UserId);
			echo "<input type='hidden' id='VarDerivadorValue' name='VarDerivadorValue' value='$DerivadorValue'>";

			//para sds_reg_registro - 1 - idregistro
			$IdRegistro = $data->idregistro;
			echo "<input type='hidden' id='VarIdRegistro' name='VarIdRegistro' value='$IdRegistro'>";
		
			$consulta = "select * from sds_reg_registro Where idregistro = $IdRegistro";
			
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

					$AuxDato = $result["equipo_detalle"];
					//para sds_reg_registro - 13 - equipo_detalle
					echo "<input type='hidden' id='VarEquipo' name='VarEquipo' value='$AuxDato'>";
					
					$AuxDato = $result["ip"];
					//para sds_reg_registro - 14 - ip
					echo "<input type='hidden' id='VarIp' name='VarIp' value='$AuxDato'>";

					$date = date_create($result['fecha_hora']);
					$AuxDato=date_format($date, 'd/m/Y');
					//echo $AuxDato;
					//para sds_reg_registro - 2 - fecha_hora
					echo "<input type='hidden' id='VarFechaInicio' name='VarFechaInicio' value='$AuxDato'><br>";
					$AuxDato=date_format($date, 'H:m');
					//para sds_reg_registro - 2 - fecha_hora
					echo "<input type='hidden' id='VarHoraInicio' name='VarHoraInicio' value='$AuxDato'><br>";
					
					$AuxDato = $result["iddispositivo"];
					//para sds_reg_registro - 15 - iddispositivo
					echo "<input type='hidden' id='VarIdDispositivo' name='VarIdDispositivo' value='$AuxDato'><br>";
					$AuxDato = getDatoPorId("mds_org_dispositivo", "iddispositivo", "descripcion", $AuxDato);
					echo "<input type='hidden' id='VarValueDispositivo' name='VarValueDispositivo' value='$AuxDato'><br>";

					$AuxDato = $result["usuario_solicitante"];
					//para sds_reg_registro - 4 - usuario_solicitante
					echo "<input type='hidden' id='VarIdContactoInicio' name='VarIdContactoInicio' value='$AuxDato'>";
					$AuxDato = GetContactoValue($AuxDato);
					echo "<input type='hidden' id='VarValueContactoInicio' name='VarValueContactoInicio' value='$AuxDato'><br>";
					
					$AuxDato = $result["problema"];
					//para sds_reg_registro - 5 - problema
					echo "<input type='hidden' id='varProblema' name='varProblema' value='$AuxDato'>";

					$AuxDato = $result["registro_abierto"];
					//para sds_reg_registro - 8 - registro_abierto
					echo "<input type='hidden' id='VarRegistroAbierto' name='VarRegistroAbierto' value='$AuxDato'>";

					$AuxDato = $result["fecha_solucion"];
					if($AuxDato=="")
						{
							$AuxDato=GetFechaActual();
						}
					//para sds_reg_registro - 12 - fecha_solucion
					$Fecha = GetFechaActual();
					echo "<input type='hidden' id='VarFechaSolucion' name='VarFechaSolucion' value='$Fecha'>";
					echo "<input type='hidden' id='VarIdContactoEgreso' name='VarIdContactoEgreso' value=''>";
					echo "<input type='hidden' id='VarTecnicoMovimiento' name='VarTecnicoMovimiento' value=''>";
					echo "<input type='hidden' id='VarMovimientoDescripcion' name='VarMovimientoDescripcion' value=''>";
					echo "<input type='hidden' id='VarFechaMovimiento' name='VarFechaMovimiento' value='$Fecha'>";
					echo "<input type='hidden' id='VarTipoMovimiento' name='VarTipoMovimiento' value=''>";

				}	
			
			$dbh->Cerrar();
			$dbh = NULL;
			//echo"<br><br>Termino de abrir la incidencia<br><br>";
		}

	function GenerarListadoContactos() 
		{
			require_once '../Lib/FuncionesComunes.php';
			require_once '../../config/db.php';
			//$Consulta = "Select idcontacto, concat(apellido,', ',nombre) as solicitante, iddispositivo from mds_org_contacto where activo=1 order by apellido, nombre";
			$Consulta = "Select idcontacto, concat(apellido,', ',nombre) as solicitante, iddispositivo 
						from mds_org_contacto inner join sds_com_persona on mds_org_contacto.idpersona = sds_com_persona.idpersona
						where activo=1 order by apellido, nombre";
			GenerarListadoGenerico($Consulta, "idcontacto", "solicitante", "iddispositivo");	
		}

	function GenerarListadoSectores() 
		{	
			require_once '../Lib/FuncionesComunes.php';
			require_once '../../config/db.php';
			//$Consulta = "Select * From mds_org_dispositivo Where activo = 1 order by descripcion";
			$Consulta =  "Select mds_org_dispositivo.iddispositivo as iddispositivo,  concat(mds_org_dispositivo.descripcion,' - ',mds_org_organismo.descripcion) as descripcion, mds_org_dispositivo.idorganismo as idorganismo From mds_org_dispositivo inner join mds_org_organismo on mds_org_dispositivo.idorganismo = mds_org_organismo.idorganismo Where mds_org_dispositivo.activo = 1 order by descripcion";
			GenerarListadoGenerico($Consulta, "iddispositivo", "descripcion", "idorganismo");	
		}


	
	function MostrarSeguimiento()
		{
			require_once '../Lib/FuncionesComunes.php';
			require_once '../../config/db.php';

			$data = data_submitted();
			//print_object($data);
			$IdRegistro = $data->idregistro;
			MostrarMovimientos($IdRegistro,1,1); 
		}
?>