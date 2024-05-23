<?php
use app\models\View_stock_detalle;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_stk_recepcion;

$consulta  = "SELECT SUM(cantidad) as cantidad 
                FROM view_stock_detalle v WHERE v.idarticulo = $model->idarticulo 
                AND v.deposito = $model->deposito_egreso
                AND v.item_recepcion = $model->item_recepcion 
                AND v.fecha_hora < '$model->fecha_hora'";
$disponible = View_stock_detalle::findBySql($consulta)->one()->cantidad;
$recepcion = Sds_stk_recepcion_item::findOne($model->item_recepcion);
$fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));
$hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha_hora)));
?>

<script>
    id = $('#hidden_input_id').val();
    aux = "index.php?r=sds_stk_movimiento/grilla_items_view&id=" + id;
    $.post(aux, function(data) {$("#div_grilla").html(data);});
</script>

<style>
    .titulo_grilla{
        background-color: #EBFFCF!important;
    }

    .titulo {
        color: #08c;
    }
    .campo {
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 32px;
    }
</style>

<input type="hidden" id="hidden_input_id" name="hidden_input_id" value="<?=$model->idmovimiento?>">
<div class="sds-stk-movimiento-view">
    <div class="row">
        <div class="col-md-6">
            <h6 class="titulo"><b>Articulo: </b></h6>
            <p class="campo"><?= Sds_stk_articulo::findOne($model->idarticulo)->descripcion; ?></p>
        </div>
        <div class="col-md-6">
            <h6 class="titulo"><b>Deposito: </b></h6>
            <p class="campo"><?=Sds_stk_deposito::findOne($model->deposito_egreso)->descripcion;?></p>
        </div>
    </div>
    <div class="row">
      <div class="col-md-4">
            <h6 class="titulo"><b>Expediente: </b></h6>
            <p class="campo">
                <?=Sds_stk_recepcion::findOne($recepcion->idrecepcion)->expediente;?>
            </p>
        </div>
        <div class="col-md-2">
            <h6 class="titulo"><b>Fecha: </b></h6>
            <p class="campo"><?= "$fecha" ?></p>
        </div>
        <div class="col-md-2">
            <h6 class="titulo"><b>Hora: </b></h6>
            <p class="campo"><?= "$hora" ?></p>
        </div>
        <div class="col-md-2">
            <h6 class="titulo"><b>Disponible: </b></h6>
            <p class="campo"><?= "$disponible" ?></p>
        </div>
        <div class="col-md-2">
            <h6 class="titulo"><b>Cantidad: </b></h6>
            <p class="campo"><?= $model->cantidad ?></p>
        </div>
    </div>

    <div class="row" style="border-radius: 5px; padding: 15px;">
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div> 
    </div>
</div>