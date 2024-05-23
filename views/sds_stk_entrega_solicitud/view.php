<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_com_persona;
use app\models\Sds_stk_articulo;
use app\models\sds_stk_entrega_solicitud_item;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega_solicitud */
?>

<div class="sds-stk-entrega-solicitud-view">
    <div class="panel">
        <div class="panel-heading" style="padding:10px; background-color: #EEEEEE;border: 1px solid #ccc;">
            <div class="row">
                <div class="col-md-9" style="font-size:15px;padding-top:5px">
                    <b>Fecha: <?= date('d/m/Y H:i:s', strtotime($model->fecha_hora)); ?></b>
                </div>
                <div class="col-md-3">
                    <?php if ($model->identrega != null) { ?>
                        <a class="btn btn-primary pull-right" href="<?= Url::to(['sds_stk_entrega/view', 'id' => $model->identrega]) ?>" target="_blank" role="button">Ver Entrega</a>
                    <?php } else { ?>
                        <div class="btn btn-warning pull-right">Entrega Pendiente</div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="panel-body" style="border: 1px solid #ccc;">
            <div class="row" style="border: 1px solid #ccc; font-size: 15px;background-color: #EEEEEE;margin:0 10px 10px;border-radius:5px;padding-bottom:10px;">
                <div class="row">
                    <div class="col-md-6" style="text-align:center"><b>Responsable</b></div>
                    <div class="col-md-6" style="text-align:center"><b>Organismo</b></div>
                </div>
                <div class="row">
                    <div class="col-md-6" style="text-align:center">
                        <?php
                        $contacto = Mds_org_contacto::findBySql(
                            "SELECT c.*, CONCAT(p.apellido,', ', p.nombre) nombre FROM mds_org_contacto c
                    JOIN sds_com_persona p ON c.idpersona=p.idpersona WHERE c.idcontacto=" . $model->idcontacto
                        )->one()
                        ?>
                        <span class="label label-primary" style="font-size:12px;margin: "><?= $contacto->nombre ?></span>
                    </div>
                    <div class="col-md-6" style="text-align:center">
                        <?php $organismo = Mds_org_organismo::findOne($model->idorganismo) ?>
                        <span class="label label-primary" style="font-size:12px;"><?= $organismo->descripcion ?></span>
                    </div>
                </div>
            </div>
            <div class="row" style="border: 1px solid #ccc; font-size: 15px;margin-bottom:5px; background-color: #EEEEEE;margin:0 10px 10px;border-radius:5px;padding-bottom:5px;">
                <div class="row">
                    <div class="col-md-6" style="text-align:center">
                        <b>Destinatario</b>
                    </div>
                    <div class="col-md-6" style="text-align:center">
                        <b>DNI</b>
                    </div>
                </div>
                <div class="row" style="margin-bottom:5px">
                    <div class="col-md-6" style="text-align:center">
                        <?php $persona = Sds_com_persona::findOne($model->idpersona) ?>
                        <span class="label label-primary" style="font-size:12px;text-align:center"><?= ($persona != null ? $persona->apellido . ', ' . $persona->nombre : '- SIN DATOS -') ?></span>
                    </div>
                    <div class="col-md-6" style="text-align:center">
                        <span class="label label-primary" style="font-size:12px;text-align:center"><?= $model->dni ?></span>
                    </div>
                </div>
            </div>
            <div class="row" style="border: 1px solid #ccc;border-radius: 5px;font-size: 15px;margin:0 10px 15px;background-color:#EEEEEE;">
                <div class="row" style="margin-left: 150px;">
                    <b>Observaciones</b>
                </div>
                <div class="row" style="margin-left: 50px;">
                    <div class="col-md-12">
                        <?= $model->observaciones ?>
                    </div>
                </div>
            </div>
           <?php $items = sds_stk_entrega_solicitud_item::find()->where(['identregasolicitud' => $model->identregasolicitud])->all(); ?>
           <?php if ($items !=null):?>
                <div class="row" style="border: 1px solid #ccc;border-radius: 5px;font-size: 15px;margin:0 10px 0;background-color:#EEEEEE;">
                    <div class="row" style="border-bottom: 1px solid #ccc;background-color:#08c;color:#fff;margin:0 0px 0;border-top-left-radius: 5px;border-top-right-radius:5px">
                        <div class="col-md-6" style="text-align:center;font-size:15px;">
                            <b>Cantidad</b>
                        </div>
                        <div class="col-md-6" style="text-align:center;font-size:15px">
                            <b>Articulos</b>
                        </div>
                    </div>
                    <?php foreach ($items as $item) {
                        $articulo = Sds_stk_articulo::findOne($item->idarticulo); ?>
                        <div class="row" style="margin-bottom: 5px;border-bottom: 1px solid #ccc;margin:0 10px 0; ">
                            <div class="col-md-6" style="text-align:center;">
                                <span style="font-size:12px;"><?= $item->cantidad ?></span>
                            </div>
                            <div class="col-md-6" style="text-align:center;">
                                <span style="font-size:12px;"><?= $item->idarticulo . ' - ' . $articulo->descripcion ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
           <?php else: ?>
                <div class="row" style="text-align:center;margin-top:10px;">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                    <span style="background-color:#ed9c28;color:#777;border-radius:5px;font-size:15px;padding: 5px;"><b>- SIN ITEMS -</b></span>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>