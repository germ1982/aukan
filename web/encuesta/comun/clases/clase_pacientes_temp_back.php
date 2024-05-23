<?
	class clase_pacientes_temp 
	{
		var $idpaciente=0;
		var $nombre   ='';
		var $fecha_nacimiento = '';
		var $sexo = '';
		var $documento = '';
		var $provincia = '';
		var $localidad = '';
		var $afiliado = '';
		var $idosocial = 0;
		var $apellido1= '';
		var $nombre1 = '';
		var $apellido2= '';
		var $nombre2 = '';
		var $paciente_federadoid='';
		var $hora_nacimiento = '';
		var $lugar_fisico_nacimiento = '';
		var $arreglo_paciente =''; 
		var $grupo_factor_madre = '';
		var $tipo_documento = '';
		var $direccion = '';
		var $telefono_fijo = '';
		var $telefono_celular = '';
		var $mail = '';
		var $estado_civil = '';
		var $ocupacion = '';
		var $codigo_postal = '';
		var $idpais = '';
		var $password = '';
		
		
		function clase_pacientes_temp($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->set_names();
			$bd->select("SELECT * FROM pacientes_temp WHERE idpaciente=$id");
			$nombre_pac = $bd->registro();
			self::asigna_arreglo($nombre_pac);
			return $this->nombre;
		}
		function asigna_arreglo($arreglo)
		{
			$this->nombre = $arreglo['nombre'];
			$this->fecha_nacimiento = $arreglo['fecha_nacimiento'];
			$this->idpaciente = $arreglo['idpaciente'];
			$this->sexo = $arreglo['sexo'];
			$this->documento=$arreglo['documento'];
			$this->provincia=$arreglo['provincia'];
			$this->localidad=$arreglo['localidad'];	
			$this->afiliado=$arreglo['afiliado'];
			$this->apellido1=$arreglo['apellido1'];
			$this->nombre1=$arreglo['nombre1'];	
			$this->apellido2=$arreglo['apellido2'];
			$this->nombre2=$arreglo['nombre2'];		
			$this->paciente_federadoid=$arreglo['paciente_federadoid'];
			$this->hora_nacimiento=$arreglo['hora_nacimiento'];
			$this->lugar_fisico_nacimiento=$arreglo['lugar_nacimiento'];
			$this->arreglo_paciente=$arreglo;
			$this->grupo_factor_madre=$arreglo['grupo_factor_madre'];
			$this->tipo_documento=$arreglo['tipo_documento'];
			$this->direccion = $arreglo['direccion'];
			$this->telefono_fijo = $arreglo['telefono_fijo'];
			$this->telefono_celular = $arreglo['telefono_celular'];
			$this->mail = $arreglo['mail'];
			$this->estado_civil = $arreglo['estado_civil'];
			$this->ocupacion = $arreglo['ocupacion'];
			$this->codigo_postal = $arreglo['codigo_postal'];
			$this->idpais = $arreglo['idpais'];
			$this->password = $arreglo['password'];
		}
		function tipo_documento()
		{
			return $this->tipo_documento;
		}
		function direccion()
		{
			return $this->direccion;
		}
		function telefono_fijo()
		{
			return $this->telefono_fijo;
		}
		function telefono_celular()
		{
			return $this->telefono_celular;
		}
		function mail()
		{
			return $this->mail;
		}
		function estado_civil()
		{
			return $this->estado_civil;
		}
		function ocupacion()
		{
			return $this->ocupacion;
		}
		function codigo_postal()
		{
			return $this->codigo_postal;
		}
		function idpais()
		{
			return $this->idpais;
		}
		function obra_social()
		{
			$bd = new baseDatos();
			$bd->Conectarse();			
			$bd->select("SELECT * FROM obra_social WHERE idpaciente=$this->idpaciente");
			$os = $bd->registro();
			$this->idosocial = $os['idobra_social'];
		}
		function nombre()
		{			
			return $this->nombre;
		}
		function fecha_nacimiento()
		{
			return $this->fecha_nacimiento;
		}
		function idpaciente()
		{
			return $this->idpaciente;
		}
		function sexo()
		{
			return $this->sexo;
		}
		function documento()
		{
			return $this->documento;
		}
		function localidad()
		{
			return $this->localidad;
		}
		function provincia()
		{
			return $this->provincia;
		}
		function afiliado()
		{
			return $this->afiliado;
		}
		function idosocial()
		{
			return $this->idosocial;
		}
		function apellido1()
		{
			return $this->apellido1;
		}
		function nombre1()
		{
			return $this->nombre1;
		}
		function apellido2()
		{
			return $this->apellido2;
		}
		function nombre2()
		{
			return $this->nombre2;
		}
		function paciente_federadoid()
		{
			return $this->paciente_federadoid;
		}
		function hora_nacimiento()
		{
			return $this->hora_nacimiento;
		}
		function lugar_fisico_nacimiento()
		{
			return $this->lugar_fisico_nacimiento;
		}
		function grupo_factor_madre()
		{
			return $this->grupo_factor_madre;
		}
		function password()
		{
			return $this->password;
		}
		function password_asigna($campo)
		{
			$this->password = $campo;
		}
		function hora_nacimiento_asigna($campo)
		{
		    $this->hora_nacimiento=$campo;
		}
		function lugar_fisico_nacimiento_asigna($campo)
		{
			$this->lugar_fisico_nacimiento=$campo;
		}
		function grupo_factor_madre_asigna($campo)
		{
			$this->grupo_factor_madre=$campo;
		}
		function arreglo_paciente()
		{
			return $this->arreglo_paciente;
		}
                /////////////asignaciones
                function nombre_asigna($campo)
                {
                    $this->nombre=$campo;
                }
                function documento_asigna($campo)
                {
                    $this->documento=$campo;
                }
                function direccion_asigna($campo)
                {
                    $this->direccion=$campo;
                }
                function localidad_asigna($campo)
                {
                    $this->localidad=$campo;
                }
                function provincia_asigna($campo)
                {
                    $this->provincia=$campo;
                }
                function telefono_fijo_asigna($campo)
                {
                    $this->telefono_fijo=$campo;
                }
                function telefono_celular_asigna($campo)
                {
                    $this->telefono_celular=$campo;
                }
                function mail_asigna($campo)
                {
                    $this->mail=$campo;
                }
		function federateCliPaciente($idpaciente,$paciente_federadoid)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			if ($bd->select("UPDATE pacientes_temp SET paciente_federadoid=$paciente_federadoid WHERE idpaciente=$idpaciente"))
			    return 1;
			else 
			    return 0;			
		}
		function verificarExistencia($numero_documento, $nombres, $apellidos, $fecha_nacimiento, $sexo)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM pacientes_temp 
						WHERE (fecha_nacimiento ='$fecha_nacimiento' AND documento='$numero_documento' AND nombre1='$nombres' 
						AND apellido1='$apellidos' AND sexo = '$sexo')");
			if ($bd->numero_filas() != 0)
			{
				$pac = $bd->registro();
				return $pac['idpaciente'];
			}
			else return 0;
		}
		function ponderateSearch($numero_documento,$nombre,$apellido,$fecha_nacimiento,$sexo)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$base = new baseDatos();
			$base->Conectarse();
			$ponderacion_sexo = 1;
			$ponderacion_fecha_nacimiento = 4;
			$ponderacion_documento = 8;
			$ponderacion_nombre = 2;
			$ponderacion_apellido = 6; 
			$ponderacion_total_minima = 0.4;
			$soundex_apellido = soundex($apellido);
			$soundex_nombre = soundex($nombre);
			//primero filtramos los posibles candidatos		
				$queryNombre ="SELECT idpaciente,nombre1,apellido1,fecha_nacimiento,documento,sexo	
							   FROM pacientes_temp 
							   WHERE nombre1 LIKE '%$nombre%' "; 
			
				$queryApellido ="SELECT idpaciente,nombre1,apellido1,fecha_nacimiento,documento,sexo	
								FROM pacientes_temp 
								WHERE (apellido1 LIKE '%$apellido%' AND fecha_nacimiento='$fecha_nacimiento') OR 
								      (apellido1 LIKE '%$apellido%' AND documento='$numero_documento') OR 
									  (nombre1 LIKE '%$nombre%' AND fecha_nacimiento='$fecha_nacimiento') OR 
									  (nombre1 LIKE '%$nombre%' AND documento='$numero_documento') OR 
									  (nombre1 LIKE '%$nombre%' AND apellido1 LIKE '%$apellido%')"; 				
			    $queryFechaNac = " SELECT idpaciente,nombre1,apellido1,fecha_nacimiento,documento,sexo	
									FROM pacientes_temp 
									WHERE (fecha_nacimiento ='$fecha_nacimiento' AND documento='$numero_documento') OR 
									(fecha_nacimiento='$fecha_nacimiento' AND apellido1 LIKE '%$apellido%')"; 
				$queryDocumento =" SELECT idpaciente,nombre1,apellido1,fecha_nacimiento,documento,sexo	
									FROM pacientes_temp 
									WHERE (documento = '$numero_documento')"; 
			
			$query = '';		
			$query = $query.$queryApellido." UNION ".$queryDocumento." UNION ".$queryFechaNac;			
			$bd->select($query." ORDER BY apellido1 asc");
	        $lista = new clase_listar();		
	        $j=0;				
	        for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro();
	    		if (count($fila) != 0 && count($fila) != '')
	    		{ 		    		    		
	    		       $query="SELECT idpaciente, tipo_documento, documento, apellido1, nombre1, fecha_nacimiento,
                        sexo, es_candidato('{$fila['sexo']}', sexo, $ponderacion_sexo, '{$fila['fecha_nacimiento']}', fecha_nacimiento, 
                       $ponderacion_fecha_nacimiento,'{$fila['documento']}', documento, $ponderacion_documento, nombre1 ,
                       '$soundex_apellido', soundex_apellido, $ponderacion_apellido,'$soundex_nombre', soundex_nombre,
                        $ponderacion_nombre) as ponderacion 
                        FROM pacientes_temp  
                        WHERE idpaciente=".$fila['idpaciente'];
	                //return $query;                        
	                $base->select($query);
	                $arreglo = $base->registro();	               
	                $j++;
		    		$lista->introducirElemento($arreglo); 
	    		}
	    	} 
			
				    	  	
	
        $iterator = new clase_patron_iterator($lista);
        $i = 0;			  
	    while ($iterator->existeElementoSiguiente()) 
        {         	 
            $fila[$i] = $iterator->elementoSiguiente();
            $i++;                      
        }	
        return $fila;
		}
		function idpacienteFederadoLocal($idpaciente_federado)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->set_names();
			$bd->select("SELECT * FROM pacientes_temp WHERE paciente_federadoid=$idpaciente_federado");
			$nombre_pac = $bd->registro();
			$this->nombre = $nombre_pac['nombre'];
			$this->fecha_nacimiento = $nombre_pac['fecha_nacimiento'];
			$this->idpaciente = $nombre_pac['idpaciente'];
			$this->sexo = $nombre_pac['sexo'];
			$this->documento=$nombre_pac['documento'];
			$this->provincia=$nombre_pac['provincia'];
			$this->localidad=$nombre_pac['localidad'];	
			$this->afiliado=$nombre_pac['afiliado'];
			$this->apellido1=$nombre_pac['apellido1'];
			$this->nombre1=$nombre_pac['nombre1'];	
			$this->apellido2=$nombre_pac['apellido2'];
			$this->nombre2=$nombre_pac['nombre2'];		
			$this->paciente_federadoid=$nombre_pac['paciente_federadoid'];
			$this->grupo_factor_madre=$nombre_pac['grupo_factor_madre'];
		}		
		function guardar()
		{
			$bd = new baseDatos();
			$bd->Conectarse();
                        if ($bd->select("INSERT INTO pacientes_temp(nombre,documento,provincia,localidad,telefono_fijo,telefono_celular,mail) VALUES('".$this->nombre."','".$this->documento."','".$this->provincia."','".$this->localidad."','".$this->telefono_fijo."','".$this->telefono_celular."','".$this->mail."')"))
                            return 1;
			else 
			    return 0;
			//por ahora solo hago que actualice los campos que necesito que son hora_nacimiento y lugar_fisico_nacimiento
			/*if ($bd->select("UPDATE pacientes_temp SET hora_nacimiento='".$this->hora_nacimiento."',lugar_fisico_nacimiento='".$this->lugar_fisico_nacimiento."' WHERE idpaciente=".$this->idpaciente))
			    return 1;
			else 
			    return 0;*/
		}
		function guardar_grupo_factor_madre()
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			//por ahora solo hago que actualice los campos que necesito que son hora_nacimiento y lugar_fisico_nacimiento
			if ($bd->select("UPDATE pacientes_temp SET grupo_factor_madre='".$this->grupo_factor_madre."' WHERE idpaciente=".$this->idpaciente))
			    return 1;
			else 
			    return 0;
		}
		function buscar_por_documento($dni)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM pacientes_temp WHERE documento='$dni'");
			self::asigna_arreglo($bd->registro());
		}
		function lista_posibles_pacientes($nombre,$apellido,$anioNac,$mesNac,$diaNac,$nrodoc)
		{
			$base = new baseDatos();
			$base->Conectarse();
			$queryNombre = '';
			$queryApellido = '';
			$queryFechaNac = '';
			$queryDocumento = '';
			$fechaNac = $anioNac."-".$mesNac."-".$diaNac;
			
				if($nombre != '')
				{
					$queryNombre ="SELECT idpaciente
										 ,nombre
										 ,fecha_nacimiento
										 ,documento	
								FROM pacientes_temp 
								WHERE nombre LIKE '%$nombre%' "; 
				}								
				if($apellido != '' )
				{
					$queryApellido ="SELECT idpaciente
											 ,nombre
											 ,fecha_nacimiento
											 ,documento	
									FROM pacientes_temp 
									WHERE (nombre LIKE '%$apellido%' AND fecha_nacimiento='$fechaNac') OR 
									      (nombre LIKE '%$apellido%' AND documento='$nrodoc') OR 
										  (nombre LIKE '%$nombre%' AND fecha_nacimiento='$fechaNac') OR 
										  (nombre LIKE '%$nombre%' AND documento='$nrodoc') OR 
										  (nombre LIKE '%$nombre%' AND nombre LIKE '%$apellido%')"; 
				}
				
				if($fechaNac != '--' && $fechaNac != "-".$mesNac."-".$diaNac &&
					$fechaNac != "--".$diaNac && $fechaNac != $anioNac."--" )
				{
					
					$queryFechaNac = " SELECT idpaciente
											 ,nombre
											 ,fecha_nacimiento
											 ,documento	
										FROM pacientes_temp 
										WHERE (fecha_nacimiento ='$fechaNac' AND documento='$nrodoc') OR 
										(fecha_nacimiento='$fechaNac' AND nombre LIKE '%$apellido%')"; 
					
				}						
				if($nrodoc != '')
				{
		
					$queryDocumento =" SELECT idpaciente
											 ,nombre
											 ,fecha_nacimiento
											 ,documento	
										FROM pacientes_temp 
										WHERE (documento = '$nrodoc')"; 
				}		
				$query = '';		
				$query = $query.$queryApellido." UNION ".$queryDocumento." UNION ".$queryFechaNac;
				$base->select($query." ORDER BY nombre asc");
				$pro = new clase_listar();								
		    	for($i=0;$i<=$base->numero_filas();$i++) 
		    	{
		    		$fila = $base->registro(); 
		    		$pro->introducirElemento($fila); 
		    	}
		    	$this->arreglo_paciente = $pro;					
		}
	}
?>