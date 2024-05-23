<?
      class clase_scores_crib       
      {
	      var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $murio = '';
          var $murio_motivo = '';
          var $murio_edad = '';
          var $peso_valor = '';
          var $peso_texto = '';
          var $edad_gestacional_valor = '';
          var $edad_gestacional_texto = '';
          var $malformaciones_congenitas_valor = '';
          var $malformaciones_congenitas_texto = '';
          var $exceso_base_valor = '';
          var $exceso_base_texto = '';
          var $fio_mayor_valor = '';
          var $fio_mayor_texto = '';
          var $fio_menor_valor = '';
          var $fio_menor_texto = '';
          var $probabilidad_mortalidad = '';
          var $tipo = '';
          var $mayor_7 = 0;
		  var $entre_4_y_7 = 0;
		  var $menor_4 = 0;
          
      
         var $arreglo_foraneo_idepisodio='';
      	     
      
         function clase_scores_crib($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM scores_crib WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asigna($arreglo);
      	     
      	 }
      	 function asigna($arreglo)       
      	 {
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->murio=$arreglo['murio'];
      	     $this->murio_motivo=$arreglo['murio_motivo'];
      	     $this->murio_edad=$arreglo['murio_edad'];
      	     $this->peso_valor=$arreglo['peso_valor'];
      	     $this->peso_texto=$arreglo['peso_texto'];
      	     $this->edad_gestacional_valor=$arreglo['edad_gestacional_valor'];
      	     $this->edad_gestacional_texto=$arreglo['edad_gestacional_texto'];
      	     $this->malformaciones_congenitas_valor=$arreglo['malformaciones_congenitas_valor'];
      	     $this->malformaciones_congenitas_texto=$arreglo['malformaciones_congenitas_texto'];
      	     $this->exceso_base_valor=$arreglo['exceso_base_valor'];
      	     $this->exceso_base_texto=$arreglo['exceso_base_texto'];
      	     $this->fio_mayor_valor=$arreglo['fio_mayor_valor'];
      	     $this->fio_mayor_texto=$arreglo['fio_mayor_texto'];
      	     $this->fio_menor_valor=$arreglo['fio_menor_valor'];
      	     $this->fio_menor_texto=$arreglo['fio_menor_texto'];
      	     $this->probabilidad_mortalidad=$arreglo['probabilidad_mortalidad'];
      	     $this->tipo=$arreglo['tipo'];	
      	 }
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO scores_crib(idepisodio,idprofesional,fecha,hora,murio,murio_motivo,murio_edad,peso_valor,peso_texto,edad_gestacional_valor,edad_gestacional_texto,malformaciones_congenitas_valor,malformaciones_congenitas_texto,exceso_base_valor,exceso_base_texto,fio_mayor_valor,fio_mayor_texto,fio_menor_valor,fio_menor_texto,probabilidad_mortalidad,tipo) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->fecha."','".$this->hora."','".$this->murio."','".$this->murio_motivo."','".$this->murio_edad."','".$this->peso_valor."','".$this->peso_texto."','".$this->edad_gestacional_valor."','".$this->edad_gestacional_texto."','".$this->malformaciones_congenitas_valor."','".$this->malformaciones_congenitas_texto."','".$this->exceso_base_valor."','".$this->exceso_base_texto."','".$this->fio_mayor_valor."','".$this->fio_mayor_texto."','".$this->fio_menor_valor."','".$this->fio_menor_texto."','".$this->probabilidad_mortalidad."','".$this->tipo."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE scores_crib SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',hora='".$this->hora."',murio='".$this->murio."',murio_motivo='".$this->murio_motivo."',murio_edad='".$this->murio_edad."',peso_valor='".$this->peso_valor."',peso_texto='".$this->peso_texto."',edad_gestacional_valor='".$this->edad_gestacional_valor."',edad_gestacional_texto='".$this->edad_gestacional_texto."',malformaciones_congenitas_valor='".$this->malformaciones_congenitas_valor."',malformaciones_congenitas_texto='".$this->malformaciones_congenitas_texto."',exceso_base_valor='".$this->exceso_base_valor."',exceso_base_texto='".$this->exceso_base_texto."',fio_mayor_valor='".$this->fio_mayor_valor."',fio_mayor_texto='".$this->fio_mayor_texto."',fio_menor_valor='".$this->fio_menor_valor."',fio_menor_texto='".$this->fio_menor_texto."',probabilidad_mortalidad='".$this->probabilidad_mortalidad."',tipo='".$this->tipo."' WHERE id='".$this->id."'"))
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
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function murio()
          {
               return $this->murio;
          }
          function murio_motivo()
          {
               return $this->murio_motivo;
          }
          function murio_edad()
          {
               return $this->murio_edad;
          }
          function peso_valor()
          {
               return $this->peso_valor;
          }
          function peso_texto()
          {
               return $this->peso_texto;
          }
          function edad_gestacional_valor()
          {
               return $this->edad_gestacional_valor;
          }
          function edad_gestacional_texto()
          {
               return $this->edad_gestacional_texto;
          }
          function malformaciones_congenitas_valor()
          {
               return $this->malformaciones_congenitas_valor;
          }
          function malformaciones_congenitas_texto()
          {
               return $this->malformaciones_congenitas_texto;
          }
          function exceso_base_valor()
          {
               return $this->exceso_base_valor;
          }
          function exceso_base_texto()
          {
               return $this->exceso_base_texto;
          }
          function fio_mayor_valor()
          {
               return $this->fio_mayor_valor;
          }
          function fio_mayor_texto()
          {
               return $this->fio_mayor_texto;
          }
          function fio_menor_valor()
          {
               return $this->fio_menor_valor;
          }
          function fio_menor_texto()
          {
               return $this->fio_menor_texto;
          }
          function probabilidad_mortalidad()
          {
               return $this->probabilidad_mortalidad;
          }
          function tipo()
          {
               return $this->tipo;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function murio_asigna($campo)
          {
               $this->murio=$campo;
               
          }
          function murio_motivo_asigna($campo)
          {
               $this->murio_motivo=$campo;
               
          }
          function murio_edad_asigna($campo)
          {
               $this->murio_edad=$campo;
               
          }
          function peso_valor_asigna($campo)
          {
               $this->peso_valor=$campo;
               
          }
          function peso_texto_asigna($campo)
          {
               $this->peso_texto=$campo;
               
          }
          function edad_gestacional_valor_asigna($campo)
          {
               $this->edad_gestacional_valor=$campo;
               
          }
          function edad_gestacional_texto_asigna($campo)
          {
               $this->edad_gestacional_texto=$campo;
               
          }
          function malformaciones_congenitas_valor_asigna($campo)
          {
               $this->malformaciones_congenitas_valor=$campo;
               
          }
          function malformaciones_congenitas_texto_asigna($campo)
          {
               $this->malformaciones_congenitas_texto=$campo;
               
          }
          function exceso_base_valor_asigna($campo)
          {
               $this->exceso_base_valor=$campo;
               
          }
          function mayor_7()
          {
              return $this->mayor_7;
          }
          function entre_4_y_7()
          {
          	  return $this->entre_4_y_7;
          }
          function menor_4()
          {
          	  return $this->menor_4;
          }
      	  function mayor_7_asigna($campo)
          {
              $this->mayor_7=$campo;
          }
      	  function entre_4_y_7_asigna($campo)
          {
          	  $this->entre_4_y_7=$campo;
          }
          function menor_4_asigna($campo)
          {
          	  $this->menor_4=$campo;
          }
          function exceso_base_texto_asigna($campo)
          {
               $this->exceso_base_texto=$campo;
               
          }
          function fio_mayor_valor_asigna($campo)
          {
               $this->fio_mayor_valor=$campo;
               
          }
          function fio_mayor_texto_asigna($campo)
          {
               $this->fio_mayor_texto=$campo;
               
          }
          function fio_menor_valor_asigna($campo)
          {
               $this->fio_menor_valor=$campo;
               
          }
          function fio_menor_texto_asigna($campo)
          {
               $this->fio_menor_texto=$campo;
               
          }
          function probabilidad_mortalidad_asigna($campo)
          {
               $this->probabilidad_mortalidad=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM scores_crib WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;	
	    		$bd->cerrar();	                              		
			}
		  function total_crib($fdesde,$fhasta)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse();
			  
			  $bd->select("SELECT sum(case when ((probabilidad_mortalidad > 7)) THEN 1 ELSE 0 END ) mayor_7,
       							  sum(case when ((probabilidad_mortalidad > 4 AND probabilidad_mortalidad <=7)) THEN 1 ELSE 0 END ) entre_4_y_7,
       							  sum(case when ((probabilidad_mortalidad <= 4)) THEN 1 ELSE 0 END ) menor_4
       				      FROM  scores_crib 
			              WHERE fecha>='".fechaBase($fdesde)."' AND fecha<='".fechaBase($fhasta)."' 
			              ");
			  $arreglo = $bd->registro();
			  $this->mayor_7 = $arreglo['mayor_7'];
			  $this->entre_4_y_7 = $arreglo['entre_4_y_7'];
			  $this->menor_4 = $arreglo['menor_4'];
			  $bd->cerrar();			 			 
		  }	
		  function listado_pacientes_crib($fdesde,$fhasta)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse();		    
			  $bd->select("SELECT pacientes.nombre, `probabilidad_mortalidad` as cant,fecha,scores_crib.idepisodio 
			              FROM `scores_crib` LEFT JOIN episodios USING ( idepisodio ) LEFT JOIN pacientes USING ( idpaciente )
                          WHERE fecha >= '".fechaBase($fdesde)."' AND fecha<='".fechaBase($fhasta)."'");				
			  $pro = new clase_listar();			
								
	    	  for($i=0;$i<=$bd->numero_filas();$i++) 
	    	  {
	    	      $fila = $bd->registro(); 
	    	      if ($fila['idepisodio'] != 0 && $fila['idepisodio'] != '')
	    		      $pro->introducirElemento($fila); 
	    	  }
	    	  $this->arreglo_foraneo_idepisodio = $pro;	
	    	  $bd->cerrar();	 	  
		  }
      
}
?>