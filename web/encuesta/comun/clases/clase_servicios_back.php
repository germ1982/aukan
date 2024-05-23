<?
      class clase_servicios       
      {
	  var $nombre = '';
          var $idservicio = '';
          
      
      
      
         function clase_servicios($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM servicios WHERE idservicio=$id");
      	     $arreglo=$bd->registro();
      	     $this->nombre=$arreglo['nombre'];
      	     $this->idservicio=$arreglo['idservicio'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idservicio==0 || $this->idservicio=='' ) {
      	      if ($bd->select("INSERT INTO servicios(nombre) VALUES('".$this->nombre."')"))
      	      {
      	          $this->idservicio=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE servicios SET nombre='".$this->nombre."' WHERE idservicio='".$this->idservicio."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function nombre()
          {
               return $this->nombre;
          }
          function idservicio()
          {
               return $this->idservicio;
          }
          
          
          
      
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function idservicio_asigna($campo)
          {
               $this->idservicio=$campo;
               
          }
          
          
          
      
}
?>