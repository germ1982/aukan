<?
	class clase_practicas 
	{
		var $id=0;
		var $descripcion   ='';
		var $codigo = '';
		var $es_rayos='';
		var $que_es = 0;
		
		
		function buscar_nomenclador($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			//$bd->set_names();
			$bd->select("SELECT * FROM nomenclador WHERE idnomenclador =$id");
			$nome = $bd->registro();
			$this->descripcion = $nome['descripcion'];			
			$this->id = $id;
			$this->codigo = $nome['codigo'];
			$this->es_rayos=$nome['activado_pacs'];
			//que_es indica si es eco rayos, tomografia,resonancia
			$this->que_es=$nome['que_es'];
			//return $this->descripcion;
		}
		function buscar_descripcion($codigo)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			//$bd->set_names();
			$bd->select("SELECT * FROM nomenclador WHERE codigo ='$codigo'");
			$nome = $bd->registro();
			$this->descripcion = $nome['descripcion'];			
			$this->id = $id;
			$this->codigo=$codigo;
			return $this->descripcion;
		}
                function buscar_descripcion_id($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			//$bd->set_names();
			$bd->select("SELECT * FROM nomenclador WHERE idnomenclador =$id");
			$nome = $bd->registro();
			$this->descripcion = $nome['descripcion'];			
			$this->codigo = $nome['codigo'];
			$this->id = $id;
			return $this->descripcion;
		}
		function descripcion()
		{			
			return $this->descripcion;
		}
		function id()
		{
			return $this->id;
		}
		function codigo()
		{
			return $this->codigo;
		}
		function es_rayos()
		{
			return $this->es_rayos;
		}
		function que_es()
		{
			return $this->que_es;
		}
	}
?>
