<?php

use app\models\Sds_stk_articulo;
use app\models\sds_stk_entrega_solicitud_item;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="sds-stk-entrega-solicitud-form-item">
    <div class="panel" style="border: 1px solid #ccc !important">
        <div class="panel-heading" style="padding:10px;margin-bottom:5px;background:#efe">
            <div class="row" style="margin-bottom:5px;font-size:15px;">
                <div class="col-md-3 text-center" style="padding-left:40px;">
                    <b>Cantidad</b>
                </div>
                <div class="col-md-9 text-center">
                    <b style="padding-right:70px;">Articulo</b>
                </div>
            </div>
        </div>
            <?php
            $items = Sds_stk_entrega_solicitud_item::find()->where(['identregasolicitud' => $model->identregasolicitud])->all();
            foreach ($items as $item) {
                $articulo = Sds_stk_articulo::findOne($item->idarticulo); ?>
            <div class="row" style="border-bottom: 1px solid #ccc;margin:0 10px 10px">
                <div class="col-md-3 text-center" style="padding-top:5px;">
                    <?= $item->cantidad ?>
                </div>
                <div class="col-md-8 text-center" style="padding-top:5px;">
                    <?= $item->idarticulo . ' - ' . $articulo->descripcion ?>
                </div>
                <div class="col-md-1">
                    <?=
                    Html::a(
                        '<i class="glyphicon glyphicon-minus-sign"></i>',
                        ['sds_stk_entrega_solicitud_item/delete', 'id' => $item->identregasolicituditem],
                        [
                            'class' => 'btn btn-danger',
                            'role' => 'modal-remote',
                            'style' => 'margin-bottom:10px;',
                            'title' => 'Eliminar Item',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => '¡Estas a punto de eliminar este item!',
                            'data-confirm-message' => '<div class="alert alert-danger text-center"><b>¿Seguro que desea continuar?</b></div>'
                        ],
                    );
                    ?>
                </div>
            </div>
<?php } ?>
</div>
</div>