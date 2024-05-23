<?
	class clase_tipo_registro 
	{
		var $idprofesional=0;
		var $idpaciente=0;
		var $rep_area_jerarquica_id   ='';
		var $rep_area_jerarquica_descripcion = '';
		var $xml = '';
		
		function clase_tipo_registro($que_es,$id,$idepisodio,$idpaciente)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			
			$this->idpaciente=$idpaciente;
			$this->idepisodio=$idepisodio;
			$epi = new clase_episodio($idepisodio);
			if ($que_es == 'SALA') 
			{
				$sala = new clase_sala($id);			    
				$this->idprofesional =  $sala->idprofesional(); 
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = 'SALA';								
				$this->xml = $sala->armar_xml();				              
			}
			if ($que_es == 'GUARDIA')
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('evoluciones_guardia', 'id', $id);
				$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}
			if ($que_es == 'SEGUIMIENTOESPECIALIDAD')
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('evoluciones_quirurgicas', 'id', $id);
				$evo->asignar_datos($arreglo);
				$evo->titulo_asigna("SEGUIMIENTO POR ESPECIALIDAD");
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}	
			if ($que_es == 'MATERNIDAD')
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('evolucion_maternidad', 'id', $id);
				$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = 'MATERNIDAD';								
				$this->xml = $evo->armar_xml();
			}	
			if ($que_es == 'ESTUDIOS')//es interconsultas
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('interconsultas', 'id', $id);
				$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}	 
			if ($que_es == 'HEMOTERAPIA')//es interconsultas
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('evoluciones_hemoterapia', 'id', $id);
				$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}  
			if ($que_es == 'INGRESO')//es ingreso epispodio
			{
				$evo = new clase_ingreso_unidad('episodios_ingreso','idepisodio',$idepisodio);				
				$motivo_ingreso = new clase_motivos_internacion($epi->motivo_internacion());
				$evo->motivo_ingeso_asigna($motivo_ingreso->nombre());
				//$arreglo = $evo->consultar_evolucion('episodios_ingreso', 'id', $id);
				//$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->unidad();								
				$this->xml = $evo->armar_xml();
			}
		    if ($que_es == 'INGRESOUN')//es ingreso unidad
			{
				$evo = new clase_ingreso_unidad('ingresos_unidades','id',$id);
				$motivo_ingreso = new clase_motivos_internacion($epi->motivo_internacion());
				$evo->motivo_ingeso_asigna($motivo_ingreso->nombre());
				//$arreglo = $evo->consultar_evolucion('episodios_ingreso', 'id', $id);
				//$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->unidad();								
				$this->xml = $evo->armar_xml();
			} 	
			if ($que_es == 'PROBLEMAS')//es problemas
			{
				$evo = new clase_problemas($idpaciente);				
				$this->idprofesional =  0; 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = '';								
				$this->xml = $evo->armar_xml();
			}	
			if ($que_es == 'DERIVAR') 
			{
				$sala = new clase_derivar_pacientes($id);			    
				$this->idprofesional =  $sala->idprofesional(); 
				$sala->asignar_paciente($this->idpaciente);
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $sala->buscar_ubicacion($idepisodio,$id,$sala->fecha());								
				$this->xml = $sala->armar_xml();				              
			}	
			if ($que_es == 'PE')
			{
				$evo = new clase_protocolo_endoscopico($id);				
				//$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				$evo->asignar_paciente($this->idpaciente);
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}
			if ($que_es == 'PQ')
			{
				$evo = new clase_protocolo_quirurgico($id);				
				//$evo->asignar_datos($arreglo);
				$this->idprofesional =  $evo->idprofesional(); 
				$evo->asignar_paciente($this->idpaciente);
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}
			if ($que_es == 'TERAPIA') 
			{
				$terapia = new clase_terapia($id);			    
				$this->idprofesional =  $terapia->idprofesional(); 
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = 'TERAPIA';								
				$this->xml = $terapia->armar_xml();				              
			}
			if ($que_es == 'NEO') 
			{
				$neo = new clase_neonatologia($id);			    
				$this->idprofesional =  $neo->idprofesional(); 
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = 'NEONATOLOGIA';								
				$this->xml = $neo->armar_xml();				              
			}
			if ($que_es == 'ENFERMERIA')
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('evolucion_enfermeria', ' 	idevolucion_enfermeria', $id);
				$evo->asignar_datos($arreglo);
				$evo->asignar_texto($arreglo['evolucion']);
				$evo->titulo_asigna("EVOLUCION DE ENFERMERIA");
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}
			if ($que_es == 'KINE')
			{
				$evo = new clase_evolucion();
				$arreglo = $evo->consultar_evolucion('evolucion_kinesioterapia', ' 	id', $id);
				$evo->asignar_datos($arreglo);
				//$evo->asignar_texto($arreglo['evolucion']);
				$this->idprofesional =  $evo->idprofesional(); 
				//para buscar el area tenemos que ver en que cama estaba en esa fecha y hora				
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = $evo->buscar_ubicacion($idepisodio,$id,$evo->fecha());								
				$this->xml = $evo->armar_xml();
			}
			if ($que_es == 'APACHE') 
			{
				$apache = new clase_apache($id);			    
				$this->idprofesional =  $apache->idprofesional(); 
				$this->rep_area_jerarquica_id = 1;
				$this->rep_area_jerarquica_descripcion = 'TERAPIA';								
				$this->xml = $apache->armar_xml();				              
			}
			                          		
		}
		function idprofesional()
		{			
			return $this->idprofesional;
		}	
		function rep_area_jerarquica_id()
		{			
			return $this->rep_area_jerarquica_id;
		}
		function rep_area_jerarquica_descripcion()
		{			
			return $this->rep_area_jerarquica_descripcion;
		}		
		function rep_xml_text()
		{			
			return $this->xml;
		}		
	}
?>