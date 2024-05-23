<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_familia */
?>
<div class="sds-800-atencion-familia-update">

    <?= $this->render('_form', [
        'model' => $model,
        'listLocalidades' => $listLocalidades,
        'listProvincias' => $listProvincias,
    ]) ?>

</div>