<?
      class clase_resultados_estudio       
      {
	  var $idresultado_estudio = '';
          var $idprofesional = '';
          var $idpedido_estudio_detalle = '';
          var $informe = '';
          var $fecha = '';
          var $hora = '';
          var $tipo_informe = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpedido_estudio_detalle='';
      	     
      
         function clase_resultados_estudio($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM resultados_estudio WHERE idresultado_estudio=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->idresultado_estudio=$arreglo['idresultado_estudio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpedido_estudio_detalle=$arreglo['idpedido_estudio_detalle'];
      	     $this->informe=$arreglo['informe'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
             $this->tipo_informe=$arreglo['tipo_informe'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idresultado_estudio==0 || $this->idresultado_estudio=='' ) {
      	      if ($bd->select("INSERT INTO resultados_estudio(idprofesional,idpedido_estudio_detalle,informe,fecha,hora,tipo_informe) VALUES(".$this->idprofesional.",".$this->idpedido_estudio_detalle.",'".$this->informe."','".$this->fecha."','".$this->hora."','".$this->tipo_informe."')"))
      	      {
      	          $this->idresultado_estudio=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE resultados_estudio SET idprofesional='".$this->idprofesional."', informe='".$this->informe."',tipo_informe='".$this->tipo_informe."' WHERE idresultado_estudio='".$this->idresultado_estudio."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idresultado_estudio()
          {
               return $this->idresultado_estudio;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpedido_estudio_detalle()
          {
               return $this->idpedido_estudio_detalle;
          }
          function informe()
          {
               return $this->informe;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function tipo_informe()
          {
               return $this->tipo_informe;
          }
          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpedido_estudio_detalle()
             {
                 return $this->arreglo_foraneo_idpedido_estudio_detalle;
             }
             
      
          function idresultado_estudio_asigna($campo)
          {
               $this->idresultado_estudio=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpedido_estudio_detalle_asigna($campo)
          {
               $this->idpedido_estudio_detalle=$campo;
               
          }
          function informe_asigna($campo)
          {
               $this->informe=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function tipo_informe_asigna($campo)
          {
               $this->tipo_informe=$campo;
               
          }
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM resultados_estudio WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idpedido_estudio_detalle($idpedido_estudio_detalle)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM resultados_estudio WHERE idpedido_estudio_detalle=$idpedido_estudio_detalle");				
				
                                self::asignar($bd->registro());
	    			                              		
			}
              function buscar_resultado_segun_estudio_fecha($idpaciente,$fecha,$codigo_contenedor,$que_es)
	      {
	          $bd = new baseDatos();
		  $bd->Conectarse();
                  if ($codigo_contenedor == 0 &&  $que_es == 0)
                  {
                        $bd->select("SELECT nomenclador.descripcion 
                                     FROM pedidos_estudio LEFT JOIN pedidos_estudio_detalle USING(idpedido_estudio) 
                                          LEFT JOIN resultados_estudio USING (idpedido_estudio_detalle) 
                                          LEFT JOIN nomenclador USING (idnomenclador)
                                     WHERE idpaciente=$idpaciente AND pedidos_estudio.fecha='".fechaBase($fecha)."'");
                        $pro = new clase_listar();
                        for($i=0;$i<=$bd->numero_filas();$i++) 
                        {
                            $fila = $bd->registro(); 
                            $pro->introducirElemento($fila); 
                        }
                        $this->arreglo_foraneo_idprofesional = $pro;
                  }
                  else
                  {
                      //restamos a la fecha 3 dias y sumamos 3 dias
                      $fdesde = restarDiasFecha($fecha,3);
                      $fhasta = restarDiasFecha($fecha,-3);
                      if ($que_es !=0)
                          $bd->select("SELECT * 
                                     FROM pedidos_estudio LEFT JOIN pedidos_estudio_detalle USING(idpedido_estudio) 
                                          LEFT JOIN resultados_estudio USING (idpedido_estudio_detalle) 
                                          LEFT JOIN nomenclador USING (idnomenclador)
                                     WHERE idpaciente=$idpaciente AND pedidos_estudio.fecha>='".$fdesde."' AND pedidos_estudio.fecha<='".$fhasta."' AND que_es=$que_es");
                      else
                      {
                          if ($codigo_contenedor != 0)
                          {
                              $bd->select("SELECT * 
                                     FROM pedidos_estudio LEFT JOIN pedidos_estudio_detalle USING(idpedido_estudio) 
                                          LEFT JOIN resultados_estudio USING (idpedido_estudio_detalle) 
                                          LEFT JOIN nomenclador USING (idnomenclador)
                                     WHERE idpaciente=$idpaciente AND pedidos_estudio.fecha>='".$fdesde."' AND pedidos_estudio.fecha<='".$fhasta."' AND codigo_contenedor='$codigo_contenedor'");
                              
                             
                          }
                      }
                       if ($bd->numero_filas() != 0)
                                  return 1;
                              else
                                  return 0;
                  }
	    			                              		
	      }
	   
      
}
?>