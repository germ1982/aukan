<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_instancia */

?>
<div class="mds-cap-instancia-create">
    <?= $this->render('_form', [
        'model' => $model,
        'filterCapacitaciones' => $filterCapacitaciones
    ]) ?>
</div>
