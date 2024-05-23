<?
      class clase_provincia       
      {
	  	  var $idprovincia = '';
          var $idpais = '';
          var $nombre = '';
          var $arreglo_todas_provincia = '';
      
      
      
         function clase_provincia($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM provincia WHERE idprovincia=$id");
      	     $arreglo=$bd->registro(); 
      	     self::asigna($arreglo);     	           	     
      	 }
      	 function asigna($arreglo)
      	 {
      	 	 $this->idprovincia=$arreglo['idprovincia'];
      	     $this->idpais=$arreglo['idpais'];
      	     $this->nombre=$arreglo['nombre'];
      	 }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idprovincia==0 || $this->idprovincia=='' ) {
      	      if ($bd->select("INSERT INTO provincia(idpais,nombre) VALUES('".$this->idpais."','".$this->nombre."')"))
      	      {
      	          $this->idprovincia=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE provincia SET idpais='".$this->idpais."',nombre='".$this->nombre."' WHERE idprovincia='".$this->idprovincia."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idprovincia()
          {
               return $this->idprovincia;
          }
          function idpais()
          {
               return $this->idpais;
          }
          function nombre()
          {
               return $this->nombre;
          }
          
          
          
      
          function idprovincia_asigna($campo)
          {
               $this->idprovincia=$campo;
               
          }
          function idpais_asigna($campo)
          {
               $this->idpais=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
      	  function todas_provincias()
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM provincia ORDER BY nombre");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_todas_provincia = $pro;		                              		
		   }
           function arreglo_todas_provincia()
           {
           	   return $this->arreglo_todas_provincia;
           }
          
          
      
}
?>