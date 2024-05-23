<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_entrega */
?>
<div class="sds-ent-entrega-update">

    <?= $this->render('_form' . (isset($interm) ? '_interm' : ''), [
        'model' => $model,
    ]) ?>
</div>
</div>