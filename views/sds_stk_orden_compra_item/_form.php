<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_stk_articulo;
use app\models\Mds_seg_usuario;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_orden_compra_item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-stk-orden-compra-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div style="display:none"> 
                <?php 
                    if(isset($idpadreitem)) 
                        {$model->idordencompra = $idpadreitem;}
                    echo $form->field($model, 'idordencompra')->textInput(['id'=>'input_idordencompra'])->label('idordencompra');
                ?>    
    </div>

    <div class="row">
            <div class="col-md-8">
                <div class="input-group"> 
                        <?php 
                            //$consulta = "SELECT * FROM sds_stk_articulo WHERE activo = 1 order by descripcion";
                            $usuario = Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario);
                            $idorganismo = $usuario->organismo_stock;

                            $consulta = "SELECT A.idarticulo as idarticulo, CONCAT(A.descripcion,' (en ',C.descripcion,')') as descripcion
                                        FROM sds_stk_articulo A
                                        INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
                                        WHERE A.activo = 1 and A.organismo = $idorganismo order by A.descripcion";
                            echo $form->field($model, 'idarticulo')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(Sds_stk_articulo::findBySql($consulta)->all(),
                                                'idarticulo',
                                                'descripcion'
                                            ),
                                            
                                            'options' => ['id'=>'cmb_articulo','placeholder' => 'Seleccionar Articulo ...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])->label('Articulo');
                        ?>
                    <span class="input-group-btn" style="padding-top:27px">
                        <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                                                                        'class' => 'btn btn-primary',
                                                                        'id' => 'btnNuevoArticulo',
                                                                        'title' => "Nuevo Articulo",
                                                                        'data-toggle' => 'tooltip',
                                                                        'onclick' => "js:mostrar_abm_articulos();"])
                        ?>
                    </span>

                </div>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'cantidad')->textInput(['maxlength' => true,'id'=>'input_cantidad']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'importe_unitario')->textInput(['maxlength' => true,'id'=>'input_importe_unitario']) ?>
            </div>
    </div>

    <div class="row">
            <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
        </div>
  
    <?php if (isset($botones)) { ?>
            <br>
            <div class="form-group">
                <?php //echo Html::submitButton($model->isNewRecord ? 'Guardar Item de Recepcion' : 'update', [ 'id' => 'btnGuardarInterno','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
                    echo Html::Button($model->isNewRecord ? 'Guardar Item' : 'update', 
                                    [   'id' => 'btnGuardarItem',
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
            $("#btnGuardar").show();
            $("#btnCerrar").show();
            limpiar_campos_item();
        }


        function limpiar_campos_item()
        {//seguir aca, tengo que armar bien el form del item, hacer la funcion de validado, y de guardado
            
            $("#cmb_articulo").val("").trigger("change");
            $("#input_cantidad").val("").trigger("change");
            $("#input_importe_unitario").val("").trigger("change");
            $('#txt_mensaje').html('');
        }
        function mostrar_abm_articulos()
        {
            $("#abm_articulos").show();
            $('#abm_items').hide();
        }


        function validar_datos_item()
        {
            idordencompra = $("#input_idordencompra").val(); console.log('idordencompra: ');console.log(idordencompra);
            id_articulo = $("#cmb_articulo").val(); console.log('id_articulo: ');console.log(id_articulo);
            cantidad = $("#input_cantidad").val(); console.log('cantidad: ');console.log(cantidad);
            importe_unitario = $("#input_importe_unitario").val(); console.log('importe_unitario: ');console.log(importe_unitario);
            ban=0;
            aux = 'Error:';
            
            if(!(idordencompra>0))
                {
                    aux = aux + ' /// Falta el id de la orden de compra';
                    ban=1;
                }

            if(!(id_articulo>0))
                {
                    aux = aux + ' /// Falta el articulo';
                    ban=1;
                }

            if(!(cantidad>0))
                {
                    aux = aux + ' /// Falta la cantidad';
                    ban=1;
                }
            if(!(importe_unitario>0))
                {
                    aux = aux + ' /// Falta el importe unitario';
                    ban=1;
                }

            if(ban==1)
                {
                    $('#txt_mensaje').html(aux);
                    //alert(aux);
                }
            else
                {
                    console.log("no hubo error y debe validar la repeticion");
                    let url = "index.php?r=sds_stk_orden_compra_item/validar_item_existente&idordencompra=" + idordencompra + "&id_articulo=" + id_articulo;
                    console.log(url);
                    $.post(url, function(data) 
                    {
                        console.log(data);
                        
                        if(data!=0)
                            {
                                aux = 'El articulo seleccionado ya existe en la orden de compra numero ' + data;
                                $('#txt_mensaje').html(aux);
                            }
                        else
                            {
                                    $('#txt_mensaje').html('guardado');
                                    guardar_item();
                                    actualizar_grilla();
                                    recalcular_importe_total();
                            }
                    });

                }

        }

    function guardar_item()
        {
            idordencompra = $("#input_idordencompra").val();
            id_articulo = $("#cmb_articulo").val();
            cantidad = $("#input_cantidad").val();
            importe_unitario = $("#input_importe_unitario").val();    

            $.ajax({
                url: "index.php?r=sds_stk_orden_compra_item/create_ajax&idordencompra=" + idordencompra + "&id_articulo=" + id_articulo + "&importe_unitario=" + importe_unitario + "&cantidad=" + cantidad, //php que recibe la peticion
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
            idordencompra = $("#input_idordencompra").val();
            aux = "index.php?r=sds_stk_orden_compra_item/grilla_items&id=" + idordencompra;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                limpiar_campos_item();
            });
        }
</script>