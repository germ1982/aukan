<?
      class clase_indicacion_controles_pedidos       
      {
	  var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $codigo = '';
          var $intervalo = '';
          var $fecha = '';
          var $hora = '';
          var $estado = '';
          var $idindicacion = '';
          var $cantidad_dias_control = 0;
          
      
          var $arreglo_foraneo_idepisodio='';
      	  var $arreglo_foraneo_codigo='';
      	     
      
         function clase_indicacion_controles_pedidos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM indicacion_controles_pedidos WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->intervalo=$arreglo['intervalo'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->estado=$arreglo['estado'];
      	     $this->idindicacion=$arreglo['idindicacion'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO indicacion_controles_pedidos(idepisodio,idprofesional,codigo,intervalo,fecha,hora,estado,idindicacion) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->codigo."','".$this->intervalo."','".$this->fecha."','".$this->hora."','".$this->estado."','".$this->idindicacion."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE indicacion_controles_pedidos SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',codigo='".$this->codigo."',intervalo='".$this->intervalo."',fecha='".$this->fecha."',hora='".$this->hora."',estado='".$this->estado."',idindicacion='".$this->idindicacion."' WHERE id='".$this->id."'"))
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
          function codigo()
          {
               return $this->codigo;
          }
          function intervalo()
          {
               return $this->intervalo;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function estado()
          {
               return $this->estado;
          }
          function idindicacion()
          {
               return $this->idindicacion;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      	     function arreglo_foraneo_codigo()
             {
                 return $this->arreglo_foraneo_codigo;
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
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
          }
          function intervalo_asigna($campo)
          {
               $this->intervalo=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function idindicacion_asigna($campo)
          {
               $this->idindicacion=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_pedidos WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
			
	      function foranea_codigo($codigo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicacion_controles_pedidos WHERE codigo=$codigo");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_codigo = $pro;		                              		
			}
			function cantidad_dias_control($idepisodio,$fdesde,$fhasta,$codigo)
			{
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT COUNT(indicacion_controles_pedidos.id) as cantidad FROM indicaciones_medicas LEFT JOIN indicacion_controles_pedidos ON (indicaciones_medicas.id=idindicacion)   
				             WHERE indicacion_controles_pedidos.idepisodio=$idepisodio 
				             AND indicaciones_medicas.fecha>='".fechaBase($fdesde)."' 
				             AND indicaciones_medicas.fecha<='".fechaBase($fhasta)."' AND indicacion_controles_pedidos.codigo=$codigo");
				$arreglo = $bd->registro();
				$this->cantidad_dias_control=$arreglo['cantidad'];			
			}
			function dias_control()
			{
				return $this->cantidad_dias_control;
			}
      
}
?>