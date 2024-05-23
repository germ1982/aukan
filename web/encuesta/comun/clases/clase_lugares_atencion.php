<?
      class clase_lugares_atencion       
      {
	      var $id = '';
          var $nombre = '';
          var $estado = '';
          var $arreglo_lugares = '';
      
      
      
         function clase_lugares_atencion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM lugares_atencion WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asigna($arreglo);      	     
      	     
      	 }
      	 function asigna($arreglo)
      	 {
      	 	$this->id=$arreglo['id'];
      	 	$this->nombre=$arreglo['nombre'];
      	 	$this->estado=$arreglo['estado'];
      	 }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO lugares_atencion(nombre,estado) VALUES('".$this->nombre."','".$this->estado."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE lugares_atencion SET nombre='".$this->nombre."',estado='".$this->estado."' WHERE id='".$this->id."'"))
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
          function nombre()
          {
               return $this->nombre;
          }
          function estado()
          {
               return $this->estado;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function foranea_arreglo_lugares()
          {
          	   return $this->arreglo_lugares;
          }
          function lugares_atencion()
          {
	          	$bd = new baseDatos();
	          	$bd->Conectarse();
	          	$bd->select("SELECT * FROM lugares_atencion WHERE estado=1 ORDER BY nombre");
	          	$pro = new clase_listar();
	          		
	          	for($i=0;$i<=$bd->numero_filas();$i++)
	          	{
	          		$fila = $bd->registro();
	          		$pro->introducirElemento($fila);
	          	}
	          	$this->arreglo_lugares = $pro;
          }
          
          
      
}
?>