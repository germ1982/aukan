<?
      class clase_indicaciones_controles_balance       
      {
	  var $id = '';
          var $idepisodio = '';
          var $idprofesional = '';
          var $fecha = '';
          var $hora = '';
          var $estado = '';
          
      
      var $arreglo_foraneo_idepisodio='';
      	     
      
         function clase_indicaciones_controles_balance($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM indicaciones_controles_balance WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->hora=$arreglo['hora'];
      	     $this->estado=$arreglo['estado'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO indicaciones_controles_balance(idepisodio,idprofesional,fecha,hora,estado) VALUES('".$this->idepisodio."','".$this->idprofesional."','".$this->fecha."','".$this->hora."','".$this->estado."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE indicaciones_controles_balance SET idepisodio='".$this->idepisodio."',idprofesional='".$this->idprofesional."',fecha='".$this->fecha."',hora='".$this->hora."',estado='".$this->estado."' WHERE id='".$this->id."'"))
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
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function hora()
          {
               return $this->hora;
          }
          function estado()
          {
               return $this->estado;
          }
          
          
          
      	     function arreglo_foraneo_idepisodio()
             {
                 return $this->arreglo_foraneo_idepisodio;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function hora_asigna($campo)
          {
               $this->hora=$campo;
               
          }
          function estado_asigna($campo)
          {
               $this->estado=$campo;
               
          }
          
          
          
	      function foranea_idepisodio($idepisodio)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicaciones_controles_balance WHERE idepisodio=$idepisodio");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idepisodio = $pro;		                              		
			}
			function control_balance_activo($idepisodio)
			{
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM indicaciones_controles_balance WHERE idepisodio=$idepisodio  AND estado=1");
				$arreglo = $bd->registro();
				return $arreglo;
			}
			function verificarNuevoDiaControlBalance($idepisodio)
			{
				$bd = new baseDatos();
				$bd->Conectarse();
				$bd->select("SELECT * FROM indicaciones_controles_balance WHERE idepisodio=$idepisodio AND fecha='".date('Y-m-d')."' AND hora >= '06:00'");
				if ($arreglo=$bd->registro())
				{
				    //la fecha existe por lo tanto coloco el estado en 1
					if ($bd->select("UPDATE indicaciones_controles_balance SET estado=1 WHERE id=".$arreglo['id']))					
					    return $arreglo['id'];					  
					else
						return 0;
				}
				else
				{
					if ($bd->select("UPDATE indicaciones_controles_balance SET estado=0 WHERE idepisodio=$idepisodio"))
				  	{
				        $bd->cerrar();
					    return 1;
				    }
				    else
				    {
				        $bd->cerrar();
					    return 0;
				    }
				}
			}
	      function cargarControlBalanceDia($idepisodio,$idprofesional)
		  {
		      $bd = new baseDatos();
			  $bd->Conectarse();
			  $bd->select("SELECT * FROM indicaciones_controles_balance WHERE idepisodio=$idepisodio AND estado=1");
			  $indi = $bd->registro();
			  //quiere decir que no existe nada creado para este dia aun
			  if ($indi['id'] == 0 || $indi['id'] == "")
			  {
			      if ($bd->select("SELECT MAX(id) as id FROM indicaciones_controles_balance_nro"))
				  {
				      $idMax = $bd->registro();
					  $id = $idMax['id'] + 1;
					  if ($bd->select("UPDATE indicaciones_controles_balance_nro SET id=$id WHERE id=".$idMax['id']))
					  {
					      if ($bd->select("INSERT INTO indicaciones_controles_balance(id,idepisodio,idprofesional,fecha,hora) 
						                  values($id,$idepisodio,$idprofesional,'".date('Y-m-d')."','".date('H:i')."')"))
		                  {
						      $bd->cerrar();								  
		                      return $id;
		                  }
						  else
						  {
						      $bd->cerrar();
						      return 0;					   								  
						  }
					  }
					  else
					  {
					      $bd->cerrar();
					      return 0;
					  } 
				  }
				  else
				  {
				      $bd->cerrar();
				      return 0;
				  } 
			  }
			  else
			  {
			      $bd->cerrar();
			      return $indi['id'];
			  } 
		  }      
}
?>