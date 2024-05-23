<?
      class clase_factura_internacion       
      {
	  	  var $movimiento_internacion = '';
          var $fecha = '';
          var $fecha_internacion = '';
          var $fecha_alta = '';
          var $idosocial = '';
          var $idpaciente = '';
          var $importe = '';
          var $impmedi = '';
          var $fecha_cierre = '';
          var $exento = '';
          var $numero_factura = '';
          var $tipo_internacion = '';
          var $tipocli = '';
          var $orden = '';
          var $idprofesional = '';
          var $bonificacion_medicamentos = '';
          var $iva1 = '';
          var $tipo_intervencion = '';
          var $idservicio = '';
          var $iddiagnostico = '';
          var $total_honorarios = '';
          var $total_gastos = '';
          var $total_precio = '';
          var $total_medicamentos = '';
          var $total_iva = '';
          var $comentarios = '';
          var $diagnostico_final = '';
          var $idepisodio = '';
          var $periodo_mes = '';
          var $periodo_ano = '';
          var $estado = '';
          var $fecha_modificacion = '';
          
      
      var $arreglo_foraneo_idepisodio='';
      	     
      
         function clase_factura_internacion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM factura_internacion WHERE movimiento_internacion=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->movimiento_internacion=$arreglo['movimiento_internacion'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->fecha_internacion=$arreglo['fecha_internacion'];
      	     $this->fecha_alta=$arreglo['fecha_alta'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->importe=$arreglo['importe'];
      	     $this->impmedi=$arreglo['impmedi'];
      	     $this->fecha_cierre=$arreglo['fecha_cierre'];
      	     $this->exento=$arreglo['exento'];
      	     $this->numero_factura=$arreglo['numero_factura'];
      	     $this->tipo_internacion=$arreglo['tipo_internacion'];
      	     $this->tipocli=$arreglo['tipocli'];
      	     $this->orden=$arreglo['orden'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->bonificacion_medicamentos=$arreglo['bonificacion_medicamentos'];
      	     $this->iva1=$arreglo['iva1'];
      	     $this->tipo_intervencion=$arreglo['tipo_intervencion'];
      	     $this->idservicio=$arreglo['idservicio'];
      	     $this->iddiagnostico=$arreglo['iddiagnostico'];
      	     $this->total_honorarios=$arreglo['total_honorarios'];
      	     $this->total_gastos=$arreglo['total_gastos'];
      	     $this->total_precio=$arreglo['total_precio'];
      	     $this->total_medicamentos=$arreglo['total_medicamentos'];
      	     $this->total_iva=$arreglo['total_iva'];
      	     $this->comentarios=$arreglo['comentarios'];
      	     $this->diagnostico_final=$arreglo['diagnostico_final'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->periodo_mes=$arreglo['periodo_mes'];
      	     $this->periodo_ano=$arreglo['periodo_ano'];
      	     $this->estado=$arreglo['estado'];
      	     $this->fecha_modificacion=$arreglo['fecha_modificacion'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->movimiento_internacion==0 || $this->movimiento_internacion=='' ) {
      	      if ($bd->select("INSERT INTO factura_internacion(fecha,fecha_internacion,fecha_alta,idosocial,idpaciente,importe,impmedi,fecha_cierre,exento,numero_factura,tipo_internacion,tipocli,orden,idprofesional,bonificacion_medicamentos,iva1,tipo_intervencion,idservicio,iddiagnostico,total_honorarios,total_gastos,total_precio,total_medicamentos,total_iva,comentarios,diagnostico_final,idepisodio,periodo_mes,periodo_ano,estado,fecha_modificacion) VALUES('".$this->fecha."','".$this->fecha_internacion."','".$this->fecha_alta."','".$this->idosocial."','".$this->idpaciente."','".$this->importe."','".$this->impmedi."','".$this->fecha_cierre."','".$this->exento."','".$this->numero_factura."','".$this->tipo_internacion."','".$this->tipocli."','".$this->orden."','".$this->idprofesional."','".$this->bonificacion_medicamentos."','".$this->iva1."','".$this->tipo_intervencion."','".$this->idservicio."','".$this->iddiagnostico."','".$this->total_honorarios."','".$this->total_gastos."','".$this->total_precio."','".$this->total_medicamentos."','".$this->total_iva."','".$this->comentarios."','".$this->diagnostico_final."','".$this->idepisodio."','".$this->periodo_mes."','".$this->periodo_ano."','".$this->estado."','".$this->fecha_modificacion."')"))
      	      {
      	          $this->movimiento_internacion=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE factura_internacion SET fecha='".$this->fecha."',fecha_internacion='".$this->fecha_internacion."',fecha_alta='".$this->fecha_alta."',idosocial='".$this->idosocial."',idpaciente='".$this->idpaciente."',importe='".$this->importe."',impmedi='".$this->impmedi."',fecha_cierre='".$this->fecha_cierre."',exento='".$this->exento."',numero_factura='".$this->numero_factura."',tipo_internacion='".$this->tipo_internacion."',tipocli='".$this->tipocli."',orden='".$this->orden."',idprofesional='".$this->idprofesional."',bonificacion_medicamentos='".$this->bonificacion_medicamentos."',iva1='".$this->iva1."',tipo_intervencion='".$this->tipo_intervencion."',idservicio='".$this->idservicio."',iddiagnostico='".$this->iddiagnostico."',total_honorarios='".$this->total_honorarios."',total_gastos='".$this->total_gastos."',total_precio='".$this->total_precio."',total_medicamentos='".$this->total_medicamentos."',total_iva='".$this->total_iva."',comentarios='".$this->comentarios."',diagnostico_final='".$this->diagnostico_final."',idepisodio='".$this->idepisodio."',periodo_mes='".$this->periodo_mes."',periodo_ano='".$this->periodo_ano."',estado='".$this->estado."',fecha_modificacion='".$this->fecha_modificacion."' WHERE movimiento_internacion='".$this->movimiento_internacion."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function movimiento_internacion()
          {
               return $this->movimiento_internacion;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function fecha_internacion()
          {
               return $this->fecha_internacion;
          }
          function fecha_alta()
          {
               return $this->fecha_alta;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function importe()
          {
               return $this->importe;
          }
          function impmedi()
          {
               return $this->impmedi;
          }
          function fecha_cierre()
          {
               return $this->fecha_cierre;
          }
          function exento()
          {
               return $this->exento;
          }
          function numero_factura()
          {
               return $this->numero_factura;
          }
          function tipo_internacion()
          {
               return $this->tipo_internacion;
          }
          function tipocli()
          {
               return $this->tipocli;
          }
          function orden()
          {
               return $this->orden;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function bonificacion_medicamentos()
          {
               return $this->bonificacion_medicamentos;
          }
          function iva1()
          {
               return $this->iva1;
          }
          function tipo_intervencion()
          {
               return $this->tipo_intervencion;
          }
          function idservicio()
          {
               return $this->idservicio;
          }
          function iddiagnostico()
          {
               return $this->iddiagnostico;
          }
          function total_honorarios()
          {
               return $this->total_honorarios;
          }
          function total_gastos()
          {
               return $this->total_gastos;
          }
          function total_precio()
          {
               return $this->total_precio;
          }
          function total_medicamentos()
          {
               return $this->total_medicamentos;
          }
          function total_iva()
          {
               return $this->total_iva;
          }
          function comentarios()
          {
               return $this->comentarios;
          }
          function diagnostico_final()
          {
               return $this->diagnostico_final;
          }
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function periodo_mes()
          {
               return $this->periodo_mes;
          }
          function periodo_ano()
          {
               return $this->periodo_ano;
          }
          function estado()
          {
               return $this->estado;
          }
          function fecha_modificacion()
          {
               return $this->fecha_modificacion;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      
          function movimiento_internacion_asigna($campo)
          {
               $this->movimiento_internacion=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function fecha_internacion_asigna($campo)
          {
               $this->fecha_internacion=$campo;
               
          }
          function fecha_alta_asigna($campo)
          {
               $this->fecha_alta=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function importe_asigna($campo)
          {
               $this->importe=$campo;
               
          }
          function impmedi_asigna($campo)
          {
               $this->impmedi=$campo;
               
          }
          function fecha_cierre_asigna($campo)
          {
               $this->fecha_cierre=$campo;
               
          }
          function exento_asigna($campo)
          {
               $this->exento=$campo;
               
          }
          function numero_factura_asigna($campo)
          {
               $this->numero_factura=$campo;
               
          }
          function tipo_internacion_asigna($campo)
          {
               $this->tipo_internacion=$campo;
               
          }
          function tipocli_asigna($campo)
          {
               $this->tipocli=$campo;
               
          }
          function orden_asigna($campo)
          {
               $this->orden=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function bonificacion_medicamentos_asigna($campo)
          {
               $this->bonificacion_medicamentos=$campo;
               
          }
          function iva1_asigna($campo)
          {
               $this->iva1=$campo;
               
          }
          function tipo_intervencion_asigna($campo)
          {
               $this->tipo_intervencion=$campo;
               
          }
          function idservicio_asigna($campo)
          {
               $this->idservicio=$campo;
               
          }
          function iddiagnostico_asigna($campo)
          {
               $this->iddiagnostico=$campo;
               
          }
          function total_honorarios_asigna($campo)
          {
               $this->total_honorarios=$campo;
               
          }
          function total_gastos_asigna($campo)
          {
               $this->total_gastos=$campo;
               
          }
          function total_precio_asigna($campo)
          {
               $this->total_precio=$campo;
               
          }
          function total_medicamentos_asigna($campo)
          {
               $this->total_medicamentos=$campo;
               
          }
          function total_iva_asigna($campo)
          {
               $this->total_iva=$campo;
               
          }
          function comentarios_asigna($campo)
          {
               $this->comentarios=$campo;
               
          }
          function diagnostico_final_asigna($campo)
          {
               $this->diagnostico_final=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function periodo_mes_asigna($campo)
          {
               $this->periodo_mes=$campo;
               
          }
          function periodo_ano_asigna($campo)
          {
               $this->periodo_ano=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function fecha_modificacion_asigna($campo)
          {
               $this->fecha_modificacion=$campo;
               
          }
          
      	  function episodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM factura_internacion WHERE idepisodio=$idepisodio");								
	    		$this->arreglo_foraneo_idepisodio = $bd->registro();		                              		
			}
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM factura_internacion WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
	    function buscar_factura_segun_detalle($id)
            {
                $bd = new baseDatos();
		$bd->Conectarse();		    
		$bd->select("SELECT factura_internacion.* 
                            FROM factura_internacion LEFT JOIN factura_internacion_detalle USING(movimiento_internacion)
                            WHERE idfactura_internacion_detalle=$id");
                self::asignar($bd->registro());
            }
      
}
?>