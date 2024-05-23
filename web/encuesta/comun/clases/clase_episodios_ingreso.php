<?
      class clase_episodios_ingreso       
      {
	  var $idepisodio = '';
          var $frecuencia_cardiaca = '';
          var $tension_arterial = '';
          var $enfermedad_actual = '';
          var $temperatura = '';
          var $frecuencia_respiratoria = '';
          var $saturacion = '';
          var $terapeutica = '';
          var $impresion_diagnostica = '';
          var $examenes_complementarios = '';
          var $examen_fisico = '';
          var $diagnostico_ingreso = '';
          var $unidad = '';
          var $idprofesional = '';
          var $fio2 = '';
          var $aspecto_respiratorio = '';
          var $arm = '';
          var $aspecto_cardiovascular = '';
          var $aparato_digestivo = '';
          var $aspecto_genitourinario = '';
          var $aspecto_neurologico = '';
          
      
      
      
         function clase_episodios_ingreso($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM episodios_ingreso WHERE idepisodio=$id");
      	     $arreglo=$bd->registro();
      	     $this->idepisodio=$arreglo['idepisodio'];
      	     $this->frecuencia_cardiaca=$arreglo['frecuencia_cardiaca'];
      	     $this->tension_arterial=$arreglo['tension_arterial'];
      	     $this->enfermedad_actual=$arreglo['enfermedad_actual'];
      	     $this->temperatura=$arreglo['temperatura'];
      	     $this->frecuencia_respiratoria=$arreglo['frecuencia_respiratoria'];
      	     $this->saturacion=$arreglo['saturacion'];
      	     $this->terapeutica=$arreglo['terapeutica'];
      	     $this->impresion_diagnostica=$arreglo['impresion_diagnostica'];
      	     $this->examenes_complementarios=$arreglo['examenes_complementarios'];
      	     $this->examen_fisico=$arreglo['examen_fisico'];
      	     $this->diagnostico_ingreso=$arreglo['diagnostico_ingreso'];
      	     $this->unidad=$arreglo['unidad'];
      	     $this->idprofesional=$arreglo['idprofesional'];
      	     $this->fio2=$arreglo['fio2'];
      	     $this->aspecto_respiratorio=$arreglo['aspecto_respiratorio'];
      	     $this->arm=$arreglo['arm'];
      	     $this->aspecto_cardiovascular=$arreglo['aspecto_cardiovascular'];
      	     $this->aparato_digestivo=$arreglo['aparato_digestivo'];
      	     $this->aspecto_genitourinario=$arreglo['aspecto_genitourinario'];
      	     $this->aspecto_neurologico=$arreglo['aspecto_neurologico'];
      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idepisodio==0 || $this->idepisodio=='' ) {
      	      if ($bd->select("INSERT INTO episodios_ingreso(frecuencia_cardiaca,tension_arterial,enfermedad_actual,temperatura,frecuencia_respiratoria,saturacion,terapeutica,impresion_diagnostica,examenes_complementarios,examen_fisico,diagnostico_ingreso,unidad,idprofesional,fio2,aspecto_respiratorio,arm,aspecto_cardiovascular,aparato_digestivo,aspecto_genitourinario,aspecto_neurologico) VALUES('".$this->frecuencia_cardiaca."','".$this->tension_arterial."','".$this->enfermedad_actual."','".$this->temperatura."','".$this->frecuencia_respiratoria."','".$this->saturacion."','".$this->terapeutica."','".$this->impresion_diagnostica."','".$this->examenes_complementarios."','".$this->examen_fisico."','".$this->diagnostico_ingreso."','".$this->unidad."','".$this->idprofesional."','".$this->fio2."','".$this->aspecto_respiratorio."','".$this->arm."','".$this->aspecto_cardiovascular."','".$this->aparato_digestivo."','".$this->aspecto_genitourinario."','".$this->aspecto_neurologico."')"))
      	      {
      	          $this->idepisodio=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  { 
      	        if ($bd->select("UPDATE episodios_ingreso SET frecuencia_cardiaca='".$this->frecuencia_cardiaca."',tension_arterial='".$this->tension_arterial."',enfermedad_actual='".$this->enfermedad_actual."',temperatura='".$this->temperatura."',frecuencia_respiratoria='".$this->frecuencia_respiratoria."',saturacion='".$this->saturacion."',terapeutica='".$this->terapeutica."',impresion_diagnostica='".$this->impresion_diagnostica."',examenes_complementarios='".$this->examenes_complementarios."',examen_fisico='".$this->examen_fisico."',diagnostico_ingreso='".$this->diagnostico_ingreso."',unidad='".$this->unidad."',idprofesional='".$this->idprofesional."',fio2='".$this->fio2."',aspecto_respiratorio='".$this->aspecto_respiratorio."',arm='".$this->arm."',aspecto_cardiovascular='".$this->aspecto_cardiovascular."',aparato_digestivo='".$this->aparato_digestivo."',aspecto_genitourinario='".$this->aspecto_genitourinario."',aspecto_neurologico='".$this->aspecto_neurologico."' WHERE idepisodio='".$this->idepisodio."'"))
      	        {
      	            
      	            return 1;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idepisodio()
          {
               return $this->idepisodio;
          }
          function frecuencia_cardiaca()
          {
               return $this->frecuencia_cardiaca;
          }
          function tension_arterial()
          {
               return $this->tension_arterial;
          }
          function enfermedad_actual()
          {
               return $this->enfermedad_actual;
          }
          function temperatura()
          {
               return $this->temperatura;
          }
          function frecuencia_respiratoria()
          {
               return $this->frecuencia_respiratoria;
          }
          function saturacion()
          {
               return $this->saturacion;
          }
          function terapeutica()
          {
               return $this->terapeutica;
          }
          function impresion_diagnostica()
          {
               return $this->impresion_diagnostica;
          }
          function examenes_complementarios()
          {
               return $this->examenes_complementarios;
          }
          function examen_fisico()
          {
               return $this->examen_fisico;
          }
          function diagnostico_ingreso()
          {
               return $this->diagnostico_ingreso;
          }
          function unidad()
          {
               return $this->unidad;
          }
          function idprofesional()
          {
               return $this->idprofesional;
          }
          function fio2()
          {
               return $this->fio2;
          }
          function aspecto_respiratorio()
          {
               return $this->aspecto_respiratorio;
          }
          function arm()
          {
               return $this->arm;
          }
          function aspecto_cardiovascular()
          {
               return $this->aspecto_cardiovascular;
          }
          function aparato_digestivo()
          {
               return $this->aparato_digestivo;
          }
          function aspecto_genitourinario()
          {
               return $this->aspecto_genitourinario;
          }
          function aspecto_neurologico()
          {
               return $this->aspecto_neurologico;
          }
          
          
          
      
          function idepisodio_asigna($campo)
          {
               $this->idepisodio=$campo;
               
          }
          function frecuencia_cardiaca_asigna($campo)
          {
               $this->frecuencia_cardiaca=$campo;
               
          }
          function tension_arterial_asigna($campo)
          {
               $this->tension_arterial=$campo;
               
          }
          function enfermedad_actual_asigna($campo)
          {
               $this->enfermedad_actual=$campo;
               
          }
          function temperatura_asigna($campo)
          {
               $this->temperatura=$campo;
               
          }
          function frecuencia_respiratoria_asigna($campo)
          {
               $this->frecuencia_respiratoria=$campo;
               
          }
          function saturacion_asigna($campo)
          {
               $this->saturacion=$campo;
               
          }
          function terapeutica_asigna($campo)
          {
               $this->terapeutica=$campo;
               
          }
          function impresion_diagnostica_asigna($campo)
          {
               $this->impresion_diagnostica=$campo;
               
          }
          function examenes_complementarios_asigna($campo)
          {
               $this->examenes_complementarios=$campo;
               
          }
          function examen_fisico_asigna($campo)
          {
               $this->examen_fisico=$campo;
               
          }
          function diagnostico_ingreso_asigna($campo)
          {
               $this->diagnostico_ingreso=$campo;
               
          }
          function unidad_asigna($campo)
          {
               $this->unidad=$campo;
               
          }
          function idprofesional_asigna($campo)
          {
               $this->idprofesional=$campo;
               
          }
          function fio2_asigna($campo)
          {
               $this->fio2=$campo;
               
          }
          function aspecto_respiratorio_asigna($campo)
          {
               $this->aspecto_respiratorio=$campo;
               
          }
          function arm_asigna($campo)
          {
               $this->arm=$campo;
               
          }
          function aspecto_cardiovascular_asigna($campo)
          {
               $this->aspecto_cardiovascular=$campo;
               
          }
          function aparato_digestivo_asigna($campo)
          {
               $this->aparato_digestivo=$campo;
               
          }
          function aspecto_genitourinario_asigna($campo)
          {
               $this->aspecto_genitourinario=$campo;
               
          }
          function aspecto_neurologico_asigna($campo)
          {
               $this->aspecto_neurologico=$campo;
               
          }
          
          
          
      
}
?>