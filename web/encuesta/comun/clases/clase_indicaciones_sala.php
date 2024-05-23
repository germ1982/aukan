<?
      class clase_indicaciones_sala       
      {
	  var $idindicacion_sala = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $hora = '';
          var $fecha = '';
          var $indicacion = '';
          var $intervalo = '';
          var $hora_inicio = '';
          var $iddroga = '';
          var $dosis = '';
          var $via = '';
          var $observaciones = '';
          var $unidad_medida = '';
          var $estado_dia = '';
          var $vehiculo = '';
          var $plan_parenteral = '';
          var $paralelo = '';
          var $idpresentacion = '';
          var $idindicacionsala = '';
          var $tipo_dilucion = '';
          var $primer_volumen = '';
          var $en_bolo = '';
          var $otro_primer_volumen = '';
          var $volumen_administrar = '';
          var $goteo = '';
          var $bic = '';
          var $a_pasar = '';
          var $dosis_unidad_tipo = '';
          var $dosis_unidad = '';
          var $segun_objetivo = '';
          var $cloruro_sodio = '';
          var $cloruro_sodio_volumen = '';
          var $cloruro_potasio = '';
          var $cloruro_potasio_volumen = '';
          var $sulfato_magnesio = '';
          var $sulfato_magnesio_volumen = '';
          var $fosfato_sodio = '';
          var $fosfato_sodio_volumen = '';
          var $vitamina_b12 = '';
          var $vitamina_b12_volumen = '';
          var $complejo_vitaminico = '';
          var $complejo_vitaminico_volumen = '';
          var $gluconato_calcio = '';
          var $gluconato_calcio_volumen = '';
          var $agua_destilada = '';
          var $agua_destilada_volumen = '';
          var $plan_hidratacion = '';
          
      
      var $idepisodio='';
      	     var $idindicacionsala='';
      	     
      
         function clase_indicaciones_sala($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM indicaciones_sala WHERE idindicacion_sala=$id");
      	     $arreglo=$bd->registro();
      	     $this->idindicacion_sala=$arreglo['idindicacion_sala'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->hora=$arreglo['hora'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->indicacion=$arreglo['indicacion'];
      	     $this->intervalo=$arreglo['intervalo'];
      	     $this->hora_inicio=$arreglo['hora_inicio'];
      	     $this->iddroga=$arreglo['iddroga'];
      	     $this->dosis=$arreglo['dosis'];
      	     $this->via=$arreglo['via'];
      	     $this->observaciones=$arreglo['observaciones'];
      	     $this->unidad_medida=$arreglo['unidad_medida'];
      	     $this->estado_dia=$arreglo['estado_dia'];
      	     $this->vehiculo=$arreglo['vehiculo'];
      	     $this->plan_parenteral=$arreglo['plan_parenteral'];
      	     $this->paralelo=$arreglo['paralelo'];
      	     $this->idpresentacion=$arreglo['idpresentacion'];
      	     $this->idindicacionsala=$arreglo['idindicacionsala'];
      	     $this->tipo_dilucion=$arreglo['tipo_dilucion'];
      	     $this->primer_volumen=$arreglo['primer_volumen'];
      	     $this->en_bolo=$arreglo['en_bolo'];
      	     $this->otro_primer_volumen=$arreglo['otro_primer_volumen'];
      	     $this->volumen_administrar=$arreglo['volumen_administrar'];
      	     $this->goteo=$arreglo['goteo'];
      	     $this->bic=$arreglo['bic'];
      	     $this->a_pasar=$arreglo['a_pasar'];
      	     $this->dosis_unidad_tipo=$arreglo['dosis_unidad_tipo'];
      	     $this->dosis_unidad=$arreglo['dosis_unidad'];
      	     $this->segun_objetivo=$arreglo['segun_objetivo'];
      	     $this->cloruro_sodio=$arreglo['cloruro_sodio'];
      	     $this->cloruro_sodio_volumen=$arreglo['cloruro_sodio_volumen'];
      	     $this->cloruro_potasio=$arreglo['cloruro_potasio'];
      	     $this->cloruro_potasio_volumen=$arreglo['cloruro_potasio_volumen'];
      	     $this->sulfato_magnesio=$arreglo['sulfato_magnesio'];
      	     $this->sulfato_magnesio_volumen=$arreglo['sulfato_magnesio_volumen'];
      	     $this->fosfato_sodio=$arreglo['fosfato_sodio'];
      	     $this->fosfato_sodio_volumen=$arreglo['fosfato_sodio_volumen'];
      	     $this->vitamina_b12=$arreglo['vitamina_b12'];
      	     $this->vitamina_b12_volumen=$arreglo['vitamina_b12_volumen'];
      	     $this->complejo_vitaminico=$arreglo['complejo_vitaminico'];
      	     $this->complejo_vitaminico_volumen=$arreglo['complejo_vitaminico_volumen'];
      	     $this->gluconato_calcio=$arreglo['gluconato_calcio'];
      	     $this->gluconato_calcio_volumen=$arreglo['gluconato_calcio_volumen'];
      	     $this->agua_destilada=$arreglo['agua_destilada'];
      	     $this->agua_destilada_volumen=$arreglo['agua_destilada_volumen'];
      	     $this->plan_hidratacion=$arreglo['plan_hidratacion'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idindicacion_sala==0 || $this->idindicacion_sala=='' ) {
      	      if ($bd->select("INSERT INTO indicaciones_sala(idepisodio,idprofesional,hora,fecha,indicacion,intervalo,hora_inicio,iddroga,dosis,via,observaciones,unidad_medida,estado_dia,vehiculo,plan_parenteral,paralelo,idpresentacion,idindicacionsala,tipo_dilucion,primer_volumen,en_bolo,otro_primer_volumen,volumen_administrar,goteo,bic,a_pasar,dosis_unidad_tipo,dosis_unidad,segun_objetivo,cloruro_sodio,cloruro_sodio_volumen,cloruro_potasio,cloruro_potasio_volumen,sulfato_magnesio,sulfato_magnesio_volumen,fosfato_sodio,fosfato_sodio_volumen,vitamina_b12,vitamina_b12_volumen,complejo_vitaminico,complejo_vitaminico_volumen,gluconato_calcio,gluconato_calcio_volumen,agua_destilada,agua_destilada_volumen,plan_hidratacion) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->hora."','".$this->fecha."','".$this->indicacion."','".$this->intervalo."','".$this->hora_inicio."','".$this->iddroga."','".$this->dosis."','".$this->via."','".$this->observaciones."','".$this->unidad_medida."','".$this->estado_dia."','".$this->vehiculo."','".$this->plan_parenteral."','".$this->paralelo."','".$this->idpresentacion."','".$this->idindicacionsala."','".$this->tipo_dilucion."','".$this->primer_volumen."','".$this->en_bolo."','".$this->otro_primer_volumen."','".$this->volumen_administrar."','".$this->goteo."','".$this->bic."','".$this->a_pasar."','".$this->dosis_unidad_tipo."','".$this->dosis_unidad."','".$this->segun_objetivo."','".$this->cloruro_sodio."','".$this->cloruro_sodio_volumen."','".$this->cloruro_potasio."','".$this->cloruro_potasio_volumen."','".$this->sulfato_magnesio."','".$this->sulfato_magnesio_volumen."','".$this->fosfato_sodio."','".$this->fosfato_sodio_volumen."','".$this->vitamina_b12."','".$this->vitamina_b12_volumen."','".$this->complejo_vitaminico."','".$this->complejo_vitaminico_volumen."','".$this->gluconato_calcio."','".$this->gluconato_calcio_volumen."','".$this->agua_destilada."','".$this->agua_destilada_volumen."','".$this->plan_hidratacion."')"))
      	      {
      	          $this->idindicacion_sala=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE indicaciones_sala SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',hora='".$this->hora."',fecha='".$this->fecha."',indicacion='".$this->indicacion."',intervalo='".$this->intervalo."',hora_inicio='".$this->hora_inicio."',iddroga='".$this->iddroga."',dosis='".$this->dosis."',via='".$this->via."',observaciones='".$this->observaciones."',unidad_medida='".$this->unidad_medida."',estado_dia='".$this->estado_dia."',vehiculo='".$this->vehiculo."',plan_parenteral='".$this->plan_parenteral."',paralelo='".$this->paralelo."',idpresentacion='".$this->idpresentacion."',idindicacionsala='".$this->idindicacionsala."',tipo_dilucion='".$this->tipo_dilucion."',primer_volumen='".$this->primer_volumen."',en_bolo='".$this->en_bolo."',otro_primer_volumen='".$this->otro_primer_volumen."',volumen_administrar='".$this->volumen_administrar."',goteo='".$this->goteo."',bic='".$this->bic."',a_pasar='".$this->a_pasar."',dosis_unidad_tipo='".$this->dosis_unidad_tipo."',dosis_unidad='".$this->dosis_unidad."',segun_objetivo='".$this->segun_objetivo."',cloruro_sodio='".$this->cloruro_sodio."',cloruro_sodio_volumen='".$this->cloruro_sodio_volumen."',cloruro_potasio='".$this->cloruro_potasio."',cloruro_potasio_volumen='".$this->cloruro_potasio_volumen."',sulfato_magnesio='".$this->sulfato_magnesio."',sulfato_magnesio_volumen='".$this->sulfato_magnesio_volumen."',fosfato_sodio='".$this->fosfato_sodio."',fosfato_sodio_volumen='".$this->fosfato_sodio_volumen."',vitamina_b12='".$this->vitamina_b12."',vitamina_b12_volumen='".$this->vitamina_b12_volumen."',complejo_vitaminico='".$this->complejo_vitaminico."',complejo_vitaminico_volumen='".$this->complejo_vitaminico_volumen."',gluconato_calcio='".$this->gluconato_calcio."',gluconato_calcio_volumen='".$this->gluconato_calcio_volumen."',agua_destilada='".$this->agua_destilada."',agua_destilada_volumen='".$this->agua_destilada_volumen."',plan_hidratacion='".$this->plan_hidratacion."' WHERE idindicacion_sala='".$this->idindicacion_sala."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idindicacion_sala()
          {
               return $this->idindicacion_sala;
          }
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function hora()
          {
               return $this->hora;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function indicacion()
          {
               return $this->indicacion;
          }
          function intervalo()
          {
               return $this->intervalo;
          }
          function hora_inicio()
          {
               return $this->hora_inicio;
          }
          function iddroga()
          {
               return $this->iddroga;
          }
          function dosis()
          {
               return $this->dosis;
          }
          function via()
          {
               return $this->via;
          }
          function observaciones()
          {
               return $this->observaciones;
          }
          function unidad_medida()
          {
               return $this->unidad_medida;
          }
          function estado_dia()
          {
               return $this->estado_dia;
          }
          function vehiculo()
          {
               return $this->vehiculo;
          }
          function plan_parenteral()
          {
               return $this->plan_parenteral;
          }
          function paralelo()
          {
               return $this->paralelo;
          }
          function idpresentacion()
          {
               return $this->idpresentacion;
          }
          function idindicacionsala()
          {
               return $this->idindicacionsala;
          }
          function tipo_dilucion()
          {
               return $this->tipo_dilucion;
          }
          function primer_volumen()
          {
               return $this->primer_volumen;
          }
          function en_bolo()
          {
               return $this->en_bolo;
          }
          function otro_primer_volumen()
          {
               return $this->otro_primer_volumen;
          }
          function volumen_administrar()
          {
               return $this->volumen_administrar;
          }
          function goteo()
          {
               return $this->goteo;
          }
          function bic()
          {
               return $this->bic;
          }
          function a_pasar()
          {
               return $this->a_pasar;
          }
          function dosis_unidad_tipo()
          {
               return $this->dosis_unidad_tipo;
          }
          function dosis_unidad()
          {
               return $this->dosis_unidad;
          }
          function segun_objetivo()
          {
               return $this->segun_objetivo;
          }
          function cloruro_sodio()
          {
               return $this->cloruro_sodio;
          }
          function cloruro_sodio_volumen()
          {
               return $this->cloruro_sodio_volumen;
          }
          function cloruro_potasio()
          {
               return $this->cloruro_potasio;
          }
          function cloruro_potasio_volumen()
          {
               return $this->cloruro_potasio_volumen;
          }
          function sulfato_magnesio()
          {
               return $this->sulfato_magnesio;
          }
          function sulfato_magnesio_volumen()
          {
               return $this->sulfato_magnesio_volumen;
          }
          function fosfato_sodio()
          {
               return $this->fosfato_sodio;
          }
          function fosfato_sodio_volumen()
          {
               return $this->fosfato_sodio_volumen;
          }
          function vitamina_b12()
          {
               return $this->vitamina_b12;
          }
          function vitamina_b12_volumen()
          {
               return $this->vitamina_b12_volumen;
          }
          function complejo_vitaminico()
          {
               return $this->complejo_vitaminico;
          }
          function complejo_vitaminico_volumen()
          {
               return $this->complejo_vitaminico_volumen;
          }
          function gluconato_calcio()
          {
               return $this->gluconato_calcio;
          }
          function gluconato_calcio_volumen()
          {
               return $this->gluconato_calcio_volumen;
          }
          function agua_destilada()
          {
               return $this->agua_destilada;
          }
          function agua_destilada_volumen()
          {
               return $this->agua_destilada_volumen;
          }
          function plan_hidratacion()
          {
               return $this->plan_hidratacion;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      	     function arreglo_foraneo_idindicacionsala()
             {
                 return $this->arreglo_foraneo_idindicacionsala;
             }
             
      
          function idindicacion_sala_asigna($campo)
          {
               $this->idindicacion_sala=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function indicacion_asigna($campo)
          {
               $this->indicacion=$campo;
               
          }
          function intervalo_asigna($campo)
          {
               $this->intervalo=$campo;
               
          }
          function hora_inicio_asigna($campo)
          {
               $this->hora_inicio=$campo;
               
          }
          function iddroga_asigna($campo)
          {
               $this->iddroga=$campo;
               
          }
          function dosis_asigna($campo)
          {
               $this->dosis=$campo;
               
          }
          function via_asigna($campo)
          {
               $this->via=$campo;
               
          }
          function observaciones_asigna($campo)
          {
               $this->observaciones=$campo;
               
          }
          function unidad_medida_asigna($campo)
          {
               $this->unidad_medida=$campo;
               
          }
          function estado_dia_asigna($campo)
          {
               $this->estado_dia=$campo;
               
          }
          function vehiculo_asigna($campo)
          {
               $this->vehiculo=$campo;
               
          }
          function plan_parenteral_asigna($campo)
          {
               $this->plan_parenteral=$campo;
               
          }
          function paralelo_asigna($campo)
          {
               $this->paralelo=$campo;
               
          }
          function idpresentacion_asigna($campo)
          {
               $this->idpresentacion=$campo;
               
          }
          function idindicacionsala_asigna($campo)
          {
               $this->idindicacionsala=$campo;
               
          }
          function tipo_dilucion_asigna($campo)
          {
               $this->tipo_dilucion=$campo;
               
          }
          function primer_volumen_asigna($campo)
          {
               $this->primer_volumen=$campo;
               
          }
          function en_bolo_asigna($campo)
          {
               $this->en_bolo=$campo;
               
          }
          function otro_primer_volumen_asigna($campo)
          {
               $this->otro_primer_volumen=$campo;
               
          }
          function volumen_administrar_asigna($campo)
          {
               $this->volumen_administrar=$campo;
               
          }
          function goteo_asigna($campo)
          {
               $this->goteo=$campo;
               
          }
          function bic_asigna($campo)
          {
               $this->bic=$campo;
               
          }
          function a_pasar_asigna($campo)
          {
               $this->a_pasar=$campo;
               
          }
          function dosis_unidad_tipo_asigna($campo)
          {
               $this->dosis_unidad_tipo=$campo;
               
          }
          function dosis_unidad_asigna($campo)
          {
               $this->dosis_unidad=$campo;
               
          }
          function segun_objetivo_asigna($campo)
          {
               $this->segun_objetivo=$campo;
               
          }
          function cloruro_sodio_asigna($campo)
          {
               $this->cloruro_sodio=$campo;
               
          }
          function cloruro_sodio_volumen_asigna($campo)
          {
               $this->cloruro_sodio_volumen=$campo;
               
          }
          function cloruro_potasio_asigna($campo)
          {
               $this->cloruro_potasio=$campo;
               
          }
          function cloruro_potasio_volumen_asigna($campo)
          {
               $this->cloruro_potasio_volumen=$campo;
               
          }
          function sulfato_magnesio_asigna($campo)
          {
               $this->sulfato_magnesio=$campo;
               
          }
          function sulfato_magnesio_volumen_asigna($campo)
          {
               $this->sulfato_magnesio_volumen=$campo;
               
          }
          function fosfato_sodio_asigna($campo)
          {
               $this->fosfato_sodio=$campo;
               
          }
          function fosfato_sodio_volumen_asigna($campo)
          {
               $this->fosfato_sodio_volumen=$campo;
               
          }
          function vitamina_b12_asigna($campo)
          {
               $this->vitamina_b12=$campo;
               
          }
          function vitamina_b12_volumen_asigna($campo)
          {
               $this->vitamina_b12_volumen=$campo;
               
          }
          function complejo_vitaminico_asigna($campo)
          {
               $this->complejo_vitaminico=$campo;
               
          }
          function complejo_vitaminico_volumen_asigna($campo)
          {
               $this->complejo_vitaminico_volumen=$campo;
               
          }
          function gluconato_calcio_asigna($campo)
          {
               $this->gluconato_calcio=$campo;
               
          }
          function gluconato_calcio_volumen_asigna($campo)
          {
               $this->gluconato_calcio_volumen=$campo;
               
          }
          function agua_destilada_asigna($campo)
          {
               $this->agua_destilada=$campo;
               
          }
          function agua_destilada_volumen_asigna($campo)
          {
               $this->agua_destilada_volumen=$campo;
               
          }
          function plan_hidratacion_asigna($campo)
          {
               $this->plan_hidratacion=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicaciones_sala WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
			
	      function foranea_idindicacionsala($idindicacionsala)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicaciones_sala WHERE idindicacionsala=$idindicacionsala");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idindicacionsala = $pro;		                              		
			}
			
      
}
?>