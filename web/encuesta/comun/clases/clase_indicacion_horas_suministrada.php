<?
      class clase_indicacion_horas_suministrada       
      {
	  	  var $id = '';
          var $idprofesional = '';
          var $hora = '';
          var $cantidad = '';
          var $idindicacion_sala = '';
          var $fecha_real = '';
          var $observaciones = '';
          var $unidad_medida = '';
          
      
      var $arreglo_foraneo_idindicacion_sala='';
      	     
      
         function clase_indicacion_horas_suministrada($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM indicacion_horas_suministrada WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->hora=$arreglo['hora'];
      	     $this->cantidad=$arreglo['cantidad'];
      	     $this->idindicacion_sala=$arreglo['idindicacion_sala'];
      	     $this->fecha_real=$arreglo['fecha_real'];
      	     $this->observaciones=$arreglo['observaciones'];
      	     $this->unidad_medida=$arreglo['unidad_medida'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO indicacion_horas_suministrada(idprofesional,hora,cantidad,idindicacion_sala,fecha_real,observaciones,unidad_medida) VALUES('".$this->idprofesional."','".$this->hora."','".$this->cantidad."','".$this->idindicacion_sala."','".$this->fecha_real."','".$this->observaciones."','".$this->unidad_medida."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE indicacion_horas_suministrada SET idprofesional='".$this->idprofesional."',hora='".$this->hora."',cantidad='".$this->cantidad."',idindicacion_sala='".$this->idindicacion_sala."',fecha_real='".$this->fecha_real."',observaciones='".$this->observaciones."',unidad_medida='".$this->unidad_medida."' WHERE id='".$this->id."'"))
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
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function hora()
          {
               return $this->hora;
          }
          function cantidad()
          {
               return $this->cantidad;
          }
          function idindicacion_sala()
          {
               return $this->idindicacion_sala;
          }
          function fecha_real()
          {
               return $this->fecha_real;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function unidad_medida()
          {
               return $this->unidad_medida;
          }
          
          
          
      	     function arreglo_foraneo_idindicacion_sala()
             {
                 return $this->arreglo_foraneo_idindicacion_sala;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function cantidad_asigna($campo)
          {
               $this->cantidad=$campo;
               
          }
          function idindicacion_sala_asigna($campo)
          {
               $this->idindicacion_sala=$campo;
               
          }
          function fecha_real_asigna($campo)
          {
               $this->fecha_real=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function unidad_medida_asigna($campo)
          {
               $this->unidad_medida=$campo;
               
          }
          
          
          
	      function foranea_idindicacion_sala($idindicacion_sala)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_horas_suministrada WHERE idindicacion_sala=$idindicacion_sala");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idindicacion_sala = $pro;		                              		
			}
			
      
}
?>