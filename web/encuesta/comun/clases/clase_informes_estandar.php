<?
      class clase_informes_estandar       
      {
	  var $id = '';
          var $descripcion = '';
          var $informe = '';
          var $arreglo_informes = '';
          
      
      
      
         function clase_informes_estandar($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM informes_estandar WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->descripcion=$arreglo['descripcion'];
      	     $this->informe=$arreglo['informe'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO informes_estandar(descripcion,informe) VALUES('".$this->descripcion."','".$this->informe."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE informes_estandar SET descripcion='".$this->descripcion."',informe='".$this->informe."' WHERE id='".$this->id."'"))
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
          function descripcion()
          {
               return $this->descripcion;
          }
          function informe()
          {
               return $this->informe;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function descripcion_asigna($campo)
          {
               $this->descripcion=$campo;
               
          }
          function informe_asigna($campo)
          {
               $this->informe=$campo;
               
          }
          function desplegar_todos_informes()
          {
              $bd = new baseDatos();
      	      $bd->Conectarse();
              $pro = new clase_listar();	
              $bd->select("SELECT * FROM informes_estandar");
	      for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_informes = $pro;
          }
          function arreglo_informes()
          {
              return $this->arreglo_informes;
          }
          
      
}
?>