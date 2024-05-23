<?
      class clase_indicacion_controles_horas       
      {
	  var $id = '';
          var $idcontrol = '';
          var $codigo = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $hora_real = '';
          var $resultado = '';
          var $idindicacion_control_pedido = '';
          var $estado = '';
          var $idepisodio = '';
          var $idcontrol_hora = '';
          
      
      var $arreglo_foraneo_idcontrol='';
      	     var $arreglo_foraneo_codigo='';
      	     var $arreglo_foraneo_idcontrol_hora='';
      	     
      
         function clase_indicacion_controles_horas($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM indicacion_controles_horas WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idcontrol=$arreglo['idcontrol'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->hora_real=$arreglo['hora_real'];
      	     $this->resultado=$arreglo['resultado'];
      	     $this->idindicacion_control_pedido=$arreglo['idindicacion_control_pedido'];
      	     $this->estado=$arreglo['estado'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idcontrol_hora=$arreglo['idcontrol_hora'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO indicacion_controles_horas(idcontrol,codigo,idprofesional,
      	      fecha,hora,hora_real,resultado,estado,idepisodio,idcontrol_hora) 
      	      VALUES('".$this->idcontrol."','".$this->codigo."','".$this->idprofesional."','".$this->fecha."','".$this->hora."',
      	      '".$this->hora_real."','".$this->resultado."',1,'".$this->idepisodio."','".$this->idcontrol_hora."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE indicacion_controles_horas SET resultado='".$this->resultado."' WHERE id='".$this->id."'"))
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
          function idcontrol()
          {
               return $this->idcontrol;
          }
          function codigo()
          {
               return $this->codigo;
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
          function hora_real()
          {
               return $this->hora_real;
          }
          function resultado()
          {
               return $this->resultado;
          }
          function idindicacion_control_pedido()
          {
               return $this->idindicacion_control_pedido;
          }
          function estado()
          {
               return $this->estado;
          }
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function idcontrol_hora()
          {
               return $this->idcontrol_hora;
          }
          
          
          
      	     function arreglo_foraneo_idcontrol()
             {
                 return $this->arreglo_foraneo_idcontrol;
             }
             
      	     function arreglo_foraneo_codigo()
             {
                 return $this->arreglo_foraneo_codigo;
             }
             
      	     function arreglo_foraneo_idcontrol_hora()
             {
                 return $this->arreglo_foraneo_idcontrol_hora;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idcontrol_asigna($campo)
          {
               $this->idcontrol=$campo;
               
          }
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
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
          function hora_real_asigna($campo)
          {
               $this->hora_real=$campo;
               
          }
          function resultado_asigna($campo)
          {
               $this->resultado=$campo;
               
          }
          function idindicacion_control_pedido_asigna($campo)
          {
               $this->idindicacion_control_pedido=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function idcontrol_hora_asigna($campo)
          {
               $this->idcontrol_hora=$campo;
               
          }
          
          
          
	      function foranea_idcontrol($idcontrol)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_horas WHERE idcontrol=$idcontrol");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idcontrol = $pro;		                              		
			}
			
	      function foranea_codigo($codigo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_horas WHERE codigo=$codigo");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_codigo = $pro;		                              		
			}
			
	      function foranea_idcontrol_hora($idcontrol_hora)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_horas WHERE idcontrol_hora=$idcontrol_hora");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idcontrol_hora = $pro;		                              		
			}
			function borrarControl($id)
			{
				$bd = new baseDatos();
				$bd->Conectarse();
				if ($bd->select("DELETE FROM indicacion_controles_horas WHERE id=$id"))
				    return 1;
				else 
				    return 0;
			}
			function quienRealizoControl($id,$idprofesional)
			{
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_horas WHERE id=$id AND idprofesional=$idprofesional");
				if ($bd->numero_filas() != 0)
				    return 1;
				else 
				    return 0;
			}
			function horaControlRealizada($idcontrol_hora,$hora,$codigo,$idcontrol)
			{
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_horas WHERE codigo=$codigo AND idcontrol=$idcontrol AND 
				 idcontrol_hora=$idcontrol_hora AND hora=$hora");
				if ($bd->numero_filas() != 0)
				    return 1;
				else 
				    return 0;
			}
            function resultado_control($id,$idepisodio,$codigo,$idcontrol)
            {
            	$bd = new baseDatos();
				$bd->Conectarse();
            	$bd->select("SELECT resultado FROM indicacion_controles_horas WHERE codigo =$codigo AND idcontrol =$idcontrol AND 
		                    idepisodio =$idepisodio AND idcontrol_hora=$id ORDER BY hora DESC");
            	$arreglo = $bd->registro();
            	$this->resultado = $arreglo['resultado'];
            }
}
?>