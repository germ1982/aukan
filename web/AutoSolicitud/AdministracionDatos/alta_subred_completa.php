<?php
    include_once '../Lib/db.php';
    include_once '../Lib/FuncionesComunes.php';
    $data = data_submitted();
    //print_object($data); 

    /* 
        VarSubRed debe ser ingesado manualmente en el url, y debe tener el diguiente formato "N1.N2.N3" donde N1, N2, y N3 son numeros que van del 1 al 255 
        No hay una validacion echa para verificar tales caracteristicas, por lo que esta carga debe hacerse minuciosamente por alguien idoneo.
        Ej del url: http://localhost/mds/web/AutoSolicitud/AdministracionDatos/alta_subred_completa.php?VarSubRed=10.1.73
        Eso daria de alta todas las ip de la subred 10.1.73
    */
    $subred = $data->VarSubRed; 

    if (VerificarExistencia($subred)==0)
        {

            /*
                $idcontacto debe setearse aca manualmente y debe ser un usuario generico que sea responsable por todas las ip libre a la hora del alta
                en el caso de mi base se setea con 17 que es el id de contacto del director de informatica
             */
            $idcontacto = 17;
            $idcontacto = null; //a pedido de juan

            /*
                $asignacion debe setearse aca manualmente y debe ser el id correspondiente al registro que en en eñ campo descripcion de la tabla sds_com_configuracion 
                se denomine Libre, y qee el campo idtipo pertenesca al tipo de configuracion TIPO_ASIGNACION_IP
                en el caso de mi base, se setea con 215.
             */
            $asignacion = 215;
            $asignacion = 1;// a pedido de juan

            $dbh = new BaseDatos();
            $dbh->Iniciar();

            for($i=1;$i<=255;$i++)
                {
                    if($idcontacto)
                        {
                            $consulta = "INSERT INTO sds_reg_ip (ip, subred, idcontacto, observaciones, asignacion) VALUES($i, '$subred', $idcontacto, '', $asignacion)"; 
                        }
                    else 
                        {
                            $consulta = "INSERT INTO sds_reg_ip (ip, subred, observaciones, asignacion) VALUES($i, '$subred', '', $asignacion)"; 
                        }
                    
                    echo "<br>$consulta";
                    $dbh->Ejecutar($consulta);
                }		

            $dbh->Cerrar();
            $dbh = NULL;
        }
    else 
        {
            echo "La subred $subred ya existe...";
        }

    


    function VerificarExistencia($subred)
        {

            $dbh = new BaseDatos();
            $dbh->Iniciar();
            
            $Consulta = "Select * from sds_reg_ip Where subred = '$subred'";
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