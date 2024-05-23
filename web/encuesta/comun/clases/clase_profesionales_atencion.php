<?
      class clase_profesionales_atencion       
      {
	  var $idprofesional = '';
          var $fecha = '';
          var $dia_semana = '';
          var $hora_inicio = '';
          var $hora_fin = '';
          var $turno = '';
          var $hora_inicio1 = '';
          var $hora_fin1 = '';
          var $id = '';
          var $idlugar = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     
      
         function clase_profesionales_atencion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM profesionales_atencion WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->dia_semana=$arreglo['dia_semana'];
      	     $this->hora_inicio=$arreglo['hora_inicio'];
      	     $this->hora_fin=$arreglo['hora_fin'];
      	     $this->turno=$arreglo['turno'];
      	     $this->hora_inicio1=$arreglo['hora_inicio1'];
      	     $this->hora_fin1=$arreglo['hora_fin1'];
      	     $this->id=$arreglo['id'];
      	     $this->idlugar=$arreglo['idlugar'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO profesionales_atencion(idprofesional,fecha,dia_semana,hora_inicio,hora_fin,turno,hora_inicio1,hora_fin1,idlugar) VALUES('".$this->idprofesional."','".$this->fecha."','".$this->dia_semana."','".$this->hora_inicio."','".$this->hora_fin."','".$this->turno."','".$this->hora_inicio1."','".$this->hora_fin1."','".$this->idlugar."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE profesionales_atencion SET idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',dia_semana='".$this->dia_semana."',hora_inicio='".$this->hora_inicio."',hora_fin='".$this->hora_fin."',turno='".$this->turno."',hora_inicio1='".$this->hora_inicio1."',hora_fin1='".$this->hora_fin1."',idlugar='".$this->idlugar."' WHERE id='".$this->id."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function dia_semana()
          {
               return $this->dia_semana;
          }
          function hora_inicio()
          {
               return $this->hora_inicio;
          }
          function hora_fin()
          {
               return $this->hora_fin;
          }
          function turno()
          {
               return $this->turno;
          }
          function hora_inicio1()
          {
               return $this->hora_inicio1;
          }
          function hora_fin1()
          {
               return $this->hora_fin1;
          }
          function id()
          {
               return $this->id;
          }
          function idlugar()
          {
               return $this->idlugar;
          }
          
          
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function dia_semana_asigna($campo)
          {
               $this->dia_semana=$campo;
               
          }
          function hora_inicio_asigna($campo)
          {
               $this->hora_inicio=$campo;
               
          }
          function hora_fin_asigna($campo)
          {
               $this->hora_fin=$campo;
               
          }
          function turno_asigna($campo)
          {
               $this->turno=$campo;
               
          }
          function hora_inicio1_asigna($campo)
          {
               $this->hora_inicio1=$campo;
               
          }
          function hora_fin1_asigna($campo)
          {
               $this->hora_fin1=$campo;
               
          }
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idlugar_asigna($campo)
          {
               $this->idlugar=$campo;
               
          }
          
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM profesionales_atencion WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
      
}
?>