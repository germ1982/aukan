<?
	class clase_terapia extends clase_evolucion
	{
		var $arreglo_datos = array();
		var $id = 0;
		function clase_terapia($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    $consulta = "SELECT * FROM hc_terapia WHERE idhc_terapia=$id";
			$bd->select($consulta);
			$arreglo = $bd->registro();	
			$this->arreglo_datos = $arreglo;
			$this->id = $arreglo['idhc_terapia'];
			parent::asignar_datos($arreglo);			                              		
		}
		function armar_xml()
		{
			$bd = new baseDatos();
			$bd->Conectarse();			
			$xml = parent::armar_xml();
			$arreglo = $this->arreglo_datos;
			if ($arreglo['sedado'] == 1)
	 		{
				if ($arreglo['sedado_conciencia'] == 'SEDADO')
        		{        	
        			if ($arreglo['sedado_ramsay'] == 1)
            			$ramsay .= "Paciente ansioso, agitado";
    				if ($arreglo['sedado_ramsay'] == 2)       
        				$ramsay .= "Paciente cooperador, orientado y tranquilo";
    				if ($arreglo['sedado_ramsay'] == 3)    
        				$ramsay .= "Paciente dormido con respuesta a las ordenes";
    				if ($arreglo['sedado_ramsay'] == 4)
        				$ramsay .= "Dormido con breves respuestas a la luz y sonido";
    				if ($arreglo['sedado_ramsay'] == 5)
	        			$ramsay .= "Dormido con solo respuestas al dolor";
    				if ($arreglo['sedado_ramsay'] == 6)
        				$ramsay .= "No respuesta";
            		$contenido .= " bajo sedacion analgesia Ramsay ".$arreglo['sedado_ramsay']." ($ramsay) ";
        		}   
        		else     		
		    		$contenido .= $arreglo['sedado_conciencia'];
     		}		
	 		$glas = 0;
	 		if ($arreglo['gcsocubi'] != "")
	 		{
	    		$glas += $arreglo['gcsocubi'];
				$contenido_glas .= "(Ocular: ".$arreglo['gcsocubi'];
	 		} 
	 		if ($arreglo['gcsverbal'] != "")
	 		{
	    		$glas += $arreglo['gcsverbal'];
				$contenido_glas .= ", Verbal: ".$arreglo['gcsverbal'];
	 		} 
	 		if ($arreglo['gcsmotor'] != "")
	 		{
	    		$glas += $arreglo['gcsmotor'];
				$contenido_glas .= ", Motor: ".$arreglo['gcsmotor'].")";
	 		} 
	 		if ($contenido_glas != "")	 		
	     		$contenido .= ", con glasglow de $glas $contenido_glas";	 		
			 //declaro variable para detectar si no tiene foco
	 		$foco = 0;
	 		$foco_dos = 0;
	 		$foco_tres = 0;
	 		$foco_cuatro = 0;
	 		$foco_cinco = 0;
	 		$foco_seis = 0;
	 		if ($arreglo['pupilas_isocoricas'] == 1 && $arreglo['reactividad'] == 1)
	 		{
				$contenido .= ", Pupilas Isocoricas reactivas";
	 			$foco = 1;
	 		} 
	 		if ($arreglo['pupilas_midriasis'] == 1 && $arreglo['reactividad'] == 1)
	 		{
				$contenido .= ", Pupilas midriaticas reactivas";
	 			$foco = 1;	 
	 		} 
	 		if ($arreglo['pupilas_midriasis'] == 1 && $arreglo['arreactiva'] == 1)
	 		{
				$contenido .= ", Pupilas midriaticas arreactivas";
 	 			$foco = 1;
	 		} 
	 		if ($arreglo['pupilas_miosis'] == 1 && $arreglo['reactividad'] == 1)
	 		{	
				$contenido .= ", Pupilas mióticas reactivas";
	 			$foco = 1;	 
	 		} 
	 		if ($arreglo['pupilas_miosis'] == 1 && $arreglo['arreactiva'] == 1)
	 		{
				$contenido .= ", Pupilas mioticas arreactivas";
	 			$foco = 1;	 
	 		} 
			 //midriasis reactiva
	 		if ($arreglo['midriazis_derecha'] == 1 && $arreglo['reactividad'] == 1)
	 		{
				$contenido .= ", Midriasis derecha reactiva";
	 			$foco = 1;	 
	 		} 
	 		if ($arreglo['midriasis_izquierda'] == 1 && $arreglo['reactividad'] == 1)
	 		{
				$contenido .= " Midriasis izquierda reactiva";
	 			$foco = 1;	 
	 		} 
			 //	midriasis reactiva
	 		if ($arreglo['midriazis_derecha'] == 1 && $arreglo['arreactiva'] == 1)
	 		{
				$contenido .= ", Midriasis derecha arreactiva";
	 			$foco = 1;	 
	 		} 
	 		if ($arreglo['midriasis_izquierda'] == 1 && $arreglo['arreactiva'] == 1)
	 		{
				$contenido .= ", Midriasis izquierda arreactiva";
	 			$foco = 1;	 
	 		} 
			 //miosis
	 		if ($arreglo['miosis_derecha'] == 1 && $arreglo['reactividad'] == 1)
	 		{
				$contenido .= ", Miosis derecha reactiva";
	 			$foco = 1;	 
	 		} 
	 		if ($arreglo['miosis_derecha'] == 1 && $arreglo['arreactiva'] == 1)
	 		{
				$contenido .= ", Miosis derecha arreactiva";
	 			$foco = 1;	 
	 		} 
	  		if ($arreglo['miosis_izquierda'] == 1 && $arreglo['reactividad'] == 1)
	 		{
				$contenido .= ", Miosis izquierda reactiva";
	 			$foco = 1;	 
	 		} 
	 		if ($arreglo['miosis_izquierda'] == 1 && $arreglo['arreactiva'] == 1)
	 		{
				$contenido .= ", Miosis izquierda arreactiva";
	 			$foco = 1;	 
	 		} 
			 //falta lo de foco motor
	 		if ($arreglo['paresiaF'] == 1 && $arreglo['pareciaD'] == 1)	 		
				$contenido .= ", Paresia facial derecha";	 		
	 		if ($arreglo['paresiaF'] == 1 && $arreglo['pareciaI'] == 1)	 		
				$contenido .= ", Paresia facial izquierda";	 	 		
	 		if ($arreglo['paresiaB'] == 1 && $arreglo['pareciaD'] == 1)	 		
				$contenido .= ", Paresia braquial derecha";	 		
	 		if ($arreglo['paresiaB'] == 1 && $arreglo['pareciaI'] == 1)	 		
				$contenido .= ", Paresia braquial izquierda";	 		
	 		if ($arreglo['pareciaC'] == 1 && $arreglo['pareciaD'] == 1)	 		
				$contenido .= ", Paresia crural derecha";	 		
	 		if ($arreglo['pareciaC'] == 1 && $arreglo['pareciaI'] == 1)	 	
				$contenido .= ", Paresia crural izquierda";	 		
	 		if ($arreglo['pareciaC'] == 1 && $arreglo['pareciaI'] == 1)	 		
				$contenido .= ", Paresia crural izquierda";	 	 		
			 //			plejia
	 		if ($arreglo['plejiaB'] == 1 && $arreglo['plejiaD'] == 1)	 		
				$contenido .= ", Plejía braquial derecha";	 	 		
	 		if ($arreglo['plejiaB'] == 1 && $arreglo['plejiaI'] == 1)	 	
				$contenido .= ", Plejía braquial izquierda";	 		
	 		if ($arreglo['plejiaC'] == 1 && $arreglo['plejiaD'] == 1)	 		
				$contenido .= ", Plejía crural derecha";	 		
			if ($arreglo['plejiaC'] == 1 && $arreglo['plejiaI'] == 1)	 		
				$contenido .= ", Plejía crural izquierda";	 		
			 //		///////////////
	 		if (($arreglo['paresiaB'] == 1) && ($arreglo['pareciaD'] == 1) && ($arreglo['sedado_conciencia'] == 'COMA'))	 	
				$contenido .= ", Hemiparesia derecha";	 		
	 		if (($arreglo['paresiaB'] == 1) && ($arreglo['pareciaI'] == 1) && ($arreglo['sedado_conciencia'] == 'COMA'))	 		
				$contenido .= ", Hemiparesia izquierda";	 		
	 		if (($arreglo['pareciaC'] == 1) && ($arreglo['pareciaD'] == 1) && ($arreglo['pareciaI'] == 1))	 		
				$contenido .= ", Paraparesia";	 		
	 		if (($arreglo['paresiaB'] == 1) && ($arreglo['pareciaD'] == 1) && ($arreglo['pareciaI'] == 1) && ($arreglo['sedado_conciencia'] == 'COMA'))	 		
				$contenido .= ", Cuadriparesia";	 		
	 		if (($arreglo['plejiaC'] == 1) && ($arreglo['plejiaD'] == 1) && ($arreglo['plejiaI'] == 1))	 		
				$contenido .= ", Paraplejía";	 		
	 		if (($arreglo['plejiaC'] == 1) && ($arreglo['plejiaD'] == 1) && ($arreglo['plejiaI'] == 1))	 		
				$contenido .= ", Cuadriplejía";	 		
	 		if ($arreglo['babink_derecho'] == 1)
				$contenido .= ", babinsky derecho";
	 		if ($arreglo['babink_izquierdo'] == 1)	 
				$contenido .= ", babinsky izquierdo";	 
	 		if ($arreglo['babink_izquierdo'] == 1 && $arreglo['babink_derecho'] == 1)	 
				$contenido .= ", babinsky bilateral"; 
			 ///////////
	 		if ($arreglo['descerebracion_derecha'] == 1)	 
				$contenido .= ", descerebración derecha";	 
	 		if ($arreglo['descerebracion_izquierda'] == 1)	 
				$contenido .= ", descerebración izquierda";	 
	 		if ($arreglo['descerebracion_izquierda'] == 1 && $arreglo['descerebracion_derecha'] == 1)	 
				$contenido .= ", descerebración bilateral";	 
			 ///////////
			if ($arreglo['decorticacion_derecha'] == 1)	 		
				$contenido .= ", decorticación derecha";	 
	 		if ($arreglo['decorticacion_izquierda'] == 1)	 
				$contenido .= ", decorticación izquierda";	 
	 		if ($arreglo['decorticacion_izquierda'] == 1 && $arreglo['decorticacion_derecha'] == 1)
				$contenido .= ", decorticación bilateral";
	 
			 /////////////////////////////////////PIC//////////////////////////////////////////////////
	 		if ($arreglo['pic'] == 1)
	 		{
	    		if ($arreglo['inestablepic'] == 1)
		    		$pic_estado = 'inestable'; 
				if ($arreglo['establepic'] == 1)
		    		$pic_estado = 'estable'; 		    
				$contenido .= ", Pic en su ".$arreglo['pic_dias']." día $pic_estado";
	 		}
	 		if ($arreglo['intercambio_estable'] == 1)	 
				$contenido .= ", Intercambio gaseoso estable";	 
	 		if ($arreglo['intercambio_inestable'] == 1)	 
				$contenido .= ", Intercambio gaseoso inestable";	 
	 		if ($arreglo['injuria_pulmonar'] != "")	 
				$contenido .= ", Injuria pulmonar de grado ".$arreglo['injuria_pulmonar'];	 
			 //declaro variable para ver si marco algo de oxigenorterapia
	 		$oxigeno = 0;
	 		$pre_contenido = '';
	 		if ($arreglo['menor_50'] == 1)
	 		{
	     		$oxigeno = 1;
	     		$pre_contenido .= "Con fracción de Oxígeno &lt; de 50% ";
	 		}
	 		if ($arreglo['mayor_50'] == 1)
	 		{
	     		$oxigeno = 1;
	     		$pre_contenido .= "Con fracción de Oxígeno &gt; de 50% ";
	 		}	 
	 		if ($arreglo['arm_inv'] == 1)
	 		{
	     		$oxigeno = 1;
	     		$pre_contenido .= " Ventilación Invasiva ";
	 		}
	 		if ($arreglo['arm_no_inv'] == 1)
	 		{
	     		$oxigeno = 1;
	     		$pre_contenido .= " Ventilación no Invasiva ";
	 		}
	 		if ($arreglo['arm_no_inv'] == 1 || $arreglo['arm_inv'] == 1)	 		
	     		$pre_contenido .= ", ARM en su ".$arreglo['arm_dias']." días";	 
	 		if ($arreglo['arm_tt'] != "")
	 		{
	     		$oxigeno = 1;
	     		$pre_contenido .= " ARM Invasiva en su día ".$arreglo['arm_tt'];
	 		}	 	 
	 		if ($oxigeno == 1)	 
	     		$contenido .= ", Oxigenoterapia: $pre_contenido ";	 
	 		else	 
	     		$contenido .= ", sin suplemento de oxigeno ";	 
	 		if ($arreglo['tet'] == 1)				 	
	     		$contenido .= " Intubación orotraqueal de ".$arreglo['tetdias']." días ";	 
	 		if ($arreglo['tqt'] == 1)	 
	     		$contenido .= " Traquestomizado hace  ".$arreglo['tqtdias']." días ";
	 
		 //////////////drenajes pleurales
			if ($arreglo['drenaje_derecho_anterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho anterior ";	 
	 		if ($arreglo['drenaje_izquierdo_anterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural izquierdo anterior ";	 
	 		if ($arreglo['drenaje_derecho_posterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho posterior ";	 
	 		if ($arreglo['drenaje_izquierdo_posterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural izquierdo posterior ";	 
	 		if ($arreglo['drenaje_izquierdo_posterior'] == 1)		 
	     		$contenido .= ", Drenaje pleural izquierdo posterior ";	 
	 		if ($arreglo['oscila'] == 1 && $arreglo['drenaje_derecho_anterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho anterior oscila ";		 	
	 		if ($arreglo['oscila_posterior'] == 1 && $arreglo['drenaje_derecho_anterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho posterior oscila ";	 
	 		if ($arreglo['burbujea'] == 1 && $arreglo['drenaje_derecho_anterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho anterior burbujea ";	 
	 		if ($arreglo['burbujea_posterior'] == 1 && $arreglo['drenaje_derecho_anterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho posterior burbujea ";	 
	 		if ($arreglo['burbujea'] == 1 && $arreglo['drenaje_derecho_anterior'] == 1 && $arreglo['oscila'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho anterior oscila y burbujea ";	 
	 		if ($arreglo['burbujea_posterior'] == 1 && $arreglo['drenaje_derecho_anterior'] == 1 && $arreglo['oscila_posterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural derecho posterior oscila y burbujea ";	 
	 		if ($arreglo['oscila'] == 1 && $arreglo['drenaje_izquierdo_anterior'] == 1)		 
	     		$contenido .= ", Drenaje pleural izquierdo anterior oscila ";	 
	 		if ($arreglo['burbujea'] == 1 && $arreglo['drenaje_izquierdo_anterior'] == 1)			 
	     		$contenido .= ", Drenaje pleural izquierdo anterior burbujea ";	 
	 		if ($arreglo['drenaje_izquierdo_posterior'] == 1 && $arreglo['oscila_posterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural izquierdo posterior oscila ";	 
	 		if ($arreglo['drenaje_izquierdo_posterior'] == 1 && $arreglo['burbujea_posterior'] == 1)	 
	     		$contenido .= ", Drenaje pleural izquierdo posterior burbujea ";	 
	 		if ($arreglo['drenaje_izquierdo_posterior'] == 1 && $arreglo['burbujea_posterior'] == 1 && $arreglo['oscila_posterior'] == 1)		 
	     		$contenido .= ", Drenaje pleural izquierdo posterior oscila y burbujea ";			 	
	 		if ($arreglo['cardio_estable'] == 1)	 
	     		$contenido .= ", Cardiovascular estable ";	 
	 		if ($arreglo['cardio_inestable'] == 1)	 
	     		$contenido .= ", Cardiovascular inestable ";	 
	 		if ($arreglo['adrenergicos'] == 1)	 
	     		$contenido .= " con el uso de adrenergicos ";	 
	 		if ($arreglo['hipotensores'] == 1)	 
	     		$contenido .= " con el uso de hipotensores";	 
	 		if ($arreglo['antiarritmicos'] == 1)	 
	     		$contenido .= " con el uso de antiarrítmicos ";	 
	 		$contenido .= " DIURESIS de ".$arreglo['vol_orina']." en 24 hs: Ingresos: ".$arreglo['ingresos']." Egresos: ".$arreglo['egresos']." Balance: ".$arreglo['balance']." Acumulado: ".$arreglo['acumulado'];
	 		if ($arreglo['cvcLocalizacion1'] != "")	 		
	     		$contenido .= " Tiene colocado un cateter ".$arreglo['cvcLocalizacion1']." hace ".$arreglo['cvcDias1'];		 
	 		if ($arreglo['cvcLocalizacion2'] != "")	 
	     		$contenido .= " Y otro cateter ".$arreglo['cvcLocalizacion2']." hace ".$arreglo['cvcDias2'];	 
	 		if ($arreglo['tamLocalizacion'] != "")	 
	     		$contenido .= " Y un cateter Arterial ".$arreglo['tamLocalizacion']." hace ".$arreglo['tamDias'];	 
	 		if ($arreglo['sganzLocalizacion'] != "")	 
	     		$contenido .= " Y un cateter para dialisis ".$arreglo['sganzLocalizacion']." hace ".$arreglo['sganzDias'];	 
	 		if ($arreglo['diagnostico'] != "")	 
	     		$contenido .= " ECG: ".$arreglo['diagnostico'];
	 
		 //vemos si es indoloro o doloroso
		 	if ($arreglo['indoloro'] == 1)
	    		$dolor .= "indoloro";
     		if ($arreglo['doloroso'] == 1)
	    		$dolor .= "doloroso"; 		
	 		if ($arreglo['abdomen_primero'] != "" && $arreglo['abdomen_segundo'] != "")	 
	     		$contenido .= " Abdomen ".$arreglo['abdomen_primero']." ".$arreglo['abdomen_segundo']." $dolor ";	 
	 		else
	 		{
	     		if ($arreglo['abdomen_primero'] != "")
		     		$contenido .= " Abdomen ".$arreglo['abdomen_primero']." $dolor ";
         		else
		     		$contenido .= " Abdomen ".$arreglo['abdomen_segundo']." $dolor "; 			
	 		}
	 		if ($arreglo['rha_positivo'] == 1)	 
	     		$contenido .= " Ruidos hidroaéreos positivos ";	 
	 		if ($arreglo['rha_negativo'] == 1)		 
	     		$contenido .= " Ruidos hidroaéreos negativos ";	 
	 		if ($arreglo['constipacion'] == 1)	 
	     		$contenido .= " Constipación ";	 
	 		if ($arreglo['diarrea'] == 1)		 
	     		$contenido .= " Diarrea ";
	 
		 ////////////////////////////////////dieta
	 		if ($arreglo['duodenal'] == 1)	 	
			    $contenido .= " DIETA: Duodenal ".$arreglo['duodenal_tipo']." Calorias ".$arreglo['duodenal_volumen'];	 
	 		if ($arreglo['gastrica'] == 1)	 
	     		$contenido .= " DIETA: GASTRICA ".$arreglo['gastrica_tipo']." Calorias ".$arreglo['gastrica_volumen'];	  	 
	 		if ($arreglo['yeyunal'] == 1)	 
	     		$contenido .= " DIETA: YEYUNAL ".$arreglo['yeyunal_tipo']." Calorias ".$arreglo['yeyunal_volumen'];	  	 
	 		if ($arreglo['oral'] == 1)	 
	     		$contenido .= " DIETA: ORAL ";	  	 
	 		if ($arreglo['npt'] != "")		 
	     		$contenido .= " DIETA: NTP nutrición parenteral total Calorias ".$arreglo['ntp'];	  
		 //SNG
		 	if ($arreglo['serohematico_sng'] != "")	 
	     		$contenido .= " Sonda nasogástrica ".$arreglo['serohematico_sng']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_sng'] != "")	 
	     		$contenido .= " Sonda nasogástrica ".$arreglo['gastrico_sng']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_sng'] != "")	 
	     		$contenido .= " Sonda nasogástrica ".$arreglo['bilioso_sng']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_sng'] != "")		 
	     		$contenido .= " Sonda nasogástrica ".$arreglo['hematico_sng']." ml día Hematico ";	 
	 		if ($arreglo['purulento_sng'] != "")	 
	     		$contenido .= " Sonda nasogástrica ".$arreglo['purulento_sng']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_sng'] != "")	 
	     		$contenido .= " Sonda nasogástrica ".$arreglo['fecaloide_sng']." ml día Fecaloide ";
	 
		 //Ilestomia
		 	if ($arreglo['serohematico_ileostomia'] != "")	 
	     		$contenido .= " Ileostomía ".$arreglo['serohematico_ileostomia']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_ileostomia'] != "")		 
	     		$contenido .= " Ileostomía ".$arreglo['gastrico_ileostomia']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_ileostomia'] != "")	 
	    		$contenido .= " Ileostomía ".$arreglo['bilioso_ileostomia']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_ileostomia'] != "")		 
	     		$contenido .= " Ileostomía ".$arreglo['hematico_ileostomia']." ml día Hematico ";	 
	 		if ($arreglo['purulento_ileostomia'] != "")	 
	     		$contenido .= " Ileostomía ".$arreglo['purulento_ileostomia']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_ileostomia'] != "")		 
	     		$contenido .= " Ileostomía ".$arreglo['fecaloide_ileostomia']." ml día Fecaloide ";
	 
		 //	colostomia
	 		if ($arreglo['serohematico_colostomia'] != "")	 
	     		$contenido .= " Colostomía ".$arreglo['serohematico_colostomia']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_colostomia'] != "")	 
	     		$contenido .= " Colostomía ".$arreglo['gastrico_colostomia']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_colostomia'] != "")	 
	     		$contenido .= " Colostomía ".$arreglo['bilioso_colostomia']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_colostomia'] != "")	 
	     		$contenido .= " Colostomía ".$arreglo['hematico_colostomia']." ml día Hematico ";	 
	 		if ($arreglo['purulento_colostomia'] != "")	 
	     		$contenido .= " Colostomía ".$arreglo['purulento_colostomia']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_colostomia'] != "")	 
	     		$contenido .= " Colostomía ".$arreglo['fecaloide_colostomia']." ml día Fecaloide ";	 
		 //drenaje1
	 		if ($arreglo['serohematico_drenaje1'] != "")	 
	     		$contenido .= " Drenaje 1 ".$arreglo['serohematico_drenaje1']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_drenaje1'] != "")	 
	     		$contenido .= " Drenaje 1 ".$arreglo['gastrico_drenaje1']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_drenaje1'] != "")	 
	     		$contenido .= " Drenaje 1 ".$arreglo['bilioso_drenaje1']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_drenaje1'] != "")	 
	     		$contenido .= " Drenaje 1 ".$arreglo['hematico_drenaje1']." ml día Hematico ";	 
	 		if ($arreglo['purulento_drenaje1'] != "")	 
	     		$contenido .= " Drenaje 1 ".$arreglo['purulento_drenaje1']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_drenaje1'] != "")	 
	     		$contenido .= " Drenaje 1 ".$arreglo['fecaloide_drenaje1']." ml día Fecaloide ";	 
			 //drenaje 2
	 		if ($arreglo['serohematico_drenaje2'] != "")	 
	     		$contenido .= " Drenaje 2 ".$arreglo['serohematico_drenaje2']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_drenaje2'] != "")
	 	        $contenido .= " Drenaje 2 ".$arreglo['gastrico_drenaje2']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_drenaje2'] != "")	 
	     		$contenido .= " Drenaje 2 ".$arreglo['bilioso_drenaje2']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_drenaje2'] != "")	 
	     		$contenido .= " Drenaje 2 ".$arreglo['hematico_drenaje2']." ml día Hematico ";	 
	 		if ($arreglo['purulento_drenaje2'] != "")	 
	     		$contenido .= " Drenaje 2 ".$arreglo['purulento_drenaje2']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_drenaje2'] != "")	 
	     		$contenido .= " Drenaje 2 ".$arreglo['fecaloide_drenaje2']." ml día Fecaloide ";	 
			 //drenaje 3
	 		if ($arreglo['serohematico_drenaje3'] != "")	 
	     		$contenido .= " Drenaje 3 ".$arreglo['serohematico_drenaje3']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_drenaje3'] != "")	 
	     		$contenido .= " Drenaje 3 ".$arreglo['gastrico_drenaje3']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_drenaje3'] != "")	 
	     		$contenido .= " Drenaje 3 ".$arreglo['bilioso_drenaje3']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_drenaje3'] != "")	 
	     		$contenido .= " Drenaje 3 ".$arreglo['hematico_drenaje3']." ml día Hematico ";	 
	 		if ($arreglo['purulento_drenaje3'] != "")		 
	     		$contenido .= " Drenaje 3 ".$arreglo['purulento_drenaje3']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_drenaje3'] != "")	 		
	     		$contenido .= " Drenaje 3 ".$arreglo['fecaloide_drenaje3']." ml día Fecaloide ";	 
			 //drenaje 4
	 		if ($arreglo['serohematico_drenaje4'] != "")	 
	     		$contenido .= " Drenaje 4 ".$arreglo['serohematico_drenaje4']." ml día SeroHematico ";	 
	 		if ($arreglo['gastrico_drenaje4'] != "")	 
	     		$contenido .= " Drenaje 4 ".$arreglo['gastrico_drenaje4']." ml día Gastrico ";	 
	 		if ($arreglo['bilioso_drenaje4'] != "")	 
	     		$contenido .= " Drenaje 4 ".$arreglo['bilioso_drenaje4']." ml día Bilioso ";	 
	 		if ($arreglo['hematico_drenaje4'] != "")	 
	     		$contenido .= " Drenaje 4 ".$arreglo['hematico_drenaje4']." ml día Hematico ";	 
	 		if ($arreglo['purulento_drenaje4'] != "")	 		
	     		$contenido .= " Drenaje 4 ".$arreglo['purulento_drenaje4']." ml día Purulento ";	 
	 		if ($arreglo['fecaloide_drenaje4'] != "")	 
	     		$contenido .= " Drenaje 4 ".$arreglo['fecaloide_drenaje4']." ml día Fecaloide ";
	 
		 ////////////////////////////pulso
	 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_conservados']==1)	 
	     		$contenido .= " Pulsos conservados ";	 
	 		else
	 		{
	     		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_ausentes']==1)		 	
		     		$contenido .= " Pulsos ausentes ";		 
		 		else
	 	 		{
		     		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_disminuidos']==1)			 
			     		$contenido .= " Pulsos disminuidos ";			 
			 		else
		 	 		{
			 	 		if ($arreglo['pulso_derecho'] == 1 && $arreglo['pulsos_conservados'] == 1 && $arreglo['pulso_superior']==1)				  
			    	 		$contenido .= " Pulsos - superior derecho Conservados ";				 
				 		if ($arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_disminuidos']==1)				 	
				     		$contenido .= " Pulsos - Inferior derechos Disminuidos ";				 
				 		if ($arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_conservados']==1)			 	 
				     		$contenido .= " Pulsos - Inferior derechos Conservados ";				 
				 		if ($arreglo['pulso_derecho'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulsos_disminuidos']==1)				 
				     		$contenido .= " Pulsos - superior derechos Disminuidos ";				 	
				 		if ($arreglo['pulso_derecho'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulsos_ausentes']==1)				 
				     		$contenido .= " Pulsos- superior derechos ausentes ";				 
				 		if ($arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_ausentes']==1)				 
			    	 		$contenido .= " Pulsos - inferior derechos ausentes ";				
				 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulsos_conservados'] == 1 && $arreglo['pulso_superior']==1)				 
			    	 		$contenido .= " Pulsos -superior izquierdos Conservados ";				 
				 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_disminuidos']==1)				 	
				     		$contenido .= " Pulsos -superior izquierdos Conservados ";				 
				 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_conservados']==1)
				 	        $contenido .= " Pulsos- Inferior izquierdos Conservados ";				 
				 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulsos_disminuidos']==1)				 
				     		$contenido .= " Pulsos - superior izquierdos Disminuidos ";				 
				 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulsos_ausentes']==1)				 
				     		$contenido .= " Pulsos- superior izquierdos ausentes ";				 	
				 		if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_ausentes']==1)				 
		    		 		$contenido .= " Pulsos - inferior izquierdos ausentes ";
				 	
            		} //fin de if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_disminuidos']==1)   				 
				}//fin de if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_ausentes']==1)			 
			}//fin de if if ($arreglo['pulso_izquierdo'] == 1 && $arreglo['pulso_superior'] == 1 && $arreglo['pulso_derecho'] == 1 && $arreglo['pulso_inferior'] == 1 && $arreglo['pulsos_conservados']==1)	  
			 //trofismo///////////////////////////////////////////////////////////////////////////////
	 		if ($arreglo['trofismo_conservado']==1 && $arreglo['trofismo_derecho'] == 0 && $arreglo['trofismo_izquierdo'] == 0 && $arreglo['trofismo_superior'] == 0 && $arreglo['trofismo_inferior'] == 0)
	     		$contenido .= " Trofismo conservado ";
     		else
	 		{		 
	 			if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_conservado']==1 && $arreglo['trofismo_inferior'] == 1)	 
	     			$contenido .= " Trofismo conservado ";	 
	 			else
	 			{
	     			if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_disminuido']==1 && $arreglo['trofismo_inferior'] == 1)		 
		     			$contenido .= " Trofismo disminuido ";		 
		 			else
		 			{	
			 			if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_conservado']==1)			 
		    	 			$contenido .= " Trofismo- derecho superior conservado ";			 
			 			if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_inferior'] == 1 && $arreglo['trofismo_conservado']==1)			 
			     			$contenido .= " Trofismo derecho inferior conservado ";			 
			 			if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_disminuido']==1)			 
			     			$contenido .= " Trofismo derecho superior disminuido ";			 
			 			if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_inferior'] == 1 && $arreglo['trofismo_disminuido']==1)			 
			     			$contenido .= " Trosismo derecho inferior disminuido ";			 
			 			if ($arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_conservado']==1)			 
			     			$contenido .= " Trofismo- izquierdo superior conservado ";			 
			 			if ($arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_inferior'] == 1 && $arreglo['trofismo_conservado']==1)			 
			     			$contenido .= " Trofismo izquierdo inferior conservado ";			 
			 			if ($arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_disminuido']==1)			 
			     			$contenido .= " Trofismo izquierdo superior disminuido ";			 
			 			if ($arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_inferior'] == 1 && $arreglo['trofismo_disminuido']==1)			 
	    		 			$contenido .= " Trosismo izquierdo inferior disminuido ";				 
			 			if ($arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_inferior'] == 1 && $arreglo['trofismo_disminuido']==1)			 
			     			$contenido .= " Trosismo izquierdo inferior disminuido ";			 
					}//if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_disminuido']==1 && $arreglo['trofismo_inferior'] == 1)			 
				}//if ($arreglo['trofismo_derecho'] == 1 && $arreglo['trofismo_izquierdo'] == 1 && $arreglo['trofismo_superior'] == 1 && $arreglo['trofismo_conservado']==1 && $arreglo['trofismo_inferior'] == 1)		 
    		}		
			//temperatura//////////////////////////////////////////////////////////////
			if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_aumentada']==1 && $arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1)		
	    		$contenido .= " Temperatura aumentada ";	
			else
			{
	    		if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_conservada']==1 && $arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1)			
		    		$contenido .= " Temperatura conservada ";		
				else
				{
					if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_disminuida']==1 && $arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1)				
			    		$contenido .= " Temperatura disminuida ";			
					else
					{	
						if ($arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1 && $arreglo['temperatura_conservada']==1)				
	    					$contenido .= " Temperatura- derecho superior conservado ";				
						if ($arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_conservada']==1)					
				    		$contenido .= " Temperatura derecho inferior conservado ";				
						if ($arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1 && $arreglo['temperatura_disminuida']==1)				
				    		$contenido .= " Temperatura derecho superior disminuido ";				
						if ($arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_disminuida']==1)					
				    		$contenido .= " Temperatura derecho inferior disminuido ";				
						if ($arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1 && $arreglo['temperatura_aumentada']==1)					
				    		$contenido .= " Temperatura derecho superior aumentada ";
						if ($arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_aumentada']==1)					
				    		$contenido .= " Temperatura derecho inferior aumentada ";				
						if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_superior'] == 1 && $arreglo['temperatura_conservada']==1)					
				    		$contenido .= " Temperatura- izquierdo superior conservado ";				
						if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_conservada']==1)					
				    		$contenido .= " Temperatura- izquierdo inferior conservado ";				
						if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_superior'] == 1 && $arreglo['temperatura_disminuida']==1)					
				    		$contenido .= " Temperatura izquierdo superior disminuido ";				
						if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_disminuida']==1)					
				    		$contenido .= " Temperatura izquierdo inferior disminuido ";				
						if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_superior'] == 1 && $arreglo['temperatura_aumentada']==1)					
				    		$contenido .= " Temperatura izquierdo superior aumentada ";				
						if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_aumentada']==1)	
							$contenido .= " Temperatura izquierdo inferior aumentada ";				
					}//if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_disminuida']==1 && $arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1)					
				}//if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_conservada']==1 && $arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1)				
    		}//if ($arreglo['temperatura_izquierda'] == 1 && $arreglo['temperatura_inferior'] == 1 && $arreglo['temperatura_aumentada']==1 && $arreglo['temperatura_derecha'] == 1 && $arreglo['temperatura_superior'] == 1)	 		
	
			//tono/////////////////////////////////////////
			if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_aumentado']==1 && $arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1)		
	    		$contenido .= " Tono aumentada ";	
			else
			{
	    		if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_conservado']==1 && $arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1)			
	    			$contenido .= " Tono conservada ";		
				else
				{
		    		if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_disminuido']==1 && $arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1)				
	    				$contenido .= " Tono disminuída ";			
					else
					{
						if ($arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1 && $arreglo['tono_conservado']==1)					
	    					$contenido .= " Tono- derecho superior conservado ";
						if ($arreglo['tono_derecho'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_conservado']==1)					
				    		$contenido .= " Tono- derecho inferior conservado ";				
						if ($arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1 && $arreglo['tono_disminuido']==1)					
				    		$contenido .= " Tono derecho superior disminuido ";					
						if ($arreglo['tono_derecho'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_disminuido']==1)					
				    		$contenido .= " Tono derecho inferior disminuido ";				
						if ($arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1 && $arreglo['tono_aumentado']==1)					
				    		$contenido .= " Tono derecho superior  aumentada ";				
						if ($arreglo['tono_derecho'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_aumentado']==1)					
		    				$contenido .= " Tono derecho inferior  aumentada ";						
						if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_superior'] == 1 && $arreglo['tono_conservado']==1)					
		    				$contenido .= " Tono- izquierdo superior conservado ";				
						if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_conservado']==1)					
				    		$contenido .= " Tono- izquierdo inferior conservado ";					
						if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_superior'] == 1 && $arreglo['tono_disminuido']==1)					
				    		$contenido .= " Tono- izquierdo superior disminuido ";				
						if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_disminuido']==1)					
				    		$contenido .= " Tono- izquierdo inferior disminuido ";				
						if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_superior'] == 1 && $arreglo['tono_aumentado']==1)					
			    			$contenido .= " Tono- izquierdo superior aumentada ";				
						if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_aumentado']==1)					
				    		$contenido .= " Tono- izquierdo inferior aumentada ";					
		            }//if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_disminuido']==1 && $arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1)					
        		}//if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_conservado']==1 && $arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1)	
    		}//if ($arreglo['tono_izquierdo'] == 1 && $arreglo['tono_inferior'] == 1 && $arreglo['tono_aumentado']==1 && $arreglo['tono_derecho'] == 1 && $arreglo['tono_superior'] == 1)			
	
			/////EDEMA/////////////////////////////////////////////////////////////
			if ($arreglo['edema_inferior'] == 1 && $arreglo['edema_izquierdo'] == 1 && $arreglo['edema_derecho'] == 1)		
				$contenido .= " Edema miembros inferiores ";	
			else
			{
	    		if ($arreglo['edema_superior'] == 1 && $arreglo['edema_izquierdo'] == 1 && $arreglo['edema_derecho'] == 1)					
					$contenido .= " Edema miembros superiores ";		
				else
				{
					if ($arreglo['edema_superior'] == 1 && $arreglo['edema_izquierdo'] == 1)				
						$contenido .= " Edema superior izquierdo ";			
					if ($arreglo['edema_inferior'] == 1 && $arreglo['edema_izquierdo'] == 1)				
						$contenido .= " Edema inferior izquierdo ";			
					if ($arreglo['edema_inferior'] == 1 && $arreglo['edema_derecho'] == 1)				
						$contenido .= " Edema inferior derecho ";			
					if ($arreglo['edema_superior'] == 1 && $arreglo['edema_derecho'] == 1)				
						$contenido .= " Edema superior derecho ";			
				}//if ($arreglo['edema_superior'] == 1 && $arreglo['edema_izquierdo'] == 1 && $arreglo['edema_derecho'] == 1)				
			}//if ($arreglo['edema_inferior'] == 1 && $arreglo['edema_izquierdo'] == 1 && $arreglo['edema_derecho'] == 1)	
	 		if ($arreglo['fiebre_si'] == 1)	 
	     		$contenido .= " presento fiebre ";	 
	 		if ($arreglo['fiebre_no'] == 1)	 
	     		$contenido .= " se mantiene afebril ";	 
	 		if ($arreglo['foco'] != "" && $arreglo['foco'] != "sin foco" && $arreglo['foco'] != " ")	 
	     		$contenido .= " Tiene un foco ".$arreglo['foco'].", ".$arreglo['foco_dos'].", ".$arreglo['foco_tres'].", ".$arreglo['foco_cuatro'].", ".$arreglo['foco_cinco'].", ".$arreglo['foco_seis'];	 
	 		else
	     		$contenido .= " no tiene foco probable ";
     		if ($arreglo['cultivos_pendientes'] == 0)		 	 
	     		$contenido .= " Resultado de cultivos ".$arreglo['cultivos'];	 
	 		else
	     		$contenido .= " Cultivos Pendientes de Resultados ";	
	 		if ($arreglo['tratamientos_atb'] != "")		 	 
	     		$contenido .= " Tratamientos ATB: ".$arreglo['tratamientos_atb'];
	     	 
	        $xml.= "<table><thead><tbody><tr><th><strong>Evolución:</strong></th></tr>
			 		<tr><td>$contenido</td></tr></tbody></thead></table>";		 		
	 		
	 		if ($bandera ==1)
	 			$xml.="</tbody></thead></table>";
			$bd->select("SELECT * FROM hc_infectologico WHERE idhc=$this->id AND lugar=2");
	 		$bandera = 0;
	 		while ($hc_in = $bd->registro())
	 		{
	     		if ($bandera ==0)
		 		{		     		    
		     	    $xml.= "<table><thead><tbody><tr><th><strong>ANTIBIOTICOS:</strong></th></tr>";
			 		$bandera = 1;
		 		}
		 		$xml.= "<tr><td>".$hc_in["nombre"]." ".$hc_in["dia"]." dias</td></tr>";		 		
	 		}
	 		if ($bandera ==1)
	 			$xml.="</tbody></thead></table>";
	 			
	 		return $xml;		
		}					
		
		function diasArm($idepisodio,$fdesde,$fhasta,$fecha_egreso,$unidad)
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
	    	$where = '';
			if ($fdesde != '' && $fhasta != '')
			    $where = " AND (fecha>='".fechaBase($fdesde)."' AND fecha<='".fechaBase($ffin)."')";
			if ($unidad != '')
			    $where .= " AND tipo_terapia = $unidad ";    
			$bd->select("SELECT count(hc_terapia.idepisodio) as cantidad
	                    FROM hc_terapia  WHERE arm_dias <>0 AND arm_dias is not null AND idepisodio=$idepisodio $where
	 	                GROUP BY hc_terapia.idepisodio ");
	 	    $arreglo = $bd->registro();
	 	    if ($arreglo['cantidad'] != '' && $arreglo['cantidad'] != 0)
	 	        return $arreglo['cantidad'];
	 	    else
	 	        return 0;
		}
	}
?>