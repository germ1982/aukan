<?
      class clase_resultados_estudios_expirometria       
      {
	  var $id = '';
          var $idresultado_estudio = '';
          var $interpretacion_espirometria = '';
          var $fecha_ingreso_empresa = '';
          var $puesto_trabajo = '';
          var $antiguedad_puesto = '';
          var $altura = '';
          var $peso = '';
          var $fumador = '';
          var $fumador_cantidad = '';
          var $ex_fumador = '';
          var $ex_fumador_cantidad = '';
          var $sobrepeso = '';
          var $obesidad = '';
          
      
      var $arreglo_foraneo_idresultado_estudio='';
      	     
      
         function clase_resultados_estudios_expirometria($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM resultados_estudios_expirometria WHERE id=$id");
      	     self::asignar($bd->registro());
      	     
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->idresultado_estudio=$arreglo['idresultado_estudio'];
      	     $this->interpretacion_espirometria=$arreglo['interpretacion_espirometria'];
      	     $this->fecha_ingreso_empresa=$arreglo['fecha_ingreso_empresa'];
      	     $this->puesto_trabajo=$arreglo['puesto_trabajo'];
      	     $this->antiguedad_puesto=$arreglo['antiguedad_puesto'];
      	     $this->altura=$arreglo['altura'];
      	     $this->peso=$arreglo['peso'];
      	     $this->fumador=$arreglo['fumador'];
      	     $this->fumador_cantidad=$arreglo['fumador_cantidad'];
      	     $this->ex_fumador=$arreglo['ex_fumador'];
      	     $this->ex_fumador_cantidad=$arreglo['ex_fumador_cantidad'];
      	     $this->sobrepeso=$arreglo['sobrepeso'];
      	     $this->obesidad=$arreglo['obesidad'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  $bd->select("SELECT * FROM resultados_estudios_expirometria WHERE idresultado_estudio=".$this->idresultado_estudio);
          $arreglo = $bd->registro();
      	  if ($arreglo['id']==0 || $arreglo['id']=='' ) {
      	      if ($bd->select("INSERT INTO resultados_estudios_expirometria(idresultado_estudio,interpretacion_espirometria,fecha_ingreso_empresa,puesto_trabajo,antiguedad_puesto,altura,peso,fumador,fumador_cantidad,ex_fumador,ex_fumador_cantidad,sobrepeso,obesidad) VALUES('".$this->idresultado_estudio."','".$this->interpretacion_espirometria."','".$this->fecha_ingreso_empresa."','".$this->puesto_trabajo."','".$this->antiguedad_puesto."','".$this->altura."','".$this->peso."','".$this->fumador."','".$this->fumador_cantidad."','".$this->ex_fumador."','".$this->ex_fumador_cantidad."','".$this->sobrepeso."','".$this->obesidad."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE resultados_estudios_expirometria SET idresultado_estudio='".$this->idresultado_estudio."',interpretacion_espirometria='".$this->interpretacion_espirometria."',fecha_ingreso_empresa='".$this->fecha_ingreso_empresa."',puesto_trabajo='".$this->puesto_trabajo."',antiguedad_puesto='".$this->antiguedad_puesto."',altura='".$this->altura."',peso='".$this->peso."',fumador='".$this->fumador."',fumador_cantidad='".$this->fumador_cantidad."',ex_fumador='".$this->ex_fumador."',ex_fumador_cantidad='".$this->ex_fumador_cantidad."',sobrepeso='".$this->sobrepeso."',obesidad='".$this->obesidad."' WHERE id='".$arreglo['id']."'"))
      	        {
      	            
      	            return "UPDATE resultados_estudios_expirometria SET idresultado_estudio='".$this->idresultado_estudio."',interpretacion_espirometria='".$this->interpretacion_espirometria."',fecha_ingreso_empresa='".$this->fecha_ingreso_empresa."',puesto_trabajo='".$this->puesto_trabajo."',antiguedad_puesto='".$this->antiguedad_puesto."',altura='".$this->altura."',peso='".$this->peso."',fumador='".$this->fumador."',fumador_cantidad='".$this->fumador_cantidad."',ex_fumador='".$this->ex_fumador."',ex_fumador_cantidad='".$this->ex_fumador_cantidad."',sobrepeso='".$this->sobrepeso."',obesidad='".$this->obesidad."' WHERE id='".$arreglo['id']."'";
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function id()
          {
               return $this->id;
          }
          function idresultado_estudio()
          {
               return $this->idresultado_estudio;
          }
          function interpretacion_espirometria()
          {
               return $this->interpretacion_espirometria;
          }
          function fecha_ingreso_empresa()
          {
               return $this->fecha_ingreso_empresa;
          }
          function puesto_trabajo()
          {
               return $this->puesto_trabajo;
          }
          function antiguedad_puesto()
          {
               return $this->antiguedad_puesto;
          }
          function altura()
          {
               return $this->altura;
          }
          function peso()
          {
               return $this->peso;
          }
          function fumador()
          {
               return $this->fumador;
          }
          function fumador_cantidad()
          {
               return $this->fumador_cantidad;
          }
          function ex_fumador()
          {
               return $this->ex_fumador;
          }
          function ex_fumador_cantidad()
          {
               return $this->ex_fumador_cantidad;
          }
          function sobrepeso()
          {
               return $this->sobrepeso;
          }
          function obesidad()
          {
               return $this->obesidad;
          }
          
          
          
      	     function arreglo_foraneo_idresultado_estudio()
             {
                 return $this->arreglo_foraneo_idresultado_estudio;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idresultado_estudio_asigna($campo)
          {
               $this->idresultado_estudio=$campo;
               
          }
          function interpretacion_espirometria_asigna($campo)
          {
               $this->interpretacion_espirometria=$campo;
               
          }
          function fecha_ingreso_empresa_asigna($campo)
          {
               $this->fecha_ingreso_empresa=$campo;
               
          }
          function puesto_trabajo_asigna($campo)
          {
               $this->puesto_trabajo=$campo;
               
          }
          function antiguedad_puesto_asigna($campo)
          {
               $this->antiguedad_puesto=$campo;
               
          }
          function altura_asigna($campo)
          {
               $this->altura=$campo;
               
          }
          function peso_asigna($campo)
          {
               $this->peso=$campo;
               
          }
          function fumador_asigna($campo)
          {
               $this->fumador=$campo;
               
          }
          function fumador_cantidad_asigna($campo)
          {
               $this->fumador_cantidad=$campo;
               
          }
          function ex_fumador_asigna($campo)
          {
               $this->ex_fumador=$campo;
               
          }
          function ex_fumador_cantidad_asigna($campo)
          {
               $this->ex_fumador_cantidad=$campo;
               
          }
          function sobrepeso_asigna($campo)
          {
               $this->sobrepeso=$campo;
               
          }
          function obesidad_asigna($campo)
          {
               $this->obesidad=$campo;
               
          }
          
          
          
	       function foranea_idresultado_estudio($idresultado_estudio)
	       {
		   $bd = new baseDatos();
		   $bd->Conectarse();		    
		   $bd->select("SELECT * FROM resultados_estudios_expirometria WHERE idresultado_estudio=$idresultado_estudio");				
		   self::asignar($bd->registro());		                              		
	       }
			
      
}
?>