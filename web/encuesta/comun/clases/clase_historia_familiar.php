<?
      class clase_historia_familiar       
      {
	  var $idpersona = '';
          var $idpadre = '';
          var $idmadre = '';
          
      
      
      
         function clase_historia_familiar($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM historia_familiar WHERE idpersona=$id");
      	     $arreglo=$bd->registro();
      	     $this->idpersona=$arreglo['idpersona'];
      	     $this->idpadre=$arreglo['idpadre'];
      	     $this->idmadre=$arreglo['idmadre'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  $query="SELECT * FROM  historia_familiar WHERE  idpersona=".$this->idpersona;
	
		  $bd->select($query);		  
		  if ($bd->numero_filas() == 0 || $bd->numero_filas() == '')
		  {
      	      if ($bd->select("INSERT INTO historia_familiar(idpersona,idpadre,idmadre) VALUES('".$this->idpersona."','".$this->idpadre."','".$this->idmadre."')"))
      	      {
      	          //$this->idpersona=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE historia_familiar SET idpadre='".$this->idpadre."',idmadre='".$this->idmadre."' WHERE idpersona='".$this->idpersona."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idpersona()
          {
               return $this->idpersona;
          }
          function idpadre()
          {
               return $this->idpadre;
          }
          function idmadre()
          {
               return $this->idmadre;
          }
          
          
          
      
          function idpersona_asigna($campo)
          {
               $this->idpersona=$campo;
               
          }
          function idpadre_asigna($campo)
          {
               $this->idpadre=$campo;
               
          }
          function idmadre_asigna($campo)
          {
               $this->idmadre=$campo;
               
          }
          
          
          
      
}
?>