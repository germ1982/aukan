<?
      class clase_hc_consulta_preoperatoria       
      {
	  var $id = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $fecha = '';
          var $idosocial = '';
          var $motivo_consulta = '';
          var $examen_fisico = '';
          var $examenes_complementarios = '';
          var $diagnostico = '';
          var $propuesta_terapeutica = '';
          var $preq_labo_normal = '';
          var $preq_cardio_normal = '';
          var $preq_antitetanica = '';
          var $preq_rx_torax_normal = '';
          var $preq_observaciones = '';
          var $conse_info_firmado = '';
          var $conse_info_entregado = '';
          var $pedido_materiales = '';
          var $lugar_fecha_cirugia = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idosocial='';
      	     
      
         function clase_hc_consulta_preoperatoria($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hc_consulta_preoperatoria WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->motivo_consulta=$arreglo['motivo_consulta'];
      	     $this->examen_fisico=$arreglo['examen_fisico'];
      	     $this->examenes_complementarios=$arreglo['examenes_complementarios'];
      	     $this->diagnostico=$arreglo['diagnostico'];
      	     $this->propuesta_terapeutica=$arreglo['propuesta_terapeutica'];
      	     $this->preq_labo_normal=$arreglo['preq_labo_normal'];
      	     $this->preq_cardio_normal=$arreglo['preq_cardio_normal'];
      	     $this->preq_antitetanica=$arreglo['preq_antitetanica'];
      	     $this->preq_rx_torax_normal=$arreglo['preq_rx_torax_normal'];
      	     $this->preq_observaciones=$arreglo['preq_observaciones'];
      	     $this->conse_info_firmado=$arreglo['conse_info_firmado'];
      	     $this->conse_info_entregado=$arreglo['conse_info_entregado'];
      	     $this->pedido_materiales=$arreglo['pedido_materiales'];
      	     $this->lugar_fecha_cirugia=$arreglo['lugar_fecha_cirugia'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hc_consulta_preoperatoria(idprofesional,idpaciente,fecha,idosocial,motivo_consulta,examen_fisico,examenes_complementarios,diagnostico,propuesta_terapeutica,preq_labo_normal,preq_cardio_normal,preq_antitetanica,preq_rx_torax_normal,preq_observaciones,conse_info_firmado,conse_info_entregado,pedido_materiales,lugar_fecha_cirugia) VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->idosocial."','".$this->motivo_consulta."','".$this->examen_fisico."','".$this->examenes_complementarios."','".$this->diagnostico."','".$this->propuesta_terapeutica."','".$this->preq_labo_normal."','".$this->preq_cardio_normal."','".$this->preq_antitetanica."','".$this->preq_rx_torax_normal."','".$this->preq_observaciones."','".$this->conse_info_firmado."','".$this->conse_info_entregado."','".$this->pedido_materiales."','".$this->lugar_fecha_cirugia."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return "INSERT INTO hc_consulta_preoperatoria(idprofesional,idpaciente,fecha,idosocial,motivo_consulta,examen_fisico,examenes_complementarios,diagnostico,propuesta_terapeutica,preq_labo_normal,preq_cardio_normal,preq_antitetanica,preq_rx_torax_normal,preq_observaciones,conse_info_firmado,conse_info_entregado,pedido_materiales,lugar_fecha_cirugia) VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->idosocial."','".$this->motivo_consulta."','".$this->examen_fisico."','".$this->examenes_complementarios."','".$this->diagnostico."','".$this->propuesta_terapeutica."','".$this->preq_labo_normal."','".$this->preq_cardio_normal."','".$this->preq_antitetanica."','".$this->preq_rx_torax_normal."','".$this->preq_observaciones."','".$this->conse_info_firmado."','".$this->conse_info_entregado."','".$this->pedido_materiales."','".$this->lugar_fecha_cirugia."')";
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hc_consulta_preoperatoria SET idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',idosocial='".$this->idosocial."',motivo_consulta='".$this->motivo_consulta."',examen_fisico='".$this->examen_fisico."',examenes_complementarios='".$this->examenes_complementarios."',diagnostico='".$this->diagnostico."',propuesta_terapeutica='".$this->propuesta_terapeutica."',preq_labo_normal='".$this->preq_labo_normal."',preq_cardio_normal='".$this->preq_cardio_normal."',preq_antitetanica='".$this->preq_antitetanica."',preq_rx_torax_normal='".$this->preq_rx_torax_normal."',preq_observaciones='".$this->preq_observaciones."',conse_info_firmado='".$this->conse_info_firmado."',conse_info_entregado='".$this->conse_info_entregado."',pedido_materiales='".$this->pedido_materiales."',lugar_fecha_cirugia='".$this->lugar_fecha_cirugia."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return "UPDATE hc_consulta_preoperatoria SET idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',idosocial='".$this->idosocial."',motivo_consulta='".$this->motivo_consulta."',examen_fisico='".$this->examen_fisico."',examenes_complementarios='".$this->examenes_complementarios."',diagnostico='".$this->diagnostico."',propuesta_terapeutica='".$this->propuesta_terapeutica."',preq_labo_normal='".$this->preq_labo_normal."',preq_cardio_normal='".$this->preq_cardio_normal."',preq_antitetanica='".$this->preq_antitetanica."',preq_rx_torax_normal='".$this->preq_rx_torax_normal."',preq_observaciones='".$this->preq_observaciones."',conse_info_firmado='".$this->conse_info_firmado."',conse_info_entregado='".$this->conse_info_entregado."',pedido_materiales='".$this->pedido_materiales."',lugar_fecha_cirugia='".$this->lugar_fecha_cirugia."' WHERE id='".$this->id."'";
      	  }
      }
      		
      
           
          function id()
          {
               return $this->id;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function motivo_consulta()
          {
               return $this->motivo_consulta;
          }
          function examen_fisico()
          {
               return $this->examen_fisico;
          }
          function examenes_complementarios()
          {
               return $this->examenes_complementarios;
          }
          function diagnostico()
          {
               return $this->diagnostico;
          }
          function propuesta_terapeutica()
          {
               return $this->propuesta_terapeutica;
          }
          function preq_labo_normal()
          {
               return $this->preq_labo_normal;
          }
          function preq_cardio_normal()
          {
               return $this->preq_cardio_normal;
          }
          function preq_antitetanica()
          {
               return $this->preq_antitetanica;
          }
          function preq_rx_torax_normal()
          {
               return $this->preq_rx_torax_normal;
          }
          function preq_observaciones()
          {
               return $this->preq_observaciones;
          }
          function conse_info_firmado()
          {
               return $this->conse_info_firmado;
          }
          function conse_info_entregado()
          {
               return $this->conse_info_entregado;
          }
          function pedido_materiales()
          {
               return $this->pedido_materiales;
          }
          function lugar_fecha_cirugia()
          {
               return $this->lugar_fecha_cirugia;
          }
          
          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      	     function arreglo_foraneo_idosocial()
             {
                 return $this->arreglo_foraneo_idosocial;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function motivo_consulta_asigna($campo)
          {
               $this->motivo_consulta=$campo;
               
          }
          function examen_fisico_asigna($campo)
          {
               $this->examen_fisico=$campo;
               
          }
          function examenes_complementarios_asigna($campo)
          {
               $this->examenes_complementarios=$campo;
               
          }
          function diagnostico_asigna($campo)
          {
               $this->diagnostico=$campo;
               
          }
          function propuesta_terapeutica_asigna($campo)
          {
               $this->propuesta_terapeutica=$campo;
               
          }
          function preq_labo_normal_asigna($campo)
          {
               $this->preq_labo_normal=$campo;
               
          }
          function preq_cardio_normal_asigna($campo)
          {
               $this->preq_cardio_normal=$campo;
               
          }
          function preq_antitetanica_asigna($campo)
          {
               $this->preq_antitetanica=$campo;
               
          }
          function preq_rx_torax_normal_asigna($campo)
          {
               $this->preq_rx_torax_normal=$campo;
               
          }
          function preq_observaciones_asigna($campo)
          {
               $this->preq_observaciones=$campo;
               
          }
          function conse_info_firmado_asigna($campo)
          {
               $this->conse_info_firmado=$campo;
               
          }
          function conse_info_entregado_asigna($campo)
          {
               $this->conse_info_entregado=$campo;
               
          }
          function pedido_materiales_asigna($campo)
          {
               $this->pedido_materiales=$campo;
               
          }
          function lugar_fecha_cirugia_asigna($campo)
          {
               $this->lugar_fecha_cirugia=$campo;
               
          }
          
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_consulta_preoperatoria WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_consulta_preoperatoria WHERE idpaciente=$idpaciente ORDER BY fecha DESC");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
	      function foranea_idosocial($idosocial)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_consulta_preoperatoria WHERE idosocial=$idosocial");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idosocial = $pro;		                              		
			}
			
      
}
?>