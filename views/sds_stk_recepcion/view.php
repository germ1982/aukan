<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_stk_orden_compra;
use yii\widgets\DetailView;


?>
 <input type="hidden" id="hidden_input_id_recepcion" name="hidden_input_id_recepcion" value="<?=$model->idrecepcion?>">

<script>
    refrescar_grilla();
    function refrescar_grilla()
        {
            id_recepcion = $('#hidden_input_id_recepcion').val();
            if(id_recepcion)
                {
                    aux = "index.php?r=sds_stk_recepcion_item/grilla_items_view&idrecepcion=" + id_recepcion;
                    $.post(aux, function(data) {$("#div_grilla").html(data);});
                }
        }
</script>

<div class="sds-stk-recepcion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idrecepcion',
                'label' => 'ID',
            ],
            [
                'attribute' => 'fecha',
                'label' => 'Fecha',
                'value' => function ($model) {
                    if ($model->fecha != null) {
                        $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
                        return "$fecha";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'proveedor',
                'label' => 'Proveedor',
                'value' => function ($model) {
                    if ($model->proveedor != null) {
                        $configuracion = Sds_com_configuracion::findOne($model->proveedor);
                        return "$configuracion->descripcion";
                    }
                    return "";
                },
            ],
            'pedido',
            'expediente',
            [
                'attribute' => 'idordencompra',
                'label' => 'Orden de Compra',
                'value' => function ($model) {
                    if ($model->idordencompra != null) {
                        $configuracion = Sds_stk_orden_compra::findOne($model->idordencompra);
                        return "$configuracion->numero";
                    }
                    return "";
                },
            ],
        ],
    ]) ?>

    <div class="row" style="border-radius: 5px; padding: 15px;">
        Items:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div> 
    </div>

</div>
