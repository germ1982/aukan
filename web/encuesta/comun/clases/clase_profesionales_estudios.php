<?
      class clase_profesionales_estudios       
      {
	  var $id = '';
          var $idprofesional = '';
          var $idnomenclador = '';
          
      
      
      
         function clase_profesionales_estudios($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM profesionales_estudios WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idnomenclador=$arreglo['idnomenclador'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO profesionales_estudios(idprofesional,idnomenclador) VALUES('".$this->idprofesional."','".$this->idnomenclador."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE profesionales_estudios SET idprofesional='".$this->idprofesional."',idnomenclador='".$this->idnomenclador."' WHERE id='".$this->id."'"))
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
          function idnomenclador()
          {
               return $this->idnomenclador;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idnomenclador_asigna($campo)
          {
               $this->idnomenclador=$campo;
               
          }
          
          
          
      
}
?>