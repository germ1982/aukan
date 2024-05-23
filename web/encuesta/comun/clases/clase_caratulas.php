<?php
      class clase_caratulas       
      {
	  var $id = '';
          var $fecha = '';
          var $hora = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $id_tipo_encuesta = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_id_tipo_encuesta='';
      	     
      
         function clase_caratulas($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM caratulas WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->id_tipo_encuesta=$arreglo['id_tipo_encuesta'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO caratulas(fecha,hora,idprofesional,idpaciente,id_tipo_encuesta) VALUES('".$this->fecha."','".$this->hora."','".$this->idprofesional."','".$this->idpaciente."','".$this->id_tipo_encuesta."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE caratulas SET fecha='".$this->fecha."',hora='".$this->hora."',idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',id_tipo_encuesta='".$this->id_tipo_encuesta."' WHERE id='".$this->id."'"))
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
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function id_tipo_encuesta()
          {
               return $this->id_tipo_encuesta;
          }
          
          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_id_tipo_encuesta()
             {
                 return $this->arreglo_foraneo_id_tipo_encuesta;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function id_tipo_encuesta_asigna($campo)
          {
               $this->id_tipo_encuesta=$campo;
               
          }
          
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM caratulas WHERE idprofesional=$idprofesional");				
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
				$bd->select("SELECT * FROM caratulas WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
	      function foranea_idpaciente_id_tipo_encuesta($idpaciente,$id_tipo_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM caratulas WHERE idpaciente=$idpaciente AND id_tipo_encuesta=$id_tipo_encuesta");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_tipo_encuesta = $pro;		                              		
			}
			
      
}
?>