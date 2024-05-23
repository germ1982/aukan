<?php

use app\models\Mds_seg_item;
use app\models\Sds_com_menu;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_menu */
?>
<style>
    .label{
        font-size:13px;
    }
    .mt{
        margin-top: 10px;
    }
</style>
<div class="row">
    <?php $padre=Sds_com_menu::findOne($model->padre);?>
    <div class="col-md-6 col-md-offset-3" style="background-color:#1d2127; color: rgb(171, 180, 190); font-size:25px; border-radius: 5px; padding: 15px; margin-left: 160px;">
        <div class="col-md-10">
            <i class="<?=$model->icono?>" aria-hidden="true"></i> <?=$model->descripcion?>
        </div>
        <div class="col-md-1">
            <?=($padre==null?'<span class="caret"></span>':'')?>
        </div>
    </div>
    <hr class="col-md-10 ol-md-offset-2" style="border-color: #dcf; background-color: #dcf; margin: 15px 0 15px 25px;" />
    <?php if($padre!=null):?>
        <div class="row">
            <div class="col-md-10 col-md-offset-2" style="margin-top: 10px !important;">
                <div class="col-md-1">URL</div>
                <div class="col-md-8 label label-info" style="margin:2px 0 0 15px; padding:5px;"><?=$model->ruta?></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row mt">
        <div class="col-md-6">
            <div class="col-md-3">
                Icono
            </div>
            <div class="col-md-9 label label-info">
                <?=$model->icono?>
            </div>
        </div>
        <?php if($padre!=null):?>
            <div class="col-md-6">
                <div class="col-md-3">
                    Padre 
                </div>
                <div class="col-md-8 label label-info">
                    <?=$padre->idmenu?> - <?=$padre->descripcion?>
                </div>
            </div>
        <?php endif;?>
    </div>
    <?php $iditem=Mds_seg_item::findOne($model->iditem);?>
    <?php if($iditem!=null):?>
        <div class="row mt">
            <div class="col-md-6">
                <div class="col-md-3">
                    Item
                </div>
                <div class="col-md-9 label label-info">
                    <?=$iditem->iditem?> - <?=$iditem->descripcion?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="col-md-3">
                    Orden
                </div>
                <div class="col-md-8 label label-info">
                    <?=$model->orden?>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>