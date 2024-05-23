<?php

use app\models\Mds_org_organismo;
use app\models\Sds_com_configuracion;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reg_contrasenia */

$tipo = Sds_com_configuracion::findOne($model->tipo);
$organismo = Mds_org_organismo::findOne($model->idorganismo);
?>
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
                    <span class="panel-title"><?= $tipo->descripcion ?></span>
                </div>
                <div class="col-md-6" style="text-align: right; color: #fff;">
                    <span><b>Fecha Carga: <?= date("d/m/Y", strtotime($model->fecha_carga)) ?></b></span>
                </div>
            </div>
        </div>
        <div class="panel-body" style="border: 1px solid #ccc; font-size: 15px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Usuario
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->usuario ?></span></b>
                    
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Contraseña
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->contrasenia ?></span></b>
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
                        IP
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->ip ?></span></b>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-3">
                        Descripción
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->descripcion ?></span></b>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        Ubicación
                    </div>
                    <b><span class="col-md-9 label label-primary"><?= $model->ubicacion ?></span></b>
                </div>
                <br>
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