<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_registro */

?>
<div class="sds-reg-registro-create">
    <?= 
    $this->render('_form', [
        'model' => $model,
        'entidad' => $entidad
    ])  ?>
</div>
