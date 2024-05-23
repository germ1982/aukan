<?
      class clase_hcl_consulta_egreso       
      {
	  var $id = '';
          var $idpaciente = '';
          var $idprofesional = '';
          var $id_hcl_egreso = '';
          var $tipo_contingencia = '';
          var $fecha_accidente = '';
          var $hora_accidente = '';
          var $fecha_inicio_inasistencia = '';
          var $hora_inicio_inasistencia = '';
          var $fecha_primera_atencion = '';
          var $hora_primera_atencion = '';
          var $motivo_consulta = '';
          var $diagnostico = '';
          var $indicaciones = '';
          var $constancia_alta_medica = '';
          var $am_tratamiento_pendiente = '';
          var $am_odontologia = '';
          var $am_dermatologia = '';
          var $am_psicoterapia = '';
          var $am_psicoterapia_descripcion = '';
          var $am_fecha_proxima_revision = '';
          var $am_hora_proxima_revision = '';
          var $am_recalificacion_profesional = '';
          var $am_fecha_retorno_trabajo = '';
          var $am_hora_retorno_trabajo = '';
          var $am_fecha_fin_tratamiento = '';
          var $am_hora_fin_tratamiento = '';
          var $am_cese_alta_medica = '';
          var $am_cese_rechazo = '';
          var $am_cese_muerte = '';
          var $am_cese_fin_tratamiento = '';
          var $am_cese_derivacion = '';
          var $am_cese_tipo_derivacion = '';
          var $am_cese_afeccion_inculpable = '';
          var $am_cese_afeccion_inculpable_descripcion = '';
          var $am_secuelas_incapacitantes = '';
          var $am_prestaciones_mantenimiento = '';
          var $constancia_fin_tratamiento = '';
          var $ft_fecha_fin_tratamiento = '';
          var $ft_hora_fin_tratamiento = '';
          var $ft_secuelas_incapacitantes = '';
          var $ft_recalificacion_profesional = '';
          var $ft_prestaciones_mantenimiento = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_id_hcl_egreso='';
      	     
      
         function clase_hcl_consulta_egreso($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_consulta_egreso WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->id_hcl_egreso=$arreglo['id_hcl_egreso'];
      	     $this->tipo_contingencia=$arreglo['tipo_contingencia'];
      	     $this->fecha_accidente=$arreglo['fecha_accidente'];
      	     $this->hora_accidente=$arreglo['hora_accidente'];
      	     $this->fecha_inicio_inasistencia=$arreglo['fecha_inicio_inasistencia'];
      	     $this->hora_inicio_inasistencia=$arreglo['hora_inicio_inasistencia'];
      	     $this->fecha_primera_atencion=$arreglo['fecha_primera_atencion'];
      	     $this->hora_primera_atencion=$arreglo['hora_primera_atencion'];
      	     $this->motivo_consulta=$arreglo['motivo_consulta'];
      	     $this->diagnostico=$arreglo['diagnostico'];
      	     $this->indicaciones=$arreglo['indicaciones'];
      	     $this->constancia_alta_medica=$arreglo['constancia_alta_medica'];
      	     $this->am_tratamiento_pendiente=$arreglo['am_tratamiento_pendiente'];
      	     $this->am_odontologia=$arreglo['am_odontologia'];
      	     $this->am_dermatologia=$arreglo['am_dermatologia'];
      	     $this->am_psicoterapia=$arreglo['am_psicoterapia'];
      	     $this->am_psicoterapia_descripcion=$arreglo['am_psicoterapia_descripcion'];
      	     $this->am_fecha_proxima_revision=$arreglo['am_fecha_proxima_revision'];
      	     $this->am_hora_proxima_revision=$arreglo['am_hora_proxima_revision'];
      	     $this->am_recalificacion_profesional=$arreglo['am_recalificacion_profesional'];
      	     $this->am_fecha_retorno_trabajo=$arreglo['am_fecha_retorno_trabajo'];
      	     $this->am_hora_retorno_trabajo=$arreglo['am_hora_retorno_trabajo'];
      	     $this->am_fecha_fin_tratamiento=$arreglo['am_fecha_fin_tratamiento'];
      	     $this->am_hora_fin_tratamiento=$arreglo['am_hora_fin_tratamiento'];
      	     $this->am_cese_alta_medica=$arreglo['am_cese_alta_medica'];
      	     $this->am_cese_rechazo=$arreglo['am_cese_rechazo'];
      	     $this->am_cese_muerte=$arreglo['am_cese_muerte'];
      	     $this->am_cese_fin_tratamiento=$arreglo['am_cese_fin_tratamiento'];
      	     $this->am_cese_derivacion=$arreglo['am_cese_derivacion'];
      	     $this->am_cese_tipo_derivacion=$arreglo['am_cese_tipo_derivacion'];
      	     $this->am_cese_afeccion_inculpable=$arreglo['am_cese_afeccion_inculpable'];
      	     $this->am_cese_afeccion_inculpable_descripcion=$arreglo['am_cese_afeccion_inculpable_descripcion'];
      	     $this->am_secuelas_incapacitantes=$arreglo['am_secuelas_incapacitantes'];
      	     $this->am_prestaciones_mantenimiento=$arreglo['am_prestaciones_mantenimiento'];
      	     $this->constancia_fin_tratamiento=$arreglo['constancia_fin_tratamiento'];
      	     $this->ft_fecha_fin_tratamiento=$arreglo['ft_fecha_fin_tratamiento'];
      	     $this->ft_hora_fin_tratamiento=$arreglo['ft_hora_fin_tratamiento'];
      	     $this->ft_secuelas_incapacitantes=$arreglo['ft_secuelas_incapacitantes'];
      	     $this->ft_recalificacion_profesional=$arreglo['ft_recalificacion_profesional'];
      	     $this->ft_prestaciones_mantenimiento=$arreglo['ft_prestaciones_mantenimiento'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_consulta_egreso(idpaciente,idprofesional,id_hcl_egreso,tipo_contingencia,fecha_accidente,hora_accidente,fecha_inicio_inasistencia,hora_inicio_inasistencia,fecha_primera_atencion,hora_primera_atencion,motivo_consulta,diagnostico,indicaciones,constancia_alta_medica,am_tratamiento_pendiente,am_odontologia,am_dermatologia,am_psicoterapia,am_psicoterapia_descripcion,am_fecha_proxima_revision,am_hora_proxima_revision,am_recalificacion_profesional,am_fecha_retorno_trabajo,am_hora_retorno_trabajo,am_fecha_fin_tratamiento,am_hora_fin_tratamiento,am_cese_alta_medica,am_cese_rechazo,am_cese_muerte,am_cese_fin_tratamiento,am_cese_derivacion,am_cese_tipo_derivacion,am_cese_afeccion_inculpable,am_cese_afeccion_inculpable_descripcion,am_secuelas_incapacitantes,am_prestaciones_mantenimiento,constancia_fin_tratamiento,ft_fecha_fin_tratamiento,ft_hora_fin_tratamiento,ft_secuelas_incapacitantes,ft_recalificacion_profesional,ft_prestaciones_mantenimiento) VALUES('".$this->idpaciente."','".$this->idprofesional."','".$this->id_hcl_egreso."','".$this->tipo_contingencia."','".$this->fecha_accidente."','".$this->hora_accidente."','".$this->fecha_inicio_inasistencia."','".$this->hora_inicio_inasistencia."','".$this->fecha_primera_atencion."','".$this->hora_primera_atencion."','".$this->motivo_consulta."','".$this->diagnostico."','".$this->indicaciones."','".$this->constancia_alta_medica."','".$this->am_tratamiento_pendiente."','".$this->am_odontologia."','".$this->am_dermatologia."','".$this->am_psicoterapia."','".$this->am_psicoterapia_descripcion."','".$this->am_fecha_proxima_revision."','".$this->am_hora_proxima_revision."','".$this->am_recalificacion_profesional."','".$this->am_fecha_retorno_trabajo."','".$this->am_hora_retorno_trabajo."','".$this->am_fecha_fin_tratamiento."','".$this->am_hora_fin_tratamiento."','".$this->am_cese_alta_medica."','".$this->am_cese_rechazo."','".$this->am_cese_muerte."','".$this->am_cese_fin_tratamiento."','".$this->am_cese_derivacion."','".$this->am_cese_tipo_derivacion."','".$this->am_cese_afeccion_inculpable."','".$this->am_cese_afeccion_inculpable_descripcion."','".$this->am_secuelas_incapacitantes."','".$this->am_prestaciones_mantenimiento."','".$this->constancia_fin_tratamiento."','".$this->ft_fecha_fin_tratamiento."','".$this->ft_hora_fin_tratamiento."','".$this->ft_secuelas_incapacitantes."','".$this->ft_recalificacion_profesional."','".$this->ft_prestaciones_mantenimiento."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_consulta_egreso SET idpaciente='".$this->idpaciente."',idprofesional='".$this->idprofesional."',id_hcl_egreso='".$this->id_hcl_egreso."',tipo_contingencia='".$this->tipo_contingencia."',fecha_accidente='".$this->fecha_accidente."',hora_accidente='".$this->hora_accidente."',fecha_inicio_inasistencia='".$this->fecha_inicio_inasistencia."',hora_inicio_inasistencia='".$this->hora_inicio_inasistencia."',fecha_primera_atencion='".$this->fecha_primera_atencion."',hora_primera_atencion='".$this->hora_primera_atencion."',motivo_consulta='".$this->motivo_consulta."',diagnostico='".$this->diagnostico."',indicaciones='".$this->indicaciones."',constancia_alta_medica='".$this->constancia_alta_medica."',am_tratamiento_pendiente='".$this->am_tratamiento_pendiente."',am_odontologia='".$this->am_odontologia."',am_dermatologia='".$this->am_dermatologia."',am_psicoterapia='".$this->am_psicoterapia."',am_psicoterapia_descripcion='".$this->am_psicoterapia_descripcion."',am_fecha_proxima_revision='".$this->am_fecha_proxima_revision."',am_hora_proxima_revision='".$this->am_hora_proxima_revision."',am_recalificacion_profesional='".$this->am_recalificacion_profesional."',am_fecha_retorno_trabajo='".$this->am_fecha_retorno_trabajo."',am_hora_retorno_trabajo='".$this->am_hora_retorno_trabajo."',am_fecha_fin_tratamiento='".$this->am_fecha_fin_tratamiento."',am_hora_fin_tratamiento='".$this->am_hora_fin_tratamiento."',am_cese_alta_medica='".$this->am_cese_alta_medica."',am_cese_rechazo='".$this->am_cese_rechazo."',am_cese_muerte='".$this->am_cese_muerte."',am_cese_fin_tratamiento='".$this->am_cese_fin_tratamiento."',am_cese_derivacion='".$this->am_cese_derivacion."',am_cese_tipo_derivacion='".$this->am_cese_tipo_derivacion."',am_cese_afeccion_inculpable='".$this->am_cese_afeccion_inculpable."',am_cese_afeccion_inculpable_descripcion='".$this->am_cese_afeccion_inculpable_descripcion."',am_secuelas_incapacitantes='".$this->am_secuelas_incapacitantes."',am_prestaciones_mantenimiento='".$this->am_prestaciones_mantenimiento."',constancia_fin_tratamiento='".$this->constancia_fin_tratamiento."',ft_fecha_fin_tratamiento='".$this->ft_fecha_fin_tratamiento."',ft_hora_fin_tratamiento='".$this->ft_hora_fin_tratamiento."',ft_secuelas_incapacitantes='".$this->ft_secuelas_incapacitantes."',ft_recalificacion_profesional='".$this->ft_recalificacion_profesional."',ft_prestaciones_mantenimiento='".$this->ft_prestaciones_mantenimiento."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function id()
          {
               return $this->id;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function id_hcl_egreso()
          {
               return $this->id_hcl_egreso;
          }
          function tipo_contingencia()
          {
               return $this->tipo_contingencia;
          }
          function fecha_accidente()
          {
               return $this->fecha_accidente;
          }
          function hora_accidente()
          {
               return $this->hora_accidente;
          }
          function fecha_inicio_inasistencia()
          {
               return $this->fecha_inicio_inasistencia;
          }
          function hora_inicio_inasistencia()
          {
               return $this->hora_inicio_inasistencia;
          }
          function fecha_primera_atencion()
          {
               return $this->fecha_primera_atencion;
          }
          function hora_primera_atencion()
          {
               return $this->hora_primera_atencion;
          }
          function motivo_consulta()
          {
               return $this->motivo_consulta;
          }
          function diagnostico()
          {
               return $this->diagnostico;
          }
          function indicaciones()
          {
               return $this->indicaciones;
          }
          function constancia_alta_medica()
          {
               return $this->constancia_alta_medica;
          }
          function am_tratamiento_pendiente()
          {
               return $this->am_tratamiento_pendiente;
          }
          function am_odontologia()
          {
               return $this->am_odontologia;
          }
          function am_dermatologia()
          {
               return $this->am_dermatologia;
          }
          function am_psicoterapia()
          {
               return $this->am_psicoterapia;
          }
          function am_psicoterapia_descripcion()
          {
               return $this->am_psicoterapia_descripcion;
          }
          function am_fecha_proxima_revision()
          {
               return $this->am_fecha_proxima_revision;
          }
          function am_hora_proxima_revision()
          {
               return $this->am_hora_proxima_revision;
          }
          function am_recalificacion_profesional()
          {
               return $this->am_recalificacion_profesional;
          }
          function am_fecha_retorno_trabajo()
          {
               return $this->am_fecha_retorno_trabajo;
          }
          function am_hora_retorno_trabajo()
          {
               return $this->am_hora_retorno_trabajo;
          }
          function am_fecha_fin_tratamiento()
          {
               return $this->am_fecha_fin_tratamiento;
          }
          function am_hora_fin_tratamiento()
          {
               return $this->am_hora_fin_tratamiento;
          }
          function am_cese_alta_medica()
          {
               return $this->am_cese_alta_medica;
          }
          function am_cese_rechazo()
          {
               return $this->am_cese_rechazo;
          }
          function am_cese_muerte()
          {
               return $this->am_cese_muerte;
          }
          function am_cese_fin_tratamiento()
          {
               return $this->am_cese_fin_tratamiento;
          }
          function am_cese_derivacion()
          {
               return $this->am_cese_derivacion;
          }
          function am_cese_tipo_derivacion()
          {
               return $this->am_cese_tipo_derivacion;
          }
          function am_cese_afeccion_inculpable()
          {
               return $this->am_cese_afeccion_inculpable;
          }
          function am_cese_afeccion_inculpable_descripcion()
          {
               return $this->am_cese_afeccion_inculpable_descripcion;
          }
          function am_secuelas_incapacitantes()
          {
               return $this->am_secuelas_incapacitantes;
          }
          function am_prestaciones_mantenimiento()
          {
               return $this->am_prestaciones_mantenimiento;
          }
          function constancia_fin_tratamiento()
          {
               return $this->constancia_fin_tratamiento;
          }
          function ft_fecha_fin_tratamiento()
          {
               return $this->ft_fecha_fin_tratamiento;
          }
          function ft_hora_fin_tratamiento()
          {
               return $this->ft_hora_fin_tratamiento;
          }
          function ft_secuelas_incapacitantes()
          {
               return $this->ft_secuelas_incapacitantes;
          }
          function ft_recalificacion_profesional()
          {
               return $this->ft_recalificacion_profesional;
          }
          function ft_prestaciones_mantenimiento()
          {
               return $this->ft_prestaciones_mantenimiento;
          }
          
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_id_hcl_egreso()
             {
                 return $this->arreglo_foraneo_id_hcl_egreso;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function id_hcl_egreso_asigna($campo)
          {
               $this->id_hcl_egreso=$campo;
               
          }
          function tipo_contingencia_asigna($campo)
          {
               $this->tipo_contingencia=$campo;
               
          }
          function fecha_accidente_asigna($campo)
          {
               $this->fecha_accidente=$campo;
               
          }
          function hora_accidente_asigna($campo)
          {
               $this->hora_accidente=$campo;
               
          }
          function fecha_inicio_inasistencia_asigna($campo)
          {
               $this->fecha_inicio_inasistencia=$campo;
               
          }
          function hora_inicio_inasistencia_asigna($campo)
          {
               $this->hora_inicio_inasistencia=$campo;
               
          }
          function fecha_primera_atencion_asigna($campo)
          {
               $this->fecha_primera_atencion=$campo;
               
          }
          function hora_primera_atencion_asigna($campo)
          {
               $this->hora_primera_atencion=$campo;
               
          }
          function motivo_consulta_asigna($campo)
          {
               $this->motivo_consulta=$campo;
               
          }
          function diagnostico_asigna($campo)
          {
               $this->diagnostico=$campo;
               
          }
          function indicaciones_asigna($campo)
          {
               $this->indicaciones=$campo;
               
          }
          function constancia_alta_medica_asigna($campo)
          {
               $this->constancia_alta_medica=$campo;
               
          }
          function am_tratamiento_pendiente_asigna($campo)
          {
               $this->am_tratamiento_pendiente=$campo;
               
          }
          function am_odontologia_asigna($campo)
          {
               $this->am_odontologia=$campo;
               
          }
          function am_dermatologia_asigna($campo)
          {
               $this->am_dermatologia=$campo;
               
          }
          function am_psicoterapia_asigna($campo)
          {
               $this->am_psicoterapia=$campo;
               
          }
          function am_psicoterapia_descripcion_asigna($campo)
          {
               $this->am_psicoterapia_descripcion=$campo;
               
          }
          function am_fecha_proxima_revision_asigna($campo)
          {
               $this->am_fecha_proxima_revision=$campo;
               
          }
          function am_hora_proxima_revision_asigna($campo)
          {
               $this->am_hora_proxima_revision=$campo;
               
          }
          function am_recalificacion_profesional_asigna($campo)
          {
               $this->am_recalificacion_profesional=$campo;
               
          }
          function am_fecha_retorno_trabajo_asigna($campo)
          {
               $this->am_fecha_retorno_trabajo=$campo;
               
          }
          function am_hora_retorno_trabajo_asigna($campo)
          {
               $this->am_hora_retorno_trabajo=$campo;
               
          }
          function am_fecha_fin_tratamiento_asigna($campo)
          {
               $this->am_fecha_fin_tratamiento=$campo;
               
          }
          function am_hora_fin_tratamiento_asigna($campo)
          {
               $this->am_hora_fin_tratamiento=$campo;
               
          }
          function am_cese_alta_medica_asigna($campo)
          {
               $this->am_cese_alta_medica=$campo;
               
          }
          function am_cese_rechazo_asigna($campo)
          {
               $this->am_cese_rechazo=$campo;
               
          }
          function am_cese_muerte_asigna($campo)
          {
               $this->am_cese_muerte=$campo;
               
          }
          function am_cese_fin_tratamiento_asigna($campo)
          {
               $this->am_cese_fin_tratamiento=$campo;
               
          }
          function am_cese_derivacion_asigna($campo)
          {
               $this->am_cese_derivacion=$campo;
               
          }
          function am_cese_tipo_derivacion_asigna($campo)
          {
               $this->am_cese_tipo_derivacion=$campo;
               
          }
          function am_cese_afeccion_inculpable_asigna($campo)
          {
               $this->am_cese_afeccion_inculpable=$campo;
               
          }
          function am_cese_afeccion_inculpable_descripcion_asigna($campo)
          {
               $this->am_cese_afeccion_inculpable_descripcion=$campo;
               
          }
          function am_secuelas_incapacitantes_asigna($campo)
          {
               $this->am_secuelas_incapacitantes=$campo;
               
          }
          function am_prestaciones_mantenimiento_asigna($campo)
          {
               $this->am_prestaciones_mantenimiento=$campo;
               
          }
          function constancia_fin_tratamiento_asigna($campo)
          {
               $this->constancia_fin_tratamiento=$campo;
               
          }
          function ft_fecha_fin_tratamiento_asigna($campo)
          {
               $this->ft_fecha_fin_tratamiento=$campo;
               
          }
          function ft_hora_fin_tratamiento_asigna($campo)
          {
               $this->ft_hora_fin_tratamiento=$campo;
               
          }
          function ft_secuelas_incapacitantes_asigna($campo)
          {
               $this->ft_secuelas_incapacitantes=$campo;
               
          }
          function ft_recalificacion_profesional_asigna($campo)
          {
               $this->ft_recalificacion_profesional=$campo;
               
          }
          function ft_prestaciones_mantenimiento_asigna($campo)
          {
               $this->ft_prestaciones_mantenimiento=$campo;
               
          }
          
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_consulta_egreso WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_consulta_egreso WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_id_hcl_egreso($id_hcl_egreso)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_consulta_egreso WHERE id_hcl_egreso=$id_hcl_egreso");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_hcl_egreso = $pro;		                              		
			}
			
      
}
?>