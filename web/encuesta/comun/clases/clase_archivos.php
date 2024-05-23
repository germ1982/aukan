<?
      class clase_archivos       
      {
	  var $id = '';
          var $nombre = '';
          var $tipo = '';
          var $id_referencia = '';
          var $descripcion = '';
          var $fecha = '';
          
      
      var $arreglo_foraneo_id_referencia='';
      	     
      
         function clase_archivos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM archivos WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->tipo=$arreglo['tipo'];
      	     $this->id_referencia=$arreglo['id_referencia'];
      	     $this->descripcion=$arreglo['descripcion'];
      	     $this->fecha=$arreglo['fecha'];
         }
      function delete(){
          $bd = new baseDatos();
          $bd->Conectarse();
          if ($this->id != 0 || $this->id != ''){
              if ($bd->select("DELETE FROM archivos WHERE id = ".$this->id()))
                 return 1;
              else
                  return 0;
          }
      }
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO archivos(nombre,tipo,id_referencia,descripcion,fecha) VALUES('".$this->nombre."','".$this->tipo."','".$this->id_referencia."','".$this->descripcion."','".$this->fecha."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else{      	  
                  var_dump("INSERT INTO archivos(nombre,tipo,id_referencia,descripcion,fecha) VALUES('".$this->nombre."','".$this->tipo."','".$this->id_referencia."','".$this->descripcion."','".$this->fecha."')");
      	          return 0;
              }
      	  }else
      	  { 
      	        if ($bd->select("UPDATE archivos SET nombre='".$this->nombre."',tipo='".$this->tipo."',id_referencia='".$this->id_referencia."',descripcion='".$this->descripcion."',fecha='".$this->fecha."' WHERE id='".$this->id."'"))
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
          function tipo()
          {
               return $this->tipo;
          }
          function id_referencia()
          {
               return $this->id_referencia;
          }
          function descripcion()
          {
               return $this->descripcion;
          }
          function fecha()
          {
               return $this->fecha;
          }
          
          
          
      	     function arreglo_foraneo_id_referencia()
             {
                 return $this->arreglo_foraneo_id_referencia;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          function id_referencia_asigna($campo)
          {
               $this->id_referencia=$campo;
               
          }
          function descripcion_asigna($campo)
          {
               $this->descripcion=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function existe_archivo($id_referencia,$tipo)
          {
              $bd = new baseDatos();
	      $bd->Conectarse();		    
	      $bd->select("SELECT * FROM archivos WHERE id_referencia=$id_referencia AND tipo = '$tipo'");
              self::asignar($bd->registro());
          }
          
          
	      function foranea_id_referencia($id_referencia,$tipo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM archivos WHERE id_referencia=$id_referencia AND tipo = '$tipo'");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_referencia = $pro;		                              		
			}
			
      
}
?>