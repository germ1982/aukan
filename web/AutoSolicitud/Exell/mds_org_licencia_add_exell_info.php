<?php
require_once '../../config/db.php';
//require_once '../Lib/FuncionesComunes.php';
//variables

$json = $_POST['VarJson'];
$idusuariocarga = $_POST['VarIdUsuarioCarga'];
$array = json_decode($json, true);
$ban = 0;

/* Las siguientes variables tienen el nombre de la columna con el que se rescataron los datos de exel, 
	si el exel cambia esos nombres deben coregirse en las siguientes variables, ya que son las que se usan 
	para rescatar los datos que se deben enviar a la base de datossa, 
	si la columna no tiene texto las muestra con EMPTY */
	$columna_Serv = "Detalle de Novedades"; 
	$columna_PSU_Hist = "__EMPTY"; 
	$columna_F_Ingreso = "__EMPTY_1";
	$columna_Legajo = "__EMPTY_2";
	$columna_Apellido_y_Nombre = "__EMPTY_3";
	$columna_T_Lic = "__EMPTY_4";
	$columna_Descripcion = "__EMPTY_5";
	$columna_Año = "__EMPTY_6";
	$columna_Desde = "__EMPTY_7";
	$columna_Hasta = "__EMPTY_8";
	$columna_Dias_Lab = "__EMPTY_9";
	$columna_Cant = "__EMPTY_10";
	$columna_Estado = "__EMPTY_11";
	$columna_Observ = "__EMPTY_12";
	$columna_Liq = "__EMPTY_13";

$ban==0;
$anuncio_log = "";
$idcontacto = 0;
$fondo = "<div>";
foreach ($array as $value) 
	{
		if($ban==1)
			{
				
				$legajo = $value["$columna_Legajo"];
				$idcontacto = getIdContacto($legajo);
				//$idcontacto
				$Apellido_y_Nombre = trim($value["$columna_Apellido_y_Nombre"]);

				if ($fondo =="<div>")
					{
						$fondo = "<div style='background-color:#EBEBEB;'>";
					}
				else
					{
						$fondo = "<div>";
					}

				$anuncio_log = "$anuncio_log $fondo";
 				if ($idcontacto > 0)
					{
						$ban_fechas = 0;			
						$desde = substr($value["$columna_Desde"], 0,10);
						$hasta = substr($value["$columna_Hasta"], 0,10);
						$detalle = trim($value["$columna_T_Lic"])." - ".trim($value["$columna_Descripcion"]);
						$cantidad_dias = $value["$columna_Cant"];
						$idusuario = $idusuariocarga;

						$ban_fechas = ValidarFechas($desde,$hasta,$idcontacto);

						if($ban_fechas==0)
							{
								$aux = GuardarLicencia($desde,$hasta,$detalle,$idcontacto,$cantidad_dias,$idusuario);
								$anuncio_log = "$anuncio_log <p style='color:#90EE90'>   Se ha guardado la licencia de: $Apellido_y_Nombre \n   Legajo: $legajo\n   $aux</p>";
							}
						else
							{
								$anuncio_log = "$anuncio_log <p style='color:red'>   No se ha guardado la licencia de: $Apellido_y_Nombre \n   Legajo: $legajo\n   Razón: ya hay licencias cargadas en las fechas solicitadas</p>";
							}
						
					}
				else
					{
						$anuncio_log = "$anuncio_log <p style='color:red'>   No se ha guardado la licencia de: $Apellido_y_Nombre \n   Legajo: $legajo\n   Razón: No existe en mds_org_contacto</p>";
					}
				$anuncio_log = "$anuncio_log </div>";
			}
		
		$arranque = $value["$columna_Serv"];
		if ($arranque=='Serv.')
			{
				$ban=1;
			}

	}

//$anuncio_log = "entro bien";

$resultado = array("anuncio_log"=>"$anuncio_log");
    

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
 
function GuardarLicencia($desde,$hasta,$detalle,$idcontacto,$cantidad_dias,$idusuario)
    {
		require_once '../../config/db.php';
		$sql = "INSERT INTO mds_hor_licencia (desde,hasta,detalle,idcontacto,cantidad_dias,idusuario) values('$desde','$hasta','$detalle',$idcontacto,$cantidad_dias,$idusuario)";
/* 		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($sql);
		$dbh->Cerrar();
		$dbh = NULL; */
		return $sql;
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
				echo "<p>Error en la consulta.</p>"; 
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
function ValidarFechas($desde,$hasta,$idcontacto)
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
				echo "<p>Error en la consulta.</p>"; 
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
	}


