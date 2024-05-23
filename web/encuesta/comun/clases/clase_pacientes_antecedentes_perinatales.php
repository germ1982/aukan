<?
      class clase_pacientes_antecedentes_perinatales       
      {
	      var $idpaciente = '';
          var $edad_madre = '';
          var $lactancia_natural = '';          
          var $tipo_embarazo = '';                   
          var $patologia_materna = '';
          var $apgar = '';
          var $pc = '';
          var $talla = '';
          var $peso_nacimiento = '';
          var $peso_actual = '';
          var $edad_gestacional_examen_fisico = '';
          var $liquido_amniotico = '';          
          var $grupo_factor_hijo = '';          
          var $orden_nacimiento = '';
          var $apgar5 = '';
          var $apgar7 = '';
          var $apgar15 = '';
          var $tiene_o_no_datos = 0;
          var $coombs='';
          
      
      
      
         function clase_pacientes_antecedentes_perinatales($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pacientes_antecedentes_perinatales WHERE idpaciente=$id");
      	     if ($arreglo=$bd->registro())  
      	         $this->tiene_o_no_datos = 1;
      	     else 
      	         $this->tiene_o_no_datos = 0;
      	     self::asignar_datos($arreglo);    	           	     
      	 }
      	 function asignar_datos($arreglo)
      	 {
      	 	 $this->idpaciente=$arreglo['idpaciente'];
      	     $this->edad_madre=$arreglo['edad_madre'];
      	     $this->lactancia_natural=$arreglo['lactancia_natural'];      	     
      	     $this->tipo_embarazo=$arreglo['tipo_embarazo'];      	          	     
      	     $this->patologia_materna=$arreglo['patologia_materna'];
      	     $this->apgar=$arreglo['apgar'];
      	     $this->pc=$arreglo['pc'];
      	     $this->talla=$arreglo['talla'];
      	     $this->peso_nacimiento=$arreglo['peso_nacimiento'];
      	     $this->peso_actual=$arreglo['peso_actual'];
      	     $this->edad_gestacional_examen_fisico=$arreglo['edad_gestacional_examen_fisico'];
      	     $this->liquido_amniotico=$arreglo['liquido_amniotico'];      	     
      	     $this->grupo_factor_hijo=$arreglo['grupo_factor_hijo'];
      	     $this->coombs=$arreglo['coombs'];
      	     $this->orden_nacimiento=$arreglo['orden_nacimiento'];
      	     $this->apgar5=$arreglo['apgar5'];
      	     $this->apgar7=$arreglo['apgar7'];
      	     $this->apgar15=$arreglo['apgar15'];
      	     $this->coombs=$arreglo['coombs'];
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  $query="SELECT * FROM   pacientes_antecedentes_perinatales WHERE  idpaciente=".$this->idpaciente;
	
		  $bd->select($query);
		  $que_es = $bd->registro();		
		  if (count($que_es) == 1 || $que_es == "")
		  { 
      	      if ($bd->select("INSERT INTO pacientes_antecedentes_perinatales(idpaciente,edad_madre,lactancia_natural,tipo_embarazo,
      	      patologia_materna,apgar,pc,talla,peso_nacimiento,peso_actual,edad_gestacional_examen_fisico,liquido_amniotico,
      	      grupo_factor_hijo,orden_nacimiento,apgar5,apgar7,apgar15,coombs) VALUES('".$this->idpaciente."','".$this->edad_madre."',
      	      '".$this->lactancia_natural."','".$this->tipo_embarazo."','".$this->patologia_materna."','".$this->apgar."',
      	      '".$this->pc."','".$this->talla."','".$this->peso_nacimiento."','".$this->peso_actual."',
      	      '".$this->edad_gestacional_examen_fisico."','".$this->liquido_amniotico."','".$this->grupo_factor_hijo."',
      	      '".$this->orden_nacimiento."','".$this->apgar5."','".$this->apgar7."','".$this->apgar15."','".$this->coombs."')"))
      	      {      	          
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pacientes_antecedentes_perinatales SET edad_madre='".$this->edad_madre."',
      	        lactancia_natural='".$this->lactancia_natural."',nacidos_vivos='".$this->nacidos_vivos."',tipo_embarazo='".$this->tipo_embarazo."',
      	        patologia_materna='".$this->patologia_materna."',apgar='".$this->apgar."',pc='".$this->pc."',talla='".$this->talla."',
      	        peso_nacimiento='".$this->peso_nacimiento."',peso_actual='".$this->peso_actual."',
      	        edad_gestacional_examen_fisico='".$this->edad_gestacional_examen_fisico."',liquido_amniotico='".$this->liquido_amniotico."',
      	        grupo_factor_hijo='".$this->grupo_factor_hijo."',orden_nacimiento='".$this->orden_nacimiento."',apgar5='".$this->apgar5."',
      	        apgar7='".$this->apgar7."',apgar15='".$this->apgar15."',coombs='".$this->coombs."' WHERE idpaciente='".$this->idpaciente."'"))
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
          function edad_madre()
          {
               return $this->edad_madre;
          }
          function lactancia_natural()
          {
               return $this->lactancia_natural;
          }          
          function tipo_embarazo()
          {
               return $this->tipo_embarazo;
          }          
          function patologia_materna()
          {
               return $this->patologia_materna;
          }
          function apgar()
          {
               return $this->apgar;
          }
          function pc()
          {
               return $this->pc;
          }
          function talla()
          {
               return $this->talla;
          }
          function peso_nacimiento()
          {
               return $this->peso_nacimiento;
          }
          function peso_actual()
          {
               return $this->peso_actual;
          }
          function edad_gestacional_examen_fisico()
          {
               return $this->edad_gestacional_examen_fisico;
          }
          function liquido_amniotico()
          {
               return $this->liquido_amniotico;
          }          
          function grupo_factor_hijo()
          {
               return $this->grupo_factor_hijo;
          }          
          function orden_nacimiento()
          {
               return $this->orden_nacimiento;
          }
          function apgar5()
          {
               return $this->apgar5;
          }
          function apgar7()
          {
               return $this->apgar7;
          }
          function apgar15()
          {
               return $this->apgar15;
          }
      	  function tiene_o_no_datos()
          {
               return $this->tiene_o_no_datos;
          }
      	  function coombs()
          {
               return $this->coombs;
          }
          
      
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function edad_madre_asigna($campo)
          {
               $this->edad_madre=$campo;
               
          }
          function lactancia_natural_asigna($campo)
          {
               $this->lactancia_natural=$campo;
               
          }          
          function tipo_embarazo_asigna($campo)
          {
               $this->tipo_embarazo=$campo;
               
          }         
          function patologia_materna_asigna($campo)
          {
               $this->patologia_materna=$campo;
               
          }
          function apgar_asigna($campo)
          {
               $this->apgar=$campo;
               
          }
          function pc_asigna($campo)
          {
               $this->pc=$campo;
               
          }
          function talla_asigna($campo)
          {
               $this->talla=$campo;
               
          }
          function peso_nacimiento_asigna($campo)
          {
               $this->peso_nacimiento=$campo;
               
          }
          function peso_actual_asigna($campo)
          {
               $this->peso_actual=$campo;
               
          }
          function edad_gestacional_examen_fisico_asigna($campo)
          {
               $this->edad_gestacional_examen_fisico=$campo;
               
          }
          function liquido_amniotico_asigna($campo)
          {
               $this->liquido_amniotico=$campo;
               
          }         
          function grupo_factor_hijo_asigna($campo)
          {
               $this->grupo_factor_hijo=$campo;
               
          }          
          function orden_nacimiento_asigna($campo)
          {
               $this->orden_nacimiento=$campo;
               
          }
          function apgar5_asigna($campo)
          {
               $this->apgar5=$campo;
               
          }
          function apgar7_asigna($campo)
          {
               $this->apgar7=$campo;
               
          }
          function apgar15_asigna($campo)
          {
               $this->apgar15=$campo;
               
          }     
     	 function coombs_asigna($campo)
          {
               $this->coombs=$campo;
          }                             
}
?>