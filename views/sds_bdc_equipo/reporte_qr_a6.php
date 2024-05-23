<?php

use app\models\Mds_org_contacto;
use app\models\Sds_bdc_equipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use Da\QrCode\QrCode;
$per_page=6;
foreach($ids as $id):
    if($per_page==0){
        echo '<div style="page-break-after:always;"></div>';
        $per_page=6;
    }
    $equipo=Sds_bdc_equipo::findOne($id);
    $urlqr='http://sur.neuquen.gov.ar/index.php?r=sds_bdc_equipo/view&id='.$id;?>
    <div class="col-xs" style="border: 1px solid #000; float:left; text-align: center; padding:1px 1px; height: 176px; width: 24.5%;">
        <?php
        $qr= (new QrCode($urlqr))
        ->setSize(200);
        ?>
        <div class="row">
            <img src="<?=$qr->writeDataUri()?>" style="width:130px; height:130px;">
        </div>
        <span style="font-size: 11px; margin-top:0px;">
            <b>Cod.: #<?=str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT);?></b>
            | 
            Mat.: <?=($equipo->matricula!=''?$equipo->matricula:'S/D');?><br>
            <?php
            $tipo=Sds_com_configuracion::findOne($equipo->tipo);
            $contacto=Mds_org_contacto::findOne($equipo->usuario);
            if($contacto!=null){
                $usuario=Sds_com_persona::findOne($contacto->idpersona);
            }else{
                $contacto=Mds_org_contacto::findOne($equipo->responsable);
                $usuario=Sds_com_persona::findOne($contacto->idpersona);
                $tUser="R";
            }
            ?>
            <span style="font-size: 9px;"><?=$tipo->descripcion?> -<?=(isset($tUser)?$tUser:'U')?>- <?=$usuario->nombre?> <?=$usuario->apellido?></span>
        </span>
    </div>
    <?php
    if($per_page==5 || $per_page==3){
        echo '<div style="width:51%;"></div>';
    }
    unset($usuario);
    unset($tUser);
    $per_page--;?>
<?php endforeach;?>