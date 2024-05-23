<?
      class clase_pacientes_gestion       
      {
	      var $idpaciente = '';
          var $fecha = '';
          var $observaciones = '';
          var $id = '';
          var $idprofesional = '';
      
          var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_pacientes_gestion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     if ($id != 0 && $id != '')
      	     {
      	         $bd->select("SELECT * FROM pacientes_gestion WHERE id=$id");
      	         $arreglo=$bd->registro(); 
      	         self::asignar($arreglo);
      	     }
      	 }
      	 function asignar($arreglo)       
      	 {
      	 	$this->idpaciente=$arreglo['idpaciente'];
      	 	$this->fecha=$arreglo['fecha'];
      	 	$this->observaciones=$arreglo['observaciones'];
      	 	$this->id=$arreglo['id'];
      	 	$this->idprofesional=$arreglo['idprofesional'];
      	 }
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO pacientes_gestion(idpaciente,fecha,observaciones,idprofesional) VALUES('".$this->idpaciente."','".$this->fecha."','".$this->observaciones."','".$this->idprofesional."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return "INSERT INTO pacientes_gestion(idpaciente,fecha,observaciones,idprofesional) VALUES('".$this->idpaciente."','".$this->fecha."','".$this->observaciones."','".$this->idprofesional."')";
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pacientes_gestion SET fecha='".$this->fecha."',observaciones='".$this->observaciones."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function id()
          {
               return $this->id;
          }
          function idprofesional()
          {
          	return $this->idprofesional;
          }
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
          	  $this->idprofesional=$campo;
          }
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM pacientes_gestion WHERE idpaciente=$idpaciente ORDER BY fecha DESC");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			if ($fila['id'] != 0 && $fila['id'] != '')
	    			    $pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
      
}
?>