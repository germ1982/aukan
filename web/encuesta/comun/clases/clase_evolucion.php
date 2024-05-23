<?
	class clase_evolucion
	{
		var $id=0;
		var $nombre   ='';
		var $fecha = '';
		var $hora = '';
		var $texto = '';
		var $idprofesional = '';
		var $idepisodio = '';	
		var $idpaciente = 0;	
		var $titulo = '';
		
		function clase_evolucion($id)
		{
						                              		
		}
		function consultar_evolucion($table,$idtable,$id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    $consulta = "SELECT * FROM $table WHERE $idtable=$id";
			$bd->select($consulta);
			return $bd->registro();				
		}
		function asignar_datos($arreglo)
		{			
			$this->fecha=$arreglo['fecha'];
			$this->hora=$arreglo['hora'];
			$this->texto=$arreglo['texto_evolucion'];
			$this->idprofesional=$arreglo['idprofesional'];
			$this->idepisodio=$arreglo['idepisodio'];
			
		}
		function buscar_ubicacion($idepisodio,$id,$fecha)
		{
			$bd = new baseDatos();
			$bd->Conectarse();			
    		$bd->select("SELECT idcama FROM camaocup WHERE idepisodio = $idepisodio AND fecha<='$fecha' ORDER BY id desc");
			$camaocup = $bd->registro();			
			$bd->select("SELECT * FROM camas WHERE idcama=".$camaocup['idcama']);
			$cama=$bd->registro();						    
		    if ($cama['descripcion'] == 'VIP')
		        return 'SALA';
		    else 
		        return $cama['descripcion'];
		}
		function fecha()
		{
			return $this->fecha;
		}
		function hora()
		{
			return $this->hora;
		}
		function texto()
		{
			return $this->texto;
		}
		function idprofesional()
		{
			return $this->idprofesional;
		}
		function idepisodio()
		{
			return $this->idepisodio;
		}
		public function armar_xml()
		{
			$xml = "<paragraph>".$this->titulo."<br />".devolverFechaNormal($this->fecha)." ".horaRecortada($this->hora)."<br/>
			       ".$this->texto.".<br/></paragraph>";
			return $xml;
		}	
		function asignar_paciente($idpaciente)
		{
			$this->idpaciente = $idpaciente;
		}	
		function asignar_texto($txt)
		{
			$this->texto=$txt;
		}
		function titulo_asigna($dato)
		{
			$this->titulo=$dato;
		}
		function titulo()
		{
			return $this->titulo;
		}
		
	}
?>