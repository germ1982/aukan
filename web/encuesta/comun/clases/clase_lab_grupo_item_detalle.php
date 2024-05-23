<?
      class clase_lab_grupo_item_detalle       
      {
	  var $id = '';
          var $lab_grupo_item_id = '';
          var $cli_nomenclador_id = '';
          
      
      var $arreglo_foraneo_lab_grupo_item_id='';
      	     var $arreglo_foraneo_cli_nomenclador_id='';
      	     
      
         function clase_lab_grupo_item_detalle($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM lab_grupo_item_detalle WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->lab_grupo_item_id=$arreglo['lab_grupo_item_id'];
      	     $this->cli_nomenclador_id=$arreglo['cli_nomenclador_id'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO lab_grupo_item_detalle(lab_grupo_item_id,cli_nomenclador_id) VALUES('".$this->lab_grupo_item_id."','".$this->cli_nomenclador_id."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE lab_grupo_item_detalle SET lab_grupo_item_id='".$this->lab_grupo_item_id."',cli_nomenclador_id='".$this->cli_nomenclador_id."' WHERE id='".$this->id."'"))
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
          function lab_grupo_item_id()
          {
               return $this->lab_grupo_item_id;
          }
          function cli_nomenclador_id()
          {
               return $this->cli_nomenclador_id;
          }
          
          
          
      	     function arreglo_foraneo_lab_grupo_item_id()
             {
                 return $this->arreglo_foraneo_lab_grupo_item_id;
             }
             
      	     function arreglo_foraneo_cli_nomenclador_id()
             {
                 return $this->arreglo_foraneo_cli_nomenclador_id;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function lab_grupo_item_id_asigna($campo)
          {
               $this->lab_grupo_item_id=$campo;
               
          }
          function cli_nomenclador_id_asigna($campo)
          {
               $this->cli_nomenclador_id=$campo;
               
          }
          
          
          
	      function foranea_lab_grupo_item_id($lab_grupo_item_id)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM lab_grupo_item_detalle WHERE lab_grupo_item_id=$lab_grupo_item_id");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_lab_grupo_item_id = $pro;		                              		
			}
			
	      function foranea_cli_nomenclador_id($cli_nomenclador_id)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM lab_grupo_item_detalle WHERE cli_nomenclador_id=$cli_nomenclador_id");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_cli_nomenclador_id = $pro;		                              		
			}
			
      
}
?>