<?
      class clase_resultados_estudios_logoaudiometria       
      {
	  var $id = '';
          var $idresultado_estudio = '';
          var $od_uno = '';
          var $oi_uno = '';
          var $od_uno_porcentaje = '';
          var $oi_uno_porcentaje = '';
          var $od_dos = '';
          var $oi_dos = '';
          var $od_dos_porcentaje = '';
          var $oi_dos_porcentaje = '';
          var $od_tres = '';
          var $oi_tres_porcentaje = '';
          var $od_cuatro = '';
          var $oi_cuatro_porcentaje = '';
          var $umbral_detectado_od = '';
          var $umbral_detectado_oi = '';
          var $umbral_palabra_od = '';
          var $umbral_palabra_oi = '';
          var $oi_tres = '';
          var $od_tres_porcentaje = '';
          var $oi_cuatro = '';
          var $od_cuatro_porcentaje = '';
          
      
      var $arreglo_foraneo_idresultado_estudio='';
      	     
      
         function clase_resultados_estudios_logoaudiometria($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM resultados_estudios_logoaudiometria WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     self::asignar($arreglo);
      	     
      	 }
      	 function asignar($arreglo)
         {
             $this->id=$arreglo['id'];
      	     $this->idresultado_estudio=$arreglo['idresultado_estudio'];
      	     $this->od_uno=$arreglo['od_uno'];
      	     $this->oi_uno=$arreglo['oi_uno'];
      	     $this->od_uno_porcentaje=$arreglo['od_uno_porcentaje'];
      	     $this->oi_uno_porcentaje=$arreglo['oi_uno_porcentaje'];
      	     $this->od_dos=$arreglo['od_dos'];
      	     $this->oi_dos=$arreglo['oi_dos'];
      	     $this->od_dos_porcentaje=$arreglo['od_dos_porcentaje'];
      	     $this->oi_dos_porcentaje=$arreglo['oi_dos_porcentaje'];
      	     $this->od_tres=$arreglo['od_tres'];
      	     $this->oi_tres_porcentaje=$arreglo['oi_tres_porcentaje'];
      	     $this->od_cuatro=$arreglo['od_cuatro'];
      	     $this->oi_cuatro_porcentaje=$arreglo['oi_cuatro_porcentaje'];
      	     $this->umbral_detectado_od=$arreglo['umbral_detectado_od'];
      	     $this->umbral_detectado_oi=$arreglo['umbral_detectado_oi'];
      	     $this->umbral_palabra_od=$arreglo['umbral_palabra_od'];
      	     $this->umbral_palabra_oi=$arreglo['umbral_palabra_oi'];
      	     $this->oi_tres=$arreglo['oi_tres'];
      	     $this->od_tres_porcentaje=$arreglo['od_tres_porcentaje'];
      	     $this->oi_cuatro=$arreglo['oi_cuatro'];
      	     $this->od_cuatro_porcentaje=$arreglo['od_cuatro_porcentaje'];
         }
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO resultados_estudios_logoaudiometria(idresultado_estudio,od_uno,oi_uno,od_uno_porcentaje,oi_uno_porcentaje,od_dos,oi_dos,od_dos_porcentaje,oi_dos_porcentaje,od_tres,oi_tres_porcentaje,od_cuatro,oi_cuatro_porcentaje,umbral_detectado_od,umbral_detectado_oi,umbral_palabra_od,umbral_palabra_oi,oi_tres,od_tres_porcentaje,oi_cuatro,od_cuatro_porcentaje) VALUES('".$this->idresultado_estudio."','".$this->od_uno."','".$this->oi_uno."','".$this->od_uno_porcentaje."','".$this->oi_uno_porcentaje."','".$this->od_dos."','".$this->oi_dos."','".$this->od_dos_porcentaje."','".$this->oi_dos_porcentaje."','".$this->od_tres."','".$this->oi_tres_porcentaje."','".$this->od_cuatro."','".$this->oi_cuatro_porcentaje."','".$this->umbral_detectado_od."','".$this->umbral_detectado_oi."','".$this->umbral_palabra_od."','".$this->umbral_palabra_oi."','".$this->oi_tres."','".$this->od_tres_porcentaje."','".$this->oi_cuatro."','".$this->od_cuatro_porcentaje."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE resultados_estudios_logoaudiometria SET idresultado_estudio='".$this->idresultado_estudio."',od_uno='".$this->od_uno."',oi_uno='".$this->oi_uno."',od_uno_porcentaje='".$this->od_uno_porcentaje."',oi_uno_porcentaje='".$this->oi_uno_porcentaje."',od_dos='".$this->od_dos."',oi_dos='".$this->oi_dos."',od_dos_porcentaje='".$this->od_dos_porcentaje."',oi_dos_porcentaje='".$this->oi_dos_porcentaje."',od_tres='".$this->od_tres."',oi_tres_porcentaje='".$this->oi_tres_porcentaje."',od_cuatro='".$this->od_cuatro."',oi_cuatro_porcentaje='".$this->oi_cuatro_porcentaje."',umbral_detectado_od='".$this->umbral_detectado_od."',umbral_detectado_oi='".$this->umbral_detectado_oi."',umbral_palabra_od='".$this->umbral_palabra_od."',umbral_palabra_oi='".$this->umbral_palabra_oi."',oi_tres='".$this->oi_tres."',od_tres_porcentaje='".$this->od_tres_porcentaje."',oi_cuatro='".$this->oi_cuatro."',od_cuatro_porcentaje='".$this->od_cuatro_porcentaje."' WHERE id='".$this->id."'"))
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
          function od_uno()
          {
               return $this->od_uno;
          }
          function oi_uno()
          {
               return $this->oi_uno;
          }
          function od_uno_porcentaje()
          {
               return $this->od_uno_porcentaje;
          }
          function oi_uno_porcentaje()
          {
               return $this->oi_uno_porcentaje;
          }
          function od_dos()
          {
               return $this->od_dos;
          }
          function oi_dos()
          {
               return $this->oi_dos;
          }
          function od_dos_porcentaje()
          {
               return $this->od_dos_porcentaje;
          }
          function oi_dos_porcentaje()
          {
               return $this->oi_dos_porcentaje;
          }
          function od_tres()
          {
               return $this->od_tres;
          }
          function oi_tres_porcentaje()
          {
               return $this->oi_tres_porcentaje;
          }
          function od_cuatro()
          {
               return $this->od_cuatro;
          }
          function oi_cuatro_porcentaje()
          {
               return $this->oi_cuatro_porcentaje;
          }
          function umbral_detectado_od()
          {
               return $this->umbral_detectado_od;
          }
          function umbral_detectado_oi()
          {
               return $this->umbral_detectado_oi;
          }
          function umbral_palabra_od()
          {
               return $this->umbral_palabra_od;
          }
          function umbral_palabra_oi()
          {
               return $this->umbral_palabra_oi;
          }
          function oi_tres()
          {
               return $this->oi_tres;
          }
          function od_tres_porcentaje()
          {
               return $this->od_tres_porcentaje;
          }
          function oi_cuatro()
          {
               return $this->oi_cuatro;
          }
          function od_cuatro_porcentaje()
          {
               return $this->od_cuatro_porcentaje;
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
          function od_uno_asigna($campo)
          {
               $this->od_uno=$campo;
               
          }
          function oi_uno_asigna($campo)
          {
               $this->oi_uno=$campo;
               
          }
          function od_uno_porcentaje_asigna($campo)
          {
               $this->od_uno_porcentaje=$campo;
               
          }
          function oi_uno_porcentaje_asigna($campo)
          {
               $this->oi_uno_porcentaje=$campo;
               
          }
          function od_dos_asigna($campo)
          {
               $this->od_dos=$campo;
               
          }
          function oi_dos_asigna($campo)
          {
               $this->oi_dos=$campo;
               
          }
          function od_dos_porcentaje_asigna($campo)
          {
               $this->od_dos_porcentaje=$campo;
               
          }
          function oi_dos_porcentaje_asigna($campo)
          {
               $this->oi_dos_porcentaje=$campo;
               
          }
          function od_tres_asigna($campo)
          {
               $this->od_tres=$campo;
               
          }
          function oi_tres_porcentaje_asigna($campo)
          {
               $this->oi_tres_porcentaje=$campo;
               
          }
          function od_cuatro_asigna($campo)
          {
               $this->od_cuatro=$campo;
               
          }
          function oi_cuatro_porcentaje_asigna($campo)
          {
               $this->oi_cuatro_porcentaje=$campo;
               
          }
          function umbral_detectado_od_asigna($campo)
          {
               $this->umbral_detectado_od=$campo;
               
          }
          function umbral_detectado_oi_asigna($campo)
          {
               $this->umbral_detectado_oi=$campo;
               
          }
          function umbral_palabra_od_asigna($campo)
          {
               $this->umbral_palabra_od=$campo;
               
          }
          function umbral_palabra_oi_asigna($campo)
          {
               $this->umbral_palabra_oi=$campo;
               
          }
          function oi_tres_asigna($campo)
          {
               $this->oi_tres=$campo;
               
          }
          function od_tres_porcentaje_asigna($campo)
          {
               $this->od_tres_porcentaje=$campo;
               
          }
          function oi_cuatro_asigna($campo)
          {
               $this->oi_cuatro=$campo;
               
          }
          function od_cuatro_porcentaje_asigna($campo)
          {
               $this->od_cuatro_porcentaje=$campo;
               
          }
          
          
          
	      function foranea_idresultado_estudio($idresultado_estudio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM resultados_estudios_logoaudiometria WHERE idresultado_estudio=$idresultado_estudio");				
				$fila = $bd->registro(); 
                                self::asignar($fila);
                              /*  $pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idresultado_estudio = $pro;*/		                              		
			}
			
      
}
?>