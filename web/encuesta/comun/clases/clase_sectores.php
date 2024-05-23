<?
	class clase_sectores 
	{
		var $id=0;
		var $nombre   ='';
		var $arreglo_todos_sectores ='';
		
		
		function clase_sectores($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->set_names();
			$bd->select("SELECT nombre FROM sectores WHERE id=$id");
			$sectores = $bd->registro();
			$this->nombre = $sectores['nombre'];			
			$this->id = $id;
			return $this->nombre;
		}
		function nombre()
		{			
			return $this->nombre;
		}
		function id()
		{
			return $this->id;
		}
		function arreglo_todos_sectores()
             {
                 return $this->arreglo_todos_sectores;
             }
		function buscar_sector_id($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->set_names();
			$bd->select("SELECT nombre FROM sectores WHERE id=$id");
			$sectores = $bd->registro();
			$this->nombre = $sectores['nombre'];			
			$this->id = $id;
			return $this->nombre;
		} 
		function todos_sectores()
		{
			$bd = new baseDatos();
			$bd->Conectarse();		    
			$bd->select("SELECT * FROM sectores WHERE sector_agrupa<>0");				
			$pro = new clase_listar();			
								
	    	for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro(); 
	    		$pro->introducirElemento($fila); 
	    	}
	    	$this->arreglo_todos_sectores = $pro;		                              		
		}
		function todos_sectores_activos($estado)
		{
			$bd = new baseDatos();
			$bd->Conectarse();		    
			$bd->select("SELECT * FROM sectores WHERE estado=$estado ORDER BY nombre ASC");				
			$pro = new clase_listar();			
								
	    	for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro(); 
	    		$pro->introducirElemento($fila); 
	    	}
	    	$this->arreglo_todos_sectores = $pro;		                              		
		}
	}
?>