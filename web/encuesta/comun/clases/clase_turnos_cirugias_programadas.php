<?php
	class clase_turnos_cirugias_programadas
	{
		var $id=0;
		var $fecha   ='';
		var $hora ='';
		var $paciente = '';
		var $cirujano = '';
		var $cirugia = '';
		var $obra_social = '';
		var $destino='';
		var $idcirugia = '';		
		var $idosocial ='';
		var $idprofesional = '';
		var $telefono_paciente = '';
		var $dias_estadia='';
		var $descriptionid = '';
		var $texto_tesauro = '';
		var $subsetid ='';
		var $descriptionidcirugias = '';
		var $subsetidcirugia = '';
		var $estado = '';
		var $observaciones = '';
		var $idpaciente = '';
		
		
		function clase_turnos_cirugias_programadas()
		{		
			
		}
		function buscarPaciente($idpaciente)
		{
			$bd = new baseDatos();
			$bd->Conectarse();
			$bd->select("SELECT * FROM turnos_cirugias_programadas WHERE idpaciente=$idpaciente ORDER BY fecha DESC");
			$arreglo = $bd->registro();
			$this->id=$arreglo['id'];
		 	$this->fecha   =$arreglo['fecha'];
		 	$this->hora =$arreglo['hora'];
			$this->paciente = $arreglo['paciente'];
			$this->cirujano = $arreglo['cirujano'];
			$this->cirugia = $arreglo['cirugia'];
			$this->obra_social = $arreglo['obra_social'];
			$this->destino=$arreglo['destino'];
			$this->idcirugia = $arreglo['idcirugia'];		
			$this->idosocial =$arreglo['idosocial'];
			$this->idprofesional = $arreglo['idprofesional'];
			$this->telefono_paciente = $arreglo['telefono_paciente'];
			$this->dias_estadia=$arreglo['dias_estadia'];
			$this->descriptionid = $arreglo['descriptionid'];
			$this->texto_tesauro = $arreglo['texto_tesauro'];
			$this->subsetid =$arreglo['subsetid'];
			$this->descriptionidcirugias = $arreglo['descriptionidcirugias'];
			$this->subsetidcirugia = $arreglo['subsetidcirugia'];
			$this->estado = $arreglo['estado'];
			$this->idpaciente = $arreglo['idpaciente'];
			$this->observaciones = $arreglo['observaciones'];
		}
		function fecha()
		{
			return $this->fecha;
		}
		function hora()
		{
			return $this->hora;
		}
		function observaciones()
		{
			return $this->observaciones;	
		}		
	}
?>