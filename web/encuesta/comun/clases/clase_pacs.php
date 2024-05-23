<?php
    class clase_pacs
    {    	
	
        function enviar_url_pacs($idpaciente,$idprofesional,$codigo,$modalidad,$fecha,$hora,$accessionNumber,$scheduledStation)
        {
        	$pac = new clase_pacientes($idpaciente);
        	$prof = new clase_profesionales($idprofesional);
        	$nome = new clase_practicas();
        	$nome->buscar_nomenclador($codigo);
        	if ($nome->es_rayos()==1)
        	{
                if ($pac->sexo() == '') $sexo='F'; else $sexo=$pac->sexo();
        		    $nomenclador=$nome->buscar_descripcion_id($codigo);
        	    if (trim($pac->apellido1()) != '' && trim($pac->nombre1()) != '' && trim($pac->apellido2()) != '' && trim($pac->nombre2()) != '')
        	        $paciente_name=str_replace(' ','|',str_replace(' ','|',trim($pac->apellido1())).' '.str_replace(' ','|',trim($pac->apellido2()))).'^'.
		                           str_replace(' ','|',str_replace(' ','|',trim($pac->nombre1())).' '.str_replace(' ','|',trim($pac->nombre2())));
                else 
                {
	                if (trim($pac->apellido2()) != '' && trim($pac->nombre2()) == ''  && trim($pac->apellido1()) != '' && trim($pac->nombre1()) != '')
					{						
						$paciente_name = str_replace(' ','|',str_replace(' ','|',trim($pac->apellido1())).' '.str_replace(' ','|',trim($pac->apellido2()))).'^'.
					     str_replace(' ','|',trim($pac->nombre1()));
					}
					else 
				    {
					    if (trim($pac->apellido2()) == '' && trim($pac->nombre2()) != ''  && trim($pac->apellido1()) != '' && trim($pac->nombre1()) != '')
					    {							
							$paciente_name = str_replace(' ','|',trim($pac->apellido1())).'^'.
		                    str_replace(' ','|',str_replace(' ','|',trim($pac->nombre1())).' '.str_replace(' ','|',trim($pac->nombre2()))); 		
					    }
					    else 
					    {
					    	if (trim($pac->apellido2()) == '' && trim($pac->nombre2()) == ''  && trim($pac->apellido1()) != '' && trim($pac->nombre1()) != '')
								$paciente_name = str_replace(' ','|',str_replace(' ','|',trim($pac->apellido1())).'^'.str_replace(' ','|',trim($pac->nombre1())));
					    }
				    }
                }		                           
        	    if (trim($paciente_name) == '')
	    		    $paciente_name = str_replace(' ','^',trim($pac->nombre()));
	            //primero editamos el paciente por las dudas que haya cambiado en avicenna
	            $bandera= self::editar_paciente_name($idpaciente, $pac->fecha_nacimiento(), $sexo, $paciente_name);
			    //si es ecografia la modalidad cambia
			    if ($nome->que_es() == 2)
			    {
			        $modalidad = 'US';
			        //hay que cambiar tambien la direccion donde apunta y los nombres de los parametros
			    }
				if ($nome->que_es() == 3)
			    {
			        $modalidad = 'MR';
					$scheduledStation = 'MREXP';
			        //hay que cambiar tambien la direccion donde apunta y los nombres de los parametros
			    }
        	    //return  $paciente_name;
        		$url="http://192.168.1.241:8086/wlprocess?PatientID=$idpaciente&PatientName=$paciente_name&PatientBD=".date('Ymd',strtotime($pac->fecha_nacimiento()))."&PatientSex=$sexo&Modality=$modalidad&ReferringPhysician=".str_replace(' ','^',trim($prof->nombre()))."&StudyDescription=".$nomenclador."&StartDate=".date('Ymd',strtotime(fechaBase($fecha)))."&StartTime=".date('Hi',strtotime($hora))."&AccessionNumber=$accessionNumber&ScheduledStation=$scheduledStation";
		//		return $url;
        		$ch = curl_init($url);
				$encoded = '';
				// 	include GET as well as POST variables; your needs may vary.
				foreach($_GET as $name => $value) {
  					$encoded .= urlencode($name).'='.urlencode($value).'&';
				}
				//chop off last ampersand
				$encoded = substr($encoded, 0, strlen($encoded)-1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);							
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);							
				curl_setopt($ch, CURLOPT_USERPWD, "admin:admin"); 
				$r=curl_exec($ch);
				curl_close($ch);
				return $r;
        	}
        	else
        		return 1;				
        }   
    	function eliminar_url_pacs($accessionNumber)
        {        	
        	$url="http://192.168.2.241:8086/wldelete?AccessionNumber=$accessionNumber";
//		return $url;
        	$ch = curl_init($url);
			$encoded = '';
			// include GET as well as POST variables; your needs may vary.
			foreach($_GET as $name => $value) {
  				$encoded .= urlencode($name).'='.urlencode($value).'&';
			}
			//chop off last ampersand
			$encoded = substr($encoded, 0, strlen($encoded)-1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);							
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);							
			curl_setopt($ch, CURLOPT_USERPWD, "admin:admin"); 
			$r=curl_exec($ch);
			curl_close($ch);
			return $r;
        } 	
        function editar_paciente($idpaciente,$fecha_nacimiento,$sexo,$nombre,$apellido1,$nombre1)
        {            
            if ($sexo == '') $sexo='F';  
            if (trim($apellido1) != '' && trim($nombre1) != '')
        	    $paciente_name = str_replace(' ','|',trim($apellido1)).'^'.str_replace(' ','|',trim($nombre1));
        	else 
        	    $paciente_name = str_replace(' ','^',trim($nombre));
        	               
            $url="http://192.168.1.241:8086/edit_patient?PatientID=$idpaciente&PatientName=$paciente_name&PatientBD=".date('Ymd',strtotime(fechaBase($fecha_nacimiento)))."&PatientSex=$sexo&Save=Save";
		//		return $url;
            $ch = curl_init($url);
		    $encoded = '';
		    // 	include GET as well as POST variables; your needs may vary.
		    foreach($_GET as $name => $value) {
	  	    	$encoded .= urlencode($name).'='.urlencode($value).'&';
		    }
		    //chop off last ampersand
		    $encoded = substr($encoded, 0, strlen($encoded)-1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);							
		    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);							
		    curl_setopt($ch, CURLOPT_USERPWD, "admin:admin"); 
		    $r=curl_exec($ch);
		    curl_close($ch);
		    return $r;        					
       }
    	function editar_paciente_name($idpaciente,$fecha_nacimiento,$sexo,$nombre)
        {            
            if ($sexo == '') $sexo='F';  
            
        	    $paciente_name = trim($nombre);
        	               
            $url="http://192.168.1.241:8086/edit_patient?PatientID=$idpaciente&PatientName=$paciente_name&PatientBD=".date('Ymd',strtotime(fechaBase($fecha_nacimiento)))."&PatientSex=$sexo&Save=Save";
		//		return $url;
            $ch = curl_init($url);
		    $encoded = '';
		    // 	include GET as well as POST variables; your needs may vary.
		    foreach($_GET as $name => $value) {
	  	    	$encoded .= urlencode($name).'='.urlencode($value).'&';
		    }
		    //chop off last ampersand
		    $encoded = substr($encoded, 0, strlen($encoded)-1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);							
		    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);							
		    curl_setopt($ch, CURLOPT_USERPWD, "admin:admin"); 
		    $r=curl_exec($ch);
		    curl_close($ch);
		    return $r;        					
       }
   }
?>
