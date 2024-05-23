<?
      class clase_pacientes_embarazos       
      {
	  var $idpaciente_embarazo = '';
          var $idpaciente = '';
          var $numero_embarazo = '';
          var $fum = '';
          
          var $antitetanica_previa = '';
          var $antitetanica_previa_observaciones = '';
          var $peso_anterior = '';
          
          var $ex_clinico_normal = '';
          var $ex_mamas_normal = '';
          var $ex_odontologico_normal = '';
          var $pelvis_normal = '';
          var $papanicolau_normal = '';
          var $colposcopia_normal = '';
          var $cervix_normal = '';
          var $vdrl_negativo = '';
          var $vdrl_1_fecha = '';
          var $vdrl_2 = '';
          var $vdrl_2_fecha = '';          
          var $presentacion = '';
          var $tamano_fetal_acorde = '';          
          var $muerte_intrauterina = '';
          var $episiotomia = '';
          var $desgarros = '';
          var $alumb_espontaneo = '';
          var $placenta_compl = '';
          var $terminacion = '';
          var $fecha_hora = '';                    
          var $observaciones_parto = '';
          var $ex_fisico_normal = '';
          var $toxoplasma = '';
          var $toxoplasma_fecha = '';
          var $chagas = '';
          var $chagas_fecha = '';
          var $hiv = '';
          var $hiv_fecha = '';
          var $hepatitis_b = '';
          var $hepatitis_b_fecha = '';
          var $streptococo = '';
          var $streptococo_descripcion = '';                    
          var $coombs = '';
          var $rm = '';
          var $rm_hora = '';
          var $edad_gestacional_fum = '';
          var $edad_gestacional_eco = '';
          
      
      var $arreglo_foraneo_idpaciente='';
      	     
      
         function clase_pacientes_embarazos($id)
         {
      	     $bd = new baseDatos();
      	     $bd->Conectarse();
      	     $bd->select("SELECT * FROM pacientes_embarazos WHERE idpaciente_embarazo=$id");
      	     $arreglo=$bd->registro();
      	     $this->idpaciente_embarazo=$arreglo['idpaciente_embarazo'];
      	     $this->idpaciente=$arreglo['idpaciente'];
      	     $this->numero_embarazo=$arreglo['numero_embarazo'];
      	     $this->fum=$arreglo['fum'];      	     
      	     $this->antitetanica_previa=$arreglo['antitetanica_previa'];
      	     $this->antitetanica_previa_observaciones=$arreglo['antitetanica_previa_observaciones'];
      	     $this->peso_anterior=$arreglo['peso_anterior'];      	           	           	     
      	     $this->ex_clinico_normal=$arreglo['ex_clinico_normal'];
      	     $this->ex_mamas_normal=$arreglo['ex_mamas_normal'];
      	     $this->ex_odontologico_normal=$arreglo['ex_odontologico_normal'];
      	     $this->pelvis_normal=$arreglo['pelvis_normal'];
      	     $this->papanicolau_normal=$arreglo['papanicolau_normal'];
      	     $this->colposcopia_normal=$arreglo['colposcopia_normal'];
      	     $this->cervix_normal=$arreglo['cervix_normal'];
      	     $this->vdrl_negativo=$arreglo['vdrl_negativo'];
      	     $this->vdrl_1_fecha=$arreglo['vdrl_1_fecha'];
      	     $this->vdrl_2=$arreglo['vdrl_2'];
      	     $this->vdrl_2_fecha=$arreglo['vdrl_2_fecha'];      	     
      	     $this->presentacion=$arreglo['presentacion'];
      	     $this->tamano_fetal_acorde=$arreglo['tamano_fetal_acorde'];      	    
      	     $this->muerte_intrauterina=$arreglo['muerte_intrauterina'];
      	     $this->episiotomia=$arreglo['episiotomia'];
      	     $this->desgarros=$arreglo['desgarros'];
      	     $this->alumb_espontaneo=$arreglo['alumb_espontaneo'];
      	     $this->placenta_compl=$arreglo['placenta_compl'];
      	     $this->terminacion=$arreglo['terminacion'];
      	     $this->fecha_hora=$arreglo['fecha_hora'];      	           	           	    
      	     $this->observaciones_parto=$arreglo['observaciones_parto'];
      	     $this->ex_fisico_normal=$arreglo['ex_fisico_normal'];
      	     $this->toxoplasma=$arreglo['toxoplasma'];
      	     $this->toxoplasma_fecha=$arreglo['toxoplasma_fecha'];
      	     $this->chagas=$arreglo['chagas'];
      	     $this->chagas_fecha=$arreglo['chagas_fecha'];
      	     $this->hiv=$arreglo['hiv'];
      	     $this->hiv_fecha=$arreglo['hiv_fecha'];
      	     $this->hepatitis_b=$arreglo['hepatitis_b'];
      	     $this->hepatitis_b_fecha=$arreglo['hepatitis_b_fecha'];
      	     $this->streptococo=$arreglo['streptococo'];
      	     $this->streptococo_descripcion=$arreglo['streptococo_descripcion'];      	    
      	     $this->coombs=$arreglo['coombs'];
      	     $this->rm=$arreglo['rm'];
      	     $this->rm_hora=$arreglo['rm_hora'];
      	     $this->edad_gestacional_eco = $arreglo['edad_gestacional_eco'];
      	     $this->edad_gestacional_fum = $arreglo['edad_gestacional_fum'];      	     
      	 }
      	        
      
      
      function guardar()
      {
          $bd = new baseDatos();
      	  $bd->Conectarse();
      	  if ($this->idpaciente_embarazo==0 || $this->idpaciente_embarazo=='' ) {      	  	
      	      if ($bd->select("INSERT INTO pacientes_embarazos(idpaciente,numero_embarazo,fum,antitetanica_previa,antitetanica_previa_observaciones,
      	      peso_anterior,ex_clinico_normal,ex_mamas_normal,ex_odontologico_normal,pelvis_normal,papanicolau_normal,
      	      colposcopia_normal,cervix_normal,vdrl_negativo,vdrl_1_fecha,vdrl_2,vdrl_2_fecha,presentacion,tamano_fetal_acorde,
      	      muerte_intrauterina,episiotomia,desgarros,
      	      alumb_espontaneo,placenta_compl,terminacion,fecha_hora,
      	      observaciones_parto,ex_fisico_normal,toxoplasma,toxoplasma_fecha,chagas,chagas_fecha,hiv,hiv_fecha,hepatitis_b,
      	      hepatitis_b_fecha,streptococo,streptococo_descripcion,coombs,rm,
      	      rm_hora,edad_gestacional_fum,edad_gestacional_eco) 
      	      VALUES('".$this->idpaciente."','".$this->numero_embarazo."','".$this->fum."','".$this->antitetanica_previa."',
      	      '".$this->antitetanica_previa_observaciones."','".$this->peso_anterior."','".$this->ex_clinico_normal."','".$this->ex_mamas_normal."',
      	      '".$this->ex_odontologico_normal."','".$this->pelvis_normal."','".$this->papanicolau_normal."','".$this->colposcopia_normal."',
      	      '".$this->cervix_normal."','".$this->vdrl_negativo."','".$this->vdrl_1_fecha."','".$this->vdrl_2."','".$this->vdrl_2_fecha."',
      	      '".$this->presentacion."','".$this->tamano_fetal_acorde."','".$this->muerte_intrauterina."','".$this->episiotomia."',
      	      '".$this->desgarros."','".$this->alumb_espontaneo."','".$this->placenta_compl."','".$this->terminacion."',
      	      '".$this->fecha_hora."','".$this->observaciones_parto."','".$this->ex_fisico_normal."','".$this->toxoplasma."',
      	      '".$this->toxoplasma_fecha."','".$this->chagas."','".$this->chagas_fecha."','".$this->hiv."','".$this->hiv_fecha."',
      	      '".$this->hepatitis_b."','".$this->hepatitis_b_fecha."','".$this->streptococo."','".$this->streptococo_descripcion."',
      	      '".$this->coombs."','".$this->rm."','".$this->rm_hora."','".$this->edad_gestacional_fum."','".$this->edad_gestacional_eco."')"))
      	      {
      	          $this->idpaciente_embarazo=$bd->ultimo_id();
      	          return 1;
      	      }
      	      else      	  
      	          return 0;
      	  }else
      	  {       	  	
      	        if ($bd->select("UPDATE pacientes_embarazos SET idpaciente='".$this->idpaciente."',numero_embarazo='".$this->numero_embarazo."',
      	        fum='".$this->fum."',antitetanica_previa='".$this->antitetanica_previa."',antitetanica_previa_observaciones='".$this->antitetanica_previa_observaciones."',
      	        peso_anterior='".$this->peso_anterior."',ex_clinico_normal='".$this->ex_clinico_normal."',ex_mamas_normal='".$this->ex_mamas_normal."',ex_odontologico_normal='".$this->ex_odontologico_normal."',
      	        pelvis_normal='".$this->pelvis_normal."',papanicolau_normal='".$this->papanicolau_normal."',colposcopia_normal='".$this->colposcopia_normal."',cervix_normal='".$this->cervix_normal."',
      	        vdrl_negativo='".$this->vdrl_negativo."',vdrl_1_fecha='".$this->vdrl_1_fecha."',vdrl_2='".$this->vdrl_2."',vdrl_2_fecha='".$this->vdrl_2_fecha."',
      	        presentacion='".$this->presentacion."',tamano_fetal_acorde='".$this->tamano_fetal_acorde."',muerte_intrauterina='".$this->muerte_intrauterina."',
      	        episiotomia='".$this->episiotomia."',desgarros='".$this->desgarros."',alumb_espontaneo='".$this->alumb_espontaneo."',placenta_compl='".$this->placenta_compl."',
      	        terminacion='".$this->terminacion."',fecha_hora='".$this->fecha_hora."',observaciones_parto='".$this->observaciones_parto."',ex_fisico_normal='".$this->ex_fisico_normal."',
      	        toxoplasma='".$this->toxoplasma."',toxoplasma_fecha='".$this->toxoplasma_fecha."',chagas='".$this->chagas."',
      	        chagas_fecha='".$this->chagas_fecha."',hiv='".$this->hiv."',hiv_fecha='".$this->hiv_fecha."',
      	        hepatitis_b='".$this->hepatitis_b."',hepatitis_b_fecha='".$this->hepatitis_b_fecha."',streptococo='".$this->streptococo."',
      	        streptococo_descripcion='".$this->streptococo_descripcion."',coombs='".$this->coombs."',rm='".$this->rm."',rm_hora='".$this->rm_hora."',
      	        edad_gestacional_fum='".$this->edad_gestacional_fum."',edad_gestacional_eco='".$this->edad_gestacional_eco."' WHERE idpaciente_embarazo='".$this->idpaciente_embarazo."'"))
      	        {
      	            
      	            return 0;
      	        }
      	        else      	  
      	            return 0;
      	  }
      }
      		
      
           
          function idpaciente_embarazo()
          {
               return $this->idpaciente_embarazo;
          }
          function idpaciente()
          {
               return $this->idpaciente;
          }
          function numero_embarazo()
          {
               return $this->numero_embarazo;
          }
          function fum()
          {
               return $this->fum;
          }
          
          function antitetanica_previa()
          {
               return $this->antitetanica_previa;
          }
          function antitetanica_previa_observaciones()
          {
               return $this->antitetanica_previa_observaciones;
          }
          function peso_anterior()
          {
               return $this->peso_anterior;
          }
          
          function ex_clinico_normal()
          {
               return $this->ex_clinico_normal;
          }
          function ex_mamas_normal()
          {
               return $this->ex_mamas_normal;
          }
          function ex_odontologico_normal()
          {
               return $this->ex_odontologico_normal;
          }
          function pelvis_normal()
          {
               return $this->pelvis_normal;
          }
          function papanicolau_normal()
          {
               return $this->papanicolau_normal;
          }
          function colposcopia_normal()
          {
               return $this->colposcopia_normal;
          }
          function cervix_normal()
          {
               return $this->cervix_normal;
          }
          function vdrl_negativo()
          {
               return $this->vdrl_negativo;
          }
          function vdrl_1_fecha()
          {
               return $this->vdrl_1_fecha;
          }
          function vdrl_2()
          {
               return $this->vdrl_2;
          }
          function vdrl_2_fecha()
          {
               return $this->vdrl_2_fecha;
          }          
          function presentacion()
          {
               return $this->presentacion;
          }
          function tamano_fetal_acorde()
          {
               return $this->tamano_fetal_acorde;
          }
          
          function muerte_intrauterina()
          {
               return $this->muerte_intrauterina;
          }
          function episiotomia()
          {
               return $this->episiotomia;
          }
          function desgarros()
          {
               return $this->desgarros;
          }
          function alumb_espontaneo()
          {
               return $this->alumb_espontaneo;
          }
          function placenta_compl()
          {
               return $this->placenta_compl;
          }
          function terminacion()
          {
               return $this->terminacion;
          }
          function fecha_hora()
          {
               return $this->fecha_hora;
          }                    
          function observaciones_parto()
          {
               return $this->observaciones_parto;
          }
          function ex_fisico_normal()
          {
               return $this->ex_fisico_normal;
          }
          function toxoplasma()
          {
               return $this->toxoplasma;
          }
          function toxoplasma_fecha()
          {
               return $this->toxoplasma_fecha;
          }
          function chagas()
          {
               return $this->chagas;
          }
          function chagas_fecha()
          {
               return $this->chagas_fecha;
          }
          function hiv()
          {
               return $this->hiv;
          }
          function hiv_fecha()
          {
               return $this->hiv_fecha;
          }
          function hepatitis_b()
          {
               return $this->hepatitis_b;
          }
          function hepatitis_b_fecha()
          {
               return $this->hepatitis_b_fecha;
          }
          function streptococo()
          {
               return $this->streptococo;
          }
          function streptococo_descripcion()
          {
               return $this->streptococo_descripcion;
          }          
          function coombs()
          {
               return $this->coombs;
          }
          function rm()
          {
               return $this->rm;
          }
          function rm_hora()
          {
               return $this->rm_hora;
          }
          function edad_gestacional_fum()
          {
              return $this->edad_gestacional_fum;
          }
          function edad_gestacional_eco()
          {
              return $this->edad_gestacional_eco; 	 
          }
          
      	     function arreglo_foraneo_idpaciente()
             {
                 return $this->arreglo_foraneo_idpaciente;
             }
             
      
          function idpaciente_embarazo_asigna($campo)
          {
               $this->idpaciente_embarazo=$campo;
               
          }
          function idpaciente_asigna($campo)
          {
               $this->idpaciente=$campo;
               
          }
          function numero_embarazo_asigna($campo)
          {
               $this->numero_embarazo=$campo;
               
          }
          function fum_asigna($campo)
          {
               $this->fum=$campo;
               
          }         
          function antitetanica_previa_asigna($campo)
          {
               $this->antitetanica_previa=$campo;
               
          }
          function antitetanica_previa_observaciones_asigna($campo)
          {
               $this->antitetanica_previa_observaciones=$campo;
               
          }
          function peso_anterior_asigna($campo)
          {
               $this->peso_anterior=$campo;
               
          }          
          function ex_clinico_normal_asigna($campo)
          {
               $this->ex_clinico_normal=$campo;
               
          }
          function ex_mamas_normal_asigna($campo)
          {
               $this->ex_mamas_normal=$campo;
               
          }
          function ex_odontologico_normal_asigna($campo)
          {
               $this->ex_odontologico_normal=$campo;
               
          }
          function pelvis_normal_asigna($campo)
          {
               $this->pelvis_normal=$campo;
               
          }
          function papanicolau_normal_asigna($campo)
          {
               $this->papanicolau_normal=$campo;
               
          }
          function colposcopia_normal_asigna($campo)
          {
               $this->colposcopia_normal=$campo;
               
          }
          function cervix_normal_asigna($campo)
          {
               $this->cervix_normal=$campo;
               
          }
          function vdrl_negativo_asigna($campo)
          {
               $this->vdrl_negativo=$campo;
               
          }
          function vdrl_1_fecha_asigna($campo)
          {
               $this->vdrl_1_fecha=$campo;
               
          }
          function vdrl_2_asigna($campo)
          {
               $this->vdrl_2=$campo;
               
          }
          function vdrl_2_fecha_asigna($campo)
          {
               $this->vdrl_2_fecha=$campo;
               
          }          
          function presentacion_asigna($campo)
          {
               $this->presentacion=$campo;
               
          }
          function tamano_fetal_acorde_asigna($campo)
          {
               $this->tamano_fetal_acorde=$campo;
               
          }         
          function muerte_intrauterina_asigna($campo)
          {
               $this->muerte_intrauterina=$campo;
               
          }
          function episiotomia_asigna($campo)
          {
               $this->episiotomia=$campo;
               
          }
          function desgarros_asigna($campo)
          {
               $this->desgarros=$campo;
               
          }
          function alumb_espontaneo_asigna($campo)
          {
               $this->alumb_espontaneo=$campo;
               
          }
          function placenta_compl_asigna($campo)
          {
               $this->placenta_compl=$campo;
               
          }
          function terminacion_asigna($campo)
          {
               $this->terminacion=$campo;
               
          }
          function fecha_hora_asigna($campo)
          {
               $this->fecha_hora=$campo;
               
          }          
          function observaciones_parto_asigna($campo)
          {
               $this->observaciones_parto=$campo;
               
          }
          function ex_fisico_normal_asigna($campo)
          {
               $this->ex_fisico_normal=$campo;
               
          }
          function toxoplasma_asigna($campo)
          {
               $this->toxoplasma=$campo;
               
          }
          function toxoplasma_fecha_asigna($campo)
          {
               $this->toxoplasma_fecha=$campo;
               
          }
          function chagas_asigna($campo)
          {
               $this->chagas=$campo;
               
          }
          function chagas_fecha_asigna($campo)
          {
               $this->chagas_fecha=$campo;
               
          }
          function hiv_asigna($campo)
          {
               $this->hiv=$campo;
               
          }
          function hiv_fecha_asigna($campo)
          {
               $this->hiv_fecha=$campo;
               
          }
          function hepatitis_b_asigna($campo)
          {
               $this->hepatitis_b=$campo;
               
          }
          function hepatitis_b_fecha_asigna($campo)
          {
               $this->hepatitis_b_fecha=$campo;
               
          }
          function streptococo_asigna($campo)
          {
               $this->streptococo=$campo;
               
          }
          function streptococo_descripcion_asigna($campo)
          {
               $this->streptococo_descripcion=$campo;
               
          }          
          function coombs_asigna($campo)
          {
               $this->coombs=$campo;
               
          }
          function rm_asigna($campo)
          {
               $this->rm=$campo;
               
          }
          function rm_hora_asigna($campo)
          {
               $this->rm_hora=$campo;
               
          }
          function edad_gestacional_fum_asigna($campo)
          {
              $this->edad_gestacional_fum = $campo;	
          }
          function edad_gestacional_eco_asigna($campo)
          {
          	  $this->edad_gestacional_eco = $campo;
          }
          
	      function foranea_idpaciente($idpaciente)
		  {
				$bd = new baseDatos();
				$bd->Conectarse();		    
				$bd->select("SELECT * FROM pacientes_embarazos WHERE idpaciente=$idpaciente");				
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