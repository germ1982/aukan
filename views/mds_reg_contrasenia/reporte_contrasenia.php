<?php

use app\models\Mds_reg_contrasenia;
use app\models\Sds_com_configuracion;

$model = new Mds_reg_contrasenia();
?>
<div class="row" style="padding: 0px 40px 0px;" >
<div class="row" style="padding: 0px 5px 10px 15px;margin: 0px 10px 20px 0px;">
        <div class="row" style="background-color: #B3E0FF;border-radius: 5px 5px 0 0;">
            <div class="col-xs-4">
            <img src="img/sur_trans.png" height="55px" alt="Sistema Único de Registro">
            </div>
            <div class="col-xs-5" style="margin-left: 210px;">
            <?php
                switch(date('m')){
                    case 1:
                        $mes='Enero';
                        break;
                    case 2:
                        $mes='Febrero';
                        break;
                    case 3:
                        $mes='Marzo';
                        break;
                    case 4:
                        $mes='Abril';
                        break;
                    case 5:
                        $mes='Mayo';
                        break;
                    case 6:
                        $mes='Junio';
                        break;
                    case 7:
                        $mes='Julio';
                        break;
                    case 8:
                        $mes='Agosto';
                        break;
                    case 9:
                        $mes='Septiembre';
                        break;
                    case 10:
                        $mes='Octubre';
                        break;
                    case 11:
                        $mes='Noviembre';
                        break;
                    case 12:
                        $mes='Diciembre';
                        break;
                }
                ?>
                <b>Neuquén, <?=date('d')?> de <?=$mes?> de <?=date('Y')?></b>
            </div>
        </div>
        <div class="row" style="text-align: center;background-color:#ccebff;font-size: 25px;border-radius:0 0 5px 5px;">
        Registro de Contraseñas
        </div>
    </div>
    <?php
    foreach ($ids as $id) :
        $model = Mds_reg_contrasenia::findOne($id);
    ?>
    <div class="col-xs-12" style="border:1px  solid #edd; padding: 0px 5px 10px 15px; border-radius: 5px; background-color: #F6FBFE; margin-bottom: 15px;">
        <div class="row" style="border-bottom: 1px solid #999; padding: 0px -10px 0px 0px; margin-right:-5px; background-color: #B3E0FF">
            <div class="col-xs-2" style="border-right: 1px solid #999;">
                Tipo
            </div>
            <div class="col-xs-2" style="border-right: 1px solid #999;">
                Usuario
            </div>
            <div class="col-xs-3" style="border-right: 1px solid #999;">
                Contraseña
            </div>
            <div class="col-xs-2">
                IP
            </div>
        </div>
        <div class="row" style="border-bottom: 1px solid #999;padding: -5px -10px 0px 0px;margin-right:-5px">
            <div class="col-xs-2" style="border-right: 1px solid #999;">
                <?php $tipo = Sds_com_configuracion::findOne($model->tipo); ?>
                <?= ($tipo != null ? $tipo->descripcion : '- SIN DATOS -'); ?>
            </div>
            <div class="col-xs-2" style="border-right: 1px solid #999;">
                <?= $model->usuario ?>
            </div>
            <div class="col-xs-3" style="border-right: 1px solid #999;">
                <?= $model->contrasenia ?>
            </div>
            <div class="col-xs-2">
                <?= $model->ip ?>
            </div>
        </div>
        <div class="row" style="border-bottom: 1px solid #999;padding: -5px -10px 0px 0px;margin-right:-5px">
            <div class="col-xs-4" style="border-right: 1px solid #999; width: 37.5%;">
                Ubicación: <?= $model->ubicacion ?>
            </div>
            <div class="col-xs-5">
                Descripción: <?= $model->descripcion ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                Observaciones: <?= $model->observaciones ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
