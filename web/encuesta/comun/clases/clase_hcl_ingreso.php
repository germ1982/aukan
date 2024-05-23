<?
      class clase_hcl_ingreso       
      {
	  var $id = '';
          var $idpaciente = '';
          var $numero_siniestro = '';
          var $idosocial = '';
          var $tipo_contingencia = '';
          var $id_empleador = '';
          var $id_prestador = '';
          var $nombre_establecimiento = '';
          var $domicilio_establecimiento = '';
          var $telefono_establecimiento = '';
          var $contacto = '';
          var $telefono_contacto = '';
          var $email = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idosocial='';
      	     var $arreglo_foraneo_id_empleador='';
      	     var $arreglo_foraneo_id_prestador='';
      	     
      
         function clase_hcl_ingreso($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_ingreso WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->numero_siniestro=$arreglo['numero_siniestro'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->tipo_contingencia=$arreglo['tipo_contingencia'];
      	     $this->id_empleador=$arreglo['id_empleador'];
      	     $this->id_prestador=$arreglo['id_prestador'];
      	     $this->nombre_establecimiento=$arreglo['nombre_establecimiento'];
      	     $this->domicilio_establecimiento=$arreglo['domicilio_establecimiento'];
      	     $this->telefono_establecimiento=$arreglo['telefono_establecimiento'];
      	     $this->contacto=$arreglo['contacto'];
      	     $this->telefono_contacto=$arreglo['telefono_contacto'];
      	     $this->email=$arreglo['email'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_ingreso(idpaciente,numero_siniestro,idosocial,tipo_contingencia,id_empleador,id_prestador,nombre_establecimiento,domicilio_establecimiento,telefono_establecimiento,contacto,telefono_contacto,email) VALUES('".$this->idpaciente."','".$this->numero_siniestro."','".$this->idosocial."','".$this->tipo_contingencia."','".$this->id_empleador."','".$this->id_prestador."','".$this->nombre_establecimiento."','".$this->domicilio_establecimiento."','".$this->telefono_establecimiento."','".$this->contacto."','".$this->telefono_contacto."','".$this->email."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_ingreso SET idpaciente='".$this->idpaciente."',numero_siniestro='".$this->numero_siniestro."',idosocial='".$this->idosocial."',tipo_contingencia='".$this->tipo_contingencia."',id_empleador='".$this->id_empleador."',id_prestador='".$this->id_prestador."',nombre_establecimiento='".$this->nombre_establecimiento."',domicilio_establecimiento='".$this->domicilio_establecimiento."',telefono_establecimiento='".$this->telefono_establecimiento."',contacto='".$this->contacto."',telefono_contacto='".$this->telefono_contacto."',email='".$this->email."' WHERE id='".$this->id."'"))
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
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function numero_siniestro()
          {
               return $this->numero_siniestro;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function tipo_contingencia()
          {
               return $this->tipo_contingencia;
          }
          function id_empleador()
          {
               return $this->id_empleador;
          }
          function id_prestador()
          {
               return $this->id_prestador;
          }
          function nombre_establecimiento()
          {
               return $this->nombre_establecimiento;
          }
          function domicilio_establecimiento()
          {
               return $this->domicilio_establecimiento;
          }
          function telefono_establecimiento()
          {
               return $this->telefono_establecimiento;
          }
          function contacto()
          {
               return $this->contacto;
          }
          function telefono_contacto()
          {
               return $this->telefono_contacto;
          }
          function email()
          {
               return $this->email;
          }
          
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idosocial()
             {
                 return $this->arreglo_foraneo_idosocial;
             }
             
      	     function arreglo_foraneo_id_empleador()
             {
                 return $this->arreglo_foraneo_id_empleador;
             }
             
      	     function arreglo_foraneo_id_prestador()
             {
                 return $this->arreglo_foraneo_id_prestador;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function numero_siniestro_asigna($campo)
          {
               $this->numero_siniestro=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function tipo_contingencia_asigna($campo)
          {
               $this->tipo_contingencia=$campo;
               
          }
          function id_empleador_asigna($campo)
          {
               $this->id_empleador=$campo;
               
          }
          function id_prestador_asigna($campo)
          {
               $this->id_prestador=$campo;
               
          }
          function nombre_establecimiento_asigna($campo)
          {
               $this->nombre_establecimiento=$campo;
               
          }
          function domicilio_establecimiento_asigna($campo)
          {
               $this->domicilio_establecimiento=$campo;
               
          }
          function telefono_establecimiento_asigna($campo)
          {
               $this->telefono_establecimiento=$campo;
               
          }
          function contacto_asigna($campo)
          {
               $this->contacto=$campo;
               
          }
          function telefono_contacto_asigna($campo)
          {
               $this->telefono_contacto=$campo;
               
          }
          function email_asigna($campo)
          {
               $this->email=$campo;
               
          }
          
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_ingreso WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
	      function foranea_idosocial($idosocial)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_ingreso WHERE idosocial=$idosocial");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idosocial = $pro;		                              		
			}
			
	      function foranea_id_empleador($id_empleador)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_ingreso WHERE id_empleador=$id_empleador");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_empleador = $pro;		                              		
			}
			
	      function foranea_id_prestador($id_prestador)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_ingreso WHERE id_prestador=$id_prestador");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_prestador = $pro;		                              		
			}
			
      
}
?>