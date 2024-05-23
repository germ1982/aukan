<?
      class clase_event_detalle       
      {
	  var $event_user_id = '';
          var $event_id = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $idprof_admision = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_event_detalle($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM event_detalle WHERE event_id=$id");
      	     $arreglo=$bd->registro();
      	     $this->event_user_id=$arreglo['event_user_id'];
      	     $this->event_id=$arreglo['event_id'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->idprof_admision=$arreglo['idprof_admision'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->event_user_id==0 || $this->event_user_id=='' ) {
      	      if ($bd->select("INSERT INTO event_detalle(event_id,idprofesional,idpaciente,idprof_admision) VALUES('".$this->event_id."','".$this->idprofesional."','".$this->idpaciente."','".$this->idprof_admision."')"))
      	      {
      	          $this->event_user_id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE event_detalle SET event_id='".$this->event_id."',idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',idprof_admision='".$this->idprof_admision."' WHERE event_user_id='".$this->event_user_id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function event_user_id()
          {
               return $this->event_user_id;
          }
          function event_id()
          {
               return $this->event_id;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function idprof_admision()
          {
               return $this->idprof_admision;
          }
          
          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function event_user_id_asigna($campo)
          {
               $this->event_user_id=$campo;
               
          }
          function event_id_asigna($campo)
          {
               $this->event_id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function idprof_admision_asigna($campo)
          {
               $this->idprof_admision=$campo;
               
          }
          
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM event_detalle WHERE idprofesional=$idprofesional");				
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
				$bd->select("SELECT * FROM event_detalle WHERE idpaciente=$idpaciente");				
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