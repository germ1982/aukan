<?php

use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_com_configuracion;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_fotocopiadora */
$proveedor= Sds_com_configuracion::findOne($model->idproveedor);
$organismo= Mds_org_organismo::findOne($model->idorganismo);
$equipo=Sds_bdc_equipo::findOne($model->idequipo);
$marca=Sds_com_configuracion::findOne($equipo->marca);
?>
<div class="sds-reg-fotocopiadora-view">
 
    <?php /* DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'idfotocopiadora',
            'idproveedor',
            'expediente_fisico',
            'expediente_gde',
            'safipro',
            'idorganismo',
            'lugar',
            'idequipo',
            'vencimiento',
            'copias',
            'observaciones:ntext',
        ],
    ]) */?>
<style>
    .label{
        padding: 5px;
        font-size: 11px;
        text-align: left;
    }
    .row{
        margin-top: 3px;
    }
</style>
<div class="mds-reg-contrasenia-view">
    <div class="panel panel-info">
        <div class="panel-heading" style="padding:10px;">
            <div class="row">
                <div class="col-md-6">
                    <span class="panel-title">Fotocopiadora <?= '#'.$model->idfotocopiadora ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color: #fff;">
                    <span><b>Vencimiento: <?=  $model->vencimiento ?></b></span>
                </div>
            </div>
        </div> 
        <div class="panel-body" style="border: 1px solid #ccc; font-size: 15px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Proveedor
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $proveedor->descripcion ?></span></b>
                    
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Exponente Físico
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->expediente_fisico ?></span></b>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Expediente GDE
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->expediente_gde ?></span></b>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Safipro
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->safipro ?></span></b>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Organismo
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $organismo->descripcion ?></span></b>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Lugar Real
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->lugar ?></span></b>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Equipo
                    </div>
                    <b><span class="col-md-9 label label-primary"><?='#'.str_pad($equipo->idequipo, 6, "0", STR_PAD_LEFT). ' - ' . $marca->descripcion . ($equipo->modelo != '' ? ' - ' . $equipo->modelo : '');?></span></b>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Copias
                    </div>
                <b><span class="col-md-9 label label-primary"><?= $model->copias ?></span></b>
                </div>
            </div>
            <div class="row" style="border: 1px solid #ccc; border-radius: 5px; margin:10px;">
                <div class="col-md-12">
                    <b>Observaciones:</b>
                </div>
                <div class="col-md-12">
                    <?= $model->observaciones ?>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
</div>
