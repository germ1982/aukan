<?
	class clase_sala extends clase_evolucion
	{
		
		function clase_sala($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    $consulta = "SELECT * FROM hc_sala WHERE idhc_sala=$id";
			$bd->select($consulta);
			$arreglo = $bd->registro();	
			$this->id=$arreglo['idhc_sala'];
			$this->asignar_datos($arreglo);			                              		
		}
		function armar_xml()
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$xml=parent::armar_xml();
			
			$bd->select("SELECT * FROM hc_infectologico WHERE idhc=$this->id AND lugar=2");
	 		$bandera = 0;
	 		while ($hc_in = $bd->registro())
	 		{
	     		if ($bandera ==0)
		 		{		     		    
		     	    $xml.= "<table><thead><tbody><tr><th><strong>ANTIBIOTICOS:</strong></th></tr>";
			 		$bandera = 1;
		 		}
		 		$xml.= "<tr><td>".$hc_in["nombre"]." ".$hc_in["dia"]." dias</td></tr>";		 		
	 		}
	 		if ($bandera ==1)
	 			$xml.="</tbody></thead></table>";
	 		return $xml;		
		}					
	}
?>