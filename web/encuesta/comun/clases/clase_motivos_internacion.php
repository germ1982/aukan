<?
	class clase_motivos_internacion 
	{
		var $id=0;
		var $nombre   ='';
		var $arreglo_motivos_internacion = '';
		
		
		function clase_motivos_internacion($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->set_names();
			$bd->select("SELECT * FROM motivos_internacion WHERE codigo=$id");
			$diag = $bd->registro();
			$this->nombre = $diag['descripcion'];			
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
		function arreglo_motivos_internacion()
		{
			return $this->arreglo_motivos_internacion;
		}
		function todos_motivos_internacion()
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM motivos_internacion ORDER BY descripcion ASC");
			$pro = new clase_listar();
			 
			for($i=0;$i<=$bd->numero_filas();$i++)
			{
				$fila = $bd->registro();
				if ($fila['codigo'] != '' && $fila['codigo'] != 0)
					$pro->introducirElemento($fila);
			}
			$this->arreglo_motivos_internacion = $pro;
		}
	}
?>