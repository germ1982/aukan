<?php

use app\models\StockInformaticaEgresoDetalle;
use app\models\StockInformaticaEgresoDetalleSearch;
use yii\helpers\Json;

$array_detalles = $model->isNewRecord
    ? []
    : StockInformaticaEgresoDetalle::find()
    ->select(['idarticulo', 'cantidad']) // Selecciona solo los campos necesarios
    ->where(['idegreso' => $model->idegreso])
    ->asArray() // Obtiene un array simple
    ->all();

// Convertir el array PHP a JSON
$json_detalles = Json::htmlEncode($array_detalles);
$this->registerJs("let detallesArray = $json_detalles;", \yii\web\View::POS_HEAD);

?>
<!-- LINEA GRILLA DE ITEMS ##################################################################################################################################################### -->
<input type="hidden" id="detallesArray" name="detallesArray">
<div class="row" style="border-radius: 5px; padding: 15px;">
    Items:
    <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div>
</div>




<script>
    refrescar_grilla();

    function refrescar_grilla() {
        //console.log(detallesArray); // Verificar el contenido del array en la consola
        console.log('entro a refrescar grilla');
        //console.log('detallesArray: ',detallesArray);

        $.post(
            "index.php?r=stock_informatica_egreso_detalle/grilla_items", {
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
        console.log('detallesArray en validacion de existencia: ', detallesArray);
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