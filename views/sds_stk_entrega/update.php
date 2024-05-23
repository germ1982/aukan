<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega */
?>
<div class="sds-stk-entrega-update">

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'generada' => $generada,
    ]) ?>

</div>
