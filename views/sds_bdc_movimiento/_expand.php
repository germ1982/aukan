<?php

use app\models\Mds_org_contacto;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_bdc_movimiento_equipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use yii\helpers\Url;

?>

<div style='padding-left:  40px; '>
    <div style='border: 1px solid #ccc; border-radius: 4px; background: #f3f9f9;'>
        <div class='row'style='padding:10px 10px; margin-right:0;'>
            <?php
                $consulta = "Select * FROM sds_bdc_movimiento_equipo where idmovimiento = $model->idmovimiento";
                $data = Sds_bdc_movimiento_equipo::findBySql($consulta)->all();
                if($data!=null){
                    foreach($data as $mov_eq){
                        $equipo=Sds_bdc_equipo::findOne($mov_eq->idequipo);?>
                        <div class='col-xs-4'>
                            <div style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px; margin-bottom:0;'>
                                <?php $tipo=Sds_com_configuracion::findOne($equipo->tipo);
                                    if($equipo->responsable0!=null){
                                        $responsable=Sds_com_persona::findOne($equipo->responsable0->idpersona);
                                    }
                                    if($equipo->usuario0!=null){
                                        $usuario=Sds_com_persona::findOne($equipo->usuario0->idpersona);
                                    }
                                ?>
                                <a href="<?=Url::to(['sds_bdc_equipo/view', 'id'=>$equipo->idequipo])?>">
                                    <b><?= $tipo->descripcion?> #<?=str_pad($equipo->idequipo,6,"0", STR_PAD_LEFT)?>:</b><br>
                                </a>
                                <?php 
                                if(isset($responsable)){
                                    echo 'Responsable: '.$responsable->apellido.', '.$responsable->nombre.'<br>';
                                }
                                if(isset($usuario)){
                                    echo 'Usuario: '.$usuario->apellido.', '.$usuario->nombre.'<br>';
                                }
                                ?>
                                <?php
                                if($model->tipo==Sds_bdc_movimiento::MOV_CAM_IP):?>
                                    <b>IP Anterior: <?= $model->ip_anterior!='' ? $model->ip_anterior:' - '?></b> | 
                                    <b>IP Nueva: <?= $model->ip_nueva!='' ? $model->ip_nueva:' - '?></b>
                                <?php endif; ?>

                                <?php if($model->tipo==Sds_bdc_movimiento::MOV_CAM_RESPONSABLE || $model->tipo==Sds_bdc_movimiento::MOV_ALTA):?>
                                    <div class="col-md-12" style="background-color: #f1f1f1; border-radius: 5px; color: #000; padding: 3px 5px; margin-top:4px;">
                                        <?php
                                        $contact_responsable_anterior=Mds_org_contacto::findOne($model->responsable_anterior);
                                        $contact_responsable_nuevo=Mds_org_contacto::findOne($model->responsable_nuevo);
                                        if($contact_responsable_anterior!=null){
                                            $responsable_anterior=Sds_com_persona::findOne($contact_responsable_anterior->idpersona);
                                        }
                                        if($contact_responsable_nuevo!=null){
                                            $responsable_nuevo=Sds_com_persona::findOne($contact_responsable_nuevo->idpersona);
                                        }
                                        if(isset($responsable_anterior)):?>
                                            <b>Responsable Anterior:<br>
                                            - <?=$responsable_anterior->apellido.', '.
                                            $responsable_anterior->nombre?></b><br>
                                        <?php endif;
                                        if(isset($responsable_nuevo)):?>
                                            <b>Responsable Nuevo:<br>
                                            - <?=$responsable_nuevo->apellido.', '.
                                            $responsable_nuevo->nombre?></b><br>
                                        <?php endif; ?>
                                        <?php
                                        $contact_usuario_anterior=Mds_org_contacto::findOne($model->usuario_anterior);
                                        $contact_usuario_nuevo=Mds_org_contacto::findOne($model->usuario_nuevo);
                                        if($contact_usuario_anterior!=null){
                                            $usuario_anterior=Sds_com_persona::findOne($contact_usuario_anterior->idpersona);
                                        }
                                        if($contact_usuario_nuevo!=null){
                                            $usuario_nuevo=Sds_com_persona::findOne($contact_usuario_nuevo->idpersona);
                                        }
                                        if(isset($usuario_anterior)):?>
                                            <b>Usuario Anterior:<br>
                                            - <?=$usuario_anterior->apellido.', '.
                                            $usuario_anterior->nombre?></b><br>
                                        <?php endif;
                                        if(isset($usuario_nuevo)):?>
                                            <b>Usuario Nuevo:<br>
                                            - <?=$usuario_nuevo->apellido.', '.
                                            $usuario_nuevo->nombre?></b><br>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
            <?php   }?>
          <?php } ?>
        </div>
    </div>
</div>