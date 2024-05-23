<?
      class clase_profesionales       
      {
	  	  var $idprofesional = '';
          var $nombre = '';
          var $rol = '';
          var $direccion = '';
          var $localidad = '';
          var $provincia = '';
          var $telefono = '';
          var $matricula = '';
          var $idcategoria = '';
          var $idespecialidad = '';
          var $cod_pos = '';
          var $mail = '';
          var $comentario = '';
          var $activado = '';
          var $idlugar = '';
          var $factura_ambulatorio = '';
          var $factura_internacion = '';
          var $tipo_profesional = '';
          var $cantidad_sobreturnos = '';
          var $activado_ambulatorio = '';
          var $fecha_alta = '';
          var $username = '';
          var $arreglo_todos_profesionales = '';
          var $arreglo_todos_profesionales_activado_ambulatorio = '';
          var $valor_consulta_ambulatoria = '';
          var $porcentaje_consulta_guardia = '';
          var $liquidacion_guardia = '';
          
      
      
      
         function clase_profesionales($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM profesionales WHERE idprofesional=$id");
      	     $arreglo=$bd->registro();
      	     self::asigna($arreglo);
      	     
      	 }
      	 function asigna($arreglo)
      	 {
      	 	 $this->idprofesional=$arreglo['idprofesional'];
      	     $this->nombre=$arreglo['nombre'];
      	     $this->rol=$arreglo['rol'];
      	     $this->direccion=$arreglo['direccion'];
      	     $this->localidad=$arreglo['localidad'];
      	     $this->provincia=$arreglo['provincia'];
      	     $this->telefono=$arreglo['telefono'];
      	     $this->matricula=$arreglo['matricula'];
      	     $this->idcategoria=$arreglo['idcategoria'];
      	     $this->idespecialidad=$arreglo['idespecialidad'];
      	     $this->cod_pos=$arreglo['cod_pos'];
      	     $this->mail=$arreglo['mail'];
      	     $this->comentario=$arreglo['comentario'];
      	     $this->activado=$arreglo['activado'];
      	     $this->idlugar=$arreglo['idlugar'];
      	     $this->factura_ambulatorio=$arreglo['factura_ambulatorio'];
      	     $this->factura_internacion=$arreglo['factura_internacion'];
      	     $this->tipo_profesional=$arreglo['tipo_profesional'];
      	     $this->cantidad_sobreturnos=$arreglo['cantidad_sobreturnos'];
      	     $this->activado_ambulatorio=$arreglo['activado_ambulatorio'];
      	     $this->fecha_alta=$arreglo['fecha_alta'];
      	     $this->username=$arreglo['username'];
      	     $this->valor_consulta_ambulatoria = $arreglo['valor_consulta_ambulatoria'];
      	     $this->porcentaje_consulta_guardia = $arreglo['porcentaje_consulta_guardia'];
          	 $this->liquidacion_guardia = $arreglo['liquidacion_guardia'];
      	 }       
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idprofesional==0 || $this->idprofesional=='' ) {
      	      if ($bd->select("INSERT INTO profesionales(nombre,rol,direccion,localidad,provincia,telefono,matricula,idcategoria,idespecialidad,cod_pos,mail,comentario,activado,idlugar,factura_ambulatorio,factura_internacion,tipo_profesional,cantidad_sobreturnos,activado_ambulatorio,fecha_alta,username,
      	      valor_consulta_ambulatoria,porcentaje_consulta_guardia,liquidacion_guardia) VALUES('".$this->nombre."','".$this->rol."','".$this->direccion."','".$this->localidad."','".$this->provincia."','".$this->telefono."','".$this->matricula."','".$this->idcategoria."','".$this->idespecialidad."','".$this->cod_pos."','".$this->mail."','".$this->comentario."','".$this->activado."',
      	      '".$this->idlugar."','".$this->factura_ambulatorio."','".$this->factura_internacion."',
      	      '".$this->tipo_profesional."','".$this->cantidad_sobreturnos."','".$this->activado_ambulatorio."',
      	      '".$this->fecha_alta."','".$this->username."','".$this->valor_consulta_ambulatoria."','".$this->porcentaje_consulta_guardia."','".$this->liquidacion_guardia."')"))
      	      {
      	          $this->idprofesional=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE profesionales SET nombre='".$this->nombre."',rol='".$this->rol."',
      	        direccion='".$this->direccion."',localidad='".$this->localidad."',provincia='".$this->provincia."',
      	        telefono='".$this->telefono."',matricula='".$this->matricula."',idcategoria='".$this->idcategoria."',
      	        idespecialidad='".$this->idespecialidad."',cod_pos='".$this->cod_pos."',mail='".$this->mail."',
      	        comentario='".$this->comentario."',activado='".$this->activado."',idlugar='".$this->idlugar."',
      	        factura_ambulatorio='".$this->factura_ambulatorio."',factura_internacion='".$this->factura_internacion."',
      	        tipo_profesional='".$this->tipo_profesional."',cantidad_sobreturnos='".$this->cantidad_sobreturnos."',
      	        activado_ambulatorio='".$this->activado_ambulatorio."',fecha_alta='".$this->fecha_alta."',
      	        username='".$this->username."',
      	        valor_consulta_ambulatoria='".$this->valor_consulta_ambulatoria."',porcentaje_consulta_guardia='".$this->porcentaje_consulta_guardia."',liquidacion_guardia='".$this->liquidacion_guardia."' WHERE idprofesional='".$this->idprofesional."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function nombre()
          {
               return $this->nombre;
          }
          function rol()
          {
               return $this->rol;
          }
          function direccion()
          {
               return $this->direccion;
          }
          function localidad()
          {
               return $this->localidad;
          }
          function provincia()
          {
               return $this->provincia;
          }
          function telefono()
          {
               return $this->telefono;
          }
          function matricula()
          {
               return $this->matricula;
          }
          function categoria()
          {
               return $this->idcategoria;
          }
          function idespecialidad()
          {
               return $this->idespecialidad;
          }
          function cod_pos()
          {
               return $this->cod_pos;
          }
          function mail()
          {
               return $this->mail;
          }
          function comentario()
          {
               return $this->comentario;
          }
          function activado()
          {
               return $this->activado;
          }
          function idlugar()
          {
               return $this->idlugar;
          }
          function factura_ambulatorio()
          {
               return $this->factura_ambulatorio;
          }
          function factura_internacion()
          {
               return $this->factura_internacion;
          }
          function tipo_profesional()
          {
               return $this->tipo_profesional;
          }
          function cantidad_sobreturnos()
          {
               return $this->cantidad_sobreturnos;
          }
          function activado_ambulatorio()
          {
               return $this->activado_ambulatorio;
          }
          function fecha_alta()
          {
               return $this->fecha_alta;
          }
          function username()
          {
               return $this->username;
          }
          function prefijo()
		  {
		      return $this->prefijo;
		  }
      	  function valor_consulta_ambulatoria()
		  {
		      return $this->valor_consulta_ambulatoria;
		  }
		  function porcentaje_consulta_guardia()
		  {
		  	  return $this->porcentaje_consulta_guardia;
		  }
		  function liquidacion_guardia()
		  {
		  	  return $this->liquidacion_guardia;
		  }
          
      
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function nombre_asigna($campo)
          {
               $this->nombre=$campo;
               
          }
          function rol_asigna($campo)
          {
               $this->rol=$campo;
               
          }
          function direccion_asigna($campo)
          {
               $this->direccion=$campo;
               
          }
          function localidad_asigna($campo)
          {
               $this->localidad=$campo;
               
          }
          function provincia_asigna($campo)
          {
               $this->provincia=$campo;
               
          }
          function telefono_asigna($campo)
          {
               $this->telefono=$campo;
               
          }
          function matricula_asigna($campo)
          {
               $this->matricula=$campo;
               
          }
          function idcategoria_asigna($campo)
          {
               $this->idcategoria=$campo;
               
          }
          function idespecialidad_asigna($campo)
          {
               $this->idespecialidad=$campo;
               
          }
          function cod_pos_asigna($campo)
          {
               $this->cod_pos=$campo;
               
          }
          function mail_asigna($campo)
          {
               $this->mail=$campo;
               
          }
          function comentario_asigna($campo)
          {
               $this->comentario=$campo;
               
          }
          function activado_asigna($campo)
          {
               $this->activado=$campo;
               
          }
          function idlugar_asigna($campo)
          {
               $this->idlugar=$campo;
               
          }
          function factura_ambulatorio_asigna($campo)
          {
               $this->factura_ambulatorio=$campo;
               
          }
          function factura_internacion_asigna($campo)
          {
               $this->factura_internacion=$campo;
               
          }
          function tipo_profesional_asigna($campo)
          {
               $this->tipo_profesional=$campo;
               
          }
          function cantidad_sobreturnos_asigna($campo)
          {
               $this->cantidad_sobreturnos=$campo;
               
          }
          function activado_ambulatorio_asigna($campo)
          {
               $this->activado_ambulatorio=$campo;
               
          }
          function fecha_alta_asigna($campo)
          {
               $this->fecha_alta=$campo;
               
          }
          function username_asigna($campo)
          {
               $this->username=$campo;
               
          }
          function valor_consulta_ambulatoria_asigna($campo)
          {
               $this->valor_consulta_ambulatoria=$campo;
               
          }
      	  function porcentaje_consulta_guardia_asigna($campo)
		  {
		  	  $this->porcentaje_consulta_guardia=$campo;
		  }
		  function liquidacion_guardia_asigna($campo)
		  {
		  	  $this->liquidacion_guardia=$campo;
		  }
          
      	  function todos_profesionales()
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM profesionales ORDER BY nombre");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_todos_profesionales = $pro;		                              		
		   }
      	  function todos_profesionales_activado_ambulatorio()
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM profesionales WHERE turnosweb=1  
 ORDER BY nombre");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_todos_profesionales_activado_ambulatorio = $pro;		                              		
		   }
           function arreglo_todos_profesionales()
           {
           	   return $this->arreglo_todos_profesionales;
           }
           function arreglo_todos_profesionales_activado_ambulatorio()
           {
           	   return $this->arreglo_todos_profesionales_activado_ambulatorio;
           }
          
          
      
}
?>
