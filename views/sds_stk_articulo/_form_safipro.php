<?php

use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_articulo_safipro;

?>

<?= "<input type='hidden' id='hidden_input_id_model' value='$model->idarticulo'>";?>


<div class="sds-stk-articulo-view" id="interv_form">

<div class="row">

    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'idarticulo',
                    'label' => 'Id',
                ],
                'descripcion',
                [
                    'attribute' => 'activo',
                    //'label' => 'Sistema Operativo',
                    'value' => function ($model) 
                                    {
                                        if($model->activo)
                                        {
                                            return 'Si';
                                        }
                                        return "No";
                                    },
                ],
                [
                    'attribute' => 'rubro',
                    'value' => function ($model) 
                                    {
                                        $id = $model->rubro;
                                        if ($id != null) {
                                            $item = Sds_com_configuracion::findOne($id);
                                            return $item->descripcion;
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
                [
                    'attribute' => 'unidad_medida',
                    //'label' => 'Sistema Operativo',
                    'value' => function ($model) 
                                    {
                                        $id = $model->unidad_medida;
                                        if ($id != null) {
                                            $item = Sds_com_configuracion::findOne($id);
                                            return $item->descripcion;
                                        }
                                        return "";
                                    },
                ],

                [
                    'attribute' => 'ingresado',
                    'label' => 'Stock Ingresado',
                    'value'=> function ($model)
                            {
                                $stock_ingresado = $this->context->actionGet_stock_ingresado($model->idarticulo);
                                return $stock_ingresado;
                            }
                ],
                [
                    'attribute' => 'entregado',
                    'label' => 'Stock Entregado',
                    'value'=> function ($model)
                        {
                            $stock_entregado = $this->context->actionGet_stock_entregado($model->idarticulo);
                            return $stock_entregado;
                        }
                ],
                [
                    'attribute' => 'disponible',
                    'label' => 'Stock Disponible',
                    'value'=> function ($model)
                        {
                            $stock_disponible = $this->context->actionGet_stock_disponible($model->idarticulo);
                            return $stock_disponible;
                        }
                ],
            ],
        ]) ?>
    </div>
</div>

        <!-- LINEA GRILLA DE ITEMS ##################################################################################################################################################### -->
        <div class="row" style="border-radius: 5px; padding: 15px;">
            Items:
            <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div> 
        </div>
 
        

</div>

<!-- DIV ITEMS ##################################################################################################################################################### -->
<div class="row" id="abm_items" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Item de Safipro a <?=$model->descripcion?>
                </h3>
            </header>
            <div class="panel-body">
                <?php
                    $model_item = new Sds_stk_articulo_safipro();
                    echo $this->render('/sds_stk_articulo_safipro/_form', [
                        'model' => $model_item,
                        'idpadreitem' => $model->idarticulo, 
                        'botones' => true
                    ]);
                ?>
            </div>
        </section>
    </div>
</div>

<script> 
    
    refrescar_grilla();
    function refrescar_grilla()
        {
            id_model = $('#hidden_input_id_model').val();
            if(id_model)
                {
                    aux = "index.php?r=sds_stk_articulo_safipro/grilla_items&id=" + id_model;
                    $.post(aux, function(data) {$("#div_grilla").html(data);});
                }
        }

    function mostrar_abm_item()
        {
            $("#abm_items").show();
            $('#interv_form').hide();
            $("#btnCerrar").hide();
        }


    function eliminar_item(id_item)
        {
            $.post("index.php?r=sds_stk_articulo_safipro/delete&id=" + id_item, function(data) 
            {
                if(data>0)
                    {
                        refrescar_grilla();
                        //alert('eliminado');
                    }
                else
                    {
                        alert('no se ha eliminado')
                    }
            });
        }
    
</script>