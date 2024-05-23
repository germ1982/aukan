<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_articulo_conversion */
?>
<div class="sds-stk-articulo-conversion-update">

    <?= $this->render('_form', [
        'model' => $model,
        'filter' => $filter,
        'botones' => isset($botones)?true:false
    
    ]) ?>

</div>
