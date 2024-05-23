<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_registro */
?>
<div class="mds-hor-registro-update">

    <?= $this->render('_form_update', [
        'model' => $model,
        'empleados' => $empleados
    ]) ?>

</div>
