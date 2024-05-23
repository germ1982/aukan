<?
      class clase_pacientes_alergias       
      {
	  var $id = '';
          var $idpaciente = '';
          var $texto_tesauro = '';
          var $descriptionid = '';
          var $subsetid = '';
          var $enfermedad_asociada = '';
          var $descriptionid_enfermedad_asociada = '';
          var $subsetid_enfermedad_asociada = '';
          var $causas = '';
          var $sintomas_relacionado_con1 = '';
          var $sintomas_relacionado_con2 = '';
          var $sintomas_relacionado_con3 = '';
          var $sintomas_relacionado_con4 = '';
          var $tipo = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	   
      
         function clase_pacientes_alergias($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pacientes_alergias WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->texto_tesauro=$arreglo['texto_tesauro'];
      	     $this->descriptionid=$arreglo['descriptionid'];
      	     $this->subsetid=$arreglo['subsetid'];
      	     $this->enfermedad_asociada=$arreglo['enfermedad_asociada'];
      	     $this->descriptionid_enfermedad_asociada=$arreglo['descriptionid_enfermedad_asociada'];
      	     $this->subsetid_enfermedad_asociada=$arreglo['subsetid_enfermedad_asociada'];
      	     $this->causas=$arreglo['causas'];
      	     $this->sintomas_relacionado_con1=$arreglo['sintomas_relacionado_con1'];
      	     $this->sintomas_relacionado_con2=$arreglo['sintomas_relacionado_con2'];
      	     $this->sintomas_relacionado_con3=$arreglo['sintomas_relacionado_con3'];
      	     $this->sintomas_relacionado_con4=$arreglo['sintomas_relacionado_con4'];
      	     $this->tipo=$arreglo['tipo'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO pacientes_alergias(idpaciente,texto_tesauro,descriptionid,subsetid,enfermedad_asociada,descriptionid_enfermedad_asociada,subsetid_enfermedad_asociada,causas,sintomas_relacionado_con1,sintomas_relacionado_con2,sintomas_relacionado_con3,sintomas_relacionado_con4,tipo) VALUES('".$this->idpaciente."','".$this->texto_tesauro."','".$this->descriptionid."','".$this->subsetid."','".$this->enfermedad_asociada."','".$this->descriptionid_enfermedad_asociada."','".$this->subsetid_enfermedad_asociada."','".$this->causas."','".$this->sintomas_relacionado_con1."','".$this->sintomas_relacionado_con2."','".$this->sintomas_relacionado_con3."','".$this->sintomas_relacionado_con4."','".$this->tipo."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE pacientes_alergias SET idpaciente='".$this->idpaciente."',texto_tesauro='".$this->texto_tesauro."',descriptionid='".$this->descriptionid."',subsetid='".$this->subsetid."',enfermedad_asociada='".$this->enfermedad_asociada."',descriptionid_enfermedad_asociada='".$this->descriptionid_enfermedad_asociada."',subsetid_enfermedad_asociada='".$this->subsetid_enfermedad_asociada."',causas='".$this->causas."',sintomas_relacionado_con1='".$this->sintomas_relacionado_con1."',sintomas_relacionado_con2='".$this->sintomas_relacionado_con2."',sintomas_relacionado_con3='".$this->sintomas_relacionado_con3."',sintomas_relacionado_con4='".$this->sintomas_relacionado_con4."',tipo='".$this->tipo."' WHERE id='".$this->id."'"))
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
          function texto_tesauro()
          {
               return $this->texto_tesauro;
          }
          function descriptionid()
          {
               return $this->descriptionid;
          }
          function subsetid()
          {
               return $this->subsetid;
          }
          function enfermedad_asociada()
          {
               return $this->enfermedad_asociada;
          }
          function descriptionid_enfermedad_asociada()
          {
               return $this->descriptionid_enfermedad_asociada;
          }
          function subsetid_enfermedad_asociada()
          {
               return $this->subsetid_enfermedad_asociada;
          }
          function causas()
          {
               return $this->causas;
          }
          function sintomas_relacionado_con1()
          {
               return $this->sintomas_relacionado_con1;
          }
          function sintomas_relacionado_con2()
          {
               return $this->sintomas_relacionado_con2;
          }
          function sintomas_relacionado_con3()
          {
               return $this->sintomas_relacionado_con3;
          }
          function sintomas_relacionado_con4()
          {
               return $this->sintomas_relacionado_con4;
          }
          function tipo()
          {
               return $this->tipo;
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
          function texto_tesauro_asigna($campo)
          {
               $this->texto_tesauro=$campo;
               
          }
          function descriptionid_asigna($campo)
          {
               $this->descriptionid=$campo;
               
          }
          function subsetid_asigna($campo)
          {
               $this->subsetid=$campo;
               
          }
          function enfermedad_asociada_asigna($campo)
          {
               $this->enfermedad_asociada=$campo;
               
          }
          function descriptionid_enfermedad_asociada_asigna($campo)
          {
               $this->descriptionid_enfermedad_asociada=$campo;
               
          }
          function subsetid_enfermedad_asociada_asigna($campo)
          {
               $this->subsetid_enfermedad_asociada=$campo;
               
          }
          function causas_asigna($campo)
          {
               $this->causas=$campo;
               
          }
          function sintomas_relacionado_con1_asigna($campo)
          {
               $this->sintomas_relacionado_con1=$campo;
               
          }
          function sintomas_relacionado_con2_asigna($campo)
          {
               $this->sintomas_relacionado_con2=$campo;
               
          }
          function sintomas_relacionado_con3_asigna($campo)
          {
               $this->sintomas_relacionado_con3=$campo;
               
          }
          function sintomas_relacionado_con4_asigna($campo)
          {
               $this->sintomas_relacionado_con4=$campo;
               
          }
          function tipo_asigna($campo)
          {
               $this->tipo=$campo;
               
          }
          
          
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM pacientes_alergias WHERE idpaciente=$idpaciente");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			if ($fila['id'] != '' && $fila['id'] != 0)
	    			    $pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idpaciente = $pro;		                              		
			}
			
      
}
?>