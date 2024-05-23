<?
      class clase_hcl_resumen_examenes       
      {
	  var $id = '';
          var $idpaciente = '';
          var $fecha = '';
          var $empresa = '';
          var $examen_preocupacional = '';
          var $examen_periodico = '';
          var $examen_postocupacional = '';
          var $examen_otros = '';
          var $datos_examen_fisico = '';
          var $datos_examen_rx = '';
          var $datos_examen_cardiologico = '';
          var $datos_examen_laboratorio = '';
          var $datos_examen_audiometria = '';
          var $datos_examen_neurologico = '';
          var $datos_examen_psicologico = '';
          var $datos_examen_otros = '';
          var $tarea_propuesta = '';
          var $apto_medico = '';
          var $recomendacion = '';
          var $observaciones = '';
          var $idprofesional = '';
          var $idprofesional_laboral = '';
          var $ausencia_prolongada = '';
          var $locacion_fisica = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idprofesional_laboral='';
      	     
      
         function clase_hcl_resumen_examenes($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_resumen_examenes WHERE id=$id");
      	     self::asignar($bd->registro());      	           	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->empresa=$arreglo['empresa'];
      	     $this->examen_preocupacional=$arreglo['examen_preocupacional'];
      	     $this->examen_periodico=$arreglo['examen_periodico'];
      	     $this->examen_postocupacional=$arreglo['examen_postocupacional'];
      	     $this->examen_otros=$arreglo['examen_otros'];
      	     $this->datos_examen_fisico=$arreglo['datos_examen_fisico'];
      	     $this->datos_examen_rx=$arreglo['datos_examen_rx'];
      	     $this->datos_examen_cardiologico=$arreglo['datos_examen_cardiologico'];
      	     $this->datos_examen_laboratorio=$arreglo['datos_examen_laboratorio'];
      	     $this->datos_examen_audiometria=$arreglo['datos_examen_audiometria'];
      	     $this->datos_examen_neurologico=$arreglo['datos_examen_neurologico'];
      	     $this->datos_examen_psicologico=$arreglo['datos_examen_psicologico'];
      	     $this->datos_examen_otros=$arreglo['datos_examen_otros'];
      	     $this->tarea_propuesta=$arreglo['tarea_propuesta'];
      	     $this->apto_medico=$arreglo['apto_medico'];
      	     $this->recomendacion=$arreglo['recomendacion'];
      	     $this->observaciones=$arreglo['observaciones'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idprofesional_laboral=$arreglo['idprofesional_laboral'];
             $this->ausencia_prolongada = $arreglo['ausencia_prolongada'];
             $this->locacion_fisica = $arreglo['locacion_fisica'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_resumen_examenes(idpaciente,fecha,empresa,examen_preocupacional,examen_periodico,examen_postocupacional,examen_otros,datos_examen_fisico,datos_examen_rx,datos_examen_cardiologico,datos_examen_laboratorio,datos_examen_audiometria,datos_examen_neurologico,datos_examen_psicologico,datos_examen_otros,tarea_propuesta,apto_medico,recomendacion,observaciones,idprofesional,idprofesional_laboral,ausencia_prolongada,locacion_fisica) VALUES('".$this->idpaciente."','".$this->fecha."','".$this->empresa."','".$this->examen_preocupacional."','".$this->examen_periodico."','".$this->examen_postocupacional."','".$this->examen_otros."','".$this->datos_examen_fisico."','".$this->datos_examen_rx."','".$this->datos_examen_cardiologico."','".$this->datos_examen_laboratorio."','".$this->datos_examen_audiometria."','".$this->datos_examen_neurologico."','".$this->datos_examen_psicologico."','".$this->datos_examen_otros."','".$this->tarea_propuesta."','".$this->apto_medico."','".$this->recomendacion."','".$this->observaciones."','".$this->idprofesional."','".$this->idprofesional_laboral."','".$this->ausencia_prolongada."','".$this->locacion_fisica."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_resumen_examenes SET locacion_fisica='".$this->locacion_fisica."',fecha='".$this->fecha."',empresa='".$this->empresa."',examen_preocupacional='".$this->examen_preocupacional."',examen_periodico='".$this->examen_periodico."',examen_postocupacional='".$this->examen_postocupacional."',examen_otros='".$this->examen_otros."',datos_examen_fisico='".$this->datos_examen_fisico."',datos_examen_rx='".$this->datos_examen_rx."',datos_examen_cardiologico='".$this->datos_examen_cardiologico."',datos_examen_laboratorio='".$this->datos_examen_laboratorio."',datos_examen_audiometria='".$this->datos_examen_audiometria."',datos_examen_neurologico='".$this->datos_examen_neurologico."',datos_examen_psicologico='".$this->datos_examen_psicologico."',datos_examen_otros='".$this->datos_examen_otros."',tarea_propuesta='".$this->tarea_propuesta."',apto_medico='".$this->apto_medico."',recomendacion='".$this->recomendacion."',observaciones='".$this->observaciones."',idprofesional='".$this->idprofesional."',idprofesional_laboral='".$this->idprofesional_laboral."',ausencia_prolongada='".$this->ausencia_prolongada."' WHERE id='".$this->id."'"))
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
          function empresa()
          {
               return $this->empresa;
          }
          function examen_preocupacional()
          {
               return $this->examen_preocupacional;
          }
          function examen_periodico()
          {
               return $this->examen_periodico;
          }
          function examen_postocupacional()
          {
               return $this->examen_postocupacional;
          }
          function examen_otros()
          {
               return $this->examen_otros;
          }
          function datos_examen_fisico()
          {
               return $this->datos_examen_fisico;
          }
          function datos_examen_rx()
          {
               return $this->datos_examen_rx;
          }
          function datos_examen_cardiologico()
          {
               return $this->datos_examen_cardiologico;
          }
          function datos_examen_laboratorio()
          {
               return $this->datos_examen_laboratorio;
          }
          function datos_examen_audiometria()
          {
               return $this->datos_examen_audiometria;
          }
          function datos_examen_neurologico()
          {
               return $this->datos_examen_neurologico;
          }
          function datos_examen_psicologico()
          {
               return $this->datos_examen_psicologico;
          }
          function datos_examen_otros()
          {
               return $this->datos_examen_otros;
          }
          function tarea_propuesta()
          {
               return $this->tarea_propuesta;
          }
          function apto_medico()
          {
               return $this->apto_medico;
          }
          function recomendacion()
          {
               return $this->recomendacion;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idprofesional_laboral()
          {
               return $this->idprofesional_laboral;
          }
          function ausencia_prolongada()
          {
              return $this->ausencia_prolongada;
          }
          function locacion_fisica()
          {
              return $this->locacion_fisica;
          }       
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idprofesional_laboral()
             {
                 return $this->arreglo_foraneo_idprofesional_laboral;
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
          function empresa_asigna($campo)
          {
               $this->empresa=$campo;
               
          }
          function examen_preocupacional_asigna($campo)
          {
               $this->examen_preocupacional=$campo;
               
          }
          function examen_periodico_asigna($campo)
          {
               $this->examen_periodico=$campo;
               
          }
          function examen_postocupacional_asigna($campo)
          {
               $this->examen_postocupacional=$campo;
               
          }
          function examen_otros_asigna($campo)
          {
               $this->examen_otros=$campo;
               
          }
          function datos_examen_fisico_asigna($campo)
          {
               $this->datos_examen_fisico=$campo;
               
          }
          function datos_examen_rx_asigna($campo)
          {
               $this->datos_examen_rx=$campo;
               
          }
          function datos_examen_cardiologico_asigna($campo)
          {
               $this->datos_examen_cardiologico=$campo;
               
          }
          function datos_examen_laboratorio_asigna($campo)
          {
               $this->datos_examen_laboratorio=$campo;
               
          }
          function datos_examen_audiometria_asigna($campo)
          {
               $this->datos_examen_audiometria=$campo;
               
          }
          function datos_examen_neurologico_asigna($campo)
          {
               $this->datos_examen_neurologico=$campo;
               
          }
          function datos_examen_psicologico_asigna($campo)
          {
               $this->datos_examen_psicologico=$campo;
               
          }
          function datos_examen_otros_asigna($campo)
          {
               $this->datos_examen_otros=$campo;
               
          }
          function tarea_propuesta_asigna($campo)
          {
               $this->tarea_propuesta=$campo;
               
          }
          function apto_medico_asigna($campo)
          {
               $this->apto_medico=$campo;
               
          }
          function recomendacion_asigna($campo)
          {
               $this->recomendacion=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idprofesional_laboral_asigna($campo)
          {
               $this->idprofesional_laboral=$campo;
               
          }
          function ausencia_prolongada_asigna($campo)
          {
              $this->ausencia_prolongada = $campo;
          }
          function locacion_fisica_asigna($campo)
          {
              $this->locacion_fisica = $campo;
          }        
          
	      function foranea_idpaciente($idpaciente,$order_fecha)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_resumen_examenes WHERE idpaciente=$idpaciente ORDER BY fecha $order_fecha");				
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
				$bd->select("SELECT * FROM hcl_resumen_examenes WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idprofesional_laboral($idprofesional_laboral)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hcl_resumen_examenes WHERE idprofesional_laboral=$idprofesional_laboral");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional_laboral = $pro;		                              		
			}
		function foranea_idpaciente_idosocial($idpaciente,$idosocial)
        {
	    $bd = new baseDatos();
				$bd->Conectarse();	
                    $base = new baseDatos();
				$base->Conectarse();
                    //primero buscamos los pedidos de examen fisico para luego buscar si en la misma fecha se hizo el estudio
                    $bd->select("SELECT fecha FROM pedidos_estudio LEFT JOIN pedidos_estudio_detalle USING(idpedido_estudio) 
                                WHERE idpaciente=$idpaciente AND idosocial=$idosocial AND (idnomenclador=1923 OR idnomenclador=2139)");
                    $pro = new clase_listar();
                    for($i=0;$i<=$bd->numero_filas();$i++) 
	    	    {
                        $arreglo = $bd->registro();
			$base->select("SELECT * FROM hcl_resumen_examenes WHERE idpaciente=$idpaciente AND fecha='".$arreglo['fecha']."'");												    		
	    		$fila = $base->registro(); 
                        
                        if ($fila['id'] != 0 && $fila['id'] != '')
	    		    $pro->introducirElemento($fila); 
	    	    }
	    	    $this->arreglo_foraneo_idpaciente = $pro;		                              		
	}		
        function buscar_estudio_fecha($idpaciente,$fecha)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_resumen_examenes WHERE idpaciente=$idpaciente AND fecha='".  fechaBase($fecha)."'");
      	     self::asignar($bd->registro());      	           	     
      	 }
}
?>