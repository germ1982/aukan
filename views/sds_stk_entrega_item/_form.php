<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_stk_articulo;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_deposito;
$usuario = Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario);
$organismo = Yii::$app->user->identity->organismo_stock != null ? Yii::$app->user->identity->organismo_stock : 0;
$iddeposito = $usuario->iddeposito > 0 ? $usuario->iddeposito:0;
echo Html::input('hidden', 'input_hidden_iddeposito', $iddeposito, $options = ['id' => 'input_hidden_iddeposito']);

?>

<div class="sds-stk-entrega-item-form">
    <?php $form = ActiveForm::begin([
        'action' => [
            'sds_stk_entrega_item/' .
                ($model->isNewRecord ? 'create_ext' : 'update'),
            'id' => $model->identregaitem,
        ],
        'id' => $model->formName(),
    ]); ?>
    <div class="row">
        <div style="display:none">
            <?php
            if (isset($identrega)) {
                $model->identrega = $identrega;
            }
            echo $form
                ->field($model, 'identrega')
                ->textInput(['id' => 'input_identrega'])
                ->label('ID Entrega');
            ?>
        </div>
    </div>

    <div class="row" id="div_txt">
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php

            $where_deposito = $usuario->iddeposito > 0 ? " and deposito = $usuario->iddeposito":'';



            /* $consulta_cantidad_tipo1 = "SELECT ifnull(SUM(Mo.cantidad),0)
                                            FROM sds_stk_movimiento Mo
                                            WHERE Mo.tipo = 1 
                                            and Mo.idarticulo = A.idarticulo";

                $consulta_cantidad_tipo3 = "SELECT ifnull(SUM(Mo.cantidad),0)
                                            FROM sds_stk_movimiento Mo
                                            WHERE Mo.tipo = 3 and Mo.idarticulo = A.idarticulo";

                if ($usuario->iddeposito) {
                    $consulta_cantidad_tipo1 = "$consulta_cantidad_tipo1 and Mo.deposito_ingreso = $usuario->iddeposito";
                    $consulta_cantidad_tipo3 = "$consulta_cantidad_tipo3 and Mo.deposito_egreso = $usuario->iddeposito";
                } */
            //$consulta_disponible = "($consulta_cantidad_tipo1)-($consulta_cantidad_tipo3)";
            $consulta_disponible = "(SELECT ifnull(sum(cantidad),0) as disponible
                FROM view_stock_detalle d 
                WHERE d.idarticulo = A.idarticulo $where_deposito
                group by d.idarticulo)";
            $consulta = "SELECT A.idarticulo as idarticulo, 
                                A.descripcion as descripcion,
                                $consulta_disponible as disponible
                            FROM sds_stk_movimiento M
                            INNER JOIN sds_stk_recepcion_item RI on RI.idrecepcionitem = M.item_recepcion
                            INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion
                            INNER JOIN sds_stk_articulo A on A.idarticulo = RI.idarticulo
                            INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
                            WHERE R.organismo = $organismo and $consulta_disponible > 0
                            group by A.idarticulo, A.descripcion";
            echo $form
                ->field($model, 'articulo')
                ->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Sds_stk_articulo::findBySql($consulta)->all(),
                        'idarticulo',
                        'descripcion'
                    ),
                    'options' => [
                        'id' => 'cmb_articulo',
                        'placeholder' =>
                        'Seleccionar Articulo ...' /*'onchange' => 'setear_depositos();'*/,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
                ->label('Articulo');
            ?>
        </div>
        <div class="col-md-6" id="div_depositos">
            <?= $form
                ->field($model, 'deposito')
                ->widget(Select2::classname(), [
                    'data' => '',
                    'options' => [
                        'prompt' => '',
                        'placeholder' => 'Seleccionar deposito ...',
                        'id' =>
                        'cmb_deposito_origen' /*'onchange' => 'setear_expedientes();'*/,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
                ->label('Deposito') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8" id="div_expedientes">
            <?= $form
                ->field($model, 'expediente')
                ->widget(Select2::classname(), [
                    'data' => '',
                    'options' => [
                        'prompt' => '',
                        'placeholder' => 'Seleccionar expediente ...',
                        'id' =>
                        'cmb_item_recepcion' /*,'onchange' => 'setear_disponible();'*/,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'format' => 'html',
                    ],
                ])
                ->label('Expediente') ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'disponible')
                ->textInput([
                    'id' => 'input_disponible',
                    'readOnly' => true,
                ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form
                ->field($model, 'cantidad')
                ->textInput(['id' => 'input_cantidad']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
    </div>

    <?php if (isset($botones)) { ?>
        <br>
        <div class="form-group">
            <?= Html::button(
                $model->isNewRecord ? 'Guardar Articulo' : 'update',
                [
                    'id' => 'btnGuardarInterno',
                    'class' => $model->isNewRecord
                        ? 'btn btn-success'
                        : 'btn btn-primary',
                    'onclick' => 'validar_datos_item_entrega();',
                ]
            ) ?>
            <?= Html::button('Cerrar Articulo', [
                'id' => 'btnCerrarInterno',
                'class' => 'btn btn-default',
                'onclick' => '$("#abm_items").hide();
                    $("#id_formulario_entrega").show();
                    $("#btnGuardar").show();
                    $("#btnCerrar").show();
                    limpiar_campos();',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<script>
    /*
    if($('#cmb_deposito_origen').val()!=''){
        //setear_depositos();
        //setear_deposito_destino();
    }
    if($('#cmb_item_recepcion').val()!=''){
        //setear_expedientes();
    }
    */

    $('#cmb_articulo').change(function() {
        let iddeposito = $("#input_hidden_iddeposito").val();
        if(iddeposito > 0)
        {setear_deposito_solo(iddeposito);}
        else
        {setear_depositos();}
        
        
    });

    $('#cmb_deposito_origen').change(function() {
        
        setear_expedientes();
        //setear_deposito_destino();
    });

    $('#cmb_item_recepcion').change(function() {
        setear_disponible();
    });
</script>




<?php
$script = <<<JS

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
            $('#id_formulario_entrega').show(); 
            e.preventDefault();
            var aux = $("#input_identrega").val();
            aux = "index.php?r=sds_stk_entrega_item/grilla_items&identrega=" + aux;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
                $("#btnGuardar").show();
                $("#btnCerrar").show();
                $("#btnGuardarInterno").show();
                $("#btnCerrarInterno").show();
                limpiar_campos();
            });            
        }else{
            $("#message").html(result);
        }
    }).fail(function(){});
   
    return false;
});

   

JS;
$this->registerJs($script);
?>

<script>
    function actualizar_grilla() {
        aux = "index.php?r=sds_stk_entrega_item/grilla_items&identrega=" + $("#input_identrega").val();
        $.post(aux, function(data) {
            $("#div_grilla").html(data);
        });
    }


    function validar_datos_item_entrega() {
        id_entrega = $("#input_identrega").val();
        id_articulo = $("#cmb_articulo").val();
        id_deposito = $("#cmb_deposito_origen").val();
        id_recepcion_expediente = $("#cmb_item_recepcion").val();
        disponible = $("#input_disponible").val();
        cantidad = $("#input_cantidad").val();

        //alert('id_entrega: ' + id_entrega + '\nid_articulo: ' + id_articulo + '\nid_deposito: ' + id_deposito + '\nid_recepcion_expediente: ' + id_recepcion_expediente + '\ndisponible: ' + disponible + '\ncantidad: ' + cantidad);
        ban = 0;
        aux = 'Error:';

        if (!(id_entrega > 0)) {
            aux = aux + ' /// Falta el id de entrega';
            ban = 1;
        }

        if (!(id_articulo > 0)) {
            aux = aux + ' /// Falta el articulo';
            ban = 1;
        }

        if (!(id_deposito > 0)) {
            aux = aux + ' /// Falta el deposito';
            ban = 1;
        }


        if (!(id_recepcion_expediente > 0)) {
            aux = aux + ' /// Falta el expediente';
            ban = 1;
        }

        if (cantidad == '') {
            aux = aux + ' /// Falta la cantidad';
            ban = 1;
        }

        if (parseInt(cantidad, 10) > parseInt(disponible, 10)) {
            aux = aux + ' /// La cantidad no debe superar al disponible';
            ban = 1;
        }

        if (ban == 1) {
            $('#txt_mensaje').html(aux);
        } else {
            $.post("index.php?r=sds_stk_entrega_item/validar_item_existente&id_entrega=" + id_entrega + "&id_articulo=" + id_articulo + "&id_recepcion_expediente=" + id_recepcion_expediente, function(data) {

                if (data != 0) {
                    aux = 'El articulo seleccionado ya existe en la entrega numero ' + id_entrega;
                    $('#txt_mensaje').html(aux);
                } else {
                    guardar_item_entrega();
                    anunciar_minimo();
                    limpiar_campos();
                    actualizar_grilla();
                }
            });

        }
    }

    function anunciar_minimo() {

        let minimo = get_minimo();
        let disponible = get_disponible();
        if (disponible <= minimo) {
            alert('Atencion!! El stock disponible esta por debajo del valor minimo')
        }
    }

    function get_disponible() {
        id_articulo = $("#cmb_articulo").val();
        let aux;
        $.ajax({
            url: "index.php?r=sds_stk_articulo/get_stock_disponible&idarticulo=" + id_articulo, //php que recibe la peticion
            type: 'post',
            async: false,
            success: function(response) {
                /*
                console.log('articulo disponible');
                console.log(response);
                aux = response;
                */
            }
        });
        return aux;
    }

    function get_minimo() {
        let aux;
        id_articulo = $("#cmb_articulo").val();
        $.ajax({
            url: "index.php?r=sds_stk_articulo/get_stock_minimo&idarticulo=" + id_articulo, //php que recibe la peticion
            type: 'post',
            async: false,
            success: function(response) {
                /*
                    console.log('articulo minimo');
                    console.log(response);
                aux = response;*/
            }
        });
        return aux;
    }

    function guardar_item_entrega() {
        id_entrega = $("#input_identrega").val();
        id_articulo = $("#cmb_articulo").val();
        id_deposito = $("#cmb_deposito_origen").val();
        id_recepcion_expediente = $("#cmb_item_recepcion").val();
        cantidad = $("#input_cantidad").val();
        disponible = $("#input_disponible").val();

        $("#abm_items").hide();
        $("#id_formulario_entrega").show();
        $("#btnGuardar").show();
        $("#btnCerrar").show();

        $.ajax({
            url: "index.php?r=sds_stk_entrega_item/create_ajax&id_entrega=" + id_entrega + "&id_articulo=" + id_articulo + "&cantidad=" + cantidad + "&id_recepcion_item=" + id_recepcion_expediente + "&id_deposito=" + id_deposito + "&disponible=" + disponible, //php que recibe la peticion
            type: 'post',
            async: false,
            success: function(response) {
                //console.log(response);

            }
        });

    }

    function setear_deposito_solo(iddeposito) {
        aux = "index.php?r=sds_view_stock_detalle/get_deposito_solo&iddeposito=" + iddeposito;
        $.post(aux, function(data) {
            data = data.sort(SortDeposito);
            var option = '';
            data.forEach(function(deposito) {
                option += '<option value="' + deposito.iddeposito + '">' + deposito.descripcion + '</option>'
            });
            $('#cmb_deposito_origen').html(option).trigger('change');
        });
    }

    /*
    function setear_depositos() {
        $("#loading").show();
        id_articulo = $('#cmb_articulo').val();
        aux = "index.php?r=sds_stk_entrega_item/get_combo_deposito&id_articulo=" + id_articulo;
        $.post(aux, function(data) {
            console.log('depositos:');
            console.log(data);
            //alert(data);
            
            //$('#div_txt').html(data);
            $('#cmb_deposito_origen').html(data);
            $("#loading").hide();
        }); 
    }

    function setear_expedientes() {
        $("#loading").show();
        id_articulo = $('#cmb_articulo').val();
        id_deposito = $('#cmb_deposito_origen').val();
        aux = "index.php?r=sds_stk_entrega_item/get_combo_expediente&id_articulo=" + id_articulo + "&id_deposito=" + id_deposito;
        $.post(aux, function(data) {
            console.log('expedientes:');
            console.log(data);
            
            //$('#div_txt').html(data);
            $('#cmb_item_recepcion').html(data);
            $("#loading").hide();
        }); 
    }

    function setear_disponible() {
        id_articulo = $('#cmb_articulo').val();
        id_deposito = $('#cmb_deposito_origen').val();
        id_recepcion_expediente = $('#cmb_item_recepcion').val();
        aux = "index.php?r=sds_stk_entrega_item/get_disponibilidad_item&id_articulo=" + id_articulo + "&id_deposito=" + id_deposito + "&id_recepcion_expediente=" + id_recepcion_expediente;
        $.post(aux, function(data) {
            console.log('Disponible:');
            console.log(data);
            $('#input_disponible').val(data);
        }); 
    }
    */

    function limpiar_campos() {
        aux = "index.php?r=sds_stk_entrega_item/get_combo_articulo";
        $.post(aux, function(data) {
            $('#cmb_articulo').html(data);
            $('#cmb_deposito_origen').html(null);
            $('#cmb_item_recepcion').html(null);
            $('#input_disponible').val(null);
            $('#input_cantidad').val(null);
            $('#txt_mensaje').html('');
        });
    }
</script>