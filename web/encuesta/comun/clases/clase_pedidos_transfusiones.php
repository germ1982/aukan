<?
      class clase_pedidos_transfusiones       
      {
	      var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $texto_tesauro = '';
          var $descriptionid = '';
          var $subsetid = '';
          var $hto_valor = '';
          var $hto_fecha = '';
          var $quick_valor = '';
          var $quick_fecha = '';
          var $hb_valor = '';
          var $hb_fecha = '';
          var $kptt_valor = '';
          var $kptt_fecha = '';
          var $plaquetas_valor = '';
          var $plaquetas_fecha = '';
          var $tp_valor = '';
          var $tp_fecha = '';
          var $globulos_blancos_valor = '';
          var $globulos_blancos_fecha = '';
          var $fibrinogeno_valor = '';
          var $fibrinogeno_fecha = '';
          var $pedido_interconsulta = '';
          var $globulos_rojos_desplasmatizados_unidad = '';
          var $globulos_rojos_desplasmatizados_ml = '';
          var $crioprecipitado_unidad = '';
          var $crioprecipitado_ml = '';
          var $plasma_fresco_congelado_unidad = '';
          var $plasma_fresco_congelado_ml = '';
          var $plasma_modificado_unidad = '';
          var $plasma_modificado_ml = '';
          var $plaquetas_unidad = '';
          var $plaquetas_ml = '';
          var $globulos_rojos_autologos_unidad = '';
          var $globulos_rojos_autologos_ml = '';
          var $plaquetas_aferesis_unidad = '';
          var $plaquetas_aferesis_ml = '';
          var $plasma_autologo_unidad = '';
          var $plasma_autologo_ml = '';
          var $irradiados = '';
          var $desleucocitados = '';
          var $fraccionado_pediatria = '';
          var $caracter_transfusion = '';
          var $caracter_transfusion_fecha = '';
          var $caracter_transfusion_hora = '';
          var $arreglo_foraneo_idepisodio='';
      	     
      
         function clase_pedidos_transfusiones($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pedidos_transfusiones WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->texto_tesauro=$arreglo['texto_tesauro'];
      	     $this->descriptionid=$arreglo['descriptionid'];
      	     $this->subsetid=$arreglo['subsetid'];
      	     $this->hto_valor=$arreglo['hto_valor'];
      	     $this->hto_fecha=$arreglo['hto_fecha'];
      	     $this->quick_valor=$arreglo['quick_valor'];
      	     $this->quick_fecha=$arreglo['quick_fecha'];
      	     $this->hb_valor=$arreglo['hb_valor'];
      	     $this->hb_fecha=$arreglo['hb_fecha'];
      	     $this->kptt_valor=$arreglo['kptt_valor'];
      	     $this->kptt_fecha=$arreglo['kptt_fecha'];
      	     $this->plaquetas_valor=$arreglo['plaquetas_valor'];
      	     $this->plaquetas_fecha=$arreglo['plaquetas_fecha'];
      	     $this->tp_valor=$arreglo['tp_valor'];
      	     $this->tp_fecha=$arreglo['tp_fecha'];
      	     $this->globulos_blancos_valor=$arreglo['globulos_blancos_valor'];
      	     $this->globulos_blancos_fecha=$arreglo['globulos_blancos_fecha'];
      	     $this->fibrinogeno_valor=$arreglo['fibrinogeno_valor'];
      	     $this->fibrinogeno_fecha=$arreglo['fibrinogeno_fecha'];
      	     $this->pedido_interconsulta=$arreglo['pedido_interconsulta'];
      	     $this->globulos_rojos_desplasmatizados_unidad=$arreglo['globulos_rojos_desplasmatizados_unidad'];
      	     $this->globulos_rojos_desplasmatizados_ml=$arreglo['globulos_rojos_desplasmatizados_ml'];
      	     $this->crioprecipitado_unidad=$arreglo['crioprecipitado_unidad'];
      	     $this->crioprecipitado_ml=$arreglo['crioprecipitado_ml'];
      	     $this->plasma_fresco_congelado_unidad=$arreglo['plasma_fresco_congelado_unidad'];
      	     $this->plasma_fresco_congelado_ml=$arreglo['plasma_fresco_congelado_ml'];
      	     $this->plasma_modificado_unidad=$arreglo['plasma_modificado_unidad'];
      	     $this->plasma_modificado_ml=$arreglo['plasma_modificado_ml'];
      	     $this->plaquetas_unidad=$arreglo['plaquetas_unidad'];
      	     $this->plaquetas_ml=$arreglo['plaquetas_ml'];
      	     $this->globulos_rojos_autologos_unidad=$arreglo['globulos_rojos_autologos_unidad'];
      	     $this->globulos_rojos_autologos_ml=$arreglo['globulos_rojos_autologos_ml'];
      	     $this->plaquetas_aferesis_unidad=$arreglo['plaquetas_aferesis_unidad'];
      	     $this->plaquetas_aferesis_ml=$arreglo['plaquetas_aferesis_ml'];
      	     $this->plasma_autologo_unidad=$arreglo['plasma_autologo_unidad'];
      	     $this->plasma_autologo_ml=$arreglo['plasma_autologo_ml'];
      	     $this->irradiados=$arreglo['irradiados'];
      	     $this->desleucocitados=$arreglo['desleucocitados'];
      	     $this->fraccionado_pediatria=$arreglo['fraccionado_pediatria'];
      	     $this->caracter_transfusion=$arreglo['caracter_transfusion'];
      	     $this->caracter_transfusion_fecha=$arreglo['caracter_transfusion_fecha'];
      	     $this->caracter_transfusion_hora=$arreglo['caracter_transfusion_hora'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO pedidos_transfusiones(idepisodio,idprofesional,fecha,hora,texto_tesauro,descriptionid,subsetid,hto_valor,hto_fecha,quick_valor,quick_fecha,hb_valor,hb_fecha,kptt_valor,kptt_fecha,plaquetas_valor,plaquetas_fecha,tp_valor,tp_fecha,globulos_blancos_valor,globulos_blancos_fecha,fibrinogeno_valor,fibrinogeno_fecha,pedido_interconsulta,globulos_rojos_desplasmatizados_unidad,globulos_rojos_desplasmatizados_ml,crioprecipitado_unidad,crioprecipitado_ml,plasma_fresco_congelado_unidad,plasma_fresco_congelado_ml,plasma_modificado_unidad,plasma_modificado_ml,plaquetas_unidad,plaquetas_ml,globulos_rojos_autologos_unidad,globulos_rojos_autologos_ml,plaquetas_aferesis_unidad,plaquetas_aferesis_ml,plasma_autologo_unidad,plasma_autologo_ml,irradiados,desleucocitados,fraccionado_pediatria,caracter_transfusion,caracter_transfusion_fecha,caracter_transfusion_hora) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->fecha."','".$this->hora."','".$this->texto_tesauro."','".$this->descriptionid."','".$this->subsetid."','".$this->hto_valor."','".$this->hto_fecha."','".$this->quick_valor."','".$this->quick_fecha."','".$this->hb_valor."','".$this->hb_fecha."','".$this->kptt_valor."','".$this->kptt_fecha."','".$this->plaquetas_valor."','".$this->plaquetas_fecha."','".$this->tp_valor."','".$this->tp_fecha."','".$this->globulos_blancos_valor."','".$this->globulos_blancos_fecha."','".$this->fibrinogeno_valor."','".$this->fibrinogeno_fecha."','".$this->pedido_interconsulta."','".$this->globulos_rojos_desplasmatizados_unidad."','".$this->globulos_rojos_desplasmatizados_ml."','".$this->crioprecipitado_unidad."','".$this->crioprecipitado_ml."','".$this->plasma_fresco_congelado_unidad."','".$this->plasma_fresco_congelado_ml."','".$this->plasma_modificado_unidad."','".$this->plasma_modificado_ml."','".$this->plaquetas_unidad."','".$this->plaquetas_ml."','".$this->globulos_rojos_autologos_unidad."','".$this->globulos_rojos_autologos_ml."','".$this->plaquetas_aferesis_unidad."','".$this->plaquetas_aferesis_ml."','".$this->plasma_autologo_unidad."','".$this->plasma_autologo_ml."','".$this->irradiados."','".$this->desleucocitados."','".$this->fraccionado_pediatria."','".$this->caracter_transfusion."','".$this->caracter_transfusion_fecha."','".$this->caracter_transfusion_hora."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pedidos_transfusiones SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',hora='".$this->hora."',texto_tesauro='".$this->texto_tesauro."',descriptionid='".$this->descriptionid."',subsetid='".$this->subsetid."',hto_valor='".$this->hto_valor."',hto_fecha='".$this->hto_fecha."',quick_valor='".$this->quick_valor."',quick_fecha='".$this->quick_fecha."',hb_valor='".$this->hb_valor."',hb_fecha='".$this->hb_fecha."',kptt_valor='".$this->kptt_valor."',kptt_fecha='".$this->kptt_fecha."',plaquetas_valor='".$this->plaquetas_valor."',plaquetas_fecha='".$this->plaquetas_fecha."',tp_valor='".$this->tp_valor."',tp_fecha='".$this->tp_fecha."',globulos_blancos_valor='".$this->globulos_blancos_valor."',globulos_blancos_fecha='".$this->globulos_blancos_fecha."',fibrinogeno_valor='".$this->fibrinogeno_valor."',fibrinogeno_fecha='".$this->fibrinogeno_fecha."',pedido_interconsulta='".$this->pedido_interconsulta."',globulos_rojos_desplasmatizados_unidad='".$this->globulos_rojos_desplasmatizados_unidad."',globulos_rojos_desplasmatizados_ml='".$this->globulos_rojos_desplasmatizados_ml."',crioprecipitado_unidad='".$this->crioprecipitado_unidad."',crioprecipitado_ml='".$this->crioprecipitado_ml."',plasma_fresco_congelado_unidad='".$this->plasma_fresco_congelado_unidad."',plasma_fresco_congelado_ml='".$this->plasma_fresco_congelado_ml."',plasma_modificado_unidad='".$this->plasma_modificado_unidad."',plasma_modificado_ml='".$this->plasma_modificado_ml."',plaquetas_unidad='".$this->plaquetas_unidad."',plaquetas_ml='".$this->plaquetas_ml."',globulos_rojos_autologos_unidad='".$this->globulos_rojos_autologos_unidad."',globulos_rojos_autologos_ml='".$this->globulos_rojos_autologos_ml."',plaquetas_aferesis_unidad='".$this->plaquetas_aferesis_unidad."',plaquetas_aferesis_ml='".$this->plaquetas_aferesis_ml."',plasma_autologo_unidad='".$this->plasma_autologo_unidad."',plasma_autologo_ml='".$this->plasma_autologo_ml."',irradiados='".$this->irradiados."',desleucocitados='".$this->desleucocitados."',fraccionado_pediatria='".$this->fraccionado_pediatria."',caracter_transfusion='".$this->caracter_transfusion."',caracter_transfusion_fecha='".$this->caracter_transfusion_fecha."',caracter_transfusion_hora='".$this->caracter_transfusion_hora."' WHERE id='".$this->id."'"))
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
          function texto_tesauro()
          {
               return $this->texto_tesauro;
          }
          function descriptionid()
          {
               return $this->descriptionid;
          }
          function subsetid()
          {
               return $this->subsetid;
          }
          function hto_valor()
          {
               return $this->hto_valor;
          }
          function hto_fecha()
          {
               return $this->hto_fecha;
          }
          function quick_valor()
          {
               return $this->quick_valor;
          }
          function quick_fecha()
          {
               return $this->quick_fecha;
          }
          function hb_valor()
          {
               return $this->hb_valor;
          }
          function hb_fecha()
          {
               return $this->hb_fecha;
          }
          function kptt_valor()
          {
               return $this->kptt_valor;
          }
          function kptt_fecha()
          {
               return $this->kptt_fecha;
          }
          function plaquetas_valor()
          {
               return $this->plaquetas_valor;
          }
          function plaquetas_fecha()
          {
               return $this->plaquetas_fecha;
          }
          function tp_valor()
          {
               return $this->tp_valor;
          }
          function tp_fecha()
          {
               return $this->tp_fecha;
          }
          function globulos_blancos_valor()
          {
               return $this->globulos_blancos_valor;
          }
          function globulos_blancos_fecha()
          {
               return $this->globulos_blancos_fecha;
          }
          function fibrinogeno_valor()
          {
               return $this->fibrinogeno_valor;
          }
          function fibrinogeno_fecha()
          {
               return $this->fibrinogeno_fecha;
          }
          function pedido_interconsulta()
          {
               return $this->pedido_interconsulta;
          }
          function globulos_rojos_desplasmatizados_unidad()
          {
               return $this->globulos_rojos_desplasmatizados_unidad;
          }
          function globulos_rojos_desplasmatizados_ml()
          {
               return $this->globulos_rojos_desplasmatizados_ml;
          }
          function crioprecipitado_unidad()
          {
               return $this->crioprecipitado_unidad;
          }
          function crioprecipitado_ml()
          {
               return $this->crioprecipitado_ml;
          }
          function plasma_fresco_congelado_unidad()
          {
               return $this->plasma_fresco_congelado_unidad;
          }
          function plasma_fresco_congelado_ml()
          {
               return $this->plasma_fresco_congelado_ml;
          }
          function plasma_modificado_unidad()
          {
               return $this->plasma_modificado_unidad;
          }
          function plasma_modificado_ml()
          {
               return $this->plasma_modificado_ml;
          }
          function plaquetas_unidad()
          {
               return $this->plaquetas_unidad;
          }
          function plaquetas_ml()
          {
               return $this->plaquetas_ml;
          }
          function globulos_rojos_autologos_unidad()
          {
               return $this->globulos_rojos_autologos_unidad;
          }
          function globulos_rojos_autologos_ml()
          {
               return $this->globulos_rojos_autologos_ml;
          }
          function plaquetas_aferesis_unidad()
          {
               return $this->plaquetas_aferesis_unidad;
          }
          function plaquetas_aferesis_ml()
          {
               return $this->plaquetas_aferesis_ml;
          }
          function plasma_autologo_unidad()
          {
               return $this->plasma_autologo_unidad;
          }
          function plasma_autologo_ml()
          {
               return $this->plasma_autologo_ml;
          }
          function irradiados()
          {
               return $this->irradiados;
          }
          function desleucocitados()
          {
               return $this->desleucocitados;
          }
          function fraccionado_pediatria()
          {
               return $this->fraccionado_pediatria;
          }
          function caracter_transfusion()
          {
               return $this->caracter_transfusion;
          }
          function caracter_transfusion_fecha()
          {
               return $this->caracter_transfusion_fecha;
          }
          function caracter_transfusion_hora()
          {
               return $this->caracter_transfusion_hora;
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
          function texto_tesauro_asigna($campo)
          {
               $this->texto_tesauro=$campo;
               
          }
          function descriptionid_asigna($campo)
          {
               $this->descriptionid=$campo;
               
          }
          function subsetid_asigna($campo)
          {
               $this->subsetid=$campo;
               
          }
          function hto_valor_asigna($campo)
          {
               $this->hto_valor=$campo;
               
          }
          function hto_fecha_asigna($campo)
          {
               $this->hto_fecha=$campo;
               
          }
          function quick_valor_asigna($campo)
          {
               $this->quick_valor=$campo;
               
          }
          function quick_fecha_asigna($campo)
          {
               $this->quick_fecha=$campo;
               
          }
          function hb_valor_asigna($campo)
          {
               $this->hb_valor=$campo;
               
          }
          function hb_fecha_asigna($campo)
          {
               $this->hb_fecha=$campo;
               
          }
          function kptt_valor_asigna($campo)
          {
               $this->kptt_valor=$campo;
               
          }
          function kptt_fecha_asigna($campo)
          {
               $this->kptt_fecha=$campo;
               
          }
          function plaquetas_valor_asigna($campo)
          {
               $this->plaquetas_valor=$campo;
               
          }
          function plaquetas_fecha_asigna($campo)
          {
               $this->plaquetas_fecha=$campo;
               
          }
          function tp_valor_asigna($campo)
          {
               $this->tp_valor=$campo;
               
          }
          function tp_fecha_asigna($campo)
          {
               $this->tp_fecha=$campo;
               
          }
          function globulos_blancos_valor_asigna($campo)
          {
               $this->globulos_blancos_valor=$campo;
               
          }
          function globulos_blancos_fecha_asigna($campo)
          {
               $this->globulos_blancos_fecha=$campo;
               
          }
          function fibrinogeno_valor_asigna($campo)
          {
               $this->fibrinogeno_valor=$campo;
               
          }
          function fibrinogeno_fecha_asigna($campo)
          {
               $this->fibrinogeno_fecha=$campo;
               
          }
          function pedido_interconsulta_asigna($campo)
          {
               $this->pedido_interconsulta=$campo;
               
          }
          function globulos_rojos_desplasmatizados_unidad_asigna($campo)
          {
               $this->globulos_rojos_desplasmatizados_unidad=$campo;
               
          }
          function globulos_rojos_desplasmatizados_ml_asigna($campo)
          {
               $this->globulos_rojos_desplasmatizados_ml=$campo;
               
          }
          function crioprecipitado_unidad_asigna($campo)
          {
               $this->crioprecipitado_unidad=$campo;
               
          }
          function crioprecipitado_ml_asigna($campo)
          {
               $this->crioprecipitado_ml=$campo;
               
          }
          function plasma_fresco_congelado_unidad_asigna($campo)
          {
               $this->plasma_fresco_congelado_unidad=$campo;
               
          }
          function plasma_fresco_congelado_ml_asigna($campo)
          {
               $this->plasma_fresco_congelado_ml=$campo;
               
          }
          function plasma_modificado_unidad_asigna($campo)
          {
               $this->plasma_modificado_unidad=$campo;
               
          }
          function plasma_modificado_ml_asigna($campo)
          {
               $this->plasma_modificado_ml=$campo;
               
          }
          function plaquetas_unidad_asigna($campo)
          {
               $this->plaquetas_unidad=$campo;
               
          }
          function plaquetas_ml_asigna($campo)
          {
               $this->plaquetas_ml=$campo;
               
          }
          function globulos_rojos_autologos_unidad_asigna($campo)
          {
               $this->globulos_rojos_autologos_unidad=$campo;
               
          }
          function globulos_rojos_autologos_ml_asigna($campo)
          {
               $this->globulos_rojos_autologos_ml=$campo;
               
          }
          function plaquetas_aferesis_unidad_asigna($campo)
          {
               $this->plaquetas_aferesis_unidad=$campo;
               
          }
          function plaquetas_aferesis_ml_asigna($campo)
          {
               $this->plaquetas_aferesis_ml=$campo;
               
          }
          function plasma_autologo_unidad_asigna($campo)
          {
               $this->plasma_autologo_unidad=$campo;
               
          }
          function plasma_autologo_ml_asigna($campo)
          {
               $this->plasma_autologo_ml=$campo;
               
          }
          function irradiados_asigna($campo)
          {
               $this->irradiados=$campo;
               
          }
          function desleucocitados_asigna($campo)
          {
               $this->desleucocitados=$campo;
               
          }
          function fraccionado_pediatria_asigna($campo)
          {
               $this->fraccionado_pediatria=$campo;
               
          }
          function caracter_transfusion_asigna($campo)
          {
               $this->caracter_transfusion=$campo;
               
          }
          function caracter_transfusion_fecha_asigna($campo)
          {
               $this->caracter_transfusion_fecha=$campo;
               
          }
          function caracter_transfusion_hora_asigna($campo)
          {
               $this->caracter_transfusion_hora=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM pedidos_transfusiones WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
			
      
}
?>