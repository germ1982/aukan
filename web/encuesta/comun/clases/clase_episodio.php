<?
	class clase_episodio 
	{
		var $idepisodio = 0;
		var $idprofesional = 0;
		var $idpaciente = 0;
		var $idobra_social = 0;
		var $fecha_ingreso = '';
		var $fecha_egreso = '';
		var $motivo_internacion = 0;
		var $tipo_alta = '';
		var $otro_medico_cabecera = '';
		var $hora_alta = '';
		var $acompanante = '';
		var $iddiagnostico_final = 0;
		var $alta_medica = '';
		var $idlugar = 0;
		var $arreglo_foraneo_idpaciente='';
		var $hora_ingreso = '';
		var $tipo_internacion = 0;
		var $tipo_intervencion = 0;
		var $internados = 0;
		
		function clase_episodio($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    $consulta = "SELECT * FROM episodios WHERE idepisodio=$id";
			$bd->select($consulta);
			$arreglo = $bd->registro();	
			$this->asignar_datos($arreglo);			                              		
		}	
		function asignar_datos($arreglo)
		{
			$this->idepisodio = $arreglo['idepisodio'];
			$this->idprofesional = $arreglo['idprofesional'];
		    $this->idpaciente = $arreglo['idpaciente'];
		    $this->idobra_social = $arreglo['idobra_social'];
		    $this->fecha_ingreso = $arreglo['fecha_ingreso'];
		 	$this->fecha_egreso = $arreglo['fecha_egreso'];
		 	$this->motivo_internacion = $arreglo['motivo_internacion'];
		 	$this->tipo_alta = $arreglo['tipo_alta'];
		 	$this->otro_medico_cabecera = $arreglo['otro_medico_cabecera'];
		 	$this->hora_alta = $arreglo['hora_alta'];
		 	$this->acompanante = $arreglo['acompanante'];
		 	$this->iddiagnostico_final = $arreglo['iddiagnostico_final'];
		 	$this->alta_medica = $arreglo['alta_medica'];
		 	$this->idlugar = $arreglo['idlugar'];
		 	$this->hora_ingreso = $arreglo['hora_ingreso'];
		 	$this->tipo_internacion = $arreglo['tipo_internacion'];	
		 	$this->tipo_intervencion = $arreglo['tipo_intervencion'];	 	
		}		
		function idepisodio()
		{
			return $this->idepisodio;
		}
		function idprofesional()
		{
			return $this->idprofesional;
		}
		function idpaciente()
		{
		    return $this->idpaciente;
		}
		function idobra_social()
		{
		    return $this->idobra_social;
		}
		function fecha_ingreso()
		{
		    return $this->fecha_ingreso;
		}
		function fecha_egreso()
		{
		 	return $this->fecha_egreso;
		}
		function motivo_internacion()
		{
		 	return $this->motivo_internacion;
		}
		function tipo_alta()
		{
		 	return $this->tipo_alta;
		}
		function otro_medico_cabecera()
		{
		 	return $this->otro_medico_cabecera;
		}
		function hora_alta()
		{
		 	return $this->hora_alta;
		}
		function acompanante()
		{
		 	return $this->acompanante;
		}
		function iddiagnostico_final()
		{
		 	return $this->iddiagnostico_final;
		}
		function alta_medica()
		{
		 	return $this->alta_medica;
		}
		function idlugar()
		{
		 	return $this->idlugar;
		}	
		function hora_ingreso()
        {
            return $this->hora_ingreso;
        }	
		function arreglo_foraneo_idpaciente()
        {
            return $this->arreglo_foraneo_idpaciente;
        }
	    function tipo_internacion()
        {
            return $this->tipo_internacion;
        }
        function tipo_intervencion()
        {
        	return $this->tipo_intervencion;
        }
        function cantidad_internados()
        {
        	return $this->internados;
        }
		function foranea_idpaciente($idpaciente)
		{
			$bd = new baseDatos();
			$bd->Conectarse();		    
			$bd->select("SELECT * FROM episodios WHERE idpaciente=$idpaciente");				
			$pro = new clase_listar();			
							
	    	for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro(); 
	    		$pro->introducirElemento($fila); 
	    	}
	    	$this->arreglo_foraneo_idpaciente = $pro;		                              		
		}
		function episodios_pacientes_profesionales_fecha($fdesde,$fhasta,$idprofesional,$idpaciente,$tipo_internacion,$cantidad_horas)
		{
			$bd = new baseDatos();
			$bd->Conectarse();		   
			if ($idprofesional != 0)
			    $where = " AND idprofesional=$idprofesional "; 
			$bd->select("SELECT * FROM episodios 
			            WHERE idpaciente=$idpaciente  $where AND idprofesional<>0
			            AND fecha_ingreso>='".fechaBase($fdesde)."' AND fecha_ingreso<='".fechaBase($fhasta)."' 
			            AND tipo_internacion=$tipo_internacion");				
			$pro = new clase_listar();			
							
	    	for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro(); 
	    		if ($fila['fecha_egreso'] != '' && $fila['fecha_egreso'] != '0000-00-00')
	    		{
	    			$fecha_ingreso = strtotime($fila['fecha_ingreso'].' '.$fila['hora_ingreso']);
					$calculo = strtotime("$cantidad_horas hours",$fecha_ingreso);
					$tiempo = date("Y-m-d H:i:s", $calculo);
					list($f,$h) = split( ' ', $tiempo );					
					//comprarmos para ver si la fecha obtenida es igual a la de internacion 
					if ($fila['fecha_ingreso'] == $f && $f == $fila['fecha_egreso'])
					{						
						if ($h>=$fila['hora_alta'])
					//        $fila['idepisodio'] = $h;
						    $pro->introducirElemento($fila);
					}
					else 
					{
						if (($fila['fecha_ingreso'] < $f) && restarFecha($f,$fila['fecha_egreso']) == 0)
						{
							if ($fila['hora_alta']<= $h)
							    $pro->introducirElemento($fila);		
						}
					}	    			
	    		}	    			    	
	    		//$pro->introducirElemento($fila);
	    	}
	    	$this->arreglo_foraneo_idpaciente = $pro;
		}
		function episodios_tipo_internacion($fdesde,$fhasta,$tipo_internacion)
		{
			$bd = new baseDatos();
			$bd->Conectarse();		    
			$bd->select("SELECT * FROM episodios WHERE fecha_ingreso>='".fechaBase($fdesde)."' AND fecha_ingreso<='".fechaBase($fhasta)."' AND 
			            tipo_internacion=$tipo_internacion");				
			$pro = new clase_listar();			
							
	    	for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro(); 
	    		if ($fila['idepisodio'] != 0 && $fila['idepisodio'] != '')
	    		{
	    		    $pro->introducirElemento($fila);	    		 
	    		    $this->internados +=1;
	    		}
	    	}
	    	$this->arreglo_foraneo_idpaciente = $pro;		                              		
		}		
	}
?>