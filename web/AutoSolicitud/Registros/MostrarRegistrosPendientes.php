<?php 
require_once '../../config/db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
//print_object($data);

$Filtro = $data->valorBusqueda;
$Tipo = $data->valorTipo;
$SoloIncidencias = $data->valorSoloIncidencias;
$IdUsuario = $data->valorIdUsuario;

$Consulta = CrearConsulta($Tipo,$SoloIncidencias);
CargarGrilla($Consulta,$Filtro,$SoloIncidencias,$IdUsuario);

function CrearConsulta($Tipo,$SoloIncidencias)
	{
		//echo"<br>Tipo: $Tipo<br>";
		if ($Tipo=="Todo")
			{
                if ($SoloIncidencias==1)
                    {
                        $Consulta = "SELECT * FROM sds_reg_registro WHERE registro_abierto = 1 and incidencia_relacionada = 1 ORDER BY idregistro";
                    }
                else
                    {
                        $Consulta = "SELECT * FROM sds_reg_registro WHERE registro_abierto = 1 ORDER BY idregistro";
                    }
				
				return $Consulta;
			}

        $Tipo = getId('sds_reg_tipo', 'idtipo', 'descripcion', $Tipo);
        
        if ($SoloIncidencias==1)
            {
                $Consulta = "SELECT * FROM sds_reg_registro WHERE idtipo = $Tipo and registro_abierto = 1 and incidencia_relacionada = 1 ORDER BY idregistro";
            }
        else
            {
                $Consulta = "SELECT * FROM sds_reg_registro WHERE idtipo = $Tipo and registro_abierto = 1 ORDER BY idregistro";
            }
		
		return $Consulta;
	}
function CargarGrilla($Consulta,$Filtro,$SoloIncidencias,$IdUsuario)
    {
        require_once '../../config/db.php';
        include_once('../Lib/FuncionesComunes.php');

        $dbh = new BaseDatos();
        $dbh->Iniciar();
        $result = $dbh->Select($Consulta);	

        echo "<table border='1'>"; 
            echo "<tr>"; 
                $Letra = "<FONT SIZE=3>";
                echo "<th>$Letra Id</th>";
                echo "<th>$Letra Fecha</th>";
                if ($SoloIncidencias==0 ) echo "<th>$Letra Hora</th>"; 
                echo "<th>$Letra Sector</th> ";
                echo "<th>$Letra Iniciante</th>";
                echo "<th>$Letra Derivador</th>"; 
                if ($SoloIncidencias==1) echo "<th>$Letra Equipo</th>"; 
                echo "<th>$Letra Problema</th>"; 
                echo "<th>$Letra Tipo</th> ";
                echo "<th>$Letra Movimientos</th> ";
                if ($SoloIncidencias==0) echo "<th>$Letra Incidencia</th> ";
            echo "</tr>";

            if (!$result) 
                {
                    echo "<p>Error en la consulta.</p>"; 
                }
            else 
                {

                    while ($result = $dbh->Registro())
                    {
                        /* guardo los datos en variables------------------------------------------------------------------------------------------- */
                            $IdRegistro = $result['idregistro'];
                            $date = date_create($result['fecha_hora']);
                            $Fecha=date_format($date, 'd/m/Y');
                            $Hora=date_format($date, 'H:m');
                            $Sector = getDatoPorId("mds_org_dispositivo", "iddispositivo", "descripcion", $result["iddispositivo"]);
                            $Iniciante = GetContactoValue($result["usuario_solicitante"]);
                            $Derivador = getDatoPorId("mds_seg_usuario", "idusuario", "user", $result["usuario_derivacion"]);
                            $Equipo = $result['equipo_detalle'];
                            $Problema = $result['problema'];
                            $IdTipo = $result["idtipo"];
                            $Tipo = getDatoPorId("sds_reg_tipo", "idtipo", "descripcion", $result["idtipo"]);
                            $IncidenciaRelacionada = "No";
                            if ($result['incidencia_relacionada']==1) $IncidenciaRelacionada = "Si";
                        /* Filtrado------------------------------------------------------------------------------------------- */
                            if($Filtro=="")
                                {$ban=1;}
                            else
                                {
                                    $ban=0;
                                    if (!(stripos($IdRegistro,$Filtro)===false)) {$ban=1;}
                                    if (!(stripos($Fecha,$Filtro)===false)) {$ban=1;}	
                                    if (!(stripos($Sector,$Filtro)===false)) {$ban=1;}						
                                    if (!(stripos($Iniciante,$Filtro)==false)) {$ban=1;}						
                                    if (!(stripos($Derivador,$Filtro)==false)) {$ban=1;}	
                                    if (!(stripos($Problema,$Filtro)==false)) {$ban=1;}					
                                    if($SoloIncidencias==1)
                                        {
                                            if (!(stripos($Equipo,$Filtro)==false)) {$ban=1;}
                                        }
                                    if (ConfirmarFiltroEnMovimientos($Filtro,$IdRegistro)==1)  {$ban=1;}
                                }

                            if($SoloIncidencias==1 && $ban==1)
                                {
                                    if($IncidenciaRelacionada=='No') $ban = 0;
                                }
                        /* Mostrar los datos------------------------------------------------------------------------------------------- */
                        if($ban==1)
						{
							echo "<tr>"; 
								echo "<td>$IdRegistro</td>"; 
								echo "<td>$Fecha</td>"; 
								if ($SoloIncidencias==0 ) echo "<td>$Hora</td>"; 
								echo "<td>$Sector</td>"; 
								echo "<td>$Iniciante</td>"; 
                                echo "<td>$Derivador</td>"; 
                                if ($SoloIncidencias==1) echo "<td>$Equipo</td>";
								echo "<td>$Problema</td>";
								echo "<td>$Tipo</td>"; 
                                echo "<td>";MostrarMovimientos($result['idregistro'],0,1);"</td>";
                                if ($SoloIncidencias==0 ) echo "<td>$IncidenciaRelacionada</td>"; 
                                //if ($SoloIncidencias==0 ) echo "<td><input type=submit value='Asistir' id=".$result['idregistro']." onclick='verBoton(this.id)'></td>"; //cambiar por un boton comun y pasar el this id y el iduser
                                if ($IncidenciaRelacionada=='Si' ) 
                                    {
                                        echo "<td><input type=button value='Asistir' id=".$result['idregistro']." onclick='AbrirIncidencia($IdRegistro,$IdUsuario,$IdTipo)'></td>"; 
                                    }
                                else
                                    {
                                        echo "<td><input type=button value='Asistir' id=".$result['idregistro']." onclick='AbrirRegistro($IdRegistro,$IdUsuario,$IdTipo)'></td>"; 
                                    }   

							echo "</tr>"; 
						}



                    }
                }	

        echo "</table>";
        $dbh->Cerrar();
        $dbh = NULL;

        
        
    }

function CargarGrilla1($Consulta,$Filtro)
	{
		require_once '../../config/db.php';

		
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