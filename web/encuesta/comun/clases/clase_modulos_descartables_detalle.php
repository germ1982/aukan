<?
      class clase_modulos_descartables_detalle       
      {
	      var $id = '';
          var $idmodulo_descartable = '';
          var $idcatalogo = '';
          var $idcatalogo_presentacion = '';
          var $cantidad = '';
          var $arreglo_modulos_descartables = '';
          
      
      
      
         function clase_modulos_descartables_detalle($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM modulos_descartables_detalle WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idmodulo_descartable=$arreglo['idmodulo_descartable'];
      	     $this->idcatalogo=$arreglo['idcatalogo'];
      	     $this->idcatalogo_presentacion=$arreglo['idcatalogo_presentacion'];
      	     $this->cantidad=$arreglo['cantidad'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO modulos_descartables_detalle(idmodulo_descartable,idcatalogo,idcatalogo_presentacion,cantidad) VALUES('".$this->idmodulo_descartable."','".$this->idcatalogo."','".$this->idcatalogo_presentacion."','".$this->cantidad."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE modulos_descartables_detalle SET idmodulo_descartable='".$this->idmodulo_descartable."',idcatalogo='".$this->idcatalogo."',idcatalogo_presentacion='".$this->idcatalogo_presentacion."',cantidad='".$this->cantidad."' WHERE id='".$this->id."'"))
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
          function idmodulo_descartable()
          {
               return $this->idmodulo_descartable;
          }
          function idcatalogo()
          {
               return $this->idcatalogo;
          }
          function idcatalogo_presentacion()
          {
               return $this->idcatalogo_presentacion;
          }
          function cantidad()
          {
               return $this->cantidad;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idmodulo_descartable_asigna($campo)
          {
               $this->idmodulo_descartable=$campo;
               
          }
          function idcatalogo_asigna($campo)
          {
               $this->idcatalogo=$campo;
               
          }
          function idcatalogo_presentacion_asigna($campo)
          {
               $this->idcatalogo_presentacion=$campo;
               
          }
          function cantidad_asigna($campo)
          {
               $this->cantidad=$campo;
               
          }
          function modulos_descartables_detalle($idmodulo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM modulos_descartables_detalle WHERE idmodulo_descartable=$idmodulo");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_modulos_descartables = $pro;		                              		
		  }
          function arreglo_modulos_descartables()
          {
              return $this->arreglo_modulos_descartables;	 
          }
          
      
}
?>