<?
      class clase_encuesta_descanso       
      {
	  var $id = '';
          var $id_encuesta = '';
          var $fecha = '';
          var $suenio_adecuado = '';
          var $medicacion_dormir = '';
          var $suenio_nocturno_reparador = '';
          var $ronca = '';
          var $cansancio = '';
          var $suenio_apnea = '';
          var $diagnostico_apnea = '';
          var $baja_fecha = '';
          
      
      var $arreglo_foraneo_id_encuesta='';
      	     
      
         function clase_encuesta_descanso($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM encuesta_descanso WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_encuesta=$arreglo['id_encuesta'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->suenio_adecuado=$arreglo['suenio_adecuado'];
      	     $this->medicacion_dormir=$arreglo['medicacion_dormir'];
      	     $this->suenio_nocturno_reparador=$arreglo['suenio_nocturno_reparador'];
      	     $this->ronca=$arreglo['ronca'];
      	     $this->cansancio=$arreglo['cansancio'];
      	     $this->suenio_apnea=$arreglo['suenio_apnea'];
      	     $this->diagnostico_apnea=$arreglo['diagnostico_apnea'];
      	     $this->baja_fecha=$arreglo['baja_fecha'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO encuesta_descanso(id_encuesta,fecha,suenio_adecuado,medicacion_dormir,suenio_nocturno_reparador,ronca,cansancio,suenio_apnea,diagnostico_apnea,baja_fecha) VALUES('".$this->id_encuesta."','".$this->fecha."','".$this->suenio_adecuado."','".$this->medicacion_dormir."','".$this->suenio_nocturno_reparador."','".$this->ronca."','".$this->cansancio."','".$this->suenio_apnea."','".$this->diagnostico_apnea."','".$this->baja_fecha."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE encuesta_descanso SET id_encuesta='".$this->id_encuesta."',fecha='".$this->fecha."',suenio_adecuado='".$this->suenio_adecuado."',medicacion_dormir='".$this->medicacion_dormir."',suenio_nocturno_reparador='".$this->suenio_nocturno_reparador."',ronca='".$this->ronca."',cansancio='".$this->cansancio."',suenio_apnea='".$this->suenio_apnea."',diagnostico_apnea='".$this->diagnostico_apnea."',baja_fecha='".$this->baja_fecha."' WHERE id='".$this->id."'"))
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
          function suenio_adecuado()
          {
               return $this->suenio_adecuado;
          }
          function medicacion_dormir()
          {
               return $this->medicacion_dormir;
          }
          function suenio_nocturno_reparador()
          {
               return $this->suenio_nocturno_reparador;
          }
          function ronca()
          {
               return $this->ronca;
          }
          function cansancio()
          {
               return $this->cansancio;
          }
          function suenio_apnea()
          {
               return $this->suenio_apnea;
          }
          function diagnostico_apnea()
          {
               return $this->diagnostico_apnea;
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
          function suenio_adecuado_asigna($campo)
          {
               $this->suenio_adecuado=$campo;
               
          }
          function medicacion_dormir_asigna($campo)
          {
               $this->medicacion_dormir=$campo;
               
          }
          function suenio_nocturno_reparador_asigna($campo)
          {
               $this->suenio_nocturno_reparador=$campo;
               
          }
          function ronca_asigna($campo)
          {
               $this->ronca=$campo;
               
          }
          function cansancio_asigna($campo)
          {
               $this->cansancio=$campo;
               
          }
          function suenio_apnea_asigna($campo)
          {
               $this->suenio_apnea=$campo;
               
          }
          function diagnostico_apnea_asigna($campo)
          {
               $this->diagnostico_apnea=$campo;
               
          }
          function baja_fecha_asigna($campo)
          {
               $this->baja_fecha=$campo;
               
          }
          
          
          
	      function foranea_id_encuesta($id_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_descanso WHERE id_encuesta=$id_encuesta");				
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