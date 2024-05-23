<?
      class clase_evolucion_kinesioterapia       
      {
	  var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $texto_evolucion = '';
          var $tipo = '';
          
      
      var $arreglo_foraneo_idepisodio='';
      	     var $arreglo_foraneo_idprofesional='';
      	     
      
         function clase_evolucion_kinesioterapia($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM evolucion_kinesioterapia WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->texto_evolucion=$arreglo['texto_evolucion'];
      	     $this->tipo=$arreglo['tipo'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO evolucion_kinesioterapia(idepisodio,idprofesional,fecha,hora,texto_evolucion,tipo) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->fecha."','".$this->hora."','".$this->texto_evolucion."','".$this->tipo."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE evolucion_kinesioterapia SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',hora='".$this->hora."',texto_evolucion='".$this->texto_evolucion."',tipo='".$this->tipo."' WHERE id='".$this->id."'"))
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
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function texto_evolucion()
          {
               return $this->texto_evolucion;
          }
          function tipo()
          {
               return $this->tipo;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function texto_evolucion_asigna($campo)
          {
               $this->texto_evolucion=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM evolucion_kinesioterapia WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
			
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM evolucion_kinesioterapia WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
          function evoluciones_paciente_entre_fechas($idepisodio,$fdesde,$fhasta,$fecha_egreso,$fecha_ingreso)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse(); 
			  if ($fecha_ingreso<=fechaBase($fdesde))
			      $finicio = fechaBase($fdesde);
			  else 
			      $finicio = $fecha_ingreso;
			  if ($fecha_egreso != '' && $fecha_egreso != '0000-00-00')
	    	  {
	    	      if (compara_fechas($fecha_egreso,fechaBase($fhasta)) == 0)	    	    
	    	          $ffin = fechaBase($fhasta);	    	         	 	 
	    	      else 
	    	      {
	    	    	  if (compara_fechas($fecha_egreso,fechaBase($fhasta)) > 0)
		    	          $ffin = fechaBase($fhasta);
		    	      else 
		    	     	  $ffin = $fecha_egreso;
	    	      }
	    	  }
	    	  else 
	    	      $ffin = fechaBase($fhasta);			  				  	 
			  $bd->select("SELECT COUNT(evolucion_kinesioterapia.id) as cantidad,idprofesional			      
				          FROM evolucion_kinesioterapia 			               			             
			              WHERE idepisodio=$idepisodio AND fecha>='$finicio' AND fecha<='$ffin'
			              GROUP BY idprofesional");						      
			  $pro = new clase_listar();			
								
	    	  for($i=0;$i<=$bd->numero_filas();$i++) 
	    	  {
	    	      $fila = $bd->registro(); 
	    	      if ($fila['cantidad'] != '' && $fila['idprofesional'] != '')
	    		      $pro->introducirElemento($fila); 
	    	  }
	    	  $this->arreglo_foraneo_idepisodio = $pro;	
		  }
      
}
?>