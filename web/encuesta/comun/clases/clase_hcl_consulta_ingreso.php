<?
      class clase_hcl_consulta_ingreso       
      {
	  var $id = '';
          var $idpaciente = '';
          var $idprofesional = '';
          var $id_hcl_ingreso = '';
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
          var $baja_laboral = '';
          var $fecha_probable_alta = '';
          var $fecha_proxima_revision = '';
          var $hora_proxima_revision = '';
          var $fecha_retorno_trabajo = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_id_hcl_ingreso='';
      	     
      
         function clase_hcl_consulta_ingreso($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_consulta_ingreso WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->id_hcl_ingreso=$arreglo['id_hcl_ingreso'];
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
      	     $this->baja_laboral=$arreglo['baja_laboral'];
      	     $this->fecha_probable_alta=$arreglo['fecha_probable_alta'];
      	     $this->fecha_proxima_revision=$arreglo['fecha_proxima_revision'];
      	     $this->hora_proxima_revision=$arreglo['hora_proxima_revision'];
      	     $this->fecha_retorno_trabajo=$arreglo['fecha_retorno_trabajo'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_consulta_ingreso(idpaciente,idprofesional,id_hcl_ingreso,tipo_contingencia,fecha_accidente,hora_accidente,fecha_inicio_inasistencia,hora_inicio_inasistencia,fecha_primera_atencion,hora_primera_atencion,motivo_consulta,diagnostico,indicaciones,baja_laboral,fecha_probable_alta,fecha_proxima_revision,hora_proxima_revision,fecha_retorno_trabajo) VALUES('".$this->idpaciente."','".$this->idprofesional."','".$this->id_hcl_ingreso."','".$this->tipo_contingencia."','".$this->fecha_accidente."','".$this->hora_accidente."','".$this->fecha_inicio_inasistencia."','".$this->hora_inicio_inasistencia."','".$this->fecha_primera_atencion."','".$this->hora_primera_atencion."','".$this->motivo_consulta."','".$this->diagnostico."','".$this->indicaciones."','".$this->baja_laboral."','".$this->fecha_probable_alta."','".$this->fecha_proxima_revision."','".$this->hora_proxima_revision."','".$this->fecha_retorno_trabajo."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_consulta_ingreso SET idpaciente='".$this->idpaciente."',idprofesional='".$this->idprofesional."',id_hcl_ingreso='".$this->id_hcl_ingreso."',tipo_contingencia='".$this->tipo_contingencia."',fecha_accidente='".$this->fecha_accidente."',hora_accidente='".$this->hora_accidente."',fecha_inicio_inasistencia='".$this->fecha_inicio_inasistencia."',hora_inicio_inasistencia='".$this->hora_inicio_inasistencia."',fecha_primera_atencion='".$this->fecha_primera_atencion."',hora_primera_atencion='".$this->hora_primera_atencion."',motivo_consulta='".$this->motivo_consulta."',diagnostico='".$this->diagnostico."',indicaciones='".$this->indicaciones."',baja_laboral='".$this->baja_laboral."',fecha_probable_alta='".$this->fecha_probable_alta."',fecha_proxima_revision='".$this->fecha_proxima_revision."',hora_proxima_revision='".$this->hora_proxima_revision."',fecha_retorno_trabajo='".$this->fecha_retorno_trabajo."' WHERE id='".$this->id."'"))
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
          function id_hcl_ingreso()
          {
               return $this->id_hcl_ingreso;
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
          function baja_laboral()
          {
               return $this->baja_laboral;
          }
          function fecha_probable_alta()
          {
               return $this->fecha_probable_alta;
          }
          function fecha_proxima_revision()
          {
               return $this->fecha_proxima_revision;
          }
          function hora_proxima_revision()
          {
               return $this->hora_proxima_revision;
          }
          function fecha_retorno_trabajo()
          {
               return $this->fecha_retorno_trabajo;
          }
          
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_id_hcl_ingreso()
             {
                 return $this->arreglo_foraneo_id_hcl_ingreso;
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
          function id_hcl_ingreso_asigna($campo)
          {
               $this->id_hcl_ingreso=$campo;
               
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
          function baja_laboral_asigna($campo)
          {
               $this->baja_laboral=$campo;
               
          }
          function fecha_probable_alta_asigna($campo)
          {
               $this->fecha_probable_alta=$campo;
               
          }
          function fecha_proxima_revision_asigna($campo)
          {
               $this->fecha_proxima_revision=$campo;
               
          }
          function hora_proxima_revision_asigna($campo)
          {
               $this->hora_proxima_revision=$campo;
               
          }
          function fecha_retorno_trabajo_asigna($campo)
          {
               $this->fecha_retorno_trabajo=$campo;
               
          }
          
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_consulta_ingreso WHERE idpaciente=$idpaciente");				
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
				$bd->select("SELECT * FROM hcl_consulta_ingreso WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_id_hcl_ingreso($id_hcl_ingreso)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_consulta_ingreso WHERE id_hcl_ingreso=$id_hcl_ingreso");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_hcl_ingreso = $pro;		                              		
			}
			
      
}
?>