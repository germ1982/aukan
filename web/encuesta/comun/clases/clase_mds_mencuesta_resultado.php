<?
      class clase_encuesta_resultado       
      {
	  var $id = '';
          var $id_encuesta = '';
          var $id_seccion = '';
          var $id_pregunta = '';
          var $id_respuesta = '';
          var $valor = '';
          
      
      var $arreglo_foraneo_id_encuesta='';
      	     var $arreglo_foraneo_id_pregunta='';
      	     var $arreglo_foraneo_id_respuesta='';
      	     
      
         function clase_encuesta_resultado($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM encuesta_resultado WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_encuesta=$arreglo['id_encuesta'];
      	     $this->id_seccion=$arreglo['id_seccion'];
      	     $this->id_pregunta=$arreglo['id_pregunta'];
      	     $this->id_respuesta=$arreglo['id_respuesta'];
      	     $this->valor=$arreglo['valor'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO encuesta_resultado(id_encuesta,id_seccion,id_pregunta,id_respuesta,valor) VALUES('".$this->id_encuesta."','".$this->id_seccion."','".$this->id_pregunta."','".$this->id_respuesta."','".$this->valor."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE encuesta_resultado SET id_encuesta='".$this->id_encuesta."',id_seccion='".$this->id_seccion."',id_pregunta='".$this->id_pregunta."',id_respuesta='".$this->id_respuesta."',valor='".$this->valor."' WHERE id='".$this->id."'"))
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
          function id_encuesta()
          {
               return $this->id_encuesta;
          }
          function id_seccion()
          {
               return $this->id_seccion;
          }
          function id_pregunta()
          {
               return $this->id_pregunta;
          }
          function id_respuesta()
          {
               return $this->id_respuesta;
          }
          function valor()
          {
               return $this->valor;
          }
          
          
          
      	     function arreglo_foraneo_id_encuesta()
             {
                 return $this->arreglo_foraneo_id_encuesta;
             }
             
      	     function arreglo_foraneo_id_pregunta()
             {
                 return $this->arreglo_foraneo_id_pregunta;
             }
             
      	     function arreglo_foraneo_id_respuesta()
             {
                 return $this->arreglo_foraneo_id_respuesta;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function id_encuesta_asigna($campo)
          {
               $this->id_encuesta=$campo;
               
          }
          function id_seccion_asigna($campo)
          {
               $this->id_seccion=$campo;
               
          }
          function id_pregunta_asigna($campo)
          {
               $this->id_pregunta=$campo;
               
          }
          function id_respuesta_asigna($campo)
          {
               $this->id_respuesta=$campo;
               
          }
          function valor_asigna($campo)
          {
               $this->valor=$campo;
               
          }
          
          
          
	      function foranea_id_encuesta($id_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_resultado WHERE id_encuesta=$id_encuesta");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_encuesta = $pro;		                              		
			}
			
	      function foranea_id_pregunta($id_pregunta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_resultado WHERE id_pregunta=$id_pregunta");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_pregunta = $pro;		                              		
			}
			
	      function foranea_id_respuesta($id_respuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_resultado WHERE id_respuesta=$id_respuesta");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_respuesta = $pro;		                              		
			}
			
      
}
?>