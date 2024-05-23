<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_inventario */
?>
<div class="sds-stk-inventario-update">

    <?= $this->render('_form', [
        'model' => $model,
        'model_inventario_items' => $model_inventario_items,
    ]) ?>

</div>
