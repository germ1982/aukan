<?php

use app\models\Sds_com_persona;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_stk_recepcion;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_movimiento */
?>

<style>
    .campo {
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 32px;
    }
</style>

<div class="sds-stk-movimiento-view">
    <div class="row">
        <div class="col-md-2">
            <h6><b>Fecha: </b></h6>
            <p class="campo">
                <?php echo date_format(date_create($model->fecha_hora), 'd/m/Y') ?>
            </p>
        </div>
        <div class="col-md-4">
            <h6><b>Articulo: </b></h6>
            <p class="campo">
                <?= Sds_stk_articulo::findOne($model->idarticulo)->descripcion; ?>
            </p>
        </div>
        <div class="col-md-2">
            <h6><b>Cantidad: </b></h6>
            <p class="campo">
                <?= $model->cantidad; ?>
            </p>
        </div>
        <div class="col-md-4">
            <h6><b>Expediente: </b></h6>
            <p class="campo">
                <?php
                $recepcion = Sds_stk_recepcion_item::findOne($model->item_recepcion);
                if ($recepcion) {
                    echo Sds_stk_recepcion::findOne($recepcion->idrecepcion)->expediente;
                } else {
                    $entrega = Sds_stk_entrega_item::findOne($model->item_entrega);
                    if ($entrega) {
                        $recepcion = Sds_stk_recepcion_item::findOne($entrega->recepcion_item);
                        if ($recepcion) {
                            echo Sds_stk_recepcion::findOne($recepcion->idrecepcion)->expediente;
                        }
                    }
                }
                ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <h6><b>Tipo: </b></h6>
            <p class="campo">
                <?php
                switch ($model->tipo) {
                    case 0: {
                            echo "Stock Inicial";
                            break;
                        }
                    case 1: {
                            echo "Ingreso";
                            break;
                        }
                    case 2: {
                            echo "Reubicacion";
                            break;
                        }
                    case 3: {
                            echo "Egreso";
                            break;
                        }
                }
                ?>
            </p>
        </div>
        <div class="col-md-5">
            <h6><b>Deposito de Origen: </b></h6>
            <p class="campo">
                <?php
                $dep = Sds_stk_deposito::findOne($model->deposito_egreso);
                if ($dep) {
                    echo $dep->descripcion;
                } else {
                    echo '.';
                }
                ?>
            </p>
        </div>
        <div class="col-md-5">
            <h6><b><?= ($model->tipo != 3 ? "Depósito de Destino:" : "Entregado a:") ?></b></h6>
            <p class="campo">
                <?php
                if ($model->tipo != 3) {
                    $dep = Sds_stk_deposito::findOne($model->deposito_ingreso);
                    if ($dep) {
                        echo $dep->descripcion;
                    }
                } else {
                    $entrega_item = Sds_stk_entrega_item::findOne($model->item_entrega);
                    $entrega = Sds_stk_entrega::findOne($entrega_item->identrega);
                    if ($entrega) {
                        $persona = Sds_com_persona::findOne($entrega->idpersona);
                        echo "DNI: ".$persona->documento." - ".$persona->apellido.", ".$persona->nombre;
                    }
                }
                ?>
            </p>
        </div>
    </div>



</div>