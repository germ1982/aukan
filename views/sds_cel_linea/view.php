<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_cel_linea;
use app\models\Sds_cel_plan;
use app\models\Sds_com_persona;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_linea */
?>
<div class="sds-cel-linea-view">
    <?php if(!Yii::$app->request->isAjax):?>
    <div class="label label-primary" style="font-size:22px; margin: 0 0 30px 15px;">
        Linea: <b><?=$model->numero?></b>
    </div>
    <br><br>
    <?php endif;?>
    <div class="panel panel-info col-md-12">
        <div class="panel-heading" style="border: 1px solid #cef; padding: 5px; min-height:37px; width:102%;">
            <h4 style="color: #fff; margin:0;" class="col-md-8">Equipo: <?=$equipo->marca?> - <?=$equipo->modelo?> | #<?=str_pad($model->idequipo,6,"0", STR_PAD_LEFT)?></h4>
        </div>
        
        <div class="panel-body" style="border: 1px solid #cef; font-size:14px; font-weight: bolder; width:102%;">
            <div class="row">
                <div class="col-md-6 border-btm-col border-left-col">
                    <div class="col-md-4">IMEI:</div>
                    <div class="col-md-8">
                        <span class="label label-info">
                            <?=($equipo!=null?$equipo->imei:'- SIN DATOS -')?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 border-btm-col">
                    <div class="col-md-3">Plan:</div>
                    <div class="col-md-9">
                        <span class="label label-info padding-col">
                            <?php $plan=Sds_cel_plan::findOne($model->idplan);?>
                            <?=(isset($plan)?$plan->descripcion:'- SIN DATOS -')?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-md-6 border-btm-col border-left-col">
                    <div class="col-md-4">Responsable:</div>
                    <div class="col-md-8">
                        <span class="label label-info">
                            <?php 
                                if($contacto=Mds_org_contacto::findOne($equipo->responsable)){
                                    $responsable=Sds_com_persona::findOne($contacto->idpersona);
                                }
                            ?>
                            <?=(isset($responsable)?$responsable->apellido.' '.$responsable->nombre:'- SIN DATOS -')?>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 border-btm-col">
                    <div class="col-md-3">Organismo:</div>
                    <div class="col-md-9">
                        <span class="label label-info padding-col">
                            <?php $organismo=Mds_org_organismo::findOne($equipo->idorganismo);?>
                            <?=($organismo!=null?$organismo->descripcion:'- SIN DATOS -')?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-md-10 col-md-offset-2 border-btm-col">
                    <div class="col-md-4">Organismo Cuenta:</div>
                    <div class="col-md-7">
                        <span class="label label-info padding-col">
                            <?php $organismo=Mds_org_organismo::findOne($model->organismo_padre);?>
                            <?=($organismo!=null?$organismo->descripcion:'- SIN DATOS -')?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>              
</div>