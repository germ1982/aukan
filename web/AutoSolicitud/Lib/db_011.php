<?php
#acceso al SQL SERVER
class BaseDatos {
	var $HOSTNAME="10.1.73.244\MDS";
	//var $HOSTNAME="10.1.73.21";
	var $BASEDATOS="Recepcion";
	var $USUARIO="usuariobd";
	var $CLAVE='clienteapp';
	//var $USUARIO="usermineria";
	//var $CLAVE='solution';
	//var $PORT="1433";
	var $CONEXION;
	var $QUERY;
	var $NumTupla;
	var $CantTupla;
	

  function Iniciar(){
//echo "llama a la funcion";
       $conexion = mssql_connect($this->HOSTNAME,$this->USUARIO,$this->CLAVE);
       if ($conexion){
	       //echo "se conecto";
	       if (mssql_select_db($this->BASEDATOS,$conexion))	       {
			//echo "ok en la ejecucion consulta";       
		        $this->CONEXION = $conexion;
				unset($this->QUERY);
				return true;
	       }  else {     echo "Error al conectar la base de datos<br>";		}
	   }else  echo "Error al conectar al servidor<br>"; 
      return false;
  }



   function Cerrar(){
  // echo $this->CONEXION;
  	return mssql_close($this->CONEXION);
	//return true;
   }

     /*Esta funcion ejecuta una consulta SELECT sobre la base de datos. El resultado es un arreglo donde
    cada elemento $arreglo_datos[$i] contiene una tupla del resultado (que tambien se guarda como
    un arreglo). */
  function Select($consulta){
  	//$this->Iniciar();
	$this->NumTupla=0;
	if ($this->QUERY = mssql_query($consulta))
     {
			$this->CantTupla = mssql_num_rows($this->QUERY);
			return $this->QUERY;
     }else {
			//$this->Cerrar();
			return false;
	  }
  }

 function Registro() {
 	if($temp = mssql_fetch_assoc($this->QUERY)) {
		$this->NumTupla++;
		return $temp;
	}else
		return false;
 }

 function Cantidad() {
  	return mssql_num_rows($QUERY);
  }
  
   function Cant() {
     return $this->CantTupla;
	}

function Ejecutar($consulta){
  //echo "<br>"."la coneccion en la q se hago Ejecutar es : ". $this->CONEXION."<br>";

    if ($this->QUERY = mssql_query($consulta))
	{  //echo $consulta."<br>";
		return true;
	} else {
		//echo "FALLOOOOOO!!!!!!!!!!".$consulta."<br>";
	  return false;
	 }
  }



// Solo para MYSQL
/*
function devuelveIDEjecuta($consulta){
//echo "<br>"."la coneccion en la q se hago devuelveIDEjecuta es : ". $this->CONEXION."<br>";
  	//$this->Iniciar();
    if ($this->QUERY = mysql_query($consulta))
	{
	 $id = mysql_insert_id();
      	 // $this->Cerrar();
		  //echo $consulta."Coorreeeccttaaa"."<br>";
          //echo "el valor que devuelve es ". $id;
	  	  return $id;
	} else {
		//echo "FALLOOOOOO!!!!!!!!!!".$consulta."<br>";
        //  $this->Cerrar();
	  		return false;
	 }
  }


  function IniciarTransaccion(){
  //echo "<br>"."la coneccion en la q se inicia la transaccion es: ". $this->CONEXION."<br>";
    if ($this->QUERY = mysql_query("BEGIN",$this->CONEXION))
		  return true;
	else
        return false;
	 
  }
  function RollbackTransaccion(){
   //echo "<br>"."la coneccion en la q voy a hacer rolback es:  ". $this->CONEXION."<br>";
    if ($this->QUERY = mysql_query("ROLLBACK",$this->CONEXION))
		  return true;
	else
        return false;
	 
  }
    function CommitTransaccion(){
	// echo "<br>"."la coneccion en la q se inicia la hago COMMIT es: ". $this->CONEXION."<br>";
    if ($this->QUERY = mysql_query("COMMIT",$this->CONEXION))
		  return true;
	else
        return false;
	 
  }
*/

// Solo para MSSQL
function IniciarTransaccion() {
  mssql_query("BEGIN TRANSACTION",$this->CONEXION);
}

function CommitTransaccion() {
  mssql_query("COMMIT",$this->CONEXION);
}

function RollbackTransaccion() {
  mssql_query("ROLLBACK",$this->CONEXION);
}

function mssql_insert_id() {
  $id = "";

  $rs = mssql_query("SELECT @@identity AS id");
  if ($row = mssql_fetch_row($rs)) {
   $id = trim($row[0]);
  }
  mssql_free_result($rs);

  return $id;
}

}
?>
