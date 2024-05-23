<?php
use Da\QrCode\QrCode;
$id = $_GET['id'];

$meses=['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_persona;
use app\models\Sds_com_persona;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_instancia;
use app\models\Mds_cap_docente_instancia;
use app\models\Mds_cap_docente;
use app\models\Sds_com_configuracion;

  
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

$una_cap_inscripcion=Mds_cap_inscripcion::findOne($id);
$estado_finalizacion=$una_cap_inscripcion->termino;


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
$nombre_per1=sanear_string(ucwords(strtolower($una_com_persona->nombre)));
$apellido_per1=sanear_string(ucwords(strtolower($una_com_persona->apellido)));
$cad_name=$nombre_per1.' '.$apellido_per1;
//$cad_name=ucwords(strtolower($una_com_persona->nombre)).' '.ucwords(strtolower($una_com_persona->apellido));
$dni = number_format($una_com_persona->documento, 0, '', '.');
$titulo_cap=$una_cap_capacitacion->descripcion;
$aval=$una_cap_instancia->resolucion_aval;
$no_tiene_aval=(($aval==null) || ($aval==""));
$area_certificado=$una_cap_instancia->area_certificado;

$res_area=(($una_cap_instancia->area_certificado==null) || ($una_cap_instancia->area_certificado==""));
if ($res_area )
{ $area_certificado.=" 'No se definió el área interviniente en la instancia' ";}
			

$modalidad=$una_cap_instancia->presencial;

if ($modalidad==0){ $modalidad="presencial";}
else{  if ($modalidad==1){ $modalidad="virtual";}  
else {if ($modalidad==2){ $modalidad="dual";} }}
$cant_horas=$una_cap_instancia->cant_horas;

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


if($una_cap_instancia->desde==$una_cap_instancia->hasta)
{
	$intervalo = " el día " . $dia_desde ." de " . $el_mes_hasta . " del año " . $anio_desde;

}
else
{
	if ($anio_desde==$anio_hasta)
	{
		$intervalo=" desde ".$dia_desde." de ".$el_mes_desde." al ".$dia_hasta." de ".$el_mes_hasta." del año ".$anio_desde;
	}
	else
	{
		$intervalo=" desde ".$dia_desde." de ".$el_mes_desde." del año ".$anio_desde." al ".$dia_hasta." de ".$el_mes_hasta." del año ".$anio_hasta;
	}

}





$logo_extra=$una_cap_instancia->logo_extra;
$logo_principal=$una_cap_instancia->logo_principal;
$codigo_qr=md5($una_cap_inscripcion->idinscripcion);
$qrCode = (new QrCode("https://cumbre.neuquen.gov.ar/validator?codigo=".$codigo_qr))
    ->setSize(100)
    ->setMargin(0)
	->useForegroundColor(2, 2, 2);
$el_model = Mds_cap_inscripcion::findOne($id);
$el_model->codigo_qr=$codigo_qr;	                                      
$el_model->save();
$roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $una_cap_inscripcion->idcapinstancia, 'firmante' => 1])->all();
$firmas_nombre = array();
$firmas_cargo = array();
$firmas_iddocente = array();
foreach ($roles as $rol) {
	$el_docente = Mds_cap_docente::findOne($rol['id_docente']);
	$per_doc = Sds_com_persona::findOne($el_docente['idpersona']);
	if (($el_docente['profesion_corta']!=null) && ($el_docente['profesion_corta']!=''))
	{
		$id_profesion=$el_docente['profesion_corta'];
		$la_profesion = Sds_com_configuracion::findOne($id_profesion);
		$nombre_per=sanear_string(ucwords(strtolower($per_doc['nombre'])));
		$apellido_per=sanear_string(ucwords(strtolower($per_doc['apellido'])));
		$cad_nombres_apel=$nombre_per.' '.$apellido_per;
		$firmas_nombre[] =$la_profesion['descripcion']." ". $cad_nombres_apel;
	}
	else
	{   $cad_nombres_apel=ucwords(strtolower($per_doc['nombre'])).' '.ucwords(strtolower($per_doc['apellido']));
		$firmas_nombre[] =$cad_nombres_apel;
	
	}
	
	$firmas_cargo[] = $el_docente['cargo_certificado'];
	$firmas_iddocente[]=$rol['id_docente'];
}
$num_firmas=count($firmas_nombre);
?>
<html>

<body> 	
<?php
	if (($logo_extra==null) || ($logo_extra==""))
	{
		if (($logo_principal==null) || ($logo_principal==""))
		{
			echo '
			<div class="div_banner2" >	 
				<img class="img_banner1" src="../web/img/banner2.png">
			</div>';
		}
		else
		{
			echo '
			<div class="div_banner2" >	 
				<img class="img_banner1" src="'.$logo_principal.'">
			</div>';
		}
			
	}
	else
	{
		if (($logo_principal==null) || ($logo_principal==""))
		{
			echo '
			<div class="div_banner1" >	 
				<img class="img_banner1" src="../web/img/banner1.png">
			</div>
			<div class="div_logo_extra" >
				<img   class="img_logoextra" src="'.$logo_extra.'" >
			</div>';

		}
		else
		{
			echo '
			<div class="div_banner1" >	 
				<img class="img_banner1" src="'.$logo_principal.'">
			</div>
			<div class="div_logo_extra" >
				<img   class="img_logoextra" src="'.$logo_extra.'" >
			</div>';
		}
		

	}
?>
	

<p class="header1" >CERTIFICADO</p>
<!--<p class="parrafo1">Por cuanto se Certifica que:</p>-->
<p class="nombre"><?php echo $cad_name; ?></p>
<p class="dni">D.N.I.: <?= $dni?></p>
<?php
	$tit_br = strpos($titulo_cap, '<br>');		
?>
<?php
	echo '<p class="';
	if ($tit_br==false){ echo 'cad_aprob';}
	else {echo 'cad_aprob_br';}
	echo '">';
?>

	<?php 
	if ($estado_finalizacion==0)
	{echo "se ha inscripto";}
	else
	{ 
		if($estado_finalizacion==1)
		{echo "esta cursando";}
		else 
		{
			if($estado_finalizacion==2)
			{
				echo "ha aprobado";
			}
			else
			{
				if($estado_finalizacion==3)
				{
					echo "ha desaprobado";
				}
				else
				{

					if($estado_finalizacion==6)
					{
						echo "ha participado de ";
					}
				}
			}
		} 
	}
	?></p>

   
	<?php
		echo '<p class="';
		
		if ($tit_br==false){ echo 'curso';}
		else {echo 'curso_br';}
		echo '">'.$titulo_cap.'</p>';
	?>

<div style="position:absolute;top: 665px; left: 47px;">
    <img src="<?= $qrCode->writeDataUri();?>"  >
</div>
<?php
	if ($no_tiene_aval){}
	else
	{	
		echo '<p class="';
		if ($tit_br==false){ echo 'aval';}
		else {echo 'aval_br';}
		echo '">Avalado por '.$aval.'</p>';
	}
?>


<?php
	if ($tit_br==false)
	{
		echo '<p class="';
		if ($no_tiene_aval){echo 'organizado_por_2';}
		else {echo 'organizado_por_1';}
		echo '" > Organizado por '.$area_certificado.', <br>';		
	}
	else
	{
		echo '<p class="';
		if ($no_tiene_aval){echo 'organizado_por_2_aval';}
		else {echo 'organizado_por_1_aval';}
		echo '"> Organizado por '.$area_certificado.', <br>';		

	}
?>
	bajo modalidad <?= $modalidad; ?>, <?= $intervalo?>
<?php


	if (($cant_horas==0 ) || ($cant_horas==null) || ($estado_finalizacion==6))
	{
		echo '.-';
	}		
	else

	{
		echo ',<br>con una duración de '.$cant_horas.' horas reloj con evaluación final.-';

	}
	
					
?>
</p>

<?php
if ($num_firmas==1)
{
	$i=1;
		foreach ($roles as $rol) 
		{
			$el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);						
			if ($firma_docente=$el_docente2['firma']!=null)
			{
				$firma_docente=$el_docente2['firma'];				
				if ($i==1)
				{   echo '<div class="img_firma11"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}																	
				
			}	
			$i++;
		
		}	
	echo '<div class="tabla_firmasx1">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td>';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[0].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[0].'</p>';	

	echo '</td>';	
	echo '</tr>';
	echo '</table></div>';
}

if ($num_firmas==2)
{
	$i=1;
		foreach ($roles as $rol) 
		{
			$el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);						
			if ($firma_docente=$el_docente2['firma']!=null)
			{
				$firma_docente=$el_docente2['firma'];				
				if ($i==1)
				{   echo '<div class="img_firma21"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==2)
				{   echo '<div class="img_firma22"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}										
				
			}	
			$i++;
		
		}	
	echo '<div class="tabla_firmasx2">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td>';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[0].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[0].'</p>';	

	echo '</td>';
	echo '<td>';
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[1].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[1].'</p>';	

	echo '</td>';
	echo '</tr>';
	echo '</table></div>';
}
if ($num_firmas==3)
{
	$i=1;
		foreach ($roles as $rol) 
		{
			$el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);						
			if ($firma_docente=$el_docente2['firma']!=null)
			{
				$firma_docente=$el_docente2['firma'];				
				if ($i==1)
				{   echo '<div class="img_firma31"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==2)
				{   echo '<div class="img_firma32"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==3)
				{   echo '<div class="img_firma33"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}					
				
			}	
			$i++;
		
		}	
	echo '<div class="tabla_firmasx3">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td valign="top">';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[0].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[0].'</p>';	

	echo '</td>';
	echo '<td style=" width:203px; " valign="top">';
	$tam2= strlen($firmas_nombre[1]);
                if ($tam2>27)
                {
                    echo '<img class="img_lineafirma2" src="../web/img/lineafirma.png">';
                }
                else
                {
                    echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
                }	
	//echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[1].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[1].'</p>';	

	echo '</td>';

	echo '<td valign="top">';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[2].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[2].'</p>';	

	echo '</td>';
	echo '</tr>';
	echo '</table></div>';
}
if ($num_firmas==4)
{

	$i=1;
		foreach ($roles as $rol) 
		{
			$el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);						
			if ($firma_docente=$el_docente2['firma']!=null)
			{
				$firma_docente=$el_docente2['firma'];				
				if ($i==1)
				{   echo '<div class="img_firma51"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==2)
				{   echo '<div class="img_firma52"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==3)
				{   echo '<div class="img_firma53"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}	
				if ($i==4)
				{   echo '<div class="img_firma44"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}		
				
			}	
			$i++;
		
		}	
	echo '<div class="tabla_firmasx4">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td>';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[0].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[0].'</p>';	

	echo '</td>';
	echo '<td>';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[1].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[1].'</p>';	

	echo '</td>';
	echo '<td>';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[2].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[2].'</p>';	

	echo '</td>';
	echo '</tr>';
	echo '</table></div>';
	echo '<div class="tabla_firmasx4b">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td>';	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[3].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[3].'</p>';	
	echo '</td>';	
	echo '</tr>';
	echo '</table></div>';

}

if ($num_firmas==5)
	{
		$i=1;
		foreach ($roles as $rol) 
		{
			$el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);						
			if ($firma_docente=$el_docente2['firma']!=null)
			{
				$firma_docente=$el_docente2['firma'];				
				if ($i==1)
				{   echo '<div class="img_firma51"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==2)
				{   echo '<div class="img_firma52"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}			
				if ($i==3)
				{   echo '<div class="img_firma53"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}	
				if ($i==4)
				{   echo '<div class="img_firma54"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}		
				if ($i==5)
				{   echo '<div class="img_firma55"><img  src="uploads/instancias/firmas/'.$firma_docente.'"></div>';}		
			}	
			$i++;
		
		}	
	
	
	echo '<div class="tabla_firmasx5">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td>';
	
	
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[0].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[0].'</p>';	

	echo '</td>';
	echo '<td>';
	//echo '<img  src="'.$firmas_imagen[0].'" >';
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[1].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[1].'</p>';	

	echo '</td>';
	echo '<td>';
	//echo '<img  src="'.$firmas_imagen[2].'" >';
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm">'.$firmas_nombre[2].'</p>';
	echo '<p class="cargo_firm">'.$firmas_cargo[2].'</p>';	

	echo '</td>';
	echo '</tr>';
	echo '</table></div>';
	echo '<div class="tabla_firmasx5b">
			<table  style="text-align: center;  border-spacing:  21px;">';
	echo '<tr>';
	echo '<td>';
	//echo '<img  src="'.$firmas_imagen[3].'" >';
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[3].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[3].'</p>';
	echo '<td>';
	echo '<td>';
	//echo '<img  src="'.$firmas_imagen[4].'" >';
	echo '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
	echo '<p class="nombre_firm" >'.$firmas_nombre[4].'</p>';
	echo '<p class="cargo_firm" >'.$firmas_cargo[4].'</p>';
	echo '<td>';		
	echo '</tr>';
	echo '</table></div>';

}

?>

</body>

</html>
