<?
      class clase_facturacion_parametros       
      {
	  	  var $idosocial = '';
          var $cama_uti = '';
          var $cama_sala = '';
          var $cama_neo = '';
          var $cama_utim = '';
          var $monitoreo_fetal = '';
          var $partera = '';
          var $nursery = '';
          var $pediatra = '';
          var $parto = '';
          var $cesarea = '';
          var $anestesia_general = '';
          var $descartables_uti = '';
          var $descartables_utim = '';
          var $descartables_sala = '';
          var $atencion_clinica_medica = '';
          var $arm_uti = '';
          var $arm_utim = '';
          var $arm_neo = '';
          var $bisturi_armonico = '';
          var $arco_c = '';
          var $video = '';
          var $aislamiento = '';
          var $kinesiologia_evoluciones = '';
          var $uso_torre_video = '';
          
      
      
      
         function clase_facturacion_parametros($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();      	     
      	     $bd->select("SELECT * FROM facturacion_parametros WHERE idosocial=$id");
      	     $arreglo=$bd->registro();
      	     if ($arreglo['idosocial'] == '' && $arreglo['idosocial'] == 0)
      	     {
      	     	$bd->select("SELECT * FROM facturacion_parametros WHERE idosocial=-1");
      	     	$arreglo=$bd->registro();
      	     }      	     
      	     self::asignar($arreglo);
      	 }
      	 function asignar($arreglo)
      	 {
      	 	 $this->idosocial=$arreglo['idosocial'];
      	     $this->cama_uti=$arreglo['cama_uti'];
      	     $this->cama_sala=$arreglo['cama_sala'];
      	     $this->cama_neo=$arreglo['cama_neo'];
      	     $this->cama_utim=$arreglo['cama_utim'];
      	     $this->monitoreo_fetal=$arreglo['monitoreo_fetal'];
      	     $this->partera=$arreglo['partera'];
      	     $this->nursery=$arreglo['nursery'];
      	     $this->pediatra=$arreglo['pediatra'];
      	     $this->parto=$arreglo['parto'];
      	     $this->cesarea=$arreglo['cesarea'];
      	     $this->anestesia_general=$arreglo['anestesia_general'];
      	     $this->descartables_uti=$arreglo['descartables_uti'];
      	     $this->descartables_utim=$arreglo['descartables_utim'];
      	     $this->descartables_sala=$arreglo['descartables_sala'];
      	     $this->atencion_clinica_medica=$arreglo['atencion_clinica_medica'];
      	     $this->arm_uti=$arreglo['arm_uti'];
      	     $this->arm_utim=$arreglo['arm_utim'];
      	     $this->arm_neo=$arreglo['arm_neo'];
      	     $this->bisturi_armonico=$arreglo['bisturi_armonico'];
      	     $this->arco_c=$arreglo['arco_c'];
      	     $this->video=$arreglo['video'];
      	     $this->aislamiento=$arreglo['aislamiento'];
      	     $this->kinesiologia_evoluciones=$arreglo['kinesiologia_evoluciones'];
      	     $this->uso_torre_video=$arreglo['uso_torre_video'];
      	 }       
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idosocial==0 || $this->idosocial=='' ) {
      	      if ($bd->select("INSERT INTO facturacion_parametros(cama_uti,cama_sala,cama_neo,cama_utim,monitoreo_fetal,partera,nursery,pediatra,parto,cesarea,anestesia_general,descartables_uti,descartables_utim,descartables_sala,atencion_clinica_medica,arm_uti,arm_utim,arm_neo,bisturi_armonico,arco_c,video,kinesiologia_evoluciones,uso_torre_video) 
      	      VALUES('".$this->cama_uti."','".$this->cama_sala."','".$this->cama_neo."','".$this->cama_utim."','".$this->monitoreo_fetal."','".$this->partera."','".$this->nursery."','".$this->pediatra."','".$this->parto."','".$this->cesarea."','".$this->anestesia_general."','".$this->descartables_uti."','".$this->descartables_utim."','".$this->descartables_sala."','".$this->atencion_clinica_medica."','".$this->arm_uti."','".$this->arm_utim."','".$this->arm_neo."','".$this->bisturi_armonico."','".$this->arco_c."','".$this->video."','".$this->kinesiologia_evoluciones."','".$this->uso_torre_video."')"))
      	      {
      	          $this->idosocial=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE facturacion_parametros SET cama_uti='".$this->cama_uti."',cama_sala='".$this->cama_sala."',cama_neo='".$this->cama_neo."',cama_utim='".$this->cama_utim."',monitoreo_fetal='".$this->monitoreo_fetal."',partera='".$this->partera."',nursery='".$this->nursery."',pediatra='".$this->pediatra."',parto='".$this->parto."',cesarea='".$this->cesarea."',anestesia_general='".$this->anestesia_general."',descartables_uti='".$this->descartables_uti."',
      	        descartables_utim='".$this->descartables_utim."',descartables_sala='".$this->descartables_sala."',
      	        atencion_clinica_medica='".$this->atencion_clinica_medica."',arm_uti='".$this->arm_uti."',
      	        arm_utim='".$this->arm_utim."',arm_neo='".$this->arm_neo."',
      	        bisturi_armonico='".$this->bisturi_armonico."',arco_c='".$this->arco_c."',video='".$this->video."',
      	        kinesiologia_evoluciones='".$this->kinesiologia_evoluciones."',uso_torre_video='".$this->uso_torre_video."' 
      	        WHERE idosocial='".$this->idosocial."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idosocial()
          {
               return $this->idosocial;
          }
          function cama_uti()
          {
               return $this->cama_uti;
          }
          function cama_sala()
          {
               return $this->cama_sala;
          }
          function cama_neo()
          {
               return $this->cama_neo;
          }
          function cama_utim()
          {
               return $this->cama_utim;
          }
          function monitoreo_fetal()
          {
               return $this->monitoreo_fetal;
          }
          function partera()
          {
               return $this->partera;
          }
          function nursery()
          {
               return $this->nursery;
          }
          function pediatra()
          {
               return $this->pediatra;
          }
          function parto()
          {
               return $this->parto;
          }
          function cesarea()
          {
               return $this->cesarea;
          }
          function anestesia_general()
          {
               return $this->anestesia_general;
          }
          function descartables_uti()
          {
               return $this->descartables_uti;
          }
          function descartables_utim()
          {
               return $this->descartables_utim;
          }
          function descartables_sala()
          {
               return $this->descartables_sala;
          }
          function atencion_clinica_medica()
          {
               return $this->atencion_clinica_medica;
          }
          function arm_uti()
          {
               return $this->arm_uti;
          }
          function arm_utim()
          {
               return $this->arm_utim;
          }
          function arm_neo()
          {
               return $this->arm_neo;
          }
          function bisturi_armonico()
          {
               return $this->bisturi_armonico;
          }
      	  function arco_c()
          {
               return $this->arco_c;
          }
      	  function video()
          {
               return $this->video;
          }
          function aislamiento()
          {
          	   return $this->aislamiento;
          }
          function kinesiologia_evoluciones()
          {
          	   return $this->kinesiologia_evoluciones;
          }
          function uso_torre_video()
          {
          	   return $this->uso_torre_video;
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function cama_uti_asigna($campo)
          {
               $this->cama_uti=$campo;
               
          }
          function cama_sala_asigna($campo)
          {
               $this->cama_sala=$campo;
               
          }
          function cama_neo_asigna($campo)
          {
               $this->cama_neo=$campo;
               
          }
          function cama_utim_asigna($campo)
          {
               $this->cama_utim=$campo;
               
          }
          function monitoreo_fetal_asigna($campo)
          {
               $this->monitoreo_fetal=$campo;
               
          }
          function partera_asigna($campo)
          {
               $this->partera=$campo;
               
          }
          function nursery_asigna($campo)
          {
               $this->nursery=$campo;
               
          }
          function pediatra_asigna($campo)
          {
               $this->pediatra=$campo;
               
          }
          function parto_asigna($campo)
          {
               $this->parto=$campo;
               
          }
          function cesarea_asigna($campo)
          {
               $this->cesarea=$campo;
               
          }
          function anestesia_general_asigna($campo)
          {
               $this->anestesia_general=$campo;
               
          }
          function descartables_uti_asigna($campo)
          {
               $this->descartables_uti=$campo;
               
          }
          function descartables_utim_asigna($campo)
          {
               $this->descartables_utim=$campo;
               
          }
          function descartables_sala_asigna($campo)
          {
               $this->descartables_sala=$campo;
               
          }
          function atencion_clinica_medica_asigna($campo)
          {
               $this->atencion_clinica_medica=$campo;
               
          }
          function arm_uti_asigna($campo)
          {
               $this->arm_uti=$campo;
               
          }
          function arm_utim_asigna($campo)
          {
               $this->arm_utim=$campo;
               
          }
          function arm_neo_asigna($campo)
          {
               $this->arm_neo=$campo;
               
          }
          function bisturi_armonico_asigna($campo)
          {
               $this->bisturi_armonico=$campo;
               
          }
          function arco_c_asigna($campo)
          {
               $this->arco_c=$campo;
          }
      	  function video_asigna($campo)
          {
               $this->video=$campo;
          }
          function aislamiento_asigna($campo)
          {
          	   $this->aislamiento = $campo;
          }
          function kinesiologia_evoluciones_asigna($campo)
          {
          	  $this->kinesiologia_evoluciones = $campo;
          }
          function uso_torre_video_asigna($campo)
          {
          	  $this->uso_torre_video = $campo;
          }
          function reglas($idosocial,$codigo)
          {
          	  if (($idosocial != 11 && $idosocial != 128) && ($codigo == '400101'))          	  
          	  	  return 1;
          	  if (($idosocial != 11 && $idosocial != 128) && ($codigo == '410101'))
          	      return 1;
          	  else 
          	      return 0;
          }
          
      
}
?>