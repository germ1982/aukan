<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_bdc_equipo;
use app\models\Sds_cel_linea;
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_movimiento_linea */
?>
<div class="sds-cel-movimiento-linea-view" style="padding:10px;">

    <div class="row" style="border: 1px solid #def; padding:10px; padding-bottom:30px; border-radius:4px;">
        <div class="row">
            <div class="col-md-4">Movimiento: <span><b><?=Sds_com_configuracion::getDescripcion($model->tipo)?></b></span></div>
            <?php $linea=Sds_cel_linea::findOne($model->idlinea)?>
            <div class="col-md-4">Linea: <b><?=$linea->numero?></b></div>
            <?php $fecha=date('d/m/Y H:i', strtotime($model->fecha_hora))?>
            <div class="col-md-4">Fecha: <b><?=$fecha?></b></div>
        </div>
        <div class="row" style="margin-top:5px; text-align:center;">
            <?php $solicitante=Mds_org_contacto::findBySql(
                "SELECT CONCAT(p.apellido, ', ', p.nombre) nombre FROM mds_org_contacto c
                JOIN sds_com_persona p ON c.idpersona=p.idpersona
                WHERE c.idcontacto=".$model->solicitante)->one()?>
            <div class="col-md-6">Solicitante: <b><?=$solicitante->nombre?></b></div>
            <?php $carga=Mds_seg_usuario::findOne($model->idusuario);?>
            <div class="col-md-6">Usuario carga: <b><?=$carga->user?></b></div>
        </div>

        <hr class="col-md-9" style="border-color: #dcf; background-color: #dcf; margin: 15px 0 15px 85px;" />

        <div class="row" style="padding-left: 15px;">
            <?php
            if($model->responsable_anterior!=null && $model->responsable_nuevo!=null){
                $resp_ant=Mds_org_contacto::findBySql(
                    "SELECT CONCAT(p.apellido, ', ', p.nombre) nombre FROM mds_org_contacto c
                    JOIN sds_com_persona p ON c.idpersona=p.idpersona
                    WHERE c.idcontacto=".$model->responsable_anterior)->one();
                $resp_nuevo=Mds_org_contacto::findBySql(
                    "SELECT CONCAT(p.apellido, ', ', p.nombre) nombre FROM mds_org_contacto c
                    JOIN sds_com_persona p ON c.idpersona=p.idpersona
                    WHERE c.idcontacto=".$model->responsable_nuevo)->one();
                if($resp_ant!=null):?>
                    <div class="row">
                        <div class="col-md-6">Responsable Anterior: <b><?=$resp_ant->nombre?></b></div>
                        <div class="col-md-6">Responsable Nuevo: <b><?=$resp_nuevo->nombre?></b></div>
                    </div>
                <?php endif;
            }
            if($model->equipo_anterior!=null && $model->equipo_nuevo!=null){
                $equipo_anterior=Sds_bdc_equipo::findOne($model->equipo_anterior);
                $equipo_nuevo=Sds_bdc_equipo::findOne($model->equipo_nuevo);
                if($equipo_anterior!=null):?>
                    <div class="row">
                        <div class="col-md-6">
                            Equipo Anterior: 
                            <a href="<?=Url::to(['sds_bdc_equipo/view', 'id'=>$equipo_anterior->idequipo])?>" target="_blank">
                                <b>#<?=str_pad($equipo_anterior->idequipo,6,"0", STR_PAD_LEFT)?></b>
                            </a>
                        </div>
                        <div class="col-md-6">
                            Equipo Nuevo:
                            <a href="<?=Url::to(['sds_bdc_equipo/view', 'id'=>$equipo_nuevo->idequipo])?>" target="_blank">
                                <b>#<?=str_pad($equipo_nuevo->idequipo,6,"0", STR_PAD_LEFT)?></b>
                            </a>
                        </div>
                    </div>
                <?php
                endif;
            }
            if($model->organismo_anterior!=null && $model->organismo_nuevo!=null){
                $organismo_anterior=Mds_org_organismo::findOne($model->organismo_anterior);
                $organismo_nuevo=Mds_org_organismo::findOne($model->organismo_nuevo);
                if($organismo_anterior!=null):?>
                    <div class="row">
                        <div class="col-md-6">Organismo Anterior: <b><?=$organismo_anterior->descripcion?></b></div>
                        <div class="col-md-6">Organismo Nuevo: <b><?=$organismo_nuevo->descripcion?></b></div>
                    </div>
            <?php
                endif;
            }?>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?=($model->observaciones!=null?'Observaciones: <b>'.$model->observaciones.'</b>':'')?>
            </div>
        </div>
    </div>
</div>
