<?
    function desplegarLista($strText,$listaDesplegable,$id,$input,$tabla,$idtabla,$descripcion,$where,$orden,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
	{
        $objResponse = new xajaxResponse();   
	    $objResponse->setCharEncoding('utf-8');
	    //$objResponse->addAlert($strText);
        $objResponse->addAssign($listaDesplegable, "innerHTML", desplegarListarFuncion($strText,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan));
		
		return $objResponse;
    }
    function desplegarListaItaliano($strText,$listaDesplegable,$id,$input,$tabla,$idtabla,$descripcion,$where,$orden,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
	{
        $objResponse = new xajaxResponse();   
	    $objResponse->setCharEncoding('utf-8');
	    //$objResponse->addAlert($strText);
        $objResponse->addAssign($listaDesplegable, "innerHTML", desplegarListarFuncionItaliano($strText,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan));
		
		return $objResponse;
    }		
	function desplegarListaConFuncion($strText,$listaDesplegable,$id,$input,$tabla,$idtabla,$descripcion,$where,$orden,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle)
	{
        $objResponse = new xajaxResponse();   
	    $objResponse->setCharEncoding('utf-8');		
        $objResponse->addAssign($listaDesplegable, "innerHTML", desplegarListarConFuncionFuncion($strText,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle));
//		$objResponse->addAlert(desplegarListarConFuncionFuncion($strText,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle));
		return $objResponse;
    }
    //$desplgarSiNo dice si cuando me para sobre la lista que se está desplegando el mouseover debe desplegar algo o no
    //$funcionDesplegarMouseOver si hay que desplegar dice que funcion desplegar
    //$divDesplegarMouseOver dice donde pegar lo que $funcionDesplegarMouseOver genera
    function desplegarListaConFuncionYmouseOver($strText,$listaDesplegable,$id,$input,$tabla,$idtabla,$descripcion,$where,$orden,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle,$desplgarSiNo,$funcionDesplegarMouseOver,$divDesplegarMouseOver)
	{
        $objResponse = new xajaxResponse();   
	    $objResponse->setCharEncoding('ISO-8859-1');		
        $objResponse->addAssign($listaDesplegable, "innerHTML", desplegarListaConFuncionYmouseOverFuncion($strText,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle,$desplgarSiNo,$funcionDesplegarMouseOver,$divDesplegarMouseOver));		
		return $objResponse;
    }
	function desplegarPacienteConDNI($strText,$contenedor,$input,$idpaciente)
	{
        $objResponse = new xajaxResponse();    
	    $objResponse->setCharEncoding('utf-8');
        $objResponse->addAssign($contenedor, "innerHTML", desplegarPacienteConDNIFuncion($strText,$contenedor,$input,$idpaciente));				
        return $objResponse;
    }
	function buscarObraSocial($idpaciente,$idosocial_input,$obra_social_input,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle)
	{
	    $objResponse = new xajaxResponse();    
	    $objResponse->setCharEncoding('ISO-8859-1');
		$bandera = buscarObraSocialFuncion($idpaciente);
		$objResponse->addAssign($idosocial_input, "value", $bandera[0]);				
        $objResponse->addAssign($obra_social_input, "value", $bandera[1]);				
        return $objResponse;
	}
	 function moverse($hacia_donde,$widthSpanDetalle,$init_limit,$fin_limit,$contenedor,$id,$tabla,$idtabla)
	 {
	     $objResponse = new xajaxResponse();    
		 $objResponse->setCharEncoding("iso-8859-1"); 
		 if ($hacia_donde== 0) //quiere decir que va hacia el anterior
		 {
		    if ((($init_limit-10) <= 0))
			{
		        $init_limit = 0;
			}
			else
			{
			    $init_limit -= 10;	
			}
		 }
		 else
		 {
             $es_fin = cantidadFilasObraSocialNomenclador($id,$tabla,$idtabla); 
		//	 $objResponse->addAlert("$id $es_fin $init_limit");
			 if ($es_fin > ($init_limit+10))
			     $init_limit += 10;
			 else
			 {
			     $hasta = $es_fin - $init_limit;
				 $init_limit += $hasta;
			 }    	 				 
		 }    
		 $objResponse->addAssign('codigo', "value", 0);
//		 $objResponse->addAlert($init_limit." ".$fin_limit);
		 $objResponse->addAssign($contenedor, "innerHTML", traerFuncion($id,$init_limit,10,$widthSpanDetalle));		
       //  $objResponse->addAssign('detalle_financiacion', "innerHTML", traerPlanesFuncion($id,$init_limit,$fin_limit,$widthSpanDetalle));
		 return $objResponse;
	 }
	 function calcularDiasEntreFecha($fdesde,$fhasta,$contenedor)
	 {
	     $objResponse = new xajaxResponse();   
	     $objResponse->setCharEncoding('ISO-8859-1');		
         $objResponse->addAssign($contenedor, "innerHTML", calcularDiasEntreFechaFuncion($fdesde,$fhasta));		
		 return $objResponse;	
	 }	 
	 function buscarMedicacionCatalogo($strText,$listaDesplegable,$id,$input,$tabla,$idtabla,$descripcion,$idmedicacion,$idpresentacion,$medicacion_sala,$dosis_sala,$unidad_sala,$via_sala,$where,$orden,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
	 {
	     $objResponse = new xajaxResponse();   
	     $objResponse->setCharEncoding('utf-8');		
         $objResponse->addAssign($listaDesplegable, "innerHTML", buscarMedicacionCatalogoFuncion($strText,$tabla,$idtabla,$descripcion,$idmedicacion,$idpresentacion,$medicacion_sala,$dosis_sala,$unidad_sala,$via_sala,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan));
//		 $objResponse->addAlert(desplegarListarConFuncionFuncion($strText,$tabla,$idtabla,$descripcion,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan,$debeColocarFuncion,$nombreFuncion,$idtablaReal,$dondePegoDato,$dondePegoDetalle,$ordenSiguienteAnterior,$widthSpanDatos,$widthSpanDetalle));
		 return $objResponse;	
	 }
	 function buscarMedicacionCatalogoPrecioKairos($strText,$listaDesplegable,$id,$input,$tabla,$idtabla,$descripcion,$idmedicacion,$idpresentacion,$medicacion_sala,$dosis_sala,$unidad_sala,$via_sala,$precio,$precio_temp,$where,$orden,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan)
	 {
	     $objResponse = new xajaxResponse();   
	     $objResponse->setCharEncoding('utf-8');	     	     		
         $objResponse->addAssign($listaDesplegable, "innerHTML", buscarMedicacionCatalogoPrecioKairosFuncion($strText,$tabla,$idtabla,$descripcion,$idmedicacion,$idpresentacion,$medicacion_sala,$dosis_sala,$unidad_sala,$via_sala,$precio,$precio_temp,$orden,$listaDesplegable,$input,$where,$id,$select,$deboPegar,$queDeboPegar,$lugarAPegar,$widthSpan));         
		 return $objResponse;	
	 }
	 function buscarPrecioCatalogoKairos($codigo_catalogo,$idpresentacion_catalogo,$precio_input,$precio_temp_input)
	 {
	 	 $objResponse = new xajaxResponse();   
	     $objResponse->setCharEncoding('utf-8');	
	     $catalogo = new catalogo();
		 $a = $catalogo->devolverCodigos($codigo_catalogo, $idpresentacion_catalogo);
		 if ($a[0] != 0 && $a[1] != '')
		 {
		     $cod_kairos = $a[0];
		     $idpre_kairos = $a[1];
		     $kairos_precio = new clase_medicamentos_comerciales();
			 $pre_k = $kairos_precio->obtener_valor_kairos($a[0],$a[1]);
			 if ($pre_k == '' || $pre_k == 0)
			     $pre_k = 0;
		 }
		 else 
		 {
		     $cod_kairos = 0;
		     $idpre_kairos = 0;
		 }     	     		
         $objResponse->addAssign($precio_input, "value", $pre_k);
         $objResponse->addAssign($precio_temp_input, "value", $pre_k);         
		 return $objResponse;	 	 
	 }
	function borrarTextoTesauro($id,$idforaneo,$id_input,$tabla,$ver,$contenedor,$id_input_foraneo,$idprofesional)
	{
	    $objResponse = new xajaxResponse();   
	    $objResponse->setCharEncoding('ISO-8859-1');
	//    if (verificarProfesional($idforaneo, $idprofesional, $tabla, $id_input_foraneo, 'idprofesional'))
	 //   {	 
	    	$tesauro = new clase_tesauro();
	    	$bandera = $tesauro->borrarTextoTesauro($id,$id_input, $tabla);    
		  //  $objResponse->addAlert($bandera);    
			if ($bandera == 1)
			{  		    
				$objResponse->addAssign($contenedor, "innerHTML", $tesauro->mostrarTextoTesauro($idforaneo, $id_input_foraneo, $contenedor, $tabla, $ver));
        	}
			else
		    	$objResponse->addAlert(MensajeError(8));
	  //  }
	   // else 
	    //    $objResponse->addAlert(MensajeError(55));		    					   
		return $objResponse;
	}
	function guardarProblema($id,$idprofesional,$idpaciente,$descriptionid,$estado,$desconoce_fecha,$fecha_problema,$texto_tesauro,$subsetid,$dirBase,$contenedor)        
    {
       //instanciamos el objeto para generar la respuesta con ajax
	    $respuesta = new xajaxResponse();
		$respuesta->setCharEncoding('ISO-8859-1');
		//$respuesta->addAlert($estado);
		if ($descriptionid != '' && $descriptionid != 0 && $estado != '')
		{
			$bandera = guardarProblemaFuncion($id,$idprofesional,$idpaciente,$descriptionid,$estado,$desconoce_fecha,$fecha_problema,$texto_tesauro,$subsetid);
			    //    $respuesta->addAlert($bandera);
			if ( $bandera == 1 )//si es != 0 es que creo la fila y me devuelve el id que creo
			{
	        	$respuesta->addScript("document.getElementById('idproblema').value=0;
	        			              document.getElementById('problema_buscado').value='';");
	            $respuesta->addAssign($contenedor,"innerHTML",grillaProblemasFuncion($idpaciente,$idprofesional,$dirBase,$contenedor));
				//$respuesta->addAlert(MensajeError(2));
			}
			else
			{
				if ($bandera == 0)
		    		$respuesta->addAlert(MensajeError(1));
		    	else//esto es para cuando el codigo se repite
		    	    $respuesta->addAlert(MensajeError(54));
			}
		    //tenemos que devolver la instanciación del objeto xajaxResponse
		}
		else
		    $respuesta->addAlert(MensajeError(1));
	    return $respuesta;
  }  
  function grillaProblemas($idpaciente,$idprofesional,$dirBase,$contenedor) 
  {
      //instanciamos el objeto para generar la respuesta con ajax
	  $respuesta = new xajaxResponse();
	  $respuesta->setCharEncoding('ISO-8859-1');
	  $respuesta->addAssign($contenedor,"innerHTML",grillaProblemasFuncion($idpaciente,$idprofesional,$dirBase,$contenedor));		
	     
	  return $respuesta;
  }

  function borrarProblema($id,$idpaciente,$idprofesional,$dirBase,$contenedor)
  {
      //instanciamos el objeto para generar la respuesta con ajax
	  $respuesta = new xajaxResponse();
	  $respuesta->setCharEncoding('ISO-8859-1');	
	//  if (verificarProfesional($id, $idprofesional, 'pacientes_problemas', 'id', 'idprofesional'))
	//  {
	      $bandera  = borrarProblemaFuncion($id); 		
	  	  if ($bandera != 0)	  
              $respuesta->addAssign($contenedor,"innerHTML",grillaProblemasFuncion($idpaciente,$idprofesional,$dirBase,$contenedor));
          else
              $respuesta->addAlert(MensajeError(13));	      
	 // }
	 // else 
	 //     $respuesta->addAlert(MensajeError(55));
	  return $respuesta;
  }
	function filtrarConsultaPorProblemas($idpaciente,$descriptionid,$contenedor)
	{
	     //instanciamos el objeto para generar la respuesta con ajax
	    $respuesta = new xajaxResponse();
		$respuesta->setCharEncoding('UTF-8');
	    //escribimos en la capa con id="respuesta" el texto que aparece en $salida
	    $respuesta->addAssign($contenedor,"innerHTML",filtrarConsultaPorProblemasFuncion($idpaciente,$descriptionid));
	    //tenemos que devolver la instanciación del objeto xajaxResponse   
	    return $respuesta;
	}
	function imprimirPulseraAdmision($tipo,$idepisodio,$idpaciente)
	{
	     //instanciamos el objeto para generar la respuesta con ajax
	    $respuesta = new xajaxResponse();
		$respuesta->setCharEncoding('UTF-8');
	    $pulseras = new clase_pulseras($tipo);
	    $u = $pulseras->crear_script($tipo, $idepisodio, $idpaciente);
	    $file_name_with_full_path = realpath('../../pulseras/temp.txt');
	    $post = array('send'=>'@'.$file_name_with_full_path);
	    $pulseras->post_asigna($post);
	    if ($pulseras->enviar() != FALSE)  
	    {
	        unlink($temp_dir."temp.txt");
	        $respuesta->addAlert(MensajeError(67));
	    }
	    else
	    	$respuesta->addAlert(MensajeError(68)); 
	    return $respuesta;
	}
	function calibrarImpresora($tipo)
	{
	     //instanciamos el objeto para generar la respuesta con ajax
	    $respuesta = new xajaxResponse();
		$respuesta->setCharEncoding('UTF-8');
	    $pulseras = new clase_pulseras($tipo);
	    $pulseras->calibrar($tipo);
	    $pulseras->enviar();
      //  $respuesta->addAlert(MensajeError(68)); 
	    return $respuesta;
	}
	function devolverNombrePaciente($contenedor,$idpaciente)
	 {
	 	$objResponse = new xajaxResponse();
	 	$objResponse->setCharEncoding('UTF-8');
	 	$pac = new clase_pacientes($idpaciente);	 	
	 	$objResponse->addAssign($contenedor,"value",$pac->nombre());
	 	return $objResponse;
	 }
?>
