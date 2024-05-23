<?
      class clase_evolucion_neonatologia_diagnosticos       
      {
	  var $id = '';
          var $id_neo = '';
          var $codigo = '';
          var $tipo = '';
          var $clasificacion_diagnostico = '';
          
      
      var $arreglo_foraneo_id_neo='';
      	     
      
         function clase_evolucion_neonatologia_diagnosticos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM evolucion_neonatologia_diagnosticos WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_neo=$arreglo['id_neo'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->tipo=$arreglo['tipo'];
      	     $this->clasificacion_diagnostico=$arreglo['clasificacion_diagnostico'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO evolucion_neonatologia_diagnosticos(id_neo,codigo,tipo,clasificacion_diagnostico) VALUES('".$this->id_neo."','".$this->codigo."','".$this->tipo."','".$this->clasificacion_diagnostico."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE evolucion_neonatologia_diagnosticos SET id_neo='".$this->id_neo."',codigo='".$this->codigo."',tipo='".$this->tipo."',clasificacion_diagnostico='".$this->clasificacion_diagnostico."' WHERE id='".$this->id."'"))
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
          function id_neo()
          {
               return $this->id_neo;
          }
          function codigo()
          {
               return $this->codigo;
          }
          function tipo()
          {
               return $this->tipo;
          }
          function clasificacion_diagnostico()
          {
               return $this->clasificacion_diagnostico;
          }
          
          
          
      	     function arreglo_foraneo_id_neo()
             {
                 return $this->arreglo_foraneo_id_neo;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function id_neo_asigna($campo)
          {
               $this->id_neo=$campo;
               
          }
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          function clasificacion_diagnostico_asigna($campo)
          {
               $this->clasificacion_diagnostico=$campo;
               
          }
          
          
          
	      function foranea_id_neo($id_neo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM evolucion_neonatologia_diagnosticos WHERE id_neo=$id_neo");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_neo = $pro;		                              		
			}
			
      
}
?>