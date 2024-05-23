<?
      class clase_ficha_diaria_pacientes       
      {
	  var $id = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $fecha = '';
          var $hora = '';
          var $motivo_consulta = '';
          var $examen_fisico = '';
          var $estudios_especiales = '';
          var $diagnostico = '';
          var $tratamiento = '';
          var $observaciones = '';
          var $peso = '';
          var $talla = '';
          var $perimetro = '';
          var $proxima_visita = '';
          var $otro_dato = '';
          var $codigo_estudio = '';
          var $codigo_diagnostico = '';
          var $codigo_tratamiento = '';
          var $solicito_cirugia = '';
          var $interconsulta = '';
          var $fq = '';
          var $tac_rnm = '';
          var $tratamiento_solicitado = '';
          var $cirugia = '';
          var $rx = '';
          var $nada = '';
          var $coloco_yeso = '';
          var $realizo_infiltracion = '';
          var $conceptId = '';
          var $programada = '';
          var $id_encuesta = '';
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_ficha_diaria_pacientes($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM ficha_diaria_pacientes WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asigna($arreglo);
      	           	     
      	 }
      	 function asigna($arreglo)
      	 {
      	 	$this->id=$arreglo['id'];
      	 	$this->idprofesional=$arreglo['idprofesional'];
      	 	$this->idpaciente=$arreglo['idpaciente'];
      	 	$this->fecha=$arreglo['fecha'];
      	 	$this->hora=$arreglo['hora'];
      	 	$this->motivo_consulta=$arreglo['motivo_consulta'];
      	 	$this->examen_fisico=$arreglo['examen_fisico'];
      	 	$this->estudios_especiales=$arreglo['estudios_especiales'];
      	 	$this->diagnostico=$arreglo['diagnostico'];
      	 	$this->tratamiento=$arreglo['tratamiento'];
      	 	$this->observaciones=$arreglo['observaciones'];
      	 	$this->peso=$arreglo['peso'];
      	 	$this->talla=$arreglo['talla'];
      	 	$this->perimetro=$arreglo['perimetro'];
      	 	$this->proxima_visita=$arreglo['proxima_visita'];
      	 	$this->otro_dato=$arreglo['otro_dato'];
      	 	$this->codigo_estudio=$arreglo['codigo_estudio'];
      	 	$this->codigo_diagnostico=$arreglo['codigo_diagnostico'];
      	 	$this->codigo_tratamiento=$arreglo['codigo_tratamiento'];
      	 	$this->solicito_cirugia=$arreglo['solicito_cirugia'];
                $this->solicito_cirugia=$arreglo['solicito_cirugia'];
                $this->interconsulta = $arreglo['interconsulta'];
                $this->fq = $arreglo['fq'];
                $this->tac_rnm = $arreglo['tac_rnm'];
                $this->tratamiento_solicitado = $arreglo['tratamiento_solicitado'];
                $this->cirugia = $arreglo['cirugia'];
                $this->nada = $arreglo['nada'];
                $this->rx = $arreglo['rx'];
                $this->coloco_yeso = $arreglo['coloco_yeso'];
                $this->realizo_infiltracion = $arreglo['realizo_infiltracion'];
                $this->conceptId = $arreglo['conceptId'];
                $this->programada = $arreglo['programada'];
                $this->id_encuesta = $arreglo['id_encuesta'];
      	 }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO ficha_diaria_pacientes(idprofesional,idpaciente,fecha,hora,motivo_consulta,examen_fisico,estudios_especiales,diagnostico,tratamiento,observaciones,peso,talla,perimetro,proxima_visita,otro_dato,codigo_estudio,codigo_diagnostico,codigo_tratamiento,solicito_cirugia,interconsulta,fq,tac_rnm,tratamiento_solicitado,cirugia,nada,rx,coloco_yeso,realizo_infiltracion,conceptId,programada,id_encuesta) VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->hora."','".$bd->parser($this->motivo_consulta)."','".$bd->parser($this->examen_fisico)."','".$bd->parser($this->estudios_especiales)."','".$this->diagnostico."','".$bd->parser($this->tratamiento)."','".$bd->parser($this->observaciones)."','".$this->peso."','".$this->talla."','".$this->perimetro."','".$this->proxima_visita."','".$bd->parser($this->otro_dato)."','".$this->codigo_estudio."','".$this->codigo_diagnostico."','".$this->codigo_tratamiento."','".$this->solicito_cirugia."','" . $this->interconsulta . "','" . $this->fq . "','" . $this->tac_rnm . "','" . $this->tratamiento_solicitado . "','" . $this->cirugia . "','" . $this->nada . "','" . $this->rx . "','".$this->coloco_yeso."','".$this->realizo_infiltracion."','".$this->conceptId."','".$this->programada."','".$this->id_encuesta."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
                  //return "INSERT INTO ficha_diaria_pacientes(idprofesional,idpaciente,fecha,hora,motivo_consulta,examen_fisico,estudios_especiales,diagnostico,tratamiento,observaciones,peso,talla,perimetro,proxima_visita,otro_dato,codigo_estudio,codigo_diagnostico,codigo_tratamiento,solicito_cirugia,interconsulta,fq,tac_rnm,tratamiento_solicitado,cirugia,nada,rx,coloco_yeso,realizo_infiltracion) VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->hora."','".$bd->parser($this->motivo_consulta)."','".$bd->parser($this->examen_fisico)."','".$bd->parser($this->estudios_especiales)."','".$this->diagnostico."','".$bd->parser($this->tratamiento)."','".$bd->parser($this->observaciones)."','".$this->peso."','".$this->talla."','".$this->perimetro."','".$this->proxima_visita."','".$bd->parser($this->otro_dato)."','".$this->codigo_estudio."','".$this->codigo_diagnostico."','".$this->codigo_tratamiento."','".$this->solicito_cirugia."','" . $this->interconsulta . "','" . $this->fq . "','" . $this->tac_rnm . "','" . $this->tratamiento_solicitado . "','" . $this->cirugia . "','" . $this->nada . "','" . $this->rx . "','".$this->coloco_yeso."','".$this->realizo_infiltracion."')";
      	  }else
      	  { 
      	        if ($bd->select("UPDATE ficha_diaria_pacientes SET coloco_yeso='".$this->coloco_yeso."',realizo_infiltracion='".$this->realizo_infiltracion."',idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',hora='".$this->hora."',motivo_consulta='".$bd->parser($this->motivo_consulta)."',examen_fisico='".$bd->parser($this->examen_fisico)."',estudios_especiales='".$bd->parser($this->estudios_especiales)."',diagnostico='".$this->diagnostico."',tratamiento='".$bd->parser($this->tratamiento)."',observaciones='".$bd->parser($this->observaciones)."',peso='".$this->peso."',talla='".$this->talla."',perimetro='".$this->perimetro."',proxima_visita='".$this->proxima_visita."',otro_dato='".$bd->parser($this->otro_dato)."',codigo_estudio='".$this->codigo_estudio."',codigo_diagnostico='".$this->codigo_diagnostico."',codigo_tratamiento='".$this->codigo_tratamiento."',solicito_cirugia='".$this->solicito_cirugia."',interconsulta='" . $this->interconsulta . "',fq='" . $this->fq . "',tac_rnm='" . $this->tac_rnm . "',tratamiento_solicitado='" . $this->tratamiento_solicitado . "',cirugia='" . $this->cirugia . "',nada='" . $this->nada . "',rx='" . $this->rx . "',conceptId='".$this->conceptId."',programada='".$this->programada."',id_encuesta='".$this->id_encuesta."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return "UPDATE ficha_diaria_pacientes SET coloco_yeso='".$this->coloco_yeso."',realizo_infiltracion='".$this->realizo_infiltracion."',idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',hora='".$this->hora."',motivo_consulta='".$bd->parser($this->motivo_consulta)."',examen_fisico='".$bd->parser($this->examen_fisico)."',estudios_especiales='".$bd->parser($this->estudios_especiales)."',diagnostico='".$this->diagnostico."',tratamiento='".$bd->parser($this->tratamiento)."',observaciones='".$bd->parser($this->observaciones)."',peso='".$this->peso."',talla='".$this->talla."',perimetro='".$this->perimetro."',proxima_visita='".$this->proxima_visita."',otro_dato='".$bd->parser($this->otro_dato)."',codigo_estudio='".$this->codigo_estudio."',codigo_diagnostico='".$this->codigo_diagnostico."',codigo_tratamiento='".$this->codigo_tratamiento."',solicito_cirugia='".$this->solicito_cirugia."',interconsulta='" . $this->interconsulta . "',fq='" . $this->fq . "',tac_rnm='" . $this->tac_rnm . "',tratamiento_solicitado='" . $this->tratamiento_solicitado . "',cirugia='" . $this->cirugia . "',nada='" . $this->nada . "',rx='" . $this->rx . "',conceptId='".$this->conceptId."',programada='".$this->programada."',id_encuesta='".$this->id_encuesta."' WHERE id='".$this->id."'";
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
          function hora()
          {
               return $this->hora;
          }
          function motivo_consulta()
          {
               return $this->motivo_consulta;
          }
          function examen_fisico()
          {
               return $this->examen_fisico;
          }
          function estudios_especiales()
          {
               return $this->estudios_especiales;
          }
          function diagnostico()
          {
               return $this->diagnostico;
          }
          function tratamiento_solicitado()
          {
               return $this->tratamiento_solicitado;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function peso()
          {
               return $this->peso;
          }
          function talla()
          {
               return $this->talla;
          }
          function perimetro()
          {
               return $this->perimetro;
          }
          function proxima_visita()
          {
               return $this->proxima_visita;
          }
          function otro_dato()
          {
               return $this->otro_dato;
          }
          function codigo_estudio()
          {
               return $this->codigo_estudio;
          }
          function codigo_diagnostico()
          {
               return $this->codigo_diagnostico;
          }
          function codigo_tratamiento()
          {
               return $this->codigo_tratamiento;
          }
          function solicito_cirugia()
          {
               return $this->solicito_cirugia;
          }
          function coloco_yeso()
          {
               return $this->coloco_yeso;
          }
          function realizo_infiltracion()
          {
               return $this->realizo_infiltracion;
          }
          function interconsulta() {
        return $this->interconsulta;
    }

    function fq() {
        return $this->fq;
    }

    function tac_rnm() {
        return $this->tac_rnm;
    }

    function tratamiento() {
        return $this->tratamiento;
    }

    function cirugia() {
        return $this->cirugia;
    }

    function nada() {
        return $this->nada;
    }

    function rx() {
        return $this->rx;
    }
    function conceptId() {
        return $this->conceptId;
    }
    function programada() {
        return $this->programada;
    }
    function id_encuesta() {
        return $this->id_encuesta;
    }

          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
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
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function motivo_consulta_asigna($campo)
          {
               $this->motivo_consulta=$campo;
               
          }
          function examen_fisico_asigna($campo)
          {
               $this->examen_fisico=$campo;
               
          }
          function estudios_especiales_asigna($campo)
          {
               $this->estudios_especiales=$campo;
               
          }
          function diagnostico_asigna($campo)
          {
               $this->diagnostico=$campo;
               
          }
          function tratamiento_asigna($campo)
          {
               $this->tratamiento=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function peso_asigna($campo)
          {
               $this->peso=$campo;
               
          }
          function talla_asigna($campo)
          {
               $this->talla=$campo;
               
          }
          function perimetro_asigna($campo)
          {
               $this->perimetro=$campo;
               
          }
          function proxima_visita_asigna($campo)
          {
               $this->proxima_visita=$campo;
               
          }
          function otro_dato_asigna($campo)
          {
               $this->otro_dato=$campo;
               
          }
          function codigo_estudio_asigna($campo)
          {
               $this->codigo_estudio=$campo;
               
          }
          function codigo_diagnostico_asigna($campo)
          {
               $this->codigo_diagnostico=$campo;
               
          }
          function codigo_tratamiento_asigna($campo)
          {
               $this->codigo_tratamiento=$campo;
               
          }
          function solicito_cirugia_asigna($campo)
          {
               $this->solicito_cirugia=$campo;
               
          }
          function interconsulta_asigna($campo) {
                $this->interconsulta = $campo;
            }

            function fq_asigna($campo) {
                $this->fq = $campo;
            }

            function tac_rnm_asigna($campo) {
                $this->tac_rnm = $campo;
            }

            function tratamiento_solicitado_asigna($campo) {
                $this->tratamiento_solicitado = $campo;
            }

            function cirugia_asigna($campo) {
                $this->cirugia = $campo;
            }

            function nada_asigna($campo) {
                $this->nada = $campo;
            }

            function rx_asigna($campo) {
                $this->rx = $campo;
            }
               function coloco_yeso_asigna($campo)
            {
                 $this->coloco_yeso=$campo;
            }
            function realizo_infiltracion_asigna($campo)
            {
                 $this->realizo_infiltracion=$campo;
            }
            function conceptId_asigna($campo)
            {
                 $this->conceptId=$campo;
            }
            function programada_asigna($campo)
            {
                 $this->programada=$campo;
            }
            function id_encuesta_asigna($campo)
            {
                 $this->id_encuesta=$campo;
            }
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM ficha_diaria_pacientes WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idpaciente($idpaciente,$orden)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM ficha_diaria_pacientes WHERE idpaciente=$idpaciente ORDER BY fecha $orden");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
      
}
?>
