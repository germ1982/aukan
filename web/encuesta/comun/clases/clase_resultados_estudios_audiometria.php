<?
      class clase_resultados_estudios_audiometria       
      {
	  var $id = '';
          var $idresultado_estudio = '';
          var $od_500 = '';
          var $oi_500 = '';
          var $od_1000 = '';
          var $oi_1000 = '';
          var $od_2000 = '';
          var $oi_2000 = '';
          var $od_4000 = '';
          var $oi_4000 = '';
          var $od_total = '';
          var $oi_total = '';
          var $antiguedad_puesto = '';
          var $hora_audiometria = '';
          var $hora_inicio_jornada_laboral = '';
          var $audiometria_realizada = '';
          var $hora_inicio_exposicion = '';
          var $expuesto_ruido = '';
          var $exposicion_ruido_diaria = '';
          var $otoscopia = '';
          
      
      var $arreglo_foraneo_idresultado_estudio='';
      	     
      
         function clase_resultados_estudios_audiometria($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM resultados_estudios_audiometria WHERE id=$id");
      	     self::asignar($bd->registro());      	           	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->idresultado_estudio=$arreglo['idresultado_estudio'];
      	     $this->od_500=$arreglo['od_500'];
      	     $this->oi_500=$arreglo['oi_500'];
      	     $this->od_1000=$arreglo['od_1000'];
      	     $this->oi_1000=$arreglo['oi_1000'];
      	     $this->od_2000=$arreglo['od_2000'];
      	     $this->oi_2000=$arreglo['oi_2000'];
      	     $this->od_4000=$arreglo['od_4000'];
      	     $this->oi_4000=$arreglo['oi_4000'];
      	     $this->od_total=$arreglo['od_total'];
      	     $this->oi_total=$arreglo['oi_total'];
      	     $this->antiguedad_puesto=$arreglo['antiguedad_puesto'];
      	     $this->hora_audiometria=$arreglo['hora_audiometria'];
      	     $this->hora_inicio_jornada_laboral=$arreglo['hora_inicio_jornada_laboral'];
      	     $this->audiometria_realizada=$arreglo['audiometria_realizada'];
      	     $this->hora_inicio_exposicion=$arreglo['hora_inicio_exposicion'];
      	     $this->expuesto_ruido=$arreglo['expuesto_ruido'];
      	     $this->exposicion_ruido_diaria=$arreglo['exposicion_ruido_diaria'];
      	     $this->otoscopia=$arreglo['otoscopia'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
          $bd->select("SELECT * FROM resultados_estudios_audiometria WHERE idresultado_estudio=".$this->idresultado_estudio);
          $arreglo = $bd->registro();
      	  if ($arreglo['id']==0 || $arreglo['id']=='' ) {
      	      if ($bd->select("INSERT INTO resultados_estudios_audiometria(idresultado_estudio,od_500,oi_500,od_1000,oi_1000,od_2000,oi_2000,od_4000,oi_4000,od_total,oi_total,antiguedad_puesto,hora_audiometria,hora_inicio_jornada_laboral,audiometria_realizada,hora_inicio_exposicion,expuesto_ruido,exposicion_ruido_diaria,otoscopia) VALUES('".$this->idresultado_estudio."','".$this->od_500."','".$this->oi_500."','".$this->od_1000."','".$this->oi_1000."','".$this->od_2000."','".$this->oi_2000."','".$this->od_4000."','".$this->oi_4000."','".$this->od_total."','".$this->oi_total."','".$this->antiguedad_puesto."','".$this->hora_audiometria."','".$this->hora_inicio_jornada_laboral."','".$this->audiometria_realizada."','".$this->hora_inicio_exposicion."','".$this->expuesto_ruido."','".$this->exposicion_ruido_diaria."','".$this->otoscopia."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE resultados_estudios_audiometria SET idresultado_estudio='".$this->idresultado_estudio."',od_500='".$this->od_500."',oi_500='".$this->oi_500."',od_1000='".$this->od_1000."',oi_1000='".$this->oi_1000."',od_2000='".$this->od_2000."',oi_2000='".$this->oi_2000."',od_4000='".$this->od_4000."',oi_4000='".$this->oi_4000."',od_total='".$this->od_total."',oi_total='".$this->oi_total."',antiguedad_puesto='".$this->antiguedad_puesto."',hora_audiometria='".$this->hora_audiometria."',hora_inicio_jornada_laboral='".$this->hora_inicio_jornada_laboral."',audiometria_realizada='".$this->audiometria_realizada."',hora_inicio_exposicion='".$this->hora_inicio_exposicion."',expuesto_ruido='".$this->expuesto_ruido."',exposicion_ruido_diaria='".$this->exposicion_ruido_diaria."',otoscopia='".$this->otoscopia."' WHERE id='".$arreglo['id']."'"))
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
          function idresultado_estudio()
          {
               return $this->idresultado_estudio;
          }
          function od_500()
          {
               return $this->od_500;
          }
          function oi_500()
          {
               return $this->oi_500;
          }
          function od_1000()
          {
               return $this->od_1000;
          }
          function oi_1000()
          {
               return $this->oi_1000;
          }
          function od_2000()
          {
               return $this->od_2000;
          }
          function oi_2000()
          {
               return $this->oi_2000;
          }
          function od_4000()
          {
               return $this->od_4000;
          }
          function oi_4000()
          {
               return $this->oi_4000;
          }
          function od_total()
          {
               return $this->od_total;
          }
          function oi_total()
          {
               return $this->oi_total;
          }
          function antiguedad_puesto()
          {
               return $this->antiguedad_puesto;
          }
          function hora_audiometria()
          {
               return $this->hora_audiometria;
          }
          function hora_inicio_jornada_laboral()
          {
               return $this->hora_inicio_jornada_laboral;
          }
          function audiometria_realizada()
          {
               return $this->audiometria_realizada;
          }
          function hora_inicio_exposicion()
          {
               return $this->hora_inicio_exposicion;
          }
          function expuesto_ruido()
          {
               return $this->expuesto_ruido;
          }
          function exposicion_ruido_diaria()
          {
               return $this->exposicion_ruido_diaria;
          }
          function otoscopia()
          {
               return $this->otoscopia;
          }
          
          
          
      	     function arreglo_foraneo_idresultado_estudio()
             {
                 return $this->arreglo_foraneo_idresultado_estudio;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idresultado_estudio_asigna($campo)
          {
               $this->idresultado_estudio=$campo;
               
          }
          function od_500_asigna($campo)
          {
               $this->od_500=$campo;
               
          }
          function oi_500_asigna($campo)
          {
               $this->oi_500=$campo;
               
          }
          function od_1000_asigna($campo)
          {
               $this->od_1000=$campo;
               
          }
          function oi_1000_asigna($campo)
          {
               $this->oi_1000=$campo;
               
          }
          function od_2000_asigna($campo)
          {
               $this->od_2000=$campo;
               
          }
          function oi_2000_asigna($campo)
          {
               $this->oi_2000=$campo;
               
          }
          function od_4000_asigna($campo)
          {
               $this->od_4000=$campo;
               
          }
          function oi_4000_asigna($campo)
          {
               $this->oi_4000=$campo;
               
          }
          function od_total_asigna($campo)
          {
               $this->od_total=$campo;
               
          }
          function oi_total_asigna($campo)
          {
               $this->oi_total=$campo;
               
          }
          function antiguedad_puesto_asigna($campo)
          {
               $this->antiguedad_puesto=$campo;
               
          }
          function hora_audiometria_asigna($campo)
          {
               $this->hora_audiometria=$campo;
               
          }
          function hora_inicio_jornada_laboral_asigna($campo)
          {
               $this->hora_inicio_jornada_laboral=$campo;
               
          }
          function audiometria_realizada_asigna($campo)
          {
               $this->audiometria_realizada=$campo;
               
          }
          function hora_inicio_exposicion_asigna($campo)
          {
               $this->hora_inicio_exposicion=$campo;
               
          }
          function expuesto_ruido_asigna($campo)
          {
               $this->expuesto_ruido=$campo;
               
          }
          function exposicion_ruido_diaria_asigna($campo)
          {
               $this->exposicion_ruido_diaria=$campo;
               
          }
          function otoscopia_asigna($campo)
          {
               $this->otoscopia=$campo;
               
          }
          
          
          function foranea_idresultado_estudio($idresultado_estudio)
              {
	          $bd = new baseDatos();
		  $bd->Conectarse();		    
		  $bd->select("SELECT * FROM resultados_estudios_audiometria WHERE idresultado_estudio=$idresultado_estudio");				
		  self::asignar($bd->registro());                              		
	      }
      
}
?>