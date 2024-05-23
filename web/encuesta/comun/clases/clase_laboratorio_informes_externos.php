<?
      class clase_laboratorio_informes_externos       
      {
	  var $id = '';
          var $idepisodio = '';
          var $fecha = '';
          var $hora = '';
          var $archivo = '';
          var $idpedido_laboratorio = '';
          
      
      
      
         function clase_laboratorio_informes_externos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM laboratorio_informes_externos WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->archivo=$arreglo['archivo'];
      	     $this->idpedido_laboratorio=$arreglo['idpedido_laboratorio'];
      	     
      	 }
         function guardar()
         {
	          $bd = new baseDatos();
	      	  $bd->Conectarse();
	      	  if ($this->id==0 || $this->id=='' ) {
	      	      if ($bd->select("INSERT INTO laboratorio_informes_externos(idepisodio,fecha,hora,archivo,idpedido_laboratorio) VALUES('".$this->idepisodio."','".$this->fecha."','".$this->hora."','".$this->archivo."','".$this->idpedido_laboratorio."')"))
	      	      {
	      	          $this->id=$bd->ultimo_id();
	      	          return 1;
	      	      }
	      	      else      	  
	      	          return 0;
	      	  }else
	      	  { 
	      	        if ($bd->select("UPDATE laboratorio_informes_externos SET idepisodio='".$this->idepisodio."',fecha='".$this->fecha."',hora='".$this->hora."',archivo='".$this->archivo."',idpedido_laboratorio='".$this->idpedido_laboratorio."' WHERE id='".$this->id."'"))
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
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function archivo()
          {
               return $this->archivo;
          }
          function idpedido_laboratorio()
          {
               return $this->idpedido_laboratorio;
          }
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function archivo_asigna($campo)
          {
               $this->archivo=$campo;
               
          }
          function idpedido_laboratorio_asigna($campo)
          {
               $this->idpedido_laboratorio=$campo;
               
          }          
          function ultimo_idpedido_laboratorio($idpaciente)
          {
          	  $bd = new baseDatos();
			  $bd->Conectarse();
			  $bd->select("SELECT * FROM pedido_laboratorio WHERE idpaciente=$idpaciente ORDER BY idpedido_laboratorio DESC");
			  $arreglo = $bd->registro();			        	      
      	      $this->idpedido_laboratorio=$arreglo['idpedido_laboratorio']; 			  			  				 
          }       
      	 function buscar_informe_externo($idpedido_laboratorio)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM laboratorio_informes_externos WHERE idpedido_laboratorio=$idpedido_laboratorio");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->archivo=$arreglo['archivo'];
      	     $this->idpedido_laboratorio=$arreglo['idpedido_laboratorio'];      	     
      	 }     
      	 function siguiente_idpedido_laboratorio($idpedido_laboratorio,$idpaciente)
         {
             $bd = new baseDatos();
			 $bd->Conectarse();
			 $bd->select("SELECT * FROM pedido_laboratorio WHERE idpaciente=$idpaciente ORDER BY idpedido_laboratorio DESC");
			 $bandera = 1;
			 while (($arreglo = $bd->registro()) && $bandera == 1)
			 {
			 	 if ($arreglo['idpedido_laboratorio'] == $idpedido_laboratorio)
			 	 {
			 	     $arreglo = $bd->registro();	 			         
      	         	 $this->idpedido_laboratorio=$arreglo['idpedido_laboratorio'];
      	         	 $bandera=0;
			 	 }
			 } 			  			  				 
         }       
}
?>