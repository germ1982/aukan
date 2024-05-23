<?
      class clase_encuesta_adicciones       
      {
	  var $id = '';
          var $id_encuesta = '';
          var $fecha = '';
          var $fuma = '';
          var $dependencia_primer_cigarrillo = '';
          var $dependencia_dificil_no_permitido = '';
          var $dependencia_dificil_dejar_de_fumar = '';
          var $dependencia_cuanto_fuma = '';
          var $dependencia_fuma_enfermo = '';
          var $dependencia_dejar_fumar = '';
          var $dependencia_interes_dejar_fumar = '';
          var $dependencia_intentar_dos_semanas = '';
          var $dependencia_dejar_seis_meses = '';
          var $consume_alcohol = '';
          var $alcohol_dia = '';
          var $alcohol_frecuencia_seis_dia = '';
          var $alcohol_incapacidad_dejar_beber = '';
          var $alcohol_inhabilitante = '';
          var $alcohol_en_ayunas = '';
          var $alcohol_remordimientos = '';
          var $alcohol_no_recordar = '';
          var $alcohol_heridos = '';
          var $alcohol_preocupacion = '';
          var $utiliza_drogas = '';
          var $baja_fecha = '';
          
      
      var $arreglo_foraneo_id_encuesta='';
      	     
      
         function clase_encuesta_adicciones($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM encuesta_adicciones WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_encuesta=$arreglo['id_encuesta'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->fuma=$arreglo['fuma'];
      	     $this->dependencia_primer_cigarrillo=$arreglo['dependencia_primer_cigarrillo'];
      	     $this->dependencia_dificil_no_permitido=$arreglo['dependencia_dificil_no_permitido'];
      	     $this->dependencia_dificil_dejar_de_fumar=$arreglo['dependencia_dificil_dejar_de_fumar'];
      	     $this->dependencia_cuanto_fuma=$arreglo['dependencia_cuanto_fuma'];
      	     $this->dependencia_fuma_enfermo=$arreglo['dependencia_fuma_enfermo'];
      	     $this->dependencia_dejar_fumar=$arreglo['dependencia_dejar_fumar'];
      	     $this->dependencia_interes_dejar_fumar=$arreglo['dependencia_interes_dejar_fumar'];
      	     $this->dependencia_intentar_dos_semanas=$arreglo['dependencia_intentar_dos_semanas'];
      	     $this->dependencia_dejar_seis_meses=$arreglo['dependencia_dejar_seis_meses'];
      	     $this->consume_alcohol=$arreglo['consume_alcohol'];
      	     $this->alcohol_dia=$arreglo['alcohol_dia'];
      	     $this->alcohol_frecuencia_seis_dia=$arreglo['alcohol_frecuencia_seis_dia'];
      	     $this->alcohol_incapacidad_dejar_beber=$arreglo['alcohol_incapacidad_dejar_beber'];
      	     $this->alcohol_inhabilitante=$arreglo['alcohol_inhabilitante'];
      	     $this->alcohol_en_ayunas=$arreglo['alcohol_en_ayunas'];
      	     $this->alcohol_remordimientos=$arreglo['alcohol_remordimientos'];
      	     $this->alcohol_no_recordar=$arreglo['alcohol_no_recordar'];
      	     $this->alcohol_heridos=$arreglo['alcohol_heridos'];
      	     $this->alcohol_preocupacion=$arreglo['alcohol_preocupacion'];
      	     $this->utiliza_drogas=$arreglo['utiliza_drogas'];
      	     $this->baja_fecha=$arreglo['baja_fecha'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO encuesta_adicciones(id_encuesta,fecha,fuma,dependencia_primer_cigarrillo,dependencia_dificil_no_permitido,dependencia_dificil_dejar_de_fumar,dependencia_cuanto_fuma,dependencia_fuma_enfermo,dependencia_dejar_fumar,dependencia_interes_dejar_fumar,dependencia_intentar_dos_semanas,dependencia_dejar_seis_meses,consume_alcohol,alcohol_dia,alcohol_frecuencia_seis_dia,alcohol_incapacidad_dejar_beber,alcohol_inhabilitante,alcohol_en_ayunas,alcohol_remordimientos,alcohol_no_recordar,alcohol_heridos,alcohol_preocupacion,utiliza_drogas,baja_fecha) VALUES('".$this->id_encuesta."','".$this->fecha."','".$this->fuma."','".$this->dependencia_primer_cigarrillo."','".$this->dependencia_dificil_no_permitido."','".$this->dependencia_dificil_dejar_de_fumar."','".$this->dependencia_cuanto_fuma."','".$this->dependencia_fuma_enfermo."','".$this->dependencia_dejar_fumar."','".$this->dependencia_interes_dejar_fumar."','".$this->dependencia_intentar_dos_semanas."','".$this->dependencia_dejar_seis_meses."','".$this->consume_alcohol."','".$this->alcohol_dia."','".$this->alcohol_frecuencia_seis_dia."','".$this->alcohol_incapacidad_dejar_beber."','".$this->alcohol_inhabilitante."','".$this->alcohol_en_ayunas."','".$this->alcohol_remordimientos."','".$this->alcohol_no_recordar."','".$this->alcohol_heridos."','".$this->alcohol_preocupacion."','".$this->utiliza_drogas."','".$this->baja_fecha."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE encuesta_adicciones SET id_encuesta='".$this->id_encuesta."',fecha='".$this->fecha."',fuma='".$this->fuma."',dependencia_primer_cigarrillo='".$this->dependencia_primer_cigarrillo."',dependencia_dificil_no_permitido='".$this->dependencia_dificil_no_permitido."',dependencia_dificil_dejar_de_fumar='".$this->dependencia_dificil_dejar_de_fumar."',dependencia_cuanto_fuma='".$this->dependencia_cuanto_fuma."',dependencia_fuma_enfermo='".$this->dependencia_fuma_enfermo."',dependencia_dejar_fumar='".$this->dependencia_dejar_fumar."',dependencia_interes_dejar_fumar='".$this->dependencia_interes_dejar_fumar."',dependencia_intentar_dos_semanas='".$this->dependencia_intentar_dos_semanas."',dependencia_dejar_seis_meses='".$this->dependencia_dejar_seis_meses."',consume_alcohol='".$this->consume_alcohol."',alcohol_dia='".$this->alcohol_dia."',alcohol_frecuencia_seis_dia='".$this->alcohol_frecuencia_seis_dia."',alcohol_incapacidad_dejar_beber='".$this->alcohol_incapacidad_dejar_beber."',alcohol_inhabilitante='".$this->alcohol_inhabilitante."',alcohol_en_ayunas='".$this->alcohol_en_ayunas."',alcohol_remordimientos='".$this->alcohol_remordimientos."',alcohol_no_recordar='".$this->alcohol_no_recordar."',alcohol_heridos='".$this->alcohol_heridos."',alcohol_preocupacion='".$this->alcohol_preocupacion."',utiliza_drogas='".$this->utiliza_drogas."',baja_fecha='".$this->baja_fecha."' WHERE id='".$this->id."'"))
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
          function fuma()
          {
               return $this->fuma;
          }
          function dependencia_primer_cigarrillo()
          {
               return $this->dependencia_primer_cigarrillo;
          }
          function dependencia_dificil_no_permitido()
          {
               return $this->dependencia_dificil_no_permitido;
          }
          function dependencia_dificil_dejar_de_fumar()
          {
               return $this->dependencia_dificil_dejar_de_fumar;
          }
          function dependencia_cuanto_fuma()
          {
               return $this->dependencia_cuanto_fuma;
          }
          function dependencia_fuma_enfermo()
          {
               return $this->dependencia_fuma_enfermo;
          }
          function dependencia_dejar_fumar()
          {
               return $this->dependencia_dejar_fumar;
          }
          function dependencia_interes_dejar_fumar()
          {
               return $this->dependencia_interes_dejar_fumar;
          }
          function dependencia_intentar_dos_semanas()
          {
               return $this->dependencia_intentar_dos_semanas;
          }
          function dependencia_dejar_seis_meses()
          {
               return $this->dependencia_dejar_seis_meses;
          }
          function consume_alcohol()
          {
               return $this->consume_alcohol;
          }
          function alcohol_dia()
          {
               return $this->alcohol_dia;
          }
          function alcohol_frecuencia_seis_dia()
          {
               return $this->alcohol_frecuencia_seis_dia;
          }
          function alcohol_incapacidad_dejar_beber()
          {
               return $this->alcohol_incapacidad_dejar_beber;
          }
          function alcohol_inhabilitante()
          {
               return $this->alcohol_inhabilitante;
          }
          function alcohol_en_ayunas()
          {
               return $this->alcohol_en_ayunas;
          }
          function alcohol_remordimientos()
          {
               return $this->alcohol_remordimientos;
          }
          function alcohol_no_recordar()
          {
               return $this->alcohol_no_recordar;
          }
          function alcohol_heridos()
          {
               return $this->alcohol_heridos;
          }
          function alcohol_preocupacion()
          {
               return $this->alcohol_preocupacion;
          }
          function utiliza_drogas()
          {
               return $this->utiliza_drogas;
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
          function fuma_asigna($campo)
          {
               $this->fuma=$campo;
               
          }
          function dependencia_primer_cigarrillo_asigna($campo)
          {
               $this->dependencia_primer_cigarrillo=$campo;
               
          }
          function dependencia_dificil_no_permitido_asigna($campo)
          {
               $this->dependencia_dificil_no_permitido=$campo;
               
          }
          function dependencia_dificil_dejar_de_fumar_asigna($campo)
          {
               $this->dependencia_dificil_dejar_de_fumar=$campo;
               
          }
          function dependencia_cuanto_fuma_asigna($campo)
          {
               $this->dependencia_cuanto_fuma=$campo;
               
          }
          function dependencia_fuma_enfermo_asigna($campo)
          {
               $this->dependencia_fuma_enfermo=$campo;
               
          }
          function dependencia_dejar_fumar_asigna($campo)
          {
               $this->dependencia_dejar_fumar=$campo;
               
          }
          function dependencia_interes_dejar_fumar_asigna($campo)
          {
               $this->dependencia_interes_dejar_fumar=$campo;
               
          }
          function dependencia_intentar_dos_semanas_asigna($campo)
          {
               $this->dependencia_intentar_dos_semanas=$campo;
               
          }
          function dependencia_dejar_seis_meses_asigna($campo)
          {
               $this->dependencia_dejar_seis_meses=$campo;
               
          }
          function consume_alcohol_asigna($campo)
          {
               $this->consume_alcohol=$campo;
               
          }
          function alcohol_dia_asigna($campo)
          {
               $this->alcohol_dia=$campo;
               
          }
          function alcohol_frecuencia_seis_dia_asigna($campo)
          {
               $this->alcohol_frecuencia_seis_dia=$campo;
               
          }
          function alcohol_incapacidad_dejar_beber_asigna($campo)
          {
               $this->alcohol_incapacidad_dejar_beber=$campo;
               
          }
          function alcohol_inhabilitante_asigna($campo)
          {
               $this->alcohol_inhabilitante=$campo;
               
          }
          function alcohol_en_ayunas_asigna($campo)
          {
               $this->alcohol_en_ayunas=$campo;
               
          }
          function alcohol_remordimientos_asigna($campo)
          {
               $this->alcohol_remordimientos=$campo;
               
          }
          function alcohol_no_recordar_asigna($campo)
          {
               $this->alcohol_no_recordar=$campo;
               
          }
          function alcohol_heridos_asigna($campo)
          {
               $this->alcohol_heridos=$campo;
               
          }
          function alcohol_preocupacion_asigna($campo)
          {
               $this->alcohol_preocupacion=$campo;
               
          }
          function utiliza_drogas_asigna($campo)
          {
               $this->utiliza_drogas=$campo;
               
          }
          function baja_fecha_asigna($campo)
          {
               $this->baja_fecha=$campo;
               
          }
          
          
          
	      function foranea_id_encuesta($id_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_adicciones WHERE id_encuesta=$id_encuesta");				
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