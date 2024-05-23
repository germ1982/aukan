<?
	class clase_indicaciones
	{
		var $id=0;
		var $nombre   ='';
		var $arreglo_indicacion ='';
		var $via = '';
		var $indicacion = '';
		var $intervalo = '';
		var $hora_inicio = '';
		var $iddroga='';
		var $dosis = '';		
		var $observaciones ='';
		var $unidad_medida = '';
		var $vehiculo = '';
		var $plan_parenteral='';
		var $paralelo = '';
		var $idpresentacion = '';
		var $tipo_dilucion ='';
		var $primer_volumen = '';
		var $en_bolo = '';
		var $otro_primer_volumen = '';
		var $volumen_administrar = '';
		var $goteo = '';
		var $bic='';
		var $a_pasar='';
		var $dosis_unidad_tipo='';
		var $dosis_unidad='';
	    var $segun_objetivo='';
	    var $cloruro_sodio='';
	    var $cloruro_sodio_volumen='';
	    var $cloruro_potasio='';
        var $cloruro_potasio_volumen='';
        var $sulfato_magnesio='';
        var $sulfato_magnesio_volumen='';
        var $fosfato_sodio='';
        var $fosfato_sodio_volumen='';
        var $complejo_vitaminico='';
        var $complejo_vitaminico_volumen='';        
		var $gluconato_calcio='';
		var $gluconato_calcio_volumen='';
		var $agua_destilada='';
		var $agua_destilada_volumen='';
		var $plan_hidratacion='';
		
		function clase_indicaciones($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM indicaciones_sala WHERE idindicacion_sala=$id");
			$arreglo = $bd->registro();
			$this->via = $arreglo['via'];
			$this->arreglo_indicacion = $arreglo;			
			//return $this->arreglo_indicacion;
		}
		function realizo_indicacion($idindicacion,$hora)
		{		
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM indicacion_horas_suministrada WHERE idindicacion_sala=$idindicacion AND hora=$hora");
			if ($bd->numero_filas() != 0)	
			    return 1;
			else 
			    return 0;
		}	
		function detalle_indicacion($indicacion)
		{
			 $primer_volumen = '';
			 if (trim($indicacion['primer_volumen']) != '')
				                           $primer_volumen = $indicacion['primer_volumen']." ml";
				
				else 
				{
					if (trim($indicacion['otro_primer_volumen']) != '')
					{
						$primer_volumen = $indicacion['otro_primer_volumen']." ml";	
					}
				}
				$diluido='';
				if ($indicacion['tipo_dilucion'] == 1)
				{					
					$diluido = "Diluido en: Dextrosa 5% ";
				}
				if ($indicacion['tipo_dilucion'] == 2)
				{					
					$diluido = "Diluido en: Dextrosa 10% ";
				}
				if ($indicacion['tipo_dilucion'] == 3)
				{					
					$diluido = "Diluido en: Dextrosa 25% ";
				}
				if ($indicacion['tipo_dilucion'] == 4)
				{					
					$diluido = "Diluido en: Dextrosa 50% ";
				}
				if ($indicacion['tipo_dilucion'] == 5)
				{					
					$diluido = "Diluido en: Solucion Fisiologica 0.9% ";
				}
				if ($indicacion['tipo_dilucion'] == 6)
				{					
					$diluido = "Diluido en: Ringer ";
				}
				if ($indicacion['tipo_dilucion'] == 7)
				{					
					$diluido = "Diluido en: Agua Destilada ";
				}
				if ($indicacion['tipo_dilucion'] == 8)
				{					
					$diluido = "Diluido en: Glicina ";
				}					
				if ($indicacion['tipo_dilucion'] == 9)
				{					
					$diluido = "Diluido en: Manitol 15% ";
				}
				$volumen_administrar = '';
				if (trim($indicacion['volumen_administrar']) != '')
				{
					$volumen_administrar = "Volumen a administrar: ".$indicacion['volumen_administrar']." ml";
				}
				$agregado_plan = '';
				if ($indicacion['cloruro_sodio'] == 1) $agregado_plan .= " Cloruro de sodio 20% x 20 ml - ".$indicacion['cloruro_sodio_volumen']." ml - ";
				if ($indicacion['cloruro_potasio'] == 1) $agregado_plan .= " Cloruro de potasio 15 meq x 5 ml - ".$indicacion['cloruro_potasio_volumen']." ml - ";
				if ($indicacion['sulfato_magnesio'] == 1) $agregado_plan .= " Sulfato de magnesio 25% x 5 ml - ".$indicacion['sulfato_magnesio_volumen']." ml - ";
				if ($indicacion['fosfato_sodio'] == 1) $agregado_plan .= " Fosfato de sodio x 45 ml - ".$indicacion['fosfato_sodio_volumen']." ml - ";
				if ($indicacion['vitamina_b12'] == 1) $agregado_plan .= " Vitamina B12 x 2 ml - ".$indicacion['vitamina_b12_volumen']." ml - ";
				if ($indicacion['complejo_vitaminico'] == 1) $agregado_plan .= " Complejo Vitaminico - ".$indicacion['complejo_vitaminico_volumen']." ml - ";
				if ($indicacion['gluconato_calcio'] == 1) $agregado_plan .= " Gluconato de calcio 10% - ".$indicacion['gluconato_calcio_volumen']." ml - ";
				if ($indicacion['agua_destilada'] == 1) $agregado_plan .= " Agua Destilada - ".$indicacion['agua_destilada_volumen']." ml - ";
				
				$modo_pasar = '';
				if (trim($indicacion['goteo']) != '') $modo_pasar .= " Goteo: ".$indicacion['goteo']." gotas/min.";
				if (trim($indicacion['bic']) != '') $modo_pasar .= " Vel. Infusion: ".$indicacion['bic']." ml/hora";
				if (trim($indicacion['a_pasar']) != '') $modo_pasar .= " A pasar en: ".$indicacion['a_pasar']." Horas";
				if ($indicacion['en_bolo'] == 1) $modo_pasar .= " En Bolo";
				if ($indicacion['segun_objetivo'] == 1) $modo_pasar .= " Segun Objetivo";
				$inicio_intervalo = '';
				if ($indicacion['hora_inicio'] != '00:00:00') $inicio_intervalo=' Inicio: '.horaRecortada($indicacion['hora_inicio']);
				$intervalo_dosis = '';
				if (trim($indicacion['intervalo']) != '') $intervalo_dosis = " cada: ".$indicacion['intervalo']." hs.";
				$renglo_dosis = '';
				if (trim($indicacion['dosis_unidad']) != '') $dosis_unidad = "Dosis x Unidad: ".$indicacion['dosis_unidad'];
				if ($indicacion['dosis_unidad_tipo'] == 1) $dosis_unidad.=' Gamma';
				if ($indicacion['dosis_unidad_tipo'] == 2) $dosis_unidad.=' Kilogramo';
				if ($indicacion['dosis_unidad_tipo'] == 3) $dosis_unidad.=' Dosis';
				if ($indicacion['dosis_unidad_tipo'] == 4) $dosis_unidad.=' Gota';
				if ($indicacion['dosis_unidad_tipo'] == 5) $dosis_unidad.=' Metro Cuadrado';
				if ($indicacion['dosis_unidad_tipo'] == 6) $dosis_unidad.=' Dia';
				if ($indicacion['dosis_unidad_tipo'] == 7) $dosis_unidad.=' Miligramo';
				if ($indicacion['dosis_unidad_tipo'] == 8) $dosis_unidad.=' UI';
				if ($indicacion['dosis_unidad_tipo'] == 9) $dosis_unidad.=' Horas';
				if ($indicacion['dosis_unidad_tipo'] == 10) $dosis_unidad.=' Minutos';
				if (trim($indicacion['dosis']) != '')								
			    $renglo_dosis = "Dosis: ".$indicacion['dosis']." ".$indicacion['unidad_medida']." ".$indicacion['via']." $intervalo_dosis $inicio_intervalo";
		        $contenido ="<table><tr><td><strong>$renglo_dosis</strong></td></tr>				               				   
				    				<tr><td><strong>$diluido $primer_volumen</strong></td></tr>";
				               if (trim($volumen_administrar) != '' || trim($modo_pasar) != '')
				               {
				                   $contenido .="<tr><td><strong>$volumen_administrar $modo_pasar</strong></td></tr>";
				               }
				               if (trim($agregado_plan) != '')
				               {
				               	   $contenido .="<tr><td><strong>Agregados: $agregado_plan</strong></td></tr>";
				               }
							   if (trim($dosis_unidad) != '')
				               {
				               	   $contenido .="<tr><td><strong>$dosis_unidad</strong></td></tr>";
				               }
				return $contenido.="</table>";
		}
	    function arreglo_indicacion()
	    {
	    	return $this->arreglo_indicacion;
	    } 
		function indicacion_personalizada_detalle($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM indicaciones_personalizadas_detalle WHERE id=$id");
			$this->arreglo_indicacion = $bd->registro();
			//return $this->arreglo_indicacion;
		}
		function via()
		{
			return $this->via;
		}
		function actualizarCampo($id,$campo,$nombrecampo)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			//return "UPDATE indicaciones_sala SET $nombrecampo='$campo' WHERE idindicacion_sala=$id";
			if ($bd->select("UPDATE indicaciones_sala SET $nombrecampo='$campo' WHERE idindicacion_sala=$id"))
			    return 1;
			else 
			    return 0;			
		}
		function estadoActivo($idepisodio)
		{
			$bd = new baseDatos();
			$bd->Conectarse();		
			if ($bd->select("SELECT id FROM indicaciones_medicas WHERE idepisodio=$idepisodio AND estado=1"))
			{
				$arreglo = $bd->registro();
			    return $arreglo['id'];
			}
			else 
			    return 0;			
		}
		function indicaciones_realizadas($idepisodio,$fdesde,$fhasta,$fecha_egreso,$idindicacion_sala)
		{
			$bd = new baseDatos();
			$bd->Conectarse();	
			if ($fecha_egreso != '' && $fecha_egreso != '0000-00-00')
	    	{
	    	    if (compara_fechas($fecha_egreso,fechaBase($fhasta)) == 0)	    	    
	    	    	$ffin = $fhasta;	    	         	 	 
	    	    else 
	    	    {
	    	    	if (compara_fechas($fecha_egreso,fechaBase($fhasta)) > 0)
		    	     	$ffin = $fhasta;
		    	    else 
		    	     	$ffin = $fecha_egreso;
	    	    }
	    	}
	    	else 
	    	    $ffin = $fhasta;
	        if ($fdesde != '' && $fhasta != '')
	            $where = " AND indicaciones_medicas.fecha>='".fechaBase($fdesde)."' 
	                       AND indicaciones_medicas.fecha<='".fechaBase($ffin)."'";
	        else 
	            $where = " AND  indicaciones_medicas.id=$idindicacion_sala ";
	            
			$bd->select("SELECT iddroga, idpresentacion, idcama, SUM( indicacion_horas_suministrada.cantidad ) as cantidad,
			            SUM( indicaciones_sala.dosis ) as cantidad_indicada,intervalo
						FROM indicaciones_medicas
						LEFT JOIN indicaciones_sala ON ( indicaciones_sala.idindicacionsala = indicaciones_medicas.id )
						LEFT JOIN indicacion_horas_suministrada ON ( indicacion_horas_suministrada.idindicacion_sala = 
						indicaciones_sala.idindicacion_sala )
						WHERE indicaciones_medicas.idepisodio =$idepisodio $where
						GROUP BY iddroga, idpresentacion, idcama,indicaciones_sala.idindicacion_sala");
			$pro = new clase_listar();			
							
	    	for($i=0;$i<=$bd->numero_filas();$i++) 
	    	{
	    		$fila = $bd->registro(); 
	    		if ($fila['iddroga'] != 0 && $fila['iddroga'] !='')
	    		    $pro->introducirElemento($fila); 
	    	}
	    	$this->arreglo_indicacion = $pro;
		}
	}
?>