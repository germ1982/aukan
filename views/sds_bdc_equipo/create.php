<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_equipo */

if(isset($conflictIp)):?>
<div class="sds-bdc-equipo-create">
    <?= $this->render('_form', [
        'model' => $model,
        'conflictIp' => $conflictIp
    ]);?>
</div>
<?php else: ?>
<div class="sds-bdc-equipo-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<?php endif; ?>