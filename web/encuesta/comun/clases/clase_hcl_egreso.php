<?
      class clase_hcl_egreso       
      {
	  var $id = '';
          var $idpaciente = '';
          var $numero_siniestro = '';
          var $idosocial = '';
          var $id_empleador = '';
          var $id_prestador = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idosocial='';
      	     var $arreglo_foraneo_id_empleador='';
      	     var $arreglo_foraneo_id_prestador='';
      	     
      
         function clase_hcl_egreso($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_egreso WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->numero_siniestro=$arreglo['numero_siniestro'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->id_empleador=$arreglo['id_empleador'];
      	     $this->id_prestador=$arreglo['id_prestador'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_egreso(idpaciente,numero_siniestro,idosocial,id_empleador,id_prestador) VALUES('".$this->idpaciente."','".$this->numero_siniestro."','".$this->idosocial."','".$this->id_empleador."','".$this->id_prestador."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_egreso SET idpaciente='".$this->idpaciente."',numero_siniestro='".$this->numero_siniestro."',idosocial='".$this->idosocial."',id_empleador='".$this->id_empleador."',id_prestador='".$this->id_prestador."' WHERE id='".$this->id."'"))
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
          function numero_siniestro()
          {
               return $this->numero_siniestro;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function id_empleador()
          {
               return $this->id_empleador;
          }
          function id_prestador()
          {
               return $this->id_prestador;
          }
          
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idosocial()
             {
                 return $this->arreglo_foraneo_idosocial;
             }
             
      	     function arreglo_foraneo_id_empleador()
             {
                 return $this->arreglo_foraneo_id_empleador;
             }
             
      	     function arreglo_foraneo_id_prestador()
             {
                 return $this->arreglo_foraneo_id_prestador;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function numero_siniestro_asigna($campo)
          {
               $this->numero_siniestro=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function id_empleador_asigna($campo)
          {
               $this->id_empleador=$campo;
               
          }
          function id_prestador_asigna($campo)
          {
               $this->id_prestador=$campo;
               
          }
          
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_egreso WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
	      function foranea_idosocial($idosocial)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_egreso WHERE idosocial=$idosocial");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idosocial = $pro;		                              		
			}
			
	      function foranea_id_empleador($id_empleador)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_egreso WHERE id_empleador=$id_empleador");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_empleador = $pro;		                              		
			}
			
	      function foranea_id_prestador($id_prestador)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_egreso WHERE id_prestador=$id_prestador");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_prestador = $pro;		                              		
			}
			
      
}
?>