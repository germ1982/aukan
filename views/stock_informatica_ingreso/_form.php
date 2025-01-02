<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\StockInformaticaIngresoDetalle;
use app\models\StockInformaticaIngresoDetalleSearch;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));

$array_detalles = $model->isNewRecord
    ? []
    : StockInformaticaIngresoDetalle::find()
    ->select(['idarticulo', 'cantidad']) // Selecciona solo los campos necesarios
    ->where(['idingreso' => $model->idingreso])
    ->asArray() // Obtiene un array simple
    ->all();

// Convertir el array PHP a JSON
$json_detalles = Json::htmlEncode($array_detalles);
$this->registerJs("let detallesArray = $json_detalles;", \yii\web\View::POS_HEAD);


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

    <div class="row" style="border-radius: 5px; padding: 15px;">
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
                <h5>
                    Agregar Item

                </h5>
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
    console.log(detallesArray); // Verificar el contenido del array en la consola
    console.log('entro a refrescar grilla');

    $.post(
        "index.php?r=stock_informatica_ingreso_detalle/grilla_items", 
        { detalles: JSON.stringify(detallesArray) }, // Pasar el array como JSON
        function(data) {
            $("#div_grilla").html(data); // Actualizar el div con la nueva grilla
        }
    );
}


    function mostrar_abm_item() {
        $("#abm_items").show();
        $('#formulario_principal').hide();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
    }

    function ocultar_abm_item() {
        $("#abm_items").hide();
        $('#formulario_principal').show();
        $("#btnGuardar").show();
        $("#btnCerrar").show();
    }


    function guardarDetalle() {
        let idArticulo = $('#idarticulo').val();
        let cantidad = $('#cantidad').val();

        // Validar campos
        if (!idArticulo || !cantidad) {
            alert("Debe completar todos los campos");
            return;
        }

        // Crear objeto de detalle
        let detalle = {
            idarticulo: idArticulo,
            cantidad: cantidad
        };

        // Añadir al array
        detallesArray.push(detalle);

        // Limpiar los campos del formulario
        $('#idarticulo').val('');
        $('#cantidad').val('');

        // Actualizar la grilla
        actualizarGrilla();
    }



    function eliminarDetalle(index) {
        detallesArray.splice(index, 1); // Eliminar del array
        actualizarGrilla(); // Actualizar la grilla
    }
</script>