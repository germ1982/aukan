<?
      class clase_uc_evolucion_nutricion       
      {
	  var $id = '';
          var $idprofesional = '';
          var $idpaciente = '';
          var $fecha = '';
          var $idosocial = '';
          var $evaluacion_antropometrica = '';
          var $sindrome_metabolico = '';
          var $seguimiento = '';
          var $peso = '';
          var $talla = '';
          var $imc = '';
          var $masa_muscular = '';
          var $grasa_muscular = '';
          var $grasa_viseral = '';
          var $en_tratamiento = '';
          var $nutricionista = '';
          var $entrega_plan = '';
          var $requiere_seguimiento = '';
          var $recomendacion_al_alta = '';
          var $circunferencia_cintura = '';
          var $motivo_consulta = '';
          var $conceptId = '';
          //var $fecha_carga = '';
          var $programada = '';
          var $id_encuesta = '';
          
      
      var $arreglo_foraneo_idprofesional='';
      	     var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_uc_evolucion_nutricion($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM uc_evolucion_nutricion WHERE id=$id");
      	     $arreglo=$bd->registro();
      	     $this->id=$arreglo['id'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->fecha=$arreglo['fecha'];
      	     $this->idosocial=$arreglo['idosocial'];
      	     $this->evaluacion_antropometrica=$arreglo['evaluacion_antropometrica'];
      	     $this->sindrome_metabolico=$arreglo['sindrome_metabolico'];
      	     $this->seguimiento=$arreglo['seguimiento'];
      	     $this->peso=$arreglo['peso'];
      	     $this->talla=$arreglo['talla'];
      	     $this->imc=$arreglo['imc'];
      	     $this->masa_muscular=$arreglo['masa_muscular'];
      	     $this->grasa_muscular=$arreglo['grasa_muscular'];
      	     $this->grasa_viseral=$arreglo['grasa_viseral'];
      	     $this->en_tratamiento=$arreglo['en_tratamiento'];
      	     $this->nutricionista=$arreglo['nutricionista'];
      	     $this->entrega_plan=$arreglo['entrega_plan'];
      	     $this->requiere_seguimiento=$arreglo['requiere_seguimiento'];
      	     $this->recomendacion_al_alta=$arreglo['recomendacion_al_alta'];
             $this->circunferencia_cintura = $arreglo['circunferencia_cintura'];
             $this->motivo_consulta = $arreglo['motivo_consulta'];
             $this->conceptId = $arreglo['conceptId'];
      	     //$this->fecha_carga=$arreglo['fecha_carga'];
             $this->programada = $arreglo['programada'];
             $this->id_encuesta = $arreglo['id_encuesta'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->id==0 || $this->id=='' ) {
      	      if ($bd->select("INSERT INTO uc_evolucion_nutricion(idprofesional,idpaciente,fecha,idosocial,evaluacion_antropometrica,sindrome_metabolico,"
                      . "seguimiento,peso,talla,imc,masa_muscular,grasa_muscular,grasa_viseral,en_tratamiento,nutricionista,entrega_plan,requiere_seguimiento,"
                      . "recomendacion_al_alta,circunferencia_cintura,motivo_consulta,conceptId,programada,id_encuesta) "
                      . "VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->idosocial."',"
                      . "'".$this->evaluacion_antropometrica."','".$this->sindrome_metabolico."','".$this->seguimiento."','".$this->peso."',"
                      . "'".$this->talla."','".$this->imc."','".$this->masa_muscular."','".$this->grasa_muscular."','".$this->grasa_viseral."',"
                      . "'".$this->en_tratamiento."','".$this->nutricionista."','".$this->entrega_plan."','".$this->requiere_seguimiento."',"
                      . "'".$this->recomendacion_al_alta."','".$this->circunferencia_cintura."','".$this->motivo_consulta."','".$this->conceptId."','".$this->programada."','".$this->id_encuesta."')"))
      	      {
      	          $this->id=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return "INSERT INTO uc_evolucion_nutricion(idprofesional,idpaciente,fecha,idosocial,evaluacion_antropometrica,sindrome_metabolico,"
                      . "seguimiento,peso,talla,imc,masa_muscular,grasa_muscular,grasa_viseral,en_tratamiento,nutricionista,entrega_plan,requiere_seguimiento,"
                      . "recomendacion_al_alta,circunferencia_cintura,motivo_consulta,conceptId,programada,id_encuesta) "
                      . "VALUES('".$this->idprofesional."','".$this->idpaciente."','".$this->fecha."','".$this->idosocial."',"
                      . "'".$this->evaluacion_antropometrica."','".$this->sindrome_metabolico."','".$this->seguimiento."','".$this->peso."',"
                      . "'".$this->talla."','".$this->imc."','".$this->masa_muscular."','".$this->grasa_muscular."','".$this->grasa_viseral."',"
                      . "'".$this->en_tratamiento."','".$this->nutricionista."','".$this->entrega_plan."','".$this->requiere_seguimiento."',"
                      . "'".$this->recomendacion_al_alta."','".$this->circunferencia_cintura."','".$this->motivo_consulta."','".$this->conceptId."','".$this->programada."','".$this->id_encuesta."')";
      	  }else
      	  { 
      	        if ($bd->select("UPDATE uc_evolucion_nutricion SET circunferencia_cintura='".$this->circunferencia_cintura."',idprofesional='".$this->idprofesional."',idpaciente='".$this->idpaciente."',fecha='".$this->fecha."',idosocial='".$this->idosocial."',evaluacion_antropometrica='".$this->evaluacion_antropometrica."',sindrome_metabolico='".$this->sindrome_metabolico."',seguimiento='".$this->seguimiento."',peso='".$this->peso."',talla='".$this->talla."',imc='".$this->imc."',masa_muscular='".$this->masa_muscular."',grasa_muscular='".$this->grasa_muscular."',grasa_viseral='".$this->grasa_viseral."',en_tratamiento='".$this->en_tratamiento."',nutricionista='".$this->nutricionista."',entrega_plan='".$this->entrega_plan."',requiere_seguimiento='".$this->requiere_seguimiento."',recomendacion_al_alta='".$this->recomendacion_al_alta."',motivo_consulta='".$this->motivo_consulta."',conceptId='".$this->conceptId."',programada='".$this->programada."',id_encuesta='".$this->id_encuesta."' WHERE id='".$this->id."'"))
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
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function fecha()
          {
               return $this->fecha;
          }
          function idosocial()
          {
               return $this->idosocial;
          }
          function evaluacion_antropometrica()
          {
               return $this->evaluacion_antropometrica;
          }
          function sindrome_metabolico()
          {
               return $this->sindrome_metabolico;
          }
          function seguimiento()
          {
               return $this->seguimiento;
          }
          function peso()
          {
               return $this->peso;
          }
          function talla()
          {
               return $this->talla;
          }
          function imc()
          {
               return $this->imc;
          }
          function masa_muscular()
          {
               return $this->masa_muscular;
          }
          function grasa_muscular()
          {
               return $this->grasa_muscular;
          }
          function grasa_viseral()
          {
               return $this->grasa_viseral;
          }
          function en_tratamiento()
          {
               return $this->en_tratamiento;
          }
          function nutricionista()
          {
               return $this->nutricionista;
          }
          function entrega_plan()
          {
               return $this->entrega_plan;
          }
          function requiere_seguimiento()
          {
               return $this->requiere_seguimiento;
          }
          function recomendacion_al_alta()
          {
               return $this->recomendacion_al_alta;
          }
          function fecha_carga()
          {
               return $this->fecha_carga;
          }
          function circunferencia_cintura()
          {
               return $this->circunferencia_cintura;
          }
          function motivo_consulta()
          {
               return $this->motivo_consulta;
          }
          function conceptId()
          {
               return $this->conceptId;
          }
          
           function programada() {
        return $this->programada;
    }
    function id_encuesta() {
        return $this->id_encuesta;
    }
          
      	     function arreglo_foraneo_idprofesional()
             {
                 return $this->arreglo_foraneo_idprofesional;
             }
             
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function id_asigna($campo)
          {
               $this->id=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function fecha_asigna($campo)
          {
               $this->fecha=$campo;
               
          }
          function idosocial_asigna($campo)
          {
               $this->idosocial=$campo;
               
          }
          function evaluacion_antropometrica_asigna($campo)
          {
               $this->evaluacion_antropometrica=$campo;
               
          }
          function sindrome_metabolico_asigna($campo)
          {
               $this->sindrome_metabolico=$campo;
               
          }
          function seguimiento_asigna($campo)
          {
               $this->seguimiento=$campo;
               
          }
          function peso_asigna($campo)
          {
               $this->peso=$campo;
               
          }
          function talla_asigna($campo)
          {
               $this->talla=$campo;
               
          }
          function imc_asigna($campo)
          {
               $this->imc=$campo;
               
          }
          function masa_muscular_asigna($campo)
          {
               $this->masa_muscular=$campo;
               
          }
          function grasa_muscular_asigna($campo)
          {
               $this->grasa_muscular=$campo;
               
          }
          function grasa_viseral_asigna($campo)
          {
               $this->grasa_viseral=$campo;
               
          }
          function en_tratamiento_asigna($campo)
          {
               $this->en_tratamiento=$campo;
               
          }
          function nutricionista_asigna($campo)
          {
               $this->nutricionista=$campo;
               
          }
          function entrega_plan_asigna($campo)
          {
               $this->entrega_plan=$campo;
               
          }
          function requiere_seguimiento_asigna($campo)
          {
               $this->requiere_seguimiento=$campo;
               
          }
          function recomendacion_al_alta_asigna($campo)
          {
               $this->recomendacion_al_alta=$campo;
               
          }
          function fecha_carga_asigna($campo)
          {
               $this->fecha_carga=$campo;
               
          }
          function circunferencia_cintura_asigna($campo)
          {
                $this->circunferencia_cintura=$campo;
          }
          function motivo_consulta_asigna($campo)
          {
                $this->motivo_consulta=$campo;
          }
          function conceptId_asigna($campo)
          {
                $this->conceptId=$campo;
          }
          
           function programada_asigna($campo)
            {
                 $this->programada=$campo;
            }
            function id_encuesta_asigna($campo)
            {
                 $this->id_encuesta=$campo;
            }
          
          
	      function foranea_idprofesional($idprofesional)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM uc_evolucion_nutricion WHERE idprofesional=$idprofesional");				
				$pro = new clase_listar();			
								
	    		for($i=0;$i<=$bd->numero_filas();$i++) 
	    		{
	    			$fila = $bd->registro(); 
	    			$pro->introducirElemento($fila); 
	    		}
	    		$this->arreglo_foraneo_idprofesional = $pro;		                              		
			}
			
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM uc_evolucion_nutricion WHERE idpaciente=$idpaciente");				
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