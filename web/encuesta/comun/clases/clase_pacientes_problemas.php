<?
      class clase_pacientes_problemas       
      {
	  var $id = '';
          var $idpaciente = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $descriptionid = '';
          var $estado = '';
          var $subsetid = '';
          var $texto_tesauro = '';
          var $fecha_carga = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_pacientes_problemas($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pacientes_problemas WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->descriptionid=$arreglo['descriptionid'];
      	     $this->estado=$arreglo['estado'];
      	     $this->subsetid=$arreglo['subsetid'];
      	     $this->texto_tesauro=$arreglo['texto_tesauro'];
      	     $this->fecha_carga=$arreglo['fecha_carga'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO pacientes_problemas(idpaciente,idprofesional,fecha,hora,descriptionid,estado,subsetid,texto_tesauro,fecha_carga) VALUES('".$this->idpaciente."','".$this->idprofesional."','".$this->fecha."','".$this->hora."','".$this->descriptionid."','".$this->estado."','".$this->subsetid."','".$this->texto_tesauro."','".$this->fecha_carga."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pacientes_problemas SET idpaciente='".$this->idpaciente."',idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',hora='".$this->hora."',descriptionid='".$this->descriptionid."',estado='".$this->estado."',subsetid='".$this->subsetid."',texto_tesauro='".$this->texto_tesauro."',fecha_carga='".$this->fecha_carga."' WHERE id='".$this->id."'"))
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
          function descriptionid()
          {
               return $this->descriptionid;
          }
          function estado()
          {
               return $this->estado;
          }
          function subsetid()
          {
               return $this->subsetid;
          }
          function texto_tesauro()
          {
               return $this->texto_tesauro;
          }
          function fecha_carga()
          {
               return $this->fecha_carga;
          }
          
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
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
          function descriptionid_asigna($campo)
          {
               $this->descriptionid=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function subsetid_asigna($campo)
          {
               $this->subsetid=$campo;
               
          }
          function texto_tesauro_asigna($campo)
          {
               $this->texto_tesauro=$campo;
               
          }
          function fecha_carga_asigna($campo)
          {
               $this->fecha_carga=$campo;
               
          }
          
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM pacientes_problemas WHERE idpaciente=$idpaciente");				
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