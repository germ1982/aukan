<?
    class clase_mssql
    {	
	var $server    = '';
	var $link      = '';
	var $conect    = '';
	var $respuesta = '';
	var $arreglo   = '';
	
	public function __construct($server,$baseDatos,$user,$password)
	{
            $bandera = 1;
	    $this->link = mssql_connect($server, $user, $password) or $bandera = 0;

	    if($this->conect=mssql_select_db($baseDatos,$this->link)) 	    
                $bandera = 1;	    
	    else
    	        $bandera = 0;        
            return $bandera;
        }
	function select($sql)
	{
	    $this->respuesta = mssql_query($sql);	  
	    return $this->respuesta;	
	}
	function registros()
	{
	    $this->arreglo = mssql_fetch_array($this->respuesta);
	    return $this->arreglo;
	}
    }

?>
