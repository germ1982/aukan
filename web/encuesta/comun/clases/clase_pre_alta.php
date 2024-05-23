<?
      class clase_pre_alta       
      {
	  var $idepisodio = '';
          var $idprofesional = '';
          var $fecha = '';
          var $diagnostico = '';
          var $hora = '';
          
      
      
      
         function clase_pre_alta($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pre_alta WHERE idepisodio=$id");
      	     $arreglo=$bd->registro();
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->diagnostico=$arreglo['diagnostico'];
      	     $this->hora=$arreglo['hora'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idepisodio==0 || $this->idepisodio=='' ) {
      	      if ($bd->select("INSERT INTO pre_alta(idprofesional,fecha,diagnostico,hora) VALUES('".$this->idprofesional."','".$this->fecha."','".$this->diagnostico."','".$this->hora."')"))
      	      {
      	          $this->idepisodio=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pre_alta SET idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',diagnostico='".$this->diagnostico."',hora='".$this->hora."' WHERE idepisodio='".$this->idepisodio."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function diagnostico()
          {
               return $this->diagnostico;
          }
          function hora()
          {
               return $this->hora;
          }
          
          
          
      
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function diagnostico_asigna($campo)
          {
               $this->diagnostico=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          
          
          
      
}
?>