<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_interior */
?>
<div class="sds-800-atencion-interior-update">

    <?= $this->render('_form', [
        'model' => $model,
        'selectLocalidad' => $selectLocalidad,
        'selectNacionalidad' => $selectNacionalidad,
        'selectGenero' => $selectGenero,
        'selectParentesco' => $selectParentesco,
    ]) ?>

</div>