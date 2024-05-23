<?php
	class catalogo 
	{
		var $codigo=0;
		var $descripcion ='';
		var $dosis = '';
		var $concentracion = '';
		var $dosis_droga_asociada = '';
		var $droga_asociada = '';
		var $id_presentacion = 0;
		var $concentracion_droga_asociada = '';
		var $via= '';
		var $presentacion = '';
		var $stock_minimo = '';
		var $cantidad = 0;
		var $arreglo = array();
		var $filas = '';
		var $resultado = '';
		var $nombre_presentacion = '';
		var $idclasificacion = '';
		var $nombre_descartable = '';
		var $arreglo_presentaciones = '';
		var $precio_comercial = '';
		
		private $mysql;
		private $result;
		public function __construct($mysql)
		{
        	$this->mysql=$mysql;
    	}
		function buscarCatalogo($texto_buscado)
		{
			return $this->mysql->select("SELECT catalogo.codigo,catalogo.descripcion,catalogo_presentacion.dosis,catalogo_presentacion.concentracion,
	  		      catalogo_presentacion.droga_asociada,catalogo_presentacion.id,catalogo_presentacion.dosis_droga_asociada,
	  		      catalogo_presentacion.concentracion_droga_asociada,catalogo_presentacion.via,catalogo_presentacion.presentacion,catalogo_presentacion.nombre
	  		      FROM catalogo LEFT JOIN catalogo_presentacion ON (catalogo.codigo=catalogo_presentacion.codigo) 
	  		      WHERE catalogo.descripcion LIKE '%$texto_buscado%' OR droga_asociada LIKE '%$texto_buscado%'");					
		}
		
		function siguiente_fila()
		{
			$fila = $this->mysql->registro_filas();
			$this->codigo = $fila[0];
			$this->descripcion = $fila[1];
			$this->dosis = $fila[2];
			$this->concentracion = $fila[3];
			$this->dosis_droga_asociada = $fila[6];
			$this->droga_asociada = $fila[4];
			$this->id_presentacion = $fila[5];
			$this->concentracion_droga_asociada = $fila[7];
			$this->via = $fila[8];
			$this->presentacion = $fila[9];
			$this->nombre = $fila[10];
		    return $fila;	
		}
		function codigo()
		{
			return $this->codigo;
		}
		function descripcion()
		{
			return $this->descripcion;
		}
		function dosis()
		{
			return $this->dosis;
		}
		function concentracion()
		{
			return $this->concentracion;
		}
		function dosis_droga_asociada()
		{
			return $this->dosis_droga_asociada;
		}
		function droga_asociada()
		{
			return $this->droga_asociada;
		}
		function id_presentacion()
		{
			return $this->id_presentacion;
		}
		function concentracion_droga_asociada()
		{
			return $this->concentracion_droga_asociada;
		}
		function via()
		{
			return $this->via;
		}
		function presentacion()
		{
			return $this->presentacion;
		}
		function nombre()
		{
			return $this->nombre;
		}
		function devolverIdSegunNombre($producto)
		{
			if ($producto == 'cloruro_sodio')
			{
				$this->codigo=6408;
				$this->id_presentacion=6314;
			}
			if ($producto == 'cloruro_potasio')
			{
				$this->codigo=973;
				$this->id_presentacion=8258;
			}
			if ($producto == 'sulfato_magnesio')
			{
				$this->codigo=6441;
				$this->id_presentacion=5895;
			}
			if ($producto == 'fosfato_sodio')
			{
				$this->codigo=7143;
				$this->id_presentacion=8404;
			}
			if ($producto == 'gluconato_calcio')
			{
				$this->codigo=6451;
				$this->id_presentacion=5905;
			}
			if ($producto == 'complejo_vitaminico')
			{
				$this->codigo=7188;
				$this->id_presentacion=7528;
			}
		}
		function stock_minimo()
		{
			return $this->stock_minimo;
		}
		function cantidad()
		{
			return $this->cantidad;
		}
		function datos_producto($iddroga,$idpresentacion)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT catalogo.codigo,catalogo.descripcion,catalogo_presentacion.dosis,catalogo_presentacion.concentracion,
	  		      catalogo_presentacion.droga_asociada,catalogo_presentacion.id,catalogo_presentacion.dosis_droga_asociada,
	  		      catalogo_presentacion.concentracion_droga_asociada,catalogo_presentacion.via,catalogo_presentacion.presentacion,catalogo_presentacion.nombre,
	  		      cantidad,stock_minimo,idclasificacion,precio_comercial
	  		      FROM catalogo LEFT JOIN catalogo_presentacion ON (catalogo.codigo=catalogo_presentacion.codigo) 
	  		      WHERE catalogo.codigo =$iddroga AND  id=$idpresentacion");
			$fila = $bd->registro();
			$this->codigo = $fila['codigo'];
			$this->descripcion = $fila['descripcion'];
			$this->dosis = $fila['dosis'];
			$this->concentracion = $fila['concentracion'];
			$this->dosis_droga_asociada = $fila['dosis_droga_asociada'];
			$this->droga_asociada = $fila['droga_asociada'];
			$this->id_presentacion = $fila['id'];
			$this->concentracion_droga_asociada = $fila['concentracion_droga_asociada'];
			$this->via = $fila['via'];
			$this->presentacion = $fila['presentacion'];
			$this->nombre = $fila['nombre'];
			$this->stock_minimo =$fila['stock_minimo']; 
			$this->cantidad =$fila['cantidad'];
			$this->idclasificacion =$fila['idclasificacion'];
			$this->precio_comercial = $fila['precio_comercial'];
			$this->nombre_descartable=$fila['descripcion']." ".$fila['nombre'];
			$this->nombre_presentacion=$fila['descripcion']." ".$fila['dosis'].' '.$fila['concentracion']." ".$fila['droga_asociada']." ".$fila['dosis_droga_asociada']." ".$fila['concentracion_droga_asociada']." ".$fila['via']." ".$fila['presentacion'];
		}
		function nombre_presentacion()
		{
			return $this->nombre_presentacion;
		}
		function devolverCodigos($iddroga,$idpresentacion)
		{			
			$kairos = new clase_webservice_kairos();
			$soap= $kairos->conectarse();
			//el primer parametro es el lugar
			$arreglo = $soap->call("devolverCodigos", array("idlugar" => 1,"codigo"=>$iddroga,"idpresentacion"=>$idpresentacion)); 
			$a[0] = $arreglo[0];
			$a[1] = $arreglo[1];
			return $a;			
		}
		function nombreDroga($idcatalogo)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT descripcion FROM catalogo WHERE codigo =$idcatalogo");
			$fila = $bd->registro();
			$this->codigo = $idcatalogo;
			$this->descripcion = $fila['descripcion'];
		}
		function idclasificacion()
		{
			return $this->idclasificacion;
		}
		function nombreDescartable()
		{
			return $this->nombre_descartable;
		}
		function presentaciones($codigo)
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
	    	$this->arreglo_presentaciones = $pro;
		}
		function arreglo_presentaciones()
		{
			return $this->arreglo_presentaciones;
		}
		function precio_comercial()
		{
			return $this->precio_comercial;
		}
	} 	      
?>
