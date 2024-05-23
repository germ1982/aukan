<?
      class clase_tipos_galenos       
      {
	  var $cod_gal = '';
          var $nombre = '';
          var $codigo_galeno_asociado = '';
          var $que_es = '';
          
      
         function clase_tipos_galenos($tipo_galeno)
      	 {
      	 	 if ($tipo_galeno != '')
      	 	 {
      	 	     $bd = new baseDatos();
      	         $bd->Conectarse();
      	         $bd->select("SELECT * FROM tipos_galenos WHERE cod_gal=$tipo_galeno");
      	         $arreglo = $bd->registro();
      	         self::asigna_campos($arreglo);
      	 	 }      	    
      	 }
         function asigna_campos($arreglo)
         {
         	$this->cod_gal = $arreglo['cod_gal'];
         	$this->nombre  = $arreglo['nombre'];
         	$this->codigo_galeno_asociado = $arreglo['codigo_galeno_asociado'];
         	$this->que_es = $arreglo['que_es'];
         }
      
      
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->cod_gal == '')
      	  {
      	      if ($bd->select("INSERT INTO tipos_galenos(cod_gal,nombre,codigo_galeno_asociado,que_es) VALUES('".$this->cod_gal."','".$this->nombre."','".$this->codigo_galeno_asociado."','".$this->que_es."')"))
      	      {
      	          
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE tipos_galenos SET cod_gal='".$this->cod_gal."',nombre='".$this->nombre."',codigo_galeno_asociado='".$this->codigo_galeno_asociado."',que_es='".$this->que_es."' WHERE "))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function cod_gal()
          {
               return $this->cod_gal;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function codigo_galeno_asociado()
          {
               return $this->codigo_galeno_asociado;
          }
          function que_es()
          {
               return $this->que_es;
          }
          
          
          
      
          function cod_gal_asigna($campo)
          {
               $this->cod_gal=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function codigo_galeno_asociado_asigna($campo)
          {
               $this->codigo_galeno_asociado=$campo;
               
          }
          function que_es_asigna($campo)
          {
               $this->que_es=$campo;
               
          }
          
          
          
      
}
?>