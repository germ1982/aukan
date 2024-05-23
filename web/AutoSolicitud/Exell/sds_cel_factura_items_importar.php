<?php
require_once '../../config/db.php';
//require_once '../Lib/FuncionesComunes.php';
//variables

$json = $_POST['VarJson'];
$idfactura = $_POST['VarIdFactura'];
$array = json_decode($json, true);


/* Las siguientes variables son nombradas asi acorde al tipo de dato que contienen, y
    guardan el nombre de la primera celda (fila) de la columna con el que se rescataron los datos de exel, 
	si el exel cambia esos nombres deben coregirse en las siguientes variables, ya que son las que se usan 
	para rescatar los datos que se deben enviar a la base de datossa, 
	si la columna no tiene texto las muestra con EMPTY 
    Esto debe hacerse cada vez que importo un exell para tener prevenido cualquier cambio*/
$columna_CUENTA = "CUENTA";
$columna_NRO_FACTURA = "NRO_FACTURA";
$columna_DESC_FACTURA = "DESC_FACTURA";
$columna_INICIO_CICLO = "INICIO_CICLO";
$columna_FIN_CICLO = "FIN_CICLO";
$columna_LINEA = "LINEA";
$columna_ID_PLAN = "ID_PLAN";
$columna_ID_CONCEPTO = "ID_CONCEPTO";
$columna_DESCRIPCION_CONCEPTO = "DESCRIPCION_CONCEPTO";
$columna_CANTIDAD = "CANTIDAD";
$columna_MONTO_NETO = "MONTO_NETO";
$columna_MONTO_IMPUESTOS = "MONTO_IMPUESTOS";
$columna_MONTO_TOTAL = "MONTO_TOTAL";

/*La variable ban va a servir para saber cuando una linea son datos a guardar, ya que 
muchos exel antes de empesar a mostrar la info tienen caratulados o titulos*/
$ban=1;//en este caso los exels arrancan de una
$anuncio_log = "";//aca guardo que va pasando con cada linea para mostrar al final
$cuenta = getDatosFactura($idfactura, 'cuenta');
$periodo_mes = getDatosFactura($idfactura, 'periodo_mes');
$periodo_anio = getDatosFactura($idfactura, 'periodo_anio');
$fondo = "<div>"; //esta variable la uso para ir cambiando el color del fondo del log y dejar el fondo intercalado
$cont = 1;
foreach ($array as $linea_exel) 
	{
        $cont = $cont+1;
		if($ban==1)//pregunta a $ban si la linea ya son datos a guardar
			{	
                if ($fondo =="<div>")
                    {$fondo = "<div style='background-color:#D5D3D3;'>";}
                else
                    {$fondo = "<div>";}

                $anuncio_log = "$anuncio_log $fondo";
                //verifico que los datos de la linea son iguales al periodo y cuenta
                /* Variables para verificar la cuenta y su periodo, si bien cada archivo trae solo esos registros
                puede pasar que erroneamente el usuario suba un exel con otra cuenta y otro periodo, 
                por lo que no esta demas comparar datos antes de guardar */
                $ban_guardar = 1;
                $cuenta_exel = $linea_exel["$columna_CUENTA"];

                if($cuenta_exel=='GLOSARIO')//quiere decir que ya termino y no debe seguir recorriendo las lineas
                    {
                        $ban_guardar=0;
                        $ban=2;
                    }
                else
                    {
                        //$periodo_mes_exel = substr($linea_exel["$columna_INICIO_CICLO"], 5,2);
                        $periodo_mes_exel = $linea_exel["$columna_INICIO_CICLO"];
                        $periodo_mes_exel = substr("$periodo_mes_exel",5,2);
                        $periodo_anio_exel = $linea_exel["$columna_INICIO_CICLO"];
                        $periodo_anio_exel = substr("$periodo_anio_exel",0,4); 
                        if(!($cuenta_exel==$cuenta))
                            {$ban_guardar=0;}
                        if(!($periodo_mes_exel==$periodo_mes))
                            {$ban_guardar=0;}
                        if(!($periodo_anio_exel==$periodo_anio))
                            {$ban_guardar=0;}
                    }
                    /*$anuncio_log = "$anuncio_log cont: $cont \n ban_guardar: $ban_guardar \n ban: $ban \n";
                    $anuncio_log = "$anuncio_log periodo mes exel: $periodo_mes_exel \n periodo mes: $periodo_mes \n";
                    $anuncio_log = "$anuncio_log periodo año exel: $periodo_anio_exel \n periodo año: $periodo_anio \n"; */
                if($ban_guardar==1)
                    {
                        //$anuncio_log = "$anuncio_log cuenta parametro: $cuenta \n cuenta exel: $cuenta_exel \n periodo_mes exel: $periodo_mes_exel \n periodo año exel: $periodo_anio_exel";
                        //Variables a guardar
                        if (isset($linea_exel["$columna_LINEA"]))
                            {$linea = $linea_exel["$columna_LINEA"];}
                        else
                            {$linea  = "";}
                        $concepto = $linea_exel["$columna_DESCRIPCION_CONCEPTO"];
                        $cantidad = $linea_exel["$columna_CANTIDAD"];
                        $neto = $linea_exel["$columna_MONTO_NETO"];
                        $impuestos = $linea_exel["$columna_MONTO_IMPUESTOS"];
                        $total = $linea_exel["$columna_MONTO_TOTAL"];
                        $idconcepto = $linea_exel["$columna_ID_CONCEPTO"];

                        $aux = GuardarItem($idfactura,$linea,$concepto,$cantidad,$neto,$impuestos,$total,$idconcepto);
						$anuncio_log = "$anuncio_log <p style='color:#008F47'>Linea $cont Guardada Correctamente...\n$aux</p>";
                    }
                else
                    {
                        if($ban==1)
                            {
                                $anuncio_log = "$anuncio_log <p style='color:red'>linea $cont con cuenta $cuenta_exel y periodo $periodo_mes_exel/$periodo_anio no se ha guardado \nRazón: no concuerdan con los datos indicadas en la factura a guardar...</p>";
                            }
                    }
                
				$anuncio_log = "$anuncio_log </div>";
                
			}
		


	}

//$anuncio_log = "entro bien";
//$anuncio_log = "cuenta: $cuenta \n periodo_mes: $periodo_mes \n periodo año: $periodo_anio";
//$anuncio_log = $json;
//$anuncio_log = var_dump($array);

$resultado = array("anuncio_log"=>"$anuncio_log");
    

echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 


function getDatosFactura($idfactura, $columna)
	{
 		require_once '../../config/db.php';
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$consulta = "SELECT v.cuenta, f.periodo_mes, f.periodo_anio 
        FROM mdsyt.sds_cel_factura as f 
        inner join apptelefonia.vista_integradora as v on f.cuenta = v.lineanro
        WHERE idfactura = $idfactura";
        $dato = '';
		
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
					$dato = $result["$columna"];
				}
			}	
			
		$dbh->Cerrar();
		$dbh = NULL; 
		return $dato;
	}
 
function GuardarItem($idfactura,$linea,$concepto,$cantidad,$neto,$impuestos,$total,$idconcepto)
        {
            require_once '../../config/db.php';
            $sql = "INSERT INTO sds_cel_factura_item (idfactura,linea,concepto,cantidad,neto,impuestos,total,idconcepto) values($idfactura,$linea,'$concepto',$cantidad,$neto,$impuestos,$total,'$idconcepto')";
            $dbh = new BaseDatos();
            $dbh->Iniciar();
            $dbh->Ejecutar($sql);
            $dbh->Cerrar();
            $dbh = NULL;
            return $sql;
        }


