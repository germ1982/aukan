<?php

use app\models\Mds_org_contacto;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_bdc_movimiento_equipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;

?>

<div style='padding-left:  40px; '>
    <div style='border: 1px solid #ccc; border-radius: 4px; background: #f3f9f9;'>
        <div class='row'style='padding:10px 10px; margin-right:0;'>
            <?php
                //$consulta = "Select * FROM sds_bdc_movimiento_equipo where idmovimiento = $model->idmovimiento";
                $movimiento = Sds_bdc_movimiento::findOne($model->idmovimiento);
                $equipos = Sds_bdc_movimiento_equipo::find()->where(['idmovimiento'=>$model->idmovimiento])->all();
                foreach($equipos as $equipo){
                    $equipo=Sds_bdc_equipo::findOne($equipo->idequipo);
                    ?>
                    <div class='col-xs-4'>
                        <div style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px; margin-bottom:0;'>
                            <?php 
                            $tipo=Sds_com_configuracion::findOne($equipo->tipo);
                            $responsable=Sds_com_persona::findOne($equipo->responsable0->idpersona);
                            $usuario=Sds_com_persona::findOne($equipo->usuario0->idpersona);
                            ?>
                            <b><?= $tipo->descripcion?> #<?=str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT)?>:</b><br>
                            Responsable: <?=$responsable->apellido?>, <?=$responsable->nombre?>.<br>
                            Usuario: <?=$usuario->apellido?>, <?=$usuario->nombre?>.<br>

                            <?php
                                if($movimiento->tipo==Sds_bdc_movimiento::MOV_CAM_IP):?>
                                    <b>IP Anterior: <?= $movimiento->ip_anterior!='' ? $movimiento->ip_anterior:' - '?></b> | 
                                    <b>IP Nueva: <?= $movimiento->ip_nueva!='' ? $movimiento->ip_nueva:' - '?></b>
                                <?php endif; ?>

                                <?php if($movimiento->tipo==Sds_bdc_movimiento::MOV_CAM_RESPONSABLE || $movimiento->tipo==Sds_bdc_movimiento::MOV_ALTA):?>
                                    <div class="col-md-12" style="background-color: #fff; border:1px solid #e0e0e0; border-radius: 7px; border-top-left-radius:0; border-top-right-radius:0; color: #111; padding: 3px 5px; margin-top:4px;">
                                        <?php
                                        $contact_responsable_anterior=Mds_org_contacto::findOne($movimiento->responsable_anterior);
                                        $contact_responsable_nuevo=Mds_org_contacto::findOne($movimiento->responsable_nuevo);
                                        if($contact_responsable_anterior!=null){
                                            $responsable_anterior=Sds_com_persona::findOne($contact_responsable_anterior->idpersona);
                                        }
                                        if($contact_responsable_nuevo!=null){
                                            $responsable_nuevo=Sds_com_persona::findOne($contact_responsable_nuevo->idpersona);
                                        }
                                        if(isset($responsable_anterior)):?>
                                            <b>Responsable Anterior:</b><br>
                                            - <?=$responsable_anterior->apellido.', '.
                                            $responsable_anterior->nombre?><br>
                                        <?php endif;
                                        if(isset($responsable_nuevo)):?>
                                            <b>Responsable Nuevo:</b><br>
                                            - <?=$responsable_nuevo->apellido.', '.
                                            $responsable_nuevo->nombre?><br>
                                        <?php endif; ?>
                                        <?php
                                        $contact_usuario_anterior=Mds_org_contacto::findOne($movimiento->usuario_anterior);
                                        $contact_usuario_nuevo=Mds_org_contacto::findOne($movimiento->usuario_nuevo);
                                        if($contact_usuario_anterior!=null){
                                            $usuario_anterior=Sds_com_persona::findOne($contact_usuario_anterior->idpersona);
                                        }
                                        if($contact_usuario_nuevo!=null){
                                            $usuario_nuevo=Sds_com_persona::findOne($contact_usuario_nuevo->idpersona);
                                        }
                                        if(isset($usuario_anterior)):?>
                                            <b>Usuario Anterior:</b><br>
                                            - <?=$usuario_anterior->apellido.', '.
                                            $usuario_anterior->nombre?><br>
                                        <?php endif;
                                        if(isset($usuario_nuevo)):?>
                                            <b>Usuario Nuevo:</b><br>
                                            - <span style="color:#444;"><?=$usuario_nuevo->apellido.', '.
                                            $usuario_nuevo->nombre?></span><br>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
        </div>
    </div>
</div>