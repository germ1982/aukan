<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_deposito;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_recepcion;
use Mpdf\Tag\Div;

$botones = true;
?>

<div class="sds-stk-recepcion-item-form">  
    <?php $form = ActiveForm::begin(['action' => ['sds_stk_recepcion_item/' . ($model->isNewRecord ? 'create_ext' : 'update'),'id' => $model->idrecepcionitem], 'id' => $model->formName()]); ?>

        <div class="row">
            <div style="display:none"> 
                <?php 
                    if(isset($idrecepcion)) 
                        {
                            $model->idrecepcion = $idrecepcion;//esto lo uso porque como invoco el form desde otro form, el form padre pasa el idrecepcion como parametro
                            $model->idordencompra = Sds_stk_recepcion::findOne($model->idrecepcion)->idordencompra;
                        }
                    echo $form->field($model, 'idrecepcion')->textInput(['id'=>'input_idrecepcion'])->label('ID Recepcion');

                    echo $form->field($model, 'idordencompra')->textInput(['id'=>'input_orden_compra'])->label('Orden Compra');
                ?>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php 
                    $usuario = Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario);
                    $idorganismo = $usuario->organismo_stock;

                    $consulta = "SELECT * FROM sds_stk_deposito WHERE idorganismo = $idorganismo ORDER BY descripcion";
                    if($usuario->iddeposito)
                    {
                        $model->deposito = $usuario->iddeposito;
                        $consulta = "SELECT * FROM sds_stk_deposito WHERE iddeposito = $usuario->iddeposito";
                    }
                    
                    echo $form->field($model, 'deposito')->dropDownList(
                    ArrayHelper::map(
                        Sds_stk_deposito::findBySql($consulta)->all(),
                        'iddeposito',
                        'descripcion'
                    ),['prompt' => '','id'=>'cmb_deposito']
                )->label('Deposito');
                ?>
            </div>
            <div class="col-md-7">
                <div class="input-group"> 
                        <?php 
                            //$consulta = "SELECT * FROM sds_stk_articulo WHERE activo = 1 order by descripcion";
                            $usuario = Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario);
                            $idorganismo = $usuario->organismo_stock;

                            $consulta = "SELECT A.idarticulo as idarticulo, CONCAT(A.descripcion,' (en ',C.descripcion,')') as descripcion
                            FROM sds_stk_articulo A
                            INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
                            WHERE A.activo = 1 and A.organismo = $idorganismo
                            order by A.descripcion";

                            $idordencompra = $model->idordencompra;
                            //si existe la orden de compra cambia la consulta para que muestre solo los articulos de esa orden
                            if($idordencompra)
                                {
                                    $sql_solicitado = "SELECT SUM(soci.cantidad) 
                                                        FROM sds_stk_orden_compra_item soci
                                                        WHERE soci.idarticulo = A.idarticulo and soci.idordencompra = $idordencompra";

                                    $sql_recepcionado = "SELECT CASE WHEN rri.cantidad IS NULL THEN 0 ELSE SUM(rri.cantidad) END recepcionado 
                                                        FROM sds_stk_recepcion_item rri
                                                        WHERE rri.idarticulo = A.idarticulo and rri.idrecepcion = $model->idrecepcion";

                                    $sql_pendiente = "($sql_solicitado) -($sql_recepcionado)";

                                    $consulta = "SELECT A.idarticulo as idarticulo, 
                                                CONCAT(A.descripcion,' (en ',C.descripcion,') (', (ROUND($sql_pendiente,0)),' pendientes)') as descripcion,
                                                ($sql_solicitado) as solicitado,
                                                ($sql_recepcionado) as recepcionado,
                                                ($sql_pendiente) as pendiente
                                    
                                                FROM sds_stk_articulo A
                                                INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
                                                INNER JOIN sds_stk_orden_compra_item oci on oci.idarticulo = A.idarticulo
                                                
                                                WHERE A.activo = 1 
                                                    and A.organismo = $idorganismo
                                                    and oci.idordencompra = $idordencompra
                                                    and ($sql_pendiente)>0
                                                order by A.descripcion";
                                }

                            
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
                        <?php /* Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                                                                        'class' => 'btn btn-primary',
                                                                        'id' => 'btnNuevoArticulo',
                                                                        'title' => "Nuevo Articulo",
                                                                        'data-toggle' => 'tooltip',
                                                                        'onclick' => "js:mostrar_abm_articulos();"]) */
                        ?>
                    </span>

                </div>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'cantidad')->textInput(['maxlength' => true,'id'=>'input_cantidad']) ?>
                <!-- Aca tengo que ver una forma de que no pase el maximo pendiente -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">    
                <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true,'id'=>'input_descripcion']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
        </div>
        <?php if (isset($botones)) { ?>
            <br>
            <div class="form-group">
                <?php //echo Html::submitButton($model->isNewRecord ? 'Guardar Item de Recepcion' : 'update', [ 'id' => 'btnGuardarInterno','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
                    echo Html::Button($model->isNewRecord ? 'Guardar Item de Recepcion' : 'update', 
                                    [   'id' => 'btnGuardarItemRecepcion',
                                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                                        'onclick' => 'validar_datos_item_recepcion();'])
                ?>
                <?= Html::button('Cerrar Item de Recepcion', [
                    'id' => 'btnCerrarInterno',
                    'class' => 'btn btn-default',
                    'onclick' => 'cerrar_recepcion_item();'
                ]);
                ?>
            </div>
        <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php



$script = <<<  JS

$('form#{$model->formName()}').on('beforeSubmit',function(e){
    
    var \$form = $(this);
    $.post(

        \$form.attr("action"),
        \$form.serialize()

    )
    .done(function(result){    
        if(result > 0){
            $(\$form).trigger("reset");      
            $('#abm_items').hide(); 
            $('#interv_form').show(); 
            e.preventDefault();
            var aux = $("#input_idrecepcion").val();
            aux = "index.php?r=sds_stk_recepcion_item/grilla_items&idrecepcion=" + aux;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                $("#btnGuardarInterno").show();
                $("#btnCerrarInterno").show();
            });            
        }else{
            $("#message").html(result);
        }
    }).fail(function(){
        console.log("server error");
    });
   
    return false;
});

   

JS;
$this->registerJs($script);

?>



<script>
    function validar_item_existente()
        {
            id_recepcion = $("#input_idrecepcion").val();
            id_articulo = $("#cmb_articulo").val();
            //aux = 0;
            //alert('id_recepcion: ' + id_recepcion + '\nid_articulo: ' + id_articulo); 

            $.post("index.php?r=sds_stk_recepcion_item/validar_item_existente&id_recepcion=" + id_recepcion + "&id_articulo=" + id_articulo, function(data) 
            {
                //alert(data);
                //return data;
                $("#hidden_input_id_item").val(data);


            });
        }
    function validar_datos_item_recepcion()
        {
            id_recepcion = $("#input_idrecepcion").val();
            id_articulo = $("#cmb_articulo").val();
            descripcion = $("#input_descripcion").val();
            cantidad = $("#input_cantidad").val();
            id_deposito = $("#cmb_deposito").val();
            ban=0;
            aux = 'Error:';
            //alert('id_recepcion: ' + id_recepcion + '\nid_articulo: ' + id_articulo + '\ndescripcion: ' + descripcion + '\ncantidad: ' + cantidad + '\nid_deposito: ' + id_deposito);
            if(!(id_recepcion>0))
                {
                    aux = aux + ' /// Falta el id de recepcion';
                    ban=1;
                }

            if(!(id_deposito>0))
                {
                    aux = aux + ' /// Falta el deposito';
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

            aux_ban = validar_cantidad();
            //alert(aux_ban);

            if(aux_ban==1)
                {
                    
                    aux = aux + ' /// La cantidad exede los articulos pendientes';
                    ban=1;
                }


            if(descripcion=='')
                {
                    aux = aux + ' /// Falta la descripcion';
                    ban=1;
                }

            if(ban==1)
                {
                    $('#txt_mensaje').html(aux);
                    //alert(aux);
                }
            else
                {
                    $.post("index.php?r=sds_stk_recepcion_item/validar_item_existente&id_recepcion=" + id_recepcion + "&id_articulo=" + id_articulo, function(data) 
                    {
                        
                        if(data!=0)
                            {
                                aux = 'El articulo seleccionado ya existe en la recepcion numero ' + id_recepcion;
                                $('#txt_mensaje').html(aux);
                                //alert('El articulo seleccionado ya existe en la recepcion numero ' + id_recepcion);
                            }
                        else
                            {
                                    $('#txt_mensaje').html('');
                                    guardar_item_recepcion();
                                    actualizar_grilla();
                            }
                    });

                }



        }

    function validar_cantidad()
        {
            ban = 0;
            cantidad = $("#input_cantidad").val();
            id_recepcion = $("#input_idrecepcion").val();
            id_orden_compra = $("#input_orden_compra").val();

            $.ajax({
                url: "index.php?r=sds_stk_recepcion_item/validar_cantidad&id_recepcion=" + id_recepcion + "&id_articulo=" + id_articulo + "&id_orden_compra=" + id_orden_compra + "&cantidad=" + cantidad, //php que recibe la peticion
                type: 'post', 
                async: false,
                success: function(response) { 
                    //console.log(response);
                    ban = 0;
                    if(response==1)
                        {ban = 1}
                    
                }
            });
            return ban;
        }


    function guardar_item_recepcion()
        {
            id_recepcion = $("#input_idrecepcion").val();
            id_articulo = $("#cmb_articulo").val();
            descripcion = $("#input_descripcion").val();
            cantidad = $("#input_cantidad").val();
            id_deposito = $("#cmb_deposito").val();
            id_orden_compra = $("#input_orden_compra").val();
            $("#abm_items").hide();
            $("#interv_form").show();
            $("#btnGuardar").show();
            $("#btnCerrar").show();
            

            $.ajax({
                url: "index.php?r=sds_stk_recepcion_item/create_ajax&id_recepcion=" + id_recepcion + "&id_articulo=" + id_articulo + "&descripcion=" + descripcion + "&cantidad=" + cantidad + "&id_deposito=" + id_deposito + "&id_orden_compra=" + id_orden_compra, //php que recibe la peticion
                type: 'post', 
                async: false,
                success: function(response) { 
                    console.log(response);

                }
            });

        }

    function actualizar_grilla()
        {
            id_recepcion = $("#input_idrecepcion").val();
            aux = "index.php?r=sds_stk_recepcion_item/grilla_items&idrecepcion=" + id_recepcion;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                limpiar_campos_recepcion_item();
            });
        }
    function cerrar_recepcion_item()
        {
            $("#abm_items").hide();
            $("#interv_form").show();
            $("#btnGuardar").show();
            $("#btnCerrar").show();
            limpiar_campos_recepcion_item();
        }
    function limpiar_campos_recepcion_item()
        {
            $("#cmb_articulo").val("").trigger("change");
            $("#input_descripcion").val("").trigger("change");
            $("#input_cantidad").val("").trigger("change");
            $("#cmb_deposito").val("").trigger("change");
            $('#txt_mensaje').html('');
        }

</script>

