<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="sds-stk-articulo-safipro-form">

    <?php $form = ActiveForm::begin(); ?>

    <div style="display:none"> 
                <?php 
                    if(isset($idpadreitem)) 
                        {$model->idarticulo = $idpadreitem;}
                    echo $form->field($model, 'idarticulo')->textInput(['id'=>'input_idarticulo'])->label('idarticulo');
                ?>    
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'clase')->textInput(['maxlength' => true,'id'=>'input_clase']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'item')->textInput(['maxlength' => true,'id'=>'input_item']) ?>
        </div>
    </div>
    <div class="row">
            <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
        </div>
  
    <?php if (isset($botones)) { ?>
            <br>
            <div class="form-group">
                <?= Html::Button('Guardar Item',[
                        'id' => 'btnGuardarItem',
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                        'onclick' => 'validar_datos_item();'])
                ?>
                <?= Html::button('Cerrar', [
                    'id' => 'btnCerrarItem',
                    'class' => 'btn btn-default',
                    'onclick' => 'cerrar_form_item();'
                ]);
                ?>
            </div>
        <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<script>
        function cerrar_form_item()
        {
            $("#abm_items").hide();
            $("#interv_form").show();
            $("#btnCerrar").show();
            limpiar_campos_item();
        }


        function limpiar_campos_item()
        {
            $("#input_clase").val("").trigger("change");
            $("#input_item").val("").trigger("change");
            $('#txt_mensaje').html('');
        }



        function validar_datos_item()
        {
            idarticulo = $("#input_idarticulo").val(); console.log('idarticulo: ');console.log(idarticulo);
            clase = $("#input_clase").val(); console.log('input_clase: ');console.log(input_clase);
            item = $("#input_item").val(); console.log('input_item: ');console.log(input_item);

            ban=0;
            aux = 'Error:';
            
            if(!(idarticulo>0))
                {
                    aux = aux + ' /// Falta el id del articulo';
                    ban=1;
                }

            if(!(clase>0))
                {
                    aux = aux + ' /// Falta la clase';
                    ban=1;
                }

            if(!(item>0))
                {
                    aux = aux + ' /// Falta el item';
                    ban=1;
                }

            if(ban==1)
                {
                    $('#txt_mensaje').html(aux);
                    //alert(aux);
                }
            else
                {
                    $.post("index.php?r=sds_stk_articulo_safipro/validar_item_existente&idarticulo=" + idarticulo + "&clase=" + clase + "&item=" + item, function(data) 
                    {
                        
                        if(data!=0)
                            {
                                aux = 'El Item seleccionado ya existe en el articulo ' + data;
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
            idarticulo = $("#input_idarticulo").val();
            clase = $("#input_clase").val();
            item = $("#input_item").val();

            $.ajax({
                url: "index.php?r=sds_stk_articulo_safipro/create_ajax&idarticulo=" + idarticulo + "&clase=" + clase + "&item=" + item, //php que recibe la peticion
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
            idarticulo = $("#input_idarticulo").val();
            aux = "index.php?r=sds_stk_articulo_safipro/grilla_items&id=" + idarticulo;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                limpiar_campos_item();
            });
        }
</script>