<?
    function desplegarListarFuncion($c,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
            $bd->set_names();  
        
		if ( $c != "" )
		    $bd->select("SELECT $select FROM $tabla $where $descripcion LIKE '$c%' order by $orden asc LIMIT 0 , 30");
		$contenido = "";
                
		while ($arreglo = $bd->registro())
		{
		    $contenido.= "<span style='$widthSpan'></span><span onClick=\"if ($deboPegar == 1)
										  {										  
										       document.getElementById('$lugarAPegar').value='".$arreglo[$queDeboPegar]."';
										  }
			                              document.getElementById('$input').value='".str_replace('"',"",str_replace("'","",$arreglo[$descripcion]))."';
			                              document.getElementById('$id').value='".$arreglo[$idtabla]."'; 
										  document.getElementById('$listaDesplegable').innerHTML = '';										 
										  
			                              \">".str_replace('"',"",str_replace("'","",$arreglo[$descripcion]))."</span><br>";
		}
		$bd->cerrar();
		return $contenido;
	}
	function desplegarListarFuncionItaliano($c,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
            $bd->set_names();  
        $tesauro = new callItaliano();
        $tesauro->llamarMetodo('obtenerPrompting', array("substring"=>"$c","subsetid"=>'601000999132',"subsetIdInstitucion"=>'22101000999137'));
        $arreglo=$tesauro->salidaPura();        
        $j=count($arreglo);
		for ($i=0;count($arreglo)>=$i;$i++)
		{
			//tenemos que buscar con el metodo obtenerOfertaTexto los datos
		    $contenido.= "<span style='$widthSpan'></span><span onClick=\"										 
										  if ($deboPegar == 1)
										  {										  
										       document.getElementById('$lugarAPegar').value='".$arreglo[$i]."';
										  }
			                              document.getElementById('$input').value='".str_replace('"',"",str_replace("'","",$arreglo[$i]))."';
			                              document.getElementById('$id').value='".$arreglo[$i]."'; 
										  document.getElementById('$listaDesplegable').innerHTML = '';	
			                              \">".$arreglo[$i]."</span><br>";
		}  
		/*if ( $c != "" )
		    $bd->select("SELECT $select FROM $tabla $where $descripcion LIKE '$c%' order by $orden asc LIMIT 0 , 30");
		$contenido = "";
                
		while ($arreglo = $bd->registro())
		{
		    $contenido.= "<span style='$widthSpan'></span><span onClick=\"if ($deboPegar == 1)
										  {										  
										       document.getElementById('$lugarAPegar').value='".$arreglo[$queDeboPegar]."';
										  }
			                              document.getElementById('$input').value='".str_replace('"',"",str_replace("'","",$arreglo[$descripcion]))."';
			                              document.getElementById('$id').value='".$arreglo[$idtabla]."'; 
										  document.getElementById('$listaDesplegable').innerHTML = '';										 
										  
			                              \">".str_replace('"',"",str_replace("'","",$arreglo[$descripcion]))."</span><br>";
		}*/
		$bd->cerrar();
		return $contenido;
	}
	function desplegarListarConFuncionFuncion($c,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
		   $bd->set_names();    
		if ( $c != "" )
		    $bd->select("SELECT $select FROM $tabla $where $descripcion LIKE '$c%' order by $orden asc LIMIT 0 , 30");
		$contenido = "";
		while ($arreglo = $bd->registro())
		{
		    $contenido.= "<span style='$widthSpanDetalle'></span><span onClick=\"if ($deboPegar == 1)
										  {										  
										       document.getElementById('$lugarAPegar').value='".trim(str_replace('"',"",str_replace("'","",$arreglo[$queDeboPegar])))."';
										  }
			                              document.getElementById('$input').value='".trim(str_replace('"',"",str_replace("'","",$arreglo[$descripcion])))."';
			                              document.getElementById('$id').value='".$arreglo[$idtabla]."'; 
										  document.getElementById('$listaDesplegable').innerHTML = '';										 
										  if ($debeColocarFuncion == 1)
										  {
										      xajax_"."$nombreFuncion"."'".$arreglo[$idtablaReal]."','$dondePegoDato','$dondePegoDetalle','$ordenSiguienteAnterior','$widthSpanDatos','$widthSpanDetalle');
										  }
			                              \">".str_replace('"',"",str_replace("'","",$arreglo[$descripcion]))."</span><br>";
		}		
		$bd->cerrar();
		return $contenido;
	}
	function desplegarListaConFuncionYmouseOverFuncion($c,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle,$desplgarSiNo,$funcionDesplegarMouseOver,$divDesplegarMouseOver)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
		
		if ( $c != "" )
		    $bd->select("SELECT $select FROM $tabla $where $descripcion LIKE '$c%' order by $orden asc LIMIT 0 , 30");
		$contenido = "";
		while ($arreglo = $bd->registro())
		{
		    $contenido.= "<span style='$widthSpanDetalle'></span><span onClick=\"if ($deboPegar == 1)
										  {										  
										       document.getElementById('$lugarAPegar').value='".$arreglo[$queDeboPegar]."';
										  }
			                              document.getElementById('$input').value='".trim(str_replace('"',"",str_replace("'","",$arreglo[$descripcion])))."';
			                              document.getElementById('$id').value='".$arreglo[$idtabla]."'; 
										  document.getElementById('$listaDesplegable').innerHTML = '';										 
										  if ($debeColocarFuncion == 1)
										  {
										      xajax_"."$nombreFuncion"."'".$arreglo[$idtablaReal]."','$dondePegoDato','$dondePegoDetalle','$ordenSiguienteAnterior','$widthSpanDatos','$widthSpanDetalle');
										  }
			                              \" if ($desplgarSiNo == 1)
			                              	     onmouseover=\"xajax_"."$funcionDesplegarMouseOver"."'".$arreglo[$idtablaReal]."','$divDesplegarMouseOver');\">".str_replace('"',"",str_replace("'","",$arreglo[$descripcion]))."</span><br>";
		}		
		$bd->cerrar();
		return $contenido;
	}
	function desplegarPacienteConDNIFuncion($c,$contenedor,$input,$idpaciente)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
            $bd->set_names();  
		if ( $c != "" )
		    $bd->select("SELECT idpaciente,nombre,documento FROM pacientes WHERE nombre LIKE '$c%' order by nombre asc LIMIT 0 , 30");
		$contenido = "";
		while ($arreglo = $bd->registro())
		{
		    $contenido.= "<span onClick=\"document.getElementById('$input').value='".$arreglo['nombre']."';
			                              document.getElementById('$idpaciente').value='".$arreglo['idpaciente']."'; 
										  document.getElementById('$contenedor').innerHTML = '';
			                              \">".$arreglo['nombre']." ".$arreglo['documento']."</span><br>";
		}
		$bd->cerrar();
		return $contenido;   
	}	
	function cantidadFilasObraSocialNomenclador($id,$tabla,$idtabla)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
		if ($id != 0)
		    $bd->select("SELECT $idtabla FROM $tabla WHERE $idtabla=$id");
		else
		    $bd->select("SELECT * FROM $tabla ");
        $filas = $bd->numero_filas();
		$bd->cerrar();				
		return $filas;
	}
	function buscarObraSocialFuncion($idpaciente)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
		$bd->select("SELECT idobra_social FROM obra_social WHERE idpaciente=$idpaciente");
		$arreglo = $bd->registro();
		$bd->select("SELECT nombre,idfinanciacion FROM financiacion WHERE idfinanciacion=".$arreglo['idobra_social']);
		$arreglo = $bd->registro();
		$os = array();
		$os[0] = $arreglo['idfinanciacion'];
		$os[1] = $arreglo['nombre'];
		$bd->cerrar();
		return $os;
	}
	function calcularDiasEntreFechaFuncion($fdesde,$fhasta)
	{
		return restarFecha(fechaBase($fdesde),fechaBase($fhasta));
	}
	function verificarUsuarioFuncion($user,$password)
	{
		$bd = new baseDatos();
		$bd->Conectarse();
		if ($bd->select("SELECT * FROM usuarios_sistema WHERE username='$user'"))
		{
			$userDataDB=$bd->registro();
		    $userPasswordDB = $userDataDB['password'];	
		    if ($userPasswordDB == sha1($password))
		    {	
		    	//el usuario 989 es gracialea abany es para cirugias progrmadas
		    	if ($userDataDB['idprof']==1322 || $userDataDB['idprof']==981 || $userDataDB['idprof']==992 || $userDataDB['idprof']==989)
		            return 1;
		        else
		            return 0;
		    }
            else
                return 0;		        
		}
		else
		    return 0;
	}
    function buscarMedicacionCatalogoFuncion($c,$tabla,$idtabla,$descripcion,$idmedicacion,$idpresentacion,$medicacion_sala,$dosis_sala,$unidad_sala,$via_sala,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
    {
        $bd = new baseDatos();
		$bd->Conectarse();
		$bd->set_names();		
		if ( $c != "" )
		    $bd->select("SELECT catalogo.codigo,catalogo.descripcion,catalogo_presentacion.dosis,catalogo_presentacion.concentracion,
	  		            catalogo_presentacion.droga_asociada,catalogo_presentacion.id,catalogo_presentacion.dosis_droga_asociada,
	  		            catalogo_presentacion.concentracion_droga_asociada,catalogo_presentacion.via,catalogo_presentacion.presentacion,catalogo_presentacion.nombre
	  		            FROM catalogo LEFT JOIN catalogo_presentacion ON (catalogo.codigo=catalogo_presentacion.codigo) 
	  		            WHERE (catalogo.descripcion LIKE '%$c%' OR droga_asociada LIKE '%$c%') AND activo=1 LIMIT 0 , 45");
		$contenido = "";
		
		while ($arreglo = $bd->registro())
		{
		    $contenido.= "<span style='$widthSpanDetalle'></span><span onClick=\"
			                              document.getElementById('$medicacion_sala').value='".trim(str_replace('"',"",str_replace("'","",$arreglo['descripcion'].' '.$arreglo['dosis'].' '.$arreglo['droga_asociada'].' '.$arreglo['dosis_droga_asociada'].' '.$arreglo['concentracion_droga_asociada'].' '.$arreglo['via'].' '.$arreglo['presentacion'].' '.$arreglo['nombre'])))."';
			                              document.getElementById('$idmedicacion').value='".$arreglo['codigo']."';
			                              document.getElementById('$idpresentacion').value='".$arreglo['id']."'; 
			                              
			                              document.getElementById('$via_sala').value='".$arreglo['via']."';
			                              
										  document.getElementById('$listaDesplegable').innerHTML = '';										  
			                              \">".str_replace('"',"",str_replace("'","",$arreglo['descripcion'].' '.$arreglo['dosis'].' '.$arreglo['droga_asociada'].' '.$arreglo['dosis_droga_asociada'].' '.$arreglo['concentracion_droga_asociada'].' '.$arreglo['via'].' '.$arreglo['presentacion'].' '.$arreglo['nombre']))."</span><br>";
		}		
		$bd->cerrar();
		return $contenido;	
    }
    function buscarMedicacionCatalogoPrecioKairosFuncion($c,$tabla,$idtabla,$descripcion,$idmedicacion,$idpresentacion,$medicacion_sala,$dosis_sala,$unidad_sala,$via_sala,$precio,$precio_temp,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
    {
        $bd = new baseDatos();
		$bd->Conectarse();
		$bd->set_names();		
		if ( $c != "" )
		    $bd->select("SELECT catalogo.codigo,catalogo.descripcion,catalogo_presentacion.dosis,catalogo_presentacion.concentracion,
	  		            catalogo_presentacion.droga_asociada,catalogo_presentacion.id,catalogo_presentacion.dosis_droga_asociada,
	  		            catalogo_presentacion.concentracion_droga_asociada,catalogo_presentacion.via,catalogo_presentacion.presentacion,catalogo_presentacion.nombre
	  		            FROM catalogo LEFT JOIN catalogo_presentacion ON (catalogo.codigo=catalogo_presentacion.codigo) 
	  		            WHERE catalogo.descripcion LIKE '%$c%' OR droga_asociada LIKE '%$c%' LIMIT 0 , 30");
		$contenido = "";
		
		while ($arreglo = $bd->registro())
		{
			
		    $contenido.= "<span style='$widthSpanDetalle'></span><span 
		                              onClick=\"xajax_buscarPrecioCatalogoKairos('".$arreglo['codigo']."','".$arreglo['id']."','$precio','$precio_temp');
			                              document.getElementById('$medicacion_sala').value='".trim(str_replace('"',"",str_replace("'","",$arreglo['descripcion'].' '.$arreglo['dosis'].' '.$arreglo['droga_asociada'].' '.$arreglo['dosis_droga_asociada'].' '.$arreglo['concentracion_droga_asociada'].' '.$arreglo['via'].' '.$arreglo['presentacion'].' '.$arreglo['nombre'])))."';
			                              document.getElementById('$idmedicacion').value='".$arreglo['codigo']."';
			                              document.getElementById('$idpresentacion').value='".$arreglo['id']."'; 
			                              
			                              document.getElementById('$via_sala').value='".$arreglo['via']."';
			                              
										  document.getElementById('$listaDesplegable').innerHTML = '';										  
			                              \">".str_replace('"',"",str_replace("'","",$arreglo['descripcion'].' '.$arreglo['dosis'].' '.$arreglo['droga_asociada'].' '.$arreglo['dosis_droga_asociada'].' '.$arreglo['concentracion_droga_asociada'].' '.$arreglo['via'].' '.$arreglo['presentacion'].' '.$arreglo['nombre']))."</span><br>";
		}		
		$bd->cerrar();
		return $contenido;	
    }
    function verificarProfesional($id,$idprofesional,$tabla,$idtabla,$buscado)
	{
	    $bd = new baseDatos();
		$bd->Conectarse();
		$bd->select("SELECT $buscado FROM $tabla WHERE $idtabla=$id");
		$arreglo = $bd->registro();
		$bd->cerrar();
		if ($idprofesional == $arreglo[$buscado])
		    return 1;
		else
		    return 0;   
	}
    function guardarProblemaFuncion($id,$idprofesional,$idpaciente,$descriptionid,$estado,$desconoce_fecha,$fecha_problema,$texto_tesauro,$subsetid)
	{
		$bd = new baseDatos();
		$bd->Conectarse();
		$tesauro = new callItaliano();
		//verifico la fecha
		if ($desconoce_fecha == 'true') $fecha_problema='0000-00-00';
		else $fecha_problema = fechaBase($fecha_problema);
		//veo si esta actualizando o insertando
		
		if ( $id == '' || $id == 0 )
		{				
			//busco que ese codigo de motivos_internacion no se repitan
			$bd->select("SELECT id FROM pacientes_problemas WHERE $descriptionid=$codigo AND idpaciente=$idpaciente ");
			$cod = $bd->registro();
			if ($cod['id'] == 0 || $cod['id'] == '')
			{
				
				if ( $bd->select("INSERT INTO pacientes_problemas(fecha,hora,idprofesional,idpaciente,descriptionid,estado,texto_tesauro,subsetid)
		                  values('$fecha_problema','".date('H:i')."',$idprofesional,$idpaciente,$descriptionid,'$estado','$texto_tesauro',$subsetid)") )
				{
					$tesauro->llamarMetodo('informarDescripcionConsumida', array("descId"=>"$descriptionid","subsetIdInstitucion"=>'22101000999137'));
			    	$bd->cerrar();			  
		    		return 1;
				}
				else
				{
			    	$bd->cerrar();
			    	return 0;				 
				}
			}
			else
			    return 2;							
		}
		else
		{
		    if ( $bd->select("UPDATE pacientes_problemas SET idprofesional='$idprofesional',fecha='$fecha_problema',estado='$estado' WHERE id=$id") )
			{
			    $bd->cerrar();
		    	return 1;
			}
			else
			{
			    $bd->cerrar();
			    return 0;				 
            }				
		}
	}
	function grillaProblemasFuncion($idpaciente,$idprofesional,$dirBase,$contenedor)
	{
		$bd = new baseDatos();
		$bd->Conectarse();
		$base = new baseDatos();
		$base->Conectarse();
		$tesauro = new callItaliano();
		$paciente = new clase_pacientes($idpaciente);
		//saco primero la cantidad de dias que pasaron desde el nacimiento		
		$resta_fecha = restarFecha($paciente->fecha_nacimiento(),date('Y-m-d'));
		if ($resta_fecha > 365)
		{
			$age_unidad = 'a';
			$age = Edad(devolverFechaNormal($paciente->fecha_nacimiento()));
		}
		else 
		{
			if ($resta_fecha>30)
			{
				$age_unidad = 'mo';
				$age = (int)($resta_fecha/30);
			}
			else 
			{
				$age_unidad = 'd';
				$age = $resta_fecha;
			}
		}
	//	$dirBase = "../../../";
		$bd->select("SELECT * FROM pacientes_problemas WHERE idpaciente=$idpaciente ORDER BY estado asc");
		$contenido = "<div class='scrollable'><table>";
		while ($arreglo = $bd->registro())
		{			
			$tesauro->llamarMetodo('obtenerClasificador', array("descId"=>$arreglo['descriptionid'],"mapSetId"=>'101045',"subsetIdInstitucion"=>'22101000999137'));
			$iterator = new clase_patron_iterator($tesauro->todos_clasificadores());
			$fila = $iterator->elementoSiguiente();
			$base->select("SELECT nombre FROM profesionales WHERE idprofesional=".$arreglo['idprofesional']);
			$prof = $base->registro();
			$contenido.= "<tr title='Fecha de inicio Problema: ".devolverFechaNormal($arreglo['fecha'])." Profesional: ".utf8_decode($prof['nombre'])."'><td style='width:340px'>
			<a href='".$dirBase."biblioteca/uptodate.php?searchType=HL7&mainSearchCriteria.v.dn=".$arreglo['texto_tesauro']."&assignedAuthorizedPerson.id.root=$idprofesional&representedOrganization.id.root=HMGR227611&mainSearchCriteria.v.cs=2.16.840.1.113883.6.103&mainSearchCriteria.v.c=".$fila['id']."&age.v.u=$age_unidad&age.v.v=$age&administrativeGenderCode.c=".$paciente->sexo()."' target='_blank' 
			 onClick=\"//xajax_filtrarConsultaPorProblemas($idpaciente,'".$arreglo['descriptionid']."','internaciones_consultas_todas');
			//xajax_editarConsulta(0,'evolucion_ingreso','',$idprofesional,$idpaciente,'".date('d/m/Y')."','".date('G:i')."','".$arreglo['descriptionid']."','".$arreglo['texto_tesauro']."')\">".$arreglo['texto_tesauro']."</a></td><td>".$arreglo['estado']."</td>
					      <td><img src=\"".$dirBase."imagenes/dbEdit.png\" style='cursor:pointer' onclick=\"
									  document.getElementById('idproblema').value=".$arreglo['id'].";
									  document.getElementById('descriptionid').value=".$arreglo['descriptionid'].";
									  document.getElementById('problema_buscado').value='".$arreglo['texto_tesauro']."';
									  document.getElementById('fecha_problema').value='".devolverFechaNormal($arreglo['fecha'])."';
									  document.getElementById('desconoce_fecha').checked=";
			                          
			
									  if ($arreglo['fecha'] == '0000-00-00') $contenido .= 'true';
									  else $contenido.='false';
									  $contenido.=";";
									  if ($arreglo['estado'] == 'ACTIVO') $contenido .= "document.getElementById('problema_activo').checked=true;document.getElementById('estado_problema').value='ACTIVO';"; 
									  if ($arreglo['estado'] == 'PASIVO') $contenido .= "document.getElementById('problema_pasivo').checked=true;document.getElementById('estado_problema').value='PASIVO';";
									  if ($arreglo['estado'] == 'RESUELTO') $contenido .= "document.getElementById('problema_resuelto').checked=true;document.getElementById('estado_problema').value='RESUELTO';";
									  if ($arreglo['estado'] == 'PROCEDIMIENTO') $contenido .= "document.getElementById('problema_procedimiento').checked=true;document.getElementById('estado_problema').value='PROCEDIMIENTO';";
									  if ($arreglo['estado'] == 'ANT_FAMILIAR') $contenido .= "document.getElementById('problema_ant_familiar').checked=true;document.getElementById('estado_problema').value='ANT_FAMILIAR';";
									  $contenido.="
									
									  \" alt='Modificar' /></td>
						  <td><img src=\"".$dirBase."imagenes/dbDelete.png\" style=\"cursor:pointer\" 
						  		onclick=\"if(confirm('Esta seguro de querer eliminar este registro?'))
	                                      {
	                                          xajax_borrarProblema(".$arreglo['id'].",$idpaciente,$idprofesional,'$dirBase','$contenedor');
	                                      }\" alt='Borrar' /></td></tr>";
		}
		return $contenido."</table></div>";
	}
	function borrarProblemaFuncion($id)
	{
		$bd = new baseDatos();
		$bd->Conectarse();
		if ($bd->select("DELETE FROM pacientes_problemas WHERE id=$id"))
		{
			$bd->cerrar();
		    return 1;
		}
		else
		{
			$bd->cerrar();
		    return 0;
		}
	}
	function filtrarConsultaPorProblemasFuncion($idpaciente,$descriptionid)
	{
		$bd = new baseDatos();
		$bd->Conectarse();		
		$base = new baseDatos();
		$base->Conectarse();		
		if ($descriptionid == '') $descriptionid = 0;
		$bd->select("SELECT * FROM consulta_ambulatoria WHERE idpaciente=$idpaciente AND descriptionid=$descriptionid ORDER BY fecha DESC");

		while ($arreglo = $bd->registro())
		{			
			$base->select("SELECT nombre FROM profesionales WHERE idprofesional=".$arreglo['idprofesional']);
			$prof = $base->registro();
			$contenido.= "<div class='evolucionSala'>
					              <b>".devolverFechaNormal($arreglo['fecha'])."</b> - <b>".horaRecortada($arreglo['hora'])."</b>
					              <input type='hidden' id='fecha$i' value='$fecha' />
					              <input type='hidden' id='hora$i' value='$hora' />
					              <br />
					              <div class='evolucionTextoSala'>
								  <span><strong>Motivo Consulta: </strong></span>
								  <span>".utf8_encode($arreglo['motivo_consulta'])."</span><br>
						          <span>".$arreglo['consulta']."</span>
						          <div align='right'>
					   		         M&eacute;dico que realiza: <b>".$prof['nombre']."</b>
							         <input type='hidden' id='nombre_medico$i' value='".$arreglo['idprofesional']."' />
						          </div>
					            </div>
					            <div align='right' style='margin-top:4px'>
								   <input class='botonImprimir' style='width:90px' type='button' onClick=\"window.open('imprimirIndicaciones.php?idconsulta=".$arreglo['idconsulta']."')\" value='Imprimir' />
								   <input class='botonDiagnosticos' type='button' onClick=\"xajax_verIndicacion('".$arreglo['idconsulta']."','contieneIndicacion$i')\" value='Ver Indicacion' />
						           <input class='botonEditar' type='button' onClick=\"modificarConsulta('".$arreglo['idconsulta']."','evolucion_ingreso','".$arreglo['idprofesional']."','".$arreglo['idpaciente']."','".devolverFechaNormal($arreglo['fecha'])."','".$arreglo['hora']."','$sid')\" value='Editar' />
					            </div>
								<div id='contieneIndicacion$i'></div>
				               </div>";   
		}
		return $contenido;
	}
?>
