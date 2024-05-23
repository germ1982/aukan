<?
      class clase_salida_catalogo_destino       
      {
	  var $id = '';
          var $idpedido = '';
          var $idcatalogo = '';
          var $idcatalogo_presentacion = '';
          var $idunidad = '';
          var $cantidad = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $lote = '';
          
      
      var $arreglo_foraneo_idpedido='';
      	     
      
         function clase_salida_catalogo_destino($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM salida_catalogo_destino WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpedido=$arreglo['idpedido'];
      	     $this->idcatalogo=$arreglo['idcatalogo'];
      	     $this->idcatalogo_presentacion=$arreglo['idcatalogo_presentacion'];
      	     $this->idunidad=$arreglo['idunidad'];
      	     $this->cantidad=$arreglo['cantidad'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->lote=$arreglo['lote'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO salida_catalogo_destino(idpedido,idcatalogo,idcatalogo_presentacion,idunidad,cantidad,idprofesional,fecha,hora,lote) VALUES('".$this->idpedido."','".$this->idcatalogo."','".$this->idcatalogo_presentacion."','".$this->idunidad."','".$this->cantidad."','".$this->idprofesional."','".$this->fecha."','".$this->hora."','".$this->lote."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE salida_catalogo_destino SET idpedido='".$this->idpedido."',idcatalogo='".$this->idcatalogo."',idcatalogo_presentacion='".$this->idcatalogo_presentacion."',idunidad='".$this->idunidad."',cantidad='".$this->cantidad."',idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',hora='".$this->hora."',lote='".$this->lote."' WHERE id='".$this->id."'"))
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
          function idpedido()
          {
               return $this->idpedido;
          }
          function idcatalogo()
          {
               return $this->idcatalogo;
          }
          function idcatalogo_presentacion()
          {
               return $this->idcatalogo_presentacion;
          }
          function idunidad()
          {
               return $this->idunidad;
          }
          function cantidad()
          {
               return $this->cantidad;
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
          function lote()
          {
               return $this->lote;
          }
          
          
          
      	     function arreglo_foraneo_idpedido()
             {
                 return $this->arreglo_foraneo_idpedido;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpedido_asigna($campo)
          {
               $this->idpedido=$campo;
               
          }
          function idcatalogo_asigna($campo)
          {
               $this->idcatalogo=$campo;
               
          }
          function idcatalogo_presentacion_asigna($campo)
          {
               $this->idcatalogo_presentacion=$campo;
               
          }
          function idunidad_asigna($campo)
          {
               $this->idunidad=$campo;
               
          }
          function cantidad_asigna($campo)
          {
               $this->cantidad=$campo;
               
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
          function lote_asigna($campo)
          {
               $this->lote=$campo;
               
          }                              
	      function foranea_idpedido($idpedido)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM salida_catalogo_destino WHERE idpedido=$idpedido");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpedido = $pro;		                              		
			}
      		function filaSalidaPedidoCatalogo($id)
			{
				$bd = new baseDatos();
				$bd->Conectarse();
				$bd->select("SELECT * FROM salida_catalogo_destino WHERE idpedido=$id");
	      	    $arreglo=$bd->registro();
	      	    $this->id=$arreglo['id'];
	      	    $this->idpedido=$arreglo['idpedido'];
	      	    $this->idcatalogo=$arreglo['idcatalogo'];
	      	    $this->idcatalogo_presentacion=$arreglo['idcatalogo_presentacion'];
	      	    $this->idunidad=$arreglo['idunidad'];
	      	    $this->cantidad=$arreglo['cantidad'];
	      	    $this->idprofesional=$arreglo['idprofesional'];
	      	    $this->fecha=$arreglo['fecha'];
	      	    $this->hora=$arreglo['hora'];
	      	    $this->lote=$arreglo['lote'];
			}
			function verificarPedidoFarmaciaRealizado($idpedido)
			{				
			    $clase_pedidos = new clase_pedidos_farmacia_sectores_detalle(0);
				$clase_pedidos->foranea_idpedido_farmacia($idpedido);
    		    $arreglo_pedidos=$clase_pedidos->arreglo_foraneo_idpedido_farmacia();    	
        		$iterator = new clase_patron_iterator($arreglo_pedidos);
        		$bandera = 0;   					 
				while ($iterator->existeElementoSiguiente())
	    		{ 				    
					$fila = $iterator->elementoSiguiente();
					self::filaSalidaPedidoCatalogo($fila['id']);					
					if (self::id() != '' && self::id() !=0)
					    return 1;	
				}
				return $bandera;
			}      			
}
?>