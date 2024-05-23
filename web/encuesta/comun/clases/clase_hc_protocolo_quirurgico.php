<?
      class clase_hc_protocolo_quirurgico
      {
          
        var $id = '';
        var $idprofesional = '';
        var $idpaciente = '';
        var $fecha = '';
        var $idosocial = '';
        var $diagnostico_ingreso = '';
        var $cirujano = '';
        var $ayudante1 = '';
        var $ayudante2 = '';
        var $anestesista = '';
        var $instrumentadora = '';
        var $quirofano = '';
        var $FTV = '';
        var $FTV_otros = '';
        var $protesis = '';
        var $protesis_detalle = '';
        var $muestras_bacteriologica = '';
        var $muestras_AP = '';
        var $muestras_cantidad = '';
        var $codigos_nomenclador = '';
        var $fecha_inicio = '';
        var $hora_inicio = '';
        var $fecha_fin = '';
        var $hora_fin = '';
        var $detalle = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     var $arreglo_foraneo_idosocial='';
      	     
      
         function clase_hc_protocolo_quirurgico($id)
         {
            $bd = new baseDatos();
            $bd->Conectarse();
            $bd->select("SELECT * FROM hc_protocolo_quirurgico WHERE id=$id");
            $arreglo=$bd->registro();
            $this->id = $arreglo['id'];
            $this->idprofesional = $arreglo['idprofesional'];
            $this->idpaciente = $arreglo['idpaciente'];
            $this->fecha = $arreglo['fecha'];
            $this->idosocial = $arreglo['idosocial'];
            $this->diagnostico_ingreso = $arreglo['diagnostico_ingreso'];
            $this->cirujano = $arreglo['cirujano'];
            $this->ayudante1 = $arreglo['ayudante1'];
            $this->ayudante2 = $arreglo['ayudante2'];
            $this->anestesista = $arreglo['anestesista'];
            $this->instrumentadora = $arreglo['instrumentadora'];
            $this->quirofano = $arreglo['quirofano'];
            $this->FTV = $arreglo['FTV'];
            $this->FTV_otros = $arreglo['FTV_otros'];
            $this->protesis = $arreglo['protesis'];
            $this->protesis_detalle = $arreglo['protesis_detalle'];
            $this->muestras_bacteriologica = $arreglo['muestras_bacteriologica'];
            $this->muestras_AP = $arreglo['muestras_AP'];
            $this->muestras_cantidad = $arreglo['muestras_cantidad'];
            $this->codigos_nomenclador = $arreglo['codigos_nomenclador'];
            $this->fecha_inicio = $arreglo['fecha_inicio'];
            $this->hora_inicio = $arreglo['hora_inicio'];
            $this->fecha_fin = $arreglo['fecha_fin'];
            $this->hora_fin = $arreglo['hora_fin'];
            $this->detalle = $arreglo['detalle'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hc_protocolo_quirurgico(idprofesional,idpaciente,fecha,idosocial,diagnostico_ingreso,cirujano,ayudante1,ayudante2,anestesista,instrumentadora,quirofano,FTV,FTV_otros,protesis,protesis_detalle,muestras_bacteriologica,muestras_AP,muestras_cantidad,codigos_nomenclador,fecha_inicio,hora_inicio,fecha_fin,hora_fin,detalle) VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->idosocial."','".$this->diagnostico_ingreso."','".$this->cirujano."','".$this->ayudante1."','".$this->ayudante2."','".$this->anestesista."','".$this->instrumentadora."','".$this->quirofano."','".$this->FTV."','".$this->FTV_otros."','".$this->protesis."','".$this->protesis_detalle."','".$this->muestras_bacteriologica."','".$this->muestras_AP."','".$this->muestras_cantidad."','".$this->codigos_nomenclador."','".$this->fecha_inicio."','".$this->hora_inicio."','".$this->fecha_fin."','".$this->hora_fin."','".$this->detalle."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hc_protocolo_quirurgico SET idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',idosocial='".$this->idosocial."',diagnostico_ingreso='".$this->diagnostico_ingreso."',cirujano='".$this->cirujano."',ayudante1='".$this->ayudante1."',ayudante2='".$this->ayudante2."',anestesista='".$this->anestesista."',instrumentadora='".$this->instrumentadora."',quirofano='".$this->quirofano."',FTV='".$this->FTV."',FTV_otros='".$this->FTV_otros."',protesis='".$this->protesis."',protesis_detalle='".$this->protesis_detalle."',muestras_bacteriologica='".$this->muestras_bacteriologica."',muestras_AP='".$this->muestras_AP."',muestras_cantidad='".$this->muestras_cantidad."',codigos_nomenclador='".$this->codigos_nomenclador."',fecha_inicio='".$this->fecha_inicio."',hora_inicio='".$this->hora_inicio."',fecha_fin='".$this->fecha_fin."',hora_fin='".$this->hora_fin."',detalle='".$this->detalle."' WHERE id='".$this->id."'"))
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
          function diagnostico_ingreso()
          {
               return $this->diagnostico_ingreso;
          }
          function cirujano()
          {
               return $this->cirujano;
          }
          function ayudante1()
          {
               return $this->ayudante1;
          }
          function ayudante2()
          {
               return $this->ayudante2;
          }
          function anestesista()
          {
               return $this->anestesista;
          }
          function instrumentadora()
          {
               return $this->instrumentadora;
          }
          function quirofano()
          {
               return $this->quirofano;
          }
          function FTV()
          {
               return $this->FTV;
          }
          function FTV_otros()
          {
               return $this->FTV_otros;
          }
          function protesis()
          {
               return $this->protesis;
          }
          function protesis_detalle()
          {
               return $this->protesis_detalle;
          }
          function muestras_bacteriologica()
          {
               return $this->muestras_bacteriologica;
          }
          function muestras_AP()
          {
               return $this->muestras_AP;
          }
          function muestras_cantidad()
          {
               return $this->muestras_cantidad;
          }
          function codigos_nomenclador()
          {
               return $this->codigos_nomenclador;
          }
          function fecha_inicio()
          {
               return $this->fecha_inicio;
          }
          function hora_inicio()
          {
               return $this->hora_inicio;
          }
          function fecha_fin()
          {
               return $this->fecha_fin;
          }
          function hora_fin()
          {
               return $this->hora_fin;
          }
          function detalle()
          {
               return $this->detalle;
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
               $this->id = $campo;
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional = $campo;
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente = $campo;
          }
          function fecha_asigna($campo)
          {
               $this->fecha = $campo; 
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial = $campo;
          }
          function diagnostico_ingreso_asigna($campo)
          {
               $this->diagnostico_ingreso = $campo;
          }
          function cirujano_asigna($campo)
          {
               $this->cirujano = $campo;
          }
          function ayudante1_asigna($campo)
          {
               $this->ayudante1 = $campo;
          }
          function ayudante2_asigna($campo)
          {
               $this->ayudante2 = $campo;
          }
          function anestesista_asigna($campo)
          {
               $this->anestesista = $campo;
          }
          function instrumentadora_asigna($campo)
          {
               $this->instrumentadora = $campo;
          }
          function quirofano_asigna($campo)
          {
               $this->quirofano = $campo;
          }
          function FTV_asigna($campo)
          {
               $this->FTV = $campo;
          }
          function FTV_otros_asigna($campo)
          {
               $this->FTV_otros = $campo;
          }
          function protesis_asigna($campo)
          {
               $this->protesis = $campo;
          }
          function protesis_detalle_asigna($campo)
          {
               $this->protesis_detalle = $campo;
          }
          function muestras_bacteriologica_asigna($campo)
          {
               $this->muestras_bacteriologica = $campo; 
          }
          function muestras_AP_asigna($campo)
          {
               $this->muestras_AP = $campo;
          }
          function muestras_cantidad_asigna($campo)
          {
               $this->muestras_cantidad = $campo;
          }
          function codigos_nomenclador_asigna($campo)
          {
               $this->codigos_nomenclador = $campo;
          }
          function fecha_inicio_asigna($campo)
          {
               $this->fecha_inicio = $campo;
          }
          function hora_inicio_asigna($campo)
          {
               $this->hora_inicio = $campo;
          }
          function fecha_fin_asigna($campo)
          {
               $this->fecha_fin = $campo;
          }
          function hora_fin_asigna($campo)
          {
               $this->hora_fin = $campo;
          }
          function detalle_asigna($campo)
          {
               $this->detalle = $campo;
          }
          
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM hc_protocolo_quirurgico WHERE idprofesional=$idprofesional");				
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
				$bd->select("SELECT * FROM hc_protocolo_quirurgico WHERE idpaciente=$idpaciente ORDER BY fecha DESC");				
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
				$bd->select("SELECT * FROM hc_protocolo_quirurgico WHERE idosocial=$idosocial");				
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