<?php
use Da\QrCode\QrCode;
$id_inscripcion= $_GET['id2'];
$meses=['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_persona;
use app\models\Sds_com_persona;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_instancia;
//$una_cap_inscripcion=Mds_cap_inscripcion::findOne($id_inscripcion);

function sanear_string($string)
{

    $string = trim($string);

    $string = str_replace(
        array('à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('ñ', 'ñ', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("¨", "º", "-", "~",
             "#", "@", "|", "!", '"', "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             "."),
        '',
        $string
    );


    return $string;
}
$una_cap_inscripcion=Mds_cap_inscripcion::find()                                 
->where(['idinscripcion' => $id_inscripcion])
->one(); 

$una_cap_persona=Mds_cap_persona::find()                                 
->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
->one(); 

$una_com_persona=Sds_com_persona::find()                                 
->where(['idpersona' => $una_cap_persona->idpersona])
->one();

$una_cap_instancia=Mds_cap_instancia::find()                                 
->where(['idinstancia' => $una_cap_inscripcion->idcapinstancia])
->one();  

$una_cap_capacitacion=Mds_cap_capacitacion::find()                                 
->where(['idcapacitacion' => $una_cap_instancia->idcapacitacion])
->one(); 

$nombre_per2=sanear_string(ucwords(strtolower($una_com_persona->nombre)));
$apellido_per2=sanear_string(ucwords(strtolower($una_com_persona->apellido)));

$cad_name=$nombre_per2.' '.$apellido_per2;

//$cad_name=$una_com_persona->nombre.' '.$una_com_persona->apellido;
$dni = number_format($una_com_persona->documento, 0, '', '.');
$titulo_cap=$una_cap_capacitacion->descripcion;
$aval=$una_cap_instancia->resolucion_aval;
$no_tiene_aval=(($aval==null) || ($aval==""));
$area_certificado=$una_cap_instancia->area_certificado;
$modalidad=$una_cap_instancia->presencial;

if ($modalidad==0){ $modalidad="presencial";}
else{  if ($modalidad==1){ $modalidad="virtual";}  
else {if ($modalidad==2){ $modalidad="dual";} }}
$cant_horas=$una_cap_instancia->cant_horas;
$no_tiene_horas=(($cant_horas==null) || ($cant_horas=="0"));
$desde=$una_cap_instancia->desde;
$unafecha = explode ("-",$desde);
$mes_desde=intval( trim($unafecha[1]) );

$anio_desde=trim($unafecha[0]);
$dia_desde=trim($unafecha[2]);
$el_mes_desde=$meses[$mes_desde-1];

$hasta=$una_cap_instancia->hasta;
$unafecha = explode ("-",$hasta);
$hasta= trim($hasta[2])."/".trim($hasta[1])."/".trim($hasta[0]);  
$mes_hasta=intval( trim($unafecha[1]) );

$anio_hasta=trim($unafecha[0]);
$dia_hasta=trim($unafecha[2]);
$el_mes_hasta=$meses[$mes_hasta-1];

if ($anio_desde==$anio_hasta)
{
	$intervalo=" desde ".$dia_desde." de ".$el_mes_desde." al ".$dia_hasta." de ".$el_mes_hasta." del año ".$anio_desde;
}
else
{
	$intervalo=" desde ".$dia_desde." de ".$el_mes_desde." del año ".$anio_desde." al ".$dia_hasta." de ".$el_mes_hasta." del año ".$anio_hasta;
}

$logo_extra=$una_cap_instancia->logo_extra;
    if ($una_cap_instancia->logo_extra_path==null)
    {
        $logo_extra=$una_cap_instancia->logo_extra;
    }
    else
    { 
        $logo_extra='../web/uploads/instancias/'.$una_cap_instancia->logo_extra_path ;

    }
$qrCode = (new QrCode("{idinscripcion=$una_cap_inscripcion->idinscripcion}"))
    ->setSize(50)
    ->setMargin(5)
    ->useForegroundColor(2, 2, 2);
?>
<html>
<?php //echo "<script type='text/javascript'>krajeeDialog.alert('probando!');</script>"; ?>
<body> 	
<?php
	if (($logo_extra==null) || ($logo_extra=="")){}
	else
	{
		echo '
			<div class="div_divisor" >	 
				<img class="img_divisor" src="../web/img/separador.png">
			</div>
			<div class="div_logo_extra" >
				<img src="'.$logo_extra.'"  style="height:88px;">
			</div>';
	}
?>
<p class="header1" >CERTIFICADO <?= $id_inscripcion;?></p>
<p class="parrafo1">Por cuanto se Certifica que:</p>
<p class="nombre"><?php echo $cad_name; ?></p>
<p class="dni">D.N.I.: <?= $dni?></p>
<p class="cad_aprob">ha aprobado</p>
<p class="curso"><?= $titulo_cap?></p>
<div style="position:absolute;top: 672px; left: 186px;">
    <img src="<?= $qrCode->writeDataUri();?>"  class="qr" >
</div>
<?php
	if ($no_tiene_aval){}
	else
	{
		echo '<p class="aval">Avalado por '.$aval.'</p>';
	}
?>

<p class="<?php if ($no_tiene_aval){echo 'organizado_por_2';}else {echo 'organizado_por_1';}?>" > Organizado por <?= $area_certificado;?>, <br>	
	en modalidad <?= $modalidad; ?>, <?= $intervalo?>';
	
<?php
	if ($no_tiene_horas){}
	else
	{
		echo ', <br>  con una duración de '.$cant_horas.' horas reloj con evaluacion final.-';

	}
?>
	
</p>
</body>

</html>
