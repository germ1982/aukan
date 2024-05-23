<?
      class clase_tipos_gasto       
      {
	  var $cod_gas = '';
          var $nombre = '';
          var $codigo_gasto_asociado = '';
          var $que_es = '';
          
      
      	 function clase_tipos_gasto($tipo_gasto)
      	 {
      	 	 if ($tipo_gasto != '')
      	 	 {
      	 	     $bd = new baseDatos();
      	         $bd->Conectarse();
      	         $bd->select("SELECT * FROM tipos_gasto WHERE cod_gas=$tipo_gasto");
      	         $arreglo = $bd->registro();
      	         self::asigna_campos($arreglo);
      	 	 }      	    
      	 }
         function asigna_campos($arreglo)
         {
         	$this->cod_gas = $arreglo['cod_gas'];
         	$this->nombre  = $arreglo['nombre'];
         	$this->codigo_gasto_asociado = $arreglo['codigo_gasto_asociado'];
         	$this->que_es = $arreglo['que_es'];
         }
      
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->cod_gas == '')
      	  {
      	      if ($bd->select("INSERT INTO tipos_gasto(cod_gas,nombre,codigo_gasto_asociado,que_es) VALUES('".$this->cod_gas."','".$this->nombre."','".$this->codigo_gasto_asociado."','".$this->que_es."')"))
      	      {
      	          
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE tipos_gasto SET nombre='".$this->nombre."',codigo_gasto_asociado='".$this->codigo_gasto_asociado."',que_es='".$this->que_es."' WHERE cod_gas='".$this->cod_gas."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function cod_gas()
          {
               return $this->cod_gas;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function codigo_gasto_asociado()
          {
               return $this->codigo_gasto_asociado;
          }
          function que_es()
          {
               return $this->que_es;
          }
          
          
          
      
          function cod_gas_asigna($campo)
          {
               $this->cod_gas=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function codigo_gasto_asociado_asigna($campo)
          {
               $this->codigo_gasto_asociado=$campo;
               
          }
          function que_es_asigna($campo)
          {
               $this->que_es=$campo;
               
          }
          
          
          
      
}
?>