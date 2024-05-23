<?php

use yii\helpers\Html;
?>

<div class="sds-vio-intervencion-movimiento-create">
    <?= $this->render('_form', [
        'model' => $model,
        'tipo_movimiento' => $tipo_movimiento,
    ]) ?>
</div>