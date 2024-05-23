<?php

use app\models\Sds_com_configuracion;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_pedido */
?>
<div class="sds-reg-pedido-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idpedido',
            'numero',
            'expediente',
            [
                'attribute' => 'estado',
                'value' => function ($model) {
                    $idconfiguracion = $model->estado;
                    if ($idconfiguracion != null) {
                        $estado = Sds_com_configuracion::findOne($idconfiguracion);
                        return $estado->descripcion;
                    }
                    return "";
                }
            ],
        ],
    ]) ?>

</div>