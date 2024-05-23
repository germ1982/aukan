<?php

/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_agresor */

?>
<div class="sds-vio-agresor-create">
    <?= $this->render('_form', [
        'model' => $model,
        'parentezco' => '',
        'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
        'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
        'escolaridad' => $escolaridad,
    ]) ?>
</div>