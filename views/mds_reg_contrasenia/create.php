<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_reg_contrasenia */

?>
<div class="mds-reg-contrasenia-create">
    <?= $this->render('_form', [
        'model' => $model,
        'mensaje_success' => $mensaje_success,
        'mensaje_error'=> $mensaje_error,
    ]) ?>
</div>
