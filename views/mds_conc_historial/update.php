<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_conc_historial */
?>
<div class="mds-conc-historial-update">

    <?= $this->render('_form', [
        'model' => $model,
        'estadosTipos' => $estadosTipos,
        'motivosImpugnacion' => $motivosImpugnacion,
        'motivosImpugnacionOptions' => $motivosImpugnacionOptions,
        'motivosImpugnacionString' => $motivosImpugnacionString
    ]) ?>

</div>