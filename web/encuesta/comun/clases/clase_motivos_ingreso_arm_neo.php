<?
      class clase_motivos_ingreso_arm_neo       
      {
	      var $id = '';
          var $idevolucion_neonatologia = '';
          var $descriptionid = '';
          var $subsetid = '';
          var $texto_tesauro = '';
          var $conceptid = '';
          
      
      var $arreglo_foraneo_idevolucion_neonatologia='';
      	     
      
         function clase_motivos_ingreso_arm_neo($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM motivos_ingreso_arm_neo WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idevolucion_neonatologia=$arreglo['idevolucion_neonatologia'];
      	     $this->descriptionid=$arreglo['descriptionid'];
      	     $this->subsetid=$arreglo['subsetid'];
      	     $this->texto_tesauro=$arreglo['texto_tesauro'];
      	     $this->conceptid=$arreglo['conceptid'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO motivos_ingreso_arm_neo(idevolucion_neonatologia,descriptionid,subsetid,texto_tesauro,conceptid) VALUES('".$this->idevolucion_neonatologia."','".$this->descriptionid."','".$this->subsetid."','".$this->texto_tesauro."','".$this->conceptid."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE motivos_ingreso_arm_neo SET idevolucion_neonatologia='".$this->idevolucion_neonatologia."',descriptionid='".$this->descriptionid."',subsetid='".$this->subsetid."',texto_tesauro='".$this->texto_tesauro."',conceptid='".$this->conceptid."' WHERE id='".$this->id."'"))
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
          function idevolucion_neonatologia()
          {
               return $this->idevolucion_neonatologia;
          }
          function descriptionid()
          {
               return $this->descriptionid;
          }
          function subsetid()
          {
               return $this->subsetid;
          }
          function texto_tesauro()
          {
               return $this->texto_tesauro;
          }
          function conceptid()
          {
               return $this->conceptid;
          }
          
          
          
      	     function arreglo_foraneo_idevolucion_neonatologia()
             {
                 return $this->arreglo_foraneo_idevolucion_neonatologia;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idevolucion_neonatologia_asigna($campo)
          {
               $this->idevolucion_neonatologia=$campo;
               
          }
          function descriptionid_asigna($campo)
          {
               $this->descriptionid=$campo;
               
          }
          function subsetid_asigna($campo)
          {
               $this->subsetid=$campo;
               
          }
          function texto_tesauro_asigna($campo)
          {
               $this->texto_tesauro=$campo;
               
          }
          function conceptid_asigna($campo)
          {
               $this->conceptid=$campo;
               
          }
          
          
          
	      function foranea_idevolucion_neonatologia($idevolucion_neonatologia)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM motivos_ingreso_arm_neo WHERE idevolucion_neonatologia=$idevolucion_neonatologia");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idevolucion_neonatologia = $pro;		                              		
			}
		   function todos_motivos_ingreso_arm_neo()
		   {
		   	   $bd = new baseDatos();
			   $bd->Conectarse();		    
			   $bd->select("SELECT * FROM motivos_ingreso_arm_neo");				
			   $pro = new clase_listar();			
								
	    	   for($i=0;$i<=$bd->numero_filas();$i++) 
	    	   {
	    		   $fila = $bd->registro(); 
	    		   $pro->introducirElemento($fila); 
	    	   }
	    	   $this->arreglo_foraneo_idevolucion_neonatologia = $pro;
		   }
      
}
?>