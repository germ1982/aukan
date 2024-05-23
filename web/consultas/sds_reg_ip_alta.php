<?php
    //require_once '../AutoSolicitud/Lib/db.php';   
    require_once '../config/db.php'; 
    //include_once '../Lib/db.php';
    require_once '../AutoSolicitud/Lib/FuncionesComunes.php';

    $primer_valor_subred = $_POST['primer_valor_subred'];
    $segundo_valor_subred = $_POST['segundo_valor_subred'];
    $tercer_valor_subred = $_POST['tercer_valor_subred'];
    $valor_inicial_ip = $_POST['valor_inicial_ip'];
    $valor_final_ip = $_POST['valor_final_ip'];

    $subred = "$primer_valor_subred.$segundo_valor_subred.$tercer_valor_subred"; 

    //$idcontacto = null; 
    $asignacion = 1;
    $mensaje = "";

    $dbh = new BaseDatos();
    $dbh->Iniciar();

    for($i=$valor_inicial_ip;$i<=$valor_final_ip;$i++)
        {
            if (VerificarExistencia($subred,$i)==0)
                {
                    $consulta = "INSERT INTO sds_reg_ip (ip, subred, observaciones, asignacion) VALUES($i, '$subred','', $asignacion)"; 
                    //echo "<br>$consulta";
                    $dbh->Ejecutar($consulta);
                    $mensaje = "$mensaje <font color='green'> Ip: $subred.$i Guardada.</font><br>";
                }
            else 
                {
                    $mensaje = "$mensaje <font color='red'> Ip: $subred.$i ya existe.</font><br>";
                }
            

        }		

    $dbh->Cerrar();
    $dbh = NULL;


//echo "$mensaje";
$resultado = array("anuncio"=>"$mensaje");
echo json_encode($resultado);


function VerificarExistencia($subred,$ip)
    {

        $dbh = new BaseDatos();
        $dbh->Iniciar();
        
        $Consulta = "Select * from sds_reg_ip Where subred = '$subred' and ip = $ip";
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
                    $aux = $result['subred'];
                    if ($aux==$subred)
                        {
                            $ban = 1;
                        }
                }	
            }	
                    
        $dbh->Cerrar();
        $dbh = NULL;
        return $ban;

    }