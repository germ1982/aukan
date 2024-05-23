<?
      class clase_encuesta_problemas_medicos       
      {
	  var $id = '';
          var $id_encuesta = '';
          var $fecha = '';
          var $control_periodico = '';
          var $control_odontologico = '';
          var $problemas_medicos = '';
          var $artritis_osteoporosis_problemas_espalda = '';
          var $problemas_articulares = '';
          var $corticoides_inyectables = '';
          var $diagnostico_cancer = '';
          var $diagnostico_cancer_tipo = '';
          var $tratamiento_cancer = '';
          var $problema_cardiovascular = '';
          var $problemas_medicamentos = '';
          var $arritmia = '';
          var $insuficiencia_cardiaca_cronica = '';
          var $enfermedad_arteria_coronaria = '';
          var $presion_elevada = '';
          var $problemas_presion_medicamentos = '';
          var $presion_mayor_160_90 = '';
          var $problemas_metabolicos = '';
          var $dificultad_control_azucar_en_sangre = '';
          var $hipoglucemia = '';
          var $complicaciones_diabetes = '';
          var $otros_problemas_metabolicos = '';
          var $interes_actividad_fisica = '';
          var $salud_mental = '';
          var $dificultad_controlar_salud_mental = '';
          var $sindrome_down = '';
          var $enfermedades_respiratorias = '';
          var $dificultad_control_enfermedad_respiratoria = '';
          var $bajo_nivel_oxigeno_sangre = '';
          var $asma = '';
          var $presion_alta_arterias_pulmonares = '';
          var $lesion_medula_espinal = '';
          var $dificultad_controlar_lesion_medula = '';
          var $presion_baja_reposo = '';
          var $disreflexia_autonomica = '';
          var $acv = '';
          var $dificultad_controlar_acv = '';
          var $dificultad_caminar = '';
          var $acv_seis_meses = '';
          var $condicion_medica_no_mencionada = '';
          var $perdida_conciencia = '';
          var $problema_medico_no_mencionado = '';
          var $mas_problemas_medicos = '';
          var $problema_medico_embarazo = '';
          var $baja_fecha = '';
          
      
      var $arreglo_foraneo_id_encuesta='';
      	     
      
         function clase_encuesta_problemas_medicos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM encuesta_problemas_medicos WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_encuesta=$arreglo['id_encuesta'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->control_periodico=$arreglo['control_periodico'];
      	     $this->control_odontologico=$arreglo['control_odontologico'];
      	     $this->problemas_medicos=$arreglo['problemas_medicos'];
      	     $this->artritis_osteoporosis_problemas_espalda=$arreglo['artritis_osteoporosis_problemas_espalda'];
      	     $this->problemas_articulares=$arreglo['problemas_articulares'];
      	     $this->corticoides_inyectables=$arreglo['corticoides_inyectables'];
      	     $this->diagnostico_cancer=$arreglo['diagnostico_cancer'];
      	     $this->diagnostico_cancer_tipo=$arreglo['diagnostico_cancer_tipo'];
      	     $this->tratamiento_cancer=$arreglo['tratamiento_cancer'];
      	     $this->problema_cardiovascular=$arreglo['problema_cardiovascular'];
      	     $this->problemas_medicamentos=$arreglo['problemas_medicamentos'];
      	     $this->arritmia=$arreglo['arritmia'];
      	     $this->insuficiencia_cardiaca_cronica=$arreglo['insuficiencia_cardiaca_cronica'];
      	     $this->enfermedad_arteria_coronaria=$arreglo['enfermedad_arteria_coronaria'];
      	     $this->presion_elevada=$arreglo['presion_elevada'];
      	     $this->problemas_presion_medicamentos=$arreglo['problemas_presion_medicamentos'];
      	     $this->presion_mayor_160_90=$arreglo['presion_mayor_160_90'];
      	     $this->problemas_metabolicos=$arreglo['problemas_metabolicos'];
      	     $this->dificultad_control_azucar_en_sangre=$arreglo['dificultad_control_azucar_en_sangre'];
      	     $this->hipoglucemia=$arreglo['hipoglucemia'];
      	     $this->complicaciones_diabetes=$arreglo['complicaciones_diabetes'];
      	     $this->otros_problemas_metabolicos=$arreglo['otros_problemas_metabolicos'];
      	     $this->interes_actividad_fisica=$arreglo['interes_actividad_fisica'];
      	     $this->salud_mental=$arreglo['salud_mental'];
      	     $this->dificultad_controlar_salud_mental=$arreglo['dificultad_controlar_salud_mental'];
      	     $this->sindrome_down=$arreglo['sindrome_down'];
      	     $this->enfermedades_respiratorias=$arreglo['enfermedades_respiratorias'];
      	     $this->dificultad_control_enfermedad_respiratoria=$arreglo['dificultad_control_enfermedad_respiratoria'];
      	     $this->bajo_nivel_oxigeno_sangre=$arreglo['bajo_nivel_oxigeno_sangre'];
      	     $this->asma=$arreglo['asma'];
      	     $this->presion_alta_arterias_pulmonares=$arreglo['presion_alta_arterias_pulmonares'];
      	     $this->lesion_medula_espinal=$arreglo['lesion_medula_espinal'];
      	     $this->dificultad_controlar_lesion_medula=$arreglo['dificultad_controlar_lesion_medula'];
      	     $this->presion_baja_reposo=$arreglo['presion_baja_reposo'];
      	     $this->disreflexia_autonomica=$arreglo['disreflexia_autonomica'];
      	     $this->acv=$arreglo['acv'];
      	     $this->dificultad_controlar_acv=$arreglo['dificultad_controlar_acv'];
      	     $this->dificultad_caminar=$arreglo['dificultad_caminar'];
      	     $this->acv_seis_meses=$arreglo['acv_seis_meses'];
      	     $this->condicion_medica_no_mencionada=$arreglo['condicion_medica_no_mencionada'];
      	     $this->perdida_conciencia=$arreglo['perdida_conciencia'];
      	     $this->problema_medico_no_mencionado=$arreglo['problema_medico_no_mencionado'];
      	     $this->mas_problemas_medicos=$arreglo['mas_problemas_medicos'];
      	     $this->problema_medico_embarazo=$arreglo['problema_medico_embarazo'];
      	     $this->baja_fecha=$arreglo['baja_fecha'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO encuesta_problemas_medicos(id_encuesta,fecha,control_periodico,control_odontologico,problemas_medicos,artritis_osteoporosis_problemas_espalda,problemas_articulares,corticoides_inyectables,diagnostico_cancer,diagnostico_cancer_tipo,tratamiento_cancer,problema_cardiovascular,problemas_medicamentos,arritmia,insuficiencia_cardiaca_cronica,enfermedad_arteria_coronaria,presion_elevada,problemas_presion_medicamentos,presion_mayor_160_90,problemas_metabolicos,dificultad_control_azucar_en_sangre,hipoglucemia,complicaciones_diabetes,otros_problemas_metabolicos,interes_actividad_fisica,salud_mental,dificultad_controlar_salud_mental,sindrome_down,enfermedades_respiratorias,dificultad_control_enfermedad_respiratoria,bajo_nivel_oxigeno_sangre,asma,presion_alta_arterias_pulmonares,lesion_medula_espinal,dificultad_controlar_lesion_medula,presion_baja_reposo,disreflexia_autonomica,acv,dificultad_controlar_acv,dificultad_caminar,acv_seis_meses,condicion_medica_no_mencionada,perdida_conciencia,problema_medico_no_mencionado,mas_problemas_medicos,problema_medico_embarazo,baja_fecha) VALUES('".$this->id_encuesta."','".$this->fecha."','".$this->control_periodico."','".$this->control_odontologico."','".$this->problemas_medicos."','".$this->artritis_osteoporosis_problemas_espalda."','".$this->problemas_articulares."','".$this->corticoides_inyectables."','".$this->diagnostico_cancer."','".$this->diagnostico_cancer_tipo."','".$this->tratamiento_cancer."','".$this->problema_cardiovascular."','".$this->problemas_medicamentos."','".$this->arritmia."','".$this->insuficiencia_cardiaca_cronica."','".$this->enfermedad_arteria_coronaria."','".$this->presion_elevada."','".$this->problemas_presion_medicamentos."','".$this->presion_mayor_160_90."','".$this->problemas_metabolicos."','".$this->dificultad_control_azucar_en_sangre."','".$this->hipoglucemia."','".$this->complicaciones_diabetes."','".$this->otros_problemas_metabolicos."','".$this->interes_actividad_fisica."','".$this->salud_mental."','".$this->dificultad_controlar_salud_mental."','".$this->sindrome_down."','".$this->enfermedades_respiratorias."','".$this->dificultad_control_enfermedad_respiratoria."','".$this->bajo_nivel_oxigeno_sangre."','".$this->asma."','".$this->presion_alta_arterias_pulmonares."','".$this->lesion_medula_espinal."','".$this->dificultad_controlar_lesion_medula."','".$this->presion_baja_reposo."','".$this->disreflexia_autonomica."','".$this->acv."','".$this->dificultad_controlar_acv."','".$this->dificultad_caminar."','".$this->acv_seis_meses."','".$this->condicion_medica_no_mencionada."','".$this->perdida_conciencia."','".$this->problema_medico_no_mencionado."','".$this->mas_problemas_medicos."','".$this->problema_medico_embarazo."','".$this->baja_fecha."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE encuesta_problemas_medicos SET id_encuesta='".$this->id_encuesta."',fecha='".$this->fecha."',control_periodico='".$this->control_periodico."',control_odontologico='".$this->control_odontologico."',problemas_medicos='".$this->problemas_medicos."',artritis_osteoporosis_problemas_espalda='".$this->artritis_osteoporosis_problemas_espalda."',problemas_articulares='".$this->problemas_articulares."',corticoides_inyectables='".$this->corticoides_inyectables."',diagnostico_cancer='".$this->diagnostico_cancer."',diagnostico_cancer_tipo='".$this->diagnostico_cancer_tipo."',tratamiento_cancer='".$this->tratamiento_cancer."',problema_cardiovascular='".$this->problema_cardiovascular."',problemas_medicamentos='".$this->problemas_medicamentos."',arritmia='".$this->arritmia."',insuficiencia_cardiaca_cronica='".$this->insuficiencia_cardiaca_cronica."',enfermedad_arteria_coronaria='".$this->enfermedad_arteria_coronaria."',presion_elevada='".$this->presion_elevada."',problemas_presion_medicamentos='".$this->problemas_presion_medicamentos."',presion_mayor_160_90='".$this->presion_mayor_160_90."',problemas_metabolicos='".$this->problemas_metabolicos."',dificultad_control_azucar_en_sangre='".$this->dificultad_control_azucar_en_sangre."',hipoglucemia='".$this->hipoglucemia."',complicaciones_diabetes='".$this->complicaciones_diabetes."',otros_problemas_metabolicos='".$this->otros_problemas_metabolicos."',interes_actividad_fisica='".$this->interes_actividad_fisica."',salud_mental='".$this->salud_mental."',dificultad_controlar_salud_mental='".$this->dificultad_controlar_salud_mental."',sindrome_down='".$this->sindrome_down."',enfermedades_respiratorias='".$this->enfermedades_respiratorias."',dificultad_control_enfermedad_respiratoria='".$this->dificultad_control_enfermedad_respiratoria."',bajo_nivel_oxigeno_sangre='".$this->bajo_nivel_oxigeno_sangre."',asma='".$this->asma."',presion_alta_arterias_pulmonares='".$this->presion_alta_arterias_pulmonares."',lesion_medula_espinal='".$this->lesion_medula_espinal."',dificultad_controlar_lesion_medula='".$this->dificultad_controlar_lesion_medula."',presion_baja_reposo='".$this->presion_baja_reposo."',disreflexia_autonomica='".$this->disreflexia_autonomica."',acv='".$this->acv."',dificultad_controlar_acv='".$this->dificultad_controlar_acv."',dificultad_caminar='".$this->dificultad_caminar."',acv_seis_meses='".$this->acv_seis_meses."',condicion_medica_no_mencionada='".$this->condicion_medica_no_mencionada."',perdida_conciencia='".$this->perdida_conciencia."',problema_medico_no_mencionado='".$this->problema_medico_no_mencionado."',mas_problemas_medicos='".$this->mas_problemas_medicos."',problema_medico_embarazo='".$this->problema_medico_embarazo."',baja_fecha='".$this->baja_fecha."' WHERE id='".$this->id."'"))
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
          function id_encuesta()
          {
               return $this->id_encuesta;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function control_periodico()
          {
               return $this->control_periodico;
          }
          function control_odontologico()
          {
               return $this->control_odontologico;
          }
          function problemas_medicos()
          {
               return $this->problemas_medicos;
          }
          function artritis_osteoporosis_problemas_espalda()
          {
               return $this->artritis_osteoporosis_problemas_espalda;
          }
          function problemas_articulares()
          {
               return $this->problemas_articulares;
          }
          function corticoides_inyectables()
          {
               return $this->corticoides_inyectables;
          }
          function diagnostico_cancer()
          {
               return $this->diagnostico_cancer;
          }
          function diagnostico_cancer_tipo()
          {
               return $this->diagnostico_cancer_tipo;
          }
          function tratamiento_cancer()
          {
               return $this->tratamiento_cancer;
          }
          function problema_cardiovascular()
          {
               return $this->problema_cardiovascular;
          }
          function problemas_medicamentos()
          {
               return $this->problemas_medicamentos;
          }
          function arritmia()
          {
               return $this->arritmia;
          }
          function insuficiencia_cardiaca_cronica()
          {
               return $this->insuficiencia_cardiaca_cronica;
          }
          function enfermedad_arteria_coronaria()
          {
               return $this->enfermedad_arteria_coronaria;
          }
          function presion_elevada()
          {
               return $this->presion_elevada;
          }
          function problemas_presion_medicamentos()
          {
               return $this->problemas_presion_medicamentos;
          }
          function presion_mayor_160_90()
          {
               return $this->presion_mayor_160_90;
          }
          function problemas_metabolicos()
          {
               return $this->problemas_metabolicos;
          }
          function dificultad_control_azucar_en_sangre()
          {
               return $this->dificultad_control_azucar_en_sangre;
          }
          function hipoglucemia()
          {
               return $this->hipoglucemia;
          }
          function complicaciones_diabetes()
          {
               return $this->complicaciones_diabetes;
          }
          function otros_problemas_metabolicos()
          {
               return $this->otros_problemas_metabolicos;
          }
          function interes_actividad_fisica()
          {
               return $this->interes_actividad_fisica;
          }
          function salud_mental()
          {
               return $this->salud_mental;
          }
          function dificultad_controlar_salud_mental()
          {
               return $this->dificultad_controlar_salud_mental;
          }
          function sindrome_down()
          {
               return $this->sindrome_down;
          }
          function enfermedades_respiratorias()
          {
               return $this->enfermedades_respiratorias;
          }
          function dificultad_control_enfermedad_respiratoria()
          {
               return $this->dificultad_control_enfermedad_respiratoria;
          }
          function bajo_nivel_oxigeno_sangre()
          {
               return $this->bajo_nivel_oxigeno_sangre;
          }
          function asma()
          {
               return $this->asma;
          }
          function presion_alta_arterias_pulmonares()
          {
               return $this->presion_alta_arterias_pulmonares;
          }
          function lesion_medula_espinal()
          {
               return $this->lesion_medula_espinal;
          }
          function dificultad_controlar_lesion_medula()
          {
               return $this->dificultad_controlar_lesion_medula;
          }
          function presion_baja_reposo()
          {
               return $this->presion_baja_reposo;
          }
          function disreflexia_autonomica()
          {
               return $this->disreflexia_autonomica;
          }
          function acv()
          {
               return $this->acv;
          }
          function dificultad_controlar_acv()
          {
               return $this->dificultad_controlar_acv;
          }
          function dificultad_caminar()
          {
               return $this->dificultad_caminar;
          }
          function acv_seis_meses()
          {
               return $this->acv_seis_meses;
          }
          function condicion_medica_no_mencionada()
          {
               return $this->condicion_medica_no_mencionada;
          }
          function perdida_conciencia()
          {
               return $this->perdida_conciencia;
          }
          function problema_medico_no_mencionado()
          {
               return $this->problema_medico_no_mencionado;
          }
          function mas_problemas_medicos()
          {
               return $this->mas_problemas_medicos;
          }
          function problema_medico_embarazo()
          {
               return $this->problema_medico_embarazo;
          }
          function baja_fecha()
          {
               return $this->baja_fecha;
          }
          
          
          
      	     function arreglo_foraneo_id_encuesta()
             {
                 return $this->arreglo_foraneo_id_encuesta;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function id_encuesta_asigna($campo)
          {
               $this->id_encuesta=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function control_periodico_asigna($campo)
          {
               $this->control_periodico=$campo;
               
          }
          function control_odontologico_asigna($campo)
          {
               $this->control_odontologico=$campo;
               
          }
          function problemas_medicos_asigna($campo)
          {
               $this->problemas_medicos=$campo;
               
          }
          function artritis_osteoporosis_problemas_espalda_asigna($campo)
          {
               $this->artritis_osteoporosis_problemas_espalda=$campo;
               
          }
          function problemas_articulares_asigna($campo)
          {
               $this->problemas_articulares=$campo;
               
          }
          function corticoides_inyectables_asigna($campo)
          {
               $this->corticoides_inyectables=$campo;
               
          }
          function diagnostico_cancer_asigna($campo)
          {
               $this->diagnostico_cancer=$campo;
               
          }
          function diagnostico_cancer_tipo_asigna($campo)
          {
               $this->diagnostico_cancer_tipo=$campo;
               
          }
          function tratamiento_cancer_asigna($campo)
          {
               $this->tratamiento_cancer=$campo;
               
          }
          function problema_cardiovascular_asigna($campo)
          {
               $this->problema_cardiovascular=$campo;
               
          }
          function problemas_medicamentos_asigna($campo)
          {
               $this->problemas_medicamentos=$campo;
               
          }
          function arritmia_asigna($campo)
          {
               $this->arritmia=$campo;
               
          }
          function insuficiencia_cardiaca_cronica_asigna($campo)
          {
               $this->insuficiencia_cardiaca_cronica=$campo;
               
          }
          function enfermedad_arteria_coronaria_asigna($campo)
          {
               $this->enfermedad_arteria_coronaria=$campo;
               
          }
          function presion_elevada_asigna($campo)
          {
               $this->presion_elevada=$campo;
               
          }
          function problemas_presion_medicamentos_asigna($campo)
          {
               $this->problemas_presion_medicamentos=$campo;
               
          }
          function presion_mayor_160_90_asigna($campo)
          {
               $this->presion_mayor_160_90=$campo;
               
          }
          function problemas_metabolicos_asigna($campo)
          {
               $this->problemas_metabolicos=$campo;
               
          }
          function dificultad_control_azucar_en_sangre_asigna($campo)
          {
               $this->dificultad_control_azucar_en_sangre=$campo;
               
          }
          function hipoglucemia_asigna($campo)
          {
               $this->hipoglucemia=$campo;
               
          }
          function complicaciones_diabetes_asigna($campo)
          {
               $this->complicaciones_diabetes=$campo;
               
          }
          function otros_problemas_metabolicos_asigna($campo)
          {
               $this->otros_problemas_metabolicos=$campo;
               
          }
          function interes_actividad_fisica_asigna($campo)
          {
               $this->interes_actividad_fisica=$campo;
               
          }
          function salud_mental_asigna($campo)
          {
               $this->salud_mental=$campo;
               
          }
          function dificultad_controlar_salud_mental_asigna($campo)
          {
               $this->dificultad_controlar_salud_mental=$campo;
               
          }
          function sindrome_down_asigna($campo)
          {
               $this->sindrome_down=$campo;
               
          }
          function enfermedades_respiratorias_asigna($campo)
          {
               $this->enfermedades_respiratorias=$campo;
               
          }
          function dificultad_control_enfermedad_respiratoria_asigna($campo)
          {
               $this->dificultad_control_enfermedad_respiratoria=$campo;
               
          }
          function bajo_nivel_oxigeno_sangre_asigna($campo)
          {
               $this->bajo_nivel_oxigeno_sangre=$campo;
               
          }
          function asma_asigna($campo)
          {
               $this->asma=$campo;
               
          }
          function presion_alta_arterias_pulmonares_asigna($campo)
          {
               $this->presion_alta_arterias_pulmonares=$campo;
               
          }
          function lesion_medula_espinal_asigna($campo)
          {
               $this->lesion_medula_espinal=$campo;
               
          }
          function dificultad_controlar_lesion_medula_asigna($campo)
          {
               $this->dificultad_controlar_lesion_medula=$campo;
               
          }
          function presion_baja_reposo_asigna($campo)
          {
               $this->presion_baja_reposo=$campo;
               
          }
          function disreflexia_autonomica_asigna($campo)
          {
               $this->disreflexia_autonomica=$campo;
               
          }
          function acv_asigna($campo)
          {
               $this->acv=$campo;
               
          }
          function dificultad_controlar_acv_asigna($campo)
          {
               $this->dificultad_controlar_acv=$campo;
               
          }
          function dificultad_caminar_asigna($campo)
          {
               $this->dificultad_caminar=$campo;
               
          }
          function acv_seis_meses_asigna($campo)
          {
               $this->acv_seis_meses=$campo;
               
          }
          function condicion_medica_no_mencionada_asigna($campo)
          {
               $this->condicion_medica_no_mencionada=$campo;
               
          }
          function perdida_conciencia_asigna($campo)
          {
               $this->perdida_conciencia=$campo;
               
          }
          function problema_medico_no_mencionado_asigna($campo)
          {
               $this->problema_medico_no_mencionado=$campo;
               
          }
          function mas_problemas_medicos_asigna($campo)
          {
               $this->mas_problemas_medicos=$campo;
               
          }
          function problema_medico_embarazo_asigna($campo)
          {
               $this->problema_medico_embarazo=$campo;
               
          }
          function baja_fecha_asigna($campo)
          {
               $this->baja_fecha=$campo;
               
          }
          
          
          
	      function foranea_id_encuesta($id_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_problemas_medicos WHERE id_encuesta=$id_encuesta");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_encuesta = $pro;		                              		
			}
			
      
}
?>