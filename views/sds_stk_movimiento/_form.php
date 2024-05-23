<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_deposito;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_recepcion;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_view_stock_articulo;
use kartik\date\DatePicker;

$user = Yii::$app->user->identity;
$idusuario = $user != null ? $user->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$organismo = $usuario->organismo_stock;
$iddeposito = $usuario->iddeposito > 0 ? $usuario->iddeposito:0;

echo Html::input('hidden', 'input_hidden_iddeposito', $iddeposito, $options = ['id' => 'input_hidden_iddeposito']);
?>

<div class="sds-stk-movimiento-form">
    <?php
    //Alerts Success y Error:
    if (Yii::$app->session->hasFlash('save_model')) : ?>
        <div class="alert alert-success alert-dismissable" id="alert-save">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
            <b><?= Yii::$app->session->getFlash('save_model') ?></b>
        </div>
    <?php endif;
    if (Yii::$app->session->hasFlash('fail_save')) : ?>
        <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fas fa-times"></i> ¡Por favor intente nuevamente!</h4>
            <b><?= Yii::$app->session->getFlash('fail_save') ?></b>
        </div>
    <?php endif;
    $form = ActiveForm::begin(); ?>
    <?php /*=Html::input('hidden', 'input_movimiento_nuevo', $model->isNewRecord?'1':'0', $options = ['id' => 'input_movimiento_nuevo']);?>
    <?=Html::input('hidden', 'input_aux_origen', $model->isNewRecord?'1':$model->deposito_egreso, $options = ['id' => 'input_aux_origen']);?>
    <?=Html::input('hidden', 'input_aux_id_recepcion_expediente', $model->isNewRecord?'1':Sds_stk_recepcion_item::findOne($model->item_recepcion)->idrecepcion, $options = ['id' => 'input_aux_id_recepcion_expediente']);?>
    <?=Html::input('hidden', 'input_aux_destino', $model->isNewRecord?'1':$model->deposito_ingreso, $options = ['id' => 'input_aux_destino']);?>
    */ ?>
    <div class="row">
        <div class="col-md-4">
            <?php
            /*
                if ($model->fecha_hora != null){
                    $ban = 1;
                    $fecha = $model->fecha_hora;
                    $model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));
                }else{
                    $ban =0;
                    $model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
                } 
                */

            echo $form->field($model, 'fecha_hora')->widget(DatePicker::class, [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'disabled' => false,
                'options' => [
                    'id' => 'input_fecha_hora',
                    'class' => 'form-control input-md',
                    'placeholder' => 'DD/MM/YYYY',
                ],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true
                ]
            ])->label('Fecha'); ?>
        </div>

        <div class="col-md-8">
            <?php
            //fijarse de cambier el id del organismo


            if ($organismo == null) {
                $organismo = 0;
            }

            $where_deposito = $usuario->iddeposito > 0 ? " and deposito = $usuario->iddeposito":'';
            $consulta_disponible = "(SELECT ifnull(sum(cantidad),0) as disponible
                                    FROM view_stock_detalle d 
                                    WHERE d.idarticulo = A.idarticulo $where_deposito
                                    group by d.idarticulo)";

            $consulta = "SELECT 
                        A.idarticulo as idarticulo, 
                        CONCAT(A.descripcion,' (en ',C.descripcion,')') as descripcion,
                        $consulta_disponible as disponible
                        FROM sds_stk_movimiento M
                        INNER JOIN sds_stk_recepcion_item RI on RI.idrecepcionitem = M.item_recepcion
                        INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion
                        INNER JOIN sds_stk_articulo A on A.idarticulo = RI.idarticulo
                        INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
                        WHERE R.organismo = $organismo and $consulta_disponible > 0
                        group by A.idarticulo, A.descripcion";
            echo $form->field($model, 'idarticulo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_stk_articulo::findBySql($consulta)->all(),
                    'idarticulo',
                    'descripcion'
                ),
                'options' => ['id' => 'cmb_articulo', 'placeholder' => 'Seleccionar Articulo ...', /*'onchange' => 'setear_depositos();'*/],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" id="div_depositos">
            <?= $form->field($model, 'deposito_egreso')->widget(Select2::class, [
                'data' => "",
                'options' => [
                    'prompt' => '',
                    'placeholder' => 'Seleccionar deposito ...',
                    'id' => 'cmb_deposito_origen',
                    //'onchange' => 'setear_expedientes();setear_deposito_destino();'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Deposito de Origen');
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'deposito_ingreso')->widget(Select2::class, [
                'data' => '',
                'options' => ['id' => 'cmb_deposito_destino', 'placeholder' => 'Seleccionar deposito de destino ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Deposito de Destino');
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6" id="div_expedientes">
            <?= $form->field($model, 'item_recepcion')->widget(Select2::class, [
                'data' => "",
                'options' => [
                    'prompt' => '',
                    'placeholder' => 'Seleccionar expediente ...',
                    'id' => 'cmb_item_recepcion'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'disponible')->textInput(['id' => 'input_disponible', 'readOnly' => true]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'cantidad')->textInput(['id' => 'input_cantidad']); ?>
        </div>
    </div>

    <!--
    <div class="row">
        <div class="col-md-12" style="padding-top:30px;color:red;" id="txt_mensaje"></div>
    </div>
    -->

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('@web/js/stock.js'); ?>
<script>
    if ($('#cmb_deposito_origen').val() != '') {
        setear_depositos();
        setear_deposito_destino();
    }
    if ($('#cmb_item_recepcion').val() != '') {
        setear_expedientes();
    }

/*     $('#cmb_articulo').change(function() {
        setear_depositos();
    }); */

    $('#cmb_articulo').change(function() {
        let iddeposito = $("#input_hidden_iddeposito").val();
        if(iddeposito > 0)
        {setear_deposito_solo(iddeposito);}
        else
        {setear_depositos();}
    });

    $('#cmb_deposito_origen').change(function() {
        setear_expedientes();
        setear_deposito_destino();
    });

    $('#cmb_item_recepcion').change(function() {
        setear_disponible();
    });

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
    verificar_nuevo();
    function verificar_nuevo(){
        movimiento = $("#input_movimiento_nuevo").val();
        if(movimiento == 0){
            input_aux = $('#input_aux_origen').val();
            setear_depositos(input_aux);
        }
    }
    *

    function guardar_movimiento(){
        id_articulo = $("#cmb_articulo").val();
        id_deposito_origen = $("#cmb_deposito_origen").val();
        id_recepcion_expediente = $("#cmb_item_recepcion").val();
        disponible = $("#input_disponible").val();
        fecha_hora = $("#input_fecha_hora").val();
        cantidad = $("#input_cantidad").val();
        id_deposito_destino = $("#cmb_deposito_destino").val();
        $.ajax({
            url: "index.php?r=sds_stk_movimiento/save_movimiento_tipo_2&cantidad=" +  cantidad + "&deposito_destino=" + id_deposito_destino + "&deposito_origen=" + id_deposito_origen + "&id_articulo=" + id_articulo + "&fecha_hora=" + fecha_hora + "&id_recepcion=" + id_recepcion_expediente, //php que recibe la peticion
            type: 'post', 
            async: false,
            success: function(response){
            }
        });
    }

    function validar_datos_movimiento(){
        id_articulo = $("#cmb_articulo").val();
        id_deposito_origen = $("#cmb_deposito_origen").val();
        id_recepcion_expediente = $("#cmb_item_recepcion").val();
        disponible = $("#input_disponible").val();
        fecha_hora = $("#input_fecha_hora").val();
        cantidad = $("#input_cantidad").val();
        id_deposito_destino = $("#cmb_deposito_destino").val();
        //alert('id_articulo: ' + id_articulo + '\nid_deposito_origen: ' + id_deposito_origen + '\nid_recepcion_expediente: ' + id_recepcion_expediente + '\ndisponible: ' + disponible  + '\nfecha_hora: ' + fecha_hora + '\ncantidad: ' + cantidad + '\nid_deposito_destino: ' + id_deposito_destino);
        ban=0;
        aux = 'Error:';
        if(!(id_articulo>0))
        {
            aux = aux + ' /// Falta el articulo';
            ban=1;
        }
        if(!(id_deposito_origen>0))
        {
            aux = aux + ' /// Falta el deposito de origen';
            ban=1;
        }
        if(!(id_recepcion_expediente>0))
        {
            aux = aux + ' /// Falta el expediente';
            ban=1;
        }
        if(fecha_hora=='')
        {
            aux = aux + ' /// Falta la fecha';
            ban=1;
        }
        if(cantidad=='')
        {
            aux = aux + ' /// Falta la cantidad';
            ban=1;
        }
        //alert(cantidad + '>' + disponible);
        if(parseInt(cantidad,10) > parseInt(disponible,10))
        {
            aux = aux + ' /// La cantidad no debe superar al disponible';
            ban=1;
        }
        if(!(id_deposito_destino>0))
        {
            aux = aux + ' /// Falta el deposito de destino';
            ban=1;
        }
        if(ban==1)
        {
            $('#txt_mensaje').html(aux);
        }
        else
        {
            guardar_movimiento();
        }
    }

    /*
    function setear_depositos(dato_update=null){
        id_articulo = $('#cmb_articulo').val();
        aux = "index.php?r=sds_stk_entrega_item/get_combo_deposito&id_articulo=" + id_articulo;
        $.post(aux, function(data){
            $('#cmb_deposito_origen').html(data).trigger('change');
            if(dato_update!=null){
                $('#cmb_deposito_origen').val(dato_update);
                input_aux = $('#input_aux_id_recepcion_expediente').val();
                setear_expedientes(input_aux);
                input_aux = $('#input_aux_destino').val();
                setear_deposito_destino(input_aux);
            }
        });
    }

    function setear_expedientes(dato_update=null) {
        id_articulo = $('#cmb_articulo').val();
        id_deposito = $('#cmb_deposito_origen').val();
        $('#input_disponible').val('');
        aux = "index.php?r=sds_stk_entrega_item/get_combo_expediente&id_articulo=" + id_articulo + "&id_deposito=" + id_deposito;
        $.post(aux, function(data) {
            $('#cmb_item_recepcion').html(data);
            $('#cmb_item_recepcion').val(null);
            if(dato_update!=null){
                $('#cmb_item_recepcion').val(dato_update);
            }
        });
    }
    *

    function setear_deposito_destino(dato_update=null){
        id_deposito_origen = $('#cmb_deposito_origen').val();
        aux = "index.php?r=sds_stk_movimiento/get_combo_deposito_destino&id_deposito_origen=" + id_deposito_origen;
        $.post(aux, function(data) {
            $('#cmb_deposito_destino').html(data);
            $('#cmb_deposito_destino').val(null);
            if(dato_update!=null){
                $('#cmb_deposito_destino').val(dato_update);
            }
        });
    }

    // function setear_disponible(){
    //     id_articulo = $('#cmb_articulo').val();
    //     id_deposito = $('#cmb_deposito_origen').val();
    //     id_recepcion_expediente = $('#cmb_item_recepcion').val();
    //     aux = "index.php?r=sds_stk_entrega_item/get_disponibilidad_item&id_articulo=" + id_articulo + "&id_deposito=" + id_deposito + "&id_recepcion_expediente=" + id_recepcion_expediente;
    //     $.post(aux, function(data) {
    //         $('#input_disponible').val(data);
    //     });
    // }

    function setear_disponible(){
        id_articulo = $('#cmb_articulo').val();
        id_deposito = $('#cmb_deposito').val();
        id_recepcion_expediente = $('#cmb_item_recepcion').val();
        //aux = "index.php?r=sds_stk_entrega_item/get_disponibilidad_item&id_articulo=" + id_articulo + "&id_deposito=" + id_deposito + "&id_recepcion_expediente=" + id_recepcion_expediente;
        aux = "index.php?r=sds_view_stock_recepcion_item/get_stock&id_articulo="+id_articulo+"&item_recepcion="+id_recepcion_expediente;
        $.post(aux, function(data){
            $('#input_disponible').val(data.cantidad);
        });
    }
    */
</script>