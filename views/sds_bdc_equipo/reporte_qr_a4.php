<?php

use app\models\Mds_org_contacto;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use Da\QrCode\QrCode;?>
<div class="row" style="margin-left: 100px;">
    <?php
    foreach($ids as $id):
        $equipo=Sds_bdc_equipo::findOne($id);
        $mantenimiento_preventivo=Sds_bdc_movimiento::findBySql(
            "SELECT max(m.fecha_hora) fecha_hora
            FROM sds_bdc_movimiento_equipo me
            JOIN sds_bdc_movimiento m ON me.idmovimiento=m.idmovimiento
            WHERE m.tipo=".Sds_bdc_movimiento::MOV_ENT_REPARACION." AND preventivo=1 AND me.idequipo=$id"
        )->one();
        if($mantenimiento_preventivo->fecha_hora!=null){
            $date_mp=date('d/m/Y', strtotime($mantenimiento_preventivo->fecha_hora));
        }else{
            $date_mp=false;
        }
        $urlqr='http://sur.neuquen.gov.ar/index.php?r=sds_bdc_equipo/view&id='.$id;?>
        <div class="col-xs-2" style="border: 1px solid #000; float:left; margin: 0; text-align: center; padding:5px 15x; height:191px;">
            <?php
            $qr= (new QrCode($urlqr))
            ->setSize(400);
            ?>
            <img src="<?=$qr->writeDataUri()?>">
            <span style="font-size: 12px; margin-top:5px;">
                Cod.: <b><?=str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT);?></b>
                <br>
                Mat.: <b><?=($equipo->matricula!=''?$equipo->matricula:'S/D');?></b><br>
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
                <span style="font-size: 10px;"><?=$tipo->descripcion?> -<?=(isset($tUser)?$tUser:'U')?>- <b><?=$usuario->nombre?> <?=$usuario->apellido?></b></span><br>
                <?php if($date_mp):?>
                    <span style="font-size: 10px;">MP: <b><?=$date_mp?></b></span>
                <?php endif;?>
            </span>
        </div>
        <?php
        unset($usuario);
        unset($tUser);?>
    <?php endforeach;?>
</div>