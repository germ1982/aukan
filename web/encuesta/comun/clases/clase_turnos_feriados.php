<?
      class clase_turnos_feriados       
      {
	  var $id = '';
          var $dia = '';
          var $mes = '';
          var $ano = '';
          var $usuario = '';
          var $descripcion = '';
          var $arreglo_dias_feriados = '';
      
      
      
         function clase_turnos_feriados($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM turnos_feriados WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->dia=$arreglo['dia'];
      	     $this->mes=$arreglo['mes'];
      	     $this->ano=$arreglo['ano'];
      	     $this->usuario=$arreglo['usuario'];
      	     $this->descripcion=$arreglo['descripcion'];
         }
                       
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO turnos_feriados(dia,mes,ano,usuario,descripcion) VALUES('".$this->dia."','".$this->mes."','".$this->ano."','".$this->usuario."','".$this->descripcion."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE turnos_feriados SET dia='".$this->dia."',mes='".$this->mes."',ano='".$this->ano."',usuario='".$this->usuario."',descripcion='".$this->descripcion."' WHERE id='".$this->id."'"))
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
          function dia()
          {
               return $this->dia;
          }
          function mes()
          {
               return $this->mes;
          }
          function ano()
          {
               return $this->ano;
          }
          function usuario()
          {
               return $this->usuario;
          }
          function descripcion()
          {
               return $this->descripcion;
          }
          
          
          
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function dia_asigna($campo)
          {
               $this->dia=$campo;
               
          }
          function mes_asigna($campo)
          {
               $this->mes=$campo;
               
          }
          function ano_asigna($campo)
          {
               $this->ano=$campo;
               
          }
          function usuario_asigna($campo)
          {
               $this->usuario=$campo;
               
          }
          function descripcion_asigna($campo)
          {
               $this->descripcion=$campo;
               
          }
          
          function dias_feriados()
          {
              $bd = new baseDatos();
	      $bd->Conectarse();		    
	      $bd->select("SELECT * FROM turnos_feriados ORDER BY ano DESC, mes DESC, dia DESC LIMIT 0,25");				
	      $pro = new clase_listar();											
	      for($i=0;$i<=$bd->numero_filas();$i++) 
	      {
	          $fila = $bd->registro(); 
                  if ($fila['id'] != 0 && $fila['id'] != '')
	    	     $pro->introducirElemento($fila); 
	      }
	      $this->arreglo_dias_feriados = $pro;	
          }
          function arreglo_dias_feriados()
          {
              return $this->arreglo_dias_feriados;
          }
          function es_feriado($fecha)
          {
              $bd = new baseDatos();
	      $bd->Conectarse();		    
              list($y,$m,$d) = explode('-', $fecha);
	      $bd->select("SELECT * FROM turnos_feriados WHERE dia='$d' and mes='$m' and ano=$y");
              self::asignar($bd->registro());
              if ($bd->numero_filas() != 0)
                  return 1;
              else
                  return 0;
          }
      
}
?>