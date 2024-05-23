<?
      class clase_valores_galenos       
      {
	  	  var $idosocial = '';
          var $codigo_galeno = '';
          var $importe = '';
          var $importe_anterior = '';
          var $arreglo_valores_galenos = '';
          
      
          function clase_valores_galenos($idosocial,$codigo_galeno)
          {
             $bd = new baseDatos();
	      	 $bd->Conectarse();
	      	 $bd->select("SELECT * FROM  valores_galenos WHERE  idosocial=$idosocial AND codigo_galeno=$codigo_galeno");
	      	 self::asigna_campos($bd->registro());
          }
          function asigna_campos($arreglo)
          {
             $this->idosocial = $arreglo['idosocial'];
             $this->codigo_galeno = $arreglo['codigo_galeno'];
             $this->importe = $arreglo['importe'];
             $this->importe_anterior = $arreglo['importe_anterior'];	
          }  
      
      
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  $query="SELECT * FROM  valores_galenos WHERE  idosocial=".$this->idosocial." AND codigo_galeno=".$this->codigo_galeno;
		  $bd->select($query);
		  $que_es = $bd->registro();		
			  if (count($que_es) == 1 || $que_es == "")
			  { 
      	      if ($bd->select("INSERT INTO valores_galenos(idosocial,codigo_galeno,importe,importe_anterior) VALUES('".$this->idosocial."','".$this->codigo_galeno."','".$this->importe."','".$this->importe_anterior."')"))
      	      {
      	          
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE valores_galenos SET idosocial='".$this->idosocial."',codigo_galeno='".$this->codigo_galeno."',importe='".$this->importe."',importe_anterior='".$this->importe_anterior."' WHERE "))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idosocial()
          {
               return $this->idosocial;
          }
          function codigo_galeno()
          {
               return $this->codigo_galeno;
          }
          function importe()
          {
               return $this->importe;
          }
          function importe_anterior()
          {
               return $this->importe_anterior;
          }
          function arreglo_valores_galenos()
          {
              return $this->arreglo_valores_galenos;	
          }
          
          
      
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function codigo_galeno_asigna($campo)
          {
               $this->codigo_galeno=$campo;
               
          }
          function importe_asigna($campo)
          {
               $this->importe=$campo;
               
          }
          function importe_anterior_asigna($campo)
          {
               $this->importe_anterior=$campo;
               
          }
          function actualiza_porcentaje($idosocial,$porcentaje)
	      {
	          $bd = new baseDatos();
	      	  $bd->Conectarse();
	      	  $base = new baseDatos();
	      	  $base->Conectarse();
	      	  $bd->select("SELECT * FROM valores_galenos WHERE idosocial=$idosocial");
	      	  $bandera = 0;
	      	  while ($arreglo = $bd->registro())
	      	  {
	      	  	  if ($arreglo['importe'] != 1)//si es 1 es galeno modulo y es de 1 peso y no debe cambiar
	      	  	  {
		      	  	  $sumar = $arreglo['importe'] + (($arreglo['importe']*$porcentaje)/100);
		      	  	  if ($base->select("UPDATE valores_galenos SET importe='$sumar',importe_anterior='".$arreglo['importe']."' 
		      	  	  WHERE idosocial=$idosocial AND codigo_galeno=".$arreglo['codigo_galeno']))
		      	  	       $bandera = 1;
	      	  	  }	      	  	          	
	      	  }
	      	  return $bandera;
	       }
	       function financiacion_actualizada($idosocial,$order)
	       {
	           $bd = new baseDatos();
	      	   $bd->Conectarse();	 
	      	   if ($idosocial != '') $where = " WHERE idosocial=$idosocial ";     	  
	      	   $bd->select("SELECT * FROM valores_galenos  $where
	      	               GROUP BY idosocial 
	      	               ORDER BY fecha_modificacion $order
	      	               LIMIT 0,5");
	      	   $pro = new clase_listar();			
							
		    	for($i=0;$i<=$bd->numero_filas();$i++) 
		    	{
		    		$fila = $bd->registro(); 
		    		$pro->introducirElemento($fila); 
		    	}
		    	$this->arreglo_valores_galenos = $pro;	   
	       }
	       function valor_galenos_obra_social($idosocial,$mejor_peor)
	       {
	       	   //el parametro mejor_peor indica si tenemos que buscar los mejores gastos o los peores
	       	   //si es 0 es el peor y si es 1 es el mejor
	       	   $bd = new baseDatos();
	      	   $bd->Conectarse();	 
	      	   if ($idosocial != '') $where_idosocial = " AND idosocial=$idosocial ";   
	      	   if ($mejor_peor == 0)	      	   
	      	   	   $select = "MIN(importe) as valor,codigo_galeno,idosocial,fecha_modificacion";
	      	   else 	      	   
	      	   	   $select = "MAX(importe) as valor,codigo_galeno,idosocial,fecha_modificacion";	      	   	   	      	    	 
	      	   $where  = " importe <> '0.00'";
	      	   $bd->select("SELECT $select FROM valores_galenos WHERE $where   $where_idosocial
	      	               GROUP BY idosocial 
	      	               
	      	               LIMIT 0,5");
	      	   $pro = new clase_listar();			
							
		    	for($i=0;$i<=$bd->numero_filas();$i++) 
		    	{
		    		$fila = $bd->registro(); 
		    		$pro->introducirElemento($fila); 
		    	}
		    	$this->arreglo_valores_galenos = $pro;
	       }                      
}
?>
