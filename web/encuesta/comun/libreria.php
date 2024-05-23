<?php
  
  //Retorna el nombre del d�a para un n�mero de d�a de semana
  function dia($numDia){
     $semana[1] = "Lunes";
	 $semana[2] = "Martes";
	 $semana[3] = "Mi�rcoles";
	 $semana[4] = "Jueves";
	 $semana[5] = "Viernes";
	 $semana[6] = "S�bado";
	 $semana[7] = "Domingo";
	 if (($numDia >= 1) and ($numDia <= 7)) {$salida = $semana[$numDia];}
	 else {$salida = "";}  
	 return $salida; 
  }
  
  //Retorna el nombre del mes para un n�mero de mes
  function mes($numMes){
     $mes[1] = "Enero";
	 $mes[2] = "Febrero";
	 $mes[3] = "Marzo";
	 $mes[4] = "Abril";
	 $mes[5] = "Mayo";
	 $mes[6] = "Junio";
	 $mes[7] = "Julio";
	 $mes[8] = "Agosto";
	 $mes[9] = "Septiembre";
	 $mes[10] = "Octubre";
	 $mes[11] = "Noviembre";
	 $mes[12] = "Diciembre";
	 if ($numMes < 10){$numMes=str_replace("0","",$numMes);}
	 if (($numMes >= 1) and ($numMes <= 12)) {$salida = $mes[$numMes];}
	 else {$salida = "";}  
	 return $salida; 
  }
  
  //Retorna el numero del mes
  function numeroMes($nombreMes){
  	switch($nombreMes){
		case "Ene"		 : 
    	case "Enero" 	 : $numero = "01";
						   break;
		case "Feb"		 :				   
	 	case "Febrero"	 : $numero = "02";
						   break;
		case "Mar"		 :				   
	 	case "Marzo"	 : $numero = "03";
						   break;
		case "Abr"		 :				   	
	 	case "Abril"	 : $numero = "04";
						   break;
		case "May"		 :				   
	 	case "Mayo"		 : $numero = "05";
						   break;
		case "Jun"		 :				   
	 	case "Junio"	 : $numero = "06";
						   break;
		case "Jul"		 :				   	
	 	case "Julio"	 : $numero = "07";
						   break;
		case "Ago"		 :				   
	 	case "Agosto"	 : $numero = "08";
						   break;	
		case "Sep"		 :				   
	 	case "Septiembre": $numero = "09";
						   break;
		case "Oct"		 :				   
	 	case "Octubre"	 : $numero = "10";
						   break;	
		case "Nov"		 :				   
	 	case "Noviembre" : $numero = "11";
						   break;
		case "Dic"		 :				   
	 	case "Diciembre" : $numero = "12";
						   break;	
	}
	 return $numero; 
  }
  
  //retorna la fecha actual. Ej: Lunes, 23 de Febrero de 2004
  function fecha_hoy(){
     $timestamp = time();
     $timearray = getdate($timestamp);  
     $fecha = sprintf("%04d-%02d-%02d", $timearray["year"], $timearray["mon"], $timearray["mday"]);  
     $diaSemana = $timearray["wday"];
	 list( $aa, $mm, $dd ) = split( '[/.-]', $fecha );    
	 $hoy = dia($diaSemana) . ", " . $dd . " de " . mes($mm) . " de " . $aa;
	 return $hoy;
  }
  

  //verifica si es una fecha v�lida
  function esFecha($fecha){
  	  list( $dd, $mm, $aa ) = split( '[/.-]', $fecha);
	  return checkdate(intval($mm),intval($dd),intval($aa));
  }
  
  //Retorna la fecha actual con formato normal
  function fecha_actual(){
     $timestamp = time();
     $timearray = getdate($timestamp);  
     $fecha = sprintf("%04d-%02d-%02d", $timearray["year"], $timearray["mon"], $timearray["mday"]);  
  
     list( $aa, $mm, $dd ) = split( '[/.-]', $fecha ); 
     $fecha_actual = $dd . "/" . $mm . "/" . $aa;
	 
     return $fecha_actual;
  }  
  
  /*Retorna el siguinete valor libre de la tabla $tabla, para el campo $campo,
    el valor del campo debe ser num�rico,
	retorna -1, si hubo problemas, sino el siguiente valor*/
  function siguienteLibre($enlace,$tabla,$campo){
     $codigo=-1;
     //Se buscan todas las entradas de $tabla
	 $consulta = "SELECT * FROM " . $tabla . " ORDER BY " . $campo;   
	 $datosTabla = mysql_query($consulta,$enlace);
	 $cantidad=mysql_num_rows($datosTabla);
	 $i=0;
	 $noEncontrado=true;
	 //Se busca por cada entrada hasta encontrar el siguiente libre
	 while (($i < $cantidad) && ($noEncontrado)){
	   $siguiente = mysql_fetch_array($datosTabla, MYSQL_ASSOC);
	   if ($siguiente["$campo"]!=$i){
	      $codigo=$i;
		  $noEncontrado=false;
	   }
	   $i++;
	 }
	 if ($noEncontrado){
	    $codigo=$i;
	 }
   return $codigo;
  }
  
  function normalizarTexto($texto){
		return trim($texto);
  }
  
  /*Genrar un archivo txt para ser le�do por un archivo swf (flash)
  	El formato generado es nombre=valor&nombre1=valor1.....
  */

	//funciones realizadas por Nicolas
	//funcion que devuelve la fecha fdesde del mes solicitado
  //mesanio viene en formato "ALGO MES ANIO (EJ: 05)"
  function comienzoFecha($mesanio)
  {
	  $i = strrpos($mesanio,"0");
	  $j = $i++;
	  $soloanio = substr($mesanio,$i,$j);
      if (substr_count($mesanio,"ENERO") != 0 )	      
          return "01/01/".$soloanio;
	  if (substr_count($mesanio,"FEBRERO") != 0 )	       	  
	      return "01/02/".$soloanio;
	  if (substr_count($mesanio,"MARZO") != 0 )	       	  
	      return "01/03/".$soloanio;	  
      if (substr_count($mesanio,"ABRIL") != 0 )	       	  
	      return "01/04/".$soloanio; 		  
	  if (substr_count($mesanio,"MAYO") != 0 )	       	  
	      return "01/05/".$soloanio;	  
	  if (substr_count($mesanio,"JUNIO") != 0 )	       	  
	      return "01/06/".$soloanio;	  
	  if (substr_count($mesanio,"JULIO") != 0 )	       	  
	      return "01/07/".$soloanio;	  
	  if (substr_count($mesanio,"AGOSTO") != 0 )	       	  
	      return "01/08/".$soloanio;	  
	  if (substr_count($mesanio,"SEPTIEMBRE") != 0 )	       	  
	      return "01/09/".$soloanio;	  
	  if (substr_count($mesanio,"OCTUBRE") != 0 )	       	  
	      return "01/10/".$soloanio;	  
      if (substr_count($mesanio,"NOVIEMBRE") != 0 )	       	  
	      return "01/11/".$soloanio; 		  
	  if (substr_count($mesanio,"DICIEMBRE") != 0 )	       	  
	      return "01/12/".$soloanio;	  
  }
  //fin de funcion comienzoFecha
    function finFecha($mesanio)
  {
	  $i = strcspn($mesanio,"0");
	  $j = $i++;
	  $soloanio = substr($mesanio,$i,$j);
      if (substr_count($mesanio,"ENERO") != 0 )	      
          return "31/01/".$soloanio;
	  if (substr_count($mesanio,"FEBRERO") != 0 )	       	  
	      return "28/02/".$soloanio;
	  if (substr_count($mesanio,"MARZO") != 0 )	       	  
	      return "31/03/".$soloanio;	  
      if (substr_count($mesanio,"ABRIL") != 0 )	       	  
	      return "30/04/".$soloanio; 		  
	  if (substr_count($mesanio,"MAYO") != 0 )	       	  
	      return "31/05/".$soloanio;	  
	  if (substr_count($mesanio,"JUNIO") != 0 )	       	  
	      return "30/06/".$soloanio;	  
	  if (substr_count($mesanio,"JULIO") != 0 )	       	  
	      return "31/07/".$soloanio;	  
	  if (substr_count($mesanio,"AGOSTO") != 0 )	       	  
	      return "31/08/".$soloanio;	  
	  if (substr_count($mesanio,"SEPTIEMBRE") != 0 )	       	  
	      return "30/09/".$soloanio;	  
	  if (substr_count($mesanio,"OCTUBRE") != 0 )	       	  
	      return "31/10/".$soloanio;	  
      if (substr_count($mesanio,"NOVIEMBRE") != 0 )	       	  
	      return "30/11/".$soloanio; 		  
	  if (substr_count($mesanio,"DICIEMBRE") != 0 )	       	  
	      return "31/12/".$soloanio;	  
  }
  //fin de funcion finFecha	
  //funciones que devuelven el nombre del mes segun el numero pasado por parametro
  // y funcion que devuelve la cantidad de dias del mes segun parametro que es un numero
  function devolverNombreMes($fecha)
	{
	    list( $dd, $mm, $aa ) = split( '/', $fecha );
	    if ($mm == "01")
		    $nombre = "ENERO";
	    else
		    if ($mm == "02") 
			    $nombre = "FEBRERO";  		
		    else
			    if ($mm == "03")  		
				    $nombre = "MARZO";
			    else
				    if ($mm == "04") 		
					    $nombre = "ABRIL";
				    else
					    if ($mm == "05")		
						    $nombre = "MAYO";
					    else
						    if ($mm == "06") 		
							    $nombre = "JUNIO";
						    else
							    if ($mm == "07") 		
								    $nombre = "JULIO";
							    else
								    if ($mm == "08") 		
									    $nombre = "AGOSTO";
								    else
									    if ($mm == "09") 		
										    $nombre = "SEPTIEMBRE";
									    else
										    if ($mm == "10")		
											    $nombre = "OCTUBRE";
										    else
											    if ($mm == "11") 		
												    $nombre = "NOVIEMBRE";
											    else
												    $nombre = "DICIEMBRE";		
	    return $nombre;
	}
	function devolverCantidadDias($md)
	{
	    if ($md == 1)
				    $dia = "31";
			    else
				    if ($md == 2)
					    $dia = "28";
					else
					    if ($md == 3)	
						    $dia = "31";
					    else
						    if ($md == 4) 		
							    $dia = "30";
						    else
							    if ($md == 5)
								    $dia = "31";
								else
								    if ($md == 6)
									    $dia = "30";
									else
									    if ($md == 7)
										    $dia = "31";				
										else
										    if ($md == 8)	
											    $dia = "31";
											else
											    if ($md == 9)	
												    $dia = "30";
												else
												    if ($md == 10)	
													    $dia = "31";
													else
													    if ($md == 11)	
														    $dia = "30";
														else
														    $dia = "31";	
		return $dia;													
	}
	//desglosar de fecha
	//recibe fecha en formato ingles
	function desglosarFecha($fecha)
	{
		list( $aa, $mm, $dd ) = split( '-', $fecha );
		$a = array();
		$a[0] = $aa;
		$a[1] = $mm;
		$a[2] = $dd;
		return $a;
	}
	function datecheck($input,$format="")
        {
            $separator_type= array("/","-",".");
            foreach ($separator_type as $separator) 
	    {
                $find= stripos($input,$separator);
                if($find<>false)
		{
	            $separator_used= $separator;
            	}
            }
            $input_array= explode($separator_used,$input);
            if ($format=="mdy") 
	    {
                return checkdate($input_array[0],$input_array[1],$input_array[2]);
               } elseif ($format=="ymd") {
                return checkdate($input_array[1],$input_array[2],$input_array[0]);
               } else {
               return checkdate($input_array[1],$input_array[0],$input_array[2]);
            }
            $input_array=array();
        }
	function win_gmmktime ( $hour, $minute, $second, $month, $day, $year )
		{
		    if ( $year > 1969 )
		    {
        		return ( gmmktime ( $hour, $minute, $second, $month, $day, $year ) );
		    }

		    $t = 0;
		    $ds = 86400;
		    $hs = 3600;
		    $dy = 365;
		    $ms = 60;

		    $months = array ( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
		    $leap_year = $year % 4 == 0 && ( $year % 100 > 0 || $year % 400 == 0 ) ? true : false;

		    if ( $year < 1969 )
		    {
        		$y = 1969 - $year;
		        $t -= ( $y * $dy ) * $ds;
        		$x = ceil ( $y / 4 );
				
		        if ( $leap_year && $month > 2 )
        		{
		            $x -= 1;
        		}
		        $t -= $x * $ds;
		    }

		    if ( $month != 12 )
		    {
        		$tm = $months;
		        $tm = array_slice ( $tm, $month );
        		$t -= array_sum ( $tm ) * $ds;
		        unset ( $tm );
		    }

		    $nh = ( ( $month == 2 && $leap_year ? 29 : $months[$month-1] ) - $day );
		    $t -= $nh != 0 ? $nh * $ds : 0;
		    $nh = 23 - $hour;
		    $t -= $nh != 0 ? $nh * $hs : 0;
		    $nh = 59 - $minute;
		    $t -= $nh != 0 ? $nh * $ms : 0;
		    $nh = 59 - $second;
		    $t -= $nh != 0 ? $nh + 1 : 0;

		    return ( $t );
		}
	   function Edad($dob)
	   {
	       //fecha actual
		   list($d,$m,$y)=explode("/",$dob);
		   $dia=date(j);
           $mes=date(n);
           $ano=date(Y);
           //fecha de nacimiento
           $dianaz=$d;
		   $mesnaz=$m;
		   $anonaz=$y;
           //si el mes es el mismo pero el d�a inferior aun no ha cumplido a�os, le quitaremos un a�o al actual
           if (($mesnaz == $mes) && ($dianaz > $dia)) 
		   {
               $ano=($ano-1); 
		   }
           //si el mes es superior al actual tampoco habr� cumplido a�os, por eso le quitamos un a�o al actual
           if ($mesnaz > $mes) 
		   {
               $ano=($ano-1);
		   }
           //ya no habr�a mas condiciones, ahora simplemente restamos los a�os y mostramos el resultado como su edad
           $edad=($ano-$anonaz);
		   if ($edad == 0)
		   {
		       $timestamp1 = mktime(0,0,0,$m,$d,$y);
			   $timestamp2 = mktime(0,0,0,$mes,$dia,$ano); 
			   $segundos_diferencia = abs($timestamp2 - $timestamp1);
			   $dias = $segundos_diferencia / (60 * 60 * 24); 
		       return redondeado($dias,0)." dias";
           } 			   
           return $edad;
	   }
	   function EdadIngreso($dob,$fechaInternacion)
	   {
	       //fecha actual
		   list($d,$m,$y)=explode("/",$dob);
		   list($dia,$mes,$ano)=explode("/",$fechaInternacion);		   
           //fecha de nacimiento
           $dianaz=$d;
		   $mesnaz=$m;
		   $anonaz=$y;
		   //sacamos este a�o para calcular si el paciente es menor de un a�o pero donde el a�o es diferente
		   $y1 = $ano;
           //si el mes es el mismo pero el d�a inferior aun no ha cumplido a�os, le quitaremos un a�o al actual
           if (($mesnaz == $mes) && ($dianaz > $dia) && ($ano==$anonaz || ($ano-$anonaz)==1)) 		   
               $ano=($ano-1); 		   
           //si el mes es superior al actual tampoco habr� cumplido a�os, por eso le quitamos un a�o al actual
           if ($mesnaz > $mes && ($ano==$anonaz || ($ano-$anonaz)==1)) 		   
               $ano=($ano-1);
           
           //ya no habr�a mas condiciones, ahora simplemente restamos los a�os y mostramos el resultado como su edad
           $edad=($ano-$anonaz);
          // return $ano.' '.$anonaz;
		   if ($edad == 0)
		   {
		       $timestamp1 = mktime(0,0,0,$m,$d,$y);
			   $timestamp2 = mktime(0,0,0,$mes,$dia,$ano); 
			   $segundos_diferencia = abs($timestamp2 - $timestamp1);
			   $dias = $segundos_diferencia / (60 * 60 * 24); 
			   if (($y1-$y)==1) $dias = 365-$dias;
		       return $dias." dias";//redondeado($dias,0)." dias";
           } 
		   else
		   {
		       $ano = date(Y);
			   $edad=($ano-$anonaz);
		   }			   
           return $edad;
	   }
	   function horaRecortada($hora)
	   {
	       list($h,$m,$s) = split(":",$hora);
		   $ho = $h.":".$m;
		   if ( $ho == ":")
		       return "00:00";
		   else
			   return $ho;	   
	   }
	   function devolverFechaNormal($fecha)
	   {
	       list($fecha,$hora) = split(" ",$fecha);
		   list($dia,$mes,$ano)=split("-",$fecha);
		   $fecha_normal = $ano."/".$mes."/".$dia;
		   if ($fecha_normal == "//")
		       return "00/00/0000";
		   else
			   return $fecha_normal;	  
		   
//		return $fecha;
	   }
	   function fechaBase($fecha)
	   {
	       if ($fecha)
		   {
		       list($d,$m,$a) = split("[/-]",$fecha);
		       return $a."-".$m."-".$d;
		   }
		   else
		       return "";		   	   	   
	   }

		function compara_fechas($fecha1,$fecha2)            
		{            
            if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
                 list($dia1,$mes1,$ano1)=split("/",$fecha1);
            if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
                 list($dia1,$mes1,$ano1)=split("-",$fecha1);
            if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
                 list($dia2,$mes2,$ano2)=split("/",$fecha2);
            if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
                 list($dia2,$mes2,$ano2)=split("-",$fecha2);
            $dif = mktime(0,0,0,$mes1,$dia1,$ano1) - mktime(0,0,0, $mes2,$dia2,$ano2);
            return ($dif);                                     
        }
		function shiftDias($dias)
   		{
      		$mes = date("m");
	        $anio = date("Y");
	        $dia = date("d");
	        $ultimo_dia = date( "d", mktime(0, 0, 0, $mes + 1, 0, $anio) ) ;
	        $dias_adelanto = $dias;
	        $siguiente = $dia + $dias_adelanto;
	        if ($ultimo_dia < $siguiente)
	        {
		         $dia_final = $siguiente - $ultimo_dia;
        		 $mes++;
		         if ($mes == '13')
        		 {
		            $anio++;
        		    $mes = '01';
		         }
        		 $fecha_final = $dia_final.'/'.$mes.'/'.$anio;         
	      }
    	  else
      	  {
         	  $fecha_final = $siguiente .'/'.$mes.'/'.$anio;         
	      }
          return $fecha_final;
   	}
	function random_color(){
          mt_srand((double)microtime()*1000000);
          $color = '';
          while(strlen($color)<6){
                $color .= sprintf("%02X", mt_rand(0, 255));
          }
          return $color;
    }
	function sumarMinutos($hora,$minuto)
	{	    
        list($hora1, $minut) = split('[:]', $hora);
        $hora = date("H:i", mktime($hora, $minut+$minuto, 0));
        return $hora;
	}
	//devuelve el dia en numero que es hoy, ejemplo: para lunes retorna 1, para martes 2	
	function numeroDiaHoy($hoy)
	{
	    if ( $hoy == 1 )
		    return 1;
        if ( $hoy == 2 )
		    return 2;			
        if ( $hoy == 3 )
		    return 3;			
	    if ( $hoy == 4 )		
		    return 4;
        if ( $hoy == 5 )
		    return 5;
		if ( $hoy == 6 )
		    return 6;
        if ( $hoy == 7 )
		    return 7;							
	}
	function diaExactoDeSemana($dia,$f)
	{
	    if ( $dia == 0 )
		    return 1;
		else
		{
		    list($yi, $mi,$di) = split('-', $f);
		    $hoy = numeroDiaHoy(date('N',mktime(0,0,0,$mi,$di,$yi)));					
			if ( $hoy == $dia )
			    return 1;
			else
			    return 0;   
		}	 
	}
    function restarFecha($dFecIni, $dFecFin)
	{
	    if ($dFecIni == '0000-00-00' || $dFecIni == '' || $dFecFin == '0000-00-00' || $dFecFin == '')
		    return "";
    //    $dFecIni = str_replace('-','',$dFecIni);
		//$dFecIni = str_replace('/','',$dFecIni);
	//	$dFecFin = str_replace('-','',$dFecFin);
		//$dFecFin = str_replace('/','',$dFecFin);
		list($yi, $mi,$di) = split('-', $dFecIni);
		list($yf, $mf,$df) = split('-', $dFecFin);
	//	ereg( '([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})', $dFecIni, $aFecIni);
	//	ereg( '([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})', $dFecFin, $aFecFin);

		$date1 = mktime(0,0,0,$mi, $di, $yi);
		$date2 = mktime(0,0,0,$mf, $df, $yf);
		return round(($date2 - $date1) / (60 * 60 * 24));
	}        
	function cantidadHorasPasadas($fechaBase,$horaBase,$fechaHoy,$horaHoy,$cantidad)
	   {   //devuelve 1 si se paso de determinada hora
	       //devulve 0 si no se paso de determinada hora
	       //la fecha llega en el formatoi ingles		 
		   if ( $horaBase == "00:00:00" || $horaBase == "" )  
		       return 0;
		      
		   if ($fechaBase > $fechaHoy)
		   {
		       $fecha1 = $fechaHoy;
			   $hora1 = $horaHoy;
			   $fecha2 = $fechaBase;
			   $hora2 = $horaBase;
		   }
		   else
		   {	
		   	   if ($fechaBase == $fechaHoy) return 0;	   	   
		       $fecha1 = $fechaBase;
			   $hora1 = $horaBase;
			   $fecha2 = $fechaHoy;
			   $hora2 = $horaHoy;		   	   
		   }		   
		   list($a1,$m1,$d1) = split("-",$fecha1);
		   list($h1,$mi1,$s1) = split(":",$hora1);
		   list($a2,$m2,$d2) = split("-",$fecha2);
		   list($h2,$mi2,$s2) = split(":",$hora2);
		   $fecha1 = mktime($h1,$mi1,$s1,$m1,$d1,$a1);
           $fecha2 = mktime($h2,$mi2,$s1,$m2,$d2,$a2); 
           $diferencia = $fecha2-$fecha1;
           $diff['horas'] = (int)($diferencia/(60*60));
           $diff['dias'] = (int)($diferencia/(60*60*24));
           //return $diff['dias'];
           if ( $diff['dias'] > 0)
           {
			   return 1;
           }
		   else
		   {
		   	   if ($diff['horas'] == '00') $hora = 24; else $hora=$diff['horas'];	
		   	   //return $hora."jj";
		   	   
		   	   if ( $hora <= $cantidad )
			       return 0;
			   else
			       return 1;
		   }	   
	   }
	   function pasarParametroPhpJavaScript ($var) 
	   {
		    if (is_array($var)) 
			{
                 $res = '[';
		         $array = array();
		         foreach ($var as $a_var) 
				 {
                     $array[] = pasarParametroPhpJavaScript($a_var);
                  }
                  return '[' . join(',', $array) . ']';
            }
            elseif (is_bool($var)) 
			{
                 return $var ? 
				 'true' : 'false';
        }
        elseif (is_int($var) || is_integer($var) || is_double($var) || is_float($var)) 
		{
            return $var;
        }
        elseif (is_string($var)) {
        return "\"" . addslashes(stripslashes($var)) . "\"";
    }

    return FALSE;
}
	function redondeado ($numero, $decimales) 
    {
	    $factor = pow(10, $decimales);
        return (round($numero*$factor)/$factor); 
	}
	
	function cortarTexto($texto, $tamano, $delim='...') 
	{
		$contador = 0;

		// Cortamos la cadena por los espacios
		$arrayTexto = split(' ',$texto);
		$texto = '';

		// Reconstruimos la cadena
		while($tamano >= strlen($texto) + strlen($arrayTexto[$contador])){
		    $texto .= ' '.$arrayTexto[$contador];
		    $contador++;
		}
		return $texto; 
	}
	function insertarTextoPdf($texto,$tipo_op,$primer_param,$segundo_param,$tercer_param,$cuarto_param,$margen,$pdf,$x,$y)
	{
	    $pdf->Cell($primer_param,$segundo_param,$texto,$tercer_param,$cuarto_param,$margen); 	    
		$pdf->setXY($x,$y+5);
		
		return 1;
	}
	function br($texto, $max) 
	{       
        if(strlen($texto) > $max) //si el texto tiene mas de los caracteres que le indicamos con la variable $max          
        {  
            $texto = wordwrap($texto,$max,"<br>",false); 
			//nos lo corta a la cantidad de caracteres indicado 
        } 
        else 
		    $texto=$texto; 
            // si no llega a los caracteres incicado, pues lo deja tal cual 
        return $texto;       
	}  
    function nl2br_skip_html($string)
    {
    	// remove any carriage returns (mysql)
    	$string = str_replace("\r", '', $string);

	    // 	replace any newlines that aren't preceded by a > with a <br />
    	$string = preg_replace('/(?<!>)\n/', "<br />\n", $string);

    	return $string;
	}
	function MensajeError($numeroError)
	{
	    $error='';
	    switch ($numeroError)
		   {
		       case 1:
			          $error = "El registro No fue Guardado";
			          break;
		       case 2:
			          $error = "El registro Fue Guardado";
					  break;
               case 3:
			          $error = "No pudo asignarse el turno";
					  break; 					  
               case 4:
			          $error = "El Paciente No Existe";
					  break;
               case 5:
			          $error = "Error al guardar el registro: Debe seleccionar un PACIENTE";					  					  
					  break;
               case 6:
			          $error = "No se puede eliminar el turno";
					  break;	
               case 7:
			          $error = "Guarde Primero el profesional";
			          break;
               case 8:
			          $error = "No pudo eliminarse el registro";
					  break;					   
               case 9:
			          $error = "El registro fue guardado: Ingrese otra Encuesta si lo desea";
					  break;	
               case 10:
			          $error = "No se pudo guardar el Codigo";
					  break;					  
               case 11:
			          $error = "No se encontro el codigo: Debe ingresarlo como NUEVO";
					  break;
               case 12:
			          $error = "Este Medicamento no tiene presentacion";					  
					  break;					  					  
			   case 13:
			          $error = "El registro fue eliminado";						  				  					  				  
					  break;
			   case 14:
			          $error = "No se puede generar la factura";
					  break;
			   case 15:
			   		  $error = "No se puede cerrar el episodio";
					  break;
               case 16:
			          $error = "No se puede abrir el episodio";
					  break;
			   case 17:
			          $error = "No pudo realizarse el calculo";
					  break;
			   case 18:
			   		  $error = "Practica incorrecta";
					  break;
               case 19:
			   		  $error = "No existe el profesional";
					  break;  
               case 20:
			          $error = "EL APACHE NO HA SIDO CARGADO AUN";
					  break;
               case 21:
			          $error = "No se puede modificar el ingreso realizado por otro profesional";
					  break; 
               case 22:
			          $error = "Bienvenido";
					  break;
               case 23:
			          $error = "Hasta Pronto";					  
					  break;
               case 24:
			          $error = "La Fecha y la Hora fueron cambiadas";
					  break;
               case 25:
			          $error = "No puede eliminar la Indicacion ya esta realizada";
					  break;
			   case 26:
			   		  $error = "No se puede suspender la indicacion";
					  break;
               case 27:
			   		  $error = "NO se pudo cargar el nuevo Dia, INTENTE NUEVAMENTE";
					  break;	
			   case 28:
			   		  $error = "Profesional invalido para registrar este control";
					  break;
			   case 29:
			   		  $error = "No puede modificar la Indicacion ya esta realizada";
					  break;
			   case 30:
			   		  $error = "No puede eliminar la Indicacion de otro profesional";
					  break;
               case 31:
			   		  $error = "Ya caduco el tiempo para modificar";
					  break;
               case 32:
			   		  $error = "No puede modificar el control de otro profesional";
					  break;
			   case 33:
			   		  $error = "Ya caduco el tiempo para eliminar";
					  break;
               case 34:
			   		  $error = "No puede eliminar el control de otro profesional";
					  break;
               case 35:
			          $error = "No se pudo cerrar el balance";
					  break;
               case 36:
			          $error = "No se pudo crear un nuevo dia de balance";
					  break; 
               case 37:
			          $error = "La cama est� reservada, primero debe eliminar la reserva";
					  break; 
               case 38:
			   		  $error = "No pudo cambiarce el estado de la cama";
					  break;
               case 39:
			          $error = "No se pudo replicar la medicacion";
					  break; 
               case 40:
			          $error = "Debe ingresar la OBRA SOCIAL y el PROFESIONAL REMITIDO";
					  break;
	           case 41:
			          $error = "NO EXISTE indicacion para esa fecha";					  
					  break;		  
               case 42:
			          $error = "NO se pudo cargar la liquidacion correspondiente";
					  break;		  
               case 43:
			          $error = "Este Medicamento ya se encuentra cargado";
					  break; 
			   case 44:
			          $error = "Debe Ingresar Fecha y Lugar antes de seleccionar un Item";
					  break;
			   case 45:
			          $error = "No tiene Permisos para realizar esta carga";
					  break;
			   case 46:
			          $error = "Ya existe una nota de credito con fecha anterior";
					  break;		  
			   case 47:
			          $error = "La cantidad restante en el lote no cubre la cantidad solicitada";
					  break;
			   case 48:
			          $error = "La cantidad a ingresar es mas de la solicitada";
					  break;	
		       case 49:
			          $error = "No se puede eliminar la compra, Lote USADO";
					  break;
			   case 50:
			          $error = "No se puede eliminar el lote, Lote USADO";
					  break;	  
			   case 51:
			          $error = "La cantidad de sobreturnos OTORGADOS ya esta cubierta";			          
					  break; 
			   case 52:
			          $error = "Registro No Encontrado";			          
					  break;
			   case 53:
			          $error = "Usuario No Autorizado";			          
					  break;
			   case 54:
			          $error = "El problema ya est� registrado para ese Paciente";			          
					  break;
			   case 55:
			          $error = "No puede eliminar el registro realizado por otro profesional";			          
					  break;
               case 56:
			          $error = "Debe ingresar al menos una cirugia antes de Guardar el Protocolo";			          
					  break;
			   case 57:
			          $error = "Debe completar la evolucion antes de guardar";			          
					  break;
			   case 58:
			          $error = "No se pudo validar la indicacion";			          
					  break;
			   case 59:
			          $error = "Ya se encuentra realizada la indicacion para ese horario";			          
					  break;
			   case 60:
			          $error = "Este producto esta por debajo del stock minimo";			          
					  break;
			   case 61:
			          $error = "No selecciono ningun medicamento";			          
					  break;
			   case 62:
			          $error = "Esa fue la ultima consulta registrada";			          
					  break;
			   case 63:
			          $error = "Esa fue la ultima cirugia registrada";			          
					  break;
			   case 64:
			          $error = "Esa fue la ultima endoscopia registrada";			          
					  break;
			   case 65:
			          $error = "Las fechas no son validas";			          
					  break;
               case 66:
			          $error = "Este control ya fue registrado en este horario";			          
					  break;
			   case 67:
			          $error = "Imprimiendo Pulsera";			          
					  break;
			   case 68:
			          $error = "Error al Imprimir la Pulsera";			          
					  break;
			   case 69:
				  $error = "La password fue modificada con exito";
					  break;
			   case 70:
				  $error = "La password elegida no cumple con los requisitos obligatorios";
					  break;
			   case 71:
				  $error = "Los datos son incorrectos, vuelva a intertarlo";
					  break;
               case 72:
				  $error = "Debe elegir al menos una opcion";
					  break;
                           case 73:
				  $error = "Solo personal administrativo puede generar un siniestro nuevo";
					  break;
               default:
			          $error = "No se ha asignado un Mensaje";					  
           }			   
           return $error; 
	}
	function restaTiempo($fecha,$a = 0, $m = 0, $d = 0) 
	{
            $array_date = explode("-", $fecha);
            return $fecha = Date("Y-m-d", mktime(0, 0, 0, $array_date[1] + 0, $array_date[2] + 1, $array_date[0] + 0));
    }
        function ordenar_array() { 
  $n_parametros = func_num_args(); // Obenemos el n�mero de par�metros 
  if ($n_parametros<3 || $n_parametros%2!=1) { // Si tenemos el n�mero de parametro mal... 
    return false; 
  } else { // Hasta aqu� todo correcto...veamos si los par�metros tienen lo que debe ser... 
    $arg_list = func_get_args(); 
 
    if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) { 
      return false; // Si el primero no es un array...MALO! 
    } 
    for ($i = 1; $i<$n_parametros; $i++) { // Miramos que el resto de par�metros tb est�n bien... 
      if ($i%2!=0) {// Par�metro impar...tiene que ser un campo del array... 
        if (!array_key_exists($arg_list[$i], current($arg_list[0]))) { 
          return false; 
        } 
      } else { // Par, no falla...si no es SORT_ASC o SORT_DESC...a la calle! 
        if ($arg_list[$i]!=SORT_ASC && $arg_list[$i]!=SORT_DESC) { 
          return false; 
        } 
      } 
    } 
    $array_salida = $arg_list[0]; 
 
    // Una vez los par�metros se que est�n bien, proceder� a ordenar... 
    $a_evaluar = "foreach (\$array_salida as \$fila){\n"; 
    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada columna... 
      $a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n"; 
    } 
    $a_evaluar .= "}\n"; 
    $a_evaluar .= "array_multisort(\n"; 
    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada elemento... 
      $a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[".($i+1)."],\n"; 
    } 
    $a_evaluar .= "  \$array_salida);"; 
    // La verdad es que es m�s complicado de lo que cre�a en principio... :) 
 
    eval($a_evaluar); 
    return $array_salida; 
  } 
}
    function in_multiarray($elem, $array)
    {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if($array[$bottom] == $elem)
                return true;
            else
                if(is_array($array[$bottom]))
                    if(in_multiarray($elem, ($array[$bottom])))
                        return true;
                   
            $bottom++;
        }       
        return false;
    }
    //resta la cantidad de dias que uno desee a la fecha
    //la fecha viene en formato ingles
    function restarDiasFecha($fecha,$dias)
    {
    	return date("Y-m-d", strtotime("$fecha -$dias day"));
    }
    function ultimaFechaMes($fecha)
    {
    	$a = desglosarFecha(fechaBase($fecha));
    	$ultimo = date("t",mktime(0, 0, 0,$a[1], 1, $a[0]));
    	return $ultimo."/".$a[1]."/".$a[0];
    }
    function DiasEntreFechas($startDate, $endDate)
	{
    // get the number of days between the two given dates.
    //$startDate='2011-01-01';
    //$endDate='2011-12-31';
    	$days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;
    	$startMonth = date("m", strtotime($startDate));
    	$startDay = date("d", strtotime($startDate));
    	$startYear = date("Y", strtotime($startDate));   
   // $dates;//the array of dates to be passed back
    	for($i=0; $i<$days; $i++){
        	$dates[$i] = date("Y-m-d", mktime(0, 0, 0, $startMonth , ($startDay+$i), $startYear));
    	}
    	return $dates;   
    }
    function dias_transcurridos($fecha_i,$fecha_f)
	{
		$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
		$dias 	= abs($dias); $dias = floor($dias);		
		return $dias;
	}
    function limpiar_caracteres_especiales($s) 
    {
		$s = ereg_replace("[����]","&aacute;",$s);
		$s = ereg_replace("[����]","A",$s);
		$s = ereg_replace("[���]","&eacute;",$s);
		$s = ereg_replace("[���]","E",$s);
		$s = ereg_replace("[���]","&iacute;",$s);
		$s = ereg_replace("[���]","I",$s);
		$s = ereg_replace("[�����]","&oacute;",$s);
		$s = ereg_replace("[����]","O",$s);
		$s = ereg_replace("[���]","&uacute;",$s);
		$s = ereg_replace("[���]","U",$s);
	//	$s = str_replace(" ","-",$s);
		$s = str_replace("�","n",$s);
		$s = str_replace("�","N",$s);		
	//	$s = str_replace("�","&aacute;",$s);
		//para ampliar los caracteres a reemplazar agregar lineas de este tipo:
		//	$s = str_replace("caracter-que-queremos-cambiar","caracter-por-el-cual-lo-vamos-a-cambiar",$s);
		return $s;
	}
    function devolverIPPACS()
    {
	    if ($_SERVER) 
	    {  
			if ( $_SERVER[HTTP_X_FORWARDED_FOR] ) 
			{  
			    $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
			} 
            elseif ( $_SERVER["HTTP_CLIENT_IP"] ) 
			{  
			    $realip = $_SERVER["HTTP_CLIENT_IP"];  
			} 
		    else 
       		    {  
		        	$realip = $_SERVER["REMOTE_ADDR"];  
		    	}  
	    }
	    else 
	    {  
		if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) 
		{  
		    $realip = getenv( 'HTTP_X_FORWARDED_FOR' );  
		} 
		elseif ( getenv( 'HTTP_CLIENT_IP' ) ) 
		{  
		    $realip = getenv( 'HTTP_CLIENT_IP' );  
	        } 
		else 
                {  
		    $realip = getenv( 'REMOTE_ADDR' );  
		}  
	    }  

//print(" T� IP es: $realip"); 
	   list( $uno, $dos, $tres,$cuatro ) = split( '.', $realip );
	   if (
                (($uno == '192' && $dos == '168' && $tres == '1' && ($cuatro>=0 && $cuatro<=255)) && ($uno == '192' && $dos == '168' && $tres == '1' && $cuatro!=5)) 
              || 
                ($uno == '127' && $dos == '0' && $tres == '0' && $cuatro==='1') 
             )
              
               return "192.168.1.240";
	   else
	       return "traumatologia.hiperion.com.ar:8889";
	}
	function restarHoras($horaini,$horafin)
	{	
		$horai=substr($horaini,0,2);
		$mini=substr($horaini,3,2);
		$segi=substr($horaini,6,2);
		
		$horaf=substr($horafin,0,2);
		$minf=substr($horafin,3,2);
		$segf=substr($horafin,6,2);
	
		$ini=((($horai*60)*60)+($mini*60)+$segi);
		$fin=((($horaf*60)*60)+($minf*60)+$segf);
		
		$dif=$fin-$ini;
		
		$difh=floor($dif/3600);
		$difm=floor(($dif-($difh*3600))/60);
		$difs=$dif-($difm*60)-($difh*3600);	
		return date("H:i",mktime($difh,$difm,$difs));	
	}
    function sumarDiasFecha($fecha,$dias)
    {
    	return date("Y-m-d", strtotime("$fecha +$dias day"));
    }
    function esImagen($path)
    {
        $imageSizeArray = getimagesize($path);
        $imageTypeArray = $imageSizeArray[2];
        return (bool)(in_array($imageTypeArray , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)));
    }
    function devolverCaracterImpresoraEtiquetas($texto)
    {
        $texto = str_replace('é', 'c3_a9', $texto);
        $texto = str_replace('á', 'c3_a1', $texto);
        $texto = str_replace('í', 'c3_ad', $texto);
        $texto = str_replace('ó', 'c3_b3', $texto);
        $texto = str_replace('ú', 'c3_ba', $texto);
        $texto = str_replace('Á', 'c3_81', $texto);
        $texto = str_replace('É', 'c3_89', $texto);
        $texto = str_replace('Í', 'c3_8d', $texto);
        $texto = str_replace('Ó', 'c3_93', $texto);
        $texto = str_replace('Ú', 'c3_9a', $texto);
        $texto = str_replace('ñ', 'c3_b1', $texto);
        $texto = str_replace('Ñ', 'c3_91', $texto);
        return $texto;
    }
?>
