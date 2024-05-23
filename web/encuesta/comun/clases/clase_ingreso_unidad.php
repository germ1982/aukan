<?
	class clase_ingreso_unidad extends clase_evolucion
	{
		var $id=0;
		var $nombre   ='';		
		var $frecuencia_cardiaca = '';
		var $tension_arterial = '';	
		var $enfermedad_actual = '';
		var $temperatura = '';
		var $frecuencia_respiratoria ='';
		var $saturacion = '';
		var $terapeutica = '';
		var $impresion_diagnostica = '';
		var $examenes_complementarios = '';
		var $examen_fisico = '';
		var $diagnostico_ingreso = '';
		var $unidad = '';
		var $fio2 = '';
		var $aspecto_respiratorio = '';
		var $arm = '';
		var $aspecto_cardiovascular = '';
		var $aparato_digestivo = '';
		var $aspecto_genitourinario = '';
		var $aspecto_neurologico = '';
		var $fecha='';
		var $hora='';
		var $motivo_ingreso ='';
		
		function clase_ingreso_unidad($table,$idtable,$id)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
		    $consulta = "SELECT * FROM $table WHERE $idtable=$id";
			$bd->select($consulta);
			$arreglo = $bd->registro();	
			$this->asignar_datos($arreglo);			                              		
		}	
		function asignar_datos($arreglo)
		{
			$this->frecuencia_cardiaca = $arreglo['frecuencia_cardiaca'];
			$this->tension_arterial = $arreglo['tension_arterial'];
			$this->enfermedad_actual = $arreglo['enfermedad_actual'];
			$this->temperatura = $arreglo['temperatura'];
			$this->frecuencia_respiratoria = $arreglo['frecuencia_respiratoria'];
			$this->saturacion = $arreglo['saturacion'];
			$this->terapeutica = $arreglo['terapeutica'];
			$this->impresion_diagnostica = $arreglo['impresion_diagnostica'];
			$this->examenes_complementarios = $arreglo['examenes_complementarios'];
			$this->examen_fisico = $arreglo['examen_fisico'];
			$this->diagnostico_ingreso = $arreglo['diagnostico_ingreso'];
			$this->unidad = $arreglo['unidad'];
			$this->fio2 = $arreglo['fio2'];
			$this->aspecto_respiratorio = $arreglo['aspecto_respiratorio'];
			$this->arm = $arreglo['arm'];
			$this->aspecto_cardiovascular = $arreglo['aspecto_cardiovascular'];
			$this->aparato_digestivo = $arreglo['aparato_digestivo'];
			$this->aspecto_genitourinario = $arreglo['aspecto_genitourinario'];
			$this->aspecto_neurologico = $arreglo['aspecto_neurologico'];
			$this->idprofesional = $arreglo['idprofesional'];
			$this->fecha = $arreglo['fecha'];
			$this->hora = $arreglo['hora'];
		}	
		function examen_fisico()
		{
			return $this->examen_fisico;
		}
		function enfermedad_actual()
		{
			return $this->enfermedad_actual;
		}
		function impresion_diagnostica()
		{
			return $this->impresion_diagnostica;
		}
		function unidad()
		{
			return $this->unidad;
		} 	
		function motivo_ingreso()
		{
			return $this->motivo_ingreso;
		}
		function motivo_ingeso_asigna($campo)
		{
			$this->motivo_ingreso=$campo;
		}
		function armar_xml()
		{
			$contenido = '';
			if ($this->fecha != '' && $this->fecha != '0000-00-00')
			{
				$contenido .= "<paragraph><table><tr><td>Fecha</td><td>".devolverFechaNormal($this->fecha)."</td>
				                          <td>Hora</td><td>".horaRecortada($this->hora)."</td></tr></table></paragraph>";
			}
			$contenido.= 
			        "<paragraph><caption>Unidad de Ingreso</caption></paragraph>
			        <paragraph><content>".$this->unidad."</content></paragraph>
			        <paragraph><caption>Motivo de internaci&#243;n</caption></paragraph>
			        <paragraph><content>".$this->motivo_ingreso()."</content></paragraph>";
			             
			        if ($this->diagnostico_ingreso != '')
			        {
			            $contenido.="<paragraph><caption>Diagnostico de Ingreso</caption></paragraph>
			                         <paragraph><content>".$this->diagnostico_ingreso."</content></paragraph>";
			        }
			        if ($this->frecuencia_cardiaca != '' || $this->tension_arterial != '' || $this->frecuencia_respiratoria != '' || $this->temperatura != '' || $this->saturacion != '' || $this->fio2 != '')
			        {
			            $contenido.="<paragraph><caption>Signos Vitales</caption></paragraph>
			        				 <paragraph><table><tr><td><strong>FC</strong></td><td>".$this->frecuencia_cardiaca."</td>
			     		   									<td><strong>TA</strong></td><td>".$this->tension_arterial."</td>	   
			     		   									<td><strong>FR</strong></td><td>".$this->frecuencia_respiratoria."</td>
			     		   									<td><strong>TEMP</strong></td><td>".$this->temperatura."</td>
			     	       									<td><strong>Sat.</strong></td><td>".$this->saturacion."</td>
			     		   									<td><strong>FIO2</strong></td><td>".$this->fio2."</td></tr></table>
							        </paragraph>";
			        }
			        if ($this->enfermedad_actual != '')
			        {
			            $contenido.="<paragraph><caption>Enfermedad Actual</caption></paragraph>
			        				 <paragraph><content>".$this->enfermedad_actual."</content></paragraph>";
			        }
			        if ($this->examen_fisico != '')
			        {
			            $contenido .= "<paragraph><caption>Exámen Físico</caption></paragraph>
			        					<paragraph><content>".$this->examen_fisico."</content></paragraph>";
			        }
			        if ($this->examenes_complementarios != '')
			        {
			            $contenido .= "<paragraph><caption>Examenes Complementarios</caption></paragraph>
			        					<paragraph><content>".$this->examenes_complementarios."</content></paragraph>";
			        }
			        if ($this->impresion_diagnostica != '')
			        {
			            $contenido.= "<paragraph><caption>Impresión Diagnóstica</caption></paragraph>
			        				  <paragraph><content>".$this->impresion_diagnostica."</content></paragraph>";
			        }
			        if ($this->terapeutica != '')
			        {
			            $contenido .= "<paragraph><caption>Terapéutica</caption></paragraph>
			        				   <paragraph><content>".$this->terapeutica."</content></paragraph>";
			        }
			        if ($this->aspecto_respiratorio !='')
			        {
			            $contenido .= "<paragraph><caption>Aspecto Respiratorio</caption></paragraph>
			        				   <paragraph><content>".$this->aspecto_respiratorio."</content></paragraph>";
			        }
			        if ($this->arm != '')
			        {
			            $contenido .= "<paragraph><caption>ARM</caption></paragraph>
			        				   <paragraph><content>".$this->arm."</content></paragraph>";
			        }
			        if ($this->aspecto_cardiovascular != '')
			        {
			        	$contenido .= "<paragraph><caption>Aspecto Cardiovascular</caption></paragraph>
			        				   <paragraph><content>".$this->aspecto_cardiovascular."</content></paragraph>";
			        }
			        if ($this->aparato_digestivo != '')
			        {
			            $contenido .= "<paragraph><caption>Aparato Digestivo</caption></paragraph>
			        					<paragraph><content>".$this->aparato_digestivo."</content></paragraph>";
			        }
			        if ($this->aspecto_genitourinario != '')
			        {
			        	$contenido .= "<paragraph><caption>Aspecto Genitourinario</caption></paragraph>
			        					<paragraph><content>".$this->aspecto_genitourinario."</content></paragraph>";
			        }
			        if ($this->aspecto_neurologico != '')
			        {
			        	$contenido .= "<paragraph><caption>Aspecto Neurológico</caption></paragraph>
			        				  <paragraph><content>".$this->aspecto_neurologico."</content></paragraph>";
			        }
			        if ($this->aspecto_neurologico != '')
			        {
			        	$contenido .="<paragraph><caption>Aspecto Neurológico</caption></paragraph>
			        					<paragraph><content>".$this->aspecto_neurologico."</content></paragraph>";
			        }
			 return $contenido;
		}		
	}
?>