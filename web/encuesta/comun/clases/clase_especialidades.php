<?
      class clase_especialidades       
      {
	      var $idespecialidad = '';
          var $nombre = '';
          var $tipo = '';
          var $arreglo_especialidades = '';
      
      
      
         function clase_especialidades($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM especialidades WHERE idespecialidad=$id");
      	     $arreglo=$bd->registro();
      	     $this->idespecialidad=$arreglo['idespecialidad'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->tipo=$arreglo['tipo'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idespecialidad==0 || $this->idespecialidad=='' ) {
      	      if ($bd->select("INSERT INTO especialidades(nombre,tipo) VALUES('".$this->nombre."','".$this->tipo."')"))
      	      {
      	          $this->idespecialidad=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE especialidades SET nombre='".$this->nombre."',tipo='".$this->tipo."' WHERE idespecialidad='".$this->idespecialidad."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idespecialidad()
          {
               return $this->idespecialidad;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function tipo()
          {
               return $this->tipo;
          }
          function arreglo_especialidades()
          {
              return $this->arreglo_especialidades;	
          }
          
          
      
          function idespecialidad_asigna($campo)
          {
               $this->idespecialidad=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          
          function especialidades_ambulatorias()
          {
          	  $bd = new baseDatos();
		      $bd->Conectarse();		   
		      $bd->select("SELECT nombre,idespecialidad FROM especialidades WHERE tipo=2 ORDER BY nombre");
          	  $pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_especialidades = $pro;
          }
          
      
}
?>