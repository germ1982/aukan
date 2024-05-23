<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use Da\QrCode\QrCode;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_equipo */
$this->title = 'Equipo #'.str_pad($model->idequipo,6,"0", STR_PAD_LEFT);
?>
<style>
    .content-body{
        padding-top: 20px;
    }
    .border-btm-col{
        border-bottom:1px solid #cef; 
        padding-bottom: 6px;
    }
    .border-left-col{
        border-right: 2px solid #dde;
    }
    .padding-col{
        padding: 5px 3px;
        min-width: 100%;
    }
    .cont-coment{
        border: 1px solid #bcf;
        border-radius: 3px;
        min-height:150px;
    }
    .titulo-r {
        font-size: 20px;
        font-weight:700;
    }
    @media (max-width: 450px) {
        .titulo-r {
            font-size: 13px;
            font-weight:700;
        }
        .item-r {
            font-size: 13px;
            font-weight:700;
        }
        .border-btm-col{
            padding-bottom: 0;
        }
        .border-left-col{
            border-right: none;
        }
    }

    @media (max-width: 375px) {
        .titulo-r {
            font-size: 10px;
            font-weight:700;
        }
        .item-r {
            font-size: 11px;
            font-weight:700;
        }
    }
</style>
<?php if(!Yii::$app->request->isAjax):?>
    <header class="page-header">
        <h2><?= $this->title ?></h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>
                    <a href="index.php?r=sds_bdc_equipo">
                        <span>Equipos</span>
                    </a>
                </li>
                <li><span><u><?= $this->title ?></u></span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php endif;?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body" style="padding-bottom: 30px;">
                <?php //Alerts Success y Error:
                if(Yii::$app->session->hasFlash('save_equipo')) : ?>
                    <div class="alert alert-success alert-dismissable" id="alert-save">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
                        <b><?= Yii::$app->session->getFlash('save_equipo') ?></b>
                    </div>
                <?php endif; ?>
                <div class="panel panel-primary col-md-12 col-xs-12">
                    <div class="panel-heading" style="border: 1px solid #cef; padding: 5px; min-height:37px;">
                        <?php 
                        $tipo=Sds_com_configuracion::findOne($model->tipo);
                        $marca=Sds_com_configuracion::findOne($model->marca);
                        ?>
                        <span style="color: #fff; margin:0;" class="col-md-4 col-xs-4 titulo-r">Equipo #<?=str_pad($model->idequipo,6,"0", STR_PAD_LEFT)?></span>
                        <span style="color: #fff; margin:0;" class="col-md-4 col-xs-4 titulo-r"><?=$tipo->descripcion?></span>
                        <?php 
                        $style='primary';
                        if($model->estado=='Alta'){
                            $style='success';
                        }
                        if($model->estado=='Baja'){
                            $style='danger';
                        }
                        ?>
                        <h3 class="col-md-4 col-xs-4" style="margin:0; font-size:17px;">
                            Estado: <span class="text-<?=$style?> label label-default" style="margin-left:5px;"><?=$model->estado?></span>
                        </h3>
                    </div>
                    <div class="panel-body" style="border: 1px solid #cef; font-size:15px; font-weight:bolder; background-color:#f4f5f980;">
                        <div class="row">
                            <div class="col-md-6 col-xs-12 border-btm-col border-left-col">
                                <?php $contacto=Mds_org_contacto::findOne($model->responsable);
                                    if($contacto!=null){
                                        $responsable=Sds_com_persona::findOne($contacto->idpersona);
                                    }?>
                                <div class="col-md-4 col-xs-4 item-r">Responsable:</div>
                                <div class="col-md-8 col-xs-8">
                                    <span class="label label-primary col-xs-12 padding-col"><?=($responsable!=null?$responsable->nombre.' '.$responsable->apellido:'- SIN DATOS -')?></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 border-btm-col">
                                <?php $contacto=Mds_org_contacto::findOne($model->usuario);
                                    if($contacto!=null){
                                        $usuario=Sds_com_persona::findOne($contacto->idpersona);
                                    }?>
                                <div class="col-md-3 col-xs-4 item-r">Usuario:</div>
                                <div class="col-md-9 col-xs-8">
                                    <span class="label label-primary col-xs-12 padding-col"><?=(isset($usuario)?$usuario->nombre.' '.$usuario->apellido:'- SIN DATOS -')?></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-xs-12 border-btm-col border-left-col">
                                <div class="col-md-6 col-xs-4 item-r">Marca:</div>
                                <div class="col-md-6 col-xs-8">
                                    <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=$marca->descripcion?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 border-btm-col border-left-col">
                                <div class="col-md-6 col-xs-4 item-r">Modelo:</div>
                                <div class="col-md-6 col-xs-8">
                                    <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=$model->modelo!=null?$model->modelo:'- SIN DATOS -'?></span>
                                </div>
                            </div>
                            <div class="col-md-4 border-btm-col">
                                <div class="col-md-4">Matricula:</div>
                                <div class="col-md-8">
                                    <span class="label label-primary col-md-12 padding-col"><?=($model->matricula!=''?$model->matricula:'- SIN DATOS -')?></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-xs-12 border-btm-col border-left-col">
                                <?php $procesador=Sds_com_configuracion::findOne($model->procesador);?>
                                <div class="col-md-6 col-xs-4 item-r">Procesador:</div> 
                                <div class="col-md-6 col-xs-8">
                                    <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=$procesador!=null?$procesador->descripcion:'- SIN DATOS -'?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 border-btm-col border-left-col">
                                <?php $memoria=Sds_com_configuracion::findOne($model->memoria);?>
                                <div class="col-md-6 col-xs-4 item-r">Memoria:</div>
                                <div class="col-md-6 col-xs-8">
                                    <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=($memoria!=null?$memoria->descripcion:'- SIN DATOS -')?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 border-btm-col">
                                <?php $disco=Sds_com_configuracion::findOne($model->disco);?>
                                <div class="col-md-4 col-xs-4 item-r">Disco:</div>
                                <div class="col-md-8 col-xs-8">
                                    <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=($disco!=null?$disco->descripcion:'- SIN DATOS -')?></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-xs-12 border-btm-col border-left-col">
                                <?php $so=Sds_com_configuracion::findOne($model->sistema_operativo);?>
                                <div class="col-md-6 col-xs-4 item-r">Sist. Op.:</div>
                                <div class="col-md-6 col-xs-8">
                                    <span class="col-m-12 label label-primary col-xs-12 padding-col"><?=$so!=null?$so->descripcion:'- SIN DATOS -'?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 border-btm-col border-left-col">
                                <?php $conectividad=Sds_com_configuracion::findOne($model->conectividad);?>
                                <div class="col-md-6 col-xs-4 item-r">Conectividad:</div>
                                <div class="col-md-6 col-xs-8">
                                    <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=($conectividad!=null?$conectividad->descripcion:'- SIN DATOS -')?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 border-btm-col">
                            <div class="col-md-4 col-xs-4 item-r">IP:</div>
                            <div class="col-md-8 col-xs-8">
                                <span class="label label-primary col-md-12 col-xs-12 padding-col"><?=($model->ip!=null?$model->ip:'- SIN DATOS -')?></span>
                            </div>
                            </div>
                        </div>
                        <br>
                        <div class="row" style="padding-left: 15px; background-color:#ffffff;">
                            <div class="col-md-6 cont-coment">
                                Observaciones:<br>
                                <?= $model->observaciones;?>
                            </div>
                            <div class="col-md-6 text-center">
                                <?php 
                                //codifico la url para evitar conflicto al pasarla como parametro a la api
                                $urlqr='http://sur.neuquen.gov.ar/index.php?r=sds_bdc_equipo/view&id='.$model->idequipo;
                                $qr= (new QrCode($urlqr))
                                ->setSize(130);
                                ?>
                                <img src="<?=$qr->writeDataUri()?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sds-bdc-equipo-view">
                    <?php /*
                            'observaciones:ntext'
                        */?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$script = <<<  JS
$(document).ready(function() {
    if($('#alert-save').html()!=undefined){
        setTimeout(() => {
            $('#alert-save').css('display', 'none');
        }, 1000);
    }
});
JS;
$this->registerJs($script);
?>