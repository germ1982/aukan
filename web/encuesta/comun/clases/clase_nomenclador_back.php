<?
      class clase_nomenclador       
      {
	  var $idnomenclador = '';
          var $codigo = '';
          var $descripcion = '';
          var $activado = '';
          var $activado_lab = '';
          var $codigo_contenedor = '';
          var $codigo_loinc = '';
          var $activado_pacs = '';
          var $arreglo_todos_activados = '';
          
      
      var $arreglo_foraneo_codigo='';
      	     
      
         function clase_nomenclador($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM nomenclador WHERE idnomenclador=$id");
      	     $arreglo=$bd->registro();
      	     $this->idnomenclador=$arreglo['idnomenclador'];
      	     $this->codigo=$arreglo['codigo'];
      	     $this->descripcion=$arreglo['descripcion'];
      	     $this->activado=$arreglo['activado'];
      	     $this->activado_lab=$arreglo['activado_lab'];
      	     $this->codigo_contenedor=$arreglo['codigo_contenedor'];
      	     $this->codigo_loinc=$arreglo['codigo_loinc'];
      	     $this->activado_pacs=$arreglo['activado_pacs'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idnomenclador==0 || $this->idnomenclador=='' ) {
      	      if ($bd->select("INSERT INTO nomenclador(codigo,descripcion,activado,activado_lab,codigo_contenedor,codigo_loinc,activado_pacs) VALUES('".$this->codigo."','".$this->descripcion."','".$this->activado."','".$this->activado_lab."','".$this->codigo_contenedor."','".$this->codigo_loinc."','".$this->activado_pacs."')"))
      	      {
      	          $this->idnomenclador=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE nomenclador SET codigo='".$this->codigo."',descripcion='".$this->descripcion."',activado='".$this->activado."',activado_lab='".$this->activado_lab."',codigo_contenedor='".$this->codigo_contenedor."',codigo_loinc='".$this->codigo_loinc."',activado_pacs='".$this->activado_pacs."' WHERE idnomenclador='".$this->idnomenclador."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idnomenclador()
          {
               return $this->idnomenclador;
          }
          function codigo()
          {
               return $this->codigo;
          }
          function descripcion()
          {
               return $this->descripcion;
          }
          function activado()
          {
               return $this->activado;
          }
          function activado_lab()
          {
               return $this->activado_lab;
          }
          function codigo_contenedor()
          {
               return $this->codigo_contenedor;
          }
          function codigo_loinc()
          {
               return $this->codigo_loinc;
          }
          function activado_pacs()
          {
               return $this->activado_pacs;
          }
          
          
          
      	     function arreglo_foraneo_codigo()
             {
                 return $this->arreglo_foraneo_codigo;
             }
             
      
          function idnomenclador_asigna($campo)
          {
               $this->idnomenclador=$campo;
               
          }
          function codigo_asigna($campo)
          {
               $this->codigo=$campo;
               
          }
          function descripcion_asigna($campo)
          {
               $this->descripcion=$campo;
               
          }
          function activado_asigna($campo)
          {
               $this->activado=$campo;
               
          }
          function activado_lab_asigna($campo)
          {
               $this->activado_lab=$campo;
               
          }
          function codigo_contenedor_asigna($campo)
          {
               $this->codigo_contenedor=$campo;
               
          }
          function codigo_loinc_asigna($campo)
          {
               $this->codigo_loinc=$campo;
               
          }
          function activado_pacs_asigna($campo)
          {
               $this->activado_pacs=$campo;
               
          }
          
          
          
	      function foranea_codigo($codigo)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM nomenclador WHERE codigo=$codigo");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_codigo = $pro;		                              		
			}
	function todos_activados() {
        $bd = new baseDatos();
        $bd->Conectarse();
        $bd->select("SELECT * FROM nomenclador WHERE activado_lab = 1  ORDER  BY descripcion ASC");
        $pro = new clase_listar();

        for ($i = 0; $i <= $bd->numero_filas(); $i++) {
            $fila = $bd->registro();
            $pro->introducirElemento($fila);
        }
        $this->arreglo_todos_activados = $pro;
    }		
    function arreglo_todos_activados()
    {
        return $this->arreglo_todos_activados;
    }
      
}
?>