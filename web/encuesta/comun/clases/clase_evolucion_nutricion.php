<?
      class clase_evolucion_nutricion       
      {
	  	  var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $talla = '';
          var $peso_actual = '';
          var $perdida_6_meses_kg = '';
          var $perdida_6_meses_porcentaje = '';
          var $cambio_2_semanas_kg = '';
          var $cambio_2_semanas_porcenaje = '';
          var $suplemento = '';
          var $sintomas_gastrointestinales_ninguno = '';
          var $sintomas_gastrointestinales_nauseas = '';
          var $sintomas_gastrointestinales_vomito = '';
          var $sintomas_gastrointestinales_diarrea = '';
          var $sintomas_gastrointestinales_dolor_posprandial = '';
          var $sintomas_gastrointestinales_distension_abdominal = '';
          var $cambio_ingesta = '';
          var $cambio_ingesta_duracion = '';
          var $tipo_cambio_ingesta = '';
          var $texto_tesauro = '';
          var $subsetid = '';
          var $descriptionid = '';
          var $demanda_metabolica = '';
          var $antropometria_edad = '';
          var $antropometria_peso = '';
          var $antropometria_peso_habitual = '';
          var $antropometria_peso_teorico = '';
          var $antropometria_talla = '';
          var $antropometria_imc = '';
          var $antropometria_cb = '';
          var $antropometria_cp = '';
          var $diagnostico_nutricional = '';
          var $colesterol_total = '';
          var $trigliceridos = '';
          var $glucemia = '';
          var $proteinas_totales = '';
          var $albumina = '';
          var $pre_albumina = '';
          var $prescripcion_dietoterapica = '';
          var $monitoreo_nutricional = '';
          
      
      var $arreglo_foraneo_idepisodio='';
      	     
      
         function clase_evolucion_nutricion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM evolucion_nutricion WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar_campos($arreglo);
      	     
      	 }
      	 function asignar_campos($arreglo)
      	 {
      	 	 $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->talla=$arreglo['talla'];
      	     $this->peso_actual=$arreglo['peso_actual'];
      	     $this->perdida_6_meses_kg=$arreglo['perdida_6_meses_kg'];
      	     $this->perdida_6_meses_porcentaje=$arreglo['perdida_6_meses_porcentaje'];
      	     $this->cambio_2_semanas_kg=$arreglo['cambio_2_semanas_kg'];
      	     $this->cambio_2_semanas_porcenaje=$arreglo['cambio_2_semanas_porcenaje'];
      	     $this->suplemento=$arreglo['suplemento'];
      	     $this->sintomas_gastrointestinales_ninguno=$arreglo['sintomas_gastrointestinales_ninguno'];
      	     $this->sintomas_gastrointestinales_nauseas=$arreglo['sintomas_gastrointestinales_nauseas'];
      	     $this->sintomas_gastrointestinales_vomito=$arreglo['sintomas_gastrointestinales_vomito'];
      	     $this->sintomas_gastrointestinales_diarrea=$arreglo['sintomas_gastrointestinales_diarrea'];
      	     $this->sintomas_gastrointestinales_dolor_posprandial=$arreglo['sintomas_gastrointestinales_dolor_posprandial'];
      	     $this->sintomas_gastrointestinales_distension_abdominal=$arreglo['sintomas_gastrointestinales_distension_abdominal'];
      	     $this->cambio_ingesta=$arreglo['cambio_ingesta'];
      	     $this->cambio_ingesta_duracion=$arreglo['cambio_ingesta_duracion'];
      	     $this->tipo_cambio_ingesta=$arreglo['tipo_cambio_ingesta'];
      	     $this->texto_tesauro=$arreglo['texto_tesauro'];
      	     $this->subsetid=$arreglo['subsetid'];
      	     $this->descriptionid=$arreglo['descriptionid'];
      	     $this->demanda_metabolica=$arreglo['demanda_metabolica'];
      	     $this->antropometria_edad=$arreglo['antropometria_edad'];
      	     $this->antropometria_peso=$arreglo['antropometria_peso'];
      	     $this->antropometria_peso_habitual=$arreglo['antropometria_peso_habitual'];
      	     $this->antropometria_peso_teorico=$arreglo['antropometria_peso_teorico'];
      	     $this->antropometria_talla=$arreglo['antropometria_talla'];
      	     $this->antropometria_imc=$arreglo['antropometria_imc'];
      	     $this->antropometria_cb=$arreglo['antropometria_cb'];
      	     $this->antropometria_cp=$arreglo['antropometria_cp'];
      	     $this->diagnostico_nutricional=$arreglo['diagnostico_nutricional'];
      	     $this->colesterol_total=$arreglo['colesterol_total'];
      	     $this->trigliceridos=$arreglo['trigliceridos'];
      	     $this->glucemia=$arreglo['glucemia'];
      	     $this->proteinas_totales=$arreglo['proteinas_totales'];
      	     $this->albumina=$arreglo['albumina'];
      	     $this->pre_albumina=$arreglo['pre_albumina'];
      	     $this->prescripcion_dietoterapica=$arreglo['prescripcion_dietoterapica'];
      	     $this->monitoreo_nutricional=$arreglo['monitoreo_nutricional'];
      	 }       
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO evolucion_nutricion(idprofesional,idepisodio,fecha,hora,talla,peso_actual,perdida_6_meses_kg,perdida_6_meses_porcentaje,cambio_2_semanas_kg,cambio_2_semanas_porcenaje,suplemento,sintomas_gastrointestinales_ninguno,sintomas_gastrointestinales_nauseas,sintomas_gastrointestinales_vomito,sintomas_gastrointestinales_diarrea,sintomas_gastrointestinales_dolor_posprandial,sintomas_gastrointestinales_distension_abdominal,cambio_ingesta,cambio_ingesta_duracion,tipo_cambio_ingesta,texto_tesauro,subsetid,descriptionid,demanda_metabolica,antropometria_edad,antropometria_peso,antropometria_peso_habitual,antropometria_peso_teorico,antropometria_talla,antropometria_imc,antropometria_cb,antropometria_cp,diagnostico_nutricional,colesterol_total,trigliceridos,glucemia,proteinas_totales,albumina,pre_albumina,prescripcion_dietoterapica,monitoreo_nutricional) VALUES('".$this->idprofesional."','".$this->idepisodio."','".$this->fecha."','".$this->hora."','".$this->talla."','".$this->peso_actual."','".$this->perdida_6_meses_kg."','".$this->perdida_6_meses_porcentaje."','".$this->cambio_2_semanas_kg."','".$this->cambio_2_semanas_porcenaje."','".$this->suplemento."','".$this->sintomas_gastrointestinales_ninguno."','".$this->sintomas_gastrointestinales_nauseas."','".$this->sintomas_gastrointestinales_vomito."','".$this->sintomas_gastrointestinales_diarrea."','".$this->sintomas_gastrointestinales_dolor_posprandial."','".$this->sintomas_gastrointestinales_distension_abdominal."','".$this->cambio_ingesta."','".$this->cambio_ingesta_duracion."','".$this->tipo_cambio_ingesta."','".$this->texto_tesauro."','".$this->subsetid."','".$this->descriptionid."','".$this->demanda_metabolica."','".$this->antropometria_edad."','".$this->antropometria_peso."','".$this->antropometria_peso_habitual."','".$this->antropometria_peso_teorico."','".$this->antropometria_talla."','".$this->antropometria_imc."','".$this->antropometria_cb."','".$this->antropometria_cp."','".$this->diagnostico_nutricional."','".$this->colesterol_total."','".$this->trigliceridos."','".$this->glucemia."','".$this->proteinas_totales."','".$this->albumina."','".$this->pre_albumina."','".$this->prescripcion_dietoterapica."','".$this->monitoreo_nutricional."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE evolucion_nutricion SET fecha='".$this->fecha."',hora='".$this->hora."',talla='".$this->talla."',peso_actual='".$this->peso_actual."',perdida_6_meses_kg='".$this->perdida_6_meses_kg."',perdida_6_meses_porcentaje='".$this->perdida_6_meses_porcentaje."',cambio_2_semanas_kg='".$this->cambio_2_semanas_kg."',cambio_2_semanas_porcenaje='".$this->cambio_2_semanas_porcenaje."',suplemento='".$this->suplemento."',sintomas_gastrointestinales_ninguno='".$this->sintomas_gastrointestinales_ninguno."',sintomas_gastrointestinales_nauseas='".$this->sintomas_gastrointestinales_nauseas."',sintomas_gastrointestinales_vomito='".$this->sintomas_gastrointestinales_vomito."',sintomas_gastrointestinales_diarrea='".$this->sintomas_gastrointestinales_diarrea."',sintomas_gastrointestinales_dolor_posprandial='".$this->sintomas_gastrointestinales_dolor_posprandial."',sintomas_gastrointestinales_distension_abdominal='".$this->sintomas_gastrointestinales_distension_abdominal."',cambio_ingesta='".$this->cambio_ingesta."',cambio_ingesta_duracion='".$this->cambio_ingesta_duracion."',tipo_cambio_ingesta='".$this->tipo_cambio_ingesta."',texto_tesauro='".$this->texto_tesauro."',subsetid='".$this->subsetid."',descriptionid='".$this->descriptionid."',demanda_metabolica='".$this->demanda_metabolica."',antropometria_edad='".$this->antropometria_edad."',antropometria_peso='".$this->antropometria_peso."',antropometria_peso_habitual='".$this->antropometria_peso_habitual."',antropometria_peso_teorico='".$this->antropometria_peso_teorico."',antropometria_talla='".$this->antropometria_talla."',antropometria_imc='".$this->antropometria_imc."',antropometria_cb='".$this->antropometria_cb."',antropometria_cp='".$this->antropometria_cp."',diagnostico_nutricional='".$this->diagnostico_nutricional."',colesterol_total='".$this->colesterol_total."',trigliceridos='".$this->trigliceridos."',glucemia='".$this->glucemia."',proteinas_totales='".$this->proteinas_totales."',albumina='".$this->albumina."',pre_albumina='".$this->pre_albumina."',prescripcion_dietoterapica='".$this->prescripcion_dietoterapica."',monitoreo_nutricional='".$this->monitoreo_nutricional."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function id()
          {
               return $this->id;
          }
          function idepisodio()
          {
               return $this->idepisodio;
          }
      	  function idprofesional()
          {
               return $this->idprofesional;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function talla()
          {
               return $this->talla;
          }
          function peso_actual()
          {
               return $this->peso_actual;
          }
          function perdida_6_meses_kg()
          {
               return $this->perdida_6_meses_kg;
          }
          function perdida_6_meses_porcentaje()
          {
               return $this->perdida_6_meses_porcentaje;
          }
          function cambio_2_semanas_kg()
          {
               return $this->cambio_2_semanas_kg;
          }
          function cambio_2_semanas_porcenaje()
          {
               return $this->cambio_2_semanas_porcenaje;
          }
          function suplemento()
          {
               return $this->suplemento;
          }
          function sintomas_gastrointestinales_ninguno()
          {
               return $this->sintomas_gastrointestinales_ninguno;
          }
          function sintomas_gastrointestinales_nauseas()
          {
               return $this->sintomas_gastrointestinales_nauseas;
          }
          function sintomas_gastrointestinales_vomito()
          {
               return $this->sintomas_gastrointestinales_vomito;
          }
          function sintomas_gastrointestinales_diarrea()
          {
               return $this->sintomas_gastrointestinales_diarrea;
          }
          function sintomas_gastrointestinales_dolor_posprandial()
          {
               return $this->sintomas_gastrointestinales_dolor_posprandial;
          }
          function sintomas_gastrointestinales_distension_abdominal()
          {
               return $this->sintomas_gastrointestinales_distension_abdominal;
          }
          function cambio_ingesta()
          {
               return $this->cambio_ingesta;
          }
          function cambio_ingesta_duracion()
          {
               return $this->cambio_ingesta_duracion;
          }
          function tipo_cambio_ingesta()
          {
               return $this->tipo_cambio_ingesta;
          }
          function texto_tesauro()
          {
               return $this->texto_tesauro;
          }
          function subsetid()
          {
               return $this->subsetid;
          }
          function descriptionid()
          {
               return $this->descriptionid;
          }
          function demanda_metabolica()
          {
               return $this->demanda_metabolica;
          }
          function antropometria_edad()
          {
               return $this->antropometria_edad;
          }
          function antropometria_peso()
          {
               return $this->antropometria_peso;
          }
          function antropometria_peso_habitual()
          {
               return $this->antropometria_peso_habitual;
          }
          function antropometria_peso_teorico()
          {
               return $this->antropometria_peso_teorico;
          }
          function antropometria_talla()
          {
               return $this->antropometria_talla;
          }
          function antropometria_imc()
          {
               return $this->antropometria_imc;
          }
          function antropometria_cb()
          {
               return $this->antropometria_cb;
          }
          function antropometria_cp()
          {
               return $this->antropometria_cp;
          }
          function diagnostico_nutricional()
          {
               return $this->diagnostico_nutricional;
          }
          function colesterol_total()
          {
               return $this->colesterol_total;
          }
          function trigliceridos()
          {
               return $this->trigliceridos;
          }
          function glucemia()
          {
               return $this->glucemia;
          }
          function proteinas_totales()
          {
               return $this->proteinas_totales;
          }
          function albumina()
          {
               return $this->albumina;
          }
          function pre_albumina()
          {
               return $this->pre_albumina;
          }
          function prescripcion_dietoterapica()
          {
               return $this->prescripcion_dietoterapica;
          }
          function monitoreo_nutricional()
          {
               return $this->monitoreo_nutricional;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
      	  function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function talla_asigna($campo)
          {
               $this->talla=$campo;
               
          }
          function peso_actual_asigna($campo)
          {
               $this->peso_actual=$campo;
               
          }
          function perdida_6_meses_kg_asigna($campo)
          {
               $this->perdida_6_meses_kg=$campo;
               
          }
          function perdida_6_meses_porcentaje_asigna($campo)
          {
               $this->perdida_6_meses_porcentaje=$campo;
               
          }
          function cambio_2_semanas_kg_asigna($campo)
          {
               $this->cambio_2_semanas_kg=$campo;
               
          }
          function cambio_2_semanas_porcenaje_asigna($campo)
          {
               $this->cambio_2_semanas_porcenaje=$campo;
               
          }
          function suplemento_asigna($campo)
          {
               $this->suplemento=$campo;
               
          }
          function sintomas_gastrointestinales_ninguno_asigna($campo)
          {
               $this->sintomas_gastrointestinales_ninguno=$campo;
               
          }
          function sintomas_gastrointestinales_nauseas_asigna($campo)
          {
               $this->sintomas_gastrointestinales_nauseas=$campo;
               
          }
          function sintomas_gastrointestinales_vomito_asigna($campo)
          {
               $this->sintomas_gastrointestinales_vomito=$campo;
               
          }
          function sintomas_gastrointestinales_diarrea_asigna($campo)
          {
               $this->sintomas_gastrointestinales_diarrea=$campo;
               
          }
          function sintomas_gastrointestinales_dolor_posprandial_asigna($campo)
          {
               $this->sintomas_gastrointestinales_dolor_posprandial=$campo;
               
          }
          function sintomas_gastrointestinales_distension_abdominal_asigna($campo)
          {
               $this->sintomas_gastrointestinales_distension_abdominal=$campo;
               
          }
          function cambio_ingesta_asigna($campo)
          {
               $this->cambio_ingesta=$campo;
               
          }
          function cambio_ingesta_duracion_asigna($campo)
          {
               $this->cambio_ingesta_duracion=$campo;
               
          }
          function tipo_cambio_ingesta_asigna($campo)
          {
               $this->tipo_cambio_ingesta=$campo;
               
          }
          function texto_tesauro_asigna($campo)
          {
               $this->texto_tesauro=$campo;
               
          }
          function subsetid_asigna($campo)
          {
               $this->subsetid=$campo;
               
          }
          function descriptionid_asigna($campo)
          {
               $this->descriptionid=$campo;
               
          }
          function demanda_metabolica_asigna($campo)
          {
               $this->demanda_metabolica=$campo;
               
          }
          function antropometria_edad_asigna($campo)
          {
               $this->antropometria_edad=$campo;
               
          }
          function antropometria_peso_asigna($campo)
          {
               $this->antropometria_peso=$campo;
               
          }
          function antropometria_peso_habitual_asigna($campo)
          {
               $this->antropometria_peso_habitual=$campo;
               
          }
          function antropometria_peso_teorico_asigna($campo)
          {
               $this->antropometria_peso_teorico=$campo;
               
          }
          function antropometria_talla_asigna($campo)
          {
               $this->antropometria_talla=$campo;
               
          }
          function antropometria_imc_asigna($campo)
          {
               $this->antropometria_imc=$campo;
               
          }
          function antropometria_cb_asigna($campo)
          {
               $this->antropometria_cb=$campo;
               
          }
          function antropometria_cp_asigna($campo)
          {
               $this->antropometria_cp=$campo;
               
          }
          function diagnostico_nutricional_asigna($campo)
          {
               $this->diagnostico_nutricional=$campo;
               
          }
          function colesterol_total_asigna($campo)
          {
               $this->colesterol_total=$campo;
               
          }
          function trigliceridos_asigna($campo)
          {
               $this->trigliceridos=$campo;
               
          }
          function glucemia_asigna($campo)
          {
               $this->glucemia=$campo;
               
          }
          function proteinas_totales_asigna($campo)
          {
               $this->proteinas_totales=$campo;
               
          }
          function albumina_asigna($campo)
          {
               $this->albumina=$campo;
               
          }
          function pre_albumina_asigna($campo)
          {
               $this->pre_albumina=$campo;
               
          }
          function prescripcion_dietoterapica_asigna($campo)
          {
               $this->prescripcion_dietoterapica=$campo;
               
          }
          function monitoreo_nutricional_asigna($campo)
          {
               $this->monitoreo_nutricional=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM evolucion_nutricion WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
			
      
}
?>