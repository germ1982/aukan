<?
      class clase_hcl_examen_medico       
      {
	  var $id = '';
          var $idpaciente = '';
          var $fecha = '';
          var $idprofesional = '';
          var $empresa = '';
          var $empresa_ingreso = '';
          var $empresa_egreso = '';
          var $empresa_periodico = '';
          var $tarea_propuesta = '';
          var $dj_enfermedad_ojos = '';
          var $dj_enfermedad_oidos = '';
          var $dj_hinchazon_tobillos = '';
          var $dj_ictericia_hepatitis = '';
          var $dj_calculos_vesicula = '';
          var $dj_enfermedades_piel = '';
          var $dj_nerviosismo_depresion = '';
          var $dj_parasitos_intestinales = '';
          var $dj_asma = '';
          var $dj_alergias = '';
          var $dj_tos = '';
          var $dj_perdida_peso = '';
          var $dj_dolor_pecho = '';
          var $dj_dificultades_respiratorias = '';
          var $dj_enfermedades_cardiacas = '';
          var $dj_ulceras_digestivas = '';
          var $dj_enfermedades_venereas = '';
          var $dj_enfermedades_rinones = '';
          var $dj_fiebres_prolongadas = '';
          var $dj_tumores_cancer = '';
          var $dj_hernias = '';
          var $dj_artritis = '';
          var $dj_pleuresia = '';
          var $dj_operaciones = '';
          var $dj_neumonia = '';
          var $dj_tuberculosis = '';
          var $dj_reumatismo = '';
          var $dj_accidentes = '';
          var $dj_hipertension_arterial = '';
          var $dj_dolor_abdominal = '';
          var $dj_epilepsia = '';
          var $dj_diabetes = '';
          var $dj_trastornos_menstruales = '';
          var $aclaraciones = '';
          var $fumador = '';
          var $fumador_cantidad = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idprofesional='';
      	     
      
         function clase_hcl_examen_medico($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_examen_medico WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->empresa=$arreglo['empresa'];
      	     $this->empresa_ingreso=$arreglo['empresa_ingreso'];
      	     $this->empresa_egreso=$arreglo['empresa_egreso'];
      	     $this->empresa_periodico=$arreglo['empresa_periodico'];
      	     $this->tarea_propuesta=$arreglo['tarea_propuesta'];
      	     $this->dj_enfermedad_ojos=$arreglo['dj_enfermedad_ojos'];
      	     $this->dj_enfermedad_oidos=$arreglo['dj_enfermedad_oidos'];
      	     $this->dj_hinchazon_tobillos=$arreglo['dj_hinchazon_tobillos'];
      	     $this->dj_ictericia_hepatitis=$arreglo['dj_ictericia_hepatitis'];
      	     $this->dj_calculos_vesicula=$arreglo['dj_calculos_vesicula'];
      	     $this->dj_enfermedades_piel=$arreglo['dj_enfermedades_piel'];
      	     $this->dj_nerviosismo_depresion=$arreglo['dj_nerviosismo_depresion'];
      	     $this->dj_parasitos_intestinales=$arreglo['dj_parasitos_intestinales'];
      	     $this->dj_asma=$arreglo['dj_asma'];
      	     $this->dj_alergias=$arreglo['dj_alergias'];
      	     $this->dj_tos=$arreglo['dj_tos'];
      	     $this->dj_perdida_peso=$arreglo['dj_perdida_peso'];
      	     $this->dj_dolor_pecho=$arreglo['dj_dolor_pecho'];
      	     $this->dj_dificultades_respiratorias=$arreglo['dj_dificultades_respiratorias'];
      	     $this->dj_enfermedades_cardiacas=$arreglo['dj_enfermedades_cardiacas'];
      	     $this->dj_ulceras_digestivas=$arreglo['dj_ulceras_digestivas'];
      	     $this->dj_enfermedades_venereas=$arreglo['dj_enfermedades_venereas'];
      	     $this->dj_enfermedades_rinones=$arreglo['dj_enfermedades_rinones'];
      	     $this->dj_fiebres_prolongadas=$arreglo['dj_fiebres_prolongadas'];
      	     $this->dj_tumores_cancer=$arreglo['dj_tumores_cancer'];
      	     $this->dj_hernias=$arreglo['dj_hernias'];
      	     $this->dj_artritis=$arreglo['dj_artritis'];
      	     $this->dj_pleuresia=$arreglo['dj_pleuresia'];
      	     $this->dj_operaciones=$arreglo['dj_operaciones'];
      	     $this->dj_neumonia=$arreglo['dj_neumonia'];
      	     $this->dj_tuberculosis=$arreglo['dj_tuberculosis'];
      	     $this->dj_reumatismo=$arreglo['dj_reumatismo'];
      	     $this->dj_accidentes=$arreglo['dj_accidentes'];
      	     $this->dj_hipertension_arterial=$arreglo['dj_hipertension_arterial'];
      	     $this->dj_dolor_abdominal=$arreglo['dj_dolor_abdominal'];
      	     $this->dj_epilepsia=$arreglo['dj_epilepsia'];
      	     $this->dj_diabetes=$arreglo['dj_diabetes'];
      	     $this->dj_trastornos_menstruales=$arreglo['dj_trastornos_menstruales'];
      	     $this->aclaraciones=$arreglo['aclaraciones'];
             $this->fumador = $arreglo['fumador'];
             $this->fumador_cantidad = $arreglo['fumador_cantidad'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_examen_medico(idpaciente,fecha,idprofesional,empresa,empresa_ingreso,empresa_egreso,empresa_periodico,tarea_propuesta,dj_enfermedad_ojos,dj_enfermedad_oidos,dj_hinchazon_tobillos,dj_ictericia_hepatitis,dj_calculos_vesicula,dj_enfermedades_piel,dj_nerviosismo_depresion,dj_parasitos_intestinales,dj_asma,dj_alergias,dj_tos,dj_perdida_peso,dj_dolor_pecho,dj_dificultades_respiratorias,dj_enfermedades_cardiacas,dj_ulceras_digestivas,dj_enfermedades_venereas,dj_enfermedades_rinones,dj_fiebres_prolongadas,dj_tumores_cancer,dj_hernias,dj_artritis,dj_pleuresia,dj_operaciones,dj_neumonia,dj_tuberculosis,dj_reumatismo,dj_accidentes,dj_hipertension_arterial,dj_dolor_abdominal,dj_epilepsia,dj_diabetes,dj_trastornos_menstruales,aclaraciones,fumador,fumador_cantidad) VALUES('".$this->idpaciente."','".$this->fecha."','".$this->idprofesional."','".$this->empresa."','".$this->empresa_ingreso."','".$this->empresa_egreso."','".$this->empresa_periodico."','".$this->tarea_propuesta."','".$this->dj_enfermedad_ojos."','".$this->dj_enfermedad_oidos."','".$this->dj_hinchazon_tobillos."','".$this->dj_ictericia_hepatitis."','".$this->dj_calculos_vesicula."','".$this->dj_enfermedades_piel."','".$this->dj_nerviosismo_depresion."','".$this->dj_parasitos_intestinales."','".$this->dj_asma."','".$this->dj_alergias."','".$this->dj_tos."','".$this->dj_perdida_peso."','".$this->dj_dolor_pecho."','".$this->dj_dificultades_respiratorias."','".$this->dj_enfermedades_cardiacas."','".$this->dj_ulceras_digestivas."','".$this->dj_enfermedades_venereas."','".$this->dj_enfermedades_rinones."','".$this->dj_fiebres_prolongadas."','".$this->dj_tumores_cancer."','".$this->dj_hernias."','".$this->dj_artritis."','".$this->dj_pleuresia."','".$this->dj_operaciones."','".$this->dj_neumonia."','".$this->dj_tuberculosis."','".$this->dj_reumatismo."','".$this->dj_accidentes."','".$this->dj_hipertension_arterial."','".$this->dj_dolor_abdominal."','".$this->dj_epilepsia."','".$this->dj_diabetes."','".$this->dj_trastornos_menstruales."','".$this->aclaraciones."','".$this->fumador."','".$this->fumador_cantidad."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_examen_medico SET idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',idprofesional='".$this->idprofesional."',empresa='".$this->empresa."',empresa_ingreso='".$this->empresa_ingreso."',empresa_egreso='".$this->empresa_egreso."',empresa_periodico='".$this->empresa_periodico."',tarea_propuesta='".$this->tarea_propuesta."',dj_enfermedad_ojos='".$this->dj_enfermedad_ojos."',dj_enfermedad_oidos='".$this->dj_enfermedad_oidos."',dj_hinchazon_tobillos='".$this->dj_hinchazon_tobillos."',dj_ictericia_hepatitis='".$this->dj_ictericia_hepatitis."',dj_calculos_vesicula='".$this->dj_calculos_vesicula."',dj_enfermedades_piel='".$this->dj_enfermedades_piel."',dj_nerviosismo_depresion='".$this->dj_nerviosismo_depresion."',dj_parasitos_intestinales='".$this->dj_parasitos_intestinales."',dj_asma='".$this->dj_asma."',dj_alergias='".$this->dj_alergias."',dj_tos='".$this->dj_tos."',dj_perdida_peso='".$this->dj_perdida_peso."',dj_dolor_pecho='".$this->dj_dolor_pecho."',dj_dificultades_respiratorias='".$this->dj_dificultades_respiratorias."',dj_enfermedades_cardiacas='".$this->dj_enfermedades_cardiacas."',dj_ulceras_digestivas='".$this->dj_ulceras_digestivas."',dj_enfermedades_venereas='".$this->dj_enfermedades_venereas."',dj_enfermedades_rinones='".$this->dj_enfermedades_rinones."',dj_fiebres_prolongadas='".$this->dj_fiebres_prolongadas."',dj_tumores_cancer='".$this->dj_tumores_cancer."',dj_hernias='".$this->dj_hernias."',dj_artritis='".$this->dj_artritis."',dj_pleuresia='".$this->dj_pleuresia."',dj_operaciones='".$this->dj_operaciones."',dj_neumonia='".$this->dj_neumonia."',dj_tuberculosis='".$this->dj_tuberculosis."',dj_reumatismo='".$this->dj_reumatismo."',dj_accidentes='".$this->dj_accidentes."',dj_hipertension_arterial='".$this->dj_hipertension_arterial."',dj_dolor_abdominal='".$this->dj_dolor_abdominal."',dj_epilepsia='".$this->dj_epilepsia."',dj_diabetes='".$this->dj_diabetes."',dj_trastornos_menstruales='".$this->dj_trastornos_menstruales."',aclaraciones='".$this->aclaraciones."',fumador='".$this->fumador."',fumador_cantidad='".$this->fumador_cantidad."' WHERE id='".$this->id."'"))
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
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function empresa()
          {
               return $this->empresa;
          }
          function empresa_ingreso()
          {
               return $this->empresa_ingreso;
          }
          function empresa_egreso()
          {
               return $this->empresa_egreso;
          }
          function empresa_periodico()
          {
               return $this->empresa_periodico;
          }
          function tarea_propuesta()
          {
               return $this->tarea_propuesta;
          }
          function dj_enfermedad_ojos()
          {
               return $this->dj_enfermedad_ojos;
          }
          function dj_enfermedad_oidos()
          {
               return $this->dj_enfermedad_oidos;
          }
          function dj_hinchazon_tobillos()
          {
               return $this->dj_hinchazon_tobillos;
          }
          function dj_ictericia_hepatitis()
          {
               return $this->dj_ictericia_hepatitis;
          }
          function dj_calculos_vesicula()
          {
               return $this->dj_calculos_vesicula;
          }
          function dj_enfermedades_piel()
          {
               return $this->dj_enfermedades_piel;
          }
          function dj_nerviosismo_depresion()
          {
               return $this->dj_nerviosismo_depresion;
          }
          function dj_parasitos_intestinales()
          {
               return $this->dj_parasitos_intestinales;
          }
          function dj_asma()
          {
               return $this->dj_asma;
          }
          function dj_alergias()
          {
               return $this->dj_alergias;
          }
          function dj_tos()
          {
               return $this->dj_tos;
          }
          function dj_perdida_peso()
          {
               return $this->dj_perdida_peso;
          }
          function dj_dolor_pecho()
          {
               return $this->dj_dolor_pecho;
          }
          function dj_dificultades_respiratorias()
          {
               return $this->dj_dificultades_respiratorias;
          }
          function dj_enfermedades_cardiacas()
          {
               return $this->dj_enfermedades_cardiacas;
          }
          function dj_ulceras_digestivas()
          {
               return $this->dj_ulceras_digestivas;
          }
          function dj_enfermedades_venereas()
          {
               return $this->dj_enfermedades_venereas;
          }
          function dj_enfermedades_rinones()
          {
               return $this->dj_enfermedades_rinones;
          }
          function dj_fiebres_prolongadas()
          {
               return $this->dj_fiebres_prolongadas;
          }
          function dj_tumores_cancer()
          {
               return $this->dj_tumores_cancer;
          }
          function dj_hernias()
          {
               return $this->dj_hernias;
          }
          function dj_artritis()
          {
               return $this->dj_artritis;
          }
          function dj_pleuresia()
          {
               return $this->dj_pleuresia;
          }
          function dj_operaciones()
          {
               return $this->dj_operaciones;
          }
          function dj_neumonia()
          {
               return $this->dj_neumonia;
          }
          function dj_tuberculosis()
          {
               return $this->dj_tuberculosis;
          }
          function dj_reumatismo()
          {
               return $this->dj_reumatismo;
          }
          function dj_accidentes()
          {
               return $this->dj_accidentes;
          }
          function dj_hipertension_arterial()
          {
               return $this->dj_hipertension_arterial;
          }
          function dj_dolor_abdominal()
          {
               return $this->dj_dolor_abdominal;
          }
          function dj_epilepsia()
          {
               return $this->dj_epilepsia;
          }
          function dj_diabetes()
          {
               return $this->dj_diabetes;
          }
          function dj_trastornos_menstruales()
          {
               return $this->dj_trastornos_menstruales;
          }
          function aclaraciones()
          {
               return $this->aclaraciones;
          }
          function fumador()
          {
               return $this->fumador;
          }
          function fumador_cantidad()
          {
               return $this->fumador_cantidad;
          }
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function empresa_asigna($campo)
          {
               $this->empresa=$campo;
               
          }
          function empresa_ingreso_asigna($campo)
          {
               $this->empresa_ingreso=$campo;
               
          }
          function empresa_egreso_asigna($campo)
          {
               $this->empresa_egreso=$campo;
               
          }
          function empresa_periodico_asigna($campo)
          {
               $this->empresa_periodico=$campo;
               
          }
          function tarea_propuesta_asigna($campo)
          {
               $this->tarea_propuesta=$campo;
               
          }
          function dj_enfermedad_ojos_asigna($campo)
          {
               $this->dj_enfermedad_ojos=$campo;
               
          }
          function dj_enfermedad_oidos_asigna($campo)
          {
               $this->dj_enfermedad_oidos=$campo;
               
          }
          function dj_hinchazon_tobillos_asigna($campo)
          {
               $this->dj_hinchazon_tobillos=$campo;
               
          }
          function dj_ictericia_hepatitis_asigna($campo)
          {
               $this->dj_ictericia_hepatitis=$campo;
               
          }
          function dj_calculos_vesicula_asigna($campo)
          {
               $this->dj_calculos_vesicula=$campo;
               
          }
          function dj_enfermedades_piel_asigna($campo)
          {
               $this->dj_enfermedades_piel=$campo;
               
          }
          function dj_nerviosismo_depresion_asigna($campo)
          {
               $this->dj_nerviosismo_depresion=$campo;
               
          }
          function dj_parasitos_intestinales_asigna($campo)
          {
               $this->dj_parasitos_intestinales=$campo;
               
          }
          function dj_asma_asigna($campo)
          {
               $this->dj_asma=$campo;
               
          }
          function dj_alergias_asigna($campo)
          {
               $this->dj_alergias=$campo;
               
          }
          function dj_tos_asigna($campo)
          {
               $this->dj_tos=$campo;
               
          }
          function dj_perdida_peso_asigna($campo)
          {
               $this->dj_perdida_peso=$campo;
               
          }
          function dj_dolor_pecho_asigna($campo)
          {
               $this->dj_dolor_pecho=$campo;
               
          }
          function dj_dificultades_respiratorias_asigna($campo)
          {
               $this->dj_dificultades_respiratorias=$campo;
               
          }
          function dj_enfermedades_cardiacas_asigna($campo)
          {
               $this->dj_enfermedades_cardiacas=$campo;
               
          }
          function dj_ulceras_digestivas_asigna($campo)
          {
               $this->dj_ulceras_digestivas=$campo;
               
          }
          function dj_enfermedades_venereas_asigna($campo)
          {
               $this->dj_enfermedades_venereas=$campo;
               
          }
          function dj_enfermedades_rinones_asigna($campo)
          {
               $this->dj_enfermedades_rinones=$campo;
               
          }
          function dj_fiebres_prolongadas_asigna($campo)
          {
               $this->dj_fiebres_prolongadas=$campo;
               
          }
          function dj_tumores_cancer_asigna($campo)
          {
               $this->dj_tumores_cancer=$campo;
               
          }
          function dj_hernias_asigna($campo)
          {
               $this->dj_hernias=$campo;
               
          }
          function dj_artritis_asigna($campo)
          {
               $this->dj_artritis=$campo;
               
          }
          function dj_pleuresia_asigna($campo)
          {
               $this->dj_pleuresia=$campo;
               
          }
          function dj_operaciones_asigna($campo)
          {
               $this->dj_operaciones=$campo;
               
          }
          function dj_neumonia_asigna($campo)
          {
               $this->dj_neumonia=$campo;
               
          }
          function dj_tuberculosis_asigna($campo)
          {
               $this->dj_tuberculosis=$campo;
               
          }
          function dj_reumatismo_asigna($campo)
          {
               $this->dj_reumatismo=$campo;
               
          }
          function dj_accidentes_asigna($campo)
          {
               $this->dj_accidentes=$campo;
               
          }
          function dj_hipertension_arterial_asigna($campo)
          {
               $this->dj_hipertension_arterial=$campo;
               
          }
          function dj_dolor_abdominal_asigna($campo)
          {
               $this->dj_dolor_abdominal=$campo;
               
          }
          function dj_epilepsia_asigna($campo)
          {
               $this->dj_epilepsia=$campo;
               
          }
          function dj_diabetes_asigna($campo)
          {
               $this->dj_diabetes=$campo;
               
          }
          function dj_trastornos_menstruales_asigna($campo)
          {
               $this->dj_trastornos_menstruales=$campo;
               
          }
          function aclaraciones_asigna($campo)
          {
               $this->aclaraciones=$campo;
               
          }
          function fumador_asigna($campo)
          {
               $this->fumador=$campo;
          }
          function fumador_cantidad_asigna($campo)
          {
               $this->fumador_cantidad=$campo;
          }
          
          
	      function foranea_idpaciente($idpaciente,$orden)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_examen_medico WHERE idpaciente=$idpaciente ORDER BY fecha $orden");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_examen_medico WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
		function foranea_idpaciente_idosocial($idpaciente,$idosocial)
		{
				$bd = new baseDatos();
				$bd->Conectarse();	
                    $base = new baseDatos();
				$base->Conectarse();
                    //primero buscamos los pedidos de examen fisico para luego buscar si en la misma fecha se hizo el estudio
                    $bd->select("SELECT fecha FROM pedidos_estudio LEFT JOIN pedidos_estudio_detalle USING(idpedido_estudio) 
                                WHERE idpaciente=$idpaciente AND idosocial=$idosocial AND (idnomenclador=1923 OR idnomenclador=2139 OR idnomenclador=2194)");
                    $pro = new clase_listar();
                    for($i=0;$i<=$bd->numero_filas();$i++) 
	    	    {
                        $arreglo = $bd->registro();
			$base->select("SELECT * FROM hcl_examen_medico WHERE idpaciente=$idpaciente AND fecha='".$arreglo['fecha']."'");												    		
	    		$fila = $base->registro(); 
                        
                        if ($fila['id'] != 0 && $fila['id'] != '')
	    		    $pro->introducirElemento($fila); 
	    	    }
	    	    $this->arreglo_foraneo_idpaciente = $pro;		                              		
		}	
      
}
?>
