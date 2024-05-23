<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_stk_articulo;
use app\models\Mds_seg_usuario;

if (isset($idinventario)) {
    $model->idinventario = $idinventario;
}

?>

<div class="sds-stk-inventario-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idinventario')->hiddenInput(["id" => "hidden_input_item_idinventario"])->label(false) ?>
    <div class="row">
        <div class="col-md-8">
            <div class="input-group">
                <?php
                $idorganismo = Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario)->organismo_stock;

                $consulta = "SELECT a.idarticulo as idarticulo, CONCAT(a.descripcion,' (en ',c.descripcion,')') as descripcion
                                        FROM sds_stk_articulo a
                                        JOIN sds_com_configuracion c on A.unidad_medida = c.idconfiguracion
                                        WHERE a.activo = 1 and a.organismo = $idorganismo order by a.descripcion";
                echo $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Sds_stk_articulo::findBySql($consulta)->all(),
                        'idarticulo',
                        'descripcion'
                    ),
                    'options' => ['id' => 'cmb_articulo', 'placeholder' => 'Seleccionar Articulo ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Articulo');
                ?>
            </div>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput(['maxlength' => true, 'id' => 'input_cantidad']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php if (isset($botones)) { ?>
        <br>
        <div class="form-group">
            <?= Html::button(
                'Guardar Item',
                [
                    'id' => 'btnGuardarInterno',
                    'class' => 'btn btn-success',
                    'onclick' => 'validar_datos_item_inventario();'
                ]
            ) ?>
            <?= Html::button('Cerrar Item', [
                'id' => 'btnCerrarInterno',
                'class' => 'btn btn-default',
                'onclick' => '$("#abm_items").hide();
                    $("#div_formulario_inventario").show();
                    $("#btnGuardar").show();
                    $("#btnCerrar").show();
                    limpiar_campos_item();' //desarrollar esta funcion
            ]);
            ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

$script = <<<  JS

    function validar_datos_item_inventario() {
        idinventario = $("#hidden_input_item_idinventario").val();
        idarticulo = $("#cmb_articulo").val();
        cantidad = $("#input_cantidad").val();
        ban=0;
        aux = 'Error:';

            if(!(idinventario>0))
                {
                    aux = aux + ' /// Falta el idinventario';
                    ban=1;
                }
            if(!(idarticulo>0))
                {
                    aux = aux + ' /// Falta el articulo';
                    ban=1;
                }

            if(!(cantidad>0))
                {
                    aux = aux + ' /// Falta la cantidad';
                    ban=1;
                }
                if(ban==1)
                {
                    $('#txt_mensaje').html(aux);
                }
            else
                {
                    $.post("index.php?r=sds_stk_inventario_item/validar_item_existente&idinventario=" + idinventario + "&idarticulo=" + idarticulo, function(data) 
                    {
                        
                        if(data>0)
                            {
                                aux = 'El articulo seleccionado ya existe en el inventario numero ' + data;
                                $('#txt_mensaje').html(aux);
                            }
                        else
                            {
                                    $('#txt_mensaje').html('guardado');
                                    guardar_item();
                                    actualizar_grilla();
                            }
                    });

                }
    }
    function guardar_item()
        {
            idinventario = $("#hidden_input_item_idinventario").val();
            idarticulo = $("#cmb_articulo").val();
            cantidad = $("#input_cantidad").val();
    

            $.ajax({
                url: "index.php?r=sds_stk_inventario_item/create_ajax&idinventario=" + idinventario + "&idarticulo=" + idarticulo + "&cantidad=" + cantidad, //php que recibe la peticion
                type: 'post', 
                async: false,
                success: function(response) { 
                    console.log(response);
                    cerrar_form_item();

                }
            });
        }

    function actualizar_grilla()
        {
            let idinventario = $('#hidden_input_item_idinventario').val();
            if (idinventario) {
                aux = "index.php?r=sds_stk_inventario_item/grilla_items&idinventario=" + idinventario;
                $.post(aux, function(data) {
                    $("#div_grilla").html(data);
                });
            }
        }

    function limpiar_campos_item()
        {            
            $("#cmb_articulo").val("").trigger("change");
            $("#input_cantidad").val("").trigger("change");
            $('#txt_mensaje').html('');
        }
    function cerrar_form_item()
        {
            $("#abm_items").hide();
            $("#div_formulario_inventario").show();
            $("#btnGuardar").show();
            $("#btnCerrar").show();
            limpiar_campos_item();
        }

JS;

$this->registerJs($script);
//print_r($script);
?>