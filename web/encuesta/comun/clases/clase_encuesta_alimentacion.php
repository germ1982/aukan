<?
      class clase_encuesta_alimentacion       
      {
	  var $id = '';
          var $id_encuesta = '';
          var $fecha = '';
          var $desayuna = '';
          var $colacion_manana = '';
          var $almuerzo = '';
          var $merienda = '';
          var $colacion_tarde = '';
          var $cena = '';
          var $intolerancia_alimentos = '';
          var $restriccion_alimentaria = '';
          var $conducta_alimentaria = '';
          var $situaciones_alimentacion = '';
          var $alergia_alimentaria = '';
          var $toma_agua = '';
          var $bebidas_azucaradas = '';
          var $peso = '';
          var $variaciones_peso = '';
          var $variaciones_peso_motivo = '';
          var $imagen_corporal = '';
          var $baja_fecha = '';
          
      
      var $arreglo_foraneo_id_encuesta='';
      	     
      
         function clase_encuesta_alimentacion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM encuesta_alimentacion WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->id_encuesta=$arreglo['id_encuesta'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->desayuna=$arreglo['desayuna'];
      	     $this->colacion_manana=$arreglo['colacion_manana'];
      	     $this->almuerzo=$arreglo['almuerzo'];
      	     $this->merienda=$arreglo['merienda'];
      	     $this->colacion_tarde=$arreglo['colacion_tarde'];
      	     $this->cena=$arreglo['cena'];
      	     $this->intolerancia_alimentos=$arreglo['intolerancia_alimentos'];
      	     $this->restriccion_alimentaria=$arreglo['restriccion_alimentaria'];
      	     $this->conducta_alimentaria=$arreglo['conducta_alimentaria'];
      	     $this->situaciones_alimentacion=$arreglo['situaciones_alimentacion'];
      	     $this->alergia_alimentaria=$arreglo['alergia_alimentaria'];
      	     $this->toma_agua=$arreglo['toma_agua'];
      	     $this->bebidas_azucaradas=$arreglo['bebidas_azucaradas'];
      	     $this->peso=$arreglo['peso'];
      	     $this->variaciones_peso=$arreglo['variaciones_peso'];
      	     $this->variaciones_peso_motivo=$arreglo['variaciones_peso_motivo'];
      	     $this->imagen_corporal=$arreglo['imagen_corporal'];
      	     $this->baja_fecha=$arreglo['baja_fecha'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO encuesta_alimentacion(id_encuesta,fecha,desayuna,colacion_manana,almuerzo,merienda,colacion_tarde,cena,intolerancia_alimentos,restriccion_alimentaria,conducta_alimentaria,situaciones_alimentacion,alergia_alimentaria,toma_agua,bebidas_azucaradas,peso,variaciones_peso,variaciones_peso_motivo,imagen_corporal,baja_fecha) VALUES('".$this->id_encuesta."','".$this->fecha."','".$this->desayuna."','".$this->colacion_manana."','".$this->almuerzo."','".$this->merienda."','".$this->colacion_tarde."','".$this->cena."','".$this->intolerancia_alimentos."','".$this->restriccion_alimentaria."','".$this->conducta_alimentaria."','".$this->situaciones_alimentacion."','".$this->alergia_alimentaria."','".$this->toma_agua."','".$this->bebidas_azucaradas."','".$this->peso."','".$this->variaciones_peso."','".$this->variaciones_peso_motivo."','".$this->imagen_corporal."','".$this->baja_fecha."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE encuesta_alimentacion SET id_encuesta='".$this->id_encuesta."',fecha='".$this->fecha."',desayuna='".$this->desayuna."',colacion_manana='".$this->colacion_manana."',almuerzo='".$this->almuerzo."',merienda='".$this->merienda."',colacion_tarde='".$this->colacion_tarde."',cena='".$this->cena."',intolerancia_alimentos='".$this->intolerancia_alimentos."',restriccion_alimentaria='".$this->restriccion_alimentaria."',conducta_alimentaria='".$this->conducta_alimentaria."',situaciones_alimentacion='".$this->situaciones_alimentacion."',alergia_alimentaria='".$this->alergia_alimentaria."',toma_agua='".$this->toma_agua."',bebidas_azucaradas='".$this->bebidas_azucaradas."',peso='".$this->peso."',variaciones_peso='".$this->variaciones_peso."',variaciones_peso_motivo='".$this->variaciones_peso_motivo."',imagen_corporal='".$this->imagen_corporal."',baja_fecha='".$this->baja_fecha."' WHERE id='".$this->id."'"))
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
          function id_encuesta()
          {
               return $this->id_encuesta;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function desayuna()
          {
               return $this->desayuna;
          }
          function colacion_manana()
          {
               return $this->colacion_manana;
          }
          function almuerzo()
          {
               return $this->almuerzo;
          }
          function merienda()
          {
               return $this->merienda;
          }
          function colacion_tarde()
          {
               return $this->colacion_tarde;
          }
          function cena()
          {
               return $this->cena;
          }
          function intolerancia_alimentos()
          {
               return $this->intolerancia_alimentos;
          }
          function restriccion_alimentaria()
          {
               return $this->restriccion_alimentaria;
          }
          function conducta_alimentaria()
          {
               return $this->conducta_alimentaria;
          }
          function situaciones_alimentacion()
          {
               return $this->situaciones_alimentacion;
          }
          function alergia_alimentaria()
          {
               return $this->alergia_alimentaria;
          }
          function toma_agua()
          {
               return $this->toma_agua;
          }
          function bebidas_azucaradas()
          {
               return $this->bebidas_azucaradas;
          }
          function peso()
          {
               return $this->peso;
          }
          function variaciones_peso()
          {
               return $this->variaciones_peso;
          }
          function variaciones_peso_motivo()
          {
               return $this->variaciones_peso_motivo;
          }
          function imagen_corporal()
          {
               return $this->imagen_corporal;
          }
          function baja_fecha()
          {
               return $this->baja_fecha;
          }
          
          
          
      	     function arreglo_foraneo_id_encuesta()
             {
                 return $this->arreglo_foraneo_id_encuesta;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function id_encuesta_asigna($campo)
          {
               $this->id_encuesta=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function desayuna_asigna($campo)
          {
               $this->desayuna=$campo;
               
          }
          function colacion_manana_asigna($campo)
          {
               $this->colacion_manana=$campo;
               
          }
          function almuerzo_asigna($campo)
          {
               $this->almuerzo=$campo;
               
          }
          function merienda_asigna($campo)
          {
               $this->merienda=$campo;
               
          }
          function colacion_tarde_asigna($campo)
          {
               $this->colacion_tarde=$campo;
               
          }
          function cena_asigna($campo)
          {
               $this->cena=$campo;
               
          }
          function intolerancia_alimentos_asigna($campo)
          {
               $this->intolerancia_alimentos=$campo;
               
          }
          function restriccion_alimentaria_asigna($campo)
          {
               $this->restriccion_alimentaria=$campo;
               
          }
          function conducta_alimentaria_asigna($campo)
          {
               $this->conducta_alimentaria=$campo;
               
          }
          function situaciones_alimentacion_asigna($campo)
          {
               $this->situaciones_alimentacion=$campo;
               
          }
          function alergia_alimentaria_asigna($campo)
          {
               $this->alergia_alimentaria=$campo;
               
          }
          function toma_agua_asigna($campo)
          {
               $this->toma_agua=$campo;
               
          }
          function bebidas_azucaradas_asigna($campo)
          {
               $this->bebidas_azucaradas=$campo;
               
          }
          function peso_asigna($campo)
          {
               $this->peso=$campo;
               
          }
          function variaciones_peso_asigna($campo)
          {
               $this->variaciones_peso=$campo;
               
          }
          function variaciones_peso_motivo_asigna($campo)
          {
               $this->variaciones_peso_motivo=$campo;
               
          }
          function imagen_corporal_asigna($campo)
          {
               $this->imagen_corporal=$campo;
               
          }
          function baja_fecha_asigna($campo)
          {
               $this->baja_fecha=$campo;
               
          }
          
          
          
	      function foranea_id_encuesta($id_encuesta)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM encuesta_alimentacion WHERE id_encuesta=$id_encuesta");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_id_encuesta = $pro;		                              		
			}
			
      
}
?>