<?
      class clase_pais       
      {
	 	  var $idpais = '';
          var $nombre = '';
          var $arreglo_todos_paises = '';           
      
          function clase_pais($id)
          {
      	      $bd = new baseDatos();
      	      $bd->Conectarse();
      	      $bd->select("SELECT * FROM pais WHERE idpais=$id");
      	      $arreglo=$bd->registro();
      	      $this->idpais=$arreglo['idpais'];
      	      $this->nombre=$arreglo['nombre'];      	     
      	  }      	           
          function guardar()
          {
              $bd = new baseDatos();
      	      $bd->Conectarse();
      	      if ($this->idpais==0 || $this->idpais=='' ) 
      	      {
      	          if ($bd->select("INSERT INTO pais(nombre) VALUES('".$this->nombre."')"))
      	          {
      	              $this->idpais=$bd->ultimo_id();
      	              return 1;
      	          }
      	          else      	  
      	              return 0;
      	     }else
      	     {  
      	          if ($bd->select("UPDATE pais SET nombre='".$this->nombre."' WHERE idpais='".$this->idpais."'"))
      	          {      	           
      	              return 1;
      	          }
      	          else      	  
      	              return 0;
      	     }
         }
      		
      
           
          function idpais()
          {
               return $this->idpais;
          }
          function nombre()
          {
               return $this->nombre;
          }
          
          
          
      
          function idpais_asigna($campo)
          {
               $this->idpais=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
      	  function todos_paises()
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM pais ORDER BY nombre");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_todos_paises = $pro;		                              		
		   }
           function arreglo_todos_paises()
           {
           	   return $this->arreglo_todos_paises;
           }
}
?>