<?
      class clase_lab_solicitud_analisis       
      {
	  var $id = '';
          var $fecha = '';
          var $cli_paciente_id = '';
          var $cli_profesional_solicitante_id = '';
          var $resumen_clinico = '';
          var $observaciones = '';
          var $baja_fecha = '';
          var $idosocial = '';
          var $cantidad_filas = 0;
          var $estado = 0;
          var $idart_empresa = '';
          
      
      var $arreglo_foraneo_cli_paciente_id='';
      	     var $arreglo_foraneo_cli_profesional_solicitante_id='';
      	     
      
         function clase_lab_solicitud_analisis($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM lab_solicitud_analisis WHERE id=$id");
      	     $arreglo=$bd->registro();      	     
      	     self::asignar($arreglo);
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->cli_paciente_id=$arreglo['cli_paciente_id'];
      	     $this->cli_profesional_solicitante_id=$arreglo['cli_profesional_solicitante_id'];
      	     $this->resumen_clinico=$arreglo['resumen_clinico'];
      	     $this->observaciones=$arreglo['observaciones'];
      	     $this->baja_fecha=$arreglo['baja_fecha'];
             $this->idosocial=$arreglo['idosocial'];
             $this->estado = $arreglo['estado'];
             $this->idart_empresa = $arreglo['idart_empresa'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO lab_solicitud_analisis(fecha,cli_paciente_id,cli_profesional_solicitante_id,resumen_clinico,observaciones,baja_fecha,idosocial,idart_empresa) VALUES('".$this->fecha."','".$this->cli_paciente_id."','".$this->cli_profesional_solicitante_id."','".$this->resumen_clinico."','".$this->observaciones."','".$this->baja_fecha."','".$this->idosocial."','".$this->idart_empresa."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE lab_solicitud_analisis SET fecha='".$this->fecha."',cli_profesional_solicitante_id='".$this->cli_profesional_solicitante_id."',resumen_clinico='".$this->resumen_clinico."',observaciones='".$this->observaciones."',idosocial='".$this->idosocial."',idart_empresa='".$this->idart_empresa."' WHERE id='".$this->id."'"))
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
          function fecha()
          {
               return $this->fecha;
          }
          function cli_paciente_id()
          {
               return $this->cli_paciente_id;
          }
          function cli_profesional_solicitante_id()
          {
               return $this->cli_profesional_solicitante_id;
          }
          function resumen_clinico()
          {
               return $this->resumen_clinico;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function baja_fecha()
          {
               return $this->baja_fecha;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function estado()
          {
               return $this->estado;
          }
          function idart_empresa()
          {
               return $this->idart_empresa;
          }
          
      	     function arreglo_foraneo_cli_paciente_id()
             {
                 return $this->arreglo_foraneo_cli_paciente_id;
             }
             
      	     function arreglo_foraneo_cli_profesional_solicitante_id()
             {
                 return $this->arreglo_foraneo_cli_profesional_solicitante_id;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function cli_paciente_id_asigna($campo)
          {
               $this->cli_paciente_id=$campo;
               
          }
          function cli_profesional_solicitante_id_asigna($campo)
          {
               $this->cli_profesional_solicitante_id=$campo;
               
          }
          function resumen_clinico_asigna($campo)
          {
               $this->resumen_clinico=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function baja_fecha_asigna($campo)
          {
               $this->baja_fecha=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function idart_empresa_asigna($campo)
          {
               $this->idart_empresa=$campo;
          }
          
	      function foranea_cli_paciente_id($cli_paciente_id)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM lab_solicitud_analisis WHERE cli_paciente_id=$cli_paciente_id AND baja_fecha IS NULL ORDER BY fecha DESC");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_cli_paciente_id = $pro;		                              		
			}
			
	      function foranea_cli_profesional_solicitante_id($cli_profesional_solicitante_id)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM lab_solicitud_analisis WHERE cli_profesional_solicitante_id=$cli_profesional_solicitante_id AND baja_fecha IS NULL");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_cli_profesional_solicitante_id = $pro;		                              		
			}
		  function foranea_cli_paciente_idosocial($cli_paciente_id,$idosocial)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM lab_solicitud_analisis WHERE 
                                            idosocial=$idosocial AND cli_paciente_id=$cli_paciente_id AND baja_fecha IS NULL ORDER BY fecha DESC");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
                                if ($fila['id'] != '' && $fila['id'] != 0)
	    			    $pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_cli_paciente_id = $pro;		                              		
			}	
                   function foranea_cli_paciente_id_rango_fecha($fdesde,$fhasta,$cli_paciente_id,$idprofesional,$estado)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();	
                                if ($cli_paciente_id != 0 && $cli_paciente_id != '')
                                    $where .= " AND cli_paciente_id =$cli_paciente_id ";
                                if ($idprofesional != 0 && $idprofesional != '')
                                    $where .= " AND cli_profesional_solicitante_id =$idprofesional ";
                                if ($estado != 4)
                                     $where .= " AND estado =$estado ";
				$bd->select("SELECT id, DATE(fecha) as fecha,idosocial,cli_paciente_id as idpaciente,
                                            cli_profesional_solicitante_id as idprofesional,TIME(fecha) as hora
                                            FROM lab_solicitud_analisis WHERE 
                                            DATE(fecha)>='".fechaBase($fdesde)."' AND DATE(fecha)<='".fechaBase($fhasta)."' $where
                                            ORDER BY DATE(fecha) DESC,TIME(fecha) ASC");				
				$pro = new clase_listar();			
			$this->cantidad_filas = $bd->numero_filas();					
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro();
                                if ($fila['id'] != 0 && $fila['id'] != '')
	    			    $pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_cli_paciente_id = $pro;
                        
		    }
                    function cantidad_filas()
                    {
                        return $this->cantidad_filas;
                    }
                    function cambiar_estado_laboratorio($idpedido,$estado)
                    {
                        $bd = new baseDatos();
			$bd->Conectarse();		    
			if ($bd->select("UPDATE lab_solicitud_analisis SET estado=$estado WHERE id=$idpedido"))
                            return 1;
                        else
                            return 0;
                    }
                    function buscar_pedido_paciente_fecha($idpaciente,$fecha)
                    {
                        $bd = new baseDatos();
			$bd->Conectarse();
                        $fdesde = restarDiasFecha($fecha,3);
                        $fhasta = restarDiasFecha($fecha,-3);
			$bd->select("SELECT * FROM lab_solicitud_analisis WHERE 
                                            DATE(fecha)>='".$fdesde."' AND DATE()<='$fhasta' AND cli_paciente_id=$cli_paciente_id");
                        self::asignar($bd->registro());
                        //ahora busco el adjunto del laboratorio para decir si hay resultado o no
                        $arch = new clase_archivos("");
                        $arch->foranea_id_referencia($this->id(), "laboratorio");
                        $archivos = $arch->arreglo_foraneo_id_referencia();
                        $iterator_archivos = new clase_patron_iterator($archivos);
                        while ($iterator_archivos->existeElementoSiguiente()) 
                        {
                            $fila_archivos = $iterator_archivos->elementoSiguiente();
                            if ($fila_archivos['nombre'] != "")
                                return 1;
                        }
                        
                    }
}
?>
