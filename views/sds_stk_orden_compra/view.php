<?php
use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
?>

<input type="hidden" id="hidden_input_id" name="hidden_input_id" value="<?=$model->idordencompra?>">

<script>
    refrescar_grilla();
    function refrescar_grilla()
        {
            id = $('#hidden_input_id').val();
            if(id)
                {
                    aux = "index.php?r=sds_stk_orden_compra_item/grilla_items_view&id=" + id;
                    $.post(aux, function(data) {$("#div_grilla").html(data);});
                }
        }
</script>

<div class="sds-stk-orden-compra-view">
    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'fecha_emision',
                        'label' => 'Fecha Emision',
                        'value' => function ($model) {
                            if ($model->fecha_emision != null) {
                                $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_emision)));
                                return "$fecha";
                            }
                            return "";
                        },
                    ],
                    [
                        'attribute' => 'vencimiento',
                        'label' => 'Fecha Vencimiento',
                        'value' => function ($model) {
                            if ($model->vencimiento != null) {
                                $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->vencimiento)));
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
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'expediente',
                    'numero',
                    /* 'norma_legal',
                    [
                        'attribute' => 'tipo_norma_legal',
                        'label' => 'Tipo Norma Legal',
                        'value' => function ($model) {
                            if ($model->tipo_norma_legal != null) {
                                $configuracion = Sds_com_configuracion::findOne($model->tipo_norma_legal);
                                return "$configuracion->descripcion";
                            }
                            return "";
                        },
                    ], */
                    [
                        'attribute' => 'importe_total',
                        'value' => "$$model->importe_total",
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="row" style="border-radius: 5px; padding: 15px;">
        Items:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div> 
    </div>
</div>
