<?
	class clase_webservice_kairos 
	{
		
		
		function conectarse()
		{
			$soap = new nusoap_client("http://localhost/traumatologia/webservice/catalogo_kairos/callCatalogoKairos.php");
			return $soap;
		}
		
	}
?>