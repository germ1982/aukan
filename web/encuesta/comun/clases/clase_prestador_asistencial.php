<?
      class clase_prestador_asistencial       
      {
	  var $id = '';
          var $nombre = '';
          var $cuit = '';
          var $calle = '';
          var $numero = '';
          var $piso = '';
          var $dpto = '';
          var $localidad = '';
          var $provincia = '';
          var $codigo_postal = '';
          var $telefono = '';
          var $fax = '';
          var $mail = '';
          
      
      
      
         function clase_prestador_asistencial($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM prestador_asistencial WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->cuit=$arreglo['cuit'];
      	     $this->calle=$arreglo['calle'];
      	     $this->numero=$arreglo['numero'];
      	     $this->piso=$arreglo['piso'];
      	     $this->dpto=$arreglo['dpto'];
      	     $this->localidad=$arreglo['localidad'];
      	     $this->provincia=$arreglo['provincia'];
      	     $this->codigo_postal=$arreglo['codigo_postal'];
      	     $this->telefono=$arreglo['telefono'];
      	     $this->fax=$arreglo['fax'];
      	     $this->mail=$arreglo['mail'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO prestador_asistencial(nombre,cuit,calle,numero,piso,dpto,localidad,provincia,codigo_postal,telefono,fax,mail) VALUES('".$this->nombre."','".$this->cuit."','".$this->calle."','".$this->numero."','".$this->piso."','".$this->dpto."','".$this->localidad."','".$this->provincia."','".$this->codigo_postal."','".$this->telefono."','".$this->fax."','".$this->mail."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE prestador_asistencial SET nombre='".$this->nombre."',cuit='".$this->cuit."',calle='".$this->calle."',numero='".$this->numero."',piso='".$this->piso."',dpto='".$this->dpto."',localidad='".$this->localidad."',provincia='".$this->provincia."',codigo_postal='".$this->codigo_postal."',telefono='".$this->telefono."',fax='".$this->fax."',mail='".$this->mail."' WHERE id='".$this->id."'"))
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
          function cuit()
          {
               return $this->cuit;
          }
          function calle()
          {
               return $this->calle;
          }
          function numero()
          {
               return $this->numero;
          }
          function piso()
          {
               return $this->piso;
          }
          function dpto()
          {
               return $this->dpto;
          }
          function localidad()
          {
               return $this->localidad;
          }
          function provincia()
          {
               return $this->provincia;
          }
          function codigo_postal()
          {
               return $this->codigo_postal;
          }
          function telefono()
          {
               return $this->telefono;
          }
          function fax()
          {
               return $this->fax;
          }
          function mail()
          {
               return $this->mail;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function cuit_asigna($campo)
          {
               $this->cuit=$campo;
               
          }
          function calle_asigna($campo)
          {
               $this->calle=$campo;
               
          }
          function numero_asigna($campo)
          {
               $this->numero=$campo;
               
          }
          function piso_asigna($campo)
          {
               $this->piso=$campo;
               
          }
          function dpto_asigna($campo)
          {
               $this->dpto=$campo;
               
          }
          function localidad_asigna($campo)
          {
               $this->localidad=$campo;
               
          }
          function provincia_asigna($campo)
          {
               $this->provincia=$campo;
               
          }
          function codigo_postal_asigna($campo)
          {
               $this->codigo_postal=$campo;
               
          }
          function telefono_asigna($campo)
          {
               $this->telefono=$campo;
               
          }
          function fax_asigna($campo)
          {
               $this->fax=$campo;
               
          }
          function mail_asigna($campo)
          {
               $this->mail=$campo;
               
          }
          
          
          
      
}
?>