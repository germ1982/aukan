<?
      class clase_encuesta_general_habitos       
      {
	  var $id = '';
          var $id_encuesta = '';
          var $fecha = '';
          var $genero = '';
          var $con_quien_vive = '';
          var $factores_riesgo_cardiovascular = '';
          var $habitos = '';
          var $actividad_fisica_planificada = '';
          var $practica_deporte = '';
          var $tiempo_semanal_actividad_fisica = '';
          var $historial_actividad_fisica = '';
          var $motivo_no_actividad_fisica = '';
          var $deporte = '';
          var $baja_fecha = '';
          
      
      var $arreglo_foraneo_id_encuesta='';
      	     
      
         function clase_encuesta_general_habitos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM encuesta_general_habitos WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_encuesta=$arreglo['id_encuesta'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->genero=$arreglo['genero'];
      	     $this->con_quien_vive=$arreglo['con_quien_vive'];
      	     $this->factores_riesgo_cardiovascular=$arreglo['factores_riesgo_cardiovascular'];
      	     $this->habitos=$arreglo['habitos'];
      	     $this->actividad_fisica_planificada=$arreglo['actividad_fisica_planificada'];
      	     $this->practica_deporte=$arreglo['practica_deporte'];
      	     $this->tiempo_semanal_actividad_fisica=$arreglo['tiempo_semanal_actividad_fisica'];
      	     $this->historial_actividad_fisica=$arreglo['historial_actividad_fisica'];
      	     $this->motivo_no_actividad_fisica=$arreglo['motivo_no_actividad_fisica'];
      	     $this->deporte=$arreglo['deporte'];
      	     $this->baja_fecha=$arreglo['baja_fecha'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO encuesta_general_habitos(id_encuesta,fecha,genero,con_quien_vive,factores_riesgo_cardiovascular,habitos,actividad_fisica_planificada,practica_deporte,tiempo_semanal_actividad_fisica,historial_actividad_fisica,motivo_no_actividad_fisica,deporte,baja_fecha) VALUES('".$this->id_encuesta."','".$this->fecha."','".$this->genero."','".$this->con_quien_vive."','".$this->factores_riesgo_cardiovascular."','".$this->habitos."','".$this->actividad_fisica_planificada."','".$this->practica_deporte."','".$this->tiempo_semanal_actividad_fisica."','".$this->historial_actividad_fisica."','".$this->motivo_no_actividad_fisica."','".$this->deporte."','".$this->baja_fecha."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE encuesta_general_habitos SET id_encuesta='".$this->id_encuesta."',fecha='".$this->fecha."',genero='".$this->genero."',con_quien_vive='".$this->con_quien_vive."',factores_riesgo_cardiovascular='".$this->factores_riesgo_cardiovascular."',habitos='".$this->habitos."',actividad_fisica_planificada='".$this->actividad_fisica_planificada."',practica_deporte='".$this->practica_deporte."',tiempo_semanal_actividad_fisica='".$this->tiempo_semanal_actividad_fisica."',historial_actividad_fisica='".$this->historial_actividad_fisica."',motivo_no_actividad_fisica='".$this->motivo_no_actividad_fisica."',deporte='".$this->deporte."',baja_fecha='".$this->baja_fecha."' WHERE id='".$this->id."'"))
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
          function genero()
          {
               return $this->genero;
          }
          function con_quien_vive()
          {
               return $this->con_quien_vive;
          }
          function factores_riesgo_cardiovascular()
          {
               return $this->factores_riesgo_cardiovascular;
          }
          function habitos()
          {
               return $this->habitos;
          }
          function actividad_fisica_planificada()
          {
               return $this->actividad_fisica_planificada;
          }
          function practica_deporte()
          {
               return $this->practica_deporte;
          }
          function tiempo_semanal_actividad_fisica()
          {
               return $this->tiempo_semanal_actividad_fisica;
          }
          function historial_actividad_fisica()
          {
               return $this->historial_actividad_fisica;
          }
          function motivo_no_actividad_fisica()
          {
               return $this->motivo_no_actividad_fisica;
          }
          function deporte()
          {
               return $this->deporte;
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
          function genero_asigna($campo)
          {
               $this->genero=$campo;
               
          }
          function con_quien_vive_asigna($campo)
          {
               $this->con_quien_vive=$campo;
               
          }
          function factores_riesgo_cardiovascular_asigna($campo)
          {
               $this->factores_riesgo_cardiovascular=$campo;
               
          }
          function habitos_asigna($campo)
          {
               $this->habitos=$campo;
               
          }
          function actividad_fisica_planificada_asigna($campo)
          {
               $this->actividad_fisica_planificada=$campo;
               
          }
          function practica_deporte_asigna($campo)
          {
               $this->practica_deporte=$campo;
               
          }
          function tiempo_semanal_actividad_fisica_asigna($campo)
          {
               $this->tiempo_semanal_actividad_fisica=$campo;
               
          }
          function historial_actividad_fisica_asigna($campo)
          {
               $this->historial_actividad_fisica=$campo;
               
          }
          function motivo_no_actividad_fisica_asigna($campo)
          {
               $this->motivo_no_actividad_fisica=$campo;
               
          }
          function deporte_asigna($campo)
          {
               $this->deporte=$campo;
               
          }
          function baja_fecha_asigna($campo)
          {
               $this->baja_fecha=$campo;
               
          }
          
          
          
	      function foranea_id_encuesta($id_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_general_habitos WHERE id_encuesta=$id_encuesta");				
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