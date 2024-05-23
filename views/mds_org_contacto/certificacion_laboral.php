<?php

use app\models\Mds_org_contacto;

switch (date('m')) {
	case 1:
		$mes = 'Enero';
		break;
	case 2:
		$mes = 'Febrero';
		break;
	case 3:
		$mes = 'Marzo';
		break;
	case 4:
		$mes = 'Abril';
		break;
	case 5:
		$mes = 'Mayo';
		break;
	case 6:
		$mes = 'Junio';
		break;
	case 7:
		$mes = 'Julio';
		break;
	case 8:
		$mes = 'Agosto';
		break;
	case 9:
		$mes = 'Septiembre';
		break;
	case 10:
		$mes = 'Octubre';
		break;
	case 11:
		$mes = 'Noviembre';
		break;
	case 12:
		$mes = 'Diciembre';
		break;
}
if(isset($contacto)){
    switch($contacto->tipo_contratacion){
        case Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE:
            $contratacion="PLANTA PERMANENTE";
            break;
        case Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA:
            $contratacion="PLANTA POLITICA";
            break;
        case Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA:
            $contratacion="PLANTA POLITICA PURA";
            break;
        case Mds_org_contacto::TIPO_CONTRATACION_CONTRATO:
            $contratacion="CONTRATADO";
            break;
        case Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES:
            $contratacion="EVENTUAL";
            break;
    }

    $fechaInicio = date('d/m/Y', strtotime($contacto->fecha_ingreso));
    if(isset($antiguedad)){
        $anios=floor($antiguedad['dias']/365); //Para obtener la cantidad de años
        $float=($antiguedad['dias']/365)-$anios; //Para obtener la parte entera de años
        //Si hay parte decimal debo calcular el tiempo (meses,días) que3 representa
        if($float > 0){
            $meses=$float*12; //Obtengo meses
            //Si hay más de un mes:
            if($meses>0){
                $meses=floor($meses);//Parte entera de los meses
                $dias=($float*365)-($meses*30);
                $dias=floor($dias);
                if($dias<1){
                    $dias=0;
                }
            }else{//Si meses es igual 0 debeo calcular cuantos días son
                $meses=0;
                $dias=($float*365);
                $dias=floor($dias);
                if($dias<1){
                    $dias=0;
                }
            }
        }else{//Si despues de calcular los años no hay parte decimal meses y dias es =0
            $meses=0;
            $dias=0;
        }
        $antiguedadString='';
        if($anios>1){
            $antiguedadString="($anios) año".($anios>1 ? 's':'');
        }
        if($meses>0){
            $antiguedadString.=($anios>0 ? ', ':'')."($meses) mes".($meses>1 ? 'es':'');
        }
        if($dias>0){
            $antiguedadString.=($anios>0 || $meses>0 ? ' y ':'')."($dias) día".($dias>1 ? 's':'');
        }
    }
}
?>
<html>
    <body>
    	<div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
    		<img src="img/membrete_nuevo_pri.png" width="100%" alt="Subsecretaría de Desarrollo Social">
        </div>
        <h3 style="font-family: Times New Roman, serif; margin: 65px 0; margin-bottom: 15px; text-align:center;"><u><b>CERTIFICACIÓN</b></u></h3>
        <?php if(isset($error)) :?>
            <?php if(isset($persona)) :?>
                <div>
                    <?=$persona->apellido?>, <?=$persona->nombre?>
                    <?=$error?> en el Ministerio de Desarrollo Social y Trabajo.
                </div>
            <?php else: ?>
                <div><?=$error?></div>
            <?php endif ?>
        <?php else: ?>
            <div style="padding: 25px 0px 0px 75px; margin-right:0; font-size:15px; text-align:justify;">
                --------------------<b>CERTIFICO,</b> por la presente que el agente: <b><?=trim($persona->apellido)?>, <?=$persona->nombre?> 
                - DNI Nº <?=$persona->documento?></b>, es personal <b><?= $contratacion?></b> de <?= $edificio->descripcion?> perteneciente al
                 Ministerio de Desarrollo Social y Trabajo 
                <b>LEGAJO Nº <?=$contacto->legajo?> - CATEGORIA (<?=$categoria?>)</b>, con fecha de ingreso a partir del <?=$fechaInicio?>.
                Acreditando una antigüedad administrativa de servicios efectivos correspondiente a <?=$antiguedadString?> en la 
                Administración Pública Pcial. Cumpliendo funciones al día de la fecha, en <b><?=$edificio->descripcion ?></b>,
                ubicado en <?=$edificio->direccion?>
                --------------------------------------------------<br><br>
                --------------------Se extiende la presente certificación, en la Ciudad de Neuquén Capital, al día 
                <?=date('d').' del mes de '.$mes .' de '.date('Y'); ?>, a fin de ser presentado ante las autoridades que así lo determinen
                --------------------------------------------------------------------------------------------
            </div>
        <?php endif; ?>
        <footer style="position: fixed; bottom:0; font-size:16px; margin: 0 auto; text-align: center; width: 100%;">
            DIRECCIÓN GENERAL DE RECURSOS HUMANOS.<br>
            MINISTERIO DE DESARROLLO SOCIAL Y TRABAJO.
        </footer>
    </body>
</html>