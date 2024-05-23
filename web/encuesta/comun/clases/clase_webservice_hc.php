<?
	class clase_webservice_hc 
	{
		var $soap='';
		var $session ='';
		function clase_webservice_hc()
		{
			
		}
		function conectarse($direccion,$lugar,$usuario,$password)
		{
			//if ($lugar == 'FUNSAL')
			//{
				//$direccion = "http://192.168.0.149/geceq/index.php?r=clinica/WebService/paciente";
			    $this->soap = new nusoap_client($direccion,'wsdl');
    			$this->session=$this->soap->call('login',array('name'=>$usuario,'password'=>$password));
			//}
		}
		function session()
		{
			return $this->session;
		}
		function soap()
		{
			return $this->soap;
		}
		function call($function,$parametros)
		{
			return $this->soap->call("$function",$parametros);
		}
		
	}
?>