<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion */

?>
<div class="sds-com-configuracion-create">
    <?= $this->render('_form', [
        'model' => $model,
        'botones' => isset($botones)? $botones:false,
    ]) ?>
</div>
