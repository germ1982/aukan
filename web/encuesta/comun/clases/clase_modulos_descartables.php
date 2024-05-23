<?
      class clase_modulos_descartables       
      {
	      var $id = '';
          var $nombre = '';
          var $idsector = '';
          var $arreglo_modulos_descartables = '';
          
      
      
      
         function clase_modulos_descartables($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM modulos_descartables WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->idsector=$arreglo['idsector'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO modulos_descartables(nombre,idsector) VALUES('".$this->nombre."','".$this->idsector."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE modulos_descartables SET nombre='".$this->nombre."',idsector='".$this->idsector."' WHERE id='".$this->id."'"))
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
          function nombre()
          {
               return $this->nombre;
          }
          function idsector()
          {
               return $this->idsector;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function idsector_asigna($campo)
          {
               $this->idsector=$campo;
               
          }
          function modulos_descartables()
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM modulos_descartables ORDER BY nombre ASC");				
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