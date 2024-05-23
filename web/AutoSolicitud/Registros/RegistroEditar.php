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
		<div id='DivFormPrincipal'>
			<form id="FormIdRegistroEditar" method="post" action="GuardarRegistro.php" onSubmit="return ValidarDatos();">
				<?php MostrarTituloTipoRegistro(); ?>

				<hr>
				
				<datalist id="ListaUsuarios"> <?php GenerarListadoUsuarios() ?> </datalist>
				<datalist id="ListaSectores"> <?php GenerarListadoSectores() ?> </datalist>
				<datalist id="ListaTiposRegistro"> <?php GenerarListadoTiposRegistro() ?> </datalist>
				<datalist id="ListaOrganismos"> <?php GenerarListadoOrganismos() ?> </datalist>
				<datalist id="ListaEdificios"> <?php GenerarListadoEdificios() ?> </datalist>
				<datalist id="ListaGeneros"> <?php GenerarListadoGeneros() ?> </datalist>
				<datalist id="ListaNacionalidades"> <?php GenerarListadoNacionalidades() ?> </datalist>

				<table>
					<tr>
						<td>Usuario:</td>
						<td id = "TdContenedorDeDatosDeContactos">
								<input class="awesomplete" list="ListaUsuarios" id="InputListaUsuarios" autocomplete ="on" placeholder="Usuario" style="width:470px;"/>	
								<?php AltaUsuarios(); ?>
						</td>
						<td><pre>   Fecha:</pre></td>
						<td><input type="text" id="FechaActual" name="FechaActual" readonly="true"></td>	
					</tr>
					<tr>
						<td>Sector:</td>
						<td id = "TdContenedorDeDispositivos">
							<input class="awesomplete" list="ListaSectores" id="InputListaSectores" placeholder="Sector" style="width:470px;"/>
						</td>
						<td><pre>   Hora:</pre></td>
						<td><input type="text" id="HoraActual" name="HoraActual" readonly="true"></td>			
					</tr>
				</table>

				Tipo de Registro:
				<!-- <input class="awesomplete" list="ListaTiposRegistro" id="InputListaTiposRegistro" placeholder="Tipo Registro"><br> -->
				<input list="ListaTiposRegistro" id="InputListaTiposRegistro" placeholder="Tipo Registro"><br>


				<br>Problema Reportado:<br>
				<textarea id="InputProblema" name="InputProblema" rows=3 placeholder="Describa el problema"></textarea>
				<br>

					<div id='ControlesDeMovimientos' >
						
						<hr>
						Movimientos:
						<?php 
							include_once('../Lib/FuncionesComunes.php');
				
							$data = data_submitted();
							//print_object($data);
				
							if(isset($data->VarIdRegistro))
								{
									MostrarMovimientos($data->VarIdRegistro,1,3);
								}
						?>


					</div>

				<hr>
				Tecnicos Asignados:
				<?php CargarCheckboxTecnicos("Asignados") ?>
				<br>
					<div id='ControlesDeSolucion' >
						<br>Movimiento:<br>	
						<textarea id="InputTrabajo" name="InputTrabajo" rows=3 placeholder="Describa el trabajo realizado."></textarea>
						<br>
						Fecha de Movimiento: <input type="text" id="FechaTrabajo" name="FechaTrabajo">
						<input type="checkbox" name="CheckboxFinalizado" id="CheckboxFinalizado">Finalizar
					</div>
				<hr>
				<br>
				
				<button type="submit" name="Guardar">Guardar</button>
				<button type="button" id="Cancelar" onclick = "DestinoCancelar()">Cancelar</button>
				<?php EjecutarOperacion(); ?>
			</form>
		</div>

		<div id='DivAltaContacto'>
			<h1>Alta Contacto</h1>
			<hr>
			

					
						Dni:<br>
						<input type="text" name="inputDniContacto" id="inputDniContacto">
						<button type='button' name='BotonBuscarUsuario' onclick='BuscarPersona();' style='width:150px'>Buscar Datos</button>
						<p id="txt_mensaje">Esperando ingreso de Dni...</p>
			<hr>
			<table>
				<tr>
					<td>
						Nombres:<br>
						<input type="text" name="InputNombreContacto" id="InputNombreContacto" style="width:300px;">
					</td>
					<td>
						Apellido:<br>
						<input type="text" name="InputApellidoContacto" id="InputApellidoContacto" style="width:300px;">
					</td>
				</tr>	
				<tr>
					<td>
						Nacionalidad:<br>
						<input list="ListaNacionalidades" id="InputNacionalidadContacto" placeholder="Nacionalidad"><br>
					</td>
					<td>
						Genero:<br>
						<input list="ListaGeneros" id="InputGeneroContacto" placeholder="Genero"><br>

					</td>
					<td>
						Fecha de Nacimiento:<br>
						<input type="text" name="InputFechaNacimientoContacto" id="InputFechaNacimientoContacto">
					</td>
				</tr>	

				<tr>
					<td>
						Mail:<br>
						<input type="text" name="InputMailContacto" id="InputMailContacto" style="width:200px;">
					</td>
					<td>
						Teléfono:<br>
						<input type="text" name="InputTelefonoContacto" id="InputTelefonoContacto">
					</td>
					<td>
						Legajo:<br>
						<input type="text" name="InputLegajoContacto" id="InputLegajoContacto">
					</td>
				</tr>		
				
			</table>

			
			Dispositivo:<br>
			<div id="DivContenedorDeDispositivos">
				<input class="awesomplete" list="ListaSectores" id="InputListaSectoresContacto" placeholder="Dispositivo" style="width:650px;"/>
				<button type='button' name='BotonAltaDispositivo' onclick='MostrarAltaDispositivo();' style='width:30px'>+</button>
			</div>
			
			<hr>

			<button type="button" id="BotonGuardarContacto" onclick = "ValidarDatosContacto()">Guardar</button>
			<button type="button" id="CancelarAltaContacto" onclick = "OcultarAltaDeContacto()">Cancelar</button>
			<input type='hidden' id='VarHiddenIdContacto' value='0'>
			<input type='hidden' id='VarHiddenIdPersona' value='0'>

		</div>

		<div id='DivAltaDispositivo'>
			<h1>Alta Dispositivo</h1>
			<hr>

			Nuevo Dispositivo:<br>
			<input type="text" name="inputDispositivo" id="inputDispositivo" style='width:650px' placeholder="Defina Nuevo Dispositivo">
			<br><br>
				
			Organismo:<br>
			<input class="awesomplete" list="ListaOrganismos" id="InputListaOrganismos" placeholder="Elija Organismo" style="width:650px;"/>
			<br><br>
				
			Edificio:<br>
			<input class="awesomplete" list="ListaEdificios" id="InputListaEdificios" placeholder="Elija edificio" style="width:650px;"/>
			<br><br>
			<div id="map" style="width:650px;height: 250px;border-style: double;">

			</div>
			<hr>
			<button type="button" id="BotonGuardarDispositivo" onclick = "ValidarDatosDispositivo()">Guardar</button>
			<button type="button" id="CancelarAltaDispositivo" onclick = "OcultarAltaDispositivo()">Cancelar</button>
		</div>
  </body>

</html>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&callback=cargarMapa" async defer></script>

<script type="text/javascript">
	
	document.getElementById("FechaActual").value = getFechaActual();
	document.getElementById("FechaTrabajo").value = getFechaActual();
	document.getElementById("HoraActual").value = getHoraActual();
	
	CargarEventoMostrarSectorDeContacto();
	CargarEventoMostrarContactosDeSector();
	CargarEventoMostrarMapa();
	/* Funciones de Registro ######################################################################################### */
		function CargarEventoMostrarSectorDeContacto()
			{
				document.querySelector('#InputListaUsuarios').addEventListener("awesomplete-selectcomplete", function(e){
						var Usuario = e.text.value;
						var IdSector = $('#ListaUsuarios [value="' + Usuario + '"]').data('idvinculo');
						var Sector = getValueDeListaGenerica("ListaSectores", IdSector);
						document.getElementById("InputListaSectores").value = Sector;
					}, false);
			}

		function CargarEventoMostrarContactosDeSector()
			{
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
			}


		function ValidarDatos()   	
		{ 		


				var usuario = document.querySelector('#InputListaUsuarios').value;
					if (!ValidarDatoExistente("ListaUsuarios", usuario))
						{return false;}

				var area = document.querySelector('#InputListaSectores').value;
					if (!ValidarDatoExistente("ListaSectores", area))
						{return false;}
						
				var tipo = document.querySelector('#InputListaTiposRegistro').value;
					if (!ValidarDatoExistente("ListaTiposRegistro", tipo))
						{return false;}

				var Problema = document.getElementById("InputProblema").value
					if (Problema=="")
						{
							alert("Detalle el Problema");
							return false;
						}

				var StringTecnicos = GetStringTecnicos();
				var Incidencia = document.getElementById("VarIncidencia").value;
				var StringTrabajo = document.getElementById("InputTrabajo").value;
				
					if(StringTecnicos.length==0 && StringTrabajo.length>0)
						{
							if (Incidencia==0)
								{
									alert("Hay una solucion detallada, Asigne un tecnico");
									return false;
								}

						}

				var Operacion = document.getElementById("VarOperacion").value;
					if(StringTecnicos.length==0 && StringTrabajo.length==0 && Operacion == 'Existente')
						{
							if (Incidencia==0)
								{
									alert("No hay una solucion detallada, Asigne un tecnico");
									return false;
								}

						}

					if(StringTecnicos.length>0 && StringTrabajo.length==0 && Operacion == 'Existente' && Incidencia==0)
						{
							document.getElementById("InputTrabajo").value = "Tecnico en derivacion para asistencia";
						}
					
				PrepararVariables();	
				return true;
			}

		function PrepararVariables() 
			{
				var aux = GetStringTecnicos();
				var Incidencia = document.getElementById("VarIncidencia").value;
				$('#InputProblema').prop("disabled", false);

				if (aux.length==0 && Incidencia==1)
					{
						aux = document.getElementById('VarDerivadorValue').value;
					}
				//alert(aux);
				document.getElementById('VarTecnicos').value = aux;

				var aux = document.getElementById("InputListaUsuarios").value;
				document.getElementById('VarIdUsuario').value = $('#ListaUsuarios [value="' + aux + '"]').data('idvalue');

				var aux = document.getElementById("InputListaSectores").value;
				document.getElementById('VarIdDispositivo').value = $('#ListaSectores [value="' + aux + '"]').data('idvalue');

				var aux = document.getElementById("InputListaTiposRegistro").value;
				document.getElementById('VarRegistroIdTipo').value = $('#ListaTiposRegistro [value="' + aux + '"]').data('idvalue');
				
				var aux = document.getElementById("InputTrabajo").value;
				document.getElementById('VarTrabajo').value = aux;

				
				if(document.getElementById("CheckboxFinalizado").checked && Incidencia==0)
					{
						document.getElementById('VarRegistroAbierto').value = 0;
					}
				else
					{
						document.getElementById('VarRegistroAbierto').value = 1;

					}
			}



		function GetStringTecnicos() 
			{
				var i = 0;
				$("input:checkbox:checked").each(function(){
					i++;
					//alert($(this).val());
				});
				var txt = ""
				$("input:checkbox:checked").each(function(){
					txt = txt + $(this).val();
					if (i>1)
					{
						txt = txt + '-';
						i--;
					}
				});
				return txt;
			}
		
		function setcontroles()
			{
				var dato ="";
				OcultarAltaDispositivo();
				OcultarAltaDeContacto();
				
				
				dato = document.getElementById("VarOperacion").value;

					//alert (dato);
				$('#nombre_victima').prop("disabled", true);
				$('#apellido_victima').prop("disabled", true);
				dato = document.getElementById("VarFecha").value;
				
				document.getElementById("FechaActual").value = document.getElementById("VarFecha").value;
				document.getElementById("HoraActual").value = document.getElementById("VarHora").value;

				document.getElementById("InputListaUsuarios").value = document.getElementById("VarValueUsuario").value;
				sector = document.getElementById("VarValueDispositivo").value + ' - ' + document.getElementById("VarValueOrganismo").value;
				document.getElementById("InputListaSectores").value = sector;
				document.getElementById("InputListaTiposRegistro").value = document.getElementById("VarRegistroTipoValue").value;
				document.getElementById("InputProblema").value = document.getElementById("VarProblema").value;
				$('#InputProblema').prop("disabled", true);

				if (dato == 'Nuevo')
					{
						$('#InputProblema').prop("disabled", false);
						document.getElementById("ControlesDeSolucion").style='display:none;';
						document.getElementById("ControlesDeMovimientos").style='display:none;';
						document.getElementById("Cancelar").style='display:none;';
						//document.getElementById("InputListaTiposRegistro").value ="Local";
						return;
					}

			}


		function DestinoCancelar()
			{
				var IdTipo = document.getElementById("VarRegistroIdTipo").value;
				var IdDerivador = document.getElementById("VarIdDerivador").value;
				
				location.href="RegistrosPendientes.php?VarTipo=" + IdTipo + "&idusuario="+IdDerivador;
			}

		function MarcarOtroNuevo()
			{
				document.getElementById("VarOtroNuevo").value = '1';
				//alert("se va a guardar otr nuevo");
				ValidarDatos();
			}
		function MarcarIncidencia()
			{
				document.getElementById("VarIncidencia").value = '1';
				//es un submit asi que va a guardar, con todas las variables existentes, incluidas el id del registro y el usuario logueado
				//y arca con uno para que ya no se vean como registros pendientes.
				//alert (document.getElementById("VarIncidencia").value);
				ValidarDatos();
			}
		function EditarMovimiento(clicked_id)
			{
				var VarIdMovimiento = clicked_id;
				var VarIdUsuario = document.getElementById("VarIdDerivador").value;//tiene que pasar como idusuario
				var VarRegistroIdTipo = document.getElementById("VarRegistroIdTipo").value;	//tiene que pasar como	VarTipo
				var VarIdRegistro = document.getElementById("VarIdRegistro").value;	//tiene que pasar VarIdRegistro
				var VarIncidenciaRelacionada = 0;
				location.href="RegistroMovimientoEditar.php?VarRegistroIdTipo=" + VarRegistroIdTipo + "&VarIdUsuario="+VarIdUsuario + "&VarIdRegistro=" + VarIdRegistro + "&VarIdMovimiento=" + VarIdMovimiento + "&VarIncidenciaRelacionada=" + VarIncidenciaRelacionada;
			}




	
	
	/* Funciones de alta de contacto y persona ################################################################################### */
		function MostrarAltaDeContacto()
			{
				$('#inputDniContacto').keyup(function(e){ValidaringresoDni()});
				$('#DivAltaContacto').show();
				$('#DivFormPrincipal').hide();
 				$("#inputDniContacto").val("");
				 /*
				$("#InputNombreContacto").val("");
				$("#InputApellidoContacto").val("");
				$("#InputMailContacto").val("");
				$("#InputTelefonoContacto").val("");
				$("#InputLegajoContacto").val("");
				$("#InputListaSectoresContacto").val(""); */
				LimpiarControlesDeAltaContactoYAltaPersona();
			}

		function OcultarAltaDeContacto()
			{
				$('#DivAltaContacto').hide();
				$('#DivFormPrincipal').show();
			}
		
		function HabilitarControlesDeAltaContactoYAltaPersona()
			{
				document.getElementById("InputNombreContacto").disabled = false;
				document.getElementById("InputApellidoContacto").disabled = false;
				document.getElementById("InputFechaNacimientoContacto").disabled = false;
				document.getElementById("InputNacionalidadContacto").disabled = false;
				document.getElementById("InputGeneroContacto").disabled = false;
				document.getElementById("InputMailContacto").disabled = false;
				document.getElementById("InputTelefonoContacto").disabled = false;
				document.getElementById("InputLegajoContacto").disabled = false;
			}

		function LimpiarControlesDeAltaContactoYAltaPersona()
			{
				document.getElementById("InputNombreContacto").value = "";
				document.getElementById("InputApellidoContacto").value = "";
				document.getElementById("InputFechaNacimientoContacto").value = "";
				document.getElementById("InputNacionalidadContacto").value = "";
				document.getElementById("InputGeneroContacto").value = "";
				document.getElementById("InputMailContacto").value = "";
				document.getElementById("InputTelefonoContacto").value = "";
				document.getElementById("InputLegajoContacto").value = "";
				document.getElementById("InputListaSectoresContacto").value = "";
			}

		function BloquearControlesDeAltaContacto()
			{
				document.getElementById("InputMailContacto").disabled = true;
				document.getElementById("InputTelefonoContacto").disabled = true;
				document.getElementById("InputLegajoContacto").disabled = true;
			}
		function BloquearControlesDeAltaPersona()
			{
				document.getElementById("InputNombreContacto").disabled = true;
				document.getElementById("InputApellidoContacto").disabled = true;
				document.getElementById("InputFechaNacimientoContacto").disabled = true;
				document.getElementById("InputNacionalidadContacto").disabled = true;
				document.getElementById("InputGeneroContacto").disabled = true;
			}
		
		
		function ValidaringresoDni() 
			{
				var aux = event.which;
				if (aux == 13) //pregunto si fue el enter
				{
					BuscarPersona();
				}
			}
		/* -------------------------------------------------------------------------------------------------------------------- */
		function BuscarPersona()
			{
				$("#VarHiddenIdPersona").val('0');
				var dni_persona = $("#inputDniContacto").val();
				LimpiarControlesDeAltaContactoYAltaPersona();

				if (dni_persona == "") 
					{
						alert("escriba un dni");
						return;
					}	
				$('#txt_mensaje').html("Buscando datos de Persona...");
				$.post("../../consultas/sds_com_persona_get.php", {
					'parametro_documento_persona': dni_persona,
					'parametro_id_persona': 0,
				},
				function(data) {
					id_persona = data['id'];
					if (id_persona > 0) {
						$("#VarHiddenIdPersona").val(id_persona);
						mostrar_datos_persona_db(id_persona);

					} else {
						mostrar_datos_renaper(dni_persona);
					}
				}, "json"
				);

			}

		function mostrar_datos_persona_db(id_persona) 
			{
				$.post("../../consultas/sds_com_persona_get.php", {
					'parametro_documento_persona': 0,
					'parametro_id_persona': id_persona,
					},
					function(data) {
						$("#InputNombreContacto").val(data['nombre']);
						$("#InputApellidoContacto").val(data['apellido']);
						$("#InputFechaNacimientoContacto").val(FormatearFecha(data['fecha_nacimiento']));
						$("#InputNacionalidadContacto").val(data['nacionalidad']);
						$("#InputGeneroContacto").val(data['genero']);
						BloquearControlesDeAltaPersona();
						var mensaje = "Persona ya existente en el sistema";
						BuscarContacto(id_persona,mensaje);		
					}, "json"
				);
			}
		function BuscarContacto(id_persona,mensaje)
			{
				$("#VarHiddenIdContacto").val('0');
				var idcontacto = 0;
	
				$('#txt_mensaje').html("Buscando datos de contacto...");
				$.post("../../consultas/mds_org_contacto_get.php", {
					'parametro_id_persona': id_persona,
					'parametro_id_contacto': 0,
				},
				function(data) {

 					idcontacto = data['idcontacto'];
					//alert(idcontacto);
					if (idcontacto > 0) 
						{
							$("#VarHiddenIdContacto").val(idcontacto);
							mostrar_datos_contacto_db(idcontacto,mensaje);
						} 
					else 
						{
							mensaje = mensaje + ", completar datos del contacto.";
							$('#txt_mensaje').html(mensaje);
						} 
				}, "json"
				);

			}
		function mostrar_datos_contacto_db(idcontacto,mensaje) 
			{
				$.post("../../consultas/mds_org_contacto_get.php", {
					'parametro_id_persona': 0,
					'parametro_id_contacto': idcontacto,
				},
					function(data) {
						$("#InputMailContacto").val(data['mail']);
						$("#InputTelefonoContacto").val(data['telefono']);
						$("#InputLegajoContacto").val(data['legajo']);
						var sector = getValueDeListaGenerica("ListaSectores", data['iddispositivo']);
						$("#InputListaSectoresContacto").val(sector);
						//BloquearControlesDeAltaContacto();
						mensaje = mensaje + ", ya existe el contacto, controles bloquedos, solo puede editarse el sector...";
						$('#txt_mensaje').html(mensaje);
						
					}, "json"
				);
			}

		function mostrar_datos_renaper(dni_campo) 
			{
				$.ajax({
					data: {
						'servicio': 'renaper',
						'auditoria': 'motu',
						'usuario_auditoria': 'motu',
						'filtro': 'documento=' + dni_campo,
						'tipo': 0 //preguntar que es esto
					},

					type: "POST",
					dataType: "json",
					url: "https://mds1.neuquen.gov.ar/IntegracionMDSyT/servicios_integracion",

					success: function(data) {
						var nombre = "";
						var apellido = "";

						$.each(data, function(ind, elem) {
							//nacionalidad = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
							//$("#detalle").val(nacionalidad);//lo plante aca porque era un texto largo
							console.log(ind);
							if (ind == 'records') {
								console.log(elem[0]);
								nombre = elem[0].nombres;
								apellido = elem[0].apellido;
								HabilitarControlesDeAltaContactoYAltaPersona();
								LimpiarControlesDeAltaContactoYAltaPersona();
								$("#VarHiddenIdContacto").val('0');
								$("#VarHiddenIdPersona").val('0');
								$('#txt_mensaje').html("Nueva persona, informacion encontrada en RENAPER, completar datos faltantes para el alta en el sistema...");
							}
						});

						nombre = corregir_palabra(nombre);
						$("#InputNombreContacto").val(ucWords(nombre));
						apellido = corregir_palabra(apellido);
						$("#InputApellidoContacto").val(ucWords(apellido));
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(xhr.status);
						console.log(thrownError);
					}
				});
			}


		




		
		/* -------------------------------------------------------------------------------------------------------------------- */
		function ValidarDatosContacto()
			{

				if ($("#inputDniContacto").val()=="")
					{
						alert('Falta dni');
						return false;
					}

				if ($("#InputNombreContacto").val()=="")
					{
						alert('Falta nombre');
						return false;
					}
				if ($("#InputApellidoContacto").val()=="")
					{
						alert('Falta apellido');
						return false;
					}

				var apellido = ucWords($("#InputApellidoContacto").val());
				var nombre = ucWords($("#InputNombreContacto").val());
				if (VerificarDatoExistente("ListaUsuarios", apellido+', '+nombre))
					{
						alert(apellido+', '+nombre+' ya existe');
						return false;
					}
				
				if ($("#InputFechaNacimientoContacto").val()=="")
					{
						alert('Falta Fecha de Nacimiento');
						return false;
					}

				var aux = document.querySelector('#InputNacionalidadContacto').value;
				if (!ValidarDatoExistente("ListaNacionalidades", aux))
					{return false;}		

				var aux = document.querySelector('#InputGeneroContacto').value;
				if (!ValidarDatoExistente("ListaGeneros", aux))
					{return false;}	

				if ($("#InputMailContacto").val()=="")
					{
						alert('Falta mail');
						return false;
					}
				if ($("#InputTelefonoContacto").val()=="")
					{
						alert('Falta telefono');
						return false;
					}
				if ($("#InputLegajoContacto").val()=="")
					{
						alert('Falta Legajo');
						return false;
					}

				var area = document.querySelector('#InputListaSectoresContacto').value;
					if (!ValidarDatoExistente("ListaSectores", area))
						{return false;}

				id_persona = $("#VarHiddenIdPersona").val();
				
				if (id_persona==0)
					{
						GuardarPersona();
					}
				else
					{
						id_contacto = $("#VarHiddenIdContacto").val();
						if(id_contacto==0)
							{
								GuardarContacto(id_persona);
							}
						else
							{
								ActualizarContacto(id_contacto);
							}
					}
				
			}
		


		
		
		
		function ActualizarContacto(id_contacto)//seguir aca
			{	
				//encapsulo los parametros a guardar.	
				var aux = $('#InputListaSectoresContacto').val()
				var id_dispositivo_contacto = $('#ListaSectores [value="' + aux + '"]').data('idvalue');

				var parametros = {
					"id_contacto": id_contacto,
					"mail_contacto": $('#InputMailContacto').val(),         
					"telefono_contacto": $('#InputTelefonoContacto').val(),
					"legajo_contacto": $('#InputLegajoContacto').val(),
					"id_dispositivo_contacto": id_dispositivo_contacto,
					"id_persona": $('#VarHiddenIdPersona').val(),
					"activo": 1
				};

				$.ajax({
					data: parametros, //datos que se envian a traves de ajax
					url: '../../consultas/mds_org_contacto_update.php', //php que recibe la peticion
					type: 'post', //método de envio
					//beforeSend: function() {},
					success: function(response) { //aca recibe el json del php que guarda o dice si ya existia

						var obj = jQuery.parseJSON(response) //parseo el json

						if (obj.anuncio == 'Actualizado') 
							{
								var descripcion = $('#InputApellidoContacto').val() + ', ' + $('#InputNombreContacto').val();
								$('#InputListaUsuarios').val(descripcion);
								document.getElementById("InputListaSectores").value = aux;
								//listo el pollo vuelvo al formulario principal
								OcultarAltaDeContacto(); 
							}
					}
				});

			}
		

		function GuardarPersona() 
			{
				//encapsulo los parametros a guardar.	
				var nacionalidad = $('#InputNacionalidadContacto').val();
				var genero = $('#InputGeneroContacto').val();
				var parametros = {
					"par_documento": $('#inputDniContacto').val(),
					"par_tipo_documento": 83,
					"par_nombre": $('#InputNombreContacto').val(),
					"par_apellido": $('#InputApellidoContacto').val(),
					"par_nacionalidad": $('#ListaNacionalidades [value="' + nacionalidad + '"]').data('idvalue'),
					"par_genero": $('#ListaGeneros [value="' + genero + '"]').data('idvalue'),
					"par_fecha_nacimiento": $('#InputFechaNacimientoContacto').val(),
					"par_padre": 'null'
				};
				$.ajax({
							data: parametros, //datos que se envian a traves de ajax
							url: '../../consultas/sds_com_persona_alta.php', //php que recibe la peticion
							type: 'post', //método de envio	
							//beforeSend: function() {},
							success: function(response) { //aca recibe un json con el id de la persona guardada
															var obj = jQuery.parseJSON(response); //parseo el json
															//alert('Persona Guardada con id: ' + obj.id);
															GuardarContacto(obj.id);
														}
						});

			}
		function GuardarContacto(id_persona) //modificar aca
			{
				//encapsulo los parametros a guardar.	
				var aux = $('#InputListaSectoresContacto').val()
				var id_dispositivo_contacto = $('#ListaSectores [value="' + aux + '"]').data('idvalue');

				var parametros = {

					"mail_contacto": $('#InputMailContacto').val(),         
					"telefono_contacto": $('#InputTelefonoContacto').val(),
					"legajo_contacto": $('#InputLegajoContacto').val(),
					"id_dispositivo_contacto": id_dispositivo_contacto,
					"id_persona": id_persona,
					"activo": 1
				};

				$.ajax({
					data: parametros, //datos que se envian a traves de ajax
					url: '../../consultas/mds_org_contacto_alta.php', //php que recibe la peticion
					type: 'post', //método de envio
					//beforeSend: function() {},
					success: function(response) { //aca recibe el json del php que guarda o dice si ya existia

						var obj = jQuery.parseJSON(response) //parseo el json

						if (obj.anuncio == 'Guardado') 
							{
								//alert('Contacto Guardado con id: ' + obj.id);
								var descripcion = $('#InputApellidoContacto').val() + ', ' + $('#InputNombreContacto').val();
								//mando los datos a la funcion que actualiza los controles
								ActualizarFormularioConNuevContacto(descripcion,obj.id,id_dispositivo_contacto);
								//listo el pollo vuelvo al formulario principal
								LimpiarControlesDeAltaContactoYAltaPersona();
								OcultarAltaDeContacto(); 
							}
					}
				});

			}
		function ActualizarFormularioConNuevContacto(contacto,id_contacto,id_dispositivo)
			{
				//Creo un option con los datos del nuevo contacto
				var opt= "<OPTION value='" + contacto + "' data-idvalue=" + id_contacto + " data-idvinculo=" + id_dispositivo + "></OPTION>";
								
				//le encajo ese option al datalist
				$('#ListaUsuarios').append(opt);

				//Vacio el td donde estan el listado sin el dato anterior 
				//(por cuestiones esteticas esto implica borrar tambien el boton de alta  que esta dentro del td, por lo que hay que volverlo a cargar )
				$('#TdContenedorDeDatosDeContactos').empty();

				//creo nuevamente el input en una variable y le añado sus atributos
				var input = document.createElement("INPUT");
				input.id = 'InputListaUsuarios';
				input.style.width='470px';

				//creo nuevamente el button de alta en una variable y le añado sus atributos
				var btn = document.createElement("BUTTON");
				btn.type = 'button'; //es importante ponerle el type buton sino asume que es un submit
				btn.setAttribute("name", "BotonAltaUsuario");
				btn.setAttribute('onclick','MostrarAltaDeContacto();');
				btn.style.width='30px';
				btn.innerText='+';

				//guardo el td en una variable para usar appendchild y agregar nuevamente el input nuevo y el boton de alta
				var padre = document.getElementById("TdContenedorDeDatosDeContactos");
				padre.appendChild(input);
				padre.appendChild(btn);

				/* Le encajo el datalist mediante la clase awesomplete al input, 
				lo hago despues del appendchild porque realmente lo que hace es crear una clase con el input adentro
				si se hace antes el input deja de existir porque pasa a ser parte de la clase nueva, la cual no tiene id
				y por lo tanto no se podria hacer el appendchile */
				new Awesomplete(input, {list: document.querySelector("#ListaUsuarios")});	

				//LA siguiente funcion genera para el input nuevo el evento de mostrar el sector del contacto cada vez que se cambia.
				CargarEventoMostrarSectorDeContacto();

				//le encajo al input el nombre nuevo
				$('#InputListaUsuarios').val(contacto);

				//Como en lo anterior encaje a la fuerza, tambien debo encajarle el sector al input de sectores.
				var IdSector = $('#ListaUsuarios [value="' + contacto + '"]').data('idvinculo');
				var Sector = getValueDeListaGenerica("ListaSectores", IdSector);
				document.getElementById("InputListaSectores").value = Sector;

				//aclaracion, encajar viene a ser lo que los profesionales chetos dicen setear...
			}
	
	
	
	
	
	/* Funciones de alta de dispositivo ########################################################################################## */
		function GuardarDispositivo()
			{
				//encapsulo los parametros a guardar.	
				var organismo = $('#InputListaOrganismos').val();
				var edificio = $('#InputListaEdificios').val();
				var parametros = {
					"descripcion": $('#inputDispositivo').val(),
					"idorganismo": $('#ListaOrganismos [value="' + organismo + '"]').data('idvalue'),
					"idcapaitem": $('#ListaEdificios [value="' + edificio + '"]').data('idvalue'),
					"activo": 1
				};

				$.ajax({
					data: parametros, //datos que se envian a traves de ajax
					url: '../../consultas/mds_org_dispositivo_alta.php', //php que recibe la peticion
					type: 'post', //método de envio
					//beforeSend: function() {},
					success: function(response) { //aca recibe el json del php que guarda o dice si ya existia

						var obj = jQuery.parseJSON(response) //parseo el json

						if (obj.anuncio == 'Guardado') 
							{
								//mando los datos a la funcion que actualiza los controles
								ActualizarFormularioConNuevoDispositivo(obj.descripcion,obj.id,obj.idorganismo);
								//listo el pollo vuelvo al formulario principal
								OcultarAltaDispositivo();
							}
					}
				});

				
			}
		function ValidarDatosDispositivo()
			{			
				if ($("#inputDispositivo").val()=="")
					{
						alert('Falta Dispocitivo nuevo');
						return false;
					}

				var dispositivo = ucWords($("#inputDispositivo").val());
				if (VerificarDatoExistente("ListaSectores", dispositivo))
					{
						alert('ya existe ' + dispositivo);
						return false;
					}

				var organismo = document.querySelector('#InputListaOrganismos').value;
					if (!ValidarDatoExistente("ListaOrganismos", organismo))
						{return false;}

				var edificio = document.querySelector('#InputListaEdificios').value;
					if (!ValidarDatoExistente("ListaEdificios", edificio))
						{return false;}
				GuardarDispositivo();
			}
		function OcultarAltaDispositivo()
			{
				$('#DivAltaDispositivo').hide();
				$('#DivAltaContacto').show();
			}
		function MostrarAltaDispositivo()
			{
				$('#DivAltaDispositivo').show();
				$('#DivAltaContacto').hide();
				$("#inputDispositivo").val("");
				$("#InputListaOrganismos").val("");
				$("#InputListaEdificios").val("");
				$("#map").empty();
			}
		
		function CargarEventoMostrarMapa()
			{
				document.querySelector('#InputListaEdificios').addEventListener("awesomplete-selectcomplete", function(e){
				cargarMapa();
						}, false);
			}
		function cargarMapa() 
			{
				
				var edificio = $('#InputListaEdificios').val();
				var idcapaitem = $('#ListaEdificios [value="' + edificio + '"]').data('idvalue')

				if (idcapaitem != '') {
					$.getJSON("../../consultas/str_capa_item.php", {
							'idcapaitem': idcapaitem
						},
						function(data) {
							latitud = data['latitud'];
							longitud = data['longitud'];
							detalle = data['descripcion'];
							
							$("#map").show();
							setMapProperties(latitud,longitud,detalle);
						}
					);
				} else {
					$("#map").html('');
				}
			}

		function setMapProperties(latitud,longitud,detalle) 
			{
				//alert("lat: " + latitud + " Long: " + longitud + " Det: " + detalle)
				var infoWindow = null;
				if (latitud == null) {
					latitud = -38.95167840000001;
				}
				if (longitud == null) {
					longitud = -68.05918880000002;
				}
				if (detalle == null) {
					detalle = "Prueba";
				}
					var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 15,
					center: new google.maps.LatLng(latitud, longitud),
					mapTypeId: 'roadmap'
				});
				if (infoWindow == null) {
					infoWindow = new google.maps.InfoWindow;
				}
				var latLng = new google.maps.LatLng(latitud, longitud);
				var marker = new google.maps.Marker({
					position: latLng,
					map: map,
					title: detalle
				});

				html = "<div>" + detalle + "</div>";

				bindInfoWindow(marker, map, infoWindow, html);

				var marker_item = new Object();
				marker_item.marker = marker;
			}

		function bindInfoWindow(marker, map, infoWindow, html) 
			{
				google.maps.event.addListener(marker, 'click', function() {
					infoWindow.setContent(html);
					infoWindow.open(map, marker);
					/*if (marker.getAnimation() !== null) {
					marker.setAnimation(null);
					} else {
					marker.setAnimation(google.maps.Animation.BOUNCE);
					}*/
				});
			}

		function ActualizarFormularioConNuevoDispositivo(dispositivo,id_dispositivo,id_organismo)
			{
				//Creo un option con los datos del nuevo contacto
				var opt= "<OPTION value='" + dispositivo + "' data-idvalue=" + id_dispositivo + " data-idvinculo=" + id_organismo + "></OPTION>";
								
				//le encajo ese option al datalist
				$('#ListaSectores').append(opt);

				//Vacio los contenedores con los listados

				$('#TdContenedorDeDispositivos').empty();
				$('#DivContenedorDeDispositivos').empty();

				//creo nuevamente los inputs
				var inputFormPrincipal = document.createElement("INPUT");
				inputFormPrincipal.id = 'InputListaSectores';
				inputFormPrincipal.style.width='470px';

				var inputFormAltaContacto = document.createElement("INPUT");
				inputFormAltaContacto.id = 'InputListaSectoresContacto';
				inputFormAltaContacto.style.width='650px';

				//creo nuevamente el button de alta en una variable y le añado sus atributos
				var btn = document.createElement("BUTTON");
				btn.type = 'button'; //es importante ponerle el type buton sino asume que es un submit
				btn.setAttribute("name", "BotonAltaDispositivo");
				btn.setAttribute('onclick','MostrarAltaDispositivo();');
				btn.style.width='30px';
				btn.innerText='+';

				//guardo el td en una variable para usar appendchild y agregar nuevamente el input nuevo y el boton de alta
				var padreFormPrincipal = document.getElementById("TdContenedorDeDispositivos");
				padreFormPrincipal.appendChild(inputFormPrincipal);
				
				var padreFormAltaContacto = document.getElementById("DivContenedorDeDispositivos");
				padreFormAltaContacto.appendChild(inputFormAltaContacto);
				padreFormAltaContacto.appendChild(btn);

				/* Le encajo el datalist mediante la clase awesomplete al input, 
				lo hago despues del appendchild porque realmente lo que hace es crear una clase con el input adentro
				si se hace antes el input deja de existir porque pasa a ser parte de la clase nueva, la cual no tiene id
				y por lo tanto no se podria hacer el appendchile */
				new Awesomplete(inputFormPrincipal, {list: document.querySelector("#ListaSectores")});	
				new Awesomplete(inputFormAltaContacto, {list: document.querySelector("#ListaSectores")});

				//LA siguiente funcion genera para el input nuevo del form principal, el evento de mostrar los contactos del sector cada vez que se cambia.
				CargarEventoMostrarContactosDeSector();

				//le encajo al input el nombre nuevo
				$('#InputListaSectoresContacto').val(dispositivo);

				//aclaracion, encajar viene a ser lo que los profesionales chetos dicen setear...
			}

	/* Funciones Genericas ################################################################################################# */
		function VerificarDatoExistente(DataList, Dato)
			{

				var x = document.getElementById(DataList).options.length;
				var val;
				for(var i=0;i<x;i++)
				{	
					val= document.getElementById(DataList).options[i].value;
					if (Dato == val)
					{
						return true;
					}
				}
				return false;
			}
		function corregir_palabra(palabra)
			{
				palabra = palabra.replace("ï¿½", "É");
				palabra = palabra.replace(/_/g, " ");
				palabra = palabra.replace("É?", "Á");
				palabra = palabra.replace("ï¿½?", "Ñ");
				palabra = palabra.replace("á", "ñ");
				palabra = palabra.replace("??", "ñ");
				
				return palabra;
			}
		
		function ordenarSelect(id_componente) 
			{
				//alta burbuja que encontre en la internet
				var selectToSort = jQuery('#' + id_componente);
				var optionActual = selectToSort.val();
				selectToSort.html(selectToSort.children('option').sort(function(a, b) {
					return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
				})).val(optionActual);
			}
		function FormatearFecha(Fecha) 
				{
					var day = Fecha.substring(8, 10);
					var month = Fecha.substring(5, 7);
					var year = Fecha.substring(0, 4);
					var today = day + "/" + month + "/" + year;
					return today;
				}


</script>



<?php 

	function AltaUsuarios()//Esta funcion define si el usuario tiene acceso a dar alta de usurios mediante la visibilidad el boton BotonAltaUsuario.
		{
			include_once('../Lib/FuncionesComunes.php');
			$data = data_submitted();

			$IdUsuario = $data->idusuario;
			/* ANOTEZE: Estaba trayendo un permiso cualquiera, como hay varios usuarios que tienen ese permiso, hay que preguntar por el del usuario.
			Por ahora dejo esta query que es la que uso también en yii. Despues fijate gastón de ponerle la forma que usas siempre si queres.
			$IdPermiso = getId('mds_seg_permiso', 'idpermiso', 'iditem', 34);
			//$Rol = "Registro Tecnico Carga Troncal";//este es el nombre del rol que define si el uausrio tiene acceso o no. si se cambia en la bd debe cambiarse aca tambien
			$IdRol = getId('mds_seg_permiso', 'idrol', 'idpermiso', $IdPermiso);
			$consulta = "select idusuariorol from mds_seg_usuario_rol Where idusuario = $IdUsuario and idrol = $IdRol";*/
			//Basicamente traigo el rol vinculado al usuario y a ese permiso, el 34. Si trae algo, es porque tiene permiso.
			//Es similar a lo que hiciste vos, pero en una sola consulta.
			$consulta = "select r.* from mds_seg_permiso p, mds_seg_usuario_rol r
			where p.idrol=r.idrol and r.idusuario=$IdUsuario
			and p.iditem = 34";

			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($consulta);
			$result = $dbh->Registro();
			if ($result) 
				{
					echo "<button type='button' name='BotonAltaUsuario' onclick='MostrarAltaDeContacto();' style='width:30px'>+</button>";
				}
			$dbh->Cerrar();
			$dbh = NULL;

		}

	function MostrarTituloTipoRegistro()
		{
			include_once('../Lib/FuncionesComunes.php');
			$data = data_submitted();
			//print_object($data);

			echo"<h1>";
			if(isset($data->VarIdRegistro))
				{
					$TipoValue = getDatoPorId("sds_reg_tipo", "idtipo", "descripcion", $data->VarTipo);
					echo"Registro de tipo $TipoValue";

				}
			else
				{
					echo "Registro nuevo";
				}
			echo"</h1>";

			$UserValue = getDatoPorId("mds_seg_usuario", "idusuario", "user", $data->idusuario);
			echo "<FONT SIZE=1>Editando: $UserValue</font>";
		}

	function EjecutarOperacion()
		{
			include_once('../Lib/FuncionesComunes.php');
					
			$data = data_submitted();
			//print_object($data);

			if(isset($data->VarIdRegistro))
				{
					AbrirRegistro();
				}
			else
				{
					PrepararAltaRegistro();
				}

			$UserId = $data->idusuario;
			echo "<input type='hidden' id='VarIdDerivador' name='VarIdDerivador' value='$UserId'>";
			$DerivadorValue = getDatoPorId("mds_seg_usuario", "idusuario", "user", $UserId);
			echo "<input type='hidden' id='VarDerivadorValue' name='VarDerivadorValue' value='$DerivadorValue'>";
			echo "<input type='hidden' id='VarOtroNuevo' name='VarOtroNuevo' value='0'>";
				
		}

	function PrepararAltaRegistro()
		{
			echo "<button type='submit' name='OtroNuevo' onclick='MarcarOtroNuevo();'>Guardar y Otro Nuevo</button>";
			echo "<input type='hidden' id='VarOperacion' name='VarOperacion' value='Nuevo'>";
			echo "<input type='hidden' id='VarTecnicos' name='VarTecnicos'>";
			echo "<input type='hidden' id='VarIdUsuario' name='VarIdUsuario'>";
			echo "<input type='hidden' id='VarIdDispositivo' name='VarIdDispositivo'>";
			echo "<input type='hidden' id='VarIdOrganismo' name='VarIdOrganismo'>";
			echo "<input type='hidden' id='VarTrabajo' name='VarTrabajo' value=''>";
			echo "<input type='hidden' id='VarAutorizado' name='VarAutorizado' value='0'>";
			echo "<input type='hidden' id='VarRegistroAbierto' name='VarRegistroAbierto' value='1'>";
			echo "<input type='hidden' id='VarIncidencia' name='VarIncidencia' value='0'>";		
			echo "<input type='hidden' id='VarRegistroIdTipo' name='VarRegistroIdTipo' value='1'>";
			echo "<input type='hidden' id='VarRegistroTipoValue' name='VarRegistroTipoValue' value='Local'>";
		}

	function AbrirRegistro()
		{
			require_once '../../config/db.php';
			include_once('../Lib/FuncionesComunes.php');
			$data = data_submitted();
			//print_object($data);
			$IdRegistro = $data->VarIdRegistro;

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
					
					echo "<button type='submit' name='Incidencia' onclick='MarcarIncidencia();'>Incidencia</button>";
					echo "<input type='hidden' id='VarIncidencia' name='VarIncidencia' value='0'>";	

					echo "<input type='hidden' id='VarOperacion' name='VarOperacion' value='Existente'>";

					echo "<br><input type='hidden' id='VarIdRegistro' name='VarIdRegistro' value='$IdRegistro'><br>";

					$date = date_create($result['fecha_hora']);
					$AuxDato=date_format($date, 'd/m/Y');
					echo "<input type='hidden' id='VarFecha' name='VarFecha' value='$AuxDato'><br>";

					$AuxDato=date_format($date, 'H:m');
					echo "<input type='hidden' id='VarHora' name='VarHora' value='$AuxDato'><br>";

					//$AuxDato = $result["Tecnico"]; //ya no hace falta porque esto se ve desde los movimientos
					echo "<input type='hidden' id='VarTecnicos' name='VarTecnicos' value='$AuxDato'><br>";//la variable se crea igual porque va a contener el movimiento a guardar

					$AuxDato = $result["usuario_solicitante"];
					echo "<input type='hidden' id='VarIdUsuario' name='VarIdUsuario' value='$AuxDato'><br>";

					$AuxDato = GetContactoValue($AuxDato);
					echo "<input type='hidden' id='VarValueUsuario' name='VarValueUsuario' value='$AuxDato'><br>";

					$AuxDato = $result["iddispositivo"];
					echo "<input type='hidden' id='VarIdDispositivo' name='VarIdDispositivo' value='$AuxDato'><br>";

					$AuxDato = getDatoPorId("mds_org_dispositivo", "iddispositivo", "descripcion", $AuxDato);
					echo "<input type='hidden' id='VarValueDispositivo' name='VarValueDispositivo' value='$AuxDato'><br>";

					$AuxDato = $result["idorganismo"];
					echo "<input type='hidden' id='VarIdOrganismo' name='VarIdOrganismo' value='$AuxDato'><br>";

					$AuxDato = getDatoPorId("mds_org_organismo", "idorganismo", "descripcion", $AuxDato);
					echo "<input type='hidden' id='VarValueOrganismo' name='VarValueOrganismo' value='$AuxDato'><br>";
					//echo "<br><br>VarValueOrganismo: $AuxDato<br><br>";

					$AuxDato = $result["problema"];
					echo "<input type='hidden' id='VarProblema' name='VarProblema' value='$AuxDato'><br>";

					//$AuxDato = $result["Trabajo"];//ya no hace falta porque esto se ve desde los movimientos
					echo "<input type='hidden' id='VarTrabajo' name='VarTrabajo' value=''><br>";//la variable se crea igual porque va a contener el movimiento a guardar

					//echo "<input type='hidden' id='VarAutorizado' name='VarAutorizado' value='0'><br>"; esto ya no se usa

					$AuxDato = $result["registro_abierto"];
					echo "<input type='hidden' id='VarRegistroAbierto' name='VarRegistroAbierto' value='$AuxDato'><br>";
					//echo "<br><br>VarRegistroAbierto: $AuxDato<br><br>";

					$AuxDato = $result["idtipo"];
					//echo "<br><br>VarRegistroIdTipo: $AuxDato<br><br>";
					echo "<input type='hidden' id='VarRegistroIdTipo' name='VarRegistroIdTipo' value='$AuxDato'><br>";

					$AuxDato = getDatoPorId("sds_reg_tipo", "idtipo", "descripcion", $AuxDato);
					echo "<input type='hidden' id='VarRegistroTipoValue' name='VarRegistroTipoValue' value='$AuxDato'>";

				}	
			
			$dbh->Cerrar();
			$dbh = NULL;
		}
	


	function GenerarListadoUsuarios() 
		{
			include_once('../Lib/FuncionesComunes.php');
			/*la consulta pide a os usuarios vinculados a la tabla de contacto y de ahi solo saca el id del organismo, no hay nesesidad de ecastrarle la tabla de organismo pues solo nesesito el id para despues trabajarlos desde java script*/
			//$Consulta = "SELECT * FROM mds_seg_usuario INNER JOIN mds_org_contacto on mds_seg_usuario.idcontacto = mds_org_contacto.idcontacto WHERE mds_seg_usuario.activo = 1 order by user";	
			//$Consulta = "Select idcontacto, concat(apellido,', ',nombre) as solicitante, iddispositivo from mds_org_contacto where activo=1 order by apellido, nombre";
			$Consulta = "Select idcontacto, concat(sds_com_persona.apellido,', ',sds_com_persona.nombre) as solicitante, iddispositivo 
			from mds_org_contacto inner join sds_com_persona on mds_org_contacto.idpersona = sds_com_persona.idpersona
			where activo=1 order by sds_com_persona.apellido, sds_com_persona.nombre";
			GenerarListadoGenerico($Consulta, "idcontacto", "solicitante", "iddispositivo");	
		}

	function GenerarListadoSectores() 
		{	
			include_once('../Lib/FuncionesComunes.php');
			//$Consulta = "Select * From mds_org_dispositivo Where activo = 1 order by descripcion";
			//$Consulta ="Select mds_org_dispositivo.iddispositivo, concat(mds_org_dispositivo.descripcion,' - ',mds_org_organismo.descripcion) as descripcion, mds_org_dispositivo.idorganismo From mds_org_dispositivo inner join mds_org_organismo on mds_org_dispositivo.idorganismo= mds_org_organismo.idorganismo Where mds_org_dispositivo.activo = 1 order by mds_org_dispositivo.descripcion, mds_org_organismo.descripcion";
			$Consulta =  "Select mds_org_dispositivo.iddispositivo as iddispositivo,  concat(mds_org_dispositivo.descripcion,' - ',mds_org_organismo.descripcion) as descripcion, mds_org_dispositivo.idorganismo as idorganismo From mds_org_dispositivo inner join mds_org_organismo on mds_org_dispositivo.idorganismo = mds_org_organismo.idorganismo Where mds_org_dispositivo.activo = 1 order by descripcion";
			GenerarListadoGenerico($Consulta, "iddispositivo", "descripcion", "idorganismo");	
		}
	function GenerarListadoTiposRegistro() 
		{	
			include_once('../Lib/FuncionesComunes.php');
			$Consulta = "Select * From sds_reg_tipo Where activo = 1 order by descripcion";
			GenerarListadoGenerico($Consulta, "idtipo", "descripcion", "idtipo");	
		}

	function GenerarListadoGeneros() 
		{	
			include_once('../Lib/FuncionesComunes.php');
			$Consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = 13 and activo = 1 order by descripcion";
			GenerarListadoGenerico($Consulta, "idconfiguracion", "descripcion", "idconfiguracion");	
		}
	function GenerarListadoNacionalidades() 
		{	
			include_once('../Lib/FuncionesComunes.php');
			$Consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = 12 and activo = 1";
			GenerarListadoGenerico($Consulta, "idconfiguracion", "descripcion", "idconfiguracion");	
		}
	
	function GenerarListadoOrganismos() 
		{	
			include_once('../Lib/FuncionesComunes.php');
			$Consulta = "Select * From mds_org_organismo Where activo = 1 order by descripcion";
			GenerarListadoGenerico($Consulta, "idorganismo", "descripcion", "idorganismo");	
		}

	function GenerarListadoEdificios() 
		{	
			include_once('../Lib/FuncionesComunes.php');
			$Consulta = "Select * From sds_gis_capa_item Where activo = 1 order by descripcion";
			GenerarListadoGenerico($Consulta, "idcapaitem", "descripcion", "idcapaitem");	
		}
	
	
?>

