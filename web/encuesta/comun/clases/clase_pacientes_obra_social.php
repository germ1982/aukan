<?
      class clase_pacientes_obra_social       
      {
	  var $idobra_social = '';
          var $idpaciente = '';
          
      
      
      
         function clase_pacientes_obra_social($idpaciente)
         {
         	$bd = new baseDatos();
         	$bd->Conectarse();
         	$bd->select("SELECT * FROM obra_social WHERE idpaciente=$idpaciente");
         	$arreglo = $bd->registro();
            self::asigna($arreglo);
         }
         function asigna($arreglo)
         {
         	 $this->idpaciente = $arreglo['idpaciente'];
         	 $this->idobra_social = $arreglo['idobra_social'];
         }
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idpaciente==0 || $this->idpaciente=='' ) {
      	      if ($bd->select("INSERT INTO obra_social() VALUES()"))
      	      {
      	          $this->idpaciente=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE obra_social SET  WHERE idpaciente='".$this->idpaciente."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idobra_social()
          {
               return $this->idobra_social;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          
          
          
      
          function idobra_social_asigna($campo)
          {
               $this->idobra_social=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          
          
          
      
}
?>