<?php

use yii\widgets\DetailView;
$model->fecha_ingreso = date_format(date_create($model->fecha_ingreso), 'd/m/Y');
$model->fecha_salida = date_format(date_create($model->fecha_salida), 'd/m/Y');
?>
<div class="mds-org-expediente-view">
    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget(['model' => $model,'attributes' => ['idexpediente','fecha_ingreso','expediente'],]) ?>
        </div>
        <div class="col-md-6">
            <?= DetailView::widget(['model' => $model,'attributes' => ['pedido_numero','gde','causante'],]) ?>
        </div>
    </div>

    <div class="row">
            <div class="col-md-12">
                <?= DetailView::widget(['model' => $model,'attributes' => ['extracto:ntext',],]) ?>
            </div>
    </div>
    <div class="row">
            <div class="col-md-3">
                <?= DetailView::widget(['model' => $model,'attributes' => ['fecha_salida',],]) ?>
            </div>
            <div class="col-md-9">
                <?= DetailView::widget(['model' => $model,'attributes' => ['destino',],]) ?>
            </div>
    </div>
 


</div>
