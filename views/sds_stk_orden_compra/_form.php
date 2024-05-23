<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Sds_stk_orden_compra_item;
use app\models\Sds_stk_articulo;
use app\controllers\SiteController;

function GetFechaActual()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date("U"));

    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }

    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }

    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }

    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }

    $Fecha = "$dia/$mes/$mydate[year]";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}

$form_principal = "interv_form";

if ($model->fecha_emision != null) {
    $model->fecha_emision = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_emision)));
} else {
    $model->fecha_emision = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
}

if ($model->vencimiento != null) {
    $model->vencimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->vencimiento)));
} else {
    $model->vencimiento = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
}

if ($model->fecha_orden_compra != null) {
    $model->fecha_orden_compra = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_orden_compra)));
}




function botonAltaConfiguracion_desde_orden_compra($model, $tipo, $titulo, $form_principal)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => $tipo]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo, 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        //"disabled" => !$model->isNewRecord,
        'onclick' => '
                    $("#abm_configuracion").show();
                    $("#abm_configuracion_content").load($(this).attr("value"));
                    $("#abm_configuracion_title").html("' . $titulo . '");
                    $("#btnGuardar").hide();$("#btnCerrar").hide();
                    $("#' . $form_principal . '").hide();'
    ]);
}


?>

<div class="sds-stk-orden-compra-form " id="interv_form">

    <?php $form = ActiveForm::begin(); ?>
    <div id='div_campos' style=<?= $items == 1 ? 'display:none' : '' ?>>
        <?= $form->field($model, 'idordencompra')->hiddenInput(["id" => "hidden_input_id_model"])->label(false) ?>
        <div class="row">
            <div class="col-md-3">
                <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_emision', 'input_fecha_emision', 'Fecha Emision') ?>
            </div>
            <div class="col-md-3">
                <?= SiteController::actionGet_input_fecha($form, $model, 'vencimiento', 'input_vencimiento', 'Fecha vencimiento', null, null) ?>
            </div>
            <div class="col-md-3">
                <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_orden_compra', 'input_fecha_orden_compra', 'Fecha OC') ?>
            </div>
            <div class="col-md-3" style="padding-top: 30px;">
                <?= $form->field($model, 'nn')->checkbox(['checked' => ($model->nn ? true : false), 'id' => 'check_nn', 'onchange' => 'marcar_label_nn();']) ?>
            </div>
        </div>
        <div class='row'>
            <div class="col-md-3">
                <?= $form->field($model, 'expediente')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <span class="custom-label">
                    <div id="div_label_numero">aaaaaaaaaaaa</div>
                </span>
                <div style="padding-top: 4.5px;"><?= $form->field($model, 'numero')->textInput(['id' => 'input_numero'])->label(false) ?></div>
            </div>
            <div class=<?= $model->isNewRecord ? "col-md-7" : "col-md-5" ?>>
                <div class="input-group">
                    <?= $form->field($model, 'proveedor')->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PROVEEDOR, true),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '',
                            'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_PROVEEDOR
                        ]
                    );
                    ?>
                    <span class="input-group-btn">
                        <?php
                        $tipo = Sds_com_configuracion_tipo::TIPO_PROVEEDOR;
                        $titulo = "Nuevo Proveedor";
                        echo botonAltaConfiguracion_desde_orden_compra($model, $tipo, $titulo, $form_principal);
                        ?>
                    </span>
                </div>
            </div>
            <div class="col-md-2" style="<?= $model->isNewRecord ? "display:none" : '' ?>">
                <?= $form->field($model, 'importe_total')->textInput(['maxlength' => true, 'id' => 'input_importe_total', 'readonly' => true]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4" style="display:none">
                <div class="input-group">
                    <?= $form->field($model, 'tipo_norma_legal')->dropdownList(
                        ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_NORMA_LEGAL, true),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        [
                            'prompt' => '',
                            'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_NORMA_LEGAL
                        ]
                    );
                    ?>
                    <span class="input-group-btn">
                        <?php
                        $tipo = Sds_com_configuracion_tipo::TIPO_NORMA_LEGAL;
                        $titulo = "Nuevo tipo de norma legal";
                        echo botonAltaConfiguracion_desde_orden_compra($model, $tipo, $titulo, $form_principal);
                        ?>
                    </span>
                </div>

            </div>
            <div class="col-md-3" style="display:none">
                <?= $form->field($model, 'norma_legal')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-2" style="display:none" id="div_aux">
                <?= $form->field($model, 'idordencompra')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>
    <!-- LINEA GRILLA DE ITEMS ##################################################################################################################################################### -->
    <div class="row" style="border-radius: 5px; padding: 15px;<?= $model->isNewRecord ? 'display:none' : '' ?>">
        Items:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<!-- DIV NUEVA CONFIGURACION ##################################################################################################################################################### -->
<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="abm_configuracion_title" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="abm_configuracion_content">
            </div>
        </section>
    </div>
</div>

<!-- DIV ITEMS ##################################################################################################################################################### -->
<div class="row" id="abm_items" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Item
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_item = new Sds_stk_orden_compra_item();
                echo $this->render('/sds_stk_orden_compra_item/_form', [
                    'model' => $model_item,
                    'idpadreitem' => $model->idordencompra, // <----- aca le paso como parametro el id de la recepcion
                    'botones' => true
                ]);
                ?>
            </div>
        </section>
    </div>
</div>

<!-- DIV Articulos ##################################################################################################################################################### -->
<div class="row" id="abm_articulos" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Articulo
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_articulo = new Sds_stk_articulo();
                echo $this->render('/sds_stk_articulo/_form', [
                    'model' => $model_articulo,
                    //'idrecepcion' => $model->idrecepcion, // <----- aca le paso como parametro el id de la recepcion
                    'botones' => true
                ]);
                ?>
            </div>
        </section>
    </div>
</div>
<script>
    refrescar_grilla();
    marcar_label_nn();

    function marcar_label_nn() {
        if ($('#check_nn').prop('checked')) {
            $('#div_label_numero').html('Numero <b>NN-</b>');
        } else {
            $('#div_label_numero').html('Numero');
        }
    }



    function refrescar_grilla() {
        id_model = $('#hidden_input_id_model').val();
        if (id_model) {
            aux = "index.php?r=sds_stk_orden_compra_item/grilla_items&id=" + id_model;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                recalcular_importe_total()
            });
        }
    }

    function recalcular_importe_total() {
        idordencompra = $('#hidden_input_id_model').val();
        aux = "index.php?r=sds_stk_orden_compra_item/recalcular_importe_total&idordencompra=" + idordencompra;
        $.post(aux, function(data) {
            aux = data ? trunc(data, 2) : null;
            if(aux){
                $("#input_importe_total").val('$' + aux);
            }else{
                console.warn('Error al actualizar importe total');
            }
        });
    }

    function mostrar_abm_item() {
        $("#abm_items").show();
        $('#interv_form').hide();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
    }

    function trunc(x, posiciones = 0) {
        var s = x.toString()
        var l = s.length
        var decimalLength = s.indexOf('.') + 1

        if (l - decimalLength <= posiciones) {
            return x
        }
        // Parte decimal del número
        var isNeg = x < 0
        var decimal = x % 1
        var entera = isNeg ? Math.ceil(x) : Math.floor(x)
        // Parte decimal como número entero
        // Ejemplo: parte decimal = 0.77
        // decimalFormated = 0.77 * (10^posiciones)
        // si posiciones es 2 ==> 0.77 * 100
        // si posiciones es 3 ==> 0.77 * 1000
        var decimalFormated = Math.floor(
            Math.abs(decimal) * Math.pow(10, posiciones)
        )
        // Sustraemos del número original la parte decimal
        // y le sumamos la parte decimal que hemos formateado
        var finalNum = entera +
            ((decimalFormated / Math.pow(10, posiciones)) * (isNeg ? -1 : 1))

        return finalNum
    }

    function eliminar_item(id_item) {
        $.post("index.php?r=sds_stk_orden_compra_item/delete&id=" + id_item, function(data) {

            refrescar_grilla();
            /* if(data>0)
                {
                    
                    
                    
                    //alert('eliminado');
                }
            else
                {
                    alert('no se ha eliminado')
                } */
        });
    }
</script>