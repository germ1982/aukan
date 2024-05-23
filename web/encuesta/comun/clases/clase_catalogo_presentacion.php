<?
      class clase_catalogo_presentacion       
      {
	  var $id = '';
          var $codigo = '';
          var $via = '';
          var $descripcion = '';
          var $cantidad = '';
          var $precio_kairos = '';
          var $stock_minimo = '';
          var $stock_maximo = '';
          var $ajuste_stock = '';
          var $fecha_ajuste_stock = '';
          var $idunidad = '';
          var $nombre = '';
          var $presentacion = '';
          var $morden = '';
          var $activo = '';
          var $dosis = '';
          var $concentracion = '';
          var $precio_comercial = '';
          var $droga_asociada = '';
          var $dosis_droga_asociada = '';
          var $concentracion_droga_asociada = '';
          var $activo_pedido = '';
          var $muestra_guardia = '';
          var $texto_tesauro = '';
          var $descriptionid = '';
          var $estado_anterior_mapeo = '';
          
      
      var $arreglo_foraneo_codigo='';
      	     
      
         function clase_catalogo_presentacion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM catalogo_presentacion WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->via=$arreglo['via'];
      	     $this->descripcion=$arreglo['descripcion'];
      	     $this->cantidad=$arreglo['cantidad'];
      	     $this->precio_kairos=$arreglo['precio_kairos'];
      	     $this->stock_minimo=$arreglo['stock_minimo'];
      	     $this->stock_maximo=$arreglo['stock_maximo'];
      	     $this->ajuste_stock=$arreglo['ajuste_stock'];
      	     $this->fecha_ajuste_stock=$arreglo['fecha_ajuste_stock'];
      	     $this->idunidad=$arreglo['idunidad'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->presentacion=$arreglo['presentacion'];
      	     $this->morden=$arreglo['morden'];
      	     $this->activo=$arreglo['activo'];
      	     $this->dosis=$arreglo['dosis'];
      	     $this->concentracion=$arreglo['concentracion'];
      	     $this->precio_comercial=$arreglo['precio_comercial'];
      	     $this->droga_asociada=$arreglo['droga_asociada'];
      	     $this->dosis_droga_asociada=$arreglo['dosis_droga_asociada'];
      	     $this->concentracion_droga_asociada=$arreglo['concentracion_droga_asociada'];
      	     $this->activo_pedido=$arreglo['activo_pedido'];
      	     $this->muestra_guardia=$arreglo['muestra_guardia'];
      	     $this->texto_tesauro=$arreglo['texto_tesauro'];
      	     $this->descriptionid=$arreglo['descriptionid'];
      	     $this->estado_anterior_mapeo=$arreglo['estado_anterior_mapeo'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO catalogo_presentacion(codigo,via,descripcion,cantidad,precio_kairos,stock_minimo,stock_maximo,ajuste_stock,fecha_ajuste_stock,idunidad,nombre,presentacion,morden,activo,dosis,concentracion,precio_comercial,droga_asociada,dosis_droga_asociada,concentracion_droga_asociada,activo_pedido,muestra_guardia,texto_tesauro,descriptionid,estado_anterior_mapeo) VALUES('".$this->codigo."','".$this->via."','".$this->descripcion."','".$this->cantidad."','".$this->precio_kairos."','".$this->stock_minimo."','".$this->stock_maximo."','".$this->ajuste_stock."','".$this->fecha_ajuste_stock."','".$this->idunidad."','".$this->nombre."','".$this->presentacion."','".$this->morden."','".$this->activo."','".$this->dosis."','".$this->concentracion."','".$this->precio_comercial."','".$this->droga_asociada."','".$this->dosis_droga_asociada."','".$this->concentracion_droga_asociada."','".$this->activo_pedido."','".$this->muestra_guardia."','".$this->texto_tesauro."','".$this->descriptionid."','".$this->estado_anterior_mapeo."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE catalogo_presentacion SET codigo='".$this->codigo."',via='".$this->via."',descripcion='".$this->descripcion."',cantidad='".$this->cantidad."',precio_kairos='".$this->precio_kairos."',stock_minimo='".$this->stock_minimo."',stock_maximo='".$this->stock_maximo."',ajuste_stock='".$this->ajuste_stock."',fecha_ajuste_stock='".$this->fecha_ajuste_stock."',idunidad='".$this->idunidad."',nombre='".$this->nombre."',presentacion='".$this->presentacion."',morden='".$this->morden."',activo='".$this->activo."',dosis='".$this->dosis."',concentracion='".$this->concentracion."',precio_comercial='".$this->precio_comercial."',droga_asociada='".$this->droga_asociada."',dosis_droga_asociada='".$this->dosis_droga_asociada."',concentracion_droga_asociada='".$this->concentracion_droga_asociada."',activo_pedido='".$this->activo_pedido."',muestra_guardia='".$this->muestra_guardia."',texto_tesauro='".$this->texto_tesauro."',descriptionid='".$this->descriptionid."',estado_anterior_mapeo='".$this->estado_anterior_mapeo."' WHERE id='".$this->id."'"))
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
          function codigo()
          {
               return $this->codigo;
          }
          function via()
          {
               return $this->via;
          }
          function descripcion()
          {
               return $this->descripcion;
          }
          function cantidad()
          {
               return $this->cantidad;
          }
          function precio_kairos()
          {
               return $this->precio_kairos;
          }
          function stock_minimo()
          {
               return $this->stock_minimo;
          }
          function stock_maximo()
          {
               return $this->stock_maximo;
          }
          function ajuste_stock()
          {
               return $this->ajuste_stock;
          }
          function fecha_ajuste_stock()
          {
               return $this->fecha_ajuste_stock;
          }
          function idunidad()
          {
               return $this->idunidad;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function presentacion()
          {
               return $this->presentacion;
          }
          function morden()
          {
               return $this->morden;
          }
          function activo()
          {
               return $this->activo;
          }
          function dosis()
          {
               return $this->dosis;
          }
          function concentracion()
          {
               return $this->concentracion;
          }
          function precio_comercial()
          {
               return $this->precio_comercial;
          }
          function droga_asociada()
          {
               return $this->droga_asociada;
          }
          function dosis_droga_asociada()
          {
               return $this->dosis_droga_asociada;
          }
          function concentracion_droga_asociada()
          {
               return $this->concentracion_droga_asociada;
          }
          function activo_pedido()
          {
               return $this->activo_pedido;
          }
          function muestra_guardia()
          {
               return $this->muestra_guardia;
          }
          function texto_tesauro()
          {
               return $this->texto_tesauro;
          }
          function descriptionid()
          {
               return $this->descriptionid;
          }
          function estado_anterior_mapeo()
          {
               return $this->estado_anterior_mapeo;
          }
          
          
          
      	     function arreglo_foraneo_codigo()
             {
                 return $this->arreglo_foraneo_codigo;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
          }
          function via_asigna($campo)
          {
               $this->via=$campo;
               
          }
          function descripcion_asigna($campo)
          {
               $this->descripcion=$campo;
               
          }
          function cantidad_asigna($campo)
          {
               $this->cantidad=$campo;
               
          }
          function precio_kairos_asigna($campo)
          {
               $this->precio_kairos=$campo;
               
          }
          function stock_minimo_asigna($campo)
          {
               $this->stock_minimo=$campo;
               
          }
          function stock_maximo_asigna($campo)
          {
               $this->stock_maximo=$campo;
               
          }
          function ajuste_stock_asigna($campo)
          {
               $this->ajuste_stock=$campo;
               
          }
          function fecha_ajuste_stock_asigna($campo)
          {
               $this->fecha_ajuste_stock=$campo;
               
          }
          function idunidad_asigna($campo)
          {
               $this->idunidad=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function presentacion_asigna($campo)
          {
               $this->presentacion=$campo;
               
          }
          function morden_asigna($campo)
          {
               $this->morden=$campo;
               
          }
          function activo_asigna($campo)
          {
               $this->activo=$campo;
               
          }
          function dosis_asigna($campo)
          {
               $this->dosis=$campo;
               
          }
          function concentracion_asigna($campo)
          {
               $this->concentracion=$campo;
               
          }
          function precio_comercial_asigna($campo)
          {
               $this->precio_comercial=$campo;
               
          }
          function droga_asociada_asigna($campo)
          {
               $this->droga_asociada=$campo;
               
          }
          function dosis_droga_asociada_asigna($campo)
          {
               $this->dosis_droga_asociada=$campo;
               
          }
          function concentracion_droga_asociada_asigna($campo)
          {
               $this->concentracion_droga_asociada=$campo;
               
          }
          function activo_pedido_asigna($campo)
          {
               $this->activo_pedido=$campo;
               
          }
          function muestra_guardia_asigna($campo)
          {
               $this->muestra_guardia=$campo;
               
          }
          function texto_tesauro_asigna($campo)
          {
               $this->texto_tesauro=$campo;
               
          }
          function descriptionid_asigna($campo)
          {
               $this->descriptionid=$campo;
               
          }
          function estado_anterior_mapeo_asigna($campo)
          {
               $this->estado_anterior_mapeo=$campo;
               
          }
          
          
          
	      function foranea_codigo($codigo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM catalogo_presentacion WHERE codigo=$codigo");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_codigo = $pro;		                              		
			}
			
      
}
?>