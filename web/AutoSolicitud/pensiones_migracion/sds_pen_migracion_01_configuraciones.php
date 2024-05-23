<?php 
	require_once '../Lib/db.php';
    $TIPO_NACIONALIDAD = 12;
    $TIPO_GENERO = 13;
    $TIPO_TIPO_DOC = 14;
    $TIPO_PENSION_ESTADO = 74;
    $TIPO_PENSION_LUGAR_DE_PAGO = 75;
    $TIPO_PENSION_TIPO_OTORGADO = 76;
    $TIPO_PENSION_TIPO_BAJA = 77;
    $TIPO_PENSION_CAUSA_BAJA = 78;
    $TIPO_PENSION_PROGRAMA = 79;

    $CONFIG_DNI = get_id_configuracion('1. DNI',$TIPO_TIPO_DOC);
    $CONFIG_LE = get_id_configuracion('2. Libreta de Enrolamiento',$TIPO_TIPO_DOC);
    $CONFIG_LC = get_id_configuracion('3. Libreta Cívica',$TIPO_TIPO_DOC);
    $CONFIG_CE = get_id_configuracion('6. Cedula extranjera',$TIPO_TIPO_DOC);
    $CONFIG_CI = get_id_configuracion('7. Cedula de Identidad',$TIPO_TIPO_DOC);

    $CONFIG_ARGENTINA = get_id_configuracion('1. Argentina',$TIPO_NACIONALIDAD);
    $CONFIG_BOLIVIA = get_id_configuracion('2. Bolivia',$TIPO_NACIONALIDAD);
    $CONFIG_BRASIL = get_id_configuracion('3. Brasil',$TIPO_NACIONALIDAD);
    $CONFIG_CHILE = get_id_configuracion('4. Chile',$TIPO_NACIONALIDAD);
    $CONFIG_PARAGUAY = get_id_configuracion('7. Paraguay',$TIPO_NACIONALIDAD);
    $CONFIG_OTRO = get_id_configuracion('99. Otro',$TIPO_NACIONALIDAD);

    $CONFIG_M = get_id_configuracion('Masculino',$TIPO_GENERO);
    $CONFIG_F = get_id_configuracion('Femenino ',$TIPO_GENERO);

    $CONFIG_DISCAPACIDAD = get_id_configuracion('Discapacidad',$TIPO_PENSION_PROGRAMA);
    $CONFIG_VEJEZ = get_id_configuracion('Vejez ',$TIPO_PENSION_PROGRAMA);

    $CONFIG_DECRETO = get_id_configuracion('Decreto',$TIPO_PENSION_TIPO_OTORGADO);
    $CONFIG_RESMIN = get_id_configuracion('Res.Min.',$TIPO_PENSION_TIPO_OTORGADO);
    $CONFIG_TRAMITE = get_id_configuracion('Tramite',$TIPO_PENSION_TIPO_OTORGADO);

    $CONFIG_TIPO_BAJA_DECRETO = get_id_configuracion('Decreto',$TIPO_PENSION_TIPO_BAJA);
    $CONFIG_TIPO_BAJA_DISPOCISION = get_id_configuracion('Dispocision',$TIPO_PENSION_TIPO_BAJA);
    $CONFIG_TIPO_BAJA_RESMIN = get_id_configuracion('Res.Min.',$TIPO_PENSION_TIPO_BAJA);
    $CONFIG_TIPO_BAJA_SDECRETO = get_id_configuracion('S/decreto',$TIPO_PENSION_TIPO_BAJA);
    $CONFIG_TIPO_BAJA_TRAMITE = get_id_configuracion('Tramite',$TIPO_PENSION_TIPO_BAJA);

    $CONFIG_TIPO_ESTADO_BAJA = get_id_configuracion('Baja',$TIPO_PENSION_ESTADO);
    $CONFIG_TIPO_ESTADO_BAJA_ISSN = get_id_configuracion('Baja Cond.(ISSN)',$TIPO_PENSION_ESTADO);
    $CONFIG_TIPO_ESTADO_BAJA_INTERNA = get_id_configuracion('Baja Interna',$TIPO_PENSION_ESTADO);
    $CONFIG_TIPO_ESTADO_OTORGADO = get_id_configuracion('Otorgado',$TIPO_PENSION_ESTADO);
    $CONFIG_TIPO_ESTADO_TRAMITE = get_id_configuracion('Tramite',$TIPO_PENSION_ESTADO);

    $CONFIG_LUGAR_PAGO_EL_CHOCON=  get_id_configuracion('EL CHOCON',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_AGUADA_SAN_ROQUE=  get_id_configuracion('AGUADA SAN ROQUE',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_ALUMINE=  get_id_configuracion('ALUMINE',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_ANDACOLLO=  get_id_configuracion('ANDACOLLO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_BAJADA_DEL_AGRIO=  get_id_configuracion('BAJADA DEL AGRIO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_BARRANCAS=  get_id_configuracion('BARRANCAS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_BUTA_RANQUIL=  get_id_configuracion('BUTA RANQUIL',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_CENTENARIO=  get_id_configuracion('CENTENARIO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_CHAPUA=  get_id_configuracion('CHAPUA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_CHORRIACA=  get_id_configuracion('CHORRIACA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_CHOS_MALAL=  get_id_configuracion('CHOS MALAL',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_COLIPILLI=  get_id_configuracion('COLIPILLI',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_COVUNCO_ABAJO=  get_id_configuracion('COVUNCO ABAJO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_COYUCO_COCHICO=  get_id_configuracion('COYUCO COCHICO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_CUTRAL_CO=  get_id_configuracion('CUTRAL-CO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_EL_CHOLAR=  get_id_configuracion('EL CHOLAR',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_EL_HUECU=  get_id_configuracion('EL HUECU',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_EL_SAUCE=  get_id_configuracion('EL SAUCE',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_HUINGANCO=  get_id_configuracion('HUINGANCO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_JUNIN_DE_LOS_ANDES=  get_id_configuracion('JUNIN DE LOS ANDES',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LAS_COLORADAS=  get_id_configuracion('LAS COLORADAS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LAS_LAJAS=  get_id_configuracion('LAS LAJAS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LAS_OVEJAS=  get_id_configuracion('LAS OVEJAS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LONCOPUE=  get_id_configuracion('LONCOPUE',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LOS_CHIHUIDOS=  get_id_configuracion('LOS CHIHUIDOS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LOS_GUAÑACOS=  get_id_configuracion('LOS GUAÑACOS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_LOS_MICHES=  get_id_configuracion('LOS MICHES',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_MANZANO_AMARGO=  get_id_configuracion('MANZANO AMARGO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_MARIANO_MORENO=  get_id_configuracion('MARIANO MORENO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_NEUQUEN_SUBSECRETARIA_DE_ACCION_SOCIAL=  get_id_configuracion('NEUQUEN - SUBSECRETARIA DE ACCION SOCIAL',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_AEROPUERTO=  get_id_configuracion('NEUQUEN - SUCURSAL AEROPUERTO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_ALTA_BARDA=  get_id_configuracion('NEUQUEN - SUCURSAL ALTA BARDA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_BELGRANO=  get_id_configuracion('NEUQUEN - SUCURSAL BELGRANO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_FELIX_SAN_MARTIN=  get_id_configuracion('NEUQUEN-SUCURSAL FELIX SAN MARTIN',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_RIVADAVIA=  get_id_configuracion('NEUQUEN-SUCURSAL RIVADAVIA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_OCTAVIO_PICO=  get_id_configuracion('OCTAVIO PICO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_PASO_AGUERRE=  get_id_configuracion('PASO AGUERRE',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_PICUN_LEUFU=  get_id_configuracion('PICUN LEUFU',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_PIEDRA_DEL_AGUILA=  get_id_configuracion('PIEDRA DEL AGUILA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_PLAZA_HUICUL=  get_id_configuracion('PLAZA HUICUL',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_PLOTTIER=  get_id_configuracion('PLOTTIER',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_RINCON_DE_LOS_SAUCES=  get_id_configuracion('RINCON DE LOS SAUCES',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_SAN_MARTIN_DE_LOS_ANDES=  get_id_configuracion('SAN MARTIN DE LOS ANDES',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_SAN_PATRICIO_DEL_CHAÑAR=  get_id_configuracion('SAN PATRICIO DEL CHAÑAR',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_SANTO_TOMAS=  get_id_configuracion('SANTO TOMAS',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_SAUZAL_BONITO=  get_id_configuracion('SAUZAL BONITO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_SENILLOSA=  get_id_configuracion('SENILLOSA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_TAQUIMILAN=  get_id_configuracion('TAQUIMILAN',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_TRICAO_MALAL=  get_id_configuracion('TRICAO MALAL',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_VARVARCO=  get_id_configuracion('VARVARCO',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_VILLA_CARILEUVU=  get_id_configuracion('VILLA CARILEUVU',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_VILLA_DEL_NAHUEVE=  get_id_configuracion('VILLA DEL NAHUEVE',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_VILLA_LA_ANGOSTURA=  get_id_configuracion('VILLA LA ANGOSTURA',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_VILLA_TRAFUL=  get_id_configuracion('VILLA TRAFUL',$TIPO_PENSION_LUGAR_DE_PAGO);
    $CONFIG_LUGAR_PAGO_ZAPALA=  get_id_configuracion('ZAPALA',$TIPO_PENSION_LUGAR_DE_PAGO);

    

    
    $resultado = array(
                            "CONFIG_DNI"=>"$CONFIG_DNI",
                            "CONFIG_LE"=>"$CONFIG_LE",
                            "CONFIG_LC"=>"$CONFIG_LC",
                            "CONFIG_CE"=>"$CONFIG_CE",
                            "CONFIG_CI"=>"$CONFIG_CI",

                            "CONFIG_ARGENTINA"=>"$CONFIG_ARGENTINA",
                            "CONFIG_BOLIVIA"=>"$CONFIG_BOLIVIA",
                            "CONFIG_BRASIL"=>"$CONFIG_BRASIL",
                            "CONFIG_CHILE"=>"$CONFIG_CHILE",
                            "CONFIG_PARAGUAY"=>"$CONFIG_PARAGUAY",
                            "CONFIG_OTRO"=>"$CONFIG_OTRO",

                            "CONFIG_M"=>"$CONFIG_M",
                            "CONFIG_F"=>"$CONFIG_F",

                            "CONFIG_DISCAPACIDAD"=>"$CONFIG_DISCAPACIDAD",
                            "CONFIG_VEJEZ"=>"$CONFIG_VEJEZ",

                            "CONFIG_DECRETO"=>"$CONFIG_DECRETO",
                            "CONFIG_RESMIN"=>"$CONFIG_RESMIN",
                            "CONFIG_TRAMITE"=>"$CONFIG_TRAMITE",

                            "CONFIG_TIPO_BAJA_DECRETO"=>"$CONFIG_TIPO_BAJA_DECRETO",
                            "CONFIG_TIPO_BAJA_DISPOCISION"=>"$CONFIG_TIPO_BAJA_DISPOCISION",
                            "CONFIG_TIPO_BAJA_RESMIN"=>"$CONFIG_TIPO_BAJA_RESMIN",
                            "CONFIG_TIPO_BAJA_SDECRETO"=>"$CONFIG_TIPO_BAJA_SDECRETO",
                            "CONFIG_TIPO_BAJA_TRAMITE"=>"$CONFIG_TIPO_BAJA_TRAMITE",

                            "CONFIG_TIPO_ESTADO_BAJA"=>"$CONFIG_TIPO_ESTADO_BAJA",
                            "CONFIG_TIPO_ESTADO_BAJA_ISSN"=>"$CONFIG_TIPO_ESTADO_BAJA_ISSN",
                            "CONFIG_TIPO_ESTADO_BAJA_INTERNA"=>"$CONFIG_TIPO_ESTADO_BAJA_INTERNA",
                            "CONFIG_TIPO_ESTADO_OTORGADO"=>"$CONFIG_TIPO_ESTADO_OTORGADO",
                            "CONFIG_TIPO_ESTADO_TRAMITE"=>"$CONFIG_TIPO_ESTADO_TRAMITE",

                            "CONFIG_LUGAR_PAGO_EL_CHOCON"=>"$CONFIG_LUGAR_PAGO_EL_CHOCON",
                            "CONFIG_LUGAR_PAGO_AGUADA_SAN_ROQUE"=>"$CONFIG_LUGAR_PAGO_AGUADA_SAN_ROQUE",
                            "CONFIG_LUGAR_PAGO_ALUMINE"=>"$CONFIG_LUGAR_PAGO_ALUMINE",
                            "CONFIG_LUGAR_PAGO_ANDACOLLO"=>"$CONFIG_LUGAR_PAGO_ANDACOLLO",
                            "CONFIG_LUGAR_PAGO_BAJADA_DEL_AGRIO"=>"$CONFIG_LUGAR_PAGO_BAJADA_DEL_AGRIO",
                            "CONFIG_LUGAR_PAGO_BARRANCAS"=>"$CONFIG_LUGAR_PAGO_BARRANCAS",
                            "CONFIG_LUGAR_PAGO_BUTA_RANQUIL"=>"$CONFIG_LUGAR_PAGO_BUTA_RANQUIL",
                            "CONFIG_LUGAR_PAGO_CENTENARIO"=>"$CONFIG_LUGAR_PAGO_CENTENARIO",
                            "CONFIG_LUGAR_PAGO_CHAPUA"=>"$CONFIG_LUGAR_PAGO_CHAPUA",
                            "CONFIG_LUGAR_PAGO_CHORRIACA"=>"$CONFIG_LUGAR_PAGO_CHORRIACA",
                            "CONFIG_LUGAR_PAGO_CHOS_MALAL"=>"$CONFIG_LUGAR_PAGO_CHOS_MALAL",
                            "CONFIG_LUGAR_PAGO_COLIPILLI"=>"$CONFIG_LUGAR_PAGO_COLIPILLI",
                            "CONFIG_LUGAR_PAGO_COVUNCO_ABAJO"=>"$CONFIG_LUGAR_PAGO_COVUNCO_ABAJO",
                            "CONFIG_LUGAR_PAGO_COYUCO_COCHICO"=>"$CONFIG_LUGAR_PAGO_COYUCO_COCHICO",
                            "CONFIG_LUGAR_PAGO_CUTRAL_CO"=>"$CONFIG_LUGAR_PAGO_CUTRAL_CO",
                            "CONFIG_LUGAR_PAGO_EL_CHOLAR"=>"$CONFIG_LUGAR_PAGO_EL_CHOLAR",
                            "CONFIG_LUGAR_PAGO_EL_HUECU"=>"$CONFIG_LUGAR_PAGO_EL_HUECU",
                            "CONFIG_LUGAR_PAGO_EL_SAUCE"=>"$CONFIG_LUGAR_PAGO_EL_SAUCE",
                            "CONFIG_LUGAR_PAGO_HUINGANCO"=>"$CONFIG_LUGAR_PAGO_HUINGANCO",
                            "CONFIG_LUGAR_PAGO_JUNIN_DE_LOS_ANDES"=>"$CONFIG_LUGAR_PAGO_JUNIN_DE_LOS_ANDES",
                            "CONFIG_LUGAR_PAGO_LAS_COLORADAS"=>"$CONFIG_LUGAR_PAGO_LAS_COLORADAS",
                            "CONFIG_LUGAR_PAGO_LAS_LAJAS"=>"$CONFIG_LUGAR_PAGO_LAS_LAJAS",
                            "CONFIG_LUGAR_PAGO_LAS_OVEJAS"=>"$CONFIG_LUGAR_PAGO_LAS_OVEJAS",
                            "CONFIG_LUGAR_PAGO_LONCOPUE"=>"$CONFIG_LUGAR_PAGO_LONCOPUE",
                            "CONFIG_LUGAR_PAGO_LOS_CHIHUIDOS"=>"$CONFIG_LUGAR_PAGO_LOS_CHIHUIDOS",
                            "CONFIG_LUGAR_PAGO_LOS_GUAÑACOS"=>"$CONFIG_LUGAR_PAGO_LOS_GUAÑACOS",
                            "CONFIG_LUGAR_PAGO_LOS_MICHES"=>"$CONFIG_LUGAR_PAGO_LOS_MICHES",
                            "CONFIG_LUGAR_PAGO_MANZANO_AMARGO"=>"$CONFIG_LUGAR_PAGO_MANZANO_AMARGO",
                            "CONFIG_LUGAR_PAGO_MARIANO_MORENO"=>"$CONFIG_LUGAR_PAGO_MARIANO_MORENO",
                            "CONFIG_LUGAR_PAGO_NEUQUEN_SUBSECRETARIA_DE_ACCION_SOCIAL"=>"$CONFIG_LUGAR_PAGO_NEUQUEN_SUBSECRETARIA_DE_ACCION_SOCIAL",
                            "CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_AEROPUERTO"=>"$CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_AEROPUERTO",
                            "CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_ALTA_BARDA"=>"$CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_ALTA_BARDA",
                            "CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_BELGRANO"=>"$CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_BELGRANO",
                            "CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_FELIX_SAN_MARTIN"=>"$CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_FELIX_SAN_MARTIN",
                            "CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_RIVADAVIA"=>"$CONFIG_LUGAR_PAGO_NEUQUEN_SUCURSAL_RIVADAVIA",
                            "CONFIG_LUGAR_PAGO_OCTAVIO_PICO"=>"$CONFIG_LUGAR_PAGO_OCTAVIO_PICO",
                            "CONFIG_LUGAR_PAGO_PASO_AGUERRE"=>"$CONFIG_LUGAR_PAGO_PASO_AGUERRE",
                            "CONFIG_LUGAR_PAGO_PICUN_LEUFU"=>"$CONFIG_LUGAR_PAGO_PICUN_LEUFU",
                            "CONFIG_LUGAR_PAGO_PIEDRA_DEL_AGUILA"=>"$CONFIG_LUGAR_PAGO_PIEDRA_DEL_AGUILA",
                            "CONFIG_LUGAR_PAGO_PLAZA_HUICUL"=>"$CONFIG_LUGAR_PAGO_PLAZA_HUICUL",
                            "CONFIG_LUGAR_PAGO_PLOTTIER"=>"$CONFIG_LUGAR_PAGO_PLOTTIER",
                            "CONFIG_LUGAR_PAGO_RINCON_DE_LOS_SAUCES"=>"$CONFIG_LUGAR_PAGO_RINCON_DE_LOS_SAUCES",
                            "CONFIG_LUGAR_PAGO_SAN_MARTIN_DE_LOS_ANDES"=>"$CONFIG_LUGAR_PAGO_SAN_MARTIN_DE_LOS_ANDES",
                            "CONFIG_LUGAR_PAGO_SAN_PATRICIO_DEL_CHAÑAR"=>"$CONFIG_LUGAR_PAGO_SAN_PATRICIO_DEL_CHAÑAR",
                            "CONFIG_LUGAR_PAGO_SANTO_TOMAS"=>"$CONFIG_LUGAR_PAGO_SANTO_TOMAS",
                            "CONFIG_LUGAR_PAGO_SAUZAL_BONITO"=>"$CONFIG_LUGAR_PAGO_SAUZAL_BONITO",
                            "CONFIG_LUGAR_PAGO_SENILLOSA"=>"$CONFIG_LUGAR_PAGO_SENILLOSA",
                            "CONFIG_LUGAR_PAGO_TAQUIMILAN"=>"$CONFIG_LUGAR_PAGO_TAQUIMILAN",
                            "CONFIG_LUGAR_PAGO_TRICAO_MALAL"=>"$CONFIG_LUGAR_PAGO_TRICAO_MALAL",
                            "CONFIG_LUGAR_PAGO_VARVARCO"=>"$CONFIG_LUGAR_PAGO_VARVARCO",
                            "CONFIG_LUGAR_PAGO_VILLA_CARILEUVU"=>"$CONFIG_LUGAR_PAGO_VILLA_CARILEUVU",
                            "CONFIG_LUGAR_PAGO_VILLA_DEL_NAHUEVE"=>"$CONFIG_LUGAR_PAGO_VILLA_DEL_NAHUEVE",
                            "CONFIG_LUGAR_PAGO_VILLA_LA_ANGOSTURA"=>"$CONFIG_LUGAR_PAGO_VILLA_LA_ANGOSTURA",
                            "CONFIG_LUGAR_PAGO_VILLA_TRAFUL"=>"$CONFIG_LUGAR_PAGO_VILLA_TRAFUL",
                            "CONFIG_LUGAR_PAGO_ZAPALA"=>"$CONFIG_LUGAR_PAGO_ZAPALA",


                        );
    echo json_encode($resultado);


    function get_id_configuracion($descripcion,$tipo)
        {
            $consulta = "select * from sds_com_configuracion where descripcion = '$descripcion' and idconfiguraciontipo = $tipo";
            $dbh = new BaseDatos();
            $dbh->Iniciar();
            $result = $dbh->Select($consulta);
            $ban=0;
            if (!$result) 
                {
                    echo "<p>Error en la consulta.</p>"; 
                }
            else 
                {
                    if ($result = $dbh->Registro())
                    {
                        $ban = $result['idconfiguracion'];
                    }
                    else 
                    {
                        $ban = alta_configuracion($descripcion,$tipo);
                    }
                }	
            $dbh->Cerrar();
            $dbh = NULL;
            return $ban;
        }


    function alta_configuracion($descripcion,$tipo)
        {
            $consulta = "INSERT INTO sds_com_configuracion(idconfiguraciontipo,descripcion,activo) VALUES ($tipo, '$descripcion', 1)";
            $dbh = new BaseDatos();
            $dbh->Iniciar();
            $dbh->Ejecutar($consulta);
            $dbh->Cerrar();
            $dbh = NULL;
            return get_id_configuracion($descripcion,$tipo);
        }  
	
?>