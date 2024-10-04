<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\StockInformaticaIngresoDetalleSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));


?>

<div id="formulario_principal">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idingreso')->hiddenInput(["id" => "hidden_input_id_model"])->label(false) ?>

    <?= $form->field($model, 'idusuario_carga')->hiddenInput(['id' => 'input_idusuario_carga'])->label(false) ?>
    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
        </div>
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado_recepcion', 'cmb_idempleado_recepcion', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Recepcion') ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idorigen', 'cmb_idorigen', Configuracion::get_configuraciones(ConfiguracionTipo::STOCK_ORIGEN), 'id_configuracion', 'descripcion', 'Origen') ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'origen_referencia')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <!-- LINEA GRILLA DE ITEMS ##################################################################################################################################################### -->
    <div class="row" style="border-radius: 5px; padding: 15px;<?= $model->isNewRecord ? 'display:none' : '' ?>">
        Items:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div>
    </div>



    <?php ActiveForm::end(); ?>
</div>

<!-- DIV ITEMS ##################################################################################################################################################### -->
<div class="row" id="abm_items" style="display:none;">
    <div class="col-md-12">
        <section>
            <header>
                <h3 >
                    Agregar Item
                </h3>
            </header>
            <div class="panel-body">
                <?php
                    $model_item = new StockInformaticaIngresoDetalleSearch();
                    echo $this->render('/stock_informatica_ingreso_detalle/_form', [
                        'model' => $model_item,
                        'idpadreitem' => $model->idingreso, // <----- aca le paso como parametro el id de la recepcion
                        'botones' => true
                    ]);
                ?>
            </div>
        </section>
    </div>
</div>


<script>
    refrescar_grilla();

    function refrescar_grilla() {
        id_model = $('#hidden_input_id_model').val();
        if (id_model) {
            aux = "index.php?r=stock_informatica_ingreso_detalle/grilla_items&id=" + id_model;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                recalcular_importe_total()
            });
        }
    }

    function mostrar_abm_item() {
        $("#abm_items").show();
        $('#formulario_principal').hide();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
    }


    function eliminar_item(id_item) {
        $.post("index.php?r=stock_informatica_ingreso_detalle/delete&id=" + id_item, function(data) {
            refrescar_grilla();
        });
    }
</script>