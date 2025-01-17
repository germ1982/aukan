<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\StockDepositoIngresoDetalle;
use app\models\StockDepositoIngresoDetalleSearch;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));

$array_detalles = $model->isNewRecord
    ? []
    : StockDepositoIngresoDetalle::find()
    ->select(['idarticulo', 'cantidad']) // Selecciona solo los campos necesarios
    ->where(['idingreso' => $model->idingreso])
    ->asArray() // Obtiene un array simple
    ->all();

// Convertir el array PHP a JSON
$json_detalles = Json::htmlEncode($array_detalles);
$this->registerJs("let detallesArray = $json_detalles;", \yii\web\View::POS_HEAD);

$idUsuario = Yii::$app->user->identity->id;
$model->idusuario_carga = $model->isNewRecord ? $idUsuario : $model->idusuario_carga;
$model->idusuario_edicion = $idUsuario;

?>

<div id="formulario_principal">
    <?php $form = ActiveForm::begin(['id' => 'formulario']); ?>

    <input type="hidden" id="detallesArray" name="detallesArray">

    <?= $form->field($model, 'idingreso')->hiddenInput(["id" => "hidden_input_id_model"])->label(false) ?>

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
                $model_item = new StockDepositoIngresoDetalleSearch();
                echo $this->render('/stock_deposito_ingreso_detalle/_form', [
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
        //console.log(detallesArray); // Verificar el contenido del array en la consola
        console.log('entro a refrescar grilla');
        //console.log('detallesArray: ',detallesArray);

        $.post(
            "index.php?r=stock_deposito_ingreso_detalle/grilla_items", {
                detalles: JSON.stringify(detallesArray)
            }, // Pasar el array como JSON
            function(data) {
                $("#div_grilla").html(data); // Actualizar el div con la nueva grilla
            }
        );
        console.log('detallesArray despues de refrescar', detallesArray);
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
        let idArticulo = $('#cmb_articulos').val();
        let cantidad = $('#input_cantidad').val();

        // Validar campos
        if (!idArticulo || !cantidad) {
            alert("Debe completar todos los campos");
            return;
        }
        console.log('detallesArray en validacion de existencia: ',detallesArray);
        const existe = detallesArray.some(item => Number(item.idarticulo) === Number(idArticulo));

        if (existe) {
            alert('El artículo ya está en la lista.');
            return;
        }


        // Crear objeto de detalle
        let detalle = {
            idarticulo: idArticulo,
            cantidad: cantidad
        };

        // Añadir al array
        detallesArray.push(detalle);
        console.log(detallesArray); // Verificar el contenido del array en la consola
        // Limpiar los campos del formulario
        $('#cmb_articulos').val(null).trigger('change');
        $('#input_cantidad').val('');

        // Actualizar la grilla
        refrescar_grilla();
        ocultar_abm_item();
    }

    function eliminar_item(idarticulo) {
        // Buscar el índice del artículo a eliminar
        console.log('entro a eliminar con idarticulo: ', idarticulo);
        const index = detallesArray.findIndex(item => item.idarticulo == idarticulo);

        if (index !== -1) {
            detallesArray.splice(index, 1); // Eliminar el elemento del array
            refrescar_grilla(); // Actualizar la grilla
        }
    }

    function guardarFormulario() {
        // Convertir detallesArray a JSON
        $('#detallesArray').val(JSON.stringify(detallesArray));

        // Luego de esto, el formulario puede ser enviado de manera normal
        $('#formulario').submit(); // Suponiendo que el formulario tiene el ID 'formulario'
    }
</script>