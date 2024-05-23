<?php
require_once '../../config/db.php';
//require_once '../Lib/FuncionesComunes.php';
//variables

$json = $_POST['VarJson'];
$array = json_decode($json, true);
//$ban = 0;

/* El siguiente Array tiene el nombre de las columna con el que se rescataron los datos de exel, 
	si el exel cambia esos nombres deben coregirse en el array, ya que son las que se usan 
	para rescatar los datos que se deben enviar a la base de datos, 
	si la columna no tiene texto las muestra con EMPTY */

$array_columnas= array(
1 => "Reporte de Eventos de Asistencia", //de aca veo si el campo es ID: es la linea de una persona y a continuacion van sus horarios
2 => "__EMPTY",
3 => "__EMPTY_1",
4 => "__EMPTY_2",
5 => "__EMPTY_3",//de aca rescato el legajo
6 => "__EMPTY_4",
7 => "__EMPTY_5", //de aca rescato el mes
8 => "__EMPTY_6",
9 => "__EMPTY_7",
10 => "__EMPTY_8",
11 => "__EMPTY_9",//de aca rescato apellido
12 => "__EMPTY_10",
13 => "__EMPTY_11",
14 => "__EMPTY_12",
15 => "__EMPTY_13",
16 => "__EMPTY_14",
17 => "__EMPTY_15",
18 => "__EMPTY_16",
19 => "__EMPTY_17",
20 => "__EMPTY_18",
21 => "__EMPTY_19",
22 => "__EMPTY_20",
23 => "__EMPTY_21",
24 => "__EMPTY_22",
25 => "__EMPTY_23",
26 => "__EMPTY_24",
27 => "__EMPTY_25",
28 => "__EMPTY_26",
29 => "__EMPTY_27",
30 => "__EMPTY_28",
31 => "__EMPTY_29"
);
    
$ban_id = 0;
$ban_arrancar=0;//me sirve para saber cuando arranco
$anuncio_log = "";//es el texto que voy a devolver
$id_contacto = 0;
$fondo = "<div>";
$mes = 0;
$ban_guardar = 0;
$fila_cont = 2;
$cont_g_fichadas = 0;
$cont_g_fichadas_repetidas = 0;
$cont_g_contacto_inexistente = 0;
$cont_g_contacto_sin_fichadas = 0;

foreach ($array as $linea_exel) 
	{
        $fila_cont = $fila_cont+1;
		//---------------------------------------------------------
		if (isset($linea_exel["$array_columnas[1]"]))
			{
				$arranque = $linea_exel["$array_columnas[1]"];
			}
		else
			{
				$arranque = "";
			}
		//----------------------------------------------------------
		if ($arranque=='Periodo:')
			{
				$periodo = $linea_exel["$array_columnas[7]"];
				$mes = substr($periodo, 5,2);
				$anio = substr($periodo, 0,4);
				//$anuncio_log = "<br>Periodo: $mes/$anio<br>";
			}
		//-------------------------------------------------------------
		if ($arranque=='ID:')
		{
			$ban_arrancar=1;//una vez que arranca ya queda fijo el 1
		}
		//-----------------------------------------------------------------
 		if($ban_arrancar==1)
			{
                if($arranque =='ID:' )
                    {
						if ($ban_id==1)//si es 1 quiere decir que la fila anterior era in ID y no existian fichadas
							{
								//$anuncio_log = "$anuncio_log Fila $fila_cont No existen fichadas";
								$anuncio_log = "$anuncio_log No existen fichadas";
								$anuncio_log = "$anuncio_log <p style='color:blue; padding-left:10px;padding-bottom:0px'>No importado...</p></div>";
								$fila_cont = $fila_cont+1;
								$cont_g_contacto_sin_fichadas = $cont_g_contacto_sin_fichadas+1;
							}
						$legajo = $linea_exel["$array_columnas[5]"];
						$apellido = trim($linea_exel["$array_columnas[11]"]);
						//----------------------------------------------------------------
						$fondo = get_fondo($fondo);
						$anuncio_log = "$anuncio_log $fondo";
						//-------------------------------------------
						$idcontacto = getIdContacto($legajo);
						if($idcontacto===0)
							{
								$ban_guardar = 0;
								//$anuncio_log = "$anuncio_log Fila $fila_cont Empleado: $apellido  Legajo: $legajo No existe en mds_org_contacto<br>";
								$anuncio_log = "$anuncio_log Empleado: $apellido  Legajo: $legajo No existe en mds_org_contacto<br>";
								$cont_g_contacto_inexistente = $cont_g_contacto_inexistente + 1;
							}
						else
							{
								//$anuncio_log = "$anuncio_log Fila $fila_cont Empleado: $apellido, Legajo: $legajo<br>";
								$anuncio_log = "$anuncio_log Empleado: $apellido, Legajo: $legajo<br>";
								$ban_guardar = 1;
							}
						//-------------------------------------------
						$ban_id=1;
                    }
                else
                    {
                        if($ban_guardar == 1)
                            {
								$aux = '';
								$cont_guardadas=0;
								$cont_repetidas=0;
                                for($i=1;$i<=31;$i++)
                                    {

                                        if(isset($linea_exel[$array_columnas[$i]]))
                                            {
												$celda_fichadas = $linea_exel[$array_columnas[$i]];
												$largo_celda_fichadas = strlen($celda_fichadas);
												$cantidad_fichadas=($largo_celda_fichadas)/5;
												$aux_fichadas = '';
												for($f=0;$f<$largo_celda_fichadas;$f++)
													{
														if(($f%5)==0)
														{
															$k=$f-5;
															$fichada = substr($celda_fichadas,$k,5);
															$fecha_bd = get_fecha($i,$mes,$anio,$fichada);

															if(verificar_repeticion($idcontacto,$fecha_bd)==1)
																{
																	$aux_fichadas = "$aux_fichadas $fichada DUPLICADA, ";
																	$cont_repetidas++;
																	$cont_g_fichadas_repetidas++;
																}
															else
																{
																	guardar_fichada($idcontacto,$fecha_bd);
																	$cont_guardadas++;
																	$aux_fichadas = "$aux_fichadas $fichada IMPORTADA, ";
																	$cont_g_fichadas++;
																}
														}
													}
                                                $aux = "$aux Dia $i: $cantidad_fichadas Fichadas,$aux_fichadas<br>";
												
                                            }
                                    }
								//$anuncio_log = "$anuncio_log Fila $fila_cont Fichadas: <br>$aux";
								$anuncio_log = "$anuncio_log $cont_guardadas fichadas importadas y $cont_repetidas fichadas duplicadas...";
								$anuncio_log = "$anuncio_log <p style='color:green; padding-left:10px;padding-bottom:0px'>importado...</p></div>";
                            }
						else
							{
								//$anuncio_log = "$anuncio_log Fila $fila_cont Solicite el Alta del Empleado para guardar las Fichadas";
								$anuncio_log = "$anuncio_log Solicite el Alta del Empleado para guardar las Fichadas";
								$anuncio_log = "$anuncio_log <p style='color:red; padding-left:10px;padding-bottom:0px'>No importado...</p></div>";
							}
						$ban_id=0;
                    } 

					
			} 

        
		

		

	}

	$anuncio_log = "<br>Periodo: $mes/$anio<br>Total empleados inexistentes en base de datos: $cont_g_contacto_inexistente<br>Total empleados sin fichadas: $cont_g_contacto_sin_fichadas<br>Total fichadas guardadas: $cont_g_fichadas <br>Total fichadas duplicadas: $cont_g_fichadas_repetidas<hr> $anuncio_log";

//$anuncio_log = $json;

$resultado = array("anuncio_log"=>"$anuncio_log");
    

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 

 
function guardar_fichada($idcontacto,$fecha_bd)
    {
		require_once '../../config/db.php';
		$sql = "INSERT INTO mds_hor_registro (idcontacto,fecha) values($idcontacto,'$fecha_bd')";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL;
		//return $sql;
    }

function get_fecha($dia,$mes,$anio,$hora)
	{
		if($dia<10)
			{
				$dia = "0$dia";
			}
		$hora = "$hora:00";

		$fecha_bd = "$anio-$mes-$dia $hora";
		return $fecha_bd;

	}

function verificar_repeticion($idcontacto,$fecha_bd)
	{
		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$consulta = "SELECT * 
        FROM mdsyt.mds_hor_registro 
        WHERE idcontacto = $idcontacto and fecha = '$fecha_bd'";
        $dato =0 ;
		
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				if($result = $dbh->Registro())
					{
						$dato = 1;
					}
			}	
			
		$dbh->Cerrar();
		$dbh = NULL; 
		return $dato;
	}
 
function get_fondo($fondo)
	{
		if ($fondo =="<div>")
			{
				$fondo = "<div style='background-color:#CDE1F9;'>";
			}
		else
			{
				$fondo = "<div>";
			}
			$fondo = "<div style='background-color:#CDE1F9;'>";
		return $fondo;
	}

function getIdContacto($Dato)
	{
 		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$IdContacto=0;
		$consulta = "SELECT * FROM mds_org_contacto WHERE legajo = '$Dato'";
		
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta."; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					//$IdContacto=1;
					$IdContacto = $result['idcontacto'];
				}
			}	
			
		$dbh->Cerrar();
		$dbh = NULL; 
		return $IdContacto;
	}
/* function ValidarFechas($desde,$hasta,$idcontacto)
	{
		$ban = 0;

		if ($ban==0)
			{
				$consulta = "SELECT * FROM mds_hor_licencia WHERE idcontacto = $idcontacto and '$desde' BETWEEN desde AND hasta";
				$ban = ValidarFecha($consulta);
			}

		if ($ban==0)
			{
				$consulta = "SELECT * FROM mds_hor_licencia WHERE idcontacto = $idcontacto and '$hasta' BETWEEN desde AND hasta";
				$ban = ValidarFecha($consulta);
			}
		
		if ($ban==0)
			{
				$consulta = "SELECT * FROM mds_hor_licencia WHERE idcontacto = $idcontacto and desde >= '$desde' and hasta <= '$hasta'";
				$ban = ValidarFecha($consulta);
			}
		return $ban;
		
	}
function ValidarFecha($consulta)
	{
		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$ban = 0;
		
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta."; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$idlicencia = $result['idlicencia'];
					if($idlicencia>0)
						{$ban = 1;}
				}
			}	
			
		$dbh->Cerrar();
		$dbh = NULL; 
		return $ban;
	} */
 

//porquerias sin uso
/* $columna_dia_01 = "Reporte de Eventos de Asistencia"; //de aca veo si el campo es ID: es la linea de una persona y a continuacion van sus horarios
$columna_dia_02 = "__EMPTY";
$columna_dia_03 = "__EMPTY_1";
$columna_dia_04 = "__EMPTY_2";
$columna_dia_05 = "__EMPTY_3";//de aca rescato el legajo
$columna_dia_06 = "__EMPTY_4";
$columna_dia_07 = "__EMPTY_5"; //de aca rescato el mes
$columna_dia_08 = "__EMPTY_6";
$columna_dia_09 = "__EMPTY_7";
$columna_dia_10 = "__EMPTY_8";
$columna_dia_11 = "__EMPTY_9";//de aca rescato apellido
$columna_dia_12 = "__EMPTY_10";
$columna_dia_13 = "__EMPTY_11";
$columna_dia_14 = "__EMPTY_12";
$columna_dia_15 = "__EMPTY_13";
$columna_dia_16 = "__EMPTY_14";
$columna_dia_17 = "__EMPTY_15";
$columna_dia_18 = "__EMPTY_16";
$columna_dia_19 = "__EMPTY_17";
$columna_dia_20 = "__EMPTY_18";
$columna_dia_21 = "__EMPTY_19";
$columna_dia_22 = "__EMPTY_20";
$columna_dia_23 = "__EMPTY_21";
$columna_dia_24 = "__EMPTY_22";
$columna_dia_25 = "__EMPTY_23";
$columna_dia_26 = "__EMPTY_24";
$columna_dia_27 = "__EMPTY_25";
$columna_dia_28 = "__EMPTY_26";
$columna_dia_29 = "__EMPTY_27";
$columna_dia_30 = "__EMPTY_28";
$columna_dia_31 = "__EMPTY_29"; */