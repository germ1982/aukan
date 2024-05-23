<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   

	<style type="text/css">
		input {
			width: 40px;
			
		}
	</style>
  </head>
  <body>

	<h1>Migracion de Pensiones</h1>

	<hr>

			<button type="button" name="Iniciar" onclick = "get_configuraciones();" >Buscar Ids de Configuraciones</button>

	<hr>

	<table> 
		<tr>
			<td>
				<table> 
					<tr><h4>Tipos de Documento</h4></tr>
					<tr> <td>1. DNI:                    </td> <td><input type="text" id="INPUT_CONFIG_DNI"></td></tr>
					<tr> <td>2. Libreta de Enrolamiento:</td> <td><input type="text" id="INPUT_CONFIG_LE"></td></tr>
					<tr> <td>3. Libreta Cívica:         </td> <td><input type="text" id="INPUT_CONFIG_LC"></td></tr>
					<tr> <td>6. Cedula extranjera:      </td> <td><input type="text" id="INPUT_CONFIG_CE">	</td></tr>
					<tr> <td>7. Cedula de Identidad:    </td> <td><input type="text" id="INPUT_CONFIG_CI">	</td></tr>
				</table> 
			</td>
			<td>
				&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
			<table> 
					<tr><h4>Nacionalidad</h4></tr>
					<tr> <td>1. Argentina:</td> <td><input type="text" id="INPUT_CONFIG_ARGENTINA" >	</td></tr>
					<tr> <td>2. Bolivia:  </td> <td><input type="text" id="INPUT_CONFIG_BOLIVIA" >	</td></tr>
					<tr> <td>3. Brasil:   </td> <td><input type="text" id="INPUT_CONFIG_BRASIL" ></td></tr>
					<tr> <td>4. Chile:    </td> <td><input type="text" id="INPUT_CONFIG_CHILE" ></td></tr>
					<tr> <td>7. Paraguay: </td> <td><input type="text" id="INPUT_CONFIG_PARAGUAY" >	</td></tr>
					<tr> <td>99. Otro:    </td> <td><input type="text" id="INPUT_CONFIG_OTRO" ></td></tr>
				</table>
			</td>
			<td>
				&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
				<table> 
					<tr><h4>Genero</h4></tr>
					<tr> <td>Masculino:</td> <td><input type="text" id="INPUT_CONFIG_M" >	</td></tr>
					<tr> <td>Femenino: </td> <td><input type="text" id="INPUT_CONFIG_F" >	</td></tr>
				</table> 
			</td>
			<td>
				&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
				<table> 
					<tr><h4>Programa</h4></tr>
					<tr> <td>Discapacidad:</td> <td><input type="text" id="INPUT_CONFIG_DISCAPACIDAD" >	</td></tr>
					<tr> <td>Vejez:		  </td> <td><input type="text" id="INPUT_CONFIG_VEJEZ" >	</td></tr>
				</table> 
			</td>
			<td>
				&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
				<table> 
					<tr><h4>Tipo Otorgado</h4></tr>
					<tr> <td>Decreto:</td> <td><input type="text" id="INPUT_DECRETO" >	</td></tr>
					<tr> <td>Res.Min.: </td> <td><input type="text" id="INPUT_RESMIN" >	</td></tr>
					<tr> <td>Tramite: </td> <td><input type="text" id="INPUT_TRAMITE" >	</td></tr>
				</table> 
			</td>
			<td>
				&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>

				<table> 
					<tr><h4>Tipo Baja</h4></tr>
					<tr> <td>Decreto:  </td> <td><input type="text" id="INPUT_CONFIG_TIPO_BAJA_DECRETO" >	</td></tr>
					<tr> <td>Dispocision: </td> <td><input type="text" id="INPUT_CONFIG_TIPO_BAJA_DISPOCISION" >	</td></tr>
					<tr> <td>Res.Min.:  </td> <td><input type="text" id="INPUT_CONFIG_TIPO_BAJA_RESMIN" >	</td></tr>
					<tr> <td>S/decreto: </td> <td><input type="text" id="INPUT_CONFIG_TIPO_BAJA_SDECRETO" >	</td></tr>
					<tr> <td>Tramite:  </td> <td><input type="text" id="INPUT_CONFIG_TIPO_BAJA_TRAMITE" >	</td></tr>
				</table> 
			</td>
			<td>
				&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
			<table> 
				<tr><h4>Estado</h4></tr>

				<tr> <td>Baja:  </td> <td><input type="text" id="INPUT_CONFIG_TIPO_ESTADO_BAJA" >	</td></tr>
				<tr> <td>Baja Cond.(ISSN): </td> <td><input type="text" id="INPUT_CONFIG_TIPO_ESTADO_BAJA_ISSN" >	</td></tr>
				<tr> <td>Baja Interna:  </td> <td><input type="text" id="INPUT_CONFIG_TIPO_ESTADO_BAJA_INTERNA" >	</td></tr>
				<tr> <td>Otorgado: </td> <td><input type="text" id="INPUT_CONFIG_TIPO_ESTADO_OTORGADO" >	</td></tr>
				<tr> <td>Tramite:  </td> <td><input type="text" id="INPUT_CONFIG_TIPO_ESTADO_TRAMITE" >	</td></tr>

			</table> 
			</td>
		</tr>
	</table> 
<hr>
	<table> 
		<tr>
			</td>


			<td>
				<table> 
					<tr><h4>Lugares de Pago</h4></tr>
					<tr> <td>EL CHOCON:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_EL_CHOCON" ></td></tr>
					<tr> <td>AGUADA SAN ROQUE:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_AGUADA_SAN_ROQUE" ></td></tr>
					<tr> <td>ALUMINE:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_ALUMINE" ></td></tr>
					<tr> <td>ANDACOLLO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_ANDACOLLO" ></td></tr>
					<tr> <td>BAJADA DEL AGRIO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_BAJADA_DEL_AGRIO" ></td></tr>
					<tr> <td>BARRANCAS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_BARRANCAS" ></td></tr>
					<tr> <td>BUTA RANQUIL:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_BUTA_RANQUIL" ></td></tr>
					<tr> <td>CENTENARIO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_CENTENARIO" ></td></tr>
					<tr> <td>CHAPUA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_CHAPUA" ></td></tr>
					<tr> <td>CHORRIACA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_CHORRIACA" ></td></tr>
					<tr> <td>CHOS MALAL:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_CHOS_MALAL" ></td></tr>
					<tr> <td>COLIPILLI:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_COLIPILLI" ></td></tr>
					<tr> <td>COVUNCO ABAJO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_COVUNCO_ABAJO" ></td></tr>
					<tr> <td>COYUCO COCHICO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_COYUCO_COCHICO" ></td></tr>
				</table> 
				</td>
			<td>
			&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
				<table> 
				<tr><h4>Lugares de Pago</h4></tr>
					<tr> <td>CUTRAL-CO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_CUTRAL-CO" ></td></tr>
					<tr> <td>EL CHOLAR:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_EL_CHOLAR" ></td></tr>
					<tr> <td>EL HUECU:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_EL_HUECU" ></td></tr>
					<tr> <td>EL SAUCE:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_EL_SAUCE" ></td></tr>
					<tr> <td>HUINGANCO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_HUINGANCO" ></td></tr>
					<tr> <td>JUNIN DE LOS ANDES:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_JUNIN_DE_LOS_ANDES" ></td></tr>
					<tr> <td>LAS COLORADAS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LAS_COLORADAS" ></td></tr>
					<tr> <td>LAS LAJAS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LAS_LAJAS" ></td></tr>
					<tr> <td>LAS OVEJAS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LAS_OVEJAS" ></td></tr>
					<tr> <td>LONCOPUE:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LONCOPUE" ></td></tr>
					<tr> <td>LOS CHIHUIDOS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LOS_CHIHUIDOS" ></td></tr>
					<tr> <td>LOS GUAÑACOS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LOS_GUAÑACOS" ></td></tr>
					<tr> <td>LOS MICHES:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_LOS_MICHES" ></td></tr>
					<tr> <td>MANZANO AMARGO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_MANZANO_AMARGO" ></td></tr>
				</table> 
				</td>
			<td>
			&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
				<table> 
				<tr><h4>Lugares de Pago</h4></tr>
					<tr> <td>MARIANO MORENO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_MARIANO_MORENO" ></td></tr>
					<tr> <td>NEUQUEN - SUBSECRETARIA DE ACCION SOCIAL:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_NEUQUEN-SUBSECRETARIA_DE_ACCION_SOCIAL" ></td></tr>
					<tr> <td>NEUQUEN - SUCURSAL AEROPUERTO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_AEROPUERTO" ></td></tr>
					<tr> <td>NEUQUEN - SUCURSAL ALTA BARDA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_ALTA_BARDA" ></td></tr>
					<tr> <td>NEUQUEN - SUCURSAL BELGRANO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_BELGRANO" ></td></tr>
					<tr> <td>NEUQUEN - SUCURSAL FELIX SAN MARTIN:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_FELIX_SAN_MARTIN" ></td></tr>
					<tr> <td>NEUQUEN - SUCURSAL RIVADAVIA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_RIVADAVIA" ></td></tr>
					<tr> <td>OCTAVIO PICO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_OCTAVIO_PICO" ></td></tr>
					<tr> <td>PASO AGUERRE:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_PASO_AGUERRE" ></td></tr>
					<tr> <td>PICUN LEUFU:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_PICUN_LEUFU" ></td></tr>
					<tr> <td>PIEDRA DEL AGUILA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_PIEDRA_DEL_AGUILA" ></td></tr>
					<tr> <td>PLAZA HUICUL:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_PLAZA_HUICUL" ></td></tr>
					<tr> <td>PLOTTIER:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_PLOTTIER" ></td></tr>
					<tr> <td>RINCON DE LOS SAUCES:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_RINCON_DE_LOS_SAUCES" ></td></tr>
				</table> 
				</td>
			<td>
			&nbsp &nbsp &nbsp &nbsp
			</td>
			<td>
				<table> 
				<tr><h4>Lugares de Pago</h4></tr>
					<tr> <td>SAN MARTIN DE LOS ANDES:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_SAN_MARTIN_DE_LOS_ANDES" ></td></tr>
					<tr> <td>SAN PATRICIO DEL CHAÑAR:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_SAN_PATRICIO_DEL_CHAÑAR" ></td></tr>
					<tr> <td>SANTO TOMAS:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_SANTO_TOMAS" ></td></tr>
					<tr> <td>SAUZAL BONITO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_SAUZAL_BONITO" ></td></tr>
					<tr> <td>SENILLOSA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_SENILLOSA" ></td></tr>
					<tr> <td>TAQUIMILAN:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_TAQUIMILAN" ></td></tr>
					<tr> <td>TRICAO MALAL:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_TRICAO_MALAL" ></td></tr>
					<tr> <td>VARVARCO:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_VARVARCO" ></td></tr>
					<tr> <td>VILLA CARILEUVU:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_VILLA_CARILEUVU" ></td></tr>
					<tr> <td>VILLA DEL NAHUEVE:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_VILLA_DEL_NAHUEVE" ></td></tr>
					<tr> <td>VILLA LA ANGOSTURA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_VILLA_LA_ANGOSTURA" ></td></tr>
					<tr> <td>VILLA TRAFUL:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_VILLA_TRAFUL" ></td></tr>
					<tr> <td>ZAPALA:  </td> <td><input type="text" id="INPUT_LUGAR_PAGO_ZAPALA" ></td></tr>
				</table> 
				</td>

		</tr>
	</table> 
	<hr>
<!-- 		<button type="button" name="Iniciar" onclick = "iniciar_migracion_uno_x_uno(1,1000);" >Iniciar migracion tanda 1</button><br>
		<button type="button" name="Iniciar" onclick = "iniciar_migracion_uno_x_uno(1001,2000);" >Iniciar Migracion tanda 2</button><br>
		<button type="button" name="Iniciar" onclick = "iniciar_migracion_uno_x_uno(2001,3000);" >Iniciar Migracion tanda 3</button><br>
		<button type="button" name="Iniciar" onclick = "iniciar_migracion_uno_x_uno(3001,4000);" >Iniciar Migracion tanda 4</button><br>
		<button type="button" name="Iniciar" onclick = "iniciar_migracion_uno_x_uno(4001,5000);" >Iniciar Migracion tanda 5</button><br>
		<button type="button" name="Iniciar" onclick = "iniciar_migracion_uno_x_uno(5001,5500);" >Iniciar Migracion tanda 6</button><br> -->
	<hr>
		<div id="resultadoBusqueda"></div>	
	<hr>		
  </body>

</html>

<script>


function iniciar_migracion() //modificar aca
			{  
				$.ajax({
					url: 'sds_pen_migracion_02_transicion.php', //php que recibe la peticion
					type: 'post', //método de envio
                    
					success: function(response) { //aca recibe el json del php 
                        //alert(response);
						var obj = jQuery.parseJSON(response) //parseo el json

						$("#resultadoBusqueda").html(obj.log);



					}
				});

			}

function iniciar_migracion_uno_x_uno(inicio,final) //modificar aca
			{
				var i = inicio;
				cont = 0;
				while (i < final) 
					{			
						migracion_uno_x_uno(i);
						i++;
						cont++;
					}
				aux = $("#resultadoBusqueda").html();
				aux  = aux + '<br><br>Total: ' + cont;
				$("#resultadoBusqueda").html(aux);
			}

function migracion_uno_x_uno(idpension) //modificar aca
			{  		
				$.ajax({
					data: {
						'idpension': idpension,
					},
					url: 'sds_pen_migracion_03.php', //php que recibe la peticion
					type: 'post', //método de envio
					
					success: function(response) { //aca recibe el json del php 

						var obj = jQuery.parseJSON(response) //parseo el json
						//alert(obj.log);
						aux = $("#resultadoBusqueda").html();
						aux  = aux + obj.log;
						$("#resultadoBusqueda").html(aux);
					}
				});
			}

function get_configuraciones() //modificar aca
			{  
				$.ajax({
					url: 'sds_pen_migracion_01_configuraciones.php', //php que recibe la peticion
					type: 'post', //método de envio
                    
					success: function(response) { //aca recibe el json del php 
                        //alert(response);
						var obj = jQuery.parseJSON(response) //parseo el json

						$("#INPUT_CONFIG_DNI").val(obj.CONFIG_DNI);
						$("#INPUT_CONFIG_LE").val(obj.CONFIG_LE);
						$("#INPUT_CONFIG_LC").val(obj.CONFIG_LC);
						$("#INPUT_CONFIG_CE").val(obj.CONFIG_CE);
						$("#INPUT_CONFIG_CI").val(obj.CONFIG_CI);

						$("#INPUT_CONFIG_ARGENTINA").val(obj.CONFIG_ARGENTINA);
						$("#INPUT_CONFIG_BOLIVIA").val(obj.CONFIG_BOLIVIA);
						$("#INPUT_CONFIG_BRASIL").val(obj.CONFIG_BRASIL);
						$("#INPUT_CONFIG_CHILE").val(obj.CONFIG_CHILE);
						$("#INPUT_CONFIG_PARAGUAY").val(obj.CONFIG_PARAGUAY);
						$("#INPUT_CONFIG_OTRO").val(obj.CONFIG_OTRO);

						$("#INPUT_CONFIG_M").val(obj.CONFIG_M);
						$("#INPUT_CONFIG_F").val(obj.CONFIG_F);

						$("#INPUT_CONFIG_DISCAPACIDAD").val(obj.CONFIG_DISCAPACIDAD);
						$("#INPUT_CONFIG_VEJEZ").val(obj.CONFIG_VEJEZ);

						$("#INPUT_DECRETO").val(obj.CONFIG_DECRETO);
						$("#INPUT_RESMIN").val(obj.CONFIG_RESMIN);	
						$("#INPUT_TRAMITE").val(obj.CONFIG_TRAMITE);


						$("#INPUT_CONFIG_TIPO_BAJA_DECRETO").val(obj.CONFIG_TIPO_BAJA_DECRETO);
						$("#INPUT_CONFIG_TIPO_BAJA_DISPOCISION").val(obj.CONFIG_TIPO_BAJA_DISPOCISION);
						$("#INPUT_CONFIG_TIPO_BAJA_RESMIN").val(obj.CONFIG_TIPO_BAJA_RESMIN);
						$("#INPUT_CONFIG_TIPO_BAJA_SDECRETO").val(obj.CONFIG_TIPO_BAJA_SDECRETO);	
						$("#INPUT_CONFIG_TIPO_BAJA_TRAMITE").val(obj.CONFIG_TIPO_BAJA_TRAMITE);

						$("#INPUT_CONFIG_TIPO_ESTADO_BAJA").val(obj.CONFIG_TIPO_ESTADO_BAJA);
						$("#INPUT_CONFIG_TIPO_ESTADO_BAJA_ISSN").val(obj.CONFIG_TIPO_ESTADO_BAJA_ISSN);
						$("#INPUT_CONFIG_TIPO_ESTADO_BAJA_INTERNA").val(obj.CONFIG_TIPO_ESTADO_BAJA_INTERNA);
						$("#INPUT_CONFIG_TIPO_ESTADO_OTORGADO").val(obj.CONFIG_TIPO_ESTADO_OTORGADO);
						$("#INPUT_CONFIG_TIPO_ESTADO_TRAMITE").val(obj.CONFIG_TIPO_ESTADO_TRAMITE);

						$("#INPUT_LUGAR_PAGO_EL_CHOCON").val(obj.CONFIG_LUGAR_PAGO_EL_CHOCON);
						$("#INPUT_LUGAR_PAGO_AGUADA_SAN_ROQUE").val(obj.CONFIG_LUGAR_PAGO_AGUADA_SAN_ROQUE);
						$("#INPUT_LUGAR_PAGO_ALUMINE").val(obj.CONFIG_LUGAR_PAGO_ALUMINE);
						$("#INPUT_LUGAR_PAGO_ANDACOLLO").val(obj.CONFIG_LUGAR_PAGO_ANDACOLLO);
						$("#INPUT_LUGAR_PAGO_BAJADA_DEL_AGRIO").val(obj.CONFIG_LUGAR_PAGO_BAJADA_DEL_AGRIO);
						$("#INPUT_LUGAR_PAGO_BARRANCAS").val(obj.CONFIG_LUGAR_PAGO_BARRANCAS);
						$("#INPUT_LUGAR_PAGO_BUTA_RANQUIL").val(obj.CONFIG_LUGAR_PAGO_BUTA_RANQUIL);
						$("#INPUT_LUGAR_PAGO_CENTENARIO").val(obj.CONFIG_LUGAR_PAGO_CENTENARIO);
						$("#INPUT_LUGAR_PAGO_CHAPUA").val(obj.CONFIG_LUGAR_PAGO_CHAPUA);
						$("#INPUT_LUGAR_PAGO_CHORRIACA").val(obj.CONFIG_LUGAR_PAGO_CHORRIACA);
						$("#INPUT_LUGAR_PAGO_CHOS_MALAL").val(obj.CONFIG_LUGAR_PAGO_CHOS_MALAL);
						$("#INPUT_LUGAR_PAGO_COLIPILLI").val(obj.CONFIG_LUGAR_PAGO_COLIPILLI);
						$("#INPUT_LUGAR_PAGO_COVUNCO_ABAJO").val(obj.CONFIG_LUGAR_PAGO_COVUNCO_ABAJO);
						$("#INPUT_LUGAR_PAGO_COYUCO_COCHICO").val(obj.CONFIG_LUGAR_PAGO_COYUCO_COCHICO);
						$("#INPUT_LUGAR_PAGO_CUTRAL-CO").val(obj.CONFIG_LUGAR_PAGO_CUTRAL_CO);
						$("#INPUT_LUGAR_PAGO_EL_CHOLAR").val(obj.CONFIG_LUGAR_PAGO_EL_CHOLAR);
						$("#INPUT_LUGAR_PAGO_EL_HUECU").val(obj.CONFIG_LUGAR_PAGO_EL_HUECU);
						$("#INPUT_LUGAR_PAGO_EL_SAUCE").val(obj.CONFIG_LUGAR_PAGO_EL_SAUCE);
						$("#INPUT_LUGAR_PAGO_HUINGANCO").val(obj.CONFIG_LUGAR_PAGO_HUINGANCO);
						$("#INPUT_LUGAR_PAGO_JUNIN_DE_LOS_ANDES").val(obj.CONFIG_LUGAR_PAGO_JUNIN_DE_LOS_ANDES);
						$("#INPUT_LUGAR_PAGO_LAS_COLORADAS").val(obj.CONFIG_LUGAR_PAGO_LAS_COLORADAS);
						$("#INPUT_LUGAR_PAGO_LAS_LAJAS").val(obj.CONFIG_LUGAR_PAGO_LAS_LAJAS);
						$("#INPUT_LUGAR_PAGO_LAS_OVEJAS").val(obj.CONFIG_LUGAR_PAGO_LAS_OVEJAS);
						$("#INPUT_LUGAR_PAGO_LONCOPUE").val(obj.CONFIG_LUGAR_PAGO_LONCOPUE);
						$("#INPUT_LUGAR_PAGO_LOS_CHIHUIDOS").val(obj.CONFIG_LUGAR_PAGO_LOS_CHIHUIDOS);
						$("#INPUT_LUGAR_PAGO_LOS_GUAÑACOS").val(obj.CONFIG_LUGAR_PAGO_LOS_GUAÑACOS);
						$("#INPUT_LUGAR_PAGO_LOS_MICHES").val(obj.CONFIG_LUGAR_PAGO_LOS_MICHES);
						$("#INPUT_LUGAR_PAGO_MANZANO_AMARGO").val(obj.CONFIG_LUGAR_PAGO_MANZANO_AMARGO);
						$("#INPUT_LUGAR_PAGO_MARIANO_MORENO").val(obj.CONFIG_LUGAR_PAGO_MARIANO_MORENO);
						$("#INPUT_LUGAR_PAGO_NEUQUEN-SUBSECRETARIA_DE_ACCION_SOCIAL").val(obj.CONFIG_LUGAR_PAGO_NEUQUEN_SUBSECRETARIA_DE_ACCION_SOCIAL);
						$("#INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_AEROPUERTO").val(obj.CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_AEROPUERTO);
						$("#INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_ALTA_BARDA").val(obj.CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_ALTA_BARDA);
						$("#INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_BELGRANO").val(obj.CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_BELGRANO);
						$("#INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_FELIX_SAN_MARTIN").val(obj.CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_FELIX_SAN_MARTIN);
						$("#INPUT_LUGAR_PAGO_NEUQUEN-SUCURSAL_RIVADAVIA").val(obj.CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_RIVADAVIA);
						$("#INPUT_LUGAR_PAGO_OCTAVIO_PICO").val(obj.CONFIG_LUGAR_PAGO_OCTAVIO_PICO);
						$("#INPUT_LUGAR_PAGO_PASO_AGUERRE").val(obj.CONFIG_LUGAR_PAGO_PASO_AGUERRE);
						$("#INPUT_LUGAR_PAGO_PICUN_LEUFU").val(obj.CONFIG_LUGAR_PAGO_PICUN_LEUFU);
						$("#INPUT_LUGAR_PAGO_PIEDRA_DEL_AGUILA").val(obj.CONFIG_LUGAR_PAGO_PIEDRA_DEL_AGUILA);
						$("#INPUT_LUGAR_PAGO_PLAZA_HUICUL").val(obj.CONFIG_LUGAR_PAGO_PLAZA_HUICUL);
						$("#INPUT_LUGAR_PAGO_PLOTTIER").val(obj.CONFIG_LUGAR_PAGO_PLOTTIER);
						$("#INPUT_LUGAR_PAGO_RINCON_DE_LOS_SAUCES").val(obj.CONFIG_LUGAR_PAGO_RINCON_DE_LOS_SAUCES);
						$("#INPUT_LUGAR_PAGO_SAN_MARTIN_DE_LOS_ANDES").val(obj.CONFIG_LUGAR_PAGO_SAN_MARTIN_DE_LOS_ANDES);
						$("#INPUT_LUGAR_PAGO_SAN_PATRICIO_DEL_CHAÑAR").val(obj.CONFIG_LUGAR_PAGO_SAN_PATRICIO_DEL_CHAÑAR);
						$("#INPUT_LUGAR_PAGO_SANTO_TOMAS").val(obj.CONFIG_LUGAR_PAGO_SANTO_TOMAS);
						$("#INPUT_LUGAR_PAGO_SAUZAL_BONITO").val(obj.CONFIG_LUGAR_PAGO_SAUZAL_BONITO);
						$("#INPUT_LUGAR_PAGO_SENILLOSA").val(obj.CONFIG_LUGAR_PAGO_SENILLOSA);
						$("#INPUT_LUGAR_PAGO_TAQUIMILAN").val(obj.CONFIG_LUGAR_PAGO_TAQUIMILAN);
						$("#INPUT_LUGAR_PAGO_TRICAO_MALAL").val(obj.CONFIG_LUGAR_PAGO_TRICAO_MALAL);
						$("#INPUT_LUGAR_PAGO_VARVARCO").val(obj.CONFIG_LUGAR_PAGO_VARVARCO);
						$("#INPUT_LUGAR_PAGO_VILLA_CARILEUVU").val(obj.CONFIG_LUGAR_PAGO_VILLA_CARILEUVU);
						$("#INPUT_LUGAR_PAGO_VILLA_DEL_NAHUEVE").val(obj.CONFIG_LUGAR_PAGO_VILLA_DEL_NAHUEVE);
						$("#INPUT_LUGAR_PAGO_VILLA_LA_ANGOSTURA").val(obj.CONFIG_LUGAR_PAGO_VILLA_LA_ANGOSTURA);
						$("#INPUT_LUGAR_PAGO_VILLA_TRAFUL").val(obj.CONFIG_LUGAR_PAGO_VILLA_TRAFUL);
						$("#INPUT_LUGAR_PAGO_ZAPALA").val(obj.CONFIG_LUGAR_PAGO_ZAPALA);


					}
				});

			}

</script>
