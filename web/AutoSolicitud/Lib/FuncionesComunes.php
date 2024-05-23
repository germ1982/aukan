<?php


function limpia_espacios($cadena)
	{
		$cadena = str_replace(' ', '', $cadena);
		return $cadena;
	}

function CargarCheckboxTecnicos($NombreCheck)
	{
	//Aca arma los checkbox con los tecnicos, ahora habria que ver la consulta segun el rol
	//$IdAplicacion = getId('Aplicaciones','IdAplicacion','Aplicacion','Registros Tecnicos');
	//$consulta = "select * from Usuarios inner join AplicacionesPermisos on Usuarios.IdUsuario = AplicacionesPermisos.IdUsuario WHERE IdAplicacion = $IdAplicacion order by Usuario";
	//Solucion, solo se cambio la consulta
	//en la siguiente consulta se cambio a iditem=33 para que funcione en la base troncal de alla
	$consulta = "SELECT * FROM mds_seg_usuario WHERE idusuario IN (SELECT idusuario FROM mds_seg_usuario_rol WHERE idrol
	in (select idrol from mds_seg_permiso where iditem=33)) order by user";
	require_once '../../config/db.php';
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$result = $dbh->Select($consulta);
	$i=0;
	if (!$result) 
		{
			echo "<p>Error en la consulta.</p>"; 
		}
	else 
		{
			echo '<table>';
				while ($result = $dbh->Registro())
				{			
					if ($i==0) {echo '<tr>';}
					echo '<td>';
						//$Tecnico = str_replace("."," ", $result["user"]);
						//$Tecnico = ucwords($Tecnico);
						$Tecnico = $result["user"];
						$NombreCompuestoCheck = limpia_espacios($NombreCheck.$Tecnico);
						echo '<input type="checkbox" name="'.$NombreCompuestoCheck.'" id="'.$NombreCompuestoCheck.'"value="'.$Tecnico.'">'.$Tecnico;
						$NombreCompuestoCheck="";
					echo '</td>';
					if ($i==5)
					   {
					   		echo '</tr>';
					   		$i=0;
					   		//echo '<br>';
					   }
					$i++;
				}
			echo '</table>';
		}	
	
	$dbh->Cerrar();
	$dbh = NULL;}

function getId($Tabla, $IdCampo, $CampoDato, $Dato)
	{
		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$consulta = "SELECT * FROM $Tabla WHERE $CampoDato = '$Dato'";
		//echo"<br>Consulta: $consulta<br>";
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$IdArea = $result[$IdCampo];
				}
			}	
		$dbh->Cerrar();
		$dbh = NULL;
		return $IdArea;}

function getDatoPorId($Tabla, $CampoId, $CampoDato, $IdDato)
	{
		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$Dato = "";
		$consulta = "Select * from $Tabla where $CampoId = '$IdDato'";
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$Dato = $result[$CampoDato];
				}
			}	
		$dbh->Cerrar();
		$dbh = NULL;
		return $Dato;
	}

function GenerarListadoGenerico($Consulta, $CampoIdValue, $CampoValue, $CampoIdVinculo)
	{
		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		//echo "<br>$Consulta<br>";
		$result = $dbh->Select($Consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$DatoValue = $result[$CampoValue];
					$IdValue=  $result[$CampoIdValue];
					$IdVinculo=  $result[$CampoIdVinculo];
					echo "<OPTION value='$DatoValue' data-idvalue=$IdValue data-idvinculo=$IdVinculo></OPTION>";
				}
			}	
		
		$dbh->Cerrar();
		$dbh = NULL;}

function LlenarCombo($consulta, $indice, $dato)
	{
		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					echo '<OPTION ID="'.$result[$indice].'" VALUE="'.$result[$indice].'">'.$result[$dato].'</OPTION>';
				}
			}	
		
		$dbh->Cerrar();
		$dbh = NULL;}

function Logout()
	{
		// Destruye todas las variables de la sesi�n
		$_SESSION = array();
		// Finalmente, destruye la sesion
		session_destroy();}

function VerificarSession()
	{
		session_start();
		if (!isset($_SESSION['gIdUsuario']))
		{
			header("Location: index.php");
		}
		else {
			return $_SESSION['gIdUsuario'];
		}
	}

function GetIdUsuarioUsando()
		{

			$idusuario = isset($_GET['idusuario']) ? $_GET['idusuario']:null;								
			if ($idusuario==null)//si esto va a andar siempre porque le pasa los datos desde yii, este if ya no haria falta
				{
					VerificarSession();
				}				
			//$Usuario = getDatoPorId('mds_seg_usuario', 'idusuario', 'user', $idusuario);
			//echo ("<br><br>usando: $Usuario Id: $idusuario<br><br>");
			return $idusuario;

		}

function data_submitted() 
	{

	    if (empty($_POST)) {
			if(empty($_GET)) {
	            return false;
			}
			else {
				return (object)$_GET;
			}
	    } else {
	        return (object)$_POST;
	    }

	}

function print_object($object) 
	{
	    echo "<PRE>";
	    print_r($object);
	    echo "</PRE>";
	}

function GetFechaActual()
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$mydate=getdate(date("U"));
		
		$dia = $mydate['mday'];
		if($dia<=9)
			{$dia = '0'.$dia;}

		$mes = $mydate['mon'];
		if ($mes<=9)
			{$mes='0'.$mes;}

		$Fecha = "$dia/$mes/$mydate[year]";
		//echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
		return $Fecha;
	}

function GetFechaActualParaMySql()
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$mydate=getdate(date("U"));
		
		$dia = $mydate['mday'];
		if($dia<=9)
			{$dia = '0'.$dia;}

		$mes = $mydate['mon'];
		if ($mes<=9)
			{$mes='0'.$mes;}

		//$Fecha = "$dia/$mes/$mydate[year]";
		//echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
		$Fecha = "$mydate[year]-$mes-$dia";
		return $Fecha;
	}

function getRealIP() 
	{

	        if (!empty($_SERVER['HTTP_CLIENT_IP']))
	            return $_SERVER['HTTP_CLIENT_IP'];
	           
	        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	            return $_SERVER['HTTP_X_FORWARDED_FOR'];
	       
	        return $_SERVER['REMOTE_ADDR'];
	}

function FechaSqlAPhp($Fecha)
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$Fecha = date_create("$Fecha");
		$Fecha= date_format($Fecha,"d/m/Y");
		return  $Fecha;
	}

function GetNombreMes($mes)
	{
		setlocale(LC_TIME, 'spanish');  
		$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
		return $nombre;
	} 

function ArmarDateTimeParaMySql($Fecha, $Hora)
	{
		$anio = substr($Fecha, 6,4);
		$mes  = substr($Fecha, 3,2);
		$dia = substr($Fecha, 0,2);
		$DT = "$anio-$mes-$dia $Hora:00";
		return $DT;
	}
function ArmarDateParaMySql($Fecha)
	{
		$anio = substr($Fecha, 6,4);
		$mes  = substr($Fecha, 3,2);
		$dia = substr($Fecha, 0,2);
		$DT = "$anio-$mes-$dia";
		return $DT;
	}


function GetContactoValue($IdContacto)
	{
		$id_persona = getId('mds_org_contacto', 'idpersona', 'idcontacto', $IdContacto);
		$consulta = "Select idpersona, concat(apellido,', ',nombre) as solicitante from sds_com_persona where idpersona = $id_persona";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{	
				$result = $dbh->Registro();
				$Contacto = $result['solicitante'];
			}	
		
		$dbh->Cerrar();
		$dbh = NULL;
		return $Contacto;
	}

function MostrarMovimientos($idregistro,$Edicion,$TamañoLetra)
	{
		require_once '../../config/db.php';
		include_once('../Lib/FuncionesComunes.php');
		
		$Consulta = "SELECT idmovimiento,idtecnico,descripcion,fecha FROM sds_reg_movimiento WHERE idregistro = $idregistro order by idmovimiento";
		$Id="";
		//echo "<br>$Consulta<br>";

		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);	
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				echo "<table>";
				echo"<FONT SIZE=1>";
					while ($result = $dbh->Registro())
						{
							echo "<tr>"; 
								$Tecnico = GetDatoPorId("mds_seg_usuario", "idusuario", "user", $result['idtecnico']);
								$movimiento = $result['descripcion'];
								$date = date_create($result['fecha']);
								$fecha=date_format($date, 'd/m/Y');
								//$TamañoLetra = 3;
								echo "<td><FONT SIZE=$TamañoLetra>$fecha</font></td>";
								echo "<td><FONT SIZE=$TamañoLetra>$Tecnico</font></td>";
								echo "<td><FONT SIZE=$TamañoLetra>$movimiento</font></td>";
								if ($Edicion==1)
									{
										echo "<td><input type=button value='Editar' id=".$result['idmovimiento']." style='font-size:8px' onclick='EditarMovimiento(this.id)'></td>";
									} 
							echo "</tr>"; 

						}
				echo"</font>";
				echo "</table>";

				/*while ($result = $dbh->Registro())
					{
						$Tecnico = GetDatoPorId("mds_seg_usuario", "idusuario", "user", $result['idtecnico']);
						$movimiento = $result['descripcion'];
						$date = date_create($result['fecha']);
						$fecha=date_format($date, 'd/m/Y');
						echo "<li>$fecha ** $Tecnico ** $movimiento<br>";

					}*/

			}	

		$dbh->Cerrar();
		$dbh = NULL;

	}
?>
