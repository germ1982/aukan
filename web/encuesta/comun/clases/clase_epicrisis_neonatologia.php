<?
      class clase_epicrisis_neonatologia       
      {
	  var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $fecha_egreso = '';
          var $hora = '';
          var $fecha_nacimiento_corregida = '';
          var $patologia_respiratoria = '';
          var $fio2max = '';
          var $dias_o2 = '';
          var $cpap = '';
          var $arm = '';
          var $apneas = '';
          var $tratamiento_respiratorio = '';
          var $respiratorio_observaciones = '';
          var $infeccion = '';
          var $infeccion_diagnostico = '';
          var $infeccion_cultivos = '';
          var $infeccion_atb = '';
          var $infeccion_atb_dias = '';
          var $infeccion_laboratorio = '';
          var $hiperbilirrubinemia = '';
          var $hiperbilirrubinemia_dias_lmt = '';
          var $hiperbilirrubinemia_valor_maximo_bili = '';
          var $hiperbilirrubinemia_exanguino = '';
          var $hiperbilirrubinemia_diagnostico = '';
          var $metabolicas = '';
          var $metabolicas_diagnostico = '';
          var $metabolicas_tratamiento = '';
          var $hematologicas = '';
          var $hematologicas_anemia = '';
          var $hematologicas_hora_alta = '';
          var $hematologicas_transfusiones = '';
          var $hematologicas_policitemia = '';
          var $patologia_quirurgica = '';
          var $patologia_quirurgica_diagnostico = '';
          var $patologia_quirurgica_tratamiento_quirurgico = '';
          var $neurologicas = '';
          var $neurologicas_diagnostico = '';
          var $neurologicas_hiv_grado = '';
          var $neurologicas_ecografia_cerebral = '';
          var $neurologicas_asfixia = '';
          var $neurologicas_convulsiones = '';
          var $neurologicas_tratamiento = '';
          var $neurologicas_ex_neurologico_alta = '';
          var $digestivas = '';
          var $digestivas_diagnostico = '';
          var $digestivas_enteral_inicio = '';
          var $digestivas_sog_sng = '';
          var $digestivas_formula = '';
          var $digestivas_parenteral = '';
          var $digestivas_observaciones = '';
          var $procedimientos_invasivos = '';
          var $procedimientos_invasivos_cateter_venoso = '';
          var $procedimientos_invasivos_cateter_arterial = '';
          var $procedimientos_invasivos_otros = '';
          var $procedimientos_invasivos_tubo_toraxico = '';
          var $cardiovascular_soplo = '';
          var $cardiovascular_ic_cardiologia = '';
          var $cardiovascular_dap = '';
          var $cardiovascular_dap_tratamiento = '';
          var $cardiovascular_hipotension = '';
          var $cardiovascular_insuficiencia_cardiaca = '';
          var $cardiovascular_paro = '';
          var $examenes_alta_laboratorio_fecha = '';
          var $examenes_alta_laboratorio = '';
          var $examenes_alta_fo = '';
          var $examenes_alta_oea = '';
          var $examenes_alta_sn = '';
          var $examenes_alta_otros = '';
          var $examenes_alta_peso_alta = '';
          var $examenes_alta_talla_alta = '';
          var $examenes_alta_pc_alta = '';
          var $condiciones_alta_alimentacion = '';
          var $condiciones_alta_medicacion = '';
          var $condiciones_alta_pendientes = '';
          var $condiciones_alta_situaciones_riesgo = '';
          var $ampliacion_conceptos = '';
          var $proximo_control = '';
          var $medico_tratante = '';
          var $tipo = '';
          var $digestivas_sog_sng_dias = '';
          var $examenes_alta_fo_estado = '';
          var $examenes_alta_peso_nacimiento_gramos = '';
          var $examenes_alta_peso_nacimiento_dias = '';
          var $neurologicas_ecografia_cerebral_estado = '';
          var $vivo = '';
          var $pesquisas_metabolicas = '';
          var $pesquisas_metabolicas_fechas = '';
          var $tipo_fondo_ojos = '';
          var $tratamiento_fondo_ojos = '';
          var $traslado_mayor_complejidad = '';
          var $alimentacion_enteral_alta = '';
          var $traslado_otra_institucion = '';
          var $infeccion_asociada_cateter = '';
          var $infeccion_asociada_cateter_germen = '';
          var $alteracion_hidroelectrolitica = '';
          var $alteracion_hidroelectrolitica_tipo = '';
          var $alteracion_hidroelectrolitica_tratamiento = '';
          var $grado_3 = 0;
		  var $grado_4 = 0;
		  var $rop_1 = 0;
		  var $rop_2 = 0;
		  var $rop_3 = 0;
		  var $rop_4 = 0;
		  var $peso_alta_mayor_15_dias = 0;
		  var $peso_alta_menor_15_dias = 0;
		  var $cantidad_diagnostico = 0;
          var $gesta_24 = 0;
          var $gesta_25 = 0;
          var $gesta_26 = 0;
          var $gesta_27 = 0;
          var $gesta_28 = 0;
          var $gesta_29 = 0;
          var $gesta_30 = 0;
          var $gesta_31 = 0;
          var $gesta_32 = 0;
          var $gesta_33 = 0;
          var $gesta_34 = 0;
          var $gesta_35 = 0;
          var $gesta_36 = 0;
          var $gesta_37 = 0;
          var $peso_500 = 0;
          var $peso_750 = 0;
          var $peso_1000 = 0;
          var $peso_1250 = 0;
          var $peso_1500 = 0;
          var $peso_2000 = 0;
          var $peso_2500 = 0;
      
      var $arreglo_foraneo_idepisodio='';
      	     
      
         function clase_epicrisis_neonatologia($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM epicrisis_neonatologia WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha_egreso=$arreglo['fecha_egreso'];
      	     $this->hora=$arreglo['hora'];
      	     $this->fecha_nacimiento_corregida=$arreglo['fecha_nacimiento_corregida'];
      	     $this->patologia_respiratoria=$arreglo['patologia_respiratoria'];
      	     $this->fio2max=$arreglo['fio2max'];
      	     $this->dias_o2=$arreglo['dias_o2'];
      	     $this->cpap=$arreglo['cpap'];
      	     $this->arm=$arreglo['arm'];
      	     $this->apneas=$arreglo['apneas'];
      	     $this->tratamiento_respiratorio=$arreglo['tratamiento_respiratorio'];
      	     $this->respiratorio_observaciones=$arreglo['respiratorio_observaciones'];
      	     $this->infeccion=$arreglo['infeccion'];
      	     $this->infeccion_diagnostico=$arreglo['infeccion_diagnostico'];
      	     $this->infeccion_cultivos=$arreglo['infeccion_cultivos'];
      	     $this->infeccion_atb=$arreglo['infeccion_atb'];
      	     $this->infeccion_atb_dias=$arreglo['infeccion_atb_dias'];
      	     $this->infeccion_laboratorio=$arreglo['infeccion_laboratorio'];
      	     $this->hiperbilirrubinemia=$arreglo['hiperbilirrubinemia'];
      	     $this->hiperbilirrubinemia_dias_lmt=$arreglo['hiperbilirrubinemia_dias_lmt'];
      	     $this->hiperbilirrubinemia_valor_maximo_bili=$arreglo['hiperbilirrubinemia_valor_maximo_bili'];
      	     $this->hiperbilirrubinemia_exanguino=$arreglo['hiperbilirrubinemia_exanguino'];
      	     $this->hiperbilirrubinemia_diagnostico=$arreglo['hiperbilirrubinemia_diagnostico'];
      	     $this->metabolicas=$arreglo['metabolicas'];
      	     $this->metabolicas_diagnostico=$arreglo['metabolicas_diagnostico'];
      	     $this->metabolicas_tratamiento=$arreglo['metabolicas_tratamiento'];
      	     $this->hematologicas=$arreglo['hematologicas'];
      	     $this->hematologicas_anemia=$arreglo['hematologicas_anemia'];
      	     $this->hematologicas_hora_alta=$arreglo['hematologicas_hora_alta'];
      	     $this->hematologicas_transfusiones=$arreglo['hematologicas_transfusiones'];
      	     $this->hematologicas_policitemia=$arreglo['hematologicas_policitemia'];
      	     $this->patologia_quirurgica=$arreglo['patologia_quirurgica'];
      	     $this->patologia_quirurgica_diagnostico=$arreglo['patologia_quirurgica_diagnostico'];
      	     $this->patologia_quirurgica_tratamiento_quirurgico=$arreglo['patologia_quirurgica_tratamiento_quirurgico'];
      	     $this->neurologicas=$arreglo['neurologicas'];
      	     $this->neurologicas_diagnostico=$arreglo['neurologicas_diagnostico'];
      	     $this->neurologicas_hiv_grado=$arreglo['neurologicas_hiv_grado'];
      	     $this->neurologicas_ecografia_cerebral=$arreglo['neurologicas_ecografia_cerebral'];
      	     $this->neurologicas_asfixia=$arreglo['neurologicas_asfixia'];
      	     $this->neurologicas_convulsiones=$arreglo['neurologicas_convulsiones'];
      	     $this->neurologicas_tratamiento=$arreglo['neurologicas_tratamiento'];
      	     $this->neurologicas_ex_neurologico_alta=$arreglo['neurologicas_ex_neurologico_alta'];
      	     $this->digestivas=$arreglo['digestivas'];
      	     $this->digestivas_diagnostico=$arreglo['digestivas_diagnostico'];
      	     $this->digestivas_enteral_inicio=$arreglo['digestivas_enteral_inicio'];
      	     $this->digestivas_sog_sng=$arreglo['digestivas_sog_sng'];
      	     $this->digestivas_formula=$arreglo['digestivas_formula'];
      	     $this->digestivas_parenteral=$arreglo['digestivas_parenteral'];
      	     $this->digestivas_observaciones=$arreglo['digestivas_observaciones'];
      	     $this->procedimientos_invasivos=$arreglo['procedimientos_invasivos'];
      	     $this->procedimientos_invasivos_cateter_venoso=$arreglo['procedimientos_invasivos_cateter_venoso'];
      	     $this->procedimientos_invasivos_cateter_arterial=$arreglo['procedimientos_invasivos_cateter_arterial'];
      	     $this->procedimientos_invasivos_otros=$arreglo['procedimientos_invasivos_otros'];
      	     $this->procedimientos_invasivos_tubo_toraxico=$arreglo['procedimientos_invasivos_tubo_toraxico'];
      	     $this->cardiovascular_soplo=$arreglo['cardiovascular_soplo'];
      	     $this->cardiovascular_ic_cardiologia=$arreglo['cardiovascular_ic_cardiologia'];
      	     $this->cardiovascular_dap=$arreglo['cardiovascular_dap'];
      	     $this->cardiovascular_dap_tratamiento=$arreglo['cardiovascular_dap_tratamiento'];
      	     $this->cardiovascular_hipotension=$arreglo['cardiovascular_hipotension'];
      	     $this->cardiovascular_insuficiencia_cardiaca=$arreglo['cardiovascular_insuficiencia_cardiaca'];
      	     $this->cardiovascular_paro=$arreglo['cardiovascular_paro'];
      	     $this->examenes_alta_laboratorio_fecha=$arreglo['examenes_alta_laboratorio_fecha'];
      	     $this->examenes_alta_laboratorio=$arreglo['examenes_alta_laboratorio'];
      	     $this->examenes_alta_fo=$arreglo['examenes_alta_fo'];
      	     $this->examenes_alta_oea=$arreglo['examenes_alta_oea'];
      	     $this->examenes_alta_sn=$arreglo['examenes_alta_sn'];
      	     $this->examenes_alta_otros=$arreglo['examenes_alta_otros'];
      	     $this->examenes_alta_peso_alta=$arreglo['examenes_alta_peso_alta'];
      	     $this->examenes_alta_talla_alta=$arreglo['examenes_alta_talla_alta'];
      	     $this->examenes_alta_pc_alta=$arreglo['examenes_alta_pc_alta'];
      	     $this->condiciones_alta_alimentacion=$arreglo['condiciones_alta_alimentacion'];
      	     $this->condiciones_alta_medicacion=$arreglo['condiciones_alta_medicacion'];
      	     $this->condiciones_alta_pendientes=$arreglo['condiciones_alta_pendientes'];
      	     $this->condiciones_alta_situaciones_riesgo=$arreglo['condiciones_alta_situaciones_riesgo'];
      	     $this->ampliacion_conceptos=$arreglo['ampliacion_conceptos'];
      	     $this->proximo_control=$arreglo['proximo_control'];
      	     $this->medico_tratante=$arreglo['medico_tratante'];
      	     $this->tipo=$arreglo['tipo'];
      	     $this->digestivas_sog_sng_dias=$arreglo['digestivas_sog_sng_dias'];
      	     $this->examenes_alta_fo_estado=$arreglo['examenes_alta_fo_estado'];
      	     $this->examenes_alta_peso_nacimiento_gramos=$arreglo['examenes_alta_peso_nacimiento_gramos'];
      	     $this->examenes_alta_peso_nacimiento_dias=$arreglo['examenes_alta_peso_nacimiento_dias'];
      	     $this->neurologicas_ecografia_cerebral_estado=$arreglo['neurologicas_ecografia_cerebral_estado'];
      	     $this->vivo=$arreglo['vivo'];
      	     $this->pesquisas_metabolicas=$arreglo['pesquisas_metabolicas'];
      	     $this->pesquisas_metabolicas_fechas=$arreglo['pesquisas_metabolicas_fechas'];
      	     $this->tipo_fondo_ojos=$arreglo['tipo_fondo_ojos'];
      	     $this->tratamiento_fondo_ojos=$arreglo['tratamiento_fondo_ojos'];
      	     $this->traslado_mayor_complejidad=$arreglo['traslado_mayor_complejidad'];
      	     $this->alimentacion_enteral_alta=$arreglo['alimentacion_enteral_alta'];
      	     $this->traslado_otra_institucion=$arreglo['traslado_otra_institucion'];
      	     $this->infeccion_asociada_cateter=$arreglo['infeccion_asociada_cateter'];
      	     $this->infeccion_asociada_cateter_germen=$arreglo['infeccion_asociada_cateter_germen'];
      	     $this->alteracion_hidroelectrolitica=$arreglo['alteracion_hidroelectrolitica'];
      	     $this->alteracion_hidroelectrolitica_tipo=$arreglo['alteracion_hidroelectrolitica_tipo'];
      	     $this->alteracion_hidroelectrolitica_tratamiento=$arreglo['alteracion_hidroelectrolitica_tratamiento'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO epicrisis_neonatologia(idepisodio,idprofesional,fecha_egreso,hora,fecha_nacimiento_corregida,patologia_respiratoria,fio2max,dias_o2,cpap,arm,apneas,tratamiento_respiratorio,respiratorio_observaciones,infeccion,infeccion_diagnostico,infeccion_cultivos,infeccion_atb,infeccion_atb_dias,infeccion_laboratorio,hiperbilirrubinemia,hiperbilirrubinemia_dias_lmt,hiperbilirrubinemia_valor_maximo_bili,hiperbilirrubinemia_exanguino,hiperbilirrubinemia_diagnostico,metabolicas,metabolicas_diagnostico,metabolicas_tratamiento,hematologicas,hematologicas_anemia,hematologicas_hora_alta,hematologicas_transfusiones,hematologicas_policitemia,patologia_quirurgica,patologia_quirurgica_diagnostico,patologia_quirurgica_tratamiento_quirurgico,neurologicas,neurologicas_diagnostico,neurologicas_hiv_grado,neurologicas_ecografia_cerebral,neurologicas_asfixia,neurologicas_convulsiones,neurologicas_tratamiento,neurologicas_ex_neurologico_alta,digestivas,digestivas_diagnostico,digestivas_enteral_inicio,digestivas_sog_sng,digestivas_formula,digestivas_parenteral,digestivas_observaciones,procedimientos_invasivos,procedimientos_invasivos_cateter_venoso,procedimientos_invasivos_cateter_arterial,procedimientos_invasivos_otros,procedimientos_invasivos_tubo_toraxico,cardiovascular_soplo,cardiovascular_ic_cardiologia,cardiovascular_dap,cardiovascular_dap_tratamiento,cardiovascular_hipotension,cardiovascular_insuficiencia_cardiaca,cardiovascular_paro,examenes_alta_laboratorio_fecha,examenes_alta_laboratorio,examenes_alta_fo,examenes_alta_oea,examenes_alta_sn,examenes_alta_otros,examenes_alta_peso_alta,examenes_alta_talla_alta,examenes_alta_pc_alta,condiciones_alta_alimentacion,condiciones_alta_medicacion,condiciones_alta_pendientes,condiciones_alta_situaciones_riesgo,ampliacion_conceptos,proximo_control,medico_tratante,tipo,digestivas_sog_sng_dias,examenes_alta_fo_estado,examenes_alta_peso_nacimiento_gramos,examenes_alta_peso_nacimiento_dias,neurologicas_ecografia_cerebral_estado,vivo,pesquisas_metabolicas,pesquisas_metabolicas_fechas,tipo_fondo_ojos,tratamiento_fondo_ojos,traslado_mayor_complejidad,alimentacion_enteral_alta,traslado_otra_institucion,infeccion_asociada_cateter,infeccion_asociada_cateter_germen,alteracion_hidroelectrolitica,alteracion_hidroelectrolitica_tipo,alteracion_hidroelectrolitica_tratamiento) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->fecha_egreso."','".$this->hora."','".$this->fecha_nacimiento_corregida."','".$this->patologia_respiratoria."','".$this->fio2max."','".$this->dias_o2."','".$this->cpap."','".$this->arm."','".$this->apneas."','".$this->tratamiento_respiratorio."','".$this->respiratorio_observaciones."','".$this->infeccion."','".$this->infeccion_diagnostico."','".$this->infeccion_cultivos."','".$this->infeccion_atb."','".$this->infeccion_atb_dias."','".$this->infeccion_laboratorio."','".$this->hiperbilirrubinemia."','".$this->hiperbilirrubinemia_dias_lmt."','".$this->hiperbilirrubinemia_valor_maximo_bili."','".$this->hiperbilirrubinemia_exanguino."','".$this->hiperbilirrubinemia_diagnostico."','".$this->metabolicas."','".$this->metabolicas_diagnostico."','".$this->metabolicas_tratamiento."','".$this->hematologicas."','".$this->hematologicas_anemia."','".$this->hematologicas_hora_alta."','".$this->hematologicas_transfusiones."','".$this->hematologicas_policitemia."','".$this->patologia_quirurgica."','".$this->patologia_quirurgica_diagnostico."','".$this->patologia_quirurgica_tratamiento_quirurgico."','".$this->neurologicas."','".$this->neurologicas_diagnostico."','".$this->neurologicas_hiv_grado."','".$this->neurologicas_ecografia_cerebral."','".$this->neurologicas_asfixia."','".$this->neurologicas_convulsiones."','".$this->neurologicas_tratamiento."','".$this->neurologicas_ex_neurologico_alta."','".$this->digestivas."','".$this->digestivas_diagnostico."','".$this->digestivas_enteral_inicio."','".$this->digestivas_sog_sng."','".$this->digestivas_formula."','".$this->digestivas_parenteral."','".$this->digestivas_observaciones."','".$this->procedimientos_invasivos."','".$this->procedimientos_invasivos_cateter_venoso."','".$this->procedimientos_invasivos_cateter_arterial."','".$this->procedimientos_invasivos_otros."','".$this->procedimientos_invasivos_tubo_toraxico."','".$this->cardiovascular_soplo."','".$this->cardiovascular_ic_cardiologia."','".$this->cardiovascular_dap."','".$this->cardiovascular_dap_tratamiento."','".$this->cardiovascular_hipotension."','".$this->cardiovascular_insuficiencia_cardiaca."','".$this->cardiovascular_paro."','".$this->examenes_alta_laboratorio_fecha."','".$this->examenes_alta_laboratorio."','".$this->examenes_alta_fo."','".$this->examenes_alta_oea."','".$this->examenes_alta_sn."','".$this->examenes_alta_otros."','".$this->examenes_alta_peso_alta."','".$this->examenes_alta_talla_alta."','".$this->examenes_alta_pc_alta."','".$this->condiciones_alta_alimentacion."','".$this->condiciones_alta_medicacion."','".$this->condiciones_alta_pendientes."','".$this->condiciones_alta_situaciones_riesgo."','".$this->ampliacion_conceptos."','".$this->proximo_control."','".$this->medico_tratante."','".$this->tipo."','".$this->digestivas_sog_sng_dias."','".$this->examenes_alta_fo_estado."','".$this->examenes_alta_peso_nacimiento_gramos."','".$this->examenes_alta_peso_nacimiento_dias."','".$this->neurologicas_ecografia_cerebral_estado."','".$this->vivo."','".$this->pesquisas_metabolicas."','".$this->pesquisas_metabolicas_fechas."','".$this->tipo_fondo_ojos."','".$this->tratamiento_fondo_ojos."','".$this->traslado_mayor_complejidad."','".$this->alimentacion_enteral_alta."','".$this->traslado_otra_institucion."','".$this->infeccion_asociada_cateter."','".$this->infeccion_asociada_cateter_germen."','".$this->alteracion_hidroelectrolitica."','".$this->alteracion_hidroelectrolitica_tipo."','".$this->alteracion_hidroelectrolitica_tratamiento."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE epicrisis_neonatologia SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',fecha_egreso='".$this->fecha_egreso."',hora='".$this->hora."',fecha_nacimiento_corregida='".$this->fecha_nacimiento_corregida."',patologia_respiratoria='".$this->patologia_respiratoria."',fio2max='".$this->fio2max."',dias_o2='".$this->dias_o2."',cpap='".$this->cpap."',arm='".$this->arm."',apneas='".$this->apneas."',tratamiento_respiratorio='".$this->tratamiento_respiratorio."',respiratorio_observaciones='".$this->respiratorio_observaciones."',infeccion='".$this->infeccion."',infeccion_diagnostico='".$this->infeccion_diagnostico."',infeccion_cultivos='".$this->infeccion_cultivos."',infeccion_atb='".$this->infeccion_atb."',infeccion_atb_dias='".$this->infeccion_atb_dias."',infeccion_laboratorio='".$this->infeccion_laboratorio."',hiperbilirrubinemia='".$this->hiperbilirrubinemia."',hiperbilirrubinemia_dias_lmt='".$this->hiperbilirrubinemia_dias_lmt."',hiperbilirrubinemia_valor_maximo_bili='".$this->hiperbilirrubinemia_valor_maximo_bili."',hiperbilirrubinemia_exanguino='".$this->hiperbilirrubinemia_exanguino."',hiperbilirrubinemia_diagnostico='".$this->hiperbilirrubinemia_diagnostico."',metabolicas='".$this->metabolicas."',metabolicas_diagnostico='".$this->metabolicas_diagnostico."',metabolicas_tratamiento='".$this->metabolicas_tratamiento."',hematologicas='".$this->hematologicas."',hematologicas_anemia='".$this->hematologicas_anemia."',hematologicas_hora_alta='".$this->hematologicas_hora_alta."',hematologicas_transfusiones='".$this->hematologicas_transfusiones."',hematologicas_policitemia='".$this->hematologicas_policitemia."',patologia_quirurgica='".$this->patologia_quirurgica."',patologia_quirurgica_diagnostico='".$this->patologia_quirurgica_diagnostico."',patologia_quirurgica_tratamiento_quirurgico='".$this->patologia_quirurgica_tratamiento_quirurgico."',neurologicas='".$this->neurologicas."',neurologicas_diagnostico='".$this->neurologicas_diagnostico."',neurologicas_hiv_grado='".$this->neurologicas_hiv_grado."',neurologicas_ecografia_cerebral='".$this->neurologicas_ecografia_cerebral."',neurologicas_asfixia='".$this->neurologicas_asfixia."',neurologicas_convulsiones='".$this->neurologicas_convulsiones."',neurologicas_tratamiento='".$this->neurologicas_tratamiento."',neurologicas_ex_neurologico_alta='".$this->neurologicas_ex_neurologico_alta."',digestivas='".$this->digestivas."',digestivas_diagnostico='".$this->digestivas_diagnostico."',digestivas_enteral_inicio='".$this->digestivas_enteral_inicio."',digestivas_sog_sng='".$this->digestivas_sog_sng."',digestivas_formula='".$this->digestivas_formula."',digestivas_parenteral='".$this->digestivas_parenteral."',digestivas_observaciones='".$this->digestivas_observaciones."',procedimientos_invasivos='".$this->procedimientos_invasivos."',procedimientos_invasivos_cateter_venoso='".$this->procedimientos_invasivos_cateter_venoso."',procedimientos_invasivos_cateter_arterial='".$this->procedimientos_invasivos_cateter_arterial."',procedimientos_invasivos_otros='".$this->procedimientos_invasivos_otros."',procedimientos_invasivos_tubo_toraxico='".$this->procedimientos_invasivos_tubo_toraxico."',cardiovascular_soplo='".$this->cardiovascular_soplo."',cardiovascular_ic_cardiologia='".$this->cardiovascular_ic_cardiologia."',cardiovascular_dap='".$this->cardiovascular_dap."',cardiovascular_dap_tratamiento='".$this->cardiovascular_dap_tratamiento."',cardiovascular_hipotension='".$this->cardiovascular_hipotension."',cardiovascular_insuficiencia_cardiaca='".$this->cardiovascular_insuficiencia_cardiaca."',cardiovascular_paro='".$this->cardiovascular_paro."',examenes_alta_laboratorio_fecha='".$this->examenes_alta_laboratorio_fecha."',examenes_alta_laboratorio='".$this->examenes_alta_laboratorio."',examenes_alta_fo='".$this->examenes_alta_fo."',examenes_alta_oea='".$this->examenes_alta_oea."',examenes_alta_sn='".$this->examenes_alta_sn."',examenes_alta_otros='".$this->examenes_alta_otros."',examenes_alta_peso_alta='".$this->examenes_alta_peso_alta."',examenes_alta_talla_alta='".$this->examenes_alta_talla_alta."',examenes_alta_pc_alta='".$this->examenes_alta_pc_alta."',condiciones_alta_alimentacion='".$this->condiciones_alta_alimentacion."',condiciones_alta_medicacion='".$this->condiciones_alta_medicacion."',condiciones_alta_pendientes='".$this->condiciones_alta_pendientes."',condiciones_alta_situaciones_riesgo='".$this->condiciones_alta_situaciones_riesgo."',ampliacion_conceptos='".$this->ampliacion_conceptos."',proximo_control='".$this->proximo_control."',medico_tratante='".$this->medico_tratante."',tipo='".$this->tipo."',digestivas_sog_sng_dias='".$this->digestivas_sog_sng_dias."',examenes_alta_fo_estado='".$this->examenes_alta_fo_estado."',examenes_alta_peso_nacimiento_gramos='".$this->examenes_alta_peso_nacimiento_gramos."',examenes_alta_peso_nacimiento_dias='".$this->examenes_alta_peso_nacimiento_dias."',neurologicas_ecografia_cerebral_estado='".$this->neurologicas_ecografia_cerebral_estado."',vivo='".$this->vivo."',pesquisas_metabolicas='".$this->pesquisas_metabolicas."',pesquisas_metabolicas_fechas='".$this->pesquisas_metabolicas_fechas."',tipo_fondo_ojos='".$this->tipo_fondo_ojos."',tratamiento_fondo_ojos='".$this->tratamiento_fondo_ojos."',traslado_mayor_complejidad='".$this->traslado_mayor_complejidad."',alimentacion_enteral_alta='".$this->alimentacion_enteral_alta."',traslado_otra_institucion='".$this->traslado_otra_institucion."',infeccion_asociada_cateter='".$this->infeccion_asociada_cateter."',infeccion_asociada_cateter_germen='".$this->infeccion_asociada_cateter_germen."',alteracion_hidroelectrolitica='".$this->alteracion_hidroelectrolitica."',alteracion_hidroelectrolitica_tipo='".$this->alteracion_hidroelectrolitica_tipo."',alteracion_hidroelectrolitica_tratamiento='".$this->alteracion_hidroelectrolitica_tratamiento."' WHERE id='".$this->id."'"))
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
          function fecha_egreso()
          {
               return $this->fecha_egreso;
          }
          function hora()
          {
               return $this->hora;
          }
          function fecha_nacimiento_corregida()
          {
               return $this->fecha_nacimiento_corregida;
          }
          function patologia_respiratoria()
          {
               return $this->patologia_respiratoria;
          }
          function fio2max()
          {
               return $this->fio2max;
          }
          function dias_o2()
          {
               return $this->dias_o2;
          }
          function cpap()
          {
               return $this->cpap;
          }
          function arm()
          {
               return $this->arm;
          }
          function apneas()
          {
               return $this->apneas;
          }
          function tratamiento_respiratorio()
          {
               return $this->tratamiento_respiratorio;
          }
          function respiratorio_observaciones()
          {
               return $this->respiratorio_observaciones;
          }
          function infeccion()
          {
               return $this->infeccion;
          }
          function infeccion_diagnostico()
          {
               return $this->infeccion_diagnostico;
          }
          function infeccion_cultivos()
          {
               return $this->infeccion_cultivos;
          }
          function infeccion_atb()
          {
               return $this->infeccion_atb;
          }
          function infeccion_atb_dias()
          {
               return $this->infeccion_atb_dias;
          }
          function infeccion_laboratorio()
          {
               return $this->infeccion_laboratorio;
          }
          function hiperbilirrubinemia()
          {
               return $this->hiperbilirrubinemia;
          }
          function hiperbilirrubinemia_dias_lmt()
          {
               return $this->hiperbilirrubinemia_dias_lmt;
          }
          function hiperbilirrubinemia_valor_maximo_bili()
          {
               return $this->hiperbilirrubinemia_valor_maximo_bili;
          }
          function hiperbilirrubinemia_exanguino()
          {
               return $this->hiperbilirrubinemia_exanguino;
          }
          function hiperbilirrubinemia_diagnostico()
          {
               return $this->hiperbilirrubinemia_diagnostico;
          }
          function metabolicas()
          {
               return $this->metabolicas;
          }
          function metabolicas_diagnostico()
          {
               return $this->metabolicas_diagnostico;
          }
          function metabolicas_tratamiento()
          {
               return $this->metabolicas_tratamiento;
          }
          function hematologicas()
          {
               return $this->hematologicas;
          }
          function hematologicas_anemia()
          {
               return $this->hematologicas_anemia;
          }
          function hematologicas_hora_alta()
          {
               return $this->hematologicas_hora_alta;
          }
          function hematologicas_transfusiones()
          {
               return $this->hematologicas_transfusiones;
          }
          function hematologicas_policitemia()
          {
               return $this->hematologicas_policitemia;
          }
          function patologia_quirurgica()
          {
               return $this->patologia_quirurgica;
          }
          function patologia_quirurgica_diagnostico()
          {
               return $this->patologia_quirurgica_diagnostico;
          }
          function patologia_quirurgica_tratamiento_quirurgico()
          {
               return $this->patologia_quirurgica_tratamiento_quirurgico;
          }
          function neurologicas()
          {
               return $this->neurologicas;
          }
          function neurologicas_diagnostico()
          {
               return $this->neurologicas_diagnostico;
          }
          function neurologicas_hiv_grado()
          {
               return $this->neurologicas_hiv_grado;
          }
          function neurologicas_ecografia_cerebral()
          {
               return $this->neurologicas_ecografia_cerebral;
          }
          function neurologicas_asfixia()
          {
               return $this->neurologicas_asfixia;
          }
          function neurologicas_convulsiones()
          {
               return $this->neurologicas_convulsiones;
          }
          function neurologicas_tratamiento()
          {
               return $this->neurologicas_tratamiento;
          }
          function neurologicas_ex_neurologico_alta()
          {
               return $this->neurologicas_ex_neurologico_alta;
          }
          function digestivas()
          {
               return $this->digestivas;
          }
          function digestivas_diagnostico()
          {
               return $this->digestivas_diagnostico;
          }
          function digestivas_enteral_inicio()
          {
               return $this->digestivas_enteral_inicio;
          }
          function digestivas_sog_sng()
          {
               return $this->digestivas_sog_sng;
          }
          function digestivas_formula()
          {
               return $this->digestivas_formula;
          }
          function digestivas_parenteral()
          {
               return $this->digestivas_parenteral;
          }
          function digestivas_observaciones()
          {
               return $this->digestivas_observaciones;
          }
          function procedimientos_invasivos()
          {
               return $this->procedimientos_invasivos;
          }
          function procedimientos_invasivos_cateter_venoso()
          {
               return $this->procedimientos_invasivos_cateter_venoso;
          }
          function procedimientos_invasivos_cateter_arterial()
          {
               return $this->procedimientos_invasivos_cateter_arterial;
          }
          function procedimientos_invasivos_otros()
          {
               return $this->procedimientos_invasivos_otros;
          }
          function procedimientos_invasivos_tubo_toraxico()
          {
               return $this->procedimientos_invasivos_tubo_toraxico;
          }
          function cardiovascular_soplo()
          {
               return $this->cardiovascular_soplo;
          }
          function cardiovascular_ic_cardiologia()
          {
               return $this->cardiovascular_ic_cardiologia;
          }
          function cardiovascular_dap()
          {
               return $this->cardiovascular_dap;
          }
          function cardiovascular_dap_tratamiento()
          {
               return $this->cardiovascular_dap_tratamiento;
          }
          function cardiovascular_hipotension()
          {
               return $this->cardiovascular_hipotension;
          }
          function cardiovascular_insuficiencia_cardiaca()
          {
               return $this->cardiovascular_insuficiencia_cardiaca;
          }
          function cardiovascular_paro()
          {
               return $this->cardiovascular_paro;
          }
          function examenes_alta_laboratorio_fecha()
          {
               return $this->examenes_alta_laboratorio_fecha;
          }
          function examenes_alta_laboratorio()
          {
               return $this->examenes_alta_laboratorio;
          }
          function examenes_alta_fo()
          {
               return $this->examenes_alta_fo;
          }
          function examenes_alta_oea()
          {
               return $this->examenes_alta_oea;
          }
          function examenes_alta_sn()
          {
               return $this->examenes_alta_sn;
          }
          function examenes_alta_otros()
          {
               return $this->examenes_alta_otros;
          }
          function examenes_alta_peso_alta()
          {
               return $this->examenes_alta_peso_alta;
          }
          function examenes_alta_talla_alta()
          {
               return $this->examenes_alta_talla_alta;
          }
          function examenes_alta_pc_alta()
          {
               return $this->examenes_alta_pc_alta;
          }
          function condiciones_alta_alimentacion()
          {
               return $this->condiciones_alta_alimentacion;
          }
          function condiciones_alta_medicacion()
          {
               return $this->condiciones_alta_medicacion;
          }
          function condiciones_alta_pendientes()
          {
               return $this->condiciones_alta_pendientes;
          }
          function condiciones_alta_situaciones_riesgo()
          {
               return $this->condiciones_alta_situaciones_riesgo;
          }
          function ampliacion_conceptos()
          {
               return $this->ampliacion_conceptos;
          }
          function proximo_control()
          {
               return $this->proximo_control;
          }
          function medico_tratante()
          {
               return $this->medico_tratante;
          }
          function tipo()
          {
               return $this->tipo;
          }
          function digestivas_sog_sng_dias()
          {
               return $this->digestivas_sog_sng_dias;
          }
          function examenes_alta_fo_estado()
          {
               return $this->examenes_alta_fo_estado;
          }
          function examenes_alta_peso_nacimiento_gramos()
          {
               return $this->examenes_alta_peso_nacimiento_gramos;
          }
          function examenes_alta_peso_nacimiento_dias()
          {
               return $this->examenes_alta_peso_nacimiento_dias;
          }
          function neurologicas_ecografia_cerebral_estado()
          {
               return $this->neurologicas_ecografia_cerebral_estado;
          }
          function vivo()
          {
               return $this->vivo;
          }
          function pesquisas_metabolicas()
          {
               return $this->pesquisas_metabolicas;
          }
          function pesquisas_metabolicas_fechas()
          {
               return $this->pesquisas_metabolicas_fechas;
          }
          function tipo_fondo_ojos()
          {
               return $this->tipo_fondo_ojos;
          }
          function tratamiento_fondo_ojos()
          {
               return $this->tratamiento_fondo_ojos;
          }
          function traslado_mayor_complejidad()
          {
               return $this->traslado_mayor_complejidad;
          }
          function alimentacion_enteral_alta()
          {
               return $this->alimentacion_enteral_alta;
          }
          function traslado_otra_institucion()
          {
               return $this->traslado_otra_institucion;
          }
          function infeccion_asociada_cateter()
          {
               return $this->infeccion_asociada_cateter;
          }
          function infeccion_asociada_cateter_germen()
          {
               return $this->infeccion_asociada_cateter_germen;
          }
          function alteracion_hidroelectrolitica()
          {
               return $this->alteracion_hidroelectrolitica;
          }
          function alteracion_hidroelectrolitica_tipo()
          {
               return $this->alteracion_hidroelectrolitica_tipo;
          }
          function alteracion_hidroelectrolitica_tratamiento()
          {
               return $this->alteracion_hidroelectrolitica_tratamiento;
          }
          function grado_3()
          {
              return $this->grado_3;	
          }
          function grado_4()
          {
		      return $this->grado_4;
          }
          function rop_1()
          {
          	  return $this->rop_1;
          }
      	  function rop_2()
          {
          	  return $this->rop_2;
          }	
          function rop_3()
          {
          	  return $this->rop_3;
          }
      	  function rop_4()
          {
          	  return $this->rop_4;
          }			  
		  function peso_alta_mayor_15_dias()
		  {
		  	  return $this->peso_alta_mayor_15_dias;
		  }
      	  function peso_alta_menor_15_dias()
		  {
		  	  return $this->peso_alta_menor_15_dias;
		  }
		  function cantidad_diagnostico()
		  {
		      return $this->cantidad_diagnostico;	
		  }
      	  function gesta_24()
		  {
		      return $this->gesta_24;	
		  }
      	  function gesta_25()
		  {
		      return $this->gesta_25;	
		  }
          function gesta_26()
		  {
		      return $this->gesta_26;	
		  }
          function gesta_27()
		  {
		      return $this->gesta_27;	
		  }	
      	  function gesta_28()
		  {
		      return $this->gesta_28;	
		  }	 
          function gesta_29()
		  {
		      return $this->gesta_29;	
		  }  
          function gesta_30()
		  {
		      return $this->gesta_30;	
		  }
          function gesta_31()
		  {
		      return $this->gesta_31;	
		  }
          function gesta_32()
		  {
		      return $this->gesta_32;	
		  }
          function gesta_33()
		  {
		      return $this->gesta_33;	
		  }
          function gesta_34()
		  {
		      return $this->gesta_34;	
		  }
          function gesta_35()
		  {
		      return $this->gesta_35;	
		  }
          function gesta_36()
		  {
		      return $this->gesta_36;	
		  }
          function gesta_37()
		  {
		      return $this->gesta_37;	
		  }
          function peso_500()
		  {
		      return $this->peso_500;	
		  }
      	  function peso_750()
		  {
		      return $this->peso_750;	
		  }
          function peso_1000()
		  {
		      return $this->peso_1000;	
		  }
          function peso_1250()
		  {
		      return $this->peso_1250;	
		  }
          function peso_1500()
		  {
		      return $this->peso_1500;	
		  }
          function peso_2000()
		  {
		      return $this->peso_2000;	
		  }
          function peso_2500()
		  {
		      return $this->peso_2500;	
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
          function fecha_egreso_asigna($campo)
          {
               $this->fecha_egreso=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function fecha_nacimiento_corregida_asigna($campo)
          {
               $this->fecha_nacimiento_corregida=$campo;
               
          }
          function patologia_respiratoria_asigna($campo)
          {
               $this->patologia_respiratoria=$campo;
               
          }
          function fio2max_asigna($campo)
          {
               $this->fio2max=$campo;
               
          }
          function dias_o2_asigna($campo)
          {
               $this->dias_o2=$campo;
               
          }
          function cpap_asigna($campo)
          {
               $this->cpap=$campo;
               
          }
          function arm_asigna($campo)
          {
               $this->arm=$campo;
               
          }
          function apneas_asigna($campo)
          {
               $this->apneas=$campo;
               
          }
          function tratamiento_respiratorio_asigna($campo)
          {
               $this->tratamiento_respiratorio=$campo;
               
          }
          function respiratorio_observaciones_asigna($campo)
          {
               $this->respiratorio_observaciones=$campo;
               
          }
          function infeccion_asigna($campo)
          {
               $this->infeccion=$campo;
               
          }
          function infeccion_diagnostico_asigna($campo)
          {
               $this->infeccion_diagnostico=$campo;
               
          }
          function infeccion_cultivos_asigna($campo)
          {
               $this->infeccion_cultivos=$campo;
               
          }
          function infeccion_atb_asigna($campo)
          {
               $this->infeccion_atb=$campo;
               
          }
          function infeccion_atb_dias_asigna($campo)
          {
               $this->infeccion_atb_dias=$campo;
               
          }
          function infeccion_laboratorio_asigna($campo)
          {
               $this->infeccion_laboratorio=$campo;
               
          }
          function hiperbilirrubinemia_asigna($campo)
          {
               $this->hiperbilirrubinemia=$campo;
               
          }
          function hiperbilirrubinemia_dias_lmt_asigna($campo)
          {
               $this->hiperbilirrubinemia_dias_lmt=$campo;
               
          }
          function hiperbilirrubinemia_valor_maximo_bili_asigna($campo)
          {
               $this->hiperbilirrubinemia_valor_maximo_bili=$campo;
               
          }
          function hiperbilirrubinemia_exanguino_asigna($campo)
          {
               $this->hiperbilirrubinemia_exanguino=$campo;
               
          }
          function hiperbilirrubinemia_diagnostico_asigna($campo)
          {
               $this->hiperbilirrubinemia_diagnostico=$campo;
               
          }
          function metabolicas_asigna($campo)
          {
               $this->metabolicas=$campo;
               
          }
          function metabolicas_diagnostico_asigna($campo)
          {
               $this->metabolicas_diagnostico=$campo;
               
          }
          function metabolicas_tratamiento_asigna($campo)
          {
               $this->metabolicas_tratamiento=$campo;
               
          }
          function hematologicas_asigna($campo)
          {
               $this->hematologicas=$campo;
               
          }
          function hematologicas_anemia_asigna($campo)
          {
               $this->hematologicas_anemia=$campo;
               
          }
          function hematologicas_hora_alta_asigna($campo)
          {
               $this->hematologicas_hora_alta=$campo;
               
          }
          function hematologicas_transfusiones_asigna($campo)
          {
               $this->hematologicas_transfusiones=$campo;
               
          }
          function hematologicas_policitemia_asigna($campo)
          {
               $this->hematologicas_policitemia=$campo;
               
          }
          function patologia_quirurgica_asigna($campo)
          {
               $this->patologia_quirurgica=$campo;
               
          }
          function patologia_quirurgica_diagnostico_asigna($campo)
          {
               $this->patologia_quirurgica_diagnostico=$campo;
               
          }
          function patologia_quirurgica_tratamiento_quirurgico_asigna($campo)
          {
               $this->patologia_quirurgica_tratamiento_quirurgico=$campo;
               
          }
          function neurologicas_asigna($campo)
          {
               $this->neurologicas=$campo;
               
          }
          function neurologicas_diagnostico_asigna($campo)
          {
               $this->neurologicas_diagnostico=$campo;
               
          }
          function neurologicas_hiv_grado_asigna($campo)
          {
               $this->neurologicas_hiv_grado=$campo;
               
          }
          function neurologicas_ecografia_cerebral_asigna($campo)
          {
               $this->neurologicas_ecografia_cerebral=$campo;
               
          }
          function neurologicas_asfixia_asigna($campo)
          {
               $this->neurologicas_asfixia=$campo;
               
          }
          function neurologicas_convulsiones_asigna($campo)
          {
               $this->neurologicas_convulsiones=$campo;
               
          }
          function neurologicas_tratamiento_asigna($campo)
          {
               $this->neurologicas_tratamiento=$campo;
               
          }
          function neurologicas_ex_neurologico_alta_asigna($campo)
          {
               $this->neurologicas_ex_neurologico_alta=$campo;
               
          }
          function digestivas_asigna($campo)
          {
               $this->digestivas=$campo;
               
          }
          function digestivas_diagnostico_asigna($campo)
          {
               $this->digestivas_diagnostico=$campo;
               
          }
          function digestivas_enteral_inicio_asigna($campo)
          {
               $this->digestivas_enteral_inicio=$campo;
               
          }
          function digestivas_sog_sng_asigna($campo)
          {
               $this->digestivas_sog_sng=$campo;
               
          }
          function digestivas_formula_asigna($campo)
          {
               $this->digestivas_formula=$campo;
               
          }
          function digestivas_parenteral_asigna($campo)
          {
               $this->digestivas_parenteral=$campo;
               
          }
          function digestivas_observaciones_asigna($campo)
          {
               $this->digestivas_observaciones=$campo;
               
          }
          function procedimientos_invasivos_asigna($campo)
          {
               $this->procedimientos_invasivos=$campo;
               
          }
          function procedimientos_invasivos_cateter_venoso_asigna($campo)
          {
               $this->procedimientos_invasivos_cateter_venoso=$campo;
               
          }
          function procedimientos_invasivos_cateter_arterial_asigna($campo)
          {
               $this->procedimientos_invasivos_cateter_arterial=$campo;
               
          }
          function procedimientos_invasivos_otros_asigna($campo)
          {
               $this->procedimientos_invasivos_otros=$campo;
               
          }
          function procedimientos_invasivos_tubo_toraxico_asigna($campo)
          {
               $this->procedimientos_invasivos_tubo_toraxico=$campo;
               
          }
          function cardiovascular_soplo_asigna($campo)
          {
               $this->cardiovascular_soplo=$campo;
               
          }
          function cardiovascular_ic_cardiologia_asigna($campo)
          {
               $this->cardiovascular_ic_cardiologia=$campo;
               
          }
          function cardiovascular_dap_asigna($campo)
          {
               $this->cardiovascular_dap=$campo;
               
          }
          function cardiovascular_dap_tratamiento_asigna($campo)
          {
               $this->cardiovascular_dap_tratamiento=$campo;
               
          }
          function cardiovascular_hipotension_asigna($campo)
          {
               $this->cardiovascular_hipotension=$campo;
               
          }
          function cardiovascular_insuficiencia_cardiaca_asigna($campo)
          {
               $this->cardiovascular_insuficiencia_cardiaca=$campo;
               
          }
          function cardiovascular_paro_asigna($campo)
          {
               $this->cardiovascular_paro=$campo;
               
          }
          function examenes_alta_laboratorio_fecha_asigna($campo)
          {
               $this->examenes_alta_laboratorio_fecha=$campo;
               
          }
          function examenes_alta_laboratorio_asigna($campo)
          {
               $this->examenes_alta_laboratorio=$campo;
               
          }
          function examenes_alta_fo_asigna($campo)
          {
               $this->examenes_alta_fo=$campo;
               
          }
          function examenes_alta_oea_asigna($campo)
          {
               $this->examenes_alta_oea=$campo;
               
          }
          function examenes_alta_sn_asigna($campo)
          {
               $this->examenes_alta_sn=$campo;
               
          }
          function examenes_alta_otros_asigna($campo)
          {
               $this->examenes_alta_otros=$campo;
               
          }
          function examenes_alta_peso_alta_asigna($campo)
          {
               $this->examenes_alta_peso_alta=$campo;
               
          }
          function examenes_alta_talla_alta_asigna($campo)
          {
               $this->examenes_alta_talla_alta=$campo;
               
          }
          function examenes_alta_pc_alta_asigna($campo)
          {
               $this->examenes_alta_pc_alta=$campo;
               
          }
          function condiciones_alta_alimentacion_asigna($campo)
          {
               $this->condiciones_alta_alimentacion=$campo;
               
          }
          function condiciones_alta_medicacion_asigna($campo)
          {
               $this->condiciones_alta_medicacion=$campo;
               
          }
          function condiciones_alta_pendientes_asigna($campo)
          {
               $this->condiciones_alta_pendientes=$campo;
               
          }
          function condiciones_alta_situaciones_riesgo_asigna($campo)
          {
               $this->condiciones_alta_situaciones_riesgo=$campo;
               
          }
          function ampliacion_conceptos_asigna($campo)
          {
               $this->ampliacion_conceptos=$campo;
               
          }
          function proximo_control_asigna($campo)
          {
               $this->proximo_control=$campo;
               
          }
          function medico_tratante_asigna($campo)
          {
               $this->medico_tratante=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          function digestivas_sog_sng_dias_asigna($campo)
          {
               $this->digestivas_sog_sng_dias=$campo;
               
          }
          function examenes_alta_fo_estado_asigna($campo)
          {
               $this->examenes_alta_fo_estado=$campo;
               
          }
          function examenes_alta_peso_nacimiento_gramos_asigna($campo)
          {
               $this->examenes_alta_peso_nacimiento_gramos=$campo;
               
          }
          function examenes_alta_peso_nacimiento_dias_asigna($campo)
          {
               $this->examenes_alta_peso_nacimiento_dias=$campo;
               
          }
          function neurologicas_ecografia_cerebral_estado_asigna($campo)
          {
               $this->neurologicas_ecografia_cerebral_estado=$campo;
               
          }
          function vivo_asigna($campo)
          {
               $this->vivo=$campo;
               
          }
          function pesquisas_metabolicas_asigna($campo)
          {
               $this->pesquisas_metabolicas=$campo;
               
          }
          function pesquisas_metabolicas_fechas_asigna($campo)
          {
               $this->pesquisas_metabolicas_fechas=$campo;
               
          }
          function tipo_fondo_ojos_asigna($campo)
          {
               $this->tipo_fondo_ojos=$campo;
               
          }
          function tratamiento_fondo_ojos_asigna($campo)
          {
               $this->tratamiento_fondo_ojos=$campo;
               
          }
          function traslado_mayor_complejidad_asigna($campo)
          {
               $this->traslado_mayor_complejidad=$campo;
               
          }
          function alimentacion_enteral_alta_asigna($campo)
          {
               $this->alimentacion_enteral_alta=$campo;
               
          }
          function traslado_otra_institucion_asigna($campo)
          {
               $this->traslado_otra_institucion=$campo;
               
          }
          function infeccion_asociada_cateter_asigna($campo)
          {
               $this->infeccion_asociada_cateter=$campo;
               
          }
          function infeccion_asociada_cateter_germen_asigna($campo)
          {
               $this->infeccion_asociada_cateter_germen=$campo;
               
          }
          function alteracion_hidroelectrolitica_asigna($campo)
          {
               $this->alteracion_hidroelectrolitica=$campo;
               
          }
          function alteracion_hidroelectrolitica_tipo_asigna($campo)
          {
               $this->alteracion_hidroelectrolitica_tipo=$campo;
               
          }
          function alteracion_hidroelectrolitica_tratamiento_asigna($campo)
          {
               $this->alteracion_hidroelectrolitica_tratamiento=$campo;
               
          }
      	  function hemorragia_fondo_ojos_recuperacion_peso($fdesde,$fhasta,$tipo)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse();
			  //si tipo == 1 entonces solo esta buscando en epicrisis_neonatologia
			  //si tipo == 2 entonce busca los que son crib y epicrisis_neonatologia
			  if ($tipo == 1)
			     $from = " epicrisis_neonatologia ";
			  if ($tipo == 2)
			     $from = " scores_crib LEFT JOIN epicrisis_neonatologia using (idepisodio) ";
			  $bd->select("SELECT sum(case when ((neurologicas_hiv_grado = 3)) THEN 1 ELSE 0 END ) grado_3,
       							  sum(case when ((neurologicas_hiv_grado = 4)) THEN 1 ELSE 0 END ) grado_4,
       							  sum(case when ((examenes_alta_fo = 'ROP I')) THEN 1 ELSE 0 END ) rop_1,
       							  sum(case when ((examenes_alta_fo = 'ROP II')) THEN 1 ELSE 0 END ) rop_2,
       							  sum(case when ((examenes_alta_fo = 'ROP III')) THEN 1 ELSE 0 END ) rop_3,
       							  sum(case when ((examenes_alta_fo = 'ROP IV')) THEN 1 ELSE 0 END ) rop_4,
       							  sum(case when ((examenes_alta_peso_nacimiento_dias >= 15)) THEN 1 ELSE 0 END ) peso_alta_mayor_15_dias,
       							  sum(case when ((examenes_alta_peso_nacimiento_dias < 15)) THEN 1 ELSE 0 END ) peso_alta_menor_15_dias,
       							  sum(case when ((vivo = 0)) THEN 1 ELSE 0 END ) vivo_cantidad
       				      FROM  $from 
			              WHERE fecha_egreso>='".fechaBase($fdesde)."' AND fecha_egreso<='".fechaBase($fhasta)."' 
			              ");
			  $arreglo = $bd->registro();
			  $this->grado_3 = $arreglo['grado_3'];
			  $this->grado_4 = $arreglo['grado_4'];
			  $this->rop_1 = $arreglo['rop_1'];
			  $this->rop_2 = $arreglo['rop_2'];
			  $this->rop_3 = $arreglo['rop_3'];
			  $this->rop_4 = $arreglo['rop_4'];
			  $this->peso_alta_mayor_15_dias = $arreglo['peso_alta_mayor_15_dias'];
			  $this->peso_alta_menor_15_dias = $arreglo['peso_alta_menor_15_dias'];
			  $this->vivo = $arreglo['vivo_cantidad'];
			  $bd->cerrar();			 			  
		  }
          function mortalidad_edad_gestacional_peso_nacimiento($fdesde,$fhasta)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse();			  
			  $bd->select("SELECT YEAR(episodios.fecha_egreso) as ano,sum(case when ((edad_gestacional_examen_fisico>=24 AND edad_gestacional_examen_fisico<25 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_24,
       							  sum(case when ((edad_gestacional_examen_fisico>=25 AND edad_gestacional_examen_fisico<26 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_25,
       							  sum(case when ((edad_gestacional_examen_fisico>=26 AND edad_gestacional_examen_fisico<27 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_26,
       							  sum(case when ((edad_gestacional_examen_fisico>=27 AND edad_gestacional_examen_fisico<28 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_27,
       							  sum(case when ((edad_gestacional_examen_fisico>=28 AND edad_gestacional_examen_fisico<29 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_28,
       							  sum(case when ((edad_gestacional_examen_fisico>=29 AND edad_gestacional_examen_fisico<30 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_29,
       							  sum(case when ((edad_gestacional_examen_fisico>=30 AND edad_gestacional_examen_fisico<31 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_30,
       							  sum(case when ((edad_gestacional_examen_fisico>=31 AND edad_gestacional_examen_fisico<32 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_31,
       							  sum(case when ((edad_gestacional_examen_fisico>=32 AND edad_gestacional_examen_fisico<33 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_32,
       							  sum(case when ((edad_gestacional_examen_fisico>=33 AND edad_gestacional_examen_fisico<34 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_33,
       							  sum(case when ((edad_gestacional_examen_fisico>=34 AND edad_gestacional_examen_fisico<35 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_34,
       							  sum(case when ((edad_gestacional_examen_fisico>=35 AND edad_gestacional_examen_fisico<36 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_35,
       							  sum(case when ((edad_gestacional_examen_fisico>=36 AND edad_gestacional_examen_fisico<37 AND vivo=0)) THEN 1 ELSE 0 END ) gesta_36,
       							  sum(case when ((edad_gestacional_examen_fisico>=37  AND vivo=0)) THEN 1 ELSE 0 END ) gesta_37,
       							  sum(case when ((peso_nacimiento>=500 AND peso_nacimiento<750 AND vivo=0)) THEN 1 ELSE 0 END ) peso_500,
       							  sum(case when ((peso_nacimiento>=750 AND peso_nacimiento<1000 AND vivo=0)) THEN 1 ELSE 0 END ) peso_750,
       							  sum(case when ((peso_nacimiento>=1000 AND peso_nacimiento<1250 AND vivo=0)) THEN 1 ELSE 0 END ) peso_1000,
       							  sum(case when ((peso_nacimiento>=1250 AND peso_nacimiento<1500 AND vivo=0)) THEN 1 ELSE 0 END ) peso_1250,
       							  sum(case when ((peso_nacimiento>=1500 AND peso_nacimiento<2000 AND vivo=0)) THEN 1 ELSE 0 END ) peso_1500,
       							  sum(case when ((peso_nacimiento>=2000 AND peso_nacimiento<2500 AND vivo=0)) THEN 1 ELSE 0 END ) peso_2000,
       							  sum(case when ((peso_nacimiento>=2500  AND vivo=0)) THEN 1 ELSE 0 END ) peso_2500
       				      FROM   episodios RIGHT JOIN epicrisis_neonatologia using(idepisodio) RIGHT JOIN pacientes_antecedentes_perinatales using(idpaciente) 
			              WHERE episodios.fecha_egreso>='".fechaBase($fdesde)."' AND episodios.fecha_egreso<='".fechaBase($fhasta)."' 
			              GROUP BY YEAR(episodios.fecha_egreso)");
			  //$arreglo = $bd->registro();
			  /*$this->gesta_24 = $arreglo['gesta_24'];
			  $this->gesta_25 = $arreglo['gesta_25'];
			  $this->gesta_26 = $arreglo['gesta_26'];
			  $this->gesta_27 = $arreglo['gesta_27'];
			  $this->gesta_28 = $arreglo['gesta_28'];
			  $this->gesta_29 = $arreglo['gesta_29'];
			  $this->gesta_30 = $arreglo['gesta_30'];
			  $this->gesta_31 = $arreglo['gesta_31'];
			  $this->gesta_32 = $arreglo['gesta_32'];
			  $this->gesta_33 = $arreglo['gesta_33'];
			  $this->gesta_34 = $arreglo['gesta_34'];
			  $this->gesta_35 = $arreglo['gesta_35'];
			  $this->gesta_36 = $arreglo['gesta_36'];
			  $this->gesta_37 = $arreglo['gesta_37'];
			  $this->peso_500 = $arreglo['peso_500'];
			  $this->peso_750 = $arreglo['peso_750'];
			  $this->peso_1000 = $arreglo['peso_1000'];
			  $this->peso_1250 = $arreglo['peso_1250'];
			  $this->peso_1500 = $arreglo['peso_1500'];
			  $this->peso_2000 = $arreglo['peso_2000'];
			  $this->peso_2500 = $arreglo['peso_2500'];*/
			  $pro = new clase_listar();		
			  for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro();
	    			if ($fila['ano'] != 0 && $fila['ano'] != '')
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;
			  $bd->cerrar();			 			  
		  }
      	  function diagnosticos_egreso($fdesde,$fhasta,$iddiagnostico,$tipo)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse();
			  //si tipo == 1 entonces solo esta buscando en epicrisis_neonatologia
			  //si tipo == 2 entonce busca los que son crib y epicrisis_neonatologia
			  if ($tipo == 1)
			     $from = " epicrisis_neonatologia RIGHT JOIN epicrisis_neonatologia_diagnosticos_egreso ON 
	                       (id_epicrisis_neonatologia=epicrisis_neonatologia.id) ";
			  if ($tipo == 2)
			     $from = " scores_crib LEFT JOIN epicrisis_neonatologia using (idepisodio) LEFT JOIN 
			               epicrisis_neonatologia RIGHT JOIN epicrisis_neonatologia_diagnosticos_egreso ON 
	                       (id_epicrisis_neonatologia=epicrisis_neonatologia.id) ";
			  $bd->select("SELECT count(idepisodio) as cant 
	                      FROM $from  
	                      WHERE (fecha_egreso >='".fechaBase($fdesde)."' AND fecha_egreso <='".fechaBase($fhasta)."') 
	                      AND codigo=$iddiagnostico 
			              ");
			  $arreglo = $bd->registro();
			  $this->cantidad_diagnostico = $arreglo['cant'];
			 
			  $bd->cerrar();			 			  
		  }
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM epicrisis_neonatologia WHERE idepisodio=$idepisodio");				
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