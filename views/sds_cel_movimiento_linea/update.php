<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_movimiento_linea */
?>
<div class="sds-cel-movimiento-linea-update">

    <?= $this->render('_form', [
        'model' => $model,
        'contactos' => $contactos,
        'mensaje' => $mensaje
    ]) ?>

</div>
