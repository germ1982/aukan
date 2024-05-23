<?
      class clase_facturas_ambulatorio       
      {
	  var $movimiento_internacion = '';
          var $estado = '';
          var $fecha = '';
          var $idosocial = '';
          var $importe = '';
          var $exento = '';
          var $numero_factura = '';
          var $tipocli = '';
          var $iva1 = '';
          var $total_honorarios = '';
          var $total_gastos = '';
          var $total_iva = '';
          var $comentarios = '';
          var $periodo_mes = '';
          var $periodo_ano = '';
          var $fdesde = '';
          var $fhasta = '';
          var $idservicio = '';
          var $idpaciente = '';
          var $numero_siniestro = '';
          var $impmedi = '';
          var $bonificacion_medicamentos = '';
          var $total_medicamentos = '';
          
      
      
      
         function clase_facturas_ambulatorio($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM facturas_ambulatorio WHERE movimiento_internacion=$id");
      	     $arreglo=$bd->registro();
      	     $this->movimiento_internacion=$arreglo['movimiento_internacion'];
      	     $this->estado=$arreglo['estado'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->importe=$arreglo['importe'];
      	     $this->exento=$arreglo['exento'];
      	     $this->numero_factura=$arreglo['numero_factura'];
      	     $this->tipocli=$arreglo['tipocli'];
      	     $this->iva1=$arreglo['iva1'];
      	     $this->total_honorarios=$arreglo['total_honorarios'];
      	     $this->total_gastos=$arreglo['total_gastos'];
      	     $this->total_iva=$arreglo['total_iva'];
      	     $this->comentarios=$arreglo['comentarios'];
      	     $this->periodo_mes=$arreglo['periodo_mes'];
      	     $this->periodo_ano=$arreglo['periodo_ano'];
      	     $this->fdesde=$arreglo['fdesde'];
      	     $this->fhasta=$arreglo['fhasta'];
      	     $this->idservicio=$arreglo['idservicio'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->numero_siniestro=$arreglo['numero_siniestro'];
      	     $this->impmedi=$arreglo['impmedi'];
      	     $this->bonificacion_medicamentos=$arreglo['bonificacion_medicamentos'];
      	     $this->total_medicamentos=$arreglo['total_medicamentos'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->movimiento_internacion==0 || $this->movimiento_internacion=='' ) {
      	      if ($bd->select("INSERT INTO facturas_ambulatorio(estado,fecha,idosocial,importe,exento,numero_factura,tipocli,iva1,total_honorarios,total_gastos,total_iva,comentarios,periodo_mes,periodo_ano,fdesde,fhasta,idservicio,idpaciente,numero_siniestro,impmedi,bonificacion_medicamentos,total_medicamentos) VALUES('".$this->estado."','".$this->fecha."','".$this->idosocial."','".$this->importe."','".$this->exento."','".$this->numero_factura."','".$this->tipocli."','".$this->iva1."','".$this->total_honorarios."','".$this->total_gastos."','".$this->total_iva."','".$this->comentarios."','".$this->periodo_mes."','".$this->periodo_ano."','".$this->fdesde."','".$this->fhasta."','".$this->idservicio."','".$this->idpaciente."','".$this->numero_siniestro."','".$this->impmedi."','".$this->bonificacion_medicamentos."','".$this->total_medicamentos."')"))
      	      {
      	          $this->movimiento_internacion=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE facturas_ambulatorio SET estado='".$this->estado."',fecha='".$this->fecha."',idosocial='".$this->idosocial."',importe='".$this->importe."',exento='".$this->exento."',numero_factura='".$this->numero_factura."',tipocli='".$this->tipocli."',iva1='".$this->iva1."',total_honorarios='".$this->total_honorarios."',total_gastos='".$this->total_gastos."',total_iva='".$this->total_iva."',comentarios='".$this->comentarios."',periodo_mes='".$this->periodo_mes."',periodo_ano='".$this->periodo_ano."',fdesde='".$this->fdesde."',fhasta='".$this->fhasta."',idservicio='".$this->idservicio."',idpaciente='".$this->idpaciente."',numero_siniestro='".$this->numero_siniestro."',impmedi='".$this->impmedi."',bonificacion_medicamentos='".$this->bonificacion_medicamentos."',total_medicamentos='".$this->total_medicamentos."' WHERE movimiento_internacion='".$this->movimiento_internacion."'"))
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
          function estado()
          {
               return $this->estado;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function importe()
          {
               return $this->importe;
          }
          function exento()
          {
               return $this->exento;
          }
          function numero_factura()
          {
               return $this->numero_factura;
          }
          function tipocli()
          {
               return $this->tipocli;
          }
          function iva1()
          {
               return $this->iva1;
          }
          function total_honorarios()
          {
               return $this->total_honorarios;
          }
          function total_gastos()
          {
               return $this->total_gastos;
          }
          function total_iva()
          {
               return $this->total_iva;
          }
          function comentarios()
          {
               return $this->comentarios;
          }
          function periodo_mes()
          {
               return $this->periodo_mes;
          }
          function periodo_ano()
          {
               return $this->periodo_ano;
          }
          function fdesde()
          {
               return $this->fdesde;
          }
          function fhasta()
          {
               return $this->fhasta;
          }
          function idservicio()
          {
               return $this->idservicio;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function numero_siniestro()
          {
               return $this->numero_siniestro;
          }
          function impmedi()
          {
               return $this->impmedi;
          }
          function bonificacion_medicamentos()
          {
               return $this->bonificacion_medicamentos;
          }
          function total_medicamentos()
          {
               return $this->total_medicamentos;
          }
          
          
          
      
          function movimiento_internacion_asigna($campo)
          {
               $this->movimiento_internacion=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function importe_asigna($campo)
          {
               $this->importe=$campo;
               
          }
          function exento_asigna($campo)
          {
               $this->exento=$campo;
               
          }
          function numero_factura_asigna($campo)
          {
               $this->numero_factura=$campo;
               
          }
          function tipocli_asigna($campo)
          {
               $this->tipocli=$campo;
               
          }
          function iva1_asigna($campo)
          {
               $this->iva1=$campo;
               
          }
          function total_honorarios_asigna($campo)
          {
               $this->total_honorarios=$campo;
               
          }
          function total_gastos_asigna($campo)
          {
               $this->total_gastos=$campo;
               
          }
          function total_iva_asigna($campo)
          {
               $this->total_iva=$campo;
               
          }
          function comentarios_asigna($campo)
          {
               $this->comentarios=$campo;
               
          }
          function periodo_mes_asigna($campo)
          {
               $this->periodo_mes=$campo;
               
          }
          function periodo_ano_asigna($campo)
          {
               $this->periodo_ano=$campo;
               
          }
          function fdesde_asigna($campo)
          {
               $this->fdesde=$campo;
               
          }
          function fhasta_asigna($campo)
          {
               $this->fhasta=$campo;
               
          }
          function idservicio_asigna($campo)
          {
               $this->idservicio=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function numero_siniestro_asigna($campo)
          {
               $this->numero_siniestro=$campo;
               
          }
          function impmedi_asigna($campo)
          {
               $this->impmedi=$campo;
               
          }
          function bonificacion_medicamentos_asigna($campo)
          {
               $this->bonificacion_medicamentos=$campo;
               
          }
          function total_medicamentos_asigna($campo)
          {
               $this->total_medicamentos=$campo;
               
          }
          function buscar_factura_segun_detalle($id)
            {
                $bd = new baseDatos();
		$bd->Conectarse();		    
		$bd->select("SELECT facturas_ambulatorio.* 
                            FROM facturas_ambulatorio LEFT JOIN facturas_ambulatorio_detalle USING(movimiento_internacion)
                            WHERE idfactura_ambulatorio_detalle=$id");
            }
          
          
      
}
?>