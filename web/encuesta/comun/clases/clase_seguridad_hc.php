<?php
    class clase_seguridad_hc
    {    	
	
        function registrar_ingreso($idprofesional,$idepisodio,$formulario)
        {
        	$bd = new baseDatos();
    		$bd->Conectarse();
    		if ($bd->select("INSERT INTO chequeo_ingreso_hc_internacion(idprofesional,idepisodio,formulario) 
    		                 VALUES ($idprofesional,$idepisodio,'$formulario')"))
    		    return 1;
    		else 
    		    return 0;
        }
    	function registrar_ingreso_ambulatorio($idprofesional,$idpaciente,$formulario)
        {
        	$bd = new baseDatos();
    		$bd->Conectarse();
    		if ($bd->select("INSERT INTO chequeo_ingreso_hc_ambulatorio(idprofesional,idpaciente,formulario) 
    		                 VALUES ($idprofesional,$idpaciente,'$formulario')"))
    		    return 1;
    		else 
    		    return 0;
        }
        function justificar_entrada($idprofesional,$idepisodio,$idpaciente,$justificar_entrada)
        {
        	$bd = new baseDatos();
    		$bd->Conectarse();
    		if ($bd->select("INSERT INTO justificar_entrada_hc(idprofesional,idepisodio,idpaciente,entrada) 
    		                 VALUES ($idprofesional,'$idepisodio',$idpaciente,'$justificar_entrada')"))
    		    return 1;
    		else 
    		    return 0;
        }
    }
?>