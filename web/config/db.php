<?php
require __DIR__ . '/../../vendor/autoload.php';

class BaseDatos
{
	var $CONEXION;
	var $QUERY;
	var $NumTupla;
	var $CantTupla;

	function Iniciar()
	{ 
		$dirBaseDatos = '10.1.73.249';
		$BaseDatos = 'familia';
		$Usuario = 'root';
		$CLAVE = '0303456';

		$conexion = mysqli_connect($dirBaseDatos, $Usuario, $CLAVE, $BaseDatos);
		if ($conexion) {
			//echo "se conecto";
			//if (mysql_select_db($this->BASEDATOS,$conexion))	//Deprecado  mysql_select_db      
			//if (mysqli_select_db($conexion,$this->BASEDATOS))
			//{
			//echo "ok en la ejecucion consulta";       
			$this->CONEXION = $conexion;
			if (!mysqli_set_charset($this->CONEXION, "utf8")) {
				printf("Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($this->CONEXION));
			}
			//mysqli_query("SET NAMES 'utf8'");
			unset($this->QUERY);
			return true;
			//}  
			//else
			//{     echo "Error al conectar la base de datos";		}
		} else  echo "Error al conectar la base de datos";
		//print_r($this);
		return false;
	}


	function Cerrar()
	{
		// echo $this->CONEXION;
		return mysqli_close($this->CONEXION);
		//return true;
	}

	function Liberar($resultado)
	{
		// libera resultados
		//return mysqli_close($this->CONEXION);
		mysqli_free_result($resultado);
		//return true;
	}

	/*Esta funcion ejecuta una consulta SELECT sobre la base de datos. El resultado es un arreglo donde
    cada elemento $arreglo_datos[$i] contiene una tupla del resultado (que tambien se guarda como
    un arreglo). */
	function Select($consulta)
	{
		//$this->Iniciar();
		//$conexion = mysqli_connect($this->HOSTNAME,$this->USUARIO,$this->CLAVE,$this->BASEDATOS);
		if (!mysqli_set_charset($this->CONEXION, "utf8")) {
			printf("Select: Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($this->CONEXION));
		}
		$this->NumTupla = 0;
		if ($this->QUERY = mysqli_query($this->CONEXION, $consulta)) {
			$this->CantTupla = mysqli_num_rows($this->QUERY);

			return $this->QUERY;
		} else {
			//$this->Cerrar();
			return false;
		}
	}

	function Registro()
	{
		if ($temp = mysqli_fetch_assoc($this->QUERY)) {
			$this->NumTupla++;
			return $temp;
		} else
			return false;
	}

	function Cantidad()
	{
		return mysqli_num_rows($this->QUERY);
	}

	function Cant()
	{
		return $this->CantTupla;
	}

	function Ejecutar($consulta)
	{
		//echo "<br>"."la coneccion en la q se hago Ejecutar es : ". $this->CONEXION."<br>";
		//$conexion = mysqli_connect($this->HOSTNAME,$this->USUARIO,$this->CLAVE,$this->BASEDATOS);
		if ($this->QUERY = mysqli_query($this->CONEXION, $consulta)) {  //echo $consulta."<br>";
			return true;
		} else {
			//echo "FALLOOOOOO!!!!!!!!!!".$consulta."<br>";
			return false;
		}
	}

	function devuelveIDEjecuta($consulta)
	{
		//echo "<br>"."la coneccion en la q se hago devuelveIDEjecuta es : ". $this->CONEXION."<br>";
		//$this->Iniciar();
		//$conexion = mysqli_connect($this->HOSTNAME,$this->USUARIO,$this->CLAVE,$this->BASEDATOS);
		if ($this->QUERY = mysqli_query($this->CONEXION, $consulta)) {
			$id = mysqli_insert_id($this->CONEXION);
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


	function IniciarTransaccion()
	{
		//echo "<br>"."la coneccion en la q se inicia la transaccion es: ". $this->CONEXION."<br>";
		//if ($this->QUERY = mysqli_query("BEGIN",$this->CONEXION))
		if (mysqli_begin_transaction($this->CONEXION, "BEGIN")) {
			return true;
		} else
			return false;
	}
	function RollbackTransaccion()
	{
		//echo "<br>"."la coneccion en la q voy a hacer rolback es:  ". $this->CONEXION."<br>";
		//if ($this->QUERY = mysqli_query("ROLLBACK",$this->CONEXION))
		if (mysqli_rollback($this->CONEXION, "ROLLBACK"))
			return true;
		else
			return false;
	}
	function CommitTransaccion()
	{
		// echo "<br>"."la coneccion en la q se inicia la hago COMMIT es: ". $this->CONEXION."<br>";
		//if ($this->QUERY = mysqli_query("COMMIT",$this->CONEXION))
		if (mysqli_commit($this->CONEXION, "COMMIT"))
			return true;
		else
			return false;
	}
}
