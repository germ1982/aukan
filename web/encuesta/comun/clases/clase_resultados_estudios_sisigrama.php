<?
      class clase_resultados_estudios_sisigrama       
      {
	  var $id = '';
          var $idresultado_estudio = '';
          var $od_500 = '';
          var $oi_500 = '';
          var $od_1000 = '';
          var $oi_1000 = '';
          var $od_2000 = '';
          var $oi_2000 = '';
          var $od_4000 = '';
          var $oi_4000 = '';
          var $od_total = '';
          var $oi_total = '';
          var $variacion_mayor_10 = '';
          var $variacion_menor_10 = '';
          var $incapacidad_auditiva = '';
          
      
      var $arreglo_foraneo_idresultado_estudio='';
      	     
      
         function clase_resultados_estudios_sisigrama($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM resultados_estudios_sisigrama WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->idresultado_estudio=$arreglo['idresultado_estudio'];
      	     $this->od_500=$arreglo['od_500'];
      	     $this->oi_500=$arreglo['oi_500'];
      	     $this->od_1000=$arreglo['od_1000'];
      	     $this->oi_1000=$arreglo['oi_1000'];
      	     $this->od_2000=$arreglo['od_2000'];
      	     $this->oi_2000=$arreglo['oi_2000'];
      	     $this->od_4000=$arreglo['od_4000'];
      	     $this->oi_4000=$arreglo['oi_4000'];
      	     $this->od_total=$arreglo['od_total'];
      	     $this->oi_total=$arreglo['oi_total'];
      	     $this->variacion_mayor_10=$arreglo['variacion_mayor_10'];
      	     $this->variacion_menor_10=$arreglo['variacion_menor_10'];
      	     $this->incapacidad_auditiva=$arreglo['incapacidad_auditiva'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO resultados_estudios_sisigrama(idresultado_estudio,od_500,oi_500,od_1000,oi_1000,od_2000,oi_2000,od_4000,oi_4000,od_total,oi_total,variacion_mayor_10,variacion_menor_10,incapacidad_auditiva) VALUES('".$this->idresultado_estudio."','".$this->od_500."','".$this->oi_500."','".$this->od_1000."','".$this->oi_1000."','".$this->od_2000."','".$this->oi_2000."','".$this->od_4000."','".$this->oi_4000."','".$this->od_total."','".$this->oi_total."','".$this->variacion_mayor_10."','".$this->variacion_menor_10."','".$this->incapacidad_auditiva."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE resultados_estudios_sisigrama SET idresultado_estudio='".$this->idresultado_estudio."',od_500='".$this->od_500."',oi_500='".$this->oi_500."',od_1000='".$this->od_1000."',oi_1000='".$this->oi_1000."',od_2000='".$this->od_2000."',oi_2000='".$this->oi_2000."',od_4000='".$this->od_4000."',oi_4000='".$this->oi_4000."',od_total='".$this->od_total."',oi_total='".$this->oi_total."',variacion_mayor_10='".$this->variacion_mayor_10."',variacion_menor_10='".$this->variacion_menor_10."',incapacidad_auditiva='".$this->incapacidad_auditiva."' WHERE id='".$this->id."'"))
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
          function idresultado_estudio()
          {
               return $this->idresultado_estudio;
          }
          function od_500()
          {
               return $this->od_500;
          }
          function oi_500()
          {
               return $this->oi_500;
          }
          function od_1000()
          {
               return $this->od_1000;
          }
          function oi_1000()
          {
               return $this->oi_1000;
          }
          function od_2000()
          {
               return $this->od_2000;
          }
          function oi_2000()
          {
               return $this->oi_2000;
          }
          function od_4000()
          {
               return $this->od_4000;
          }
          function oi_4000()
          {
               return $this->oi_4000;
          }
          function od_total()
          {
               return $this->od_total;
          }
          function oi_total()
          {
               return $this->oi_total;
          }
          function variacion_mayor_10()
          {
               return $this->variacion_mayor_10;
          }
          function variacion_menor_10()
          {
               return $this->variacion_menor_10;
          }
          function incapacidad_auditiva()
          {
               return $this->incapacidad_auditiva;
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
          function od_500_asigna($campo)
          {
               $this->od_500=$campo;
               
          }
          function oi_500_asigna($campo)
          {
               $this->oi_500=$campo;
               
          }
          function od_1000_asigna($campo)
          {
               $this->od_1000=$campo;
               
          }
          function oi_1000_asigna($campo)
          {
               $this->oi_1000=$campo;
               
          }
          function od_2000_asigna($campo)
          {
               $this->od_2000=$campo;
               
          }
          function oi_2000_asigna($campo)
          {
               $this->oi_2000=$campo;
               
          }
          function od_4000_asigna($campo)
          {
               $this->od_4000=$campo;
               
          }
          function oi_4000_asigna($campo)
          {
               $this->oi_4000=$campo;
               
          }
          function od_total_asigna($campo)
          {
               $this->od_total=$campo;
               
          }
          function oi_total_asigna($campo)
          {
               $this->oi_total=$campo;
               
          }
          function variacion_mayor_10_asigna($campo)
          {
               $this->variacion_mayor_10=$campo;
               
          }
          function variacion_menor_10_asigna($campo)
          {
               $this->variacion_menor_10=$campo;
               
          }
          function incapacidad_auditiva_asigna($campo)
          {
               $this->incapacidad_auditiva=$campo;
               
          }
          
          
          
	      function foranea_idresultado_estudio($idresultado_estudio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM resultados_estudios_sisigrama WHERE idresultado_estudio=$idresultado_estudio");				
				$fila = $bd->registro(); 
                                self::asignar($fila);
                        /*        $pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idresultado_estudio = $pro;*/		                              		
			}
			
      
}
?>