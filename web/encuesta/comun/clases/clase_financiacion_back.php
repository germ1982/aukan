<?
      class clase_financiacion       
      {
	  var $idfinanciacion = '';
          var $nombre = '';
          var $direccion = '';
          var $telefono = '';
          var $utiliza_nro = '';
          var $activa = '';
          var $utilizacategoria = '';
          var $iva = '';
          var $cuit = '';
          var $tipo_cliente = '';
          var $honorarios_traumatologico_categoria_a = '';
          var $lista_consultorios = '';
          var $categoriza_consultas = '';
          var $categoriza_practicas = '';
          var $tabla_galenaje = '';
          var $cod_anestesista = '';
          var $activado = '';
          var $fecha_vencimiento = '';
          var $fecha_proximo_vencimiento = '';
          var $honorarios_traumatologico_categoria_b = '';
          var $honorarios_cirujano_categoria_a = '';
          var $honorarios_cirujano_categoria_b = '';
          var $descuento_medicamentos = '';
          var $honorarios_traumatologico_categoria_a_anterior = '';
          var $honorarios_traumatologico_categoria_b_anterior = '';
          var $honorarios_cirujano_categoria_a_anterior = '';
          var $honorarios_cirujano_categoria_b_anterior = '';
          var $codigo_postal = '';
          var $provincia = '';
          var $localidad = '';
          var $descuento_medicamentos_anestesia = '';
          var $art = '';
          
      
      var $arreglo_foraneo_activa='';
      	     
      
         function clase_financiacion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM financiacion WHERE idfinanciacion=$id");
      	     $arreglo=$bd->registro();
      	     $this->idfinanciacion=$arreglo['idfinanciacion'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->direccion=$arreglo['direccion'];
      	     $this->telefono=$arreglo['telefono'];
      	     $this->utiliza_nro=$arreglo['utiliza_nro'];
      	     $this->activa=$arreglo['activa'];
      	     $this->utilizacategoria=$arreglo['utilizacategoria'];
      	     $this->iva=$arreglo['iva'];
      	     $this->cuit=$arreglo['cuit'];
      	     $this->tipo_cliente=$arreglo['tipo_cliente'];
      	     $this->honorarios_traumatologico_categoria_a=$arreglo['honorarios_traumatologico_categoria_a'];
      	     $this->lista_consultorios=$arreglo['lista_consultorios'];
      	     $this->categoriza_consultas=$arreglo['categoriza_consultas'];
      	     $this->categoriza_practicas=$arreglo['categoriza_practicas'];
      	     $this->tabla_galenaje=$arreglo['tabla_galenaje'];
      	     $this->cod_anestesista=$arreglo['cod_anestesista'];
      	     $this->activado=$arreglo['activado'];
      	     $this->fecha_vencimiento=$arreglo['fecha_vencimiento'];
      	     $this->fecha_proximo_vencimiento=$arreglo['fecha_proximo_vencimiento'];
      	     $this->honorarios_traumatologico_categoria_b=$arreglo['honorarios_traumatologico_categoria_b'];
      	     $this->honorarios_cirujano_categoria_a=$arreglo['honorarios_cirujano_categoria_a'];
      	     $this->honorarios_cirujano_categoria_b=$arreglo['honorarios_cirujano_categoria_b'];
      	     $this->descuento_medicamentos=$arreglo['descuento_medicamentos'];
      	     $this->honorarios_traumatologico_categoria_a_anterior=$arreglo['honorarios_traumatologico_categoria_a_anterior'];
      	     $this->honorarios_traumatologico_categoria_b_anterior=$arreglo['honorarios_traumatologico_categoria_b_anterior'];
      	     $this->honorarios_cirujano_categoria_a_anterior=$arreglo['honorarios_cirujano_categoria_a_anterior'];
      	     $this->honorarios_cirujano_categoria_b_anterior=$arreglo['honorarios_cirujano_categoria_b_anterior'];
      	     $this->codigo_postal=$arreglo['codigo_postal'];
      	     $this->provincia=$arreglo['provincia'];
      	     $this->localidad=$arreglo['localidad'];
      	     $this->descuento_medicamentos_anestesia=$arreglo['descuento_medicamentos_anestesia'];
      	     $this->art=$arreglo['art'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idfinanciacion==0 || $this->idfinanciacion=='' ) {
      	      if ($bd->select("INSERT INTO financiacion(nombre,direccion,telefono,utiliza_nro,activa,utilizacategoria,iva,cuit,tipo_cliente,honorarios_traumatologico_categoria_a,lista_consultorios,categoriza_consultas,categoriza_practicas,tabla_galenaje,cod_anestesista,activado,fecha_vencimiento,fecha_proximo_vencimiento,honorarios_traumatologico_categoria_b,honorarios_cirujano_categoria_a,honorarios_cirujano_categoria_b,descuento_medicamentos,honorarios_traumatologico_categoria_a_anterior,honorarios_traumatologico_categoria_b_anterior,honorarios_cirujano_categoria_a_anterior,honorarios_cirujano_categoria_b_anterior,codigo_postal,provincia,localidad,descuento_medicamentos_anestesia,art) VALUES('".$this->nombre."','".$this->direccion."','".$this->telefono."','".$this->utiliza_nro."','".$this->activa."','".$this->utilizacategoria."','".$this->iva."','".$this->cuit."','".$this->tipo_cliente."','".$this->honorarios_traumatologico_categoria_a."','".$this->lista_consultorios."','".$this->categoriza_consultas."','".$this->categoriza_practicas."','".$this->tabla_galenaje."','".$this->cod_anestesista."','".$this->activado."','".$this->fecha_vencimiento."','".$this->fecha_proximo_vencimiento."','".$this->honorarios_traumatologico_categoria_b."','".$this->honorarios_cirujano_categoria_a."','".$this->honorarios_cirujano_categoria_b."','".$this->descuento_medicamentos."','".$this->honorarios_traumatologico_categoria_a_anterior."','".$this->honorarios_traumatologico_categoria_b_anterior."','".$this->honorarios_cirujano_categoria_a_anterior."','".$this->honorarios_cirujano_categoria_b_anterior."','".$this->codigo_postal."','".$this->provincia."','".$this->localidad."','".$this->descuento_medicamentos_anestesia."','".$this->art."')"))
      	      {
      	          $this->idfinanciacion=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE financiacion SET nombre='".$this->nombre."',direccion='".$this->direccion."',telefono='".$this->telefono."',utiliza_nro='".$this->utiliza_nro."',activa='".$this->activa."',utilizacategoria='".$this->utilizacategoria."',iva='".$this->iva."',cuit='".$this->cuit."',tipo_cliente='".$this->tipo_cliente."',honorarios_traumatologico_categoria_a='".$this->honorarios_traumatologico_categoria_a."',lista_consultorios='".$this->lista_consultorios."',categoriza_consultas='".$this->categoriza_consultas."',categoriza_practicas='".$this->categoriza_practicas."',tabla_galenaje='".$this->tabla_galenaje."',cod_anestesista='".$this->cod_anestesista."',activado='".$this->activado."',fecha_vencimiento='".$this->fecha_vencimiento."',fecha_proximo_vencimiento='".$this->fecha_proximo_vencimiento."',honorarios_traumatologico_categoria_b='".$this->honorarios_traumatologico_categoria_b."',honorarios_cirujano_categoria_a='".$this->honorarios_cirujano_categoria_a."',honorarios_cirujano_categoria_b='".$this->honorarios_cirujano_categoria_b."',descuento_medicamentos='".$this->descuento_medicamentos."',honorarios_traumatologico_categoria_a_anterior='".$this->honorarios_traumatologico_categoria_a_anterior."',honorarios_traumatologico_categoria_b_anterior='".$this->honorarios_traumatologico_categoria_b_anterior."',honorarios_cirujano_categoria_a_anterior='".$this->honorarios_cirujano_categoria_a_anterior."',honorarios_cirujano_categoria_b_anterior='".$this->honorarios_cirujano_categoria_b_anterior."',codigo_postal='".$this->codigo_postal."',provincia='".$this->provincia."',localidad='".$this->localidad."',descuento_medicamentos_anestesia='".$this->descuento_medicamentos_anestesia."',art='".$this->art."' WHERE idfinanciacion='".$this->idfinanciacion."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idfinanciacion()
          {
               return $this->idfinanciacion;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function direccion()
          {
               return $this->direccion;
          }
          function telefono()
          {
               return $this->telefono;
          }
          function utiliza_nro()
          {
               return $this->utiliza_nro;
          }
          function activa()
          {
               return $this->activa;
          }
          function utilizacategoria()
          {
               return $this->utilizacategoria;
          }
          function iva()
          {
               return $this->iva;
          }
          function cuit()
          {
               return $this->cuit;
          }
          function tipo_cliente()
          {
               return $this->tipo_cliente;
          }
          function honorarios_traumatologico_categoria_a()
          {
               return $this->honorarios_traumatologico_categoria_a;
          }
          function lista_consultorios()
          {
               return $this->lista_consultorios;
          }
          function categoriza_consultas()
          {
               return $this->categoriza_consultas;
          }
          function categoriza_practicas()
          {
               return $this->categoriza_practicas;
          }
          function tabla_galenaje()
          {
               return $this->tabla_galenaje;
          }
          function cod_anestesista()
          {
               return $this->cod_anestesista;
          }
          function activado()
          {
               return $this->activado;
          }
          function fecha_vencimiento()
          {
               return $this->fecha_vencimiento;
          }
          function fecha_proximo_vencimiento()
          {
               return $this->fecha_proximo_vencimiento;
          }
          function honorarios_traumatologico_categoria_b()
          {
               return $this->honorarios_traumatologico_categoria_b;
          }
          function honorarios_cirujano_categoria_a()
          {
               return $this->honorarios_cirujano_categoria_a;
          }
          function honorarios_cirujano_categoria_b()
          {
               return $this->honorarios_cirujano_categoria_b;
          }
          function descuento_medicamentos()
          {
               return $this->descuento_medicamentos;
          }
          function honorarios_traumatologico_categoria_a_anterior()
          {
               return $this->honorarios_traumatologico_categoria_a_anterior;
          }
          function honorarios_traumatologico_categoria_b_anterior()
          {
               return $this->honorarios_traumatologico_categoria_b_anterior;
          }
          function honorarios_cirujano_categoria_a_anterior()
          {
               return $this->honorarios_cirujano_categoria_a_anterior;
          }
          function honorarios_cirujano_categoria_b_anterior()
          {
               return $this->honorarios_cirujano_categoria_b_anterior;
          }
          function codigo_postal()
          {
               return $this->codigo_postal;
          }
          function provincia()
          {
               return $this->provincia;
          }
          function localidad()
          {
               return $this->localidad;
          }
          function descuento_medicamentos_anestesia()
          {
               return $this->descuento_medicamentos_anestesia;
          }
          function art()
          {
               return $this->art;
          }
          
          
          
      	     function arreglo_foraneo_activa()
             {
                 return $this->arreglo_foraneo_activa;
             }
             
      
          function idfinanciacion_asigna($campo)
          {
               $this->idfinanciacion=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function direccion_asigna($campo)
          {
               $this->direccion=$campo;
               
          }
          function telefono_asigna($campo)
          {
               $this->telefono=$campo;
               
          }
          function utiliza_nro_asigna($campo)
          {
               $this->utiliza_nro=$campo;
               
          }
          function activa_asigna($campo)
          {
               $this->activa=$campo;
               
          }
          function utilizacategoria_asigna($campo)
          {
               $this->utilizacategoria=$campo;
               
          }
          function iva_asigna($campo)
          {
               $this->iva=$campo;
               
          }
          function cuit_asigna($campo)
          {
               $this->cuit=$campo;
               
          }
          function tipo_cliente_asigna($campo)
          {
               $this->tipo_cliente=$campo;
               
          }
          function honorarios_traumatologico_categoria_a_asigna($campo)
          {
               $this->honorarios_traumatologico_categoria_a=$campo;
               
          }
          function lista_consultorios_asigna($campo)
          {
               $this->lista_consultorios=$campo;
               
          }
          function categoriza_consultas_asigna($campo)
          {
               $this->categoriza_consultas=$campo;
               
          }
          function categoriza_practicas_asigna($campo)
          {
               $this->categoriza_practicas=$campo;
               
          }
          function tabla_galenaje_asigna($campo)
          {
               $this->tabla_galenaje=$campo;
               
          }
          function cod_anestesista_asigna($campo)
          {
               $this->cod_anestesista=$campo;
               
          }
          function activado_asigna($campo)
          {
               $this->activado=$campo;
               
          }
          function fecha_vencimiento_asigna($campo)
          {
               $this->fecha_vencimiento=$campo;
               
          }
          function fecha_proximo_vencimiento_asigna($campo)
          {
               $this->fecha_proximo_vencimiento=$campo;
               
          }
          function honorarios_traumatologico_categoria_b_asigna($campo)
          {
               $this->honorarios_traumatologico_categoria_b=$campo;
               
          }
          function honorarios_cirujano_categoria_a_asigna($campo)
          {
               $this->honorarios_cirujano_categoria_a=$campo;
               
          }
          function honorarios_cirujano_categoria_b_asigna($campo)
          {
               $this->honorarios_cirujano_categoria_b=$campo;
               
          }
          function descuento_medicamentos_asigna($campo)
          {
               $this->descuento_medicamentos=$campo;
               
          }
          function honorarios_traumatologico_categoria_a_anterior_asigna($campo)
          {
               $this->honorarios_traumatologico_categoria_a_anterior=$campo;
               
          }
          function honorarios_traumatologico_categoria_b_anterior_asigna($campo)
          {
               $this->honorarios_traumatologico_categoria_b_anterior=$campo;
               
          }
          function honorarios_cirujano_categoria_a_anterior_asigna($campo)
          {
               $this->honorarios_cirujano_categoria_a_anterior=$campo;
               
          }
          function honorarios_cirujano_categoria_b_anterior_asigna($campo)
          {
               $this->honorarios_cirujano_categoria_b_anterior=$campo;
               
          }
          function codigo_postal_asigna($campo)
          {
               $this->codigo_postal=$campo;
               
          }
          function provincia_asigna($campo)
          {
               $this->provincia=$campo;
               
          }
          function localidad_asigna($campo)
          {
               $this->localidad=$campo;
               
          }
          function descuento_medicamentos_anestesia_asigna($campo)
          {
               $this->descuento_medicamentos_anestesia=$campo;
               
          }
          function art_asigna($campo)
          {
               $this->art=$campo;
               
          }
          
          
          
	      function foranea_activa($activa)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM financiacion WHERE activa=$activa ORDER BY nombre ASC");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_activa = $pro;		                              		
			}
			
          function foranea_activado()
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM financiacion WHERE activado=1 ORDER BY nombre ASC");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_activa = $pro;		                              		
			}
      
}
?>
