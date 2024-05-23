<?php
    class clase_federador    
    {
    	var $soap='';
    	var $session = '';
    	function clase_federador()
    	{
    		$soap = new nusoap_client("http://179.43.114.70/federador/index.php?r=federador/WebService/paciente",'wsdl');
    		$this->soap=$soap;
    		$this->session = $this->soap->call('login',array('name'=>'funsal','password'=>'scpdt66'));
    	}    	
    	function soap()
    	{
 		    return $this->soap;   		
    	}
    	function session()
    	{
    		return $this->session;
    	}
    	function call($funcion,$arreglo_parametros)
    	{
    		return $this->soap->call("$funcion",$arreglo_parametros);
    	}    	
    }    
?>