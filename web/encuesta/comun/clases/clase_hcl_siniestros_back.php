<?
      class clase_hcl_siniestros       
      {
	  var $id = '';
          var $idpaciente = '';
          var $numero = '';
          var $idosocial = '';
          var $fecha_accidente = '';
          var $hora_accidente = '';
          var $empleador = '';
          var $tel_empleador = '';
          var $fecha_probable_alta = '';
          var $numero_siniestro = '';
          var $baja_laboral = '';
          var $evento = '';
          var $diagnostico = '';
          var $cuil_empleador = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_hcl_siniestros($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM hcl_siniestros WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);  
      	 }
      	 function asignar($arreglo)
      	 {
      	 	$this->id=$arreglo['id'];
      	 	$this->idpaciente=$arreglo['idpaciente'];
      	 	$this->numero=$arreglo['numero'];
      	 	$this->idosocial=$arreglo['idosocial'];
      	 	$this->fecha_accidente=$arreglo['fecha_accidente'];
      	 	$this->hora_accidente=$arreglo['hora_accidente'];
      	 	$this->empleador=$arreglo['empleador'];
      	 	$this->tel_empleador=$arreglo['tel_empleador'];
      	 	$this->fecha_probable_alta=$arreglo['fecha_probable_alta'];
      	 	$this->numero_siniestro=$arreglo['numero_siniestro'];
      	 	$this->baja_laboral=$arreglo['baja_laboral'];
      	 	$this->evento=$arreglo['evento'];
      	 	$this->diagnostico=$arreglo['diagnostico'];
      	 	$this->cuil_empleador=$arreglo['cuil_empleador'];
      	 }  
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO hcl_siniestros(idpaciente,numero,idosocial,fecha_accidente,hora_accidente,empleador,tel_empleador,fecha_probable_alta,numero_siniestro,baja_laboral,evento,diagnostico,cuil_empleador) VALUES('".$this->idpaciente."','".$this->numero."','".$this->idosocial."','".$this->fecha_accidente."','".$this->hora_accidente."','".$this->empleador."','".$this->tel_empleador."','".$this->fecha_probable_alta."','".$this->numero_siniestro."','".$this->baja_laboral."','".$this->evento."','".$this->diagnostico."','".$this->cuil_empleador."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE hcl_siniestros SET idpaciente='".$this->idpaciente."',numero='".$this->numero."',idosocial='".$this->idosocial."',fecha_accidente='".$this->fecha_accidente."',hora_accidente='".$this->hora_accidente."',empleador='".$this->empleador."',tel_empleador='".$this->tel_empleador."',fecha_probable_alta='".$this->fecha_probable_alta."',numero_siniestro='".$this->numero_siniestro."',baja_laboral='".$this->baja_laboral."',evento='".$this->evento."',diagnostico='".$this->diagnostico."',cuil_empleador='".$this->cuil_empleador."' WHERE id='".$this->id."'"))
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
          function numero()
          {
               return $this->numero;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function fecha_accidente()
          {
               return $this->fecha_accidente;
          }
          function hora_accidente()
          {
               return $this->hora_accidente;
          }
          function empleador()
          {
               return $this->empleador;
          }
          function tel_empleador()
          {
               return $this->tel_empleador;
          }
          function fecha_probable_alta()
          {
               return $this->fecha_probable_alta;
          }
          function numero_siniestro()
          {
               return $this->numero_siniestro;
          }
          function baja_laboral()
          {
               return $this->baja_laboral;
          }
          function evento()
          {
               return $this->evento;
          }
          function diagnostico()
          {
               return $this->diagnostico;
          }
          function cuil_empleador()
          {
               return $this->cuil_empleador;
          }
          
          
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function numero_asigna($campo)
          {
               $this->numero=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function fecha_accidente_asigna($campo)
          {
               $this->fecha_accidente=$campo;
               
          }
          function hora_accidente_asigna($campo)
          {
               $this->hora_accidente=$campo;
               
          }
          function empleador_asigna($campo)
          {
               $this->empleador=$campo;
               
          }
          function tel_empleador_asigna($campo)
          {
               $this->tel_empleador=$campo;
               
          }
          function fecha_probable_alta_asigna($campo)
          {
               $this->fecha_probable_alta=$campo;
               
          }
          function numero_siniestro_asigna($campo)
          {
               $this->numero_siniestro=$campo;
               
          }
          function baja_laboral_asigna($campo)
          {
               $this->baja_laboral=$campo;
               
          }
          function evento_asigna($campo)
          {
               $this->evento=$campo;
               
          }
          function diagnostico_asigna($campo)
          {
               $this->diagnostico=$campo;
               
          }
          function cuil_empleador_asigna($campo)
          {
               $this->cuil_empleador=$campo;
               
          }
          
        function getUltimoNumero($idpaciente){
            $bd = new baseDatos();
            $bd->Conectarse();
            $bd->select("SELECT numero+1 as numero FROM hcl_siniestros WHERE idpaciente=$idpaciente ORDER BY numero DESC LIMIT 1");
            $result = $bd->registro();
            $this->numero_asigna($result['numero']);
        }  
          
        function foranea_idpaciente($idpaciente)
            {
                          $bd = new baseDatos();
                          $bd->Conectarse();		    
                          $bd->select("SELECT * FROM hcl_siniestros WHERE idpaciente=$idpaciente ORDER BY numero DESC");

                          $pro = new clase_listar();			

                  for($i=0;$i<=$bd->numero_filas();$i++) 
                  {
                          $fila = $bd->registro(); 
                          $pro->introducirElemento($fila); 
                  }
                  $this->arreglo_foraneo_idpaciente = $pro;		                              		
                  }
                  function foranea_id($id)
                  {
                          $bd = new baseDatos();
                          $bd->Conectarse();		    
                          $bd->select("SELECT * FROM hcl_siniestros WHERE id=$id");

                          $pro = new clase_listar();			

                    for($i=0;$i<=$bd->numero_filas();$i++) 
                    {
                          $fila = $bd->registro(); 
                          $pro->introducirElemento($fila); 
                    }
                    $this->arreglo_foraneo_idpaciente = $pro;		                              		
                  }
		function ultimo_siniestro($idpaciente)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM hcl_siniestros WHERE idpaciente=$idpaciente ORDER BY numero DESC");
			$arreglo = $bd->registro(); 
			self::asignar($arreglo);
		}			
      function foranea_idpaciente_idosocial($idpaciente,$idosocial)
            {
                          $bd = new baseDatos();
                          $bd->Conectarse();		    
                          $bd->select("SELECT * FROM hcl_siniestros WHERE idpaciente=$idpaciente AND idosocial=$idosocial ORDER BY numero DESC");

                          $pro = new clase_listar();			

                  for($i=0;$i<=$bd->numero_filas();$i++) 
                  {
                          $fila = $bd->registro(); 
                          $pro->introducirElemento($fila); 
                  }
                  $this->arreglo_foraneo_idpaciente = $pro;		                              		
                  }
}
?>
