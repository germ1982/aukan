<?php

use yii\helpers\Html;
?>

<div class="sds-vio-intervencion-movimiento-update">
    <?= $this->render('_form', [
        'model' => $model,
        'tipo_movimiento' => $tipo_movimiento,
    ]) ?>
</div>