<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_cierre */

?>
<div class="sds-ent-cierre-create">
    <?= $this->render('_form', [
        'model_entrega' => $model_entrega,
        'models_cierres' => $models_cierres
    ]) ?>
</div>
