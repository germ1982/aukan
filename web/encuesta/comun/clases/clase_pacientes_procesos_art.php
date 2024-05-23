<?
      class clase_pacientes_procesos_art       
      {
	  var $id = '';
          var $idpaciente = '';
          var $numero_proceso = '';
          var $fecha_ingreso_sistema = '';
          var $fecha_inicio = '';
          var $fecha_vencimiento = '';
          var $arreglo_paciente = '';
          var $cantidad_filas = '';
          var $idosocial = '';
      
      
      
         function clase_pacientes_procesos_art($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pacientes_procesos_art WHERE id=$id");
      	     $arreglo=$bd->registro(); 
             self::asignar($arreglo);             
      	 }
      	function asignar($arreglo)
        {
            $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->numero_proceso=$arreglo['numero_proceso'];
      	     $this->fecha_ingreso_sistema=$arreglo['fecha_ingreso_sistema'];
      	     $this->fecha_inicio=$arreglo['fecha_inicio'];
      	     $this->fecha_vencimiento=$arreglo['fecha_vencimiento'];
             $this->idosocial=$arreglo['idosocial'];
        }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO pacientes_procesos_art(idpaciente,numero_proceso,fecha_inicio,fecha_vencimiento,idosocial) VALUES('".$this->idpaciente."','".$this->numero_proceso."','".$this->fecha_inicio."','".$this->fecha_vencimiento."','".$this->idosocial."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pacientes_procesos_art SET idpaciente='".$this->idpaciente."',numero_proceso='".$this->numero_proceso."',fecha_inicio='".$this->fecha_inicio."',fecha_vencimiento='".$this->fecha_vencimiento."',idosocial='".$this->idosocial."' WHERE id='".$this->id."'"))
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
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function numero_proceso()
          {
               return $this->numero_proceso;
          }
          function fecha_ingreso_sistema()
          {
               return $this->fecha_ingreso_sistema;
          }
          function fecha_inicio()
          {
               return $this->fecha_inicio;
          }
          function fecha_vencimiento()
          {
               return $this->fecha_vencimiento;
          }
          function cantidad_filas()
          {
               return $this->cantidad_filas;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function numero_proceso_asigna($campo)
          {
               $this->numero_proceso=$campo;
               
          }
          function fecha_ingreso_sistema_asigna($campo)
          {
               $this->fecha_ingreso_sistema=$campo;
               
          }
          function fecha_inicio_asigna($campo)
          {
               $this->fecha_inicio=$campo;
               
          }
          function fecha_vencimiento_asigna($campo)
          {
               $this->fecha_vencimiento=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
          }
          function foranea_idpaciente()
          {
              return $this->arreglo_paciente;
          }
          function buscar_pacientes_segun_proceso($numero_proceso)
          {
              $base = new baseDatos();
	      $base->Conectarse();
              $base->select("SELECT * FROM pacientes_procesos_art WHERE  	numero_proceso='$numero_proceso'");
              $pro = new clase_listar();								
	      for($i=0;$i<=$base->numero_filas();$i++) 
	      {
	          $fila = $base->registro(); 
                  if ($fila['id'] != 0 && $fila['id'] != '')
		      $pro->introducirElemento($fila); 
	      }
	      $this->arreglo_paciente = $pro;	
              $this->cantidad_filas = $base->numero_filas();
          }
          function buscar_proceso_segun_paciente($idpaciente)
          {
              $base = new baseDatos();
	      $base->Conectarse();
              $base->select("SELECT * FROM pacientes_procesos_art WHERE idpaciente=$idpaciente");
              $pro = new clase_listar();								
	      for($i=0;$i<=$base->numero_filas();$i++) 
	      {
	          $fila = $base->registro(); 
                  if ($fila['id'] != 0 && $fila['id'] != '')
		  $pro->introducirElemento($fila); 
	      }
	      $this->arreglo_paciente = $pro;	
              $this->cantidad_filas = $base->numero_filas();
          }
          function buscar_proceso_segun_os($idosocial)
          {
              $base = new baseDatos();
	      $base->Conectarse();
              $base->select("SELECT * FROM pacientes_procesos_art WHERE idosocial=$idosocial");
              $pro = new clase_listar();								
	      for($i=0;$i<=$base->numero_filas();$i++) 
	      {
	          $fila = $base->registro(); 
                  if ($fila['id'] != 0 && $fila['id'] != '')
		  $pro->introducirElemento($fila); 
	      }
	      $this->arreglo_paciente = $pro;	
              $this->cantidad_filas = $base->numero_filas();
          }
      
}
?>