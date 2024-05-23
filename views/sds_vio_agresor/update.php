<?php

/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_agresor */
?>
<div class="sds-vio-agresor-update">

    <?= $this->render('_form', [
        'model' => $model,
        'parentezco' => $parentezco,
        'vioVinculoSelectOptions' => $vioVinculoSelectOptions,
        'vioConsumoSelectOptions' => $vioConsumoSelectOptions,
        'vioConsumosPreCargados' => $vioConsumosPreCargados,
        'escolaridad' => $escolaridad,
    ]) ?>

</div>