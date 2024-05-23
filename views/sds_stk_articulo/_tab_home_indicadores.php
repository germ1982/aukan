<?php

use app\assets\HighchartAsset;
use app\controllers\SiteController;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_orden_compra;
use app\models\View_stock_detalle_oc;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

HighchartAsset::register($this);

?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <?= $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    Sds_stk_articulo::find()->where(['organismo' => $id_organismo])->orderBy(['orden' => SORT_ASC])->all(),
                    'idarticulo',
                    'descripcion'
                ),
                'options' => ['onchange' => 'cargarComboOrdenCompra();refrescarGrafico();', 'id' => 'cmb_articulo'],
                'pluginOptions' => [
                    'allowClear' => false
                ],

            ])->label('Articulo');
            ?>
        </div>
        <div class="col-md-2">
            <label class="control-label" for="cmbAnio">Año</label>
            <select class="form-control" id="cmb_anio" name="cmb_anio" style="padding-left: 2px;padding-right: 2px;" onchange="refrescarGrafico();">
            </select>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'idordencompra')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    Sds_stk_orden_compra::find()->where(
                        "idordencompra in (select idordencompra from view_stock_detalle_oc) and idorganismo=$id_organismo"
                    )->all(),
                    'idordencompra',
                    'numero'
                ),
                'options' => [
                    'placeholder' =>
                    'Seleccionar OC...',
                    'id' => 'cmb_ordencompra',
                    'onchange' => 'refrescarGrafico();',
                    'disabled' => false,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label('Orden de compra'); ?>
        </div>

        <div class="col-md-2">
        <?= SiteController::actionGet_input_select2($form,$model,'organizacion_social','cmb_organizacion_social',Sds_com_configuracion::find()->where("activo = 1 and idconfiguraciontipo = ".Sds_com_configuracion_tipo::ORGANIZACION_SOCIAL)->all(),'idconfiguracion','descripcion','Organizacion Social','Organizacion Social ...',null,'refrescarGrafico();') ?>
        </div>
    </div>
    <div class="col-md-12" style="padding-top: 25px;">
        <figure class="highcharts-figure">
            <div id="graph_stock">

            </div>
        </figure>
    </div>
</div>

<?php
$this->registerJs(
    "$(document).ready(function () {
        cargarComboOrdenCompra();        
    });"
);
?>
<script>
    function refrescarGrafico() {
        let idarticulo = $('#cmb_articulo').val();
        let idordencompra = $('#cmb_ordencompra').val();
        let idorganizacionsocial = $('#cmb_organizacion_social').val();
        let anio = $('#cmb_anio').val();
        render_graph_stock(idarticulo, anio, idordencompra,idorganizacionsocial);
    }

    function cargarComboOrdenCompra() {
        let idarticulo = $('#cmb_articulo').val();
        let idorganismo = "<?= $id_organismo ?>";
        $.post("index.php?r=sds_stk_orden_compra/get_cmb_ordenes_compra_graph&idarticulo=" + idarticulo + "&idorganismo=" + idorganismo, function(data) {
            $("#cmb_ordencompra").html(data);
            $("#cmb_ordencompra").val(null);
            let settings = $("#cmb_ordencompra").attr('data-krajee-select2');
            settings = window[settings];
            $("#cmb_ordencompra").select2(settings);
            refrescarGrafico();
        });
    }
</script>