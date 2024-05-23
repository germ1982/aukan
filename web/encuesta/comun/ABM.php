<?php
	class ABM{
	    //el cuarto parametro de insertar indica si mando yo la clave
		var $tabla;
		var $consulta;
		var $enlace;
		var $cantidadCampos;
		var $tipoCampos;
		var $campos;
		var $nombreTabla;
		var $ultimaConsulta;
		var $bufferConsulta;
		var $clave_generada;
		//*****************************************************************//		
		//**************************** CONSTRUCTOR ************************//
		//*****************************************************************//

		//Crea un objeto ABM en base a una consulta y un enlace a una base de datos
		function ABM($consulta,$enlace,$valoresCampo){
			$numpalabras = count(explode(" ", $consulta));
			$cantCorchetes = count(explode("[", $consulta));
			if (($numpalabras == 1) || ($cantCorchetes >0)){
				$this->nombreTabla = $consulta;
				$consulta = "SELECT * FROM $consulta";
				
			}else{
				$consultaMay = strtoupper($consulta);
				$posFrom = strpos($consultaMay,"FROM");
				$posWhere = strpos($consultaMay,"WHERE");
				if ($posFrom === false){
					return 0;
				}else{ 
					if ($posWhere === false){
						$this->nombreTabla = trim(substr($consulta,$posFrom+5));
					}else{
						$this->nombreTabla = trim(substr($consulta,$posFrom+5,$posWhere-$posFrom-6));
					}		
				}
			} 
	//		@mysql_query("SET SESSION character_set_results = 'latin1'",$enlace);
	//         @mysql_query("SET NAMES 'iso-8859-1'");
			$resultado = mysql_query($consulta,$enlace);  	
				
   			for ($j = 0; $j < (mysql_num_fields($resultado)); $j++)
			{                        
           		$nombre_campo_aux = mysql_fetch_field($resultado, $j);				
				$nombre_campo = $nombre_campo_aux->name;
        		$campos[$j] = $nombre_campo; 				
				if (strcmp($valoresCampo[$nombre_campo],"on")== 0)				
				     $tipoCampos[$nombre_campo] = "smallint";					 
				else	 
				{
					if (($valoresCampo[$nombre_campo] != "") && ($nombre_campo_aux->primary_key!=1))
					{							
				        $tipoCampos[$nombre_campo] = $nombre_campo_aux->type;										    
					}						
					else
					{
					    if ($nombre_campo_aux->primary_key==1)
                           //  $tipoCampos[$nombre_campo] = $nombre_campo_aux->type;
						   $tipoCampos[$nombre_campo] = 'es_clave';
						  
					}   
				}	
       		}//fin de for
			
       		$this->campos = $campos;
			$this->tipoCampos = $tipoCampos;
			$this->tabla = $arreglo;
			$this->consulta = $consulta;
			$this->enlace = $enlace;
			$this->cantidadCampos = mysql_num_fields($resultado);
			$this->ultimaConsulta = "";
			$this->bufferConsulta = "";
			$this->clave_generada = "";
   			return true;
		}
						
		function actualizar()
		{		
			$realizado = false;
			$datosTodos = func_get_arg(0); //Primer argumento, el arreglo de datos
			$campoClave = func_get_arg(1); //Si hay clausula where, es actualización, sino, inserción
			$set = "";
			$consultaN = "";																			
			$set = ""; 
			foreach($datosTodos as $nombreCampo => $datoCampo)
			{					
				//Armado de la consulta según el tipo de dato del campo
				switch($this->tipoCampos[$nombreCampo])
				{
				    case "counter" 		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";			
											break;
					case "varchar" 		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) .  " = '".utf8_decode($datoCampo)."' ";	
											break;
					case "string" 		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = '".str_replace("'","\'",utf8_decode($datoCampo))."' ";	
											break;
                    case "blob" 		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = '".str_replace("'","\'",$datoCampo)."' ";	
											break;																				
					case "int"  		:   if ($datoCampo != "")	
												$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";		
											else
												$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = 0 ";			
											break;
					case "smallint"		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";			
											break;
					case "double"		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";			
											break;
					case "time" 		:	if ($datoCampo == "00:00:00"){
												$datoCampo = "00:00:00";
											}else{
											      list($h,$m,$s) = split(":",$datoCampo);
												  if ($s != "") 
												      $datoCampo = $h.":".$m.":".$s;
												  else
												      $datoCampo = $h.":".$m.":00";		
												 }
											$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = '$datoCampo' ";	
											break;
					case "date"		    :	if ($datoCampo == ""){
												$datoCampo = "NULL";
											}else{
											     list( $dd, $mm, $aa ) = split( '[/-]', $datoCampo );
												 $datoCampo = "'$aa-$mm-$dd'";
												  }
										    $set .= "," . $this->nombreTabla.".".normal($nombreCampo). " = $datoCampo ";
											break;						
					case "currency"		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";			
											break;
					case "on"	 		:	if ($datoCampo == "on"){
												$datoCampo = "1";
											}
											if ($datoCampo == ""){
												$datoCampo = "0";
											}
											if ($datoCampo == "1"){
											    $datoCampo = "1";
											}
											if ($datoCampo == "0"){
												$datoCampo = "0";
											}													
											$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";
											break;
					case "longbinary"	:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) .  " = '".utf8_decode($datoCampo)."' ";				
											break;
					case "longchar"		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) .  " = '".utf8_decode($datoCampo)."' ";			
											break;	
					case "longtext"		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) .  " = '".utf8_decode($datoCampo)."' ";
											break;
					case "byte"			:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";				
											break;
					case "real"			:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";				
											break;
					case "guid"			:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";				
											break;
					case "decimal"		:	$set .= "," . $this->nombreTabla . "." . normal($nombreCampo) . " = $datoCampo ";				
											break;
									
				}				
		   }//fin de for 							
										
		   if ($set[0] == ",")
		       $set=substr($set,1);				
		   //Operación de Actualización
		   $consulta_actualiza = "UPDATE " . $this->nombreTabla . " SET $set WHERE $campoClave";  
//	 echo $consulta_actualiza;
				
		   if (mysql_query($consulta_actualiza,$this->enlace))
		   {				
			   $setN .= $set;
			   $realizado = true;
		   }
		   else				
			   return false;						
		
		   return $realizado;
	}
	function generarClave($campo,$objeto){
			$resultado = mysql_query( "SELECT MAX($campo) AS ultima_clave FROM " . $objeto->nombreTabla,$objeto->enlace);  
			//$indiceCampo = odbc_field_num($resultado,"ultima_clave");
			$arreglo_ultimo = mysql_fetch_array($resultado,MYSQL_ASSOC);
			$ultimo = $arreglo_ultimo["ultima_clave"];
			return $ultimo + 1;
		}
	function devolverClave()
	{
	    return $this->clave_generada;
	}
	function insertar()
	{
	    $realizado = false;
		$datosTodos = func_get_arg(0); //Primer argumento, el arreglo de datos
		$campoClave = func_get_arg(1); //Si hay clausula where, es actualización, sino, inserción
		$que_es = func_get_arg(2); //me dice si tengo que generar una clave
		$clave_si = func_get_arg(3); //me dice si voy a insertar y si yo le pongo la clave

	    //Inserción
		$into = "";
		$values = "";
		foreach($datosTodos as $nombreCampo => $datoCampo)
		{ 
		    //Armado de la consulta según el tipo de dato del campo
			switch($this->tipoCampos[$nombreCampo])
			{
				case "counter" 		:	break;
				case "varchar" 		:   $into .= "," . normal($nombreCampo);	
										$values .= ",'". " = '".utf8_decode($datoCampo)."' ";
										break;
				case "string" 		:	$into .= "," .normal($nombreCampo);	
										$values .= ",'".str_replace("'","\'",utf8_decode($datoCampo))."' ";
										break;
                case "blob" 		:	$into .= "," . normal($nombreCampo);	
										$values .= ",'".utf8_decode($datoCampo)."' ";
										break;																		
				case "int"		    :   if ($datoCampo != "")
									    {	
										    $into .= "," . normal($nombreCampo);
										    $values .= ",$datoCampo ";		
										}
										else
										{
										    $into .= "," . normal($nombreCampo);
										    $values .= ",0 ";
										}	
										break;
				case "smallint"		:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";			
										break;
				case "double"		:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";			
										break;
				case "time"		    :	$into .= "," . normal($nombreCampo);
									    if ($datoCampo == "00:00:00"){
											$datoCampo = "00:00:00";
										}else{
										    list($h,$m,$s) = split(":",$datoCampo);
											if ($m == "")
											    $m = "00";
											if ($s =="")
											    $s = "00";	
									    $datoCampo = $h.":".$m.":".$s;														    
										}
										$values .= ",'$datoCampo' ";	
										break;
				case "date"		:	    $into .= "," . normal($nombreCampo);
										if ($datoCampo == ""){
											$datoCampo = "NULL";
										}else{
										    list( $dd, $mm, $aa ) = split( '[/-]', $datoCampo );
											$datoCampo = "'$aa-$mm-$dd'";
										}
										$values .= ",$datoCampo ";	
										break;						
				case "currency"		:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";			
										break;
				case "on"			:	if ($datoCampo == "on"){
											$datoCampo = "1";
										}
										if ($datoCampo == ""){
											$datoCampo = "0";
										}	
										$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";
										break;
				case "longbinary"	:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";				
										break;
				case "longchar"		:	$into .= "," . utf8_decode(normal($nombreCampo));
										$values .= ",'".str_replace("'","\'",utf8_decode($datoCampo))."' ";			
										break;	
				case "longtext"		:	$into .= "," . utf8_decode(normal($nombreCampo));
										$values .= ",'".str_replace("'","\'",utf8_decode($datoCampo))."' ";
										break;
				case "byte"			:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";				
										break;
				case "real"			:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";				
				  					    break;
				case "guid"			:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";				
										break;
				case "decimal"		:	$into .= "," . normal($nombreCampo);
										$values .= ",$datoCampo ";				
										break;
			}				
		}								
				//Se sacan las comas izquierdas
				$into=substr($into,1);
				$values=substr($values,1);								
				//Se verifica que la clave pertenezca a la tabla				
				if (isset($this->tipoCampos[$campoClave])){				
					//Si la clave no es de tipo autonumérico se la genera
					if (strcmp($this->tipoCampos[$campoClave],"auto_increment") != 0 && ($que_es != 1) && ($clave_si == -1)){
						//Se obtiene una nueva clave
						$clave = $this->generarClave($campoClave,$this);
						$this->clave_generada = $clave;
						$into = $into . ",$campoClave";
						$values = $values . ",$clave";
					}
					else
					{
					    if ($clave_si != -1 )
						{
						    $clave = $clave_si;
						    $into = $into . ",$campoClave";
						    $values = $values . ",$clave";
						}
					}						
					//Inserción
					$consulta_inserta = "INSERT INTO " . $this->nombreTabla . "($into) VALUES ($values)";  
					//Protección contra reinserción por recarga de página
//		echo $consulta_inserta;				
//@mysql_query("SET SESSION character_set_results = 'latin1'",$this->enlace);
//	         @mysql_query("SET NAMES 'iso-8859-1'");	
					if (mysql_query($consulta_inserta,$this->enlace))
					{
						$this->ultimaConsulta = $consulta_inserta;
						$this->bufferConsulta = $buffer;
						$realizado = true;
					}
					else					
						return false;				
			    }							
	    return true;				
	}	
		//*****************************************************************//		
		//********************* MÉTODOS DE ASISTENCIA *********************//
		//*****************************************************************//
}//fin de abm
		//Retorna la cantidad de campos
		function cantidadCampos(){
			return $this->cantidadCampos;
		}
				
		//Retorna el nombre de la tabla 
		function nombreTabla(){
			return $this->nombreTabla;
		}
		
		//Retorna la última consulta realizada con exito
		function ultimaConsulta(){
			return $this->ultimaConsulta;
		} 
		
		//Reconecta el objeto a la base de datos
		/*Serialize funciona perfectamente pero por algún motivo se pierde
		  la conección con la base de datos, aún usando odbc_pconnect     */
		function reconectar($enlace){
			$this->enlace = $enlace;
			return true;
		}	
		function normal($nombre){
			if (substr_count($nombre," ") > 0){
				$normalizado = "[$nombre]";
			}else{
				$normalizado = $nombre;
			}	
			return $normalizado;
		}

?>
