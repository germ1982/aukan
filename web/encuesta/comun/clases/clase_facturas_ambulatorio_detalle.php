<?
      class clase_facturas_ambulatorio_detalle       
      {
	  var $idfactura_ambulatorio_detalle = '';
          var $movimiento_internacion = '';
          var $codigo = '';
          var $cantidad = '';
          var $idespecialista = '';
          var $honorarios_especialista = '';
          var $recargo_especialista = '';
          var $retencion_especialista = '';
          var $gastos = '';
          var $recargo_gastos = '';
          var $retencion = '';
          var $comentarios = '';
          var $fecha = '';
          var $idprofesional_derecho = '';
          var $facturado_gastos = '';
          var $facturado_profesional = '';
          
      
      var $arreglo_foraneo_movimiento_internacion='';
      	     var $arreglo_foraneo_idespecialista='';
      	     
      
         function clase_facturas_ambulatorio_detalle($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM facturas_ambulatorio_detalle WHERE idfactura_ambulatorio_detalle=$id");
      	     $arreglo=$bd->registro();
      	     $this->idfactura_ambulatorio_detalle=$arreglo['idfactura_ambulatorio_detalle'];
      	     $this->movimiento_internacion=$arreglo['movimiento_internacion'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->cantidad=$arreglo['cantidad'];
      	     $this->idespecialista=$arreglo['idespecialista'];
      	     $this->honorarios_especialista=$arreglo['honorarios_especialista'];
      	     $this->recargo_especialista=$arreglo['recargo_especialista'];
      	     $this->retencion_especialista=$arreglo['retencion_especialista'];
      	     $this->gastos=$arreglo['gastos'];
      	     $this->recargo_gastos=$arreglo['recargo_gastos'];
      	     $this->retencion=$arreglo['retencion'];
      	     $this->comentarios=$arreglo['comentarios'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->idprofesional_derecho=$arreglo['idprofesional_derecho'];
      	     $this->facturado_gastos=$arreglo['facturado_gastos'];
      	     $this->facturado_profesional=$arreglo['facturado_profesional'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idfactura_ambulatorio_detalle==0 || $this->idfactura_ambulatorio_detalle=='' ) {
      	      if ($bd->select("INSERT INTO facturas_ambulatorio_detalle(movimiento_internacion,codigo,cantidad,idespecialista,honorarios_especialista,recargo_especialista,retencion_especialista,gastos,recargo_gastos,retencion,comentarios,fecha,idprofesional_derecho,facturado_gastos,facturado_profesional) VALUES('".$this->movimiento_internacion."','".$this->codigo."','".$this->cantidad."','".$this->idespecialista."','".$this->honorarios_especialista."','".$this->recargo_especialista."','".$this->retencion_especialista."','".$this->gastos."','".$this->recargo_gastos."','".$this->retencion."','".$this->comentarios."','".$this->fecha."','".$this->idprofesional_derecho."','".$this->facturado_gastos."','".$this->facturado_profesional."')"))
      	      {
      	          $this->idfactura_ambulatorio_detalle=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE facturas_ambulatorio_detalle SET movimiento_internacion='".$this->movimiento_internacion."',codigo='".$this->codigo."',cantidad='".$this->cantidad."',idespecialista='".$this->idespecialista."',honorarios_especialista='".$this->honorarios_especialista."',recargo_especialista='".$this->recargo_especialista."',retencion_especialista='".$this->retencion_especialista."',gastos='".$this->gastos."',recargo_gastos='".$this->recargo_gastos."',retencion='".$this->retencion."',comentarios='".$this->comentarios."',fecha='".$this->fecha."',idprofesional_derecho='".$this->idprofesional_derecho."',facturado_gastos='".$this->facturado_gastos."',facturado_profesional='".$this->facturado_profesional."' WHERE idfactura_ambulatorio_detalle='".$this->idfactura_ambulatorio_detalle."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idfactura_ambulatorio_detalle()
          {
               return $this->idfactura_ambulatorio_detalle;
          }
          function movimiento_internacion()
          {
               return $this->movimiento_internacion;
          }
          function codigo()
          {
               return $this->codigo;
          }
          function cantidad()
          {
               return $this->cantidad;
          }
          function idespecialista()
          {
               return $this->idespecialista;
          }
          function honorarios_especialista()
          {
               return $this->honorarios_especialista;
          }
          function recargo_especialista()
          {
               return $this->recargo_especialista;
          }
          function retencion_especialista()
          {
               return $this->retencion_especialista;
          }
          function gastos()
          {
               return $this->gastos;
          }
          function recargo_gastos()
          {
               return $this->recargo_gastos;
          }
          function retencion()
          {
               return $this->retencion;
          }
          function comentarios()
          {
               return $this->comentarios;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function idprofesional_derecho()
          {
               return $this->idprofesional_derecho;
          }
          function facturado_gastos()
          {
               return $this->facturado_gastos;
          }
          function facturado_profesional()
          {
               return $this->facturado_profesional;
          }
          
          
          
      	     function arreglo_foraneo_movimiento_internacion()
             {
                 return $this->arreglo_foraneo_movimiento_internacion;
             }
             
      	     function arreglo_foraneo_idespecialista()
             {
                 return $this->arreglo_foraneo_idespecialista;
             }
             
      
          function idfactura_ambulatorio_detalle_asigna($campo)
          {
               $this->idfactura_ambulatorio_detalle=$campo;
               
          }
          function movimiento_internacion_asigna($campo)
          {
               $this->movimiento_internacion=$campo;
               
          }
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
          }
          function cantidad_asigna($campo)
          {
               $this->cantidad=$campo;
               
          }
          function idespecialista_asigna($campo)
          {
               $this->idespecialista=$campo;
               
          }
          function honorarios_especialista_asigna($campo)
          {
               $this->honorarios_especialista=$campo;
               
          }
          function recargo_especialista_asigna($campo)
          {
               $this->recargo_especialista=$campo;
               
          }
          function retencion_especialista_asigna($campo)
          {
               $this->retencion_especialista=$campo;
               
          }
          function gastos_asigna($campo)
          {
               $this->gastos=$campo;
               
          }
          function recargo_gastos_asigna($campo)
          {
               $this->recargo_gastos=$campo;
               
          }
          function retencion_asigna($campo)
          {
               $this->retencion=$campo;
               
          }
          function comentarios_asigna($campo)
          {
               $this->comentarios=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function idprofesional_derecho_asigna($campo)
          {
               $this->idprofesional_derecho=$campo;
               
          }
          function facturado_gastos_asigna($campo)
          {
               $this->facturado_gastos=$campo;
               
          }
          function facturado_profesional_asigna($campo)
          {
               $this->facturado_profesional=$campo;
               
          }
          
          
          
	      function foranea_movimiento_internacion($movimiento_internacion)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM facturas_ambulatorio_detalle WHERE movimiento_internacion=$movimiento_internacion");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_movimiento_internacion = $pro;		                              		
			}
			
	      function foranea_idespecialista($idespecialista)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM facturas_ambulatorio_detalle WHERE idespecialista=$idespecialista");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idespecialista = $pro;		                              		
			}
			
      
}
?>