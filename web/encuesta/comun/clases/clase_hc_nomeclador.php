<?
      class clase_hc_nomeclador       
      {
	  var $id = '';
          var $cod_nom = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $fecha = '';
          var $hora = '';
          var $cantidad = '';
          
      
      var $arreglo_foraneo_cod_nom='';
      	     var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_hc_nomeclador($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hc_nomeclador WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->cod_nom=$arreglo['cod_nom'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->cantidad=$arreglo['cantidad'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hc_nomeclador(cod_nom,idprofesional,idpaciente,fecha,hora,cantidad) VALUES('".$this->cod_nom."','".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->hora."','".$this->cantidad."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hc_nomeclador SET cod_nom='".$this->cod_nom."',idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',hora='".$this->hora."',cantidad='".$this->cantidad."' WHERE id='".$this->id."'"))
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
          function cod_nom()
          {
               return $this->cod_nom;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function cantidad()
          {
               return $this->cantidad;
          }
          
          
          
      	     function arreglo_foraneo_cod_nom()
             {
                 return $this->arreglo_foraneo_cod_nom;
             }
             
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function cod_nom_asigna($campo)
          {
               $this->cod_nom=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function cantidad_asigna($campo)
          {
               $this->cantidad=$campo;
               
          }
          
          
          
	      function foranea_cod_nom($cod_nom)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_nomeclador WHERE cod_nom=$cod_nom");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_cod_nom = $pro;		                              		
			}
			
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_nomeclador WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_nomeclador WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
      
}
?>