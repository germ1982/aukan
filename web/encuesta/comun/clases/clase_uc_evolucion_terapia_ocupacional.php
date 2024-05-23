<?
      class clase_uc_evolucion_terapia_ocupacional       
      {
	  var $id = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $fecha = '';
          var $idosocial = '';
          var $actividad_laboral = '';
          var $factores_riesgo_ergonomicos = '';
          var $riesgo_ergonomicos_detalle = '';
          var $requiere_adaptaciones_ergonomicas = '';
          var $adaptaciones_ergonomicas_detalle = '';
          var $recomendacion_al_alta = '';
          //var $fecha_carga = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_uc_evolucion_terapia_ocupacional($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM uc_evolucion_terapia_ocupacional WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->actividad_laboral=$arreglo['actividad_laboral'];
      	     $this->factores_riesgo_ergonomicos=$arreglo['factores_riesgo_ergonomicos'];
      	     $this->riesgo_ergonomicos_detalle=$arreglo['riesgo_ergonomicos_detalle'];
      	     $this->requiere_adaptaciones_ergonomicas=$arreglo['requiere_adaptaciones_ergonomicas'];
      	     $this->adaptaciones_ergonomicas_detalle=$arreglo['adaptaciones_ergonomicas_detalle'];
      	     $this->recomendacion_al_alta=$arreglo['recomendacion_al_alta'];
      	     //$this->fecha_carga=$arreglo['fecha_carga'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO uc_evolucion_terapia_ocupacional(idprofesional,idpaciente,fecha,idosocial,actividad_laboral,factores_riesgo_ergonomicos,riesgo_ergonomicos_detalle,requiere_adaptaciones_ergonomicas,adaptaciones_ergonomicas_detalle,recomendacion_al_alta) VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->idosocial."','".$this->actividad_laboral."','".$this->factores_riesgo_ergonomicos."','".$this->riesgo_ergonomicos_detalle."','".$this->requiere_adaptaciones_ergonomicas."','".$this->adaptaciones_ergonomicas_detalle."','".$this->recomendacion_al_alta."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE uc_evolucion_terapia_ocupacional SET idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',idosocial='".$this->idosocial."',actividad_laboral='".$this->actividad_laboral."',factores_riesgo_ergonomicos='".$this->factores_riesgo_ergonomicos."',riesgo_ergonomicos_detalle='".$this->riesgo_ergonomicos_detalle."',requiere_adaptaciones_ergonomicas='".$this->requiere_adaptaciones_ergonomicas."',adaptaciones_ergonomicas_detalle='".$this->adaptaciones_ergonomicas_detalle."',recomendacion_al_alta='".$this->recomendacion_al_alta."' WHERE id='".$this->id."'"))
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
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function actividad_laboral()
          {
               return $this->actividad_laboral;
          }
          function factores_riesgo_ergonomicos()
          {
               return $this->factores_riesgo_ergonomicos;
          }
          function riesgo_ergonomicos_detalle()
          {
               return $this->riesgo_ergonomicos_detalle;
          }
          function requiere_adaptaciones_ergonomicas()
          {
               return $this->requiere_adaptaciones_ergonomicas;
          }
          function adaptaciones_ergonomicas_detalle()
          {
               return $this->adaptaciones_ergonomicas_detalle;
          }
          function recomendacion_al_alta()
          {
               return $this->recomendacion_al_alta;
          }
          function fecha_carga()
          {
               return $this->fecha_carga;
          }
          
          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function actividad_laboral_asigna($campo)
          {
               $this->actividad_laboral=$campo;
               
          }
          function factores_riesgo_ergonomicos_asigna($campo)
          {
               $this->factores_riesgo_ergonomicos=$campo;
               
          }
          function riesgo_ergonomicos_detalle_asigna($campo)
          {
               $this->riesgo_ergonomicos_detalle=$campo;
               
          }
          function requiere_adaptaciones_ergonomicas_asigna($campo)
          {
               $this->requiere_adaptaciones_ergonomicas=$campo;
               
          }
          function adaptaciones_ergonomicas_detalle_asigna($campo)
          {
               $this->adaptaciones_ergonomicas_detalle=$campo;
               
          }
          function recomendacion_al_alta_asigna($campo)
          {
               $this->recomendacion_al_alta=$campo;
               
          }
          function fecha_carga_asigna($campo)
          {
               $this->fecha_carga=$campo;
               
          }
          
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM uc_evolucion_terapia_ocupacional WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM uc_evolucion_terapia_ocupacional WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
      
}
?>