<?
      class clase_pacientes_antecedentes_ginecobstetricos       
      {
	      var $idpaciente = '';
          var $menarca = '';
          var $fecha_fin_ultimo_embarazo = '';
          var $gestas = '';
          var $abortos = '';
          var $partos = '';
          var $cesareas = '';
          var $mayor_peso_rn = '';
          var $rn_menor_2500 = '';
          var $nacidos_vivos = '';
          var $nacidos_vivos_viven = '';
          var $nacidos_vivos_fallecidos_antes_1_semana = '';
          var $nacidos_vivos_fallecidos_despues_1_semana = '';
          var $nacidos_muertos = '';
          var $observaciones = '';
          var $fecha_probable_parto = '';
          var $idprofesional_responsable_parto = '';
          var $edad_gestacional_fum = '';
          var $edad_gestacional_eco = '';
          
      
      
      
         function clase_pacientes_antecedentes_ginecobstetricos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pacientes_antecedentes_ginecobstetricos WHERE idpaciente=$id");
      	     $arreglo=$bd->registro();
      	     $this->idpaciente=$id;
      	     $this->menarca=$arreglo['menarca'];
      	     $this->fecha_fin_ultimo_embarazo=$arreglo['fecha_fin_ultimo_embarazo'];
      	     $this->gestas=$arreglo['gestas'];
      	     $this->abortos=$arreglo['abortos'];
      	     $this->partos=$arreglo['partos'];
      	     $this->cesareas=$arreglo['cesareas'];
      	     $this->mayor_peso_rn=$arreglo['mayor_peso_rn'];
      	     $this->rn_menor_2500=$arreglo['rn_menor_2500'];
      	     $this->nacidos_vivos=$arreglo['nacidos_vivos'];
      	     $this->nacidos_vivos_viven=$arreglo['nacidos_vivos_viven'];
      	     $this->nacidos_vivos_fallecidos_antes_1_semana=$arreglo['nacidos_vivos_fallecidos_antes_1_semana'];
      	     $this->nacidos_vivos_fallecidos_despues_1_semana=$arreglo['nacidos_vivos_fallecidos_despues_1_semana'];
      	     $this->nacidos_muertos=$arreglo['nacidos_muertos'];
      	     $this->observaciones=$arreglo['observaciones'];
      	     $this->fecha_probable_parto=$arreglo['fecha_probable_parto'];
      	     $this->idprofesional_responsable_parto=$arreglo['idprofesional_responsable_parto'];
      	     $this->edad_gestacional_fum=$arreglo['edad_gestacional_fum'];
      	     $this->edad_gestacional_eco=$arreglo['edad_gestacional_eco'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  $query="SELECT * FROM  pacientes_antecedentes_ginecobstetricos WHERE  idpaciente=".$this->idpaciente;
	
		  $bd->select($query);
		  $que_es = $bd->registro();		
		  if (count($que_es) == 1 || $que_es == "")
		  { 
      	      if ($bd->select("INSERT INTO pacientes_antecedentes_ginecobstetricos(idpaciente,menarca,fecha_fin_ultimo_embarazo,gestas,abortos,partos,cesareas,mayor_peso_rn,rn_menor_2500,nacidos_vivos,nacidos_vivos_viven,nacidos_vivos_fallecidos_antes_1_semana,nacidos_vivos_fallecidos_despues_1_semana,nacidos_muertos,observaciones,fecha_probable_parto,idprofesional_responsable_parto,edad_gestacional_fum,edad_gestacional_eco) VALUES('".$this->idpaciente."','".$this->menarca."','".$this->fecha_fin_ultimo_embarazo."','".$this->gestas."','".$this->abortos."','".$this->partos."','".$this->cesareas."','".$this->mayor_peso_rn."','".$this->rn_menor_2500."','".$this->nacidos_vivos."','".$this->nacidos_vivos_viven."','".$this->nacidos_vivos_fallecidos_antes_1_semana."','".$this->nacidos_vivos_fallecidos_despues_1_semana."','".$this->nacidos_muertos."','".$this->observaciones."','".$this->fecha_probable_parto."','".$this->idprofesional_responsable_parto."','".$this->edad_gestacional_fum."','".$this->edad_gestacional_eco."')"))
      	      {
      	          //$this->idpaciente=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pacientes_antecedentes_ginecobstetricos SET menarca='".$this->menarca."',fecha_fin_ultimo_embarazo='".$this->fecha_fin_ultimo_embarazo."',gestas='".$this->gestas."',abortos='".$this->abortos."',partos='".$this->partos."',cesareas='".$this->cesareas."',mayor_peso_rn='".$this->mayor_peso_rn."',rn_menor_2500='".$this->rn_menor_2500."',nacidos_vivos='".$this->nacidos_vivos."',nacidos_vivos_viven='".$this->nacidos_vivos_viven."',nacidos_vivos_fallecidos_antes_1_semana='".$this->nacidos_vivos_fallecidos_antes_1_semana."',nacidos_vivos_fallecidos_despues_1_semana='".$this->nacidos_vivos_fallecidos_despues_1_semana."',nacidos_muertos='".$this->nacidos_muertos."',observaciones='".$this->observaciones."',fecha_probable_parto='".$this->fecha_probable_parto."',idprofesional_responsable_parto='".$this->idprofesional_responsable_parto."',edad_gestacional_fum='".$this->edad_gestacional_fum."',edad_gestacional_eco='".$this->edad_gestacional_eco."' WHERE idpaciente='".$this->idpaciente."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function menarca()
          {
               return $this->menarca;
          }
          function fecha_fin_ultimo_embarazo()
          {
               return $this->fecha_fin_ultimo_embarazo;
          }
          function gestas()
          {
               return $this->gestas;
          }
          function abortos()
          {
               return $this->abortos;
          }
          function partos()
          {
               return $this->partos;
          }
          function cesareas()
          {
               return $this->cesareas;
          }
          function mayor_peso_rn()
          {
               return $this->mayor_peso_rn;
          }
          function rn_menor_2500()
          {
               return $this->rn_menor_2500;
          }
          function nacidos_vivos()
          {
               return $this->nacidos_vivos;
          }
          function nacidos_vivos_viven()
          {
               return $this->nacidos_vivos_viven;
          }
          function nacidos_vivos_fallecidos_antes_1_semana()
          {
               return $this->nacidos_vivos_fallecidos_antes_1_semana;
          }
          function nacidos_vivos_fallecidos_despues_1_semana()
          {
               return $this->nacidos_vivos_fallecidos_despues_1_semana;
          }
          function nacidos_muertos()
          {
               return $this->nacidos_muertos;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function fecha_probable_parto()
          {
               return $this->fecha_probable_parto;
          }
          function idprofesional_responsable_parto()
          {
               return $this->idprofesional_responsable_parto;
          }
          function edad_gestacional_fum()
          {
               return $this->edad_gestacional_fum;
          }
          function edad_gestacional_eco()
          {
               return $this->edad_gestacional_eco;
          }
          
          
          
      
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function menarca_asigna($campo)
          {
               $this->menarca=$campo;
               
          }
          function fecha_fin_ultimo_embarazo_asigna($campo)
          {
               $this->fecha_fin_ultimo_embarazo=$campo;
               
          }
          function gestas_asigna($campo)
          {
               $this->gestas=$campo;
               
          }
          function abortos_asigna($campo)
          {
               $this->abortos=$campo;
               
          }
          function partos_asigna($campo)
          {
               $this->partos=$campo;
               
          }
          function cesareas_asigna($campo)
          {
               $this->cesareas=$campo;
               
          }
          function mayor_peso_rn_asigna($campo)
          {
               $this->mayor_peso_rn=$campo;
               
          }
          function rn_menor_2500_asigna($campo)
          {
               $this->rn_menor_2500=$campo;
               
          }
          function nacidos_vivos_asigna($campo)
          {
               $this->nacidos_vivos=$campo;
               
          }
          function nacidos_vivos_viven_asigna($campo)
          {
               $this->nacidos_vivos_viven=$campo;
               
          }
          function nacidos_vivos_fallecidos_antes_1_semana_asigna($campo)
          {
               $this->nacidos_vivos_fallecidos_antes_1_semana=$campo;
               
          }
          function nacidos_vivos_fallecidos_despues_1_semana_asigna($campo)
          {
               $this->nacidos_vivos_fallecidos_despues_1_semana=$campo;
               
          }
          function nacidos_muertos_asigna($campo)
          {
               $this->nacidos_muertos=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function fecha_probable_parto_asigna($campo)
          {
               $this->fecha_probable_parto=$campo;
               
          }
          function idprofesional_responsable_parto_asigna($campo)
          {
               $this->idprofesional_responsable_parto=$campo;
               
          }
          function edad_gestacional_fum_asigna($campo)
          {
               $this->edad_gestacional_fum=$campo;
               
          }
          function edad_gestacional_eco_asigna($campo)
          {
               $this->edad_gestacional_eco=$campo;
               
          }
          
          
          
      
}
?>