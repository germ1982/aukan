<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_linea */

?>
<div class="sds-cel-linea-create">
    <?= $this->render('_form', [
        'model' => $model,
        'mensaje_error' => (isset($mensaje_error)?$mensaje_error:null),
        'mensaje_success' => (isset($mensaje_success)?$mensaje_success:null),
    ]) ?>
</div>
