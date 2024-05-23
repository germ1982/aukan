<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_visita */

?>
<div class="sds-bdc-visita-create">
    <?= $this->render('_form', [
        'model' => $model,
        'sectores' => $sectores
    ]) ?>
</div>
