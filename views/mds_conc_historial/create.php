<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_conc_historial */

?>
<div class="mds-conc-historial-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelPostulacion' => $modelPostulacion,
        'estadosTipos' => $estadosTipos,
        'motivosImpugnacion' => [],
        'motivosImpugnacionOptions' => $motivosImpugnacionOptions,
        'motivosImpugnacionString' => ''
    ]) ?>
</div>