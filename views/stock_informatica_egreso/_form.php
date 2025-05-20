<?php

use app\models\StockInformaticaEgresoDetalleSearch;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

?>

<div id="formulario_principal">
    <?php $form = ActiveForm::begin(['id' => 'formulario']); ?>

    <?= $this->render('_tab1', ['form' => $form, 'model' => $model]) ?>

    <?= $this->render('_tab2', ['form' => $form, 'model' => $model]) ?>


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
                    $model_item = new StockInformaticaEgresoDetalleSearch();
                    echo $this->render('/stock_informatica_egreso_detalle/_form', [
                        'model' => $model_item,
                        'idpadreitem' => $model->idegreso, // <----- aca le paso como parametro el id de la recepcion
                        'botones' => true
                    ]);
                ?>
            </div>
        </section>
    </div>
</div>