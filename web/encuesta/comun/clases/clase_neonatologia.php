<?
	class clase_neonatologia extends clase_evolucion
	{
		var $id ='';
		var $fecha = '';
		var $hora = '';
		var $idepisodio = '';
		var $idprofesional = '';				
		var $edad_gestacional_corregida ='';
		var $frecuencia_respiratoria ='';
		var $frecuencia_cardiaca ='';
		var $temperatura ='';
		var $tension_arterial_minima ='';
		var $tension_arterial_maxima ='';
		var $tension_arterial_media ='';
		var $saturacion ='';
		var $aire_ambiente ='';
		var $fio2 ='';
		var $dextro ='';
		var $arm ='';
		var $cpap ='';
		var $canula_nasal ='';
		var $paciente_en ='';
		var $peso_actual ='';
		var $peso_anterior_estado ='';
		var $peso_anterior_valor ='';
		var $hidroelectrolitico_ingresos ='';
		var $hidroelectrolitico_egresos ='';
		var $hidroelectrolitico_e_i ='';
		var $hidroelectrolitico_pia ='';
		var $hidroelectrolitico_nb ='';
		var $hidroelectrolitico_ritmo_diuretico ='';
		var $aportes_php ='';
		var $aportes_via_oral ='';
		var $aportes_via_oral_tipo ='';
		var $aportes_leche ='';
		var $aportes_leche_otras ='';
		var $aportes_npt ='';
		var $vias ='';
		var $via_central_tipo ='';
		var $via_central_umbilical ='';
		var $sitio_colocacion ='';
		var $dias_colocacion ='';
		var $sistema_respiratorio_tipo ='';
		var $arm_convencional_pim ='';
		var $arm_convencional_peep ='';
		var $arm_convencional_ti ='';
		var $arm_convencional_fr ='';
		var $arm_convencional_fio2 ='';
		var $arm_convencional_map ='';
		var $arm_convencional_io ='';
		var $arm_alta_frecuencia_map ='';
		var $arm_alta_frecuencia_fio2 ='';
		var $arm_alta_frecuencia_amplitud ='';
		var $arm_alta_frecuencia_hz ='';
		var $cpap_peep ='';
		var $cpap_fio2 ='';
		var $surfactante ='';
		var $surfactante_dosis ='';
		var $sistema_respiratorio ='';
		var $sistema_cardiovascular_drogas_inotropicas ='';
		var $sistema_cardiovascular_otras_drogas ='';
		var $sistema_cardiovascular ='';
		var $aparato_digestivo_catarsis ='';
		var $aparato_digestivo ='';
		var $sistema_nervioso ='';
		var $aspecto_infectologico_antibioticos ='';
		var $aspecto_infectologico_cultivos ='';
		var $observaciones ='';
		var $comentario_permanencia ='';
		var $aspecto_general ='';
		var $hidroelectrolitico_egresos_gastricos ='';
		var $hidroelectrolitico_egresos_ostomias ='';
		var $edad_cronologica ='';
		var $uti ='';
		function clase_neonatologia($id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    $consulta = "SELECT * FROM evolucion_neonatologia WHERE id=$id";
			$bd->select($consulta);
			$arreglo = $bd->registro();				
			$this->id=$arreglo['id'];
			$this->asignar_datos($arreglo);			                              		
		}
		function asignar_datos($arreglo)
		{
			
			$this->idprofesional = $arreglo['idprofesional'];
			$this->idepisodio = $arreglo['idepisodio'];
			$this->fecha = $arreglo['fecha'];
			$this->hora = $arreglo['hora'];
			$this->idprofesional = $arreglo['idprofesional'];
			$this->edad_gestacional_corregida=$arreglo['edad_gestacional_corregida'];
			$this->frecuencia_respiratoria=$arreglo['frecuencia_respiratoria'];
			$this->frecuencia_cardiaca=$arreglo['frecuencia_cardiaca'];
			$this->temperatura=$arreglo['temperatura'];
			$this->tension_arterial_minima=$arreglo['tension_arterial_minima'];
			$this->tension_arterial_maxima=$arreglo['tension_arterial_maxima'];
			$this->tension_arterial_media=$arreglo['tension_arterial_media'];
			$this->saturacion=$arreglo['saturacion'];
			$this->aire_ambiente=$arreglo['aire_ambiente'];
			$this->fio2=$arreglo['fio2'];
			$this->dextro=$arreglo['dextro'];
			$this->arm=$arreglo['arm'];
			$this->cpap=$arreglo['cpap'];
			$this->canula_nasal=$arreglo['canula_nasal'];
			$this->paciente_en=$arreglo['paciente_en'];
			$this->peso_actual=$arreglo['peso_actual'];
			$this->peso_anterior_estado=$arreglo['peso_anterior_estado'];
			$this->peso_anterior_valor=$arreglo['peso_anterior_valor'];
			$this->hidroelectrolitico_ingresos=$arreglo['hidroelectrolitico_ingresos'];
			$this->hidroelectrolitico_egresos=$arreglo['hidroelectrolitico_egresos'];
			$this->hidroelectrolitico_e_i=$arreglo['hidroelectrolitico_e_i'];
			$this->hidroelectrolitico_pia=$arreglo['hidroelectrolitico_pia'];
			$this->hidroelectrolitico_nb=$arreglo['hidroelectrolitico_nb'];
			$this->hidroelectrolitico_ritmo_diuretico=$arreglo['hidroelectrolitico_ritmo_diuretico'];
			$this->aportes_php=$arreglo['aportes_php'];
			$this->aportes_via_oral=$arreglo['aportes_via_oral'];
			$this->aportes_via_oral_tipo=$arreglo['aportes_via_oral_tipo'];
			$this->aportes_leche=$arreglo['aportes_leche'];
			$this->aportes_leche_otras=$arreglo['aportes_leche_otras'];
			$this->aportes_npt=$arreglo['aportes_npt'];
			$this->vias=$arreglo['vias'];
			$this->via_central_tipo=$arreglo['via_central_tipo'];
			$this->via_central_umbilical=$arreglo['via_central_umbilical'];
			$this->sitio_colocacion=$arreglo['sitio_colocacion'];
			$this->dias_colocacion=$arreglo['dias_colocacion'];
			$this->sistema_respiratorio_tipo=$arreglo['sistema_respiratorio_tipo'];
			$this->arm_convencional_pim=$arreglo['arm_convencional_pim'];
			$this->arm_convencional_peep=$arreglo['arm_convencional_peep'];
			$this->arm_convencional_ti=$arreglo['arm_convencional_ti'];
			$this->arm_convencional_fr=$arreglo['arm_convencional_fr'];
			$this->arm_convencional_fio2=$arreglo['arm_convencional_fio2'];
			$this->arm_convencional_map=$arreglo['arm_convencional_map'];
			$this->arm_convencional_io=$arreglo['arm_convencional_io'];
			$this->arm_alta_frecuencia_map=$arreglo['arm_alta_frecuencia_map'];
			$this->arm_alta_frecuencia_fio2=$arreglo['arm_alta_frecuencia_fio2'];
			$this->arm_alta_frecuencia_amplitud=$arreglo['arm_alta_frecuencia_amplitud'];
			$this->arm_alta_frecuencia_hz=$arreglo['arm_alta_frecuencia_hz'];
			$this->cpap_peep=$arreglo['cpap_peep'];
			$this->cpap_fio2=$arreglo['cpap_fio2'];
			$this->surfactante=$arreglo['surfactante'];
			$this->surfactante_dosis=$arreglo['surfactante_dosis'];
			$this->sistema_respiratorio=$arreglo['sistema_respiratorio'];
			$this->sistema_cardiovascular_drogas_inotropicas=$arreglo['sistema_cardiovascular_drogas_inotropicas'];
			$this->sistema_cardiovascular_otras_drogas=$arreglo['sistema_cardiovascular_otras_drogas'];
			$this->sistema_cardiovascular=$arreglo['sistema_cardiovascular'];
			$this->aparato_digestivo_catarsis=$arreglo['aparato_digestivo_catarsis'];
			$this->aparato_digestivo=$arreglo['aparato_digestivo'];
			$this->sistema_nervioso=$arreglo['sistema_nervioso'];
			$this->aspecto_infectologico_antibioticos=$arreglo['aspecto_infectologico_antibioticos'];
			$this->aspecto_infectologico_cultivos=$arreglo['aspecto_infectologico_cultivos'];
			$this->observaciones=$arreglo['observaciones'];
			$this->comentario_permanencia=$arreglo['comentario_permanencia'];
			$this->aspecto_general=$arreglo['aspecto_general'];
			$this->hidroelectrolitico_egresos_gastricos=$arreglo['hidroelectrolitico_egresos_gastricos'];
			$this->hidroelectrolitico_egresos_ostomias=$arreglo['hidroelectrolitico_egresos_ostomias'];
			$this->edad_cronologica=$arreglo['edad_cronologica'];
			$this->uti=$arreglo['uti'];
		}
		function armar_xml()
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$base = new baseDatos();
			$base->Conectarse();
			$arreglo_epi = new clase_episodio($this->idepisodio);
			$arreglo_paciente = new clase_pacientes($arreglo_epi->idpaciente());
			
			$html="


<table><tr><td><strong><font size='+2'>EVOLUCION DE NEONATOLOGIA</font></strong></td></tr></table>

<table>
<tr><td><strong><font size='+2'>Fecha Evolución</font></strong></td><td><font size='+2'>".devolverFechaNormal($this->fecha)."</font></td>
    <td><strong><font size='+2'>Dias de Internación</font></strong></td><td><font size='+2'>".restarFecha($arreglo_epi->fecha_ingreso,fechaBase($this->fecha))."</font></td>
    <td><strong><font size='+2'>Días de Vida</font></strong></td><td><font size='+2'>".EdadIngreso(devolverFechaNormal($arreglo_paciente->fecha_nacimiento()),devolverFechaNormal($this->fecha))."</font></td></tr></table>

<table></table>
<table><tr><td><strong><font size='+3'>Evolución</font></strong></td></tr></table>
			
			<table><tr>
			<td>
				<strong><font size='+2'>Fecha</font></strong>
			</td>			
			<td><font size='+2'>".devolverFechaNormal($this->fecha)."</font>
			</td>			
			<td>
				<strong><font size='+2'>Hora</font></strong>
			</td>			
			<td><font size='+2'>".horaRecortada($this->hora)."</font>
			</td>						
			</tr></table>
			
			<table>"; if ($this->edad_cronologica != 0 && $this->edad_cronologica != "") { $html .="<tr><td><strong><font size='+2'>Edad Cronologica</font></strong></td><td><font size='+2'>".$this->edad_cronologica."</font></td></tr>"; } 
			
			$html .= "<tr><td><strong><font size='+2'>Edad Gestacional corregida</font></strong></td><td><font size='+2'>".$this->edad_gestacional_corregida."</font></td></tr></table>
			
			<table><tr><td><strong><font size='+2'>Diagnostico</font></strong></td><td><strong><font size='+2'>Tipo</font></strong></td></tr>";
			          $bd->select("SELECT * FROM evolucion_neonatologia_diagnosticos WHERE id_neo=$this->id ORDER BY tipo");
				      while ($diag=$bd->registro())
					  {
					      $base->select("SELECT descripcion FROM motivos_internacion WHERE codigo=".$diag['codigo']);
						  $descripcion = $base->registro();
					      $html.= "<tr><td><font size='+2'>".$descripcion['descripcion']."</font></td><td><font size='+2'>".$diag['tipo']."</font></td></tr>";
					  }                
			$html.="</table>
			
			<table width='100%' border='2'><tr><td><strong><font size='+2'>Signos Vitales</font></strong></td></tr>
			       <tr><td><strong><font size='+2'>FR</font></strong></td><td><strong><font size='+2'>FC</font></strong></td><td><strong><font size='+2'>TEMP.</font></strong></td><td><strong><font size='+2'>T.A. MIN</font></strong></td><td><strong><font size='+2'>T.A. MAX</font></strong></td><td><strong><font size='+2'>T.A.M</font></strong></td><td><strong><font size='+2'>FIO2</font></strong></td><td><strong><font size='+2'>Saturación</font></strong></td><td><strong><font size='+2'>AIRE AMB.</font></strong></td><td><strong><font size='+2'>CANULA NASAL</font></strong></td><td><strong><font size='+2'>ARM</font></strong></td><td><strong><font size='+2'>CPAP</font></strong></td><td><strong><font size='+2'>DEXTRO (0g/dl)</font></strong></td></tr>
				   <tr><td><font size='+2'>".$this->frecuencia_respiratoria."</font></td>
				       <td><font size='+2'>".$this->frecuencia_cardiaca."</font></td>
					   <td><font size='+2'>".$this->temperatura."</font></td>
					   <td><font size='+2'>".$this->tension_arterial_minima."</font></td>
					   <td><font size='+2'>".$this->tension_arterial_maxima."</font></td>
					   <td><font size='+2'>".$this->tension_arterial_media."</font></td>
					   <td><font size='+2'>".$this->fio2."</font></td>
					   <td><font size='+2'>".$this->saturacion."</font></td>
					   <td><font size='+2'>".$this->aire_ambiente."</font></td>
					   
					   <td><font size='+2'>
					       ";if ($this->canula_nasal == 1) $html.=" X";
						$html.="</font></td>
					   <td><font size='+2'>
					       "; if ($arreglo['arm'] == 1) $html.= " X";
						$html.="</font></td>
					   <td><font size='+2'>
					       "; if ($arreglo['cpap'] == 1) $html.= " X";
						$html.="</font></td>
					   <td><font size='+2'>".$this->dextro."</font></td>
					   </tr>
				   </table>
					   
			
			<table><tr><td><strong><font size='+2'>Peso Actual</font></strong></td><td><font size='+2'>".$this->peso_actual."</font></td>
			                        <td><font size='+2'>".$this->peso_anterior_estado."</font></td>
									<td><font size='+2'>".$this->peso_anterior_valor."</font></td></tr></table>
			
			<table><tr><td><strong><font size='+2'>Paciente en</font></strong></td><td><font size='+2'>".$this->paciente_en."</font></td></tr></table>
			
			<table width='100%'><tr><td><strong><font size='+2'>Aspecto General</font></strong></td></tr>
			       <tr><td><font size='+2'>".$this->aspecto_general."</font></td></tr> 
			</table>
			
			<table><tr><td colspan='3'><strong><font size='+2'>Aspecto Hidroelectrolitico y Nutricional</font></strong></td></tr></table>
			
			<legend><strong><font size='+2'>Balance Diario</font></strong></legend>
			<table>
			      <tr><td><strong><font size='+2'>Ingresos</font></strong></td>
				   <td><font size='+2'>".$this->hidroelectrolitico_ingresos."</font></td><td><strong><font size='+2'>ml/kg</font></strong></td></tr>
				   <tr><td><strong><font size='+2'>Egresos (Diuresis)</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_egresos."</font></td>
				       <td><strong><font size='+2'>ml/kg</font></strong></td></tr>
				   <tr><td><strong><font size='+2'>Egresos (Gastricos)</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_egresos_gastricos."</font></td>
				       <td><strong><font size='+2'>ml/kg</font></strong></td></tr>
				   <tr><td><strong><font size='+2'>Egresos (Ostomias)</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_egresos_ostomias."</font></td>
				       <td><strong><font size='+2'>ml/kg</font></strong></td></tr>
				   <tr><td><strong><font size='+2'>Ritmo Diurético</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_ritmo_diuretico."</font></td>
				       <td><strong><font size='+2'>ml/kg/hora</font></strong></td></tr>
				   <tr><td><strong><font size='+2'>E/I</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_e_i."</font></td><td></td></tr>
				   <tr><td><strong><font size='+2'>PIA</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_pia."</font></td><td></td></tr>
				   <tr><td><strong><font size='+2'>NB</font></strong></td><td><font size='+2'>".$this->hidroelectrolitico_nb."</font></td>
				       <td><strong><font size='+2'>ml/kg/hora</font></strong></td></tr>				   
		    </table>
			<legend><strong><font size='+2'>Aportes</font></strong></legend>
			<table>
			  <tr>
			    <td><strong><font size='+2'>PHP</font></strong></td>
			    <td><font size='+2'>".$this->aportes_php."</font></td>
				<td></td><td></td>
			  </tr>
			  <tr>
			    <td><strong><font size='+2'>Via Oral</font></strong></td>
			    <td><font size='+2'>".$this->aportes_via_oral."</font></td>
			    <td><strong><font size='+2'>ml/k dia</font></strong></td>
			    <td><font size='+2'>".$this->aportes_via_oral_tipo."</font></td>
			  </tr>
			  <tr>
			    <td><strong><font size='+2'>Leche</font></strong></td>
			    <td><font size='+2'>".$this->aportes_leche."</font></td>
			    <td><strong><font size='+2'>OTRAS</font></strong></td>
			    <td><font size='+2'>".$this->aportes_leche_otras."</font></td>
			  </tr>
			  <tr>
			    <td><strong><font size='+2'>NPT</font></strong></td>
			    <td><font size='+2'>".$this->aportes_npt."</font></td>
				<td></td><td></td>
			  </tr>
			</table>	
                        <table><tr><td><strong><font size='+2'>Vías</font></strong></td><td><font size='+2'>".$this->vias."</font></td>
			           <td><strong><font size='+2'>Sitio de colocación</font></strong></td>
																<td><font size='+2'>".$this->sitio_colocacion."</font></td>
																<td><strong><font size='+2'>Días de colocación</font></strong></td>
																<td><font size='+2'>".$this->dias_colocacion."</font></td></tr></table>";
            if ($this->vias=='CENTRALES') {
                $html.= "<table ><tr><td><strong><font size='+2'>Tipo Via Central</font></strong></td>
			                                        <td><font size='+2'>".$this->via_central_tipo."</font></td></tr></table>"; 
             } 
			 if ($this->via_central_tipo == 'UMBILICAL') { 
                 $html.= "<table><tr><td><strong><font size='+2'>Tipo Umbilical</font></strong></td><td><font size='+2'>".$this->via_central_umbilical."</font></td></tr></table>";
			} 																	 																
			
		$html.="
			<table width='100%'><tr><td width='27%'><strong><font size='+2'>Sistema Respiratorio</font></strong></td>
			                        <td width='73%'><font size='+2'>".$this->sistema_respiratorio_tipo."</font></td></tr>
                 <tr><td colspan='2'><font size='+2'>".$this->sistema_respiratorio."</font></td></tr></table>";
           				 
			if ($this->sistema_respiratorio_tipo == 'ARM - CONVENCIONAL') {
                $html.="<table width='100%' border='2'><tr><td width='24%'><strong><font size='+2'>ARM Convencional</font></strong></td>
			           <td width='4%'><strong><font size='+2'>PIM</font></strong></td><td width='4%'>
					    <font size='+2'>".$this->arm_convencional_pim."</font></td>
					   <td width='4%'><strong><font size='+2'>PEEP</font></strong></td><td width='4%'><font size='+2'>".$this->arm_convencional_peep."</font></td>
					   <td width='8%'><strong><font size='+2'>TI</font></strong></td><td width='4%'><font size='+2'>".$this->arm_convencional_ti."</font></td>
					   <td width='3%'><strong><font size='+2'>FR</font></strong></td><td width='4%'><font size='+2'>".$this->arm_convencional_fr."</font></td>
					   <td width='4%'><strong><font size='+2'>FIO2</font></strong></td><td width='4%'><font size='+2'>".$this->arm_convencional_fio2."</font></td>
					   <td width='4%'><strong><font size='+2'>MAP</font></strong></td><td width='4%'><font size='+2'>".$this->arm_convencional_map."</font></td>
					   <td width='3%'><strong><font size='+2'>IO</font></strong></td><td width='22%'><font size='+2'>".$this->arm_convencional_io."</font></td>					   
					   </tr></table>"; 
            } 	
			if ($this->sistema_respiratorio_tipo == 'ARM - ALTA FRECUENCIA') {
                $html.= "<table width='100%' border='2'><tr><td width='24%'><strong><font size='+2'>ARM Alta Frecuencia</font></strong></td>
			<td width='4%'><strong><font size='+2'>MAP</font></strong></td><td width='4%'><font size='+2'>".$this->arm_alta_frecuencia_map."</font></td>
			<td width='4%'><strong><font size='+2'>FIO2</font></strong></td><td width'4%'><font size='+2'>".$this->arm_alta_frecuencia_fio2."</font></td>
			<td width='8%'><strong><font size='+2'>AMPLITUD</font></strong></td><td width='4%'><font size='+2'>".$this->arm_alta_frecuencia_amplitud."</font></td>
			<td width='3%'><strong><font size='+2'>HZ</font></strong></td><td width='45%'><font size='+2'>".$this->arm_alta_frecuencia_hz."</font></td>
			</tr></table>"; 
			} 
			if ($arreglo['sistema_respiratorio_tipo'] == 'CPAP') { 
                $html.="<table width='529'  border='2'>
  <tr><td width='289'><strong><font size='+2'>CPAP</font></strong></td>
			<td width='46'><strong><font size='+2'>PEEP</font></strong></td><td width='44'><font size='+2'>".$this->cpap_peep."</font></td>
					   <td width='46'><strong><font size='+2'>FIO2</font></strong></td><td width='80'><font size='+2'>".$this->cpap_fio2."</font></td>	
			           </tr></table>";
            } 
	   					   
			$html.="<table><tr><td><strong><font size='+2'>Surfactante</font></strong></td>
			           <td><font size='+2'>";if ($this->surfactante == 1) $html.="SI";
			$html.= "</font></td>
					   <td><font size='+2'>";if ($this->surfactante == 0 || $this->surfactante == '') $html.="NO";
					   $html.="</font></td>
					   <td><strong><font size='+2'>Dosis</font></strong></td><td><font size='+2'>".$this->surfactante_dosis."</font></td></tr></table>
     					   
			
			<table width='100%'><tr><td width='35%'><strong><font size='+2'>Sistema Cardiovascular:</font></strong></td>
			                        <td width='21%'><strong><font size='+2'>Drogas Inotropicas</font></strong></td>			           
			           <td width='2%'><font size='+2'>";if ($this->sistema_cardiovascular_drogas_inotropicas == 1) $html.="SI";$html.="</font></td>
					   <td width='3%'><font size='+2'>";if ($this->sistema_cardiovascular_drogas_inotropicas == 0 || $this->sistema_cardiovascular_drogas_inotropicas == '') $html.="NO";
$html.="</font></td> 
					   <td width='18%'><strong><font size='+2'>Otras Drogas</font></strong></td>
					   <td width='2%'><font size='+2'>";if ($this->sistema_cardiovascular_otras_drogas == 1) $html.="SI";
$html.="</font></td>
					   <td width='19%'><font size='+2'>";if ($this->sistema_cardiovascular_otras_drogas == 0 || $this->sistema_cardiovascular_otras_drogas == '') $html.="NO";
$html.="</font></td> 
			</tr>
			<tr><td colspan='11'><font size='+2'>".$this->sistema_cardiovascular."</font></td></tr></table>
			
			<table width='100%'><tr><td width='19%'><strong><font size='+2'>Aparato Digestivo</font></strong></td>
			       <td width='9%'><strong><font size='+2'>Catarsis</font></strong></td>
			           <td width='2%'><font size='+2'>"; if ($this->aparato_digestivo_catarsis == 1) $html.="SI";
$html.="</font></td>
					   <td width='70%'><font size='+2'>";if ($this->aparato_digestivo_catarsis == 0 || $this->aparato_digestivo_catarsis == '') $html.="NO";
$html.="</font></td>
			       </tr>
				   <tr><td colspan='6'><font size='+2'>".$this->aparato_digestivo."</font></td></tr></table>
			
			<table width='100%'><tr><td><strong><font size='+2'>Sistema Nervioso</font></strong></td></tr>
			       <tr><td><font size='+2'>".$this->sistema_nervioso."</font></td></tr>  
			</table>
			
			<table><tr><td><strong><font size='+2'>Aspecto Infectológico</font></strong></td>
			           <td><strong><font size='+2'>Antibióticos</font></strong></td>
					   <td><font size='+2'>";if ($this->aspecto_infectologico_antibioticos == 1) $html.="SI";
$html.="</font></td>
					   <td><font size='+2'>";if ($this->aspecto_infectologico_antibioticos == 0 || $this->aspecto_infectologico_antibioticos == '') $html.="NO";
$html.="</font></td>
					   <td><strong><font size='+2'>Cultivos</font></strong></td><td><font size='+2'>".$this->aspecto_infectologico_cultivos."</font></td></tr></table>							   
			
			<table><tr><td><strong><font size='+2'>ATB</font></strong></td><td><strong><font size='+2'>Dosis</font></strong></td></tr>";
			          $bd->select("SELECT * FROM evolucion_neonatologia_antibioticos WHERE id_neo=$this->id");
				      while ($atb=$bd->registro())
					  {
					      $html.=" <tr><td><font size='+2'>".$atb['antibiotico']."</font></td><td><font size='+2'>".$atb['dosis']." Día</font></td></tr>";
					  } 
	$html.="</table>
					   
			
			<table width='100%'><tr><td><strong><font size='+2'>Otras Observaciones/ Estudios Pendientes</font></strong></td></tr>
			       <tr><td><font size='+2'>".$this->observaciones."</font></td></tr></table>
			
			<table width='100%'><tr><td><strong><font size='+2'>Comentario de Permanencia (Resumen semiológico - plan terapéutico - Interpretación diagnostica)</font></strong></td></tr>
			       <tr><td><font size='+2'>".$this->comentario_permanencia."</font></td></tr></table>
			
			";
	 		return $html;		
		}					
	    
		function diasArm($idepisodio,$fdesde,$fhasta,$fecha_egreso)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			if ($fecha_egreso != '' && $fecha_egreso != '0000-00-00')
	    	{
	    	    if (compara_fechas($fecha_egreso,fechaBase($fhasta)) == 0)	    	    
	    	    	$ffin = $fhasta;	    	         	 	 
	    	    else 
	    	    {
	    	    	if (compara_fechas($fecha_egreso,fechaBase($fhasta)) > 0)
		    	     	$ffin = $fhasta;
		    	    else 
		    	     	$ffin = $fecha_egreso;
	    	    }
	    	}
	    	else 
	    	    $ffin = $fhasta;
			if ($fdesde != '' && $fhasta != '')
			    $where = " AND (fecha>='".fechaBase($fdesde)."' AND fecha<='".fechaBase($ffin)."')";
			$bd->select("SELECT count(evolucion_neonatologia.idepisodio) as cantidad
	              FROM evolucion_neonatologia  WHERE 
	              (sistema_respiratorio_tipo ='ARM - CONVENCIONAL' OR sistema_respiratorio_tipo ='ARM - ALTA FRECUENCIA') 
	              AND idepisodio=$idepisodio $where
	 		      GROUP BY evolucion_neonatologia.idepisodio");
	 	    $arreglo = $bd->registro();
	 	    if ($arreglo['cantidad'] != '' && $arreglo['cantidad'] != 0)
	 	        return $arreglo['cantidad'];
	 	    else
	 	        return 0;
		}
	}
?>