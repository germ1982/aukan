<?
      class clase_turnos       
      {
	  	  var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $idpaciente = '';
          var $codigo = '';
          var $consul = '';
          var $idfinanciacion = '';
          var $obra_social = '';
          var $fpago = '';
          var $estado = '';
          var $observacion = '';
          var $nombre = '';
          var $fecha_carga = '';
          var $usuario = '';
          var $idlugar = '';
          var $sobreturno = '';
          var $hora_cambio_estado = '';
          var $arreglo_turnos_profesional = '';
          var $total_coseguro = '';
          
          
      
      
      
         function clase_turnos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM turnos WHERE hora=$id");
      	     $arreglo=$bd->registro();      	           	     
      	 }
      	 function asigna($arreglo)
      	 {
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->consul=$arreglo['consul'];
      	     $this->idfinanciacion=$arreglo['idfinanciacion'];
      	     $this->obra_social=$arreglo['obra_social'];
      	     $this->fpago=$arreglo['fpago'];
      	     $this->estado=$arreglo['estado'];
      	     $this->observacion=$arreglo['observacion'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->fecha_carga=$arreglo['fecha_carga'];
      	     $this->usuario=$arreglo['usuario'];
      	     $this->idlugar=$arreglo['idlugar'];
      	     $this->sobreturno=$arreglo['sobreturno'];
      	     $this->hora_cambio_estado=$arreglo['hora_cambio_estado'];	
      	 }       
      
      
      function guardar($operacion)
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($operacion==0) {
      	      if ($bd->select("INSERT INTO turnos(idprofesional,fecha,hora,idpaciente,codigo,idfinanciacion,estado,observacion,fecha_carga,usuario,idlugar,sobreturno) 
      	      VALUES('".$this->idprofesional."','".fechaBase($this->fecha)."','".horaRecortada($this->hora)."','".$this->idpaciente."','".$this->codigo."','".$this->idfinanciacion."','".$this->estado."','".$this->observacion."','".$this->fecha_carga."','".$this->usuario."','".$this->idlugar."','".$this->sobreturno."')"))
      	      {
      	          $this->hora=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE turnos SET idpaciente='".$this->idpaciente."',codigo='".$this->codigo."',idfinanciacion='".$this->idfinanciacion."',estado='".$this->estado."',observacion='".$this->observacion."',fecha_carga='".$this->fecha_carga."',usuario='".$this->usuario."',idlugar='".$this->idlugar."',sobreturno='".$this->sobreturno."' WHERE idprofesional=".$this->idprofesional." AND fecha='".fechaBase($this->fecha)."' AND hora='".$this->hora."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
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
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function codigo()
          {
               return $this->codigo;
          }
          function consul()
          {
               return $this->consul;
          }
          function idfinanciacion()
          {
               return $this->idfinanciacion;
          }
          function obra_social()
          {
               return $this->obra_social;
          }
          function fpago()
          {
               return $this->fpago;
          }
          function estado()
          {
               return $this->estado;
          }
          function observacion()
          {
               return $this->observacion;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function fecha_carga()
          {
               return $this->fecha_carga;
          }
          function usuario()
          {
               return $this->usuario;
          }
          function idlugar()
          {
               return $this->idlugar;
          }
          function sobreturno()
          {
               return $this->sobreturno;
          }
          function hora_cambio_estado()
          {
               return $this->hora_cambio_estado;
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
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
          }
          function consul_asigna($campo)
          {
               $this->consul=$campo;
               
          }
          function idfinanciacion_asigna($campo)
          {
               $this->idfinanciacion=$campo;
               
          }
          function obra_social_asigna($campo)
          {
               $this->obra_social=$campo;
               
          }
          function fpago_asigna($campo)
          {
               $this->fpago=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function observacion_asigna($campo)
          {
               $this->observacion=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function fecha_carga_asigna($campo)
          {
               $this->fecha_carga=$campo;
               
          }
          function usuario_asigna($campo)
          {
               $this->usuario=$campo;
               
          }
          function idlugar_asigna($campo)
          {
               $this->idlugar=$campo;
               
          }
          function sobreturno_asigna($campo)
          {
               $this->sobreturno=$campo;
               
          }
          function hora_cambio_estado_asigna($campo)
          {
               $this->hora_cambio_estado=$campo;
               
          }
          function arreglo_turnos_profesional()
          {
              return $this->arreglo_turnos_profesional;	
          }
          function chequea_turno_hora_lugares($idprofesional,$fecha,$hora)
          {
              $bd = new baseDatos();
              $bd->Conectarse();
              $bd->select("SELECT * FROM turnos WHERE idprofesional=$idprofesional AND fecha='".fechaBase($fecha)."' AND hora='".$hora."'");
              if ($bd->numero_filas() != 0)
                  return 1;
              else 
                  return 0;	
          }      
          function grilla_turnos_profesional($idprofesional,$fecha,$idlugar)
          {
              $bd = new baseDatos();
      	      $bd->Conectarse();
      	      $bd->select("SELECT * FROM turnos WHERE idlugar=$idlugar AND idprofesional=$idprofesional AND fecha='".fechaBase($fecha)."'");
      	      $filas = $bd->numero_filas();
      	      if ($filas != 0)
      	      {
      	      	  $pro = new clase_listar();											
		    	  for($i=0;$i<=$bd->numero_filas();$i++) 
		    	  {
		    	      $fila = $bd->registro(); 
		    	      if ($fila['idprofesional'] != 0 && $fila['idprofesional'] != '')
		    	      {
		    	      	  $a['idprofesional'] = $fila['idprofesional'];
		    	      	  $a['fecha'] = $fila['fecha'];
		    	      	  $a['hora'] = $fila['hora'];
		    	      	  $a['idpaciente'] = $fila['idpaciente'];
		    	      	  $a['idfinanciacion'] = $fila['idfinanciacion'];
		    	      	  $a['estado'] = $fila['estado'];
		    	      	  $a['observacion'] = $fila['observacion'];
		    	      	  $a['idlugar'] = $fila['idlugar']; 		    	      	  
		    		      $pro->introducirElemento($a);		    		      
		    	      } 
		    	  }
		    	  $this->arreglo_turnos_profesional = $pro;		    	  
      	      }
      	      else 
      	      {
      	      	  $bd->select("SELECT * FROM profesionales_atencion WHERE idprofesional = $idprofesional AND idlugar=$idlugar");
				  while ( $arreglo = $bd->registro() )
				  {
				      $hi = $arreglo['hora_inicio'];
					  $hf = $arreglo['hora_fin'];
					  $turno = $arreglo['turno'];
					  $hi1 = $arreglo['hora_inicio1'];
					  $hf1 = $arreglo['hora_fin1'];
					  $dia = $arreglo['dia_semana'];//dia me dice en numeros que dia es 1 es para lunes, 2 para martes, 3 miercoles, 4 jueves 
						                              //5 viernes, 6 sabado y 7 domingo
                      $idlugar_atencion = $arreglo['idlugar'];
                      
                      if (($hi<=$hf) && ($hi1<=$hf1))
					  {									   
					      if ( diaExactoDeSemana($dia,fechaBase($fecha)) )					   
						  {
						      if (($hi != "" && $hi != "00:00:00") && ($hf != "" && $hf != "00:00:00"))
							  {
				    		      $horaFormada = $hi;
								  list($h,$m,$s)=explode(":",$hf);
								  $minutosF = ($h*60+$m)/$turno;
								  list($h,$m,$s)=explode(":",$hi);
								  $minutosI = ($h*60+$m)/$turno;
								  $minutos = $minutosF-$minutosI;
							      $bandera = 0;
							      for ($j=0;($j<=$minutos && $bandera ==0);$j++)
								  {//cambiar tambien el de abajo									  	
	                               //el if con el else de bandera es para el bug	
	                               //chequeamos antes que no existe ningun registro para es profesional en esa fecha en cualquier lugar	                                  								
								      if (self::chequea_turno_hora_lugares($idprofesional,$fecha,$horaFormada) == 0)
									  {	
									      $this->idprofesional = $idprofesional;
									      $this->codigo = '420101';
									      $this->fecha = $fecha;
									      $this->hora = $horaFormada;
									      $this->idlugar = $idlugar_atencion;
									      $this->idpaciente = 0;
									      $this->idfinanciacion = 0;
									      $this->estado = '';
									      $this->observaciones = '';
									      $this->fecha_carga = '';
									      $this->usuario = ''; 
									      $this->sobreturno = 0; 										  		 
									      $f = self::guardar(0);
									      //return $f."nn";
	                                   }                                                                  
									   else  
									       $bandera = 1;
									   $horaFormada = sumarMinutos($horaFormada,$turno); 			
								
								    }
								    if (($hi1 != "" && $hi1 != "00:00:00") && ($hf1 != "" && $hf1 != "00:00:00"))
							        {
			    				        $horaFormada = $hi1;
										list($h,$m,$s)=explode(":",$hf1);
										$minutosF = ($h*60+$m)/$turno;
										list($h,$m,$s)=explode(":",$hi1);
										$minutosI = ($h*60+$m)/$turno;
										$minutos = $minutosF-$minutosI;
		                                $bandera=0;
									    for ($j=0;($j<=$minutos && $bandera ==0);$j++)		        			
							    		{		
		                                    //el if con el else de bandera es para el bug			    	    
									    	if (self::chequea_turno_hora_lugares($idprofesional,$fecha,$horaFormada))
										    {	
										        $this->idprofesional = $idprofesional;
										        $this->codigo = '420101';
										        $this->fecha = $fecha;
										        $this->hora = $horaFormada;
										        $this->idlugar = $idlugar_atencion;
										        $this->idpaciente = 0;
										        $this->idfinanciacion = 0;
										        $this->estado = '';
										        $this->observaciones = '';
										        $this->fecha_carga = '';
										        $this->usuario = ''; 
										        $this->sobreturno = 0; 										  		 
										        self::guardar();
		                                    }      
									    	else 
									    	    $bandera = 1;
		                    	            $horaFormada = sumarMinutos($horaFormada,$turno); 										 
					    	    		}						    	    				
					    		    }
								    ///es para el arreglo del bug
	                                if ($bandera == 0)
					                     self::grilla_turnos_profesional($idprofesional,$fecha,$idlugar);							
						            else
							            $this->arreglo_turnos_profesional = 0;							            
	      	                   }//if (($hi != "" && $hi != "00:00:00") && ($hf != "" && $hf != "00:00:00"))  	      	                       	     
                          }//if ( diaExactoDeSemana($dia,fechaBase($fecha)) )
					  }//if (($hi<=$hf) && ($hi1<=$hf1))
					  else
				    	  $this->arreglo_turnos_profesional = 0;
				  }//while ( $arreglo = $baseAux->registro() )
      	      }//else  
          }//fin de funcion		

          function honorarios_guardia($fdesde,$fhasta,$idprofesional_base)
          {
              $bd = new baseDatos();
              $bd->Conectarse();
              $bd->select("SELECT count(idpaciente) as cantidad,turnos.idprofesional_atiende 
              			  FROM turnos LEFT JOIN profesionales ON (profesionales.idprofesional=turnos.idprofesional_atiende)
                          WHERE fecha>='".fechaBase($fdesde)."' AND fecha<='".fechaBase($fhasta)."' 
                          AND turnos.idprofesional=$idprofesional_base AND turnos.idprofesional_atiende <>0 AND 
                          liquidacion_guardia=1 
                          GROUP BY turnos.idprofesional_atiende");
              $pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_turnos_profesional = $pro;		
          }
          function total_consultas_medicos_guardia($fdesde,$fhasta,$idprofesional_base)
          {
              $bd = new baseDatos();
              $bd->Conectarse();
              $bd->select("SELECT (COUNT(idpaciente) * valor_consulta_ambulatoria) as total
              			  FROM turnos LEFT JOIN profesionales ON (profesionales.idprofesional=turnos.idprofesional_atiende)
                          WHERE fecha>='".fechaBase($fdesde)."' AND fecha<='".fechaBase($fhasta)."' 
                          AND turnos.idprofesional=$idprofesional_base AND turnos.idprofesional_atiende <>0 AND 
                          liquidacion_guardia=1 AND porcentaje_consulta_guardia <>0 AND porcentaje_consulta_guardia <> ''
                          GROUP BY turnos.idprofesional_atiende");
              $total = 0;	
              while ($arreglo = $bd->registro())
              {
                  $total += $arreglo['total'];
              }
              return $total;
          }
          function pacientes_guardia_internados($fdesde,$fhasta,$idprofesional)
          {
          	  $bd = new baseDatos();
              $bd->Conectarse();
              if ($idprofesional != 0)
                  $where = " AND turnos.idprofesional_atiende=$idprofesional AND liquidacion_guardia=1 ";
              $bd->select("SELECT idpaciente,profesionales.nombre FROM turnos LEFT JOIN 
                          profesionales ON (turnos.idprofesional_atiende=profesionales.idprofesional)
                          WHERE turnos.idprofesional=662  $where AND turnos.idprofesional_atiende<>0
                          AND fecha>='".fechaBase($fdesde)."' AND fecha<='".fechaBase($fhasta)."' 
                           
                          GROUP BY idpaciente");
              
              $pro = new clase_listar();										
	    	  for($i=0;$i<=$bd->numero_filas();$i++) 
	    	  {
	    	      $fila = $bd->registro(); 
	    		  $pro->introducirElemento($fila); 
	    	  }
	    	  $this->arreglo_turnos_profesional = $pro; 
          }
          function buscar_turnos_art($fdesde,$fhasta,$idosocial,$empresa)
          {
          	$bd = new baseDatos();
          	$bd->Conectarse();
                if ($empresa != '')
                    $where_empresa = " AND empleador like '%$empresa%'";
          	if ($idosocial != 0)
          		$where = " AND turnos.idfinanciacion=$idosocial ";
          	else 
          		$where = " AND financiacion.art=1 ";
          		$bd->select("SELECT pacientes.nombre as pac,pacientes.idpaciente,profesionales.nombre as prof,
          				    financiacion.nombre as os,turnos.idfinanciacion,turnos.fecha,turnos.hora,turnos.idprofesional_atiende,estado_revision
          				FROM turnos LEFT JOIN 
          				profesionales ON (turnos.idprofesional_atiende=profesionales.idprofesional)
          				LEFT JOIN pacientes ON (pacientes.idpaciente=turnos.idpaciente) LEFT JOIN 
          				financiacion ON (financiacion.idfinanciacion=turnos.idfinanciacion) LEFT JOIN hcl_siniestros ON (hcl_siniestros.idpaciente=
                                        turnos.idpaciente)
          				WHERE turnos.fecha>='".fechaBase($fdesde)."' AND turnos.fecha<='".fechaBase($fhasta)."' 
          				$where AND turnos.idpaciente<>0  $where_empresa        				
              
                          GROUP BY idpaciente,turnos.idfinanciacion");
          
          		$pro = new clase_listar();
          		for($i=0;$i<=$bd->numero_filas();$i++)
          		{
          			$fila = $bd->registro();
          			if ($fila['idpaciente'] != 0 && $fila['idpaciente'] != '')
                                {
                                    
          			        $pro->introducirElemento($fila);
				}
          		}
          		$this->arreglo_turnos_profesional = $pro;
          }
          function turnoRevisado($idpaciente,$idprofesional,$fecha,$hora,$estado)
          {
          	$bd = new baseDatos();
          	$bd->Conectarse();          	
          	$where = " idprofesional_atiende=$idprofesional AND fecha='$fecha' AND hora='$hora' AND idpaciente=$idpaciente ";
          	if ($bd->select("UPDATE turnos SET estado_revision=$estado WHERE $where"))
                return 1;
          	else 
          		return 0;          		
          }
          function buscar_ultimo_turno_profesional($idprofesional)
          {
              $bd = new baseDatos();
              $bd->Conectarse();          	          
              $bd->select("SELECT * FROM turnos WHERE idprofesional=$idprofesional AND idpaciente<>0 ORDER BY fecha DESC,hora DESC");
              self::asigna($bd->registro());
          }
          function eliminar_turnos_profesional_desde_fecha($idprofesional,$fecha)
          {
              $bd = new baseDatos();
              $bd->Conectarse();          	          
              if ($bd->select("DELETE FROM turnos WHERE idprofesional=$idprofesional AND fecha >'$fecha'"))
                  return 1;
              else 
                  return 0;
          }
          function total_coseguro_profesional($idprofesional,$fecha)
          {
              $bd = new baseDatos();
              $bd->Conectarse();      
              $bd->select("SELECT SUM(codigo) as cantidad FROM turnos WHERE idprofesional=$idprofesional AND fecha ='".fechaBase($fecha)."'");
              $arreglo = $bd->registro();
              $this->total_coseguro = $arreglo['cantidad'];
          }
          function total_coseguro()
          {
              return $this->total_coseguro;
          }
          function insertar_fila_cambio_estado($idprofesional,$idpaciente,$fecha,$hora,$usuario)
          {
              $bd = new baseDatos();
              $bd->Conectarse(); 
              
              $bd->select("SELECT * FROM turnos_espera WHERE idprofesional=$idprofesional AND fecha ='$fecha' AND hora='$hora'");
              $arreglo = $bd->registro();
              if ($arreglo['idpaciente'] != 0 && $arreglo['idpaciente'] != '')
              {
                  $bd->select("UPDATE turnos_espera SET estado='EN ESPERA',usuario='$usuario' WHERE idprofesional=$idprofesional AND fecha ='$fecha' AND hora='$hora'");
                  return 1;
              }
              else
              {
                  $bd->select("INSERT INTO turnos_espera(idprofesional,fecha,hora,estado,idpaciente,usuario) "
                          . "VALUES($idprofesional,'$fecha','$hora','EN ESPERA',$idpaciente,'$usuario')");
                  return "INSERT INTO turnos_espera(idprofesional,fecha,hora,estado,idpaciente,usuario) "
                          . "VALUES($idprofesional,'$fecha','$hora','EN ESPERA',$idpaciente,'$usuario')";
              }
          }
          function eliminar_turnos_espera($idprofesional,$fecha,$hora)
          {
              $bd = new baseDatos();
              $bd->Conectarse();          	          
              if ($bd->select("DELETE FROM turnos_espera WHERE idprofesional=$idprofesional AND fecha ='$fecha' AND hora='$hora'"))
                  return 1;
              else 
                  return 0;
          }
}
?>
