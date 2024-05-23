<?php 
require_once '../../config/db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
//print_object($data);

$Filtro = $data->valorBusqueda;
$Tipo = $data->valorTipo;

$Consulta = CrearConsulta($Tipo);
CargarGrilla($Consulta,$Filtro);

function CrearConsulta($Tipo)
	{
		//echo"<br>Tipo: $Tipo<br>";
		if ($Tipo=="Todo")
			{
				$Consulta = "SELECT * FROM sds_reg_registro WHERE registro_abierto = 0 ORDER BY idregistro";
				return $Consulta;
			}
		
		if ($Tipo=="Incidencias")
			{
				$Consulta = "SELECT * FROM sds_reg_registro WHERE incidencia_relacionada = 1 and registro_abierto = 0 ORDER BY idregistro";
				return $Consulta;
			}

		$Tipo = getId('sds_reg_tipo', 'idtipo', 'descripcion', $Tipo);
		$Consulta = "SELECT * FROM sds_reg_registro WHERE idtipo = $Tipo and registro_abierto = 0 ORDER BY idregistro";
		return $Consulta;
	}

function CargarGrilla($Consulta,$Filtro)
	{
		require_once '../../config/db.php';

		//echo "<br>$Consulta<br>";
		
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);
		
		echo "<table id='TablaRegistros' border='1'>";
		echo "<tr>"; 
			echo "<th>Id</th>";
			echo "<th>Fecha</th>";
			echo "<th>Hora</th>"; 
			echo "<th>Sector</th> ";
			echo "<th>Iniciante</th>";
			echo "<th>Derivador</th>"; 
			echo "<th>Problema</th> ";
			echo "<th>Tipo</th> ";
			echo "<th>Desarrollo</th>"; 
			

		echo "</tr>";

		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$IdRegistro = $result['idregistro'];
					$date = date_create($result['fecha_hora']);
					$Fecha=date_format($date, 'd/m/Y');
					$Hora=date_format($date, 'H:m');
					$Area = getDatoPorId("mds_org_dispositivo", "iddispositivo", "descripcion", $result["iddispositivo"]);
					$Solicitante = GetContactoValue($result["usuario_solicitante"]);
					$Derivador = getDatoPorId("mds_seg_usuario", "idusuario", "user", $result["usuario_derivacion"]);
					$Problema = $result['problema'];
					if($result['incidencia_relacionada']==1)
						{
							$Tipo = "Incidencia<br>Equipo: ".$result['equipo_detalle'];
						}
					else
						{
							$Tipo = getDatoPorId("sds_reg_tipo", "idtipo", "descripcion", $result["idtipo"]);
						}
					
					

					if($Filtro=="")
						{$ban=1;}
					else
						{
							$ban=0;
							if (!(stripos($IdRegistro,$Filtro)===false)) {$ban=1;}
							if (!(stripos($Fecha,$Filtro)===false)) {$ban=1;}	
							if (!(stripos($Area,$Filtro)===false)) {$ban=1;}						
							if (!(stripos($Solicitante,$Filtro)==false)) {$ban=1;}						
							if (!(stripos($Derivador,$Filtro)==false)) {$ban=1;}						
							if (!(stripos($Problema,$Filtro)==false)) {$ban=1;}
							if($result['incidencia_relacionada']==1)
								{
									if (!(stripos($Tipo,$Filtro)==false)) {$ban=1;}
								}
							if (ConfirmarFiltroEnMovimientos($Filtro,$IdRegistro)==1)  {$ban=1;}
						}

					if($ban==1)
						{
							echo "<tr>"; 
								echo "<td>$IdRegistro</td>"; 
								echo "<td>$Fecha</td>"; 
								echo "<td>$Hora</td>"; 
								echo "<td>$Area</td>"; 
								echo "<td>$Solicitante</td>"; 
								echo "<td>$Derivador</td>"; 
								echo "<td>$Problema</td>";
								echo "<td>$Tipo</td>"; 
								echo "<td>";MostrarMovimientos($result['idregistro'],0,1);"</td>";
								echo "<td><input type=button value='Ver' id=".$result['idregistro']." onclick='verBoton(this.id)'></td>"; 
							echo "</tr>"; 
						}
					
				}
			}

		echo "</table>";	
		$dbh->Cerrar();
		$dbh = NULL;
	}

function ConfirmarFiltroEnMovimientos($Filtro,$IdRegistro)
	{
		require_once '../../config/db.php';
		$Consulta = "SELECT GROUP_CONCAT(user,DATE_FORMAT(fecha,'%d/%m/%Y'),descripcion) as movimiento FROM sds_reg_movimiento inner join mds_seg_usuario on sds_reg_movimiento.idtecnico = mds_seg_usuario.idusuario WHERE sds_reg_movimiento.idregistro = $IdRegistro";
		//echo "<br>$Consulta<br>";
		
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);
	
		$ban=0;
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$Movimiento = $result['movimiento'];
					//echo "$Movimiento";
					if (!(stripos($Movimiento,$Filtro)===false)) {$ban=1;}
				}
			}

		$dbh->Cerrar();
		$dbh = NULL;
		return $ban;
	}

?>