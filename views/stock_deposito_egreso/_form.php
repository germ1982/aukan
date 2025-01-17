<?php

use app\models\StockDepositoEgresoDetalleSearch;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

?>

<style>
    /* Estilo para las pestañas (tabs) */
    .nav-tabs {
        border-bottom: 2px solid #ddd;
        /* Línea de borde debajo de las pestañas */
    }

    .nav-tabs>li>a {
        background-color: #2B3E4C;
        /* Fondo gris claro para las pestañas */
        color: #F4DFB9;
        /* Color de texto gris */
        border-radius: 8px 8px 0 0;
        /* Bordes redondeados en la parte superior */
        padding: 10px 20px;
        /* font-weight: bold; */
        text-transform: uppercase;
        /* Texto en mayúsculas */
        transition: background-color 0.3s ease, color 0.3s ease;
        /* Transición suave */
    }

    .nav-tabs>li>a:hover {
        background-color: #87B867;
        /* Color de fondo en hover */
        color: white;
        /* Color de texto en hover */
    }

    .nav-tabs>li.active>a {
        background-color: #87B867;
        /* Color de fondo de la pestaña activa */
        color: white;
        /* Color de texto en la pestaña activa */
        box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
        /* Sombra para darle un efecto destacado */
    }

    .nav-tabs>li.active>a:hover {
        background-color: #87B867;
        /* Sombra más oscura en hover de la pestaña activa */
    }

    /* Estilo para el contenido de la pestaña */
    .tab-content {
        background-color: #fff;
        /* Fondo blanco para el contenido */
        padding: 20px;
        border-radius: 0 0 8px 8px;
        /* Bordes redondeados en la parte inferior */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        /* Sombra suave para el contenido */
    }
</style>

<div id="formulario_principal">
    <?php $form = ActiveForm::begin(['id' => 'formulario']); ?>

    <?=
    Tabs::widget([
        'items' => [
            [
                'label' => 'Datos de Entrega',
                'content' => $this->render('_tab1', ['form' => $form, 'model' => $model]),
                'active' => true, // Define la pestaña activa

            ],
            [
                'label' => 'Articulos',
                'content' => $this->render('_tab2', ['form' => $form, 'model' => $model]),
            ],

        ],
    ]);
    ?>



    <?php ActiveForm::end(); ?>

</div>

<!-- DIV ITEMS ##################################################################################################################################################### -->
<div class="row" id="abm_items" style="display:none;">
    <div class="col-md-12">
        <section>
            <header>
                <h5>
                    Agregar Item
                </h5>
            </header>
            <div class="panel-body">
                <?php
                    $model_item = new StockDepositoEgresoDetalleSearch();
                    echo $this->render('/stock_deposito_egreso_detalle/_form', [
                        'model' => $model_item,
                        'idpadreitem' => $model->idegreso, // <----- aca le paso como parametro el id de la recepcion
                        'botones' => true
                    ]);
                ?>
            </div>
        </section>
    </div>
</div>