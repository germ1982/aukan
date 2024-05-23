<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_certificacion */

?>
<div class="mds-hor-certificacion-create">
    <?= $this->render('_form', [
        'model' => $model,
        'contactos' => $contactos
    ]) ?>
</div>
