<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$ip = $_GET['ip_addr'];

        $ruta = get_ruta_camara($ip);
        //echo "Ruta: $ruta<br>";
        $ban = 2;
        $contador=0;
        $minutitos = 61;
        $aux = '';
        //return "ip: $ip \n Ruta: $ruta";
        if($ruta!='')
            {
                $ban = 1;
                if($elementos = opendir($ruta))
                    {
                        while($elemento = readdir($elementos))
                            {
                                if(!($elemento=='.' || $elemento=='..'))
                                    {
                                        if(!(is_dir("$ruta\\$elemento")))
                                            {
                                                $contador++;
                                                $fecha_creacion = date("Y/m/d H:i:s", filectime("$ruta\\$elemento"));
                                                $fecha_actual = date("Y/m/d H:i:s");
                                                $minutos = minutosTranscurridos($fecha_creacion,$fecha_actual);
                                                //echo "minutos: $minutos<br>";
                                                if($minutos < $minutitos)
                                                    {
                                                        $minutitos = $minutos;
                                                    }
                                                if($minutitos>60)
                                                    {
                                                        $ban = 0;
                                                    }
                                                $aux = "$aux $contador: Actual:($fecha_actual), Creacion($fecha_creacion) - minutos transcurridos: $minutos, lapso mas chico: $minutitos<br>";
                                            }

                                    }
                            }
                            closedir($elementos);
                    }
                    
            }
        
            echo "ruta: $ruta <br> $aux <br> ban: $ban";

            $resultado = array("respuesta"=>"$ban");
            print json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 


function get_ruta_camara($ip)
    {
        $aux = '';
        $ruta = '\\\10.1.73.243\P\ip cam\RecordFile\\'.date("Y").date("m").date("d");
        $elementos = opendir($ruta);
        $ban = 0;
        while(($elemento = readdir($elementos))!==false)
            {
                if(!($elemento=='.' || $elemento=='..'))
                    {
                        if(is_dir("$ruta\\$elemento"))
                            {
                                //echo "<br>$elemento<br>";
                                if($ban==0)
                                    {
                                        if(strpos($elemento,$ip)===false)
                                            {
                                                $aux = '';  
                                            }
                                        else
                                            {
                                                $aux = "$ruta\\$elemento";
                                                $ban = 1;
                                                //echo "<br>$aux<br>";
                                            }
                                    }
                            }
                    }
            }
        return $aux;
    }

function minutosTranscurridos($fecha_i,$fecha_f)
{
    $minutos = (strtotime($fecha_i)-strtotime($fecha_f))/60;
    $minutos = abs($minutos); $minutos = floor($minutos);
    return $minutos;
}

?>