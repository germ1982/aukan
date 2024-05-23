<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_registro */

?>
<div class="mds-hor-registro-create">
    <?= $this->render('_form', [
        'model' => $model,
        'model_franco' => $model_franco,
        'guardados' => $guardados,
        'errores' => $errores,
        'empleados' => $empleados,
        'tipos_franco' => $tipos_franco,
        'hasFichadas' => $hasFichadas
    ]) ?>
</div>
